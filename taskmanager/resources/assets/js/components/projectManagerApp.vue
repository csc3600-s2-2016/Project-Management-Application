<template>
<div class="container-fluid" v-if="loaded">
    <!-- Titles -->
    <div class="row">

        <div class="container">
        <div class="row"><h2>Manage: {{projectData.projectName}}</h2></div>
        <div class="row" v-show="projectData.archived">This project is archived</div>
        </div>
    </div>
    <div class="row">

        <!-- Project People Information -->
        <div class="col-md-4" id="project-people">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3>Manage People</h3>
                </div>
                <div class="panel-body">
                    <div class="list-group">
                        <user-project-stats v-for="userStats in projectData.projectMembers"
                                            :user-id="userStats.userid"
                                            :username="userStats.username"
                                            :created-tasks="userStats.tasksCreated"
                        >

                        </user-project-stats>
                    </div>
                </div>
            </div>
        </div>

        <!-- Project Stats Cards -->
        <div class="col-md-4" id="project-stats">
            <div class="panel panel-primary">

                <div class="panel-heading">
                    <h3>Project Stats</h3>
                </div>

                <div class="panel-body"><div class="list-group">
                    <div class="list-group-item">
                        <div class="row-action-primary">
                            <i class="material-icons">view_week</i>
                        </div>
                        <div class="row-content">
                            <h4 class="list-group-item-heading">Task Information</h4>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="row">
                                        {{projectData.numTasks}}
                                    </div>
                                    <div class="row">
                                        Total Tasks
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="row">
                                        {{projectData.openTasks}}
                                    </div>
                                    <div class="row">
                                        Tasks Open
                                    </div>
                                </div>
                                <div class="col-sm-4 border-left">
                                    <div class="row">
                                        {{projectData.numTasks - projectData.openTasks}}
                                    </div>
                                    <div class="row">
                                        Tasks Closed
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="list-group-item" v-show="false">
                        <div class="row-action-primary">
                            <i class="material-icons">view_week</i>
                        </div>
                        <div class="row-content">
                            <h4 class="list-group-item-heading">Effort Information</h4>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="row">
                                    </div>
                                    <div class="row">
                                        Planned Effort
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="row">
                                    </div>
                                    <div class="row">
                                        Expended Effort
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="row">
                                    </div>
                                    <div class="row">
                                        Effort Remaining
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div></div>
            </div>
        </div>

        <!-- Project Actions -->
        <div class="col-md-4" id="project-actions">
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <h3>Danger Zone</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <button class="btn btn-danger" v-on:click="changeProjectManager()">Change Project Manager</button>
                        <select v-model="newManager">

                            <option v-for="userStats in projectData.projectMembers" :value="userStats.userId">{{userStats.username}}</option>
                        </select>
                    </div>
                    <div class="row">
                        <button class="btn btn-danger" v-on:click="archiveProject()">Archive Project</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div v-else>
    <h1>Fetching Data</h1>
</div>
</template>
<style>
</style>
<script>
    import UserProjectStats from './userProjectStats.vue';
    export default{
        props : {
            projectId : {}
        },
        data(){
            return{
                projectData : {},
                loaded: false,
                newManager: ""
            }
        },
        components:{
            userProjectStats: UserProjectStats
        },
        methods: {
          projectName() {
              return "";
          },
          changeProjectManager()
          {
              alert("we are working on that!");
          },
          archiveProject()
          {
              this.$http.get('/projects/' + this.projectId + '/archive').then((response) =>
              {
                 alert("Project successfully archived. This project will no longer display on your projects page.")
                 this.projectData.archived = true;
              }, (response) => {

              });
          },
          fetchAllData(){
              this.$http.get('/projects/' + this.projectId + '/getAll').then((response)=>{
                this.projectData = JSON.parse(response.data);
              }, (response) =>{

              });
          }
        },
        created()
        {
            this.fetchAllData();
            this.loaded = true;
        }
    }
</script>
