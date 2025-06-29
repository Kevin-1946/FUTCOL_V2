import React, { useEffect, useState } from "react";
import { getInscripciones } from "../../api/InscripcionService";
import ProtectedRoute from "../../componentes/ProtectedRoute";
import "../../estilos/InscripcionesParticipante.css";

const Inscripciones = () => {
  const [inscripciones, setInscripciones] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    getInscripciones()
      .then((res) => {
        setInscripciones(res.data);
        setLoading(false);
      })
      .catch((err) => {
        console.error("Error al cargar inscripciones", err);
        setLoading(false);
      });
  }, []);

  if (loading) return <div className="cargando">Cargando inscripciones...</div>;

  return (
    <div className="inscripciones-container">
      <h2 className="titulo-seccion">Listado de Inscripciones</h2>
      <table className="tabla-inscripciones">
        <thead>
          <tr>
            <th>Equipo</th>
            <th>Torneo</th>
            <th>Fecha</th>
            <th>Forma de Pago</th>
            <th>Estado de Pago</th>
            <th>Total Pagado</th>
            <th>Correo Confirmado</th>
          </tr>
        </thead>
        <tbody>
          {inscripciones.map((inscripcion) => (
            <tr key={inscripcion.id}>
              <td>{inscripcion.equipo?.nombre || "N/A"}</td>
              <td>{inscripcion.torneo?.nombre || "N/A"}</td>
              <td>{inscripcion.fecha_de_inscripcion}</td>
              <td>{inscripcion.forma_pago}</td>
              <td>{inscripcion.estado_pago}</td>
              <td>${inscripcion.total_pagado}</td>
              <td>{inscripcion.correo_confirmado ? "Sí" : "No"}</td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
};

const InscripcionesProtegido = () => (
  <ProtectedRoute allowedRoles={["participante", "capitan", "administrador"]}>
    <Inscripciones />
  </ProtectedRoute>
);

export default InscripcionesProtegido;