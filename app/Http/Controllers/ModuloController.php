<?php

namespace App\Http\Controllers;

use App\Models\Modulo;
use Illuminate\Http\Request;

class ModuloController extends Controller
{
    public function index(Request $request)
    {
        $cursoId = $request->get('curso_id');
        $q = Modulo::query()->with('course');
        if ($cursoId) $q->where('curso_id', $cursoId);
        return $q->orderBy('ordem')->paginate(100);
    }

    public function show(Modulo $modulo)
    {
        return $modulo->load(['course','lessons']);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'curso_id' => 'required|exists:cursos,id',
            'titulo' => 'required|string|max:255',
            'ordem' => 'nullable|integer|min:0',
        ]);
        $m = Modulo::create($data);
        return response()->json($m, 201);
    }

    public function update(Request $request, Modulo $modulo)
    {
        $data = $request->validate([
            'curso_id' => 'sometimes|required|exists:cursos,id',
            'titulo' => 'sometimes|required|string|max:255',
            'ordem' => 'nullable|integer|min:0',
        ]);
        $modulo->update($data);
        return $modulo->fresh(['course','lessons']);
    }

    public function destroy(Modulo $modulo)
    {
        $modulo->delete();
        return response()->noContent();
    }
}
