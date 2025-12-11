import React, { useState, useEffect } from "react";
import {
  getTorneos,
  createTorneo,
  updateTorneo,
  deleteTorneo,
} from "../../api/TorneoService";
import { getSedes } from "../../api/SedeService";
import "./TorneosCrud.css";

const TorneosCrud = () => {
  const [torneos, setTorneos] = useState([]);
  const [sedes, setSedes] = useState([]);

  const [form, setForm] = useState({
    nombre: "",
    categoria: "",
    fecha_inicio: "",
    fecha_fin: "",
    modalidad: "",
    organizador: "",
    precio: "",
    sede_id: "",
  });

  const [notification, setNotification] = useState({ show: false, message: "", type: "" });
  const [editingId, setEditingId] = useState(null);

  const showNotification = (message, type) => {
    setNotification({ show: true, message, type });
    setTimeout(() => {
      setNotification({ show: false, message: "", type: "" });
    }, 5000);
  };

  const fetchTorneos = async () => {
    try {
      const res = await getTorneos();
      setTorneos(res.data);
    } catch (error) {
      console.error("Error al obtener torneos:", error);
      showNotification("Error al cargar los torneos", "error");
    }
  };

  const fetchSedes = async () => {
    try {
      const res = await getSedes();
      setSedes(res.data);
    } catch (error) {
      console.error("Error al obtener sedes:", error);
    }
  };

  useEffect(() => {
    fetchTorneos();
    fetchSedes();
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  const handleChange = (e) => {
    const { name, value } = e.target;
    setForm((prev) => ({ ...prev, [name]: value }));
  };

  const buildPayload = () => {
    const payload = {
      nombre: form.nombre,
      categoria: form.categoria,
      fecha_inicio: form.fecha_inicio,
      fecha_fin: form.fecha_fin,
      modalidad: form.modalidad,
      organizador: (form.organizador || "").trim(),
      precio: form.precio === "" ? "" : Number(form.precio),
    };

    if (form.sede_id) {
      payload.sede_id = Number(form.sede_id);
    }

    Object.keys(payload).forEach((k) => {
      if (payload[k] === "" || payload[k] === null || payload[k] === undefined) {
        delete payload[k];
      }
    });

    return payload;
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    const payload = buildPayload();

    try {
      if (editingId) {
        await updateTorneo(editingId, payload);
        showNotification("Torneo actualizado exitosamente", "success");
      } else {
        await createTorneo(payload);
        showNotification("Torneo creado exitosamente", "success");
      }

      setForm({
        nombre: "",
        categoria: "",
        fecha_inicio: "",
        fecha_fin: "",
        modalidad: "",
        organizador: "",
        precio: "",
        sede_id: "",
      });
      setEditingId(null);
      fetchTorneos();
    } catch (error) {
      console.error("Error al guardar torneo:", error);
      showNotification("Error al guardar el torneo", "error");
    }
  };

  const handleEdit = (torneo) => {
    const primeraSedeId = Array.isArray(torneo.sedes) && torneo.sedes.length > 0
      ? torneo.sedes[0].id
      : "";

    setForm({
      nombre: torneo.nombre || "",
      categoria: torneo.categoria || "",
      fecha_inicio: torneo.fecha_inicio || "",
      fecha_fin: torneo.fecha_fin || "",
      modalidad: torneo.modalidad || "",
      organizador: torneo.organizador || "",
      precio: torneo.precio ?? "",
      sede_id: primeraSedeId || "",
    });
    setEditingId(torneo.id);
  };

  const handleDelete = async (id) => {
    if (window.confirm("¿Estás seguro de que quieres eliminar este torneo?")) {
      try {
        await deleteTorneo(id);
        showNotification("Torneo eliminado exitosamente", "success");
        fetchTorneos();
      } catch (error) {
        console.error("Error al eliminar torneo:", error);
        showNotification("Error al eliminar el torneo", "error");
      }
    }
  };

  const renderSedes = (lista) => {
    if (Array.isArray(lista) && lista.length) {
      return lista.map((s) => s.nombre || s.name || `Sede #${s.id}`).join(", ");
    }
    return "Sin sedes";
  };

  return (
    <div className="page-container">
      <div className="torneo-crud">
        {notification.show && (
          <div className={`notification ${notification.type}`}>
            {notification.message}
          </div>
        )}

        <h2>Gestión de Torneos</h2>

        <form onSubmit={handleSubmit}>
          <select name="nombre" value={form.nombre} onChange={handleChange} required>
            <option value="">Seleccione un tipo de torneo</option>
            <option value="Liga">Liga</option>
            <option value="Relampago">Relámpago</option>
            <option value="Eliminacion directa">Eliminación directa</option>
            <option value="Mixto">Mixto</option>
          </select>

          <select name="categoria" value={form.categoria} onChange={handleChange} required>
            <option value="">Seleccione una categoría</option>
            <option value="Juvenil">Juvenil</option>
            <option value="Senior">Senior</option>
          </select>

          <input type="date" name="fecha_inicio" value={form.fecha_inicio} onChange={handleChange} required />
          <input type="date" name="fecha_fin" value={form.fecha_fin} onChange={handleChange} required />

          <select name="modalidad" value={form.modalidad} onChange={handleChange} required>
            <option value="">Seleccione una modalidad</option>
            <option value="todos contra todos">Todos contra todos</option>
            <option value="mixto">Mixto</option>
            <option value="competencia rapida">Competencia rápida</option>
            <option value="uno contra uno">Uno contra uno</option>
          </select>

          <input name="organizador" placeholder="Organizador" value={form.organizador} onChange={handleChange} required />

          <input name="precio" type="number" step="0.01" placeholder="Precio" value={form.precio} onChange={handleChange} required />

          <select name="sede_id" value={form.sede_id} onChange={handleChange}>
            <option value="">Seleccione una sede</option>
            {sedes.map((sede) => (
              <option key={sede.id} value={sede.id}>
                {sede.nombre || sede.name}
              </option>
            ))}
          </select>

          <button type="submit">{editingId ? "Actualizar" : "Crear"}</button>

          {editingId && (
            <button
              type="button"
              onClick={() => {
                setEditingId(null);
                setForm({
                  nombre: "",
                  categoria: "",
                  fecha_inicio: "",
                  fecha_fin: "",
                  modalidad: "",
                  organizador: "",
                  precio: "",
                  sede_id: "",
                });
              }}
            >
              Cancelar
            </button>
          )}
        </form>

        <h2>Torneos Disponibles</h2>
        <div className="torneos-grid">
          {torneos.length > 0 ? (
            torneos.map((torneo) => (
              <div key={torneo.id} className="torneo-card-simplificado">
                <h3>{torneo.nombre}</h3>
                <p><strong>Categoría:</strong> {torneo.categoria}</p>
                <p><strong>Inicio:</strong> {torneo.fecha_inicio}</p>
                <p><strong>Fin:</strong> {torneo.fecha_fin}</p>
                <p><strong>Modalidad:</strong> {torneo.modalidad}</p>
                <p><strong>Organizador:</strong> {torneo.organizador}</p>
                <p><strong>Precio:</strong> ${torneo.precio}</p>
                <p><strong>Sedes:</strong> {renderSedes(torneo.sedes)}</p>
                <div className="card-buttons">
                  <button onClick={() => handleEdit(torneo)}>Editar</button>
                  <button onClick={() => handleDelete(torneo.id)}>Eliminar</button>
                </div>
              </div>
            ))
          ) : (
            <p>No hay torneos disponibles</p>
          )}
        </div>
      </div>
    </div>
  );
};

export default TorneosCrud;