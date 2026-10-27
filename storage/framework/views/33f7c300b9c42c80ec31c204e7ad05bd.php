<?php $__env->startSection('title', ($profession->exists ? 'Edit Profesi' : 'Tambah Profesi').' — Career Compass'); ?>
<?php $__env->startPush('head'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('css/professions.css')); ?>?v=<?php echo e(filemtime(public_path('css/professions.css'))); ?>">
    <script src="<?php echo e(asset('js/professions.js')); ?>?v=<?php echo e(filemtime(public_path('js/professions.js'))); ?>" defer></script>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
<?php
    $prospectRows = old('prospects', $profession->prospects) ?: [''];
    $responsibilityRows = old('responsibilities', $profession->responsibilities) ?: [''];
    $competencyRows = old('competency_ids', $profession->competencies->pluck('id')->all()) ?: [''];
?>
<div class="profession-page">
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
    <div class="profession-heading">
        <div><h1><?php echo e($profession->exists ? 'Edit Profesi' : 'Tambah Profesi'); ?></h1><p>Lengkapi informasi profesi untuk membantu mahasiswa mengeksplorasi pilihan karier.</p></div>
        <?php if (isset($component)) { $__componentOriginal392b56d02ebb1ac82a60e45af6b5fff2 = $component; } ?>
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
<?php endif; ?>
    </div>
    <?php if(session('status')): ?><div class="profession-notice profession-notice--success" role="status"><?php echo e(session('status')); ?></div><?php endif; ?>
    <?php if($errors->any()): ?>
        <div class="profession-notice profession-notice--error" role="alert" tabindex="-1" id="validation-summary"><strong>Periksa kembali isian berikut.</strong><ul><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($error); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul><p>Jika memilih berkas tadi, pilih ulang sebelum menyimpan.</p></div>
    <?php endif; ?>
    <p class="required-note"><span aria-hidden="true">*</span> Wajib diisi untuk menerbitkan profesi. Kamu dapat menyimpan draf sebelum semua informasi lengkap.</p>
    <form method="POST" action="<?php echo e($profession->exists ? route('admin.professions.update', $profession) : route('admin.professions.store')); ?>" enctype="multipart/form-data" data-profession-form>
        <?php echo csrf_field(); ?>
        <?php if($profession->exists): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>
        <section class="editor-section" aria-labelledby="basic-title">
            <div class="section-heading"><h2 id="basic-title"><span>1.</span> Informasi dasar</h2><p>Informasi ini muncul pada kartu dan halaman detail profesi.</p></div>
            <div class="editor-grid editor-grid--two">
                <?php if (isset($component)) { $__componentOriginal8c8be0b61cf17d475f9738e2e72486ec = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8c8be0b61cf17d475f9738e2e72486ec = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.professions.field','data' => ['name' => 'name','label' => 'Nama profesi','required' => true,'alwaysRequired' => true,'value' => old('name', $profession->name),'placeholder' => 'Contoh: Data Analyst','maxlength' => '150']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('professions.field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'name','label' => 'Nama profesi','required' => true,'always-required' => true,'value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('name', $profession->name)),'placeholder' => 'Contoh: Data Analyst','maxlength' => '150']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8c8be0b61cf17d475f9738e2e72486ec)): ?>
<?php $attributes = $__attributesOriginal8c8be0b61cf17d475f9738e2e72486ec; ?>
<?php unset($__attributesOriginal8c8be0b61cf17d475f9738e2e72486ec); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8c8be0b61cf17d475f9738e2e72486ec)): ?>
<?php $component = $__componentOriginal8c8be0b61cf17d475f9738e2e72486ec; ?>
<?php unset($__componentOriginal8c8be0b61cf17d475f9738e2e72486ec); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal8c8be0b61cf17d475f9738e2e72486ec = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8c8be0b61cf17d475f9738e2e72486ec = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.professions.field','data' => ['name' => 'career_category_id','label' => 'Kategori','type' => 'select','required' => true,'dataPublishRequired' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('professions.field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'career_category_id','label' => 'Kategori','type' => 'select','required' => true,'data-publish-required' => true]); ?>
                    <option value="">Pilih kategori karier</option>
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($category->id); ?>" <?php if(old('career_category_id', $profession->career_category_id) == $category->id): echo 'selected'; endif; ?>><?php echo e($category->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8c8be0b61cf17d475f9738e2e72486ec)): ?>
<?php $attributes = $__attributesOriginal8c8be0b61cf17d475f9738e2e72486ec; ?>
<?php unset($__attributesOriginal8c8be0b61cf17d475f9738e2e72486ec); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8c8be0b61cf17d475f9738e2e72486ec)): ?>
<?php $component = $__componentOriginal8c8be0b61cf17d475f9738e2e72486ec; ?>
<?php unset($__componentOriginal8c8be0b61cf17d475f9738e2e72486ec); ?>
<?php endif; ?>
            </div>
            <?php if (isset($component)) { $__componentOriginal8c8be0b61cf17d475f9738e2e72486ec = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8c8be0b61cf17d475f9738e2e72486ec = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.professions.field','data' => ['name' => 'summary','label' => 'Deskripsi singkat','type' => 'textarea','required' => true,'value' => old('summary', $profession->summary),'rows' => '2','maxlength' => '200','placeholder' => 'Ringkasan singkat tentang profesi ini','hint' => 'Ringkasan untuk daftar profesi. Maksimal 200 karakter.','dataCharacterCount' => '200','dataPublishRequired' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('professions.field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'summary','label' => 'Deskripsi singkat','type' => 'textarea','required' => true,'value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('summary', $profession->summary)),'rows' => '2','maxlength' => '200','placeholder' => 'Ringkasan singkat tentang profesi ini','hint' => 'Ringkasan untuk daftar profesi. Maksimal 200 karakter.','data-character-count' => '200','data-publish-required' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8c8be0b61cf17d475f9738e2e72486ec)): ?>
<?php $attributes = $__attributesOriginal8c8be0b61cf17d475f9738e2e72486ec; ?>
<?php unset($__attributesOriginal8c8be0b61cf17d475f9738e2e72486ec); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8c8be0b61cf17d475f9738e2e72486ec)): ?>
<?php $component = $__componentOriginal8c8be0b61cf17d475f9738e2e72486ec; ?>
<?php unset($__componentOriginal8c8be0b61cf17d475f9738e2e72486ec); ?>
<?php endif; ?>
            <p class="character-counter" data-count-for="summary"><?php echo e(mb_strlen(old('summary', $profession->summary) ?? '')); ?>/200</p>
            <?php if (isset($component)) { $__componentOriginal8c8be0b61cf17d475f9738e2e72486ec = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8c8be0b61cf17d475f9738e2e72486ec = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.professions.field','data' => ['name' => 'description','label' => 'Deskripsi lengkap','type' => 'textarea','required' => true,'value' => old('description', $profession->description),'rows' => '5','maxlength' => '20000','placeholder' => 'Jelaskan peran profesi, ruang lingkup pekerjaan, dan kontribusinya.','dataPublishRequired' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('professions.field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'description','label' => 'Deskripsi lengkap','type' => 'textarea','required' => true,'value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('description', $profession->description)),'rows' => '5','maxlength' => '20000','placeholder' => 'Jelaskan peran profesi, ruang lingkup pekerjaan, dan kontribusinya.','data-publish-required' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8c8be0b61cf17d475f9738e2e72486ec)): ?>
<?php $attributes = $__attributesOriginal8c8be0b61cf17d475f9738e2e72486ec; ?>
<?php unset($__attributesOriginal8c8be0b61cf17d475f9738e2e72486ec); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8c8be0b61cf17d475f9738e2e72486ec)): ?>
<?php $component = $__componentOriginal8c8be0b61cf17d475f9738e2e72486ec; ?>
<?php unset($__componentOriginal8c8be0b61cf17d475f9738e2e72486ec); ?>
<?php endif; ?>
            <div class="illustration-upload">
                <div class="illustration-preview" data-image-preview>
                    <?php if($profession->illustration_path): ?><img src="<?php echo e(route('admin.professions.illustration', $profession)); ?>" alt="Ilustrasi profesi saat ini"><?php else: ?><?php if (isset($component)) { $__componentOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.app.icon','data' => ['name' => 'image']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'image']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa)): ?>
<?php $attributes = $__attributesOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa; ?>
<?php unset($__attributesOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa)): ?>
<?php $component = $__componentOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa; ?>
<?php unset($__componentOriginal2576f5d6de2a4b8cb7c0fbee0eb938aa); ?>
<?php endif; ?><span>Ilustrasi</span><?php endif; ?>
                </div>
                <div class="editor-field">
                    <label for="illustration">Ilustrasi profesi <span aria-hidden="true">*</span></label>
                    <p class="field-help" data-image-filename><?php echo e($profession->illustration_path ? 'Ilustrasi tersimpan. Pilih berkas untuk menggantinya.' : 'Belum ada berkas dipilih.'); ?></p>
                    <input id="illustration" name="illustration" type="file" accept="image/png,image/jpeg" data-file-max="2097152" data-illustration data-existing-image="<?php echo e($profession->illustration_path ? 'true' : 'false'); ?>" aria-describedby="illustration-help illustration-error">
                    <p class="field-help" id="illustration-help">PNG atau JPG, maksimal 2 MB. Rekomendasi rasio 3:2.</p>
                    <?php if($profession->illustration_path): ?><label class="remove-image"><input type="checkbox" name="remove_illustration" value="1" <?php if(old('remove_illustration')): echo 'checked'; endif; ?>> Hapus ilustrasi lama</label><?php endif; ?>
                    <p id="illustration-error" class="editor-field-error" <?php if (! ($errors->has('illustration'))): ?> hidden <?php endif; ?>><?php echo e($errors->first('illustration')); ?></p>
                </div>
            </div>
        </section>

        <section class="editor-section" aria-labelledby="profile-title">
            <div class="section-heading"><h2 id="profile-title"><span>2.</span> Profil dan prospek karier</h2><p>Tentukan tingkat awal, rentang jenjang, dan peluang di industri.</p></div>
            <div class="editor-grid editor-grid--three">
                <?php if (isset($component)) { $__componentOriginal8c8be0b61cf17d475f9738e2e72486ec = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8c8be0b61cf17d475f9738e2e72486ec = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.professions.field','data' => ['name' => 'entry_level','label' => 'Tingkat awal','type' => 'select','required' => true,'dataPublishRequired' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('professions.field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'entry_level','label' => 'Tingkat awal','type' => 'select','required' => true,'data-publish-required' => true]); ?>
                    <option value="">Pilih tingkat awal</option><?php $__currentLoopData = config('professions.entry_levels'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $level): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option <?php if(old('entry_level', $profession->entry_level) === $level): echo 'selected'; endif; ?>><?php echo e($level); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8c8be0b61cf17d475f9738e2e72486ec)): ?>
