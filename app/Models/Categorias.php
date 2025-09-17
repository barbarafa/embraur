<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categorias extends Model
{
    protected $table = 'categorias';
    public $timestamps = false;

    protected $fillable = ['nome','descricao','icone','ordem_exibicao'];

    public function cursos(){ return $this->hasMany(Cursos::class, 'categoria_id'); }
}
