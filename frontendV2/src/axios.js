import axios from "axios";

const instance = axios.create({
    baseURL: "http://localhost:8000/api", // Asegúrate de que coincida con Laravel
    headers: {
        "Content-Type": "application/json",
    },
}); // ← Cerrar el axios.create() aquí

// Interceptor para manejar errores de autorización
instance.interceptors.response.use( // ← Usar 'instance' en lugar de 'axios'
    (response) => response,
    (error) => {
        if (error.response?.status === 401) {
            localStorage.removeItem('token');
            window.location.href = '/login';
        } else if (error.response?.status === 403) {
            window.location.href = '/unauthorized';
        }
        return Promise.reject(error);
    }
);

export default instance;