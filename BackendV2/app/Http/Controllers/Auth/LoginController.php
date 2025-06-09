<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\LoginUsuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /**
     * Mostrar el formulario de login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Procesar el login
     */
    public function login(Request $request)
    {
        // Validar los datos del formulario
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required' => 'El email es obligatorio',
            'email.email' => 'Debe ser un email válido',
            'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Buscar el usuario por email
        $usuario = LoginUsuario::where('email', $request->email)->first();

        // Verificar si el usuario existe y las credenciales son correctas
        if ($usuario && Hash::check($request->password, $usuario->password)) {
            
            // Verificar si el usuario está activo
            if (!$usuario->estaActivo()) {
                return redirect()->back()
                    ->withErrors(['email' => 'Tu cuenta está desactivada. Contacta al administrador.'])
                    ->withInput();
            }

            // Iniciar sesión
            Auth::login($usuario, $request->filled('remember'));

            // Redireccionar según el rol
            return $this->redirectToRole($usuario);

        } else {
            // Credenciales incorrectas
            return redirect()->back()
                ->withErrors(['email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.'])
                ->withInput();
        }
    }

    /**
     * Redireccionar según el rol del usuario
     */
    private function redirectToRole($usuario)
    {
        switch ($usuario->rol) {
            case 'admin':
                return redirect()->route('dashboard')->with('success', '¡Bienvenido Administrador!');
            case 'capitan':
                return redirect()->route('dashboard')->with('success', '¡Bienvenido Capitán!');
            default:
                return redirect()->route('dashboard')->with('success', '¡Bienvenido!');
        }
    }

    /**
     * Cerrar sesión
     */
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Has cerrado sesión correctamente.');
    }

    /**
     * Mostrar el dashboard
     */
    public function dashboard()
    {
        $usuario = Auth::user();
        
        if (!$usuario) {
            return redirect()->route('login');
        }

        return view('dashboard', compact('usuario'));
    }
}