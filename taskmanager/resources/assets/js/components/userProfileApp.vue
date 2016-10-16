<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-success">
                    <div class="panel-heading">
                        <h1>Your Projects</h1>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <project-status-panel
                    v-for="panel in allData.userProjectInfo.projectOverviews" :project-id="parseInt(panel.projectId)"
                    :project-name="panel.projectName"
                    :current="parseFloat(panel.current)" :expected="parseFloat(panel.expected)"
                    :members="parseInt(panel.numMembers)" :can-manage="parseFloat(panel.canManage)">
            </project-status-panel>
        </div>
        <div class="row">
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-success">
                    <div class="panel-heading panel-success">
                        <h1>Your Contributions</h1>
                    </div>
                    <user-contributions :ttl-contributed="parseFloat(allData.userProjectInfo.numHours)"
                                        :num-contributed="allData.userProjectInfo.numTasks"></user-contributions>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h1>Your Profile</h1>
                        </div>
                        <div class="panel-body">
                            <user-details></user-details>
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

    import ProjectStatusPanel from './projectStatusPanel.vue';
    import UserContributions from './userContributions.vue';
    import UserDetails from './userDetails.vue';

    export default{
        data(){
            return{
                allData: {}
            }
        },
        components: {
            projectStatusPanel: ProjectStatusPanel,
            userContributions: UserContributions,
            userDetails: UserDetails
        },
        methods: {
            fetchAllData(){
                this.$http.get('profile/getAll').then((response) =>{
                    this.allData = JSON.parse(response.data);
                    console.log(response);
                    console.log('loaded data');
                }, (response) => {
                    //failed
                    console.log({"Failure": response});
                });
            }
        },
        created() {
            this.fetchAllData();

        }
    }
</script>
