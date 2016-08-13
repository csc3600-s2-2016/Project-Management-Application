@extends('templates.layout')
@section('content')
<div class="container-fluid">
<div id="taskManagementApp" class="row">
	<div id="col-todo" class="col-md-4">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<div class="panel-title">
					To Do:
				</div>
			</div>
			<div class="panel-body">


				<task-card></task-card>

			</div>
		</div>
	</div>
	<div id="col-inProgress" class="col-md-4">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<div class="panel-title">
					In Progress:
				</div>
			</div>
			<div class="panel-body">
				some stuff
			</div>
		</div>
	</div>
	<div id="col-completed" class="col-md-4">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<div class="panel-title">
					Complete:
				</div>
			</div>
			<div class="panel-body">
				some stuff
			</div>
		</div>
	</div>
</div>
</div>
@stop

