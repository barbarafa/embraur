<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Aula extends Model
{
    protected $fillable = [
        'modulo_id','titulo','tipo','duracao_min','conteudo','ordem'
    ];

    public function modulo(): BelongsTo
    {
        return $this->belongsTo(Modulo::class);
    }

    public function medias(): HasMany
    {
        return $this->hasMany(AulaMedia::class);
    }
}
