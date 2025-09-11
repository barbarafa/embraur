<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgressoAula extends Model
{
    protected $table = 'progresso_aulas';

    protected $fillable = [
        'matricula_id',
        'aula_id',
        'tempo_assistido_segundos',
        'porcentagem_assistida',
        'concluida',
        'data_inicio',
        'data_fim',
    ];

    public $timestamps = true; // deixe true se sua tabela tiver created_at/updated_at

    public function matricula()
    {
        return $this->belongsTo(Matriculas::class, 'matricula_id');
    }

    public function aula()
    {
        return $this->belongsTo(Aula::class, 'aula_id');
    }
}
