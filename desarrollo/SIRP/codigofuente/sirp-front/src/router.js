import Vue from "vue";
import Router from "vue-router";
import LoginPage from "./pages/LoginPage.vue";
import RegisterPage from "./pages/RegisterPage.vue";
import DashboardPage from "./pages/DashboardPage.vue";
import ProductPage from "./pages/product/ProductPage.vue";
import CategoryPage from "./pages/category/CategoryPage.vue";
import BrandPage from "./pages/brand/BrandPage.vue";

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
        name: "ProductPage"
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
      path: "/register",
      name: "RegisterPage",
      meta: {
        public: true
      },
      component: RegisterPage
    },
    {
      path: "/dashboard",
      meta: { breadcrumb: true },
      name: "DashboardPage",
      component: DashboardPage,
      beforeEnter: requireAuth
    },
    {
      path: "/producto",
      meta: { breadcrumb: true },
      name: "ProductPage",
      component: ProductPage,
      beforeEnter: requireAuth
    },
    {
      path: "/categoria",
      meta: { breadcrumb: true },
      name: "CategoryPage",
      component: CategoryPage,
      beforeEnter: requireAuth
    },
    {
      path: "/marca",
      meta: { breadcrumb: true },
      name: "BrandPage",
      component: BrandPage,
      beforeEnter: requireAuth
    }
  ]
});
