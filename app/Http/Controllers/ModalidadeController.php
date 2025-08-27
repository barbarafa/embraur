<?php

namespace App\Http\Controllers;

use App\Models\Modalidade;
use Illuminate\Http\Request;

class ModalidadeController extends Controller
{
    public function index(Request $request)
    {
        $cursoId = $request->get('curso_id');
        $q = Modalidade::query()->with('course');
        if ($cursoId) $q->where('curso_id', $cursoId);
        return $q->orderBy('nome')->paginate(50);
    }

    public function show(Modalidade $modalidade)
    {
        return $modalidade->load('course');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'curso_id' => 'required|exists:cursos,id',
            'nome' => 'required|string|max:255',
            'preco' => 'required|numeric|min:0',
            'carga_horaria' => 'required|integer|min:0',
            'descricao' => 'nullable|string',
            'ativo' => 'boolean',
        ]);

        $m = Modalidade::create($data);
        return response()->json($m, 201);
    }

    public function update(Request $request, Modalidade $modalidade)
    {
        $data = $request->validate([
            'curso_id' => 'sometimes|required|exists:cursos,id',
            'nome' => 'sometimes|required|string|max:255',
            'preco' => 'sometimes|required|numeric|min:0',
            'carga_horaria' => 'sometimes|required|integer|min:0',
            'descricao' => 'nullable|string',
            'ativo' => 'boolean',
        ]);

        $modalidade->update($data);
        return $modalidade->fresh('course');
    }

    public function destroy(Modalidade $modalidade)
    {
        $modalidade->delete();
        return response()->noContent();
    }
}
