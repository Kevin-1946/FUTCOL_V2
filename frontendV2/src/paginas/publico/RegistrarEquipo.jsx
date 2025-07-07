import React, { useState, useEffect } from "react";
import axios from "axios";
import "../../estilos/RegistrarEquipo.css";

const RegistrarEquipo = () => {
  const [torneos, setTorneos] = useState([]);
  const [form, setForm] = useState({
    nombre_equipo: "",
    torneo_id: "",
    forma_pago: "efectivo",
    capitan: {
      nombre: "",
      email: "",
      documento: "",
      password: "",
      genero: "",
      edad: "",
      fecha_nacimiento: "",
    },
    jugadores: Array(6).fill({
      nombre: "",
      email: "",
      documento: "",
      genero: "",
      edad: "",
      fecha_nacimiento: "",
    }),
  });

  useEffect(() => {
    axios.get("http://localhost:8000/api/torneos").then((res) => {
      setTorneos(res.data);
    });
  }, []);

  const handleChange = (e, path = []) => {
    const updatedForm = { ...form };
    if (path.length === 0) {
      updatedForm[e.target.name] = e.target.value;
    } else {
      let field = updatedForm;
      path.slice(0, -1).forEach((key) => (field = field[key]));
      field[path[path.length - 1]] = e.target.value;
    }
    setForm(updatedForm);
  };

  const handleJugadorChange = (index, e) => {
    const jugadores = [...form.jugadores];
    jugadores[index] = { ...jugadores[index], [e.target.name]: e.target.value };
    setForm({ ...form, jugadores });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (form.jugadores.length < 6 || form.jugadores.length > 8) {
      alert("Debes registrar entre 6 y 8 jugadores, además del capitán.");
      return;
    }
    try {
      const res = await axios.post("http://localhost:8000/api/registrar-equipo", form);
      alert("Inscripción exitosa");
      console.log(res.data);
    } catch (error) {
      console.error(error.response?.data || error.message);
      alert("Error en la inscripción");
    }
  };

  return (
    <div className="registro-container">
      <div className="header-section">
        <div className="header-content">
          <h2>Registrar Equipo</h2>
          <p>Complete todos los campos para inscribir su equipo al torneo</p>
        </div>
      </div>

      <form onSubmit={handleSubmit} className="formulario-equipo">
        
        {/* Información del Equipo */}
        <div className="form-section">
          <div className="section-header">
            <h3>Información del Equipo</h3>
          </div>
          
          <div className="input-group">
            <label htmlFor="nombre_equipo">Nombre del Equipo</label>
            <input 
              id="nombre_equipo"
              name="nombre_equipo" 
              placeholder="Ingrese el nombre del equipo" 
              value={form.nombre_equipo} 
              onChange={handleChange} 
              required
            />
          </div>

          <div className="input-group">
            <label htmlFor="torneo_id">Torneo</label>
            <select name="torneo_id" value={form.torneo_id} onChange={handleChange} required>
              <option value="">Seleccione Torneo</option>
              {torneos.map((torneo) => (
                <option key={torneo.id} value={torneo.id}>{torneo.nombre}</option>
              ))}
            </select>
          </div>
        </div>

        {/* Información de Pago */}
        <div className="form-section">
          <div className="section-header">
            <h3>Información de Pago</h3>
          </div>
          
          <div className="input-row">
            <div className="input-group">
              <label htmlFor="forma_pago">Forma de Pago</label>
              <select name="forma_pago" value={form.forma_pago} onChange={handleChange}>
                <option value="efectivo">Efectivo</option>
                <option value="transferencia">Transferencia</option>
              </select>
            </div>
          </div>
        </div>

        {/* Datos del Capitán */}
        <div className="form-section capitan-section">
          <div className="section-header">
            <h3>Datos del Capitán</h3>
          </div>
          
          <div className="capitan-grid">
            {Object.entries(form.capitan).map(([key, value]) => (
              <div key={key} className="input-group">
                <label htmlFor={key}>
                  {key === 'nombre' ? 'Nombre Completo' :
                   key === 'email' ? 'Correo Electrónico' :
                   key === 'documento' ? 'Documento' :
                   key === 'password' ? 'Contraseña' :
                   key === 'genero' ? 'Género' :
                   key === 'edad' ? 'Edad' :
                   key === 'fecha_nacimiento' ? 'Fecha de Nacimiento' : 
                   key.replace(/_/g, ' ').toUpperCase()}
                </label>
                <input
                  id={key}
                  name={key}
                  type={key === 'email' ? 'email' : 
                        key === 'password' ? 'password' : 
                        key === 'edad' ? 'number' : 
                        key === 'fecha_nacimiento' ? 'date' : 'text'}
                  placeholder={key === 'nombre' ? 'Nombre completo del capitán' :
                              key === 'email' ? 'correo@ejemplo.com' :
                              key === 'documento' ? 'Número de documento' :
                              key === 'password' ? 'Contraseña' :
                              key === 'genero' ? 'M/F' :
                              key === 'edad' ? 'Edad' :
                              key.replace(/_/g, ' ').toUpperCase()}
                  value={value}
                  onChange={(e) => handleChange(e, ["capitan", key])}
                  required
                />
              </div>
            ))}
          </div>
        </div>

        {/* Jugadores */}
        <div className="form-section jugadores-section">
          <div className="section-header">
            <h3>Jugadores</h3>
            <span className="jugadores-count">{form.jugadores.length} de 8 máximo</span>
          </div>
          
          <div className="jugadores-grid">
            {form.jugadores.map((jugador, index) => (
              <div key={index} className="jugador-box">
                <div className="jugador-header">
                  <span className="jugador-numero">{index + 1}</span>
                  <h4>Jugador {index + 1}</h4>
                </div>
                
                <div className="jugador-fields">
                  {Object.entries(jugador).map(([key, value]) => (
                    <div key={key} className="input-group small">
                      <label htmlFor={`jugador_${index}_${key}`}>
                        {key === 'nombre' ? 'Nombre' :
                         key === 'email' ? 'Email' :
                         key === 'documento' ? 'Documento' :
                         key === 'genero' ? 'Género' :
                         key === 'edad' ? 'Edad' :
                         key === 'fecha_nacimiento' ? 'Fecha Nac.' : key}
                      </label>
                      <input
                        id={`jugador_${index}_${key}`}
                        name={key}
                        type={key === 'email' ? 'email' : 
                              key === 'edad' ? 'number' : 
                              key === 'fecha_nacimiento' ? 'date' : 'text'}
                        placeholder={key === 'nombre' ? 'Nombre completo' :
                                    key === 'email' ? 'email@ejemplo.com' :
                                    key === 'documento' ? 'Documento' :
                                    key === 'genero' ? 'M/F' :
                                    key === 'edad' ? 'Edad' :
                                    key.replace(/_/g, ' ').toUpperCase()}
                        value={value}
                        onChange={(e) => handleJugadorChange(index, e)}
                        required
                      />
                    </div>
                  ))}
                </div>
              </div>
            ))}
          </div>

          <div className="botones-jugadores">
            {form.jugadores.length < 8 && (
              <button type="button" className="btn-add" onClick={() => setForm({
                ...form,
                jugadores: [...form.jugadores, {
                  nombre: "",
                  email: "",
                  documento: "",
                  genero: "",
                  edad: "",
                  fecha_nacimiento: "",
                }]
              })}>
                <span>+</span> Añadir jugador
              </button>
            )}

            {form.jugadores.length > 6 && (
              <button type="button" className="btn-remove" onClick={() => {
                const nuevos = [...form.jugadores];
                nuevos.pop();
                setForm({ ...form, jugadores: nuevos });
              }}>
                <span>-</span> Quitar jugador
              </button>
            )}
          </div>
        </div>

        <div className="submit-section">
          <button type="submit" className="btn-submit">
             Enviar Inscripción
          </button>
        </div>
      </form>
    </div>
  );
};

export default RegistrarEquipo;