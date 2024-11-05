import network from './network.services.js';

const obtenerPermisos = async (params) => {
  return await network.get(`api/v1/admin/permisos?${new URLSearchParams(params).toString()}`);
}

const crearPermiso = async (data) => {
  return await network.post('api/v1/admin/permisos', data);
}

const actualizarPermiso = async (id, data) => {
  return await network.put(`api/v1/admin/permisos/${id}`, data);
}

const verPermiso = async (id) => {
  return await network.get(`api/v1/admin/permisos/${id}`);
}

const eliminarPermiso = async ({ id }) => {
  return await network.delete(`api/v1/admin/permisos/${id}`);
}

export default {
  obtenerPermisos,
  crearPermiso,
  actualizarPermiso,
  verPermiso,
  eliminarPermiso,
}
