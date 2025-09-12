import React, { useState } from "react";
import "../../estilos/torneos.css";
import ligaImg from "../../assets/imagenes/torneo_liga.png";
import relampagoImg from "../../assets/imagenes/torneo_relampago.png";
import eliminacionImg from "../../assets/imagenes/torneo_e_directa.png";
import categoriasImg from "../../assets/imagenes/torneo_categorias.png";

const torneos = [
  {
    nombre: "Liga Tradicional",
    descripcion: "Competencia de largo aliento donde todos los equipos se enfrentan entre sí en una fase de todos contra todos. Gana el equipo más constante.",
    imagen: ligaImg,
    detalles: [
      "Duración: 3 meses",
      "Partidos semanales",
      "Tabla de posiciones acumulada",
      "Inscripción: $80.000",
      "Premio al campeón: $1.200.000"
    ]
  },
  {
    nombre: "Torneo Relámpago",
    descripcion: "Formato rápido y dinámico, ideal para un solo día o fin de semana. Los partidos son más cortos y la eliminación es directa.",
    imagen: relampagoImg,
    detalles: [
      "Duración: 1 día",
      "Partidos de 20 minutos",
      "Eliminación directa",
      "Inscripción: $50.000",
      "Premio: artículos deportivos"
    ]
  },
  {
    nombre: "Eliminación Directa",
    descripcion: "Sistema de eliminación desde el primer partido. Solo los ganadores avanzan. ¡Cada partido es una final!",
    imagen: eliminacionImg,
    detalles: [
      "Duración: 1 mes",
      "Fase de eliminación directa",
      "No hay segundas oportunidades",
      "Inscripción: $70.000",
      "Premio: trofeo + medallas"
    ]
  },
  {
    nombre: "Torneo Mixto",
    descripcion: "Espacio inclusivo para que hombres y mujeres compitan juntos en igualdad. Promueve el trabajo en equipo y la diversidad.",
    imagen: categoriasImg,
    detalles: [
      "Duración: 2 meses",
      "Mínimo 2 mujeres por equipo",
      "Formato todos contra todos",
      "Inscripción: $90.000",
      "Premio: bono en efectivo + uniforme"
    ]
  }
];

const requisitos = [
  "Cada equipo debe tener entre 7 y 9 jugadores (incluido el capitán).",
  "Categoría Élite Juvenil: jugadores entre 15 y 25 años.",
  "Categoría Sénior Competitiva: jugadores entre 26 y 55 años.",
  "Todos los jugadores deben estar inscritos previamente para poder participar."
];

const Torneos = () => {
  const [torneoSeleccionado, setTorneoSeleccionado] = useState(null);

  return (
    <div className="torneos-container">
      <h2 className="torneos-title">Torneos Disponibles</h2>

      <div className="torneos-lista"> 
        {torneos.map((torneo, idx) => (
          <div
            key={idx}
            className="torneo-card"
            onClick={() => setTorneoSeleccionado(torneo)}
          >
            <img src={torneo.imagen} alt={torneo.nombre} className="torneo-imagen" />
          </div>
        ))}
      </div>

      <div className="requisitos-section">
        <h3>Requisitos Generales</h3>
        <ul>
          {requisitos.map((req, idx) => (
            <li key={idx}>{req}</li>
          ))}
        </ul>
      </div>

      {torneoSeleccionado && (
        <div className="modal-content">
          <button className="cerrar-modal" onClick={() => setTorneoSeleccionado(null)}>X</button>
          <h2>{torneoSeleccionado.nombre}</h2>
          <p style={{ marginTop: '1rem' }}>{torneoSeleccionado.descripcion}</p>
          
          {/* Aquí va tu texto personalizado */}
          <div style={{ marginTop: '1rem', textAlign: 'left' }}>
            <h4>Detalles del torneo:</h4>
            <ul>
              {torneoSeleccionado.detalles?.map((item, i) => (
                <li key={i}>{item}</li>
              ))}
            </ul>
          </div>
        </div>
      )}
      </div>
  );
};

export default Torneos;