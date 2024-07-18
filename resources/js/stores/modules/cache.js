export default {
  namespaced: true,

  state: () => ({
    list       : {},
    localAccess: false,
  }),

  getters: {
    list(state) {
      return state.list;
    },
    localAccess(state) {
      return state.localAccess;
    }
  },

  mutations: {
    localAccess(state, value) {
      state.localAccess = value;
    },

    setList(state, value) {
      state.list = value;
    }
  }
};