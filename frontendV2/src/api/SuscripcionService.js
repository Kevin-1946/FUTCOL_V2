import axios from "../axios";

// Listar todas las suscripciones
export const getSuscripciones = () => axios.get("/suscripciones");

// Crear una nueva suscripción
export const createSuscripcion = (data) => axios.post("/suscripciones", data);

// Obtener una suscripción por ID
export const getSuscripcionById = (id) => axios.get(`/suscripciones/${id}`);

// Actualizar una suscripción
export const updateSuscripcion = (id, data) => axios.put(`/suscripciones/${id}`, data);

// Eliminar una suscripción
export const deleteSuscripcion = (id) => axios.delete(`/suscripciones/${id}`);
