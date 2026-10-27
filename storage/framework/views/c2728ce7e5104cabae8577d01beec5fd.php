<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['user', 'roleLabel', 'initials', 'navigation']));

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

foreach (array_filter((['user', 'roleLabel', 'initials', 'navigation']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<aside class="app-sidebar" id="app-sidebar" aria-label="Navigasi aplikasi" tabindex="-1">
    <div class="sidebar-scroll">
        <div class="drawer-heading"><span>Menu navigasi</span><button class="icon-button" type="button" data-sidebar-close aria-label="Tutup sidebar"><?php if (isset($component)) { $__componentOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.app.icon','data' => ['name' => 'close']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'close']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa)): ?>
<?php $attributes = $__attributesOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa; ?>
<?php unset($__attributesOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa)): ?>
<?php $component = $__componentOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa; ?>
<?php unset($__componentOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa); ?>
<?php endif; ?></button></div>
        <?php if (isset($component)) { $__componentOriginalcc10cd9da43fa573d0fbf4b536f0d778 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcc10cd9da43fa573d0fbf4b536f0d778 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.app.user-profile','data' => ['user' => $user,'roleLabel' => $roleLabel,'initials' => $initials]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.user-profile'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['user' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($user),'role-label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($roleLabel),'initials' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($initials)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcc10cd9da43fa573d0fbf4b536f0d778)): ?>
<?php $attributes = $__attributesOriginalcc10cd9da43fa573d0fbf4b536f0d778; ?>
<?php unset($__attributesOriginalcc10cd9da43fa573d0fbf4b536f0d778); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcc10cd9da43fa573d0fbf4b536f0d778)): ?>
<?php $component = $__componentOriginalcc10cd9da43fa573d0fbf4b536f0d778; ?>
<?php unset($__componentOriginalcc10cd9da43fa573d0fbf4b536f0d778); ?>
<?php endif; ?>
        <nav class="sidebar-nav" aria-label="Menu <?php echo e($roleLabel); ?>">
            <?php $__currentLoopData = $navigation; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <section class="nav-section <?php echo e($loop->last ? 'nav-section--account' : ''); ?>" aria-labelledby="nav-section-<?php echo e($loop->index); ?>">
                    <h2 id="nav-section-<?php echo e($loop->index); ?>" class="nav-section-title"><?php echo e($section['title']); ?></h2>
                    <ul class="nav-list">
                        <?php $__currentLoopData = $section['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li>
                                <?php if($item['url'] && ($item['method'] ?? 'GET') === 'POST'): ?>
                                    <form action="<?php echo e($item['url']); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <button class="nav-link nav-link--logout" type="submit"><?php if (isset($component)) { $__componentOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.app.icon','data' => ['name' => $item['icon']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item['icon'])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa)): ?>
<?php $attributes = $__attributesOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa; ?>
<?php unset($__attributesOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa)): ?>
<?php $component = $__componentOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa; ?>
<?php unset($__componentOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa); ?>
<?php endif; ?><span><?php echo e($item['label']); ?></span></button>
                                    </form>
                                <?php elseif($item['url']): ?>
                                    <a class="nav-link <?php echo e($item['active'] ? 'is-active' : ''); ?>" href="<?php echo e($item['url']); ?>" <?php if($item['active']): ?> aria-current="page" <?php endif; ?>>
                                        <?php if (isset($component)) { $__componentOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.app.icon','data' => ['name' => $item['icon']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item['icon'])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa)): ?>
<?php $attributes = $__attributesOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa; ?>
<?php unset($__attributesOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa)): ?>
<?php $component = $__componentOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa; ?>
<?php unset($__componentOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa); ?>
<?php endif; ?><span><?php echo e($item['label']); ?></span>
                                    </a>
                                <?php else: ?>
                                    <span class="nav-link nav-link--disabled" aria-disabled="true">
                                        <?php if (isset($component)) { $__componentOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.app.icon','data' => ['name' => $item['icon']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item['icon'])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa)): ?>
<?php $attributes = $__attributesOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa; ?>
<?php unset($__attributesOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa)): ?>
<?php $component = $__componentOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa; ?>
<?php unset($__componentOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa); ?>
<?php endif; ?><span class="nav-link-copy"><span><?php echo e($item['label']); ?></span><span class="nav-status">Segera Hadir</span></span>
                                    </span>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </section>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </nav>
    </div>
</aside>
<?php /**PATH H:\fatih\Telkom\smtr 5\PPL\career-compass\resources\views\components\app\sidebar.blade.php ENDPATH**/ ?>