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
    background-color: $table-bg-hover;
}

</style>

<template id="taskCard-template">
<div class="panel panel-default">
    <div class="panel-heading">

        <div class=row>
            <div class='col-xs-10'>
                <span class="h4"> {{ title }} </span>
            </div>
            <div class='col-xs-2 text-right'>
                <a id="dLabel" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-bars fa-lg" aria-hidden="true"></i>
                </a>
                <ul class="dropdown-menu" aria-labelledby="dLabel">
                <li><a href="#">Preview</a></li>
                <li><a href="#">Edit</a></li>
                <li role="separator" class="divider"></li>
                <li><a href="#">Mark as in progress</a></li>
                <li><a href="#">Mark as complete</a></li>
                

                </ul>
            </div>
        </div>
        <div class="h6"> Due 3/10/16, [30/80hrs] </div> 
        <h4>
            <span class="label label-default">Bill</span>
            <span class="label label-default">Matt</span>
        </h4>


    </div>
    <div class="panel-body">
        
        
        <ul class="subtaskList" v-sortable="{animation: 150}">
            <li>A subtask for the task</li>
            <li>Another subtask right here</li>
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
            title: "Task Title"
        }
    }
}
</script>