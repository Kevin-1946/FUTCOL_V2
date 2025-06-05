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
import LoginUsuario from "./paginas/participantes/loginUsuario";
import ResetContrasena from "./paginas/participantes/ResetContrasena";
import Suscripcion from "./paginas/participantes/suscripcion";

// Administración
import EstadisticaEquipo from "./paginas/administracion/estadisticaEquipo.jsx";
import Recibo from "./paginas/administracion/recibo";

// Componentes
import RutaProtegida from "./componentes/Proteccion/RutaProtegida.jsx";

// Usuario simulado
const usuarioActual = {
  nombre: "Juan",
  rol: "administrador" // puede ser "capitan", "usuario", o "administrador"
};

function App() {
  return (
    <Routes>
      {/* TODAS las rutas usan Layout, incluyendo la de inicio */}
      <Route path="/" element={<Layout />}>
        {/* Página de inicio como index */}
        <Route index element={<Futcol />} />

        {/* Páginas públicas */}
        <Route path="informacion/sobre_nosotros" element={<Nosotros />} />
        <Route path="login_participante" element={<LoginUsuario />} />
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
            <RutaProtegida rolesPermitidos={["capitan"]} usuario={usuarioActual}>
              <Inscripciones />
            </RutaProtegida>
          }
        />

        {/* Administración protegida */}
        <Route
          path="administracion/estadisticaEquipo"
          element={
            <RutaProtegida rolesPermitidos={["administrador"]} usuario={usuarioActual}>
              <EstadisticaEquipo />
            </RutaProtegida>
          }
        />
        <Route
          path="participantes/juez"
          element={
            <RutaProtegida rolesPermitidos={["administrador"]} usuario={usuarioActual}>
              <Juez />
            </RutaProtegida>
          }
        />
        <Route
          path="participantes/jugador"
          element={
            <RutaProtegida rolesPermitidos={["administrador"]} usuario={usuarioActual}>
              <Jugador />
            </RutaProtegida>
          }
        />
        <Route
          path="administracion/recibo"
          element={
            <RutaProtegida rolesPermitidos={["administrador"]} usuario={usuarioActual}>
              <Recibo />
            </RutaProtegida>
          }
        />
      </Route>
    </Routes>
  );
}

export default App;