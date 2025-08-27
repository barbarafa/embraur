<?php

namespace App\Http\Controllers;

use App\Models\Aula;
use Illuminate\Http\Request;

class AulaController extends Controller
{
    public function index(Request $request)
    {
        $moduloId = $request->get('modulo_id');
        $q = Aula::query()->with('module');
        if ($moduloId) $q->where('modulo_id', $moduloId);
        return $q->orderBy('ordem')->paginate(200);
    }

    public function show(Aula $aula)
    {
        return $aula->load('module.course');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'modulo_id' => 'required|exists:modulos,id',
            'titulo' => 'required|string|max:255',
            'tipo' => 'required|in:video,pdf,link,outro',
            'url_conteudo' => 'nullable|string',
            'ordem' => 'nullable|integer|min:0',
            'percentual_minimo' => 'nullable|integer|min:0|max:100',
        ]);
        $a = Aula::create($data);
        return response()->json($a, 201);
    }

    public function update(Request $request, Aula $aula)
    {
        $data = $request->validate([
            'modulo_id' => 'sometimes|required|exists:modulos,id',
            'titulo' => 'sometimes|required|string|max:255',
            'tipo' => 'sometimes|required|in:video,pdf,link,outro',
            'url_conteudo' => 'nullable|string',
            'ordem' => 'nullable|integer|min:0',
            'percentual_minimo' => 'nullable|integer|min:0|max:100',
        ]);
        $aula->update($data);
        return $aula->fresh('module.course');
    }

    public function destroy(Aula $aula)
    {
        $aula->delete();
        return response()->noContent();
    }
}
