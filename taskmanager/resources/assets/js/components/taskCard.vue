

<!-- ==================================================
    TASK CARD
    shows summary of task in a card component
====================================================-->



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
    min-height: 110px;
}
.task-summary:hover {
    background-color:  #cce6ff !important;
}

.task-panel {
    max-width: 600px;
    margin: 0 auto 20px auto;
}

.name-label {
    display: inline-block !important;
    margin: 7px 7px 0 0;
}

</style>

<!-- - - - - - - - - - - - - - - - - - - - - - - - - - 
                TEMPLATE
- - - - - - - - - - - - - - - - - - - - - - - - - - -->
<template id="taskCard-template">
<div>
<view-task-modal :id="randomIdentifier" :title="task.name" :assigned-users="task.assignedUsers" :subtasks="task.subtasks" :description="task.description" :time-logged="task.hoursUtilized" :time-estimated="task.hoursEstimated" :due-date="task.dueDate"></view-task-modal>
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
                    <li><a href="#"><i class="fa fa-pencil taskMenuIcon" aria-hidden="true"></i> Edit</a></li>
                </ul>
            </div>
        </div>
        <div class="h6">
            <span v-if="task.dueDate">Due {{ task.dueDate }}</span>
            <span v-if="task.timeEstimated"> [{{ timeSummary }}] </span>
        </div> 
        
        <h4>
            <div v-for="user in task.assignedUsers" class="label label-default name-label">{{user.displayName}}</div>
            <a href="#"  style="float:right;"  v-on:click="toggleSubtasks" v-if="task.subtasks">
                <span class='badge'>
                    <i v-bind:class="[ 'fa', showSubtasks ? 'fa-caret-up' : 'fa-caret-down' ]" aria-hidden="true"></i>
                    {{ task.subtasks.length }}
                </span>
            </a>
        </h4>

    </div>

    <div class="panel-body" v-show='showSubtasks'>
        <ul class="subtaskList" v-sortable="{animation: 250}">
            <li v-for="subtask in task.subtasks">
                {{ subtask.description }}
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
export default {
    data () {
        return {
            showSubtasks: false,
        }
    },
    props: [
        "task"
    ],
    computed: {
        timeSummary: function(){
            if (this.task.timeLogged){
                return  this.task.timeLogged + '/' + this.task.timeEstimated;
            }else {
                return this.task.timeEstimated;
            }  
        },
        canMarkAs: function(){
            if (this.status == 1){
                return 
            }
        },
        randomIdentifier: function(){
            var identifierLength = 16;
            var identifier = "";
            var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
            for(var i = 0; i < identifierLength; i++) {
                identifier += possible.charAt(Math.floor(Math.random() * possible.length));
            }
            return identifier;
        }
    },
    methods: {
        toggleSubtasks: function(){
            this.showSubtasks = ! this.showSubtasks;
        }
    },
    components: {
        viewTaskModal: ViewTaskModal
    }
}
</script>