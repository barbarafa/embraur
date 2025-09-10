<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cursos extends Model
{
    use HasFactory;

    protected $table = 'cursos';
    public $timestamps = false; // suas migrations não usam created_at/updated_at

    protected $fillable = [
        'professor_id',
        'categoria_id',
        'titulo',
        'descricao_curta',
        'descricao_completa',
        'imagem_capa',
        'video_introducao',
        'nivel',                  // ['iniciante','intermediario','avancado']
        'carga_horaria_total',
        'preco',
        'preco_original',
        'nota_minima_aprovacao',  // usado para travar/destravar módulos (>= 7.0)
        'maximo_alunos',
        'status_publicacao',      // ex.: 'rascunho','publicado','oculto'
        'slug',
    ];

    protected $casts = [
        'preco'                  => 'decimal:2',
        'preco_original'         => 'decimal:2',
        'carga_horaria_total'    => 'integer',
        'nota_minima_aprovacao'  => 'float',
        'maximo_alunos'          => 'integer',
    ];

    /* ----------------------------- RELACIONAMENTOS ----------------------------- */

    public function instrutor()
    {
        return $this->belongsTo(User::class, 'professor_id');
    }

    public function categoria()
    {
        return $this->belongsTo(Categorias::class, 'categoria_id');
    }

    public function modulos()
    {
        return $this->hasMany(Modulos::class, 'curso_id')->orderBy('ordem');
    }

    public function matriculas()
    {
        return $this->hasMany(Matricula::class, 'curso_id');
    }

    /**
     * Todas as aulas do curso via módulos (usado para withCount('aulas as aulas_total')).
     */
    public function aulas()
    {
        return $this->hasManyThrough(
            Aulas::class,    // model final
            Modulos::class,  // model intermediário
            'curso_id',      // FK em Modulos -> cursos.id
            'modulo_id',     // FK em Aulas -> modulos.id
            'id',            // PK em Cursos
            'id'             // PK em Modulos
        );
    }

    /**
     * Quizzes do curso (cada módulo pode ter um quiz).
     */
    public function quizzes()
    {
        return $this->hasManyThrough(
            Quiz::class,     // model final
            Modulos::class,  // model intermediário
            'curso_id',      // FK em Modulos
            'modulo_id',     // FK em Quiz
            'id',            // PK em Cursos
            'id'             // PK em Modulos
        );
    }

    /* --------------------------------- SCOPES --------------------------------- */

    public function scopePublicados($query)
    {
        return $query->where('status_publicacao', 'publicado');
    }

    /* ------------------------------- ACCESSORS -------------------------------- */

    public function getImagemCapaUrlAttribute()
    {
        return $this->imagem_capa ? asset('storage/' . $this->imagem_capa) : null;
    }

    /* ------------------------- HELPERS/CONSULTAS ÚTEIS ------------------------ */

    /**
     * Lista cursos do aluno com informações de matrícula e contagem de aulas.
     */
    public static function getCursosByAlunoId($alunoId)
    {
        return self::query()
            ->select(
                'cursos.*',
                'matriculas.progresso_porcentagem',
                'matriculas.status',
                'matriculas.data_matricula'
            )
            ->join('matriculas', 'matriculas.curso_id', '=', 'cursos.id')
            ->where('matriculas.aluno_id', $alunoId)
            ->with(['categoria'])
            ->withCount('aulas as aulas_total') // usa o relacionamento aulas()
            ->orderByDesc('matriculas.data_matricula')
            ->get();
    }

    /**
     * (Opcional) Se quiser usar slug nas rotas: habilite e ajuste Route Model Binding.
     */
    // public function getRouteKeyName(): string
    // {
    //     return 'slug';
    // }
}
