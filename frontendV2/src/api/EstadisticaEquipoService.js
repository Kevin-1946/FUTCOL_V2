import axios from "../axios.js";

export const getEstadisticas = () => axios.get("/estadisticas-equipos");

export const getEstadistica = (id) => axios.get(`/estadisticas-equipos/${id}`);

export const createEstadistica = (data) =>
  axios.post("/estadisticas-equipos", data);

export const updateEstadistica = (id, data) =>
  axios.put(`/estadisticas-equipos/${id}`, data);

export const deleteEstadistica = (id) =>
  axios.delete(`/estadisticas-equipos/${id}`);