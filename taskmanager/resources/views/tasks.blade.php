@extends('templates.layout')
@section('content')
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