<?php $attributes = $__attributesOriginal8c8be0b61cf17d475f9738e2e72486ec; ?>
<?php unset($__attributesOriginal8c8be0b61cf17d475f9738e2e72486ec); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8c8be0b61cf17d475f9738e2e72486ec)): ?>
<?php $component = $__componentOriginal8c8be0b61cf17d475f9738e2e72486ec; ?>
<?php unset($__componentOriginal8c8be0b61cf17d475f9738e2e72486ec); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal8c8be0b61cf17d475f9738e2e72486ec = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8c8be0b61cf17d475f9738e2e72486ec = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.professions.field','data' => ['name' => 'career_start','label' => 'Jenjang awal','type' => 'select','required' => true,'dataPublishRequired' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('professions.field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'career_start','label' => 'Jenjang awal','type' => 'select','required' => true,'data-publish-required' => true]); ?>
                    <option value="">Pilih jenjang awal</option><?php $__currentLoopData = config('professions.career_levels'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $level): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option <?php if(old('career_start', $profession->career_start) === $level): echo 'selected'; endif; ?>><?php echo e($level); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8c8be0b61cf17d475f9738e2e72486ec)): ?>
<?php $attributes = $__attributesOriginal8c8be0b61cf17d475f9738e2e72486ec; ?>
<?php unset($__attributesOriginal8c8be0b61cf17d475f9738e2e72486ec); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8c8be0b61cf17d475f9738e2e72486ec)): ?>
<?php $component = $__componentOriginal8c8be0b61cf17d475f9738e2e72486ec; ?>
<?php unset($__componentOriginal8c8be0b61cf17d475f9738e2e72486ec); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal8c8be0b61cf17d475f9738e2e72486ec = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8c8be0b61cf17d475f9738e2e72486ec = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.professions.field','data' => ['name' => 'career_end','label' => 'Jenjang akhir','type' => 'select','required' => true,'dataPublishRequired' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('professions.field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'career_end','label' => 'Jenjang akhir','type' => 'select','required' => true,'data-publish-required' => true]); ?>
                    <option value="">Pilih jenjang akhir</option><?php $__currentLoopData = config('professions.career_levels'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $level): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option <?php if(old('career_end', $profession->career_end) === $level): echo 'selected'; endif; ?>><?php echo e($level); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8c8be0b61cf17d475f9738e2e72486ec)): ?>
