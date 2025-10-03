<?php

namespace App\Http\Controllers\Aluno;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class StudentProfileController extends Controller
{
    public function index(Request $request)
    {
        $alunoId = $request->session()->get('aluno_id');
        $aluno = User::find($alunoId);
        return view('aluno.perfil', compact('aluno'));
    }
}
