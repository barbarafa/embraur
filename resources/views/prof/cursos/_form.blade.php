@csrf
<div class="grid md:grid-cols-2 gap-4">
    <div>
        <label class="text-sm">Título</label>
        <input name="titulo" value="{{ old('titulo', $curso->titulo ?? '') }}" class="w-full px-3 py-2 border rounded">
        @error('titulo')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="text-sm">Categoria</label>
        <select name="categoria_id" class="w-full px-3 py-2 border rounded">
            @foreach($cats as $cat)
                <option value="{{ $cat->id }}" @selected(old('categoria_id', $curso->categoria_id ?? '')==$cat->id)>{{ $cat->nome }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="text-sm">Carga Horária (h)</label>
        <input type="number" name="carga_horaria" value="{{ old('carga_horaria', $curso->carga_horaria ?? 1) }}" class="w-full px-3 py-2 border rounded">
    </div>
    <div>
        <label class="text-sm">Nível</label>
        <select name="nivel" class="w-full px-3 py-2 border rounded">
            @foreach(['Básico','Intermediário','Avançado'] as $n)
                <option value="{{ $n }}" @selected(old('nivel', $curso->nivel ?? 'Básico')==$n)>{{ $n }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="text-sm">Preço</label>
        <input type="number" step="0.01" name="preco" value="{{ old('preco',$curso->preco ?? 0) }}" class="w-full px-3 py-2 border rounded">
    </div>
    <div>
        <label class="text-sm">Preço Promocional</label>
        <input type="number" step="0.01" name="preco_promocional" value="{{ old('preco_promocional',$curso->preco_promocional ?? '') }}" class="w-full px-3 py-2 border rounded">
    </div>
    <div>
        <label class="text-sm">Avaliação (0–5)</label>
        <input type="number" step="0.1" name="avaliacao" value="{{ old('avaliacao',$curso->avaliacao ?? 0) }}" class="w-full px-3 py-2 border rounded">
    </div>
    <div>
        <label class="text-sm">Alunos (nº)</label>
        <input type="number" name="alunos" value="{{ old('alunos',$curso->alunos ?? 0) }}" class="w-full px-3 py-2 border rounded">
    </div>
</div>

<div class="mt-3">
    <label class="text-sm">Descrição</label>
    <textarea name="descricao" rows="5" class="w-full px-3 py-2 border rounded">{{ old('descricao',$curso->descricao ?? '') }}</textarea>
</div>

<label class="inline-flex items-center gap-2 mt-3">
    <input type="checkbox" name="popular" value="1" @checked(old('popular', $curso->popular ?? false)) class="rounded">
    Popular
</label>

<div class="mt-4">
    <button class="btn btn-primary">Salvar</button>
    <a href="{{ route('prof.cursos.index') }}" class="btn btn-outline ml-2">Cancelar</a>
</div>
