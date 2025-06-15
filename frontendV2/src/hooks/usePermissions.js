import { useAuth } from '../contexts/AuthContext';

const usePermissions = () => {
    const { user } = useAuth();

    const hasPermission = (allowedRoles) => {
        if (!user) return false;
        return allowedRoles.includes(user.role);
    };

    const canViewStats = () => hasPermission(['admin', 'capitan', 'participante', 'publico']);
    const canUpdateStats = () => hasPermission(['admin']);
    const canInscribeTeam = () => hasPermission(['admin', 'capitan']);
    const canManageSystem = () => hasPermission(['admin']);

    return {
        hasPermission,
        canViewStats,
        canUpdateStats,
        canInscribeTeam,
        canManageSystem
    };
};

export default usePermissions;