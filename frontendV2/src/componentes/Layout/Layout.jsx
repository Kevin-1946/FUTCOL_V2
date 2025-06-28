import React, { useState, useEffect } from 'react';
import { Link, Outlet, useNavigate } from 'react-router-dom';
import './layout.css';
import { useAuth } from '../../contexts/AuthContext';

const Layout = () => {
  const [activeMenu, setActiveMenu] = useState(null);
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);

  const { user, logout } = useAuth();
  const navigate = useNavigate();

  useEffect(() => {
    const elements = document.querySelectorAll('*');
    elements.forEach(el => {
      if (el.scrollWidth > document.documentElement.clientWidth) {
        console.warn("Elemento con desbordamiento:", el);
      }
    });
  }, []);

  const handleLogout = () => {
    logout();
    navigate("/login");
  };

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

  // Mostrar nombre según tipo de usuario logueado
  const mostrarNombre = () => {
  if (!user) return "";

  const rol = user.role?.nombre?.toLowerCase();

  if (rol === "administrador" || rol === "capitan") {
    return user.role.nombre; // Solo muestra el rol
  }

  if (rol === "participante") {
    return user.nombre || "Participante"; // Muestra nombre completo
  }

  return "Usuario"; // Fallback
};

    const menuDerecha = user
    ? [
        {
          nombre: mostrarNombre(),
          esEtiqueta: true
        },
        {
          nombre: "Cerrar sesión",
          accion: handleLogout,
          esBoton: true
        }
      ]:
    [
      { nombre: "Iniciar Sesión",
        ruta: "/login" },
      { nombre: "Suscribirse", // <- esto se mostrará solo si NO hay usuario
        ruta: "/suscribirse" }
      
    ];

  return (
    <div className="layout-container">
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
          {menuDerecha.map((item, index) => {
            if (item.esEtiqueta) {
              return (
                <span key={index} className="navbar-action-item etiqueta-rol">
                  {item.nombre}
                </span>
              );
            } else if (item.esBoton) {
              return (
                <button
                  key={index}
                  className="navbar-action-item"
                  onClick={item.accion}
                >
                  {item.nombre}
                </button>
              );
            } else {
              return (
                <Link key={index} to={item.ruta} className="navbar-action-item">
                  {item.nombre}
                </Link>
              );
            }
          })}
        </div>
      </nav>

      <main className="main-content">
        <Outlet />
      </main>
    </div>
  );
};

export default Layout;