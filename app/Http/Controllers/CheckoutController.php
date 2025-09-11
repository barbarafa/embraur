<?php

namespace App\Http\Controllers;

use App\Models\{Cursos, ItensPedido, Matricula, Pagamento, Pedido, User};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use MercadoPago\Resources\Preference\Item;
use Ramsey\Uuid\Uuid;

use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Exceptions\MPApiException;

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

    public function count(Request $rq)
    {
        $cart = collect($rq->session()->get('cart', []));
        return response()->json(['count' => $cart->count()]);
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
        return view('site.carrinho', ['itens'=>$cart]);
    }

    public function startCart(Request $r)
    {
        $aluno = auth('aluno')->user();
        abort_if(!$aluno, 403);

        $cart = collect($r->session()->get('cart', []))->values(); // items: id,titulo,preco
        abort_if($cart->isEmpty(), 400, 'Carrinho vazio.');

        $valorTotal = (float) $cart->sum(fn($i) => (float)$i['preco']);

        // Cria pedido + itens
        $pedido = DB::transaction(function () use ($aluno, $cart, $valorTotal) {
            $pedido = Pedido::create([
                'aluno_id'                     => $aluno->id,
                'valor_total'                  => $valorTotal,
                'status'                       => 'pendente',
                'metodo_pagamento'             => 'mercadopago',
                'referencia_pagamento_externa' => null,
                'data_pedido'                  => now(),
                'data_pagamento'               => null,
            ]);

            foreach ($cart as $c) {
                ItensPedido::create([
                    'pedido_id'      => $pedido->id,
                    'curso_id'       => (int)$c['id'],
                    'quantidade'     => 1,
                    'preco_unitario' => (float)$c['preco'],
                    'subtotal'       => (float)$c['preco'],
                ]);
            }

            return $pedido;
        });

        // === Mercado Pago ===
        MercadoPagoConfig::setAccessToken(config('services.mercadopago.token'));

        $preference = new \MercadoPago\Client\Preference();

        $items = $cart->map(function($c){
            $i = new \MercadoPago\Item();
            $i->title       = $c['titulo'];
            $i->quantity    = 1;
            $i->currency_id = 'BRL';
            $i->unit_price  = (float)$c['preco'];
            return $i;
        })->values()->all();

        $preference->items              = $items;
        $preference->external_reference = 'PED:' . $pedido->id;
        $preference->back_urls = [
            'success' => route('checkout.retorno', ['status' => 'success']),
            'failure' => route('checkout.retorno', ['status' => 'failure']),
            'pending' => route('checkout.retorno', ['status' => 'pending']),
        ];
        $preference->auto_return = 'approved';

        $preference->save();

        $pedido->update([
            'referencia_pagamento_externa' => $preference->id,
        ]);

        // opcional: manter carrinho até confirmar pagamento. Se quiser limpar agora, descomente:
        // $r->session()->forget('cart');

        return redirect()->away($preference->init_point);
    }


    public function start(Request $r, Cursos $curso)
    {
        $aluno = auth('aluno')->user();
        abort_if(!$aluno, 403);

        $preco = (float)($curso->preco ?? 0);
        $quantidade = 1;
        $subtotal = $preco * $quantidade;

        // Cria o pedido + item de forma transacional
        $pedido = DB::transaction(function () use ($aluno, $curso, $preco, $quantidade, $subtotal) {
            $pedido = Pedido::create([
                'aluno_id'                     => $aluno->id,
                'valor_total'                  => $subtotal,              // 1 item
                'status'                       => 'pendente',
                'metodo_pagamento'             => 'mercadopago',
                'referencia_pagamento_externa' => null,                   // setaremos após criar a Preference
                'data_pedido'                  => now(),
                'data_pagamento'               => null,
            ]);

            ItensPedido::create([
                'pedido_id'      => $pedido->id,
                'curso_id'       => $curso->id,
                'quantidade'     => $quantidade,
                'preco_unitario' => $preco,
                'subtotal'       => $subtotal,
            ]);

            return $pedido;
        });

        MercadoPagoConfig::setAccessToken(config('services.mercadopago.token'));

        $preference = new \MercadoPago\Preference();

        $item = new \MercadoPago\Item();
        $item->title        = $curso->titulo;
        $item->quantity     = 1;
        $item->currency_id  = 'BRL';
        $item->unit_price   = (float)$preco;

        $preference->items               = [$item];
        $preference->external_reference  = 'PED:' . $pedido->id;

        $preference->back_urls = [
            'success' => route('checkout.retorno', ['status' => 'success']),
            'failure' => route('checkout.retorno', ['status' => 'failure']),
            'pending' => route('checkout.retorno', ['status' => 'pending']),
        ];
        $preference->auto_return = 'approved';

        $preference->save();

        // Vincula o pedido à referência externa gerada (preference_id)
        $pedido->update([
            'referencia_pagamento_externa' => $preference->id,
        ]);

        return redirect()->away($preference->init_point);
    }

    /**
     * Retorno síncrono do Mercado Pago (success|failure|pending).
     * Se você já possui webhook, aqui apenas faz o friendly-redirect.
     */
    public function retorno(Request $r)
    {
        $status = $r->query('status');                // success | failure | pending
        $prefId = $r->query('preference_id');         // string do MP
        $extRef = $r->query('external_reference');    // ex.: "PED:15" (se você passou isso ao criar a preferência)

        // Busque o pedido pela referência gravada quando criou a preferência
        $pedido = Pedido::with(['itens.curso'])
            ->where('referencia_pagamento_externa', $prefId)
            ->latest()
            ->first();

        // Fallback usando external_reference "PED:{id}"
        if (!$pedido && $extRef && str_starts_with($extRef, 'PED:')) {
            $id = (int) str_replace('PED:', '', $extRef);
            $pedido = Pedido::with(['itens.curso'])->find($id);
        }

        if (!$pedido) {
            return redirect()->route('aluno.dashboard')
                ->with('error', 'Não foi possível localizar o seu pedido. Se necessário, contate o suporte.');
        }

        // Trate cada status
        if ($status === 'success') {
            try {
                DB::transaction(function () use ($pedido) {

                    // marca pedido como pago
                    $pedido->update([
                        'status'         => 'pago',
                        'data_pagamento' => now(),
                    ]);

                    // cria matrícula para cada item do pedido
                    foreach ($pedido->itens as $item) {
                        $curso = $item->curso; // via relacionamento
                        if (!$curso) {
                            continue; // item inconsistente – ignora
                        }

                        // Evita matrícula duplicada
                        $jaTem = Matricula::where('aluno_id', $pedido->aluno_id)
                            ->where('curso_id', $curso->id)
                            ->exists();
                        if ($jaTem) {
                            continue;
                        }

                        $agora = now();

                        Matricula::create([
                            'aluno_id'              => $pedido->aluno_id,
                            'curso_id'              => $curso->id,
                            'data_matricula'        => $agora,
                            'data_inicio'           => $agora,
                            'data_conclusao'        => null,
                            'progresso_porcentagem' => 0,
                            'nota_final'            => null,
                        ]);
                    }
                });
            } catch (\Throwable $e) {
                Log::error('Falha ao finalizar pedido e gerar matrículas', [
                    'pedido_id' => $pedido->id,
                    'err'       => $e->getMessage(),
                ]);

                return redirect()->route('aluno.dashboard')
                    ->with('error', 'Seu pagamento foi aprovado, mas houve um erro ao liberar o acesso. Nosso time já foi notificado.');
            }

            return redirect()->route('aluno.dashboard')
                ->with('success', 'Pagamento aprovado! Seus cursos foram liberados.');
        }

        if ($status === 'pending') {
            $pedido->update(['status' => 'pendente']);

            return redirect()->route('aluno.dashboard')
                ->with('info', 'Seu pagamento está pendente de confirmação. Assim que for aprovado, liberaremos o acesso automaticamente.');
        }

        // failure (ou qualquer outro)
        $pedido->update(['status' => 'cancelado']);

        return redirect()->route('aluno.dashboard')
            ->with('error', 'Pagamento não aprovado. Você pode tentar novamente quando quiser.');
    }

    public function success(){ return view('aluno.checkout-status',['status'=>'sucesso']); }
    public function failure(){ return view('aluno.checkout-status',['status'=>'falha']); }
    public function pending(){ return view('aluno.checkout-status',['status'=>'pendente']); }
}
