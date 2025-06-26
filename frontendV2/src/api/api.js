import axios from "axios";

// Crear la instancia de Axios
const api = axios.create({
  baseURL: "http://localhost:8000/api",  // Base URL de tu API backend
  headers: {
    "Content-Type": "application/json",  // Tipo de contenido JSON
    Accept: "application/json",  // Espera una respuesta en formato JSON
  },
});

export default api;