import React, { useEffect, useState } from "react";
import { getEstadisticas } from "../../api/EstadisticaEquipoService";
import ProtectedRoute from "../../componentes/ProtectedRoute";
import "../../estilos/EstadisticaEquipoParticipante.css";

const EstadisticaEquipo = () => {
  const [estadisticas, setEstadisticas] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    getEstadisticas()
      .then((res) => {
        setEstadisticas(res.data);
        setLoading(false);
      })
      .catch((err) => {
        console.error("Error al cargar estadísticas", err);
        setLoading(false);
      });
  }, []);

  if (loading) return <div className="cargando">Cargando tabla de posiciones...</div>;

  return (
    <div className="estadisticas-container">
      <h2 className="titulo-seccion">Tabla de Posiciones</h2>
      <table className="tabla-estadisticas">
        <thead>
          <tr>
            <th>#</th>
            <th>Equipo</th>
            <th>PJ</th>
            <th>PG</th>
            <th>PE</th>
            <th>PP</th>
            <th>GF</th>
            <th>GC</th>
            <th>DG</th>
            <th>PTS</th>
          </tr>
        </thead>
        <tbody>
          {estadisticas
            .sort((a, b) => b.puntos - a.puntos) // Ordenar por puntos
            .map((e, index) => (
              <tr key={e.id}>
                <td>{index + 1}</td>
                <td>{e.equipo?.nombre || "Sin equipo"}</td>
                <td>{e.partidos_jugados}</td>
                <td>{e.partidos_ganados}</td>
                <td>{e.partidos_empatados}</td>
                <td>{e.partidos_perdidos}</td>
                <td>{e.goles_a_favor}</td>
                <td>{e.goles_en_contra}</td>
                <td>{e.diferencia_de_goles}</td>
                <td>{e.puntos}</td>
              </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
};

const EstadisticaEquipoProtegido = () => (
  <ProtectedRoute allowedRoles={["participante", "capitan", "administrador"]}>
    <EstadisticaEquipo />
  </ProtectedRoute>
);

export default EstadisticaEquipoProtegido;