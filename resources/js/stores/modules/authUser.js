export default {
  namespaced: false,

  state: {
    authUser: {},
  },

  getters: {
    authUser(state) {
      return state.authUser;
    },
  },

  mutations: {
    setAuthUser(state, authUser) {
      state.authUser = authUser;
    },
  }
};