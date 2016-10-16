<template id="project-status-panel-template">
    <div class="col-md-3">
        <div class="panel panel-info">
            <div class="panel-heading">
                {{projectName}}
            </div>
            <div class="panel-body">
                <div class="col-md-4">
                    <div class="row">
                        <h4 class="list-group-item-heading">Effort</h4>
                    </div>
                    <div class="row">
                        <h3 class="text-center vertica">{{Math.floor(current/expected * 100)}}%</h3>
                    </div>
                </div>
                <div class="col-md-8 ">
                    <ul class="list-group project-details" >
                        <li class="list-group-item">Members</li>
                        <li class="list-group-item">Total: {{members}}</li>
                    </ul>
                </div>
            </div>
            <div class="panel-footer">
                <a class="button" v-if="linkToManage()" href="/project/{{projectId}}">Manage Project</a>
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
            projectId: {type: Number, default: 0, required: true},
            projectName: {type: String, default: "Project Name", required: true},
            current: {type: Number, required: true, default: 0},
            expected: {type: Number, required: true, default: 0},
            members: {type: Number, required: true, default: 1},
            canManage: {type: Number, required: true, default: 0}
        },
        data(){
            return{

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
            },
            linkToManage(){
                if(this.canManage === 1) return true;
                return false;
            }
        }

    }
</script>
