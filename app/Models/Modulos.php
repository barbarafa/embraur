<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modulos extends Model
{
    protected $table = 'modulos';
    public $timestamps = false;

    protected $fillable = ['curso_id','titulo','descricao','ordem'];

    public function curso(){ return $this->belongsTo(Cursos::class, 'curso_id'); }
    public function aulas(){ return $this->hasMany(Aulas::class, 'modulo_id')->orderBy('ordem'); }
}
