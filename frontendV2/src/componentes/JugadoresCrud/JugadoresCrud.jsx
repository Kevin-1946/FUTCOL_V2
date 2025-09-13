import React, { useEffect, useState } from "react";
import {
  getJugadores,
  createJugador,
  updateJugador,
  deleteJugador,
} from "../../api/JugadorService";
import "./JugadoresCrud.css";

const EMPTY_FORM = {
  nombre: "",
  n_documento: "",
  fecha_nacimiento: "",
  email: "",
  password: "",
  equipo_id: "",
};

const JugadoresCrud = () => {
  const [jugadores, setJugadores] = useState([]);
  const [form, setForm] = useState(EMPTY_FORM);
  const [editandoId, setEditandoId] = useState(null);
  const [cargando, setCargando] = useState(false);     // evita doble submit
  const [errorMsg, setErrorMsg] = useState("");        // muestra errores 422

  const cargarJugadores = async () => {
    try {
      setErrorMsg("");
      const res = await getJugadores();       // GET /api/jugadores
      // Si el back devuelve colección vacía por rol, esto será []
      setJugadores(Array.isArray(res.data) ? res.data : []);
    } catch (err) {
      const data = err.response?.data;
      setErrorMsg(
        data?.message || "No se pudieron cargar los jugadores."
      );
      console.error("GET /jugadores failed:", data || err);
      setJugadores([]);
    }
  };

  useEffect(() => {
    cargarJugadores();
  }, []);

  const manejarCambio = (e) => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };

  const buildPayload = () => {
    const payload = {
      nombre: form.nombre,
      n_documento: form.n_documento,
      fecha_nacimiento: form.fecha_nacimiento, // input type="date" -> YYYY-MM-DD
      email: form.email,
    };
    // password solo cuando se crea o cuando en edición lo cambiaron
    if (!editandoId || form.password) payload.password = form.password;
    // equipo_id solo si lo enviaron
    if (form.equipo_id !== "") payload.equipo_id = Number(form.equipo_id);
    return payload;
  };

  const formErrorsToString = (errorsObj) =>
    Object.entries(errorsObj || {})
      .map(([k, v]) => `${k}: ${(Array.isArray(v) ? v : [v]).join(", ")}`)
      .join("\n");

  const manejarSubmit = async (e) => {
    e.preventDefault();
    if (cargando) return;   // anti doble click
    setCargando(true);
    setErrorMsg("");

    const payload = buildPayload();

    try {
      if (editandoId) {
        await updateJugador(editandoId, payload);
      } else {
        await createJugador(payload);
      }

      // recarga lista y resetea formulario
      await cargarJugadores();
      setForm(EMPTY_FORM);
      setEditandoId(null);
    } catch (err) {
      const data = err.response?.data;
      const msg = data?.errors
        ? formErrorsToString(data.errors)
        : (data?.message || "Error al guardar el jugador.");
      setErrorMsg(msg);
      console.error("submit jugador error:", data || err);
      // OJO: aquí NO resetees el form para que el usuario pueda corregir
    } finally {
      setCargando(false);
    }
  };

  const editarJugador = (jugador) => {
    setErrorMsg("");
    setForm({
      nombre: jugador.nombre || "",
      n_documento: jugador.n_documento || "",
      fecha_nacimiento: jugador.fecha_nacimiento || "",
      email: jugador.email || "",
      password: "", // vacío por seguridad
      equipo_id: jugador.equipo_id ?? "",
    });
    setEditandoId(jugador.id);
  };

  const eliminarJugador = async (id) => {
    if (!confirm("¿Estás seguro de eliminar este jugador?")) return;
    try {
      setErrorMsg("");
      await deleteJugador(id);
      await cargarJugadores();
    } catch (err) {
      const data = err.response?.data;
      setErrorMsg(
        data?.message || "No se pudo eliminar el jugador."
      );
      console.error("DELETE /jugadores error:", data || err);
    }
  };

  return (
    <div className="page-container">
      <div className="jugadores-container">
        <h2>{editandoId ? "Editar Jugador" : "Nuevo Jugador"}</h2>

        {errorMsg && (
          <div className="alert-error" style={{ whiteSpace: "pre-line" }}>
            {errorMsg}
          </div>
        )}

        <form onSubmit={manejarSubmit} className="jugadores-form" noValidate>
          <input
            name="nombre"
            placeholder="Nombre"
            value={form.nombre}
            onChange={manejarCambio}
            required
          />
          <input
            name="n_documento"
            placeholder="N° Documento"
            value={form.n_documento}
            onChange={manejarCambio}
            required
          />
          <input
            type="date"
            name="fecha_nacimiento"
            value={form.fecha_nacimiento}
            onChange={manejarCambio}
            required
          />
          <input
            name="email"
            type="email"
            placeholder="Email"
            value={form.email}
            onChange={manejarCambio}
            required
          />
          <input
            name="password"
            type="password"
            placeholder="Contraseña"
            value={form.password}
            onChange={manejarCambio}
            required={!editandoId}
          />
          <input
            name="equipo_id"
            placeholder="Equipo ID (opcional)"
            value={form.equipo_id}
            onChange={manejarCambio}
          />

          <button type="submit" disabled={cargando}>
            {cargando ? "Guardando..." : (editandoId ? "Actualizar" : "Crear")}
          </button>

          {editandoId && (
            <button
              type="button"
              onClick={() => {
                setEditandoId(null);
                setForm(EMPTY_FORM);
                setErrorMsg("");
              }}
              style={{ marginLeft: 8 }}
            >
              Cancelar
            </button>
          )}
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
            {jugadores.length === 0 ? (
              <tr>
                <td colSpan="5" style={{ textAlign: "center" }}>
                  {errorMsg
                    ? "No se pudo cargar la lista."
                    : "No hay jugadores para mostrar."}
                </td>
              </tr>
            ) : (
              jugadores.map((j) => (
                <tr key={j.id}>
                  <td>{j.nombre}</td>
                  <td>{j.email}</td>
                  <td>{j.n_documento}</td>
                  <td>{j.equipo?.nombre || "N/A"}{/* evita crash si null */}</td>
                  <td>
                    <button onClick={() => editarJugador(j)}>Editar</button>
                    <button onClick={() => eliminarJugador(j.id)}>Eliminar</button>
                  </td>
                </tr>
              ))
            )}
          </tbody>
        </table>
      </div>
    </div>
  );
};

export default JugadoresCrud;