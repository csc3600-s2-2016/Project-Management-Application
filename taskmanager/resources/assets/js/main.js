

//add libraries
var Vue = require('vue');
var Sortable = require('vue-sortable');
var VDragableFor = require('vuedragablefor');
var VueResoure = require('vue-resource');
var VueSortable = require('vue-sortable');
global.jQuery = require('jquery');
var Flatpickr = require('flatpickr');
var Bootstrap = require('bootstrap');
var Material = require('bootstrap-material-design');
global.toastr = require('toastr');



import TaskManagementApp from './components/taskManagementApp.vue';


Vue.use(Sortable);
Vue.use(VDragableFor);
Vue.use(VueResoure);


Vue.http.headers.common['X-CSRF-TOKEN'] = jQuery('meta[name="csrf-token"]').attr('content');

toastr.options.preventDuplicates = true;
toastr.options.positionClass = 'toast-bottom-left';
toastr.options.timeOut = 6000;



var taskApp = new Vue({
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