<?php $attributes = $__attributesOriginal8c8be0b61cf17d475f9738e2e72486ec; ?>
<?php unset($__attributesOriginal8c8be0b61cf17d475f9738e2e72486ec); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8c8be0b61cf17d475f9738e2e72486ec)): ?>
<?php $component = $__componentOriginal8c8be0b61cf17d475f9738e2e72486ec; ?>
<?php unset($__componentOriginal8c8be0b61cf17d475f9738e2e72486ec); ?>
<?php endif; ?>
            </div>
            <?php if (isset($component)) { $__componentOriginal8c8be0b61cf17d475f9738e2e72486ec = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8c8be0b61cf17d475f9738e2e72486ec = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.professions.field','data' => ['name' => 'industries_text','label' => 'Bidang industri','required' => true,'value' => old('industries_text', implode(', ', $profession->industries ?? [])),'placeholder' => 'Contoh: Teknologi, Keuangan, E-commerce','maxlength' => '3000','hint' => 'Pisahkan industri dengan koma. Kamu dapat memasukkan lebih dari satu bidang.','dataPublishRequired' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('professions.field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'industries_text','label' => 'Bidang industri','required' => true,'value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('industries_text', implode(', ', $profession->industries ?? []))),'placeholder' => 'Contoh: Teknologi, Keuangan, E-commerce','maxlength' => '3000','hint' => 'Pisahkan industri dengan koma. Kamu dapat memasukkan lebih dari satu bidang.','data-publish-required' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8c8be0b61cf17d475f9738e2e72486ec)): ?>
<?php $attributes = $__attributesOriginal8c8be0b61cf17d475f9738e2e72486ec; ?>
<?php unset($__attributesOriginal8c8be0b61cf17d475f9738e2e72486ec); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8c8be0b61cf17d475f9738e2e72486ec)): ?>
<?php $component = $__componentOriginal8c8be0b61cf17d475f9738e2e72486ec; ?>
<?php unset($__componentOriginal8c8be0b61cf17d475f9738e2e72486ec); ?>
<?php endif; ?>
            <?php $__errorArgs = ['industries'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="editor-field-error"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            <div class="repeat-group" data-repeat-group="prospects" data-max-rows="20">
                <h3>Prospek karier <span aria-hidden="true">*</span></h3>
                <?php $__errorArgs = ['prospects'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="editor-field-error"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                <div data-repeat-rows>
                    <?php $__currentLoopData = is_array($prospectRows) ? $prospectRows : ['']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if (isset($component)) { $__componentOriginal1079b4db2d128ec881e23dacd5ebe79e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1079b4db2d128ec881e23dacd5ebe79e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.professions.list-row','data' => ['field' => 'prospects','index' => $index,'value' => $value,'label' => 'Prospek karier']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('professions.list-row'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['field' => 'prospects','index' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($index),'value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($value),'label' => 'Prospek karier']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1079b4db2d128ec881e23dacd5ebe79e)): ?>
