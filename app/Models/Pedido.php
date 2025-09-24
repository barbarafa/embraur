<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


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
        'cupom_id',
        'desconto_total'
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


    /** Receita total (itens de cursos do professor) apenas com pedidos pagos */
    public static function receitaTotalProfessor(int $profId): float
    {
        return (float) DB::table('pedidos as p')
            ->join('itens_pedido as ip','ip.pedido_id','=','p.id')
            ->join('cursos as c','c.id','=','ip.curso_id')
            ->where('c.professor_id',$profId)
            ->where('p.status','pago')
            ->sum('ip.subtotal');
    }

    /** Receita do mês corrente (com base em data_pagamento, senão data_pedido) */
    public static function receitaMesProfessor(int $profId): float
    {
        [$ini, $fim] = [now()->startOfMonth(), now()->endOfMonth()];

        // Se houver data_pagamento, priorize-a; senão, usa data_pedido
        $colData = DB::getSchemaBuilder()->hasColumn('pedidos','data_pagamento') ? 'p.data_pagamento' : 'p.data_pedido';

        return (float) DB::table('pedidos as p')
            ->join('itens_pedido as ip','ip.pedido_id','=','p.id')
            ->join('cursos as c','c.id','=','ip.curso_id')
            ->where('c.professor_id',$profId)
            ->where('p.status','pago')
            ->whereBetween($colData, [$ini, $fim])
            ->sum('ip.subtotal');
    }
}
