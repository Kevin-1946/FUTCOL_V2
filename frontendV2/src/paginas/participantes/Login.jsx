import React, { useState } from "react";
import axios from "axios";
import "../../index.css";
import { useNavigate } from "react-router-dom";

const Login = () => {
  const [credentials, setCredentials] = useState({ email: "", password: "" });
  const [error, setError] = useState(null);
  const navigate = useNavigate();

  const handleChange = (e) => {
    setCredentials({ ...credentials, [e.target.name]: e.target.value });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      const res = await axios.post("http://localhost:8000/api/login", credentials, {
        withCredentials: true,
      });

      const { access_token, user } = res.data;

      localStorage.setItem("token", access_token);
      localStorage.setItem("usuario", JSON.stringify(user));

      if (user.role?.nombre === "admin") {
        navigate("/administracion/estadisticaEquipo");
      } else if (user.role?.nombre === "capitan") {
        navigate("/participantes/Inscripciones");
      } else if (user.role?.nombre === "participante") {
        navigate("/participantes/jugador");
      } else {
        alert("Rol no reconocido");
      }
    } catch (err) {
      setError("Correo o contraseña incorrectos.");
      console.error(err);
    }
  };

  return (
    <div className="login-page-wrapper">
      <form className="login-form" onSubmit={handleSubmit}>
        <h2>Iniciar sesión</h2>
        {error && <div className="error-message">{error}</div>}
        <input
          type="email"
          name="email"
          placeholder="Correo"
          value={credentials.email}
          onChange={handleChange}
          required
        />
        <input
          type="password"
          name="password"
          placeholder="Contraseña"
          value={credentials.password}
          onChange={handleChange}
          required
        />
        <button type="submit">Iniciar sesión</button>
      </form>
    </div>
  );
};

export default Login;