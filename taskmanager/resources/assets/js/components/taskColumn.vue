<template>
	<div :class="colClass" class="task-col-container" :style="colWidths">
		<div class="task-column-heading-container">
			<span class="h2">
				{{name}}
			</span>
		</div>
		<div class="task-column" :style="colPadding" v-sortable="sortableOptions">

				<task-card v-for="(key, task) in colTasks | orderBy 'priority' "  :task.sync="task" :users="users" :current-user="currentUser" :id="key" :cols="colNames" class="taskCard"></task-card>

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
        sortableOptions: function(){
            var that = this;
            return{
                animation: 100,
                group: {
                    name: that.name, 
                    put: that.puttableColumns()
                },
                onAdd: function(event){
                    
                    var taskAdded = that.tasks[event.item.id];
                    if (!taskAdded){ return; }
                    console.log(event.item.id);
                    var newPriority = event.newIndex;
                    for (var id in that.colTasks){
                        if (that.tasks[id].priority >= newPriority){
                            that.tasks[id].priority++;
                        }
                    }
                    taskAdded.status = that.colID;
                    taskAdded.priority = newPriority;
                    that.tasks = Object.assign({}, that.tasks );
                    
                },
                onUpdate: function(event){
                    var taskMoved = that.tasks[event.item.id];
                    taskMoved.priority = -1;
                    var newPriority = event.newIndex;
                    var oldPriority = event.oldIndex;
                    var taskPriorityIsIncreased = oldPriority > newPriority ? true : false;
                    for (var id in that.colTasks){
                        var rivalPriority = that.colTasks[id].priority;
                        if (taskPriorityIsIncreased){
                            if (rivalPriority >= newPriority && rivalPriority < oldPriority){
                                that.colTasks[id].priority++
                            }
                        } else {
                            if (rivalPriority <= newPriority && rivalPriority > oldPriority){
                                that.colTasks[id].priority--
                            }
                        }
                        taskMoved.priority = newPriority;
                    }

                    taskMoved.priority = newPriority;
                },
                onRemove: function(event){
                    var length = that.colTasks.length;
                    var sortedPrioritys = new Array();


                    for (var id in that.colTasks){
                        var nextsmallest = that.colTasks[id].priority;
                        for (var idsToCheck in that.colTasks){
                            var nextPriorityToCheck = that.colTasks[idsToCheck].priority;
                            if (nextsmallest > nextPriorityToCheck && sortedPrioritys.indexOf(nextPriorityToCheck) === -1){
                                nextsmallest = nextPriorityToCheck;
                            }
                        }
                        sortedPrioritys.push(id);
                    }

                    for (var i=0; i<sortedPrioritys.length;i++){
                        var taskID = sortedPrioritys[i];
                        that.colTasks[taskID].priority = i;
                    }

                }
            };
        },
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
    	}

    },
    components: {
    	taskCard: TaskCard
    }
    
}
</script>
