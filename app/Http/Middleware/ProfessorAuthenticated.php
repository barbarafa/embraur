<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ProfessorAuthenticated
{
    public function handle(Request $request, Closure $next)
    {
        // Se não existir professor logado, redireciona pro login
        if (!$request->session()->has('prof_id')) {
            return redirect()->route('prof.login');
        }

        return $next($request);
    }
}
