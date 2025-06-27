import { Routes, Route } from "react-router-dom";

// Layout
import Layout from "./componentes/Layout/Layout";

// Páginas de inicio e información
import Futcol from "./paginas/inicio/futcol";
import Nosotros from "./paginas/informacion/sobre_nosotros";

// Torneos
import Torneos from "./paginas/torneos/torneos";
import Amonestacion from "./paginas/torneos/amonestacion";
import Encuentros from "./paginas/torneos/encuentros";
import Equipos from "./paginas/torneos/equipos";
import Goles from "./paginas/torneos/goles";
import Sede from "./paginas/torneos/sede";

// Participantes
import Jugador from "./paginas/participantes/jugador";
import Juez from "./paginas/participantes/juez";
import Inscripciones from "./paginas/participantes/Inscripciones";
import Login from "./paginas/participantes/Login.jsx";
import ResetContrasena from "./paginas/participantes/ResetContrasena";
import Suscripcion from "./paginas/participantes/suscripcion";

// Administración
import EstadisticaEquipo from "./paginas/administracion/estadisticaEquipo.jsx";
import Recibo from "./paginas/administracion/recibo";
import LoginUsuariosCrud from "./componentes/LoginUsuarioCrud/LoginUsuarioCrud.jsx"; // ✅ CRUD de inicios de sesión

// Componentes
import ProtectedRoute from "./componentes/ProtectedRoute.jsx";

// Contexto de autenticación
import { useAuth } from "./contexts/AuthContext.jsx";

// Errores
import Unauthorized from "./paginas/errores/Unauthorized";

function App() {
  const { user, loading } = useAuth();

  if (loading) {
    return <div>Cargando...</div>;
  }

  return (
    <Routes>
      <Route path="/" element={<Layout />}>
        {/* Página de inicio */}
        <Route index element={<Futcol />} />

        {/* Páginas públicas */}
        <Route path="informacion/sobre_nosotros" element={<Nosotros />} />
        <Route path="login" element={<Login />} />
        <Route path="participantes/login" element={<Login />} />
        <Route path="suscribirse" element={<Suscripcion />} />
        <Route path="Reset_contrasena/:token" element={<ResetContrasena />} />

        {/* Torneos */}
        <Route path="torneos" element={<Torneos />} />
        <Route path="torneos/amonestacion" element={<Amonestacion />} />
        <Route path="torneos/encuentros" element={<Encuentros />} />
        <Route path="torneos/equipos" element={<Equipos />} />
        <Route path="torneos/goles" element={<Goles />} />
        <Route path="torneos/sede" element={<Sede />} />

        {/* Participantes protegidos */}
        <Route
            path="participantes/Inscripciones"
            element={
              <ProtectedRoute allowedRoles={["Capitan"]}>
                <Inscripciones />
              </ProtectedRoute>
            }
          />
        <Route
          path="participantes/juez"
          element={
            <ProtectedRoute rolesPermitidos={["Administrador"]} usuario={user}>
              <Juez />
            </ProtectedRoute>
          }
        />
        <Route
          path="participantes/jugador"
          element={
            <ProtectedRoute rolesPermitidos={["Administrador"]} usuario={user}>
              <Jugador />
            </ProtectedRoute>
          }
        />

        {/* Administración protegida */}
        <Route
          path="administracion/estadisticaEquipo"
          element={
            <ProtectedRoute rolesPermitidos={["Administrador"]} usuario={user}>
              <EstadisticaEquipo />
            </ProtectedRoute>
          }
        />
        <Route
          path="administracion/recibo"
          element={
            <ProtectedRoute rolesPermitidos={["Administrador"]} usuario={user}>
              <Recibo />
            </ProtectedRoute>
          }
        />
        <Route
          path="administracion/login-crud"
          element={
            <ProtectedRoute rolesPermitidos={["Administrador"]} usuario={user}>
              <LoginUsuariosCrud />
            </ProtectedRoute>
          }
        />

        {/* Página de acceso denegado */}
        <Route path="/errores/unauthorized" element={<Unauthorized />} />
      </Route>
    </Routes>
  );
}

export default App;