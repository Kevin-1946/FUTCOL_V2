import React, { useState, useEffect } from "react";
import {
  getInscripciones,
  createInscripcion,
  updateInscripcion,
  deleteInscripcion,
} from "../../api/InscripcionService";
import "./InscripcionCrud.css";

const InscripcionesCrud = () => {
  const [inscripciones, setInscripciones] = useState([]);
  const [form, setForm] = useState({
    equipo_id: "",
    torneo_id: "",
    fecha_de_inscripcion: "",
    forma_pago: "",
    estado_pago: "",
    correo_confirmado: false,
    total_pagado: 0,
  });
  const [editingId, setEditingId] = useState(null);

  useEffect(() => {
    fetchInscripciones();
  }, []);

  const fetchInscripciones = async () => {
    try {
      const res = await getInscripciones();
      setInscripciones(res.data);
    } catch (error) {
      console.error("Error al obtener inscripciones:", error);
    }
  };

  const handleChange = (e) => {
    const { name, value, type, checked } = e.target;
    setForm({
      ...form,
      [name]: type === "checkbox" ? checked : value,
    });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      if (editingId) {
        await updateInscripcion(editingId, form);
      } else {
        await createInscripcion(form);
      }
      setForm({
        equipo_id: "",
        torneo_id: "",
        fecha_de_inscripcion: "",
        forma_pago: "",
        estado_pago: "",
        correo_confirmado: false,
        total_pagado: 0,
      });
      setEditingId(null);
      fetchInscripciones();
    } catch (error) {
      console.error("Error al guardar inscripción:", error);
    }
  };

  const handleEdit = (inscripcion) => {
    setForm(inscripcion);
    setEditingId(inscripcion.id);
  };

  const handleDelete = async (id) => {
    if (confirm("¿Eliminar esta inscripción?")) {
      try {
        await deleteInscripcion(id);
        fetchInscripciones();
      } catch (error) {
        console.error("Error al eliminar inscripción:", error);
      }
    }
  };

  return (
    <div className="page-container"> 
      <div className="inscripciones-crud">
        <h2>Inscripciones</h2>
        <form onSubmit={handleSubmit}>
          <input name="equipo_id" placeholder="ID Equipo" value={form.equipo_id} onChange={handleChange} />
          <input name="torneo_id" placeholder="ID Torneo" value={form.torneo_id} onChange={handleChange} />
          <input type="date" name="fecha_de_inscripcion" value={form.fecha_de_inscripcion} onChange={handleChange} />
          <input name="forma_pago" placeholder="Forma de Pago" value={form.forma_pago} onChange={handleChange} />
          <input name="estado_pago" placeholder="Estado de Pago" value={form.estado_pago} onChange={handleChange} />
          <label>
            Correo Confirmado:
            <input type="checkbox" name="correo_confirmado" checked={form.correo_confirmado} onChange={handleChange} />
          </label>
          <input
            name="total_pagado"
            type="number"
            min="0"
            placeholder="Total Pagado"
            value={form.total_pagado}
            onChange={handleChange}
          />
          <button type="submit">{editingId ? "Actualizar" : "Registrar"}</button>
        </form>

        <ul>
          {inscripciones.map((i) => (
            <li key={i.id}>
              Equipo: {i.equipo?.nombre || i.equipo_id} | Torneo: {i.torneo?.nombre || i.torneo_id} |
              Total Pagado: ${i.total_pagado}
              <br />
              Pago: {i.forma_pago} | Estado: {i.estado_pago} | Correo Confirmado: {i.correo_confirmado ? "Sí" : "No"}
              <div>
                <button onClick={() => handleEdit(i)}>Editar</button>
                <button onClick={() => handleDelete(i.id)}>Eliminar</button>
              </div>
            </li>
          ))}
        </ul>
      </div>
    </div>  
  );
};

export default InscripcionesCrud; 
