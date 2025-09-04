<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class AlunoAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.aluno-login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        $aluno = User::where('email', $data['email'])
            ->where('tipo_usuario', 'aluno')
            ->where('status', 'ativo')
            ->first();

        if ($aluno && Hash::check($data['password'], $aluno->password)) {
            $request->session()->regenerate();
            // define sessão simples usada pelas views/rotas do aluno
            $request->session()->put('aluno_id', $aluno->id);
            $request->session()->put('aluno_nome', $aluno->nome_completo);
            return redirect()->route('aluno.dashboard');
        }

        return back()->withErrors(['email' => 'Credenciais inválidas ou usuário inativo.']);
    }

    public function logout(Request $request)
    {
        $request->session()->forget(['aluno_id', 'aluno_nome']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('site.home');
    }

    public function showRegisterForm()
    {
        return view('auth.aluno-register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'nome' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:6'],
        ]);

        $aluno = User::create([
            'nome_completo' => $data['nome'],
            'email' => $data['email'],
            'password' => $data['password'], // será hasheada pelo mutator do Model
            'tipo_usuario' => 'aluno',
            'status' => 'ativo',
        ]);

        $request->session()->regenerate();
        $request->session()->put('aluno_id', $aluno->id);
        $request->session()->put('aluno_nome', $aluno->nome_completo);

        return redirect()->route('aluno.dashboard')->with('success', 'Cadastro realizado com sucesso!');
    }
}
