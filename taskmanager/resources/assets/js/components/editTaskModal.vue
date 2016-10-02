<template>
	<div class="modal fade" id="editTaskModal" tabindex="-1" >
	  <div class="modal-dialog modal-lg" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close"  @click="close" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	        <h3 class="modal-title">Edit task:</h3<hr />
	      </div>
	      <div class="modal-body">
	      <form class="form-hoizontal">
	      	<div class="row">
	      		<div class="col-sm-7">

			      <div class="form-group">
				    <label for="i5" class="control-label">Task Name</label>
				    <input v-model="taskToEdit.name" type="text" class="form-control" id="i5">
				  </div>
			      <div class="form-group">
				    <label for="i5" class="control-label">Description</label>
				    <textArea v-model="taskToEdit.description" type="text" class="form-control" id="i5" rows="4"></textArea>
				  </div>
				  <assign-users :users="users" :assigned.sync="taskToEdit.assignedUsers" :unassigned.sync="unassignedUsers"></assign-users>
				  <create-subtasks :subtasks.sync="taskToEdit.subtasks"></create-subtasks>
			  	</div>
			 
			  <div class="col-sm-4 col-sm-offset-1">
				  
				<duedatecreator :due-date.sync="taskToEdit.dueDate"></duedatecreator>
			  	<estimated-time-creator :estimated-time.sync="taskToEdit.timeEstimated"></estimated-time-creator>
				    
			  </div>
	      </div>
	      </form>
	    </div>

			<div class="modal-footer">
				<hr />
				<span v-show="showError && errorHint" class=" text-warning" role="alert">{{errorHint}}</span>
		        <button type="button" class="btn btn-primary" :title="errorHint" :class="errorHint ? 'disabled' : ''" v-on:click="save" >Save Changes</button>
		        <button type="button" class="btn btn-secondary" @click="close" aria-label="Close">Close</button>
	      	</div>

	  </div>
	</div>
	</div>
</template>




<script>
import DuedateCreator from './duedateCreator.vue';
import EstimatedTimeCreator from './estimatedTimeCreator.vue';
import AssignUsers from './assignUsers.vue';
import CreateSubtasks from './createSubtasks.vue';
export default {
	components: { 
	  	assignUsers: AssignUsers,
	  	duedatecreator: DuedateCreator,
	  	estimatedTimeCreator: EstimatedTimeCreator,
	  	createSubtasks: CreateSubtasks
  	},
  	props: [
        "task", "users"
  	],
  	data () {
        return {
            showError: false,
            unassignedUsers: []
        }
    },
    computed: {
        taskToEdit: function(){
            if (!this.task.data){
                this.task = {'id' : -1 , "data" : {} };
                this.task.data = {
                    name: "",
                    description: "",
                    assignedUsers: [],
                    dueDate: "",
                    timeLogged: "",
                    timeEstimated: "",
                    subtasks: [],
                    status: 0,
                    priority: 0,
                    loggedTimeHistory: []
                }
            }
            this.unassignedUsers = this.getUnassignedUsers();
            return this.task.data;
        },
        errorHint: function(){
            var error = "";
            if (!this.task.data.name || !this.task.data.name.trim()){
                error = "Task name is required. ";
            }
            if (this.task.data.timeEstimated){
                if (parseInt(this.task.data.timeEstimated) < 0 || parseInt(this.task.data.timeEstimated) % 1 !== 0){
                    error += "Estimated time is not valid";
                }
            }
            return error;
        }
    },
    methods: {
        save: function(){
            if (this.errorHint){
                this.showError = true;
                return;
            }
            this.$broadcast('savingTask', 'removeEmptySubtasks');
            this.$dispatch('saveEditedTask', this.task);
            this.close();
        },
        close: function(){
            jQuery('#editTaskModal').modal('hide');
        },
        getUnassignedUsers: function(){
            var unassigned = new Array();
            for (var uid in this.users){
                if (this.task.data.assignedUsers.indexOf(uid) < 0){
                    unassigned.push(uid);
                }
            }
            return unassigned;
        }
    }
}
</script>