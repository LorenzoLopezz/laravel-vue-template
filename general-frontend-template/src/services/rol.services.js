import network from './network.services.js';

const obtenerRoles = async (params) => {
  return await network.get(`api/v1/admin/roles?${new URLSearchParams(params).toString()}`);
}

const crearRol = async (data) => {
  return await network.post('api/v1/admin/roles', data);
}

const actualizarRol = async (id, data) => {
  return await network.put(`api/v1/admin/roles/${id}`, data);
}

const verRol = async (id) => {
  return await network.get(`api/v1/admin/roles/${id}`);
}

const eliminarRol = async (id) => {
  return await network.delete(`api/v1/admin/roles/${id}`);
}

const agregarPermiso = async (id, data) => {
  return await network.post(`api/v1/admin/roles/${id}/permisos`, data);
}

const obtenerPermisos = async (id, params) => {
  return await network.get(`api/v1/admin/roles/${id}/permisos?${new URLSearchParams(params).toString()}`);
}

const eliminarPermiso = async (id, data) => {
  return await network.delete(`api/v1/admin/roles/${id}/permisos`, { data: data });
}

export default {
  obtenerRoles,
  crearRol,
  actualizarRol,
  verRol,
  eliminarRol,
  agregarPermiso,
  obtenerPermisos,
  eliminarPermiso
}
