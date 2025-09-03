<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function loginAluno(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'senha' => 'required'
        ]);

        $user = User::where('email', $credentials['email'])
            ->where('tipo_usuario', 'aluno')
            ->first();

        if ($user && Hash::check($credentials['senha'], $user->senha)) {
            Auth::login($user);
            return response()->json([
                'success' => true,
                'user' => $user,
                'redirect' => '/student/dashboard'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Credenciais inválidas'
        ], 401);
    }

    public function showLogin()
    {
        return view('auth.professor-login');
    }

    public function loginProfessor(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'senha' => 'required'
        ]);

        $user = User::where('email', $credentials['email'])
            ->where('tipo_usuario', 'professor')
            ->first();

        if ($user && Hash::check($credentials['senha'], $user->senha)) {
            Auth::login($user);
            return response()->json([
                'success' => true,
                'user' => $user,
                'redirect' => '/teacher/dashboard'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Credenciais inválidas'
        ], 401);
    }

    public function registroAluno(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'senha' => 'required|min:6',
            'cpf' => 'required|unique:users'
        ]);

        $user = User::create([
            'nome' => $validated['nome'],
            'email' => $validated['email'],
            'senha' => Hash::make($validated['senha']),
            'cpf' => $validated['cpf'],
            'tipo_usuario' => 'aluno',
            'data_cadastro' => now()
        ]);

        Auth::login($user);

        return response()->json([
            'success' => true,
            'user' => $user,
            'redirect' => '/student/dashboard'
        ]);
    }

    public function logout(Request $request)
    {
        return redirect()->route('prof.login');
    }
}
