<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Aluno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AlunoRegisterController extends Controller
{
public function show() {
return view('auth.aluno-register');
}

public function store(Request $request) {
$data = $request->validate([
'nome' => ['required','string','max:120'],
'email' => ['required','email','max:255','unique:alunos,email'],
'password' => ['required','confirmed','min:6'],
]);

$aluno = Aluno::create([
'nome' => $data['nome'],
'email' => $data['email'],
'password' => Hash::make($data['password']),
]);

Auth::guard('aluno')->login($aluno);
$request->session()->regenerate();

return redirect()->route('aluno.dashboard');
}
}
