import React, { useEffect, useState } from "react";
import axios from "axios";
import "../../estilos/jugador.css";

const Jugador = () => {
  const [jugadores, setJugadores] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    axios.get("/api/jugadores")
      .then((response) => {
        console.log("Respuesta jugadores:", response.data);
        if (Array.isArray(response.data)) {
          setJugadores(response.data);
        } else {
          setJugadores([]); // fallback por si la respuesta no es un array
        }
        setLoading(false);
      })
      .catch((error) => {
        console.error("Error al obtener jugadores:", error);
        setJugadores([]); // asegurarse que no sea null
        setLoading(false);
      });
  }, []);

  return (
    <div className="jugador-container">
      <h2 className="jugador-titulo">Listado de Jugadores</h2>

      {loading ? (
        <p className="jugador-cargando">Cargando jugadores...</p>
      ) : (
        Array.isArray(jugadores) && jugadores.length > 0 ? (
          <div className="jugador-lista">
            {jugadores.map((jugador) => (
              <div key={jugador.id} className="jugador-item">
                <h3>{jugador.nombre}</h3>
                <p><strong>Documento:</strong> {jugador.n_documento}</p>
                <p><strong>Fecha de nacimiento:</strong> {jugador.fecha_nacimiento}</p>
                <p><strong>Email:</strong> {jugador.email}</p>
                {jugador.equipo && (
                  <p><strong>Equipo:</strong> {jugador.equipo.nombre}</p>
                )}
              </div>
            ))}
          </div>
        ) : (
          <p className="jugador-cargando">No hay jugadores registrados.</p>
        )
      )}
    </div>
  );
};

export default Jugador;