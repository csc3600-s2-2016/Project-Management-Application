<style>

</style>
<template>
        <br />
        <h4>Create Subtasks <button v-on:click="addNewSubtask" class="btn btn-primary btn-xs btn-raised" style="margin-left:20px;">new</button></h4>
        <subtask-input v-for="subtaskID in subtasks" :id="subtaskID"></subtask-input>
            
</template>

<script>
import SubtaskInput from './subtaskInput.vue';
export default {
    data () {
        return {
        	subtasks: []
        }
     },
     methods: {
        addNewSubtask: function(){
            if (this.subtasks.length === 0){
                this.subtasks.push(1)
            } else {
                this.subtasks.push(this.subtasks[this.subtasks.length - 1] + 1);
            }
            
        }
     },
     components: {
        subtaskInput: SubtaskInput
     },
     events: {
        'deleteSubtask': function (subtaskID){
            for (var i = 0; i < this.subtasks.length; i++){
                if (this.subtasks[i] === subtaskID){
                    this.subtasks.splice(i, 1);
                }
            }
        }
     }
}

</script>