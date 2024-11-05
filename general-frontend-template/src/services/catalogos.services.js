import network from './network.services.js'

const obtenerPerfiles = async () => {
  return await network.get("api/v1/admin/perfiles")
}

const obtenerModulos = async () => {
  return await network.get("api/v1/admin/modulos")
}

export default {
  obtenerPerfiles,
  obtenerModulos,
}
