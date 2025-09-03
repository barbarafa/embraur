<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Modulo extends Model
{
    protected $fillable = ['curso_id','titulo','descricao','ordem'];

    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class);
    }

    public function aulas(): HasMany
    {
        return $this->hasMany(Aula::class)->orderBy('ordem');
    }
}
