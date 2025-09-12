import axios from "../axios.js";

// Listar todas las inscripciones
export const getInscripciones = () => axios.get("/inscripciones");

// Crear una inscripción
export const createInscripcion = (data) => axios.post("/inscripciones", data);

// Obtener una inscripción por ID
export const getInscripcionById = (id) => axios.get(`/inscripciones/${id}`);

// Actualizar una inscripción
export const updateInscripcion = (id, data) => axios.put(`/inscripciones/${id}`, data);

// Eliminar una inscripción
export const deleteInscripcion = (id) => axios.delete(`/inscripciones/${id}`);