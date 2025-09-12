import React, { createContext, useContext, useState, useEffect } from 'react';
import axios from '../axios';

const AuthContext = createContext();

// Hook personalizado para usar el contexto
export const useAuth = () => useContext(AuthContext);

export const AuthProvider = ({ children }) => {
    const [user, setUser] = useState(null);
    const [loading, setLoading] = useState(true);

    // Verifica si hay sesión activa en localStorage
    useEffect(() => {   
        const checkAuth = async () => {
            const token = localStorage.getItem('token');
            const storedUser = localStorage.getItem('user');

            if (token && storedUser) {
                try {
                    const parsedUser = JSON.parse(storedUser);

                    // Validación simple: debe tener al menos nombre y rol.nombre
                    if (parsedUser?.name && parsedUser?.role?.nombre) {
                        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
                        setUser(parsedUser);
                    } else {
                        // Si el objeto es inválido, limpia sesión
                        localStorage.removeItem('token');
                        localStorage.removeItem('user');
                        delete axios.defaults.headers.common['Authorization'];
                    }
                } catch (error) {
                    // Si el JSON es inválido o da error
                    localStorage.removeItem('token');
                    localStorage.removeItem('user');
                    delete axios.defaults.headers.common['Authorization'];
                }
            }
            setLoading(false);
        };

        checkAuth();
    }, []);

    // Función de login que guarda token y usuario
    const login = async (credentials) => {
        const response = await axios.post('/login', credentials);
        const { access_token, user } = response.data;

        if (!user?.name || !user?.role?.nombre) {
            throw new Error("El usuario devuelto no tiene los datos esperados.");
        }

        localStorage.setItem('token', access_token);
        localStorage.setItem('user', JSON.stringify(user));
        axios.defaults.headers.common['Authorization'] = `Bearer ${access_token}`;
        setUser(user);

        return response;
    };

    // Cierra sesión, limpia localStorage y axios
    const logout = () => {
        localStorage.removeItem('token');
        localStorage.removeItem('user');
        delete axios.defaults.headers.common['Authorization'];
        setUser(null);
    };

    return (
        <AuthContext.Provider value={{ user, login, logout, loading }}>
            {children}
        </AuthContext.Provider>
    );
};