import axios from "../axios.js";

// Obtener todos los encuentros
export const getEncuentros = () => axios.get("/encuentros");

// Crear un encuentro
export const createEncuentro = (data) => axios.post("/encuentros", data);

// Obtener un encuentro por ID
export const getEncuentroById = (id) => axios.get(`/encuentros/${id}`);

// Actualizar un encuentro
export const updateEncuentro = (id, data) => axios.put(`/encuentros/${id}`, data);

// Eliminar un encuentro
export const deleteEncuentro = (id) => axios.delete(`/encuentros/${id}`);