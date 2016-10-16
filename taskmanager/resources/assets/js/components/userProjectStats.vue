<template>
    <div class="list-group-item">
        <div class="row-action-primary">
            <i class="material-icons">account_box</i>
        </div>
        <div class="row-content">
            <div class="least-content">15m</div>
            <h4 class="list-group-item-heading">{{username}}</h4>

            <p class="list-group-item-text">Donec id elit non mi porta gravida at eget metus.</p>
        </div>
    </div>
</template>
<style>
</style>
<script>
    export default{
        props : {
            userId: {type: Number, default: 0}
        },
        data(){
            return{
            }
        },
    }
</script>
