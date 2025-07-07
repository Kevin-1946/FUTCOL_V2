  import { Routes, Route } from "react-router-dom";

  // Layout
  import Layout from "./componentes/Layout/Layout";

  // Publicas
  import RegistrarEquipo from './paginas/publico/RegistrarEquipo';

  // Páginas de administracion
  import Futcol from "./paginas/administracion/futcol";
  import Login from "./paginas/administracion/Login.jsx";
  import Nosotros from "./paginas/administracion/sobre_nosotros";
  import Unauthorized from "./paginas/administracion/Unauthorized";

  // Paginas de Participantes
  import Amonestaciones from "./paginas/participantes/Amonestaciones";
  import Encuentros from "./paginas/participantes/Ecuentros";
  import Equipos from "./paginas/participantes/Equipos";
  import EstadisticaEquipo from "./paginas/participantes/EstadisticaEquipo";
  import Goles from "./paginas/participantes/Goles";
  import Inscripciones from "./paginas/participantes/Inscripciones";
  import Juez from "./paginas/participantes/Juez";
  import Jugador from "./paginas/participantes/Jugador";
  import Recibo from "./paginas/participantes/Recibo";
  import Sede from "./paginas/participantes/Sede";
  import Torneos from "./paginas/participantes/Torneos";

  // Paginas de Cruds
  import AmonestacionCrud from "./paginas/cruds/AmonestacionCrud";
  import EncuentrosCrud from "./paginas/cruds/EncuentrosCrud";
  import EquiposCrud from "./paginas/cruds/EquiposCrud";
  import EstadisticaEquipoCrud from "./paginas/cruds/EstadisticaEquipoCrud";
  import GolesCrud from "./paginas/cruds/GolesCrud";
  import InscripcionesCrud from "./paginas/cruds/InscripcionesCrud";
  import JuezCrud from "./paginas/cruds/JuezCrud";
  import JugadorCrud from "./paginas/cruds/JugadorCrud";
  import LoginUsuarioCrud from "./paginas/cruds/LoginUsuarioCrud";
  import ReciboCrud from "./paginas/cruds/ReciboCrud";
  import ResetContrasenaCrud from "./paginas/cruds/ResetContrasenaCrud";
  import SedeCrud from "./paginas/cruds/SedeCrud";
  import TorneosCrud from "./paginas/cruds/TorneosCrud";


  // Componentes
  import ProtectedRoute from "./componentes/ProtectedRoute.jsx";

  // Contexto de autenticación
  import { useAuth } from "./contexts/AuthContext.jsx";


  function App() {
    const { user, loading } = useAuth();

    if (loading) {
      return <div>Cargando...</div>;
    }

    return (
        <Routes>
      <Route path="/" element={<Layout />}>

        {/* Página de inicio y públicas */}
        <Route index element={<Futcol />} />
        <Route path="/inscripcion" element={<RegistrarEquipo />} />
        <Route path="login" element={<Login />} />
        <Route path="participantes/login" element={<Login />} />
        <Route path="reset_contrasena/:token" element={<ResetContrasenaCrud />} />
        <Route path="informacion/sobre_nosotros" element={<Nosotros />} />
        <Route path="/errores/unauthorized" element={<Unauthorized />} />

        {/* PARTICIPANTE y CAPITÁN: Vistas solo lectura */}
        <Route path="torneos" element={<Torneos />} />
        
        <Route
          path="torneos/amonestacion"
          element={<ProtectedRoute allowedRoles={["participante", "capitan", "administrador"]}><Amonestaciones /></ProtectedRoute>}
        />
        <Route
          path="torneos/encuentros"
          element={<ProtectedRoute allowedRoles={["participante", "capitan", "administrador"]}><Encuentros /></ProtectedRoute>}
        />
        <Route
          path="torneos/equipos"
          element={<ProtectedRoute allowedRoles={["participante", "capitan", "administrador"]}><Equipos /></ProtectedRoute>}
        />
        <Route
          path="torneos/goles"
          element={<ProtectedRoute allowedRoles={["participante", "capitan", "administrador"]}><Goles /></ProtectedRoute>}
        />
        <Route
          path="torneos/sede"
          element={<ProtectedRoute allowedRoles={["participante", "capitan", "administrador"]}><Sede /></ProtectedRoute>}
        />
        <Route
          path="torneos/estadisticas"
          element={<ProtectedRoute allowedRoles={["participante", "capitan", "administrador"]}><EstadisticaEquipo /></ProtectedRoute>}
        />

        {/* CAPITÁN: gestión de su equipo e inscripción */}
        <Route
          path="capitan/inscripciones"
          element={<ProtectedRoute allowedRoles={["capitan"]}><Inscripciones /></ProtectedRoute>}
        />
        <Route
          path="capitan/jugador"
          element={<ProtectedRoute allowedRoles={["capitan"]}><Jugador /></ProtectedRoute>}
        />
        <Route
          path="capitan/recibo"
          element={<ProtectedRoute allowedRoles={["capitan"]}><Recibo /></ProtectedRoute>}
        />

        {/* CRUDS: solo ADMINISTRADOR */}
        <Route
          path="admin/amonestaciones"
          element={<ProtectedRoute allowedRoles={["administrador"]}><AmonestacionCrud /></ProtectedRoute>}
        />
        <Route
          path="admin/encuentros"
          element={<ProtectedRoute allowedRoles={["administrador"]}><EncuentrosCrud /></ProtectedRoute>}
        />
        <Route
          path="admin/equipos"
          element={<ProtectedRoute allowedRoles={["administrador"]}><EquiposCrud /></ProtectedRoute>}
        />
        <Route
          path="admin/goles"
          element={<ProtectedRoute allowedRoles={["administrador"]}><GolesCrud /></ProtectedRoute>}
        />
        <Route
          path="admin/jueces"
          element={<ProtectedRoute allowedRoles={["administrador"]}><JuezCrud /></ProtectedRoute>}
        />
        <Route
          path="admin/jugadores"
          element={<ProtectedRoute allowedRoles={["administrador"]}><JugadorCrud /></ProtectedRoute>}
        />
        <Route
          path="admin/estadisticas"
          element={<ProtectedRoute allowedRoles={["administrador"]}><EstadisticaEquipoCrud /></ProtectedRoute>}
        />
        <Route
          path="admin/sedes"
          element={<ProtectedRoute allowedRoles={["administrador"]}><SedeCrud /></ProtectedRoute>}
        />
        <Route
          path="admin/torneos"
          element={<ProtectedRoute allowedRoles={["administrador"]}><TorneosCrud /></ProtectedRoute>}
        />
        <Route
          path="admin/inscripciones"
          element={<ProtectedRoute allowedRoles={["administrador"]}><InscripcionesCrud /></ProtectedRoute>}
        />
        <Route
          path="admin/recibos"
          element={<ProtectedRoute allowedRoles={["administrador"]}><ReciboCrud /></ProtectedRoute>}
        />
        <Route
          path="admin/usuarios"
          element={<ProtectedRoute allowedRoles={["administrador"]}><LoginUsuarioCrud /></ProtectedRoute>}
        />
        <Route
          path="admin/reset"
          element={<ProtectedRoute allowedRoles={["administrador"]}><ResetContrasenaCrud /></ProtectedRoute>}
        />
      </Route>
    </Routes>

    );
  }

  export default App;