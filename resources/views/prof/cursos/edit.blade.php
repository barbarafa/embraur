@extends('layouts.app')
@section('title','Editar Curso')

@section('content')
    <section class="mx-auto container-page px-4 py-10 max-w-3xl">
        <div class="card p-6">
            <h1 class="text-xl font-bold mb-4">Editar Curso</h1>
            <form method="post" action="{{ route('prof.cursos.update',$curso) }}">
                @method('PUT')
                @include('prof.cursos._form')
            </form>
        </div>
    </section>
@endsection
