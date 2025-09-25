@props(['id','name','value' => '','rows' => 5,'placeholder' => ''])

<textarea id="{{ $id }}"
          name="{{ $name }}"
          rows="{{ $rows }}"
          placeholder="{{ $placeholder }}"
          class="js-ckeditor w-full rounded-md border border-slate-300">{{ old($name, $value) }}</textarea>
