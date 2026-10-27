@props(['index', 'tool' => [], 'profession'])
@php($tool = is_array($tool) ? array_map(fn ($value) => is_scalar($value) ? $value : '', $tool) : [])
<div class="tool-row" data-repeat-row data-row-index="{{ $index }}">
    <input type="hidden" name="tools[{{ $index }}][key]" value="{{ $tool['key'] ?? '' }}">
    <div class="editor-field">
        <label for="tool-name-{{ $index }}">Nama tool / teknologi <span aria-hidden="true">*</span></label>
        <input class="form-control" id="tool-name-{{ $index }}" name="tools[{{ $index }}][name]" value="{{ $tool['name'] ?? '' }}" placeholder="Contoh: Microsoft Excel" maxlength="150" data-publish-required>
        @error('tools.'.$index.'.name')<p class="editor-field-error">{{ $message }}</p>@enderror
    </div>
    <div class="editor-field">
        <label for="tool-usage-{{ $index }}">Kegunaan <span aria-hidden="true">*</span></label>
        <input class="form-control" id="tool-usage-{{ $index }}" name="tools[{{ $index }}][usage]" value="{{ $tool['usage'] ?? '' }}" placeholder="Jelaskan kegunaan tool" maxlength="1000" data-publish-required>
        @error('tools.'.$index.'.usage')<p class="editor-field-error">{{ $message }}</p>@enderror
    </div>
    <div class="editor-field tool-logo-field">
        <label for="tool-logo-{{ $index }}">Logo <span class="optional-label">(opsional)</span></label>
        @php($savedTool = collect($profession->tools ?? [])->firstWhere('key', $tool['key'] ?? null))
        <div class="tool-logo-control">
            @if ($profession->exists && ! empty($savedTool['logo_path']))<img class="tool-logo" src="{{ route('admin.professions.tool-logo', [$profession, $savedTool['key']]) }}" alt="Logo tool saat ini" width="30" height="30">@endif
            <input id="tool-logo-{{ $index }}" name="tools[{{ $index }}][logo]" type="file" accept="image/png,image/jpeg" data-file-max="1048576" class="file-input-small">
        </div>
        @if (! empty($savedTool['logo_path']))<label class="remove-image"><input type="checkbox" name="tools[{{ $index }}][remove_logo]" value="1"> Hapus logo lama</label>@endif
        @error('tools.'.$index.'.logo')<p class="editor-field-error">{{ $message }}</p>@enderror
    </div>
    <button class="remove-row" type="button" data-remove-row aria-label="Hapus tool">Hapus</button>
</div>
