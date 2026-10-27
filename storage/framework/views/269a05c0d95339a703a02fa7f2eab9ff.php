<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['field', 'index', 'value' => '', 'label', 'competencies' => null]));

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

foreach (array_filter((['field', 'index', 'value' => '', 'label', 'competencies' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<?php ($value = is_scalar($value) ? $value : ''); ?>
<div class="repeat-row" data-repeat-row data-row-index="<?php echo e($index); ?>">
    <div class="repeat-control">
        <label class="sr-only" for="<?php echo e($field); ?>-<?php echo e($index); ?>"><?php echo e($label); ?> <?php echo e(is_numeric($index) ? $index + 1 : ''); ?></label>
        <?php if($competencies): ?>
            <select id="<?php echo e($field); ?>-<?php echo e($index); ?>" name="<?php echo e($field); ?>[<?php echo e($index); ?>]" class="form-control" data-publish-required aria-describedby="<?php echo e($field); ?>-<?php echo e($index); ?>-error">
                <option value="">Pilih kompetensi</option>
                <?php $__currentLoopData = $competencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $competency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($competency->id); ?>" <?php if((string) $value === (string) $competency->id): echo 'selected'; endif; ?>><?php echo e($competency->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        <?php else: ?>
            <input id="<?php echo e($field); ?>-<?php echo e($index); ?>" name="<?php echo e($field); ?>[<?php echo e($index); ?>]" value="<?php echo e($value); ?>" class="form-control" placeholder="<?php echo e($label); ?>" maxlength="<?php echo e($field === 'responsibilities' ? 2000 : 1000); ?>" data-publish-required aria-describedby="<?php echo e($field); ?>-<?php echo e($index); ?>-error">
        <?php endif; ?>
        <p id="<?php echo e($field); ?>-<?php echo e($index); ?>-error" class="editor-field-error" <?php if (! ($errors->has($field.'.'.$index))): ?> hidden <?php endif; ?>><?php echo e($errors->first($field.'.'.$index)); ?></p>
    </div>
    <button type="button" class="remove-row" data-remove-row aria-label="Hapus <?php echo e(mb_strtolower($label)); ?>">Hapus</button>
</div>
<?php /**PATH H:\fatih\Telkom\smtr 5\PPL\career-compass\resources\views/components/professions/list-row.blade.php ENDPATH**/ ?>