<?php
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\Accommodation;

/**
 * Accommodations carousel partial
 * - Loads all active accommodations if $accommodations is not provided to the view.
 * - Expects Accommodation model with fields: name, slug, featured_image, type, category,
 *   price_from, price_to, currency, short_description, full_description, meta_description,
 *   is_active, is_featured, location, sort_order + relations: country, destination, images.
 * - Resolves featured image robustly and falls back to asset('images/default-accommodation.jpg').
 * - Seamless infinite auto-looping carousel with prev/next, indicators, touch swipe & mouse drag.
 */

// Normalize incoming $accommodations or fetch all active ones
if (isset($accommodations)) {
    if ($accommodations instanceof \Illuminate\Pagination\LengthAwarePaginator) {
        $accommodations = collect($accommodations->items());
    } else {
        $accommodations = collect($accommodations);
    }
} else {
    $accommodations = Accommodation::where('is_active', true)
        ->with(['country', 'destination', 'images'])
        ->orderBy('sort_order')
        ->orderBy('name')
        ->get();
}

$accommodations = $accommodations->unique('id')->values();
$count = $accommodations->count();
?>

<?php if($count > 0): ?>
<div class="accommodations-section bg-gray-700 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-white">Where To Stay while on East Africa Safaris</h2>
            <a href="<?php echo e(url('/accommodations')); ?>" class="text-white hover:text-white text-sm font-medium">View all &rarr;</a>
        </div>

        <div class="relative">

            <!-- Prev button -->
            <button type="button" aria-label="Previous accommodation" id="accom-prev"
                class="absolute left-2 top-1/2 -translate-y-1/2 z-20 bg-gray-800 hover:bg-white hover:text-gray-800 text-white rounded-full p-2 shadow transition">
                <svg class="w-4 h-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4l-6 6 6 6"/>
                </svg>
            </button>

            <!-- Next button -->
            <button type="button" aria-label="Next accommodation" id="accom-next"
                class="absolute right-2 top-1/2 -translate-y-1/2 z-20 bg-gray-800 hover:bg-white hover:text-gray-800 text-white rounded-full p-2 shadow transition">
                <svg class="w-4 h-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 4l6 6-6 6"/>
                </svg>
            </button>

            <!-- Scroller container -->
            <div id="accommodations-container" class="overflow-hidden">
                <div id="accommodations-track" class="flex space-x-6 will-change-transform">

                    <?php $__currentLoopData = $accommodations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $accommodation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            // ── Resolve featured image ────────────────────────────────
                            $raw = $accommodation->featured_image ?? null;

                            // Fallback to first gallery image if no featured_image
                            if (!$raw && $accommodation->relationLoaded('images') && $accommodation->images->isNotEmpty()) {
                                $raw = $accommodation->images->sortBy('sort_order')->first()->path ?? null;
                            }

                            if ($raw) {
                                if (Str::startsWith($raw, ['http://', 'https://'])) {
                                    $imgSrc = $raw;
                                } elseif (Str::startsWith($raw, '/storage/')) {
                                    $imgSrc = $raw;
                                } else {
                                    try {
                                        if (Storage::disk('public')->exists($raw)) {
                                            $imgSrc = Storage::url($raw);
                                        } elseif (file_exists(public_path($raw))) {
                                            $imgSrc = asset($raw);
                                        } else {
                                            $imgSrc = Storage::url($raw);
                                        }
                                    } catch (\Throwable $e) {
                                        $imgSrc = asset('images/default-accommodation.jpg');
                                    }
                                }
                            } else {
                                $imgSrc = asset('images/default-accommodation.jpg');
                            }

                            // ── Card link ─────────────────────────────────────────────
                            if (Route::has('accommodations.show') && !empty($accommodation->slug)) {
                                $link = route('accommodations.show', $accommodation->slug);
                            } else {
                                $link = url('/accommodations/' . ($accommodation->slug ?? $accommodation->id));
                            }

                            // ── Description: USE META_DESCRIPTION FIRST, then fallback ──
                            $desc = $accommodation->meta_description
                                    ?? $accommodation->short_description
                                    ?? $accommodation->full_description
                                    ?? '';

                            // ── Price label ───────────────────────────────────────────
                            $currency = $accommodation->currency ?? 'USD';
                            $priceLabel = null;
                            if (!empty($accommodation->price_from)) {
                                $priceLabel = $currency . ' ' . number_format($accommodation->price_from);
                                if (!empty($accommodation->price_to)) {
                                    $priceLabel .= ' – ' . number_format($accommodation->price_to);
                                }
                                $priceLabel .= ' / night';
                            }

                            // ── Type / category badge ─────────────────────────────────
                            $badge = $accommodation->category ?? $accommodation->type ?? null;
                        ?>

                        <a href="<?php echo e($link); ?>"
                           class="accom-card flex-shrink-0 w-64 bg-white rounded-lg overflow-hidden border hover:shadow-lg transition group"
                           aria-label="<?php echo e($accommodation->name ?? 'Accommodation'); ?>">

                            <!-- Image -->
                            <div class="h-40 bg-gray-200 overflow-hidden relative">
                                <img src="<?php echo e($imgSrc); ?>"
                                     alt="<?php echo e($accommodation->name ?? 'Accommodation'); ?>"
                                     class="w-full h-full object-cover transform transition duration-500 group-hover:scale-105"
                                     loading="lazy"
                                     decoding="async">

                                <?php if($accommodation->is_featured): ?>
                                    <span class="absolute top-2 left-2 bg-yellow-400 text-yellow-900 text-xs font-bold px-2 py-0.5 rounded">
                                        Featured
                                    </span>
                                <?php endif; ?>

                                <?php if($badge): ?>
                                    <span class="absolute top-2 right-2 bg-black/60 text-white text-xs px-2 py-0.5 rounded capitalize">
                                        <?php echo e($badge); ?>

                                    </span>
                                <?php endif; ?>
                            </div>

                            <!-- Card body -->
                            <div class="p-3">
                                <h3 class="text-sm font-semibold text-gray-800 leading-snug">
                                    <?php echo e(Str::limit($accommodation->name ?? 'Accommodation', 40)); ?>

                                </h3>

                                
                                <?php if(!empty($accommodation->destination?->name)): ?>
                                    <p class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                                        <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        <?php echo e($accommodation->destination->name); ?>

                                    </p>
                                <?php elseif(!empty($accommodation->country?->name)): ?>
                                    <p class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                                        <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        <?php echo e($accommodation->country->name); ?>

                                    </p>
                                <?php elseif(!empty($accommodation->location)): ?>
                                    <p class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                                        <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        <?php echo e(Str::limit($accommodation->location, 35)); ?>

                                    </p>
                                <?php endif; ?>

                                
                                <?php if(!empty($desc)): ?>
                                    <p class="text-xs text-gray-600 mt-2 leading-relaxed line-clamp-2">
                                        <?php echo e(Str::limit(strip_tags($desc), 100)); ?>

                                    </p>
                                <?php else: ?>
                                    <p class="text-xs text-gray-500 mt-2">Discover comfort and style at this property.</p>
                                <?php endif; ?>

                                
                                <?php if($priceLabel): ?>
                                    <p class="text-xs font-semibold text-green-700 mt-2">
                                        From <?php echo e($priceLabel); ?>

                                    </p>
                                <?php endif; ?>
                            </div>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </div>
            </div>

            <!-- Indicators -->
            <div id="accom-indicators" class="mt-4 flex items-center justify-center space-x-2" role="tablist">
                <?php for($i = 0; $i < $count; $i++): ?>
                    <button
                        class="accom-indicator w-2 h-2 rounded-full bg-gray-400 hover:bg-gray-300 transition"
                        data-index="<?php echo e($i); ?>"
                        aria-label="Show accommodation <?php echo e($i + 1); ?>"
                        role="tab"
                        aria-selected="<?php echo e($i === 0 ? 'true' : 'false'); ?>">
                    </button>
                <?php endfor; ?>
            </div>

        </div>
    </div>

    <style>
        #accommodations-container {
            overflow: hidden;
            scroll-behavior: auto;
            cursor: grab;
            user-select: none;
            -webkit-user-select: none;
        }
        #accommodations-container:active {
            cursor: grabbing;
        }
        #accommodations-track a {
            -webkit-user-drag: none;
            user-drag: none;
        }
        #accommodations-track img {
            display: block;
            pointer-events: none;
        }
        .accom-indicator[aria-selected="true"] {
            background-color: #4f46e5;
            width: 10px;
            height: 10px;
        }
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const container      = document.getElementById('accommodations-container');
            const track          = document.getElementById('accommodations-track');
            const nextBtn        = document.getElementById('accom-next');
            const prevBtn        = document.getElementById('accom-prev');
            const indicatorsWrap = document.getElementById('accom-indicators');
            const indicators     = indicatorsWrap ? Array.from(indicatorsWrap.children) : [];

            if (!container || !track) return;

            const originalItems = Array.from(track.children);
            const n = originalItems.length;
            if (n === 0) return;

            if (n === 1) {
                track.classList.add('justify-center');
                if (indicators[0]) {
                    indicators[0].setAttribute('aria-selected', 'true');
                    indicators[0].classList.replace('bg-gray-400', 'bg-indigo-600');
                }
                return;
            }

            originalItems.forEach(node => track.appendChild(node.cloneNode(true)));

            let originalWidth    = 0;
            let cardAdvance      = 0;
            const speed          = 0.6;
            let paused           = false;
            let rafId            = null;
            let manualPauseTimer = null;
            let initialized      = false;

            let isDragging          = false;
            let dragStartX          = 0;
            let dragStartScrollLeft = 0;
            let dragMoved           = false;

            function recalcSizes() {
                setTimeout(() => {
                    originalWidth = track.scrollWidth / 2;
                    const firstCard = track.querySelector('.accom-card');
                    if (firstCard) {
                        const trackStyle = getComputedStyle(track);
                        const gap = parseFloat(trackStyle.gap) || parseFloat(trackStyle.columnGap) || 24;
                        cardAdvance = Math.ceil(firstCard.offsetWidth + gap);
                    } else {
                        cardAdvance = 280;
                    }
                    initialized = true;
                }, 150);
            }

            function wrapScroll() {
                if (!originalWidth) return;
                if (container.scrollLeft >= originalWidth) {
                    container.scrollLeft -= originalWidth;
                } else if (container.scrollLeft < 0) {
                    container.scrollLeft += originalWidth;
                }
            }

            function step() {
                if (!paused && initialized) {
                    container.scrollLeft += speed;
                    wrapScroll();
                    updateIndicators();
                }
                rafId = requestAnimationFrame(step);
            }

            function updateIndicators() {
                if (!indicators.length || !cardAdvance || !originalWidth) return;
                const pos   = ((container.scrollLeft % originalWidth) + originalWidth) % originalWidth;
                const index = Math.round(pos / cardAdvance) % n;
                indicators.forEach((dot, i) => {
                    const selected = i === index;
                    dot.setAttribute('aria-selected', selected ? 'true' : 'false');
                    dot.classList.toggle('bg-gray-400',   !selected);
                    dot.classList.toggle('bg-indigo-600',  selected);
                });
            }

            function pauseBriefly(duration = 2200) {
                paused = true;
                clearTimeout(manualPauseTimer);
                manualPauseTimer = setTimeout(() => { paused = false; }, duration);
            }

            nextBtn.addEventListener('click', () => {
                container.style.scrollBehavior = 'smooth';
                container.scrollBy({ left: cardAdvance * 2 });
                setTimeout(() => { container.style.scrollBehavior = 'auto'; }, 400);
                pauseBriefly();
            });

            prevBtn.addEventListener('click', () => {
                container.style.scrollBehavior = 'smooth';
                let newLeft = container.scrollLeft - (cardAdvance * 2);
                if (newLeft < 0) {
                    container.scrollLeft = originalWidth + newLeft + (cardAdvance * 2);
                    newLeft = container.scrollLeft - (cardAdvance * 2);
                }
                container.scrollBy({ left: -(cardAdvance * 2) });
                setTimeout(() => { container.style.scrollBehavior = 'auto'; }, 400);
                pauseBriefly();
            });

            indicators.forEach((dot) => {
                dot.addEventListener('click', () => {
                    const idx       = parseInt(dot.getAttribute('data-index'), 10);
                    const baseLoops = Math.floor(container.scrollLeft / originalWidth);
                    const target    = (baseLoops * originalWidth) + (idx * cardAdvance);
                    container.style.scrollBehavior = 'smooth';
                    container.scrollTo({ left: target });
                    setTimeout(() => { container.style.scrollBehavior = 'auto'; }, 400);
                    pauseBriefly();
                });
                dot.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); dot.click(); }
                });
            });

            container.addEventListener('mousedown', (e) => {
                if (e.button !== 0) return;
                isDragging = true;
                dragMoved = false;
                dragStartX = e.pageX;
                dragStartScrollLeft = container.scrollLeft;
                container.style.cursor = 'grabbing';
                paused = true;
                e.preventDefault();
            });

            window.addEventListener('mousemove', (e) => {
                if (!isDragging) return;
                const delta = dragStartX - e.pageX;
                if (Math.abs(delta) > 3) dragMoved = true;
                container.scrollLeft = dragStartScrollLeft + delta;
                wrapScroll();
                updateIndicators();
            });

            window.addEventListener('mouseup', () => {
                if (!isDragging) return;
                isDragging = false;
                container.style.cursor = 'grab';
                pauseBriefly(1500);
            });

            track.addEventListener('click', (e) => {
                if (dragMoved) {
                    e.preventDefault();
                    dragMoved = false;
                }
            }, true);

            let touchStartX          = 0;
            let touchStartScrollLeft = 0;
            let touchMoved           = false;

            container.addEventListener('touchstart', (e) => {
                touchStartX = e.touches[0].pageX;
                touchStartScrollLeft = container.scrollLeft;
                touchMoved = false;
                paused = true;
            }, { passive: true });

            container.addEventListener('touchmove', (e) => {
                const delta = touchStartX - e.touches[0].pageX;
                if (Math.abs(delta) > 5) touchMoved = true;
                container.scrollLeft = touchStartScrollLeft + delta;
                wrapScroll();
                updateIndicators();
            }, { passive: true });

            container.addEventListener('touchend', () => {
                pauseBriefly(1500);
            });

            track.addEventListener('click', (e) => {
                if (touchMoved) {
                    e.preventDefault();
                    touchMoved = false;
                }
            }, true);

            container.addEventListener('mouseenter', () => { if (!isDragging) paused = true; });
            container.addEventListener('mouseleave', () => { if (!isDragging) paused = false; });
            container.addEventListener('focusin',    () => paused = true);
            container.addEventListener('focusout',   () => paused = false);

            window.addEventListener('resize', recalcSizes);
            track.querySelectorAll('img').forEach(img => {
                if (img.complete) return;
                img.addEventListener('load', recalcSizes);
            });

            recalcSizes();
            rafId = requestAnimationFrame(step);

            window.addEventListener('beforeunload', () => cancelAnimationFrame(rafId));
        });
    </script>
</div>
<?php endif; ?><?php /**PATH C:\xampp\htdocs\safaris\resources\views/partials/accommodation.blade.php ENDPATH**/ ?>