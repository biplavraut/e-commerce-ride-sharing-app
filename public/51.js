(window.webpackJsonp=window.webpackJsonp||[]).push([[51],{431:function(t,e,n){var a=n(434);"string"==typeof a&&(a=[[t.i,a,""]]);var r={hmr:!0,transform:void 0,insertInto:void 0};n(9)(a,r);a.locals&&(t.exports=a.locals)},433:function(t,e,n){"use strict";var a=n(431);n.n(a).a},434:function(t,e,n){(t.exports=n(8)(!1)).push([t.i,"ul.list-container[data-v-7e810940] {\n  list-style-type: none;\n  margin-bottom: 0;\n  background: rgba(0, 135, 203, 0.1);\n}\nul.list-container > li .list-item[data-v-7e810940] {\n  width: 100%;\n  padding: 10px 15px;\n  background-color: #ffffff;\n  border-bottom: 1px solid #efefef;\n  box-sizing: border-box;\n  user-select: none;\n  color: #333333;\n  font-weight: 400;\n  position: relative;\n}\nul.list-container > li .list-item .child-arrow[data-v-7e810940] {\n  font-weight: bold;\n  position: absolute;\n  left: -20px;\n  transform: rotateY(180deg);\n  line-height: 40px;\n}\nul.list-container > li .list-item .item-name[data-v-7e810940],\nul.list-container > li .list-item .item-action[data-v-7e810940] {\n  display: inline-block;\n}\nul.list-container > li .list-item .item-name img[data-v-7e810940] {\n  width: 40px;\n  height: auto;\n  border-radius: 50%;\n  margin-right: 10px;\n}\nul.list-container > li .list-item .item-action[data-v-7e810940] {\n  float: right;\n  line-height: 40px;\n}",""])},459:function(t,e,n){"use strict";var a=n(10);function r(t,e){var n=Object.keys(t);if(Object.getOwnPropertySymbols){var a=Object.getOwnPropertySymbols(t);e&&(a=a.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),n.push.apply(n,a)}return n}function i(t){for(var e=1;e<arguments.length;e++){var n=null!=arguments[e]?arguments[e]:{};e%2?r(Object(n),!0).forEach((function(e){o(t,e,n[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(n)):r(Object(n)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(n,e))}))}return t}function o(t,e,n){return e in t?Object.defineProperty(t,e,{value:n,enumerable:!0,configurable:!0,writable:!0}):t[e]=n,t}var s={name:"CategoryTree",props:{items:Array,tab:Number},methods:{format:function(){var t=this,e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:null;return this.$vnode.key>0?this.items:this.items.filter((function(t){return t.parentId===e})).map((function(e){return{id:e.id,name:e.name,slug:e.slug,parent:e.parent,parentId:e.parentId,image:e.image50||e.image,children:t.format(e.id)}}))},emitDeleteEvent:function(t){this.$emit("deleteItem",t)}},computed:i(i({},Object(a.mapGetters)(["authUser"])),{},{formattedItems:function(){return this.format(this.$vnode.key||null)}})},l=(n(433),n(2)),c=Object(l.a)(s,(function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("ul",{staticClass:"list-container"},t._l(t.formattedItems,(function(e){return n("li",{key:e.id,attrs:{"data-id":e.id,"data-name":e.name,"data-slug":e.slug,"data-parent":e.parent,"data-parent-id":e.parentId}},[n("div",{staticClass:"list-item"},[t.$vnode.key>0?n("span",{staticClass:"child-arrow"},[t._v("↵")]):t._e(),t._v(" "),n("div",{staticClass:"item-name"},[n("img",{attrs:{src:e.image}}),t._v("\n        "+t._s(e.name)+" ("+t._s(e.slug)+")\n      ")]),t._v(" "),"admin"===t.authUser.type||"superadmin"===t.authUser.type?n("app-actions",{staticClass:"item-action",attrs:{actions:{edit:{name:"product-category.edit",params:{id:e.id,tab:t.tab}},delete:e.id}},on:{deleteItem:function(n){return t.emitDeleteEvent(e.id)}}}):t._e()],1),t._v(" "),e.children.length>0?n("category-tree",{key:e.id,attrs:{items:e.children},on:{deleteItem:t.emitDeleteEvent}}):t._e()],1)})),0)}),[],!1,null,"7e810940",null);e.a=c.exports},515:function(t,e,n){"use strict";n.r(e);var a=n(0),r=n.n(a),i=n(214),o=n(18),s=n(3),l=n(459),c=n(10);function u(t,e,n,a,r,i,o){try{var s=t[i](o),l=s.value}catch(t){return void n(t)}s.done?e(l):Promise.resolve(l).then(a,r)}function d(t){return function(){var e=this,n=arguments;return new Promise((function(a,r){var i=t.apply(e,n);function o(t){u(i,a,r,o,s,"next",t)}function s(t){u(i,a,r,o,s,"throw",t)}o(void 0)}))}}function p(t,e){var n=Object.keys(t);if(Object.getOwnPropertySymbols){var a=Object.getOwnPropertySymbols(t);e&&(a=a.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),n.push.apply(n,a)}return n}function m(t){for(var e=1;e<arguments.length;e++){var n=null!=arguments[e]?arguments[e]:{};e%2?p(Object(n),!0).forEach((function(e){f(t,e,n[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(n)):p(Object(n)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(n,e))}))}return t}function f(t,e,n){return e in t?Object.defineProperty(t,e,{value:n,enumerable:!0,configurable:!0,writable:!0}):t[e]=n,t}var v={name:"CategoryIndex",components:{CategoryTree:l.a},mixins:[s.b,s.a],data:function(){return{columns:["Image","Name"],rows:{data:[],links:{},meta:{}},model:new i.a,file:"",type:"category",categories:[],service:new o.a,services:[],active:0,allCount:0}},methods:m(m({},Object(c.mapMutations)(["updateThisMonthCategoriesCount"])),{},{exportSheet:function(){confirm("Are you sure?")&&(window.location=this.model.indexUrl+"/excel-export")},submitFile:function(){var t=new FormData;t.append("import_file",this.file),t.append("type",this.type),axios.post(this.model.indexUrl+"/excel-import",t,{headers:{"Content-Type":"multipart/form-data"}}).then((function(){alertMessage("Product Category Imported successfully.")})).catch((function(){console.log("FAILURE!!")})),this.otherFields()},otherFields:function(){$("#import").modal("hide"),this.getModels()},handleFileUpload:function(){this.file=this.$refs.file.files[0]},downloadCategorySample:function(){var t=window.location.origin+"/dashboard/excel-samples/";location.href=t+"Product Category Import.xlsx"},downloadSubCategorySample:function(){var t=window.location.origin+"/dashboard/excel-samples/";location.href=t+"Product Sub Category Import.xlsx"},reset:function(){var t=this;axios.get("/admin/product-category").then((function(e){t.active=0,t.rows.data=e.data.data}))},fetchCategoryData:function(t){var e=this;return d(r.a.mark((function n(){var a;return r.a.wrap((function(n){for(;;)switch(n.prev=n.next){case 0:return n.next=2,e.model.getData(t);case 2:a=n.sent,e.rows.data=a.data,e.active=t;case 5:case"end":return n.stop()}}),n)})))()},getCategories:function(){var t=this;return d(r.a.mark((function e(){var n;return r.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,t.model.getRoot();case 2:n=e.sent,t.categories=n.data.map((function(t){return{id:t.id,name:t.name,slug:t.slug}}));case 4:case"end":return e.stop()}}),e)})))()},getServices:function(){var t=this;return d(r.a.mark((function e(){var n;return r.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,t.service.getAll();case 2:n=e.sent,t.services=n.data.map((function(e){return t.allCount+=e.categoryCount,{id:e.id,name:e.name,categoryCount:e.categoryCount}}));case 4:case"end":return e.stop()}}),e)})))()}}),mounted:function(){this.getModels(),this.getCategories(),this.getServices()},created:function(){this.$route.params.active&&0!==this.$route.params.active&&this.fetchCategoryData(this.$route.params.active)}},g=n(2),b=Object(g.a)(v,(function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("app-card",{attrs:{title:"All <b>Product Categories</b>","body-padding":"0"}},[n("template",{slot:"actions"},[n("app-btn-link",{attrs:{"route-name":"product-category.create"}},[t._v("Add New")]),t._v(" "),n("app-btn",{attrs:{background:"info",icon:"archive"},on:{click:function(e){return e.preventDefault(),t.exportSheet(e)}}},[t._v("Download Excel")]),t._v(" "),n("app-btn",{attrs:{background:"warning",icon:"cloud_upload","data-toggle":"modal","data-target":"#import"}},[t._v("Import Excel\n    ")])],1),t._v(" "),n("ul",{staticClass:"nav nav-pills nav-pills-warning",staticStyle:{padding:"5px"}},[n("li",{class:0==t.active?"active":"",on:{click:function(e){return e.preventDefault(),t.reset(e)}}},[n("a",{attrs:{href:"#all","data-toggle":"tab","aria-expanded":"true"}},[t._v("All "),n("span",{staticClass:"badge"},[t._v(t._s(t.allCount))])])]),t._v(" "),t._l(t.services,(function(e,a){return n("li",{key:a,class:t.active==e.id?"active":"",on:{click:function(n){return n.preventDefault(),t.fetchCategoryData(e.id)}}},[n("a",{attrs:{href:"#"+e.slug,"data-toggle":"tab","aria-expanded":"true"}},[t._v(t._s(e.name)+"\n        "),n("sup",[n("span",{staticClass:"badge"},[t._v(t._s(e.categoryCount))])])])])}))],2),t._v(" "),n("category-tree",{staticStyle:{padding:"0"},attrs:{items:t.rows.data,tab:t.active},on:{deleteItem:t.deleteModel}}),t._v(" "),n("div",{staticClass:"modal fade",attrs:{id:"import",role:"dialog"}},[n("div",{staticClass:"modal-dialog"},[n("div",{staticClass:"modal-content"},[n("div",{staticClass:"modal-header"},[n("button",{staticClass:"close",attrs:{type:"button","data-dismiss":"modal"}},[t._v("\n            ×\n          ")]),t._v(" "),n("h4",{staticClass:"modal-title"},[t._v("Import Category Excel")])]),t._v(" "),n("div",{staticClass:"modal-body"},[n("input",{ref:"file",staticClass:"btn btn-secondary",attrs:{type:"file",id:"file",name:"import_file"},on:{change:function(e){return t.handleFileUpload()}}}),t._v(" "),n("select",{directives:[{name:"model",rawName:"v-model",value:t.type,expression:"type"}],staticClass:"form-control",attrs:{name:"type"},on:{change:function(e){var n=Array.prototype.filter.call(e.target.options,(function(t){return t.selected})).map((function(t){return"_value"in t?t._value:t.value}));t.type=e.target.multiple?n:n[0]}}},[n("option",{attrs:{disabled:"",selected:""}},[t._v("Selct Type of Data")]),t._v(" "),n("option",{attrs:{value:"category"}},[t._v("Category")]),t._v(" "),n("option",{attrs:{value:"subcategory"}},[t._v("Sub Category")])]),t._v(" "),n("button",{staticClass:"btn btn-round btn-primary",attrs:{type:"submit"},on:{click:function(e){return t.submitFile()}}},[t._v("\n            Import\n          ")]),t._v(" "),n("button",{staticClass:"btn btn-sm btn-round btn-warning",attrs:{type:"button"},on:{click:function(e){return t.downloadCategorySample()}}},[t._v("\n            Download Category Sample\n          ")]),t._v(" "),n("button",{staticClass:"btn btn-sm btn-round btn-warning",attrs:{type:"button"},on:{click:function(e){return t.downloadSubCategorySample()}}},[t._v("\n            Download Sub Category Sample\n          ")])]),t._v(" "),n("div",{staticClass:"modal-footer"},[n("button",{staticClass:"btn btn-default",attrs:{type:"button","data-dismiss":"modal"}},[t._v("\n            Close\n          ")])])])])])],2)}),[],!1,null,null,null);e.default=b.exports}}]);