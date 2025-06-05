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
    <>
      <div
        className="sobre-nosotros-section"
        style={{
          position: "relative",
          backgroundImage: `url(${fondo_nosotros})`,
          backgroundSize: "cover",
          backgroundPosition: "center",
          minHeight: "100vh",
          paddingTop: "30px", // Ajustado para header
          paddingBottom: "2rem",
        }}
      >
        {/* Capa oscura sobre el fondo */}
        <div
          style={{
            position: "absolute",
            inset: 0,
            backgroundColor: "rgba(0, 0, 0, 0.6)",
            zIndex: 0,
          }}
        ></div>

        {/* Contenido */}
        <div style={{ position: "relative", zIndex: 1 }}>
          <h1 className="text-center text-white mb-5">Sobre Nosotros</h1>

          <div className="container">
            {data.map((item, index) => (
              <div
                key={index}
                className={`row align-items-center mb-5 flex-md-row ${
                  index % 2 !== 0 ? "flex-md-row-reverse" : ""
                }`}
              >
                <div className="col-md-6 text-center mb-3 mb-md-0">
                  <img
                    src={item.img}
                    alt={item.title}
                    style={{
                      maxWidth: "45%",
                      height: "auto",
                      borderRadius: "12px",
                      boxShadow: "0 4px 15px rgba(0,0,0,0.4)",
                    }}
                  />
                </div>
                <div
                  className="col-md-6 p-4 bg-white text-black rounded shadow"
                >
                  <h2>{item.title}</h2>
                  <p>{item.text}</p>
                </div>
              </div>
            ))}
          </div>
        </div>
      </div>
    </>
  );
};

export default SobreNosotros;

