@props(['field', 'index', 'value' => '', 'label', 'competencies' => null])
@php($value = is_scalar($value) ? $value : '')
<div class="repeat-row" data-repeat-row data-row-index="{{ $index }}">
    <div class="repeat-control">
        <label class="sr-only" for="{{ $field }}-{{ $index }}">{{ $label }} {{ is_numeric($index) ? $index + 1 : '' }}</label>
        @if ($competencies)
            <select id="{{ $field }}-{{ $index }}" name="{{ $field }}[{{ $index }}]" class="form-control" data-publish-required aria-describedby="{{ $field }}-{{ $index }}-error">
                <option value="">Pilih kompetensi</option>
                @foreach ($competencies as $competency)<option value="{{ $competency->id }}" @selected((string) $value === (string) $competency->id)>{{ $competency->name }}</option>@endforeach
            </select>
        @else
            <input id="{{ $field }}-{{ $index }}" name="{{ $field }}[{{ $index }}]" value="{{ $value }}" class="form-control" placeholder="{{ $label }}" maxlength="{{ $field === 'responsibilities' ? 2000 : 1000 }}" data-publish-required aria-describedby="{{ $field }}-{{ $index }}-error">
        @endif
        <p id="{{ $field }}-{{ $index }}-error" class="editor-field-error" @unless ($errors->has($field.'.'.$index)) hidden @endunless>{{ $errors->first($field.'.'.$index) }}</p>
    </div>
    <button type="button" class="remove-row" data-remove-row aria-label="Hapus {{ mb_strtolower($label) }}">Hapus</button>
</div>
