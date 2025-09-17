<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use App\Models\Cursos;
use Illuminate\Http\Request;

class CursoMediaController extends Controller
{
    public function uploadCover(Request $request, Cursos $curso)
    {
        $this->authorizeCurso($curso);

        $request->validate([
            'imagem_capa' => 'required|image|mimes:jpeg,png,jpg|max:4096'
        ]);

        // remove capa anterior
        if ($curso->imagem_capa && Storage::disk('public')->exists($curso->imagem_capa)) {
            Storage::disk('public')->delete($curso->imagem_capa);
        }

        $path = $request->file('imagem_capa')->store('cursos/capas', 'public');
        $curso->update(['imagem_capa' => $path]);

        return back()->with('success','Capa atualizada!');
    }

    public function removeCover(Cursos $curso)
    {
        $this->authorizeCurso($curso);

        if ($curso->imagem_capa && Storage::disk('public')->exists($curso->imagem_capa)) {
            Storage::disk('public')->delete($curso->imagem_capa);
        }
        $curso->update(['imagem_capa' => null]);

        return back()->with('success','Capa removida!');
    }

    private function authorizeCurso(Cursos $curso)
    {
        if ($curso->professor_id != session('prof_id')) abort(403);
    }
}
