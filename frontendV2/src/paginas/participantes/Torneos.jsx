import React from "react";
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
  },
  {
    nombre: "Torneo Relámpago",
    descripcion: "Formato rápido y dinámico, ideal para un solo día o fin de semana. Los partidos son más cortos y la eliminación es directa.",
    imagen: relampagoImg,
  },
  {
    nombre: "Eliminación Directa",
    descripcion: "Sistema de eliminación desde el primer partido. Solo los ganadores avanzan. ¡Cada partido es una final!",
    imagen: eliminacionImg,
  },
  {
    nombre: "Torneo Mixto",
    descripcion: "Espacio inclusivo para que hombres y mujeres compitan juntos en igualdad. Promueve el trabajo en equipo y la diversidad.",
    imagen: categoriasImg,
  },
];

const requisitos = [
  "Cada equipo debe tener entre 7 y 9 jugadores (incluido el capitán).",
  "Categoría Élite Juvenil: jugadores entre 15 y 25 años.",
  "Categoría Sénior Competitiva: jugadores entre 26 y 55 años.",
  "Todos los jugadores deben estar inscritos previamente para poder participar."
];

const Torneos = () => {
  return (
    <div className="torneos-container">
      <h2 className="torneos-title">Torneos Disponibles</h2>

      <div className="torneos-lista">
        {torneos.map((torneo, idx) => (
          <div key={idx} className="torneo-card">
            <img src={torneo.imagen} alt={torneo.nombre} className="torneo-imagen" />
            <h3>{torneo.nombre}</h3>
            <p>{torneo.descripcion}</p>
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
    </div>
  );
};

export default Torneos;