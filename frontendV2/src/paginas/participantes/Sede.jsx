import React, { useEffect, useState } from "react";
import axios from "axios";
import "../../estilos/sede.css";

const Sede = () => {
  const [sedes, setSedes] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    axios.get("/api/sedes")
      .then((response) => {
        // Validación: asegurarse de que la respuesta sea un array
        if (Array.isArray(response.data)) {
          setSedes(response.data);
        } else {
          setSedes([]); // fallback si no es arreglo
        }
        setLoading(false);
      })
      .catch((error) => {
        console.error("Error al cargar las sedes:", error);
        setSedes([]); // fallback en caso de error
        setLoading(false);
      });
  }, []);

  return (
    <div className="sede-container">
      <h2 className="sede-title">Sedes del Torneo</h2>

      {loading ? (
        <p className="sede-loading">Cargando...</p>
      ) : (
        Array.isArray(sedes) && sedes.length > 0 ? (
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
        ) : (
          <p className="sede-loading">No hay sedes registradas.</p>
        )
      )}
    </div>
  );
};

export default Sede;