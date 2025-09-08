<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cursos extends Model
{
    protected $table = 'cursos';
    public $timestamps = false; // migrations usam campos custom, sem updated_at/created_at

    protected $fillable = [
        'professor_id','categoria_id','titulo','descricao_curta','descricao_completa',
        'imagem_capa','video_introducao','nivel','carga_horaria_total','preco','preco_original',
        'nota_minima_aprovacao','maximo_alunos','status_publicacao','slug'
    ];

    protected $casts = [
        'preco' => 'decimal:2',
        'preco_original' => 'decimal:2',
        'carga_horaria_total' => 'integer',
    ];

     //cursos.nivel ['iniciante', 'intermediario', 'avancado']



    public function instrutor(){ return $this->belongsTo(User::class, 'professor_id'); }
    public function categoria(){ return $this->belongsTo(Categorias::class, 'categoria_id'); }
    public function modulos()  { return $this->hasMany(Modulos::class, 'curso_id')->orderBy('ordem'); }
//    public function tags()     { return $this->hasMany(TagCurso::class, 'curso_id'); }
    public function matriculas(){ return $this->hasMany(Matricula::class, 'curso_id'); }

    public function getImagemCapaUrlAttribute()
    {
        return $this->imagem_capa
            ? asset('storage/'.$this->imagem_capa)
            : null; // ou um placeholder: asset('images/placeholder-16x9.jpg')
    }

    public function aulas()
    {
        return $this->hasManyThrough(
            Aulas::class,      // model final
            Modulos::class,    // model intermediário
            'curso_id',        // FK em Modulos -> cursos.id
            'modulo_id',       // FK em Aulas -> modulos.id
            'id',              // PK em Cursos
            'id'               // PK em Modulos
        );
    }

    public static function getCursosByAlunoId($alunoId)
    {
        return self::query()
            ->select('cursos.*',
                'matriculas.progresso_porcentagem',
                'matriculas.status',
                'matriculas.data_matricula'
            )
            ->join('matriculas', 'matriculas.curso_id', '=', 'cursos.id')
            ->where('matriculas.aluno_id', $alunoId)
            ->with(['categoria'])
            ->withCount('aulas as aulas_total') // exige o relacionamento aulas() mostrado na opção A
            ->orderByDesc('matriculas.data_matricula')
            ->get();
    }
}
