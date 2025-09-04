<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use App\Models\Categorias;

use App\Models\Cursos;
use App\Models\TagCurso;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CursoAdminController extends Controller
{
    public function index(Request $request)
    {
        $profId = session('prof_id');
        $cursos = Cursos::with('categoria')
            ->where('professor_id', $profId)
            ->orderByDesc('id')
            ->paginate(12);

        return view('prof.cursos.index', compact('cursos'));
    }

    public function create()
    {
        $curso = new Cursos();
        $categorias = Categorias::orderBy('nome')->get();
        return view('prof.cursos.create', compact('curso','categorias'));
    }

    public function store(Request $request)
    {
        $profId = session('prof_id');

        $data = $request->validate([
            'titulo'              => 'required|string|max:255',
            'descricao_curta'     => 'nullable|string|max:255',
            'carga_horaria_total'  => 'nullable|numeric|min:0',
            'descricao_completa'  => 'nullable|string',
            'categoria_id'        => 'required|exists:categorias,id',
            'nivel'               => 'required|in:iniciante,intermediario,avancado',
            'preco'               => 'nullable|numeric|min:0',
            'preco_original'      => 'nullable|numeric|min:0',
            'nota_minima_aprovacao'=> 'nullable|numeric|min:0|max:10',
            'maximo_alunos'       => 'nullable|integer|min:1',
            'slug'                => 'nullable|string|max:255',
            'imagem_capa'          => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data['professor_id'] = $profId;

        // salva a imagem (se enviada)
        if ($request->hasFile('imagem_capa')) {
            $data['imagem_capa'] = $request->file('imagem_capa')
                ->store('cursos/capas', 'public'); // ex.: storage/app/public/cursos/capas/xxxx.jpg
        }

        $curso = Cursos::create($data);

        return redirect()->route('prof.cursos.edit', $curso)
            ->with('success','Curso criado com sucesso!');
    }

    public function edit(Cursos $curso)
    {
        $this->authorizeCurso($curso);
        $categorias = Categorias::orderBy('nome')->get();

        return view('prof.cursos.edit', compact('curso','categorias'));
    }

    public function update(Request $request, Cursos $curso)
    {
        $this->authorizeCurso($curso);

        $data = $request->validate([
            'titulo'              => 'required|string|max:255',
            'descricao_curta'     => 'nullable|string|max:255',
            'descricao_completa'  => 'nullable|string',
            'categoria_id'        => 'required|exists:categorias,id',
            'carga_horaria_total'  => 'nullable|numeric|min:0',
            'nivel'               => 'required|in:iniciante,intermediario,avancado',
            'preco'               => 'nullable|numeric|min:0',
            'preco_original'      => 'nullable|numeric|min:0',
            'nota_minima_aprovacao'=> 'nullable|numeric|min:0|max:10',
            'maximo_alunos'       => 'nullable|integer|min:1',
            'slug'                => 'nullable|string|max:255',
            'resumo'              => 'nullable|string',
            'imagem_capa'          => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('imagem_capa')) {
            if ($curso->imagem_capa && Storage::disk('public')->exists($curso->imagem_capa)) {
                Storage::disk('public')->delete($curso->imagem_capa);
            }
            $data['imagem_capa'] = $request->file('imagem_capa')
                ->store('cursos/capas', 'public');
        }

        $curso->update($data);

        return back()->with('success','Curso atualizado com sucesso!');
    }

    public function destroy(Cursos $curso)
    {

        $this->authorizeCurso($curso);

        if ($curso->imagem_capa && Storage::disk('public')->exists($curso->imagem_capa)) {
            Storage::disk('public')->delete($curso->imagem_capa);
        }
        $curso->delete();
        return redirect()->route('prof.cursos.index')->with('success','Curso removido.');
    }

    private function authorizeCurso(Cursos $curso)
    {
        if ($curso->professor_id != session('prof_id')) {
            abort(403, 'Sem permissão para esse curso.');
        }
    }
}
