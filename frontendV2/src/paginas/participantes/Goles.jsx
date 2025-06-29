import React, { useEffect, useState } from "react";
import { getGoles } from "../../api/GolJugadorService";
import ProtectedRoute from "../../componentes/ProtectedRoute";
import "../../estilos/GolesParticipante.css";

const Goles = () => {
  const [goles, setGoles] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    getGoles()
      .then((res) => {
        setGoles(res.data);
        setLoading(false);
      })
      .catch((err) => {
        console.error("Error al cargar los goles", err);
        setLoading(false);
      });
  }, []);

  if (loading) return <div className="cargando">Cargando goleadores...</div>;

  return (
    <div className="goles-container">
      <h2 className="titulo-seccion">Tabla de Goleadores</h2>
      <table className="tabla-goleadores">
        <thead>
          <tr>
            <th>#</th>
            <th>Jugador</th>
            <th>Equipo</th>
            <th>Goles</th>
          </tr>
        </thead>
        <tbody>
          {goles.map((gol, index) => (
            <tr key={gol.id}>
              <td>{index + 1}</td>
              <td>{gol.jugador?.nombre || "Desconocido"}</td>
              <td>{gol.jugador?.equipo?.nombre || "Sin equipo"}</td>
              <td>{gol.cantidad}</td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
};

const GolesProtegido = () => (
  <ProtectedRoute allowedRoles={["participante", "capitan", "administrador"]}>
    <Goles />
  </ProtectedRoute>
);

export default GolesProtegido;