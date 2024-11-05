import network from './network.services.js';

const obtenerPerfiles = async () => {
  return await network.get(`api/v1/admin/perfiles`);
}

const agregarPermiso = async (id, data) => {
  return await network.post(`api/v1/admin/perfiles/${id}/permisos`, data);
}

const obtenerPermisos = async (id, params) => {
  return await network.get(`api/v1/admin/perfiles/${id}/permisos?${new URLSearchParams(params).toString()}`);
}

const eliminarPermiso = async (id, data) => {
  return await network.delete(`api/v1/admin/perfiles/${id}/permisos`, { data: data });
}

export default {
  obtenerPerfiles,
  agregarPermiso,
  obtenerPermisos,
  eliminarPermiso
}
