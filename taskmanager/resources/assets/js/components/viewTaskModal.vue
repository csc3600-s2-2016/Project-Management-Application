<template>
	<div class="modal fade" id="{{id}}" tabindex="-1" >
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <span class="h3 modal-title">{{title}}</span><hr />
      </div>


    <div class="modal-body">  
      <section v-show="description">
        <span class="h3">Task Description:</span>
        <h5>{{description}}</h5>
      </section>
      <div class="row">
        <div class="col-sm-4" v-show="assignedUsers">
            <h3 ><span v-for="user in assignedUsers" class="label label-primary">{{user.displayName}}</span></h3>
        </div>
        <div class="col-sm-4" v-show="dueDate">
          <h3>Due date: {{dueDate}}
        </div>
        <div class="col-sm-4" v-show="timeLogged || timeEstimated">
          <h3 v-show="timeLogged">Time logged: {{timeLogged}}</h3>
          <h3 v-show="timeEstimated">Time estimated: {{timeEstimated}}</h3>
        </div>
      </div>
      <div>
        <ul class="subtaskList" v-sortable="{animation: 250}">
            <li v-for="subtask in task.subtasks">
                {{ subtask.description }}
            </li>
        </ul>
      </div>


    </div>
  </div>
</div>
</template>

<script>
export default {
    data () {
        return {
        }
    },
    props: [
    	"title",
    	"assignedUsers",
    	"subtasks",
    	"description",
    	"timeLogged",
    	"timeEstimated",
    	"dueDate",
    	"id"
    ]
}
</script>