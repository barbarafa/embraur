<?php

namespace App\Http\Controllers;

use App\Models\Prova;
use Illuminate\Http\Request;

class ProvaController extends Controller
{
    public function index(Request $request)
    {
        $cursoId = $request->get('curso_id');
        $q = Prova::query()->withCount('questions');
        if ($cursoId) $q->where('curso_id', $cursoId);
        return $q->orderBy('id')->paginate(50);
    }

    public function show(Prova $prova)
    {
        return $prova->load('questions');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'curso_id' => 'required|exists:cursos,id',
            'titulo' => 'required|string|max:255',
            'nota_minima' => 'required|integer|min:0|max:100',
            'tempo_limite_min' => 'nullable|integer|min:0',
            'tentativas_permitidas' => 'integer|min:0',
        ]);
        $p = Prova::create($data);
        return response()->json($p, 201);
    }

    public function update(Request $request, Prova $prova)
    {
        $data = $request->validate([
            'curso_id' => 'sometimes|required|exists:cursos,id',
            'titulo' => 'sometimes|required|string|max:255',
            'nota_minima' => 'sometimes|required|integer|min:0|max:100',
            'tempo_limite_min' => 'nullable|integer|min:0',
            'tentativas_permitidas' => 'integer|min:0',
        ]);
        $prova->update($data);
        return $prova->fresh('questions');
    }

    public function destroy(Prova $prova)
    {
        $prova->delete();
        return response()->noContent();
    }
}
