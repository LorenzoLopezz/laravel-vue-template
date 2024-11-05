import network from "./network.services"

const login = async (email, password) => {
  return await network.post("public/auth/iniciar-sesion", { email, password })
}

const logout = async () => {
  return await network.post("api/v1/auth/cerrar-sesion")
}

const getAuthorizedPaths = async () => {
  return await network.get("api/v1/rutas/get-rutas")
}

const getInfoUser = async () => {
  return await network.get("api/v1/auth/cuenta")
}

const getPermissions = async () => {
  return await network.get("api/v1/auth/permisos")
}

const getMenu = async () => {
  return await network.get("api/v1/auth/menu")
}

export default {
  login,
  logout,
  getAuthorizedPaths,
  getInfoUser,
  getPermissions,
  getMenu
}
