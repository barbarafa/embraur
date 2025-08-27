<?php

namespace App\Http\Controllers;

use App\Models\MensagemSuporte;
use Illuminate\Http\Request;

class MensagemSuporteController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->get('user_id');
        $cursoId = $request->get('curso_id');
        $q = MensagemSuporte::query();

        if ($userId) $q->where('user_id', $userId);
        if ($cursoId) $q->where('curso_id', $cursoId);

        return $q->orderByDesc('id')->paginate(50);
    }

    public function show(MensagemSuporte $mensagemSuporte)
    {
        return $mensagemSuporte;
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'curso_id' => 'nullable|exists:cursos,id',
            'aula_id' => 'nullable|exists:aulas,id',
            'assunto' => 'required|string|max:255',
            'mensagem' => 'required|string',
        ]);

        $m = MensagemSuporte::create($data);
        return response()->json($m, 201);
    }

    public function update(Request $request, MensagemSuporte $mensagemSuporte)
    {
        $data = $request->validate([
            'status' => 'required|in:aberta,respondida,fechada',
        ]);
        $mensagemSuporte->update($data);
        return $mensagemSuporte;
    }

    public function destroy(MensagemSuporte $mensagemSuporte)
    {
        $mensagemSuporte->delete();
        return response()->noContent();
    }
}
