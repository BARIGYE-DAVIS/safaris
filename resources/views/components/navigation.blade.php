<nav class="bg-white shadow-lg sticky top-0 z-50 transition-all duration-300">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16 md:h-20">
            <!-- Logo -->
            <div class="flex-shrink-0">
                <a href="{{ route('home') }}" class="flex items-center space-x-2">
                    <img class="h-8 w-auto md:h-10" src="{{ asset('images/logo.png') }}" alt="Safari Logo">
                    <span class="text-xl md:text-2xl font-bold text-green-700">SafariTours</span>
                </a>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:block">
                <div class="ml-10 flex items-baseline space-x-8">
                    <a href="{{ route('home') }}" 
                       class="nav-link {{ request()->routeIs('home') ? 'text-green-700 border-b-2 border-green-700' : 'text-gray-700 hover:text-green-700' }} px-3 py-2 text-sm font-medium transition-colors duration-200">
                        Home
                    </a>
                    
                    <div class="relative group">
                        <button class="nav-link {{ request()->routeIs('tours.*') ? 'text-green-700' : 'text-gray-700 hover:text-green-700' }} px-3 py-2 text-sm font-medium transition-colors duration-200 flex items-center">
                            Tours
                            <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
                            <div class="py-1">
                                <a href="{{ route('tours.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700">All Tours</a>
                                <a href="{{ route('tours.category', 'wildlife') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700">Wildlife Safaris</a>
                                <a href="{{ route('tours.category', 'adventure') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700">Adventure Tours</a>
                                <a href="{{ route('tours.category', 'cultural') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700">Cultural Tours</a>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('gallery') }}" 
                       class="nav-link {{ request()->routeIs('gallery') ? 'text-green-700 border-b-2 border-green-700' : 'text-gray-700 hover:text-green-700' }} px-3 py-2 text-sm font-medium transition-colors duration-200">
                        Gallery
                    </a>
                    
                    <a href="{{ route('blog.index') }}" 
                       class="nav-link {{ request()->routeIs('blog.*') ? 'text-green-700 border-b-2 border-green-700' : 'text-gray-700 hover:text-green-700' }} px-3 py-2 text-sm font-medium transition-colors duration-200">
                        Blog
                    </a>
                    
                    <a href="{{ route('contact') }}" 
                       class="nav-link {{ request()->routeIs('contact') ? 'text-green-700 border-b-2 border-green-700' : 'text-gray-700 hover:text-green-700' }} px-3 py-2 text-sm font-medium transition-colors duration-200">
                        Contact
                    </a>
                </div>
            </div>

            <!-- CTA Button (Desktop) -->
            <div class="hidden md:block">
                <a href="{{ route('booking.create') }}" 
                   class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-full text-sm font-medium transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg">
                    Book Now
                </a>
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden">
                <button type="button" 
                        class="mobile-menu-button bg-gray-100 inline-flex items-center justify-center p-2 rounded-md text-gray-700 hover:text-green-700 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-green-500 transition-colors duration-200"
                        aria-controls="mobile-menu" 
                        aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <!-- Menu icon -->
                    <svg class="menu-icon block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <!-- Close icon -->
                    <svg class="close-icon hidden h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile menu -->
        <div class="mobile-menu hidden md:hidden bg-white border-t border-gray-200" id="mobile-menu">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="{{ route('home') }}" 
                   class="mobile-nav-link {{ request()->routeIs('home') ? 'bg-green-50 text-green-700 border-l-4 border-green-700' : 'text-gray-700 hover:bg-gray-50 hover:text-green-700' }} block px-3 py-2 text-base font-medium transition-all duration-200">
                    Home
                </a>
                
                <!-- Mobile Tours Dropdown -->
                <div class="relative">
                    <button class="mobile-tours-toggle w-full text-left mobile-nav-link {{ request()->routeIs('tours.*') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-50 hover:text-green-700' }} block px-3 py-2 text-base font-medium transition-all duration-200 flex items-center justify-between">
                        Tours
                        <svg class="tours-arrow h-5 w-5 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="mobile-tours-menu hidden pl-4 space-y-1">
                        <a href="{{ route('tours.index') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-green-700 hover:bg-green-50 transition-colors duration-200">All Tours</a>
                        <a href="{{ route('tours.category', 'wildlife') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-green-700 hover:bg-green-50 transition-colors duration-200">Wildlife Safaris</a>
                        <a href="{{ route('tours.category', 'adventure') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-green-700 hover:bg-green-50 transition-colors duration-200">Adventure Tours</a>
                        <a href="{{ route('tours.category', 'cultural') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-green-700 hover:bg-green-50 transition-colors duration-200">Cultural Tours</a>
                    </div>
                </div>

                <a href="{{ route('gallery') }}" 
                   class="mobile-nav-link {{ request()->routeIs('gallery') ? 'bg-green-50 text-green-700 border-l-4 border-green-700' : 'text-gray-700 hover:bg-gray-50 hover:text-green-700' }} block px-3 py-2 text-base font-medium transition-all duration-200">
                    Gallery
                </a>
                
                <a href="{{ route('blog.index') }}" 
                   class="mobile-nav-link {{ request()->routeIs('blog.*') ? 'bg-green-50 text-green-700 border-l-4 border-green-700' : 'text-gray-700 hover:bg-gray-50 hover:text-green-700' }} block px-3 py-2 text-base font-medium transition-all duration-200">
                    Blog
                </a>
                
                <a href="{{ route('contact') }}" 
                   class="mobile-nav-link {{ request()->routeIs('contact') ? 'bg-green-50 text-green-700 border-l-4 border-green-700' : 'text-gray-700 hover:bg-gray-50 hover:text-green-700' }} block px-3 py-2 text-base font-medium transition-all duration-200">
                    Contact
                </a>
                
                <!-- Mobile CTA -->
                <div class="px-3 py-2">
                    <a href="{{ route('booking.create') }}" 
                       class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-full text-sm font-medium transition-all duration-300 inline-block text-center">
                        Book Now
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle
    const mobileMenuButton = document.querySelector('.mobile-menu-button');
    const mobileMenu = document.querySelector('.mobile-menu');
    const menuIcon = document.querySelector('.menu-icon');
    const closeIcon = document.querySelector('.close-icon');

    if (mobileMenuButton) {
        mobileMenuButton.addEventListener('click', function() {
            const isExpanded = mobileMenuButton.getAttribute('aria-expanded') === 'true';
            
            mobileMenuButton.setAttribute('aria-expanded', !isExpanded);
            mobileMenu.classList.toggle('hidden');
            menuIcon.classList.toggle('hidden');
            closeIcon.classList.toggle('hidden');
        });
    }

    // Mobile tours dropdown
    const toursToggle = document.querySelector('.mobile-tours-toggle');
    const toursMenu = document.querySelector('.mobile-tours-menu');
    const toursArrow = document.querySelector('.tours-arrow');

    if (toursToggle) {
        toursToggle.addEventListener('click', function() {
            toursMenu.classList.toggle('hidden');
            toursArrow.classList.toggle('rotate-180');
        });
    }

    // Close mobile menu when clicking outside
    document.addEventListener('click', function(event) {
        if (mobileMenuButton && mobileMenu && 
            !mobileMenuButton.contains(event.target) && 
            !mobileMenu.contains(event.target)) {
            mobileMenu.classList.add('hidden');
            mobileMenuButton.setAttribute('aria-expanded', 'false');
            menuIcon.classList.remove('hidden');
            closeIcon.classList.add('hidden');
        }
    });

    // Close mobile menu on window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 768 && mobileMenu) {
            mobileMenu.classList.add('hidden');
            if (mobileMenuButton) {
                mobileMenuButton.setAttribute('aria-expanded', 'false');
            }
            if (menuIcon) menuIcon.classList.remove('hidden');
            if (closeIcon) closeIcon.classList.add('hidden');
        }
    });
});
</script>