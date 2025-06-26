import React from 'react';
import ReactDOM from 'react-dom/client';
import App from './App';
import './index.css';  // ✅ Importando el CSS
import { BrowserRouter } from "react-router-dom";  // ✅ Importando Router
import { AuthProvider } from './contexts/AuthContext';  // Asegúrate de importar AuthProvider

ReactDOM.createRoot(document.getElementById('root')).render(
  <React.StrictMode>
    <BrowserRouter>   
      <AuthProvider>   {/* ✅ Envuelve App con AuthProvider */}
        <App />
      </AuthProvider>
    </BrowserRouter>
  </React.StrictMode>
);