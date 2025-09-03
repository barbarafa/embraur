<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Duvida extends Model
{
    protected $fillable = [
        'curso_id','aluno_id','professor_id','assunto','texto','lida'
    ];

    protected $casts = [
        'lida' => 'boolean',
    ];

    public function curso(): BelongsTo { return $this->belongsTo(Curso::class); }
    public function aluno(): BelongsTo { return $this->belongsTo(Aluno::class); }
    public function professor(): BelongsTo { return $this->belongsTo(Professor::class); }
}
