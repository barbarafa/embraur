<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\Modulo;
use Illuminate\Http\Request;

class ModuloAdminController extends Controller
{
    public function index(Curso $curso)
    {
        $this->authorizeCurso($curso);
        $modulos = $curso->modulos()->get();
        return view('prof.modulos.index', compact('curso','modulos'));
    }

    public function store(Request $request, Curso $curso)
    {
        $this->authorizeCurso($curso);

        $data = $request->validate([
            'titulo'    => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'ordem'     => 'nullable|integer|min:0'
        ]);

        $data['curso_id'] = $curso->id;
        Modulo::create($data);

        return back()->with('success','Módulo criado!');
    }

    public function update(Request $request, Curso $curso, Modulo $modulo)
    {
        $this->authorizeCurso($curso);
        $this->authorizeModulo($curso, $modulo);

        $data = $request->validate([
            'titulo'    => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'ordem'     => 'nullable|integer|min:0'
        ]);

        $modulo->update($data);

        return back()->with('success','Módulo atualizado!');
    }

    public function destroy(Curso $curso, Modulo $modulo)
    {
        $this->authorizeCurso($curso);
        $this->authorizeModulo($curso, $modulo);

        $modulo->delete();
        return back()->with('success','Módulo removido.');
    }

    public function reorder(Request $request, Curso $curso)
    {
        $this->authorizeCurso($curso);

        $data = $request->validate([
            'ordens' => 'required|array' // ex.: [['id'=>1,'ordem'=>1], ...]
        ]);

        foreach ($data['ordens'] as $it) {
            Modulo::where('id', $it['id'])->where('curso_id',$curso->id)->update(['ordem'=>$it['ordem']]);
        }

        return back()->with('success','Ordenação salva!');
    }

    private function authorizeCurso(Curso $curso)
    {
        if ($curso->professor_id != session('prof_id')) abort(403);
    }

    private function authorizeModulo(Curso $curso, Modulo $modulo)
    {
        if ($modulo->curso_id != $curso->id) abort(404);
    }
}
