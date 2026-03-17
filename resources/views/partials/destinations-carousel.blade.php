@php
    use Illuminate\Support\Str;
    $destinations = $destinations ?? collect();
    $count = $destinations->count();
@endphp

@if($count > 0)
<div class="destinations-section py-8 bg-gray-600">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-2xl font-bold text-white text-center"> East African Popular Destinations</h2>
            <a href="{{ url('/destinations') }}" class="text-white hover:text-indigo-800 text-sm font-medium">View all &rarr;</a>
        </div>

        <div class="relative">
            <!-- Prev / Next controls -->
            <button type="button" aria-label="Previous" id="dest-prev" class="absolute left-2 top-1/2 -translate-y-1/2 z-20 bg-gray-800 hover:bg-white text-gray-700 rounded-full p-2 shadow">
                <i class="fas fa-chevron-left text-sm hover:scale-110 transition-transform text-white"></i>
            </button>

            <button type="button" aria-label="Next" id="dest-next" class="absolute right-2 top-1/2 -translate-y-1/2 z-20 bg-gray-800 hover:bg-white text-gray-700 rounded-full p-2 shadow">
                <i class="fas fa-chevron-right text-sm hover:scale-110 transition-transform text-white"></i>
            </button>

            <!-- Scroller container -->
            <div id="destinations-container" class="overflow-hidden">
                <div id="destinations-track" class="flex space-x-6 track will-change-transform">
                    @foreach ($destinations as $destination)
                        <a href="{{ url('/destinations/' . ($destination->slug ?? $destination->id)) }}" class="destination-card flex-shrink-0 w-64 bg-white rounded-lg overflow-hidden border hover:shadow-md transition" aria-label="{{ $destination->name ?? 'Destination' }}">
                            <div class="h-40 bg-gray-200">
                                <img src="{{ $destination->featured_image_url ?? $destination->image_url ?? asset('images/default-destination.jpg') }}"
                                     alt="{{ $destination->name ?? 'Destination' }}"
                                     class="w-full h-full object-cover">
                            </div>
                            <div class="p-3">
                                <h3 class="text-sm font-semibold text-gray-800">{{ \Illuminate\Support\Str::limit($destination->name ?? 'Destination', 40) }}</h3>

                                @if(!empty($destination->country?->name))
                                    <p class="text-xs text-gray-500 mt-1">{{ $destination->country->name }}</p>
                                @endif

                                @php
                                    $desc = $destination->short_description
                                            ?? $destination->excerpt
                                            ?? $destination->summary
                                            ?? $destination->overview
                                            ?? $destination->description
                                            ?? '';
                                @endphp

                                @if(!empty($desc))
                                    <p class="text-xs text-gray-700 mt-2">{{ \Illuminate\Support\Str::limit(strip_tags($desc), 100) }}</p>
                                @else
                                    <p class="text-xs text-gray-500 mt-2">Explore this destination's highlights, wildlife, and recommended tours.</p>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Indicators -->
            <div id="dest-indicators" class="mt-4 flex items-center justify-center space-x-2" aria-hidden="false" role="tablist">
                @for ($i = 0; $i < $count; $i++)
                    <button class="dest-indicator w-2 h-2 rounded-full bg-gray-300 hover:bg-gray-400" data-index="{{ $i }}" aria-label="Show destination {{ $i + 1 }}" role="tab" aria-selected="{{ $i === 0 ? 'true' : 'false' }}"></button>
                @endfor
            </div>
        </div>
    </div>

    <style>
        #destinations-container {
            overflow: hidden;
            scroll-behavior: auto; /* must be auto, not smooth, for JS seamless reset */
            cursor: grab;
            user-select: none;       /* prevent text selection while dragging */
            -webkit-user-select: none;
        }
        #destinations-container:active {
            cursor: grabbing;
        }
        /* Prevent images/links from interfering with drag */
        #destinations-track a {
            -webkit-user-drag: none;
            user-drag: none;
        }
        #destinations-track img {
            display: block;
            pointer-events: none; /* stops image ghost-drag on desktop */
        }
        .dest-indicator[aria-selected="true"] {
            background-color: #4f46e5;
            width: 10px;
            height: 10px;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const container = document.getElementById('destinations-container');
            const track = document.getElementById('destinations-track');
            const nextBtn = document.getElementById('dest-next');
            const prevBtn = document.getElementById('dest-prev');
            const indicatorsWrap = document.getElementById('dest-indicators');
            const indicators = indicatorsWrap ? Array.from(indicatorsWrap.children) : [];

            if (!container || !track) return;

            // Capture original items before cloning
            const originalItems = Array.from(track.children);
            const n = originalItems.length;
            if (n === 0) return;

            // Clone all original items and append — creates a seamless double-length track
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
            let dragMoved = false; // tracks if the pointer actually moved (to block link clicks)

            // ─── Size calculation ─────────────────────────────────────────────────────
            function recalcSizes() {
                setTimeout(() => {
                    originalWidth = track.scrollWidth / 2;
                    const firstCard = track.querySelector('.destination-card');
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
                // Only handle left mouse button
                if (e.button !== 0) return;
                isDragging = true;
                dragMoved = false;
                dragStartX = e.pageX;
                dragStartScrollLeft = container.scrollLeft;
                container.style.cursor = 'grabbing';
                paused = true;
                e.preventDefault(); // prevents text selection
            });

            window.addEventListener('mousemove', (e) => {
                if (!isDragging) return;
                const delta = dragStartX - e.pageX;
                if (Math.abs(delta) > 3) dragMoved = true; // threshold so small jitter isn't a drag
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

            // Block card link navigation if user actually dragged
            track.addEventListener('click', (e) => {
                if (dragMoved) {
                    e.preventDefault();
                    dragMoved = false;
                }
            }, true); // capture phase so it fires before the <a> click

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
                // Block link tap if user swiped
                if (touchMoved) {
                    touchMoved = false;
                }
            });

            // Block link click after a touch-swipe
            track.addEventListener('click', (e) => {
                if (touchMoved) {
                    e.preventDefault();
                    touchMoved = false;
                }
            }, true);

            // ─── Hover pause (desktop only — don't pause on touch hover) ─────────────
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

            // Cleanup
            window.addEventListener('beforeunload', () => cancelAnimationFrame(rafId));
        });
    </script>
</div>
@endif