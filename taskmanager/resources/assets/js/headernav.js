var Vue = require('vue');
var HorizontalNav = require('./components/HorizontalNav.js');

new Vue({
  el: '#headernav',
  components: { HorizontalNav },
  ready () {
      //alert("Vue && vueify is working");
  }
});
