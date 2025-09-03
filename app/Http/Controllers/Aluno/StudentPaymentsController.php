<?php

namespace App\Http\Controllers\Aluno;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StudentPaymentsController extends Controller
{
    public function index(Request $request)
    {
        $aluno = $request->user('aluno');
        $pagamentos = []; // preencha com suas faturas/boletos etc.

        return view('aluno.pagamentos', compact('aluno','pagamentos'));
    }
}

