<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TagCursos extends Model
{
    protected $table = 'tags_curso';
    public $timestamps = false;

    protected $fillable = ['curso_id','tag'];
    public function curso(){ return $this->belongsTo(Curso::class, 'curso_id'); }
}
