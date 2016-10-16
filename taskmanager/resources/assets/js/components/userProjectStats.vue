<template>
    <div class="list-group-item" style="margin-bottom:30px;">
        <div class="row-action-primary">
            <i class="material-icons">account_box</i>
        </div>
        <div class="row-content" >
            <div class="least-content">UID: {{userId}}</div>
            <span class="list-group-item-heading">{{username}}</span>

            <div class="list-group-item-text">This user has created {{createdTasks}} tasks.</div>

            </div>
        </div>
    </div>
</template>
<style>
</style>
<script>
    export default{
        props : {
            userId: {type: Number, default: 0},
            username: {},
            createdTasks: {}
        },
        data(){
        }
    }
</script>
