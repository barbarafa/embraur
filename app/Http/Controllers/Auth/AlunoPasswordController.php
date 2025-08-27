<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

class AlunoPasswordController extends Controller
{
public function request()
{
return view('auth.aluno-password-request');
}

public function email(Request $request)
{
$request->validate(['email' => ['required','email']]);

$status = Password::broker('alunos')->sendResetLink(
$request->only('email')
);

return back()->with('status', __($status));
}

public function reset(string $token)
{
return view('auth.aluno-password-reset', ['token' => $token]);
}

public function update(Request $request)
{
$request->validate([
'token'    => 'required',
'email'    => 'required|email',
'password' => 'required|min:6|confirmed',
]);

$status = Password::broker('alunos')->reset(
$request->only('email', 'password', 'password_confirmation', 'token'),
function ($user, $password) {
$user->forceFill(['password' => Hash::make($password)]);
$user->setRememberToken(Str::random(60));
$user->save();
event(new PasswordReset($user));
}
);

return $status === Password::PASSWORD_RESET
? redirect()->route('portal.aluno')->with('status', __($status))
: back()->withErrors(['email' => __($status)]);
}
}
