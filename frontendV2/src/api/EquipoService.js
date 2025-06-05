import axios from "../axios";

// Obtener todos los equipos
export const getEquipos = () => axios.get("/equipos");

// Crear un equipo
export const createEquipo = (data) => axios.post("/equipos", data);

// Obtener equipo por ID
export const getEquipoById = (id) => axios.get(`/equipos/${id}`);

// Actualizar un equipo
export const updateEquipo = (id, data) => axios.put(`/equipos/${id}`, data);

// Eliminar un equipo
export const deleteEquipo = (id) => axios.delete(`/equipos/${id}`);