<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Professor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfessorAuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.professor-login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Ver dados que chegam do formulário


        $p = Professor::where('email', $data['email'])->first();

        // Ver se encontrou o professor no banco


        if ($p && Hash::check($data['password'], $p->password)) {
            $request->session()->put('prof_id', $p->id);
            $request->session()->put('prof_nome', $p->nome);
            return redirect()->route('prof.cursos.index');

        }

        return back()->withErrors([
            'email' => 'Credenciais inválidas.'
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        $request->session()->forget(['prof_id', 'prof_nome']);
        return redirect()->route('prof.login');
    }
}
