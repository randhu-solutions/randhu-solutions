import Vue from "vue";
import Vuex from "vuex";
import createPersistedState from "vuex-persistedstate";
import roles from "./roles";
import session from "./session";
import invitations from "./invitations";
import neighbor from "./neighbor";
import areas from "./areas";
import reserve from "./reserve";
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
    reserve,
    session,
    invitations,
    neighbor,
    areas
  },
  plugins: [createPersistedState({ paths: ["session"] })]
});
