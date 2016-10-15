<template>
	<div class="modal fade" id="newTaskModal" tabindex="-1">
	  <div class="modal-dialog modal-lg" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close"  @click="close" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	        <h3 class="modal-title">Create New Task...</h3<hr />
	      </div>
	      <div class="modal-body">
	      <form class="form-hoizontal">
	      	<div class="row">
	      		<div class="col-sm-7">

			      <div class="form-group label-floating">
				    <label for="i5" class="control-label">Task Name</label>
				    <input v-model="task.name" type="text" class="form-control" id="i5">
				  </div>
			      <div class="form-group label-floating">
				    <label for="i5" class="control-label">Description</label>
				    <textArea v-model="task.description" type="text" class="form-control" id="i5" rows="4"></textArea>
				  </div>
				  <assign-users :users="users" :unassigned.sync="unassignedUsers" :assigned.sync="task.assignedUsers"></assign-users>
				  <create-subtasks :subtasks.sync="task.subtasks"></create-subtasks>
			  	</div>	
			 
			  <div class="col-sm-4 col-sm-offset-1">
				  
				<duedatecreator :due-date.sync="task.dueDate"></duedatecreator>
			  	<estimated-time-creator :estimated-time.sync="task.timeEstimated"></estimated-time-creator>
				    
			  </div>
	      </div>
	      </form>
	    </div>

			<div class="modal-footer">
				<hr />
				<span v-show="showError && errorHint" class=" text-warning" role="alert">{{errorHint}}</span>
		        <button type="button" class="btn btn-primary" :title="errorHint" :class="errorHint ? 'disabled' : ''" v-on:click="saveTask" >Save Task</button>
		        <button type="button" class="btn btn-secondary" @click="close" aria-label="Close">Close</button>
	      	</div>

	  </div>
	</div>
	</div>
</template>




<script>
import DuedateCreator from './duedateCreator.vue';
import EstimatedTimeCreator from './estimatedTimeCreator.vue';
import AssignUsers from './AssignUsers.vue';
import CreateSubtasks from './createSubtasks.vue';
export default {
	components: { 
	  	assignUsers: AssignUsers,
	  	duedatecreator: DuedateCreator,
	  	estimatedTimeCreator: EstimatedTimeCreator,
	  	createSubtasks: CreateSubtasks
  	},
  	props: [
  		"users"
  	],
  	data () {
        return {
        	task: {
        		name: "",
        		description:"",
        		timeLogged: "",
        		timeEstimated : "",
        		subtasks : new Array(),
        		dueDate : "",
        		loggedTimeHistory: new Array(),
                assignedUsers: [],
        	},
            unassignedUsers: [],
        	showError: false
        }
    },
    computed: {
    	errorHint: function(){
    		var error = "";
    		if (!this.task.name || !this.task.name.trim()){
    			error = "Task name is required. ";
    		}
    		if (this.task.timeEstimated){
	    		if (parseInt(this.task.timeEstimated) < 0 || parseInt(this.task.timeEstimated) % 1 !== 0){
	    			error += "Estimated time is not valid";
	    		}
	    	}
    		return error;
    	}
    },
    watch: {
    	'users' : function(){
    		this.unassignedUsers = this.getEveryUserId();
    	}
    },
    methods: {
    	saveTask: function(){
    		if (this.errorHint){
    			this.showError = true;
    			return;
    		}
    		this.$broadcast('savingTask', 'removeEmptySubtasks');
            this.unassignedUsers = this.getEveryUserId();
    		this.$dispatch("newTask", this.task);
    		this.close();
    	},
        getEveryUserId: function(){
            var userids = new Array();
            for (var userid in this.users){
                userids.push(userid);
            }
            return userids;
        },
    	close: function(){
    		this.resetContent();
    		jQuery('#newTaskModal').modal('hide');
    	},
        resetContent: function(){
            this.task = {
                name: "",
                description:"",
                timeLogged: "",
                timeEstimated : "",
                subtasks : new Array(),
                dueDate : "",
                loggedTimeHistory: new Array(),
                assignedUsers: [],
            };
            this.unassignedUsers = this.getEveryUserId();
            this.showError= false;
        }
    },
    ready: function(){
        this.resetContent();
    }
}
</script>