<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Pagamentos extends Model
{
    protected $fillable = ['aluno_id','matricula_id','valor','moeda','status','gateway','mp_preference_id','mp_payment_id','external_reference','raw_payload'];

    protected $casts = ['raw_payload' => 'array', 'valor' => 'decimal:2'];

    public function aluno(){ return $this->belongsTo(User::class, 'aluno_id'); }
    public function matricula(){ return $this->belongsTo(Matriculas::class, 'matricula_id'); }
}
