<?php
namespace App\Http\Middleware;

use Illuminate\Foundation\Configuration\Middleware;

use Illuminate\Http\Request;


class AlunoAuthenticated extends Middleware
{
    public function handle(Request $request, \Closure $next)
    {
        // Se não existir professor logado, redireciona pro login
        if (!$request->session()->has('aluno_id')) {
            return redirect()->route('aluno.login');
        }

        return $next($request);
    }
}
