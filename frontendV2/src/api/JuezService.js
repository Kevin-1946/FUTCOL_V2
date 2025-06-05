import axios from "../axios";

// Obtener todos los jueces
export const getJueces = () => axios.get("/jueces");

// Crear un juez
export const createJuez = (data) => axios.post("/jueces", data);

// Obtener un juez por ID
export const getJuezById = (id) => axios.get(`/jueces/${id}`);

// Actualizar un juez
export const updateJuez = (id, data) => axios.put(`/jueces/${id}`, data);

// Eliminar un juez
export const deleteJuez = (id) => axios.delete(`/jueces/${id}`);