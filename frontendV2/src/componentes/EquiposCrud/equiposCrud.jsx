import React, { useState, useEffect } from "react";
import {
  getEquipos,
  createEquipo,
  updateEquipo,
  deleteEquipo,
} from "../../api/EquipoService";
import "./EquiposCrud.css";

const EquiposCrud = () => {
  const [equipos, setEquipos] = useState([]);
  const [form, setForm] = useState({ nombre: "", descripcion: "" });
  const [editingId, setEditingId] = useState(null);

  const fetchEquipos = async () => {
    const res = await getEquipos();
    setEquipos(res.data);
  };

  useEffect(() => {
    fetchEquipos();
  }, []);

  const handleChange = (e) => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (editingId) {
      await updateEquipo(editingId, form);
    } else {
      await createEquipo(form);
    }
    setForm({ nombre: "", descripcion: "" });
    setEditingId(null);
    fetchEquipos();
  };

  const handleEdit = (equipo) => {
    setForm(equipo);
    setEditingId(equipo.id);
  };

  const handleDelete = async (id) => {
    await deleteEquipo(id);
    fetchEquipos();
  };

  return (
      <div className="equipo-crud">
        <h2>Equipos</h2>
        <form onSubmit={handleSubmit}>
          <input
            name="nombre"
            placeholder="Nombre del equipo"
            value={form.nombre}
            onChange={handleChange}
          />
          <input
            name="descripcion"
            placeholder="Descripción"
            value={form.descripcion}
            onChange={handleChange}
          />
          <button type="submit">{editingId ? "Actualizar" : "Crear"}</button>
        </form>

        <ul>
          {equipos.map((equipo) => (
            <li key={equipo.id}>
              <strong>{equipo.nombre}</strong>: {equipo.descripcion}
              <div>
                <button onClick={() => handleEdit(equipo)}>Editar</button>
                <button onClick={() => handleDelete(equipo.id)}>Eliminar</button>
              </div>
            </li>
          ))}
        </ul>
      </div>
  );
};

export default EquiposCrud;