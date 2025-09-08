<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Matricula extends Model
{
    protected $table = 'matriculas';
    public $timestamps = false;

    protected $fillable = [
        'aluno_id','curso_id','data_matricula','data_inicio','data_conclusao','data_vencimento',
        'progresso_porcentagem','status','nota_final'
    ];

    protected $casts = [
        'data_matricula' => 'datetime', 'data_inicio'=>'datetime',
        'data_conclusao'=>'datetime', 'data_vencimento'=>'datetime'
    ];

    public function aluno(){ return $this->belongsTo(User::class, 'aluno_id'); }
    public function curso(){ return $this->belongsTo(Cursos::class, 'curso_id'); }
    public function progresso(){ return $this->hasMany(ProgressoAula::class, 'matricula_id'); }
}
