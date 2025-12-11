import React, { useEffect, useState } from "react";
import {
  getRecibos,
  createRecibo,
  updateRecibo,
  deleteRecibo,
} from "../../api/ReciboService";
import "./RecibosCrud.css";

const RecibosCrud = () => {
  const [recibos, setRecibos] = useState([]);
  const [notification, setNotification] = useState({ show: false, message: "", type: "" });

  const [form, setForm] = useState({
    inscripcion_id: "",
    torneo_id: "",
    monto: "",
    fecha_emision: "",
    confirmado: false,
    metodo_pago: "",
    numero_comprobante: "",
  });

  const [editandoId, setEditandoId] = useState(null);

  // Mostrar notificaciones
  const mostrarNotificacion = (message, type) => {
    setNotification({ show: true, message, type });
    setTimeout(() => {
      setNotification({ show: false, message: "", type: "" });
    }, 10000);
  };

  const cargarRecibos = () => {
    getRecibos()
      .then((res) => setRecibos(res.data))
      .catch(() => {
        mostrarNotificacion("Error al cargar los recibos", "error");
      });
  };

  useEffect(() => {
    cargarRecibos();
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  const manejarCambio = (e) => {
    const { name, value, type, checked } = e.target;
    setForm({
      ...form,
      [name]: type === "checkbox" ? checked : value,
    });
  };

  const manejarSubmit = (e) => {
    e.preventDefault();
    const metodo = editandoId ? updateRecibo : createRecibo;
    const args = editandoId ? [editandoId, form] : [form];

    metodo(...args)
      .then(() => {
        mostrarNotificacion(
          editandoId ? "Recibo actualizado exitosamente" : "Recibo creado exitosamente",
          "success"
        );
        cargarRecibos();
        setForm({
          inscripcion_id: "",
          torneo_id: "",
          monto: "",
          fecha_emision: "",
          confirmado: false,
          metodo_pago: "",
          numero_comprobante: "",
        });
        setEditandoId(null);
      })
      .catch(() => {
        mostrarNotificacion("Error al guardar el recibo", "error");
      });
  };

  const editarRecibo = (recibo) => {
    setForm({
      inscripcion_id: recibo.inscripcion_id,
      torneo_id: recibo.torneo_id,
      monto: recibo.monto,
      fecha_emision: recibo.fecha_emision,
      confirmado: recibo.confirmado,
      metodo_pago: recibo.metodo_pago,
      numero_comprobante: recibo.numero_comprobante,
    });
    setEditandoId(recibo.id);
  };

  const eliminarRecibo = (id) => {
    if (window.confirm("¿Seguro que quieres eliminar este recibo?")) {
      deleteRecibo(id)
        .then(() => {
          mostrarNotificacion("Recibo eliminado exitosamente", "success");
          cargarRecibos();
        })
        .catch(() => {
          mostrarNotificacion("Error al eliminar el recibo", "error");
        });
    }
  };

  return (
    <div className="page-container">
      <div>

        {notification.show && (
          <div className={`notification ${notification.type}`}>
            {notification.message}
          </div>
        )}

        <h2>{editandoId ? "Editar Recibo" : "Nuevo Recibo"}</h2>
        <form onSubmit={manejarSubmit}>
          <input
            name="inscripcion_id"
            placeholder="ID Inscripcion"
            value={form.inscripcion_id}
            onChange={manejarCambio}
            required
          />
          <input
            name="torneo_id"
            placeholder="ID Torneo"
            value={form.torneo_id}
            onChange={manejarCambio}
            required
          />
          <input
            name="monto"
            type="number"
            placeholder="Monto"
            value={form.monto}
            onChange={manejarCambio}
            required
            min="0"
            step="0.01"
          />
          <input
            name="fecha_emision"
            type="date"
            value={form.fecha_emision}
            onChange={manejarCambio}
            required
          />
          <label>
            Confirmado
            <input
              name="confirmado"
              type="checkbox"
              checked={form.confirmado}
              onChange={manejarCambio}
            />
          </label>
          <input
            name="metodo_pago"
            placeholder="Método de Pago"
            value={form.metodo_pago}
            onChange={manejarCambio}
            required
          />
          <input
            name="numero_comprobante"
            placeholder="Número Comprobante"
            value={form.numero_comprobante}
            onChange={manejarCambio}
            required
          />
          <button type="submit">{editandoId ? "Actualizar" : "Crear"}</button>
        </form>

        <h3>Lista de Recibos</h3>
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Inscripción</th>
              <th>Torneo</th>
              <th>Monto</th>
              <th>Fecha Emisión</th>
              <th>Confirmado</th>
              <th>Método Pago</th>
              <th>Comprobante</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            {recibos.map((r) => (
              <tr key={r.id}>
                <td>{r.id}</td>
                <td>{r.inscripcion_id}</td>
                <td>{r.torneo_id}</td>
                <td>{r.monto}</td>
                <td>{r.fecha_emision}</td>
                <td>{r.confirmado ? "Sí" : "No"}</td>
                <td>{r.metodo_pago}</td>
                <td>{r.numero_comprobante}</td>
                <td>
                  <button onClick={() => editarRecibo(r)}>Editar</button>
                  <button onClick={() => eliminarRecibo(r.id)}>Eliminar</button>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
};

export default RecibosCrud;