import React, { useState, useEffect } from "react";
import {
  getAmonestaciones,
  createAmonestacion,
  updateAmonestacion,
  deleteAmonestacion,
} from "../../api/AmonestacionService";
import "./AmonestacionCrud.css";

const AmonestacionesCrud = () => {
  const [amonestaciones, setAmonestaciones] = useState([]);
  const [form, setForm] = useState({
    jugador_id: "",
    equipo_id: "",
    encuentro_id: "",
    numero_camiseta: "",
    tarjeta_roja: false,
    tarjeta_amarilla: false,
    tarjeta_azul: false,
  });
  const [editingId, setEditingId] = useState(null);

  useEffect(() => {
    fetchAmonestaciones();
  }, []);

  const fetchAmonestaciones = async () => {
    const res = await getAmonestaciones();
    setAmonestaciones(res.data);
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
    if (editingId) {
      await updateAmonestacion(editingId, form);
    } else {
      await createAmonestacion(form);
    }
    setForm({
      jugador_id: "",
      equipo_id: "",
      encuentro_id: "",
      numero_camiseta: "",
      tarjeta_roja: false,
      tarjeta_amarilla: false,
      tarjeta_azul: false,
    });
    setEditingId(null);
    fetchAmonestaciones();
  };

  const handleEdit = (item) => {
    setForm({
      jugador_id: item.jugador_id,
      equipo_id: item.equipo_id,
      encuentro_id: item.encuentro_id,
      numero_camiseta: item.numero_camiseta,
      tarjeta_roja: item.tarjeta_roja,
      tarjeta_amarilla: item.tarjeta_amarilla,
      tarjeta_azul: item.tarjeta_azul,
    });
    setEditingId(item.id);
  };

  const handleDelete = async (id) => {
    await deleteAmonestacion(id);
    fetchAmonestaciones();
  };

  return (
    <div className="page-container">
      <div className="amonestacion-crud">
        <h2>Amonestaciones</h2>
        <form onSubmit={handleSubmit}>
          <input
            name="jugador_id"
            placeholder="ID Jugador"
            value={form.jugador_id}
            onChange={handleChange}
          />
          <input
            name="equipo_id"
            placeholder="ID Equipo"
            value={form.equipo_id}
            onChange={handleChange}
          />
          <input
            name="encuentro_id"
            placeholder="ID Encuentro"
            value={form.encuentro_id}
            onChange={handleChange}
          />
          <input
            name="numero_camiseta"
            placeholder="Número Camiseta"
            value={form.numero_camiseta}
            onChange={handleChange}
          />
          <label>
            <input
              type="checkbox"
              name="tarjeta_roja"
              checked={form.tarjeta_roja}
              onChange={handleChange}
            />
            Roja
          </label>
          <label>
            <input
              type="checkbox"
              name="tarjeta_amarilla"
              checked={form.tarjeta_amarilla}
              onChange={handleChange}
            />
            Amarilla
          </label>
          <label>
            <input
              type="checkbox"
              name="tarjeta_azul"
              checked={form.tarjeta_azul}
              onChange={handleChange}
            />
            Azul
          </label>
          <button type="submit">{editingId ? "Actualizar" : "Crear"}</button>
        </form>

        <ul>
          {amonestaciones.map((a) => (
            <li key={a.id}>
              Jugador: {a.jugador?.nombre || a.jugador_id} | Camiseta #{a.numero_camiseta}
              <br />
              Tarjetas:{" "}
              {a.tarjeta_roja ? "🟥 " : ""}
              {a.tarjeta_amarilla ? "🟨 " : ""}
              {a.tarjeta_azul ? "🟦 " : ""}
              <div>
                <button onClick={() => handleEdit(a)}>Editar</button>
                <button onClick={() => handleDelete(a.id)}>Eliminar</button>
              </div>
            </li>
          ))}
        </ul>
      </div>
    </div>
  );
};

export default AmonestacionesCrud;