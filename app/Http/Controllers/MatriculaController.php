<?php

namespace App\Http\Controllers;

use App\Models\Matricula;
use Illuminate\Http\Request;

class MatriculaController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->get('user_id');
        $cursoId = $request->get('curso_id');

        $q = Matricula::query()->with(['course','modality','user']);
        if ($userId) $q->where('user_id', $userId);
        if ($cursoId) $q->where('curso_id', $cursoId);

        return $q->orderByDesc('id')->paginate(50);
    }

    public function show(Matricula $matricula)
    {
        return $matricula->load(['course.modules.lessons','modality','user']);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'curso_id' => 'required|exists:cursos,id',
            'modalidade_id' => 'nullable|exists:modalidades,id',
            'pedido_id' => 'nullable|exists:pedidos,id',
            'status' => 'nullable|in:ativa,concluida,bloqueada',
        ]);

        $m = Matricula::create($data + ['progresso' => 0]);
        return response()->json($m, 201);
    }

    public function update(Request $request, Matricula $matricula)
    {
        $data = $request->validate([
            'status' => 'in:ativa,concluida,bloqueada',
            'progresso' => 'nullable|integer|min:0|max:100',
            'concluida_em' => 'nullable|date',
        ]);

        $matricula->update($data);
        return $matricula->fresh(['course','user']);
    }

    public function destroy(Matricula $matricula)
    {
        $matricula->delete();
        return response()->noContent();
    }
}
