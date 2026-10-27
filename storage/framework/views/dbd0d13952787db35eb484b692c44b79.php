<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="theme-color" content="#F8F7F2">
    <meta name="description" content="Kenali diri, eksplorasi profesi, dan rencanakan langkah pengembangan kariermu bersama Career Compass. Untuk mahasiswa S1 Sistem Informasi Telkom University Kampus Jakarta.">
    <title><?php echo $__env->yieldContent('title', 'Career Compass — Temukan arah kariermu'); ?></title>
    <link rel="icon" href="<?php echo e(asset('images/compass.svg')); ?>" type="image/svg+xml">
    <link rel="stylesheet" href="<?php echo e(asset('css/auth.css')); ?>?v=<?php echo e(filemtime(public_path('css/auth.css'))); ?>">
    <script src="<?php echo e(asset('js/auth.js')); ?>?v=<?php echo e(filemtime(public_path('js/auth.js'))); ?>" defer></script>
</head>
<body>
    <a class="skip-link" href="#auth-card">Langsung ke formulir</a>
    <header class="site-header">
        <nav class="header-inner" aria-label="Navigasi utama">
            <a class="brand" href="<?php echo e(route('landing')); ?>" aria-label="Career Compass — Beranda">
                <span class="brand-mark"><?php if (isset($component)) { $__componentOriginalbab72e08d5afd2f0b3d85efcbb85c9d8 = $component; } ?>
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
        </nav>
    </header>
    <main class="landing-layout">
        <section class="hero" aria-labelledby="hero-title">
            <p class="eyebrow"><?php if (isset($component)) { $__componentOriginal35256cbc8835b79b0ad2169177d019cb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal35256cbc8835b79b0ad2169177d019cb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.arrow','data' => ['dataDirection' => 'diagonal']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('arrow'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['data-direction' => 'diagonal']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal35256cbc8835b79b0ad2169177d019cb)): ?>
<?php $attributes = $__attributesOriginal35256cbc8835b79b0ad2169177d019cb; ?>
<?php unset($__attributesOriginal35256cbc8835b79b0ad2169177d019cb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal35256cbc8835b79b0ad2169177d019cb)): ?>
<?php $component = $__componentOriginal35256cbc8835b79b0ad2169177d019cb; ?>
<?php unset($__componentOriginal35256cbc8835b79b0ad2169177d019cb); ?>
<?php endif; ?> <span>KENALI DIRI. TEMUKAN ARAH.</span></p>
            <h1 id="hero-title">Langkah berikutnya,<br>lebih <em>terarah.</em></h1>
            <p class="hero-description">Karier yang tepat dimulai dari mengenal diri. Eksplorasi profesi, pahami kompetensi yang dibutuhkan, dan rencanakan langkah pengembangan kariermu bersama Career Compass.</p>
            <div class="hero-action">
                <a class="hero-cta" href="#auth-card" data-focus-auth>Temukan arah kariermu <?php if (isset($component)) { $__componentOriginal35256cbc8835b79b0ad2169177d019cb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal35256cbc8835b79b0ad2169177d019cb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.arrow','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('arrow'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal35256cbc8835b79b0ad2169177d019cb)): ?>
<?php $attributes = $__attributesOriginal35256cbc8835b79b0ad2169177d019cb; ?>
<?php unset($__attributesOriginal35256cbc8835b79b0ad2169177d019cb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal35256cbc8835b79b0ad2169177d019cb)): ?>
<?php $component = $__componentOriginal35256cbc8835b79b0ad2169177d019cb; ?>
<?php unset($__componentOriginal35256cbc8835b79b0ad2169177d019cb); ?>
<?php endif; ?></a>
                <span class="hero-microcopy">Mulai dari langkah kecil.</span>
            </div>
        </section>
        <section class="auth-card" id="auth-card" aria-labelledby="auth-title" tabindex="-1">
            <div class="card-brand"><?php if (isset($component)) { $__componentOriginalbab72e08d5afd2f0b3d85efcbb85c9d8 = $component; } ?>
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
<?php endif; ?><span>CAREER COMPASS</span></div>
            <?php echo $__env->yieldContent('auth-content'); ?>
        </section>
    </main>
</body>
</html>
<?php /**PATH H:\fatih\Telkom\smtr 5\PPL\career-compass\resources\views/layouts/guest.blade.php ENDPATH**/ ?>