import React, { useState, useEffect } from "react";
import {
  getEquipos,
  createEquipo,
  updateEquipo,
  deleteEquipo,
} from "../../api/EquipoService";
import axios from "../../axios.js"; // Usar tu configuración de axios
import "./EquiposCrud.css";

const EquiposCrud = () => {
  const [equipos, setEquipos] = useState([]);
  const [torneos, setTorneos] = useState([]);
  const [jugadores, setJugadores] = useState([]);
  const [form, setForm] = useState({ 
    nombre: "", 
    torneo_id: "", 
    capitan_id: "" 
  });
  const [editingId, setEditingId] = useState(null);
  const [loading, setLoading] = useState(false);

  // Función para obtener equipos
  const fetchEquipos = async () => {
    try {
      setLoading(true);
      const res = await getEquipos();
      setEquipos(res.data);
    } catch (error) {
      console.error("Error al obtener equipos:", error);
    } finally {
      setLoading(false);
    }
  };

  // Función para obtener torneos usando tu axios configurado
  const fetchTorneos = async () => {
    try {
      const response = await axios.get('/torneos');
      setTorneos(response.data);
    } catch (error) {
      console.error("Error al obtener torneos:", error);
    }
  };

  // Función para obtener jugadores usando tu axios configurado
  const fetchJugadores = async () => {
    try {
      const response = await axios.get('/jugadores');
      setJugadores(response.data);
    } catch (error) {
      console.error("Error al obtener jugadores:", error);
    }
  };

  useEffect(() => {
    fetchEquipos();
    fetchTorneos();
    fetchJugadores();
  }, []);

  const handleChange = (e) => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    
    // Validación básica
    if (!form.nombre || !form.torneo_id) {
      alert("El nombre y el torneo son obligatorios");
      return;
    }

    try {
      setLoading(true);
      
      // Preparar datos para enviar
      const dataToSend = {
        nombre: form.nombre.trim(),
        torneo_id: parseInt(form.torneo_id),
        ...(form.capitan_id && { capitan_id: parseInt(form.capitan_id) })
      };

      if (editingId) {
        const response = await updateEquipo(editingId, dataToSend);
        alert("Equipo actualizado correctamente");
      } else {
        const response = await createEquipo(dataToSend);
        alert("Equipo creado correctamente");
      }
      
      setForm({ nombre: "", torneo_id: "", capitan_id: "" });
      setEditingId(null);
      fetchEquipos();
    } catch (error) {
      console.error("Error al guardar equipo:", error);
      
      // Manejo específico de errores
      if (error.response?.status === 422) {
        const errors = error.response.data.errors;
        if (errors) {
          const errorMessages = Object.values(errors).flat().join('\n');
          alert(`Errores de validación:\n${errorMessages}`);
        } else {
          alert(error.response.data.message || "Error de validación");
        }
      } else if (error.response?.status === 403) {
        alert("No tienes permisos para realizar esta acción");
      } else {
        alert("Error al guardar el equipo. Intenta nuevamente.");
      }
    } finally {
      setLoading(false);
    }
  };

  const handleEdit = (equipo) => {
    setForm({
      nombre: equipo.nombre,
      torneo_id: equipo.torneo_id?.toString() || "",
      capitan_id: equipo.capitan_id?.toString() || ""
    });
    setEditingId(equipo.id);
  };

  const handleDelete = async (id) => {
    if (window.confirm("⚠️ ¿Estás seguro de que quieres eliminar este equipo?\n\nEsta acción no se puede deshacer y eliminará también:\n- Todos los jugadores asociados\n- Las inscripciones del equipo\n- Los encuentros programados")) {
      try {
        setLoading(true);
        await deleteEquipo(id);
        alert("Equipo eliminado correctamente");
        fetchEquipos();
      } catch (error) {
        console.error("Error al eliminar equipo:", error);
        
        if (error.response?.status === 403) {
          alert("No tienes permisos para eliminar equipos");
        } else if (error.response?.status === 404) {
          alert("El equipo no existe o ya fue eliminado");
        } else {
          alert("Error al eliminar el equipo. Puede que tenga datos asociados.");
        }
      } finally {
        setLoading(false);
      }
    }
  };

  const handleCancel = () => {
    setForm({ nombre: "", torneo_id: "", capitan_id: "" });
    setEditingId(null);
  };

  // Obtener nombre del torneo
  const getTorneoNombre = (torneoId) => {
    const torneo = torneos.find(t => t.id === torneoId);
    return torneo ? torneo.nombre : "Sin torneo";
  };

  // Obtener nombre del capitán
  const getCapitanNombre = (capitanId) => {
    const capitan = jugadores.find(j => j.id === capitanId);
    return capitan ? capitan.nombre : "Sin capitán";
  };

  // Filtrar jugadores disponibles (sin equipo o del equipo actual si está editando)
  const getJugadoresDisponibles = () => {
    if (editingId) {
      // En modo edición, mostrar todos los jugadores
      return jugadores;
    }
    // En modo creación, mostrar solo jugadores sin equipo
    return jugadores.filter(jugador => !jugador.equipo_id);
  };

  if (loading && equipos.length === 0) {
    return <div className="loading">Cargando...</div>;
  }

  return (
    <div className="page-container">
      <div className="equipo-crud">
        <h2>Gestión de Equipos</h2>
        
        <form onSubmit={handleSubmit} className="equipo-form">
          <div className="form-group">
            <label htmlFor="nombre">Nombre del Equipo *</label>
            <input
              id="nombre"
              name="nombre"
              type="text"
              placeholder="Ingrese el nombre del equipo"
              value={form.nombre}
              onChange={handleChange}
              required
            />
          </div>

          <div className="form-group">
            <label htmlFor="torneo_id">Torneo *</label>
            <select
              id="torneo_id"
              name="torneo_id"
              value={form.torneo_id}
              onChange={handleChange}
              required
            >
              <option value="">Seleccione un torneo</option>
              {torneos.map((torneo) => (
                <option key={torneo.id} value={torneo.id}>
                  {torneo.nombre}
                </option>
              ))}
            </select>
          </div>

          <div className="form-group">
            <label htmlFor="capitan_id">Capitán (Opcional)</label>
            <select
              id="capitan_id"
              name="capitan_id"
              value={form.capitan_id}
              onChange={handleChange}
            >
              <option value="">Seleccione un capitán</option>
              {getJugadoresDisponibles().map((jugador) => (
                <option key={jugador.id} value={jugador.id}>
                  {jugador.nombre} - {jugador.email}
                  {jugador.equipo_id && jugador.equipo_id !== editingId ? ' (En otro equipo)' : ''}
                </option>
              ))}
            </select>
            <small className="form-help">
              {editingId 
                ? "Puedes cambiar el capitán del equipo" 
                : "Solo se muestran jugadores disponibles (sin equipo)"
              }
            </small>
          </div>

          <div className="form-actions">
            <button type="submit" disabled={loading}>
              {loading ? "Guardando..." : editingId ? "Actualizar" : "Crear"}
            </button>
            {editingId && (
              <button type="button" onClick={handleCancel} className="btn-cancel">
                Cancelar
              </button>
            )}
          </div>
        </form>

        <div className="equipos-list">
          <h3>Lista de Equipos ({equipos.length})</h3>
          {equipos.length === 0 ? (
            <p className="no-data">No hay equipos registrados</p>
          ) : (
            <div className="equipos-grid">
              {equipos.map((equipo) => (
                <div key={equipo.id} className="equipo-card">
                  <div className="equipo-header">
                    <h4>{equipo.nombre}</h4>
                    <span className="equipo-id">ID: {equipo.id}</span>
                  </div>
                  
                  <div className="equipo-info">
                    <div className="info-item">
                      <strong>Torneo:</strong> {getTorneoNombre(equipo.torneo_id)}
                    </div>
                    <div className="info-item">
                      <strong>Capitán:</strong> {getCapitanNombre(equipo.capitan_id)}
                    </div>
                    <div className="info-item">
                      <strong>Jugadores:</strong> {equipo.jugadores ? equipo.jugadores.length : 0}
                    </div>
                    {equipo.inscripcion && (
                      <div className="info-item">
                        <strong>Estado:</strong> 
                        <span className="status-badge inscrito">Inscrito</span>
                      </div>
                    )}
                  </div>
                  
                  <div className="equipo-actions">
                    <button 
                      onClick={() => handleEdit(equipo)}
                      className="btn-edit"
                      disabled={loading}
                    >
                      Editar
                    </button>
                    <button 
                      onClick={() => handleDelete(equipo.id)}
                      className="btn-delete"
                      disabled={loading}
                    >
                      Eliminar
                    </button>
                  </div>
                </div>
              ))}
            </div>
          )}
        </div>
      </div>
    </div>
  );
};

export default EquiposCrud;