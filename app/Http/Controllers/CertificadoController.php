<?php

namespace App\Http\Controllers;

use App\Models\Certificado;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CertificadoController extends Controller
{
    public function index(Request $request)
    {
        $matriculaId = $request->get('matricula_id');
        $q = Certificado::query();
        if ($matriculaId) $q->where('matricula_id', $matriculaId);
        return $q->orderByDesc('emitido_em')->paginate(50);
    }

    public function show(Certificado $certificado)
    {
        return $certificado;
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'matricula_id' => 'required|exists:matriculas,id',
        ]);

        $c = Certificado::create([
            'matricula_id' => $data['matricula_id'],
            'codigo' => (string) Str::uuid(),
            'emitido_em' => now(),
        ]);

        return response()->json($c, 201);
    }

    // verificação pública do certificado
    public function verify($codigo)
    {
        $cert = Certificado::where('codigo', $codigo)->firstOrFail();
        return [
            'valido' => true,
            'codigo' => $cert->codigo,
            'emitido_em' => $cert->emitido_em,
            'matricula_id' => $cert->matricula_id,
        ];
    }

    public function destroy(Certificado $certificado)
    {
        $certificado->delete();
        return response()->noContent();
    }
}
