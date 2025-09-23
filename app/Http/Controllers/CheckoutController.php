<?php

namespace App\Http\Controllers;

use App\Models\{Cursos, ItensPedido, Matriculas, Pagamentos, Pedido, User};
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\Exceptions\MPApiException;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;

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

        // Fallback: se tem sessão antiga, loga no guard para esta requisição
        if (!$aluno && ($id = $r->session()->get('aluno_id'))) {
            $aluno = User::find($id);
            if ($aluno) {
                auth('aluno')->login($aluno);
            }
        }
        abort_if(!$aluno, 403);

        $cart = collect($r->session()->get('cart', []))->values(); // items: id,titulo,preco
        abort_if($cart->isEmpty(), 400, 'Carrinho vazio.');

        $valorTotal = (float) $cart->sum(fn($i) => (float)$i['preco']);

        // Cria pedido + itens
        $pedido = DB::transaction(function () use ($aluno, $cart, $valorTotal) {
            $pedido = Pedido::create([
                'aluno_id' => $aluno->id,
                'valor_total' => $valorTotal,
                'status' => 'pendente',
                'metodo_pagamento' => 'mercadopago',
                'referencia_pagamento_externa' => null,
                'data_pedido' => now(),
            ]);
            foreach ($cart as $c) {
                ItensPedido::create([
                    'pedido_id' => $pedido->id,
                    'curso_id' => (int) $c['id'],
                    'quantidade' => 1,
                    'preco_unitario' => (float) $c['preco'],
                    'subtotal' => (float) $c['preco'],
                ]);
            }
            return $pedido;
        });

        $items = $cart->map(fn($c) => [
            'id'          => (string) $c['id'],
            'title'       => (string) $c['titulo'],
            'quantity'    => 1,
            'unit_price'  => (float) $c['preco'],
            'currency_id' => 'BRL',
        ])->all();

        // === Mercado Pago ===
        $pref = $this->mpCreatePreference($items, 'PED:' . $pedido->id);

        $pedido->update(['referencia_pagamento_externa' => $pref->id]);

        return redirect()->away($pref->init_point);
    }


    public function start(Request $r, Cursos $curso)
    {
        $aluno = auth('aluno')->user();

        // Fallback: se tem sessão antiga, loga no guard para esta requisição
        if (!$aluno && ($id = $r->session()->get('aluno_id'))) {
            $aluno = User::find($id);
            if ($aluno) {
                auth('aluno')->login($aluno);
            }
        }
        abort_if(!$aluno, 403);

        $preco = (float)($curso->preco ?? 0);
        $quantidade = 1;
        $subtotal = $preco * $quantidade;

        // Cria o pedido + item de forma transacional
        $pedido = DB::transaction(function () use ($aluno, $curso, $preco,$subtotal) {
            $pedido = Pedido::create([
                'aluno_id' => $aluno->id,
                'valor_total' => $subtotal,
                'status' => 'pendente',
                'metodo_pagamento' => 'mercadopago',
                'referencia_pagamento_externa' => null,
                'data_pedido' => now(),
            ]);
            ItensPedido::create([
                'pedido_id' => $pedido->id,
                'curso_id' => $curso->id,
                'quantidade' => 1,
                'preco_unitario' => $preco,
                'subtotal' => $preco,
            ]);
            return $pedido;
        });

        $items = [[
            'id'          => (string) $curso->id,
            'title'       => (string) $curso->titulo,
            'quantity'    => 1,
            'unit_price'  => (float) $preco,
            'currency_id' => 'BRL',
        ]];

        $pref = $this->mpCreatePreference($items, 'PED:' . $pedido->id);

        $pedido->update(['referencia_pagamento_externa' => $pref->id]);

        return redirect()->away($pref->init_point);
    }


    private function mpCreatePreference(array $items, string $externalRef)
    {
        MercadoPagoConfig::setAccessToken(config('services.mercadopago.token'));

        $client  = new PreferenceClient();

        // garanta URLs absolutas e não-vazias


        // fallback defensivo: se por qualquer motivo $success ficar vazio, não use auto_return
        $payload = [
            'items'               => $items,
            'external_reference'  => $externalRef,
            'back_urls'           => [
                'success' => route('checkout.retorno', ['status' => 'success']),
                'failure' => route('checkout.retorno', ['status' => 'failure']),
                'pending' => route('checkout.retorno', ['status' => 'pending']),
            ],
            'auto_return' => 'approved', // agora pode
        ];

        $payload['notification_url'] = config('services.mercadopago.notification_url');

        try {
            return $client->create($payload);
        } catch (MPApiException $e) {
            $resp = $e->getApiResponse();
            Log::error('MP Preference error', [
                'status'  => optional($resp)->getStatusCode(),
                'body'    => optional($resp)->getContent(),
                'payload' => $payload,
            ]);
            throw $e;
        }
    }

    /**
     * Retorno síncrono do Mercado Pago (success|failure|pending).
     * Se você já possui webhook, aqui apenas faz o friendly-redirect.
     */
    public function retorno(Request $r)
    {
        $reqId  = (string) Str::uuid();
        $status = $r->query('status');                // success | failure | pending
        $prefId = $r->query('preference_id');         // string do MP
        $extRef = $r->query('external_reference');    // ex.: "PED:15" (se você passou isso ao criar a preferência)


//        / ---- LOG DE ENTRADA DO REQUEST ----
        $headersSafe = Arr::except($r->headers->all(), ['authorization', 'cookie']);
        Log::channel('mp')->info('MP retorno: HIT', [
            'req_id'     => $reqId,
            'method'     => $r->method(),
            'full_url'   => $r->fullUrl(),
            'ip'         => $r->ip(),
            'user_agent' => $r->userAgent(),
            'status_qs'  => $status,
            'pref_id'    => $prefId,
            'ext_ref'    => $extRef,
            'query'      => $r->query(),                    // só querystring
            'body'       => Arr::except($r->all(), ['token','access_token','password','senha']),
            'headers'    => $headersSafe,
        ]);

        // Busque o pedido pela referência gravada quando criou a preferência
        $pedido = Pedido::with(['itens.curso'])
            ->where('referencia_pagamento_externa', $prefId)
            ->latest('pedidos.data_pedido')
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
        if ($status === 'success' || $status === 'approved') {

            Log::channel('mp')->info('MP retorno: aprovado', [
                'req_id'    => $reqId,
                'pedido_id' => $pedido->id,
            ]);
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
                        $jaTem = Matriculas::where('aluno_id', $pedido->aluno_id)
                            ->where('curso_id', $curso->id)
                            ->exists();
                        if ($jaTem) {
                            continue;
                        }

                        $agora = now();

                        Matriculas::create([
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
                Log::channel('mp')->error('MP retorno: erro ao finalizar pedido', [
                    'req_id'    => $reqId,
                    'pedido_id' => $pedido->id,
                    'exception' => $e->getMessage(),
                    'trace'     => Str::limit($e->getTraceAsString(), 2000),
                ]);

                return redirect()->route('aluno.dashboard')
                    ->with('error', 'Seu pagamento foi aprovado, mas houve um erro ao liberar o acesso. Nosso time já foi notificado.');
            }
            $r->session()->forget('cart');
            return redirect()->route('aluno.dashboard')
                ->with('success', 'Pagamento aprovado! Seus cursos foram liberados.');

        }

        Log::channel('mp')->info('MP retorno: não aprovado', [
            'req_id'    => $reqId,
            'pedido_id' => $pedido->id,
            'status_qs' => $status,
        ]);

        if ($status === 'pending') {

            Log::channel('mp')->info('MP retorno: pendente', [
                'req_id'    => $reqId,
                'pedido_id' => $pedido->id,
            ]);

            $pedido->update(['status' => 'pendente']);

            return redirect()->route('aluno.dashboard')
                ->with('info', 'Seu pagamento está pendente de confirmação. Assim que for aprovado, liberaremos o acesso automaticamente.');
        }

        // failure (ou qualquer outro)
        $pedido->update(['status' => 'cancelado']);

        return redirect()->route('aluno.dashboard')
            ->with('error', 'Pagamento não aprovado. Você pode tentar novamente quando quiser.');
    }


    public function webhook(Request $r)
    {
        // O Mercado Pago envia JSON
        $payload = $r->all();

        Log::info('Webhook MP recebido', $payload);

        // Exemplo de evento de pagamento
        if (($payload['type'] ?? null) === 'payment') {
            $paymentId = $payload['data']['id'] ?? null;

            if ($paymentId) {
                try {
                    // Consulta os detalhes do pagamento via SDK
                    MercadoPagoConfig::setAccessToken(config('services.mercadopago.token'));
                    $client   = new PaymentClient();
                    $payment  = $client->get($paymentId);

                    $extRef   = $payment->external_reference ?? null; // "PED:15"
                    $status   = $payment->status ?? null;             // approved, pending, rejected

                    if ($extRef && str_starts_with($extRef, 'PED:')) {
                        $pedidoId = (int) str_replace('PED:', '', $extRef);
                        $pedido   = Pedido::with('itens.curso')->find($pedidoId);

                        if ($pedido) {
                            DB::transaction(function () use ($pedido, $status) {
                                if ($status === 'approved') {
                                    $pedido->update([
                                        'status'         => 'pago',
                                        'data_pagamento' => now(),
                                    ]);

                                    // cria matrículas
                                    foreach ($pedido->itens as $item) {
                                        $curso = $item->curso;
                                        if (!$curso) continue;

                                        $jaTem = Matriculas::where('aluno_id', $pedido->aluno_id)
                                            ->where('curso_id', $curso->id)
                                            ->exists();

                                        if (!$jaTem) {
                                            Matriculas::create([
                                                'aluno_id'              => $pedido->aluno_id,
                                                'curso_id'              => $curso->id,
                                                'data_matricula'        => now(),
                                                'data_inicio'           => now(),
                                                'progresso_porcentagem' => 0,
                                            ]);
                                        }
                                    }
                                } elseif ($status === 'pending') {
                                    $pedido->update(['status' => 'pendente']);
                                } else {
                                    $pedido->update(['status' => 'cancelado']);
                                }
                            });
                        }
                    }
                } catch (MPApiException $e) {
                    Log::error('Erro ao processar webhook MP', [
                        'payment_id' => $paymentId,
                        'err'        => $e->getMessage(),
                    ]);
                }
            }
        }

        // O Mercado Pago exige um 200 de retorno
        return response()->json(['status' => 'ok'], 200);
    }


}
