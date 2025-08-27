<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfessorAuthController extends Controller
{
public function showLoginForm()
{
return view('auth.professor-login');
}

public function login(Request $request)
{
$credentials = $request->validate([
'email' => ['required','email'],
'password' => ['required'],
]);

if (Auth::guard('professor')->attempt($credentials, $request->remember)) {
$request->session()->regenerate();
return redirect()->intended('/professor/dashboard');
}

return back()->withErrors([
'email' => 'Credenciais inválidas.',
]);
}

public function logout(Request $request)
{
Auth::guard('professor')->logout();
$request->session()->invalidate();
$request->session()->regenerateToken();

return redirect()->route('portal.professor');
}
}
