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

  //['iniciante', 'intermediario', 'avancado']


    // RELS
    public function instrutor(){ return $this->belongsTo(User::class, 'professor_id'); }
    public function categoria(){ return $this->belongsTo(Categorias::class, 'categoria_id'); }
    public function modulos()  { return $this->hasMany(Modulo::class, 'curso_id')->orderBy('ordem'); }
//    public function tags()     { return $this->hasMany(TagCurso::class, 'curso_id'); }
    public function matriculas(){ return $this->hasMany(Matricula::class, 'curso_id'); }

    public function getImagemCapaUrlAttribute()
    {
        return $this->imagem_capa
            ? asset('storage/'.$this->imagem_capa)
            : null; // ou um placeholder: asset('images/placeholder-16x9.jpg')
    }
}
