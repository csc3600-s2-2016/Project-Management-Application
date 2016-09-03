<template>

            
            <!-- <div class="row">
              <div class="checkbox">
                <label>
                  <input type="checkbox" v-model="addEstimatedTime"> Add estimated time
                </label>
              </div>
            </div>
            <form class="form-horizontal">
			<div class="row" v-show="addEstimatedTime">
                <label for="exampleInputEmail1" class="col-sm-6 h5">Estimated Time:</label>
                <div class=" col-sm-5">
                    <input type="number" class="form-control" placeholder="Hrs" min="0" step="1">
                </div>
            </div>
            </form> -->

            <div class="form-group">
            <label class="control-label">Estimated Time: </label>
            <div class="input-group">
                
                    <input v-model="estimatedTime" type="number" min='0' step='1' id="addon1" class="form-control" placeholder="No estimated time">
                
                <div class="input-group-addon">
                    <span class="btn btn-primary btn-xs btn-raised" v-on:click="estimatedTime = 0" data-toggle="tooltip" data-placement="bottom" title="Remove time estimation from task">X</span>
                </div>
            </div>
            
</template>

<script>
import Datepicker from './datepicker.vue';
export default {
    data () {
        return {
        	addEstimatedTime: false,
            estimatedTime: ""
        }
     },
     methods: {
        resetEstimatedTime: function(){
            this.estimatedTime = "";
        }
     }
}

</script>