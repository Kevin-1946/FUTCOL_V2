import React, { useEffect, useState } from "react";
import {
  getEncuentros,
  createEncuentro,
  updateEncuentro,
  deleteEncuentro,
} from "../../api/EncuentroService";
import { getTorneos } from "../../api/TorneoService";
import { getSedes } from "../../api/SedeService";
import { getEquipos } from "../../api/EquipoService";
import "./EncuentroCrud.css";

const EncuentrosCrud = () => {
  const [encuentros, setEncuentros] = useState([]);
  const [torneos, setTorneos] = useState([]);
  const [sedes, setSedes] = useState([]);
  const [equipos, setEquipos] = useState([]);
  
  // Estado para las notificaciones
  const [notification, setNotification] = useState({ show: false, message: "", type: "" });

  const [form, setForm] = useState({
    torneo_id: "",
    sede_id: "",
    modalidad: "",
    fecha: "",
    hora: "",
    equipo_local_id: "",
    equipo_visitante_id: "",
    goles_local: "",
    goles_visitante: "",
  });

  const [editingId, setEditingId] = useState(null);

  // Función para mostrar notificaciones
  const showNotification = (message, type) => {
    setNotification({ show: true, message, type });
    setTimeout(() => {
      setNotification({ show: false, message: "", type: "" });
    }, 10000);
  };

  const fetchAll = async () => {
    const [encR, torR, sedR, eqR] = await Promise.all([
      getEncuentros(),
      getTorneos(),
      getSedes(),
      getEquipos(),
    ]);
    setEncuentros(encR.data);
    setTorneos(torR.data);
    setSedes(sedR.data);
    setEquipos(eqR.data);
  };

  useEffect(() => { fetchAll(); }, []);

  const equiposDelTorneo = () => {
    if (!form.torneo_id) return [];
    return equipos.filter((e) => String(e.torneo_id) === String(form.torneo_id));
  };

  const equiposVisitantes = () => {
    const lista = equiposDelTorneo();
    if (!form.equipo_local_id) return lista;
    return lista.filter((e) => String(e.id) !== String(form.equipo_local_id));
  };

  const getNombre = (arr, id) => {
    const x = arr.find((i) => String(i.id) === String(id));
    return x ? x.nombre : `ID: ${id}`;
  };

  const handleChange = (e) => {
    const { name, value } = e.target;

    if (name === "torneo_id") {
      setForm((prev) => ({
        ...prev,
        torneo_id: value,
        equipo_local_id: "",
        equipo_visitante_id: "",
      }));
      return;
    }

    if (name === "equipo_local_id") {
      setForm((prev) => ({
        ...prev,
        equipo_local_id: value,
        equipo_visitante_id: "",
      }));
      return;
    }

    setForm((prev) => ({ ...prev, [name]: value }));
  };

  const buildPayload = () => {
    const payload = {
      sede_id: form.sede_id ? Number(form.sede_id) : undefined,
      modalidad: form.modalidad || undefined,
      fecha: form.fecha,
      hora: form.hora,
      equipo_local_id: form.equipo_local_id ? Number(form.equipo_local_id) : undefined,
      equipo_visitante_id: form.equipo_visitante_id ? Number(form.equipo_visitante_id) : undefined,
      goles_local: form.goles_local !== "" ? Number(form.goles_local) : undefined,
      goles_visitante: form.goles_visitante !== "" ? Number(form.goles_visitante) : undefined,
    };
    if (form.torneo_id) payload.torneo_id = Number(form.torneo_id);
    Object.keys(payload).forEach((k) => payload[k] === undefined && delete payload[k]);
    return payload;
  };

  const resetForm = () => {
    setForm({
      torneo_id: "",
      sede_id: "",
      modalidad: "",
      fecha: "",
      hora: "",
      equipo_local_id: "",
      equipo_visitante_id: "",
      goles_local: "",
      goles_visitante: "",
    });
    setEditingId(null);
  };

  const handleSubmit = async (e) => {
    e.preventDefault();

    if (
      form.equipo_local_id &&
      form.equipo_visitante_id &&
      String(form.equipo_local_id) === String(form.equipo_visitante_id)
    ) {
      showNotification("El equipo local y visitante no pueden ser el mismo", "error");
      return;
    }

    const payload = buildPayload();

    try {
      if (editingId) {
        await updateEncuentro(editingId, payload);
        showNotification("Encuentro actualizado exitosamente", "success");
      } else {
        await createEncuentro(payload);
        showNotification("Encuentro creado exitosamente", "success");
      }
      resetForm();
      fetchAll();
    } catch (error) {
      showNotification(error.message || "Error al guardar el encuentro", "error");
      console.error("Error al guardar:", error);
    }
  };

  const handleEdit = (enc) => {
    setEditingId(enc.id);
    setForm({
      torneo_id: enc.torneo_id || "",
      sede_id: enc.sede_id || "",
      modalidad: enc.modalidad || "",
      fecha: enc.fecha || "",
      hora: enc.hora || "",
      equipo_local_id: enc.equipo_local_id || "",
      equipo_visitante_id: enc.equipo_visitante_id || "",
      goles_local: enc.goles_local ?? "",
      goles_visitante: enc.goles_visitante ?? "",
    });
  };

  const handleDelete = async (id) => {
    if (window.confirm("¿Está seguro de eliminar este encuentro?")) {
      try {
        await deleteEncuentro(id);
        showNotification("Encuentro eliminado exitosamente", "success");
        fetchAll();
      } catch (error) {
        showNotification(error.message || "Error al eliminar el encuentro", "error");
        console.error("Error al eliminar:", error);
      }
    }
  };

  return (
    <div className="page-container">
      <div className="encuentro-crud">
        {notification.show && (
          <div className={`notification ${notification.type}`}>
            {notification.message}
          </div>
        )}

        <h2>Encuentros</h2>

        <form onSubmit={handleSubmit}>
          <select name="torneo_id" value={form.torneo_id} onChange={handleChange}>
            <option value="">Seleccionar Torneo</option>
            {torneos.map((t) => (
              <option key={t.id} value={t.id}>{t.nombre}</option>
            ))}
          </select>

          <select name="sede_id" value={form.sede_id} onChange={handleChange} required>
            <option value="">Seleccionar Sede</option>
            {sedes.map((s) => (
              <option key={s.id} value={s.id}>{s.nombre}</option>
            ))}
          </select>

          <select name="modalidad" value={form.modalidad} onChange={handleChange}>
            <option value="">Seleccione modalidad (opcional)</option>
            <option value="todos contra todos">Todos contra todos</option>
            <option value="mixto">Mixto</option>
            <option value="competencia rapida">Competencia rápida</option>
            <option value="uno contra uno">Uno contra uno</option>
          </select>

          <input type="date" name="fecha" value={form.fecha} onChange={handleChange} required />
          <input type="time" name="hora" value={form.hora} onChange={handleChange} required />

          <select
            name="equipo_local_id"
            value={form.equipo_local_id}
            onChange={handleChange}
            required
            disabled={!form.torneo_id}
          >
            <option value="">{form.torneo_id ? "Equipo local" : "Seleccione un torneo"}</option>
            {equiposDelTorneo().map((e) => (
              <option key={e.id} value={e.id}>{e.nombre}</option>
            ))}
          </select>

          <select
            name="equipo_visitante_id"
            value={form.equipo_visitante_id}
            onChange={handleChange}
            required
            disabled={!form.equipo_local_id}
          >
            <option value="">{form.equipo_local_id ? "Equipo visitante" : "Seleccione el equipo local"}</option>
            {equiposVisitantes().map((e) => (
              <option key={e.id} value={e.id}>{e.nombre}</option>
            ))}
          </select>

          <input
            type="number"
            name="goles_local"
            min="0"
            placeholder="Goles local"
            value={form.goles_local}
            onChange={handleChange}
          />
          <input
            type="number"
            name="goles_visitante"
            min="0"
            placeholder="Goles visitante"
            value={form.goles_visitante}
            onChange={handleChange}
          />

          <button type="submit">{editingId ? "Actualizar" : "Crear"}</button>
        </form>

        <ul>
          {encuentros.map((e) => (
            <li key={e.id}>
              <div className="encuentro-header">
                <strong>{getNombre(torneos, e.torneo_id)}</strong>
                <span> — {e.fecha} {e.hora}</span>
              </div>

              <div>ID: {e.id}</div>

              <div>Sede: {getNombre(sedes, e.sede_id)}</div>
              <div>
                {getNombre(equipos, e.equipo_local_id)} vs {getNombre(equipos, e.equipo_visitante_id)}
                {(e.goles_local ?? e.goles_visitante) !== null && (
                  <> — {e.goles_local ?? 0} : {e.goles_visitante ?? 0}</>
                )}
              </div>
              <div className="acciones">
                <button onClick={() => handleEdit(e)}>Editar</button>
                <button onClick={() => handleDelete(e.id)}>Eliminar</button>
              </div>
            </li>
          ))}
        </ul>
      </div>
    </div>
  );
};

export default EncuentrosCrud;