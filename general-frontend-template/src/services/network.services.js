import axios from "axios"
import { useRootStore } from "@/store/index.store"

let config = {
  baseURL: import.meta.env.VITE_VUE_APP_API_URL || "http://127.0.0.1:8000/api",
  headers: {
    "Content-Type": "application/json",
    Accept: "application/json",
    Authorization: `Bearer ${localStorage.getItem("token")}`,
  },
}

const instance = axios.create(config)

instance.interceptors.request.use(function (config) {
  const rootStore = useRootStore()

  if (!config.disableLoader) {
    rootStore.loader = true;
  }
  const token = localStorage.getItem("token")

  if (token && config.headers !== null) {
    config.headers.Authorization = `Bearer ${token}`
  }

  return config
})

instance.interceptors.response.use(
  (response) => {
    const rootStore = useRootStore()
    rootStore.loader = false

    return response
  },
  async (error) => {
    const rootStore = useRootStore()
    rootStore.loader = false

    let message = ""
    let type = "error"

    if (error.response?.status == 0) {
      message = "No se pudo establecer conexión con el servidor"
    } else if (error.response?.status == 500) {
      message = error.response?.data?.message ?? "Error de servidor"
    } else if (
      error.response?.status == 401 &&
      !localStorage.getItem("refresh_token")
    ) {
      message = error.response?.data?.message ?? "Acceso no autorizado"

      setTimeout(function () {
        window.location.href = "/login";
      }, 2000);
    } else if (
      error.response?.status === 401 &&
      localStorage.getItem("refresh_token")
    ) {
      try {
        const response = await axios.post("/refresh-token", {
          refresh_token: localStorage.getItem("refresh_token"),
        })
        localStorage.setItem("token", response.data.token)
        localStorage.setItem("refresh_token", response.data.refresh_token)

        const config = error.config
        config.headers["Authorization"] = `Bearer ${response.data.token}`
        return axios.request(config)
      } catch (error) {
        localStorage.removeItem("token")
        localStorage.removeItem("refresh_token")
        window.location.href = "/login"
        return Promise.reject(error)
      }
    } else if (error.response?.status >= 400 && error.response?.status < 500) {
      message = error.response.data.message
    } else {
      message = error.response.statusText
    }

    rootStore.setNotification({
      type: type,
      message: message,
      timeout: 5000,
    })


    return Promise.resolve(error.message)
  }
)

export default instance
