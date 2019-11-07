import Vue from "vue";
import Router from "vue-router";
import LoginPage from "./pages/LoginPage.vue";
import GuestPage from "./pages/GuestPage.vue";
import NeighborPage from "./pages/NeighborPage.vue";

Vue.use(Router);

function requireAuth(to, from, next) {
  const token = localStorage.getItem("API_TOKEN") || null;
  if (!token) {
    next({
      path: "/login",
      query: { redirect: to.fullPath }
    });
  } else {
    next();
  }
}

export default new Router({
  mode: "history",
  base: process.env.BASE_URL,
  scrollBehavior: () => ({ y: 0 }),
  routes: [
    {
      path: "/",
      meta: {},
      name: "Root",
      redirect: {
        name: "GuestPage"
      }
    },
    {
      path: "/login",
      name: "LoginPage",
      meta: {
        public: true
      },
      component: LoginPage
    },
    {
      path: "/invitados",
      meta: { breadcrumb: true },
      name: "GuestPage",
      component: GuestPage,
      beforeEnter: requireAuth
    },
    {
      path: "/vecinos",
      meta: { breadcrumb: true },
      name: "NeighborPage",
      component: NeighborPage,
      beforeEnter: requireAuth
    }
  ]
});
