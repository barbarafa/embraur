@extends('layouts.app')
@section('title', $aula->titulo)
@section('content')
    <section class="container-page mx-auto max-w-4xl py-8">
        <h1 class="text-xl font-semibold mb-4">{{ $aula->titulo }}</h1>

        <video id="player" controls class="w-full rounded-lg border bg-black">
            <source src="{{ $aula->conteudo_url }}" type="video/mp4">
        </video>
    </section>

    <script>
        (async function(){
            const aulaId = {{ $aula->id }};
            const getUrl = "{{ route('aluno.aula.progresso.show', $aula) }}";
            const postUrl = "{{ route('aluno.aula.progresso.store', $aula) }}";
            const v = document.getElementById('player');

            // retomar
            try {
                const r = await fetch(getUrl); const j = await r.json();
                if (j.segundos_assistidos && v.readyState > 0) v.currentTime = j.segundos_assistidos;
                else v.addEventListener('loadedmetadata', ()=> v.currentTime = (j.segundos_assistidos||0));
            } catch(e){}

            // salvar periodicamente
            let t; function save(){
                clearTimeout(t);
                t = setTimeout(async ()=>{
                    const body = new URLSearchParams({
                        segundos_assistidos: Math.floor(v.currentTime || 0),
                        duracao_total: Math.floor(v.duration || 0),
                    });
                    try { await fetch(postUrl, {method:'POST', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Content-Type':'application/x-www-form-urlencoded'}, body}); } catch(e){}
                }, 800);
            }
            v.addEventListener('timeupdate', save);
            v.addEventListener('seeked', save);
            v.addEventListener('ended', save);
        })();
    </script>
@endsection
