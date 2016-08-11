var Vue = require('vue');

var Root = Vue.extend({
  template: `
    <div class='root'>
      <h1> {{ appName }} </h1>
    </div>
  `,
  data () {
    return {
      appName: "Task Manager"  
    };
  }
  // options...

})
Vue.component('root', Root);
