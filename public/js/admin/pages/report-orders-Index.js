(window.webpackJsonp=window.webpackJsonp||[]).push([[10],{320:function(t,e,a){"use strict";a.r(e);var r=a(497),o=a(10),n=a(5);function s(t,e){var a=Object.keys(t);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(t);e&&(r=r.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),a.push.apply(a,r)}return a}function i(t,e,a){return e in t?Object.defineProperty(t,e,{value:a,enumerable:!0,configurable:!0,writable:!0}):t[e]=a,t}var d={name:"ReportIndex",data:function(){var t;return{topvendors:[],webDataCounts:[],model:new r.a,from:"",to:"",filter_from:"",filter_to:"",filter:null!==(t=this.$route.params.filter)&&void 0!==t?t:"today",order_pie_data:[],backgroundColor:["rgba(255, 99, 132, 0.2)","rgba(54, 162, 235, 0.2)","rgba(255, 206, 86, 0.2)","rgba(75, 192, 192, 0.2)","rgba(153, 102, 255, 0.2)","rgba(255, 159, 64, 0.2)"],borderColor:["rgba(255, 99, 132, 1)","rgba(54, 162, 235, 1)","rgba(255, 206, 86, 1)","rgba(75, 192, 192, 1)","rgba(153, 102, 255, 1)","rgba(255, 159, 64, 1)"],trendingProductsData:[],orderPlaces:[]}},methods:{numberFormat:function(t,e){var a=new Intl.NumberFormat("en-IN",{style:"decimal",currency:e||"NPR"}).format(t);return a.includes("NaN")?"Rs "+t:"Rs "+a},filterData:function(t){this.from="",this.to="",this.filter=t,this.loadData()},orderDoughnutData:function(){$("#orderBriefChart").remove(),$("#order-brief-doughnut-container").append('<canvas id="orderBriefChart" width="100" height="100"><canvas>');var t=document.getElementById("orderBriefChart");new Chart(t,{type:"doughnut",data:{labels:["Total Orders","Delivered","Takeaway","Settled"],datasets:[{label:this.filter,data:this.order_pie_data,backgroundColor:this.backgroundColor,borderColor:this.borderColor,borderWidth:1}]},options:{responsive:!0,plugins:{legend:{position:"bottom"},title:{display:!1,text:this.filter.replace(/-/g," ")}}}})},paymentModeData:function(){$("#paymentModeChart").remove(),$("#payment-mode-bar-container").append('<canvas id="paymentModeChart" width="100" height="100"><canvas>');var t=document.getElementById("paymentModeChart");new Chart(t,{type:"bar",data:{datasets:[{label:this.filter.replace(/-/g," ").toUpperCase(),data:this.webDataCounts.payment_mode,backgroundColor:this.backgroundColor,borderColor:this.borderColor,borderWidth:1}]},options:{parsing:{xAxisKey:"payment_mode",yAxisKey:"paid_total"},responsive:!0,plugins:{legend:{position:"bottom"},title:{display:!1}}}})},orderStatusRaderData:function(){$("#orderStatusChart").remove(),$("#order-status-radar-container").append('<canvas id="orderStatusChart" width="100" height="100"><canvas>');var t=document.getElementById("orderStatusChart");new Chart(t,{type:"bar",data:{datasets:[{label:this.filter.replace(/-/g," ").toUpperCase(),data:this.webDataCounts.order_status,backgroundColor:this.backgroundColor,borderColor:this.borderColor,borderWidth:1}]},options:{parsing:{xAxisKey:"status",yAxisKey:"status_total"},responsive:!0,plugins:{legend:{position:"bottom"},title:{display:!1}}}})},trendingProductsDataTable:function(){$.fn.DataTable.isDataTable("#trendingProductsTable")&&$("#trendingProductsTable").DataTable().destroy(),$("#trendingProductsTable").DataTable({buttons:!1,paging:!1,pageLength:20,data:this.trendingProductsData,order:[[2,"desc"]],columns:[{data:"name"},{data:"vendor"},{data:"totalQty",render:$.fn.DataTable.render.number(",")},{data:"productPrice",render:$.fn.DataTable.render.number(",",".",2,"Rs ")},{data:"avgSellPrice",render:$.fn.DataTable.render.number(",",".",2,"Rs ")},{data:"totalRev",render:$.fn.DataTable.render.number(",")}]})},reloadCounts:function(t){t.target.classList.add("fa-spin"),location.href=n.X},listItems:function(){this.topvendors=this.webDataCounts.top_vendors,this.orderPlaces=this.webDataCounts.order_markers;var t=new google.maps.Map(document.getElementById("myMap"),{zoom:10,center:{lat:27.733697229707158,lng:85.34125707301315}});this.setMarkers(t)},checkDate:function(){var t=this;if(""!=this.from&&""!=this.to){this.filter="custom",this.filter_from=this.from,this.filter_to=this.to;axios.get(this.model.indexUrl+"/order-report?q=custom&from="+this.from+"&to="+this.to).then((function(e){t.webDataCounts=e.data.data,t.order_pie_data=[t.webDataCounts.orders,t.webDataCounts.delivered,t.webDataCounts.takeaway,t.webDataCounts.settled],t.trendingProductsData=t.webDataCounts.top_products,t.orderDoughnutData(),t.paymentModeData(),t.orderStatusRaderData(),t.trendingProductsDataTable(),t.listItems()}))}},loadData:function(){var t=this,e=this.filter;axios.get(this.model.indexUrl+"/order-report?q="+e).then((function(e){t.webDataCounts=e.data.data,t.order_pie_data=[t.webDataCounts.orders,t.webDataCounts.delivered,t.webDataCounts.takeaway,t.webDataCounts.settled],t.trendingProductsData=t.webDataCounts.top_products,t.orderDoughnutData(),t.paymentModeData(),t.orderStatusRaderData(),t.trendingProductsDataTable(),t.listItems()}))},setMarkers:function(t){for(var e={url:"https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png",size:new google.maps.Size(20,32),origin:new google.maps.Point(0,0),anchor:new google.maps.Point(0,32)},a={coords:[1,1,1,20,18,20,18,1],type:"poly"},r=0;r<this.orderPlaces.length;r++){var o=this.orderPlaces[r];new google.maps.Marker({position:{lat:parseFloat(o.lat),lng:parseFloat(o.long)},map:t,icon:e,shape:a,title:o.location,zIndex:r+1})}}},computed:function(t){for(var e=1;e<arguments.length;e++){var a=null!=arguments[e]?arguments[e]:{};e%2?s(Object(a),!0).forEach((function(e){i(t,e,a[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(a)):s(Object(a)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(a,e))}))}return t}({},Object(o.mapGetters)(["homePageCounts","authUser"])),mounted:function(){this.$route.params[0]&&alertMessage("404, Page not found !!!","danger")},created:function(){this.loadData()}},l=a(1),c=Object(l.a)(d,(function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",[a("div",{staticStyle:{"margin-bottom":"10px",display:"flex"}},[a("ul",{staticClass:"nav nav-pills nav-pills-warning"},[a("li",{class:"today"===t.filter?"active":"nav-item"},[a("a",{staticClass:"nav-link",attrs:{href:"#"},on:{click:function(e){return t.filterData("today")}}},[t._v("Today ")])]),t._v(" "),a("li",{class:"yesterday"===t.filter?"active":"nav-item"},[a("a",{staticClass:"nav-link",attrs:{href:"#"},on:{click:function(e){return t.filterData("yesterday")}}},[t._v("Yesterday ")])]),t._v(" "),a("li",{class:"this-week"===t.filter?"active":"nav-item"},[a("a",{staticClass:"nav-link",attrs:{href:"#"},on:{click:function(e){return t.filterData("this-week")}}},[t._v("This Week ")])]),t._v(" "),a("li",{class:"this-month"===t.filter?"active":"nav-item"},[a("a",{attrs:{"aria-current":"true",href:"#"},on:{click:function(e){return t.filterData("this-month")}}},[t._v("This Month ")])]),t._v(" "),a("li",{class:"custom"===t.filter?"active":"nav-item"},[a("a",{attrs:{"aria-current":"true",href:"#","aria-disabled":"disabled",title:"Please Select Date"}},[t._v("Custom ")])])]),t._v(" "),a("div",{staticClass:"input-group"},[t._m(0),t._v(" "),a("input",{directives:[{name:"model",rawName:"v-model",value:t.from,expression:"from"}],staticClass:"form-control",attrs:{type:"date",id:"datetimepicker6","aria-describedby":"basic-addon1"},domProps:{value:t.from},on:{input:[function(e){e.target.composing||(t.from=e.target.value)},t.checkDate]}})]),t._v(" "),a("div",{staticClass:"input-group"},[t._m(1),t._v(" "),a("input",{directives:[{name:"model",rawName:"v-model",value:t.to,expression:"to"}],staticClass:"form-control",attrs:{type:"date",id:"datetimepicker7","aria-describedby":"basic-addon2"},domProps:{value:t.to},on:{input:[function(e){e.target.composing||(t.to=e.target.value)},t.checkDate]}})])]),t._v(" "),a("div",{staticClass:"row"},[a("div",{staticClass:"col-md-8"},[a("div",{staticClass:"col"},["support"!==t.authUser.type&&"officer"!==t.authUser.type?a("app-card",{attrs:{title:"Top Selling Products"}},[a("div",{staticClass:"table-responsive"},[a("table",{staticClass:"table table-hover table-striped table-bordered table-lg",attrs:{id:"trendingProductsTable",width:"100%"}},[a("thead",[a("tr",[a("th",{attrs:{width:"20%"}},[t._v("Title")]),t._v(" "),a("th",{attrs:{width:"20%"}},[t._v("Vendor")]),t._v(" "),a("th",[t._v("Sales Qty")]),t._v(" "),a("th",[t._v("Unit Price")]),t._v(" "),a("th",[t._v("Selling Price")]),t._v(" "),a("th",[t._v("Revenue")])])])])])]):t._e()],1),t._v(" "),a("div",{staticClass:"col"},["support"!==t.authUser.type&&"officer"!==t.authUser.type?a("app-card",{attrs:{title:"Top Vendors"}},[a("ul",{staticClass:"list-group-flush list-group"},t._l(t.topvendors,(function(e){return a("li",{key:e.vendor_id,staticClass:"list-group-item d-flex justify-content-between align-items-center list-group-item-action",attrs:{waves:""}},[a("span",[t._v(t._s(e.vendor.business_name))]),t._v(" "),a("span",{staticClass:"badge badge-pill"},[t._v("\n                "+t._s(t.numberFormat(e.order_total/100))+"\n              ")]),t._v(" "),a("span",{staticClass:"badge badge-pill"},[t._v("\n                "+t._s(e.orders)+" orders\n              ")])])})),0)]):t._e()],1),t._v(" "),a("div",{staticClass:"col"},["support"!==t.authUser.type&&"officer"!==t.authUser.type?a("app-card",{attrs:{title:"Order Location Marker"}},[a("div",{staticClass:"z-depth-1-half map-container",staticStyle:{height:"300px"},attrs:{id:"myMap"}})]):t._e()],1)]),t._v(" "),a("div",{staticClass:"col-md-4"},[a("div",{staticClass:"col"},["support"!==t.authUser.type&&"officer"!==t.authUser.type?a("app-card",{attrs:{title:"Order Brief ",width:"400",height:"400"}},[a("div",{attrs:{id:"order-brief-doughnut-container"}},[a("canvas",{attrs:{id:"orderBriefChart"}})])]):t._e()],1),t._v(" "),a("div",{staticClass:"col"},["support"!==t.authUser.type&&"officer"!==t.authUser.type?a("app-card",{attrs:{title:"Order Status"}},[a("div",{attrs:{id:"order-status-radar-container"}},[a("canvas",{attrs:{id:"orderStatusChart"}})])]):t._e()],1),t._v(" "),a("div",{staticClass:"col"},["support"!==t.authUser.type&&"officer"!==t.authUser.type?a("app-card",{attrs:{title:"Payment Mode"}},[a("div",{attrs:{id:"payment-mode-bar-container"}},[a("canvas",{attrs:{id:"paymentModeChart"}})])]):t._e()],1)])])])}),[function(){var t=this.$createElement,e=this._self._c||t;return e("span",{staticClass:"input-group-addon",attrs:{id:"date-addon1"}},[e("strong",[this._v("From")])])},function(){var t=this.$createElement,e=this._self._c||t;return e("span",{staticClass:"input-group-addon",attrs:{id:"date-addon2"}},[e("strong",[this._v("To")])])}],!1,null,null,null);e.default=c.exports},497:function(t,e,a){"use strict";a.d(e,"a",(function(){return c}));var r=a(6),o=a(5);function n(t){return(n="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t})(t)}function s(t,e){return(s=Object.setPrototypeOf||function(t,e){return t.__proto__=e,t})(t,e)}function i(t){var e=function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Date.prototype.toString.call(Reflect.construct(Date,[],(function(){}))),!0}catch(t){return!1}}();return function(){var a,r=l(t);if(e){var o=l(this).constructor;a=Reflect.construct(r,arguments,o)}else a=r.apply(this,arguments);return d(this,a)}}function d(t,e){return!e||"object"!==n(e)&&"function"!=typeof e?function(t){if(void 0===t)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return t}(t):e}function l(t){return(l=Object.setPrototypeOf?Object.getPrototypeOf:function(t){return t.__proto__||Object.getPrototypeOf(t)})(t)}var c=function(t){!function(t,e){if("function"!=typeof e&&null!==e)throw new TypeError("Super expression must either be null or a function");t.prototype=Object.create(e&&e.prototype,{constructor:{value:t,writable:!0,configurable:!0}}),e&&s(t,e)}(a,t);var e=i(a);function a(t){var r;return function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,a),(r=e.call(this,t)).indexUrl=o.X,r.namePlural="reports",r.nameLowerCase="report",r}return a}(r.a)}}]);