<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Aluno extends Model
{
    protected $table = 'alunos';

    protected $fillable = ['nome','email','password'];

    protected $hidden = ['password','remember_token'];

    public function matriculas(): HasMany
    {
        return $this->hasMany(Matricula::class);
    }
}
