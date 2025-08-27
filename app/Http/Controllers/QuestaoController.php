<?php

namespace App\Http\Controllers;

use App\Models\Questao;
use Illuminate\Http\Request;

class QuestaoController extends Controller
{
    public function index(Request $request)
    {
        $provaId = $request->get('prova_id');
        $q = Questao::query()->where('prova_id', $provaId)->orderBy('id');
        return $q->paginate(100);
    }

    public function show(Questao $questao)
    {
        return $questao;
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'prova_id' => 'required|exists:provas,id',
            'tipo' => 'required|in:objetiva,multipla,dissertativa',
            'enunciado' => 'required|string',
            'opcoes' => 'nullable|array',
            'resposta_correta' => 'nullable|array',
            'peso' => 'integer|min:1',
        ]);
        $data['opcoes'] = $data['opcoes'] ?? null;
        $data['resposta_correta'] = $data['resposta_correta'] ?? null;

        $q = Questao::create($data);
        return response()->json($q, 201);
    }

    public function update(Request $request, Questao $questao)
    {
        $data = $request->validate([
            'tipo' => 'sometimes|required|in:objetiva,multipla,dissertativa',
            'enunciado' => 'sometimes|required|string',
            'opcoes' => 'nullable|array',
            'resposta_correta' => 'nullable|array',
            'peso' => 'integer|min:1',
        ]);
        $questao->update($data);
        return $questao;
    }

    public function destroy(Questao $questao)
    {
        $questao->delete();
        return response()->noContent();
    }
}
