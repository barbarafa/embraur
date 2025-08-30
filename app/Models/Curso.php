<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Curso extends Model
{
    protected $fillable = [
        'categoria_id','titulo','descricao','carga_horaria',
        'preco','preco_promocional','nivel','avaliacao','alunos','slug','popular'
    ];

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    public function matriculas(): HasMany
    {
        return $this->hasMany(Matricula::class);
    }

    public function getPrecoFinalAttribute()
    {
        return $this->preco_promocional ?? $this->preco;
    }
}
