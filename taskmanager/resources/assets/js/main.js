

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
import UserProfile from './components/userProfile.vue';
import ProjectStatusPanel from './components/projectStatusPanel.vue';
import UserDetails from './components/userDetails.vue';
import UserContributions from './components/userContributions.vue';
Vue.use(Sortable);

new Vue({
  el: 'body',
  components: { 
  	taskCard: TaskCard,
  	navbarHorizontal: NavbarHorizontal,
    userProfile : UserProfile,
    projectStatusPanel: ProjectStatusPanel,
    userDetails: UserDetails,
    userContributions: UserContributions
  }
});
