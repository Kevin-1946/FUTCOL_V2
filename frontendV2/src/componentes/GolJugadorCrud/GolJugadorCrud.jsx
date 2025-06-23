import React, { useState, useEffect } from "react";
import {
  getGoles,
  createGol,
  updateGol,
  deleteGol,
} from "../../api/GolJugadorService";
import "./GolJugadorCrud.css";

const GolesCrud = () => {
  const [goles, setGoles] = useState([]);
  const [form, setForm] = useState({
    jugador_id: "",
    encuentro_id: "",
    cantidad: 1,
  });
  const [editingId, setEditingId] = useState(null);

  useEffect(() => {
    fetchGoles();
  }, []);

  const fetchGoles = async () => {
    try {
      const res = await getGoles();
      setGoles(res.data);
    } catch (error) {
      console.error("Error al obtener goles:", error);
    }
  };

  const handleChange = (e) => {
    const { name, value } = e.target;
    setForm({ ...form, [name]: value });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      if (editingId) {
        await updateGol(editingId, form);
      } else {
        await createGol(form);
      }
      setForm({ jugador_id: "", encuentro_id: "", cantidad: 1 });
      setEditingId(null);
      fetchGoles();
    } catch (error) {
      console.error("Error al guardar gol:", error);
    }
  };

  const handleEdit = (gol) => {
    setForm({
      jugador_id: gol.jugador_id,
      encuentro_id: gol.encuentro_id,
      cantidad: gol.cantidad,
    });
    setEditingId(gol.id);
  };

  const handleDelete = async (id) => {
    if (confirm("¿Eliminar este gol?")) {
      try {
        await deleteGol(id);
        fetchGoles();
      } catch (error) {
        console.error("Error al eliminar gol:", error);
      }
    }
  };

  return (
    <div className="page-container"> 
      <div className="goles-crud">
        <h2>Goles por Jugador</h2>
        <form onSubmit={handleSubmit}>
          <input
            name="jugador_id"
            placeholder="ID Jugador"
            value={form.jugador_id}
            onChange={handleChange}
            required
          />
          <input
            name="encuentro_id"
            placeholder="ID Encuentro"
            value={form.encuentro_id}
            onChange={handleChange}
            required
          />
          <input
            name="cantidad"
            type="number"
            min="1"
            placeholder="Cantidad"
            value={form.cantidad}
            onChange={handleChange}
            required
          />
          <button type="submit">{editingId ? "Actualizar" : "Registrar"}</button>
        </form>

        <ul>
          {goles.map((gol) => (
            <li key={gol.id}>
              Jugador: {gol.jugador?.nombre || gol.jugador_id} | Goles: {gol.cantidad}
              <br />
              Encuentro: {gol.encuentro?.id || gol.encuentro_id}
              <div>
                <button onClick={() => handleEdit(gol)}>Editar</button>
                <button onClick={() => handleDelete(gol.id)}>Eliminar</button>
              </div>
            </li>
          ))}
        </ul>
      </div>
    </div>  
  );
};

export default GolesCrud;
