import { defineStore } from "pinia"
import { toast } from "vue3-toastify"
import "vue3-toastify/dist/index.css"

export const useUtilsStore = defineStore("utils", {
  state: () => ({
    loader_st: false,
    side_bar_st: true,
  }),

  actions: {
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

    $reset() {
      this.loader_st = false
      this.side_bar_st = true
    }
  }
})
