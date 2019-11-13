import axios from "../http";

const session = {
  state: {
    loading: false,
    token: null,
    currentUser: null
  },
  mutations: {
    setLoading(state, value) {
      state.loading = value;
    },
    setCurrentUser(state, value) {
      state.currentUser = value;
    },
    setToken(state, value) {
      state.token = value;
    }
  },
  actions: {
    // values: { email, password }
    async login({ commit }, values) {
      const { data } = await axios.post(`usuario/login`, values);
      console.log("login", data);
      localStorage.setItem("API_TOKEN", data.token);
      commit("setToken", data);
      commit("setCurrentUser", data.user);
    },
    async logout({ commit }) {
      localStorage.removeItem("API_TOKEN");
      commit("setCurrentUser", null);
      commit("setToken", null);
    }
  },
  getters: {
    loggedIn: state => !!state.currentUser,
    isAdmin: state => state.currentUser && state.currentUser.role_id == 2
  }
};

export default session;
