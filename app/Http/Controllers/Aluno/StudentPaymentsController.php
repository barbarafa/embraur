<?php

namespace App\Http\Controllers\Aluno;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Models\User;
use Illuminate\Http\Request;

class StudentPaymentsController extends Controller
{


    public function index(Request $rq)
    {
        $alunoId = auth('aluno')->id() ?? $rq->session()->get('aluno_id');

        // itens + curso para evitar N+1
        $pedidos = Pedido::with(['itens.curso'])
            ->where('aluno_id', $alunoId)
            ->orderByDesc('data_pedido')
            ->get();

        $aluno = User::where('id', $alunoId)->first();

        return view('aluno.pagamentos', compact('pedidos','aluno'));
    }
}

