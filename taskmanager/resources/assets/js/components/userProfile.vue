<template id="user-profile-template">
    <div class="scroll-x">
    <div class="well col-sm-4">
            <h4>Projects</h4>
            <table class="table table-striped table-hover ">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Project Effort Status</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                    <tr is="project-item" :used="20" :avail="100" :project-id="200"></tr>
                    <tr is="project-item" :used="90" :avail="100" :project-id="201"></tr>
                    <tr is="project-item" :used="45" :avail="100" :project-id="202"></tr>
                    <tr is="project-item" :used="30" :avail="100" :project-id="203"></tr>
                </tbody>
            </table>
    </div>
    <user-details style="float: right;"></user-details>
    <user-details></user-details>
</template>
<style>
    .well {
        margin: 0.1em;
        padding: 0.1em;
    }
    body{
        background-color:#e5e5e5;
    }
    .scroll-x {
        overflow-x: scroll;
        width:200%;
    }
    .form-group {
        padding-left: 10px;
    }
</style>
<script>
    import ProjectItem from "./projectStatusPanel.vue";
    import UserDetails from "./userDetails.vue";
    export default{
        props: {
            userEmail:{type: String, default: "john.doe@mail.com.au"}
        },
        data(){
            return{
                msg:'hello vue'
            }
        },
        components: {
            'project-item': ProjectItem,
            'user-details': UserDetails
        }
    }
</script>
