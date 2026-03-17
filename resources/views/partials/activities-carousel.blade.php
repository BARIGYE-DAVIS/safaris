@php
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\Activity;

/**
 * Activities carousel partial
 * - Loads all active activities if $activities is not provided to the view.
 * - Expects Activity model with fields like: name, slug, featured_image, image, image_path, is_active.
 * - Resolves images robustly and falls back to asset('images/default-activity.jpg').
 * - Seamless infinite auto-looping carousel with prev/next, indicators, touch swipe & mouse drag.
 */

// Normalize incoming $activities or fetch all active activities ordered by name
if (isset($activities)) {
    if ($activities instanceof \Illuminate\Pagination\LengthAwarePaginator) {
        $activities = collect($activities->items());
    } else {
        $activities = collect($activities);
    }
} else {
    $activities = Activity::where('is_active', 1)
        ->orderBy('name', 'asc')
        ->get();
}

$activities = $activities->unique('id')->values();
$count = $activities->count();
@endphp

@if($count > 0)
<div class="activities-section bg-gray-400 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Activities You May Enjoy</h2>
            <a href="{{ url('/activities') }}" class="text-white hover:text-white text-sm font-medium">View all &rarr;</a>
        </div>

        <div class="relative">
            <!-- Prev / Next controls -->
            <button type="button" aria-label="Previous activity" id="act-prev" class="absolute left-2 top-1/2 -translate-y-1/2 z-20 bg-white/90 hover:bg-white text-gray-700 rounded-full p-2 shadow">
                <svg class="w-4 h-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4l-6 6 6 6"/></svg>
            </button>

            <button type="button" aria-label="Next activity" id="act-next" class="absolute right-2 top-1/2 -translate-y-1/2 z-20 bg-white/90 hover:bg-white text-gray-700 rounded-full p-2 shadow">
                <svg class="w-4 h-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 4l6 6-6 6"/></svg>
            </button>

            <!-- Scroller container -->
            <div id="activities-container" class="overflow-hidden">
                <div id="activities-track" class="flex space-x-6 track will-change-transform">
                    @foreach ($activities as $activity)
                        @php
                            // Resolve image source robustly
                            $raw = $activity->featured_image
                                ?? $activity->image
                                ?? $activity->image_url
                                ?? $activity->image_path
                                ?? $activity->photo
                                ?? null;

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
                                        $imgSrc = asset('images/default-activity.jpg');
                                    }
                                }
                            } else {
                                $imgSrc = asset('images/default-activity.jpg');
                            }

                            // Card link
                            $link = null;
                            if (Route::has('activities.show') && !empty($activity->slug)) {
                                $link = route('activities.show', $activity->slug);
                            } else {
                                $link = url('/activities/' . ($activity->slug ?? $activity->id));
                            }

                            // Short description fallback
                            $desc = $activity->short_description
                                    ?? $activity->overview
                                    ?? $activity->excerpt
                                    ?? $activity->description
                                    ?? '';
                        @endphp

                        <a href="{{ $link }}" class="activity-card flex-shrink-0 w-64 bg-white rounded-lg overflow-hidden border hover:shadow-md transition" aria-label="{{ $activity->name ?? 'Activity' }}">
                            <div class="h-40 bg-gray-200 overflow-hidden">
                                <img src="{{ $imgSrc }}" alt="{{ $activity->name ?? 'Activity' }}" class="w-full h-full object-cover transform transition duration-500 hover:scale-105" loading="lazy" decoding="async">
                            </div>
                            <div class="p-3">
                                <h3 class="text-sm font-semibold text-gray-800">{{ Str::limit($activity->name ?? 'Activity', 40) }}</h3>

                                @if(!empty($activity->destination?->name))
                                    <p class="text-xs text-gray-500 mt-1">{{ $activity->destination->name }}</p>
                                @endif

                                @if(!empty($desc))
                                    <p class="text-xs text-gray-600 mt-2">{{ Str::limit(strip_tags($desc), 100) }}</p>
                                @else
                                    <p class="text-xs text-gray-500 mt-2">Learn more about this activity and booking options.</p>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Indicators -->
            <div id="act-indicators" class="mt-4 flex items-center justify-center space-x-2" role="tablist" aria-hidden="false">
                @for ($i = 0; $i < $count; $i++)
                    <button class="act-indicator w-2 h-2 rounded-full bg-gray-300 hover:bg-gray-400" data-index="{{ $i }}" aria-label="Show activity {{ $i + 1 }}" role="tab" aria-selected="{{ $i === 0 ? 'true' : 'false' }}"></button>
                @endfor
            </div>
        </div>
    </div>

    <style>
        #activities-container {
            overflow: hidden;
            scroll-behavior: auto; /* must be auto for seamless JS reset to work */
            cursor: grab;
            user-select: none;
            -webkit-user-select: none;
        }
        #activities-container:active {
            cursor: grabbing;
        }
        /* Prevent images/links from interfering with drag */
        #activities-track a {
            -webkit-user-drag: none;
            user-drag: none;
        }
        #activities-track img {
            display: block;
            pointer-events: none; /* stops browser native image-drag ghost on desktop */
        }
        .act-indicator[aria-selected="true"] {
            background-color: #4f46e5;
            width: 10px;
            height: 10px;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const container = document.getElementById('activities-container');
            const track = document.getElementById('activities-track');
            const nextBtn = document.getElementById('act-next');
            const prevBtn = document.getElementById('act-prev');
            const indicatorsWrap = document.getElementById('act-indicators');
            const indicators = indicatorsWrap ? Array.from(indicatorsWrap.children) : [];

            if (!container || !track) return;

            // Capture original items before cloning
            const originalItems = Array.from(track.children);
            const n = originalItems.length;
            if (n === 0) return;

            // Single-item guard: no cloning or animation needed
            if (n === 1) {
                track.classList.add('justify-center');
                if (indicators[0]) {
                    indicators[0].setAttribute('aria-selected', 'true');
                    indicators[0].classList.replace('bg-gray-300', 'bg-indigo-600');
                }
                return;
            }

            // Clone all original items → seamless double-length track
            originalItems.forEach(node => track.appendChild(node.cloneNode(true)));

            let originalWidth = 0;
            let cardAdvance = 0;
            const speed = 0.6; // pixels per frame — increase for faster auto-scroll
            let paused = false;
            let rafId = null;
            let manualPauseTimer = null;
            let initialized = false;

            // ─── Drag state ───────────────────────────────────────────────────────────
            let isDragging = false;
            let dragStartX = 0;
            let dragStartScrollLeft = 0;
            let dragMoved = false; // tracks if pointer actually moved (to block accidental link clicks)

            // ─── Size calculation ─────────────────────────────────────────────────────
            function recalcSizes() {
                setTimeout(() => {
                    originalWidth = track.scrollWidth / 2;
                    const firstCard = track.querySelector('.activity-card');
                    if (firstCard) {
                        const trackStyle = getComputedStyle(track);
                        const gap = parseFloat(trackStyle.gap) || parseFloat(trackStyle.columnGap) || 24;
                        cardAdvance = Math.ceil(firstCard.offsetWidth + gap);
                    } else {
                        cardAdvance = 300;
                    }
                    initialized = true;
                }, 150);
            }

            // ─── Wrap scroll position to [0, originalWidth) ──────────────────────────
            function wrapScroll() {
                if (!originalWidth) return;
                if (container.scrollLeft >= originalWidth) {
                    container.scrollLeft -= originalWidth;
                } else if (container.scrollLeft < 0) {
                    container.scrollLeft += originalWidth;
                }
            }

            // ─── Animation loop ───────────────────────────────────────────────────────
            function step() {
                if (!paused && initialized) {
                    container.scrollLeft += speed;
                    wrapScroll();
                    updateIndicators();
                }
                rafId = requestAnimationFrame(step);
            }

            // ─── Indicator sync ───────────────────────────────────────────────────────
            function updateIndicators() {
                if (!indicators.length || !cardAdvance || !originalWidth) return;
                const pos = ((container.scrollLeft % originalWidth) + originalWidth) % originalWidth;
                const index = Math.round(pos / cardAdvance) % n;
                indicators.forEach((dot, i) => {
                    const selected = i === index;
                    dot.setAttribute('aria-selected', selected ? 'true' : 'false');
                    dot.classList.toggle('bg-gray-300', !selected);
                    dot.classList.toggle('bg-indigo-600', selected);
                });
            }

            // ─── Manual pause helper ──────────────────────────────────────────────────
            function pauseBriefly(duration = 2200) {
                paused = true;
                clearTimeout(manualPauseTimer);
                manualPauseTimer = setTimeout(() => { paused = false; }, duration);
            }

            // ─── Next button ──────────────────────────────────────────────────────────
            nextBtn.addEventListener('click', () => {
                container.style.scrollBehavior = 'smooth';
                container.scrollBy({ left: cardAdvance * 2 });
                setTimeout(() => { container.style.scrollBehavior = 'auto'; }, 400);
                pauseBriefly();
            });

            // ─── Prev button ──────────────────────────────────────────────────────────
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

            // ─── Indicator click ──────────────────────────────────────────────────────
            indicators.forEach((dot) => {
                dot.addEventListener('click', () => {
                    const idx = parseInt(dot.getAttribute('data-index'), 10);
                    const baseLoops = Math.floor(container.scrollLeft / originalWidth);
                    const target = (baseLoops * originalWidth) + (idx * cardAdvance);
                    container.style.scrollBehavior = 'smooth';
                    container.scrollTo({ left: target });
                    setTimeout(() => { container.style.scrollBehavior = 'auto'; }, 400);
                    pauseBriefly();
                });
                dot.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); dot.click(); }
                });
            });

            // ─── MOUSE DRAG (desktop) ─────────────────────────────────────────────────
            container.addEventListener('mousedown', (e) => {
                if (e.button !== 0) return; // left button only
                isDragging = true;
                dragMoved = false;
                dragStartX = e.pageX;
                dragStartScrollLeft = container.scrollLeft;
                container.style.cursor = 'grabbing';
                paused = true;
                e.preventDefault(); // prevent text selection during drag
            });

            window.addEventListener('mousemove', (e) => {
                if (!isDragging) return;
                const delta = dragStartX - e.pageX;
                if (Math.abs(delta) > 3) dragMoved = true; // small threshold to distinguish click vs drag
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

            // Block card link navigation if user actually dragged (not just clicked)
            track.addEventListener('click', (e) => {
                if (dragMoved) {
                    e.preventDefault();
                    dragMoved = false;
                }
            }, true); // capture phase fires before the <a> click

            // ─── TOUCH DRAG (mobile / tablet) ────────────────────────────────────────
            let touchStartX = 0;
            let touchStartScrollLeft = 0;
            let touchMoved = false;

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

            // Block link tap if user swiped instead of tapping
            track.addEventListener('click', (e) => {
                if (touchMoved) {
                    e.preventDefault();
                    touchMoved = false;
                }
            }, true);

            // ─── Hover pause (desktop only — don't interfere with touch) ─────────────
            container.addEventListener('mouseenter', () => { if (!isDragging) paused = true; });
            container.addEventListener('mouseleave', () => { if (!isDragging) paused = false; });
            container.addEventListener('focusin',    () => paused = true);
            container.addEventListener('focusout',   () => paused = false);

            // ─── Recalc on resize + image load ────────────────────────────────────────
            window.addEventListener('resize', recalcSizes);
            track.querySelectorAll('img').forEach(img => {
                if (img.complete) return;
                img.addEventListener('load', recalcSizes);
            });

            // ─── Boot ─────────────────────────────────────────────────────────────────
            recalcSizes();
            rafId = requestAnimationFrame(step);

            // Cleanup on page unload
            window.addEventListener('beforeunload', () => cancelAnimationFrame(rafId));
        });
    </script>
</div>
@endif