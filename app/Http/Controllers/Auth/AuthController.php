<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // ====== PROFESSOR ======
    public function showLogin()
    {
        return view('auth.professor-login');
    }

    public function loginProfessor(Request $request)
    {
        $data = $request->validate([
            'email'    => ['required','email'],
            'password' => ['required'],
        ]);

        $prof = User::where('email', $data['email'])
            ->where('tipo_usuario', 'professor')
            ->where('status', 'ativo')
            ->first();

        if ($prof && Hash::check($data['password'], $prof->password)) {
            $request->session()->regenerate();
            $request->session()->put('prof_id', $prof->id);
            $request->session()->put('prof_nome', $prof->nome_completo);
            return redirect()->route('prof.dashboard');
        }

        return back()->withErrors(['email'=>'Credenciais inválidas ou usuário inativo.']);
    }

    public function logout(Request $request)
    {
        $request->session()->forget(['prof_id','prof_nome']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('site.home');
    }
}
