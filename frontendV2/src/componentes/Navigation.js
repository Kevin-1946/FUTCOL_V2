import React from 'react';
import { Link } from 'react-router-dom';
import usePermissions from '../hooks/usePermissions';

const Navigation = () => {
    const { canViewStats, canInscribeTeam, canManageSystem } = usePermissions();

    return (
        <div className="page-container">
        <nav>
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