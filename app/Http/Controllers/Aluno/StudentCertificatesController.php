<?php

namespace App\Http\Controllers\Aluno;

use App\Http\Controllers\Controller;
use App\Models\Certificados;
use App\Models\Cursos;
use App\Models\Matriculas;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Psy\Util\Str;

class StudentCertificatesController extends Controller
{
    public function index(Request $request)
    {
        $alunoId = auth('aluno')->id() ?? $request->session()->get('aluno_id');
        abort_if(!$alunoId, 403);

        $certs = Certificados::query()
            ->whereHas('matricula', fn($q)=>$q->where('aluno_id',$alunoId))
            ->with(['matricula.curso'])
            ->orderByDesc('data_emissao')
            ->get();

        return view('aluno.certificados', [
            'aluno' => $request->user('aluno'),
            'certificados' => $certs,
        ]);
    }

    public function verify(Request $request, string $codigo)
    {
        $cert = Certificados::where('codigo_verificacao',$codigo)->with(['matricula.curso','user'])->firstOrFail();
        // renderiza uma página de verificação simples
        return view('site.certificado-verificar', compact('cert'));
    }

    public function issue(Request $request, Cursos $curso)
    {
        $alunoId = auth('aluno')->id() ?? $request->session()->get('aluno_id');
        $aluno = User::where('tipo_usuario','aluno')->where('id',$alunoId)->firstOrFail();
        abort_if(!$aluno, 403);

        $matricula = Matriculas::where('aluno_id', $aluno->id)
            ->where('curso_id', $curso->id)
            ->firstOrFail();

        // Regra simples: nosso CourseCompletionService seta 'concluido' quando elegível
        if ($matricula->status !== 'concluido') {
            return back()->with('error', 'Finalize todas as aulas/provas para emitir o certificado.');
        }

        // cria/recupera registro
        $cert = Certificados::firstOrCreate(
            ['matricula_id' => $matricula->id],
            [
                'codigo_verificacao' => strtoupper(\Illuminate\Support\Str::random(10)),
                'data_emissao'       => now(),
                'valido'             => true,
            ]
        );

        // Renderiza PDF
        $pdf = Pdf::loadView('certificados.pdf', [
            'aluno'      => $aluno,
            'curso'      => $curso,
            'matricula'  => $matricula,
            'certificado'=> $cert,
        ])->setPaper('a4', 'landscape');

        // Salva em storage público
//        $safeCourse = Str::slug($curso->titulo);
        $filename   = "certificado--{$aluno->id}-{$cert->codigo_verificacao}.pdf";
        $path       = "certificados/{$filename}";

        Storage::disk('public')->put($path, $pdf->output());

        $cert->url_certificado = Storage::url($path);
        $cert->save();

        // Baixa o arquivo
        return response()->download(storage_path("app/public/{$path}"));
    }

}

