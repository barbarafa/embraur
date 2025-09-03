<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Matricula extends Model
{
    protected $fillable = [
        'aluno_id', 'curso_id', 'data_matricula', 'data_conclusao',
        'status', 'progresso_geral', 'tempo_assistido', 'certificado_emitido'
    ];

    protected $casts = [
        'data_matricula' => 'datetime',
        'data_conclusao' => 'datetime',
        'certificado_emitido' => 'boolean'
    ];

    public function aluno()
    {
        return $this->belongsTo(User::class, 'aluno_id');
    }

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    public function progressoAulas()
    {
        return $this->hasMany(ProgressoAula::class);
    }
}
