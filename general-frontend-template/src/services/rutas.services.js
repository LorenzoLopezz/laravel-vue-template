import network from './network.services.js';

const listarRutas = async (params = {}) => {
  return await network.get(`api/v1/admin/menu?${new URLSearchParams(params).toString()}`);
}

const obtenerDetalleRuta = async (id) => {
  return await network.get(`api/v1/admin/menu/${id}`);
}

const crearRuta = async (datos) => {
  return await network.post(`api/v1/admin/menu`, datos);
}

const actualizarRuta = async (id, datos) => {
  return await network.put(`api/v1/admin/menu/${id}`, datos);
}

const eliminarRuta = async (id) => {
  return await network.put(`api/v1/admin/menu/${id}/estado`);
}

const toogleMostarRuta = async (id, params) => {
  return await network.put(`api/v1/admin/menu/${id}/mostrar-menu`, params);
}

export default {
  listarRutas,
  obtenerDetalleRuta,
  crearRuta,
  actualizarRuta,
  eliminarRuta,
  toogleMostarRuta
}
