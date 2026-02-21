@php
    // Default values if not passed
    $limit = $limit ?? 10;
    $showExploreButton = $showExploreButton ?? true;
    $heading = $heading ?? 'Featured Safari Tours';
    $subheading = $subheading ?? 'Discover our most popular safari experiences';
@endphp

<section class="py-16 bg-gray-50 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        

        @if($tours->count() > 0)
        <!-- Carousel Container -->
        <div class="relative px-12">
            <!-- Carousel Wrapper -->
            <div class="overflow-hidden" id="tourCarousel">
                <div class="carousel-track flex gap-6" id="carouselTrack">
                    @foreach($tours as $index => $tour)
                    <div class="carousel-slide flex-shrink-0 w-full sm:w-[calc(50%-12px)] lg:w-[calc(33.333%-16px)]">
                        <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 group h-full transform hover:-translate-y-2">
                            <!-- Tour Image -->
                            <div class="relative h-64 overflow-hidden">
                                @if($tour->featured_image)
                                    <img src="{{ asset('storage/' . $tour->featured_image) }}" 
                                         alt="{{ $tour->title }}" 
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                @elseif($tour->images && $tour->images->first())
                                    <img src="{{ asset('storage/' . $tour->images->first()->image_path) }}" 
                                         alt="{{ $tour->title }}" 
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-green-400 to-blue-500 flex items-center justify-center">
                                        <svg class="w-16 h-16 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                                
                                <!-- Category Badge -->
                                <div class="absolute top-3 left-3">
                                    <span class="bg-green-600 text-white px-3 py-1 rounded-full text-sm font-semibold capitalize shadow-lg">
                                        {{ $tour->category ?? 'Safari' }}
                                    </span>
                                </div>
                                
                                <!-- Duration Badge -->
                                <div class="absolute top-3 right-3">
                                    <span class="bg-white/95 backdrop-blur text-gray-800 px-3 py-1 rounded-full text-sm font-semibold shadow-lg">
                                     {{ $tour->itineraries->count() ?: 'Multi' }} {{ $tour->itineraries->count() == 1 ? 'Day' : 'Days' }}
                                    </span>
                                </div>

                                <!-- Type Badge -->
                                @if($tour->type)
                                <div class="absolute bottom-3 left-3">
                                    <span class="bg-blue-600 text-white px-3 py-1 rounded-full text-xs font-semibold capitalize shadow-lg">
                                        {{ $tour->type}}
                                    </span>
                                </div>
                                @endif
                            </div>

                            <!-- Tour Content -->
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2 group-hover:text-green-600 transition-colors">
                                    {{ $tour->title }}
                                </h3>
                                
                                <p class="text-gray-600 mb-4 text-base line-clamp-3">
                                    {{ Str::limit($tour->description, 120) }}
                                </p>

                                <!-- Destinations -->
                                @if($tour->destinations)
                                <div class="flex items-center mb-4 text-sm text-gray-500">
                                    <svg class="w-4 h-4 text-green-600 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span class="line-clamp-1">{{ is_array($tour->destinations) ? implode(', ', $tour->destinations_array) : ($tour->destinations ?: 'East Africa') }}</span>
                                </div>
                                @endif

                                <!-- Pricing -->
                                <div class="flex items-center justify-between mb-6">
                                    <div>
                                        @if($tour->price && $tour->price > 0)
                                            <div class="flex items-baseline">
                                                <span class="text-2xl font-bold text-green-600">{{ $tour->formatted_price }}</span>
                                                <span class="text-gray-500 text-sm ml-1">per person</span>
                                            </div>
                                            @if($tour->prices && $tour->prices->count() > 1)
                                                <p class="text-xs text-gray-400">Starting from</p>
                                            @endif
                                        @elseif($tour->prices && $tour->prices->count() > 0)
                                            @php $minPrice = $tour->prices->min('price'); @endphp
                                            <div class="flex items-baseline">
                                                <span class="text-2xl font-bold text-green-600">${{ number_format($minPrice) }}</span>
                                                <span class="text-gray-500 text-sm ml-1">per person</span>
                                            </div>
                                            <p class="text-xs text-gray-400">Starting from</p>
                                        @else
                                            <span class="text-green-600 font-semibold text-base">Contact for Pricing</span>
                                        @endif
                                    </div>
                                    
                                    <!-- Rating -->
                                    <div class="flex items-center">
                                        <div class="flex text-yellow-400 mr-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                            @endfor
                                        </div>
                                        <span class="text-sm text-gray-600 font-medium">{{ $tour->average_rating }}</span>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex gap-3">
                                    <a href="{{ route('tours.show', $tour->slug) }}" 
                                       class="flex-1 bg-green-600 hover:bg-green-700 text-white text-center py-3 px-4 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105">
                                        View Details
                                    </a>
                                    <a href="{{ route('booking.create') }}" 
                                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105 flex items-center justify-center">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Navigation Buttons (for manual control) -->
            <button id="prevBtn" 
                    class="absolute left-0 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-green-600 text-gray-800 hover:text-white rounded-full p-4 shadow-xl transition-all duration-300 z-20 backdrop-blur-sm transform hover:scale-110 active:scale-95">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
            <button id="nextBtn" 
                    class="absolute right-0 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-green-600 text-gray-800 hover:text-white rounded-full p-4 shadow-xl transition-all duration-300 z-20 backdrop-blur-sm transform hover:scale-110 active:scale-95">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>

            <!-- Play/Pause Button -->
            <div class="text-center mt-8">
                <button id="playPauseBtn" 
                        class="bg-green-600 hover:bg-green-700 text-white rounded-full p-3 shadow-lg transition-all duration-300 transform hover:scale-110 active:scale-95">
                    <svg id="pauseIcon" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM7 8a1 1 0 012 0v4a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v4a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <svg id="playIcon" class="w-6 h-6 hidden" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>

            <!-- Speed Control -->
            <div class="text-center mt-4">
                <div class="inline-flex items-center gap-3 bg-white rounded-full px-5 py-2 shadow-md">
                    <span class="text-sm font-medium text-gray-600">Speed:</span>
                    <button class="speed-btn px-3 py-1 rounded-full text-sm font-medium transition-all" data-speed="slow">Slow</button>
                    <button class="speed-btn px-3 py-1 rounded-full text-sm font-medium transition-all active" data-speed="normal">Normal</button>
                    <button class="speed-btn px-3 py-1 rounded-full text-sm font-medium transition-all" data-speed="fast">Fast</button>
                </div>
            </div>
        </div>

        @else
        <!-- Empty State -->
        <div class="text-center py-16">
            <svg class="w-24 h-24 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="text-2xl font-bold text-gray-600 mb-2">No Tours Available</h3>
            <p class="text-gray-500">Check back soon for amazing safari adventures!</p>
        </div>
        @endif

        <!-- Explore More Button -->
        @if($showExploreButton && $tours->count() >= $limit)
        <div class="text-center mt-16">
            <a href="{{ route('tours.index') }}" 
               class="inline-flex items-center px-8 py-4 bg-green-600 hover:bg-green-700 text-white font-semibold text-lg rounded-lg transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                Explore All Tours
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
            </a>
        </div>
        @endif
    </div>
