(window.webpackJsonp=window.webpackJsonp||[]).push([[11],{322:function(t,e,r){"use strict";r.r(e);var a=r(497),n=r(10),i=r(5);function o(t,e){var r=Object.keys(t);if(Object.getOwnPropertySymbols){var a=Object.getOwnPropertySymbols(t);e&&(a=a.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),r.push.apply(r,a)}return r}function s(t,e,r){return e in t?Object.defineProperty(t,e,{value:r,enumerable:!0,configurable:!0,writable:!0}):t[e]=r,t}var l={name:"ReportIndex",data:function(){var t;return{topUserReferrar:[],topRiderReferrar:[],webDataCounts:[],model:new a.a,from:"",to:"",filter_from:"",filter_to:"",filter:null!==(t=this.$route.params.filter)&&void 0!==t?t:"today",backgroundColor:["rgba(255, 99, 132, 0.2)","rgba(54, 162, 235, 0.2)","rgba(255, 206, 86, 0.2)","rgba(75, 192, 192, 0.2)","rgba(153, 102, 255, 0.2)","rgba(255, 159, 64, 0.2)"],borderColor:["rgba(255, 99, 132, 1)","rgba(54, 162, 235, 1)","rgba(255, 206, 86, 1)","rgba(75, 192, 192, 1)","rgba(153, 102, 255, 1)","rgba(255, 159, 64, 1)"],appUsersFromReferralsData:[],ridersFromReferralsData:[],orderPlaces:[],ud:!0,tur:!0,rd:!0,trr:!0}},methods:{formatDate:function(t){return moment(t).format("LLLL")},filterData:function(t){this.from="",this.to="",this.filter=t,this.loadData()},appUsersFromReferralsDataTable:function(){$.fn.DataTable.isDataTable("#appUsersFromReferrals")&&$("#appUsersFromReferrals").DataTable().destroy(),$("#appUsersFromReferrals").DataTable({dom:"Bfrtip",buttons:["excel",{text:"PDF",extend:"pdfHtml5",message:"",orientation:"potrait",exportOptions:{columns:":visible"},customize:function(t){var e=new Date,r=e.getDate()+"-"+(e.getMonth()+1)+"-"+e.getFullYear();t.pageMargins=[40,60,60,40],t.defaultStyle.fontSize=7,t.styles.tableHeader.fontSize=10,t.styles.title.fontSize=12,t.styles.title.alignment="left",t.content[0].text=t.content[0].text.trim(),t.header=function(t,e){return{columns:["GOGO20 | HIGHLY CONFIDENTIAL | ",{text:"FOR AUTHORIZED CIRCULATIONS ONLY",fontSize:8},{alignment:"right",text:["Date: ",{text:r}],color:"#000",background:"#fff",fontSize:8}],margin:[40,40],color:"#ad0b00",fontSize:10}};var a={hLineWidth:function(t){return.5},vLineWidth:function(t){return.5},hLineColor:function(t){return"#aaa"},vLineColor:function(t){return"#aaa"},paddingLeft:function(t){return 4},paddingRight:function(t){return 4}};t.content[1].layout=a}}],pageLength:10,ajax:{url:"/admin/report/referral-report/referred-user?q="+this.filter+"&from="+this.from+"&to="+this.to,dataSrc:"referredUser"},columns:[{data:"used_by",render:function(t,e,r){return t.first_name+" "+t.last_name}},{data:"used_by",render:function(t,e,r){return t.phone}},{data:"user",render:function(t,e,r){return t.first_name+" "+t.last_name}},{data:"user",render:function(t,e,r){return t.refer_code}},{data:"created_at",render:function(t,e,r){return moment(t).format("LL")}}]})},ridersFromReferralsDataTable:function(){$.fn.DataTable.isDataTable("#ridersFromReferrals")&&$("#ridersFromReferrals").DataTable().destroy(),$("#ridersFromReferrals").DataTable({dom:"Bfrtip",buttons:["excel",{text:"PDF",extend:"pdfHtml5",message:"",orientation:"potrait",exportOptions:{columns:":visible"},customize:function(t){var e=new Date,r=e.getDate()+"-"+(e.getMonth()+1)+"-"+e.getFullYear();t.pageMargins=[40,60,60,40],t.defaultStyle.fontSize=7,t.styles.tableHeader.fontSize=10,t.styles.title.fontSize=12,t.styles.title.alignment="left",t.content[0].text=t.content[0].text.trim(),t.header=function(t,e){return{columns:["GOGO20 | HIGHLY CONFIDENTIAL | ",{text:"FOR AUTHORIZED CIRCULATIONS ONLY",fontSize:8},{alignment:"right",text:["Date: ",{text:r}],color:"#000",background:"#fff",fontSize:8}],margin:[40,40],color:"#ad0b00",fontSize:10}};var a={hLineWidth:function(t){return.5},vLineWidth:function(t){return.5},hLineColor:function(t){return"#aaa"},vLineColor:function(t){return"#aaa"},paddingLeft:function(t){return 4},paddingRight:function(t){return 4}};t.content[1].layout=a}}],pageLength:10,ajax:{url:"/admin/report/referral-report/referred-riders?q="+this.filter+"&from="+this.from+"&to="+this.to,dataSrc:"riderFromReferred"},data:this.ridersFromReferralsData,columns:[{data:"used_by",render:function(t,e,r){return t.first_name+" "+t.last_name}},{data:"used_by",render:function(t,e,r){return t.phone}},{data:"driver",render:function(t,e,r){return t.first_name+" "+t.last_name}},{data:"driver",render:function(t,e,r){return t.refer_code}},{data:"created_at",render:function(t,e,r){return moment(t).format("LL")}}]})},reloadCounts:function(t){t.target.classList.add("fa-spin"),location.href=i.X},checkDate:function(){var t=this;if(""!=this.from&&""!=this.to){this.filter="custom",this.filter_from=this.from,this.filter_to=this.to;var e="custom";axios.get(this.model.indexUrl+"/referral-report/top-user-referrar?q="+e+"&from="+this.from+"&to="+this.to).then((function(e){t.topUserReferrar=e.data.topAppReferrars,t.appUsersFromReferralsDataTable()})),axios.get(this.model.indexUrl+"/referral-report/top-rider-referrar?q="+e+"&from="+this.from+"&to="+this.to).then((function(e){t.topRiderReferrar=e.data.topRiderReferrars,t.ridersFromReferralsDataTable()}))}},loadData:function(){var t=this,e=this.filter;axios.get(this.model.indexUrl+"/referral-report/top-user-referrar?q="+e).then((function(e){t.topUserReferrar=e.data.topAppReferrars,t.appUsersFromReferralsDataTable()})),axios.get(this.model.indexUrl+"/referral-report/top-rider-referrar?q="+e).then((function(e){t.topRiderReferrar=e.data.topRiderReferrars,t.ridersFromReferralsDataTable()}))}},computed:function(t){for(var e=1;e<arguments.length;e++){var r=null!=arguments[e]?arguments[e]:{};e%2?o(Object(r),!0).forEach((function(e){s(t,e,r[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(r)):o(Object(r)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(r,e))}))}return t}({},Object(n.mapGetters)(["homePageCounts","authUser"])),mounted:function(){this.$route.params[0]&&alertMessage("404, Page not found !!!","danger")},created:function(){this.loadData()}},f=(r(604),r(1)),c=Object(f.a)(l,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",[r("div",{staticStyle:{"margin-bottom":"10px",display:"flex"}},[r("ul",{staticClass:"nav nav-pills nav-pills-warning"},[r("li",{class:"today"===t.filter?"active":"nav-item"},[r("a",{staticClass:"nav-link",attrs:{href:"#"},on:{click:function(e){return t.filterData("today")}}},[t._v("Today ")])]),t._v(" "),r("li",{class:"yesterday"===t.filter?"active":"nav-item"},[r("a",{staticClass:"nav-link",attrs:{href:"#"},on:{click:function(e){return t.filterData("yesterday")}}},[t._v("Yesterday ")])]),t._v(" "),r("li",{class:"this-week"===t.filter?"active":"nav-item"},[r("a",{staticClass:"nav-link",attrs:{href:"#"},on:{click:function(e){return t.filterData("this-week")}}},[t._v("This Week ")])]),t._v(" "),r("li",{class:"this-month"===t.filter?"active":"nav-item"},[r("a",{attrs:{"aria-current":"true",href:"#"},on:{click:function(e){return t.filterData("this-month")}}},[t._v("This Month ")])]),t._v(" "),r("li",{class:"custom"===t.filter?"active":"nav-item"},[r("a",{attrs:{"aria-current":"true",href:"#","aria-disabled":"disabled",title:"Please Select Date"}},[t._v("Custom ")])])]),t._v(" "),r("div",{staticClass:"input-group"},[t._m(0),t._v(" "),r("input",{directives:[{name:"model",rawName:"v-model",value:t.from,expression:"from"}],staticClass:"form-control",attrs:{type:"date",id:"datetimepicker6","aria-describedby":"basic-addon1"},domProps:{value:t.from},on:{input:[function(e){e.target.composing||(t.from=e.target.value)},t.checkDate]}})]),t._v(" "),r("div",{staticClass:"input-group"},[t._m(1),t._v(" "),r("input",{directives:[{name:"model",rawName:"v-model",value:t.to,expression:"to"}],staticClass:"form-control",attrs:{type:"date",id:"datetimepicker7","aria-describedby":"basic-addon2"},domProps:{value:t.to},on:{input:[function(e){e.target.composing||(t.to=e.target.value)},t.checkDate]}})])]),t._v(" "),r("div",{staticClass:"row"},[r("div",{staticClass:"col-md-8"},["support"!==t.authUser.type&&"officer"!==t.authUser.type?r("app-card",{attrs:{title:"App Users from Referrals"}},[r("div",{staticClass:"table-responsive"},[r("table",{staticClass:"table table-hover table-striped table-bordered table-lg",attrs:{id:"appUsersFromReferrals",width:"100%"}},[r("thead",[r("tr",[r("th",{attrs:{width:"20%"}},[t._v("Name")]),t._v(" "),r("th",{attrs:{width:"20%"}},[t._v("Phone")]),t._v(" "),r("th",[t._v("Referred By")]),t._v(" "),r("th",[t._v("Referred Code")]),t._v(" "),r("th",[t._v("Registered")])])])])])]):t._e()],1),t._v(" "),r("div",{staticClass:"col-md-4"},["support"!==t.authUser.type&&"officer"!==t.authUser.type?r("app-card",{attrs:{title:"Top 10 App Referrars"}},[r("ul",{staticClass:"list-group-flush list-group",staticStyle:{"font-size":"13px !important"}},t._l(t.topUserReferrar,(function(e){return r("li",{key:e.user_id,staticClass:"list-group-item d-flex justify-content-between align-items-center list-group-item-action",attrs:{waves:""}},[r("span",[t._v(t._s(e.user.first_name+" "+e.user.last_name))]),t._v(" "),r("span",{staticClass:"badge badge-pill"},[t._v("\n                "+t._s(e.total_referred)+" User\n              ")]),t._v(" "),r("span",{staticClass:"badge badge-pill"},[t._v("\n                "+t._s(e.user.refer_code)+"\n              ")])])})),0)]):t._e()],1)]),t._v(" "),r("div",{staticClass:"row"},[r("div",{staticClass:"col-md-8"},["support"!==t.authUser.type&&"officer"!==t.authUser.type?r("app-card",{attrs:{title:"Riders from Referrals"}},[r("div",{staticClass:"table-responsive"},[r("table",{staticClass:"table table-hover table-striped table-bordered table-lg",attrs:{id:"ridersFromReferrals",width:"100%"}},[r("thead",[r("tr",[r("th",{attrs:{width:"20%"}},[t._v("Name")]),t._v(" "),r("th",{attrs:{width:"20%"}},[t._v("Phone")]),t._v(" "),r("th",[t._v("Referred By")]),t._v(" "),r("th",[t._v("Referred Code")]),t._v(" "),r("th",[t._v("Registered")])])])])])]):t._e()],1),t._v(" "),r("div",{staticClass:"col-md-4"},["support"!==t.authUser.type&&"officer"!==t.authUser.type?r("app-card",{attrs:{title:"Top 10 Rider Referrars"}},[r("ul",{staticClass:"list-group-flush list-group"},t._l(t.topRiderReferrar,(function(e){return r("li",{key:e.driver_id,staticClass:"list-group-item d-flex justify-content-between align-items-center list-group-item-action",attrs:{waves:""}},[r("span",[t._v(t._s(e.driver.first_name+" "+e.driver.last_name))]),t._v(" "),r("span",{staticClass:"badge badge-pill"},[t._v("\n                "+t._s(e.total_referred)+" Rider\n              ")]),t._v(" "),r("span",{staticClass:"badge badge-pill"},[t._v("\n                "+t._s(e.driver.refer_code)+"\n              ")])])})),0)]):t._e()],1)])])}),[function(){var t=this.$createElement,e=this._self._c||t;return e("span",{staticClass:"input-group-addon",attrs:{id:"date-addon1"}},[e("strong",[this._v("From")])])},function(){var t=this.$createElement,e=this._self._c||t;return e("span",{staticClass:"input-group-addon",attrs:{id:"date-addon2"}},[e("strong",[this._v("To")])])}],!1,null,null,null);e.default=c.exports},497:function(t,e,r){"use strict";r.d(e,"a",(function(){return c}));var a=r(6),n=r(5);function i(t){return(i="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t})(t)}function o(t,e){return(o=Object.setPrototypeOf||function(t,e){return t.__proto__=e,t})(t,e)}function s(t){var e=function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Date.prototype.toString.call(Reflect.construct(Date,[],(function(){}))),!0}catch(t){return!1}}();return function(){var r,a=f(t);if(e){var n=f(this).constructor;r=Reflect.construct(a,arguments,n)}else r=a.apply(this,arguments);return l(this,r)}}function l(t,e){return!e||"object"!==i(e)&&"function"!=typeof e?function(t){if(void 0===t)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return t}(t):e}function f(t){return(f=Object.setPrototypeOf?Object.getPrototypeOf:function(t){return t.__proto__||Object.getPrototypeOf(t)})(t)}var c=function(t){!function(t,e){if("function"!=typeof e&&null!==e)throw new TypeError("Super expression must either be null or a function");t.prototype=Object.create(e&&e.prototype,{constructor:{value:t,writable:!0,configurable:!0}}),e&&o(t,e)}(r,t);var e=s(r);function r(t){var a;return function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,r),(a=e.call(this,t)).indexUrl=n.X,a.namePlural="reports",a.nameLowerCase="report",a}return r}(a.a)},531:function(t,e,r){var a=r(605);"string"==typeof a&&(a=[[t.i,a,""]]);var n={hmr:!0,transform:void 0,insertInto:void 0};r(9)(a,n);a.locals&&(t.exports=a.locals)},604:function(t,e,r){"use strict";var a=r(531);r.n(a).a},605:function(t,e,r){(t.exports=r(8)(!1)).push([t.i,"\ndiv.dataTables_wrapper div.dataTables_filter {\n    text-align: left !important;\n}\ndiv.dataTables_wrapper div.dataTables_filter input {\n    width: 55em !important;\n}\ndiv.dataTables_wrapper div.dt-buttons {\n  float: right;\n}\ndiv.dataTables_wrapper div.dt-buttons button {\n    margin: 0px 10px !important;\n}\n.buttons-excel{\n  background-color: #1f6e43 !important;\n}\n.buttons-pdf{\n  background-color: #ad0b00 !important;\n}\n.table td {\n   text-align: center;\n}\n.table th {\n   height: 40px !important;\n}\n/* table.fixedHeader-floating{position:fixed !important;background-color:white}table.fixedHeader-floating.no-footer{border-bottom-width:0}table.fixedHeader-locked{position:absolute !important;background-color:white}@media print{table.fixedHeader-floating{display:none}} */\n",""])}}]);