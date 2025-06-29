import React, { useEffect, useState } from "react";
import axios from "axios";
import "../../estilos/recibo.css";

const Recibo = () => {
  const [recibos, setRecibos] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    axios.get("/api/recibos") // asegúrate que tu ruta sea esta o ajústala
      .then((response) => {
        setRecibos(response.data);
        setLoading(false);
      })
      .catch((error) => {
        console.error("Error al obtener los recibos:", error);
        setLoading(false);
      });
  }, []);

  return (
    <div className="recibo-container">
      <h2 className="recibo-titulo">Recibos de Pago</h2>

      {loading ? (
        <p className="recibo-cargando">Cargando recibos...</p>
      ) : (
        <div className="recibo-lista">
          {recibos.map((recibo) => (
            <div key={recibo.id} className="recibo-item">
              <p><strong>Monto:</strong> ${recibo.monto}</p>
              <p><strong>Fecha de emisión:</strong> {recibo.fecha_emision}</p>
              <p><strong>Método de pago:</strong> {recibo.metodo_pago}</p>
              <p><strong>Comprobante:</strong> {recibo.numero_comprobante}</p>
              <p><strong>Confirmado:</strong> {recibo.confirmado ? "Sí" : "No"}</p>
              {recibo.torneo && (
                <p><strong>Torneo:</strong> {recibo.torneo.nombre}</p>
              )}
            </div>
          ))}
        </div>
      )}
    </div>
  );
};

export default Recibo;