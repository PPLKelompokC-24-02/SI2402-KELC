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
<header class="app-topbar">
    <a class="app-brand" href="<?php echo e(route('home')); ?>" aria-label="Career Compass — Beranda">
        <span class="app-brand-mark"><?php if (isset($component)) { $__componentOriginalbab72e08d5afd2f0b3d85efcbb85c9d8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbab72e08d5afd2f0b3d85efcbb85c9d8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.compass','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('compass'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbab72e08d5afd2f0b3d85efcbb85c9d8)): ?>
<?php $attributes = $__attributesOriginalbab72e08d5afd2f0b3d85efcbb85c9d8; ?>
<?php unset($__attributesOriginalbab72e08d5afd2f0b3d85efcbb85c9d8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbab72e08d5afd2f0b3d85efcbb85c9d8)): ?>
<?php $component = $__componentOriginalbab72e08d5afd2f0b3d85efcbb85c9d8; ?>
<?php unset($__componentOriginalbab72e08d5afd2f0b3d85efcbb85c9d8); ?>
<?php endif; ?></span>
        <span>career compass</span>
    </a>
    <button class="icon-button sidebar-toggle" type="button" data-sidebar-toggle aria-controls="app-sidebar" aria-expanded="true" aria-label="Tutup sidebar">
        <?php if (isset($component)) { $__componentOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.app.icon','data' => ['name' => 'menu']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'menu']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa)): ?>
<?php $attributes = $__attributesOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa; ?>
<?php unset($__attributesOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa)): ?>
<?php $component = $__componentOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa; ?>
<?php unset($__componentOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa); ?>
<?php endif; ?>
    </button>
    <details class="profile-menu" data-profile-menu>
        <summary class="profile-trigger">
            <span class="user-avatar" aria-hidden="true"><?php echo e($initials); ?></span>
            <span class="topbar-user-copy"><span class="topbar-user-name"><?php echo e($user->name); ?></span><span class="topbar-user-role"><?php echo e($roleLabel); ?></span></span>
            <?php if (isset($component)) { $__componentOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.app.icon','data' => ['name' => 'chevron','class' => 'profile-chevron']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'chevron','class' => 'profile-chevron']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa)): ?>
<?php $attributes = $__attributesOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa; ?>
<?php unset($__attributesOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa)): ?>
<?php $component = $__componentOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa; ?>
<?php unset($__componentOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa); ?>
<?php endif; ?>
            <span class="sr-only">Buka menu akun</span>
        </summary>
        <div class="profile-dropdown">
            <p class="dropdown-name"><?php echo e($user->name); ?></p>
            <p class="dropdown-email"><?php echo e($user->email); ?></p>
            <span class="role-label"><?php echo e($roleLabel); ?></span>
            <div class="dropdown-divider"></div>
            <form method="POST" action="<?php echo e(route('logout')); ?>">
                <?php echo csrf_field(); ?>
                <button class="dropdown-logout" type="submit"><?php if (isset($component)) { $__componentOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.app.icon','data' => ['name' => 'logout']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'logout']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa)): ?>
<?php $attributes = $__attributesOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa; ?>
<?php unset($__attributesOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa)): ?>
<?php $component = $__componentOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa; ?>
<?php unset($__componentOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa); ?>
<?php endif; ?> Logout</button>
            </form>
        </div>
    </details>
</header>
<?php /**PATH H:\fatih\Telkom\smtr 5\PPL\career-compass\resources\views\components\app\topbar.blade.php ENDPATH**/ ?>