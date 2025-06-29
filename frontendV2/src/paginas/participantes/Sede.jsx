import React, { useEffect, useState } from "react";
import axios from "axios";
import "../../estilos/sede.css";

const Sede = () => {
  const [sedes, setSedes] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    axios.get("/api/sedes") // Asegúrate que esta ruta esté configurada en tus endpoints
      .then((response) => {
        setSedes(response.data);
        setLoading(false);
      })
      .catch((error) => {
        console.error("Error al cargar las sedes:", error);
        setLoading(false);
      });
  }, []);

  return (
    <div className="sede-container">
      <h2 className="sede-title">Sedes del Torneo</h2>
      {loading ? (
        <p className="sede-loading">Cargando...</p>
      ) : (
        <div className="sede-list">
          {sedes.map((sede) => (
            <div key={sede.id} className="sede-item">
              <h3>{sede.nombre}</h3>
              <p><strong>Dirección:</strong> {sede.direccion}</p>
              {sede.torneo && (
                <p><strong>Torneo:</strong> {sede.torneo.nombre}</p>
              )}
            </div>
          ))}
        </div>
      )}
    </div>
  );
};

export default Sede;