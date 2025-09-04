<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgressoAulas extends Model
{
    protected $table = 'progresso_aulas';
    public $timestamps = false;

    protected $fillable = [
        'matricula_id','aula_id','tempo_assistido_segundos','porcentagem_assistida',
        'concluida','data_inicio','data_conclusao'
    ];

    protected $casts = ['data_inicio'=>'datetime', 'data_conclusao'=>'datetime'];

    public function matricula(){ return $this->belongsTo(Matricula::class, 'matricula_id'); }
    public function aula()     { return $this->belongsTo(Aula::class, 'aula_id'); }
}
