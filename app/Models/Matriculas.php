<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Cursos;         // <-- ajuste para App\Models\Curso se seu model for singular
use App\Models\ProgressoAula;  // <-- garante o import do model de progresso

class Matriculas extends Model
{
    protected $table = 'matriculas';
    public $timestamps = false;

    protected $fillable = [
        'aluno_id','curso_id','data_matricula','data_inicio','data_conclusao','data_vencimento',
        'progresso_porcentagem','status','nota_final'
    ];

    protected $casts = [
        'data_matricula' => 'datetime',
        'data_inicio'    => 'datetime',
        'data_conclusao' => 'datetime',
        'data_vencimento'=> 'datetime',
    ];

    // aluno_id -> users.id
    public function aluno()
    {
        return $this->belongsTo(User::class, 'aluno_id');
    }

    // curso_id -> cursos.id  (ou 'curso_id' -> 'curso.id' se seu model/tabela for singular)
    public function curso()
    {
        return $this->belongsTo(Cursos::class, 'curso_id'); // troque para Curso::class se o model for singular
    }

    public function certificado()
    {
        return $this->hasMany(Certificados::class, 'matricula_id'); // troque para Curso::class se o model for singular
    }

    // progresso_aulas.matricula_id -> matriculas.id
    public function progressoAulas()
    {
        return $this->hasMany(ProgressoAula::class, 'matricula_id');
    }
}
