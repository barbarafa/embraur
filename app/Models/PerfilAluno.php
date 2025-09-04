<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerfilAluno extends Model
{
    protected $table = 'perfil_aluno';
    public $timestamps = false;

    protected $fillable = [
        'usuario_id','empresa','cargo','endereco_completo','data_ultimo_acesso'
    ];

    protected $casts = ['data_ultimo_acesso' => 'datetime'];

    public function usuario(){ return $this->belongsTo(User::class, 'usuario_id'); }
}
