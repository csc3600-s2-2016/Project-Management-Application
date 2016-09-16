<template>
	<div :class="colClass" class="task-col-container" :style="colWidths">
		<div class="task-column-heading-container">
			<span class="h2">
				{{name}}
			</span>
		</div>
		<div class="task-column" :style="colPadding" @dragover.prevent="dragOver" @drop="colDropZone">

				<task-card v-for="(key, task) in colTasks | orderBy 'priority' "  :task.sync="task" :users="users" :current-user="currentUser" :id="key"
                 draggable="true" @dragstart="dragStart" class="taskCard"></task-card>

		</div>
	</div>
</template>

<script>
import TaskCard from './taskCard.vue';
export default {
	props: [
		"name",
		"colNames",
		"tasks",
        "users",
        "currentUser"
	],
	data () {
        return {

        };
    },
    computed: {
        colID: function(){
            for (var i=0;i<this.colNames.length;i++){
                if (this.colNames[i] === this.name){
                    return i;
                }
            }
            return -1;
        },
    	colSize: function(){
    		if (this.colNames.length === 3){
    			return 4;
    		}
			return 3;
    	},
    	colClass: function(){
    		return "col-md-" + this.colSize;
    	},
    	colPadding: function(){
    		if (this.colNames.length === 3){
    			return "padding: 10px 50px;";
    		} else {
    			return "padding: 10px 30px;";
    		}
    	},
    	colTasks: function(){
            var tasks = {};
            if (!this.tasks){
                return tasks;
            }
            for (var id in this.tasks){
                if (this.tasks[id].status === this.colID){
                    tasks[id] = this.tasks[id];
                }
            }
            return tasks;
        },
        colWidths: function(){
            var style = "@media (min-width: 992px) {padding:0px;max-width:";
            var maxPercentage = 100 / this.colNames.length;
            style += maxPercentage + "%;}";
            return style;
        }
    },
    methods: {
    	puttableColumns: function(){
    		var cols = [];
    		for (var i=0;i<this.colNames.length;i++){
    			if (this.colNames[i] !== this.name){
    				cols.push(this.colNames[i]);
    			}
    		}
    		return cols;
    	},
        dragStart: function(event){
            event.dataTransfer.setData("text/plain", event.target.id);
        },
        dragOver: function(event){
        },
        colDropZone: function(event){
            var taskDragged = event.dataTransfer.getData("text/plain");



            
            if (event.target.classList.contains('task-column')){
                this.tasks[taskDragged].status = this.colID;
                var nextPriority = 0;
                for (var id in this.colTasks){
                    if (this.colTasks[id].priority > nextPriority){
                        nextPriority = this.colTasks[id].priority + 1;
                    }
                }
                this.tasks[taskDragged].priority = nextPriority;
                return
            }

            



            var taskCard = '';
            var eventItem = event.target.parentElement;
            while (!taskCard){
                if (eventItem.classList.contains("taskCard")){
                    taskCard = eventItem;
                } else {
                    eventItem = eventItem.parentElement;
                }
            }
            var taskDropped = this.tasks[taskCard.id];

            
            this.tasks[taskDragged].priority = taskDropped.priority;
            for (var taskid in this.colTasks){
                if (this.colTasks[taskid].priority >= taskDropped.priority){
                    this.colTasks[taskid].priority++
                }
            }

            this.tasks[taskDragged].status = this.colID;

        }
    },
    components: {
    	taskCard: TaskCard
    }
    
}
</script>
