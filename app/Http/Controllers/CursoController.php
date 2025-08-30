<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Categoria;
use Illuminate\Http\Request;

class CursoController extends Controller
{
    public function index(Request $request)
    {
        $q = Curso::with('categoria');

        if ($request->filled('categoria')) {
            $q->whereHas('categoria', fn($qq) => $qq->where('slug', $request->categoria));
        }

        if ($request->filled('busca')) {
            $q->where(function($w) use ($request) {
                $w->where('titulo','like','%'.$request->busca.'%')
                    ->orWhere('descricao','like','%'.$request->busca.'%');
            });
        }

        $cursos = $q->orderBy('titulo')->paginate(6)->withQueryString();
        $categorias = Categoria::orderBy('nome')->get();

        return view('site.catalogo', compact('cursos','categorias'));
    }

    public function show(string $slug)
    {
        $curso = Curso::where('slug',$slug)->with('categoria')->firstOrFail();
        return view('site.curso-detalhe', compact('curso'));
    }
}
