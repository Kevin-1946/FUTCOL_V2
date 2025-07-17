import React, { useState, useEffect } from "react";
import {
  getTorneos,
  createTorneo,
  updateTorneo,
  deleteTorneo,
} from "../../api/TorneoService";
import "./TorneosCrud.css";

const TorneosCrud = () => {
  const [torneos, setTorneos] = useState([]);
  const [form, setForm] = useState({
    nombre: "",
    categoria: "",
    fecha_inicio: "",
    fecha_fin: "",
    modalidad: "",
    organizador: "",
    precio: "",
    sedes: "",
  });
  const [editingId, setEditingId] = useState(null);

  // Función para obtener torneos
  const fetchTorneos = async () => {
    const res = await getTorneos();
    setTorneos(res.data);
  };

  // Efecto para cargar torneos al montar el componente
  useEffect(() => {
    fetchTorneos();
  }, []);

  // Manejar cambios en el formulario
  const handleChange = (e) => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };

  // Manejar envío del formulario
  const handleSubmit = async (e) => {
    e.preventDefault();
    if (editingId) {
      await updateTorneo(editingId, form);
    } else {
      await createTorneo(form);
    }
    setForm({
      nombre: "",
      categoria: "",
      fecha_inicio: "",
      fecha_fin: "",
      modalidad: "",
      organizador: "",
      precio: "",
      sedes: "",
    });
    setEditingId(null);
    fetchTorneos();
  };

  // Manejar edición de torneo
  const handleEdit = (torneo) => {
    // Convertir el array de sedes a string para el formulario
    const torneoParaEditar = {
      ...torneo,
      sedes: Array.isArray(torneo.sedes) 
        ? torneo.sedes.map(sede => sede.nombre || sede.name || 'Sede').join(', ')
        : torneo.sedes
    };
    setForm(torneoParaEditar);
    setEditingId(torneo.id);
  };

  // Manejar eliminación de torneo
  const handleDelete = async (id) => {
    if (window.confirm('¿Estás seguro de que quieres eliminar este torneo?')) {
      await deleteTorneo(id);
      fetchTorneos();
    }
  };

  // Función para renderizar las sedes
  const renderSedes = (sedes) => {
    if (Array.isArray(sedes)) {
      return sedes.map(sede => sede.nombre || sede.name || 'Sede').join(', ');
    }
    return sedes || 'Sin sedes';
  };

  return (
    <div className="page-container">
      <div className="torneo-crud">
        <h2>Gestión de Torneos</h2>

        {/* Formulario */}
        <form onSubmit={handleSubmit}>
          {/* Nombre del torneo */}
          <select 
            name="nombre" 
            value={form.nombre} 
            onChange={handleChange}
            required
          >
            <option value="">Seleccione un tipo de torneo</option>
            <option value="Liga">Liga</option>
            <option value="Relampago">Relámpago</option>
            <option value="Eliminacion directa">Eliminación directa</option>
            <option value="Mixto">Mixto</option>
          </select>

          {/* Categoría */}
          <select 
            name="categoria" 
            value={form.categoria} 
            onChange={handleChange}
            required
          >
            <option value="">Seleccione una categoría</option>
            <option value="Juvenil">Juvenil</option>
            <option value="Senior">Senior</option>
          </select>

          {/* Fecha de inicio */}
          <input
            type="date"
            name="fecha_inicio"
            value={form.fecha_inicio}
            onChange={handleChange}
            required
          />

          {/* Fecha de fin */}
          <input
            type="date"
            name="fecha_fin"
            value={form.fecha_fin}
            onChange={handleChange}
            required
          />

          {/* Modalidad */}
          <select 
            name="modalidad" 
            value={form.modalidad} 
            onChange={handleChange}
            required
          >
            <option value="">Seleccione una modalidad</option>
            <option value="todos contra todos">Todos contra todos</option>
            <option value="mixto">Mixto</option>
            <option value="competencia rapida">Competencia rápida</option>
            <option value="uno contra uno">Uno contra uno</option>
          </select>

          {/* Organizador */}
          <input
            name="organizador"
            placeholder="Organizador"
            value={form.organizador}
            onChange={handleChange}
            required
          />

          {/* Precio */}
          <input
            name="precio"
            type="number"
            step="0.01"
            placeholder="Precio"
            value={form.precio}
            onChange={handleChange}
            required
          />

          {/* Sedes */}
          <input
            name="sedes"
            placeholder="Sedes (separadas por coma)"
            value={form.sedes}
            onChange={handleChange}
            required
          />

          <button type="submit">
            {editingId ? "Actualizar" : "Crear"}
          </button>
          
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
                  sedes: "",
                });
              }}
            >
              Cancelar
            </button>
          )}
        </form>

        {/* Lista de torneos */}
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