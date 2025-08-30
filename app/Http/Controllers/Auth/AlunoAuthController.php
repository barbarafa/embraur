<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Aluno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AlunoAuthController extends Controller
{
    public function showLoginForm(){ return view('auth.aluno-login'); }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email'=>['required','email'],
            'password'=>['required'],
            'remember'=>['nullable']
        ]);

        $aluno = Aluno::where('email',$data['email'])->first();
        if ($aluno && Hash::check($data['password'], $aluno->password)) {
            $request->session()->put('aluno_id', $aluno->id);
            $request->session()->put('aluno_nome', $aluno->nome);
            if ($request->boolean('remember')) $request->session()->put('aluno_remember', true);
            return redirect()->intended(route('aluno.dashboard'));
        }
        return back()->withErrors(['email'=>'Credenciais inválidas.'])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        $request->session()->forget(['aluno_id','aluno_nome','aluno_remember']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('site.home');
    }

    public function showRegisterForm(){ return view('auth.aluno-register'); }

    public function register(Request $request)
    {
        $data = $request->validate([
            'nome'=>['required','string','max:120'],
            'email'=>['required','email','unique:alunos,email'],
            'password'=>['required','min:6','confirmed'],
        ]);

        $aluno = Aluno::create([
            'nome'=>$data['nome'],
            'email'=>$data['email'],
            'password'=>Hash::make($data['password']),
        ]);

        $request->session()->put('aluno_id', $aluno->id);
        $request->session()->put('aluno_nome', $aluno->nome);
        return redirect()->route('aluno.dashboard');
    }
}