<?php $attributes = $__attributesOriginal1079b4db2d128ec881e23dacd5ebe79e; ?>
<?php unset($__attributesOriginal1079b4db2d128ec881e23dacd5ebe79e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1079b4db2d128ec881e23dacd5ebe79e)): ?>
<?php $component = $__componentOriginal1079b4db2d128ec881e23dacd5ebe79e; ?>
<?php unset($__componentOriginal1079b4db2d128ec881e23dacd5ebe79e); ?>
<?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <button class="button button--small button--outline" type="button" data-add-row>+ Tambah Prospek</button>
                <template><?php if (isset($component)) { $__componentOriginal1079b4db2d128ec881e23dacd5ebe79e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1079b4db2d128ec881e23dacd5ebe79e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.professions.list-row','data' => ['field' => 'prospects','index' => '__INDEX__','label' => 'Prospek karier']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('professions.list-row'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['field' => 'prospects','index' => '__INDEX__','label' => 'Prospek karier']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1079b4db2d128ec881e23dacd5ebe79e)): ?>
<?php $attributes = $__attributesOriginal1079b4db2d128ec881e23dacd5ebe79e; ?>
<?php unset($__attributesOriginal1079b4db2d128ec881e23dacd5ebe79e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1079b4db2d128ec881e23dacd5ebe79e)): ?>
<?php $component = $__componentOriginal1079b4db2d128ec881e23dacd5ebe79e; ?>
<?php unset($__componentOriginal1079b4db2d128ec881e23dacd5ebe79e); ?>
<?php endif; ?></template>
            </div>
        </section>

        <section class="editor-section" aria-labelledby="responsibilities-title">
            <div class="section-heading"><h2 id="responsibilities-title"><span>3.</span> Tanggung jawab dan kompetensi</h2><p>Tuliskan satu poin per baris. Pilih kompetensi dari master data yang tersedia.</p></div>
            <div class="editor-grid editor-grid--two repeat-columns">
                <div class="repeat-group" data-repeat-group="responsibilities" data-max-rows="50">
                    <h3>Tanggung jawab utama <span aria-hidden="true">*</span></h3>
                    <?php $__errorArgs = ['responsibilities'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="editor-field-error"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <div data-repeat-rows><?php $__currentLoopData = is_array($responsibilityRows) ? $responsibilityRows : ['']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if (isset($component)) { $__componentOriginal1079b4db2d128ec881e23dacd5ebe79e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1079b4db2d128ec881e23dacd5ebe79e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.professions.list-row','data' => ['field' => 'responsibilities','index' => $index,'value' => $value,'label' => 'Tanggung jawab']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('professions.list-row'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['field' => 'responsibilities','index' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($index),'value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($value),'label' => 'Tanggung jawab']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1079b4db2d128ec881e23dacd5ebe79e)): ?>
