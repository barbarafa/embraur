<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class ItensPedido extends Model
{
    protected $table = 'itens_pedido';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'pedido_id',
        'curso_id',
        'quantidade',
        'preco_unitario',
        'subtotal',
    ];

    protected $casts = [
        'quantidade'    => 'int',
        'preco_unitario'=> 'decimal:2',
        'subtotal'      => 'decimal:2',
    ];

    # RELACIONAMENTOS
    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'pedido_id', 'id');
    }

    public function curso()
    {
        return $this->belongsTo(Cursos::class, 'curso_id', 'id');
    }

    # (Opcional) manter subtotal coerente
    public function fillSubtotal(): void
    {
        $this->subtotal = ($this->quantidade ?? 1) * ($this->preco_unitario ?? 0);
    }
}

