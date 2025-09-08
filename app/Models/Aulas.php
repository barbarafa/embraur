<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aulas extends Model
{
    protected $table = 'aulas';
    public $timestamps = false;

    protected $fillable = [
        'modulo_id','titulo','descricao','tipo','duracao_minutos',
        'conteudo_url','conteudo_texto','ordem','liberada_apos_anterior'
    ];

    protected $casts = ['liberada_apos_anterior' => 'boolean'];

    public function modulo(){ return $this->belongsTo(Modulos::class, 'modulo_id'); }
    public function materiais(){ return $this->hasMany(MaterialApoio::class, 'aula_id'); }
    public function questoes(){ return $this->hasMany(Questao::class, 'aula_id'); }
}
