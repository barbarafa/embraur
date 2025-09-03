<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\Modulo;
use Illuminate\Http\Request;

class ModuloAdminController extends Controller
{
    public function index(Curso $curso, Request $request)
    {
        $this->authz($curso, $request);
        $modulos = Modulo::where('curso_id', $curso->id)->orderBy('ordem')->get();
        return view('prof.modulos.index', compact('curso', 'modulos'));
    }

    public function store(Curso $curso, Request $request)
    {
        $this->authz($curso, $request);

        $data = $request->validate([
            'titulo' => 'required|string|max:160',
        ]);

        $ordem = (int) Modulo::where('curso_id', $curso->id)->max('ordem') + 1;

        Modulo::create([
            'curso_id' => $curso->id,
            'titulo'   => $data['titulo'],
            'ordem'    => $ordem,
        ]);

        return back()->with('success', 'Módulo criado.');
    }

    public function update(Curso $curso, Modulo $modulo, Request $request)
    {
        $this->authz($curso, $request);
        abort_unless($modulo->curso_id === $curso->id, 404);

        $data = $request->validate([
            'titulo' => 'required|string|max:160',
        ]);

        $modulo->update($data);
        return back()->with('success', 'Módulo atualizado.');
    }

    public function destroy(Curso $curso, Modulo $modulo, Request $request)
    {
        $this->authz($curso, $request);
        abort_unless($modulo->curso_id === $curso->id, 404);

        $modulo->delete();
        return back()->with('success', 'Módulo removido.');
    }

    public function reorder(Curso $curso, Request $request)
    {
        $this->authz($curso, $request);
        $data = $request->validate([
            'ordem' => 'required|array',     // ex: [modulo_id => ordem]
        ]);

        foreach ($data['ordem'] as $moduloId => $ordem) {
            Modulo::where('id', $moduloId)->where('curso_id', $curso->id)->update(['ordem' => (int)$ordem]);
        }

        return back()->with('success', 'Ordem dos módulos atualizada.');
    }

    private function authz(Curso $curso, Request $request)
    {
        $profId = $request->session()->get('prof_id');
        abort_unless((int)$curso->professor_id === (int)$profId, 403);
    }
}
