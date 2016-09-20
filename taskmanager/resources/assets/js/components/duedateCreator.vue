<template>
		
        <div>
            <input v-due-date-picker="dueDate" type="hidden">

            
            <br />
            <div class="form-group">
            <label class="control-label">Due Date: </label>
            <div class="input-group">
                <div data-toggle="tooltip" data-placement="bottom" title="Set due date with calendar above">
                    <input v-model="dueDateString" type="text" disabled="disabled" id="addon1" class="form-control" placeholder="No Due Date">
                </div>
                <div class="input-group-addon">
                    <span class="btn btn-primary btn-xs btn-raised" v-on:click="dueDate=null " data-toggle="tooltip" data-placement="bottom" title="Remove due date from task">&times;</span>
                </div>
            </div>
          </div>
            
</template>

<script>
import flatpickr from 'flatpickr';
export default {
	props: ["dueDate"],
	computed:{
		dueDateString: function(){
			if (this.dueDate){
				return (this.dueDate.getDate() + "-" + (this.dueDate.getMonth() + 1) + "-" + this.dueDate.getFullYear());
			}
			return "";		
		}
	},
	directives: {
		dueDatePicker: {
			twoWay: true,
			update: function(newval, oldval){
				var that = this;
				var datepicker = flatpickr(this.el, {
		                onChange(dateObject, dateString) {
		                    that.set(dateObject);
		                },
		                defaultDate: newval,
		                inline: true,
		                enableTime: false
		            });
				if (newval === null){
					datepicker.clear();
				}
			}
		}
	}
}

</script>