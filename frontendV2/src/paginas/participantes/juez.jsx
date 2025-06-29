import React, { useEffect, useState } from "react";
import axios from "axios";
import "../../estilos/juez.css";

const Juez = () => {
  const [jueces, setJueces] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    axios.get("http://localhost:8000/api/jueces")
      .then(response => {
        setJueces(response.data);
        setLoading(false);
      })
      .catch(error => {
        console.error("Error al obtener los jueces:", error);
        setLoading(false);
      });
  }, []);

  if (loading) return <p className="juez-cargando">Cargando jueces...</p>;

  return (
    <div className="juez-container">
      <h2 className="juez-titulo">Listado de Jueces</h2>
      <div className="juez-lista">
        {jueces.map(juez => (
          <div key={juez.id} className="juez-item">
            <h3>{juez.nombre}</h3>
            <p><strong>Contacto:</strong> {juez.numero_de_contacto}</p>
            <p><strong>Correo:</strong> {juez.correo || "No registrado"}</p>
            <p><strong>Sede:</strong> {juez.sede?.nombre || "Sin sede asignada"}</p>
          </div>
        ))}
      </div>
    </div>
  );
};

export default Juez;