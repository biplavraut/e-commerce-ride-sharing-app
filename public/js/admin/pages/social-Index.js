(window.webpackJsonp=window.webpackJsonp||[]).push([[62],{352:function(t,e,n){"use strict";n.r(e);var a=n(0),r=n.n(a),s=n(275),o=n(4);function i(t,e,n,a,r,s,o){try{var i=t[s](o),c=i.value}catch(t){return void n(t)}i.done?e(c):Promise.resolve(c).then(a,r)}var c={name:"SocialIndex",mixins:[o.b,o.a],data:function(){return{columns:["Icon","Name","Url"],rows:{data:[],links:{},meta:{}},model:new s.a}},methods:{changeOrder:function(t){var e,n=this;return(e=r.a.mark((function e(){return r.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,n.model.changeOrder(t);case 2:alertMessage("Order changed successfully.");case 3:case"end":return e.stop()}}),e)})),function(){var t=this,n=arguments;return new Promise((function(a,r){var s=e.apply(t,n);function o(t){i(s,a,r,o,c,"next",t)}function c(t){i(s,a,r,o,c,"throw",t)}o(void 0)}))})()}},mounted:function(){this.getModels()}},d=n(1),l=Object(d.a)(c,(function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("app-card",{attrs:{title:"All <b>Socials</b>","body-padding":"0"}},[n("template",{slot:"actions"},[n("app-btn-link",{attrs:{"route-name":"social.create"}},[t._v("Add New")])],1),t._v(" "),n("app-table-sortable",{attrs:{columns:t.columns,paginate:!1,sortable:"",rows:t.rows},on:{orderHasChanged:t.changeOrder},scopedSlots:t._u([{key:"default",fn:function(e){var a=e.row;return[n("td",{attrs:{width:"100"}},[n("img",{staticStyle:{width:"50px",height:"50px"},attrs:{src:a.icon50}})]),t._v(" "),n("td",[t._v(t._s(a.name))]),t._v(" "),n("td",[n("a",{attrs:{href:a.url,target:"_blank"}},[t._v(t._s(a.url))])]),t._v(" "),n("td",{attrs:{width:"100"}},[n("app-actions",{attrs:{actions:{edit:{name:"social.edit",params:{id:a.id}},delete:a.id}},on:{deleteItem:t.deleteModel}})],1)]}}])})],2)}),[],!1,null,null,null);e.default=l.exports}}]);