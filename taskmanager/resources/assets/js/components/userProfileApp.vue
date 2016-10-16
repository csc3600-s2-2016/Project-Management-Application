<template>
    <div v-if="loaded" class="container-fluid" style="margin-top:100px;" >
        
        <div class="row">
            <div class="col-md-4 col-md-offset-1">
                <div class="panel panel-primary">
                    <div class="panel-heading panel-primary">
                        <h1>Your Contributions</h1>
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
                            <h1>Your Profile</h1>
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
    </div>
    <div class="container" v-else>
        <h1 class="" style="text-align:center;">Fetching data</h1>
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
