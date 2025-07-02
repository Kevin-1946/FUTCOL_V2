import React, { useState, useEffect } from 'react';
import axios from 'axios';
import '../../estilos/registroCapitan.css';

const RegistroEquipoCapitan = () => {
  const [formData, setFormData] = useState({
  nombre_equipo: '',
  torneo_id: '',
  capitan: {
    nombre: '',
    correo: '',
    documento: '',
    edad: '',
    genero: '',
    password: ''
  },
  jugadores: Array(6).fill({
    nombre: '',
    correo: '',
    documento: '',
    edad: '',
    genero: ''
  })
});

  const [torneos, setTorneos] = useState([]);
  const [mensaje, setMensaje] = useState('');

  useEffect(() => {
    axios.get('http://localhost:8000/api/torneos') // asegúrate que esta ruta existe
      .then(res => setTorneos(res.data))
      .catch(err => console.error('Error al cargar torneos:', err));
  }, []);

  const handleChangeCapitan = (e) => {
    setFormData({
      ...formData,
      capitan: {
        ...formData.capitan,
        [e.target.name]: e.target.value
      }
    });
  };

  const handleJugadorChange = (index, e) => {
    const nuevosJugadores = [...formData.jugadores];
    nuevosJugadores[index] = {
      ...nuevosJugadores[index],
      [e.target.name]: e.target.value
    };
    setFormData({ ...formData, jugadores: nuevosJugadores });
  };

  const agregarJugador = () => {
    if (formData.jugadores.length < 8) {
        setFormData({
        ...formData,
        jugadores: [...formData.jugadores, {
            nombre: '', correo: '', documento: '', edad: '', genero: ''
        }]
        });
    }
    };

  const quitarJugador = (index) => {
    const nuevos = formData.jugadores.filter((_, i) => i !== index);
    setFormData({ ...formData, jugadores: nuevos });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();

    if (formData.jugadores.length < 6) {
      alert("Debes registrar al menos 6 jugadores además del capitán.");
      return;
    }

    try {
      const res = await axios.post('http://localhost:8000/api/registro-equipo', formData);
      setMensaje('¡Equipo registrado correctamente!');
      setFormData({
        nombre_equipo: '',
        torneo_id: '',
        capitan: { nombre: '', correo: '', documento: '', password: '' },
        jugadores: Array(6).fill({ nombre: '', correo: '', documento: '' })
      });
    } catch (err) {
      console.error(err);
      setMensaje('Ocurrió un error al registrar el equipo.');
    }
  };

  return (
  <div className="registro-container">
    <h2 className="titulo-suscripcion">📝Suscripción </h2>

    <form onSubmit={handleSubmit} className="registro-form">
      <fieldset>
        <legend>🛡 Información del equipo</legend>
        <div className="campos-doble-columna">
            <div>
            <label>Nombre del equipo:</label>
            <input type="text" name="nombre_equipo" value={formData.nombre_equipo}
                onChange={e => setFormData({ ...formData, nombre_equipo: e.target.value })}
                required />
            </div>
            <div>
            <label>Torneo en el que desea participar:</label>
            <select name="torneo_id" value={formData.torneo_id}
                onChange={e => setFormData({ ...formData, torneo_id: e.target.value })}
                required>
                <option value="">Seleccione un torneo</option>
                {torneos.map(t => (
                <option key={t.id} value={t.id}>{t.nombre}</option>
                ))}
            </select>
            </div>
        </div>
        </fieldset>

      <fieldset>
        <legend>👨‍✈️ Datos del Capitán</legend>
        <div className="campos-doble-columna">
            <div>
            <label>Nombre:</label>
            <input name="nombre" value={formData.capitan.nombre} onChange={handleChangeCapitan} required />
            </div>
            <div>
            <label>Correo:</label>
            <input name="correo" type="email" value={formData.capitan.correo} onChange={handleChangeCapitan} required />
            </div>
            <div>
            <label>Documento:</label>
            <input name="documento" value={formData.capitan.documento} onChange={handleChangeCapitan} required />
            </div>
            <div>
            <label>Edad:</label>
            <input name="edad" type="number" value={formData.capitan.edad} onChange={handleChangeCapitan} required />
            </div>
            <div>
            <label>Género:</label>
            <select name="genero" value={formData.capitan.genero} onChange={handleChangeCapitan} required>
                <option value="">Seleccione</option>
                <option value="masculino">Masculino</option>
                <option value="femenino">Femenino</option>
                <option value="otro">Otro</option>
            </select>
            </div>
            <div>
            <label>Contraseña (para inicio de sesión):</label>
            <input name="password" type="password" value={formData.capitan.password} onChange={handleChangeCapitan} required />
            </div>
        </div>
        </fieldset>

      <fieldset>
        <legend>🧑‍🤝‍🧑 Jugadores del equipo ({formData.jugadores.length})</legend>

        <div className="jugadores-grid">
            {formData.jugadores.map((jugador, index) => (
            <div className="jugador-card" key={index}>
                <h4>Jugador {index + 1}</h4>
                <label>Nombre:</label>
                <input name="nombre" value={jugador.nombre} onChange={(e) => handleJugadorChange(index, e)} required />

                <label>Correo:</label>
                <input name="correo" type="email" value={jugador.correo} onChange={(e) => handleJugadorChange(index, e)} required />

                <label>Documento:</label>
                <input name="documento" value={jugador.documento} onChange={(e) => handleJugadorChange(index, e)} required />

                <label>Edad:</label>
                <input name="edad" type="number" value={jugador.edad} onChange={(e) => handleJugadorChange(index, e)} required />

                <label>Género:</label>
                <select name="genero" value={jugador.genero} onChange={(e) => handleJugadorChange(index, e)} required>
                <option value="">Seleccione</option>
                <option value="masculino">Masculino</option>
                <option value="femenino">Femenino</option>
                <option value="otro">Otro</option>
                </select>

                {index >= 6 && (
                <button type="button" onClick={() => quitarJugador(index)} className="btn-quitar">Quitar jugador</button>
                )}
            </div>
            ))}
        </div>

        {formData.jugadores.length < 8 && (
            <button type="button" onClick={agregarJugador}>Agregar otro jugador</button>
        )}
        </fieldset>

      <fieldset>
        <legend>💳 Método de pago</legend>
        <p><strong>Valor de inscripción:</strong> Se calculará según el torneo seleccionado.</p>
        <button type="button" disabled>Simular pago (proximamente)</button>
      </fieldset>

      <button type="submit" className="btn-enviar">Registrar equipo</button>
    </form>

    {mensaje && <p className="mensaje">{mensaje}</p>}
  </div>
);

};

export default RegistroEquipoCapitan;