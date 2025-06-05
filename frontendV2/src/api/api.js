import axios from "axios";

const api = axios.create({
  baseURL: "http://localhost:8000/api", // Asegúrate que tu backend corra ahí
  withCredentials: true, // Solo si usas cookies para auth (ej. con Sanctum)
  headers: {
    "Content-Type": "application/json",

    Accept: "application/json",
  },
});

export default api;