import axios from "../axios.js";

export const solicitarReset = (email) =>
  axios.post("/reset-contrasena", { email });

export const verificarToken = (token) =>
  axios.get(`/reset-contrasena/${token}`);

export const eliminarToken = (token) =>
  axios.delete(`/reset-contrasena/${token}`);