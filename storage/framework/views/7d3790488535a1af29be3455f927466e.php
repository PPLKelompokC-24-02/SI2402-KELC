<?php $__env->startSection('title', $register ? 'Daftar — Career Compass' : 'Selamat datang — Career Compass'); ?>

<?php $__env->startSection('auth-content'); ?>
        <h2 id="auth-title"><?php echo e($register ? 'Mulai langkahmu' : 'Selamat datang'); ?></h2>
        <p class="card-subtitle"><?php echo e($register ? 'Buat akun untuk menemukan arahmu.' : 'Masuk untuk melanjutkan'); ?></p>

        <?php if(session('status')): ?>
            <p class="auth-notice auth-notice--success" role="status"><?php echo e(session('status')); ?></p>
        <?php endif; ?>

        <form class="auth-form" action="<?php echo e($register ? route('register.store') : route('login.store')); ?>" method="POST" data-auth-form data-mode="<?php echo e($register ? 'register' : 'login'); ?>">
            <?php echo csrf_field(); ?>
            <div class="auth-notice auth-notice--error" data-form-error role="alert" tabindex="-1" hidden></div>

            <?php if($register): ?>
                <?php if (isset($component)) { $__componentOriginale2be5f5f1c011e26e63e98af53354f16 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale2be5f5f1c011e26e63e98af53354f16 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.auth-field','data' => ['name' => 'name','label' => 'Nama lengkap','placeholder' => 'Nama lengkapmu','autocomplete' => 'name','maxlength' => '255']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth-field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'name','label' => 'Nama lengkap','placeholder' => 'Nama lengkapmu','autocomplete' => 'name','maxlength' => '255']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale2be5f5f1c011e26e63e98af53354f16)): ?>
<?php $attributes = $__attributesOriginale2be5f5f1c011e26e63e98af53354f16; ?>
<?php unset($__attributesOriginale2be5f5f1c011e26e63e98af53354f16); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale2be5f5f1c011e26e63e98af53354f16)): ?>
<?php $component = $__componentOriginale2be5f5f1c011e26e63e98af53354f16; ?>
<?php unset($__componentOriginale2be5f5f1c011e26e63e98af53354f16); ?>
<?php endif; ?>
            <?php endif; ?>

            <?php if (isset($component)) { $__componentOriginale2be5f5f1c011e26e63e98af53354f16 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale2be5f5f1c011e26e63e98af53354f16 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.auth-field','data' => ['name' => 'email','label' => 'Alamat email','type' => 'email','placeholder' => 'nama@email.com','autocomplete' => 'email','maxlength' => '255','inputmode' => 'email','autocapitalize' => 'none','spellcheck' => 'false']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth-field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'email','label' => 'Alamat email','type' => 'email','placeholder' => 'nama@email.com','autocomplete' => 'email','maxlength' => '255','inputmode' => 'email','autocapitalize' => 'none','spellcheck' => 'false']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale2be5f5f1c011e26e63e98af53354f16)): ?>
<?php $attributes = $__attributesOriginale2be5f5f1c011e26e63e98af53354f16; ?>
<?php unset($__attributesOriginale2be5f5f1c011e26e63e98af53354f16); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale2be5f5f1c011e26e63e98af53354f16)): ?>
<?php $component = $__componentOriginale2be5f5f1c011e26e63e98af53354f16; ?>
<?php unset($__componentOriginale2be5f5f1c011e26e63e98af53354f16); ?>
<?php endif; ?>

            <?php if($register): ?>
                <?php if (isset($component)) { $__componentOriginale2be5f5f1c011e26e63e98af53354f16 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale2be5f5f1c011e26e63e98af53354f16 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.auth-field','data' => ['name' => 'password','label' => 'Kata sandi','type' => 'password','placeholder' => 'Buat kata sandi','autocomplete' => 'new-password','minlength' => '8','maxlength' => '72','hint' => 'Minimal 8 karakter, dengan huruf dan angka.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth-field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'password','label' => 'Kata sandi','type' => 'password','placeholder' => 'Buat kata sandi','autocomplete' => 'new-password','minlength' => '8','maxlength' => '72','hint' => 'Minimal 8 karakter, dengan huruf dan angka.']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale2be5f5f1c011e26e63e98af53354f16)): ?>
