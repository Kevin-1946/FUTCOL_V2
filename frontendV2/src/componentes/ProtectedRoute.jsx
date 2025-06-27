import React from 'react';
import { Navigate } from 'react-router-dom';
import { useAuth } from '../contexts/AuthContext';

const ProtectedRoute = ({ children, allowedRoles = [] }) => {
    const { user, loading } = useAuth();

    if (loading) return <div>Cargando...</div>;

    if (!user) return <Navigate to="/login" />;

    // Validación segura
    const roleName = user?.role?.nombre?.toLowerCase(); // por si quieres manejarlo en minúsculas
    const rolesPermitidos = allowedRoles.map(r => r.toLowerCase()); // opcional: para evitar problemas de mayúsculas

    if (!roleName || !rolesPermitidos.includes(roleName)) {
        return <Navigate to="/errores/Unauthorized" />;
    }

    return children;
};

export default ProtectedRoute;