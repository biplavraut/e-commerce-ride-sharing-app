(window.webpackJsonp=window.webpackJsonp||[]).push([[8],{170:function(t,e,a){"use strict";a.r(e);var i={name:"Chart",props:{id:{type:String,default:"bar-chart-"+parseInt(1e4*Math.random())},type:{type:String,default:"bar"},width:{type:String,default:"400"},height:{type:String,default:"200"},xLabels:{type:Array,required:!0},datasets:{required:!0}},mounted:function(){var t=document.getElementById(this.id).getContext("2d");new Chart(t,{type:this.type,data:{labels:this.xLabels,datasets:this.datasets},options:{scales:{yAxes:[{ticks:{beginAtZero:!0}}]}}})}},s=a(2),n=Object(s.a)(i,function(){var t=this.$createElement;return(this._self._c||t)("canvas",{attrs:{id:this.id,width:this.width,height:this.height}})},[],!1,null,"4782e856",null);e.default=n.exports}}]);