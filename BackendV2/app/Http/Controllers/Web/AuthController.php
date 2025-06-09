<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // 📄 Mostrar formulario de login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // 🔐 Procesar login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        if (Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
            $request->session()->regenerate();
            
            // Redirigir según el rol
            return $this->redirectToDashboard();
        }

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas son incorrectas.',
        ])->onlyInput('email');
    }

    // 🚪 Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('message', 'Sesión cerrada correctamente');
    }

    // 🎯 Redirigir según rol
    private function redirectToDashboard()
    {
        $user = Auth::user();
        
        switch ($user->role_id) {
            case 1: // Admin único
                return redirect()->route('admin.dashboard');
            case 2: // Capitán
                return redirect()->route('capitan.dashboard');
            case 3: // Participante
                return redirect()->route('dashboard');
            default:
                return redirect()->route('dashboard');
        }
    }

    // 📊 Dashboard general
    public function dashboard()
    {
        $user = Auth::user();
        return view('dashboard', compact('user'));
    }

    // 👑 Dashboard Admin
    public function adminDashboard()
    {
        $user = Auth::user();
        
        // Verificar que sea admin
        if ($user->role_id !== 1) {
            abort(403, 'Acceso denegado');
        }

        // Estadísticas para admin
        $stats = [
            'total_users' => User::count(),
            'total_equipos' => \App\Models\Equipo::count(),
            'total_jugadores' => \App\Models\Jugador::count(),
        ];

        return view('admin.dashboard', compact('user', 'stats'));
    }

    // 🎮 Dashboard Capitán
    public function capitanDashboard()
    {
        $user = Auth::user();
        
        // Verificar que sea capitán
        if ($user->role_id !== 2) {
            abort(403, 'Acceso denegado');
        }

        // Datos del equipo del capitán
        $equipo = $user->equipo()->with(['jugadores', 'capitan'])->first();

        return view('capitan.dashboard', compact('user', 'equipo'));
    }
}