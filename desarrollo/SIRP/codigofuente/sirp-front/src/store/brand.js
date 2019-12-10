import axios from "../http";

const brand = {
  state: {
    loading: false,
    totalPages: 1,
    items: []
  },
  mutations: {
    setLoadBrand(state, value) {
      state.loading = value;
    },
    setBrand(state, value) {
      state.items = value;
    },
    deleteBrand(state, value) {
      state.items = state.items.filter(item => item.brand_id !== value);
    },
    setPagesBrand(state, value) {
      state.totalPages = value;
    }
  },
  actions: {
    async fetchBrand({ commit }, page) {
      commit("setLoadBrand", false);
      const { data } = await axios.post(`marca/listar`, { limit: 20, page });
      const brands = data.response.data;
      const totalPages = Math.floor(data.response.total / 20);
      // console.log("brand results", brands);
      commit("setPagesBrand", totalPages);
      commit("setBrand", brands);
      commit("setLoadBrand", true);
    },
    async createBrand({ dispatch }, payload) {
      const result = await axios.post(`marca/crear`, payload);
      // commit('setItems', data.invitations);
      // console.log("create brand result", result);
      await dispatch("fetchBrand");
    },
    async deleteBrand({ commit }, payload) {
      const id = payload;
      await axios.post("marca/eliminar", { brand_id: id });
      commit("deleteBrand", id);
    }
  }
};

export default brand;
