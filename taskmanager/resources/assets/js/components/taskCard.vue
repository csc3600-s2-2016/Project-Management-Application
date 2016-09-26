



<!-- - - - - - - - - - - - - - - - - - - - - - - - - - 
                TEMPLATE
- - - - - - - - - - - - - - - - - - - - - - - - - - -->
<template >
<div :id="id">
<view-task-modal :id="randomIdentifier" :log-time-id="randomIdentifier + 'logtime'" :task-id="id" :task.sync="task" :users="users" :cols="cols"></view-task-modal>
<log-time :id="randomIdentifier + 'logtime'" :task-name="task.name" :task-id="id" :view-task-modal-id="randomIdentifier" :task-history.sync="task.loggedTimeHistory" :task-time-logged.sync="task.timeLogged" :current-user="currentUser" :users="users"></log-time>
<div class="panel panel-default task-panel">
    <div class="panel-heading task-summary">
        <div class=row>
            <div class='col-xs-10'>
                <span class="h4"> {{ task.name }} </span>
            </div>
            <div class='col-xs-2 text-right'>
                <a id="dLabel" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-bars fa-lg" aria-hidden="true"></i>
                </a>
                <ul class="dropdown-menu" aria-labelledby="dLabel">
                    <li>
                        <a data-toggle="modal" data-target="{{'#' + randomIdentifier}}">
                            <i class="fa fa-info-circle taskMenuIcon" aria-hidden="true"></i> Preview
                        </a>
                    </li>
                    <li>
                        <a v-on:click="openLogTimeModal">
                            <i class="fa fa-clock-o taskMenuIcon" aria-hidden="true"></i> Log Time
                        </a>
                    </li>
                    <li><a @click="editTask"><i class="fa fa-pencil taskMenuIcon" aria-hidden="true"></i> Edit</a></li>
                </ul>
            </div>
        </div>
        <div class="h6">
            <span v-show="task.dueDate" :class="pastDueDate ? 'text-danger' : ''">Due {{ dueDateString }}</span>
            <span v-show="timeSummary" :class="loggedTimeOverBudget ? 'text-danger' : '' "> [{{ timeSummary }}] </span>
        </div> 
        
        <h4>
            <table style="width:100%;">
                <tr>
                    <td>
                         <div v-for="userID in task.assignedUsers" class="label label-username">{{users[userID].displayName}}</div>
                    </td>
                    <td v-if="task.subtasks && task.subtasks.length > 0" class="text-right">
                        <a v-on:click="toggleSubtasks" >
                        <span class='badge'>
                            <i v-bind:class="[ 'fa', showSubtasks ? 'fa-caret-up' : 'fa-caret-down' ]" aria-hidden="true"></i>
                            {{ task.subtasks.length }}
                        </span>
                        </a>
                    </td>
                </tr>
            </table>
        </h4>

    </div>

    <div class="panel-body" v-show='showSubtasks'>
        <ul class="subtaskList">
            <li v-for="subtask in task.subtasks" :class="subtask.complete ? 'line-through' : '' " >
                {{ subtask.name }}
            </li>
        </ul>
    </div>
</div>
</div>
</template>


<!-- - - - - - - - - - - - - - - - - - - - - - - - - - 
             SCRIPT
- - - - - - - - - - - - - - - - - - - - - - - - - - -->
<script>
import ViewTaskModal from './viewTaskModal.vue';
import LogTime from './logTime.vue';
export default {
    data () {
        return {
            showSubtasks: false,
            needToOpenTaskModal: false
        }
    },
    props: [
        "task",
        "users",
        "currentUser",
        "cols",
        "id"
    ],
    computed: {
        dueDateString: function(){
            if (this.task.dueDate){
                return this.task.dueDate.toDateString();
            }
            return "";
        },
        timeSummary: function(){
            var summary = '';
            console.log(this.task.timeLogged);
            if (this.task.timeLogged){
                summary += this.task.timeLogged + "/";
            }
            if (this.task.timeEstimated){
                summary += this.task.timeEstimated;
            } else if (this.task.timeLogged){
                summary += "--";
            } 
            return summary;

        },
        loggedTimeOverBudget : function(){
            if (this.task.timeLogged && this.task.timeEstimated){
                return parseInt(this.task.timeLogged) > parseInt(this.task.timeEstimated);
            }
            return false;
        },
        randomIdentifier: function(){
            var identifierLength = 16;
            var identifier = "";
            var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
            for(var i = 0; i < identifierLength; i++) {
                identifier += possible.charAt(Math.floor(Math.random() * possible.length));
            }
            return identifier;
        },
        pastDueDate : function(){
            return  this.task.dueDate ? (this.task.dueDate.getTime() < Date.now()) : false;
        },
        sortableOptions: function(){
            return {
                animation: 250,
                onDrop: {

                }
            };
        }
    },
    methods: {
        toggleSubtasks: function(){
            this.showSubtasks = ! this.showSubtasks;
        },
        openLogTimeModal: function(){
            this.needToOpenTaskModal = false;
            jQuery('#' + this.randomIdentifier + 'logtime').modal('show');
        },
        editTask: function(){
            this.$dispatch('editTask', this.id);
        }
    },
    components: {
        viewTaskModal: ViewTaskModal,
        logTime: LogTime
    },
    events: {
        'logTimeOpenedFromTaskModal' : function(msg){
            this.needToOpenTaskModal = msg;
        },
        'recalculateTimeLogged' : function(msg){
            if (!this.task.loggedTimeHistory){
                return 0;
            }
            var time = 0;
            for(var i = 0; i<this.task.loggedTimeHistory.length;i++){
                time += parseInt(this.task.loggedTimeHistory[i].timeLogged);
            }
            this.task.timeLogged = time;
        }
    },
    ready: function(){
        this.$emit('recalculateTimeLogged');
    }
}
</script>







<!-- - - - - - - - - - - - - - - - - - - - - - - - - - 
                STYLE
- - - - - - - - - - - - - - - - - - - - - - - - - - -->
<style>
.subtaskList {
    list-style: none;
    padding: 0 5px;
    margin: 0;
}
.subtaskList li{
    border-top: 1px solid red;
    padding: 5px;
    width: 100%;
    max-width: 100%;
    border-top: 1px solid #AAA;
}
.subtaskList   li:first-child {
    border-top: 0;
}
.subtaskList   li:hover {
    background-color: #EEE;
}
.taskMenuIcon {
    padding-right: 10px;
}
.task-summary {
    background-color: #FFFFFF !important;
}
.task-summary:hover {
    background-color:  #cce6ff !important;
}
.line-through{
    text-decoration: line-through;
}



</style>
