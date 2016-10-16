<template>
<div>
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
</div>
</template>
<script>
export default {
	data() {
		return {
			categories: [],
        	tasks: {},
            users: {},
            currentUser: ""
		}
	},
	ready: function () {
    	//load project from server
        this.$http.get('/taskdata').then((response) => {
            var tasks = this.processIncomingTasksData(response.json().tasks);
            this.tasks = Object.assign({}, this.tasks, tasks);
            this.categories = (response.json().categories);
            this.users = (response.json().users);
            this.currentUser = (response.json().currentUser);
            toastr.success("Project loaded.");
        }, (response) =>{
            toastr.error("Error loading tasks from server!");
        });
	},
	methods: {
		processIncomingTasksData : function(tasks){
            for (var id in tasks){

                //convert duedate to Date object
                if (tasks[id].dueDate){
                    tasks[id].dueDate = new Date(tasks[id].dueDate + "T00:00:00");
                }

                // convert logged times to Date object
                if (tasks[id].loggedTimeHistory.constructor === Array){
                    for (var i=0; i<tasks[id].loggedTimeHistory.length; i++){
                        var log = tasks[id].loggedTimeHistory[i];
                        log.task = 't' + log.task;
                        log.startDateTime = new Date(log.start_date_time.replace(" ","t"));
                        delete log.start_date_time;
                        log.timeLogged = log.time_logged;
                        delete log.time_logged;
                        log.user = 'u' + log.user_id;
                        delete log.user_id;
                    }
                }

                //add calculated fields
                tasks[id].timeLogged = "";
            }
            return tasks;
        },
	}

}
	
</script>
