<template>
<div class="container-fluid">
    <!-- Titles -->
    <div class="row">

        <div class="container">
        <div class="row"><h2>Manage: </h2></div>
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
                        <!--<user-project-stats v-for="userStats in allData.userProjectStats"></user-project-stats>-->
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
                                    </div>
                                    <div class="row">
                                        Total Tasks
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="row">
                                    </div>
                                    <div class="row">
                                        Tasks Open
                                    </div>
                                </div>
                                <div class="col-sm-4 border-left">
                                    <div class="row">
                                    </div>
                                    <div class="row">
                                        Tasks Closed
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="list-group-item">
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
                        <select>
                            <option>u1</option>
                            <option>kyle lewer</option>
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
                loaded: false
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
              alert("we are working on that!");
          },
          fetchAllData(){
              this.$http.get('/projects/' + this.projectId + '/getAll').then((response)=>{
                console.log(response.json());
              }, (response) =>{

              });
          }
        }
    }
</script>
