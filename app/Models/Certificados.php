<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificados extends Model
{
    use HasFactory;

    protected $table = 'certificados';

    protected $fillable = [
        'matricula_id',
        'codigo_validacao',
        'data_emissao',
        'arquivo_pdf',
        'horas_cursadas',
        'ativo'
    ];

    protected $casts = [
        'data_emissao' => 'datetime',
        'ativo' => 'boolean',
        'horas_cursadas' => 'integer'
    ];

    // Relacionamentos
    public function matricula()
    {
        return $this->belongsTo(Matricula::class);
    }

    public function user()
    {
        return $this->hasOneThrough(User::class, Matricula::class, 'id', 'id', 'matricula_id', 'user_id');
    }

    public function curso()
    {
        return $this->hasOneThrough(Curso::class, Matricula::class, 'id', 'id', 'matricula_id', 'curso_id');
    }
}
