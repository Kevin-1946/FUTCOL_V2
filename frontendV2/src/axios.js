import axios from "axios";

const instance = axios.create({
    baseURL: "http://localhost:8000/api",
    headers: {
        "Content-Type": "application/json",
    },
});

// Interceptor para agregar el token a todas las peticiones
instance.interceptors.request.use(
    (config) => {
        const token = localStorage.getItem('token');
        if (token) {
            config.headers.Authorization = `Bearer ${token}`;
        }
        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

// Interceptor para manejar errores de autorización
instance.interceptors.response.use(
    (response) => {
        return response;
    },
    (error) => {
        // Solo redirigir si realmente no hay token o está expirado
        if (error.response?.status === 401) {
            const token = localStorage.getItem('token');
            if (!token) {
                window.location.href = '/login';
            } else {
                localStorage.removeItem('token');
                window.location.href = '/login';
            }
        } else if (error.response?.status === 403) {
            window.location.href = '/unauthorized';
        }
        return Promise.reject(error);
    }
);

export default instance;