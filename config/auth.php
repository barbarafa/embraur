<?php

use App\Models\User;
use App\Models\Aluno;
use App\Models\Professor;

return [

    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        // 👇 novo guard do aluno
        'aluno' => [
            'driver' => 'session',
            'provider' => 'alunos',
        ],

        // 👇 novo guard do professor
        'prof' => [
            'driver' => 'session',
            'provider' => 'professores',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => User::class,
        ],

        // 👇 provider do aluno
        'alunos' => [
            'driver' => 'eloquent',
            'model' => Aluno::class,   // crie App\Models\Aluno
        ],

        // 👇 provider do professor
        'professores' => [
            'driver' => 'eloquent',
            'model' => Professor::class, // crie App\Models\Professor
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
        'alunos' => [
            'provider' => 'alunos',
            'table' => 'aluno_password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
        'professores' => [
            'provider' => 'professores',
            'table' => 'prof_password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,
];
