import React, { useState, useEffect } from "react";
import {
  solicitarReset,
  verificarToken,
  eliminarToken,
} from "../../api/ResetContrasenaService";
  import "./ResetContrasenaCrud.css";

const ResetContrasenaCrud = () => {
  const [email, setEmail] = useState("");
  const [token, setToken] = useState("");
  const [tokenVerificado, setTokenVerificado] = useState(null);
  const [mensaje, setMensaje] = useState("");
  const [error, setError] = useState("");

  const manejarSolicitud = (e) => {
    e.preventDefault();
    setMensaje("");
    setError("");

    solicitarReset(email)
      .then((res) => {
        setMensaje(`Token generado: ${res.data.token}`);
        setToken(res.data.token);
      })
      .catch(() => setError("Error al generar el token"));
  };

  const manejarVerificacion = (e) => {
    e.preventDefault();
    setMensaje("");
    setError("");

    verificarToken(token)
      .then((res) => {
        setTokenVerificado(res.data);
        setMensaje("Token válido para: " + res.data.email);
      })
      .catch(() => setError("Token inválido o expirado"));
  };

  const manejarEliminacion = () => {
    eliminarToken(token)
      .then(() => {
        setMensaje("Token eliminado");
        setToken("");
        setTokenVerificado(null);
      })
      .catch(() => setError("Error al eliminar el token"));
  };

  return (
    <div className="reset-container">
      <h2>Gestión de Reset de Contraseña</h2>

      <form onSubmit={manejarSolicitud} className="formulario">
        <label>Correo electrónico:</label>
        <input
          type="email"
          value={email}
          onChange={(e) => setEmail(e.target.value)}
          required
        />
        <button type="submit">Solicitar Token</button>
      </form>

      <form onSubmit={manejarVerificacion} className="formulario">
        <label>Token:</label>
        <input
          type="text"
          value={token}
          onChange={(e) => setToken(e.target.value)}
          required
        />
        <button type="submit">Verificar Token</button>
      </form>

      {tokenVerificado && (
        <div className="verificado">
          <p>Token válido para: <strong>{tokenVerificado.email}</strong></p>
          <button onClick={manejarEliminacion}>Eliminar Token</button>
        </div>
      )}

      {mensaje && <p className="mensaje">{mensaje}</p>}
      {error && <p className="error">{error}</p>}
    </div>
  );
};

export default ResetContrasenaCrud;