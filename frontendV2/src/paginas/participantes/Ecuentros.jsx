import React, { useEffect, useState } from "react";
import { getEncuentros } from "../../api/EncuentroService";
import ProtectedRoute from "../../componentes/ProtectedRoute";
import "../../estilos/EncuentrosParticipante.css";

const Encuentros = () => {
  const [encuentros, setEncuentros] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    getEncuentros()
      .then((res) => {
        setEncuentros(res.data);
        setLoading(false);
      })
      .catch((err) => {
        console.error("Error al cargar encuentros", err);
        setLoading(false);
      });
  }, []);

  if (loading) return <div className="cargando">Cargando encuentros...</div>;

  return (
    <div className="encuentros-container">
      <h2 className="titulo-seccion">Próximos Encuentros y Resultados</h2>
      <table className="tabla-encuentros">
        <thead>
          <tr>
            <th>Fecha</th>
            <th>Local</th>
            <th>Goles</th>
            <th>Visitante</th>
            <th>Goles</th>
            <th>Sede</th>
          </tr>
        </thead>
        <tbody>
          {encuentros.map((e) => (
            <tr key={e.id}>
              <td>{e.fecha}</td>
              <td>{e.equipo_local?.nombre || "Sin equipo"}</td>
              <td>{e.goles_equipo_local ?? "-"}</td>
              <td>{e.equipo_visitante?.nombre || "Sin equipo"}</td>
              <td>{e.goles_equipo_visitante ?? "-"}</td>
              <td>{e.sede?.nombre || "No asignada"}</td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
};

const EncuentrosProtegido = () => (
  <ProtectedRoute allowedRoles={["participante", "capitan", "administrador"]}>
    <Encuentros />
  </ProtectedRoute>
);

export default EncuentrosProtegido;