<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialApoio extends Model
{
    protected $table = 'materiais_apoio';
    public $timestamps = false;

    protected $fillable = ['aula_id','nome_arquivo','tipo_arquivo','url_download','tamanho_kb'];

    public function aula(){ return $this->belongsTo(Aula::class, 'aula_id'); }
}
