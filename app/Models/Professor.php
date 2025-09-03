<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Professor extends Model
{
    protected $fillable = ['nome','email','password']; // ajuste aos seus campos

    public function cursos(): HasMany
    {
        return $this->hasMany(Curso::class);
    }
}
