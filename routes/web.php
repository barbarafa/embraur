<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\Auth\AlunoAuthController;
use App\Http\Controllers\Aluno\DashboardController;
use App\Http\Controllers\Aluno\MatriculaController;

// Site
Route::get('/', [HomeController::class, 'index'])->name('site.home');
Route::get('/catalogo', [CursoController::class, 'index'])->name('site.cursos');
Route::get('/curso/{slug}', [CursoController::class, 'show'])->name('site.curso.detalhe');

// Área do Aluno (auth + protegido)
Route::prefix('aluno')->name('aluno.')->group(function () {
    // Auth
    Route::get('login', [AlunoAuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AlunoAuthController::class, 'login'])->name('login.do');
    Route::post('logout', [AlunoAuthController::class, 'logout'])->name('logout');
    Route::get('register', [AlunoAuthController::class, 'showRegisterForm'])->name('register');
    Route::post('register', [AlunoAuthController::class, 'register'])->name('register.do');

    // Protegido
    Route::middleware('aluno.auth')->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('matricular/{curso}', [MatriculaController::class, 'store'])->name('matricular');
    });
});

// Placeholders do menu
Route::view('/portal/aluno', 'site.stub')->name('portal.aluno');
Route::view('/portal/professor', 'site.stub')->name('portal.professor');
