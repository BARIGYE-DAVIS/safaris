@php
    $limit             = $limit             ?? 10;
    $showExploreButton = $showExploreButton ?? true;
    $heading           = $heading           ?? 'Featured Safari Tours';
    $subheading        = $subheading        ?? 'Discover our most popular safari experiences';
    $totalTours        = $tours->count();
@endphp

<section class="py-12 bg-gray-200 overflow-hidden rounded-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        @if($totalTours > 0)

        <!-- Carousel Wrapper -->
        <div class="relative px-8 sm:px-12">

            <!-- Prev Button -->
            <button id="tourPrevBtn" aria-label="Previous tour"
                class="absolute left-0 top-1/2 -translate-y-8 z-20 bg-white/90 hover:bg-green-600 hover:text-white text-gray-800 rounded-full p-3 sm:p-4 shadow-xl transition-all duration-300 transform hover:scale-110 active:scale-95 backdrop-blur-sm">
                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>

            <!-- Next Button -->
            <button id="tourNextBtn" aria-label="Next tour"
                class="absolute right-0 top-1/2 -translate-y-8 z-20 bg-white/90 hover:bg-green-600 hover:text-white text-gray-800 rounded-full p-3 sm:p-4 shadow-xl transition-all duration-300 transform hover:scale-110 active:scale-95 backdrop-blur-sm">
                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                </svg>
            </button>

            <!-- Overflow Container -->
            <div id="tourCarouselContainer" class="overflow-hidden">
                <div id="tourCarouselTrack" class="flex gap-5 will-change-transform">

                    @foreach($tours as $tour)
                    {{--
                        KEY FIX: mobile gets w-full (100% of container = 1 card, no peeking).
                        sm (≥640px) → 2 cards. lg (≥1024px) → 3 cards.
                        JS recalcSizes() reads the actual rendered width so the math always matches.
                    --}}
                    <div class="tour-slide flex-shrink-0
                                w-full
                                sm:w-[calc(50%-10px)]
                                lg:w-[calc(33.333%-14px)]">

                        <div class="bg-white rounded-2xl shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 group h-full transform hover:-translate-y-1">

                            <!-- Image -->
                            <div class="relative h-52 sm:h-60 overflow-hidden">
                                @if($tour->featured_image)
                                    <img src="{{ asset('storage/' . $tour->featured_image) }}"
                                         alt="{{ $tour->title }}"
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                                         loading="lazy" decoding="async">
                                @elseif($tour->images && $tour->images->first())
                                    <img src="{{ asset('storage/' . $tour->images->first()->image_path) }}"
                                         alt="{{ $tour->title }}"
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                                         loading="lazy" decoding="async">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-green-400 to-blue-500 flex items-center justify-center">
                                        <svg class="w-14 h-14 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif

                                <!-- Category badge -->
                                <div class="absolute top-3 left-3">
                                    <span class="bg-green-600 text-white px-3 py-1 rounded-full text-xs font-semibold capitalize shadow-lg">
                                        {{ $tour->category ?? 'Safari' }}
                                    </span>
                                </div>

                                <!-- Duration badge -->
                                <div class="absolute top-3 right-3">
                                    <span class="bg-white/95 backdrop-blur text-gray-800 px-3 py-1 rounded-full text-xs font-semibold shadow-lg">
                                        {{ $tour->itineraries->count() ?: 'Multi' }} {{ $tour->itineraries->count() == 1 ? 'Day' : 'Days' }}
                                    </span>
                                </div>

                                <!-- Type badge -->
                                @if($tour->type)
                                <div class="absolute bottom-3 left-3">
                                    <span class="bg-blue-600 text-white px-3 py-1 rounded-full text-xs font-semibold capitalize shadow-lg">
                                        {{ $tour->type }}
                                    </span>
                                </div>
                                @endif
                            </div>

                            <!-- Card Content -->
                            <div class="p-4 sm:p-5 flex flex-col" style="min-height:200px;">

                                <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-green-600 transition-colors">
                                    {{ $tour->title }}
                                </h3>

                                <p class="text-gray-500 text-sm mb-3 line-clamp-2 flex-grow">
                                    {{ Str::limit(strip_tags($tour->description), 110) }}
                                </p>

                                <!-- Destination -->
                                @if($tour->destinations)
                                <div class="flex items-center mb-3 text-xs text-gray-500">
                                    <svg class="w-3.5 h-3.5 text-green-600 mr-1.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <span class="line-clamp-1">
                                        {{ is_array($tour->destinations) ? implode(', ', $tour->destinations_array) : ($tour->destinations ?: 'East Africa') }}
                                    </span>
                                </div>
                                @endif

                                <!-- Price + Rating -->
                                <div class="flex items-center justify-between mb-4">
                                    <div>
                                        @if($tour->price && $tour->price > 0)
                                            <div class="flex items-baseline gap-1">
                                                <span class="text-xl font-bold text-green-600">{{ $tour->formatted_price }}</span>
                                                <span class="text-gray-400 text-xs">/ person</span>
                                            </div>
                                            @if($tour->prices && $tour->prices->count() > 1)
                                                <p class="text-xs text-gray-400">Starting from</p>
                                            @endif
                                        @elseif($tour->prices && $tour->prices->count() > 0)
                                            @php $minPrice = $tour->prices->min('price'); @endphp
                                            <div class="flex items-baseline gap-1">
                                                <span class="text-xl font-bold text-green-600">${{ number_format($minPrice) }}</span>
                                                <span class="text-gray-400 text-xs">/ person</span>
                                            </div>
                                            <p class="text-xs text-gray-400">Starting from</p>
                                        @else
                                            <span class="text-green-600 font-semibold text-sm">Contact for Pricing</span>
                                        @endif
                                    </div>

                                    <!-- Stars -->
                                    <div class="flex items-center gap-1">
                                        <div class="flex text-yellow-400">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            @endfor
                                        </div>
                                        <span class="text-xs text-gray-600 font-medium">{{ $tour->average_rating }}</span>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex gap-2 mt-auto">
                                    <a href="{{ route('tours.show', $tour->slug) }}"
                                       class="flex-1 bg-green-600 hover:bg-green-700 text-white text-center py-2.5 px-3 rounded-lg text-sm font-semibold transition-all duration-300">
                                        View Details
                                    </a>
                                    <a href="{{ route('booking.create') }}"
                                       class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2.5 rounded-lg font-semibold transition-all duration-300 flex items-center justify-center"
                                       aria-label="Book this tour">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </a>
                                </div>

                            </div>
                        </div>
                    </div>
                    @endforeach

                </div>
            </div>
        </div>

        <!-- Indicators -->
        <div id="tourIndicators" class="mt-6 flex items-center justify-center gap-2 flex-wrap" role="tablist">
            @for($i = 0; $i < $totalTours; $i++)
                <button
                    class="tour-indicator w-2 h-2 rounded-full bg-gray-400 hover:bg-gray-600 transition-all duration-200"
                    data-index="{{ $i }}"
                    aria-label="Go to tour {{ $i + 1 }}"
                    role="tab"
                    aria-selected="{{ $i === 0 ? 'true' : 'false' }}">
                </button>
            @endfor
        </div>

        <!-- Controls Row -->
        <div class="mt-5 flex flex-col sm:flex-row items-center justify-center gap-4">
            <button id="tourPlayPauseBtn"
                class="bg-green-600 hover:bg-green-700 text-white rounded-full p-3 shadow-lg transition-all duration-300 transform hover:scale-110 active:scale-95"
                aria-label="Pause carousel">
                <svg id="tourPauseIcon" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM7 8a1 1 0 012 0v4a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v4a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <svg id="tourPlayIcon" class="w-5 h-5 hidden" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/>
                </svg>
            </button>
            <div class="inline-flex items-center gap-2 bg-white rounded-full px-4 py-2 shadow-md">
                <span class="text-xs font-medium text-gray-500">Speed:</span>
                <button class="tour-speed-btn px-3 py-1 rounded-full text-xs font-medium transition-all" data-speed="slow">Slow</button>
                <button class="tour-speed-btn px-3 py-1 rounded-full text-xs font-medium transition-all active" data-speed="normal">Normal</button>
                <button class="tour-speed-btn px-3 py-1 rounded-full text-xs font-medium transition-all" data-speed="fast">Fast</button>
            </div>
        </div>

        @else
        <div class="text-center py-16">
            <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3 class="text-xl font-bold text-gray-500 mb-2">No Tours Available</h3>
            <p class="text-gray-400 text-sm">Check back soon for amazing safari adventures!</p>
        </div>
        @endif

        @if($showExploreButton && $totalTours >= $limit)
        <div class="text-center mt-10">
            <a href="{{ route('tours.index') }}"
               class="inline-flex items-center gap-2 px-7 py-3.5 bg-green-600 hover:bg-green-700 text-white font-semibold text-base rounded-lg transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Explore All Tours
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
            </a>
        </div>
        @endif

    </div>
