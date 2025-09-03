<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use Illuminate\Http\Request;

class CursoMediaController extends Controller
{
    public function uploadCover(Curso $curso, Request $request)
    {
        $this->authz($curso, $request);
        $request->validate(['capa' => 'required|image|max:4096']);

        $curso->capa_path = $request->file('capa')->store('cursos', 'public');
        $curso->save();

        return back()->with('success', 'Capa enviada com sucesso.');
    }

    public function removeCover(Curso $curso, Request $request)
    {
        $this->authz($curso, $request);
        $curso->capa_path = null;
        $curso->save();

        return back()->with('success', 'Capa removida.');
    }

    private function authz(Curso $curso, Request $request)
    {
        $profId = $request->session()->get('prof_id');
        abort_unless((int)$curso->professor_id === (int)$profId, 403);
    }
}
