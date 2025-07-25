import React, { useEffect, useState } from "react";
import {
  getSedes,
  createSede,
  updateSede,
  deleteSede,
} from "../../api/SedeService";
// Importa el servicio para obtener torneos
import { getTorneos } from "../../api/TorneoService";
import "./SedesCrud.css";

const SedesCrud = () => {
  const [sedes, setSedes] = useState([]);
  const [torneos, setTorneos] = useState([]); // Estado para los torneos
  const [form, setForm] = useState({
    nombre: "",
    direccion: "",
    torneo_id: "",
  });
  const [editingId, setEditingId] = useState(null);
  const [loading, setLoading] = useState(false);

  const fetchSedes = async () => {
    try {
      setLoading(true);
      const res = await getSedes();
      setSedes(res.data);
    } catch (error) {
      console.error("Error al cargar sedes:", error);
    } finally {
      setLoading(false);
    }
  };

  const fetchTorneos = async () => {
    try {
      const res = await getTorneos();
      setTorneos(res.data); // Como tu service ya devuelve la respuesta de axios, res.data contiene los datos
    } catch (error) {
      console.error("Error al cargar torneos:", error);
    }
  };

  useEffect(() => {
    fetchSedes();
    fetchTorneos(); // Cargar torneos al montar el componente
  }, []);

  const handleChange = (e) => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    
    // Validación básica
    if (!form.nombre.trim() || !form.direccion.trim() || !form.torneo_id) {
      alert("Por favor, completa todos los campos");
      return;
    }

    try {
      setLoading(true);
      if (editingId) {
        await updateSede(editingId, form);
      } else {
        await createSede(form);
      }
      setForm({ nombre: "", direccion: "", torneo_id: "" });
      setEditingId(null);
      await fetchSedes();
    } catch (error) {
      console.error("Error al guardar sede:", error);
      alert("Error al guardar la sede");
    } finally {
      setLoading(false);
    }
  };

  const handleEdit = (sede) => {
    setForm({
      nombre: sede.nombre,
      direccion: sede.direccion,
      torneo_id: sede.torneo_id.toString(), // Asegurar que sea string para el select
    });
    setEditingId(sede.id);
  };

  const handleDelete = async (id) => {
    if (window.confirm("¿Estás seguro de que quieres eliminar esta sede?")) {
      try {
        setLoading(true);
        await deleteSede(id);
        await fetchSedes();
      } catch (error) {
        console.error("Error al eliminar sede:", error);
        alert("Error al eliminar la sede");
      } finally {
        setLoading(false);
      }
    }
  };

  const handleCancel = () => {
    setForm({ nombre: "", direccion: "", torneo_id: "" });
    setEditingId(null);
  };

  // Función para obtener el nombre del torneo por ID
  const getTorneoNombre = (torneoId) => {
    const torneo = torneos.find(t => t.id === torneoId);
    return torneo ? torneo.nombre : `Torneo ID: ${torneoId}`;
  };

  return (
    <div className="page-container">
      <div className="sede-crud">
        <h2>Gestión de Sedes</h2>
        
        <form onSubmit={handleSubmit}>
          <input
            type="text"
            name="nombre"
            placeholder="Nombre de la sede"
            value={form.nombre}
            onChange={handleChange}
            required
            disabled={loading}
          />
          
          <input
            type="text"
            name="direccion"
            placeholder="Dirección"
            value={form.direccion}
            onChange={handleChange}
            required
            disabled={loading}
          />
          
          <select
            name="torneo_id"
            value={form.torneo_id}
            onChange={handleChange}
            required
            disabled={loading}
            className="select-torneo"
          >
            <option value="">Selecciona un torneo</option>
            {torneos.map((torneo) => (
              <option key={torneo.id} value={torneo.id}>
                {torneo.nombre}
              </option>
            ))}
          </select>

          <div className="form-buttons">
            <button type="submit" disabled={loading}>
              {loading ? "Guardando..." : (editingId ? "Actualizar" : "Crear")}
            </button>
            {editingId && (
              <button type="button" onClick={handleCancel} disabled={loading}>
                Cancelar
              </button>
            )}
          </div>
        </form>

        {loading && <div className="loading">Cargando...</div>}

        <div className="sedes-list">
          <h3>Lista de Sedes ({sedes.length})</h3>
          {sedes.length === 0 ? (
            <p className="no-data">No hay sedes registradas</p>
          ) : (
            <ul>
              {sedes.map((sede) => (
                <li key={sede.id}>
                  <div className="sede-info">
                    <strong>{sede.nombre}</strong>
                    <span className="direccion">{sede.direccion}</span>
                    <span className="torneo">
                      Torneo: {getTorneoNombre(sede.torneo_id)}
                    </span>
                  </div>
                  <div className="sede-actions">
                    <button 
                      onClick={() => handleEdit(sede)}
                      disabled={loading}
                      className="btn-edit"
                    >
                      Editar
                    </button>
                    <button 
                      onClick={() => handleDelete(sede.id)}
                      disabled={loading}
                      className="btn-delete"
                    >
                      Eliminar
                    </button>
                  </div>
                </li>
              ))}
            </ul>
          )}
        </div>
      </div>
    </div>
  );
};

export default SedesCrud;