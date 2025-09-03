<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'nome', 'email', 'senha', 'tipo_usuario', 'cpf', 'telefone',
        'data_nascimento', 'endereco', 'cidade', 'estado', 'cep',
        'empresa', 'cargo', 'avatar', 'data_cadastro', 'ativo'
    ];

    protected $hidden = ['senha'];

    public function cursosComoInstrutor()
    {
        return $this->hasMany(Curso::class, 'instrutor_id');
    }

    public function matriculas()
    {
        return $this->hasMany(Matricula::class, 'aluno_id');
    }

    public function anotacoes()
    {
        return $this->hasMany(Anotacao::class, 'aluno_id');
    }

    public function pagamentos()
    {
        return $this->hasMany(Pagamento::class, 'usuario_id');
    }

}
