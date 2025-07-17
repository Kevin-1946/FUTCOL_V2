import axios from "../axios.js";

// Obtener todos los equipos
export const getEquipos = () => axios.get("/equipos");

// Crear un equipo
export const createEquipo = (data) => axios.post("/equipos", data);

// Obtener equipo por ID
export const getEquipoById = (id) => axios.get(`/equipos/${id}`);

// Actualizar un equipo
export const updateEquipo = (id, data) => axios.put(`/equipos/${id}`, data);

// Eliminar un equipo
export const deleteEquipo = (id) => axios.delete(`/equipos/${id}`);

// 🔧 NUEVA FUNCIÓN: Obtener mi equipo
export const getMiEquipo = () => axios.get("/mi-equipo");

// Agregar jugador al equipo
export const agregarJugador = (equipoId, jugadorId) => 
  axios.post(`/equipos/${equipoId}/jugadores`, { jugador_id: jugadorId });

// Remover jugador del equipo
export const removerJugador = (equipoId, jugadorId) => 
  axios.delete(`/equipos/${equipoId}/jugadores/${jugadorId}`);

// Obtener equipos por torneo
export const getEquiposPorTorneo = (torneoId) => 
  axios.get(`/equipos/torneo/${torneoId}`);

// Registrar equipo completo
export const registrarEquipoCompleto = (data) => 
  axios.post("/equipos/registrar-completo", data);