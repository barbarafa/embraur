<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Cursos;
use App\Models\Matriculas;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class AlunoAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.aluno-login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        $aluno = User::where('email', $data['email'])
            ->where('tipo_usuario', 'aluno')
            ->where('status', 'ativo')
            ->first();

        if ($aluno && Hash::check($data['password'], $aluno->password)) {
            $request->session()->regenerate();
            // define sessão simples usada pelas views/rotas do aluno
            $request->session()->put('aluno_id', $aluno->id);
            $request->session()->put('aluno_nome', $aluno->nome_completo);
            return redirect()->route('aluno.dashboard');
        }

        return back()->withErrors(['email' => 'Credenciais inválidas ou usuário inativo.']);
    }

    public function logout(Request $request)
    {
        $request->session()->forget(['aluno_id', 'aluno_nome']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('site.home');
    }

    public function showRegisterForm(Request $request)
    {
        return view('auth.aluno-register', [
            'intended' => $request->query('intended'),
            'curso' => $request->query('curso'),
        ]);
    }

    public function register(Request $request)
    {
        $intended = $request->input('intended');
        $cursoId = $request->input('curso');

        $data = $request->validate([
            'nome' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:6'],
            'cpf' => ['required', 'string', 'unique:users,cpf'],
            'telefone' => ['required', 'string', 'unique:users,telefone'],
            'data_nascimento' => ['required', 'date'],
        ]);

        $aluno = User::createOrFirst([
            'nome_completo' => $data['nome'],
            'cpf' => $data['cpf'],
            'email' => $data['email'],
            'password' => $data['password'], // será hasheada pelo mutator do Model
            'tipo_usuario' => 'aluno',
            'status' => 'ativo',
            'telefone' => $data['telefone'],
            'data_nascimento' => $data['data_nascimento'],
        ]);

        auth('aluno')->login($aluno);

        if ($cursoId) {
            DB::transaction(function () use ($aluno, $cursoId) {
                $curso = Cursos::find($cursoId);
                if (!$curso) return;

                $jaTem = Matriculas::where('aluno_id', $aluno->id)
                    ->where('curso_id', $cursoId)
                    ->exists();
                if ($jaTem) return;

                $agora = Carbon::now();
                Matriculas::create([
                    'aluno_id' => $aluno->id,
                    'curso_id' => $cursoId,
                    'data_matricula' => $agora,
                    'data_inicio' => $agora,
                    'data_conclusao' => null,
                    'data_vencimento' => $curso->validade_dias
                        ? $agora->copy()->addDays((int)$curso->validade_dias)
                        : null,
                    'progresso_porcentagem' => 0,
                    'nota_final' => null,
                ]);
            });


            $request->session()->regenerate();
            $request->session()->put('aluno_id', $aluno->id);
            $request->session()->put('aluno_nome', $aluno->nome_completo);

            return $intended
                ? redirect()->to($intended)->with('sucesso', 'Cadastro realizado e matrícula criada!')
                : redirect()->route('aluno.dashboard')->with('sucesso', 'Cadastro realizado com sucesso!');
        }
        return back();
    }
}
