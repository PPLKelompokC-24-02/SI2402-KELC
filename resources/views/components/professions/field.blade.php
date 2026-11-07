@props(['name', 'label', 'value' => null, 'type' => 'text', 'required' => false, 'hint' => null, 'alwaysRequired' => false])
@php($value = is_scalar($value) ? $value : '')
<div class="editor-field">
    <label for="{{ $name }}">{{ $label }} @if ($required)<span aria-hidden="true">*</span>@endif</label>
    @if ($type === 'select')
        <select id="{{ $name }}" name="{{ $name }}" aria-describedby="{{ $name }}-error{{ $hint ? ' '.$name.'-hint' : '' }}" aria-invalid="{{ $errors->has($name) ? 'true' : 'false' }}" {{ $attributes->class(['form-control']) }}>{{ $slot }}</select>
    @elseif ($type === 'textarea')
        <textarea id="{{ $name }}" name="{{ $name }}" aria-describedby="{{ $name }}-error{{ $hint ? ' '.$name.'-hint' : '' }}" aria-invalid="{{ $errors->has($name) ? 'true' : 'false' }}" {{ $attributes->class(['form-control']) }}>{{ $value }}</textarea>
    @else
        <input id="{{ $name }}" name="{{ $name }}" type="{{ $type }}" value="{{ $value }}" @if ($alwaysRequired) required @endif aria-describedby="{{ $name }}-error{{ $hint ? ' '.$name.'-hint' : '' }}" aria-invalid="{{ $errors->has($name) ? 'true' : 'false' }}" {{ $attributes->class(['form-control']) }}>
    @endif
    @if ($hint)<p id="{{ $name }}-hint" class="field-help">{{ $hint }}</p>@endif
    @error($name)<p id="{{ $name }}-error" class="editor-field-error">{{ $message }}</p>@else<span id="{{ $name }}-error" hidden></span>@enderror
</div>
