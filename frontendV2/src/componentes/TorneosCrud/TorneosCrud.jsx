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
  });
  const [editingId, setEditingId] = useState(null);

  const fetchTorneos = async () => {
    const res = await getTorneos();
    setTorneos(res.data);
  };

  useEffect(() => {
    fetchTorneos();
  }, []);

  const handleChange = (e) => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };

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
    });
    setEditingId(null);
    fetchTorneos();
  };

  const handleEdit = (torneo) => {
    setForm(torneo);
    setEditingId(torneo.id);
  };

  const handleDelete = async (id) => {
    await deleteTorneo(id);
    fetchTorneos();
  };

  return (
      <div className="torneo-crud">
        <h2>Torneos</h2>
        <form onSubmit={handleSubmit}>
          <input
            name="nombre"
            placeholder="Nombre"
            value={form.nombre}
            onChange={handleChange}
          />
          <input
            name="categoria"
            placeholder="Categoría"
            value={form.categoria}
            onChange={handleChange}
          />
          <input
            type="date"
            name="fecha_inicio"
            value={form.fecha_inicio}
            onChange={handleChange}
          />
          <input
            type="date"
            name="fecha_fin"
            value={form.fecha_fin}
            onChange={handleChange}
          />
          <button type="submit">{editingId ? "Actualizar" : "Crear"}</button>
        </form>

        <ul>
          {torneos.map((torneo) => (
            <li key={torneo.id}>
              {torneo.nombre} | {torneo.categoria} | {torneo.fecha_inicio} - {torneo.fecha_fin}
              <button onClick={() => handleEdit(torneo)}>Editar</button>
              <button onClick={() => handleDelete(torneo.id)}>Eliminar</button>
            </li>
          ))}
        </ul>
      </div>
  );
};

export default TorneosCrud;