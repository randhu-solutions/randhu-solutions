import axios from "../http";
// import { uniqBy } from "lodash";

const sales = {
  state: {
    loading: false,
    totalPages: 1,
    items: []
  },
  mutations: {
    setLoadSales(state, value) {
      state.loading = value;
    },
    setSales(state, value) {
      state.items = value;
    },
    setPagesSale(state, value) {
      state.totalPages = value;
    }
  },
  actions: {
    async fetchSales({ commit }, page) {
      commit("setLoadSales", false);
      const { data } = await axios.post(`venta/listar`, { limit: 20, page });
      const sales = data.response.data;
      const totalPages = Math.floor(data.response.total / 20);
      console.log("sale results", sales);
      commit("setPagesSale", totalPages);
      commit("setSales", sales);
      commit("setLoadSales", true);
    }
    // async createProduct({ dispatch }, payload) {
    //   const result = await axios.post(`producto/crear`, payload);
    //   // commit('setItems', data.invitations);
    //   console.log("create product result", result);
    //   await dispatch("fetchProduct");
    // }
  }
};

export default sales;
