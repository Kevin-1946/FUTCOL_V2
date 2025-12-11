import React, { useState, useEffect } from "react";
import {
  getJueces,
  createJuez,
  updateJuez,
  deleteJuez,
} from "../../api/JuezService";
import { getSedes } from "../../api/SedeService";
import "./JuezCrud.css";

const JuezCrud = () => {
  const [jueces, setJueces] = useState([]);
  const [sedes, setSedes] = useState([]);

  // Estado para las notificaciones
  const [notification, setNotification] = useState({ show: false, message: "", type: "" });

  const [form, setForm] = useState({
    nombre: "",
    numero_de_contacto: "",
    correo: "",
    sede_asignada: "",
  });

  const [editingId, setEditingId] = useState(null);

  // Función para mostrar notificaciones
  const showNotification = (message, type) => {
    setNotification({ show: true, message, type });
    setTimeout(() => {
      setNotification({ show: false, message: "", type: "" });
    }, 10000);
  };

  const fetchJueces = async () => {
    const res = await getJueces();
    setJueces(res.data);
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
    fetchJueces();
    fetchSedes();
    
  }, []);

  const handleChange = (e) => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      if (editingId) {
        await updateJuez(editingId, form);
        showNotification("Juez actualizado exitosamente", "success");
      } else {
        await createJuez(form);
        showNotification("Juez creado exitosamente", "success");
      }
      setForm({
        nombre: "",
        numero_de_contacto: "",
        correo: "",
        sede_asignada: "",
      });
      setEditingId(null);
      fetchJueces();
    } catch (error) {
      showNotification(error.message || "Error al guardar el juez", "error");
      console.error("Error al guardar juez:", error);
    }
  };

  const handleEdit = (juez) => {
    setForm(juez);
    setEditingId(juez.id);
  };

  const handleDelete = async (id) => {
    if (window.confirm("¿Está seguro de eliminar este juez?")) {
      try {
        await deleteJuez(id);
        showNotification("Juez eliminado exitosamente", "success");
        fetchJueces();
      } catch (error) {
        showNotification(error.message || "Error al eliminar el juez", "error");
        console.error("Error al eliminar juez:", error);
      }
    }
  };

  const renderSedeNombre = (sedeId) => {
    const sede = sedes.find(s => s.id == sedeId);
    return sede ? sede.nombre || sede.name : sedeId;
  };

  return (
    <div className="page-container">
      <div className="juez-crud">

        {notification.show && (
          <div className={`notification ${notification.type}`}>
            {notification.message}
          </div>
        )}

        <h2>Jueces</h2>
        <form onSubmit={handleSubmit}>
          <input
            name="nombre"
            placeholder="Nombre"
            value={form.nombre}
            onChange={handleChange}
            required
          />
          <input
            name="numero_de_contacto"
            placeholder="Número de Contacto"
            value={form.numero_de_contacto}
            onChange={handleChange}
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
          <button type="submit">
            {editingId ? "Actualizar" : "Crear"}
          </button>
        </form>

        <ul>
          {jueces.map((juez) => (
            <li key={juez.id}>
              {juez.nombre} | {juez.numero_de_contacto} | {juez.correo} |{" "}
              {renderSedeNombre(juez.sede_asignada)}
              <button onClick={() => handleEdit(juez)}>Editar</button>
              <button onClick={() => handleDelete(juez.id)}>Eliminar</button>
            </li>
          ))}
        </ul>
      </div>
    </div>
  );
};

export default JuezCrud;
