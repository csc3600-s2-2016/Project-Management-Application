<template>
<div>
<div class="container-fluid">
      <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
          <ul class="nav nav-sidebar">
            <li :class="activeClass[0]"><a @click.prevent="displayOverview">Overview</a></li>
            <li :class="activeClass[1]"><a @click.prevent="completionWeek">Task Completion this Week</a></li>
            <li :class="activeClass[3]"><a @click.prevent="completionYear">Task Completion this Year</a></li>
            <li :class="activeClass[4]"><a @click.prevent="overdueTasks">Overdue tasks</a></li>
          </ul>
          <ul class="nav nav-sidebar">
          	<li role="separator" class="divider"><br /></li>
            <li :class="activeClass[5]"><a @click.prevent="">Time Logged by Task</a></li>
            <li :class="activeClass[6]"><a @click.prevent="">Time Logged by User</a></li>
            <li :class="activeClass[7]"><a @click.prevent="usersTasks">Tasks Assiged to user</a></li>
          </ul>
          <ul class="nav nav-sidebar">
          	<li role="separator" class="divider"><br /><br /></li>
            <li :class="activeClass[9]"><a @click.prevent="print"><i class="fa fa-fw fa-print" aria-hidden="true"></i> Print</a></li>
            <li :class="activeClass[10]"><a @click.prevent="comingSoon"><i class="fa fa-fw fa-file-excel-o" aria-hidden="true"></i> Export</a></li>
          </ul>
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <canvas v-el:canvas v-show="chart !== null" height="auto", width="auto"> </canvas>
          <div v-if="showOverview" class="row">
				<div class="col-sm-4 text-center overview">
					<div class="overviewNumber">{{overview.tasksOverdue}}</div>
					Overdue Tasks
				</div>
				<div class="col-sm-4 text-center overview">
					<div class="overviewNumber">{{overview.tasksIncomplete}}</div>
					Incomplete Tasks
				</div>
				<div class="col-sm-4 text-center overview">
					<div class="overviewNumber">{{overview.tasksComplete}}</div>
					Completed Tasks
				</div>
          </div>
          


          <div v-show="showOverdueTasks">
          	<h1> Overdue Tasks: </h1>
          	<h3 v-show="overdueTasksData.length === 0">There are no overdue tasks <span class="fa fa-fw fa-thumbs-up"></span></h3>
          	<table v-show="overdueTasksData.length > 0" class="table">
          	<thead>
          		<tr>
			  	<th>#</th>
			  	<th>Task Name</th>
			  	<th>Assigned Users</th>
			  	<th>Task Due Date</th>
			  	<th>Days Overdue</th>
			  </tr>
          	</thead>
			<tbody>
				<tr v-for="task in overdueTasksData">
					<td>
						{{$index + 1}}
					</td>
					<td>
						{{task.name}}
					</td>
					<td>
						{{task.users}}
					</td>
					<td>
						{{task.dueDate}}
					</td>
					<td>
						{{task.daysOverdue}}
					</td>
				</tr>
			</tbody>
			</table>
         </div>


         <div v-if="showTimeLoggedByTask">
         	
         </div>
         <div v-if="showTimeLoggedByUser">
         	
         </div>
         <div v-show="showTasksAssignedToUser" class="row">
         	<div class="col-sm-4 col-sm-offset-1">
         	<br />
			  <h2>User:</h2>
			  <select class="form-control" v-model="selectedUser">
				  <option v-for="user in users" :value="user.id">{{user.displayName}}</option>
				</select>
			</div>
         	<div class="col-sm-4 col-sm-offset-1">
         		<br />
         		<h2>Tasks:</h2>
         		<br />
         		<ul>
         		<li v-for="task in tasksAssignedToUser">
         			<h4>{{task}}</h4>
         		</li>
         		</ul>
         	</div>


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
            currentUser: "",
            labels: [],
            data: [],
            reportTitle: '',
            chartType: "line",
            chart: null,
            activeClass: ["", "", "", "", "", "", "", "", "", "" ],
            overview: {
            	tasksComplete : 0,
            	tasksIncomplete: 0,
            	tasksOverdue: 0
            },
            showOverview: false,
            showOverdueTasks: false,
            showTimeLoggedByUser: false,
            showTimeLoggedByUser: false,
            showTasksAssignedToUser: false,
            selectedUser: ""
		}
	},
	ready: function () {
    	//load project from server
    	var vm = this;
        this.$http.get('/taskdata').then((response) => {
            var tasks = this.processIncomingTasksData(response.json().tasks);
            this.tasks = Object.assign({}, this.tasks, tasks);
            this.categories = (response.json().categories);
            this.users = (response.json().users);
            this.currentUser = (response.json().currentUser);
            toastr.success("Project loaded.");
            vm.displayOverview();
        }, (response) =>{
            toastr.error("Error loading tasks from server!");
        });
		this.addWeekFeatureToDateObject();
        
	},
	methods: {
		usersTasks : function(){
			this.showOverview = false;
			this.chart = null;
			this.showOverdueTasks = false;
			this.showTimeLoggedByUser = false;
			this.showTimeLoggedByTask = false;
			this.showTasksAssignedToUser = true;
			this.activeClass = ["", "", "", "", "", "", "", "active", "", "", ];

		},
		print: function(){
			window.print();
		},
		comingSoon: function(){
			alert("Sorry, this feature is not yet available...");
		},
		displayOverview : function(){
			this.activeClass = ["active", "", "", "", "", "", "", "", "", "" ];
			var today = new Date();
			this.chart = null;
			this.overview.tasksComplete = 0;
			this.overview.tasksIncomplete = 0;
			this.overview.tasksOverdue = 0;

			for (var id in this.tasks){
				if (this.tasks[id].completed === null){
					this.overview.tasksIncomplete ++;
				} else {
					this.overview.tasksComplete ++;
				}
				if (this.tasks[id].dueDate !== null && this.tasks[id].dueDate !== ""){
					try{
						if (this.tasks[id].dueDate < today){
							this.overview.tasksOverdue ++;
						}
					} catch (e){
					}
				}
			}
			this.overview
			this.showOverview = true;
			this.showOverdueTasks = false;
			this.showTimeLoggedByUser = false;
			this.showTimeLoggedByTask = false;
			this.showTasksAssignedToUser = false;

		},
		overdueTasks: function (){
			this.showOverview = false;
			this.chart = null;
			this.showTimeLoggedByUser = false;
			this.showTimeLoggedByTask = false;
			this.showTasksAssignedToUser = false;
			this.showOverdueTasks = true;
			this.activeClass = ["", "", "", "", "active", "", "", "", "", "", ];
		},
		completionWeek: function (){
			this.labels = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
			this.data = this.calculateCompletedTaskCount("daysThisWeek");
			this.reportTitle = "Task Completion this Week";
			this.activeClass = ["", "active", "", "", "", "", "", "", "", "", ];
			this.buildChart();
		},
		completionYear: function (){
			this.labels = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
			this.data =null;
			this.data = this.calculateCompletedTaskCount("monthsThisYear");
			this.reportTitle = "Task Completion this Year";
			this.activeClass = ["", "", "", "active", "", "", "", "", "", "", ];

			this.buildChart();
		},
		buildChart: function (){
			this.showOverview = false;
			this.showOverdueTasks = false;
			this.showTimeLoggedByUser = false;
			this.showTimeLoggedByTask = false;
			this.showTasksAssignedToUser = false;
			this.chart = new Chart(this.$els.canvas, this.chartDataset);
		},
		calculateCompletedTaskCount : function(frequency){
			var today = new Date();
			var data = [];
			if (frequency === "monthsThisYear"){
				for (var i=0;i<=today.getMonth();i++){
					data[i] = 0;
				}
				for (var id in this.tasks){
					if (this.tasks[id].completed !== null 
						&& this.tasks[id].completed.getFullYear() === today.getFullYear()){
						data[ this.tasks[id].completed.getMonth() ]++;
					}
				}
			} else if (frequency === "daysThisWeek"){
				data = [0,0,0,0,0,0,0];
				for (var id in this.tasks){
					if (this.tasks[id].completed !== null 
						&& this.tasks[id].completed.getWeek() === today.getWeek()  ){
						data[ this.tasks[id].completed.getDay() ]++;
					}
				}
			}


			return data;
		},
		processIncomingTasksData : function(tasks){
            for (var id in tasks){

                //convert duedate to Date object
                if (tasks[id].dueDate){
                    tasks[id].dueDate = new Date(tasks[id].dueDate + "T00:00:00");
                }

                if (tasks[id].completed !== null){
                	tasks[id].completed = new Date(tasks[id].completed);
                	console.log(tasks[id].completed);
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
        addWeekFeatureToDateObject: function(){
        	// Returns the ISO week of the date.
			Date.prototype.getWeek = function() {
			  var date = new Date(this.getTime());
			   date.setHours(0, 0, 0, 0);
			  // Thursday in current week decides the year.
			  date.setDate(date.getDate() + 3 - (date.getDay() + 6) % 7);
			  // January 4 is always in week 1.
			  var week1 = new Date(date.getFullYear(), 0, 4);
			  // Adjust to Thursday in week 1 and count number of weeks from date to week1.
			  return 1 + Math.round(((date.getTime() - week1.getTime()) / 86400000
			                        - 3 + (week1.getDay() + 6) % 7) / 7);
			}

			// Returns the four-digit year corresponding to the ISO week of the date.
			Date.prototype.getWeekYear = function() {
			  var date = new Date(this.getTime());
			  date.setDate(date.getDate() + 3 - (date.getDay() + 6) % 7);
			  return date.getFullYear();
			}
        }
    },
    computed: {
    	tasksAssignedToUser : function(){
    		var tasks = [];
    		for (id in this.tasks){
    			for (userId in this.tasks[id].assignedUsers){
    				if (this.tasks[id].assignedUsers[userId] == this.selectedUser){
    					tasks.push(this.tasks[id].name);
    				}
    			}
    		}
    		return tasks;
    	},
    	overdueTasksData: function(){
    		var today = new Date();
    		var overdueTasks = [];
    		for (var id in this.tasks){
    			if (this.tasks[id].dueDate !== null && this.tasks[id].dueDate < today){
    				var days = Math.floor(( today - this.tasks[id].dueDate ) / 86400000);
    				overdueTasks.push({
    					name: this.tasks[id].name,
    					users: "Coming Soon",
    					dueDate: this.tasks[id].dueDate.toDateString(),
    					daysOverdue: days  
    				});
    			}
    		}
    		return overdueTasks;
    	},
    	chartDataset : function(){
			return {
				type: this.chartType,
				data: {
				    labels: this.labels,
				    datasets: [
				        {
				            label: this.reportTitle,
				            fill: false,
				            lineTension: 0.1,
				            backgroundColor: "rgba(75,192,192,0.4)",
				            borderColor: "rgba(75,192,192,1)",
				            borderCapStyle: 'butt',
				            borderDash: [],
				            borderDashOffset: 0.0,
				            borderJoinStyle: 'miter',
				            pointBorderColor: "rgba(75,192,192,1)",
				            pointBackgroundColor: "#fff",
				            pointBorderWidth: 1,
				            pointHoverRadius: 5,
				            pointHoverBackgroundColor: "rgba(75,192,192,1)",
				            pointHoverBorderColor: "rgba(220,220,220,1)",
				            pointHoverBorderWidth: 2,
				            pointRadius: 1,
				            pointHitRadius: 10,
				            data: this.data,
				            spanGaps: false
				        }
				    ]
				}
			};
    	}
        
	}

}
	
</script>

<style>
.overview{
	font-family: 'Lalezar', cursive;
	font-size: 30px;
}
.overviewNumber {
	font-size: 200px;
}
</style>
