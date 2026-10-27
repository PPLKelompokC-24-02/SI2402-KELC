<?php $__env->startSection('title', 'Manajemen Profesi — Career Compass'); ?>
<?php $__env->startPush('head'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/professions.css')); ?>?v=<?php echo e(filemtime(public_path('css/professions.css'))); ?>">
<script src="<?php echo e(asset('js/professions.js')); ?>?v=<?php echo e(filemtime(public_path('js/professions.js'))); ?>" defer></script>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
<div class="profession-page">
    <p class="profession-eyebrow">MANAJEMEN DATA</p>
    <div class="profession-heading"><div><h1>Manajemen Profesi</h1><p>Kelola informasi profesi untuk mendukung eksplorasi karier mahasiswa.</p></div><a class="button button--primary" href="<?php echo e(route('admin.professions.create')); ?>"><?php if (isset($component)) { $__componentOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.app.icon','data' => ['name' => 'plus']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'plus']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa)): ?>
<?php $attributes = $__attributesOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa; ?>
<?php unset($__attributesOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa)): ?>
<?php $component = $__componentOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa; ?>
<?php unset($__componentOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa); ?>
<?php endif; ?> Tambah Profesi</a></div>
    <?php if(session('status')): ?><p class="profession-notice profession-notice--success" role="status"><?php echo e(session('status')); ?></p><?php endif; ?>
    <section class="profession-list-card" aria-label="Daftar profesi">
        <form class="profession-filters" method="GET" action="<?php echo e(route('admin.professions.index')); ?>">
            <div class="search-field"><label class="sr-only" for="keyword">Cari nama profesi</label><?php if (isset($component)) { $__componentOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.app.icon','data' => ['name' => 'search']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'search']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa)): ?>
<?php $attributes = $__attributesOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa; ?>
<?php unset($__attributesOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa)): ?>
<?php $component = $__componentOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa; ?>
<?php unset($__componentOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa); ?>
<?php endif; ?><input class="form-control" id="keyword" type="search" name="keyword" value="<?php echo e(request('keyword')); ?>" placeholder="Cari nama profesi…" maxlength="150"></div>
            <div><label class="sr-only" for="status">Filter status</label><select class="form-control" id="status" name="status"><option value="">Semua status</option><option value="published" <?php if(request('status') === 'published'): echo 'selected'; endif; ?>>Terbit</option><option value="draft" <?php if(request('status') === 'draft'): echo 'selected'; endif; ?>>Draf</option><option value="inactive" <?php if(request('status') === 'inactive'): echo 'selected'; endif; ?>>Nonaktif</option></select></div>
            <button class="button button--outline" type="submit">Terapkan</button>
            <?php if(request()->filled('keyword') || request()->filled('status')): ?><a class="text-link" href="<?php echo e(route('admin.professions.index')); ?>">Reset</a><?php endif; ?>
        </form>
        <?php if($errors->any()): ?><div class="profession-notice profession-notice--error" role="alert"><?php echo e($errors->first()); ?></div><?php endif; ?>
        <?php if($professions->isEmpty()): ?>
            <div class="profession-empty"><?php if (isset($component)) { $__componentOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.app.icon','data' => ['name' => 'briefcase']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'briefcase']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa)): ?>
<?php $attributes = $__attributesOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa; ?>
<?php unset($__attributesOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa)): ?>
<?php $component = $__componentOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa; ?>
<?php unset($__componentOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa); ?>
<?php endif; ?><h2>Tidak ada profesi yang ditemukan</h2><p><?php echo e(request()->filled('keyword') || request()->filled('status') ? 'Coba kata kunci atau status yang lain.' : 'Mulai dengan menambahkan informasi profesi pertama.'); ?></p></div>
        <?php else: ?>
            <div class="table-scroll" tabindex="0" role="region" aria-label="Tabel profesi, geser untuk melihat semua kolom">
                <table class="profession-table"><caption class="sr-only">Daftar seluruh profesi, termasuk draf dan profesi nonaktif</caption><thead><tr><th scope="col">Profesi</th><th scope="col">Kategori</th><th scope="col">Status</th><th scope="col">Diperbarui</th><th scope="col">Aksi</th></tr></thead>
                <tbody><?php $__currentLoopData = $professions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $profession): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><tr>
                    <td><a class="profession-name" href="<?php echo e(route('admin.professions.show', $profession)); ?>"><?php echo e($profession->name); ?></a><p class="profession-excerpt"><?php echo e(Str::limit($profession->summary ?: ($profession->description ?: 'Informasi profesi belum dilengkapi.'), 100)); ?></p></td>
                    <td><?php echo e($profession->careerCategory?->name ?? 'Belum dipilih'); ?></td>
                    <td><?php if (isset($component)) { $__componentOriginal392b56d02ebb1ac82a60e45af6b5fff2 = $component; } ?>
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
<?php endif; ?></td>
                    <td class="date-cell"><?php echo e($profession->updated_at->format('d M Y')); ?></td>
                    <td><div class="row-actions"><a class="text-link" href="<?php echo e(route('admin.professions.show', $profession)); ?>">Detail<span class="sr-only"> <?php echo e($profession->name); ?></span></a><a class="text-link" href="<?php echo e(route('admin.professions.edit', $profession)); ?>">Edit<span class="sr-only"> <?php echo e($profession->name); ?></span></a>
                    <?php if($profession->is_active): ?><form method="POST" action="<?php echo e(route('admin.professions.destroy', $profession)); ?>" data-deactivate-form data-profession-name="<?php echo e($profession->name); ?>"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button class="text-link text-link--muted" type="submit">Nonaktifkan<span class="sr-only"> <?php echo e($profession->name); ?></span></button></form><?php endif; ?>
                    </div></td>
                </tr><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></tbody></table>
            </div>
            <div class="profession-pagination"><span>Menampilkan <?php echo e($professions->firstItem()); ?>–<?php echo e($professions->lastItem()); ?> dari <?php echo e($professions->total()); ?> profesi</span><nav aria-label="Pagination profesi"><?php if($professions->onFirstPage()): ?><span class="button button--small button--outline" aria-disabled="true">Sebelumnya</span><?php else: ?><a class="button button--small button--outline" href="<?php echo e($professions->previousPageUrl()); ?>">Sebelumnya</a><?php endif; ?><span>Halaman <?php echo e($professions->currentPage()); ?> / <?php echo e($professions->lastPage()); ?></span><?php if($professions->hasMorePages()): ?><a class="button button--small button--outline" href="<?php echo e($professions->nextPageUrl()); ?>">Berikutnya</a><?php else: ?><span class="button button--small button--outline" aria-disabled="true">Berikutnya</span><?php endif; ?></nav></div>
        <?php endif; ?>
    </section>
</div>
<dialog class="confirm-dialog" data-deactivate-dialog aria-labelledby="deactivate-title"><h2 id="deactivate-title">Nonaktifkan profesi?</h2><p data-deactivate-copy></p><p>Profesi tidak lagi tampil di katalog mahasiswa. Data dan relasinya tetap tersimpan.</p><div class="dialog-actions"><button class="button button--outline" type="button" data-cancel-deactivate>Batal</button><button class="button button--primary" type="button" data-confirm-deactivate>Nonaktifkan</button></div></dialog>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH H:\fatih\Telkom\smtr 5\PPL\career-compass\resources\views\admin\professions\index.blade.php ENDPATH**/ ?>