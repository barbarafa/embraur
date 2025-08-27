<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Pagamento;
use App\Models\Matricula;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PagamentoController extends Controller
{
    public function index(Request $request)
    {
        $pedidoId = $request->get('pedido_id');
        $q = Pagamento::query()->with('order');
        if ($pedidoId) $q->where('pedido_id', $pedidoId);
        return $q->orderByDesc('id')->paginate(50);
    }

    public function show(Pagamento $pagamento)
    {
        return $pagamento->load('order');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'pedido_id' => 'required|exists:pedidos,id',
            'metodo' => 'required|in:pix,cartao,boleto',
            'status' => 'nullable|in:pendente,aprovado,recusado,estornado',
            'valor' => 'required|numeric|min:0',
            'payload_gateway' => 'nullable|array',
        ]);

        $p = Pagamento::create([
            ...$data,
            'payload_gateway' => $data['payload_gateway'] ?? null,
        ]);

        return response()->json($p, 201);
    }

    public function update(Request $request, Pagamento $pagamento)
    {
        $data = $request->validate([
            'status' => 'in:pendente,aprovado,recusado,estornado',
            'pago_em' => 'nullable|date',
            'payload_gateway' => 'nullable|array',
        ]);

        $pagamento->update([
            ...$data,
            'payload_gateway' => $data['payload_gateway'] ?? $pagamento->payload_gateway,
        ]);

        return $pagamento->fresh('order');
    }

    // Webhook do Mercado Pago (exemplo simples)
    public function webhook(Request $request)
    {
        // TODO: validar assinatura do provedor
        $tipo = $request->get('type'); // payment
        $dados = $request->all();

        if ($tipo !== 'payment') return response()->noContent();

        return DB::transaction(function () use ($dados) {
            // encontre o pedido a partir do payload (ajuste conforme seu mapeamento)
            $pedido = Pedido::where('referencia_gateway', $dados['data']['id'] ?? null)->first();
            if (!$pedido) return response()->noContent();

            // atualiza pagamento + pedido
            $status = ($dados['status'] ?? 'pending') === 'approved' ? 'pago' : 'pendente';
            $pedido->update(['status' => $status]);

            if ($status === 'pago') {
                // liberar matrículas para cada item do pedido
                foreach ($pedido->items as $item) {
                    Matricula::firstOrCreate([
                        'user_id' => $pedido->user_id,
                        'curso_id' => $item->curso_id,
                        'modalidade_id' => $item->modalidade_id,
                        'pedido_id' => $pedido->id,
                    ], [
                        'status' => 'ativa',
                        'progresso' => 0,
                    ]);
                }
            }

            return response()->noContent();
        });
    }

    public function destroy(Pagamento $pagamento)
    {
        $pagamento->delete();
        return response()->noContent();
    }
}
