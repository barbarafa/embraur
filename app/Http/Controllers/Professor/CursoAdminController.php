<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CursoAdminController extends Controller
{
    public function index()
    {
        $cursos = Curso::with('categoria')->latest()->paginate(10);
        return view('prof.cursos.index', compact('cursos'));
    }

    public function create()
    {
        $cats = Categoria::orderBy('nome')->get();
        return view('prof.cursos.create', compact('cats'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'categoria_id'=>'required|exists:categorias,id',
            'titulo'=>'required|string|max:200',
            'descricao'=>'required|string',
            'carga_horaria'=>'required|integer|min:1',
            'preco'=>'required|numeric|min:0',
            'preco_promocional'=>'nullable|numeric|min:0',
            'nivel'=>'required|in:Básico,Intermediário,Avançado',
            'avaliacao'=>'nullable|numeric|min:0|max:5',
            'alunos'=>'nullable|integer|min:0',
            'popular'=>'nullable|boolean',
        ]);
        $data['slug'] = Str::slug($data['titulo']);
        $data['popular'] = (bool)($data['popular'] ?? false);

        Curso::create($data);
        return redirect()->route('prof.cursos.index')->with('ok','Curso criado.');
    }

    public function edit(Curso $curso)
    {
        $cats = Categoria::orderBy('nome')->get();
        return view('prof.cursos.edit', compact('curso','cats'));
    }

    public function update(Request $request, Curso $curso)
    {
        $data = $request->validate([
            'categoria_id'=>'required|exists:categorias,id',
            'titulo'=>'required|string|max:200',
            'descricao'=>'required|string',
            'carga_horaria'=>'required|integer|min:1',
            'preco'=>'required|numeric|min:0',
            'preco_promocional'=>'nullable|numeric|min:0',
            'nivel'=>'required|in:Básico,Intermediário,Avançado',
            'avaliacao'=>'nullable|numeric|min:0|max:5',
            'alunos'=>'nullable|integer|min:0',
            'popular'=>'nullable|boolean',
        ]);
        $data['slug'] = Str::slug($data['titulo']);
        $data['popular'] = (bool)($data['popular'] ?? false);

        $curso->update($data);
        return redirect()->route('prof.cursos.index')->with('ok','Curso atualizado.');
    }

    public function destroy(Curso $curso)
    {
        $curso->delete();
        return back()->with('ok','Curso excluído.');
    }
}
