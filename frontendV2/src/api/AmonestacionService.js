import axios from "../axios.js";

// Obtener todas las amonestaciones
export const getAmonestaciones = () => axios.get("/amonestaciones");

// Crear una amonestación
export const createAmonestacion = (data) => axios.post("/amonestaciones", data);

// Obtener una amonestación por ID
export const getAmonestacionById = (id) => axios.get(`/amonestaciones/${id}`);

// Actualizar una amonestación
export const updateAmonestacion = (id, data) => axios.put(`/amonestaciones/${id}`, data);

// Eliminar una amonestación
export const deleteAmonestacion = (id) => axios.delete(`/amonestaciones/${id}`);