<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['name']));

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

foreach (array_filter((['name']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<svg <?php echo e($attributes->merge(['viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor', 'stroke-width' => '1.6', 'stroke-linecap' => 'round', 'stroke-linejoin' => 'round', 'aria-hidden' => 'true'])); ?>>
    <?php switch($name):
        case ('data'): ?> <rect x="3" y="3" width="18" height="18" rx="3" /><path d="M7 16v-4m5 4V8m5 8v-6" /> <?php break; ?>
        <?php case ('code'): ?> <rect x="2" y="4" width="20" height="16" rx="3" /><path d="m8 9-3 3 3 3m8-6 3 3-3 3m-5 1 2-8" /> <?php break; ?>
        <?php case ('design'): ?> <path d="M4 20h4L21 7l-4-4L4 16v4Zm10-14 4 4M3 4h5M3 8h3" /> <?php break; ?>
        <?php case ('check'): ?> <path d="m5 12 4 4L19 6" /> <?php break; ?>
        <?php case ('back'): ?> <path d="m10 5-7 7 7 7M3 12h18" /> <?php break; ?>
        <?php case ('image'): ?> <rect x="3" y="3" width="18" height="18" rx="2" /><circle cx="8" cy="8" r="1.5" /><path d="m3 17 6-6 4 4 3-3 5 5" /> <?php break; ?>
        <?php case ('plus'): ?> <path d="M12 5v14M5 12h14" /> <?php break; ?>
        <?php case ('search'): ?> <circle cx="10.5" cy="10.5" r="6.5" /><path d="m16 16 5 5" /> <?php break; ?>
        <?php case ('home'): ?> <path d="m3 10 9-7 9 7v10H3V10Z" /><path d="M9 20v-7h6v7" /> <?php break; ?>
        <?php case ('user'): ?> <circle cx="12" cy="8" r="3.5" /><path d="M5 21v-2a7 7 0 0 1 14 0v2" /> <?php break; ?>
        <?php case ('users'): ?> <circle cx="9" cy="8" r="3" /><path d="M3 21v-2a6 6 0 0 1 12 0v2M16 5a3 3 0 0 1 0 6m3 10v-2a6 6 0 0 0-3-5" /> <?php break; ?>
        <?php case ('logout'): ?> <path d="M10 4H4v16h6m3-12 4 4-4 4m-5-4h13" /> <?php break; ?>
        <?php case ('compass'): ?> <circle cx="12" cy="12" r="9" /><path d="m16 8-2.5 5.5L8 16l2.5-5.5L16 8Z" /> <?php break; ?>
        <?php case ('briefcase'): ?> <rect x="3" y="7" width="18" height="14" rx="2" /><path d="M8 7V4h8v3M3 12a22 22 0 0 0 18 0m-9 0v3" /> <?php break; ?>
        <?php case ('book'): ?> <path d="M12 5c-3-2-6-2-9-1v15c3-1 6-1 9 1 3-2 6-2 9-1V4c-3-1-6-1-9 1v15" /> <?php break; ?>
        <?php case ('assessment'): ?> <rect x="5" y="4" width="14" height="17" rx="2" /><path d="M9 4V2h6v2M9 9h6m-6 4h6m-6 4h4" /> <?php break; ?>
        <?php case ('plan'): ?> <rect x="3" y="4" width="18" height="17" rx="2" /><path d="m7 9 1 1 2-2m3 1h4m-10 7 1 1 2-2m3 1h4" /> <?php break; ?>
        <?php case ('calendar'): ?> <rect x="3" y="5" width="18" height="16" rx="2" /><path d="M7 3v4m10-4v4M3 11h18m-14 4h2m4 0h2" /> <?php break; ?>
        <?php case ('document'): ?> <path d="M5 3h9l5 5v13H5V3Zm9 0v5h5M9 12h6m-6 4h6" /> <?php break; ?>
        <?php case ('progress'): ?> <circle cx="12" cy="12" r="9" /><path d="m8 12 3 3 5-6" /> <?php break; ?>
        <?php case ('monitor'): ?> <rect x="3" y="4" width="18" height="13" rx="2" /><path d="M8 21h8m-4-4v4m-5-10 3-3 3 3 4-4" /> <?php break; ?>
        <?php case ('inbox'): ?> <path d="m3 12 3-8h12l3 8v8H3v-8Zm0 0h5l2 3h4l2-3h5" /> <?php break; ?>
        <?php case ('publish'): ?> <path d="M4 15v6h16v-6M12 16V3m-5 5 5-5 5 5" /> <?php break; ?>
        <?php case ('history'): ?> <path d="M3 11a9 9 0 1 1 2 7M3 4v7h7m2-4v6l4 2" /> <?php break; ?>
        <?php case ('menu'): ?> <path d="M4 6h16M4 12h16M4 18h10" /> <?php break; ?>
        <?php case ('close'): ?> <path d="m6 6 12 12M6 18 18 6" /> <?php break; ?>
        <?php case ('chevron'): ?> <path d="m8 10 4 4 4-4" /> <?php break; ?>
    <?php endswitch; ?>
</svg>
<?php /**PATH H:\fatih\Telkom\smtr 5\PPL\career-compass\resources\views/components/app/icon.blade.php ENDPATH**/ ?>