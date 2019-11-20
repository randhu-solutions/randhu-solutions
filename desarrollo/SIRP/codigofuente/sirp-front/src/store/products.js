import axios from "../http";
// import { uniqBy } from "lodash";

const products = {
  state: {
    loading: false,
    items: []
  },
  mutations: {
    setLoading(state, value) {
      state.loading = value;
    },
    setNeighbor(state, value) {
      state.items = value;
    }
  },
  actions: {
    async fetchProduct({ commit }) {
      commit("setLoading", false);
      const data = await axios.post(`producto/listar`);
      console.log("producto results", data);
      // commit("setNeighbor",);
      commit("setLoading", true);
    },
    async createProduct({ dispatch }, values) {
      const result = await axios.post(`producto/crear`, values);
      // commit('setItems', data.invitations);
      console.log("create product result", result);
      await dispatch("fetchProduct");
    }
  }
};

export default products;
