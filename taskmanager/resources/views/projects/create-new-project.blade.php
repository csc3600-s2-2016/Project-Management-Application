@extends('templates.layout')
@section('content')
    <div class="container" >
    <div class="row">
    <div class="col-xs-10 col-xs-offset-1">
    	<div class="panel panel-primary" style="margin-top: 100px;">
    	<div class="panel-heading">
    	<h2>Create New Project</h2>
    	</div>
    	<div class="panel-body">
    	<form class="form-horizontal" action="/create-new-project" method="post">
    		{{ csrf_field() }}
		  
		  <div class="form-group">
		    <label for="projectName" class="col-sm-2 control-label">Project Name</label>
		    <div class="col-sm-10">
		      <input type="text" rows="4" class="form-control" name="name" id="projectName" placeholder="Project Name">
		    </div>
		  </div>


		  <div class="form-group">
		    <label for="projectDescription" class="col-sm-2 control-label">Project Description</label>
		    <div class="col-sm-10">
		      <textarea rows="4" class="form-control" id="projectDescription" name="description" placeholder="Project Description" ></textarea>
		    </div>
		  </div>


		  <div class="form-group">
		    <label for="col1Name" class="col-sm-2 control-label">First Column</label>
		    <div class="col-sm-10">
		      <input type="text" class="form-control" id="col1Name" name="col1" placeholder="Column Title" value="To Do">
		    </div>
		  </div>


		  <div class="form-group">
		    <label for="col2Name" class="col-sm-2 control-label">Second Column</label>
		    <div class="col-sm-10">
		      <input type="text" class="form-control" id="col2Name" name="col2" placeholder="Column Title" value="In Progress">
		    </div>
		  </div>

		  <div class="form-group">
		    <label for="col3Name" class="col-sm-2 control-label">Third Column</label>
		    <div class="col-sm-10">
		      <input type="text" class="form-control" id="col3Name" name="col3" placeholder="Column Title" value="Peer Review">
		    </div>
		  </div>

		  <div class="form-group">
		    <label for="col4Name" class="col-sm-2 control-label">Forth Column</label>
		    <div class="col-sm-10">
		      <input type="text" class="form-control" id="col4Name" name="col4" placeholder="Column Title" value="Complete">
		      <p class="help-block">Leave this field empty if you only want 3 columns</p>
		    </div>
		  </div>

			<div class="form-group">
			    <div class="col-sm-offset-2 col-sm-10">
			      <button type="submit" class="btn btn-default btn-raised">Submit</button>
			    </div>
  			</div>

  			</form>
    	</div>
    	</div>
    	</div>
    	</div>
    </div>
@stop