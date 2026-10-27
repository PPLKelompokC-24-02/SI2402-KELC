<svg <?php echo e($attributes->merge(['viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor', 'stroke-width' => '1.6', 'stroke-linecap' => 'round', 'stroke-linejoin' => 'round', 'aria-hidden' => 'true'])); ?>>
    <?php if($attributes->get('data-direction') === 'diagonal'): ?>
        <path d="M7 17 17 7M7 7h10v10" />
    <?php else: ?>
        <path d="M5 12h14m-6-6 6 6-6 6" />
    <?php endif; ?>
</svg>
<?php /**PATH H:\fatih\Telkom\smtr 5\PPL\career-compass\resources\views\components\arrow.blade.php ENDPATH**/ ?>