@php
    // Get REAL categories from database
    $tourCategories = \App\Models\ActivityCategory::where('is_active', true)->get();
    
    // Get REAL tours with their itinerary count
    $tours = \App\Models\Tour::with('itineraries')->where('status', 'published')->get();
    
    // Get REAL destinations
    $destinations = \App\Models\Destination::where('is_active', true)->with('country')->get();
    
    // Get REAL activities
    $activities = \App\Models\Activity::where('is_active', true)->with(['category', 'destination'])->get();
@endphp

<nav class="bg-white shadow-lg sticky top-0 z-50 transition-all duration-300">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16 md:h-20">
            <!-- Logo -->
            <div class="flex-shrink-0">
                <a href="{{ route('index') }}" class="flex items-center space-x-2">
                    <img class="h-8 w-auto md:h-10" src="{{ asset('images/logo.png') }}" alt="Calm Africa Safaris Logo">
                    <div class="flex flex-col">
                        <span class="text-xl md:text-2xl font-bold text-green-700 leading-tight">Calm Africa</span>
                        <span class="text-sm md:text-base font-medium text-gray-600 -mt-1">Safaris</span>
                    </div>
                </a>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:block">
                <div class="ml-10 flex items-baseline space-x-8">
                    <a href="{{ route('index') }}" 
                       class="nav-link {{ request()->routeIs('index') ? 'text-green-700 border-b-2 border-green-700' : 'text-gray-700 hover:text-green-700' }} px-3 py-2 text-sm font-medium transition-colors duration-200">
                        Home
                    </a>
                    
                    <!-- Tours Dropdown -->
                    <div class="relative group">
                        <button class="nav-link {{ request()->routeIs('tours.*') ? 'text-green-700' : 'text-gray-700 hover:text-green-700' }} px-3 py-2 text-sm font-medium transition-colors duration-200 flex items-center">
                            Tours
                            <svg class="ml-1 h-4 w-4 transform group-hover:rotate-180 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        
                        <!-- Tours Dropdown Menu -->
                        <div class="absolute left-0 mt-2 w-80 bg-white rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50 translate-y-2 group-hover:translate-y-0 border border-gray-100">
                            <div class="p-4 max-h-96 overflow-y-auto">
                                
                                <!-- By Category (if categories exist) -->
                                @if($tourCategories->count() > 0)
                                <div class="mb-4">
                                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">By Category</h3>
                                    <div class="space-y-1">
                                        @foreach($tourCategories as $category)
                                            @php
                                                $categoryTours = $tours->filter(function($tour) use ($category) {
                                                    return $tour->category_id == $category->id;
                                                });
                                            @endphp
                                            
                                            @if($categoryTours->count() > 0)
                                            <div class="px-3 py-2 hover:bg-green-50 rounded">
                                                <div class="text-sm font-medium text-gray-800 mb-1">
                                                    <i class="{{ $category->icon }} mr-2"></i>{{ $category->name }}
                                                </div>
                                                <div class="ml-6 space-y-1">
                                                    @foreach($categoryTours->take(5) as $tour)
                                                    <a href="{{ route('tours.show', $tour->slug) }}" class="block text-xs text-gray-600 hover:text-green-700 py-1">
                                                        → {{ $tour->title }}
                                                    </a>
                                                    @endforeach
                                                </div>
                                            </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                                @endif

                                <!-- Available Tours by Duration -->
                                @if($tours->count() > 0)
                                <div class="border-t pt-4">
                                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Available Tours</h3>
                                    <div class="space-y-2">
                                        @foreach($tours->take(8) as $tour)
                                        <a href="{{ route('tours.show', $tour->slug) }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 rounded transition">
                                            <div class="font-medium">{{ $tour->title }}</div>
                                            <div class="text-xs text-gray-500">
                                                {{ $tour->itineraries->count() }} Days Safari
                                                @if($tour->destination)
                                                    in {{ $tour->destination }}
                                                @endif
                                            </div>
                                        </a>
                                        @endforeach
                                    </div>
                                    
                                    @if($tours->count() > 8)
                                    <a href="{{ route('tours.index') }}" class="block mt-2 text-center text-xs text-green-600 hover:text-green-700 font-medium">
                                        View All {{ $tours->count() }} Tours →
                                    </a>
                                    @endif
                                </div>
                                @else
                                <div class="text-center text-gray-500 py-4">
                                    No tours available yet
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Destinations Dropdown -->
                    <div class="relative group">
                        <button class="nav-link {{ request()->routeIs('destinations.*') ? 'text-green-700' : 'text-gray-700 hover:text-green-700' }} px-3 py-2 text-sm font-medium transition-colors duration-200 flex items-center">
                            Destinations
                            <svg class="ml-1 h-4 w-4 transform group-hover:rotate-180 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <!-- Destinations Dropdown Menu -->
                        <div class="absolute left-0 mt-2 w-80 bg-white rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50 translate-y-2 group-hover:translate-y-0 border border-gray-100">
                            <div class="p-4">
                                @if($destinations->count() > 0)
                                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">All Destinations</h3>
                                <div class="grid grid-cols-2 gap-3 max-h-96 overflow-y-auto">
                                    @foreach($destinations as $destination)
                                    <a href="{{ route('destinations.show', $destination->slug) }}" class="group/item">
                                        <div class="bg-gray-50 hover:bg-green-50 rounded-lg p-3 transition">
                                            @if($destination->image)
                                            <img src="{{ asset('storage/' . $destination->image) }}" alt="{{ $destination->name }}" class="w-full h-20 object-cover rounded mb-2">
                                            @else
                                            <div class="w-full h-20 bg-gradient-to-br from-green-400 to-blue-500 rounded mb-2 flex items-center justify-center">
                                                <i class="fas fa-map-marker-alt text-white text-2xl"></i>
                                            </div>
                                            @endif
                                            <h4 class="text-sm font-semibold text-gray-800 group-hover/item:text-green-700 transition truncate">
                                                {{ $destination->name }}
                                            </h4>
                                            <p class="text-xs text-gray-500">{{ $destination->country->flag_icon }} {{ $destination->country->name }}</p>
                                        </div>
                                    </a>
                                    @endforeach
                                </div>
                                @else
                                <div class="text-center text-gray-500 py-4">
                                    No destinations available yet
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Activities Dropdown -->
                    <div class="relative group">
                        <button class="nav-link {{ request()->routeIs('activities.*') ? 'text-green-700' : 'text-gray-700 hover:text-green-700' }} px-3 py-2 text-sm font-medium transition-colors duration-200 flex items-center">
                            Activities
                            <svg class="ml-1 h-4 w-4 transform group-hover:rotate-180 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <!-- Activities Dropdown Menu -->
                        <div class="absolute left-0 mt-2 w-80 bg-white rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50 translate-y-2 group-hover:translate-y-0 border border-gray-100">
                            <div class="p-4">
                                @if($activities->count() > 0)
                               
                            <a href="{{ route('activities.index') }}" class="block mb-4 text-center text-sm font-medium text-green-600 hover:text-green-700">
                                View All {{ $activities->count() }} Activities →
                            </a>
                                
                                <div class="grid grid-cols-1 gap-2 max-h-96 overflow-y-auto">
                                    @foreach($activities as $activity)
                                    <a href="{{ route('activities.show', $activity->slug) }}" class="group/item">
                                        <div class="flex items-center gap-3 bg-gray-50 hover:bg-green-50 rounded-lg p-3 transition">
                                            @if($activity->image)
                                            <img src="{{ asset('storage/' . $activity->image) }}" alt="{{ $activity->name }}" class="w-16 h-16 object-cover rounded flex-shrink-0">
                                            @elseif($activity->icon)
                                            <div class="w-16 h-16 bg-gradient-to-br from-green-400 to-blue-500 rounded flex items-center justify-center flex-shrink-0">
                                                <i class="{{ $activity->icon }} text-white text-2xl"></i>
                                            </div>
                                            @else
                                            <div class="w-16 h-16 bg-gray-300 rounded flex items-center justify-center flex-shrink-0">
                                                <i class="fas fa-hiking text-gray-500 text-2xl"></i>
                                            </div>
                                            @endif
                                            <div class="flex-1 min-w-0">
                                                <h4 class="text-sm font-semibold text-gray-800 group-hover/item:text-green-700 transition truncate">
                                                    {{ $activity->name }}
                                                </h4>
                                                @if($activity->category)
                                                <p class="text-xs text-purple-600">
                                                    <i class="{{ $activity->category->icon }} mr-1"></i>{{ $activity->category->name }}
                                                </p>
                                                @endif
                                                @if($activity->destination)
                                                <p class="text-xs text-gray-500">
                                                    <i class="fas fa-map-marker-alt mr-1"></i>{{ $activity->destination->name }}
                                                </p>
                                                @endif
                                            </div>
                                        </div>
                                    </a>
                                    @endforeach
                                </div>
                                @else
                                <div class="text-center text-gray-500 py-4">
                                    No activities available yet
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('gallery.index') }}" 
                       class="nav-link {{ request()->routeIs('gallery.index') ? 'text-green-700 border-b-2 border-green-700' : 'text-gray-700 hover:text-green-700' }} px-3 py-2 text-sm font-medium transition-colors duration-200">
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
                    Book Safari
                </a>
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden">
                <button type="button" 
                        class="mobile-menu-button p-2 focus:outline-none group"
                        aria-controls="mobile-menu" 
                        aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <div class="w-6 h-5 relative">
                        <span class="hamburger-line block absolute left-0 top-0 w-full h-0.5 bg-gray-800 transform transition-all duration-300 ease-in-out group-hover:bg-green-600"></span>
                        <span class="hamburger-line block absolute left-0 top-1/2 -translate-y-1/2 w-full h-0.5 bg-gray-800 transform transition-all duration-300 ease-in-out group-hover:bg-green-600"></span>
                        <span class="hamburger-line block absolute left-0 bottom-0 w-full h-0.5 bg-gray-800 transform transition-all duration-300 ease-in-out group-hover:bg-green-600"></span>
                    </div>
                </button>
            </div>
        </div>

        <!-- Mobile menu -->
        <div class="mobile-menu hidden md:hidden bg-white border-t border-gray-200" id="mobile-menu">
            <div class="px-2 pt-2 pb-3 space-y-1 max-h-96 overflow-y-auto">
                <a href="{{ route('index') }}" 
                   class="mobile-nav-link {{ request()->routeIs('index') ? 'bg-green-50 text-green-700 border-l-4 border-green-700' : 'text-gray-700 hover:bg-gray-50 hover:text-green-700' }} block px-3 py-2 text-base font-medium transition-all duration-200">
                    🏠 Home
                </a>
                
                <a href="{{ route('tours.index') }}" 
                   class="mobile-nav-link {{ request()->routeIs('tours.*') ? 'bg-green-50 text-green-700 border-l-4 border-green-700' : 'text-gray-700 hover:bg-gray-50 hover:text-green-700' }} block px-3 py-2 text-base font-medium transition-all duration-200">
                    🦁 Tours
                </a>

                <a href="{{ route('destinations.index') }}" 
                   class="mobile-nav-link {{ request()->routeIs('destinations.*') ? 'bg-green-50 text-green-700 border-l-4 border-green-700' : 'text-gray-700 hover:bg-gray-50 hover:text-green-700' }} block px-3 py-2 text-base font-medium transition-all duration-200">
                    🗺️ Destinations
                </a>

                <a href="{{ route('activities.index') }}" 
                   class="mobile-nav-link {{ request()->routeIs('activities.*') ? 'bg-green-50 text-green-700 border-l-4 border-green-700' : 'text-gray-700 hover:bg-gray-50 hover:text-green-700' }} block px-3 py-2 text-base font-medium transition-all duration-200">
                    🏃 Activities
                </a>

                <a href="{{ route('gallery.index') }}" 
                   class="mobile-nav-link {{ request()->routeIs('gallery.index') ? 'bg-green-50 text-green-700 border-l-4 border-green-700' : 'text-gray-700 hover:bg-gray-50 hover:text-green-700' }} block px-3 py-2 text-base font-medium transition-all duration-200">
                    📸 Gallery
                </a>
                
                <a href="{{ route('blog.index') }}" 
                   class="mobile-nav-link {{ request()->routeIs('blog.*') ? 'bg-green-50 text-green-700 border-l-4 border-green-700' : 'text-gray-700 hover:bg-gray-50 hover:text-green-700' }} block px-3 py-2 text-base font-medium transition-all duration-200">
                    📝 Blog
                </a>
                
                <a href="{{ route('contact') }}" 
                   class="mobile-nav-link {{ request()->routeIs('contact') ? 'bg-green-50 text-green-700 border-l-4 border-green-700' : 'text-gray-700 hover:bg-gray-50 hover:text-green-700' }} block px-3 py-2 text-base font-medium transition-all duration-200">
                    📞 Contact
                </a>
                
                <div class="px-3 py-4 border-t border-gray-200 mt-4">
                    <a href="{{ route('booking.create') }}" 
                       class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-full text-sm font-medium transition-all duration-300 inline-block text-center">
                        🎯 Book Safari Now
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuButton = document.querySelector('.mobile-menu-button');
    const mobileMenu = document.querySelector('.mobile-menu');

    if (mobileMenuButton) {
        mobileMenuButton.addEventListener('click', function() {
            const isExpanded = mobileMenuButton.getAttribute('aria-expanded') === 'true';
            mobileMenuButton.setAttribute('aria-expanded', !isExpanded);
            mobileMenu.classList.toggle('hidden');
        });
    }
});
</script>