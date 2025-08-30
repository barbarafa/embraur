<?php

namespace App\Http\Controllers\Aluno;

use App\Http\Controllers\Controller;
use App\Models\Matricula;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $alunoId = $request->session()->get('aluno_id');
        $matriculas = Matricula::with('curso.categoria')
            ->where('aluno_id',$alunoId)
            ->latest()->get();

        return view('aluno.dashboard', compact('matriculas'));
    }
}
