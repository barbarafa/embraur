<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{
    CursoController, ModalidadeController, ModuloController, AulaController,
    PedidoController, PagamentoController, MatriculaController,
    ProvaController, QuestaoController, CertificadoController,
    MensagemSuporteController
    // se existir, adiciona aqui os “opcionais” também
};

use App\Http\Controllers\Auth\AlunoAuthController;
use App\Http\Controllers\Auth\ProfessorAuthController;

/*
|--------------------------------------------------------------------------
| Páginas do site (público)
|--------------------------------------------------------------------------
*/
Route::view('/', 'site.home')->name('site.home');

// Catálogo (view) – use um path diferente de /cursos para não colidir com o CRUD
Route::view('/catalogo', 'site.cursos')->name('site.cursos');
// (Se preferir manter /cursos para o catálogo, mova o CRUD para /api/cursos ou /adm/cursos)

/*
|--------------------------------------------------------------------------
| Portais (logins)
|--------------------------------------------------------------------------
*/
// Portal do Aluno
Route::prefix('aluno')->group(function () {
    Route::get('login',  [AlunoAuthController::class, 'showLoginForm'])->name('portal.aluno');
    Route::post('login', [AlunoAuthController::class, 'login'])->name('aluno.login');
    Route::post('logout',[AlunoAuthController::class, 'logout'])->name('aluno.logout');
});

// Portal do Professor
Route::prefix('professor')->group(function () {
    Route::get('login',  [ProfessorAuthController::class, 'showLoginForm'])->name('portal.professor');
    Route::post('login', [ProfessorAuthController::class, 'login'])->name('professor.login');
    Route::post('logout',[ProfessorAuthController::class, 'logout'])->name('professor.logout');
});


/*
|--------------------------------------------------------------------------
| CRUDs (padrão REST) — mantenha em /cursos, /modalidades, etc.
| Se preferir, pode mover tudo para prefixo /api
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
// Webhook Mercado Pago
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
Route::get('certificados/verificar/{codigo}', [CertificadoController::class, 'verify']);

Route::prefix('mensagens-suporte')->group(function () {
    Route::get('/',                     [MensagemSuporteController::class, 'index']);
    Route::get('/{mensagemSuporte}',    [MensagemSuporteController::class, 'show']);
    Route::post('/',                    [MensagemSuporteController::class, 'store']);
    Route::put('/{mensagemSuporte}',    [MensagemSuporteController::class, 'update']);
    Route::delete('/{mensagemSuporte}', [MensagemSuporteController::class, 'destroy']);
});

/*
|--------------------------------------------------------------------------
| (Opcional) Se quiser expor grupos só se o controller existir
|--------------------------------------------------------------------------
*/
function optionalGroup(string $prefix, string $controller, array $routes)
{
    if (!class_exists($controller)) return;
    Route::prefix($prefix)->group(function () use ($controller, $routes) {
        foreach ($routes as $r) {
            [$method, $uri, $action] = $r; // ex: ['get','/','index']
            Route::{ $method }($uri, [$controller, $action]);
        }
    });
}

