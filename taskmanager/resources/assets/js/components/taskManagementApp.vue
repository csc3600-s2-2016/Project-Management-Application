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
            currentUser: "u5",
            taskToEdit: ''
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
            this.$http.post("/taskdata", dataPackage, {'headers': {'Content-Type': 'application/json'}}).then((response) =>{
                console.log("Data sent to server");
                this.tasks[response.json().newID] = this.tasks[response.json().tempID];
                delete this.tasks[response.json().tempID];
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
        'sendToServer' : function(data){
        this.$http.post('/taskdata', data).then((response) => {
            console.log("sucessfully sent data to server");
        }, (response) => {
            //todo handle error
        });
        }
    },
    ready: function(){

        //load tasks from server
        this.$http.get('/taskdata').then((response) => {
            this.tasks = Object.assign({}, this.tasks, this.processIncomingTasksData(response.json().tasks));
            this.users = (response.json().users);
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
            console.log(sentStuff.message);
            var task = {};
            task[sentStuff.data.id] = sentStuff.data.newtask;
            vm.tasks = Object.assign(vm.tasks, task);

        });

    },
}
</script>