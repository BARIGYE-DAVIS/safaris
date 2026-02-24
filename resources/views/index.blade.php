@extends('layouts.app')

@section('title', 'Calm Africa Safaris - Premium Safari Tours & Wildlife Adventures in East Africa')
@section('meta_description', 'Experience unforgettable safari adventures in East Africa with Calm Africa Safaris. Expert guides, luxury accommodations, wildlife tours, cultural experiences. Book your dream African safari today!')
@section('meta_keywords', 'safari tours, East Africa safari, Tanzania safari, Kenya safari, Uganda safari, wildlife tours, luxury safari, family safari, honeymoon safari, Big Five safari, gorilla trekking, cultural tours, adventure tours, safari packages, African safari, wildlife photography, conservation tours, guided safari, custom safari, safari booking, Serengeti, Ngorongoro, Maasai Mara, Mount Kilimanjaro, safari holidays, eco tourism, responsible travel')

@section('page-header')
<!-- Hero Carousel Section -->
<section class="relative h-screen overflow-hidden">
    <!-- Carousel Container -->
    <div class="hero-carousel relative w-full h-full">
        <!-- Slide 1 -->
        <div class="hero-slide absolute inset-0 w-full h-full opacity-100 transition-opacity duration-1000 ease-in-out" 
             style="background-image: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('{{ asset('images/BWINDI.jpg') }}'); background-size: cover; background-position: center;">
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="text-center text-white max-w-4xl px-4 sm:px-6 lg:px-8">
                    <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold mb-6 animate-fade-in-up">
                        Experience Wild Africa
                    </h1>
                    <p class="text-lg md:text-xl lg:text-2xl mb-8 opacity-90 animate-fade-in-up animation-delay-300">
                        Embark on extraordinary safari adventures across East Africa's most iconic destinations
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center animate-fade-in-up animation-delay-600">
                        <a href="{{ route('tours.index') }}" 
                           class="bg-green-600 hover:bg-green-700 text-white px-8 py-4 rounded-lg font-semibold text-lg transition-all duration-300 transform hover:scale-105">
                            Explore Tours
                        </a>
                        <a href="#tour-packages" 
                           class="border-2 border-white text-white hover:bg-white hover:text-green-600 px-8 py-4 rounded-lg font-semibold text-lg transition-all duration-300">
                            View Packages
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Slide 2 -->
        <div class="hero-slide absolute inset-0 w-full h-full opacity-0 transition-opacity duration-1000 ease-in-out"
             style="background-image: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('{{ asset('images/BIG FIVE.jpg') }}         '); background-size: cover; background-position: center;">
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="text-center text-white max-w-4xl px-4 sm:px-6 lg:px-8">
                    <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold mb-6 animate-fade-in-up">
                        Witness the Big Five
                    </h1>
                    <p class="text-lg md:text-xl lg:text-2xl mb-8 opacity-90 animate-fade-in-up animation-delay-300">
                        Get up close with Africa's most magnificent wildlife in their natural habitat
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center animate-fade-in-up animation-delay-600">
                        <a href="{{ route('tours.index') }}" 
                           class="bg-orange-600 hover:bg-orange-700 text-white px-8 py-4 rounded-lg font-semibold text-lg transition-all duration-300 transform hover:scale-105">
                            Wildlife Safaris
                        </a>
                        <a href="{{ route('contact') }}" 
                           class="border-2 border-white text-white hover:bg-white hover:text-orange-600 px-8 py-4 rounded-lg font-semibold text-lg transition-all duration-300">
                            Plan Custom Safari
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Slide 3 -->
        <div class="hero-slide absolute inset-0 w-full h-full opacity-0 transition-opacity duration-1000 ease-in-out"
             style="background-image: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('https://images.unsplash.com/photo-1523805009345-7448845a9e53?ixlib=rb-4.0.3&auto=format&fit=crop&w=2400&q=80'); background-size: cover; background-position: center;">
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="text-center text-white max-w-4xl px-4 sm:px-6 lg:px-8">
                    <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold mb-6 animate-fade-in-up">
                        Luxury Safari Adventures
                    </h1>
                    <p class="text-lg md:text-xl lg:text-2xl mb-8 opacity-90 animate-fade-in-up animation-delay-300">
                        Indulge in premium safari experiences with world-class accommodations
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center animate-fade-in-up animation-delay-600">
                        <a href="{{ route('tours.index') }}" 
                           class="bg-yellow-600 hover:bg-yellow-700 text-white px-8 py-4 rounded-lg font-semibold text-lg transition-all duration-300 transform hover:scale-105">
                            Luxury Tours
                        </a>
                        <a href="{{ route('booking.create') }}" 
                           class="border-2 border-white text-white hover:bg-white hover:text-yellow-600 px-8 py-4 rounded-lg font-semibold text-lg transition-all duration-300">
                            Book Now
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Carousel Navigation Dots -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 flex space-x-3 z-20">
        <button class="hero-dot w-3 h-3 rounded-full bg-white opacity-100 transition-opacity duration-300" data-slide="0"></button>
        <button class="hero-dot w-3 h-3 rounded-full bg-white opacity-50 transition-opacity duration-300" data-slide="1"></button>
        <button class="hero-dot w-3 h-3 rounded-full bg-white opacity-50 transition-opacity duration-300" data-slide="2"></button>
    </div>

    <!-- Scroll Down Indicator -->
    <div class="absolute bottom-8 right-8 text-white animate-bounce">
        <a href="#introduction" class="flex flex-col items-center text-sm opacity-80 hover:opacity-100 transition-opacity">
            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
            </svg>
            <span>Scroll</span>
        </a>
    </div>
