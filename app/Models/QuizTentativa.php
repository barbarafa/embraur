<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizTentativa extends Model
{

    protected $table = 'quiz_tentativas';
    public $timestamps = false;

    protected $fillable = [
        'quiz_id',
        'aluno_id',
        'matricula_id',
        'nota_obtida',   // pontos obtidos (brutos)
        'nota_maxima',   // pontos possíveis (brutos)
        'aprovado',      // bool
        'concluido_em',  // datetime
    ];

    protected $casts = [
        'nota_obtida'  => 'float',
        'nota_maxima'  => 'float',
        'aprovado'     => 'boolean',
        'concluido_em' => 'datetime',
    ];

    /* ----------------------------- RELACIONAMENTOS ----------------------------- */

    public function quiz()
    {
        return $this->belongsTo(Quiz::class, 'quiz_id');
    }

    public function matricula()
    {
        return $this->belongsTo(Matriculas::class, 'matricula_id');
    }

    public function respostas()
    {
        return $this->hasMany(QuizResposta::class, 'tentativa_id');
    }
}
