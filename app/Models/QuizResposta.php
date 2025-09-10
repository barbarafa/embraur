<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizResposta extends Model
{
    use HasFactory;

    protected $table = 'quiz_respostas';
    public $timestamps = false;

    protected $fillable = [
        'tentativa_id',
        'questao_id',
        'opcao_id',          // nullable (quando discursiva)
        'resposta_texto',    // nullable
        'pontuacao_obtida',  // float
    ];

    protected $casts = [
        'pontuacao_obtida' => 'float',
    ];

    /* ----------------------------- RELACIONAMENTOS ----------------------------- */

    public function tentativa()
    {
        return $this->belongsTo(QuizTentativa::class, 'tentativa_id');
    }

    public function questao()
    {
        return $this->belongsTo(QuizQuestao::class, 'questao_id');
    }

    public function opcao()
    {
        return $this->belongsTo(QuizOpcao::class, 'opcao_id');
    }
}
