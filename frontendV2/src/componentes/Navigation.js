import React from 'react';
import { Link } from 'react-router-dom';
import usePermissions from '../hooks/usePermissions';

const Navigation = () => {
    const { canViewStats, canInscribeTeam, canManageSystem } = usePermissions();

    return (
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
    );
};

export default Navigation;