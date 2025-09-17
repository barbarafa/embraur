<?php

namespace App\Http\Controllers\Aluno;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StudentProfileController extends Controller
{
    public function index(Request $request)
    {
        $aluno = $request->user('aluno');
        return view('aluno.perfil', compact('aluno'));
    }
}
