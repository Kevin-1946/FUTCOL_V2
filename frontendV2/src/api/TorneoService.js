import axios from "../axios.js";

// Obtener todos los torneos
export const getTorneos = () => axios.get("/torneos");

// Crear un nuevo torneo
export const createTorneo = (data) => axios.post("/torneos", data);

// Obtener un torneo por ID
export const getTorneoById = (id) => axios.get(`/torneos/${id}`);

// Actualizar un torneo
export const updateTorneo = (id, data) => axios.put(`/torneos/${id}`, data);

// Eliminar un torneo
export const deleteTorneo = (id) => axios.delete(`/torneos/${id}`);