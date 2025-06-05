import React, { useEffect, useState } from "react";
import {
  getLogins,
  createLogin,
  updateLogin,
  deleteLogin,
} from "../../api/LoginUsuarioService";
import "./LoginUsuarioCrud.css";

const LoginUsuariosCrud = () => {
  const [logins, setLogins] = useState([]);
  const [form, setForm] = useState({
    jugador_id: "",
    ip: "",
    user_agent: "",
    exitoso: false,
    fecha_login: "",
  });
  const [editandoId, setEditandoId] = useState(null);

  const cargarLogins = () => {
    getLogins()
      .then((res) => setLogins(res.data))
      .catch((err) => console.error(err));
  };

  useEffect(() => {
    cargarLogins();
  }, []);

  const manejarCambio = (e) => {
    const { name, value, type, checked } = e.target;
    setForm({ ...form, [name]: type === "checkbox" ? checked : value });
  };

  const manejarSubmit = (e) => {
    e.preventDefault();
    const metodo = editandoId ? updateLogin : createLogin;
    const args = editandoId ? [editandoId, form] : [form];

    metodo(...args)
      .then(() => {
        cargarLogins();
        setForm({
          jugador_id: "",
          ip: "",
          user_agent: "",
          exitoso: false,
          fecha_login: "",
        });
        setEditandoId(null);
      })
      .catch((err) => console.error(err));
  };

  const editarLogin = (login) => {
    setForm({
      jugador_id: login.jugador_id,
      ip: login.ip,
      user_agent: login.user_agent,
      exitoso: login.exitoso,
      fecha_login: login.fecha_login,
    });
    setEditandoId(login.id);
  };

  const eliminarLogin = (id) => {
    if (confirm("¿Estás seguro de eliminar este registro de login?")) {
      deleteLogin(id)
        .then(() => cargarLogins())
        .catch((err) => console.error(err));
    }
  };

  return (
      <div>
        <h2>{editandoId ? "Editar Login" : "Nuevo Login"}</h2>

        <form onSubmit={manejarSubmit}>
          <input name="jugador_id" placeholder="Jugador ID" value={form.jugador_id} onChange={manejarCambio} required />
          <input name="ip" placeholder="IP" value={form.ip} onChange={manejarCambio} required />
          <input name="user_agent" placeholder="User Agent" value={form.user_agent} onChange={manejarCambio} required />
          <label>
            <input type="checkbox" name="exitoso" checked={form.exitoso} onChange={manejarCambio} />
            ¿Login Exitoso?
          </label>
          <input type="date" name="fecha_login" value={form.fecha_login} onChange={manejarCambio} required />

          <button type="submit">{editandoId ? "Actualizar" : "Crear"}</button>
        </form>

        <h3>Historial de Logins</h3>
        <table>
          <thead>
            <tr>
              <th>Jugador</th>
              <th>IP</th>
              <th>User Agent</th>
              <th>Exitoso</th>
              <th>Fecha</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            {logins.map((login) => (
              <tr key={login.id}>
                <td>{login.jugador?.nombre || login.jugador_id}</td>
                <td>{login.ip}</td>
                <td>{login.user_agent}</td>
                <td>{login.exitoso ? "Sí" : "No"}</td>
                <td>{login.fecha_login}</td>
                <td>
                  <button onClick={() => editarLogin(login)}>Editar</button>
                  <button onClick={() => eliminarLogin(login.id)}>Eliminar</button>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
  );
};

export default LoginUsuariosCrud;