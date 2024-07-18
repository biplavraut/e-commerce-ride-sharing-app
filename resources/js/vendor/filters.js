import Vue from "vue";

Vue.filter("fillEmpty", function (value) {
  return (value === "" || value === null) ? "-" : value;
});
