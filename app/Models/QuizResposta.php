<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizResposta extends Model
{
    protected $table = 'quiz_respostas';
    protected $fillable = ['tentativa_id','questao_id','opcao_id','resposta_texto','pontuacao_obtida'];

    public function tentativa(){ return $this->belongsTo(QuizTentativa::class,'tentativa_id'); }
    public function questao(){ return $this->belongsTo(QuizQuestao::class,'questao_id'); }
    public function opcao(){ return $this->belongsTo(QuizOpcao::class,'opcao_id'); }
}
