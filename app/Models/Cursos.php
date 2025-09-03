<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cursos extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo', 'descricao', 'descricao_curta', 'categoria_id', 'instrutor_id',
        'nivel', 'preco', 'preco_original', 'duracao_horas', 'max_alunos',
        'imagem_capa', 'video_promocional', 'tags', 'status', 'data_lancamento',
        'data_atualizacao', 'avaliacao_media', 'total_avaliacoes'
    ];

    protected $casts = [
        'tags' => 'array',
        'data_lancamento' => 'datetime',
        'data_atualizacao' => 'datetime'
    ];

    public function categoria()
    {
        return $this->belongsTo(Categorias::class);
    }

    public function instrutor()
    {
        return $this->belongsTo(User::class, 'instrutor_id');
    }

    public function modulos()
    {
        return $this->hasMany(Modulo::class)->orderBy('ordem');
    }

    public function matriculas()
    {
        return $this->hasMany(Matricula::class);
    }

    public function avaliacoes()
    {
        return $this->hasMany(Avaliaca::class);
    }
}
