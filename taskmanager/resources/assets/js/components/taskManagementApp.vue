<template>
<div>
	<new-task-modal :users="users"></new-task-modal>
    <edit-task-modal id="editTaskModal" :task="taskToEdit" :users="users"></edit-task-modal>
    <div :style="container">
        <div id="taskManagementApp" class="row">
            <task-column v-for="category in categories" :name.sync="category" :col-names.sync="categories" :tasks.sync="tasks" :users="users" :current-user="currentUser"></task-column>
        </div>
    </div>
</div>
</template>

<script>
var io = require('socket.io-client');
var socket = io('http://192.168.33.10:3000');
import NewTaskModal from './newTaskModal.vue';
import TaskColumn from './taskColumn.vue';
import EditTaskModal from './editTaskModal.vue';
export default {
    data () {
        return {
            serverAddress: '192.168.33.10',
            categories: [
                "To Do", "In Progress", "Completed", "Too Hard Basket"
            ],
        	tasks: {
        		
        	},
            users: {
                u1: {id: "u1", displayName:"John"},
                u2: {id: "u2", displayName:"Sarah"},
                
            },
            currentUser: "",
            taskToEdit: ""
        }
    },
    computed:{
        container: function(){
            if (this.categories.length <= 4){
                return "";
            }
            var percentage = this.categories.length * 25;
            return "@media (min-width: 992px) {width:" + percentage + "%;}";
        }
    },
    components: {
    	newTaskModal: NewTaskModal,
        taskColumn: TaskColumn,
        editTaskModal: EditTaskModal
    },
    methods: {
        keyGenerator: function(){
            var identifierLength = 8;
            var identifier = "";
            var possible = "0123456789";
            for(var i = 0; i < identifierLength; i++) {
                identifier += possible.charAt(Math.floor(Math.random() * possible.length));
            }
            return 'TEMPORARY' + identifier;
        },
        processIncomingTasksData : function(tasks){
            for (var id in tasks){

                //convert duedate to Date object
                if (tasks[id].dueDate){
                    tasks[id].dueDate = new Date(tasks[id].dueDate);
                }

                // convert logged times to Date object
                if (tasks[id].loggedTimeHistory.constructor === Array){
                    for (var i=0; i<tasks[id].loggedTimeHistory.length; i++){
                        var log = tasks[id].loggedTimeHistory[i];
                        log.task = 't' + log.task;
                        log.startDateTime = new Date(log.start_date_time);
                        delete log.start_date_time;
                        log.timeLogged = log.time_logged;
                        delete log.time_logged;
                        log.user = 'u' + log.user_id;
                        delete log.user_id;
                    }
                }

                //add calculated fields
                tasks[id].timeLogged = "";
            }
            return tasks;
        },
        getCookie : function(cname) {
            var name = cname + "=";
            var ca = document.cookie.split(';');

            for(var i = 0; i <ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0)==' ') {
                    c = c.substring(1);
                }
                if (c.indexOf(name) == 0) {
                    return c.substring(name.length,c.length);
                }
            }
            return "";
        },
        sendToServer : function(dataPackage){
            this.$http.post("/taskdata", dataPackage, {'headers': {'Content-Type': 'application/json'}}).then((response) =>{
                //success
            }, (response) => {
                console.log("Failed to send data to server");
            });
        }
    },
    events: {
        'newTask': function(newTask){
            var tempID = this.keyGenerator();
            newTask.status = 0;
            newTask.priority = 0;
            for (var id in this.tasks){
                if (this.tasks[id].status === 0){
                    newTask.priority++;
                }
            }
            this.tasks[tempID] = newTask;
            this.tasks = Object.assign({}, this.tasks );

            //send task to server
            var dataPackage = {'updateType':'newTask','newtask': newTask, 'tempID': tempID};
            console.log(dataPackage);
            this.$http.post("/taskdata", dataPackage, {'headers': {'Content-Type': 'application/json'}}).then((response) =>{
                var jsonResponse = response.json();

                //change subtask ids to reflect those in database
                var subtasks = this.tasks[jsonResponse.tempID].subtasks;
                for (var tempid in jsonResponse.subtaskIds){
                    for (var i = 0; i<subtasks.length; i++ ){
                        if(subtasks[i].tempID === tempid){
                            subtasks[i].id = jsonResponse.subtaskIds[tempid];
                            delete subtasks[i].tempID;
                            continue;
                        }
                    }
                }

                //change task id to reflect database
                this.tasks[jsonResponse.newID] = this.tasks[jsonResponse.tempID];
                delete this.tasks[jsonResponse.tempID];

                
            }, (response) => {
                console.log("Failed to send data to server");
            });
        },
        'editTask': function(taskId){
            this.taskToEdit = {'id': taskId, 'data': jQuery.extend({}, this.tasks[taskId])};
            if ( ! Array.isArray(this.taskToEdit.data.subtasks)){
                this.taskToEdit.data.subtasks = new Array();
            }
            jQuery('#editTaskModal').modal('show');
        },
        'saveTask' : function(task){
            this.tasks[task.id] = Object.assign({}, task.data);
            this.taskToEdit = {};
        },
        'prioritiesUpdated': function(priorities){
            this.sendToServer( {'updateType':'changeTaskPrioritys','priorities': priorities} );
            
        },
        'changeTaskStatus': function(statusData){
            this.sendToServer( {'updateType':'changeTaskStatus','statusData': statusData} );
        },
        'completeSubtask': function(data){
            this.sendToServer({"updateType": "completeSubtask", "data": data});
        },
        'logTime': function(data){
            console.log(data);
            this.sendToServer({"updateType": "logTime", "logData": data});
        }
    },
    ready: function(){

        //load project from server
        this.$http.get('/taskdata').then((response) => {
            var tasks = this.processIncomingTasksData(response.json().tasks);
            this.tasks = Object.assign({}, this.tasks, tasks);
            this.users = (response.json().users);
            this.currentUser = (response.json().currentUser);
        }, (response) =>{
            console.log("Error loading tasks from server!");
        });

        var vm = this;
        console.log(vm.getCookie('socketKey'));
        //setup websocket for future incoming data
        socket.on("connect", function(){
            
            socket.emit('authenticate', vm.getCookie('socketKey'));
            console.log("Connection to server established");
        });
        socket.on("disconnect", function(){
            console.log("Disconnected from server");
        });
        socket.on('userOnline', function(userID){
            console.log("User " + userID + " is online.");
        });
        socket.on('newTask', function(sentStuff){
            if (sentStuff.updatedBy !== vm.currentUser){
                console.log(sentStuff.message);
                var task = {};
                task[sentStuff.data.id] = sentStuff.data.newtask;
                vm.tasks = Object.assign({}, vm.tasks, task);
            }
        });
        socket.on('changeTaskStatus', function(sentStuff){
            // if (sentStuff.updatedBy !== vm.currentUser){
                vm.tasks[sentStuff.data.statusData.taskid].status = sentStuff.data.statusData.newStatus;
            // }
        });
        socket.on('changeTaskPrioritys', function(sentStuff){
            // if (sentStuff.updatedBy !== vm.currentUser){
                for (var i = 0; i<sentStuff.data.priorities.length; i++){
                    var taskID = sentStuff.data.priorities[i];
                    vm.tasks[taskID].priority = i;
                }                
            // }
        });
        socket.on("completeSubtask", function(sentStuff){
            // if (sentStuff.updatedBy !== vm.currentUser){
                console.log(sentStuff.message);
                var subtasks = vm.tasks["t" + sentStuff.data.data.taskId].subtasks;
                for (var i = subtasks.length - 1; i >= 0; i--) {
                    if (subtasks[i].id === sentStuff.data.data.subtaskId){
                        subtasks[i].complete = sentStuff.data.data.complete;
                        break;
                    }
                }
            // }
        });
        socket.on("logTime", function(sentStuff){
            // if (sentStuff.updatedBy !== vm.currentUser){
                console.log(sentStuff.message);
                var log = sentStuff.data.logData.log;
                log.startDateTime = new Date(log.startDateTime);
                log.user = log.user.id;
                var history = vm.tasks[sentStuff.data.logData.taskId].loggedTimeHistory;
                if (Array.isArray(history)){
                    vm.tasks[sentStuff.data.logData.taskId].loggedTimeHistory.push(log);
                } else {
                    vm.tasks[sentStuff.data.logData.taskId].loggedTimeHistory = [ log ];
                }
            // }
        });


    },
}
</script>