<?php $attributes = $__attributesOriginal1079b4db2d128ec881e23dacd5ebe79e; ?>
<?php unset($__attributesOriginal1079b4db2d128ec881e23dacd5ebe79e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1079b4db2d128ec881e23dacd5ebe79e)): ?>
<?php $component = $__componentOriginal1079b4db2d128ec881e23dacd5ebe79e; ?>
<?php unset($__componentOriginal1079b4db2d128ec881e23dacd5ebe79e); ?>
<?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></div>
                    <button class="button button--small button--outline" type="button" data-add-row>+ Tambah Tanggung Jawab</button>
                    <template><?php if (isset($component)) { $__componentOriginal1079b4db2d128ec881e23dacd5ebe79e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1079b4db2d128ec881e23dacd5ebe79e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.professions.list-row','data' => ['field' => 'responsibilities','index' => '__INDEX__','label' => 'Tanggung jawab']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('professions.list-row'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['field' => 'responsibilities','index' => '__INDEX__','label' => 'Tanggung jawab']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1079b4db2d128ec881e23dacd5ebe79e)): ?>
<?php $attributes = $__attributesOriginal1079b4db2d128ec881e23dacd5ebe79e; ?>
<?php unset($__attributesOriginal1079b4db2d128ec881e23dacd5ebe79e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1079b4db2d128ec881e23dacd5ebe79e)): ?>
<?php $component = $__componentOriginal1079b4db2d128ec881e23dacd5ebe79e; ?>
<?php unset($__componentOriginal1079b4db2d128ec881e23dacd5ebe79e); ?>
<?php endif; ?></template>
                </div>
                <div class="repeat-group" data-repeat-group="competency_ids" data-max-rows="100">
                    <h3>Kompetensi yang dibutuhkan <span aria-hidden="true">*</span></h3>
                    <?php $__errorArgs = ['competency_ids'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="editor-field-error"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <div data-repeat-rows><?php $__currentLoopData = is_array($competencyRows) ? $competencyRows : ['']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if (isset($component)) { $__componentOriginal1079b4db2d128ec881e23dacd5ebe79e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1079b4db2d128ec881e23dacd5ebe79e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.professions.list-row','data' => ['field' => 'competency_ids','index' => $index,'value' => $value,'competencies' => $competencies,'label' => 'Kompetensi']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('professions.list-row'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['field' => 'competency_ids','index' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($index),'value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($value),'competencies' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($competencies),'label' => 'Kompetensi']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1079b4db2d128ec881e23dacd5ebe79e)): ?>
