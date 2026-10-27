<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['index', 'tool' => [], 'profession']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['index', 'tool' => [], 'profession']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<?php ($tool = is_array($tool) ? array_map(fn ($value) => is_scalar($value) ? $value : '', $tool) : []); ?>
<div class="tool-row" data-repeat-row data-row-index="<?php echo e($index); ?>">
    <input type="hidden" name="tools[<?php echo e($index); ?>][key]" value="<?php echo e($tool['key'] ?? ''); ?>">
    <div class="editor-field">
        <label for="tool-name-<?php echo e($index); ?>">Nama tool / teknologi <span aria-hidden="true">*</span></label>
        <input class="form-control" id="tool-name-<?php echo e($index); ?>" name="tools[<?php echo e($index); ?>][name]" value="<?php echo e($tool['name'] ?? ''); ?>" placeholder="Contoh: Microsoft Excel" maxlength="150" data-publish-required>
        <?php $__errorArgs = ['tools.'.$index.'.name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="editor-field-error"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
    <div class="editor-field">
        <label for="tool-usage-<?php echo e($index); ?>">Kegunaan <span aria-hidden="true">*</span></label>
        <input class="form-control" id="tool-usage-<?php echo e($index); ?>" name="tools[<?php echo e($index); ?>][usage]" value="<?php echo e($tool['usage'] ?? ''); ?>" placeholder="Jelaskan kegunaan tool" maxlength="1000" data-publish-required>
        <?php $__errorArgs = ['tools.'.$index.'.usage'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="editor-field-error"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
    <div class="editor-field tool-logo-field">
        <label for="tool-logo-<?php echo e($index); ?>">Logo <span class="optional-label">(opsional)</span></label>
        <?php ($savedTool = collect($profession->tools ?? [])->firstWhere('key', $tool['key'] ?? null)); ?>
        <div class="tool-logo-control">
            <?php if($profession->exists && ! empty($savedTool['logo_path'])): ?><img class="tool-logo" src="<?php echo e(route('admin.professions.tool-logo', [$profession, $savedTool['key']])); ?>" alt="Logo tool saat ini" width="30" height="30"><?php endif; ?>
            <input id="tool-logo-<?php echo e($index); ?>" name="tools[<?php echo e($index); ?>][logo]" type="file" accept="image/png,image/jpeg" data-file-max="1048576" class="file-input-small">
        </div>
        <?php if(! empty($savedTool['logo_path'])): ?><label class="remove-image"><input type="checkbox" name="tools[<?php echo e($index); ?>][remove_logo]" value="1"> Hapus logo lama</label><?php endif; ?>
        <?php $__errorArgs = ['tools.'.$index.'.logo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="editor-field-error"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
    <button class="remove-row" type="button" data-remove-row aria-label="Hapus tool">Hapus</button>
</div>
<?php /**PATH H:\fatih\Telkom\smtr 5\PPL\career-compass\resources\views/components/professions/tool-row.blade.php ENDPATH**/ ?>