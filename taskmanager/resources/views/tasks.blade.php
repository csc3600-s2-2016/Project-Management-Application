@extends('templates.layout')
@section('content')
<!-- CREATE NEW TASK MODAL -->
<div class="modal fade" id="newTaskModal" tabindex="-1" >
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
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
			    <input type="text" class="form-control" id="i5">
			  </div>
		      <div class="form-group label-floating">
			    <label for="i5" class="control-label">Description</label>
			    <textArea type="text" class="form-control" id="i5" rows="4"></textArea>
			  </div>
			  <assign-users></assign-users>
			  <create-subtasks></create-subtasks>
		  	</div>
		 
		  <div class="col-sm-4 col-sm-offset-1">
			  
			<duedatecreator></duedatecreator>
		  	<estimated-time-creator></estimated-time-creator>
			    
		  </div>
      </div>
      </form>
    </div>

		<div class="modal-footer">
			<hr />
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	        <button type="button" class="btn btn-primary">Save Task</button>
      	</div>

  </div>
</div>
</div>

<div class="container-fluid">
<div id="taskManagementApp" class="row">
	<div class="col-md-4" style="padding:0px;">
		<div class="">
			<div class="task-column-heading-container">
				<span class="task-column-heading">
					To Do:
				</span>
			</div>
			<div id="first-task-column" class="task-column" v-sortable="{group :{name:'todos', put:['doings', 'dones']}, animation: '250'}">


				<task-card></task-card>

			</div>
		</div>
	</div>
	<div class="col-md-4" style="padding:0px;">
		<div class="">
			<div class="task-column-heading-container">
				<span class="task-column-heading">
					In Progress:
				</span>
			</div>
			<div class="task-column" v-sortable="{group :{name:'todos', put:['doings', 'dones']}, animation: '250'}">


				<task-card></task-card>

			</div>
		</div>
	</div>
	<div class="col-md-4" style="padding:0px;">
		<div class="">
			<div class="task-column-heading-container">
				<span class="task-column-heading">
					Complete:
				</span>
			</div>
			<div class="task-column" v-sortable="{group :{name:'todos', put:['doings', 'dones']}, animation: '250'}">


				<task-card></task-card>

			</div>
		</div>
	</div>
</div>
</div>


@stop

