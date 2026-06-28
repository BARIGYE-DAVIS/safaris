<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="robots" content="index, follow">
    <title><?php echo $__env->yieldContent('title', 'Safari Tours - Adventure Awaits'); ?></title>

    <!-- SEO Meta Tags -->
    <meta name="description" content="<?php echo $__env->yieldContent('meta_description', 'Discover amazing safari adventures in East Africa. Wildlife tours, cultural experiences, and unforgettable journeys await you.'); ?>">
    <meta name="keywords" content="<?php echo $__env->yieldContent('meta_keywords', 'safari, tours, wildlife, Africa, adventure, travel, booking'); ?>">
    <link rel="icon" type="image/png" href="<?php echo e(asset('images/logo.png')); ?>">

    <link rel="canonical" href="<?php echo $__env->yieldContent('canonical', url()->current()); ?>" />
    <meta name="robots" content="index, follow">
    <link rel="alternate" hreflang="en" href="<?php echo $__env->yieldContent('canonical', url()->current()); ?>" />

    <!-- Open Graph Tags -->
    <meta property="og:title" content="<?php echo $__env->yieldContent('og_title', 'Safari Tours - Adventure Awaits'); ?>">
    <meta property="og:description" content="<?php echo $__env->yieldContent('og_description', 'Discover amazing safari adventures in East Africa.'); ?>">
    <meta property="og:image" content="<?php echo $__env->yieldContent('og_image', asset('images/safari-og.jpg')); ?>">
    <meta property="og:url" content="<?php echo e(url()->current()); ?>">
    <meta property="og:type" content="website">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo $__env->yieldContent('twitter_title', 'Calm Africa Safaris'); ?>">
    <meta name="twitter:description" content="<?php echo $__env->yieldContent('twitter_description'); ?>">
    <meta name="twitter:image" content="<?php echo $__env->yieldContent('twitter_image', asset('images/safari-og.jpg')); ?>">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <!-- Styles -->
    <link rel="stylesheet" href="<?php echo e(asset('build/assets/app-lVDNHE2B.css')); ?>">
    <script type="module" src="<?php echo e(asset('build/assets/app-BLNZwArW.js')); ?>"></script>

    <!-- Additional Styles -->
    <?php echo $__env->yieldPushContent('styles'); ?>

    <!-- ============================================================
         CONTENT PROTECTION — Global CSS
    ============================================================ -->
    <style>
        /* Disable text selection site-wide */
        * {
            -webkit-user-select: none !important;
            -moz-user-select: none !important;
            -ms-user-select: none !important;
            user-select: none !important;
        }

        /* Re-enable selection inside form fields only */
        input, textarea, select, [contenteditable="true"] {
            -webkit-user-select: text !important;
            -moz-user-select: text !important;
            -ms-user-select: text !important;
            user-select: text !important;
        }

        /* Protection overlay (hidden by default) */
        #__cp_overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.75);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            z-index: 999999;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        #__cp_overlay.active {
            display: flex;
        }
        #__cp_box {
            background: #ffffff;
            border-radius: 16px;
            padding: 2.5rem 2rem;
            max-width: 420px;
            width: 100%;
            text-align: center;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.4);
            font-family: system-ui, sans-serif;
            animation: __cp_pop 0.25s ease;
        }
        @keyframes __cp_pop {
            from { transform: scale(0.85); opacity: 0; }
            to   { transform: scale(1);    opacity: 1; }
        }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased">

    <!-- ============================================================
         CONTENT PROTECTION OVERLAY
         hidden attribute keeps it out of Google's index entirely.
         JavaScript removes it before showing — works exactly as before.
    ============================================================ -->
    <div id="__cp_overlay" role="alertdialog" aria-modal="true" aria-labelledby="__cp_title" hidden>
        <div id="__cp_box">
            <div style="font-size:3rem; margin-bottom:0.75rem;"><i class="fas fa-lock text-red-500"></i></div>
            <h2 id="__cp_title" style="font-size:1.35rem; font-weight:700; color:#111; margin:0 0 0.5rem;">
                Content Protected
            </h2>
            <p style="font-size:0.95rem; color:#555; margin:0 0 1.25rem; line-height:1.6;">
                This website's content is protected by copyright.<br>
                Unauthorised copying or reproduction is strictly prohibited.
            </p>
            <p style="font-size:0.8rem; color:#aaa; margin:0 0 1.5rem;">
                &copy; <?php echo e(date('Y')); ?> Calm Africa Safaris. All rights reserved.
            </p>
            <button id="__cp_close"
                    style="background:#16a34a; color:#fff; border:none; border-radius:8px;
                           padding:0.65rem 2rem; font-size:0.95rem; font-weight:600; cursor:pointer;">
                OK, Got it
            </button>
        </div>
    </div>

    <!-- Navigation Component -->
    <?php if (isset($component)) { $__componentOriginalcd6dfa97cd4704d24f53bf5968f88fee = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcd6dfa97cd4704d24f53bf5968f88fee = $attributes; } ?>
