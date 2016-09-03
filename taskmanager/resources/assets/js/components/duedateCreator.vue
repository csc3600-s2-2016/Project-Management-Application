<template>

            
            <!-- <div class="row">
              <div class="checkbox">
                <label>
                  <input type="checkbox" v-model="addDueDate"> Add a due date
                </label>
              </div>
            </div>
			<datepicker v-if="addDueDate"></datepicker> -->
			<datepicker></datepicker>
            
</template>

<script>
import Datepicker from './datepicker.vue';
export default {
    data () {
        return {
        	addDueDate: false
        }
     },
     components: {
     	datepicker: Datepicker
     }
}

</script>