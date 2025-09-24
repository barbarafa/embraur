<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use App\Models\Cupom;
use Illuminate\Http\Request;

class CupomController extends Controller
{
    public function index(Request $r)
    {
        $q = trim((string)$r->get('q',''));
        $itens = Cupom::when($q, fn($qb)=>$qb->where('codigo','like','%'.mb_strtoupper($q).'%'))
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('prof.cupons.index', compact('itens','q'));
    }

    public function create()
    {
        $cupom = new Cupom(['ativo' => true, 'tipo' => 'fixo', 'valor'=>0]);
        return view('prof.cupons.form', compact('cupom'));
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'codigo'    => 'required|string|max:40|unique:cupons,codigo',
            'tipo'      => 'required|in:percentual,fixo',
            'valor'     => 'required|numeric|min:0',
            'inicio_em' => 'nullable|date',
            'fim_em'    => 'nullable|date|after_or_equal:inicio_em',
            'ativo'     => 'boolean',
        ]);
        $data['ativo'] = (bool)($data['ativo'] ?? false);

        Cupom::create($data);
        return redirect()->route('prof.cupons.index')->with('ok','Cupom criado com sucesso!');
    }

    public function edit(Cupom $cupom)
    {
        return view('prof.cupons.form', compact('cupom'));
    }

    public function update(Request $r, Cupom $cupom)
    {
        $data = $r->validate([
            'codigo'    => 'required|string|max:40|unique:cupons,codigo,'.$cupom->id,
            'tipo'      => 'required|in:percentual,fixo',
            'valor'     => 'required|numeric|min:0',
            'inicio_em' => 'nullable|date',
            'fim_em'    => 'nullable|date|after_or_equal:inicio_em',
            'ativo'     => 'boolean',
        ]);
        $data['ativo'] = (bool)($data['ativo'] ?? false);

        $cupom->update($data);
        return redirect()->route('prof.cupons.index')->with('ok','Cupom atualizado!');
    }

    public function destroy(Cupom $cupom)
    {
        $cupom->delete();
        return back()->with('ok','Cupom excluído!');
    }
}
