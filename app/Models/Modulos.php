<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modulo extends Model
{
    protected $table = 'modulos';
    public $timestamps = false;

    protected $fillable = ['curso_id','titulo','descricao','ordem'];

    public function curso(){ return $this->belongsTo(Curso::class, 'curso_id'); }
    public function aulas(){ return $this->hasMany(Aula::class, 'modulo_id')->orderBy('ordem'); }
}
