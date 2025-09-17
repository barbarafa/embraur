<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerfilProfessor extends Model
{
    protected $table = 'perfil_professor';
    public $timestamps = false;

    protected $fillable = [
        'usuario_id','especialidade','biografia','curriculo_url','aprovado'
    ];

    public function usuario(){ return $this->belongsTo(User::class, 'usuario_id'); }
}
