<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="theme-color" content="#F8F7F2">
    <title><?php echo $__env->yieldContent('title', 'Beranda — Career Compass'); ?></title>
    <link rel="icon" href="<?php echo e(asset('images/compass.svg')); ?>" type="image/svg+xml">
    <link rel="stylesheet" href="<?php echo e(asset('css/app.css')); ?>?v=<?php echo e(filemtime(public_path('css/app.css'))); ?>">
    <script src="<?php echo e(asset('js/app.js')); ?>?v=<?php echo e(filemtime(public_path('js/app.js'))); ?>" defer></script>
    <?php echo $__env->yieldPushContent('head'); ?>
</head>
<body class="app-shell">
    <a class="skip-link" href="#main-content">Langsung ke konten</a>
    <?php if (isset($component)) { $__componentOriginal9679e66420851475f061bd7197575bd5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9679e66420851475f061bd7197575bd5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.app.topbar','data' => ['user' => $user,'roleLabel' => $roleLabel,'initials' => $initials]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.topbar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['user' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($user),'role-label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($roleLabel),'initials' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($initials)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9679e66420851475f061bd7197575bd5)): ?>
<?php $attributes = $__attributesOriginal9679e66420851475f061bd7197575bd5; ?>
<?php unset($__attributesOriginal9679e66420851475f061bd7197575bd5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9679e66420851475f061bd7197575bd5)): ?>
<?php $component = $__componentOriginal9679e66420851475f061bd7197575bd5; ?>
<?php unset($__componentOriginal9679e66420851475f061bd7197575bd5); ?>
<?php endif; ?>
    <div class="sidebar-backdrop" data-sidebar-backdrop hidden></div>
    <?php if (isset($component)) { $__componentOriginal790df3a3003b05a46d3e5fdd59aeab47 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal790df3a3003b05a46d3e5fdd59aeab47 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.app.sidebar','data' => ['user' => $user,'roleLabel' => $roleLabel,'initials' => $initials,'navigation' => $navigation]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.sidebar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['user' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($user),'role-label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($roleLabel),'initials' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($initials),'navigation' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($navigation)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal790df3a3003b05a46d3e5fdd59aeab47)): ?>
<?php $attributes = $__attributesOriginal790df3a3003b05a46d3e5fdd59aeab47; ?>
<?php unset($__attributesOriginal790df3a3003b05a46d3e5fdd59aeab47); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal790df3a3003b05a46d3e5fdd59aeab47)): ?>
<?php $component = $__componentOriginal790df3a3003b05a46d3e5fdd59aeab47; ?>
<?php unset($__componentOriginal790df3a3003b05a46d3e5fdd59aeab47); ?>
<?php endif; ?>
    <main class="app-main" id="main-content" tabindex="-1">
        <?php echo $__env->yieldContent('content'); ?>
    </main>
</body>
</html>
<?php /**PATH H:\fatih\Telkom\smtr 5\PPL\career-compass\resources\views/layouts/app.blade.php ENDPATH**/ ?>