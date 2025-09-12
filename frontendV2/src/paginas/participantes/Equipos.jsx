import React, { useEffect, useState } from "react";
import { getEquipos } from "../../api/EquipoService";
import ProtectedRoute from "../../componentes/ProtectedRoute";
import "../../estilos/EquiposParticipante.css";

const Equipos = () => {
  const [equipos, setEquipos] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    getEquipos()
      .then((res) => {
        setEquipos(res.data);
        setLoading(false);
      })
      .catch((err) => {
        console.error("Error al cargar equipos", err);
        setLoading(false);
      });
  }, []);

  if (loading) return <div className="cargando">Cargando equipos...</div>;

  return (
    <div className="equipos-container">
      <h2 className="titulo-seccion">Equipos Registrados</h2>
      <div className="equipos-grid">
        {equipos.map((equipo) => (
          <div key={equipo.id} className="equipo-card">
            <h3>{equipo.nombre}</h3>
            <p><strong>Torneo:</strong> {equipo.torneo?.nombre || "No asignado"}</p>
            <p><strong>Capitán:</strong> {equipo.capitan?.nombre || "No definido"}</p>

            <details>
              <summary>Jugadores ({equipo.jugadores?.length || 0})</summary>
              <ul>
                {equipo.jugadores?.map((j) => (
                  <li key={j.id}>{j.nombre}</li>
                ))}
              </ul>
            </details>
          </div>
        ))}
      </div>
    </div>
  );
};

const EquiposProtegido = () => (
  <ProtectedRoute allowedRoles={["participante", "capitan", "administrador"]}>
    <Equipos />
  </ProtectedRoute>
);

export default EquiposProtegido;