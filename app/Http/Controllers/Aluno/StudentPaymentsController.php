<?php

namespace App\Http\Controllers\Aluno;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StudentPaymentsController extends Controller
{
    public function index(Request $rq)
    {
        $alunoId = auth('aluno')->id() ?? $rq->session()->get('aluno_id');
        $pagamentos = \App\Models\Pagamentos::with('matricula.curso')
            ->where('aluno_id',$alunoId)->latest()->get();

        return view('aluno.pagamentos', compact('pagamentos'));
    }
}

