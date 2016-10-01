<template>
	<div :class="colClass" class="task-col-container" :style="colWidths">
		<div class="task-column-heading-container">
			<span class="h2">
				{{name}}
			</span>
		</div>
        <div class="task-column" :style="colPadding" @dragover.prevent="dragOver" @drop="colDropZone">
                <div v-if="colID === 0" class="btn btn-raised btn-primary" style="width:100%;margin-top:0px;" data-toggle="modal" data-target="#newTaskModal">create new task</div>

                <task-card v-for="(key, task) in tasks | filterBy colID in 'status' | orderBy 'priority' " :task.sync="task" :users="users" :current-user="currentUser" :id="key" :cols="colNames" 
                 :draggable="!key.includes('TEMPORARY')" @dragstart="dragStart" :class=" key.includes('TEMPORARY') ? 'taskCard' : 'draggableCursor taskCard' " ></task-card>

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
    			return "padding: 0px 50px;";
    		} else {
    			return "padding: 0px 30px;";
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
            var dragged = event.dataTransfer.getData("text/plain");
            var oldStatus = this.tasks[dragged].status;
            var newStatus = this.colID
            var oldPriority = this.tasks[dragged].priority;
            var sortedPrioritys = new Array();
            var droppedOnColumn = event.target.classList.contains('task-column');
            var droppedOnTaskCard = !droppedOnColumn;
            sortedPrioritys = new Array();


            //if dropped on column, reorder the list and then add task to end
            if (droppedOnColumn){
                this.tasks[dragged].status = -1;
                var newsorted = this.sortTasksIntoNewArray(newStatus);
                newsorted.push(dragged);
                this.saveSortedArray(newsorted);
                this.changeTaskStatus(dragged, newStatus, oldStatus);
                //if the task was from a different column then also update old cols priorities
                if (oldStatus !== newStatus){
                    var oldsorted = this.sortTasksIntoNewArray(oldStatus);
                    this.saveSortedArray(oldsorted);
                }
                return;
            }

            if (droppedOnTaskCard){
                var taskDropped = this.getTaskThatTheDraggedElementWasDroppedOn(event);
                var newPriority = taskDropped.priority;
                
                // if re-ordering in same column then update this column
                if (newStatus === oldStatus){
                    var taskPriorityIsIncreased = oldPriority > newPriority ? true : false;
                    for (var id in this.colTasks){
                        var rivalPriority = this.colTasks[id].priority;
                        if (taskPriorityIsIncreased){
                            if (rivalPriority >= newPriority && rivalPriority < oldPriority){
                                this.colTasks[id].priority++
                            }
                        } else {
                            if (rivalPriority <= newPriority && rivalPriority > oldPriority){
                                this.colTasks[id].priority--
                            }
                        }
                        this.tasks[dragged].priority = newPriority;
                        this.changeTaskStatus(dragged, newStatus, oldStatus);
                        
                    }
                }

                // if task dragged from another column then re-order both cols
                if (newStatus !== oldStatus)
                    for (var id in this.colTasks){
                        if (this.colTasks[id].priority >= newPriority){
                            this.colTasks[id].priority ++;
                        }
                    }
                    this.tasks[dragged].priority = newPriority;
                    this.changeTaskStatus(dragged, newStatus, oldStatus);
                    this.tasks[dragged].status = newStatus;
                    this.saveSortedArray(this.sortTasksIntoNewArray(newStatus));
                    this.saveSortedArray(this.sortTasksIntoNewArray(oldStatus));
            }
        },
        changeTaskStatus: function(taskid, newStatus, oldStatus){
            this.tasks[taskid].status = newStatus;
            this.$dispatch('changeTaskStatus', {"taskid" : taskid, "newStatus":newStatus, "oldStatus":oldStatus});
        },

        sortTasksIntoNewArray: function(status) {
            var sortedPrioritys = new Array();
            var collided = new Array();
            for (var id in this.tasks){
                if (this.tasks[id].status === status){
                    var index = this.tasks[id].priority;
                    if (!sortedPrioritys[index]){
                        sortedPrioritys[index] = id;
                    } else { //unexpected collision!
                        collided.push(id);
                    }
                }
            }
            //remove any holes in sorted array
            for (var i = sortedPrioritys.length - 1; i >= 0; i--) {
                if (!sortedPrioritys[i]){
                    sortedPrioritys.splice(i, 1);
                }
            }

            //concatenate any collided items
            sortedPrioritys.concat(collided);
            return sortedPrioritys;
        },
        saveSortedArray: function(sortedPrioritys) {
            for (var i=0; i<sortedPrioritys.length;i++){
                var taskID = sortedPrioritys[i];
                this.tasks[taskID].priority = i;
            }
            this.$dispatch('prioritiesUpdated', sortedPrioritys);
        },
        getTaskThatTheDraggedElementWasDroppedOn: function(event){
            var taskCard = '';
            var eventItem = event.target.parentElement;
            while (!taskCard){
                if (eventItem.classList.contains("taskCard")){
                    taskCard = eventItem;
                } else {
                    eventItem = eventItem.parentElement;
                }
            }
            return this.tasks[taskCard.id];
        }
    },
    components: {
    	taskCard: TaskCard
    }
    
}
</script>
