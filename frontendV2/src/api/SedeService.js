import axios from "../axios.js";

// Obtener todas las sedes
export const getSedes = () => axios.get("/sedes");

// Crear una nueva sede
export const createSede = (data) => axios.post("/sedes", data);

// Obtener una sede por ID
export const getSedeById = (id) => axios.get(`/sedes/${id}`);

// Actualizar una sede
export const updateSede = (id, data) => axios.put(`/sedes/${id}`, data);

// Eliminar una sede
export const deleteSede = (id) => axios.delete(`/sedes/${id}`);
