

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
<div class="panel panel-default task-panel">
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
                </ul>
            </div>
        </div>
        <div class="h6">
            <span v-if="dueDate">Due {{ dueDate }}</span>
            <span v-if="hoursEstimated"> [{{ hoursSummary }}] </span>
        </div> 
        
        <h4>
            <div v-for="user in assignedUsers" class="label label-default name-label">{{user.displayName}}</div>
            <a href="#"  style="float:right;"  v-on:click="toggleSubtasks">
                <span class='badge'>
                    <i v-bind:class="[ 'fa', showSubtasks ? 'fa-caret-up' : 'fa-caret-down' ]" aria-hidden="true"></i>
                    {{ subtasks.length }}
                </span>
            </a>
        </h4>

    </div>

    <div class="panel-body" v-show='showSubtasks'>
        <ul class="subtaskList" v-sortable="{animation: 250}">
            <li v-for="subtask in subtasks">
                {{ subtask.description }}
            </li>
        </ul>
    </div>
</div>
</template>


<!-- - - - - - - - - - - - - - - - - - - - - - - - - - 
             SCRIPT
- - - - - - - - - - - - - - - - - - - - - - - - - - -->
<script>
export default {
    data () {
        return {
            title: "Task Title",
            status: 0,
            showSubtasks: false,
            dueDate: "3/10/16",
            hoursUtilized: 20,
            hoursEstimated: 30,
            assignedUsers: [
                {displayName: "Bill"},
                {displayName: "Matt"}
            ],
            subtasks: [
                {description: "Buy milk"},
                {description: "Build time machine"}
            ]
        }
    },
    props: {

    },
    computed: {
        hoursSummary: function(){
            if (this.hoursUtilized){
                return  this.hoursUtilized + '/' + this.hoursEstimated;
                }else {
                    return this.hoursEstimated;
                }  
        },
        canMarkAs: function(){
            if (this.status == 1){
                return 
            }
        }
    },
    methods: {
        toggleSubtasks: function(){
            this.showSubtasks = ! this.showSubtasks;
        }
    }
}
</script>