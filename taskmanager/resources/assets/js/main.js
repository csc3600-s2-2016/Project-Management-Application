

//add libraries
var Vue = require('vue');
var Sortable = require('vue-sortable');
require('vue-resource');
require('vue-sortable');
global.jQuery = require('jquery');
require('bootstrap');

//Add components
import TaskCard from './components/taskCard.vue';
import NavbarHorizontal from './components/navbarHorizontal.vue';

Vue.use(Sortable);

new Vue({
  el: 'body',
  components: { 
  	taskCard: TaskCard,
  	navbarHorizontal: NavbarHorizontal
  }
});
