<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['user', 'roleLabel', 'initials']));

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

foreach (array_filter((['user', 'roleLabel', 'initials']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<div class="sidebar-user">
    <span class="user-avatar user-avatar--large" aria-hidden="true"><?php echo e($initials); ?></span>
    <p class="sidebar-user-name"><?php echo e($user->name); ?></p>
    <span class="role-label"><?php echo e($roleLabel); ?></span>
</div>
<?php /**PATH H:\fatih\Telkom\smtr 5\PPL\career-compass\resources\views\components\app\user-profile.blade.php ENDPATH**/ ?>