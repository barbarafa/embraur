<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\Modulo;
use App\Models\Aula;
use Illuminate\Http\Request;

class AulaAdminController extends Controller
{
    public function index(Curso $curso, Modulo $modulo, Request $request)
    {
        $this->authz($curso, $request);
        abort_unless($modulo->curso_id === $curso->id, 404);

        $aulas = Aula::where('modulo_id', $modulo->id)->orderBy('ordem')->get();
        return view('prof.aulas.index', compact('curso', 'modulo', 'aulas'));
    }

    public function store(Curso $curso, Modulo $modulo, Request $request)
    {
        $this->authz($curso, $request);
        abort_unless($modulo->curso_id === $curso->id, 404);

        $data = $request->validate([
            'titulo'   => 'required|string|max:160',
            'tipo'     => 'required|in:video,pdf,texto',
            'url_video'=> 'nullable|url',
            'arquivo'  => 'nullable|file|max:20480', // 20MB
            'duracao'  => 'nullable|integer|min:0',
            'preview'  => 'nullable|boolean',
        ]);

        $ordem = (int) Aula::where('modulo_id', $modulo->id)->max('ordem') + 1;

        $aula = new Aula();
        $aula->fill([
            'modulo_id' => $modulo->id,
            'titulo'    => $data['titulo'],
            'tipo'      => $data['tipo'],
            'url_video' => $data['url_video'] ?? null,
            'duracao'   => $data['duracao'] ?? null,
            'preview'   => (bool)($data['preview'] ?? false),
            'ordem'     => $ordem,
        ]);

        if ($request->hasFile('arquivo')) {
            $aula->arquivo_path = $request->file('arquivo')->store('aulas', 'public');
        }

        $aula->save();
        return back()->with('success', 'Aula criada.');
    }

    public function update(Curso $curso, Modulo $modulo, Aula $aula, Request $request)
    {
        $this->authz($curso, $request);
        abort_unless($modulo->curso_id === $curso->id && $aula->modulo_id === $modulo->id, 404);

        $data = $request->validate([
            'titulo'   => 'required|string|max:160',
            'tipo'     => 'required|in:video,pdf,texto',
            'url_video'=> 'nullable|url',
            'arquivo'  => 'nullable|file|max:20480',
            'duracao'  => 'nullable|integer|min:0',
            'preview'  => 'nullable|boolean',
            'ordem'    => 'nullable|integer|min:1',
        ]);

        $aula->fill([
            'titulo'    => $data['titulo'],
            'tipo'      => $data['tipo'],
            'url_video' => $data['url_video'] ?? null,
            'duracao'   => $data['duracao'] ?? null,
            'preview'   => (bool)($data['preview'] ?? false),
        ]);

        if ($request->hasFile('arquivo')) {
            $aula->arquivo_path = $request->file('arquivo')->store('aulas', 'public');
        }
        if (isset($data['ordem'])) {
            $aula->ordem = (int)$data['ordem'];
        }

        $aula->save();
        return back()->with('success', 'Aula atualizada.');
    }

    public function destroy(Curso $curso, Modulo $modulo, Aula $aula, Request $request)
    {
        $this->authz($curso, $request);
        abort_unless($modulo->curso_id === $curso->id && $aula->modulo_id === $modulo->id, 404);

        $aula->delete();
        return back()->with('success', 'Aula removida.');
    }

    public function reorder(Curso $curso, Modulo $modulo, Request $request)
    {
        $this->authz($curso, $request);
        abort_unless($modulo->curso_id === $curso->id, 404);

        $data = $request->validate(['ordem' => 'required|array']); // [aula_id=>ordem]
        foreach ($data['ordem'] as $aulaId => $ordem) {
            Aula::where('id', $aulaId)->where('modulo_id', $modulo->id)->update(['ordem' => (int)$ordem]);
        }
        return back()->with('success', 'Ordem das aulas atualizada.');
    }

    public function uploadMedia(Curso $curso, Modulo $modulo, Aula $aula, Request $request)
    {
        // opcional se quiser endpoint separado
        return $this->update($curso, $modulo, $aula, $request);
    }

    public function removeMedia(Curso $curso, Modulo $modulo, Aula $aula, $media, Request $request)
    {
        $this->authz($curso, $request);
        if ($media === 'arquivo') {
            $aula->arquivo_path = null;
        } elseif ($media === 'url_video') {
            $aula->url_video = null;
        }
        $aula->save();
        return back()->with('success', 'Mídia removida.');
    }

    private function authz(Curso $curso, Request $request)
    {
        $profId = $request->session()->get('prof_id');
        abort_unless((int)$curso->professor_id === (int)$profId, 403);
    }
}

