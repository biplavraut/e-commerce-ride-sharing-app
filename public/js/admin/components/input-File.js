(window.webpackJsonp=window.webpackJsonp||[]).push([[10],{172:function(e,t,r){"use strict";r.r(t);var a={name:"InputFile",inheritAttrs:!1,props:{value:{},required:{type:Boolean,default:!1},label:{type:String,required:!0},className:{type:String},errorText:{type:String,default:""}},methods:{emitEvent:function(e){var t=e.target.files[0]||"";this.$emit("input",t)}},computed:{placeholder:function(){return this.$attrs.placeholder}}},l=r(2),s=Object(l.a)(a,function(){var e=this,t=e.$createElement,r=e._self._c||t;return r("div",{staticClass:"form-group"},[r("label",[e._v(e._s(e.label)+"\n    "),e.required?r("small",[e._v("*")]):e._e()]),e._v(" "),r("input",{staticClass:"form-control",attrs:{type:"text",placeholder:e.placeholder,readonly:""}}),e._v(" "),r("input",e._b({staticClass:"form-control",class:e.className,attrs:{type:"file",required:e.required},on:{change:e.emitEvent}},"input",e.$attrs,!1)),e._v(" "),""!==e.errorText?r("small",{staticClass:"text-danger"},[e._v("* "+e._s(e.errorText))]):e._e()])},[],!1,null,"29385c6e",null);t.default=s.exports}}]);