import React, { useEffect, useState } from "react";
import {
  getSedes,
  createSede,
  updateSede,
  deleteSede,
} from "../../api/SedeService";
import "./SedesCrud.css";

const SedesCrud = () => {
  const [sedes, setSedes] = useState([]);
  const [form, setForm] = useState({
    nombre: "",
    direccion: "",
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

  useEffect(() => {
    fetchSedes();
  }, []);

  const handleChange = (e) => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();

    // ✅ Validación solo de nombre y dirección
    if (!form.nombre.trim() || !form.direccion.trim()) {
      alert("Por favor, completa nombre y dirección");
      return;
    }

    try {
      setLoading(true);
      if (editingId) {
        await updateSede(editingId, form); // envía solo nombre y dirección
      } else {
        await createSede(form); // envía solo nombre y dirección
      }
      setForm({ nombre: "", direccion: "" });
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
      nombre: sede.nombre || "",
      direccion: sede.direccion || "",
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
    setForm({ nombre: "", direccion: "" });
    setEditingId(null);
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

          {/* ✅ Sin select de torneo */}

          <div className="form-buttons">
            <button type="submit" disabled={loading}>
              {loading ? "Guardando..." : editingId ? "Actualizar" : "Crear"}
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
                    {/* ❌ Se quita el texto de torneo */}
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
