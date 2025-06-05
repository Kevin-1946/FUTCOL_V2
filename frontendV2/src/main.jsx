import React from 'react';
import ReactDOM from 'react-dom/client';
import App from './App';
import './index.css'  // ✅ Importando el CSS
import { BrowserRouter } from "react-router-dom";  // ✅ Importando Bootstrap

ReactDOM.createRoot(document.getElementById('root')).render(
  <React.StrictMode>
    <BrowserRouter>   {/* ✅ Envuelve App con BrowserRouter */}
      <App />
    </BrowserRouter>
  </React.StrictMode>
);
