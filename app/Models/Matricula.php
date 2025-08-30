<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Matricula extends Model
{
    protected $fillable = ['aluno_id','curso_id'];

    public function aluno(){ return $this->belongsTo(Aluno::class); }
    public function curso(){ return $this->belongsTo(Curso::class); }
}
