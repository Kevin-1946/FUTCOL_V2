import React from "react";
import "bootstrap/dist/css/bootstrap.min.css";
import fondo_nosotros from "../../assets/imagenes/fondo_nosotros.png";
import mision from "../../assets/imagenes/mision.png";
import vision from "../../assets/imagenes/vision.png";
import valores from "../../assets/imagenes/valores.png";
import "../../index.css";

const SobreNosotros = () => {
  const data = [
    {
      title: "Nuestra Misión",
      text: "Brindar oportunidades para que los jugadores de todas las edades y niveles participen en torneos de calidad, fomentando el deporte y el trabajo en equipo.",
      img: mision,
    },
    {
      title: "Nuestra Visión",
      text: "Ser el referente en la organización de torneos deportivos, promoviendo el juego limpio, la inclusión y el desarrollo del talento deportivo.",
      img: vision,
    },
    {
      title: "Nuestros Valores",
      text: "Compromiso, pasión y respeto son los valores fundamentales que nos guían en cada torneo y en cada experiencia para nuestros jugadores y equipos.",
      img: valores,
    },
  ];

  return (
    
    <div
      className="sobre-nosotros-section full-width"
      style={{
        backgroundImage: `url(${fondo_nosotros})`,
      }}
    >
      {/* Capa oscura sobre el fondo */}
      <div className="sobre-nosotros-overlay"></div>

      {/* Contenido */}
      <div className="sobre-nosotros-content">
        <h1 className="sobre-nosotros-title">Sobre Nosotros</h1>

        <div className="sobre-nosotros-items">
          {data.map((item, index) => (
            <div
              key={index}
              className={`sobre-nosotros-item ${
                index % 2 !== 0 ? "reverse" : ""
              }`}
            >
              <div className="sobre-nosotros-image-container">
                <img
                  src={item.img}
                  alt={item.title}
                  className="sobre-nosotros-image"
                />
              </div>
              <div className="sobre-nosotros-text">
                <h2>{item.title}</h2>
                <p>{item.text}</p>
              </div>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
};

export default SobreNosotros;