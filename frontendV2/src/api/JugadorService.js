import axios from "../axios.js";

// Obtener todos los jugadores
export const getJugadores = () => axios.get("/jugadores");

// Obtener un jugador por ID
export const getJugador = (id) => axios.get(`/jugadores/${id}`);

// Crear un jugador
export const createJugador = (data) => axios.post("/jugadores", data);

// Actualizar un jugador
export const updateJugador = (id, data) => axios.put(`/jugadores/${id}`, data);

// Eliminar un jugador
export const deleteJugador = (id) => axios.delete(`/jugadores/${id}`);