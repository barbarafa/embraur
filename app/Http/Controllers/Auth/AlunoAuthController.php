namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlunoAuthController extends Controller
{
public function showLoginForm()
{
return view('auth.aluno-login');
}

public function login(Request $request)
{
$credentials = $request->validate([
'email' => ['required','email'],
'password' => ['required'],
]);

if (Auth::guard('aluno')->attempt($credentials, $request->remember)) {
$request->session()->regenerate();
return redirect()->intended('/aluno/dashboard');
}

return back()->withErrors([
'email' => 'Credenciais inválidas.',
]);
}

public function logout(Request $request)
{
Auth::guard('aluno')->logout();
$request->session()->invalidate();
$request->session()->regenerateToken();

return redirect()->route('portal.aluno');
}
}
