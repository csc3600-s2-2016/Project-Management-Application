<template>
<div class="container">
    <div v-if="!loaded" style="margin-top:40vh;" class="text-center">
        <i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i>
        <span class="sr-only">Fetching Data...</span>
    </div>
    <!-- Titles -->
    <div v-if="loaded">
        <div class="row">
            <div class="col-md-12" style="margin-top:80px; ">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h1> {{projectData.projectName}} <small style="color:#ccc;"> - Project Management</small></h1>
                        <p v-show="projectData.archived">This project is archived</p>
                    </div>
                </div>
            </div>
        </div>
        
    <div class="row">
        <div class="col-md-5">
            <div class="row">
                <!-- Project Stats Cards -->
        <div class="col-md-12" id="project-stats">
            <div class="panel panel-info">

                <div class="panel-heading">
                    <h4>Project Stats</h4>
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
        </div> <!-- end stats col -->
         
        </div>
        <div class="row">
        <!-- Project Actions -->
        <div class="col-md-12" id="project-actions">
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <h4>Danger Zone</h4>
                </div>
                <div class="panel-body"  >
                    <div class="row">
                        <button class="btn btn-danger" v-on:click="changeProjectManager()">Change Project Manager</button>
                        <select v-model="newManager">

                            <option v-for="userStats in projectData.projectMembers" :value="userStats.userId">{{userStats.username}}</option>
                        </select>
                    </div>
                    <div class="row" >
                        <button v-if="!projectData.archived" class="btn btn-danger" v-on:click="archiveProject()">Archive Project</button>
                    </div>
                </div>
            </div>
        </div>
        

            </div>
        </div>
        



           <!-- Project People Information -->
           <div class="col-md-7">
           <div class="row">


        <div class="col-md-12" id="project-people">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h4>Invite People To Join Project</h4>
                </div>
                <div class="panel-body">
                    <form class="form-horizontal" style="padding:10px 30px;">
                      <div class="form-group">
                          <label for="inputEmail" class="col-md-2 control-label">Email</label>
                          <div class="col-md-10">
                            <input v-model="inviteEmail" type="email" class="form-control" id="inputEmail" placeholder="Email">
                          </div>
                        </div>
                        <div class="text-center">
                      <button class="btn btn-default" @click.prevent="sendInvite">Send invitation</button>
                      </div>
                    </form>
                </div>
            </div>
        </div>

        </div>


           <div class="row">
        <div class="col-md-12" id="project-people">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h4>Current Project Members</h4>
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
                loaded: false,
                newManager: "",
                inviteEmail: ""
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
          sendInvite() {
            var formData = new FormData();
            formData.append('invite', this.inviteEmail);
            this.$http.post('/projects/invite', formData).then((response)=>{
                alert("Invite Sent");
            }, (response) => {

            });
            this.inviteEmail = "";
          },
          fetchAllData(){
              this.$http.get('/projects/' + this.projectId + '/getAll').then((response)=>{
                this.projectData = JSON.parse(response.data);
                this.loaded = true;
              }, (response) =>{

              });
          }
        },
        created()
        {
            this.fetchAllData();
        }
    }
</script>
