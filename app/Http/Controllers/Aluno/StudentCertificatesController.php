<?php

namespace App\Http\Controllers\Aluno;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StudentCertificatesController extends Controller
{
    public function index(Request $request)
    {
        $aluno = $request->user('aluno');

        $certificados = [
            [
                'curso' => 'NR35 - Trabalho em Altura',
                'emitido_em' => '2024-07-12',
                'download' => '#', // rota de download do seu certificado
                'visualizar' => '#',
            ],
        ];

        return view('aluno.certificados', compact('aluno','certificados'));
    }
}

