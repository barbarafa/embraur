<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AulaProgresso extends Model
{
    protected $table = 'aula_progresso';
    protected $fillable = ['aluno_id','aula_id','segundos_assistidos','duracao_total'];

    public function aula(){ return $this->belongsTo(Aulas::class,'aula_id'); }
    public function aluno(){ return $this->belongsTo(User::class,'aluno_id'); }
}
