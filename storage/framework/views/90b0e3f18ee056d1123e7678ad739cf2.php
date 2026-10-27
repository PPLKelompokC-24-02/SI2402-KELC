<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['profession']));

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

foreach (array_filter((['profession']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<span class="profession-status profession-status--<?php echo e(! $profession->exists || $profession->publication_status === 'draft' ? 'draft' : ($profession->is_active ? 'published' : 'inactive')); ?>">
    <span aria-hidden="true"></span><?php echo e(! $profession->exists ? 'Belum disimpan' : ($profession->publication_status === 'draft' ? 'Draf' : ($profession->is_active ? 'Terbit' : 'Nonaktif'))); ?>

</span>
<?php /**PATH H:\fatih\Telkom\smtr 5\PPL\career-compass\resources\views/components/professions/status.blade.php ENDPATH**/ ?>