import React from 'react';
import './contrasena_reset.css';

const ResetPasswordEmail = ({ resetLink }) => {
  return (
    <div className="page-container">
    <div className="email-container">
      <h1 className="email-title">Restablecer Contraseña</h1>
      <p className="email-text">Hemos recibido una solicitud para restablecer tu contraseña.</p>
      <p className="email-text">Haz clic en el siguiente enlace para continuar:</p>
      <a href={resetLink} className="email-link" target="_blank" rel="noopener noreferrer">
        Restablecer Contraseña
      </a>
      <p className="email-text">Si no solicitaste este cambio, puedes ignorar este mensaje.</p>
      <p className="email-text">El enlace expirará en 1 hora.</p>
    </div>
  </div>
  );
};

export default Contrasena_reset;