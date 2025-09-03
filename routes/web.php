<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CursoController;

// ===== ALUNO
use App\Http\Controllers\Auth\AlunoAuthController;
use App\Http\Controllers\Aluno\DashboardController;
use App\Http\Controllers\Aluno\MatriculaController;
use App\Http\Controllers\Aluno\StudentCoursesController;
use App\Http\Controllers\Aluno\StudentCertificatesController;
use App\Http\Controllers\Aluno\StudentPaymentsController;
use App\Http\Controllers\Aluno\StudentProfileController;

// ===== PROFESSOR
use App\Http\Controllers\Auth\ProfessorAuthController;
use App\Http\Controllers\Professor\ProfessorDashboardController;
use App\Http\Controllers\Professor\CursoAdminController;
use App\Http\Controllers\Professor\CursoMediaController;
use App\Http\Controllers\Professor\ModuloAdminController;
use App\Http\Controllers\Professor\AulaAdminController;
use App\Http\Controllers\Professor\DuvidaController;
use App\Http\Controllers\Professor\ProfessorAlunoController;

/*
|--------------------------------------------------------------------------
| Site (público)
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('site.home');
Route::get('/catalogo', [CursoController::class, 'index'])->name('site.cursos');
Route::get('/curso/{slug}', [CursoController::class, 'show'])->name('site.curso.detalhe');

/*
|--------------------------------------------------------------------------
| Área do Aluno
|--------------------------------------------------------------------------
*/
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

        Route::get('cursos', [StudentCoursesController::class, 'index'])->name('cursos');
        Route::get('certificados', [StudentCertificatesController::class, 'index'])->name('certificados');
        Route::get('pagamentos', [StudentPaymentsController::class, 'index'])->name('pagamentos');
        Route::get('perfil', [StudentProfileController::class, 'index'])->name('perfil');
    });
});

/*
|--------------------------------------------------------------------------
| Atalhos de Portal (redirecionam)
|--------------------------------------------------------------------------
*/
Route::get('/portal/aluno', fn () => redirect()->route('aluno.dashboard'))->name('portal.aluno');
Route::get('/portal/professor', fn () => redirect()->route('prof.dashboard'))->name('portal.professor');

/*
|--------------------------------------------------------------------------
| Portal do Professor
|--------------------------------------------------------------------------
*/
Route::prefix('prof')->name('prof.')->group(function () {
    // Auth
    Route::get('login', [ProfessorAuthController::class, 'showLogin'])->name('login');
    Route::post('login', [ProfessorAuthController::class, 'login'])->name('login.do');
    Route::post('logout', [ProfessorAuthController::class, 'logout'])->name('logout');

    // Protegido
    Route::middleware('prof.auth')->group(function () {
        // Dashboard
        Route::get('dashboard', [ProfessorDashboardController::class, 'index'])->name('dashboard');

        // Dúvidas (aba "Dúvidas")
        Route::get('duvidas', [DuvidaController::class, 'index'])->name('duvidas.index');
        Route::post('duvidas/{duvida}/lida', [DuvidaController::class, 'markRead'])->name('duvidas.markRead');

        // Alunos (aba "Alunos")
        Route::get('alunos', [ProfessorAlunoController::class, 'index'])->name('alunos.index');

        // Cursos (CRUD)
        Route::get('cursos', [CursoAdminController::class, 'index'])->name('cursos.index');
        Route::get('cursos/criar', [CursoAdminController::class, 'create'])->name('cursos.create');
        Route::post('cursos', [CursoAdminController::class, 'store'])->name('cursos.store');
        Route::get('cursos/{curso}/editar', [CursoAdminController::class, 'edit'])->name('cursos.edit');
        Route::put('cursos/{curso}', [CursoAdminController::class, 'update'])->name('cursos.update');
        Route::delete('cursos/{curso}', [CursoAdminController::class, 'destroy'])->name('cursos.destroy');

        // Capa do curso
        Route::post('cursos/{curso}/capa', [CursoMediaController::class, 'uploadCover'])->name('cursos.capa.upload');
        Route::delete('cursos/{curso}/capa', [CursoMediaController::class, 'removeCover'])->name('cursos.capa.remove');

        // Módulos
        Route::get('cursos/{curso}/modulos', [ModuloAdminController::class, 'index'])->name('cursos.modulos.index');
        Route::post('cursos/{curso}/modulos', [ModuloAdminController::class, 'store'])->name('cursos.modulos.store');
        Route::put('cursos/{curso}/modulos/{modulo}', [ModuloAdminController::class, 'update'])->name('cursos.modulos.update');
        Route::delete('cursos/{curso}/modulos/{modulo}', [ModuloAdminController::class, 'destroy'])->name('cursos.modulos.destroy');
        Route::post('cursos/{curso}/modulos/reordenar', [ModuloAdminController::class, 'reorder'])->name('cursos.modulos.reorder');

        // Aulas
        Route::get('cursos/{curso}/modulos/{modulo}/aulas', [AulaAdminController::class, 'index'])->name('cursos.modulos.aulas.index');
        Route::post('cursos/{curso}/modulos/{modulo}/aulas', [AulaAdminController::class, 'store'])->name('cursos.modulos.aulas.store');
        Route::put('cursos/{curso}/modulos/{modulo}/aulas/{aula}', [AulaAdminController::class, 'update'])->name('cursos.modulos.aulas.update');
        Route::delete('cursos/{curso}/modulos/{modulo}/aulas/{aula}', [AulaAdminController::class, 'destroy'])->name('cursos.modulos.aulas.destroy');
        Route::post('cursos/{curso}/modulos/{modulo}/aulas/reordenar', [AulaAdminController::class, 'reorder'])->name('cursos.modulos.aulas.reorder');

        // Mídias da aula
        Route::post('cursos/{curso}/modulos/{modulo}/aulas/{aula}/midia', [AulaAdminController::class, 'uploadMedia'])->name('cursos.modulos.aulas.media.upload');
        Route::delete('cursos/{curso}/modulos/{modulo}/aulas/{aula}/midia/{media}', [AulaAdminController::class, 'removeMedia'])->name('cursos.modulos.aulas.media.remove');
    });
});