<?php $attributes = $__attributesOriginal1079b4db2d128ec881e23dacd5ebe79e; ?>
<?php unset($__attributesOriginal1079b4db2d128ec881e23dacd5ebe79e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1079b4db2d128ec881e23dacd5ebe79e)): ?>
<?php $component = $__componentOriginal1079b4db2d128ec881e23dacd5ebe79e; ?>
<?php unset($__componentOriginal1079b4db2d128ec881e23dacd5ebe79e); ?>
<?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></div>
                    <button class="button button--small button--outline" type="button" data-add-row>+ Tambah Kompetensi</button>
                    <template><?php if (isset($component)) { $__componentOriginal1079b4db2d128ec881e23dacd5ebe79e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1079b4db2d128ec881e23dacd5ebe79e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.professions.list-row','data' => ['field' => 'competency_ids','index' => '__INDEX__','competencies' => $competencies,'label' => 'Kompetensi']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('professions.list-row'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['field' => 'competency_ids','index' => '__INDEX__','competencies' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($competencies),'label' => 'Kompetensi']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1079b4db2d128ec881e23dacd5ebe79e)): ?>
<?php $attributes = $__attributesOriginal1079b4db2d128ec881e23dacd5ebe79e; ?>
<?php unset($__attributesOriginal1079b4db2d128ec881e23dacd5ebe79e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1079b4db2d128ec881e23dacd5ebe79e)): ?>
<?php $component = $__componentOriginal1079b4db2d128ec881e23dacd5ebe79e; ?>
<?php unset($__componentOriginal1079b4db2d128ec881e23dacd5ebe79e); ?>
<?php endif; ?></template>
                </div>
            </div>
        </section>

        <section class="editor-section" aria-labelledby="tools-title">
            <div class="section-heading"><h2 id="tools-title"><span>4.</span> Tools / teknologi</h2><p>Tambahkan tools beserta kegunaannya jika tersedia. Logo bersifat opsional, PNG/JPG maksimal 1 MB.</p></div>
            <div class="repeat-group" data-repeat-group="tools" data-max-rows="30">
                <div data-repeat-rows><?php $__currentLoopData = old('tools', $profession->tools ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $tool): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if (isset($component)) { $__componentOriginal32320b674f376826ae05f969ac31d410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal32320b674f376826ae05f969ac31d410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.professions.tool-row','data' => ['index' => $index,'tool' => $tool,'profession' => $profession]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('professions.tool-row'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['index' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($index),'tool' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($tool),'profession' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($profession)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal32320b674f376826ae05f969ac31d410)): ?>
<?php $attributes = $__attributesOriginal32320b674f376826ae05f969ac31d410; ?>
<?php unset($__attributesOriginal32320b674f376826ae05f969ac31d410); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal32320b674f376826ae05f969ac31d410)): ?>
<?php $component = $__componentOriginal32320b674f376826ae05f969ac31d410; ?>
<?php unset($__componentOriginal32320b674f376826ae05f969ac31d410); ?>
<?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></div>
                <p class="tools-empty" data-empty-rows>Belum ada tool ditambahkan.</p>
                <button class="button button--small button--outline" type="button" data-add-row>+ Tambah Tool / Teknologi</button>
                <template><?php if (isset($component)) { $__componentOriginal32320b674f376826ae05f969ac31d410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal32320b674f376826ae05f969ac31d410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.professions.tool-row','data' => ['index' => '__INDEX__','profession' => $profession]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('professions.tool-row'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['index' => '__INDEX__','profession' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($profession)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal32320b674f376826ae05f969ac31d410)): ?>
<?php $attributes = $__attributesOriginal32320b674f376826ae05f969ac31d410; ?>
<?php unset($__attributesOriginal32320b674f376826ae05f969ac31d410); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal32320b674f376826ae05f969ac31d410)): ?>
<?php $component = $__componentOriginal32320b674f376826ae05f969ac31d410; ?>
<?php unset($__componentOriginal32320b674f376826ae05f969ac31d410); ?>
<?php endif; ?></template>
            </div>
        </section>

        <div class="editor-actions">
            <div><strong data-save-state><?php echo e($profession->exists && ! session()->hasOldInput() ? 'Semua perubahan tersimpan.' : 'Profesi belum disimpan.'); ?></strong><p>Draf tidak ditampilkan kepada mahasiswa.</p></div>
            <div class="editor-action-buttons">
                <a class="button button--outline" href="<?php echo e(route('admin.professions.index')); ?>">Batal</a>
                <button class="button button--outline" type="submit" name="action" value="draft">Simpan Draf</button>
                <button class="button button--primary" type="submit" name="action" value="publish"><?php echo e($profession->is_active && $profession->exists ? 'Simpan & Terbitkan' : 'Terbitkan Profesi'); ?></button>
            </div>
            <span class="sr-only" role="status" data-editor-status></span>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH H:\fatih\Telkom\smtr 5\PPL\career-compass\resources\views\admin\professions\form.blade.php ENDPATH**/ ?>