import { useAuth } from '../contexts/AuthContext';

const usePermissions = () => {
    const { user } = useAuth();

    const role = user?.role?.nombre?.toLowerCase(); // 🔍 ¡Aquí normalizamos!

    const hasPermission = (allowedRoles) => {
        if (!role) return false;
        return allowedRoles.map(r => r.toLowerCase()).includes(role);
    };

    const canViewStats = () => hasPermission(['administrador', 'capitan', 'participante']);
    const canUpdateStats = () => hasPermission(['administrador']);
    const canInscribeTeam = () => hasPermission(['administrador', 'capitan']);
    const canManageSystem = () => hasPermission(['administrador']);

    return {
        hasPermission,
        canViewStats,
        canUpdateStats,
        canInscribeTeam,
        canManageSystem,
    };
};

export default usePermissions;