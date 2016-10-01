<style>

</style>
<template>
    <div>
        <br />
        <h4>
            Create Subtasks 
            <button v-on:click.prevent="addNewSubtask" class="btn btn-primary btn-xs btn-raised" style="margin-left:20px;">
                new
            </button>
        </h4>


        <template v-for="subtask in subtasks" :key="subtask.priority">
            <div class="form-group"  style="margin:0px;">
                    <div class="input-group">
                        <input v-model="subtask.name" type="text" class="form-control" placeholder="Subtask name">
                        <div class="input-group-addon">
                            <span class="btn btn-primary btn-xs btn-raised" v-on:click="removeSubtask($index)" data-toggle="tooltip" data-placement="bottom" title="Remove subtask">&times;</span>
                        </div>
                    </div>
                </div>
        </template>
    </div>     
</template>

<script>
export default {
    data () {
        return {
            
        }
     },
     props: ["subtasks"],
     computed: {
     },
     methods: {
        addNewSubtask: function(){
            
            var nextIndex;

            if (this.subtasks != null){
                nextIndex = this.subtasks.length;

            } else {
                this.subtasks = new Array();
                nextIndex = 0;
            }

            var subtask = {'priority': nextIndex, 'name' : '', 'complete': false, 'tempID': this.randomID()};
            this.subtasks.push(subtask);
            this.subtasks = this.subtasks.slice();
        },
        removeSubtask: function(index){
            this.subtasks.splice(index, 1);
            this.subtasks = this.subtasks.slice();
            //redo priorities
            for (var i = 0; i<this.subtasks.length; i++){
                this.subtasks[i].priority = i;
            }
            this.subtasks = this.subtasks.slice();

        },
        randomID: function(){
            var identifierLength = 8;
            var identifier = "";
            var possible = "ABCDEFGHIFJLMNOPQRSTUVWXYZ";
            for(var i = 0; i < identifierLength; i++) {
                identifier += possible.charAt(Math.floor(Math.random() * possible.length));
            }
            return identifier;
        }
     },
     events: {
        'savingTask': function(removeEmptySubtasks){
            if (!this.subtasks){
                this.subtasks = [];
                return;
            }

            var priority = 0;
            for (var i = 0; i<this.subtasks.length; i++){
                if (this.subtasks[i].name.trim().length > 0){
                    this.subtasks[i].priority = priority++;
                } else {
                    this.subtasks.splice(i, 1);  //remove subtasks with empty name
                }
            }
        } 
     }
}

</script>