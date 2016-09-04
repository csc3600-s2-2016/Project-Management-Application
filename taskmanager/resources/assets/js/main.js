

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

import TaskManagementApp from './components/taskManagementApp.vue';




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
  	taskManagementApp: TaskManagementApp
  }
});
