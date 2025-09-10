<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modulos extends Model
{
    use HasFactory;

    protected $table = 'modulos';
    public $timestamps = false;

    protected $fillable = [
        'curso_id',
        'titulo',
        'descricao',
        'ordem',
    ];

    /* ----------------------------- RELACIONAMENTOS ----------------------------- */

    public function curso()
    {
        return $this->belongsTo(Cursos::class, 'curso_id');
    }

    public function aulas()
    {
        return $this->hasMany(Aulas::class, 'modulo_id')->orderBy('ordem');
    }

    // Se cada módulo tem uma prova:
    public function quiz()
    {
        return $this->hasOne(Quiz::class, 'modulo_id');
    }

    // Se preferir suportar múltiplos quizzes por módulo, troque por hasMany().
}
