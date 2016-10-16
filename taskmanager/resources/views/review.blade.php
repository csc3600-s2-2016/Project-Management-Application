@extends('templates.layout')
@section('content')
<!--link href="/css/app.css" rel="stylesheet"--> 
<div id="reviewWrapper">
	<div id="menuWrapper">
		<ul class="menuNavigation">
			<h3>Report Menu </h3>
			<li><a href="#" id="projectMenu"> Project Base</a></li>
				<ul id="projectSub" >
					<li><a href="#" id="projectSubComplete"> Task Completion by Month</a></li>
					<li><a href="#" id="projectSubProgress"> Task Progression </a></li>
					<li><a href="#" id="projectSubUser"> Tasks Assigned by User </a></li>		
					<li><a href="#" id="projectSubTask"> Time Assigned by Task </a></li>				
				</ul>
			<li><a href="#" id="taskMenu"> Task Base</a></li>
				<ul id="taskSub" >
					<li><a href="#" id="taskSubTimeLog"> Time Logged by Task </a></li>
					<li><a href="#" id="taskSubOverall"> Overall Status </a></li>		
					<li><a href="#" id="taskSubCrit"> Critical Tasks </a></li>				
				</ul>
			<li><a href="#" id="userMenu">  User Base</a></li>
				<ul id="userSub" >
					<li><a href="#" id="userSubTime"> Time Logged by User </a></li>
					<li><a href="#" id="userSubTask"> Tasks assiged by user </a></li>		
					<li><a href="#" id="userSubOverdue"> Overdue tasks by user </a></li>				
				</ul>

			<hr>
			<h4>Select Display Type</h4>
			<ul>
				<input type="radio" name="chartType" id="DataGrid" checked="yes"/> Data Grid  <br>
				<input type="radio" name="chartType" id="pie" /> Pie  <br>
				<input type="radio" name="chartType" id="line"/> Line  <br>
				<input type="radio" name="chartType" id="bar"/> Bar  <br>
				<input type="radio" name="chartType" id="radar"/> Radar  <br>
			</ul>
		</ul>
	</div>

	<div id="menuBuffer" align="left">
		<a href="#" class="btn btn-success" id="toggle">&#8665; </a>
	</div>

	<div id="bodyReport">
		<div class="row">
			<div class="col">
				<h1> Reporting</h1>
				<div id="reportDisplay"> </div>
			</div>
		</div>
	</div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.3.0/Chart.min.js"></script>

@stop

