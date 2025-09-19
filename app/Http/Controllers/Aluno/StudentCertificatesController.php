<?php

namespace App\Http\Controllers\Aluno;

use App\Http\Controllers\Controller;
use App\Models\Certificados;
use App\Models\Cursos;
use App\Models\Matriculas;
use App\Models\User;
use App\Services\CourseCompletionService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Psy\Util\Str;

class StudentCertificatesController extends Controller
{

    private function assertOwnership(Request $request, Certificados $cert): void
    {
        $alunoId = auth('aluno')->id() ?? $request->session()->get('aluno_id');


        abort_if(!$alunoId || (int)$cert->matricula->aluno_id !== (int)$alunoId, 403);
    }


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

//    public function baixar(Cursos $curso, Request $request)
//    {
//        $alunoId = auth('aluno')->id() ?? $request->session()->get('aluno_id');
//        $matricula = Matriculas::where('aluno_id', $alunoId)
//            ->where('curso_id', $curso->id)->firstOrFail();
//
//        $cert = $matricula->certificado()->firstOrFail();
//
//        // stream/gera o PDF (exemplo com Dompdf):
//        $pdf = Pdf::loadView('certificados.pdf', [
//            'aluno' => $matricula->aluno, 'curso' => $curso, 'certificado' => $cert,
//        ])->setPaper('a4', 'landscape');
//
//        return $pdf->download("certificado-{$curso->id}.pdf");
//    }

//    public function baixar(Cursos $curso, Request $request)
//    {
//        $alunoId = auth('aluno')->id() ?? $request->session()->get('aluno_id');
//        $matricula = Matriculas::where('aluno_id', $alunoId)
//            ->where('curso_id', $curso->id)->firstOrFail();
//
//        // Caminho do background: use a imagem do template que você enviou (A4 landscape)
//        $bgPath = public_path('certificados/template/template.jpg'); // ou .png
//        $bgDataUri = is_file($bgPath) ? 'data:image/'.pathinfo($bgPath, PATHINFO_EXTENSION).';base64,'.base64_encode(file_get_contents($bgPath)) : null;
//        $cert = $matricula->certificado->first();
//        // (Opcional) Assinatura do responsável (PNG com transparência)
////        $assinPath = public_path('certificados/assinaturas/responsavel.png');
////        $assinData = is_file($assinPath) ? 'data:image/png;base64,'.base64_encode(file_get_contents($assinPath)) : null;
//
//        $data = [
//            'aluno'        => $matricula->aluno,
//            'curso'        => $curso,
//            'certificado'  =>  $matricula->certificado,
//            'alunoNome'    => $matricula->aluno->name ?? $matricula->aluno->nome,
//            'cursoTitulo'  => $curso->titulo,
//            'cargaHoraria' => $curso->carga_horaria ?? null,
//            'dataEmissao'  => $cert->data_emissao,
//            'codigo'       => $cert->codigo_verificacao,
//            'bgDataUri'    => $bgDataUri,
//            'assinatura1'  => ['nome' => 'Juliana Silva', 'cargo' => 'Coordenadora'],
////            'assinaturaImgData' => $assinData,
//        ];
//
//        $pdf = Pdf::loadView('certificados.template', $data)
//            ->setPaper('a4', 'landscape');
//
//        return $pdf->download("certificado-{$curso->id}-{$matricula->id}.pdf");
//    }

    private function findCertTemplatePath(string $relative): ?string
    {
        $candidates = [
            public_path($relative),                     // ex.: public/certificados/template/template.jpg
            public_path('storage/'.$relative),         // ex.: public/storage/certificados/template/template.jpg (requer storage:link)
            storage_path('app/public/'.$relative),     // ex.: storage/app/public/certificados/template/template.jpg
            base_path('public/'.$relative),
        ];

        foreach ($candidates as $p) {
            if (is_file($p) && is_readable($p)) return $p;
        }
        return null;
    }

