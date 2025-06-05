import React, { useEffect, useState } from "react";
import {
  getJugadores,
  createJugador,
  updateJugador,
  deleteJugador,
} from "../../api/JugadorService";
import "./JugadoresCrud.css";

const JugadoresCrud = () => {
  const [jugadores, setJugadores] = useState([]);
  const [form, setForm] = useState({
    nombre: "",
    n_documento: "",
    fecha_nacimiento: "",
    email: "",
    password: "",
    equipo_id: "",
  });
  const [editandoId, setEditandoId] = useState(null);

  const cargarJugadores = () => {
    getJugadores()
      .then((res) => setJugadores(res.data))
      .catch((err) => console.error(err));
  };

  useEffect(() => {
    cargarJugadores();
  }, []);

  const manejarCambio = (e) => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };

  const manejarSubmit = (e) => {
    e.preventDefault();
    const metodo = editandoId ? updateJugador : createJugador;
    const args = editandoId ? [editandoId, form] : [form];

    metodo(...args)
      .then(() => {
        cargarJugadores();
        setForm({
          nombre: "",
          n_documento: "",
          fecha_nacimiento: "",
          email: "",
          password: "",
          equipo_id: "",
        });
        setEditandoId(null);
      })
      .catch((err) => console.error(err));
  };

  const editarJugador = (jugador) => {
    setForm({
      nombre: jugador.nombre,
      n_documento: jugador.n_documento,
      fecha_nacimiento: jugador.fecha_nacimiento,
      email: jugador.email,
      password: "", // Dejar vacío, no se muestra por seguridad
      equipo_id: jugador.equipo_id || "",
    });
    setEditandoId(jugador.id);
  };

  const eliminarJugador = (id) => {
    if (confirm("¿Estás seguro de eliminar este jugador?")) {
      deleteJugador(id)
        .then(() => cargarJugadores())
        .catch((err) => console.error(err));
    }
  };

  return (
      <div className="jugadores-container">
        <h2>{editandoId ? "Editar Jugador" : "Nuevo Jugador"}</h2>

        <form onSubmit={manejarSubmit} className="jugadores-form">
          <input name="nombre" placeholder="Nombre" value={form.nombre} onChange={manejarCambio} required />
          <input name="n_documento" placeholder="N° Documento" value={form.n_documento} onChange={manejarCambio} required />
          <input type="date" name="fecha_nacimiento" value={form.fecha_nacimiento} onChange={manejarCambio} required />
          <input name="email" type="email" placeholder="Email" value={form.email} onChange={manejarCambio} required />
          <input name="password" type="password" placeholder="Contraseña" value={form.password} onChange={manejarCambio} required={!editandoId} />
          <input name="equipo_id" placeholder="Equipo ID (opcional)" value={form.equipo_id} onChange={manejarCambio} />

          <button type="submit">{editandoId ? "Actualizar" : "Crear"}</button>
        </form>

        <h3>Lista de Jugadores</h3>
        <table>
          <thead>
            <tr>
              <th>Nombre</th>
              <th>Email</th>
              <th>Documento</th>
              <th>Equipo</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            {jugadores.map((j) => (
              <tr key={j.id}>
                <td>{j.nombre}</td>
                <td>{j.email}</td>
                <td>{j.n_documento}</td>
                <td>{j.equipo?.nombre || "N/A"}</td>
                <td>
                  <button onClick={() => editarJugador(j)}>Editar</button>
                  <button onClick={() => eliminarJugador(j.id)}>Eliminar</button>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
  );
};

export default JugadoresCrud;