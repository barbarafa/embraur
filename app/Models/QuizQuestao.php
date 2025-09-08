<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizQuestao extends Model
{
    protected $table = 'quiz_questoes';
    protected $fillable = ['quiz_id','enunciado','tipo','pontuacao'];

    public function quiz(){ return $this->belongsTo(Quiz::class,'quiz_id'); }
    public function opcoes(){ return $this->hasMany(QuizOpcao::class,'questao_id'); }
}
