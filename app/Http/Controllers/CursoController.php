<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use Illuminate\Http\Request;

class CursoController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->get('q');
        $ativos = $request->boolean('ativos', null);

        $query = Curso::query()->with(['modalidades', 'modules']);

        if ($q) $query->where(fn($qq) =>
        $qq->where('titulo', 'like', "%$q%")
            ->orWhere('resumo', 'like', "%$q%")
        );
        if (!is_null($ativos)) $query->where('ativo', $ativos);

        return $query->orderBy('titulo')->paginate(20);
    }

    public function show(Curso $curso)
    {
        return $curso->load(['modalidades', 'modules.lessons']);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'titulo' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:cursos,slug',
            'resumo' => 'nullable|string',
            'descricao' => 'nullable|string',
            'carga_horaria' => 'required|integer|min:0',
            'nota_minima' => 'required|integer|min:0|max:100',
            'validade_meses' => 'nullable|integer|min:0',
            'imagem_capa' => 'nullable|string',
            'video_intro' => 'nullable|string',
            'ativo' => 'boolean',
        ]);

        $curso = Curso::create($data);
        return response()->json($curso, 201);
    }

    public function update(Request $request, Curso $curso)
    {
        $data = $request->validate([
            'titulo' => 'sometimes|required|string|max:255',
            'slug' => 'sometimes|required|string|max:255|unique:cursos,slug,' . $curso->id,
            'resumo' => 'nullable|string',
            'descricao' => 'nullable|string',
            'carga_horaria' => 'sometimes|required|integer|min:0',
            'nota_minima' => 'sometimes|required|integer|min:0|max:100',
            'validade_meses' => 'nullable|integer|min:0',
            'imagem_capa' => 'nullable|string',
            'video_intro' => 'nullable|string',
            'ativo' => 'boolean',
        ]);

        $curso->update($data);
        return $curso->fresh(['modalidades', 'modules']);
    }

    public function destroy(Curso $curso)
    {
        $curso->delete();
        return response()->noContent();
    }
}
