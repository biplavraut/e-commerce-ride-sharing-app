<<<<<<< HEAD
(window.webpackJsonp=window.webpackJsonp||[]).push([[58],{444:function(t,e,n){var a=n(447);"string"==typeof a&&(a=[[t.i,a,""]]);var r={hmr:!0,transform:void 0,insertInto:void 0};n(9)(a,r);a.locals&&(t.exports=a.locals)},446:function(t,e,n){"use strict";var a=n(444);n.n(a).a},447:function(t,e,n){(t.exports=n(8)(!1)).push([t.i,"ul.list-container[data-v-7e810940] {\n  list-style-type: none;\n  margin-bottom: 0;\n  background: rgba(0, 135, 203, 0.1);\n}\nul.list-container > li .list-item[data-v-7e810940] {\n  width: 100%;\n  padding: 10px 15px;\n  background-color: #ffffff;\n  border-bottom: 1px solid #efefef;\n  box-sizing: border-box;\n  user-select: none;\n  color: #333333;\n  font-weight: 400;\n  position: relative;\n}\nul.list-container > li .list-item .child-arrow[data-v-7e810940] {\n  font-weight: bold;\n  position: absolute;\n  left: -20px;\n  transform: rotateY(180deg);\n  line-height: 40px;\n}\nul.list-container > li .list-item .item-name[data-v-7e810940],\nul.list-container > li .list-item .item-action[data-v-7e810940] {\n  display: inline-block;\n}\nul.list-container > li .list-item .item-name img[data-v-7e810940] {\n  width: 40px;\n  height: auto;\n  border-radius: 50%;\n  margin-right: 10px;\n}\nul.list-container > li .list-item .item-action[data-v-7e810940] {\n  float: right;\n  line-height: 40px;\n}",""])},475:function(t,e,n){"use strict";var a=n(10);function r(t,e){var n=Object.keys(t);if(Object.getOwnPropertySymbols){var a=Object.getOwnPropertySymbols(t);e&&(a=a.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),n.push.apply(n,a)}return n}function i(t){for(var e=1;e<arguments.length;e++){var n=null!=arguments[e]?arguments[e]:{};e%2?r(Object(n),!0).forEach((function(e){o(t,e,n[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(n)):r(Object(n)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(n,e))}))}return t}function o(t,e,n){return e in t?Object.defineProperty(t,e,{value:n,enumerable:!0,configurable:!0,writable:!0}):t[e]=n,t}var s={name:"CategoryTree",props:{items:Array,tab:Number},methods:{format:function(){var t=this,e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:null;return this.$vnode.key>0?this.items:this.items.filter((function(t){return t.parentId===e})).map((function(e){return{id:e.id,name:e.name,slug:e.slug,parent:e.parent,parentId:e.parentId,image:e.image50||e.image,children:t.format(e.id)}}))},emitDeleteEvent:function(t){this.$emit("deleteItem",t)}},computed:i(i({},Object(a.mapGetters)(["authUser"])),{},{formattedItems:function(){return this.format(this.$vnode.key||null)}})},l=(n(446),n(1)),c=Object(l.a)(s,(function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("ul",{staticClass:"list-container"},t._l(t.formattedItems,(function(e){return n("li",{key:e.id,attrs:{"data-id":e.id,"data-name":e.name,"data-slug":e.slug,"data-parent":e.parent,"data-parent-id":e.parentId}},[n("div",{staticClass:"list-item"},[t.$vnode.key>0?n("span",{staticClass:"child-arrow"},[t._v("↵")]):t._e(),t._v(" "),n("div",{staticClass:"item-name"},[n("img",{attrs:{src:e.image}}),t._v("\n        "+t._s(e.name)+" ("+t._s(e.slug)+")\n      ")]),t._v(" "),"admin"===t.authUser.type||"superadmin"===t.authUser.type?n("app-actions",{staticClass:"item-action",attrs:{actions:{edit:{name:"product-category.edit",params:{id:e.id,tab:t.tab}},delete:e.id}},on:{deleteItem:function(n){return t.emitDeleteEvent(e.id)}}}):t._e()],1),t._v(" "),e.children.length>0?n("category-tree",{key:e.id,attrs:{items:e.children},on:{deleteItem:t.emitDeleteEvent}}):t._e()],1)})),0)}),[],!1,null,"7e810940",null);e.a=c.exports},537:function(t,e,n){"use strict";n.r(e);var a=n(0),r=n.n(a),i=n(217),o=n(12),s=n(3),l=n(475),c=n(10);function u(t,e,n,a,r,i,o){try{var s=t[i](o),l=s.value}catch(t){return void n(t)}s.done?e(l):Promise.resolve(l).then(a,r)}function d(t){return function(){var e=this,n=arguments;return new Promise((function(a,r){var i=t.apply(e,n);function o(t){u(i,a,r,o,s,"next",t)}function s(t){u(i,a,r,o,s,"throw",t)}o(void 0)}))}}function p(t,e){var n=Object.keys(t);if(Object.getOwnPropertySymbols){var a=Object.getOwnPropertySymbols(t);e&&(a=a.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),n.push.apply(n,a)}return n}function m(t){for(var e=1;e<arguments.length;e++){var n=null!=arguments[e]?arguments[e]:{};e%2?p(Object(n),!0).forEach((function(e){f(t,e,n[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(n)):p(Object(n)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(n,e))}))}return t}function f(t,e,n){return e in t?Object.defineProperty(t,e,{value:n,enumerable:!0,configurable:!0,writable:!0}):t[e]=n,t}var v={name:"CategoryIndex",components:{CategoryTree:l.a},mixins:[s.b,s.a],data:function(){return{columns:["Image","Name"],rows:{data:[],links:{},meta:{}},model:new i.a,file:"",type:"category",categories:[],service:new o.a,services:[],active:0,allCount:0}},methods:m(m({},Object(c.mapMutations)(["updateThisMonthCategoriesCount"])),{},{exportSheet:function(){confirm("Are you sure?")&&(window.location=this.model.indexUrl+"/excel-export")},submitFile:function(){var t=new FormData;t.append("import_file",this.file),t.append("type",this.type),axios.post(this.model.indexUrl+"/excel-import",t,{headers:{"Content-Type":"multipart/form-data"}}).then((function(){alertMessage("Product Category Imported successfully.")})).catch((function(){console.log("FAILURE!!")})),this.otherFields()},otherFields:function(){$("#import").modal("hide"),this.getModels()},handleFileUpload:function(){this.file=this.$refs.file.files[0]},downloadCategorySample:function(){var t=window.location.origin+"/dashboard/excel-samples/";location.href=t+"Product Category Import.xlsx"},downloadSubCategorySample:function(){var t=window.location.origin+"/dashboard/excel-samples/";location.href=t+"Product Sub Category Import.xlsx"},reset:function(){var t=this;axios.get("/admin/product-category").then((function(e){t.active=0,t.rows.data=e.data.data}))},fetchCategoryData:function(t){var e=this;return d(r.a.mark((function n(){var a;return r.a.wrap((function(n){for(;;)switch(n.prev=n.next){case 0:return n.next=2,e.model.getData(t);case 2:a=n.sent,e.rows.data=a.data,e.active=t;case 5:case"end":return n.stop()}}),n)})))()},getCategories:function(){var t=this;return d(r.a.mark((function e(){var n;return r.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,t.model.getRoot();case 2:n=e.sent,t.categories=n.data.map((function(t){return{id:t.id,name:t.name,slug:t.slug}}));case 4:case"end":return e.stop()}}),e)})))()},getServices:function(){var t=this;return d(r.a.mark((function e(){var n;return r.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,t.service.getAll();case 2:n=e.sent,t.services=n.data.map((function(e){return t.allCount+=e.categoryCount,{id:e.id,name:e.name,categoryCount:e.categoryCount}}));case 4:case"end":return e.stop()}}),e)})))()}}),mounted:function(){this.getModels(),this.getCategories(),this.getServices()},created:function(){this.$route.params.active&&0!==this.$route.params.active&&this.fetchCategoryData(this.$route.params.active)}},g=n(1),b=Object(g.a)(v,(function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("app-card",{attrs:{title:"All <b>Product Categories</b>","body-padding":"0"}},[n("template",{slot:"actions"},[n("app-btn-link",{attrs:{"route-name":"product-category.create"}},[t._v("Add New")]),t._v(" "),n("app-btn",{attrs:{background:"info",icon:"archive"},on:{click:function(e){return e.preventDefault(),t.exportSheet(e)}}},[t._v("Download Excel")]),t._v(" "),n("app-btn",{attrs:{background:"warning",icon:"cloud_upload","data-toggle":"modal","data-target":"#import"}},[t._v("Import Excel\n    ")])],1),t._v(" "),n("ul",{staticClass:"nav nav-pills nav-pills-warning",staticStyle:{padding:"5px"}},[n("li",{class:0==t.active?"active":"",on:{click:function(e){return e.preventDefault(),t.reset(e)}}},[n("a",{attrs:{href:"#all","data-toggle":"tab","aria-expanded":"true"}},[t._v("All "),n("span",{staticClass:"badge"},[t._v(t._s(t.allCount))])])]),t._v(" "),t._l(t.services,(function(e,a){return n("li",{key:a,class:t.active==e.id?"active":"",on:{click:function(n){return n.preventDefault(),t.fetchCategoryData(e.id)}}},[n("a",{attrs:{href:"#"+e.slug,"data-toggle":"tab","aria-expanded":"true"}},[t._v(t._s(e.name)+"\n        "),n("sup",[n("span",{staticClass:"badge"},[t._v(t._s(e.categoryCount))])])])])}))],2),t._v(" "),n("category-tree",{staticStyle:{padding:"0"},attrs:{items:t.rows.data,tab:t.active},on:{deleteItem:t.deleteModel}}),t._v(" "),n("div",{staticClass:"modal fade",attrs:{id:"import",role:"dialog"}},[n("div",{staticClass:"modal-dialog"},[n("div",{staticClass:"modal-content"},[n("div",{staticClass:"modal-header"},[n("button",{staticClass:"close",attrs:{type:"button","data-dismiss":"modal"}},[t._v("\n            ×\n          ")]),t._v(" "),n("h4",{staticClass:"modal-title"},[t._v("Import Category Excel")])]),t._v(" "),n("div",{staticClass:"modal-body"},[n("input",{ref:"file",staticClass:"btn btn-secondary",attrs:{type:"file",id:"file",name:"import_file"},on:{change:function(e){return t.handleFileUpload()}}}),t._v(" "),n("select",{directives:[{name:"model",rawName:"v-model",value:t.type,expression:"type"}],staticClass:"form-control",attrs:{name:"type"},on:{change:function(e){var n=Array.prototype.filter.call(e.target.options,(function(t){return t.selected})).map((function(t){return"_value"in t?t._value:t.value}));t.type=e.target.multiple?n:n[0]}}},[n("option",{attrs:{disabled:"",selected:""}},[t._v("Selct Type of Data")]),t._v(" "),n("option",{attrs:{value:"category"}},[t._v("Category")]),t._v(" "),n("option",{attrs:{value:"subcategory"}},[t._v("Sub Category")])]),t._v(" "),n("button",{staticClass:"btn btn-round btn-primary",attrs:{type:"submit"},on:{click:function(e){return t.submitFile()}}},[t._v("\n            Import\n          ")]),t._v(" "),n("button",{staticClass:"btn btn-sm btn-round btn-warning",attrs:{type:"button"},on:{click:function(e){return t.downloadCategorySample()}}},[t._v("\n            Download Category Sample\n          ")]),t._v(" "),n("button",{staticClass:"btn btn-sm btn-round btn-warning",attrs:{type:"button"},on:{click:function(e){return t.downloadSubCategorySample()}}},[t._v("\n            Download Sub Category Sample\n          ")])]),t._v(" "),n("div",{staticClass:"modal-footer"},[n("button",{staticClass:"btn btn-default",attrs:{type:"button","data-dismiss":"modal"}},[t._v("\n            Close\n          ")])])])])])],2)}),[],!1,null,null,null);e.default=b.exports}}]);
=======
(window.webpackJsonp=window.webpackJsonp||[]).push([[58],{263:function(t,e,s){var n={"./af":83,"./af.js":83,"./ar":84,"./ar-dz":85,"./ar-dz.js":85,"./ar-kw":86,"./ar-kw.js":86,"./ar-ly":87,"./ar-ly.js":87,"./ar-ma":88,"./ar-ma.js":88,"./ar-sa":89,"./ar-sa.js":89,"./ar-tn":90,"./ar-tn.js":90,"./ar.js":84,"./az":91,"./az.js":91,"./be":92,"./be.js":92,"./bg":93,"./bg.js":93,"./bm":94,"./bm.js":94,"./bn":95,"./bn.js":95,"./bo":96,"./bo.js":96,"./br":97,"./br.js":97,"./bs":98,"./bs.js":98,"./ca":99,"./ca.js":99,"./cs":100,"./cs.js":100,"./cv":101,"./cv.js":101,"./cy":102,"./cy.js":102,"./da":103,"./da.js":103,"./de":104,"./de-at":105,"./de-at.js":105,"./de-ch":106,"./de-ch.js":106,"./de.js":104,"./dv":107,"./dv.js":107,"./el":108,"./el.js":108,"./en-SG":109,"./en-SG.js":109,"./en-au":110,"./en-au.js":110,"./en-ca":111,"./en-ca.js":111,"./en-gb":112,"./en-gb.js":112,"./en-ie":113,"./en-ie.js":113,"./en-il":114,"./en-il.js":114,"./en-nz":115,"./en-nz.js":115,"./eo":116,"./eo.js":116,"./es":117,"./es-do":118,"./es-do.js":118,"./es-us":119,"./es-us.js":119,"./es.js":117,"./et":120,"./et.js":120,"./eu":121,"./eu.js":121,"./fa":122,"./fa.js":122,"./fi":123,"./fi.js":123,"./fo":124,"./fo.js":124,"./fr":125,"./fr-ca":126,"./fr-ca.js":126,"./fr-ch":127,"./fr-ch.js":127,"./fr.js":125,"./fy":128,"./fy.js":128,"./ga":129,"./ga.js":129,"./gd":130,"./gd.js":130,"./gl":131,"./gl.js":131,"./gom-latn":132,"./gom-latn.js":132,"./gu":133,"./gu.js":133,"./he":134,"./he.js":134,"./hi":135,"./hi.js":135,"./hr":136,"./hr.js":136,"./hu":137,"./hu.js":137,"./hy-am":138,"./hy-am.js":138,"./id":139,"./id.js":139,"./is":140,"./is.js":140,"./it":141,"./it-ch":142,"./it-ch.js":142,"./it.js":141,"./ja":143,"./ja.js":143,"./jv":144,"./jv.js":144,"./ka":145,"./ka.js":145,"./kk":146,"./kk.js":146,"./km":147,"./km.js":147,"./kn":148,"./kn.js":148,"./ko":149,"./ko.js":149,"./ku":150,"./ku.js":150,"./ky":151,"./ky.js":151,"./lb":152,"./lb.js":152,"./lo":153,"./lo.js":153,"./lt":154,"./lt.js":154,"./lv":155,"./lv.js":155,"./me":156,"./me.js":156,"./mi":157,"./mi.js":157,"./mk":158,"./mk.js":158,"./ml":159,"./ml.js":159,"./mn":160,"./mn.js":160,"./mr":161,"./mr.js":161,"./ms":162,"./ms-my":163,"./ms-my.js":163,"./ms.js":162,"./mt":164,"./mt.js":164,"./my":165,"./my.js":165,"./nb":166,"./nb.js":166,"./ne":167,"./ne.js":167,"./nl":168,"./nl-be":169,"./nl-be.js":169,"./nl.js":168,"./nn":170,"./nn.js":170,"./pa-in":171,"./pa-in.js":171,"./pl":172,"./pl.js":172,"./pt":173,"./pt-br":174,"./pt-br.js":174,"./pt.js":173,"./ro":175,"./ro.js":175,"./ru":176,"./ru.js":176,"./sd":177,"./sd.js":177,"./se":178,"./se.js":178,"./si":179,"./si.js":179,"./sk":180,"./sk.js":180,"./sl":181,"./sl.js":181,"./sq":182,"./sq.js":182,"./sr":183,"./sr-cyrl":184,"./sr-cyrl.js":184,"./sr.js":183,"./ss":185,"./ss.js":185,"./sv":186,"./sv.js":186,"./sw":187,"./sw.js":187,"./ta":188,"./ta.js":188,"./te":189,"./te.js":189,"./tet":190,"./tet.js":190,"./tg":191,"./tg.js":191,"./th":192,"./th.js":192,"./tl-ph":193,"./tl-ph.js":193,"./tlh":194,"./tlh.js":194,"./tr":195,"./tr.js":195,"./tzl":196,"./tzl.js":196,"./tzm":197,"./tzm-latn":198,"./tzm-latn.js":198,"./tzm.js":197,"./ug-cn":199,"./ug-cn.js":199,"./uk":200,"./uk.js":200,"./ur":201,"./ur.js":201,"./uz":202,"./uz-latn":203,"./uz-latn.js":203,"./uz.js":202,"./vi":204,"./vi.js":204,"./x-pseudo":205,"./x-pseudo.js":205,"./yo":206,"./yo.js":206,"./zh-cn":207,"./zh-cn.js":207,"./zh-hk":208,"./zh-hk.js":208,"./zh-tw":209,"./zh-tw.js":209};function r(t){var e=o(t);return s(e)}function o(t){if(!s.o(n,t)){var e=new Error("Cannot find module '"+t+"'");throw e.code="MODULE_NOT_FOUND",e}return n[t]}r.keys=function(){return Object.keys(n)},r.resolve=o,t.exports=r,r.id=263},448:function(t,e,s){"use strict";s.d(e,"a",(function(){return l}));var n=s(7),r=s(5);function o(t){return(o="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t})(t)}function a(t,e){for(var s=0;s<e.length;s++){var n=e[s];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(t,n.key,n)}}function c(t,e){return(c=Object.setPrototypeOf||function(t,e){return t.__proto__=e,t})(t,e)}function i(t){var e=function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Date.prototype.toString.call(Reflect.construct(Date,[],(function(){}))),!0}catch(t){return!1}}();return function(){var s,n=j(t);if(e){var r=j(this).constructor;s=Reflect.construct(n,arguments,r)}else s=n.apply(this,arguments);return u(this,s)}}function u(t,e){return!e||"object"!==o(e)&&"function"!=typeof e?function(t){if(void 0===t)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return t}(t):e}function j(t){return(j=Object.setPrototypeOf?Object.getPrototypeOf:function(t){return t.__proto__||Object.getPrototypeOf(t)})(t)}var l=function(t){!function(t,e){if("function"!=typeof e&&null!==e)throw new TypeError("Super expression must either be null or a function");t.prototype=Object.create(e&&e.prototype,{constructor:{value:t,writable:!0,configurable:!0}}),e&&c(t,e)}(u,t);var e,s,n,o=i(u);function u(t){var e;return function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,u),(e=o.call(this,t)).indexUrl=r.X,e.namePlural="users",e.nameLowerCase="user",e}return e=u,(s=[{key:"exportSheet",value:function(){return Api.get(this.indexUrl+"/excel-export")}}])&&a(e.prototype,s),n&&a(e,n),u}(n.a)},464:function(t,e,s){var n=s(513);"string"==typeof n&&(n=[[t.i,n,""]]);var r={hmr:!0,transform:void 0,insertInto:void 0};s(10)(n,r);n.locals&&(t.exports=n.locals)},512:function(t,e,s){"use strict";var n=s(464);s.n(n).a},513:function(t,e,s){(t.exports=s(9)(!1)).push([t.i,"\n.cursor[data-v-45d58e39] {\n  cursor: pointer;\n}\n",""])},547:function(t,e,s){"use strict";s.r(e);var n=s(448),r=s(2),o=s.n(r),a=s(3),c=s(8);function i(t,e){var s=Object.keys(t);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(t);e&&(n=n.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),s.push.apply(s,n)}return s}function u(t,e,s){return e in t?Object.defineProperty(t,e,{value:s,enumerable:!0,configurable:!0,writable:!0}):t[e]=s,t}var j={name:"EliteRequestListIndex",mixins:[a.b,a.a],data:function(){return{rows:{data:[],links:{},meta:{}},columns:["GUID","Full_Name","Address","gogoPoint","Total Spent on Orders","Joined On","Last Login"],model:new n.a}},methods:{formatDate:function(t){return o()(t).format("LL")},copy:function(t){var e=document.createElement("textarea");document.body.appendChild(e),e.value=t,e.select(),document.execCommand("copy"),document.body.removeChild(e),alertMessage("Content copied to clipboard.")},getList:function(){var t=this;axios.get(this.model.indexUrl+"/elite-request-list").then((function(e){t.rows=e.data}))}},created:function(){this.getList()},mounted:function(){},computed:function(t){for(var e=1;e<arguments.length;e++){var s=null!=arguments[e]?arguments[e]:{};e%2?i(Object(s),!0).forEach((function(e){u(t,e,s[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(s)):i(Object(s)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(s,e))}))}return t}({},Object(c.mapGetters)(["authUser"])),watch:{}},l=(s(512),s(1)),f=Object(l.a)(j,(function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("app-card",{attrs:{title:"App <b>Users</b>","body-padding":"0"}},[s("app-table-sortable",{attrs:{columns:t.columns,rows:t.rows,paginate:!0},scopedSlots:t._u([{key:"default",fn:function(e){var n=e.row;return[s("td",{staticStyle:{cursor:"pointer"},attrs:{title:"click to copy"},on:{click:function(e){return t.copy(n.userId)}}},[t._v("\n        "+t._s(n.userId)+"\n      ")]),t._v(" "),s("td",[t._v("\n        "+t._s(n.firstName)+" "+t._s(n.lastName)+" "),s("br"),t._v(" "),s("span",{staticClass:"badge"},[t._v(" "+t._s(n.phone))]),t._v(" /\n        "),s("span",{staticClass:"badge"},[t._v(t._s(n.email?n.email:"-"))])]),t._v(" "),s("td",[t._v(t._s(n.address?n.address:"-"))]),t._v(" "),s("td",[t._v(t._s(n.gogoWallet))]),t._v(" "),s("td",[t._v("Nrs. "+t._s(n.totalSpentOnOrder))]),t._v(" "),s("td",[t._v(t._s(t.formatDate(n.createdAt)))]),t._v(" "),s("td",[t._v(t._s(n.recentLogin))]),t._v(" "),s("td",{attrs:{width:"100"}})]}}])})],1)}),[],!1,null,"45d58e39",null);e.default=f.exports}}]);
>>>>>>> master
