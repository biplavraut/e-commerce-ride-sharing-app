(window.webpackJsonp=window.webpackJsonp||[]).push([[11],{173:function(e,t,i){"use strict";i.r(t);var n={name:"ImageContainer",props:{value:{},name:{type:String,default:"image"},imageUrl:{default:"/images/camera.png"},label:{default:"Image"},required:{type:Boolean,default:!1},width:{default:200},height:{default:200},errorText:{type:String,default:""}},data:function(){return{showDelete:!1,imageFile:"",imageUrlInside:""}},methods:{onFilePicked:function(e){this.imageFile=e.target.files[0]||"",""!==this.imageFile&&this.imageFile.type.indexOf("image")>-1?this.visualizeImage():this.deleteImage()},showFileSelectionDialogue:function(){this.$refs.inputImageFile.click()},deleteImage:function(){this.imageFile=this.$refs.inputImageFile.value="",this.imageUrlInside=this.imageUrl,this.$emit("input",this.imageFile)},visualizeImage:function(){var e=this;this.$emit("input",this.imageFile);var t=new FileReader;t.addEventListener("load",function(){return e.imageUrlInside=t.result}),t.readAsDataURL(this.imageFile)}},computed:{hasError:function(){return""!==this.errorText}},mounted:function(){this.imageUrlInside=this.imageUrl},watch:{imageFile:function(e){this.showDelete=""!==e},imageUrl:function(e){this.imageUrlInside=e,this.showDelete=!1}}},a=(i(323),i(2)),l=Object(a.a)(n,function(){var e=this,t=e.$createElement,i=e._self._c||t;return i("div",{attrs:{tabindex:e.$vnode.key||0}},[i("div",{staticClass:"image-container",style:"width:"+e.width+"px;height:"+e.height+"px;",attrs:{title:"Click to choose"+e.label}},[i("input",{ref:"inputImageFile",attrs:{type:"file",accept:"image/*",name:e.name},on:{change:e.onFilePicked}}),e._v(" "),i("img",{attrs:{src:e.imageUrlInside},on:{click:e.showFileSelectionDialogue}}),e._v(" "),e.showDelete?i("a",{staticClass:"delete-image",attrs:{href:"#"},on:{click:function(t){return t.preventDefault(),e.deleteImage(t)}}},[e._v("Clear")]):e._e()]),e._v(" "),i("div",{staticClass:"text-center"},[i("span",{staticClass:"text-label cursor-pointer",on:{click:e.showFileSelectionDialogue}},[e._v("Choose "+e._s(e.label)+"\n\t\t\t\t"),e.required?i("small",[e._v("*")]):e._e()])]),e._v(" "),e.hasError?i("small",{staticClass:"text-danger text-center",staticStyle:{display:"block"}},[e._v("* "+e._s(e.errorText)+"\n    ")]):e._e()])},[],!1,null,"0d69d7c8",null);t.default=l.exports},303:function(e,t,i){var n=i(324);"string"==typeof n&&(n=[[e.i,n,""]]);var a={hmr:!0,transform:void 0,insertInto:void 0};i(185)(n,a);n.locals&&(e.exports=n.locals)},323:function(e,t,i){"use strict";var n=i(303);i.n(n).a},324:function(e,t,i){(e.exports=i(184)(!1)).push([e.i,".image-container[data-v-0d69d7c8] {\n  background: #EEEEEE;\n  position: relative;\n  overflow: hidden;\n  border: 1px solid #EEEEEE;\n  cursor: pointer;\n  margin: 0 auto;\n}\n.image-container input[type=file][data-v-0d69d7c8] {\n  position: absolute;\n  z-index: -1;\n  width: 100%;\n}\n.image-container img[data-v-0d69d7c8] {\n  width: 100% !important;\n  height: 100% !important;\n}\n.image-container .delete-image[data-v-0d69d7c8] {\n  position: absolute;\n  left: 0;\n  bottom: 0;\n  right: 0;\n  opacity: 0.9;\n  text-align: center;\n  background: red;\n  color: white;\n  height: 30px;\n  line-height: 30px;\n  font-weight: 500;\n}",""])}}]);