</section>
@endsection

@section('content')
<!-- Introduction Section -->
<section id="introduction" class="py-16 lg:py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-6">
                Welcome to <span class="text-green-600">Calm Africa Safaris</span>
            </h2>
          @include('partials.about-safari')
        </div>


        </div>
    </div>
</section>

<!-- Tour Packages Section -->
<section id="tour-packages" class="py-16 lg:py-24 bg-gray-400">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-6">
                Featured Safari Packages
            </h2>
            <p class="text-lg md:text-xl text-gray-700 max-w-3xl mx-auto">
                Discover our most popular safari experiences, carefully crafted to showcase the best of East Africa
            </p>
        </div>

        @php
    // Fetch featured tours from database (max 10)
    $featuredTours = \App\Models\Tour::published()
      
        ->orderBy('created_at', 'desc')
        ->limit(10)
        ->get();
@endphp

@include('partials.tour-list', [
    'tours' => $featuredTours,
    'limit' => 10,
    'showExploreButton' => true,
    'columns' => 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3',
    'heading' => 'Featured Safari Packages',
    'subheading' => 'Discover our most popular safari experiences, carefully crafted to showcase the best of East Africa'
])

        <!-- View All Tours Button -->
        <div class="text-center mt-12">
            <a href="{{ route('tours.index') }}" 
               class="inline-flex items-center bg-green-600 hover:bg-green-700 text-white px-8 py-4 rounded-lg font-semibold text-lg transition-all duration-300 transform hover:scale-105">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                Explore All Safari Tours
            </a>
        </div>
    </div>
</section>

@include('partials.destinations-carousel')

@include('partials.activities-carousel')



<!-- Why Choose Us Section -->
<section class="py-16 lg:py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-6">
                Why Choose Calm Africa Safaris?
            </h2>
            <p class="text-lg md:text-xl text-gray-700 max-w-3xl mx-auto">
                We're not just tour operators – we're conservation partners, community supporters, and dream makers
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Feature 1 -->
            <div class="text-center group">
                <div class="w-20 h-20 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Safety & Security First</h3>
                <p class="text-gray-600">
                    Your safety is our top priority. All our guides are certified, our vehicles are regularly maintained, 
                    and we maintain comprehensive insurance coverage.
                </p>
            </div>

            <!-- Feature 2 -->
            <div class="text-center group">
                <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Small Group Experiences</h3>
                <p class="text-gray-600">
                    We keep our groups small to ensure personalized attention, better wildlife viewing opportunities, 
                    and minimal environmental impact.
                </p>
            </div>

            <!-- Feature 3 -->
            <div class="text-center group">
                <div class="w-20 h-20 bg-gradient-to-br from-orange-500 to-orange-600 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Community Impact</h3>
                <p class="text-gray-600">
                    Every safari supports local communities through employment, fair wages, and community development projects. 
                    Travel with purpose and positive impact.
                </p>
            </div>

            <!-- Feature 4 -->
            <div class="text-center group">
                <div class="w-20 h-20 bg-gradient-to-br from-purple-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Guaranteed Departures</h3>
                <p class="text-gray-600">
                    Once you book with us, your safari is guaranteed to depart regardless of group size. 
                    No last-minute cancellations or disappointments.
                </p>
            </div>

            <!-- Feature 5 -->
            <div class="text-center group">
                <div class="w-20 h-20 bg-gradient-to-br from-red-500 to-red-600 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-4">24/7 Support</h3>
                <p class="text-gray-600">
                    Our dedicated support team is available around the clock to assist you before, during, 
                    and after your safari. Peace of mind guaranteed.
                </p>
            </div>

            <!-- Feature 6 -->
            <div class="text-center group">
                <div class="w-20 h-20 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Best Value Promise</h3>
                <p class="text-gray-600">
                    We offer competitive pricing without compromising on quality. If you find a comparable tour for less, 
                    we'll match the price and add extra value.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action Section -->
