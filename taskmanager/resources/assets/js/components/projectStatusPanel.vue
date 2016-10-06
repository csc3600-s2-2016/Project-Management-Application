<template id="project-status-panel-template">
    <div class="col-md-3">
        <div class="panel panel-info">
            <div class="panel-heading">
                Project Name
            </div>
            <div class="panel-body">
                <div class="col-md-4">
                    <div class="row">
                        <h4 class="list-group-item-heading">Effort {{current}}/{{expected}}</h4>
                    </div>
                    <div class="row">
                        <h3 class="text-center vertica">45%</h3>
                    </div>
                </div>
                <div class="col-md-8 ">
                    <ul class="list-group project-details" >
                        <li class="list-group-item">Members {{memberCount}}</li>
                        <li class="list-group-item">Active Members 6</li>
                        <li class="list-group-item">Inactive Members 24</li>
                    </ul>
                </div>
            </div>
            <div class="panel-footer">
                <a class="button" href="#/manage/project/12345">Manage Project</a>
            </div>
        </div>
    </div>
</template>
<style>
    .manage {
        margin: 0px;
    }
    .manage:hover {
        border:0.1em solid inset #eee;
    }
</style>
<script>
    export default{
        props: {
            used: {type: Number, default: 0},
            avail: {type: Number, default: 0},
            projectId: {type: Number, default: 0}
        },
        data(){
            return{
                msg:'pong'
            }
        },
        methods: {
            criticalClass() {
                var percEffortAvail = 1 - (this.used / this.avail);
                if(percEffortAvail <= 0.05) {
                    return "danger";
                }else if( percEffortAvail < 0.6){
                    return "warning";
                }else {
                    return "success";
                }
            }
        }
    }
</script>
