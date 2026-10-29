@props(['name', 'label', 'type' => 'text', 'hint' => null])
@php($password = $type === 'password')
<div class="form-field">
    <label for="{{ $name }}">{{ $label }}</label>
    <div class="input-wrap {{ $password ? 'input-wrap--password' : '' }}">
        <input
            id="{{ $name }}"
            name="{{ $name }}"
            type="{{ $type }}"
            @if (! $password) value="{{ old($name) }}" @endif
            aria-describedby="{{ $name }}-error{{ $hint ? ' '.$name.'-hint' : '' }}"
            aria-invalid="{{ $errors->has($name) ? 'true' : 'false' }}"
            {{ $attributes->merge(['required' => true]) }}
        >
        @if ($password)
            <button class="password-toggle" type="button" data-password-toggle aria-controls="{{ $name }}" aria-pressed="false" aria-label="Tampilkan {{ mb_strtolower($label) }}">
                <svg class="eye-hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="m3 3 18 18M10.6 10.6a2 2 0 0 0 2.8 2.8M9.8 5.2A11 11 0 0 1 12 5c5.5 0 9 7 9 7a19 19 0 0 1-3.2 4M6.2 6.2A20 20 0 0 0 3 12s3.5 7 9 7a10 10 0 0 0 4-.9" />
                </svg>
                <svg class="eye-visible" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M3 12s3.5-7 9-7 9 7 9 7-3.5 7-9 7-9-7-9-7Z" /><circle cx="12" cy="12" r="2.5" />
                </svg>
            </button>
        @endif
    </div>
    @if ($hint)<p id="{{ $name }}-hint" class="field-hint">{{ $hint }}</p>@endif
    <p id="{{ $name }}-error" class="field-error" @unless ($errors->has($name)) hidden @endunless>{{ $errors->first($name) }}</p>
</div>
