import React, { useState, useEffect } from 'react';
import { Link, Outlet } from 'react-router-dom';
import './layout.css';
//import './navbar.css'; // Asegúrate de importar los estilos del navbar

const Layout = () => {
  // Estados del navbar
  const [activeMenu, setActiveMenu] = useState(null);
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);

  // useEffect del layout original para detectar desbordamiento
  useEffect(() => {
    const elements = document.querySelectorAll('*');
    elements.forEach(el => {
      if (el.scrollWidth > document.documentElement.clientWidth) {
        console.warn("Elemento con desbordamiento:", el);
      }
    });
  }, []);

  // Datos del menú
  const menuEstructurado = [
    { nombre: "Inicio", ruta: "/" },
    {
      nombre: "Torneos",
      ruta: "/torneos",
      subitems: [
        { nombre: "Amonestaciones", ruta: "/torneos/amonestacion" },
        { nombre: "Encuentros", ruta: "/torneos/encuentros" },
        { nombre: "Equipos", ruta: "/torneos/equipos" },
        { nombre: "Goleadores", ruta: "/torneos/goles" },
        { nombre: "Sedes", ruta: "/torneos/sede" }
      ]
    },
    {
      nombre: "Administración",
      ruta: "/administracion",
      subitems: [
        { nombre: "Estadisticas por equipo", ruta: "/administracion/estadisticaEquipo" },
        { nombre: "Recibos", ruta: "/administracion/recibo" }
      ]
    },
    {
      nombre: "Participantes",
      ruta: "/participantes",
      subitems: [
        { nombre: "Jugadores", ruta: "/participantes/jugador" },
        { nombre: "Jueces", ruta: "/participantes/juez" }
      ]
    },
    {
      nombre: "Información",
      ruta: "/informacion/sobre_nosotros"
    }
  ];

  const menuDerecha = [
    { nombre: "Iniciar Sesión", ruta: "/login" },
    { nombre: "Suscribirse", ruta: "/suscribirse" }
  ];

  return (
    <div className="layout-container">
      {/* Navbar integrado */}
      <nav className="navbar-container">
        <button className="menu-btn" onClick={() => setMobileMenuOpen(!mobileMenuOpen)}>
          ☰
        </button>

        <div className={`navbar-menu ${mobileMenuOpen ? 'active' : ''}`}>
          {menuEstructurado.map((item, index) => (
            <div 
              key={index}
              className={`navbar-item ${item.subitems ? 'has-dropdown' : ''}`}
              onMouseEnter={() => item.subitems && setActiveMenu(index)}
              onMouseLeave={() => item.subitems && setActiveMenu(null)}
            >
              <Link to={item.ruta}>{item.nombre}</Link>
              {item.subitems && (mobileMenuOpen || activeMenu === index) && (
                <div className="navbar-dropdown">
                  {item.subitems.map((subitem, subIndex) => (
                    <Link key={subIndex} to={subitem.ruta} className="dropdown-item">
                      {subitem.nombre}
                    </Link>
                  ))}
                </div>
              )}
            </div>
          ))}
        </div>

        <div className={`navbar-actions ${mobileMenuOpen ? 'active' : ''}`}>
          {menuDerecha.map((item, index) => (
            <Link key={index} to={item.ruta} className="navbar-action-item">
              {item.nombre}
            </Link>
          ))}
        </div>
      </nav>

      {/* Contenido principal */}
      <main className="main-content">
        <Outlet />
      </main>
    </div>
  );
};

export default Layout;