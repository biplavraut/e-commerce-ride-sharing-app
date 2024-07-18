export default {
  namespaced: false,

  state: {
    cardLoading: false
  },

  getters: {
    cardLoading(state) {
      return state.cardLoading;
    }
  },

  mutations: {
    setCardLoading(state, loading) {
      state.cardLoading = loading;
    }
  }
};