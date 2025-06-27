import React from 'react';
import { Link, useNavigate } from 'react-router-dom';
import usePermissions from '../hooks/usePermissions';
import { useAuth } from '../contexts/AuthContext'; // 👈 Importas el contexto

const Navigation = () => {
    const { canViewStats, canInscribeTeam, canManageSystem } = usePermissions();
    const { user, logout } = useAuth(); // 👈 Accedes al usuario y logout
    const navigate = useNavigate();

    const handleLogout = () => {
        logout();
        navigate('/login'); // redirige al login tras cerrar sesión
    };

    return (
        <div className="page-container">
            <nav>
                {user && (
                    <div className="session-info">
                        <span>Bienvenido, {user.name} ({user.role?.nombre})</span>
                        <button onClick={handleLogout}>Cerrar sesión</button>
                    </div>
                )}

                {canViewStats() && (
                    <Link to="/estadisticas">Estadísticas</Link>
                )}
                
                {canInscribeTeam() && (
                    <Link to="/equipos">Gestión de Equipos</Link>
                )}
                
                {canManageSystem() && (
                    <Link to="/admin">Panel de Administración</Link>
                )}
            </nav>
        </div>
    );
};

export default Navigation;