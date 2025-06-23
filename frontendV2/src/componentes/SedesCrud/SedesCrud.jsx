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
    torneo_id: "",
  });
  const [editingId, setEditingId] = useState(null);

  const fetchSedes = async () => {
    const res = await getSedes();
    setSedes(res.data);
  };

  useEffect(() => {
    fetchSedes();
  }, []);

  const handleChange = (e) => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (editingId) {
      await updateSede(editingId, form);
    } else {
      await createSede(form);
    }
    setForm({ nombre: "", direccion: "", torneo_id: "" });
    setEditingId(null);
    fetchSedes();
  };

  const handleEdit = (sede) => {
    setForm(sede);
    setEditingId(sede.id);
  };

  const handleDelete = async (id) => {
    await deleteSede(id);
    fetchSedes();
  };

  return (
    <div className="page-container">
      <div className="sede-crud">
        <h2>Sedes</h2>
        <form onSubmit={handleSubmit}>
          <input
            type="text"
            name="nombre"
            placeholder="Nombre"
            value={form.nombre}
            onChange={handleChange}
          />
          <input
            type="text"
            name="direccion"
            placeholder="Dirección"
            value={form.direccion}
            onChange={handleChange}
          />
          <input
            type="number"
            name="torneo_id"
            placeholder="ID del Torneo"
            value={form.torneo_id}
            onChange={handleChange}
          />
          <button type="submit">
            {editingId ? "Actualizar" : "Crear"}
          </button>
        </form>

        <ul>
          {sedes.map((sede) => (
            <li key={sede.id}>
              {sede.nombre} - {sede.direccion} (Torneo ID: {sede.torneo_id})
              <button onClick={() => handleEdit(sede)}>Editar</button>
              <button onClick={() => handleDelete(sede.id)}>Eliminar</button>
            </li>
          ))}
        </ul>
      </div>
    </div>  
  );
};

export default SedesCrud;