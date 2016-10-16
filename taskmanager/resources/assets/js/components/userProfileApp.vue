<template>
    <div v-if="loaded" class="container-fluid" style="margin-top:100px;" >
        
        <div class="row">
            <div class="col-md-4 col-md-offset-1">
                <div class="panel panel-primary">
                    <div class="panel-heading panel-primary">
                        <h3>Your Contributions</h3>
                    </div>
                    <user-contributions :ttl-contributed="parseFloat(allData.userProjectInfo.numHours)"
                                        :num-contributed="allData.userProjectInfo.numTasks">

                    </user-contributions>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3>Your Profile</h3>
                        </div>
                        <div class="panel-body">
                            <user-details :user-id="allData.userId"
                                          :username="allData.username"
                                          :email="allData.email">
                            </user-details>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="row">
        <div class="col-md-4 col-md-offset-1">
            <div class="panel panel-primary">
                <div class="panel-heading panel-primary">
                    <h3>Pending Invitations</h3>
                </div>
                <div class="panel-body">
                Invite to blah <button class="btn btn-primary btn-raised">Accept</button>
                </div>
            </div>
        </div>
        </div>
    </div>
    <div v-if="!loaded" style="margin-top:40vh;" class="text-center">
        <i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i>
        <span class="sr-only">Fetching Data...</span>
    </div>
</template>
<style>
</style>
<script>

    import UserContributions from './userContributions.vue';
    import UserDetails from './userDetails.vue';

    export default{
        data(){
            return{
                allData: {},
                loaded: false
            }
        },
        components: {
            userContributions: UserContributions,
            userDetails: UserDetails
        },
        methods: {
            fetchAllData(){
                this.$http.get('profile/getAll').then((response) =>{
                    this.allData = JSON.parse(response.data);
                    console.log('loaded data');
                    this.loaded = true;
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
