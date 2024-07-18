(window.webpackJsonp=window.webpackJsonp||[]).push([[58],{311:function(t,e,n){"use strict";n.r(e);var s=n(6),a=n(5);function i(t){return(i="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t})(t)}function r(t,e){return(r=Object.setPrototypeOf||function(t,e){return t.__proto__=e,t})(t,e)}function o(t){var e=function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Date.prototype.toString.call(Reflect.construct(Date,[],(function(){}))),!0}catch(t){return!1}}();return function(){var n,s=d(t);if(e){var a=d(this).constructor;n=Reflect.construct(s,arguments,a)}else n=s.apply(this,arguments);return l(this,n)}}function l(t,e){return!e||"object"!==i(e)&&"function"!=typeof e?function(t){if(void 0===t)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return t}(t):e}function d(t){return(d=Object.setPrototypeOf?Object.getPrototypeOf:function(t){return t.__proto__||Object.getPrototypeOf(t)})(t)}var c=function(t){!function(t,e){if("function"!=typeof e&&null!==e)throw new TypeError("Super expression must either be null or a function");t.prototype=Object.create(e&&e.prototype,{constructor:{value:t,writable:!0,configurable:!0}}),e&&r(t,e)}(n,t);var e=o(n);function n(t){var s;return function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,n),(s=e.call(this,t)).indexUrl=a.db,s.namePlural="sends",s.nameLowerCase="send",s}return n}(s.a),u=n(4),p={name:"SendIndex",mixins:[u.b,u.a],data:function(){return{columns:["#","User","Send Details","Pickup Details","Delivery Details","Price Details","Payment Type","Status"],rows:{data:[],links:{},meta:{}},model:new c,sendData:{riders:null,assignedRider:"",sendId:""}}},methods:{getRiders:function(t){var e=this;this.sendData.sendId=t,axios.get("/admin/get-rider/").then((function(t){null!=t.data?e.sendData.riders=t.data:alertMessage("Unable to retrive driver","danger")}))},assignRiderForDelivery:function(){var t=this;axios.post("/admin/assign-delivery",{sendId:this.sendData.sendId,rider_id:this.sendData.assignedRider,type:"send"}).then((function(e){!0===e.status?(alertMessage("Delivery successfully assigned to rider."),$("#assignRider").modal("hide"),t.reset()):alertMessage("Something went wrong while processing","danger")})).catch((function(t){alertMessage("Something went wrong while processing","danger")})),this.reset()}},mounted:function(){this.getModels()}},m=(n(632),n(1)),v=Object(m.a)(p,(function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("app-card",{attrs:{title:"Gogo <b>Send</b>","body-padding":"0"}},[n("app-table-sortable",{attrs:{columns:t.columns,rows:t.rows},scopedSlots:t._u([{key:"default",fn:function(e){var s=e.row;return[n("td",[t._v("\n        "+t._s(s.id)+"\n        "),n("span",{staticClass:"badge",style:{background:s.dynamicColor}},[t._v(t._s(s.agoTime))])]),t._v(" "),n("td",[t._v("\n        "+t._s(s.user.firstName)+" "+t._s(s.user.lastName)),n("br"),t._v(" "),n("small",[t._v(t._s(s.user.phone))])]),t._v(" "),n("td",[t._v("\n        "+t._s(s.delivery_item_type.name)),n("br"),n("span",{staticClass:"badge badge-primary"},[t._v(t._s(s.distance_in_km)+" km")])]),t._v(" "),n("td",[t._v("\n        "+t._s(s.pickup_location_name)),n("br"),n("span",{staticClass:"badge badge-primary"},[t._v(t._s(s.pickup_date)+" @ "+t._s(s.pickup_time))]),t._v(" "),s.pickup_comment.length>0?n("small",[n("i",{staticClass:"fa fa-comment"}),t._v(" "+t._s(s.pickup_comment))]):t._e()]),t._v(" "),n("td",[t._v("\n        "+t._s(s.delivery_location_name)),n("br"),n("span",{staticClass:"badge badge-primary"},[t._v(t._s(s.delivery_date)+" @ "+t._s(s.delivery_time))]),t._v(" "),s.delivery_comment.length>0?n("small",[n("i",{staticClass:"fa fa-comment"}),t._v(" "+t._s(s.delivery_comment))]):t._e()]),t._v(" "),n("td",{staticStyle:{"min-width":"250px"}},[n("small",[t._v("\n          Flat Amout: Rs. "+t._s(s.extra_column.flatPrice)),n("br"),t._v("\n          Distance Amount: Rs. "+t._s(s.extra_column.distancePrice)),n("br"),t._v("\n          Weight Amout: Rs. "+t._s(s.extra_column.weightPrice)),n("br"),t._v("\n          Discount Amout: Rs. "+t._s(s.extra_column.discountAmount)),n("br"),t._v("\n          Net Amount: Rs.\n          "+t._s(s.extra_column.totalPriceAfterDiscount))])]),t._v(" "),n("td",[t._v("COD")]),t._v(" "),n("td",[t._v(t._s(1==s.status?"active":"inactive"))]),t._v(" "),s.delivery?t._e():n("td",[n("div",{staticClass:"col-md-4"},[n("button",{staticClass:"btn btn-success btn-ajax",attrs:{type:"button",title:"Assign Rider","data-toggle":"modal","data-target":"#assignRider"},on:{click:function(e){return t.getRiders(s.id)}}},[n("i",{staticClass:"material-icons"},[t._v("event_seat")]),t._v(" "),n("div",{staticClass:"ripple-container"})])]),t._v(" "),n("div",{staticClass:"col-md-4"},[n("button",{staticClass:"btn btn-danger btn-ajax",attrs:{type:"button",title:"Delete"},on:{click:function(e){return e.preventDefault(),t.deleteOrder(s.id)}}},[n("i",{staticClass:"material-icons"},[t._v("close")]),t._v(" "),n("div",{staticClass:"ripple-container"})])])])]}}])}),t._v(" "),n("div",{staticClass:"modal fade",attrs:{id:"assignRider",role:"dialog"}},[n("div",{staticClass:"modal-dialog"},[n("div",{staticClass:"modal-content"},[n("div",{staticClass:"modal-header"},[n("button",{staticClass:"close",attrs:{type:"button","data-dismiss":"modal"}},[t._v("\n            ×\n          ")]),t._v(" "),n("h4",{staticClass:"modal-title"},[t._v("Assign rider for this delivery")])]),t._v(" "),n("div",{staticClass:"modal-body"},[n("form",{on:{submit:function(e){return e.preventDefault(),t.assignRiderForDelivery(e)}}},[n("div",{staticClass:"row"},[n("div",{staticClass:"col-md-12"},[t.sendData.riders?n("div",{staticClass:"form-group"},[n("div",{staticClass:"list-group"},t._l(t.sendData.riders.data,(function(e,s){return n("div",{key:s,attrs:{value:e.id}},[n("input",{directives:[{name:"model",rawName:"v-model",value:t.sendData.assignedRider,expression:"sendData.assignedRider"}],attrs:{type:"radio",name:"selectrider",id:e.id},domProps:{value:e.id,checked:t._q(t.sendData.assignedRider,e.id)},on:{change:function(n){return t.$set(t.sendData,"assignedRider",e.id)}}}),t._v(" "),n("label",{staticClass:"list-group-item",attrs:{for:e.id}},[t._v(t._s(e.name)+":"+t._s(e.phone)+"\n                        "),n("div",{staticClass:"row"},[n("small",{staticClass:"col-sm-3"},[t._v("Today's Summary:")]),t._v(" "),n("small",{staticClass:"col-sm-3"},[t._v("Assigned:"+t._s(e.totalAssigned))]),t._v(" "),n("small",{staticClass:"col-sm-3"},[t._v("Delivered:"+t._s(e.totalDelivered))]),t._v(" "),n("small",{staticClass:"col-sm-3"},[t._v("Remaining:"+t._s(e.totalAssigned-e.totalDelivered))])])])])})),0)]):t._e()]),t._v(" "),n("div",{staticClass:"col-md-offset-9 col-md-3"},[t.sendData.assignedRider?n("button",{staticClass:"btn btn-success",staticStyle:{"margin-top":"20%"},attrs:{type:"submit",disabled:t.errors.any()}},[t._v("\n                  Assign\n                ")]):t._e()])])])])])])])],1)}),[],!1,null,"675d4aa8",null);e.default=v.exports},545:function(t,e,n){var s=n(633);"string"==typeof s&&(s=[[t.i,s,""]]);var a={hmr:!0,transform:void 0,insertInto:void 0};n(9)(s,a);s.locals&&(t.exports=s.locals)},632:function(t,e,n){"use strict";var s=n(545);n.n(s).a},633:function(t,e,n){(t.exports=n(8)(!1)).push([t.i,'\n.cursor[data-v-675d4aa8] {\n  cursor: pointer;\n}\n.list-group-item[data-v-675d4aa8] {\n  user-select: none;\n}\n.list-group input[type="checkbox"][data-v-675d4aa8] {\n  display: none;\n}\n.list-group input[type="checkbox"] + .list-group-item[data-v-675d4aa8] {\n  cursor: pointer;\n}\n.list-group input[type="checkbox"] + .list-group-item[data-v-675d4aa8]:before {\n  content: "\\2713";\n  color: transparent;\n  font-weight: bold;\n  margin-right: 1em;\n}\n.list-group input[type="checkbox"]:checked + .list-group-item[data-v-675d4aa8] {\n  background-color: #022584;\n  color: #fff;\n}\n.list-group input[type="checkbox"]:checked + .list-group-item[data-v-675d4aa8]:before {\n  color: inherit;\n}\n.list-group input[type="radio"][data-v-675d4aa8] {\n  display: none;\n}\n.list-group input[type="radio"] + .list-group-item[data-v-675d4aa8] {\n  cursor: pointer;\n}\n.list-group input[type="radio"] + .list-group-item[data-v-675d4aa8]:before {\n  content: "\\2022";\n  color: transparent;\n  font-weight: bold;\n  margin-right: 1em;\n}\n.list-group input[type="radio"]:checked + .list-group-item[data-v-675d4aa8] {\n  background-color: #0275d8;\n  color: #fff;\n}\n.list-group input[type="radio"]:checked + .list-group-item[data-v-675d4aa8]:before {\n  color: inherit;\n}\n',""])}}]);