    public function baixarFPDF(Cursos $curso, Request $request)
    {
        $alunoId   = auth('aluno')->id() ?? $request->session()->get('aluno_id');
        $matricula = Matriculas::where('aluno_id', $alunoId)
            ->where('curso_id', $curso->id)->firstOrFail();

        $cert = $matricula->certificado->firstOrFail();

        $bytes = $this->renderCertificadoFPDF($curso, $matricula, $cert);

        return response($bytes, 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="certificado-preview.pdf"', // inline p/ visualizar
        ]);
    }

    private function renderCertificadoFPDF(Cursos $curso, Matriculas $matricula, Certificados $cert): string
    {
        // Localiza o template de fundo
        $rel    = 'certificados/template/template.jpg'; // ajuste se necessário
        $bgPath = $this->findCertTemplatePath($rel);
        if (!$bgPath) {
            throw new \RuntimeException("Template do certificado não encontrado. Coloque em:
          - public/{$rel}
          - public/storage/{$rel} (após php artisan storage:link)
          - storage/app/public/{$rel}");
        }

        // Instancia o FPDF
        $pdf = new \FPDF('L', 'mm', 'A4'); // Landscape
        $pdf->AddPage();

        // Fundo
        $type = strtolower(pathinfo($bgPath, PATHINFO_EXTENSION)) === 'png' ? 'PNG' : 'JPG';
        $pdf->Image($bgPath, 0, 0, 297, 210, $type); // ocupa a página inteira

        // Helper de encoding (UTF-8 -> CP1252) para evitar "?" em – “ ” ’ etc.
        $toPdf = fn(?string $s) => iconv('UTF-8', 'Windows-1252//TRANSLIT', $s ?? '');

        // ===== Nome do aluno =====
        $pdf->SetFont('Arial','B',26);
        $pdf->SetTextColor(136,152,117); // brand-500
        $pdf->SetXY(20, 83);
        $pdf->Cell(257, 10, $toPdf($matricula->aluno->nome_completo ?? ''), 0, 1, 'C');

        // ===== Texto descritivo (centralizado; título do curso em negrito) =====
        $pdf->SetTextColor(51,51,51);
        $pdf->SetFont('Arial','',12);

        $blockW  = 200;                                   // largura do parágrafo (mm)
        $textY   = 112;                                   // Y do parágrafo
        $pageW   = $pdf->GetPageWidth();
        $blockX  = ($pageW - $blockW) / 2;                // centraliza horizontalmente

        $parte1 = "Este certificado é apresentado a ".($matricula->aluno->nome_completo ?? '')
            ." por ter concluído o curso ";
        $titulo = "\"{$curso->titulo}\"";
        $parte2 = $curso->carga_horaria_total
            ? " com carga horária de {$curso->carga_horaria_total} horas."
            : ".";

        // normaliza aspas curvas e converte encoding
        $norm   = fn($t) => $toPdf(str_replace(['“','”','’'], ['"','"',"'" ], $t));
        $parte1 = $norm($parte1);
        $titulo = $norm($titulo);
        $parte2 = $norm($parte2);

        // bloco central
        $origLeft = 10; $origRight = 10; // se usa outras margens globais, ajuste
        $pdf->SetLeftMargin($blockX);
        $pdf->SetRightMargin($blockX);
        $pdf->SetXY($blockX, $textY);
        $pdf->Write(7, $parte1);
        $pdf->SetFont('Arial','B',12);   // destaque só no TÍTULO
        $pdf->Write(7, $titulo);
        $pdf->SetFont('Arial','',12);
        $pdf->Write(7, $parte2);
        $pdf->Ln(10);
        $pdf->SetLeftMargin($origLeft);
        $pdf->SetRightMargin($origRight);

        // ===== Data (sobre o pontilhado) =====
        $dateStr = $cert->data_emissao ? $cert->data_emissao->format('d/m/Y') : date('d/m/Y');
        $pdf->SetFont('Arial','',11);
        $pdf->SetTextColor(33,33,33);
        $pdf->SetXY(64, 162);                 // ajuste fino se necessário
        $pdf->Cell(52, 6, $toPdf($dateStr), 0, 0, 'C');

        // ===== Assinatura (linha + nome/cargo no lado direito) =====
        $pdf->SetXY(297-38-70, 170);
        $pdf->Cell(70, 0, '', 'T'); // linha
        $pdf->Ln(2);
        $pdf->SetX(297-38-70);
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(70, 5, $toPdf('Responsável'), 0, 2, 'C');
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(70, 5, $toPdf('Cargo / Conselho'), 0, 2, 'C');

        // Retorna os bytes do PDF (sem enviar headers)
        return $pdf->Output('S');
    }

    public function issue(Request $request, Cursos $curso)
    {
        $alunoId = auth('aluno')->id() ?? $request->session()->get('aluno_id');
        $aluno   = User::where('tipo_usuario','aluno')->where('id',$alunoId)->firstOrFail();
        abort_if(!$aluno, 403);

        $matricula = Matriculas::where('aluno_id', $aluno->id)
            ->where('curso_id', $curso->id)
            ->firstOrFail();

        // cria/recupera registro
        $cert = Certificados::firstOrCreate(
            ['matricula_id' => $matricula->id],
            [
                'codigo_verificacao' => strtoupper(\Illuminate\Support\Str::random(10)),
                'data_emissao'       => now(),
                'valido'             => true,
            ]
        );

        // regra de elegibilidade
        $elig = app(CourseCompletionService::class)->checkEligibility($cert->matricula);
        if (!$elig['elegivel']) {
            abort(403, 'Certificado disponível apenas após concluir todas as provas dos módulos com média mínima de '
                . number_format($elig['exigido'], 2));
        }

        // ---------- Gera PDF (FPDF) usando o template criado ----------
        $pdfBytes = $this->renderCertificadoFPDF($curso, $matricula, $cert);

        // Salva arquivo em storage/public
        $filename = "certificado--{$aluno->id}-{$cert->codigo_verificacao}.pdf";
        $path     = "certificados/{$filename}";
        \Storage::disk('public')->put($path, $pdfBytes);

        // Atualiza URL pública
        $cert->url_certificado = \Storage::url($path);
        $cert->save();

        // Baixa (ou troque por inline, se quiser visualizar em tela)
        return response($pdfBytes, 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }

    public function visualizar(Request $request, Certificados $cert)
    {
        $this->assertOwnership($request, $cert);
        $bytes = $this->renderCertificadoFPDF($cert->matricula->curso, $cert->matricula, $cert);

        $filename = "certificado-{$cert->matricula->aluno_id}-{$cert->codigo_verificacao}.pdf";
        return response($bytes, 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$filename.'"',
        ]);
    }

// DOWNLOAD
    public function download(Request $request, Certificados $cert)
    {
        $this->assertOwnership($request, $cert);
        $bytes = $this->renderCertificadoFPDF($cert->matricula->curso, $cert->matricula, $cert);

        $filename = "certificado-{$cert->matricula->aluno_id}-{$cert->codigo_verificacao}.pdf";
        return response($bytes, 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }


}

