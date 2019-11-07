import axios from "../http";

const invitations = {
  state: {
    loading: false,
    items: []
  },
  mutations: {
    setLoading(state, value) {
      state.loading = value;
    },
    setReservations(state, value) {
      state.items = value;
    }
  },
  actions: {
    async fetchReservations({ commit }) {
      commit("setLoading", false);
      const { data } = await axios.get(`/reservations`);
      console.log("events reserve", data);
      const { reservations } = data;
      reservations.forEach(item => {
        item.reservation_start_date = item.reservation_start_date.split(" ")[0];
        item.reservation_end_date = item.reservation_end_date.split(" ")[0];
        item.open = false;
      });
      commit("setReservations", reservations);
      commit("setLoading", true);
    },
    async createReserve({ dispatch }, payload) {
      if (!payload.hasEvent) {
        delete payload.title;
        delete payload.details;
        delete payload.invitations_id;
      }
      const result = await axios.post(`/reserve`, payload);
      console.log("create event result", result);
      await dispatch("fetchReservations");
    },
    async editReserve({ dispatch }, payload) {
      const id = payload.id;
      delete payload.id;
      const result = await axios.post(`/invitation/update/${id}`, payload);
      // commit('setItems', data.invitations);
      console.log("update invitation result", result);
      await dispatch("fetchInvitations");
    }
  }
};

export default invitations;
