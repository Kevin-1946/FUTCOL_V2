import axios from "../axios";

// Obtener todos los registros de goles
export const getGoles = () => axios.get("/gol-jugadors");

// Crear un gol
export const createGol = (data) => axios.post("/gol-jugadors", data);

// Obtener un gol por ID
export const getGolById = (id) => axios.get(`/gol-jugadors/${id}`);

// Actualizar un gol
export const updateGol = (id, data) => axios.put(`/gol-jugadors/${id}`, data);

// Eliminar un gol
export const deleteGol = (id) => axios.delete(`/gol-jugadors/${id}`);