import axios from "../axios";

// Listar todos los recibos
export const getRecibos = () => axios.get("/recibos");

// Obtener recibo por id
export const getRecibo = (id) => axios.get(`/recibos/${id}`);

// Crear recibo
export const createRecibo = (data) => axios.post("/recibos", data);

// Actualizar recibo
export const updateRecibo = (id, data) => axios.put(`/recibos/${id}`, data);

// Eliminar recibo
export const deleteRecibo = (id) => axios.delete(`/recibos/${id}`);