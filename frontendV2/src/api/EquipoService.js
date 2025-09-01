import axios from "../axios.js";

// ===== SERVICIOS DE EQUIPOS =====

// Obtener todos los equipos
export const getEquipos = () => axios.get("/equipos");

// Crear un equipo (solo administrador)
export const createEquipo = (data) => axios.post("/equipos", data);

// Obtener equipo por ID
export const getEquipoById = (id) => axios.get(`/equipos/${id}`);

// Actualizar un equipo (solo administrador)
export const updateEquipo = (id, data) => axios.put(`/equipos/${id}`, data);

// Eliminar un equipo (solo administrador)
export const deleteEquipo = (id) => axios.delete(`/equipos/${id}`);

// Obtener mi equipo (para capitanes)
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

// ===== SERVICIOS ADICIONALES PARA SELECTS =====

// Obtener todos los torneos (público)
export const getTorneos = () => axios.get("/torneos");

// Obtener torneo por ID (público)
export const getTorneoById = (id) => axios.get(`/torneos/${id}`);

// Obtener todos los jugadores (público)
export const getJugadores = () => axios.get("/jugadores");

// Obtener jugador por ID (público)
export const getJugadorById = (id) => axios.get(`/jugadores/${id}`);

// Obtener jugadores sin equipo
export const getJugadoresSinEquipo = () => axios.get("/jugadores-sin-equipo");

// Buscar jugadores
export const buscarJugadores = (query) => 
  axios.get(`/buscar-jugadores?query=${encodeURIComponent(query)}`);

// ===== SERVICIOS DE ADMINISTRADOR =====

// CRUD Torneos (solo administrador)
export const createTorneo = (data) => axios.post("/torneos", data);
export const updateTorneo = (id, data) => axios.put(`/torneos/${id}`, data);
export const deleteTorneo = (id) => axios.delete(`/torneos/${id}`);

// CRUD Jugadores (solo administrador)
export const createJugador = (data) => axios.post("/jugadores", data);
export const updateJugador = (id, data) => axios.put(`/jugadores/${id}`, data);
export const deleteJugador = (id) => axios.delete(`/jugadores/${id}`);

// Obtener sedes (público)
export const getSedes = () => axios.get("/sedes");

// CRUD Sedes (solo administrador)
export const createSede = (data) => axios.post("/sedes", data);
export const updateSede = (id, data) => axios.put(`/sedes/${id}`, data);
export const deleteSede = (id) => axios.delete(`/sedes/${id}`);

// ===== UTILIDADES =====

// Función helper para manejar errores de API
export const handleApiError = (error) => {
  if (error.response) {
    // El servidor respondió con un código de error
    const { status, data } = error.response;
    switch (status) {
      case 401:
        return "No autorizado. Por favor, inicia sesión nuevamente.";
      case 403:
        return "No tienes permisos para realizar esta acción.";
      case 404:
        return "Recurso no encontrado.";
      case 422:
        return data.message || "Datos de entrada inválidos.";
      case 500:
        return "Error interno del servidor. Intenta más tarde.";
      default:
        return data.message || "Error desconocido.";
    }
  } else if (error.request) {
    // La petición fue hecha pero no se recibió respuesta
    return "Error de conexión. Verifica tu conexión a internet.";
  } else {
    // Algo pasó al configurar la petición
    return "Error inesperado. Intenta nuevamente.";
  }
};

// Función helper para validar datos de equipo
export const validateEquipoData = (data) => {
  const errors = [];
  
  if (!data.nombre || data.nombre.trim() === '') {
    errors.push('El nombre del equipo es obligatorio');
  }
  
  if (!data.torneo_id) {
    errors.push('Debe seleccionar un torneo');
  }
  
  if (data.nombre && data.nombre.length > 100) {
    errors.push('El nombre del equipo no puede exceder 100 caracteres');
  }
  
  return errors;
};

export default axios;