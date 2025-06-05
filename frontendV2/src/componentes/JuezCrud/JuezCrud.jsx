import React, { useState, useEffect } from "react";
import {
  getJueces,
  createJuez,
  updateJuez,
  deleteJuez,
} from "../../api/JuezService";
import "./JuezCrud.css";

const JuezCrud = () => {
  const [jueces, setJueces] = useState([]);
  const [form, setForm] = useState({
    nombre: "",
    numero_de_contacto: "",
    correo: "",
    sede_asignada: "",
  });
  const [editingId, setEditingId] = useState(null);

  const fetchJueces = async () => {
    const res = await getJueces();
    setJueces(res.data);
  };

  useEffect(() => {
    fetchJueces();
  }, []);

  const handleChange = (e) => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (editingId) {
      await updateJuez(editingId, form);
    } else {
      await createJuez(form);
    }
    setForm({
      nombre: "",
      numero_de_contacto: "",
      correo: "",
      sede_asignada: "",
    });
    setEditingId(null);
    fetchJueces();
  };

  const handleEdit = (juez) => {
    setForm(juez);
    setEditingId(juez.id);
  };

  const handleDelete = async (id) => {
    await deleteJuez(id);
    fetchJueces();
  };

  return (
      <div className="juez-crud">
        <h2>Jueces</h2>
        <form onSubmit={handleSubmit}>
          <input
            name="nombre"
            placeholder="Nombre"
            value={form.nombre}
            onChange={handleChange}
          />
          <input
            name="numero_de_contacto"
            placeholder="Número de Contacto"
            value={form.numero_de_contacto}
            onChange={handleChange}
          />
          <input
            name="correo"
            placeholder="Correo"
            value={form.correo}
            onChange={handleChange}
          />
          <input
            name="sede_asignada"
            placeholder="Sede Asignada"
            value={form.sede_asignada}
            onChange={handleChange}
          />
          <button type="submit">
            {editingId ? "Actualizar" : "Crear"}
          </button>
        </form>

        <ul>
          {jueces.map((juez) => (
            <li key={juez.id}>
              {juez.nombre} | {juez.numero_de_contacto} | {juez.correo} |{" "}
              {juez.sede_asignada}
              <button onClick={() => handleEdit(juez)}>Editar</button>
              <button onClick={() => handleDelete(juez.id)}>Eliminar</button>
            </li>
          ))}
        </ul>
      </div>
  );
};

export default JuezCrud;
