import axios from "../http";

const brand = {
  state: {
    loading: false,
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
    }
  },
  actions: {
    async fetchBrand({ commit }) {
      commit("setLoadBrand", false);
      const { data } = await axios.post(`marca/listar`);
      const brands = data.response.data;
      console.log("brand results", brands);
      commit("setBrand", brands);
      commit("setLoadBrand", true);
    },
    async createBrand({ dispatch }, payload) {
      const result = await axios.post(`marca/crear`, payload);
      // commit('setItems', data.invitations);
      console.log("create brand result", result);
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
