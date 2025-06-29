import React from "react";
import { Link } from "react-router-dom";  // Para la navegación

const Unauthorized = () => {
  return (
    <div className="unauthorized">
      <div>
        <h2>Acceso Denegado</h2>
        <p>No tienes permisos para acceder a esta página.</p>
        <Link to="/login" className="back-button">Volver al Login</Link>
      </div>
    </div>
  );
};

export default Unauthorized;