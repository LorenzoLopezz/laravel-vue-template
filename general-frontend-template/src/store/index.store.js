// Utilities
import { defineStore } from "pinia"
import { toast } from "vue3-toastify"
import "vue3-toastify/dist/index.css"

// PLugins
import jwtDecode from "jwt-decode"

export const useRootStore = defineStore("app", {
  state: () => ({
    // user
    token: null,
    userInfo: null,

    // utils
    alerts: [],
    loader: false,
    sideBar: false,
    menu: [],
    rutas: [],
  }),

  actions: {
    setAuth(payload) {
      const { token, refresh_token } = payload
      this.token = token
      this.userInfo = jwtDecode(token)
      localStorage.setItem("token", token)
      localStorage.setItem("refresh_token", refresh_token)
    },

    setNotification(payload) {
      toast[payload.type](payload.message, {
        timeout: payload.timeout,
        position: "top-right",
        pauseOnHover: true,
        icon: true,
        containerClassName: "custom-toast",
        theme: "colored",
        hideProgressBar: true,
      });
    },

    setLoader(payload) {
      this.loader = payload
    },

    setOptions() {
      this.menu = menuOptions;
    },

    clearMenu() {
      this.menu = []
      this.rutas = []
    }
  }
})