</section>

@push('styles')
<style>
    .line-clamp-1 { display:-webkit-box; -webkit-line-clamp:1; -webkit-box-orient:vertical; overflow:hidden; }
    .line-clamp-2 { display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; }
    .line-clamp-3 { display:-webkit-box; -webkit-line-clamp:3; -webkit-box-orient:vertical; overflow:hidden; }

    #tourCarouselContainer { overflow:hidden; touch-action:pan-y pinch-zoom; }
    #tourCarouselTrack     { will-change:transform; }

    .tour-speed-btn       { color:#6b7280; background:transparent; }
    .tour-speed-btn.active { background:#16a34a; color:#fff; }
    .tour-speed-btn:hover  { background:#dcfce7; color:#16a34a; }
    .tour-speed-btn.active:hover { background:#15803d; color:#fff; }

    .tour-indicator[aria-selected="true"] {
        background-color: #16a34a;
        width:  10px;
        height: 10px;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const container      = document.getElementById('tourCarouselContainer');
    const track          = document.getElementById('tourCarouselTrack');
    const prevBtn        = document.getElementById('tourPrevBtn');
    const nextBtn        = document.getElementById('tourNextBtn');
    const playPauseBtn   = document.getElementById('tourPlayPauseBtn');
    const playIcon       = document.getElementById('tourPlayIcon');
    const pauseIcon      = document.getElementById('tourPauseIcon');
    const speedBtns      = document.querySelectorAll('.tour-speed-btn');
    const indicatorsWrap = document.getElementById('tourIndicators');
    const indicators     = indicatorsWrap ? Array.from(indicatorsWrap.children) : [];
    const n              = {{ $totalTours }};

    if (!track || !container || n === 0) return;

    // Clone slides x2 for seamless infinite loop
    const originalSlides = Array.from(track.children);
    originalSlides.forEach(s => track.appendChild(s.cloneNode(true)));
    originalSlides.forEach(s => track.appendChild(s.cloneNode(true)));

    const speeds = { slow: 0.25, normal: 0.5, fast: 1.0 };
    let speed            = speeds.normal;
    let currentPos       = 0;
    let isPlaying        = true;
    let rafId            = null;
    let initialized      = false;
    let originalWidth    = 0;
    let cardAdvance      = 0;
    let manualPauseTimer = null;

    // ── recalcSizes ───────────────────────────────────────────────────────────
    // Reads the ACTUAL rendered card width from the DOM so the JS math always
    // matches whatever CSS width the card has (w-full on mobile, 50% on sm,
    // 33% on lg). No hardcoded vw values needed.
    function recalcSizes() {
        setTimeout(function () {
            const firstSlide = track.querySelector('.tour-slide');
            if (!firstSlide) return;

            const gap     = 20; // gap-5 = 20px
            const cardW   = firstSlide.getBoundingClientRect().width;

            cardAdvance   = cardW + gap;
            originalWidth = cardAdvance * n;

            // Clamp so we don't jump after resize
            currentPos    = currentPos % originalWidth;
            initialized   = true;
        }, 150);
    }

    // ── Animation loop ────────────────────────────────────────────────────────
    function step() {
        if (isPlaying && initialized) {
            currentPos += speed;
            if (currentPos >= originalWidth) currentPos -= originalWidth;
            if (currentPos < 0)             currentPos  = originalWidth + currentPos;
            track.style.transform = 'translateX(-' + currentPos + 'px)';
            updateIndicators();
        }
        rafId = requestAnimationFrame(step);
    }

    // ── Indicators ────────────────────────────────────────────────────────────
    function updateIndicators() {
        if (!indicators.length || !cardAdvance) return;
        const pos   = ((currentPos % originalWidth) + originalWidth) % originalWidth;
        const index = Math.round(pos / cardAdvance) % n;
        indicators.forEach(function (dot, i) {
            const sel = i === index;
            dot.setAttribute('aria-selected', sel ? 'true' : 'false');
            dot.classList.toggle('bg-gray-400',  !sel);
            dot.classList.toggle('bg-green-600',  sel);
        });
    }

    // ── Play / Pause ──────────────────────────────────────────────────────────
    function setPlaying(playing) {
        isPlaying = playing;
        playIcon.classList.toggle('hidden',   isPlaying);
        pauseIcon.classList.toggle('hidden', !isPlaying);
        playPauseBtn.setAttribute('aria-label', isPlaying ? 'Pause carousel' : 'Play carousel');
    }

    function pauseBriefly(ms) {
        ms = ms || 2200;
        var was = isPlaying;
        setPlaying(false);
        clearTimeout(manualPauseTimer);
        if (was) manualPauseTimer = setTimeout(function () { setPlaying(true); }, ms);
    }

    playPauseBtn.addEventListener('click', function () { setPlaying(!isPlaying); });

    // ── Next / Prev ───────────────────────────────────────────────────────────
    nextBtn.addEventListener('click', function () {
        currentPos += cardAdvance;
        if (currentPos >= originalWidth) currentPos -= originalWidth;
        track.style.transition = 'transform 0.45s ease-out';
        track.style.transform  = 'translateX(-' + currentPos + 'px)';
        setTimeout(function () { track.style.transition = 'none'; }, 450);
        pauseBriefly();
    });

    prevBtn.addEventListener('click', function () {
        currentPos -= cardAdvance;
        if (currentPos < 0) currentPos = originalWidth + currentPos;
        track.style.transition = 'transform 0.45s ease-out';
        track.style.transform  = 'translateX(-' + currentPos + 'px)';
        setTimeout(function () { track.style.transition = 'none'; }, 450);
        pauseBriefly();
    });

    // ── Indicator click ───────────────────────────────────────────────────────
    indicators.forEach(function (dot) {
        dot.addEventListener('click', function () {
            var idx  = parseInt(dot.getAttribute('data-index'), 10);
            currentPos = idx * cardAdvance;
            track.style.transition = 'transform 0.45s ease-out';
            track.style.transform  = 'translateX(-' + currentPos + 'px)';
            setTimeout(function () { track.style.transition = 'none'; }, 450);
            pauseBriefly();
        });
        dot.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); dot.click(); }
        });
    });

    // ── Speed ─────────────────────────────────────────────────────────────────
    speedBtns.forEach(function (btn) {
        btn.addEventListener('click', function () {
            speed = speeds[btn.dataset.speed] || speeds.normal;
            speedBtns.forEach(function (b) { b.classList.toggle('active', b === btn); });
        });
    });

    // ── Hover pause ───────────────────────────────────────────────────────────
    container.addEventListener('mouseenter', function () { if (isPlaying) setPlaying(false); });
    container.addEventListener('mouseleave', function () { setPlaying(true); });
    container.addEventListener('focusin',    function () { if (isPlaying) setPlaying(false); });
    container.addEventListener('focusout',   function () { setPlaying(true); });

    // ── Touch / swipe ─────────────────────────────────────────────────────────
    var touchStartX = 0, touchEndX = 0, touchStartTime = 0;

    container.addEventListener('touchstart', function (e) {
        touchStartX    = e.touches[0].clientX;
        touchStartTime = Date.now();
        setPlaying(false);
    }, { passive: true });

    container.addEventListener('touchmove', function (e) {
        touchEndX = e.touches[0].clientX;
    }, { passive: true });

    container.addEventListener('touchend', function () {
        var diff = touchStartX - touchEndX;
        if (Date.now() - touchStartTime < 300) {
            if (diff > 50)       nextBtn.click();
            else if (diff < -50) prevBtn.click();
        }
        setPlaying(true);
    });

    // ── Visibility ────────────────────────────────────────────────────────────
    document.addEventListener('visibilitychange', function () {
        setPlaying(!document.hidden);
    });

    // ── Resize ────────────────────────────────────────────────────────────────
    var resizeTimer;
    window.addEventListener('resize', function () {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(recalcSizes, 250);
    });

    track.querySelectorAll('img').forEach(function (img) {
        if (!img.complete) img.addEventListener('load', recalcSizes);
    });

    // ── Boot ──────────────────────────────────────────────────────────────────
    recalcSizes();
    rafId = requestAnimationFrame(step);
    window.addEventListener('beforeunload', function () { cancelAnimationFrame(rafId); });
});
</script>
@endpush