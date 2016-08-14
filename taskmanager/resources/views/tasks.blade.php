@extends('templates.layout')
@section('content')
<div class="container-fluid">
<div id="taskManagementApp" class="row">
	<div class="col-md-4">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<div class="panel-title">
					To Do:
				</div>
			</div>
			<div class="panel-body  task-column" v-sortable="{group :{name:'todos', put:['doings', 'dones']}, animation: '250'}">


				<task-card></task-card>

			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<div class="panel-title">
					In Progress:
				</div>
			</div>
			<div class="panel-body task-column" v-sortable="{group: {name: 'doings', put: ['todos','dones'] }, animation: '250'}">
				
				<task-card></task-card><task-card></task-card><task-card></task-card>

			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<div class="panel-title">
					Complete:
				</div>
			</div>
			<div class="panel-body  task-column" v-sortable="{group: {name: 'dones', put: ['todos','doings'] }, animation: '250'}">
				

			</div>
		</div>
	</div>
</div>
</div>
@stop

