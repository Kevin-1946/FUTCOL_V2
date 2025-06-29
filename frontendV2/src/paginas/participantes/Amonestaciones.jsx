import React, { useEffect, useState } from "react";
import { getAmonestaciones } from "../../api/AmonestacionesService";
import ProtectedRoute from "../../componentes/ProtectedRoute";
import "../../estilos/AmonestacionesParticipante.css";

const Amonestaciones = () => {
  const [amonestaciones, setAmonestaciones] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    getAmonestaciones()
      .then((res) => {
        setAmonestaciones(res.data);
        setLoading(false);
      })
      .catch((err) => {
        console.error("Error al cargar las amonestaciones", err);
        setLoading(false);
      });
  }, []);

  if (loading) return <div className="cargando">Cargando amonestaciones...</div>;

  return (
    <div className="amonestaciones-container">
      <h2 className="titulo-seccion">Amonestaciones por Jugador</h2>
      <table className="tabla-amonestaciones">
        <thead>
          <tr>
            <th>#</th>
            <th>Jugador</th>
            <th>Equipo</th>
            <th>Número</th>
            <th>Amarilla</th>
            <th>Roja</th>
            <th>Azul</th>
          </tr>
        </thead>
        <tbody>
          {amonestaciones.map((a, index) => (
            <tr key={a.id}>
              <td>{index + 1}</td>
              <td>{a.jugador?.nombre || "Desconocido"}</td>
              <td>{a.equipo?.nombre || "Sin equipo"}</td>
              <td>{a.numero_camiseta || "-"}</td>
              <td>{a.tarjeta_amarilla ? "✔️" : "❌"}</td>
              <td>{a.tarjeta_roja ? "✔️" : "❌"}</td>
              <td>{a.tarjeta_azul ? "✔️" : "❌"}</td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
};

const AmonestacionesProtegido = () => (
  <ProtectedRoute allowedRoles={["participante", "capitan", "administrador"]}>
    <Amonestaciones />
  </ProtectedRoute>
);

export default AmonestacionesProtegido;