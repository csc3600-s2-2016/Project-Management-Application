<style>
  .taskTimeLoggedTable {
    width: 80%;
    margin: auto;
  }
  .taskTimeLoggedTable td {
    width: 50%;
  }
  .taskTimeLoggedTable td:nth-child(2) {
    text-align: right;
  }
  .checkbox-small{
    transform: scale(0.75,0.75);
  }
</style>
<template>

	<div class="modal fade" id="{{id}}" tabindex="-1" >
  <div class="modal-dialog" :class="modalSize" role="document">
    <div class="modal-content">
      

      <div class="modal-header">
        
        
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <div class="row">
          <div class="col-sm-8">
            <span class="h3 modal-title">{{task.name}}</span>
          </div>
          
        </div>
      </div>


    <div class="modal-body">  
    <div class="row">



    <section class="col-md-8" v-show="task.description || task.subtasks">
      <div v-show="task.description">
        <h5>{{task.description}}</h5>
      </div><br />

      <div>
      <br />
      



      <div class="h4" v-show="task.subtasks">Subtasks:</div>
        <table style="width:100%;">
        <tbody v-sortable="subtaskSortableOptions">
          <tr v-for="subtask in task.subtasks" >
            <td style="width:30px;">
                <div class="checkbox checkbox-small">
                  <label>
                    <input type="checkbox" v-model="subtask.complete">
                  </label>
                </div>
            </td>
            <td>
                {{ subtask.description }}
            </td>
            <td v-show="task.subtasks.length > 1" class="sort-handle text-right" style="width:20px;">
              <span class="fa fa-sort"></span>
            </td>
          </tr>
          </tbody>
        </table>
    </section>




    <section class="{{ modalIsLarge ? 'col-md-4' : 'col-md-12' }}" :style=" modalIsLarge ? 'border-left:1px solid #ddd;' : '' ">
       
          <table class="taskTimeLoggedTable" v-show="task.dueDate || task.timeLogged || task.timeEstimated" style="max-width:200px;">
            <tr v-show="task.dueDate">
              <td><span class="h5">Due:</span></td>
              <td><span class="h3" :class="pastDueDate ? 'text-warning' : '' ">  {{shortDueDate}}</span></td>
          </div>
              <td></td>
            </tr>
            <tr v-show="task.timeLogged">
              <td><span class="h5" v-show="task.timeLogged">Logged:</span></td>
              <td><span class="h3" :class="loggedTimeOverBudget ? 'text-warning' : '' ">{{task.timeLogged}}</span></td>
            </tr>
            <tr v-show="task.timeEstimated">
              <td><span class="h5"  v-show="task.timeEstimated">Estimate: </span></td>
              <td><span class="h3">{{task.timeEstimated}}</span></td>
            </tr>
          </table>
          <br /><br />
          




          <div v-show="task.assignedUsers" class="text-center" style="margin-top:10px;">
            <h3 ><span v-for="userID in task.assignedUsers" class="label label-username">{{users[userID].displayName}}</span></h3>
          </div>

          <div class="text-center" style="margin-top:30px;">
            <a v-on:click="showLogTimeModal">
              <button class="btn btn-primary btn-raised"><span class="fa fa-clock-o"></span> Log Time</button>
            </a>
          </div>



    </section>

    
  </div>
  
  <section v-show="task.loggedTimeHistory">
  <br /><hr />
      <table style="width:100%;" class="table table-striped table-hover timeLoggedTable">
        <thead>
          <tr>
            <th>Start Date</th>
            <th>Start Time</th>
            <th>Time Logged</th>
            <th>Team Member</th>
            <th>Notes</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="log in task.loggedTimeHistory">
            <td>{{log.startDate}}</td>
            <td>{{log.startTime}}</td>
            <td>{{log.timeLogged}}</td>
            <td>{{log.user}}</td>
            <td>{{log.notes}}</td>
          </tr>
        </tbody>
      </table>
      <hr />
    </section>

    </div>
  </div>
</div>
</template>

<script>
export default {
    data () {
        return {
          subtaskSortableOptions: {
            animation: 250,
            handle: ".sort-handle",
            ghostClass: "ghostclass"
          }
        }
    },
    props: [
      "task",
    	"id",
      "users",
      "logTimeId"
    ],
    computed: {
      modalSize: function(){
        if (this.task.description || this.task.subtasks){
          return "modal-lg";
        }
        return "";
      },
      modalIsLarge: function(){
        return this.modalSize ? true : false;
      },
      shortDueDate: function(){
        if (!this.task.dueDate){
          return '';
        }
        var parts = this.task.dueDate.split("-");
        var shortDate = parts[0] + "-";
        if (parts[1].length == 1){
          shortDate += "0"
        }
        shortDate += parts[1] + "-";
        if (parts[2].length === 4){
          return shortDate + parts[2].substring(2);
        }
        return shortDate + parts[2];
      },
      pastDueDate : function(){
            if (!this.task.dueDate){
                return false;
            }
            var parts = this.task.dueDate.split('-');
            var today = new Date();
            if (parts[2] < today.getFullYear()) {
                return true;
            }
            if (parts[2] > today.getFullYear()) {
                return false;
            }
            if (parts[1] < today.getMonth() + 1){
                return true;
            }
            if (parts[1] > today.getMonth() + 1){
                return false;
            }
            if (parts[0] <= today.getDate()){
                return true;
            }
            return false;
        },
        loggedTimeOverBudget : function(){
            if (this.task.timeLogged && this.task.timeEstimated){
                if( parseInt(this.task.timeLogged) > parseInt(this.task.timeEstimated) ){
                  return true;
                }
            }
            return false;
        }
    },
    methods: {
      showLogTimeModal: function(){
        this.$dispatch('logTimeOpenedFromTaskModal', true);
        jQuery("#" + this.id).modal('hide');
        jQuery('#' + this.logTimeId).modal('show');
      }
    },
    ready: function(){
      jQuery.material.checkbox();
    }
}
</script>