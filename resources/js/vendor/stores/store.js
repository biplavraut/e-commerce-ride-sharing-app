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
    notification: module.cache,
    product: module.cache,
    order: module.cache,
    productqa: module.cache,
    productreview: module.cache,
    dinein: module.cache,
  },
});
