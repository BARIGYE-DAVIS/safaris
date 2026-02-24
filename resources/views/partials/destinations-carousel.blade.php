@php
    use Illuminate\Support\Str;
    $destinations = $destinations ?? collect();
    $count = $destinations->count();
@endphp

@if($count > 0)
<div class="destinations-section py-8 bg-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-2xl font-bold text-white text-center"> East African Popular Destinations</h2>
            <a href="{{ url('/destinations') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">View all &rarr;</a>
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

                                {{-- Description / excerpt --}}
                                @php
                                    $desc = $destination->short_description
                                            ?? $destination->excerpt
                                            ?? $destination->summary
                                            ?? $destination->overview
                                            ?? $destination->description
                                            ?? '';
                                @endphp

                                @if(!empty($desc))
                                    <p class="text-xs text-gray-600 mt-2">{{ \Illuminate\Support\Str::limit(strip_tags($desc), 100) }}</p>
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
        /* small visual tweaks */
        #destinations-container { scroll-behavior: smooth; }
        .destination-card img { display: block; }
        .dest-indicator[aria-selected="true"] { background-color: #4f46e5; width: 10px; height: 10px; }
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

            // original items (before cloning)
            const originalItems = Array.from(track.children);
            const n = originalItems.length;
            if (n === 0) return;

            // Clone original items so we can scroll infinitely
            originalItems.forEach(node => track.appendChild(node.cloneNode(true)));

            // variables that depend on layout - recalculated on load & resize
            let originalWidth = 0;
            let cardAdvance = 0;
            let speed = 0.6; // pixels per frame; tweak as needed
            let paused = false;
            let rafId;
            let manualPauseTimer;

            function recalcSizes() {
                // Wait one frame for layout to stabilize
                requestAnimationFrame(() => {
                    originalWidth = track.scrollWidth / 2; // because we duplicated
                    const firstCard = track.querySelector('.destination-card');
                    if (firstCard) {
                        const style = getComputedStyle(firstCard);
                        const marginRight = parseFloat(style.marginRight) || 0;
                        cardAdvance = Math.ceil(firstCard.offsetWidth + marginRight);
                    } else {
                        cardAdvance = 300;
                    }
                });
            }

            // continuous scroll using requestAnimationFrame
            function step() {
                if (!paused) {
                    container.scrollLeft += speed;
                    if (container.scrollLeft >= originalWidth) {
                        // reset to start seamlessly
                        container.scrollLeft -= originalWidth;
                    }
                    updateIndicators();
                }
                rafId = requestAnimationFrame(step);
            }

            function updateIndicators() {
                if (!indicators.length || !cardAdvance) return;
                const pos = ((container.scrollLeft % originalWidth) + originalWidth) % originalWidth;
                const index = Math.floor(pos / cardAdvance) % n;
                indicators.forEach((dot, i) => {
                    const selected = i === index;
                    dot.setAttribute('aria-selected', selected ? 'true' : 'false');
                    dot.classList.toggle('bg-gray-300', !selected);
                    dot.classList.toggle('bg-indigo-600', selected);
                });
            }

            function pauseBriefly(duration = 2000) {
                paused = true;
                clearTimeout(manualPauseTimer);
                manualPauseTimer = setTimeout(() => { paused = false; }, duration);
            }

            // Next / Prev actions
            nextBtn.addEventListener('click', () => {
                const advance = cardAdvance * 2;
                container.scrollBy({ left: advance, behavior: 'smooth' });
                pauseBriefly(2200);
            });

            prevBtn.addEventListener('click', () => {
                const advance = cardAdvance * 2;
                container.scrollBy({ left: -advance, behavior: 'smooth' });
                pauseBriefly(2200);
            });

            // Indicator click: jump to that destination (within current loop)
            indicators.forEach((dot) => {
                dot.addEventListener('click', (e) => {
                    const idx = parseInt(dot.getAttribute('data-index'), 10);
                    // compute nearest loop base to avoid big jump
                    const baseLoops = Math.floor(container.scrollLeft / originalWidth);
                    const target = (baseLoops * originalWidth) + (idx * cardAdvance);
                    container.scrollTo({ left: target, behavior: 'smooth' });
                    pauseBriefly(2200);
                });
                // keyboard support
                dot.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        dot.click();
                    }
                });
            });

            // Pause on hover/focus
            container.addEventListener('mouseenter', () => paused = true);
            container.addEventListener('mouseleave', () => paused = false);
            container.addEventListener('focusin', () => paused = true);
            container.addEventListener('focusout', () => paused = false);

            // Recalculate sizes on resize and when images load
            window.addEventListener('resize', recalcSizes);
            // when images inside track load, recalc
            track.querySelectorAll('img').forEach(img => {
                img.addEventListener('load', recalcSizes);
            });

            // initial calc and start
            recalcSizes();
            rafId = requestAnimationFrame(step);

            // Cleanup on page unload
            window.addEventListener('beforeunload', () => cancelAnimationFrame(rafId));
        });
    </script>
</div>
@endif