import axios from "axios";
import router from "./router";

axios.defaults.baseURL = process.env.VUE_APP_API_URL;
axios.defaults.headers.post["Content-Type"] = "application/json;charset=utf-8";
axios.defaults.headers.post["Access-Control-Allow-Origin"] = "*";
axios.interceptors.request.use(config => {
  const token = localStorage.getItem("API_TOKEN") || null;
  if (token) {
    config.headers.Authorization = `${token}`;
  }
  return config;
}, Promise.reject);

axios.interceptors.response.use(
  response => {
    return response;
  },
  error => {
    if (!error.response) {
      console.log("Please check your internet connection.");
    }
    if (error.response.status === 500) {
      localStorage.removeItem("API_TOKEN");
      router.push({ name: "LoginPage" });
    }
    return Promise.reject(error);
  }
);

export default axios;
