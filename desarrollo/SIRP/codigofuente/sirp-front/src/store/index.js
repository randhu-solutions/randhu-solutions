import Vue from "vue";
import Vuex from "vuex";
import createPersistedState from "vuex-persistedstate";
import roles from "./roles";
import session from "./session";
import category from "./category";
import brand from "./brand";
import products from "./products";
import sales from "./sales";
// import reserve from "./reserve";
import axios from "../http";

Vue.use(Vuex);

// ticketStates

export default new Vuex.Store({
  state: {
    ticketStatuses: [],
    departments: []
  },
  mutations: {
    setTicketStatuses(state, statuses) {
      state.ticketStatuses = statuses;
    },
    setDepartments(state, departments) {
      state.departments = departments;
    }
  },
  actions: {
    async fetchTicketStatuses({ commit }) {
      const { data } = await axios.get("/ticketStates");
      commit("setTicketStatuses", data.ticketStates);
    },
    async fetchDepartments({ commit }) {
      const { data } = await axios.get("/departmentsByEdifice");
      commit("setDepartments", data.departments);
    }
  },
  modules: {
    roles,
    // reserve,
    session,
    category,
    brand,
    products,
    sales
  },
  plugins: [createPersistedState({ paths: ["session"] })]
});
