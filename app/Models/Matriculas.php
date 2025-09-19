<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Cursos;         // <-- ajuste para App\Models\Curso se seu model for singular
use App\Models\ProgressoAula;
use Illuminate\Support\Facades\DB;

// <-- garante o import do model de progresso

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


    public function scopeDoProfessor($q, int $profId)
    {
        return $q->join('cursos','cursos.id','=','matriculas.curso_id')
            ->where('cursos.professor_id',$profId);
    }

    /** Percentual concluído baseado em QUIZZES respondidos do aluno neste curso */
    public function percentQuizzes(): int
    {
        $cursoId = (int) $this->curso_id;

        // total de quizzes do curso
        $total = (int) DB::table('quizzes')
            ->where('curso_id', $cursoId)
            ->count('id');

        if ($total === 0) return 0;

        // quizzes executados pelo aluno (houve conclusão)
        $ok = (int) DB::table('quiz_tentativas as qt')
            ->join('quizzes as q', 'q.id', '=', 'qt.quiz_id')
            ->where('q.curso_id', $cursoId)
            ->where('qt.aluno_id', $this->aluno_id)
            ->whereNotNull('qt.concluido_em')
            ->distinct('qt.quiz_id')
            ->count('qt.quiz_id');

        return (int) round(min(100, ($ok / $total) * 100));
    }

    public function lastQuizAt(): ?Carbon
    {
        $cursoId = (int) $this->curso_id;

        $ts = DB::table('quiz_tentativas as qt')
            ->join('quizzes as q', 'q.id', '=', 'qt.quiz_id')
            ->where('q.curso_id', $cursoId)
            ->where('qt.aluno_id', $this->aluno_id)
            ->whereNotNull('qt.concluido_em')
            ->max('qt.concluido_em');

        if ($ts) return Carbon::parse($ts);

        return $this->data_matricula instanceof Carbon ? $this->data_matricula : null;
    }
}
