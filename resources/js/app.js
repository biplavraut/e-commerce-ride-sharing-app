import router from "@routes/route";

import "@/bootstrap";

import Vue from "vue";

window.Vue = Vue;

import "@/globals.js";
import "@/filters";

import VueRouter from "vue-router";
import VeeValidate from "vee-validate";
import store from "@stores/store";

Vue.use(VueRouter);
Vue.use(VeeValidate);

if (ENV === "production") {
    if (location.protocol !== "https:") {
        location.replace(
            `https:${location.href.substring(location.protocol.length)}`
        );
    }
}

// Add a request interceptor
window.axios.interceptors.request.use(
    function(config) {
        // Do something before request is sent
        store.commit("setCardLoading", true);
        return config;
    },
    function(error) {
        // Do something with request error
        store.commit("setCardLoading", false);
        return Promise.reject(error);
    }
);
// Add a response interceptor
window.axios.interceptors.response.use(
    function(response) {
        // Do something with response data
        store.commit("setCardLoading", false);
        return response;
    },
    function(error) {
        // Do something with response error
        store.commit("setCardLoading", false);
        if (
            error.response.status === 401 &&
            error.response.data.message === "Unauthenticated."
        ) {
            location.href = "/admin";
        }
        return Promise.reject(error);
    }
);

Vue.filter('commaNumberFormat', function(no, budgetType) {
    let from = new Intl.NumberFormat("en-IN", {
        style: "decimal",
        currency: budgetType ? budgetType : "NPR",
    }).format(no);
    if (!no) {
        return '-';
    }
    return from;
});

import "@components/material/GlobalComponents";
import App from "@pages/layouts/App";
import { ENV } from "./env";
// const App = () => import(/* webpackChunkName: "./js/App2" */'@pages/layouts/App');

const app = new Vue({
    el: "#app",
    components: {
        "app-container": App,
    },
    router,
    store,
});