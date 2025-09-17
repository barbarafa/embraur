<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 30px; }
        body { font-family: DejaVu Sans, Arial, Helvetica, sans-serif; color:#1f2937; }
        .wrap { border: 8px solid #2563eb; padding: 30px; height: 520px; position: relative; }
        .title { text-align:center; font-size: 42px; font-weight: 800; letter-spacing:1px; color:#111827; margin-top: 10px;}
        .subtitle { text-align:center; color:#374151; margin-top: 6px; }
        .name { text-align:center; font-size: 32px; margin: 40px 0 10px; font-weight: 700; }
        .course { text-align:center; font-size: 20px; }
        .meta { position:absolute; bottom:30px; left:30px; right:30px; font-size: 12px; color:#374151; }
        .meta td { padding-right: 20px; }
        .brand { position:absolute; top:30px; left:30px; font-weight:700; color:#2563eb; }
    </style>
</head>
<body>
<div class="wrap">
    <div class="brand">Embraur Cursos</div>
    <div class="title">CERTIFICADO</div>
    <div class="subtitle">Concluído com êxito</div>

    <div class="name">{{ $aluno->name }}</div>

    <div class="course">
        concluiu o curso <strong>“{{ $curso->titulo }}”</strong>
        em {{ optional($certificado->data_emissao ?? now())->format('d/m/Y') }}.
    </div>

    <table class="meta">
        <tr>
            <td><strong>Código:</strong> {{ $certificado->codigo_verificacao }}</td>
            <td><strong>Carga horária:</strong> {{ (int)($curso->carga_horaria_total ?? 0) }}h</td>
            <td><strong>Matrícula:</strong> #{{ $matricula->id }}</td>
            <td><strong>Validade:</strong> {{ ($certificado->valido ?? true) ? 'Válido' : 'Inválido' }}</td>
        </tr>
        <tr>
{{--            <td colspan="4"><strong>Verificação:</strong> {{ route('certificados.verify', $certificado->codigo_verificacao) }}</td>--}}
        </tr>
    </table>
</div>
</body>
</html>
