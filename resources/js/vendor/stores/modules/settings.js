export default {
  namespaced: false,

  state: {
    settings: {},
  },

  getters: {
    settings(state) {
      return state.settings;
    },
  },

  mutations: {
    setSettings(state, settings) {
      state.settings = settings;
    },
  }
};