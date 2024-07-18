import Vue from "vue";
import Vuex from "vuex";
import * as module from "./modules";

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        csrf: document.querySelector('meta[name="csrf-token"]').content,
    },

    // same as computed properties from state
    getters: {
        csrf(state) {
            return state.csrf;
        },
    },

    // for sync actions
    mutations: {},

    // for async actions
    actions: {},

    modules: {
        homePageCounts: module.homePageCounts,
        settings: module.settings,
        authUser: module.authUser,
        card: module.card,
        category: module.cache,
        testimonial: module.cache,
        news: module.cache,
        social: module.cache,
        notification: module.cache,
        productcategory: module.cache,
        unit: module.cache,
        vendor: module.cache,
        product: module.cache,
        driver: module.cache,
        user: module.cache,
        premiumplace: module.cache,
        ridingfare: module.cache,
        rentalpackage: module.cache,
        rentaltrip: module.cache,
        outstationtrip: module.cache,
        launchpadcategory: module.cache,
        launchpad: module.cache,
        slider: module.cache,
        productoptioncategory: module.cache,
        vendoroptioncategory: module.cache,
        order: module.cache,
        superuser: module.cache,
        delivery: module.cache,
        trip: module.cache,
        coupon: module.cache,
        donation: module.cache,
        globalnotification: module.cache,
        roadblocknotification: module.cache,
        faq: module.cache,
        ad: module.cache,
        discount: module.cache,
        send: module.cache,
        pool: module.cache,
        items: module.cache,
        paymentlog: module.cache,
        defaultconf: module.cache,
        partner: module.cache,
        package: module.cache,
        inhousepilot: module.cache,
        vendorreview: module.cache,
        layoutmanager: module.cache,
        deliveryjunction: module.cache,
        elite: module.cache,
        campaign: module.cache,
        orderfeedback: module.cache,
        voucher: module.cache,
        deal: module.cache,
        vendoroption: module.cache,
        productoption: module.cache,
        deliverydriver: module.cache,
        vendordiscount: module.cache,
        addservice: module.cache,
        academyslider: module.cache,
        academycontent: module.cache,
        utilitycoupon: module.cache,
        utilityvoucher: module.cache,
        dinein: module.cache,
        orderofferConf: module.cache,
        websiteslider: module.cache,
        orderreturn: module.cache,
        prescriptionrequest: module.cache,
        walletlog: module.cache,
        hospital: module.cache,
        rideofferConf: module.cache,
    },
});