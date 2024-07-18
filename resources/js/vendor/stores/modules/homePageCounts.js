export default {
  namespaced: false,

  state: {
    homePageCounts: {},
  },

  getters: {
    homePageCounts({homePageCounts}) {
      return homePageCounts;
    },

    thisMonthCounts({homePageCounts}) {
      return homePageCounts.thisMonth;
    }
  },

  mutations: {
    setHomePageCounts(state, counts) {
      state.homePageCounts = counts;
    },

    updateThisMonthCounts(state, {type, count = 1}) {
      if (state.homePageCounts.thisMonth.hasOwnProperty(type)) {
        let todaysDate = (new Date()).getDate();
        state.homePageCounts.thisMonth[type][todaysDate] += count;
      }
    },
  },

  actions: {
    setHomePageCounts(state, data) {
      state.commit('setHomePageCounts', data);
    },

    updateThisMonthCategoriesCount(state, count = 1) {
      state.commit('updateThisMonthCounts', {type: 'categories', count});
    },

    updateThisMonthUsersCount(state, count = 1) {
      state.commit('updateThisMonthCounts', {type: 'users', count});
    },

    updateThisMonthTestimonialsCount(state, count = 1) {
      state.commit('updateThisMonthCounts', {type: 'testimonials', count});
    },

    updateThisMonthNewsCount(state, count = 1) {
      state.commit('updateThisMonthCounts', {type: 'news', count});
    },
  }
};