<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{
    CursoController, ModalidadeController, ModuloController, AulaController,
    PedidoController, PagamentoController, MatriculaController,
    ProvaController, QuestaoController, CertificadoController,
    MensagemSuporteController
};

use App\Http\Controllers\Auth\AlunoAuthController;
use App\Http\Controllers\Auth\ProfessorAuthController;
// opcionais (se existirem)
use App\Http\Controllers\Auth\AlunoPasswordController;
use App\Http\Controllers\Auth\AlunoRegisterController;

/*
|--------------------------------------------------------------------------
| Páginas do site (público)
|--------------------------------------------------------------------------
*/
Route::view('/', 'site.home')->name('site.home');

// Catálogo (view) – usar /catalogo para não colidir com CRUD /cursos
Route::view('/catalogo', 'site.cursos')->name('site.cursos');

/*
|--------------------------------------------------------------------------
| Portais (logins)
|--------------------------------------------------------------------------
*/
// Portal do Aluno
Route::prefix('aluno')->group(function () {
    // Login/Logout
    Route::get('login',  [AlunoAuthController::class, 'showLoginForm'])->name('portal.aluno');
    Route::post('login', [AlunoAuthController::class, 'login'])->name('aluno.login');
    Route::post('logout',[AlunoAuthController::class, 'logout'])->name('aluno.logout');

    // (OPCIONAL) Cadastro do Aluno
    if (class_exists(AlunoRegisterController::class)) {
        Route::get('cadastro',  [AlunoRegisterController::class, 'show'])->name('aluno.register');
        Route::post('cadastro', [AlunoRegisterController::class, 'store'])->name('aluno.register.store');
    }

    // (OPCIONAL) Esqueci minha senha (broker 'alunos')
    if (class_exists(AlunoPasswordController::class)) {
        Route::get('senha/esqueci',   [AlunoPasswordController::class, 'request'])->name('aluno.password.request');
        Route::post('senha/email',    [AlunoPasswordController::class, 'email'])->name('aluno.password.email');
        Route::get('senha/reset/{t}', [AlunoPasswordController::class, 'reset'])->name('aluno.password.reset');
        Route::post('senha/reset',    [AlunoPasswordController::class, 'update'])->name('aluno.password.update');
    }

    // (OPCIONAL) Acesso Demo
    Route::post('demo', [AlunoAuthController::class, 'demoLogin'])->name('aluno.demo');

    // Dashboard protegido do Aluno
    Route::middleware('auth:aluno')->group(function () {
        Route::view('dashboard', 'aluno.dashboard')->name('aluno.dashboard');
    });
});

// Portal do Professor
Route::prefix('professor')->group(function () {
    Route::get('login',  [ProfessorAuthController::class, 'showLoginForm'])->name('portal.professor');
    Route::post('login', [ProfessorAuthController::class, 'login'])->name('professor.login');
    Route::post('logout',[ProfessorAuthController::class, 'logout'])->name('professor.logout');

    // Dashboard protegido do Professor
    Route::middleware('auth:professor')->group(function () {
        Route::view('dashboard', 'professor.dashboard')->name('professor.dashboard');
    });
});

/*
|--------------------------------------------------------------------------
| CRUDs (padrão REST) — por ora, mantidos no web.php
| Dica: futuramente mover para routes/api.php e proteger com auth/token.
|--------------------------------------------------------------------------
*/
Route::prefix('cursos')->group(function () {
    Route::get('/',            [CursoController::class, 'index']);
    Route::get('/{curso}',     [CursoController::class, 'show']);
    Route::post('/',           [CursoController::class, 'store']);
    Route::put('/{curso}',     [CursoController::class, 'update']);
    Route::delete('/{curso}',  [CursoController::class, 'destroy']);
});

Route::prefix('modalidades')->group(function () {
    Route::get('/',                 [ModalidadeController::class, 'index']);
    Route::get('/{modalidade}',     [ModalidadeController::class, 'show']);
    Route::post('/',                [ModalidadeController::class, 'store']);
    Route::put('/{modalidade}',     [ModalidadeController::class, 'update']);
    Route::delete('/{modalidade}',  [ModalidadeController::class, 'destroy']);
});

