

//add libraries
var Vue = require('vue');
var Sortable = require('vue-sortable');
require('vue-resource');
require('vue-sortable');
global.jQuery = require('jquery');
require('flatpickr');
require('bootstrap');
var material = require('bootstrap-material-design');

//Add components
import TaskCard from './components/taskCard.vue';
import DuedateCreator from './components/duedateCreator.vue';
import EstimatedTimeCreator from './components/estimatedTimeCreator.vue';
import AssignUsers from './components/AssignUsers.vue';
import CreateSubtasks from './components/createSubtasks.vue';


Vue.use(Sortable);

new Vue({
  el: 'body',
  ready: function(){
  	jQuery.material.init();
  	jQuery(function () {
  		jQuery('[data-toggle="tooltip"]').tooltip()
	});
  },
  components: { 
  	taskCard: TaskCard,
  	assignUsers: AssignUsers,
  	duedatecreator: DuedateCreator,
  	estimatedTimeCreator: EstimatedTimeCreator,
  	createSubtasks: CreateSubtasks
  }
});
