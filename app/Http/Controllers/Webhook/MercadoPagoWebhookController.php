<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Models\{Pagamentos, Matriculas, Cursos};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MercadoPagoWebhookController extends Controller
{
    public function __invoke(Request $rq)
    {
        abort_if($rq->query('secret') !== env('MP_WEBHOOK_SECRET'), 403);

        $topic = $rq->input('type') ?? $rq->input('topic');
        $data = $rq->input();

        // Você pode consultar o pagamento com o SDK pelo ID; aqui usamos o payload direto
        $paymentId = data_get($data, 'data.id') ?? data_get($data, 'id');
        $status = data_get($data, 'data.status') ?? data_get($data, 'status');
        $externalRef = data_get($data, 'data.external_reference') ?? data_get($data, 'external_reference');

        if (!$externalRef) return response()->json(['ignored'=>true]);

        DB::transaction(function() use ($externalRef, $paymentId, $status, $data) {
            $pg = Pagamentos::where('external_reference', $externalRef)->lockForUpdate()->first();
            if (!$pg) return;

            $pg->update([
                'status' => $status ?? 'desconhecido',
                'mp_payment_id' => $paymentId,
                'raw_payload' => $data,
            ]);

            if (in_array($status, ['approved','accredited','succeeded','approved_partially'])) {
                // Criar matrículas para todos os cursos presentes originalmente
                // Para simplificar: assuma que você salvou os itens no Pagamentos (poderia ter uma tabela pagamento_itens).
                // Alternativa simples: um pagamento = 1 curso; se for carrinho, amplie com pagamento_itens.
            }
        });

        return response()->json(['ok'=>true]);
    }
}
