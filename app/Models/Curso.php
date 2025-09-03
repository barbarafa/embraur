<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Curso extends Model
{
    protected $fillable = [
        'titulo','slug','resumo','descricao','preco','nivel','carga_horaria',
        'max_alunos','publicado','capa_path','categoria_id','professor_id',
        'tags','estrutura',
    ];

    protected $casts = [
        'publicado' => 'boolean',
        'preco'     => 'decimal:2',
        'tags'      => 'array',
        'estrutura' => 'array',
    ];

    // RELAÇÕES
    public function professor(): BelongsTo
    {
        return $this->belongsTo(Professor::class);
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    public function modulos(): HasMany
    {
        return $this->hasMany(Modulo::class)->orderBy('ordem');
    }

    public function matriculas(): HasMany
    {
        return $this->hasMany(Matricula::class);
    }

    public function duvidas(): HasMany
    {
        return $this->hasMany(Duvida::class);
    }

    // Exemplo de acessor
    public function getPrecoFinalAttribute()
    {
        return $this->preco; // ajuste se tiver preço promocional
    }
}
