var Vue = require('vue');
var Root = require('./components/Root.js');

new Vue({
  el: '#root',
  components: { Root },
  ready () {
      //alert("Vue && vueify is working");
  }
});
