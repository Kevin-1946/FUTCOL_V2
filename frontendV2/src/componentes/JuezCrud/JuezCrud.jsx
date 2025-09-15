import React, { useState, useEffect } from "react";
import {
  getJueces,
  createJuez,
  updateJuez,
  deleteJuez,
} from "../../api/JuezService";
<<<<<<< HEAD
=======
import { getSedes } from "../../api/SedeService";
>>>>>>> 0811bf220f286354eedfbe5dcd950a8cd31dba1c
import "./JuezCrud.css";

const JuezCrud = () => {
  const [jueces, setJueces] = useState([]);
<<<<<<< HEAD
=======
  const [sedes, setSedes] = useState([]);
>>>>>>> 0811bf220f286354eedfbe5dcd950a8cd31dba1c
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

<<<<<<< HEAD
  useEffect(() => {
    fetchJueces();
=======
  const fetchSedes = async () => {
    try {
      const res = await getSedes();
      setSedes(res.data);
    } catch (error) {
      console.error("Error al obtener sedes:", error);
    }
  };

  useEffect(() => {
    fetchJueces();
    fetchSedes();
>>>>>>> 0811bf220f286354eedfbe5dcd950a8cd31dba1c
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

<<<<<<< HEAD
  return (
    <div className="page-container"> 
      <div className="juez-crud">
=======
  // Función para mostrar el nombre de la sede
  const renderSedeNombre = (sedeId) => {
    const sede = sedes.find(s => s.id == sedeId);
    return sede ? sede.nombre || sede.name : sedeId;
  };

  return (
    <div className="page-container">
       <div className="juez-crud">
>>>>>>> 0811bf220f286354eedfbe5dcd950a8cd31dba1c
        <h2>Jueces</h2>
        <form onSubmit={handleSubmit}>
          <input
            name="nombre"
            placeholder="Nombre"
            value={form.nombre}
            onChange={handleChange}
<<<<<<< HEAD
=======
            required
>>>>>>> 0811bf220f286354eedfbe5dcd950a8cd31dba1c
          />
          <input
            name="numero_de_contacto"
            placeholder="Número de Contacto"
            value={form.numero_de_contacto}
            onChange={handleChange}
<<<<<<< HEAD
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
=======
            required
          />
          <input
            name="correo"
            type="email"
            placeholder="Correo"
            value={form.correo}
            onChange={handleChange}
            required
          />
          <select
            name="sede_asignada"
            value={form.sede_asignada}
            onChange={handleChange}
            required
          >
            <option value="">Seleccione una sede</option>
            {sedes.map((sede) => (
              <option key={sede.id} value={sede.id}>
                {sede.nombre || sede.name}
              </option>
            ))}
          </select>
>>>>>>> 0811bf220f286354eedfbe5dcd950a8cd31dba1c
          <button type="submit">
            {editingId ? "Actualizar" : "Crear"}
          </button>
        </form>

        <ul>
          {jueces.map((juez) => (
            <li key={juez.id}>
              {juez.nombre} | {juez.numero_de_contacto} | {juez.correo} |{" "}
<<<<<<< HEAD
              {juez.sede_asignada}
=======
              {renderSedeNombre(juez.sede_asignada)}
>>>>>>> 0811bf220f286354eedfbe5dcd950a8cd31dba1c
              <button onClick={() => handleEdit(juez)}>Editar</button>
              <button onClick={() => handleDelete(juez.id)}>Eliminar</button>
            </li>
          ))}
        </ul>
      </div>
<<<<<<< HEAD
    </div>  
  );
};

export default JuezCrud;
=======
    </div>
    );
};

export default JuezCrud;
>>>>>>> 0811bf220f286354eedfbe5dcd950a8cd31dba1c
