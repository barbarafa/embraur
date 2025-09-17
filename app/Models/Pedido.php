<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Pedido extends Model
{
    protected $table = 'pedidos';
    public $timestamps = false;

    protected $fillable = [
        'aluno_id',
        'valor_total',
        'status', // pendente|pago|cancelado|estornado
        'metodo_pagamento',
        'referencia_pagamento_externa',
        'data_pedido',
        'data_pagamento',
    ];

    protected $casts = [
        'valor_total'      => 'decimal:2',
        'data_pedido'      => 'datetime',
        'data_pagamento'   => 'datetime',
    ];

    # RELACIONAMENTOS
    public function itens()
    {
        return $this->hasMany(ItensPedido::class, 'pedido_id', 'id');
    }

    public function aluno()
    {
        return $this->belongsTo(User::class, 'aluno_id');
    }

    # (Opcional) somar total pelo relacionamento
    public function recalcularTotal(): void
    {
        $total = $this->itens()->sum('subtotal');
        $this->update(['valor_total' => $total]);
    }
}
