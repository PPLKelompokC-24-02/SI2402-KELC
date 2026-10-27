<?php $__env->startSection('content'); ?>
    <div class="page-heading">
        <p class="page-eyebrow">MENU UTAMA</p>
        <h1>Beranda</h1>
    </div>
    <?php if (isset($component)) { $__componentOriginal0f606019c503dc9c0b07b3f9e4671106 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0f606019c503dc9c0b07b3f9e4671106 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.app.welcome-card','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.welcome-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0f606019c503dc9c0b07b3f9e4671106)): ?>
<?php $attributes = $__attributesOriginal0f606019c503dc9c0b07b3f9e4671106; ?>
<?php unset($__attributesOriginal0f606019c503dc9c0b07b3f9e4671106); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0f606019c503dc9c0b07b3f9e4671106)): ?>
<?php $component = $__componentOriginal0f606019c503dc9c0b07b3f9e4671106; ?>
<?php unset($__componentOriginal0f606019c503dc9c0b07b3f9e4671106); ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH H:\fatih\Telkom\smtr 5\PPL\career-compass\resources\views\home.blade.php ENDPATH**/ ?>