<template>
	<div class="form-group"  style="margin:0px;">
            <div class="input-group">
                <input v-model="subtaskName" type="text" class="form-control" placeholder="Subtask description">
                <div class="input-group-addon">
                    <span class="btn btn-primary btn-xs btn-raised" v-on:click="removeSubtask" data-toggle="tooltip" data-placement="bottom" title="Remove subtask">X</span>
                </div>
            </div>
        </div>
</template>

<script>
	export default {
    data () {
        return {
        	subtaskName: ""
        }
     },
     props: ["id"],
     methods: {
    	removeSubtask: function(){
    		this.$dispatch('deleteSubtask', this.id);
    	}
     }
}
</script>