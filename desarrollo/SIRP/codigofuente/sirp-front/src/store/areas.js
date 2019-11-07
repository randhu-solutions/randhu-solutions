import axios from "../http";

const areas = {
  state: {
    loading: false,
    items: []
  },
  mutations: {
    setLoading(state, value) {
      state.loading = value;
    },
    setAreas(state, value) {
      state.items = value;
    }
  },
  actions: {
    async fetchAreas({ commit }) {
      commit("setLoading", false);
      const { data } = await axios.get(`/commonAreas`);
      commit("setAreas", data.commonAreas);
      commit("setLoading", true);
    }
  }
};

export default areas;
