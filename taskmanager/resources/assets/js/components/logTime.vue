<style>

</style>
<template>

<div class="modal fade" id="{{id}}" tabindex="-1">
    <div class="modal-dialog" role="document">
            <div class="modal-content">


                <div class="modal-header">
                    <button type="button" class="close" v-on:click="closeModal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h3 class="modal-title">Log Time</h3<hr />
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <span class="h4">{{taskName}}</span>
                            <div class="form-group label-static" data-toggle="tooltip" data-placement="bottom" title="Set start time using calendar">
                                <label for="startTime" class="control-label">Start Time</label>
                                <input v-model="startTime" type="text" disabled="disabled" class="form-control text-center" id="i5">
                            </div>
                            <div class="form-group label-floating">
                                <label for="timeToLog" class="control-label">Time to Log</label>
                                <input v-model="timeLogged" type="number" step="0.25" min="0.25" class="form-control text-center" id="timeToLog">
                            </div>
                            <div class="form-group label-floating">
                                <label for="notes" class="control-label">Notes</label>
                                <textarea v-model="notes" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <input v-el:flatpickr value="{{today}}" type="hidden">
                        </div>
                    </div>
                </div><!-- Modal Body -->

                <div class="modal-footer">
                    <hr />
                    <span v-show="showErrors" class="text-warning" style="margin-right:20px;">{{errorHint}}</span>
                    <button type="button" class="btn btn-primary" v-bind:class="errorHint ? 'disabled' : ''" :title="errorHint" v-on:click="save" >Save</button>
                    <button type="button" class="btn btn-secondary" v-on:click="closeModal">Close</button>
                </div>
        </div>
    </div>
</div>
            
</template>

<script>
import flatpickr from 'flatpickr';
export default {
    data () {
        return {
            startTime: this.todayFormattedString(),
            startTimeObject: new Date(),
            timeLogged: "",
            notes: "",
            showErrors: false
        }
     },
     props: [
        "id",
        "taskName",
        "taskTimeLogged",
        "viewTaskModalId",
        "taskHistory",
        "currentUser",
        "users"
     ],
     computed: {
        today: function(){
            return new Date();
        },
        errorHint: function(){
            var error = '';
            if (! this.timeLogged){
                error = "Amount of time to log is required.";
            }
            else {
                if (this.timeLogged <= 0){
                    error = "Amount of time to log must be positive.  "
                }
                if (this.timeLogged % 0.25 !== 0){
                    error += "Time must be in increments of 0.25"
                }
            }

            return error;
        }
     },
     methods: {
        todayFormattedString: function(){
            var today = new Date();
            var MONTHS = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            var todaysDateString = today.getDate() + " ";
            todaysDateString += MONTHS[today.getMonth()] + ", " + today.getFullYear() + "  ";
            var hours = today.getHours() % 12;
            todaysDateString += hours + ":" + today.getMinutes() + " ";
            if (today.getHours() >= 12){
                todaysDateString += "PM";
            } else {
                todaysDateString += "AM";
            }
            return todaysDateString;
        },
        closeModal: function(){
            jQuery('#' + this.id).modal('hide');
            if (this.$parent.needToOpenTaskModal){
                jQuery('#' + this.viewTaskModalId).modal('show');
                this.$dispatch('logTimeOpenedFromTaskModal', false);
            }
            this.refreshContent();
        },
        save: function(){
            if (this.errorHint){
                this.showErrors = true;
                return;
            }
            var startDate = this.startTimeObject.getDate() + "-" + (this.startTimeObject.getMonth() + 1) + "-" + this.startTimeObject.getFullYear();
            var startTime = this.startTimeObject.getHours() + ":" + this.startTimeObject.getMinutes();
            var log = {'startDate': startDate, 'startTime': startTime, 'timeLogged': this.timeLogged, 'user' : this.users[this.currentUser].displayName, 'notes': this.notes};
            if (this.taskHistory == null){
                this.taskHistory = [ log ];
            } else {
                this.taskHistory.push(log);
            }
            this.$dispatch('recalculateTimeLogged', 'true');
            this.closeModal();
        },
        refreshContent: function(){
            this.timeLogged = "";
            this.notes = "";
            this.showErrors = false;
            this.startTime = this.todayFormattedString();
            this.today = new Date();
            this.startTimeObject = new Date();
        }
     },
     ready() {
            var self = this;

            this.flatpickr = flatpickr(this.$els.flatpickr, {
                onChange(dateObject, dateString) {
                    self.startTime = dateString;
                    self.startTimeObject = dateObject;
                },
                defaultDate: this.date,
                inline: true,
                enableTime: true,
                maxDate: self.today,
                dateFormat: "j F, Y  h:iK"
            });

            if (this.date)
                this.$refs.flatpickr.value = this.date;
        }
}

</script>