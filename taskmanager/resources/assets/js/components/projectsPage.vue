<template>
<div>
	<div v-if="!loaded" style="margin-top:40vh;" class="text-center">
		<i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i>
		<span class="sr-only">Fetching Data...</span>
	</div>
	<div v-if="loaded">
    	<div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h1>Your Projects</h1>
                        <a href="/create-new-project"><button class="btn btn-info btn-raised">create new project</button></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <template v-for="panel in allData.userProjectInfo.projectOverviews">
			        <div class="col-md-4">
			            <div class="panel panel-info">
			                <div class="panel-heading">
			                    {{panel.projectName}}
			                </div>
			                <div class="panel-body">
			                    <div class="col-md-4">
			                        <div class="row">
			                            <h4 class="list-group-item-heading">Effort</h4>
			                        </div>
			                        <div class="row">
			                            <h3 class="text-center vertica">{{Math.floor(panel.current/panel.expected * 100)}}%</h3>
			                        </div>
			                    </div>
			                    <div class="col-md-8 ">
			                        <ul class="list-group project-details" >
			                            <li class="list-group-item">Members</li>
			                            <li class="list-group-item">Total: {{panel.numMembers}}</li>
			                        </ul>
			                    </div>
			                </div>
			                <div class="panel-footer">
			                    <a class="button" href="/projects/{{parseInt(panel.projectId)}}">Manage Project</a>
			                </div>
			            </div>
			        </div>
			</template>
        </div>
	</div>
</div>
</template>
<script>
	 export default{
        data(){
            return{
                allData: {},
                loaded: false
            }
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
            },
            createNewProject(){

            }
        },
        created() {
            this.fetchAllData();
        }
    }
</script>