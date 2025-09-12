import React, { useEffect, useState } from "react";
import logo from "../../assets/imagenes/logo1.png";
import "../../index.css";
import { useAuth } from "../../contexts/AuthContext"; // ← importa el contexto

const Futcol = () => {
  const [mensaje, setMensaje] = useState("");
  const [rolVisible, setRolVisible] = useState("");
  const { user } = useAuth(); // ← obtiene el usuario del contexto

  useEffect(() => {
    const mensajeGuardado = localStorage.getItem("mensajeBienvenida");
    if (mensajeGuardado) {
      setMensaje(mensajeGuardado);

      setTimeout(() => {
        setMensaje("");
        localStorage.removeItem("mensajeBienvenida");
      }, 5000);
    }

    if (user) {
      const rol = user.role?.nombre;

      if (rol === "Administrador" || rol === "Capitan") {
        setRolVisible(rol);
      } else if (rol === "Participante") {
        setRolVisible(user.name || user.nombre || "Participante");
      }
    }
  }, [user]); // ← se vuelve a ejecutar si cambia el usuario

  return (
    <div className="futcol-container">
      {mensaje && (
        <div className="mensaje-bienvenida">
          {mensaje}
        </div>
      )}

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