Route::prefix('modulos')->group(function () {
    Route::get('/',             [ModuloController::class, 'index']);
    Route::get('/{modulo}',     [ModuloController::class, 'show']);
    Route::post('/',            [ModuloController::class, 'store']);
    Route::put('/{modulo}',     [ModuloController::class, 'update']);
    Route::delete('/{modulo}',  [ModuloController::class, 'destroy']);
});

Route::prefix('aulas')->group(function () {
    Route::get('/',           [AulaController::class, 'index']);
    Route::get('/{aula}',     [AulaController::class, 'show']);
    Route::post('/',          [AulaController::class, 'store']);
    Route::put('/{aula}',     [AulaController::class, 'update']);
    Route::delete('/{aula}',  [AulaController::class, 'destroy']);
});

Route::prefix('pedidos')->group(function () {
    Route::get('/',            [PedidoController::class, 'index']);
    Route::get('/{pedido}',    [PedidoController::class, 'show']);
    Route::post('/',           [PedidoController::class, 'store']);
    Route::put('/{pedido}',    [PedidoController::class, 'update']);
    Route::delete('/{pedido}', [PedidoController::class, 'destroy']);
});

Route::prefix('pagamentos')->group(function () {
    Route::get('/',                 [PagamentoController::class, 'index']);
    Route::get('/{pagamento}',      [PagamentoController::class, 'show']);
    Route::post('/',                [PagamentoController::class, 'store']);
    Route::put('/{pagamento}',      [PagamentoController::class, 'update']);
    Route::delete('/{pagamento}',   [PagamentoController::class, 'destroy']);
});
// Webhook Mercado Pago (público)
Route::post('pagamentos/webhook/mercadopago', [PagamentoController::class, 'webhook']);

Route::prefix('matriculas')->group(function () {
    Route::get('/',               [MatriculaController::class, 'index']);
    Route::get('/{matricula}',    [MatriculaController::class, 'show']);
    Route::post('/',              [MatriculaController::class, 'store']);
    Route::put('/{matricula}',    [MatriculaController::class, 'update']);
    Route::delete('/{matricula}', [MatriculaController::class, 'destroy']);
});

Route::prefix('provas')->group(function () {
    Route::get('/',            [ProvaController::class, 'index']);
    Route::get('/{prova}',     [ProvaController::class, 'show']);
    Route::post('/',           [ProvaController::class, 'store']);
    Route::put('/{prova}',     [ProvaController::class, 'update']);
    Route::delete('/{prova}',  [ProvaController::class, 'destroy']);
});

Route::prefix('questoes')->group(function () {
    Route::get('/',             [QuestaoController::class, 'index']);
    Route::get('/{questao}',    [QuestaoController::class, 'show']);
    Route::post('/',            [QuestaoController::class, 'store']);
    Route::put('/{questao}',    [QuestaoController::class, 'update']);
    Route::delete('/{questao}', [QuestaoController::class, 'destroy']);
});

Route::prefix('certificados')->group(function () {
    Route::get('/',                   [CertificadoController::class, 'index']);
    Route::get('/{certificado}',      [CertificadoController::class, 'show']);
    Route::post('/',                  [CertificadoController::class, 'store']);
    Route::delete('/{certificado}',   [CertificadoController::class, 'destroy']);
});
// verificação pública por código
Route::get('certificados/verificar/{codigo}', [CertificadoController::class, 'verify']);

Route::prefix('mensagens-suporte')->group(function () {
    Route::get('/',                     [MensagemSuporteController::class, 'index']);
    Route::get('/{mensagemSuporte}',    [MensagemSuporteController::class, 'show']);
    Route::post('/',                    [MensagemSuporteController::class, 'store']);
    Route::put('/{mensagemSuporte}',    [MensagemSuporteController::class, 'update']);
    Route::delete('/{mensagemSuporte}', [MensagemSuporteController::class, 'destroy']);
});
