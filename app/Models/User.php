<?php

namespace App\Models;




use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model implements Authenticatable
{


    protected $table = 'users';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    const CREATED_AT = 'data_criacao';
    const UPDATED_AT = 'data_atualizacao';

    protected $fillable = [
        'email','password','nome_completo','telefone','cpf','data_nascimento','foto_perfil',
        'tipo_usuario','status',
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'data_nascimento' => 'date:Y-m-d',
        'data_criacao' => 'datetime',
        'data_atualizacao' => 'datetime',
    ];

    public function setPasswordAttribute($value)
    {
        if (!$value) return;
        if (is_string($value) && preg_match('/^\$2[ayb]\$.{56}$/', $value)) {
            $this->attributes['password'] = $value;
        } else {
            $this->attributes['password'] = bcrypt($value);
        }
    }

    public function getNameAttribute()
    {
        return $this->nome_completo;
    }

    // Perfis
    public function perfilAluno()     { return $this->hasOne(PerfilAluno::class, 'usuario_id'); }
    public function perfilProfessor() { return $this->hasOne(PerfilProfessor::class, 'usuario_id'); }

    // Cursos.php (como professor)
    public function cursosMinistrados(){ return $this->hasMany(Cursos::class, 'professor_id'); }

    // Matrículas (como aluno)
    public function matriculas()      { return $this->hasMany(Matriculas::class, 'aluno_id'); }

    public function cursos()
    {
        // pega cursos via pivot 'matriculas' e expõe campos úteis da matrícula
        return $this->belongsToMany(Cursos::class, 'matriculas', 'aluno_id', 'curso_id')
            ->withPivot(['progresso_porcentagem', 'status', 'data_matricula']);
    }

    public function getAuthIdentifierName()
    {
        // TODO: Implement getAuthIdentifierName() method.
    }

    public function getAuthIdentifier()
    {
        // TODO: Implement getAuthIdentifier() method.
    }

    public function getAuthPasswordName()
    {
        // TODO: Implement getAuthPasswordName() method.
    }

    public function getAuthPassword()
    {
        // TODO: Implement getAuthPassword() method.
    }

    public function getRememberToken()
    {
        // TODO: Implement getRememberToken() method.
    }

    public function setRememberToken($value)
    {
        // TODO: Implement setRememberToken() method.
    }

    public function getRememberTokenName()
    {
        // TODO: Implement getRememberTokenName() method.
    }
}
