<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['name', 'label', 'value' => null, 'type' => 'text', 'required' => false, 'hint' => null, 'alwaysRequired' => false]));

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

foreach (array_filter((['name', 'label', 'value' => null, 'type' => 'text', 'required' => false, 'hint' => null, 'alwaysRequired' => false]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<?php ($value = is_scalar($value) ? $value : ''); ?>
<div class="editor-field">
    <label for="<?php echo e($name); ?>"><?php echo e($label); ?> <?php if($required): ?><span aria-hidden="true">*</span><?php endif; ?></label>
    <?php if($type === 'select'): ?>
        <select id="<?php echo e($name); ?>" name="<?php echo e($name); ?>" aria-describedby="<?php echo e($name); ?>-error<?php echo e($hint ? ' '.$name.'-hint' : ''); ?>" aria-invalid="<?php echo e($errors->has($name) ? 'true' : 'false'); ?>" <?php echo e($attributes->class(['form-control'])); ?>><?php echo e($slot); ?></select>
    <?php elseif($type === 'textarea'): ?>
        <textarea id="<?php echo e($name); ?>" name="<?php echo e($name); ?>" aria-describedby="<?php echo e($name); ?>-error<?php echo e($hint ? ' '.$name.'-hint' : ''); ?>" aria-invalid="<?php echo e($errors->has($name) ? 'true' : 'false'); ?>" <?php echo e($attributes->class(['form-control'])); ?>><?php echo e($value); ?></textarea>
    <?php else: ?>
        <input id="<?php echo e($name); ?>" name="<?php echo e($name); ?>" type="<?php echo e($type); ?>" value="<?php echo e($value); ?>" <?php if($alwaysRequired): ?> required <?php endif; ?> aria-describedby="<?php echo e($name); ?>-error<?php echo e($hint ? ' '.$name.'-hint' : ''); ?>" aria-invalid="<?php echo e($errors->has($name) ? 'true' : 'false'); ?>" <?php echo e($attributes->class(['form-control'])); ?>>
    <?php endif; ?>
    <?php if($hint): ?><p id="<?php echo e($name); ?>-hint" class="field-help"><?php echo e($hint); ?></p><?php endif; ?>
    <?php $__errorArgs = [$name];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p id="<?php echo e($name); ?>-error" class="editor-field-error"><?php echo e($message); ?></p><?php else: ?><span id="<?php echo e($name); ?>-error" hidden></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
</div>
<?php /**PATH H:\fatih\Telkom\smtr 5\PPL\career-compass\resources\views/components/professions/field.blade.php ENDPATH**/ ?>