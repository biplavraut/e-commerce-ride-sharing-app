(window.webpackJsonp=window.webpackJsonp||[]).push([[89],{521:function(e,t,a){var r=a(585);"string"==typeof r&&(r=[[e.i,r,""]]);var s={hmr:!0,transform:void 0,insertInto:void 0};a(9)(r,s);r.locals&&(e.exports=r.locals)},584:function(e,t,a){"use strict";var r=a(521);a.n(r).a},585:function(e,t,a){(e.exports=a(8)(!1)).push([e.i,"\n.color[data-v-bc4c6cf6] {\n  background: #1b4bf9;\n}\n",""])},657:function(e,t,a){"use strict";a.r(t);var r=a(0),s=a.n(r),o=a(10),i=a(3),n=(a(18),a(30)),c=a(4),l=(a(236),a(12)),u=a.n(l),d=a(14);function p(e,t){var a=Object.keys(e);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(e);t&&(r=r.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),a.push.apply(a,r)}return a}function m(e,t,a){return t in e?Object.defineProperty(e,t,{value:a,enumerable:!0,configurable:!0,writable:!0}):e[t]=a,e}function g(e,t,a,r,s,o,i){try{var n=e[o](i),c=n.value}catch(e){return void a(e)}n.done?t(c):Promise.resolve(c).then(r,s)}function v(e){return function(){var t=this,a=arguments;return new Promise((function(r,s){var o=e.apply(t,a);function i(e){g(o,r,s,i,n,"next",e)}function n(e){g(o,r,s,i,n,"throw",e)}i(void 0)}))}}var f={name:"ProductCompare",mixins:[c.d,c.c],data:function(){return{form:new i.a({productCategoryId:"",title:"",slug:"",code:"",price:"",hide:!1,openingStock:0,description:"",discountType:"amount",discount:0,size:"",color:"",batchNo:"",expireDate:"",unit:"",vatPercentage:0,serviceChargePercentage:0,update:{}}),serverErrors:new d.a,tags:{},sizes:{S:"S",M:"M",L:"L",XL:"XL",XXL:"XXL"},colors:{blue:"blue",green:"green",red:"red",yellow:"yellow",purple:"purple",white:"white",black:"black"},edit:!1,model:new n.a,categories:[],subCategories:[],subChildCategories:[],units:[],discountTypes:[{id:"amount",name:"Amount"},{id:"percent",name:"Percent"}],images:[],subCategoryId:"",subChildCategoryId:"",updateSubCategoryId:"",updateSubChildCategoryId:""}},methods:{getData:function(){var e=this;return v(s.a.mark((function t(){var a,r,o,n,c,l,u,d,p,m,g,v,f,b,C,h,y,x,_,w,k,S;return s.a.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return t.next=2,e.model.show(e.$route.params.id);case 2:a=t.sent,r=a.data,o=r.title,n=r.slug,c=r.description,l=r.price,u=r.category,d=r.subCategory,p=r.subChildCategory,m=r.openingStock,g=r.code,v=r.discountType,f=r.discount,b=r.size,C=r.color,h=r.batchNo,y=r.expireDate,x=r.unit,_=r.hide,w=r.serviceChargePercentage,k=r.vatPercentage,S=r.update,e.form=new i.a({title:o,slug:n,price:l,description:c,productCategoryId:u.id,openingStock:m,code:g,discountType:v,discount:f,size:b,color:C,batchNo:h,expireDate:y,unit:x,hide:_,serviceChargePercentage:w,vatPercentage:k,update:S}),e.subCategoryId=d.id,e.subChildCategoryId=p.id,e.form.update.productCategoryId=S.category.id,e.updateSubCategoryId=S.subCategory.id,e.updateSubChildCategoryId=S.subChildCategory.id;case 10:case"end":return t.stop()}}),t)})))()},getCategories:function(){var e=this;return v(s.a.mark((function t(){var a;return s.a.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return t.next=2,e.model.getCategory();case 2:a=t.sent,e.categories=a.data.map((function(e){return{id:e.id,name:e.name+" ("+e.slug+")"}}));case 4:case"end":return t.stop()}}),t)})))()},getSubCategory:function(){var e=arguments,t=this;return v(s.a.mark((function a(){var r,o;return s.a.wrap((function(a){for(;;)switch(a.prev=a.next){case 0:return r=e.length>0&&void 0!==e[0]?e[0]:"",a.next=3,t.model.getSubCategory(r);case 3:0==(o=a.sent).data.length&&(t.subCategoryId=null,t.subChildCategoryId=null),t.subCategories=o.data.map((function(e){return{id:e.id,name:e.name}}));case 6:case"end":return a.stop()}}),a)})))()},getSubChildCategory:function(){var e=arguments,t=this;return v(s.a.mark((function a(){var r,o;return s.a.wrap((function(a){for(;;)switch(a.prev=a.next){case 0:return r=e.length>0&&void 0!==e[0]?e[0]:"",a.next=3,t.model.getSubCategory(r);case 3:0==(o=a.sent).data.length&&(t.subChildCategoryId=null),t.subChildCategories=o.data.map((function(e){return{id:e.id,name:e.name}}));case 6:case"end":return a.stop()}}),a)})))()},revert:function(){var e=this;confirm("Are you sure? You want to process this action.")&&this.$route.params.id&&u.a.get(this.model.indexUrl+"/revert-vendor-update?id="+this.$route.params.id).then((function(t){"success"===t.data?(alertMessage("Successfully Reverted to Original."),e.$router.push({name:"product.index"})):alertMessage("Something went wrong.","danger")}))},saveChange:function(){var e=this;confirm("Are you sure? You want to process this action.")&&this.$route.params.id&&u.a.get(this.model.indexUrl+"/update-change?id="+this.$route.params.id).then((function(t){"success"===t.data?(alertMessage("Successfully updates the vendor changes."),e.$router.push({name:"product.index"})):alertMessage("Something went wrong.","danger")}))}},mounted:function(){this.edit=this.$route.params.hasOwnProperty("id"),this.getCategories(),this.edit&&(this.imageUrl=Helpers.loadingImage(),this.getData(),this.getSubCategory(this.form.update.productCategoryId))},created:function(){this.$route.params.idx&&(this.$route.params.id=this.$route.params.idx)},computed:function(e){for(var t=1;t<arguments.length;t++){var a=null!=arguments[t]?arguments[t]:{};t%2?p(Object(a),!0).forEach((function(t){m(e,t,a[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(a)):p(Object(a)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(a,t))}))}return e}({},Object(o.mapGetters)(["authUser"])),watch:{"form.productCategoryId":function(e){this.edit&&this.getSubCategory(e)},subCategoryId:function(e){this.edit&&this.getSubChildCategory(e)},updateSubCategoryId:function(e){this.edit&&this.getSubChildCategory(e)}}},b=(a(584),a(1)),C=Object(b.a)(f,(function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("app-card",{attrs:{title:"Comparing <b>"+e.form.title+"</b>"}},[a("form",{on:{submit:function(t){return t.preventDefault(),e.saveData(t)}}},[a("div",{staticClass:"row"},[a("div",{staticClass:"col-md-6"},[a("div",{staticClass:"row"},[a("div",{staticClass:"col-md-10"},[a("h3",{staticClass:"my-sub-heading color"},[e._v("Original")])])]),e._v(" "),a("div",{staticClass:"row"},[a("div",{class:e.subCategories.length>0&&e.subChildCategories.length>0?"col-md-4":e.subCategories.length>0?"col-md-6":"col-md-12"},[a("input-select",{attrs:{name:"category",label:"Category",options:e.categories},on:{input:function(t){return e.getSubCategory(e.form.productCategoryId)}},model:{value:e.form.productCategoryId,callback:function(t){e.$set(e.form,"productCategoryId",t)},expression:"form.productCategoryId"}}),e._v(" "),e.errors.any("category")?a("small",{staticClass:"text-center text-danger"},[e._v("* "+e._s(e.errors.first("category")))]):e._e()],1),e._v(" "),a("div",{class:e.subCategories.length>0&&e.subChildCategories.length>0?"col-md-4":e.subCategories.length>0?"col-md-6":"hide"},[a("input-select",{attrs:{label:"Sub-Category",options:e.subCategories},on:{input:function(t){return e.getSubChildCategory(e.subCategoryId)}},model:{value:e.subCategoryId,callback:function(t){e.subCategoryId=t},expression:"subCategoryId"}})],1),e._v(" "),a("div",{class:e.subChildCategories.length>0?"col-md-4":"hide"},[a("input-select",{attrs:{label:"Sub-Child-Category",options:e.subChildCategories},model:{value:e.subChildCategoryId,callback:function(t){e.subChildCategoryId=t},expression:"subChildCategoryId"}})],1)]),e._v(" "),a("div",{staticClass:"row"},[a("div",{staticClass:"col-md-6"},[a("input-text",{directives:[{name:"validate",rawName:"v-validate",value:"required",expression:"'required'"}],attrs:{label:"Name",name:"title","error-text":e.errors.first("title"),required:""},model:{value:e.form.title,callback:function(t){e.$set(e.form,"title",t)},expression:"form.title"}})],1),e._v(" "),a("div",{staticClass:"col-md-6"},[a("input-text",{directives:[{name:"validate",rawName:"v-validate",value:"required",expression:"'required'"}],attrs:{label:"Slug",name:"slug","error-text":e.errors.first("slug"),required:""},model:{value:e.form.slug,callback:function(t){e.$set(e.form,"slug",t)},expression:"form.slug"}})],1)]),e._v(" "),a("div",{staticClass:"row"},[a("div",{staticClass:"col-md-12"},[a("input-text",{attrs:{label:"Stock",type:"number",name:"opening_stock",min:"0"},model:{value:e.form.openingStock,callback:function(t){e.$set(e.form,"openingStock",t)},expression:"form.openingStock"}})],1)]),e._v(" "),a("div",{staticClass:"row"},[a("div",{staticClass:"col-md-6"},[a("label",[e._v("Size")]),e._v(" "),a("input-tags",{attrs:{"element-id":"sizes","typeahead-style":"dropdown",typeahead:!0,"existing-tags":e.sizes},model:{value:e.form.size,callback:function(t){e.$set(e.form,"size",t)},expression:"form.size"}})],1),e._v(" "),a("div",{staticClass:"col-md-6"},[a("label",[e._v("Color")]),e._v(" "),a("input-tags",{attrs:{"element-id":"colors","typeahead-style":"dropdown",typeahead:!0,"existing-tags":e.colors},model:{value:e.form.color,callback:function(t){e.$set(e.form,"color",t)},expression:"form.color"}})],1)]),e._v(" "),a("div",{staticClass:"row"},[a("div",{staticClass:"col-md-4"},[a("input-text",{attrs:{label:"Batch No",type:"number",name:"batch_no"},model:{value:e.form.batchNo,callback:function(t){e.$set(e.form,"batchNo",t)},expression:"form.batchNo"}})],1),e._v(" "),a("div",{staticClass:"col-md-4"},[a("input-text",{attrs:{label:"Expiration Date",type:"date",name:"expire_date"},model:{value:e.form.expireDate,callback:function(t){e.$set(e.form,"expireDate",t)},expression:"form.expireDate"}})],1),e._v(" "),a("div",{staticClass:"col-md-4"},[a("input-text",{attrs:{label:"Unit",type:"text",name:"unit"},model:{value:e.form.unit,callback:function(t){e.$set(e.form,"unit",t)},expression:"form.unit"}})],1)]),e._v(" "),a("div",{staticClass:"row"},[a("div",{staticClass:"row"},[a("div",{staticClass:"col-md-10"},[a("h3",{staticClass:"my-sub-heading"},[e._v("Price Related")])])]),e._v(" "),a("div",{staticClass:"col-md-6"},[a("input-select",{attrs:{name:"discount_type",label:"Discount Type",options:e.discountTypes},model:{value:e.form.discountType,callback:function(t){e.$set(e.form,"discountType",t)},expression:"form.discountType"}})],1),e._v(" "),a("div",{staticClass:"col-md-6"},[a("input-text",{attrs:{type:"number",min:"0",max:"100",label:"Discount",name:"discount"},model:{value:e.form.discount,callback:function(t){e.$set(e.form,"discount",t)},expression:"form.discount"}})],1),e._v(" "),a("div",{staticClass:"col-md-4"},[a("input-text",{directives:[{name:"validate",rawName:"v-validate",value:"required",expression:"'required'"}],attrs:{label:"MRP Price",type:"text",name:"price","error-text":e.errors.first("price"),required:""},model:{value:e.form.price,callback:function(t){e.$set(e.form,"price",t)},expression:"form.price"}})],1),e._v(" "),a("div",{staticClass:"col-md-4"},[a("input-text",{attrs:{label:"Vat Percentage",type:"number",name:"vatPercentage"},model:{value:e.form.vatPercentage,callback:function(t){e.$set(e.form,"vatPercentage",t)},expression:"form.vatPercentage"}})],1),e._v(" "),a("div",{staticClass:"col-md-4"},[a("input-text",{attrs:{label:"Service Charge Percentage",type:"number",name:"serviceChargePercentage"},model:{value:e.form.serviceChargePercentage,callback:function(t){e.$set(e.form,"serviceChargePercentage",t)},expression:"form.serviceChargePercentage"}})],1)]),e._v(" "),a("app-quill-editor",{key:0,attrs:{label:"Description",name:"description","error-text":e.errors.first("description")},model:{value:e.form.description,callback:function(t){e.$set(e.form,"description",t)},expression:"form.description"}}),e._v(" "),a("div",{staticClass:"text-right"},[a("button",{staticClass:"btn btn-danger",attrs:{type:"button"},on:{click:function(t){return t.preventDefault(),e.revert()}}},[e._v("\n            Revert to Original\n          ")])])],1),e._v(" "),a("div",{staticClass:"col-md-6"},[a("div",{staticClass:"row text-right"},[a("div",{staticClass:"col-md-10"},[a("h3",{staticClass:"my-sub-heading color"},[e._v("Updates By Vendor")])])]),e._v(" "),a("div",{staticClass:"row"},[a("div",{class:e.subCategories.length>0&&e.subChildCategories.length>0?"col-md-4":e.subCategories.length>0?"col-md-6":"col-md-12"},[a("input-select",{attrs:{name:"category",label:"Category",options:e.categories},on:{input:function(t){return e.getSubCategory(e.form.update.productCategoryId)}},model:{value:e.form.update.productCategoryId,callback:function(t){e.$set(e.form.update,"productCategoryId",t)},expression:"form.update.productCategoryId"}}),e._v(" "),e.errors.any("category")?a("small",{staticClass:"text-center text-danger"},[e._v("* "+e._s(e.errors.first("category")))]):e._e()],1),e._v(" "),a("div",{class:e.subCategories.length>0&&e.subChildCategories.length>0?"col-md-4":e.subCategories.length>0?"col-md-6":"hide"},[a("input-select",{attrs:{label:"Sub-Category",options:e.subCategories},on:{input:function(t){return e.getSubChildCategory(e.updateSubCategoryId)}},model:{value:e.updateSubCategoryId,callback:function(t){e.updateSubCategoryId=t},expression:"updateSubCategoryId"}})],1),e._v(" "),a("div",{class:e.subChildCategories.length>0?"col-md-4":"hide"},[a("input-select",{attrs:{label:"Sub-Child-Category",options:e.subChildCategories},model:{value:e.updateSubChildCategoryId,callback:function(t){e.updateSubChildCategoryId=t},expression:"updateSubChildCategoryId"}})],1)]),e._v(" "),a("div",{staticClass:"row"},[a("div",{staticClass:"col-md-6"},[a("input-text",{directives:[{name:"validate",rawName:"v-validate",value:"required",expression:"'required'"}],attrs:{label:"Name",name:"title","error-text":e.errors.first("title"),required:""},model:{value:e.form.update.title,callback:function(t){e.$set(e.form.update,"title",t)},expression:"form.update.title"}})],1),e._v(" "),a("div",{staticClass:"col-md-6"},[a("input-text",{directives:[{name:"validate",rawName:"v-validate",value:"required",expression:"'required'"}],attrs:{label:"Slug",name:"slug","error-text":e.errors.first("slug"),required:""},model:{value:e.form.update.slug,callback:function(t){e.$set(e.form.update,"slug",t)},expression:"form.update.slug"}})],1)]),e._v(" "),a("div",{staticClass:"row"},[a("div",{staticClass:"col-md-12"},[a("input-text",{attrs:{label:"Stock",type:"number",name:"opening_stock",min:"0"},model:{value:e.form.update.openingStock,callback:function(t){e.$set(e.form.update,"openingStock",t)},expression:"form.update.openingStock"}})],1)]),e._v(" "),a("div",{staticClass:"row"},[a("div",{staticClass:"col-md-6"},[a("label",[e._v("Size")]),e._v(" "),a("input-tags",{attrs:{"element-id":"sizes","typeahead-style":"dropdown",typeahead:!0,"existing-tags":e.sizes},model:{value:e.form.update.size,callback:function(t){e.$set(e.form.update,"size",t)},expression:"form.update.size"}})],1),e._v(" "),a("div",{staticClass:"col-md-6"},[a("label",[e._v("Color")]),e._v(" "),a("input-tags",{attrs:{"element-id":"colors","typeahead-style":"dropdown",typeahead:!0,"existing-tags":e.colors},model:{value:e.form.update.color,callback:function(t){e.$set(e.form.update,"color",t)},expression:"form.update.color"}})],1)]),e._v(" "),a("div",{staticClass:"row"},[a("div",{staticClass:"col-md-4"},[a("input-text",{attrs:{label:"Batch No",type:"number",name:"batch_no"},model:{value:e.form.update.batchNo,callback:function(t){e.$set(e.form.update,"batchNo",t)},expression:"form.update.batchNo"}})],1),e._v(" "),a("div",{staticClass:"col-md-4"},[a("input-text",{attrs:{label:"Expiration Date",type:"date",name:"expire_date"},model:{value:e.form.update.expireDate,callback:function(t){e.$set(e.form.update,"expireDate",t)},expression:"form.update.expireDate"}})],1),e._v(" "),a("div",{staticClass:"col-md-4"},[a("input-text",{attrs:{label:"Unit",type:"text",name:"unit"},model:{value:e.form.update.unit,callback:function(t){e.$set(e.form.update,"unit",t)},expression:"form.update.unit"}})],1)]),e._v(" "),a("div",{staticClass:"row"},[a("div",{staticClass:"row"},[a("div",{staticClass:"col-md-10"},[a("h3",{staticClass:"my-sub-heading"},[e._v("Price Related")])])]),e._v(" "),a("div",{staticClass:"col-md-6"},[a("input-select",{attrs:{name:"discount_type",label:"Discount Type",options:e.discountTypes},model:{value:e.form.update.discountType,callback:function(t){e.$set(e.form.update,"discountType",t)},expression:"form.update.discountType"}})],1),e._v(" "),a("div",{staticClass:"col-md-6"},[a("input-text",{attrs:{type:"number",min:"0",max:"100",label:"Discount",name:"discount"},model:{value:e.form.update.discount,callback:function(t){e.$set(e.form.update,"discount",t)},expression:"form.update.discount"}})],1),e._v(" "),a("div",{staticClass:"col-md-4"},[a("input-text",{directives:[{name:"validate",rawName:"v-validate",value:"required",expression:"'required'"}],attrs:{label:"MRP Price",type:"text",name:"price","error-text":e.errors.first("price"),required:""},model:{value:e.form.update.price,callback:function(t){e.$set(e.form.update,"price",t)},expression:"form.update.price"}})],1),e._v(" "),a("div",{staticClass:"col-md-4"},[a("input-text",{attrs:{label:"Vat Percentage",type:"number",name:"vatPercentage"},model:{value:e.form.update.vatPercentage,callback:function(t){e.$set(e.form.update,"vatPercentage",t)},expression:"form.update.vatPercentage"}})],1),e._v(" "),a("div",{staticClass:"col-md-4"},[a("input-text",{attrs:{label:"Service Charge Percentage",type:"number",name:"serviceChargePercentage"},model:{value:e.form.update.serviceChargePercentage,callback:function(t){e.$set(e.form.update,"serviceChargePercentage",t)},expression:"form.update.serviceChargePercentage"}})],1)]),e._v(" "),a("app-quill-editor",{key:0,attrs:{label:"Description",name:"description","error-text":e.errors.first("description")},model:{value:e.form.update.description,callback:function(t){e.$set(e.form.update,"description",t)},expression:"form.update.description"}}),e._v(" "),a("div",{staticClass:"text-right"},[a("button",{staticClass:"btn btn-success",attrs:{type:"button"},on:{click:function(t){return t.preventDefault(),e.saveChange()}}},[e._v("\n            Save Changes\n          ")])])],1)])])])}),[],!1,null,"bc4c6cf6",null);t.default=C.exports}}]);