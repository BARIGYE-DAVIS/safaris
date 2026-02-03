@php
    // Get dynamic data from database
    $tourCategories = \App\Models\Tour::whereNotNull('category')
                                    ->where('category', '!=', '')
                                    ->distinct()
                                    ->pluck('category')
                                    ->filter()
                                    ->sort();
                                    
    $tourTypes = \App\Models\Tour::whereNotNull('type')
                               ->where('type', '!=', '')
                               ->distinct()
                               ->pluck('type')
                               ->filter()
                               ->sort();
                               
    // Get tours grouped by duration for quick access
    $toursByDuration = \App\Models\Tour::with('itineraries')
                                      ->get()
                                      ->groupBy(function ($tour) {
                                          $days = $tour->itineraries->count();
                                          return $days . ' Day' . ($days != 1 ? 's' : '');
                                      })
                                      ->sortKeys();
@endphp

<nav class="bg-white shadow-lg sticky top-0 z-50 transition-all duration-300">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16 md:h-20">
            <!-- Logo -->
            <div class="flex-shrink-0">
                <a href="{{ route('home') }}" class="flex items-center space-x-2">
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
                    <a href="{{ route('home') }}" 
                       class="nav-link {{ request()->routeIs('home') ? 'text-green-700 border-b-2 border-green-700' : 'text-gray-700 hover:text-green-700' }} px-3 py-2 text-sm font-medium transition-colors duration-200">
                        Home
                    </a>
                    
                    <!-- Tours Mega Menu -->
                    <div class="relative group">
                        <button class="nav-link {{ request()->routeIs('tours.*') ? 'text-green-700' : 'text-gray-700 hover:text-green-700' }} px-3 py-2 text-sm font-medium transition-colors duration-200 flex items-center">
                            Tours
                            <svg class="ml-1 h-4 w-4 transform group-hover:rotate-180 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        
                        <!-- Centered Mega Menu Dropdown -->
                        <div class="absolute left-1/2 transform -translate-x-1/2 mt-2 w-screen max-w-5xl bg-white rounded-lg shadow-2xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50 translate-y-2 group-hover:translate-y-0 border border-gray-100">
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 p-6">
                                
                                <!-- Quick Access -->
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                        <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                        Quick Access
                                    </h3>
                                    <div class="space-y-2">
                                        <a href="{{ route('tours.index') }}" 
                                           class="block px-4 py-3 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 rounded-lg transition-colors duration-200 group/item">
                                            <div class="font-medium group-hover/item:text-green-700">All Tours</div>
                                            <div class="text-xs text-gray-500">Browse all available safaris</div>
                                        </a>
                                        <a href="{{ route('tours.index', ['sort' => 'popular']) }}" 
                                           class="block px-4 py-3 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 rounded-lg transition-colors duration-200 group/item">
                                            <div class="font-medium group-hover/item:text-green-700">Most Popular</div>
                                            <div class="text-xs text-gray-500">Customer favorites</div>
                                        </a>
                                        <a href="{{ route('tours.index', ['sort' => 'newest']) }}" 
                                           class="block px-4 py-3 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 rounded-lg transition-colors duration-200 group/item">
                                            <div class="font-medium group-hover/item:text-green-700">New Tours</div>
                                            <div class="text-xs text-gray-500">Latest additions</div>
                                        </a>
                                        <a href="{{ route('tours.index', ['price_range' => 'low']) }}" 
                                           class="block px-4 py-3 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 rounded-lg transition-colors duration-200 group/item">
                                            <div class="font-medium group-hover/item:text-green-700">Budget Friendly</div>
                                            <div class="text-xs text-gray-500">Affordable options</div>
                                        </a>
                                    </div>
                                </div>

                                <!-- Categories -->
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                        <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                        </svg>
                                        By Category
                                    </h3>
                                    <div class="space-y-2">
                                        @forelse($tourCategories->take(7) as $category)
                                        <a href="{{ route('tours.category', $category) }}" 
                                           class="block px-4 py-3 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 rounded-lg transition-colors duration-200 group/item">
                                            <div class="font-medium group-hover/item:text-green-700">{{ ucfirst($category) }} Safaris</div>
                                            <div class="text-xs text-gray-500">
                                                {{ \App\Models\Tour::where('category', $category)->count() }} tours available
                                            </div>
                                        </a>
                                        @empty
                                        <div class="px-4 py-3 text-sm text-gray-500">
                                            <div>Wildlife Safaris</div>
                                            <div class="text-xs">Coming soon</div>
                                        </div>
                                        <div class="px-4 py-3 text-sm text-gray-500">
                                            <div>Cultural Tours</div>
                                            <div class="text-xs">Coming soon</div>
                                        </div>
                                        <div class="px-4 py-3 text-sm text-gray-500">
                                            <div>Adventure Tours</div>
                                            <div class="text-xs">Coming soon</div>
                                        </div>
                                        @endforelse
                                        
                                        @if($tourCategories->count() > 7)
                                        <a href="{{ route('tours.index') }}" 
                                           class="block px-4 py-2 text-xs text-green-600 hover:text-green-700 font-medium">
                                            View All Categories →
                                        </a>
                                        @endif
                                    </div>
                                </div>

                                <!-- Tour Types & Duration -->
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                        <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        By Duration
                                    </h3>
                                    
                                    <!-- Duration Options -->
                                    <div class="space-y-2 mb-4">
                                        @forelse($toursByDuration->take(5) as $duration => $tours)
                                        <a href="{{ route('tours.index', ['duration' => $tours->first()->itineraries->count()]) }}" 
                                           class="block px-4 py-3 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 rounded-lg transition-colors duration-200 group/item">
                                            <div class="font-medium group-hover/item:text-green-700">{{ $duration }} Safaris</div>
                                            <div class="text-xs text-gray-500">{{ $tours->count() }} tours available</div>
                                        </a>
                                        @empty
                                        <div class="px-4 py-3 text-sm text-gray-500">
                                            <div>1 Day Safaris</div>
                                            <div class="text-xs">Coming soon</div>
                                        </div>
                                        <div class="px-4 py-3 text-sm text-gray-500">
                                            <div>3 Days Safaris</div>
                                            <div class="text-xs">Coming soon</div>
                                        </div>
                                        <div class="px-4 py-3 text-sm text-gray-500">
                                            <div>7 Days Safaris</div>
                                            <div class="text-xs">Coming soon</div>
                                        </div>
                                        @endforelse
                                    </div>
                                    
                                    <!-- Tour Types -->
                                    @if($tourTypes->count() > 0)
                                    <div class="border-t pt-4">
                                        <h4 class="text-sm font-medium text-gray-900 mb-3">Tour Types:</h4>
                                        <div class="grid grid-cols-2 gap-2">
                                            @foreach($tourTypes->take(6) as $type)
                                            <a href="{{ route('tours.index', ['type' => $type]) }}" 
                                               class="bg-gray-100 hover:bg-green-100 text-gray-700 hover:text-green-700 px-3 py-2 rounded-md text-xs font-medium transition-colors duration-200 text-center">
                                                {{ ucfirst($type) }}
                                            </a>
                                            @endforeach
                                        </div>
                                    </div>
                                    @else
                                    <div class="border-t pt-4">
                                        <h4 class="text-sm font-medium text-gray-900 mb-3">Tour Types:</h4>
                                        <div class="grid grid-cols-2 gap-2">
                                            <div class="bg-gray-100 text-gray-500 px-3 py-2 rounded-md text-xs text-center">
                                                Group Tours
                                            </div>
                                            <div class="bg-gray-100 text-gray-500 px-3 py-2 rounded-md text-xs text-center">
                                                Private Tours
                                            </div>
                                            <div class="bg-gray-100 text-gray-500 px-3 py-2 rounded-md text-xs text-center">
                                                Family Tours
                                            </div>
                                            <div class="bg-gray-100 text-gray-500 px-3 py-2 rounded-md text-xs text-center">
                                                Luxury Tours
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Bottom CTA -->
                            <div class="bg-gradient-to-r from-green-50 to-blue-50 px-6 py-4 rounded-b-lg border-t border-gray-100">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">Can't find what you're looking for?</div>
                                        <div class="text-xs text-gray-600">We create custom safari experiences tailored to your needs</div>
                                    </div>
                                    <div class="flex space-x-3">
                                        <a href="{{ route('contact') }}" 
                                           class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                                            Contact Us
                                        </a>
                                        <a href="{{ route('booking.create') }}" 
                                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                                            Custom Tour
                                        </a>
                                    </div>
                                </div>
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
                    Book Safari
                </a>
            </div>

            <!-- Mobile menu button -->
            <!-- Clean Flat Mobile Menu Toggle -->
