<template>
<div>
    <div v-if="disconnectedFromServer" class="disablePage"></div>
	<new-task-modal :users="users"></new-task-modal>
    <edit-task-modal id="editTaskModal" :task="taskToEdit" :users="users"></edit-task-modal>
    <div v-if="loaded" :style="container">
        <div id="taskManagementApp" class="row">
            <task-column v-for="category in categories" :name.sync="category" :col-names.sync="categories" :tasks.sync="tasks" :users="users" :current-user="currentUser"></task-column>
        </div>
    </div>
    <div v-if="!loaded" style="margin-top:40vh;" class="text-center">
        <i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i>
        <span class="sr-only">Fetching Data...</span>
    </div>
</div>
</template>

<script>
var io = require('socket.io-client');
import NewTaskModal from './newTaskModal.vue';
import TaskColumn from './taskColumn.vue';
import EditTaskModal from './editTaskModal.vue';
export default {
    data () {
        return {
            categories: [],
        	tasks: {},
            users: {},
            currentUser: "",
            taskToEdit: "",
            disconnectedFromServer : false,
            loaded: false

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
                    tasks[id].dueDate = new Date(tasks[id].dueDate + "T00:00:00");
                }

                // convert logged times to Date object
                if (tasks[id].loggedTimeHistory.constructor === Array){
                    for (var i=0; i<tasks[id].loggedTimeHistory.length; i++){
                        var log = tasks[id].loggedTimeHistory[i];
                        log.task = 't' + log.task;
                        log.startDateTime = new Date(log.start_date_time.replace(" ","t"));
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
            newTask.completed = null;
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
                var taskWithNewId = {};
                var newId = jsonResponse.newID;
                this.tasks[newId] = this.tasks[jsonResponse.tempID];
                delete this.tasks[jsonResponse.tempID];
                this.tasks = Object.assign({}, this.tasks);


                
            }, (response) => {
                console.log("Failed to send data to server");
            });
        },
        'editTask': function(taskId){
            this.taskToEdit = {'id': taskId, 'data': jQuery.extend({}, this.tasks[taskId])};
            if ( ! Array.isArray(this.taskToEdit.data.subtasks)){
                this.taskToEdit.data.subtasks = new Array();
            } else {
                this.taskToEdit.data.subtasks = this.taskToEdit.data.subtasks.slice();
            }
            if ( ! Array.isArray(this.taskToEdit.data.assignedUsers)){
                this.taskToEdit.data.assignedUsers = new Array();
            } else {
                this.taskToEdit.data.assignedUsers = this.taskToEdit.data.assignedUsers.slice();
            }
            jQuery('#editTaskModal').modal('show');
        },
        'saveEditedTask' : function(task){
            this.tasks[task.id] = Object.assign({}, task.data);
            this.taskToEdit = {};
            console.log({updateType: "editTask", task: task});
            this.$http.post("/taskdata", {updateType: "editTask", task: task}, {'headers': {'Content-Type': 'application/json'}}).then((response) =>{
                var subtaskIds = response.json();
            //change subtask ids to reflect those in database
            for (var tempid in subtaskIds){
                for (var i = 0; i<this.tasks[task.id].subtasks.length; i++ ){
                    if(this.tasks[task.id].subtasks[i].tempID === tempid){
                        this.tasks[task.id].subtasks[i].id = subtaskIds[tempid];
                        delete this.tasks[task.id].subtasks[i].tempID;
                        continue;
                    }
                }
            }
        }, (response) => {
                console.log("Failed to send data to server");
            });
        },
        'prioritiesUpdated': function(priorities){
            this.sendToServer( {'updateType':'changeTaskPrioritys','priorities': priorities} );
            
        },
        'changeTaskStatus': function(statusData){
            this.tasks = Object.assign({}, this.tasks);
            this.sendToServer( {'updateType':'changeTaskStatus','statusData': statusData} );
        },
        'completeSubtask': function(data){
            this.sendToServer({"updateType": "completeSubtask", "data": data});
        },
        'logTime': function(data){
            this.sendToServer({"updateType": "logTime", "logData": data});
        },
        'updateSubtaskPriorites' : function(data){
            console.log(data);
            this.sendToServer({"updateType": "updateSubtaskPriorites", "subtaskPriorityData": data});
        },
        'archiveTask' : function(taskId){
            this.sendToServer({"updateType": "archiveTask", "taskId": taskId});
            delete this.tasks[taskId];
            this.tasks = Object.assign({}, this.tasks);
        },
        'completeTask' : function(taskId){
            this.tasks[taskId].completed = Object.assign(new Date());
            this.sendToServer({"updateType": "completeTask", "taskId": taskId, "completed": this.tasks[taskId].completed});
        }
    },
    ready: function() {
        this.currentUser = this.getCookie('uid');
        var socket = io('http://taskmanager.site:3000');


        //load project from server
        this.$http.get('/taskdata').then((response) => {
            var tasks = this.processIncomingTasksData(response.json().tasks);
            this.tasks = Object.assign({}, this.tasks, tasks);
            this.categories = (response.json().categories);
            this.users = (response.json().users);
            this.currentUser = (response.json().currentUser);
            toastr.success("Project loaded.");
            this.loaded = true;
        }, (response) =>{
            toastr.error("Error loading tasks from server!");
        });
       

        //setup websocket for future incoming data
        var vm = this;
        socket.on("connect", function () {
                vm.disconnectedFromServer = false;
                socket.emit('authenticate', vm.getCookie('socketKey'));
        });
        socket.on("reconnect", function () {
            vm.disconnectedFromServer = false;
            toastr.options.timeOut = 6000;
            toastr.remove();
            toastr.success("Connection to server re-established");
        });
        socket.on("disconnect", function () {
            vm.disconnectedFromServer = true;
            toastr.remove();
            toastr.options.timeOut = 0;
            toastr.warning("Trying to reconnect...", "Disconnected from server!");
            jQuery('.modal').modal('hide');

        });
        socket.on('userOnline', function (user) {
            if (user.id !== vm.currentUser) {
                toastr.info(user.name + " is online.");
            }
        });
        socket.on('newTask', function (sentStuff) {
            if (sentStuff.updatedBy !== vm.currentUser) {
                toastr.info(sentStuff.message);
                var task = {};

                task[sentStuff.data.id] = sentStuff.data.newtask;
                task[sentStuff.data.id].dueDate = new Date(task[sentStuff.data.id].dueDate);
                vm.tasks = Object.assign({}, vm.tasks, task);
            }
        });
        socket.on('changeTaskStatus', function (sentStuff) {
            if (sentStuff.updatedBy !== vm.currentUser) {
                toastr.info(sentStuff.message);
                vm.tasks[sentStuff.data.statusData.taskid].status = sentStuff.data.statusData.newStatus;
            }
        });
        socket.on('changeTaskPrioritys', function (sentStuff) {
            if (sentStuff.updatedBy !== vm.currentUser) {
                for (var i = 0; i < sentStuff.data.priorities.length; i++) {
                    var taskID = sentStuff.data.priorities[i];
                    vm.tasks[taskID].priority = i;
                }
            }
        });
        socket.on("completeSubtask", function (sentStuff) {
            if (sentStuff.updatedBy !== vm.currentUser) {
                toastr.info(sentStuff.message);
                var subtasks = vm.tasks["t" + sentStuff.data.data.taskId].subtasks;
                for (var i = subtasks.length - 1; i >= 0; i--) {
                    if (subtasks[i].id === sentStuff.data.data.subtaskId) {
                        subtasks[i].complete = sentStuff.data.data.complete;
                        break;
                    }
                }
            }
        });
        socket.on("logTime", function (sentStuff) {
            if (sentStuff.updatedBy !== vm.currentUser) {
                toastr.info(sentStuff.message);
                var log = sentStuff.data.logData.log;
                log.startDateTime = new Date(log.startDateTime);
                log.user = log.user.id;
                var history = vm.tasks[sentStuff.data.logData.taskId].loggedTimeHistory;
                if (Array.isArray(history)) {
                    vm.tasks[sentStuff.data.logData.taskId].loggedTimeHistory.push(log);
                } else {
                    vm.tasks[sentStuff.data.logData.taskId].loggedTimeHistory = [log];
                }
            }
        });
        socket.on("updateSubtaskPriorites", function (sentStuff) {
            if (sentStuff.updatedBy !== vm.currentUser) {
                toastr.info(sentStuff.message);
                var taskToUpdate = vm.tasks[sentStuff.data.subtaskPriorityData.taskId];
                for (var id in sentStuff.data.subtaskPriorityData.subtasks) {
                    for (var i = 0; i < taskToUpdate.subtasks.length; i++) {
                        if (taskToUpdate.subtasks[i].id == id) {
                            vm.tasks[sentStuff.data.subtaskPriorityData.taskId].subtasks[i].priority = sentStuff.data.subtaskPriorityData.subtasks[id];
                        } else {

                        }
                    }

                }
                vm.tasks[sentStuff.data.subtaskPriorityData.taskId].subtasks.sort(function (a, b) {
                    return a.priority - b.priority;
                })
            }
        });
        socket.on("archiveTask", function (sentStuff) {
            if (sentStuff.updatedBy !== vm.currentUser){
                toastr.info(sentStuff.message);
                delete vm.tasks[sentStuff.data.taskId];
                vm.tasks = Object.assign({}, vm.tasks);
            }
        });
        socket.on("completeTask", function(sentStuff){
            if (sentStuff.updatedBy !== vm.currentUser){
                toastr.info(sentStuff.message);
                vm.tasks[sentStuff.data.taskId].complete = sentStuff.data.completed;
            }
        });
        socket.on("editTask", function (sentStuff) {
            if (sentStuff.updatedBy !== vm.currentUser){
                toastr.info(sentStuff.message);
                console.log(sentStuff);
                vm.tasks[sentStuff.data.task.id] =  sentStuff.data.task.data;
                var dueDate = vm.tasks[sentStuff.data.task.id].dueDate;
                vm.tasks[sentStuff.data.task.id].dueDate =  dueDate ? new Date(vm.tasks[sentStuff.data.task.id].dueDate) : null;
                vm.tasks = Object.assign({}, vm.tasks);
            }
        });
    }
}
</script>