<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificados extends Model
{

    protected $table = 'certificados';

    protected $fillable = [
        'matricula_id',
        'codigo_verificacao',
        'url_certificado',
        'qr_code_url',
        'data_emissao',
        'valido'
    ];

    public $timestamps = false;

    protected $casts = [
        'data_emissao' => 'datetime',
        'valido' => 'boolean',
    ];

    // Relacionamentos
    public function matricula()
    {
        return $this->belongsTo(Matriculas::class);
    }

    public function user()
    {
        return $this->hasOneThrough(User::class, Matriculas::class, 'id', 'id', 'matricula_id', 'user_id');
    }

    public function curso()
    {
        return $this->hasOneThrough(Cursos::class, Matriculas::class, 'id', 'id', 'matricula_id', 'curso_id');
    }
}
