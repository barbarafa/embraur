<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\Auth\AlunoAuthController;
use App\Http\Controllers\Aluno\DashboardController;
use App\Http\Controllers\Aluno\MatriculaController;
use App\Http\Controllers\Auth\ProfessorAuthController;
use App\Http\Controllers\Professor\CursoAdminController;

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


// Portal do Professor (auth)
Route::prefix('prof')->name('prof.')->group(function () {
    Route::get('login', [ProfessorAuthController::class, 'showLogin'])->name('login');
    Route::post('login', [ProfessorAuthController::class, 'login'])->name('login.do');
    Route::post('logout', [ProfessorAuthController::class, 'logout'])->name('logout');

    Route::middleware('prof.auth')->group(function () {
        Route::get('cursos', [CursoAdminController::class, 'index'])->name('cursos.index');
        Route::get('cursos/criar', [CursoAdminController::class, 'create'])->name('cursos.create');
        Route::post('cursos', [CursoAdminController::class, 'store'])->name('cursos.store');
        Route::get('cursos/{curso}/editar', [CursoAdminController::class, 'edit'])->name('cursos.edit');
        Route::put('cursos/{curso}', [CursoAdminController::class, 'update'])->name('cursos.update');
        Route::delete('cursos/{curso}', [CursoAdminController::class, 'destroy'])->name('cursos.destroy');
    });
});
