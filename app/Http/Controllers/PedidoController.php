<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\ItemPedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PedidoController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->get('user_id');
        $q = Pedido::query()->with(['items','payments','address']);
        if ($userId) $q->where('user_id', $userId);
        return $q->orderByDesc('id')->paginate(50);
    }

    public function show(Pedido $pedido)
    {
        return $pedido->load(['items.modalidade','items.curso','payments']);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'endereco_id' => 'nullable|exists:enderecos,id',
            'itens' => 'required|array|min:1',
            'itens.*.curso_id' => 'required|exists:cursos,id',
            'itens.*.modalidade_id' => 'required|exists:modalidades,id',
            'itens.*.quantidade' => 'nullable|integer|min:1',
            'itens.*.preco_unitario' => 'required|numeric|min:0',
        ]);

        return DB::transaction(function () use ($data) {
            $total = collect($data['itens'])->sum(fn($i) => ($i['preco_unitario'] * ($i['quantidade'] ?? 1)));
            $pedido = Pedido::create([
                'user_id' => $data['user_id'],
                'endereco_id' => $data['endereco_id'] ?? null,
                'total' => $total,
                'status' => 'pendente',
            ]);

            foreach ($data['itens'] as $i) {
                ItemPedido::create([
                    'pedido_id' => $pedido->id,
                    'curso_id' => $i['curso_id'],
                    'modalidade_id' => $i['modalidade_id'],
                    'quantidade' => $i['quantidade'] ?? 1,
                    'preco_unitario' => $i['preco_unitario'],
                    'subtotal' => $i['preco_unitario'] * ($i['quantidade'] ?? 1),
                ]);
            }

            return response()->json($pedido->load('items'), 201);
        });
    }

    public function update(Request $request, Pedido $pedido)
    {
        $data = $request->validate([
            'status' => 'in:pendente,pago,cancelado,falhou',
            'endereco_id' => 'nullable|exists:enderecos,id',
        ]);
        $pedido->update($data);
        return $pedido->fresh(['items','payments']);
    }

    public function destroy(Pedido $pedido)
    {
        $pedido->delete();
        return response()->noContent();
    }
}
