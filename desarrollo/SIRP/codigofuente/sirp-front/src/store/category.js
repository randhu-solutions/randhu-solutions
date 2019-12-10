import axios from "../http";

const category = {
  state: {
    loading: false,
    items: []
  },
  mutations: {
    setLoadCategory(state, value) {
      state.loading = value;
    },
    setCategories(state, value) {
      state.items = value;
    },
    deleteCategory(state, value) {
      state.items = state.items.filter(item => item.category_id !== value);
    }
  },
  actions: {
    async fetchCategory({ commit }) {
      commit("setLoadCategory", false);
      const { data } = await axios.post(`categoria/listar`);
      const categories = data.response.data;
      console.log("categories results", categories);
      commit("setCategories", categories);
      commit("setLoadCategory", true);
    },
    async createCategory({ dispatch }, payload) {
      const result = await axios.post("categoria/crear", payload);
      // commit('setItems', data.invitations);
      console.log("create category result", result);
      await dispatch("fetchCategory");
    },
    async editInvitation({ dispatch }, payload) {
      const id = payload.id;
      delete payload.id;
      const result = await axios.post(`/invitation/update/${id}`, payload);
      // commit('setItems', data.invitations);
      console.log("update invitation result", result);
      await dispatch("fetchCategory");
    },
    async deleteCategory({ commit }, payload) {
      const id = payload;
      await axios.post("categoria/eliminar", { category_id: id });
      commit("deleteCategory", id);
    }
  }
};

export default category;
