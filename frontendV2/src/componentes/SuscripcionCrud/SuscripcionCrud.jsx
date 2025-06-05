import React, { useEffect, useState } from 'react';
import {
    getSuscripciones,
    createSuscripcion,
    updateSuscripcion,
    deleteSuscripcion
} from '../../api/SuscripcionService';
import './SuscripcionCrud.css';

const Suscripciones = () => {
    const [suscripciones, setSuscripciones] = useState([]);
    const [formData, setFormData] = useState({
        torneo_id: '',
        equipo_id: '',
        fecha_suscripcion: '',
        estado: ''
    });
    const [editingId, setEditingId] = useState(null);

    useEffect(() => {
        fetchSuscripciones();
    }, []);

    const fetchSuscripciones = async () => {
        const data = await getSuscripciones();
        setSuscripciones(data);
    };

    const handleChange = (e) => {
        setFormData({ ...formData, [e.target.name]: e.target.value });
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        if (editingId) {
            await updateSuscripcion(editingId, formData);
            setEditingId(null);
        } else {
            await createSuscripcion(formData);
        }
        setFormData({
            torneo_id: '',
            equipo_id: '',
            fecha_suscripcion: '',
            estado: ''
        });
        fetchSuscripciones();
    };

    const handleEdit = (suscripcion) => {
        setFormData({
            torneo_id: suscripcion.torneo_id,
            equipo_id: suscripcion.equipo_id,
            fecha_suscripcion: suscripcion.fecha_suscripcion,
            estado: suscripcion.estado
        });
        setEditingId(suscripcion.id);
    };

    const handleDelete = async (id) => {
        await deleteSuscripcion(id);
        fetchSuscripciones();
    };

    return (
        <div className="suscripciones-container">
            <h2>Gestión de Suscripciones</h2>
            <form onSubmit={handleSubmit} className="suscripcion-form">
                <input
                    type="number"
                    name="torneo_id"
                    placeholder="Torneo ID"
                    value={formData.torneo_id}
                    onChange={handleChange}
                    required
                />
                <input
                    type="number"
                    name="equipo_id"
                    placeholder="Equipo ID"
                    value={formData.equipo_id}
                    onChange={handleChange}
                    required
                />
                <input
                    type="date"
                    name="fecha_suscripcion"
                    value={formData.fecha_suscripcion}
                    onChange={handleChange}
                    required
                />
                <input
                    type="text"
                    name="estado"
                    placeholder="Estado"
                    value={formData.estado}
                    onChange={handleChange}
                    required
                />
                <button type="submit">
                    {editingId ? 'Actualizar' : 'Crear'}
                </button>
            </form>

            <table className="suscripcion-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Torneo</th>
                        <th>Equipo</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    {suscripciones.map((s) => (
                        <tr key={s.id}>
                            <td>{s.id}</td>
                            <td>{s.torneo?.nombre || s.torneo_id}</td>
                            <td>{s.equipo?.nombre || s.equipo_id}</td>
                            <td>{s.fecha_suscripcion}</td>
                            <td>{s.estado}</td>
                            <td>
                                <button onClick={() => handleEdit(s)}>Editar</button>
                                <button onClick={() => handleDelete(s.id)}>Eliminar</button>
                            </td>
                        </tr>
                    ))}
                </tbody>
            </table>
        </div>
    );
};

export default Suscripciones;