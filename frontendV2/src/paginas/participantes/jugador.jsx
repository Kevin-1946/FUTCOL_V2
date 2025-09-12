import React, { useEffect, useState } from "react";
import axios from "axios";
import "../../estilos/jugador.css";

const Jugador = () => {
  const [jugadores, setJugadores] = useState([]);
  const [loading, setLoading] = useState(true);
  const [editandoJugador, setEditandoJugador] = useState(null);
  const [formData, setFormData] = useState({
    nombre: '',
    n_documento: '',
    fecha_nacimiento: '',
    email: ''
  });

  useEffect(() => {
    cargarJugadores();
  }, []);

  const cargarJugadores = () => {
    // Obtener el token del localStorage
    const token = localStorage.getItem('token');
    
    if (!token) {
      console.error('No hay token de autenticación');
      setLoading(false);
      return;
    }

    // Petición a /api/mi-equipo con autenticación
    axios.get("/api/mi-equipo", {
      headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json'
      }
    })
      .then((response) => {
        console.log("Respuesta mi-equipo:", response.data);
        if (response.data.success && Array.isArray(response.data.data)) {
          setJugadores(response.data.data);
        } else {
          setJugadores([]);
        }
        setLoading(false);
      })
      .catch((error) => {
        console.error("Error al obtener mi equipo:", error);
        setJugadores([]);
        setLoading(false);
      });
  };

  const iniciarEdicion = (jugador) => {
    setEditandoJugador(jugador.id);
    setFormData({
      nombre: jugador.nombre,
      n_documento: jugador.n_documento,
      fecha_nacimiento: jugador.fecha_nacimiento,
      email: jugador.email
    });
  };

  const cancelarEdicion = () => {
    setEditandoJugador(null);
    setFormData({
      nombre: '',
      n_documento: '',
      fecha_nacimiento: '',
      email: ''
    });
  };

  const handleInputChange = (e) => {
    const { name, value } = e.target;
    setFormData(prev => ({
      ...prev,
      [name]: value
    }));
  };

  const guardarCambios = async (jugadorId) => {
    const token = localStorage.getItem('token');
    
    if (!token) {
      alert('No hay token de autenticación');
      return;
    }

    try {
      const response = await axios.put(`/api/jugadores/${jugadorId}`, formData, {
        headers: {
          'Authorization': `Bearer ${token}`,
          'Accept': 'application/json',
          'Content-Type': 'application/json'
        }
      });

      if (response.data.success) {
        alert('Jugador actualizado correctamente');
        setEditandoJugador(null);
        cargarJugadores(); // Recargar la lista
      } else {
        alert('Error al actualizar el jugador');
      }
    } catch (error) {
      console.error('Error al actualizar jugador:', error);
      alert('Error al actualizar el jugador');
    }
  };

  return (
    <div className="jugador-container">
      <h2 className="jugador-titulo">Mis Jugadores</h2>

      {loading ? (
        <p className="jugador-cargando">Cargando jugadores...</p>
      ) : (
        Array.isArray(jugadores) && jugadores.length > 0 ? (
          <div className="jugador-lista">
            {jugadores.map((jugador) => (
              <div key={jugador.id} className="jugador-item">
                {editandoJugador === jugador.id ? (
                  // Modo edición
                  <div className="jugador-edicion">
                    <div className="form-group">
                      <label>Nombre:</label>
                      <input
                        type="text"
                        name="nombre"
                        value={formData.nombre}
                        onChange={handleInputChange}
                        className="form-input"
                      />
                    </div>
                    
                    <div className="form-group">
                      <label>Documento:</label>
                      <input
                        type="text"
                        name="n_documento"
                        value={formData.n_documento}
                        onChange={handleInputChange}
                        className="form-input"
                      />
                    </div>
                    
                    <div className="form-group">
                      <label>Fecha de nacimiento:</label>
                      <input
                        type="date"
                        name="fecha_nacimiento"
                        value={formData.fecha_nacimiento}
                        onChange={handleInputChange}
                        className="form-input"
                      />
                    </div>
                    
                    <div className="form-group">
                      <label>Email:</label>
                      <input
                        type="email"
                        name="email"
                        value={formData.email}
                        onChange={handleInputChange}
                        className="form-input"
                      />
                    </div>
                    
                    <div className="botones-edicion">
                      <button 
                        onClick={() => guardarCambios(jugador.id)}
                        className="btn-guardar"
                      >
                        Guardar
                      </button>
                      <button 
                        onClick={cancelarEdicion}
                        className="btn-cancelar"
                      >
                        Cancelar
                      </button>
                    </div>
                  </div>
                ) : (
                  // Modo visualización
                  <div className="jugador-info">
                    <h3>{jugador.nombre}</h3>
                    <p><strong>Documento:</strong> {jugador.n_documento}</p>
                    <p><strong>Fecha de nacimiento:</strong> {jugador.fecha_nacimiento}</p>
                    <p><strong>Email:</strong> {jugador.email}</p>
                    {jugador.equipo && (
                      <p><strong>Equipo:</strong> {jugador.equipo.nombre}</p>
                    )}
                    
                    <button 
                      onClick={() => iniciarEdicion(jugador)}
                      className="btn-editar"
                    >
                      Editar
                    </button>
                  </div>
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