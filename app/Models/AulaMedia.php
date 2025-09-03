<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AulaMedia extends Model
{
    protected $fillable = ['aula_id','tipo','path','url'];

    public function aula(): BelongsTo
    {
        return $this->belongsTo(Aula::class);
    }
}
