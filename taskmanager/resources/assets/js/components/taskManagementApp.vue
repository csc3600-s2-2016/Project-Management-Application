<template>
<div>
	<new-task-modal :users="users"></new-task-modal>
    <div :style="container">
        <div id="taskManagementApp" class="row">
            <task-column v-for="category in categories" :name.sync="category" :col-names.sync="categories" :tasks.sync="tasks" :users="users" :current-user="currentUser"></task-column>
        </div>
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
                "To Do", "In Progress", "Completed", "Too Hard Basket"
            ],
        	tasks: {
        		t01: {
        			name:"Some Task",
        			description: "Lorem ipsum dolor sit amet, consectetur adipisicing elit. Vel quisquam odio, voluptates ipsa iure, recusandae aliquam!",
                    assignedUsers: [
                        "u1", "u4", "u6"
                    ],
                    dueDate: "24-05-2016",
                    timeLogged: "4",
                    timeEstimated: "20",
                    subtasks: [
                        {description: "Lorem ipsum dolor sit amet.", complete: false},
                        {description: "Consectetur adipisicing elit. Aliquam, quia.", complete: true},
                        {description: "Lorem ipsum dolor sit amet, consectetur adipisicing.", complete: true}
                    ],
                    status: 0,
                    priority: 0,
                    loggedTimeHistory: [
                        {startDate: "11-06-2016", startTime: "15:03", timeLogged: "4", user:"Matt", notes:"I hated this task!"},
                        {startDate: "11-06-2016", startTime: "15:42", timeLogged: "6", user:"Tony", notes:"I Loved this task!"},
                        {startDate: "11-06-2016", startTime: "15:03", timeLogged: "4", user:"Matt", notes:"I hated this task!"},
                        {startDate: "11-06-2016", startTime: "15:42", timeLogged: "6", user:"Tony", notes:"I Loved this task!"}
                    ]
        		},
                t02: {
                    name:"Find the one armed, one eyed, one legged man!",
                    description: "Placeat amet vitae eos expedita rem labore! Magni, quaerat incidunt. Perferendis architecto, id vitae omnis sit libero, voluptate qui voluptas similique accusantium!",
                    assignedUsers: [
                        "u4", "u5"
                    ],
                    dueDate: "24-11-2016",
                    timeLogged: "",
                    timeEstimated: "20",
                    subtasks: [
                        {description: "Maxime, harum!", complete: false}
                    ],
                    status: 1,
                    priority: 0,
                },
                t03: {
                    name:"Finish coding these components",
                    description: "Placeat amet vitae eos expedita rem labore! Magni, quaerat incidunt. Perferendis architecto, id vitae omnis sit libero, voluptate qui voluptas similique accusantium!",
                    assignedUsers: [
                        "u2"
                    ],
                    dueDate: "14-12-2016",
                    timeLogged: "4",
                    timeEstimated: "20",
                    subtasks: [
                        {description: "Code this", complete: false},
                        {description: "Code that", complete: false}
                    ],
                    status: 0,
                    priority: 2
                },
                t04: {
                    name:"Lorem ipsum dolor.",
                    timeLogged: "",
                    dueDate: "2-3-16",
                    status: 0,
                    priority: 1
                },
                t05: {
                    name:"Watch game of thrones",
                    dueDate: "",
                    timeLogged: "",
                    timeEstimated: "",
                    assignedUsers: [
                        "u6", "u5", "u1", "u3"
                    ],
                    status: 3,
                    priority: 0
                }
        	},
            users: {
                u1: {id: "u1", displayName:"John"},
                u2: {id: "u2", displayName:"Sarah"},
                u3: {id: "u3", displayName:"Tony"},
                u4: {id: "u4", displayName:"Jill"},
                u5: {id: "u5", displayName:"Anontio"},
                u6: {id: "u6", displayName:"Emma"}
            },
            currentUser: "u5"
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
    },
    methods: {
        generateTempKeyREPLACE_THIS_METHOD_ONCE_DATABASE_GENERATES_KEY_FOR_US: function(){
            var identifierLength = 8;
            var identifier = "";
            var possible = "0123456789";
            for(var i = 0; i < identifierLength; i++) {
                identifier += possible.charAt(Math.floor(Math.random() * possible.length));
            }
            return 't' + identifier;
        }
    },
    events: {
        'newTask': function(newTask){
            var nextId = this.generateTempKeyREPLACE_THIS_METHOD_ONCE_DATABASE_GENERATES_KEY_FOR_US();
            newTask.status = 0;
            this.tasks[nextId] = newTask;
            this.tasks = Object.assign({}, this.tasks );
        }
    }
}
</script>