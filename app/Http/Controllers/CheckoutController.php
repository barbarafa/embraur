<?php

namespace App\Http\Controllers;

use App\Models\{Cursos, Matricula, Pagamento, User};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use MercadoPago\SDK;
use MercadoPago\Preference;
use MercadoPago\Item;
use Ramsey\Uuid\Uuid;

class CheckoutController extends Controller
{
    private function alunoId(Request $rq){ return auth('aluno')->id() ?? $rq->session()->get('aluno_id'); }

    public function add(Request $rq, Cursos $curso)
    {
        $cart = collect($rq->session()->get('cart', []));
        $cart->put($curso->id, ['id'=>$curso->id,'titulo'=>$curso->titulo,'preco'=>(float)$curso->preco]);
        $rq->session()->put('cart', $cart->toArray());
        return back()->with('sucesso','Curso adicionado ao carrinho.');
    }

    public function remove(Request $rq, Cursos $curso)
    {
        $cart = collect($rq->session()->get('cart', []));
        $cart->forget($curso->id);
        $rq->session()->put('cart', $cart->toArray());
        return back()->with('sucesso','Curso removido do carrinho.');
    }

    public function cart(Request $rq)
    {
        $cart = $rq->session()->get('cart', []);
        return view('aluno.carrinho', ['itens'=>$cart]);
    }

    public function checkout(Request $rq)
    {
        $alunoId = $this->alunoId($rq);
        abort_if(!$alunoId, 403);

        $cart = collect($rq->session()->get('cart', []));
        abort_if($cart->isEmpty(), 400, 'Carrinho vazio');

        SDK::setAccessToken(config('services.mercadopago.token') ?? env('MP_ACCESS_TOKEN'));
        $preference = new Preference();

        $items = [];
        $total = 0;
        foreach ($cart as $c) {
            $it = new Item();
            $it->title = $c['titulo'];
            $it->quantity = 1;
            $it->currency_id = 'BRL';
            $it->unit_price = (float)$c['preco'];
            $items[] = $it;
            $total += (float)$c['preco'];
        }
        $preference->items = $items;

        $externalRef = Uuid::uuid4()->toString();
        $preference->external_reference = $externalRef;
        $preference->back_urls = [
            'success' => route('aluno.checkout.sucesso'),
            'failure' => route('aluno.checkout.falha'),
            'pending' => route('aluno.checkout.pendente'),
        ];
        $preference->auto_return = 'approved';
        $preference->notification_url = route('webhook.mercadopago').'?secret='.urlencode(env('MP_WEBHOOK_SECRET'));
        $preference->save();

        // cria um registro de pagamento pendente (sem matricular ainda — matricula após aprovado)
        Pagamento::create([
            'aluno_id' => $alunoId,
            'valor' => $total,
            'status' => 'pendente',
            'mp_preference_id' => $preference->id,
            'external_reference' => $externalRef,
            'raw_payload' => null,
        ]);

        // limpa carrinho
        $rq->session()->forget('cart');

        return redirect($preference->init_point);
    }

    public function success(){ return view('aluno.checkout-status',['status'=>'sucesso']); }
    public function failure(){ return view('aluno.checkout-status',['status'=>'falha']); }
    public function pending(){ return view('aluno.checkout-status',['status'=>'pendente']); }
}
