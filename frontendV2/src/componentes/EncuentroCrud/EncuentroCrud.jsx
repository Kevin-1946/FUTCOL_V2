import React, { useEffect, useState } from "react";
import {
  getEncuentros,
  createEncuentro,
  updateEncuentro,
  deleteEncuentro,
} from "../../api/EncuentroService";
// ✅ AGREGADO - Importar servicios necesarios
import { getTorneos } from "../../api/TorneoService";
import { getSedes } from "../../api/SedeService";
import { getEquipos } from "../../api/EquipoService";
import "./EncuentroCrud.css";

const EncuentrosCrud = () => {
  const [encuentros, setEncuentros] = useState([]);
  // ✅ AGREGADO - Estados para los datos relacionados
  const [torneos, setTorneos] = useState([]);
  const [sedes, setSedes] = useState([]);
  const [equipos, setEquipos] = useState([]);
  
  const [form, setForm] = useState({
    torneo_id: "",
    sede_id: "",
    fecha: "",
    hora: "", // ✅ AGREGADO - Campo hora del modelo
    equipo_local_id: "",
    equipo_visitante_id: "",
    goles_local: "", // ✅ CORREGIDO - Nombres según el modelo
    goles_visitante: "", // ✅ CORREGIDO - Nombres según el modelo
  });
  const [editingId, setEditingId] = useState(null);

  const fetchEncuentros = async () => {
    try {
      const res = await getEncuentros();
      setEncuentros(res.data);
    } catch (error) {
      console.error("Error fetching encuentros:", error);
    }
  };

  // ✅ AGREGADO - Funciones para obtener datos relacionados
  const fetchTorneos = async () => {
    try {
      const res = await getTorneos();
      setTorneos(res.data);
    } catch (error) {
      console.error("Error fetching torneos:", error);
    }
  };

  const fetchSedes = async () => {
    try {
      const res = await getSedes();
      setSedes(res.data);
    } catch (error) {
      console.error("Error fetching sedes:", error);
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

  useEffect(() => {
    fetchEncuentros();
    fetchTorneos();
    fetchSedes();
    fetchEquipos();
  }, []);

  // ✅ AGREGADO - Función para obtener equipos del torneo seleccionado
  const getEquiposDelTorneo = () => {
    if (!form.torneo_id) {
      return [];
    }
    // Filtrar equipos que pertenezcan al torneo seleccionado
    return equipos.filter(equipo => equipo.torneo_id == form.torneo_id);
  };

  // ✅ AGREGADO - Función para obtener equipos visitantes (excluyendo el local)
  const getEquiposVisitantes = () => {
    const equiposDelTorneo = getEquiposDelTorneo();
    if (!form.equipo_local_id) {
      return equiposDelTorneo;
    }
    // Excluir el equipo local de las opciones de visitante
    return equiposDelTorneo.filter(equipo => equipo.id != form.equipo_local_id);
  };

  // ✅ MEJORADO - Función para obtener nombres
  const getTorneoNombre = (torneoId) => {
    const torneo = torneos.find(t => t.id === torneoId);
    return torneo ? torneo.nombre : `ID: ${torneoId}`;
  };

  const getSedeNombre = (sedeId) => {
    const sede = sedes.find(s => s.id === sedeId);
    return sede ? sede.nombre : `ID: ${sedeId}`;
  };

  const getEquipoNombre = (equipoId) => {
    const equipo = equipos.find(e => e.id === equipoId);
    return equipo ? equipo.nombre : `ID: ${equipoId}`;
  };

  const handleChange = (e) => {
    const { name, value } = e.target;
    
    // ✅ NUEVO - Limpiar selecciones cuando cambie el torneo
    if (name === 'torneo_id') {
      setForm({
        ...form,
        [name]: value,
        equipo_local_id: "", // Limpiar equipo local
        equipo_visitante_id: "", // Limpiar equipo visitante
      });
    }
    // ✅ NUEVO - Limpiar equipo visitante cuando cambie el local
    else if (name === 'equipo_local_id') {
      setForm({
        ...form,
        [name]: value,
        equipo_visitante_id: "", // Limpiar equipo visitante
      });
    }
    else {
      setForm({ ...form, [name]: value });
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    
    // ✅ AGREGADO - Validaciones básicas
    if (form.equipo_local_id === form.equipo_visitante_id) {
      alert("El equipo local y visitante no pueden ser el mismo");
      return;
    }
    
    try {
      if (editingId) {
        await updateEncuentro(editingId, form);
      } else {
        await createEncuentro(form);
      }
      setForm({
        torneo_id: "",
        sede_id: "",
        fecha: "",
        hora: "",
        equipo_local_id: "",
        equipo_visitante_id: "",
        goles_local: "",
        goles_visitante: "",
      });
      setEditingId(null);
      fetchEncuentros();
    } catch (error) {
      console.error("Error submitting form:", error);
      alert("Error al guardar el encuentro");
    }
  };

  const handleEdit = (encuentro) => {
    setForm({
      torneo_id: encuentro.torneo_id || "",
      sede_id: encuentro.sede_id || "",
      fecha: encuentro.fecha || "",
      hora: encuentro.hora || "",
      equipo_local_id: encuentro.equipo_local_id || "",
      equipo_visitante_id: encuentro.equipo_visitante_id || "",
      goles_local: encuentro.goles_local || "",
      goles_visitante: encuentro.goles_visitante || "",
    });
    setEditingId(encuentro.id);
  };

  const handleDelete = async (id) => {
    if (window.confirm("¿Está seguro de eliminar este encuentro?")) {
      try {
        await deleteEncuentro(id);
        fetchEncuentros();
      } catch (error) {
        console.error("Error deleting encuentro:", error);
        alert("Error al eliminar el encuentro");
      }
    }
  };

  return (
    <div className="page-container">
      <div className="encuentro-crud">
        <h2>Encuentros</h2>
        <form onSubmit={handleSubmit}>
          {/* ✅ MEJORADO - Selector de Torneo */}
          <select
            name="torneo_id"
            value={form.torneo_id}
            onChange={handleChange}
            required
          >
            <option value="">Seleccionar Torneo</option>
            {torneos.map((torneo) => (
              <option key={torneo.id} value={torneo.id}>
                {torneo.nombre}
              </option>
            ))}
          </select>

          {/* ✅ MEJORADO - Selector de Sede */}
          <select
            name="sede_id"
            value={form.sede_id}
            onChange={handleChange}
            required
          >
            <option value="">Seleccionar Sede</option>
            {sedes.map((sede) => (
              <option key={sede.id} value={sede.id}>
                {sede.nombre}
              </option>
            ))}
          </select>

          {/* ✅ MEJORADO - Campo de fecha */}
          <input
            name="fecha"
            type="date"
            value={form.fecha}
            onChange={handleChange}
            required
          />

          {/* ✅ AGREGADO - Campo de hora */}
          <input
            name="hora"
            type="time"
            value={form.hora}
            onChange={handleChange}
            required
          />

          {/* ✅ MEJORADO - Selector de Equipo Local */}
          <select
            name="equipo_local_id"
            value={form.equipo_local_id}
            onChange={handleChange}
            required
            disabled={!form.torneo_id}
          >
            <option value="">
              {form.torneo_id ? "Seleccionar Equipo Local" : "Primero seleccione un torneo"}
            </option>
            {getEquiposDelTorneo().map((equipo) => (
              <option key={equipo.id} value={equipo.id}>
                {equipo.nombre}
              </option>
            ))}
          </select>

          {/* ✅ MEJORADO - Selector de Equipo Visitante */}
          <select
            name="equipo_visitante_id"
            value={form.equipo_visitante_id}
            onChange={handleChange}
            required
            disabled={!form.equipo_local_id}
          >
            <option value="">
              {form.equipo_local_id ? "Seleccionar Equipo Visitante" : "Primero seleccione el equipo local"}
            </option>
            {getEquiposVisitantes().map((equipo) => (
              <option key={equipo.id} value={equipo.id}>
                {equipo.nombre}
              </option>
            ))}
          </select>

          {/* ✅ MEJORADO - Campos de goles */}
          <input
            name="goles_local"
            type="number"
            min="0"
            placeholder="Goles Equipo Local"
            value={form.goles_local}
            onChange={handleChange}
          />

          <input
            name="goles_visitante"
            type="number"
            min="0"
            placeholder="Goles Equipo Visitante"
            value={form.goles_visitante}
            onChange={handleChange}
          />

          <button type="submit">
            {editingId ? "Actualizar" : "Crear"}
          </button>
        </form>

        <ul>
          {encuentros.map((e) => (
            <li key={e.id}>
              <div className="encuentro-header">
                <strong>{getTorneoNombre(e.torneo_id)}</strong>
                <span className="fecha-hora">{e.fecha} - {e.hora}</span>
              </div>
              
              <div className="encuentro-info">
                <div className="equipos">
                  <div className="equipo-local">
                    <strong>Local:</strong> {getEquipoNombre(e.equipo_local_id)}
                    {e.goles_local !== null && <span className="goles"> - {e.goles_local} goles</span>}
                  </div>
                  <div className="vs">VS</div>
                  <div className="equipo-visitante">
                    <strong>Visitante:</strong> {getEquipoNombre(e.equipo_visitante_id)}
                    {e.goles_visitante !== null && <span className="goles"> - {e.goles_visitante} goles</span>}
                  </div>
                </div>
                
                <div className="sede">
                  <strong>Sede:</strong> {getSedeNombre(e.sede_id)}
                </div>
              </div>
              
              <div className="acciones">
                <button onClick={() => handleEdit(e)}>Editar</button>
                <button onClick={() => handleDelete(e.id)}>Eliminar</button>
              </div>
            </li>
          ))}
        </ul>
      </div>
    </div>
  );
};

export default EncuentrosCrud;