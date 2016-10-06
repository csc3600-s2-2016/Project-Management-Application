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
.task-summary:hover {
    background-color:  #cce6ff !important;
}
progress !important {
    background-color: white !important;
    color:white !important;
}
</style>

<template id="taskCard-template">
<div class="panel panel-default">
    <div class="panel-heading task-summary">
        <div class=row>
            <div class='col-xs-10'>
                <span class="h4"> {{ title }} </span>
            </div>
            <div class='col-xs-2 text-right'>
                <a id="dLabel" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-bars fa-lg" aria-hidden="true"></i>
                </a>
                <ul class="dropdown-menu" aria-labelledby="dLabel">
                <li><a href="#"><i class="fa fa-info-circle taskMenuIcon" aria-hidden="true"></i> Preview</a></li>
                <li><a href="#"><i class="fa fa-pencil taskMenuIcon" aria-hidden="true"></i> Edit</a></li>
                <li role="separator" class="divider"></li>
                <li><a href="#"><i class="fa fa-thumbs-up taskMenuIcon" aria-hidden="true"></i> Mark as in progress</a></li>
                <li><a href="#"><i class="fa fa-trophy taskMenuIcon" aria-hidden="true"></i> Mark as complete</a></li>
                

                </ul>
            </div>
        </div>
        <div class="h6"> Due 3/10/16, [{{expendedEffort}}/{{expectedEffort}}hrs] </div> 
        <h4>
            <span class="label label-default">Bill</span>
            <span class="label label-default">Matt</span>
            <a href="#"  style="float:right;"  v-on:click="toggleSubtasks">
                <span class='badge'>
                    <i v-bind:class="[ 'fa', showSubtasks ? 'fa-caret-up' : 'fa-caret-down' ]" aria-hidden="true"></i>
                    4
                </span>
            </a>
        </h4>
        <div class="progress">
            <div class="progress-bar" style="width:{{progress()}}%;">
                <span class="sr-only">{{progress()}}% Complete</span>
            </div>
        </div>

    </div>

    <div class="panel-body" v-show='showSubtasks'>
        
        
        <ul class="subtaskList" v-sortable="{animation: 250}">
            <li>A subtask for the task </li>
            <li>Another subtask right here </li>
            <li>This is yet another subtask</li>
            <li>A task that is part of another task</li>
        </ul>
    
    </div>
</div>
</template>

<script>
export default {
    data () {
        return {
            title: "Task Title",
            showSubtasks: false,
            expendedEffort: 20,
            expectedEffort: 100
        }
    },
    methods: {
        toggleSubtasks(){
            this.showSubtasks = ! this.showSubtasks;
        },
        requestSubTasks(){
            // request list of subtasks 
        },
        progress(){
            return parseInt(this.expendedEffort/this.expectedEffort*100)
        }
    }
}
</script>