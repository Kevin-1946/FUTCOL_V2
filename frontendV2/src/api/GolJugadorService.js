import axios from "../axios.js";

// Obtener todos los registros de goles
export const getGoles = () => axios.get("/goles-jugadores");

// Crear un gol
export const createGol = (data) => axios.post("/goles-jugadores", data);

// Obtener un gol por ID
export const getGolById = (id) => axios.get(`/goles-jugadores/${id}`);

// Actualizar un gol
export const updateGol = (id, data) => axios.put(`/goles-jugadores/${id}`, data);

// Eliminar un gol
export const deleteGol = (id) => axios.delete(`/goles-jugadores/${id}`);