import React, { useState, useEffect } from "react";
import {
  getAmonestaciones,
  createAmonestacion,
  updateAmonestacion,
  deleteAmonestacion,
} from "../../api/AmonestacionService";
import { getJugadores } from "../../api/JugadorService";
import { getEquipos } from "../../api/EquipoService";
import { getEncuentros } from "../../api/EncuentroService";
import "./AmonestacionCrud.css";

const AmonestacionesCrud = () => {
  const [amonestaciones, setAmonestaciones] = useState([]);
  const [jugadores, setJugadores] = useState([]);
  const [equipos, setEquipos] = useState([]);
  const [encuentros, setEncuentros] = useState([]);
  const [form, setForm] = useState({
    jugador_id: "",
    equipo_id: "",
    encuentro_id: "",
    numero_camiseta: "",
    tarjeta_roja: false,
    tarjeta_amarilla: false,
    tarjeta_azul: false,
  });
  const [editingId, setEditingId] = useState(null);
  const [notification, setNotification] = useState({ show: false, message: "", type: "" });

  // Función para mostrar notificaciones
  const showNotification = (message, type) => {
    setNotification({ show: true, message, type });
    setTimeout(() => {
      setNotification({ show: false, message: "", type: "" });
    }, 3000);
  };

  useEffect(() => {
    fetchAmonestaciones();
    fetchJugadores();
    fetchEquipos();
    fetchEncuentros();
    // eslint-disable-next-line react-hooks/exhaustive-deps
    }, []);

  const fetchAmonestaciones = async () => {
    try {
      const res = await getAmonestaciones();
      setAmonestaciones(res.data);
    } catch (error) {
      console.error("Error fetching amonestaciones:", error);
      showNotification("Error al cargar las amonestaciones", "error");
    }
  };

  const fetchJugadores = async () => {
    try {
      const res = await getJugadores();
      setJugadores(res.data);
    } catch (error) {
      console.error("Error fetching jugadores:", error);
    }
  };

  const fetchEquipos = async () => {
    try {
      const res = await getEquipos();
      setEquipos(res.data);
    } catch (error) {
      console.error("Error fetching equipos:", error);
    }
  };

  const fetchEncuentros = async () => {
    try {
      const res = await getEncuentros();
      setEncuentros(res.data);
    } catch (error) {
      console.error("Error fetching encuentros:", error);
    }
  };

  const handleChange = (e) => {
    const { name, value, type, checked } = e.target;
    
    if (name === 'encuentro_id') {
      setForm({
        ...form,
        [name]: type === "checkbox" ? checked : value,
        jugador_id: "",
        equipo_id: "",
      });
    }
    else if (name === 'jugador_id' && value) {
      const jugadorSeleccionado = jugadores.find(j => j.id == value);
      setForm({
        ...form,
        [name]: value,
        equipo_id: jugadorSeleccionado ? jugadorSeleccionado.equipo_id : "",
      });
    }
    else {
      setForm({
        ...form,
        [name]: type === "checkbox" ? checked : value,
      });
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      if (editingId) {
        await updateAmonestacion(editingId, form);
        showNotification("Amonestación actualizada exitosamente", "success");
      } else {
        await createAmonestacion(form);
        showNotification("Amonestación creada exitosamente", "success");
      }
      setForm({
        jugador_id: "",
        equipo_id: "",
        encuentro_id: "",
        numero_camiseta: "",
        tarjeta_roja: false,
        tarjeta_amarilla: false,
        tarjeta_azul: false,
      });
      setEditingId(null);
      fetchAmonestaciones();
    } catch (error) {
      console.error("Error submitting form:", error);
      showNotification("Error al guardar la amonestación", "error");
    }
  };

  const handleEdit = (item) => {
    setForm({
      jugador_id: item.jugador_id,
      equipo_id: item.equipo_id,
      encuentro_id: item.encuentro_id,
      numero_camiseta: item.numero_camiseta,
      tarjeta_roja: item.tarjeta_roja,
      tarjeta_amarilla: item.tarjeta_amarilla,
      tarjeta_azul: item.tarjeta_azul,
    });
    setEditingId(item.id);
  };

  const handleDelete = async (id) => {
    if (window.confirm("¿Está seguro de eliminar esta amonestación?")) {
      try {
        await deleteAmonestacion(id);
        showNotification("Amonestación eliminada exitosamente", "success");
        fetchAmonestaciones();
      } catch (error) {
        console.error("Error deleting amonestacion:", error);
        showNotification("Error al eliminar la amonestación", "error");
      }
    }
  };

  const getJugadorNombre = (jugadorId) => {
    const jugador = jugadores.find(j => j.id === jugadorId);
    return jugador ? jugador.nombre : `ID: ${jugadorId}`;
  };

  const getJugadoresDelEncuentro = () => {
    if (!form.encuentro_id) {
      return [];
    }

    const encuentroSeleccionado = encuentros.find(e => e.id == form.encuentro_id);
    if (!encuentroSeleccionado) {
      return [];
    }

    let equipoLocalId, equipoVisitanteId;
    
    if (encuentroSeleccionado.equipoLocal && encuentroSeleccionado.equipoVisitante) {
      equipoLocalId = encuentroSeleccionado.equipoLocal.id;
      equipoVisitanteId = encuentroSeleccionado.equipoVisitante.id;
    }
    else {
      equipoLocalId = encuentroSeleccionado.equipo_local_id;
      equipoVisitanteId = encuentroSeleccionado.equipo_visitante_id;
    }

    return jugadores.filter(jugador => 
      jugador.equipo_id === equipoLocalId || jugador.equipo_id === equipoVisitanteId
    );
  };

  const getEquipoNombre = (equipoId) => {
    const equipo = equipos.find(e => e.id === equipoId);
    return equipo ? equipo.nombre : `ID: ${equipoId}`;
  };

  const getEncuentroInfo = (encuentroId) => {
    const encuentro = encuentros.find(e => e.id === encuentroId);
    if (encuentro) {
      if (encuentro.equipoLocal && encuentro.equipoVisitante) {
        return `${encuentro.equipoLocal.nombre} vs ${encuentro.equipoVisitante.nombre}`;
      }
      else {
        const equipoLocal = equipos.find(eq => eq.id === encuentro.equipo_local_id);
        const equipoVisitante = equipos.find(eq => eq.id === encuentro.equipo_visitante_id);
        const localNombre = equipoLocal ? equipoLocal.nombre : `ID: ${encuentro.equipo_local_id}`;
        const visitanteNombre = equipoVisitante ? equipoVisitante.nombre : `ID: ${encuentro.equipo_visitante_id}`;
        return `${localNombre} vs ${visitanteNombre}`;
      }
    }
    return `ID: ${encuentroId}`;
  };

  return (
    <div className="page-container">
      <div className="amonestacion-crud">
        {notification.show && (
          <div className={`notification ${notification.type}`}>
            {notification.message}
          </div>
        )}

        <h2>Amonestaciones</h2>
        
        <form onSubmit={handleSubmit}>
          <select
            name="encuentro_id"
            value={form.encuentro_id}
            onChange={handleChange}
            required
          >
            <option value="">Seleccionar Encuentro</option>
            {encuentros.map((encuentro) => (
              <option key={encuentro.id} value={encuentro.id}>
                {getEncuentroInfo(encuentro.id)} - {encuentro.fecha}
              </option>
            ))}
          </select>

          <select
            name="jugador_id"
            value={form.jugador_id}
            onChange={handleChange}
            required
            disabled={!form.encuentro_id}
          >
            <option value="">
              {form.encuentro_id ? "Seleccionar Jugador" : "Primero seleccione un encuentro"}
            </option>
            {getJugadoresDelEncuentro().map((jugador) => (
              <option key={jugador.id} value={jugador.id}>
                {jugador.nombre} - {getEquipoNombre(jugador.equipo_id)}
              </option>
            ))}
          </select>

          <select
            name="equipo_id"
            value={form.equipo_id}
            onChange={handleChange}
            required
            disabled={!form.jugador_id}
          >
            <option value="">Equipo del jugador</option>
            {equipos
              .filter(equipo => {
                const jugadorSeleccionado = jugadores.find(j => j.id == form.jugador_id);
                return jugadorSeleccionado ? equipo.id === jugadorSeleccionado.equipo_id : true;
              })
              .map((equipo) => (
                <option key={equipo.id} value={equipo.id}>
                  {equipo.nombre}
                </option>
              ))
            }
          </select>

          <input
            type="number"
            name="numero_camiseta"
            placeholder="Número Camiseta"
            value={form.numero_camiseta}
            onChange={handleChange}
            required
          />

          <label>
            <input
              type="checkbox"
              name="tarjeta_roja"
              checked={form.tarjeta_roja}
              onChange={handleChange}
            />
            Tarjeta Roja
          </label>
          <label>
            <input
              type="checkbox"
              name="tarjeta_amarilla"
              checked={form.tarjeta_amarilla}
              onChange={handleChange}
            />
            Tarjeta Amarilla
          </label>
          <label>
            <input
              type="checkbox"
              name="tarjeta_azul"
              checked={form.tarjeta_azul}
              onChange={handleChange}
            />
            Tarjeta Azul
          </label>

          <button type="submit">{editingId ? "Actualizar" : "Crear"}</button>
        </form>

        <ul>
          {amonestaciones.map((a) => (
            <li key={a.id}>
              <div className="amonestacion-info">
                <strong>Jugador:</strong> {getJugadorNombre(a.jugador_id)} | 
                <strong> Camiseta:</strong> #{a.numero_camiseta}
                <br />
                <strong>Equipo:</strong> {getEquipoNombre(a.equipo_id)}
                <br />
                <strong>Encuentro:</strong> {getEncuentroInfo(a.encuentro_id)}
                <br />
                <strong>Tarjetas:</strong>{" "}
                {a.tarjeta_roja ? "🟥 Roja " : ""}
                {a.tarjeta_amarilla ? "🟨 Amarilla " : ""}
                {a.tarjeta_azul ? "🟦 Azul " : ""}
                {!a.tarjeta_roja && !a.tarjeta_amarilla && !a.tarjeta_azul && "Ninguna"}
              </div>
              <div>
                <button onClick={() => handleEdit(a)}>Editar</button>
                <button onClick={() => handleDelete(a.id)}>Eliminar</button>
              </div>
            </li>
          ))}
        </ul>
      </div>
    </div>
  );
};

export default AmonestacionesCrud;