<?php $attributes = $__attributesOriginale2be5f5f1c011e26e63e98af53354f16; ?>
<?php unset($__attributesOriginale2be5f5f1c011e26e63e98af53354f16); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale2be5f5f1c011e26e63e98af53354f16)): ?>
<?php $component = $__componentOriginale2be5f5f1c011e26e63e98af53354f16; ?>
<?php unset($__componentOriginale2be5f5f1c011e26e63e98af53354f16); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginale2be5f5f1c011e26e63e98af53354f16 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale2be5f5f1c011e26e63e98af53354f16 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.auth-field','data' => ['name' => 'password_confirmation','label' => 'Konfirmasi kata sandi','type' => 'password','placeholder' => 'Ulangi kata sandi','autocomplete' => 'new-password','minlength' => '8','maxlength' => '72']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth-field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'password_confirmation','label' => 'Konfirmasi kata sandi','type' => 'password','placeholder' => 'Ulangi kata sandi','autocomplete' => 'new-password','minlength' => '8','maxlength' => '72']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale2be5f5f1c011e26e63e98af53354f16)): ?>
<?php $attributes = $__attributesOriginale2be5f5f1c011e26e63e98af53354f16; ?>
<?php unset($__attributesOriginale2be5f5f1c011e26e63e98af53354f16); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale2be5f5f1c011e26e63e98af53354f16)): ?>
<?php $component = $__componentOriginale2be5f5f1c011e26e63e98af53354f16; ?>
<?php unset($__componentOriginale2be5f5f1c011e26e63e98af53354f16); ?>
<?php endif; ?>
            <?php else: ?>
                <?php if (isset($component)) { $__componentOriginale2be5f5f1c011e26e63e98af53354f16 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale2be5f5f1c011e26e63e98af53354f16 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.auth-field','data' => ['name' => 'password','label' => 'Kata sandi','type' => 'password','placeholder' => 'Masukkan kata sandi','autocomplete' => 'current-password','maxlength' => '72']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth-field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'password','label' => 'Kata sandi','type' => 'password','placeholder' => 'Masukkan kata sandi','autocomplete' => 'current-password','maxlength' => '72']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale2be5f5f1c011e26e63e98af53354f16)): ?>
<?php $attributes = $__attributesOriginale2be5f5f1c011e26e63e98af53354f16; ?>
<?php unset($__attributesOriginale2be5f5f1c011e26e63e98af53354f16); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale2be5f5f1c011e26e63e98af53354f16)): ?>
<?php $component = $__componentOriginale2be5f5f1c011e26e63e98af53354f16; ?>
<?php unset($__componentOriginale2be5f5f1c011e26e63e98af53354f16); ?>
<?php endif; ?>
                <div class="form-options">
                    <label class="remember-label" for="remember"><input id="remember" type="checkbox" name="remember" value="1" <?php if(old('remember')): echo 'checked'; endif; ?>> <span>Ingat saya</span></label>
                </div>
            <?php endif; ?>

            <button class="primary-button" type="submit" data-submit-button>
                <span data-submit-label><?php echo e($register ? 'Buat akun' : 'Masuk ke akun'); ?></span>
                <span class="button-spinner" aria-hidden="true"></span>
                <?php if (isset($component)) { $__componentOriginal35256cbc8835b79b0ad2169177d019cb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal35256cbc8835b79b0ad2169177d019cb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.arrow','data' => ['dataDirection' => 'diagonal','class' => 'button-arrow']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('arrow'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['data-direction' => 'diagonal','class' => 'button-arrow']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal35256cbc8835b79b0ad2169177d019cb)): ?>
<?php $attributes = $__attributesOriginal35256cbc8835b79b0ad2169177d019cb; ?>
<?php unset($__attributesOriginal35256cbc8835b79b0ad2169177d019cb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal35256cbc8835b79b0ad2169177d019cb)): ?>
<?php $component = $__componentOriginal35256cbc8835b79b0ad2169177d019cb; ?>
<?php unset($__componentOriginal35256cbc8835b79b0ad2169177d019cb); ?>
<?php endif; ?>
            </button>
            <span class="sr-only" data-form-status role="status" aria-live="polite"></span>
        </form>

        <p class="card-footer">
            <?php echo e($register ? 'Sudah punya akun?' : 'Belum punya akun?'); ?>

            <a href="<?php echo e($register ? route('login').'#auth-card' : route('register').'#auth-card'); ?>"><?php echo e($register ? 'Masuk sekarang' : 'Daftar sekarang'); ?></a>
        </p>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.guest', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH H:\fatih\Telkom\smtr 5\PPL\career-compass\resources\views\auth\index.blade.php ENDPATH**/ ?>