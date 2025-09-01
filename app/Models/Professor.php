<?php

namespace App\Models;



use Illuminate\Database\Eloquent\Model;

class Professor extends Model
{
    protected $table = 'professores';
    protected $fillable = ['nome','email','password'];
    protected $hidden = ['password','remember_token'];
}

