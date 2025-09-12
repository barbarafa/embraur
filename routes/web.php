<?php

use Illuminate\Support\Facades\Route;

// Site (público)
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
use App\Http\Controllers\Aluno\CursoConteudoController;
use App\Http\Controllers\Aluno\AlunoQuizController;
use App\Http\Controllers\AulaProgressoController;
use App\Http\Controllers\CheckoutController;

// ===== PROFESSOR
use App\Http\Controllers\Auth\ProfessorAuthController;
use App\Http\Controllers\Professor\ProfessorDashboardController;
use App\Http\Controllers\Professor\CursoAdminController;
use App\Http\Controllers\Professor\CursoMediaController;
use App\Http\Controllers\Professor\ModuloAdminController;
use App\Http\Controllers\Professor\AulaAdminController;
use App\Http\Controllers\Professor\DuvidaController;
use App\Http\Controllers\Professor\ProfessorAlunoController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Professor\QuizController as ProfessorQuizController;

/*
|--------------------------------------------------------------------------
| Site (público)
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('site.home');
Route::get('/catalogo', [CursoController::class, 'index'])->name('site.cursos');
Route::get('/curso/{id}', [CursoController::class, 'show'])->name('site.curso.detalhe');

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

        // Dashboard e menus
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('matricular/{curso}', [MatriculaController::class, 'store'])->name('matricular');

        Route::get('cursos', [StudentCoursesController::class, 'index'])->name('cursos');
        Route::get('certificados', [StudentCertificatesController::class, 'index'])->name('certificados');
        Route::get('pagamentos', [StudentPaymentsController::class, 'index'])->name('pagamentos');
        Route::get('perfil', [StudentProfileController::class, 'index'])->name('perfil');

        /**
         * CONTEÚDO DO CURSO (player + sidebar)
         */
        Route::get('cursos/{curso}', [CursoConteudoController::class, 'show'])
            ->name('curso.conteudo');

        Route::get('cursos/{curso}/modulos/{modulo}', [CursoConteudoController::class, 'showModulo'])
            ->name('curso.modulo');

        Route::get('cursos/{curso}/modulos/{modulo}/aulas/{aula}', [CursoConteudoController::class, 'show'])
            ->name('curso.modulo.aula');

        /**
         * PROGRESSO DE AULA
         */
        Route::get('aulas/{aula}/progresso', [AulaProgressoController::class, 'show'])->name('aula.progresso.show');
        Route::post('aulas/{aula}/progresso', [AulaProgressoController::class, 'store'])->name('aula.progresso.store');

        /**
         * QUIZ (prova do módulo)
         */
        Route::get('cursos/{curso}/quiz/{quiz}', [AlunoQuizController::class, 'show'])
            ->name('quiz.show');

        Route::post('cursos/{curso}/quiz/{quiz}', [AlunoQuizController::class, 'submit'])
            ->name('quiz.submit');

        // 🔧 Ajuste: {tentativa} para bater com QuizTentativa $tentativa
        Route::get('cursos/{curso}/quiz/{quiz}/resultado/{tentativa}', [AlunoQuizController::class, 'result'])
            ->name('quiz.result');

        Route::get('cursos/{curso}/quiz/{quiz}/refazer', [AlunoQuizController::class, 'refazer'])
            ->name('quiz.refazer');



        /**
         * Carrinho/Checkout
         */
        Route::post('carrinho/add/{curso}', [CheckoutController::class, 'add'])->name('carrinho.add');
        Route::post('carrinho/remove/{curso}', [CheckoutController::class, 'remove'])->name('carrinho.remove');
        Route::get('carrinho', [CheckoutController::class, 'cart'])->name('carrinho');

        Route::post('checkout', [CheckoutController::class, 'checkout'])->name('checkout');
        Route::get('checkout/sucesso', [CheckoutController::class, 'success'])->name('checkout.sucesso');
        Route::get('checkout/falha', [CheckoutController::class, 'failure'])->name('checkout.falha');
        Route::get('checkout/pendente', [CheckoutController::class, 'pending'])->name('checkout.pendente');
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
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'loginProfessor'])->name('login.do');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // Protegido
    Route::middleware('prof.auth')->group(function () {
        // Dashboard
        Route::get('dashboard', [ProfessorDashboardController::class, 'index'])->name('dashboard');

        // Dúvidas
        Route::get('duvidas', [DuvidaController::class, 'index'])->name('duvidas.index');
        Route::post('duvidas/{duvida}/lida', [DuvidaController::class, 'markRead'])->name('duvidas.markRead');

        // Alunos
        Route::get('alunos', [ProfessorAlunoController::class, 'index'])->name('alunos.index');

        // Cursos.php (CRUD)
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

//        // Quiz do professor (CRUD) — usando alias para evitar conflito com AlunoQuizController
//        Route::resource('cursos.quizzes', ProfessorQuizController::class)->shallow();     // /prof/cursos/{curso}/quizzes
//        Route::resource('modulos.quizzes', ProfessorQuizController::class)->shallow();    // /prof/modulos/{modulo}/quizzes


        Route::get('quizzes',             [ProfessorQuizController::class, 'index'])->name('quizzes.index');
        Route::get('quizzes/create',      [ProfessorQuizController::class, 'create'])->name('quizzes.create');
        Route::post('quizzes',            [ProfessorQuizController::class, 'store'])->name('quizzes.store');
        Route::get('quizzes/{quiz}/edit', [ProfessorQuizController::class, 'edit'])->name('quizzes.edit');
        Route::put('quizzes/{quiz}',      [ProfessorQuizController::class, 'update'])->name('quizzes.update');
    });
});
