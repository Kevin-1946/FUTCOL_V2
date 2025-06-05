import axios from "../axios";

export const getEstadisticas = () => axios.get("/estadistica-equipos");

export const getEstadistica = (id) => axios.get(`/estadistica-equipos/${id}`);

export const createEstadistica = (data) =>
  axios.post("/estadistica-equipos", data);

export const updateEstadistica = (id, data) =>
  axios.put(`/estadistica-equipos/${id}`, data);

export const deleteEstadistica = (id) =>
  axios.delete(`/estadistica-equipos/${id}`);