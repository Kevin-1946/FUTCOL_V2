import axios from "../axios";

// Listar todas las inscripciones
export const getInscripciones = () => axios.get("/inscripcions");

// Crear una inscripción
export const createInscripcion = (data) => axios.post("/inscripcions", data);

// Obtener una inscripción por ID
export const getInscripcionById = (id) => axios.get(`/inscripcions/${id}`);

// Actualizar una inscripción
export const updateInscripcion = (id, data) => axios.put(`/inscripcions/${id}`, data);

// Eliminar una inscripción
export const deleteInscripcion = (id) => axios.delete(`/inscripcions/${id}`);