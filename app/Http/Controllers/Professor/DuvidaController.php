<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use App\Models\Duvida;
use Illuminate\Http\Request;

class DuvidaController extends Controller
{
    public function index(Request $request)
    {
        $profId = (int) $request->session()->get('prof_id');

        // Busca dúvidas apenas dos cursos do professor logado
        $duvidas = Duvida::with(['aluno', 'curso'])
            ->whereHas('curso', fn($q) => $q->where('professor_id', $profId))
            ->latest()
            ->paginate(10);

        return view('prof.duvidas.index', compact('duvidas'));
    }

    public function markRead(Duvida $duvida, Request $request)
    {
        $profId = (int) $request->session()->get('prof_id');

        // Segurança: só marca dúvidas de cursos do professor
        abort_unless(optional($duvida->curso)->professor_id === $profId, 403);

        $duvida->lida_at = now();
        $duvida->save();

        return back()->with('success', 'Dúvida marcada como lida.');
    }
}
