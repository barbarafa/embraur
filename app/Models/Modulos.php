<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modulos extends Model
{
    protected $fillable = [
        'curso_id', 'titulo', 'descricao', 'ordem', 'duracao_estimada'
    ];

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    public function aulas()
    {
        return $this->hasMany(Aula::class)->orderBy('ordem');
    }
}
