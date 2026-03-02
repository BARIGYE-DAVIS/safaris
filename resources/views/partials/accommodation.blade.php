@php
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\Accommodation;

/**
 * Accommodations carousel partial
 * - Loads all active accommodations if $accommodations is not provided to the view.
 * - Expects Accommodation model with fields: name, slug, featured_image, type, category,
 *   price_from, price_to, currency, short_description, full_description, is_active,
 *   is_featured, location, sort_order + relations: country, destination, images.
 * - Resolves featured image robustly and falls back to asset('images/default-accommodation.jpg').
 * - Seamless infinite auto-looping carousel with prev/next and indicators.
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
@endphp

@if($count > 0)
<div class="accommodations-section bg-gray-700 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-white">Where To Stay while on East Africa Safaris</h2>
            <a href="{{ url('/accommodations') }}" class="text-indigo-400 hover:text-indigo-300 text-sm font-medium">View all &rarr;</a>
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

                    @foreach ($accommodations as $accommodation)
                        @php
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

                            // ── Short description fallback ────────────────────────────
                            $desc = $accommodation->short_description
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
                        @endphp

                        <a href="{{ $link }}"
                           class="accom-card flex-shrink-0 w-64 bg-white rounded-lg overflow-hidden border hover:shadow-lg transition group"
                           aria-label="{{ $accommodation->name ?? 'Accommodation' }}">

                            <!-- Image -->
                            <div class="h-40 bg-gray-200 overflow-hidden relative">
                                <img src="{{ $imgSrc }}"
                                     alt="{{ $accommodation->name ?? 'Accommodation' }}"
                                     class="w-full h-full object-cover transform transition duration-500 group-hover:scale-105"
                                     loading="lazy"
                                     decoding="async">

                                @if($accommodation->is_featured)
                                    <span class="absolute top-2 left-2 bg-yellow-400 text-yellow-900 text-xs font-bold px-2 py-0.5 rounded">
                                        Featured
                                    </span>
                                @endif

                                @if($badge)
                                    <span class="absolute top-2 right-2 bg-black/60 text-white text-xs px-2 py-0.5 rounded capitalize">
                                        {{ $badge }}
                                    </span>
                                @endif
                            </div>

                            <!-- Card body -->
                            <div class="p-3">
                                <h3 class="text-sm font-semibold text-gray-800 leading-snug">
                                    {{ Str::limit($accommodation->name ?? 'Accommodation', 40) }}
                                </h3>

                                {{-- Location line: destination or country --}}
                                @if(!empty($accommodation->destination?->name))
                                    <p class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                                        <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        {{ $accommodation->destination->name }}
                                    </p>
                                @elseif(!empty($accommodation->country?->name))
                                    <p class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                                        <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        {{ $accommodation->country->name }}
                                    </p>
                                @elseif(!empty($accommodation->location))
                                    <p class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                                        <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        {{ Str::limit($accommodation->location, 35) }}
                                    </p>
                                @endif

                                {{-- Description --}}
                                @if(!empty($desc))
                                    <p class="text-xs text-gray-600 mt-2 leading-relaxed">
                                        {{ Str::limit(strip_tags($desc), 90) }}
                                    </p>
                                @else
                                    <p class="text-xs text-gray-500 mt-2">Discover comfort and style at this property.</p>
                                @endif

                                {{-- Price --}}
                                @if($priceLabel)
                                    <p class="text-xs font-semibold text-green-700 mt-2">
                                        From {{ $priceLabel }}
                                    </p>
                                @endif
                            </div>
                        </a>
                    @endforeach

                </div>
            </div>

            <!-- Indicators -->
            <div id="accom-indicators" class="mt-4 flex items-center justify-center space-x-2" role="tablist">
                @for ($i = 0; $i < $count; $i++)
                    <button
                        class="accom-indicator w-2 h-2 rounded-full bg-gray-400 hover:bg-gray-300 transition"
                        data-index="{{ $i }}"
                        aria-label="Show accommodation {{ $i + 1 }}"
                        role="tab"
                        aria-selected="{{ $i === 0 ? 'true' : 'false' }}">
                    </button>
                @endfor
            </div>

        </div>
    </div>

    <style>
        #accommodations-container {
            overflow: hidden;
            scroll-behavior: auto; /* must be auto — seamless JS reset won't work with smooth */
        }
        .accom-card img { display: block; }
        .accom-indicator[aria-selected="true"] {
            background-color: #4f46e5;
            width: 10px;
            height: 10px;
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

            // Capture original items BEFORE cloning
            const originalItems = Array.from(track.children);
            const n = originalItems.length;
            if (n === 0) return;

            // Single-item guard
            if (n === 1) {
                track.classList.add('justify-center');
                if (indicators[0]) {
                    indicators[0].setAttribute('aria-selected', 'true');
                    indicators[0].classList.replace('bg-gray-400', 'bg-indigo-600');
                }
                return;
            }

            // Clone all items → creates a seamless double-length track
            originalItems.forEach(node => track.appendChild(node.cloneNode(true)));

            let originalWidth    = 0;
            let cardAdvance      = 0;
            const speed          = 0.6; // px per frame — increase to scroll faster
            let paused           = false;
            let rafId            = null;
            let manualPauseTimer = null;
            let initialized      = false;

            // ── Size calculation ──────────────────────────────────────────────────────
            function recalcSizes() {
                // setTimeout lets the browser fully paint cloned nodes before we measure
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

            // ── Animation loop ────────────────────────────────────────────────────────
            function step() {
                if (!paused && initialized) {
                    container.scrollLeft += speed;

                    // Seamless forward loop — jump back by one full copy
                    if (container.scrollLeft >= originalWidth) {
                        container.scrollLeft = container.scrollLeft - originalWidth;
                    }

                    // Seamless backward loop — jump forward when scrollLeft goes negative
                    if (container.scrollLeft < 0) {
                        container.scrollLeft = originalWidth + container.scrollLeft;
                    }

                    updateIndicators();
                }
                rafId = requestAnimationFrame(step);
            }

            // ── Indicator sync ────────────────────────────────────────────────────────
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

            // ── Manual pause helper ───────────────────────────────────────────────────
            function pauseBriefly(duration = 2200) {
                paused = true;
                clearTimeout(manualPauseTimer);
                manualPauseTimer = setTimeout(() => { paused = false; }, duration);
            }

            // ── Next button ───────────────────────────────────────────────────────────
            nextBtn.addEventListener('click', () => {
                container.style.scrollBehavior = 'smooth';
                container.scrollBy({ left: cardAdvance * 2 });
                setTimeout(() => { container.style.scrollBehavior = 'auto'; }, 400);
                pauseBriefly();
            });

            // ── Prev button ───────────────────────────────────────────────────────────
            prevBtn.addEventListener('click', () => {
                container.style.scrollBehavior = 'smooth';
                let newLeft = container.scrollLeft - (cardAdvance * 2);
                // Wrap seamlessly if we'd scroll past position 0
                if (newLeft < 0) {
                    container.scrollLeft = originalWidth + newLeft + (cardAdvance * 2);
                    newLeft = container.scrollLeft - (cardAdvance * 2);
                }
                container.scrollBy({ left: -(cardAdvance * 2) });
                setTimeout(() => { container.style.scrollBehavior = 'auto'; }, 400);
                pauseBriefly();
            });

            // ── Indicator click ───────────────────────────────────────────────────────
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

            // ── Hover / focus pause ───────────────────────────────────────────────────
            container.addEventListener('mouseenter', () => paused = true);
            container.addEventListener('mouseleave', () => paused = false);
            container.addEventListener('focusin',    () => paused = true);
            container.addEventListener('focusout',   () => paused = false);

            // ── Recalc on resize + image load ─────────────────────────────────────────
            window.addEventListener('resize', recalcSizes);
            track.querySelectorAll('img').forEach(img => {
                if (img.complete) return; // already cached, skip
                img.addEventListener('load', recalcSizes);
            });

            // ── Boot ──────────────────────────────────────────────────────────────────
            recalcSizes();
            rafId = requestAnimationFrame(step);

            // Cleanup
            window.addEventListener('beforeunload', () => cancelAnimationFrame(rafId));
        });
    </script>
</div>
@endif