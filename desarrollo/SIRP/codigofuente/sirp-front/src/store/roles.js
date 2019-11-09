import axios from "../http";

const roles = {
  state: {
    loading: false,
    items: []
  },
  mutations: {
    setLoading(state, value) {
      state.loading = value;
    },
    setItems(state, value) {
      state.items = value;
    }
  },
  actions: {
    async fetchRoles({ commit }) {
      const { data } = await axios.get(`/roles`);
      console.log("Roles results", data);
      commit("setItems", data.roles);
    }
  }
};

export default roles;
