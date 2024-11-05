import network from './network.services.js';

const obtenerUsuarios = async (params) => {
  return await network.get(`api/v1/admin/usuarios?${new URLSearchParams(params).toString()}`);
}

const verUsuario = async (id) => {
  return await network.get(`api/v1/admin/usuarios/${id}`);
}

const crearUsuario = async (data) => {
  return await network.post('api/v1/admin/usuarios', data);
}

const actualizarUsuario = async (id, data) => {
  return await network.put(`api/v1/admin/usuarios/${id}`, data);
}

const recuperacionContra = async (correo) => {
  return await network.post(`public/auth/usuario/password/recuperacion`, correo);
}

const bloquearUsuario = async (id, justificacion,) => {
  return await network.post(`api/v1/admin/usuarios/${id}/bloqueo`, justificacion);
}

export default {
  obtenerUsuarios,
  verUsuario,
  crearUsuario,
  actualizarUsuario,
  recuperacionContra,
  bloquearUsuario
}
