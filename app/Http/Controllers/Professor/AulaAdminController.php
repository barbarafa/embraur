<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\Modulo;
use App\Models\Aula;
use Illuminate\Http\Request;

class AulaAdminController extends Controller
{
    public function index(Curso $curso, Modulo $modulo)
    {
        $this->authorizeCurso($curso);
        $this->authorizeModulo($curso, $modulo);
        $aulas = $modulo->aulas()->with('materiais')->get();

        return view('prof.aulas.index', compact('curso','modulo','aulas'));
    }

    public function store(Request $request, Curso $curso, Modulo $modulo)
    {
        $this->authorizeCurso($curso);
        $this->authorizeModulo($curso, $modulo);

        $data = $request->validate([
            'titulo'               => 'required|string|max:255',
            'descricao'            => 'nullable|string',
            'tipo'                 => 'required|in:video,texto,quiz,arquivo',
            'duracao_minutos'      => 'nullable|integer|min:0',
            'conteudo_url'         => 'nullable|string|max:255',
            'conteudo_texto'       => 'nullable|string',
            'ordem'                => 'nullable|integer|min:0',
            'liberada_apos_anterior'=> 'nullable|boolean'
        ]);

        $data['modulo_id'] = $modulo->id;
        $data['liberada_apos_anterior'] = (bool)($data['liberada_apos_anterior'] ?? false);

        Aula::create($data);
        return back()->with('success','Aula criada!');
    }

    public function update(Request $request, Curso $curso, Modulo $modulo, Aula $aula)
    {
        $this->authorizeCurso($curso);
        $this->authorizeModulo($curso, $modulo);
        $this->authorizeAula($modulo, $aula);

        $data = $request->validate([
            'titulo'               => 'required|string|max:255',
            'descricao'            => 'nullable|string',
            'tipo'                 => 'required|in:video,texto,quiz,arquivo',
            'duracao_minutos'      => 'nullable|integer|min:0',
            'conteudo_url'         => 'nullable|string|max:255',
            'conteudo_texto'       => 'nullable|string',
            'ordem'                => 'nullable|integer|min:0',
            'liberada_apos_anterior'=> 'nullable|boolean'
        ]);

        $data['liberada_apos_anterior'] = (bool)($data['liberada_apos_anterior'] ?? false);

        $aula->update($data);
        return back()->with('success','Aula atualizada!');
    }

    public function destroy(Curso $curso, Modulo $modulo, Aula $aula)
    {
        $this->authorizeCurso($curso);
        $this->authorizeModulo($curso, $modulo);
        $this->authorizeAula($modulo, $aula);

        $aula->delete();
        return back()->with('success','Aula removida.');
    }

    public function reorder(Request $request, Curso $curso, Modulo $modulo)
    {
        $this->authorizeCurso($curso);
        $this->authorizeModulo($curso, $modulo);

        $data = $request->validate([
            'ordens' => 'required|array'
        ]);

        foreach ($data['ordens'] as $it) {
            Aula::where('id',$it['id'])->where('modulo_id',$modulo->id)->update(['ordem'=>$it['ordem']]);
        }
        return back()->with('success','Ordenação salva!');
    }

    // Upload/Remoção de materiais da aula (links nas rotas)
    public function uploadMedia(Request $request, Curso $curso, Modulo $modulo, Aula $aula)
    {
        $this->authorizeCurso($curso);
        $this->authorizeModulo($curso, $modulo);
        $this->authorizeAula($modulo, $aula);

        $request->validate([
            'arquivo' => 'required|file|max:10240' // 10MB
        ]);

        $path = $request->file('arquivo')->store('aulas/materiais', 'public');

        MaterialApoio::create([
            'aula_id'      => $aula->id,
            'nome_arquivo' => $request->file('arquivo')->getClientOriginalName(),
            'tipo_arquivo' => $request->file('arquivo')->getMimeType(),
            'url_download' => $path,
            'tamanho_kb'   => round($request->file('arquivo')->getSize()/1024)
        ]);

        return back()->with('success','Material enviado!');
    }

    public function removeMedia(Curso $curso, Modulo $modulo, Aula $aula, MaterialApoio $media)
    {
        $this->authorizeCurso($curso);
        $this->authorizeModulo($curso, $modulo);
        $this->authorizeAula($modulo, $aula);

        if ($media->aula_id != $aula->id) abort(404);

        // remove arquivo
        if ($media->url_download && Storage::disk('public')->exists($media->url_download)) {
            Storage::disk('public')->delete($media->url_download);
        }
        $media->delete();

        return back()->with('success','Material removido!');
    }

    private function authorizeCurso(Curso $curso)
    {
        if ($curso->professor_id != session('prof_id')) abort(403);
    }
    private function authorizeModulo(Curso $curso, Modulo $modulo)
    {
        if ($modulo->curso_id != $curso->id) abort(404);
    }
    private function authorizeAula(Modulo $modulo, Aula $aula)
    {
        if ($aula->modulo_id != $modulo->id) abort(404);
    }
}

