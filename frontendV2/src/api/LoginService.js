import axios from "../axios.js";

class LoginService {
    static async login(credentials) {
        try {
            console.log('🔐 Iniciando login con:', credentials); // Debug
            const response = await axios.post('/login', credentials);
            
            console.log('📋 Respuesta del login:', response.data); // Debug
            
            // Extraer el token de la respuesta (según tu backend)
            const { access_token, user } = response.data;
            
            if (access_token) {
                // Guardar el token en localStorage
                localStorage.setItem('token', access_token);
                console.log('✅ Token guardado exitosamente:', access_token); // Debug
                
                // Guardar datos del usuario
                if (user) {
                    localStorage.setItem('user', JSON.stringify(user));
                    console.log('👤 Usuario guardado:', user); // Debug
                }
            } else {
                console.error('❌ No se recibió token en la respuesta'); // Debug
            }
            
            return response.data;
        } catch (error) {
            console.error('💥 Error en login:', error); // Debug
            throw error;
        }
    }
    
    static async logout() {
        try {
            // Opcional: notificar al backend del logout
            await axios.post('/logout');
        } catch (error) {
            console.log('Error al hacer logout en backend:', error);
        } finally {
            // Limpiar localStorage
            localStorage.removeItem('token');
            localStorage.removeItem('user');
            console.log('🚪 Logout completado, tokens eliminados'); // Debug
        }
    }
    
    static getToken() {
        return localStorage.getItem('token');
    }
    
    static getUser() {
        const user = localStorage.getItem('user');
        return user ? JSON.parse(user) : null;
    }
    
    static isAuthenticated() {
        return !!this.getToken();
    }
}

export default LoginService;