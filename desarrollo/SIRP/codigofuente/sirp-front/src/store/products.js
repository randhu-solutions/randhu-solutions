import axios from "../http";
// import { uniqBy } from "lodash";

const products = {
  state: {
    loading: false,
    totalPages: 1,
    items: []
  },
  mutations: {
    setLoadProducts(state, value) {
      state.loading = value;
    },
    setProducts(state, value) {
      state.items = value;
    },
    setPagesProducts(state, value) {
      state.totalPages = value;
    }
  },
  actions: {
    async fetchProduct({ commit }, page) {
      commit("setLoadProducts", false);
      const { data } = await axios.post(`producto/listar`, { limit: 20, page });
      const products = data.response.data;
      const totalPages = Math.floor(data.response.total / 20);
      // console.log("producto results", products);
      commit("setPagesProducts", totalPages);
      commit("setProducts", products);
      commit("setLoadProducts", true);
    },
    async createProduct({ dispatch }, payload) {
      const result = await axios.post(`producto/crear`, payload);
      // commit('setItems', data.invitations);
      // console.log("create product result", result);
      await dispatch("fetchProduct");
    }
  }
};

export default products;
