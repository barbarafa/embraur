<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CursoAdminController extends Controller
{
    public function index(Request $request)
    {
        $profId = (int) $request->session()->get('prof_id');

        $q     = trim((string) $request->get('q', ''));
        $order = $request->get('order', 'recent'); // recent | title-asc | title-desc

        $cursos = Curso::where('professor_id', $profId)
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($qq) use ($q) {
                    $qq->where('titulo', 'like', "%{$q}%")
                        ->orWhere('resumo', 'like', "%{$q}%");
                });
            })
            ->when($order === 'title-asc', fn($q) => $q->orderBy('titulo', 'asc'))
            ->when($order === 'title-desc', fn($q) => $q->orderBy('titulo', 'desc'))
            ->when($order === 'recent', fn($q) => $q->latest())
            ->paginate(12)
            ->withQueryString();

        return view('prof.cursos.index', compact('cursos', 'q', 'order'));
    }

    public function create()
    {
        // Se quiser enviar categorias, basta buscar e incluir no compact.
        return view('prof.cursos.form', ['curso' => new Curso()]);
    }

    public function store(Request $request)
    {
        $profId = (int) $request->session()->get('prof_id');

        $data = $request->validate([
            'titulo'        => 'required|string|max:180',
            'resumo'        => 'nullable|string|max:500',
            'descricao'     => 'nullable|string',
            'preco'         => 'nullable|numeric|min:0',
            'nivel'         => 'nullable|string|max:50',
            'carga_horaria' => 'nullable|integer|min:0',
            'max_alunos'    => 'nullable|integer|min:0',
            'categoria_id'  => 'nullable|integer',
            'publicado'     => 'nullable|boolean',
            'tags'          => 'nullable',  // JSON string vindo do form
            'estrutura'     => 'nullable',  // JSON string vindo do form
            'capa'          => 'nullable|image|max:4096',
        ]);

        // Converte JSON (string) em array para os casts do Model
        foreach (['tags', 'estrutura'] as $jsonField) {
            if (isset($data[$jsonField]) && is_string($data[$jsonField])) {
                $data[$jsonField] = json_decode($data[$jsonField], true) ?: [];
            }
        }

        $curso = new Curso();
        $curso->fill($data);
        $curso->professor_id = $profId;
        $curso->publicado    = (bool)($data['publicado'] ?? false);

        // slug único
        $base = Str::slug($data['titulo']);
        $slug = $base;
        $i = 0;
        while (Curso::where('slug', $slug)->exists()) {
            $i++;
            $slug = $base . '-' . Str::lower(Str::random(5));
            if ($i > 10) break; // segurança
        }
        $curso->slug = $slug;

        // capa
        if ($request->hasFile('capa')) {
            $curso->capa_path = $request->file('capa')->store('cursos', 'public');
        }

        $curso->save();

        // fluxo dos botões
        $acao = $request->input('salvar'); // rascunho | publicar
        if ($acao === 'publicar') {
            return redirect()
                ->route('prof.cursos.modulos.index', $curso)
                ->with('success', 'Curso salvo. Agora, adicione módulos e aulas.');
        }

        return redirect()
            ->route('prof.cursos.edit', $curso)
            ->with('success', 'Curso criado como rascunho.');
    }

    public function edit(Curso $curso, Request $request)
    {
        $this->authorizeProfessor($curso, $request);
        return view('prof.cursos.form', compact('curso'));
    }

    public function update(Curso $curso, Request $request)
    {
        $this->authorizeProfessor($curso, $request);

        $data = $request->validate([
            'titulo'        => 'required|string|max:180',
            'resumo'        => 'nullable|string|max:500',
            'descricao'     => 'nullable|string',
            'preco'         => 'nullable|numeric|min:0',
            'nivel'         => 'nullable|string|max:50',
            'carga_horaria' => 'nullable|integer|min:0',
            'max_alunos'    => 'nullable|integer|min:0',
            'categoria_id'  => 'nullable|integer',
            'publicado'     => 'nullable|boolean',
            'tags'          => 'nullable',  // JSON string
            'estrutura'     => 'nullable',  // JSON string
            'capa'          => 'nullable|image|max:4096',
        ]);

        foreach (['tags', 'estrutura'] as $jsonField) {
            if (isset($data[$jsonField]) && is_string($data[$jsonField])) {
                $data[$jsonField] = json_decode($data[$jsonField], true) ?: [];
            }
        }

        $curso->fill($data);
        $curso->publicado = (bool)($data['publicado'] ?? false);

        if ($request->hasFile('capa')) {
            if ($curso->capa_path) {
                Storage::disk('public')->delete($curso->capa_path);
            }
            $curso->capa_path = $request->file('capa')->store('cursos', 'public');
        }

        $curso->save();

        $acao = $request->input('salvar'); // rascunho | publicar
        if ($acao === 'publicar') {
            return redirect()
                ->route('prof.cursos.modulos.index', $curso)
                ->with('success', 'Curso salvo. Continue configurando os módulos e aulas.');
        }

        return back()->with('success', 'Curso atualizado com sucesso.');
    }

    public function destroy(Curso $curso, Request $request)
    {
        $this->authorizeProfessor($curso, $request);

        if ($curso->capa_path) {
            Storage::disk('public')->delete($curso->capa_path);
        }

        $curso->delete();

        return redirect()
            ->route('prof.cursos.index')
            ->with('success', 'Curso removido.');
    }

    private function authorizeProfessor(Curso $curso, Request $request)
    {
        $profId = (int) $request->session()->get('prof_id');
        abort_unless((int) $curso->professor_id === $profId, 403);
    }
}
