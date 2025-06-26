import React from 'react';
import { Navigate } from 'react-router-dom';
import { useAuth } from '../contexts/AuthContext';

const ProtectedRoute = ({ children, allowedRoles = [] }) => {
    const { user, loading } = useAuth();

    if (loading) {
        return <div>Cargando...</div>;
    }

    if (!user) {
        // Si no hay usuario autenticado, redirige al login
        return <Navigate to="/login" />;
    }

    if (allowedRoles.length > 0 && !allowedRoles.includes(user.role?.nombre)) {
        // Si el rol del usuario no está permitido, redirige a una página de acceso no autorizado
        return <Navigate to="/unauthorized" />;
    }

    return children;
};

export default ProtectedRoute;