<?php $component = App\View\Components\Navigation::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('navigation'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\Navigation::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcd6dfa97cd4704d24f53bf5968f88fee)): ?>
<?php $attributes = $__attributesOriginalcd6dfa97cd4704d24f53bf5968f88fee; ?>
<?php unset($__attributesOriginalcd6dfa97cd4704d24f53bf5968f88fee); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd6dfa97cd4704d24f53bf5968f88fee)): ?>
<?php $component = $__componentOriginalcd6dfa97cd4704d24f53bf5968f88fee; ?>
<?php unset($__componentOriginalcd6dfa97cd4704d24f53bf5968f88fee); ?>
<?php endif; ?>

    <!-- Page Header Section -->
    <?php if (! empty(trim($__env->yieldContent('page-header')))): ?>
        <?php echo $__env->yieldContent('page-header'); ?>
    <?php endif; ?>

    <!-- Main Content -->
    <main class="min-h-screen">
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <!-- Footer Component -->
    <?php if (isset($component)) { $__componentOriginal99051027c5120c83a2f9a5ae7c4c3cfa = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal99051027c5120c83a2f9a5ae7c4c3cfa = $attributes; } ?>
<?php $component = App\View\Components\Footer::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('footer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\Footer::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal99051027c5120c83a2f9a5ae7c4c3cfa)): ?>
<?php $attributes = $__attributesOriginal99051027c5120c83a2f9a5ae7c4c3cfa; ?>
<?php unset($__attributesOriginal99051027c5120c83a2f9a5ae7c4c3cfa); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal99051027c5120c83a2f9a5ae7c4c3cfa)): ?>
<?php $component = $__componentOriginal99051027c5120c83a2f9a5ae7c4c3cfa; ?>
<?php unset($__componentOriginal99051027c5120c83a2f9a5ae7c4c3cfa); ?>
<?php endif; ?>

    <!-- Additional Scripts -->
    <?php echo $__env->yieldPushContent('scripts'); ?>

    <!-- ============================================================
         CONTENT PROTECTION — Global JS
    ============================================================ -->
    <script>
    (function () {
        const overlay = document.getElementById('__cp_overlay');
        const closeBtn = document.getElementById('__cp_close');
        let _dismissTimer = null;

        /* Show the overlay — removes hidden first, then activates */
        function showAlert() {
            if (!overlay) return;
            overlay.removeAttribute('hidden'); // makes it available to CSS
            overlay.classList.add('active');   // triggers display:flex
            clearTimeout(_dismissTimer);
            _dismissTimer = setTimeout(hideAlert, 5000);
        }

        function hideAlert() {
            overlay?.classList.remove('active');
            clearTimeout(_dismissTimer);
        }

        closeBtn?.addEventListener('click', hideAlert);

        /* Close on click outside the box */
        overlay?.addEventListener('click', function (e) {
            if (e.target === overlay) hideAlert();
        });

        /* ── 1. Block right-click ── */
        document.addEventListener('contextmenu', function (e) {
            e.preventDefault();
            showAlert();
        });

        /* ── 2. Block text selection via mouse drag ── */
        document.addEventListener('selectstart', function (e) {
            if (!isFormEl(e.target)) e.preventDefault();
        });

        /* ── 3. Block copy & cut ── */
        document.addEventListener('copy', function (e) {
            if (!isFormEl(e.target)) { e.preventDefault(); showAlert(); }
        });
        document.addEventListener('cut', function (e) {
            if (!isFormEl(e.target)) { e.preventDefault(); showAlert(); }
        });

        /* ── 4. Block keyboard shortcuts ── */
        document.addEventListener('keydown', function (e) {
            const key  = e.key.toLowerCase();
            const ctrl = e.ctrlKey || e.metaKey; // Cmd on Mac

            // View source
            if (ctrl && key === 'u') { e.preventDefault(); showAlert(); return; }
            // Save page
            if (ctrl && key === 's') { e.preventDefault(); showAlert(); return; }
            // Print
            if (ctrl && key === 'p') { e.preventDefault(); showAlert(); return; }
            // Select all (outside forms)
            if (ctrl && key === 'a' && !isFormEl(e.target)) { e.preventDefault(); showAlert(); return; }
            // Copy (outside forms)
            if (ctrl && key === 'c' && !isFormEl(e.target)) { e.preventDefault(); showAlert(); return; }
            // F12 DevTools
            if (e.key === 'F12') { e.preventDefault(); showAlert(); return; }
            // Ctrl+Shift+I  DevTools
            if (ctrl && e.shiftKey && key === 'i') { e.preventDefault(); showAlert(); return; }
            // Ctrl+Shift+J  Console
            if (ctrl && e.shiftKey && key === 'j') { e.preventDefault(); showAlert(); return; }
            // Ctrl+Shift+C  Inspector
            if (ctrl && e.shiftKey && key === 'c') { e.preventDefault(); showAlert(); return; }
        });

        /* ── Helper: allow actions inside form fields ── */
        function isFormEl(el) {
            if (!el) return false;
            const tag = (el.tagName || '').toLowerCase();
            return ['input', 'textarea', 'select'].includes(tag) || el.isContentEditable;
        }
    })();
    </script>

</body>
</html><?php /**PATH C:\xampp\htdocs\safaris\resources\views/layouts/app.blade.php ENDPATH**/ ?>