import React, { useEffect, useState } from "react";
import {
  getEstadisticas,
  createEstadistica,
  updateEstadistica,
  deleteEstadistica,
} from "../../api/EstadisticaEquipoService";
import "./EstadisticaEquipoCrud.css";

const EstadisticaEquipoCrud = () => {
  const [estadisticas, setEstadisticas] = useState([]);
  const [form, setForm] = useState({
    equipo_id: "",
    torneo_id: "",
    partidos_jugados: 0,
    partidos_ganados: 0,
    partidos_empatados: 0,
    partidos_perdidos: 0,
    goles_a_favor: 0,
    goles_en_contra: 0,
    diferencia_de_goles: 0,
    puntos: 0,
  });
  const [editandoId, setEditandoId] = useState(null);

  useEffect(() => {
    cargarEstadisticas();
  }, []);

  const cargarEstadisticas = () => {
    getEstadisticas()
      .then((res) => setEstadisticas(res.data))
      .catch((err) => console.error(err));
  };

  const manejarCambio = (e) => {
    const { name, value } = e.target;
    setForm({ ...form, [name]: Number(value) || value });
  };

  const manejarSubmit = (e) => {
    e.preventDefault();
    const metodo = editandoId ? updateEstadistica : createEstadistica;
    const args = editandoId ? [editandoId, form] : [form];

    metodo(...args)
      .then(() => {
        cargarEstadisticas();
        resetForm();
      })
      .catch((err) => console.error(err));
  };

  const editar = (item) => {
    setForm({ ...item });
    setEditandoId(item.id);
  };

  const eliminar = (id) => {
    if (confirm("¿Eliminar estadística?")) {
      deleteEstadistica(id)
        .then(() => cargarEstadisticas())
        .catch((err) => console.error(err));
    }
  };

  const resetForm = () => {
    setForm({
      equipo_id: "",
      torneo_id: "",
      partidos_jugados: 0,
      partidos_ganados: 0,
      partidos_empatados: 0,
      partidos_perdidos: 0,
      goles_a_favor: 0,
      goles_en_contra: 0,
      diferencia_de_goles: 0,
      puntos: 0,
    });
    setEditandoId(null);
  };

  return (
      <div className="estadistica-crud">
        <h2>{editandoId ? "Editar Estadística" : "Estadísticas"}</h2>

        <form onSubmit={manejarSubmit} className="formulario-estadistica">
  <label>Equipo ID
    <input name="equipo_id" value={form.equipo_id} onChange={manejarCambio} required />
  </label>
  <label>Torneo ID
    <input name="torneo_id" value={form.torneo_id} onChange={manejarCambio} required />
  </label>
  <label>Partidos Jugados
    <input name="partidos_jugados" type="number" value={form.partidos_jugados} onChange={manejarCambio} />
  </label>
  <label>Partidos Ganados
    <input name="partidos_ganados" type="number" value={form.partidos_ganados} onChange={manejarCambio} />
  </label>
  <label>Partidos Empatados
    <input name="partidos_empatados" type="number" value={form.partidos_empatados} onChange={manejarCambio} />
  </label>
  <label>Partidos Perdidos
    <input name="partidos_perdidos" type="number" value={form.partidos_perdidos} onChange={manejarCambio} />
  </label>
  <label>Goles a Favor
    <input name="goles_a_favor" type="number" value={form.goles_a_favor} onChange={manejarCambio} />
  </label>
  <label>Goles en Contra
    <input name="goles_en_contra" type="number" value={form.goles_en_contra} onChange={manejarCambio} />
  </label>
  <label>Diferencia de Goles
    <input name="diferencia_de_goles" type="number" value={form.diferencia_de_goles} onChange={manejarCambio} />
  </label>
  <label>Puntos
    <input name="puntos" type="number" value={form.puntos} onChange={manejarCambio} />
  </label>

  <button type="submit">{editandoId ? "Actualizar" : "Crear"}</button>
</form>


        <h3>Estadísticas por Equipo</h3>
        <table className="tabla-estadisticas">
          <thead>
            <tr>
              <th>Equipo</th>
              <th>Torneo</th>
              <th>PJ</th>
              <th>G</th>
              <th>E</th>
              <th>P</th>
              <th>GF</th>
              <th>GC</th>
              <th>DG</th>
              <th>Pts</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            {estadisticas.map((e) => (
              <tr key={e.id}>
                <td>{e.equipo?.nombre || e.equipo_id}</td>
                <td>{e.torneo?.nombre || e.torneo_id}</td>
                <td>{e.partidos_jugados}</td>
                <td>{e.partidos_ganados}</td>
                <td>{e.partidos_empatados}</td>
                <td>{e.partidos_perdidos}</td>
                <td>{e.goles_a_favor}</td>
                <td>{e.goles_en_contra}</td>
                <td>{e.diferencia_de_goles}</td>
                <td>{e.puntos}</td>
                <td>
                  <button onClick={() => editar(e)}>Editar</button>
                  <button onClick={() => eliminar(e.id)}>Eliminar</button>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
  );
};

export default EstadisticaEquipoCrud;
