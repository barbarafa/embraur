<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aulas extends Model
{
    protected $fillable = [
        'modulo_id', 'titulo', 'descricao', 'tipo_conteudo', 'url_video',
        'duracao_minutos', 'ordem', 'materiais_apoio', 'liberada'
    ];

    protected $casts = [
        'materiais_apoio' => 'array',
        'liberada' => 'boolean'
    ];

    public function modulo()
    {
        return $this->belongsTo(Modulo::class);
    }

    public function progressos()
    {
        return $this->hasMany(ProgressoAula::class);
    }
}
