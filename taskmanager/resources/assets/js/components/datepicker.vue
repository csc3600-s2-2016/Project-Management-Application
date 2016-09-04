<template>

            
            <input v-el:flatpickr type="hidden">
           <!--  <div class="row">
                <div class="col-sm-5">
                <h4>Due Date</h4>
                </div>
                <div class="col-sm-5">
                <input type="text" name="dueDate" class="form-control" v-model="dueDate">
                </div>
            </div> -->

            
            <br />
            <div class="form-group">
            <label class="control-label">Due Date: </label>
            <div class="input-group">
                <div data-toggle="tooltip" data-placement="bottom" title="Set due date with calendar above">
                    <input v-model="dueDate" type="text" disabled="disabled" id="addon1" class="form-control" placeholder="No Due Date">
                </div>
                <div class="input-group-addon">
                    <span class="btn btn-primary btn-xs btn-raised" v-on:click="dueDate='' " data-toggle="tooltip" data-placement="bottom" title="Remove due date from task">&times;</span>
                </div>
            </div>
          

</template>

<script>
    import flatpickr from 'flatpickr';

    export default {
        props: {
            
        },

        ready() {
            var self = this;

            this.flatpickr = flatpickr(this.$els.flatpickr, {
                onChange(dateObject, dateString) {
                    self.dueDate = dateString;
                },
                defaultDate: this.date,
                inline: true,
                enableTime: true
            });

            if (this.date)
                this.$refs.flatpickr.value = this.date;
        },

        data() {
            return {
                flatpickr: null,
                dueDate: ""
            }
        },

        computed: {

        },

        methods: {

        },

        events: {

        }
    }
</script>