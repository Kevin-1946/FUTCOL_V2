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

  // Estado para notificaciones
  const [notification, setNotification] = useState({ show: false, message: "", type: "" });

  const showNotification = (message, type) => {
    setNotification({ show: true, message, type });
    setTimeout(() => {
      setNotification({ show: false, message: "", type: "" });
    }, 10000);
  };

  const fetchSedes = async () => {
    try {
      setLoading(true);
      const res = await getSedes();
      setSedes(res.data);
    } catch (error) {
      console.error("Error al cargar sedes:", error);
      showNotification("⚠ Error al cargar las sedes", "error");
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchSedes();
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  const handleChange = (e) => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();

    if (!form.nombre.trim() || !form.direccion.trim()) {
      showNotification("Por favor, completa nombre y dirección", "error");
      return;
    }

    try {
      setLoading(true);

      if (editingId) {
        await updateSede(editingId, form);
        showNotification("Sede actualizada correctamente", "success");
      } else {
        await createSede(form);
        showNotification("Sede creada exitosamente", "success");
      }

      setForm({ nombre: "", direccion: "" });
      setEditingId(null);
      await fetchSedes();
    } catch (error) {
      console.error("Error al guardar sede:", error);
      showNotification("❌ Error al guardar la sede", "error");
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
    if (window.confirm("¿Estás seguro de eliminar esta sede?")) {
      try {
        setLoading(true);
        await deleteSede(id);
        await fetchSedes();
        showNotification("Sede eliminada con éxito 🗑️", "success");
      } catch (error) {
        console.error("Error al eliminar sede:", error);
        showNotification("⚠ No se pudo eliminar la sede", "error");
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

        {notification.show && (
          <div className={`notification ${notification.type}`}>
            {notification.message}
          </div>
        )}

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
                  </div>
                  <div className="sede-actions">
                    <button className="btn-edit" onClick={() => handleEdit(sede)} disabled={loading}>
                      Editar
                    </button>
                    <button className="btn-delete" onClick={() => handleDelete(sede.id)} disabled={loading}>
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
