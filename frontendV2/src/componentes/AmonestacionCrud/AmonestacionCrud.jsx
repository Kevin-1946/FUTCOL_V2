import React, { useState, useEffect } from "react";
import {
  getAmonestaciones,
  createAmonestacion,
  updateAmonestacion,
  deleteAmonestacion,
} from "../../api/AmonestacionService";
// Importa los servicios para obtener jugadores, equipos y encuentros
import { getJugadores } from "../../api/JugadorService"; // Asume que tienes este servicio
import { getEquipos } from "../../api/EquipoService"; // Asume que tienes este servicio
import { getEncuentros } from "../../api/EncuentroService"; // Asume que tienes este servicio
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

  useEffect(() => {
    fetchAmonestaciones();
    fetchJugadores();
    fetchEquipos();
    fetchEncuentros();
  }, []);

  const fetchAmonestaciones = async () => {
    try {
      const res = await getAmonestaciones();
      setAmonestaciones(res.data);
    } catch (error) {
      console.error("Error fetching amonestaciones:", error);
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
    
    // ✅ NUEVO - Limpiar jugador_id cuando cambie el encuentro
    if (name === 'encuentro_id') {
      setForm({
        ...form,
        [name]: type === "checkbox" ? checked : value,
        jugador_id: "", // Limpiar selección de jugador
        equipo_id: "", // Limpiar selección de equipo
      });
    }
    // ✅ NUEVO - Auto-completar equipo cuando se seleccione jugador
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
      } else {
        await createAmonestacion(form);
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
    try {
      await deleteAmonestacion(id);
      fetchAmonestaciones();
    } catch (error) {
      console.error("Error deleting amonestacion:", error);
    }
  };

  // ✅ CORREGIDO - Función para obtener el nombre del jugador por ID
  const getJugadorNombre = (jugadorId) => {
    const jugador = jugadores.find(j => j.id === jugadorId);
    return jugador ? jugador.nombre : `ID: ${jugadorId}`;
  };

  // ✅ NUEVO - Función para obtener jugadores del encuentro seleccionado
  const getJugadoresDelEncuentro = () => {
    if (!form.encuentro_id) {
      return []; // Si no hay encuentro seleccionado, no mostrar jugadores
    }

    const encuentroSeleccionado = encuentros.find(e => e.id == form.encuentro_id);
    if (!encuentroSeleccionado) {
      return [];
    }

    // Obtener IDs de los equipos del encuentro
    let equipoLocalId, equipoVisitanteId;
    
    // Si tiene las relaciones cargadas
    if (encuentroSeleccionado.equipoLocal && encuentroSeleccionado.equipoVisitante) {
      equipoLocalId = encuentroSeleccionado.equipoLocal.id;
      equipoVisitanteId = encuentroSeleccionado.equipoVisitante.id;
    }
    // Si solo tiene los IDs
    else {
      equipoLocalId = encuentroSeleccionado.equipo_local_id;
      equipoVisitanteId = encuentroSeleccionado.equipo_visitante_id;
    }

    // Filtrar jugadores que pertenezcan a cualquiera de los dos equipos
    return jugadores.filter(jugador => 
      jugador.equipo_id === equipoLocalId || jugador.equipo_id === equipoVisitanteId
    );
  };

  // ✅ CORREGIDO - Función para obtener el nombre del equipo por ID
  const getEquipoNombre = (equipoId) => {
    const equipo = equipos.find(e => e.id === equipoId);
    return equipo ? equipo.nombre : `ID: ${equipoId}`;
  };

  // ✅ CORREGIDO - Función para obtener información del encuentro por ID
  const getEncuentroInfo = (encuentroId) => {
    const encuentro = encuentros.find(e => e.id === encuentroId);
    if (encuentro) {
      // Si tiene las relaciones cargadas
      if (encuentro.equipoLocal && encuentro.equipoVisitante) {
        return `${encuentro.equipoLocal.nombre} vs ${encuentro.equipoVisitante.nombre}`;
      }
      // Si solo tiene los IDs
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
        <h2>Amonestaciones</h2>
        <form onSubmit={handleSubmit}>
          {/* ✅ REORDENADO - Primero seleccionar el encuentro */}
          <select
            name="encuentro_id"
            value={form.encuentro_id}
            onChange={handleChange}
            required
          >
            <option value="">Seleccionar Encuentro</option>
            {encuentros.map((encuentro) => (
              <option key={encuentro.id} value={encuentro.id}>
                {/* ✅ CORREGIDO - Usar la función que maneja ambos casos */}
                {getEncuentroInfo(encuentro.id)} - {encuentro.fecha}
              </option>
            ))}
          </select>

          {/* ✅ MODIFICADO - Selector de Jugador (solo del encuentro seleccionado) */}
          <select
            name="jugador_id"
            value={form.jugador_id}
            onChange={handleChange}
            required
            disabled={!form.encuentro_id} // Deshabilitado hasta que se seleccione encuentro
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

          {/* ✅ MODIFICADO - Auto-completar equipo basado en el jugador seleccionado */}
          <select
            name="equipo_id"
            value={form.equipo_id}
            onChange={handleChange}
            required
            disabled={!form.jugador_id} // Deshabilitado hasta que se seleccione jugador
          >
            <option value="">Equipo del jugador</option>
            {equipos
              .filter(equipo => {
                // Solo mostrar el equipo del jugador seleccionado
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

          {/* Campo de número de camiseta */}
          <input
            type="number"
            name="numero_camiseta"
            placeholder="Número Camiseta"
            value={form.numero_camiseta}
            onChange={handleChange}
            required
          />

          {/* Checkboxes para tarjetas */}
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
                {/* ✅ CORREGIDO - Usar la función corregida */}
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