<div class="md:hidden">
    <button type="button" 
            class="mobile-menu-button p-2 focus:outline-none group"
            aria-controls="mobile-menu" 
            aria-expanded="false">
        <span class="sr-only">Open main menu</span>
        
        <!-- Simple Hamburger Lines -->
        <div class="w-6 h-5 relative">
            <!-- Top line -->
            <span class="hamburger-line block absolute left-0 top-0 w-full h-0.5 bg-gray-800 transform transition-all duration-300 ease-in-out group-hover:bg-green-600"></span>
            <!-- Middle line -->
            <span class="hamburger-line block absolute left-0 top-1/2 -translate-y-1/2 w-full h-0.5 bg-gray-800 transform transition-all duration-300 ease-in-out group-hover:bg-green-600"></span>
            <!-- Bottom line -->
            <span class="hamburger-line block absolute left-0 bottom-0 w-full h-0.5 bg-gray-800 transform transition-all duration-300 ease-in-out group-hover:bg-green-600"></span>
        </div>
    </button>
</div>
        </div>

        <!-- Mobile menu -->
        <div class="mobile-menu hidden md:hidden bg-white border-t border-gray-200" id="mobile-menu">
            <div class="px-2 pt-2 pb-3 space-y-1 max-h-96 overflow-y-auto">
                <a href="{{ route('home') }}" 
                   class="mobile-nav-link {{ request()->routeIs('home') ? 'bg-green-50 text-green-700 border-l-4 border-green-700' : 'text-gray-700 hover:bg-gray-50 hover:text-green-700' }} block px-3 py-2 text-base font-medium transition-all duration-200">
                    🏠 Home
                </a>
                
                <!-- Mobile Tours Section -->
                <div class="border-b border-gray-100 pb-3 mb-3">
                    <div class="relative">
                        <button class="mobile-tours-toggle w-full text-left mobile-nav-link {{ request()->routeIs('tours.*') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-50 hover:text-green-700' }} block px-3 py-2 text-base font-medium transition-all duration-200 flex items-center justify-between">
                            🦁 Tours
                            <svg class="tours-arrow h-5 w-5 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        
                        <div class="mobile-tours-menu hidden overflow-hidden transition-all duration-300">
                            <!-- Quick Access -->
                            <div class="pl-6 py-3">
                                <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3 flex items-center">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                    Quick Access
                                </div>
                                <div class="space-y-1">
                                    <a href="{{ route('tours.index') }}" 
                                       class="block px-3 py-2 text-sm text-gray-600 hover:text-green-700 hover:bg-green-50 rounded transition-colors duration-200">
                                        All Tours
                                    </a>
                                    <a href="{{ route('tours.index', ['sort' => 'popular']) }}" 
                                       class="block px-3 py-2 text-sm text-gray-600 hover:text-green-700 hover:bg-green-50 rounded transition-colors duration-200">
                                        Most Popular
                                    </a>
                                    <a href="{{ route('tours.index', ['sort' => 'newest']) }}" 
                                       class="block px-3 py-2 text-sm text-gray-600 hover:text-green-700 hover:bg-green-50 rounded transition-colors duration-200">
                                        New Tours
                                    </a>
                                    <a href="{{ route('tours.index', ['price_range' => 'low']) }}" 
                                       class="block px-3 py-2 text-sm text-gray-600 hover:text-green-700 hover:bg-green-50 rounded transition-colors duration-200">
                                        Budget Friendly
                                    </a>
                                </div>
                            </div>
                            
                            <!-- Categories -->
                            @if($tourCategories->count() > 0)
                            <div class="pl-6 py-3 border-t border-gray-100">
                                <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3 flex items-center">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                    Categories
                                </div>
                                <div class="space-y-1">
                                    @foreach($tourCategories->take(8) as $category)
                                    <a href="{{ route('tours.category', $category) }}" 
                                       class="block px-3 py-2 text-sm text-gray-600 hover:text-green-700 hover:bg-green-50 rounded transition-colors duration-200">
                                        {{ ucfirst($category) }} Safaris
                                        <span class="text-xs text-gray-400 block">
                                            {{ \App\Models\Tour::where('category', $category)->count() }} tours
                                        </span>
                                    </a>
                                    @endforeach
                                    
                                    @if($tourCategories->count() > 8)
                                    <a href="{{ route('tours.index') }}" 
                                       class="block px-3 py-1 text-xs text-green-600 hover:text-green-700 font-medium">
                                        View All Categories →
                                    </a>
                                    @endif
                                </div>
                            </div>
                            @else
                            <div class="pl-6 py-3 border-t border-gray-100">
                                <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Categories</div>
                                <div class="space-y-1">
                                    <div class="px-3 py-2 text-sm text-gray-400">Wildlife Safaris (Coming soon)</div>
                                    <div class="px-3 py-2 text-sm text-gray-400">Cultural Tours (Coming soon)</div>
                                    <div class="px-3 py-2 text-sm text-gray-400">Adventure Tours (Coming soon)</div>
                                </div>
                            </div>
                            @endif
                            
                            <!-- Duration -->
                            @if($toursByDuration->count() > 0)
                            <div class="pl-6 py-3 border-t border-gray-100">
                                <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3 flex items-center">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    By Duration
                                </div>
                                <div class="grid grid-cols-2 gap-2">
                                    @foreach($toursByDuration->take(6) as $duration => $tours)
                                    <a href="{{ route('tours.index', ['duration' => $tours->first()->itineraries->count()]) }}" 
                                       class="bg-gray-100 hover:bg-green-100 text-gray-700 hover:text-green-700 px-2 py-2 rounded text-xs font-medium transition-colors duration-200 text-center">
                                        {{ $duration }}
                                        <span class="block text-xs text-gray-400">{{ $tours->count() }} tours</span>
                                    </a>
                                    @endforeach
                                </div>
                            </div>
                            @else
                            <div class="pl-6 py-3 border-t border-gray-100">
                                <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">By Duration</div>
                                <div class="grid grid-cols-2 gap-2">
                                    <div class="bg-gray-100 text-gray-500 px-2 py-2 rounded text-xs text-center">1 Day Tours</div>
                                    <div class="bg-gray-100 text-gray-500 px-2 py-2 rounded text-xs text-center">3 Days Tours</div>
                                    <div class="bg-gray-100 text-gray-500 px-2 py-2 rounded text-xs text-center">7 Days Tours</div>
                                    <div class="bg-gray-100 text-gray-500 px-2 py-2 rounded text-xs text-center">14+ Days Tours</div>
                                </div>
                            </div>
                            @endif
                            
                            <!-- Tour Types -->
                            @if($tourTypes->count() > 0)
                            <div class="pl-6 py-3 border-t border-gray-100">
                                <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Tour Types</div>
                                <div class="grid grid-cols-2 gap-1">
                                    @foreach($tourTypes as $type)
                                    <a href="{{ route('tours.index', ['type' => $type]) }}" 
                                       class="bg-gray-100 hover:bg-green-100 text-gray-700 hover:text-green-700 px-2 py-1 rounded text-xs font-medium transition-colors duration-200 text-center">
                                        {{ ucfirst($type) }}
                                    </a>
                                    @endforeach
                                </div>
                            </div>
                            @else
                            <div class="pl-6 py-3 border-t border-gray-100">
                                <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Tour Types</div>
                                <div class="grid grid-cols-2 gap-1">
                                    <div class="bg-gray-100 text-gray-500 px-2 py-1 rounded text-xs text-center">Group Tours</div>
                                    <div class="bg-gray-100 text-gray-500 px-2 py-1 rounded text-xs text-center">Private Tours</div>
                                    <div class="bg-gray-100 text-gray-500 px-2 py-1 rounded text-xs text-center">Family Tours</div>
                                    <div class="bg-gray-100 text-gray-500 px-2 py-1 rounded text-xs text-center">Luxury Tours</div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <a href="{{ route('gallery') }}" 
                   class="mobile-nav-link {{ request()->routeIs('gallery') ? 'bg-green-50 text-green-700 border-l-4 border-green-700' : 'text-gray-700 hover:bg-gray-50 hover:text-green-700' }} block px-3 py-2 text-base font-medium transition-all duration-200">
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
                
                <!-- Mobile CTA -->
                <div class="px-3 py-4 border-t border-gray-200 mt-4 space-y-3">
                    <a href="{{ route('booking.create') }}" 
                       class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-full text-sm font-medium transition-all duration-300 inline-block text-center">
                        🎯 Book Safari Now
                    </a>
                    <a href="{{ route('contact') }}" 
                       class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-full text-sm font-medium transition-all duration-300 inline-block text-center">
                        💬 Custom Safari
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
            
            // Prevent body scroll when menu is open
            if (!isExpanded) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        });
    }

    // Mobile tours dropdown with smooth animation
    const toursToggle = document.querySelector('.mobile-tours-toggle');
    const toursMenu = document.querySelector('.mobile-tours-menu');
    const toursArrow = document.querySelector('.tours-arrow');

    if (toursToggle && toursMenu) {
        toursToggle.addEventListener('click', function(e) {
            e.preventDefault();
            
            const isHidden = toursMenu.classList.contains('hidden');
            
            if (isHidden) {
                toursMenu.classList.remove('hidden');
                toursMenu.style.maxHeight = '0px';
                toursMenu.style.opacity = '0';
                
                // Force reflow
                toursMenu.offsetHeight;
                
                // Animate in
                toursMenu.style.maxHeight = toursMenu.scrollHeight + 'px';
                toursMenu.style.opacity = '1';
                toursArrow.classList.add('rotate-180');
                
                // Clean up after animation
                setTimeout(() => {
                    toursMenu.style.maxHeight = 'none';
                }, 300);
            } else {
                // Animate out
                toursMenu.style.maxHeight = toursMenu.scrollHeight + 'px';
                toursMenu.offsetHeight; // Force reflow
                toursMenu.style.maxHeight = '0px';
                toursMenu.style.opacity = '0';
                toursArrow.classList.remove('rotate-180');
                
                setTimeout(() => {
                    toursMenu.classList.add('hidden');
                    toursMenu.style.maxHeight = '';
                    toursMenu.style.opacity = '';
                }, 300);
            }
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
            document.body.style.overflow = '';
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
            document.body.style.overflow = '';
            
            // Reset tours menu state
            if (toursMenu) {
                toursMenu.classList.add('hidden');
                toursMenu.style.maxHeight = '';
                toursMenu.style.opacity = '';
                if (toursArrow) toursArrow.classList.remove('rotate-180');
            }
        }
    });

    // Handle mega menu positioning for edge cases
    const megaMenus = document.querySelectorAll('.group > div.absolute');
    
    megaMenus.forEach(menu => {
        const parent = menu.parentElement;
        
        parent.addEventListener('mouseenter', function() {
            setTimeout(() => {
                const rect = menu.getBoundingClientRect();
                const screenWidth = window.innerWidth;
                const menuWidth = menu.offsetWidth;
                
                // If menu goes off the right edge
                if (rect.right > screenWidth - 20) {
                    menu.style.transform = 'translateX(calc(-100% + 100px))';
                }
                // If menu goes off the left edge  
                else if (rect.left < 20) {
                    menu.style.transform = 'translateX(-20px)';
                }
                // Default centered position
                else {
                    menu.style.transform = 'translateX(-50%)';
                }
            }, 10);
        });
        
        parent.addEventListener('mouseleave', function() {
            menu.style.transform = 'translateX(-50%)';
        });
    });

    // Close any open dropdowns when clicking elsewhere
    document.addEventListener('click', function(event) {
        const groups = document.querySelectorAll('.group');
        groups.forEach(group => {
            if (!group.contains(event.target)) {
                // Force remove hover state
                group.classList.remove('hover');
            }
        });
    });

    // Add smooth transitions
    const style = document.createElement('style');
    style.textContent = `
        .mobile-tours-menu {
            transition: max-height 0.3s ease, opacity 0.3s ease;
        }
        
        .group:hover > .absolute {
            animation: fadeInUp 0.3s ease forwards;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateX(-50%) translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateX(-50%) translateY(0);
            }
        }
    `;
    document.head.appendChild(style);
});
</script>