<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizOpcao extends Model
{
    protected $table = 'quiz_opcoes';
    protected $fillable = ['questao_id','texto','correta'];

    public function questao(){ return $this->belongsTo(QuizQuestao::class,'questao_id'); }
}
