<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Aluno extends Authenticatable
{
    protected $table = 'alunos';

    protected $fillable = ['nome','email','password'];

    protected $hidden = ['password','remember_token'];

    public function matriculas(): HasMany
    {
        return $this->hasMany(Matricula::class);
    }
}
