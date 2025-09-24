<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Cupom extends Model
{
    protected $table = 'cupons';

    protected $fillable = [
        'codigo','tipo','valor','inicio_em','fim_em','ativo'
    ];

    protected $casts = [
        'inicio_em' => 'datetime',
        'fim_em'    => 'datetime',
        'ativo'     => 'boolean',
        'valor'     => 'decimal:2',
    ];

    // Sempre trabalhar com código em UPPER
    public function setCodigoAttribute($v): void
    {
        $this->attributes['codigo'] = mb_strtoupper(trim($v ?? ''));
    }

    public function ativoAgora(?Carbon $agora = null): bool
    {
        $agora ??= now();

        if (!$this->ativo) return false;
        if($this->inicio_em != null){
            if ($this->inicio_em && $agora->lt($this->inicio_em)) return false;
        }
        if($this->fim_em != null){
            if ($this->fim_em && $agora->gt($this->fim_em)) return false;
        }


        return true;
    }

    public function calcularDesconto(float $subtotal): float
    {
        if ($subtotal <= 0) return 0.0;

        if ($this->tipo === 'percentual') {
            $desconto = $subtotal * ((float)$this->valor / 100.0);
        } else { // fixo
            $desconto = (float)$this->valor;
        }

        // nunca passar do subtotal
        return round(min($desconto, $subtotal), 2);
    }
}
