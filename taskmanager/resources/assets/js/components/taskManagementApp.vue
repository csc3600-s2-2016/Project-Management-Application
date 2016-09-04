<template>
	<new-task-modal></new-task-modal>
    <div :style="container">
        <div id="taskManagementApp" class="row">
            <task-column v-for="category in categories" :name.sync="category" :col-names.sync="categories" :tasks.sync="tasks"></task-column>
        </div>
    </div>
</template>

<script>
import NewTaskModal from './newTaskModal.vue';
import TaskColumn from './taskColumn.vue';
export default {
    data () {
        return {
            categories: [
                "To Do", "In Progress", "Completed"
            ],
        	tasks: [
        		{
        			name:"Some Task",
        			description: "Lorem ipsum dolor sit amet, consectetur adipisicing elit. Vel quisquam odio, voluptates ipsa iure, recusandae aliquam!",
                    assignedUsers: [
                        {displayName:"John"},
                        {displayName:"Antonio"}
                    ],
                    dueDate: "24-05-16",
                    timeLogged: "4",
                    timeEstimated: "20",
                    subtasks: [
                        {description: "Lorem ipsum dolor sit amet."},
                        {description: "Consectetur adipisicing elit. Aliquam, quia."},
                        {description: "Lorem ipsum dolor sit amet, consectetur adipisicing."}
                    ],
                    status: 0
        		},
                {
                    name:"Find the one armed, one eyed, one legged man!",
                    description: "Placeat amet vitae eos expedita rem labore! Magni, quaerat incidunt. Perferendis architecto, id vitae omnis sit libero, voluptate qui voluptas similique accusantium!",
                    assignedUsers: [
                        {displayName:"John"},
                        {displayName:"Antonio"}
                    ],
                    dueDate: "24-05-16",
                    timeLogged: "4",
                    timeEstimated: "20",
                    subtasks: [
                        {description: "Maxime, harum!"},
                        {description: "Aliquam, quia."},
                        {description: "Consectetur adipisicing."}
                    ],
                    status: 1
                },
                {
                    name:"Finish coding these components",
                    description: "Placeat amet vitae eos expedita rem labore! Magni, quaerat incidunt. Perferendis architecto, id vitae omnis sit libero, voluptate qui voluptas similique accusantium!",
                    assignedUsers: [
                        {displayName:"John"},
                        {displayName:"Sarah"},
                        {displayName:"Prof.Smith"},
                        {displayName:"Antonio"}
                    ],
                    dueDate: "24-05-16",
                    timeLogged: "4",
                    timeEstimated: "20",
                    subtasks: [
                        {description: "Code this"},
                        {description: "Code that"}
                    ],
                    status: 2
                },
                {
                    name:"Lorem ipsum dolor.",
                    dueDate: "24-05-16",
                    status: 1
                },
                {
                    name:"Watch game of thrones",
                    assignedUsers: [
                        {displayName:"Hiroshi"},
                        {displayName:"BoatyMcBoat"}
                    ],
                    status: 3
                }
        	]
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
        taskColumn: TaskColumn
    }
}
</script>