</section>

@push('styles')
<style>
    .line-clamp-1 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    #tourCarousel {
        touch-action: pan-y pinch-zoom;
    }

    .carousel-track {
        will-change: transform;
    }
    
    .speed-btn {
        color: #6b7280;
        background: transparent;
    }
    
    .speed-btn.active {
        background: #16a34a;
        color: white;
    }
    
    .speed-btn:hover {
        background: #dcfce7;
        color: #16a34a;
    }
    
    .speed-btn.active:hover {
        background: #15803d;
        color: white;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const track = document.getElementById('carouselTrack');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const playPauseBtn = document.getElementById('playPauseBtn');
    const playIcon = document.getElementById('playIcon');
    const pauseIcon = document.getElementById('pauseIcon');
    const speedBtns = document.querySelectorAll('.speed-btn');
    const totalSlides = {{ $tours->count() }};
    
    if (totalSlides === 0) return;
    
    let currentPosition = 0;
    let isPlaying = true;
    let animationId = null;
    let speed = 0.5; // pixels per frame (normal speed)
    
    const speeds = {
        slow: 0.25,
        normal: 0.5,
        fast: 1
    };
    
    // Duplicate slides for seamless infinite scroll
    function duplicateSlides() {
        const slides = Array.from(track.children);
        
        // Clone all slides and append
        slides.forEach(slide => {
            const clone = slide.cloneNode(true);
            track.appendChild(clone);
        });
        
        // Clone again for smoother loop
        slides.forEach(slide => {
            const clone = slide.cloneNode(true);
            track.appendChild(clone);
        });
    }
    
    function getCardWidth() {
        const firstCard = track.children[0];
        if (!firstCard) return 0;
        const cardRect = firstCard.getBoundingClientRect();
        const gap = 24; // gap-6 = 24px
        return cardRect.width + gap;
    }
    
    function animateScroll() {
        if (!isPlaying) return;
        
        currentPosition += speed;
        const cardWidth = getCardWidth();
        const totalWidth = cardWidth * totalSlides;
        
        // Reset position for seamless loop
        if (currentPosition >= totalWidth) {
            currentPosition = 0;
        }
        
        track.style.transform = `translateX(-${currentPosition}px)`;
        animationId = requestAnimationFrame(animateScroll);
    }
    
    function startAnimation() {
        if (isPlaying && !animationId) {
            animationId = requestAnimationFrame(animateScroll);
        }
    }
    
    function stopAnimation() {
        if (animationId) {
            cancelAnimationFrame(animationId);
            animationId = null;
        }
    }
    
    function togglePlayPause() {
        isPlaying = !isPlaying;
        
        if (isPlaying) {
            playIcon.classList.add('hidden');
            pauseIcon.classList.remove('hidden');
            startAnimation();
        } else {
            playIcon.classList.remove('hidden');
            pauseIcon.classList.add('hidden');
            stopAnimation();
        }
    }
    
    function jumpToNext() {
        const cardWidth = getCardWidth();
        currentPosition += cardWidth;
        const totalWidth = cardWidth * totalSlides;
        
        if (currentPosition >= totalWidth) {
            currentPosition = 0;
        }
        
        track.style.transition = 'transform 0.5s ease-out';
        track.style.transform = `translateX(-${currentPosition}px)`;
        
        setTimeout(() => {
            track.style.transition = 'none';
        }, 500);
    }
    
    function jumpToPrev() {
        const cardWidth = getCardWidth();
        currentPosition -= cardWidth;
        
        if (currentPosition < 0) {
            const totalWidth = cardWidth * totalSlides;
            currentPosition = totalWidth - cardWidth;
        }
        
        track.style.transition = 'transform 0.5s ease-out';
        track.style.transform = `translateX(-${currentPosition}px)`;
        
        setTimeout(() => {
            track.style.transition = 'none';
        }, 500);
    }
    
    function setSpeed(newSpeed) {
        speed = speeds[newSpeed];
        speedBtns.forEach(btn => {
            btn.classList.toggle('active', btn.dataset.speed === newSpeed);
        });
    }
    
    // Event Listeners
    playPauseBtn.addEventListener('click', togglePlayPause);
    
    nextBtn.addEventListener('click', () => {
        jumpToNext();
    });
    
    prevBtn.addEventListener('click', () => {
        jumpToPrev();
    });
    
    speedBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            setSpeed(btn.dataset.speed);
        });
    });
    
    // Pause on hover
    track.addEventListener('mouseenter', () => {
        stopAnimation();
    });
    
    track.addEventListener('mouseleave', () => {
        if (isPlaying) {
            startAnimation();
        }
    });
    
    // Touch/Swipe support
    let touchStartX = 0;
    let touchEndX = 0;
    let touchStartTime = 0;
    
    track.addEventListener('touchstart', (e) => {
        touchStartX = e.touches[0].clientX;
        touchStartTime = Date.now();
        stopAnimation();
    }, { passive: true });
    
    track.addEventListener('touchmove', (e) => {
        touchEndX = e.touches[0].clientX;
    }, { passive: true });
    
    track.addEventListener('touchend', () => {
        const swipeThreshold = 50;
        const horizontalDiff = touchStartX - touchEndX;
        const swipeTime = Date.now() - touchStartTime;
        
        // Quick swipe detection
        if (swipeTime < 300) {
            if (horizontalDiff > swipeThreshold) {
                jumpToNext();
            } else if (horizontalDiff < -swipeThreshold) {
                jumpToPrev();
            }
        }
        
        if (isPlaying) {
            startAnimation();
        }
    });
    
    // Handle visibility change (pause when tab is hidden)
    document.addEventListener('visibilitychange', () => {
        if (document.hidden) {
            stopAnimation();
        } else if (isPlaying) {
            startAnimation();
        }
    });
    
    // Handle window resize
    let resizeTimer;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
            // Maintain relative position after resize
            const cardWidth = getCardWidth();
            const slideIndex = Math.floor(currentPosition / cardWidth);
            currentPosition = slideIndex * cardWidth;
        }, 250);
    });
    
    // Initialize
    duplicateSlides();
    startAnimation();
    
    // Ensure proper initialization after fonts/images load
    window.addEventListener('load', () => {
        duplicateSlides();
    });
});
</script>
@endpush