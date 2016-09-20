<template>
            <div class="form-group">
            <label class="control-label">Estimated Time: </label>
            <div class="input-group">
                
                    <input v-model="estimatedTime" type="number" min='0' step='1' id="addon1" class="form-control" placeholder="No estimated time">
                
                <div class="input-group-addon">
                    <span class="btn btn-primary btn-xs btn-raised" v-on:click="estimatedTime = 0" data-toggle="tooltip" data-placement="bottom" title="Remove time estimation from task">&times;</span>
                </div>
            </div>
            </div>
</template>

<script>
export default {
    props: [
        "estimatedTime"
    ],
    data () {
        return {
        	addEstimatedTime: false,
        }
     },
     methods: {
        resetEstimatedTime: function(){
            this.estimatedTime = "";
        }
     }
}

</script>