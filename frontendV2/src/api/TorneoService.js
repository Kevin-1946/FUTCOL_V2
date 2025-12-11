import axios from "../axios.js";

export const getTorneos = () => axios.get("/torneos");
export const createTorneo = (data) => axios.post("/torneos", data);
export const getTorneoById = (id) => axios.get(`/torneos/${id}`);
export const updateTorneo = (id, data) => axios.put(`/torneos/${id}`, data);
export const deleteTorneo = (id) => axios.delete(`/torneos/${id}`);