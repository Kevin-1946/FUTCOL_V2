import React from "react";
import logo from "../../assets/imagenes/logo1.png";
import "../../index.css"; // ✅ Estilos globales

const Futcol = () => {
  return (
    <div className="futcol-container">
      <div className="hero-content">
        <h1 className="bienvenida">¡Bienvenido a FUTCOL!</h1>
        <img src={logo} alt="Logo Futcol" className="logo-central" />
        <p className="descripcion">
          Donde los torneos cobran vida y la pasión por el fútbol se vive al máximo.
        </p>
      </div>
    </div>
  );
};

export default Futcol;