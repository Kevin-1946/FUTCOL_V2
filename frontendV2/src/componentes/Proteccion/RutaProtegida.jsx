import { Navigate } from "react-router-dom";
//import RutaProtegida from "./componentes/proteccion/RutaProtegida";

/**
 * Componente que protege rutas según el rol del usuario.
 * 
 * @param {ReactNode} children - Componente que se renderiza si el usuario tiene permiso.
 * @param {string[]} rolesPermitidos - Lista de roles con permiso para acceder.
 * @param {object} usuario - Objeto del usuario actual con al menos la propiedad `rol`.
 */
const RutaProtegida = ({ children, rolesPermitidos, usuario }) => {
  if (!usuario) {
    return <Navigate to="/participantes/login" replace />;
  }

  if (!rolesPermitidos.includes(usuario.rol)) {
    return <Navigate to="/" replace />;
  }

  return children;
};

export default RutaProtegida;
