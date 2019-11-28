import axios from "../http";
// import { uniqBy } from "lodash";

const products = {
  state: {
    loading: false,
    items: []
  },
  mutations: {
    setLoadProducts(state, value) {
      state.loading = value;
    },
    setProducts(state, value) {
      state.items = value;
    }
  },
  actions: {
    async fetchProduct({ commit }) {
      commit("setLoadProducts", false);
      const { data } = await axios.post(`producto/listar`);
      const products = data.response.data;
      console.log("producto results", products);
      commit("setProducts", products);
      commit("setLoadProducts", true);
    },
    async createProduct({ dispatch }, payload) {
      const result = await axios.post(`producto/crear`, payload);
      // commit('setItems', data.invitations);
      console.log("create product result", result);
      await dispatch("fetchProduct");
    }
  }
};

export default products;
