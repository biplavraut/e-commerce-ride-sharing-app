(window.webpackJsonp=window.webpackJsonp||[]).push([[6],{168:function(t,a,n){"use strict";n.r(a);var r={name:"Card",props:{title:{type:String,required:!0},bodyPadding:{type:String,default:"15px"}}},o=(n(319),n(2)),e=Object(o.a)(r,function(){var t=this,a=t.$createElement,n=t._self._c||a;return n("div",{staticClass:"card-root"},[t.$store.getters.cardLoading?n("div",{staticClass:"loader-container"},[n("app-loader",{staticClass:"center-loader",attrs:{width:70}})],1):t._e(),t._v(" "),t._t("actions"),t._v(" "),n("h3",{staticClass:"card-title",domProps:{innerHTML:t._s(t.title)}}),t._v(" "),n("div",{staticClass:"card-content",style:{padding:t.bodyPadding}},[t._t("default")],2)],2)},[],!1,null,"3af50a40",null);a.default=e.exports},301:function(t,a,n){var r=n(320);"string"==typeof r&&(r=[[t.i,r,""]]);var o={hmr:!0,transform:void 0,insertInto:void 0};n(185)(r,o);r.locals&&(t.exports=r.locals)},319:function(t,a,n){"use strict";var r=n(301);n.n(r).a},320:function(t,a,n){(t.exports=n(184)(!1)).push([t.i,"\n.card-root[data-v-3af50a40] {\n  position      : relative;\n  margin-bottom : 20px;\n}\n.card-title[data-v-3af50a40] {\n  padding       : 10px 15px;\n  margin        : 0;\n  font-weight   : 400;\n  background    : rgba(255, 255, 255, 0.6);\n  color         : #666666;\n  border-radius : 15px 15px 0 0;\n  border-bottom : 1px dashed #CCCCCC;\n}\n.card-content[data-v-3af50a40] {\n  background-color : #FFFFFF;\n}\n",""])}}]);