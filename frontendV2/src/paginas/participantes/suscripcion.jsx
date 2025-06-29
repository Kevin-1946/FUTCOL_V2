import React, { useEffect, useState } from "react";
import axios from "axios";
import "../../estilos/suscripcion.css";

const Suscripcion = () => {
  const [suscripciones, setSuscripciones] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    axios.get("/api/suscripciones") // Ajusta esta ruta si es necesario
      .then((response) => {
        setSuscripciones(response.data);
        setLoading(false);
      })
      .catch((error) => {
        console.error("Error al obtener las suscripciones:", error);
        setLoading(false);
      });
  }, []);

  return (
    <div className="suscripcion-container">
      <h2 className="suscripcion-title">Mis Suscripciones</h2>
      {loading ? (
        <p className="suscripcion-loading">Cargando...</p>
      ) : (
        <div className="suscripcion-list">
          {suscripciones.map((suscripcion) => (
            <div key={suscripcion.id} className="suscripcion-item">
              <h3>{suscripcion.torneo?.nombre || "Torneo desconocido"}</h3>
              <p><strong>Equipo:</strong> {suscripcion.equipo?.nombre || "Equipo no disponible"}</p>
              <p><strong>Fecha de suscripción:</strong> {suscripcion.fecha_suscripcion}</p>
              <p><strong>Estado:</strong> {suscripcion.estado}</p>
              <p><strong>Recibos asociados:</strong> {suscripcion.recibos?.length || 0}</p>
            </div>
          ))}
        </div>
      )}
    </div>
  );
};

export default Suscripcion;