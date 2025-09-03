<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Aluno extends Model
{
    protected $fillable = ['nome','email','password']; // ajuste aos seus campos

    public function matriculas(): HasMany
    {
        return $this->hasMany(Matricula::class);
    }
}
