<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizTentativa extends Model
{
    protected $table = 'quiz_tentativas';
    protected $fillable = ['quiz_id','aluno_id','matricula_id','nota_obtida','nota_maxima','aprovado','concluido_em'];

    public function quiz(){ return $this->belongsTo(Quiz::class, 'quiz_id'); }
    public function aluno(){ return $this->belongsTo(User::class, 'aluno_id'); }
    public function matricula(){ return $this->belongsTo(Matricula::class, 'matricula_id'); }
    public function respostas(){ return $this->hasMany(QuizResposta::class,'tentativa_id'); }
}
