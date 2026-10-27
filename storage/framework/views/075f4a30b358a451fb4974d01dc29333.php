<?php $__env->startSection('title', $profession->name.' — Career Compass'); ?>
<?php $__env->startPush('head'); ?><link rel="stylesheet" href="<?php echo e(asset('css/professions.css')); ?>?v=<?php echo e(filemtime(public_path('css/professions.css'))); ?>"><?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
<div class="profession-page profession-detail">
    <a class="back-link" href="<?php echo e(route('admin.professions.index')); ?>"><?php if (isset($component)) { $__componentOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.app.icon','data' => ['name' => 'back']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'back']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa)): ?>
<?php $attributes = $__attributesOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa; ?>
<?php unset($__attributesOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa)): ?>
<?php $component = $__componentOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa; ?>
<?php unset($__componentOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa); ?>
<?php endif; ?> Kembali ke Manajemen Profesi</a>
    <div class="profession-heading"><div><h1><?php echo e($profession->name); ?></h1><p><?php echo e($profession->careerCategory?->name ?? 'Kategori belum dipilih'); ?></p></div><div class="detail-heading-actions"><?php if (isset($component)) { $__componentOriginal392b56d02ebb1ac82a60e45af6b5fff2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal392b56d02ebb1ac82a60e45af6b5fff2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.professions.status','data' => ['profession' => $profession]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('professions.status'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['profession' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($profession)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal392b56d02ebb1ac82a60e45af6b5fff2)): ?>
<?php $attributes = $__attributesOriginal392b56d02ebb1ac82a60e45af6b5fff2; ?>
<?php unset($__attributesOriginal392b56d02ebb1ac82a60e45af6b5fff2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal392b56d02ebb1ac82a60e45af6b5fff2)): ?>
<?php $component = $__componentOriginal392b56d02ebb1ac82a60e45af6b5fff2; ?>
<?php unset($__componentOriginal392b56d02ebb1ac82a60e45af6b5fff2); ?>
<?php endif; ?><a class="button button--primary" href="<?php echo e(route('admin.professions.edit', $profession)); ?>">Edit Profesi</a></div></div>
    <section class="editor-section detail-overview" aria-labelledby="overview-title">
        <?php if($profession->illustration_path): ?><img class="detail-illustration" src="<?php echo e(route('admin.professions.illustration', $profession)); ?>" alt="Ilustrasi <?php echo e($profession->name); ?>"><?php endif; ?>
        <div><h2 id="overview-title">Tentang profesi</h2><p class="detail-summary"><?php echo e($profession->summary ?: 'Deskripsi singkat belum diisi.'); ?></p><p class="detail-description"><?php echo e($profession->description ?: 'Deskripsi lengkap belum diisi.'); ?></p></div>
    </section>
    <section class="editor-section" aria-labelledby="detail-profile"><h2 id="detail-profile">Profil dan prospek karier</h2><dl class="detail-facts"><div><dt>Tingkat awal</dt><dd><?php echo e($profession->entry_level ?: 'Belum diisi'); ?></dd></div><div><dt>Rentang jenjang</dt><dd><?php echo e($profession->career_start ?: '—'); ?> → <?php echo e($profession->career_end ?: '—'); ?></dd></div><div><dt>Bidang industri</dt><dd><?php echo e(implode(', ', $profession->industries ?? []) ?: 'Belum diisi'); ?></dd></div></dl><h3>Prospek karier</h3><ul class="detail-list"><?php $__empty_1 = true; $__currentLoopData = $profession->prospects ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prospect): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><li><?php echo e($prospect); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><li>Belum diisi.</li><?php endif; ?></ul></section>
    <section class="editor-section editor-grid editor-grid--two"><div><h2>Tanggung jawab utama</h2><ul class="detail-list"><?php $__empty_1 = true; $__currentLoopData = $profession->responsibilities ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $responsibility): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><li><?php echo e($responsibility); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><li>Belum diisi.</li><?php endif; ?></ul></div><div><h2>Kompetensi yang dibutuhkan</h2><ul class="detail-list"><?php $__empty_1 = true; $__currentLoopData = $profession->competencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $competency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><li><strong><?php echo e($competency->name); ?></strong><span class="competency-description"><?php echo e($competency->description); ?></span></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><li>Belum dipilih.</li><?php endif; ?></ul></div></section>
    <section class="editor-section"><h2>Tools / teknologi</h2><div class="detail-tools"><?php $__empty_1 = true; $__currentLoopData = $profession->tools ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tool): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><div class="detail-tool"><?php if(! empty($tool['logo_path'])): ?><img src="<?php echo e(route('admin.professions.tool-logo', [$profession, $tool['key']])); ?>" alt="Logo <?php echo e($tool['name']); ?>" width="40" height="40"><?php endif; ?><div><h3><?php echo e($tool['name'] ?: 'Tool belum diberi nama'); ?></h3><p><?php echo e($tool['usage'] ?: 'Kegunaan belum diisi.'); ?></p></div></div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><p class="field-help">Belum ada tool ditambahkan.</p><?php endif; ?></div></section>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH H:\fatih\Telkom\smtr 5\PPL\career-compass\resources\views/admin/professions/show.blade.php ENDPATH**/ ?>