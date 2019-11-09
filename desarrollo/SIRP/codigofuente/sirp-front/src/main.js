import Vue from "vue";
import "./plugins/vuetify";
import App from "./App.vue";
import router from "./router";
import axios from "./http";
import store from "./store";
import "./registerServiceWorker";
import "./scss/app.scss";
import Pusher from "pusher-js";
import HighchartsVue from "highcharts-vue";

Vue.prototype.axios = axios;
Vue.config.productionTip = false;

window.Pusher = Pusher;

Vue.use(HighchartsVue);

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount("#app");
