import React, { useEffect, useState } from "react";
import {
  getEncuentros,
  createEncuentro,
  updateEncuentro,
  deleteEncuentro,
} from "../../api/EncuentroService";
import "./EncuentroCrud.css";

const EncuentrosCrud = () => {
  const [encuentros, setEncuentros] = useState([]);
  const [form, setForm] = useState({
    torneo_id: "",
    sede_id: "",
    fecha: "",
    equipo_local_id: "",
    equipo_visitante_id: "",
    goles_equipo_local: "",
    goles_equipo_visitante: "",
  });
  const [editingId, setEditingId] = useState(null);

  const fetchEncuentros = async () => {
    const res = await getEncuentros();
    setEncuentros(res.data);
  };

  useEffect(() => {
    fetchEncuentros();
  }, []);

  const handleChange = (e) => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (editingId) {
      await updateEncuentro(editingId, form);
    } else {
      await createEncuentro(form);
    }
    setForm({
      torneo_id: "",
      sede_id: "",
      fecha: "",
      equipo_local_id: "",
      equipo_visitante_id: "",
      goles_equipo_local: "",
      goles_equipo_visitante: "",
    });
    setEditingId(null);
    fetchEncuentros();
  };

  const handleEdit = (encuentro) => {
    setForm(encuentro);
    setEditingId(encuentro.id);
  };

  const handleDelete = async (id) => {
    await deleteEncuentro(id);
    fetchEncuentros();
  };

  return (
      <div className="encuentro-crud">
        <h2>Encuentros</h2>
        <form onSubmit={handleSubmit}>
          <input
            name="torneo_id"
            placeholder="ID Torneo"
            value={form.torneo_id}
            onChange={handleChange}
          />
          <input
            name="sede_id"
            placeholder="ID Sede"
            value={form.sede_id}
            onChange={handleChange}
          />
          <input
            name="fecha"
            type="date"
            value={form.fecha}
            onChange={handleChange}
          />
          <input
            name="equipo_local_id"
            placeholder="ID Equipo Local"
            value={form.equipo_local_id}
            onChange={handleChange}
          />
          <input
            name="equipo_visitante_id"
            placeholder="ID Equipo Visitante"
            value={form.equipo_visitante_id}
            onChange={handleChange}
          />
          <input
            name="goles_equipo_local"
            placeholder="Goles Local"
            value={form.goles_equipo_local}
            onChange={handleChange}
          />
          <input
            name="goles_equipo_visitante"
            placeholder="Goles Visitante"
            value={form.goles_equipo_visitante}
            onChange={handleChange}
          />
          <button type="submit">
            {editingId ? "Actualizar" : "Crear"}
          </button>
        </form>

        <ul>
          {encuentros.map((e) => (
            <li key={e.id}>
              <strong>{e.torneo?.nombre || `Torneo ID: ${e.torneo_id}`}</strong> - {e.fecha}
              <div>
                Local: {e.equipo_local?.nombre || e.equipo_local_id} - Goles: {e.goles_equipo_local}
              </div>
              <div>
                Visitante: {e.equipo_visitante?.nombre || e.equipo_visitante_id} - Goles: {e.goles_equipo_visitante}
              </div>
              <div>
                <button onClick={() => handleEdit(e)}>Editar</button>
                <button onClick={() => handleDelete(e.id)}>Eliminar</button>
              </div>
            </li>
          ))}
        </ul>
      </div>
  );
};

export default EncuentrosCrud;