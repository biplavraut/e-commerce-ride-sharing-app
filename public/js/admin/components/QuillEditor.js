(window.webpackJsonp=window.webpackJsonp||[]).push([[3],{165:function(e,t,r){"use strict";r.r(t);var i=r(212),o=r(211);i.Quill.register("modules/imageDrop",o.ImageDrop);var l={name:"QuillEditor",components:{VueEditor:i.VueEditor},inheritAttrs:!1,props:{value:{type:String},required:{type:Boolean,default:!1},label:{type:String,required:!0},errorText:{type:String,default:""}},data:function(){return{customToolbar:[[{font:[!1,"serif","monospace"]}],[{header:[1,2,3,4,5,6,!1]}],["bold","italic","underline"],[{align:""},{align:"center"},{align:"right"},{align:"justify"}],["blockquote","code-block"],[{script:"sub"},{script:"super"}],[{list:"ordered"},{list:"bullet"},{list:"check"}],[{indent:"-1"},{indent:"+1"}],[{color:[]},{background:[]}],["link","image"],["clean"]],editorSettings:{modules:{imageDrop:!0}}}},computed:{listeners:function(){return this.$listeners}}},n=r(2),s=Object(n.a)(l,function(){var e=this,t=e.$createElement,r=e._self._c||t;return r("div",{staticClass:"form-group"},[r("label",[e._v("\n    "+e._s(e.label)+"\n    "),e.required?r("small",[e._v("*")]):e._e()]),e._v(" "),r("vue-editor",e._g(e._b({attrs:{value:e.value,editorToolbar:e.customToolbar,editorOptions:e.editorSettings}},"vue-editor",e.$attrs,!1),e.listeners)),e._v(" "),""!==e.errorText?r("small",{staticClass:"text-danger"},[e._v("* "+e._s(e.errorText)+"\n  ")]):e._e()],1)},[],!1,null,"50f68f68",null);t.default=s.exports}}]);