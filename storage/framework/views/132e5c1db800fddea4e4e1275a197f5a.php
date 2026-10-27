<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['name', 'label', 'type' => 'text', 'hint' => null]));

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

foreach (array_filter((['name', 'label', 'type' => 'text', 'hint' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<?php ($password = $type === 'password'); ?>
<div class="form-field">
    <label for="<?php echo e($name); ?>"><?php echo e($label); ?></label>
    <div class="input-wrap <?php echo e($password ? 'input-wrap--password' : ''); ?>">
        <input
            id="<?php echo e($name); ?>"
            name="<?php echo e($name); ?>"
            type="<?php echo e($type); ?>"
            <?php if(! $password): ?> value="<?php echo e(old($name)); ?>" <?php endif; ?>
            aria-describedby="<?php echo e($name); ?>-error<?php echo e($hint ? ' '.$name.'-hint' : ''); ?>"
            aria-invalid="<?php echo e($errors->has($name) ? 'true' : 'false'); ?>"
            <?php echo e($attributes->merge(['required' => true])); ?>

        >
        <?php if($password): ?>
            <button class="password-toggle" type="button" data-password-toggle aria-controls="<?php echo e($name); ?>" aria-pressed="false" aria-label="Tampilkan <?php echo e(mb_strtolower($label)); ?>">
                <svg class="eye-hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="m3 3 18 18M10.6 10.6a2 2 0 0 0 2.8 2.8M9.8 5.2A11 11 0 0 1 12 5c5.5 0 9 7 9 7a19 19 0 0 1-3.2 4M6.2 6.2A20 20 0 0 0 3 12s3.5 7 9 7a10 10 0 0 0 4-.9" />
                </svg>
                <svg class="eye-visible" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M3 12s3.5-7 9-7 9 7 9 7-3.5 7-9 7-9-7-9-7Z" /><circle cx="12" cy="12" r="2.5" />
                </svg>
            </button>
        <?php endif; ?>
    </div>
    <?php if($hint): ?><p id="<?php echo e($name); ?>-hint" class="field-hint"><?php echo e($hint); ?></p><?php endif; ?>
    <p id="<?php echo e($name); ?>-error" class="field-error" <?php if (! ($errors->has($name))): ?> hidden <?php endif; ?>><?php echo e($errors->first($name)); ?></p>
</div>
<?php /**PATH H:\fatih\Telkom\smtr 5\PPL\career-compass\resources\views\components\auth-field.blade.php ENDPATH**/ ?>