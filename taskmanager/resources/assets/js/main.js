

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
var ReviewApp = require('./components/reviewApp.vue');
global.Chart = require('chart.js');

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
  	taskManagementApp: TaskManagementApp,
    reviewApp: ReviewApp
  }
});


/**
*
*  Reporting
*
**/






// //Chart Selector
// var chartTypeSelect;

// $(document).ready(function() {
//     initialLoad();

//   $("#toggle").click(function () {
//     $("#reviewWrapper").toggleClass("menuActive");
//       });

//   $("#projectMenu").click(function () {
//     $("#projectSub").toggleClass("projectActivate");
//       });

//   $("#taskMenu").click(function () {
//     $("#taskSub").toggleClass("taskActivate");
//       });

//   $("#userMenu").click(function () {
//     $("#userSub").toggleClass("userActivate");
//       });


// //build reports
// $("#projectSubComplete").click(function () {
//         var data = $(this).html();
//         $('h1').html(data);
// });

// $("#projectSubProgress").click(function () {
//         var data = $(this).html();
//         $('h1').html(data);
// });


// $("#projectSubUser").click(function () {
//         var data = $(this).html();
//         $('h1').html(data);
// });

// $("#projectSubTask").click(function () {
//         var data = $(this).html();
//         $('h1').html(data);
// });

// $("#taskSubTimeLog").click(function () {
//         var data = $(this).html();
//         $('h1').html(data);
// });

// $("#taskSubOverall").click(function () {
//         var data = $(this).html();
//         $('h1').html(data);
// });

// $("#taskSubCrit").click(function () {
//         var data = $(this).html();
//         $('h1').html(data);
// });

// $("#userSubTime").click(function () {
//         var data = $(this).html();
//         $('h1').html(data);
// });

// $("#userSubTask").click(function () {
//         var data = $(this).html();
//         $('h1').html(data);
// });
      
//  $("#userSubOverdue").click(function () {
//         var data = $(this).html();
//         $('h1').html(data);
// });       


// //update charts

//   $('#DataGrid').click(function() {
//     if($('#DataGrid').is(':checked')) { 
//       noChart();
//     }
//   });

//   $('#pie').click(function() {
//     if($('#pie').is(':checked')) { 
//       chartTypeSelect = 'pie';
//       reBuildChart();
//     }
//   });

//   $('#line').click(function() {
//     if($('#line').is(':checked')) { 
//       chartTypeSelect = 'line';
//       //rebuild chart
//       reBuildChart();
//     }
//   });

//   $('#bar').click(function() {
//     if($('#bar').is(':checked')) { 
//       chartTypeSelect = 'bar';
//       //rebuild chart
//       reBuildChart();
//     }
//   });

//     $('#radar').click(function() {
//     if($('#radar').is(':checked')) { 
//       chartTypeSelect = 'radar';
//       //rebuild chart
//       reBuildChart();
//     }
//   });
 
// });


// function reBuildChart(){
//   var reportCanvas = '<canvas id="displayChart"></canvas>';
//   $('#reportDisplay').html(reportCanvas);
//         console.log(document.getElementById("displayChart"));
//         new Chart(document.getElementById("displayChart"), {
//               type: chartTypeSelect,
//               data: data});
// }

// function initialLoad(){
//     var reportCanvas = 'Please select a report from the menu';

//       $('#reportDisplay').html(reportCanvas);
//   }

// function noChart(){
//     var reportCanvas = 'Please select a report from the menu';

//       $('#reportDisplay').html(reportCanvas);
//   }

// //Chart Data
// var data = {
//     labels: ["January", "February", "March", "April", "May", "June", "July"],
//     datasets: [
//         {
//             label: "Completed Tasks By Month",
//             fill: false,
//             lineTension: 0.1,
//             backgroundColor: "rgba(75,192,192,0.4)",
//             borderColor: "rgba(75,192,192,1)",
//             borderCapStyle: 'butt',
//             borderDash: [],
//             borderDashOffset: 0.0,
//             borderJoinStyle: 'miter',
//             pointBorderColor: "rgba(75,192,192,1)",
//             pointBackgroundColor: "#fff",
//             pointBorderWidth: 1,
//             pointHoverRadius: 5,
//             pointHoverBackgroundColor: "rgba(75,192,192,1)",
//             pointHoverBorderColor: "rgba(220,220,220,1)",
//             pointHoverBorderWidth: 2,
//             pointRadius: 1,
//             pointHitRadius: 10,
//             data: [5, 15, 13, 19, 21, 29, 40],
//             spanGaps: false,
//         }
//     ]
// };