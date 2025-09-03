<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgressoAula extends Model
{
    use HasFactory;

    protected $table = 'progresso_aulas';

    protected $fillable = [
        'matricula_id',
        'aula_id',
        'tempo_assistido',
        'concluida',
        'data_conclusao',
        'anotacoes'
    ];

    protected $casts = [
        'concluida' => 'boolean',
        'data_conclusao' => 'datetime',
        'tempo_assistido' => 'integer'
    ];

    // Relacionamentos
    public function matricula()
    {
        return $this->belongsTo(Matricula::class);
    }

    public function aula()
    {
        return $this->belongsTo(Aula::class);
    }
}