<section class="bg-gradient-to-r from-green-600 via-green-700 to-blue-600 py-16 lg:py-24">
    <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-6">
            Ready to Begin Your African Adventure?
        </h2>
        <p class="text-lg md:text-xl text-green-100 mb-8 max-w-3xl mx-auto">
            Join thousands of satisfied travelers who have experienced the magic of East Africa with us. 
            Your dream safari is just one click away.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('booking.create') }}" 
               class="bg-white text-green-600 px-8 py-4 rounded-lg font-bold text-lg hover:bg-gray-100 transition-all duration-300 transform hover:scale-105">
                Book Your Safari Now
            </a>
            <a href="{{ route('contact') }}" 
               class="border-2 border-white text-white px-8 py-4 rounded-lg font-bold text-lg hover:bg-white hover:text-green-600 transition-all duration-300">
                Get Custom Quote
            </a>
            <a href="https://wa.me/256700000000" 
               class="bg-green-500 text-white px-8 py-4 rounded-lg font-bold text-lg hover:bg-green-400 transition-all duration-300 flex items-center justify-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                </svg>
                WhatsApp Chat
            </a>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    /* ... keep all existing styles ... */
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Hero Carousel
    const heroSlides = document.querySelectorAll('.hero-slide');
    const heroDots = document.querySelectorAll('.hero-dot');
    let currentHeroSlide = 0;

    function showHeroSlide(index) {
        heroSlides.forEach((slide, i) => {
            slide.style.opacity = i === index ? '1' : '0';
        });
        heroDots.forEach((dot, i) => {
            dot.style.opacity = i === index ? '1' : '0.5';
        });
    }

    function nextHeroSlide() {
        currentHeroSlide = (currentHeroSlide + 1) % heroSlides.length;
        showHeroSlide(currentHeroSlide);
    }

    // Hero dots click handlers
    heroDots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            currentHeroSlide = index;
            showHeroSlide(currentHeroSlide);
        });
    });

    // Auto-advance hero carousel
    setInterval(nextHeroSlide, 6000);

    // Package Carousel
    const packageTrack = document.querySelector('.package-track');
    const packageSlides = document.querySelectorAll('.package-slide');
    const packagePrev = document.querySelector('.package-prev');
    const packageNext = document.querySelector('.package-next');
    const packageIndicators = document.querySelectorAll('.package-indicator');
    
    let currentPackageIndex = 0;
    let slidesToShow = 3; // Default for desktop
    
    // Determine slides to show based on screen size
    function updateSlidesToShow() {
        const width = window.innerWidth;
        if (width < 640) {
            slidesToShow = 1;
        } else if (width < 1024) {
            slidesToShow = 2;
        } else {
            slidesToShow = 3;
        }
    }
    
    function updatePackageCarousel() {
        if (!packageTrack) return;
        
        const slideWidth = 100 / slidesToShow;
        const translateX = -(currentPackageIndex * slideWidth);
        packageTrack.style.transform = `translateX(${translateX}%)`;
        
        // Update indicators
        packageIndicators.forEach((indicator, index) => {
            indicator.classList.toggle('bg-green-600', index === currentPackageIndex);
            indicator.classList.toggle('bg-gray-300', index !== currentPackageIndex);
        });
    }
    
    // Package navigation
    if (packagePrev) {
        packagePrev.addEventListener('click', () => {
            const maxIndex = Math.max(0, packageSlides.length - slidesToShow);
            currentPackageIndex = Math.max(0, currentPackageIndex - 1);
            updatePackageCarousel();
        });
    }
    
    if (packageNext) {
        packageNext.addEventListener('click', () => {
            const maxIndex = Math.max(0, packageSlides.length - slidesToShow);
            currentPackageIndex = Math.min(maxIndex, currentPackageIndex + 1);
            updatePackageCarousel();
        });
    }
    
    // Package indicators
    packageIndicators.forEach((indicator, index) => {
        indicator.addEventListener('click', () => {
            currentPackageIndex = index;
            updatePackageCarousel();
        });
    });
    
    // Initialize carousel
    updateSlidesToShow();
    updatePackageCarousel();
    
    // Handle window resize
    let resizeTimer;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
            const oldSlidesToShow = slidesToShow;
            updateSlidesToShow();
            
            if (oldSlidesToShow !== slidesToShow) {
                currentPackageIndex = 0; // Reset to first slide
                updatePackageCarousel();
            }
        }, 250);
    });

    // Auto-advance package carousel (optional)
    setInterval(() => {
        if (packageSlides.length > slidesToShow) {
            const maxIndex = Math.max(0, packageSlides.length - slidesToShow);
            currentPackageIndex = currentPackageIndex >= maxIndex ? 0 : currentPackageIndex + 1;
            updatePackageCarousel();
        }
    }, 8000);

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Intersection Observer for animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-fade-in-up');
            }
        });
    }, observerOptions);

    // Observe elements for animation
    document.querySelectorAll('.group, .package-slide, .text-center').forEach(el => {
        observer.observe(el);
    });
});
</script>
@endpush