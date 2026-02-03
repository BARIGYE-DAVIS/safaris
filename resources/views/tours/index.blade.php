<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uganda Safari Tours | Adventure Awaits</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50 font-sans">
    <!-- Header -->
    <header class="bg-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <h1 class="text-2xl font-bold text-green-600">Safari Uganda</h1>
                </div>
                <nav class="hidden md:flex space-x-8">
                    <a href="#" class="text-gray-700 hover:text-green-600 font-medium">Home</a>
                    <a href="#" class="text-green-600 font-medium">Tours</a>
                    <a href="#" class="text-gray-700 hover:text-green-600 font-medium">About</a>
                    <a href="#" class="text-gray-700 hover:text-green-600 font-medium">Contact</a>
                </nav>
                <div class="md:hidden">
                    <button class="text-gray-700 hover:text-green-600">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-green-600 to-blue-600 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-6xl font-bold text-white mb-6">
                Discover Uganda's Wildlife
            </h1>
            <p class="text-xl text-green-100 mb-8 max-w-3xl mx-auto">
                Experience the Pearl of Africa with our carefully crafted safari adventures. 
                From mountain gorillas to the Big Five, create memories that last a lifetime.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <div class="bg-white/20 backdrop-blur rounded-lg p-4 text-white">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-star text-yellow-400"></i>
                        <span class="font-semibold">500+ Happy Travelers</span>
                    </div>
                </div>
                <div class="bg-white/20 backdrop-blur rounded-lg p-4 text-white">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-award text-green-400"></i>
                        <span class="font-semibold">Award Winning Tours</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Filter Section -->
    <section class="py-8 bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Our Safari Tours</h2>
                    <p class="text-gray-600">{{ $tours->count() }} amazing adventures waiting for you</p>
                </div>
                <div class="flex gap-4">
                    <!-- Dynamic Categories Filter -->
                    <form method="GET" action="{{ route('tours.index') }}" class="flex gap-4">
                        <select name="category" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" onchange="this.form.submit()">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                    {{ $category }}
                                </option>
                            @endforeach
                        </select>

                        <!-- Dynamic Tour Types Filter -->
                        <select name="type" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" onchange="this.form.submit()">
                            <option value="">All Types</option>
                            @foreach($types as $type)
                                <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                                    {{ $type }}
                                </option>
                            @endforeach
                        </select>

                        <!-- Price Sorting -->
                        <select name="sort" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" onchange="this.form.submit()">
                            <option value="">Sort by</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="duration" {{ request('sort') == 'duration' ? 'selected' : '' }}>Duration</option>
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                        </select>

                        <!-- Clear Filters Button -->
                        @if(request()->hasAny(['category', 'type', 'sort']))
                            <a href="{{ route('tours.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors flex items-center">
                                <i class="fas fa-times mr-2"></i> Clear
                            </a>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Active Filters Display -->
            @if(request()->hasAny(['category', 'type', 'sort']))
            <div class="mt-4 flex flex-wrap gap-2">
                <span class="text-sm text-gray-600 mr-2">Active filters:</span>
                @if(request('category'))
                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm">
                        Category: {{ request('category') }}
                        <a href="{{ request()->fullUrlWithQuery(['category' => null]) }}" class="ml-2 text-red-600 hover:text-red-800">×</a>
                    </span>
                @endif
                @if(request('type'))
                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                        Type: {{ request('type') }}
                        <a href="{{ request()->fullUrlWithQuery(['type' => null]) }}" class="ml-2 text-red-600 hover:text-red-800">×</a>
                    </span>
                @endif
                @if(request('sort'))
                    <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm">
                        Sort: {{ ucfirst(str_replace('_', ' ', request('sort'))) }}
                        <a href="{{ request()->fullUrlWithQuery(['sort' => null]) }}" class="ml-2 text-red-600 hover:text-red-800">×</a>
                    </span>
                @endif
            </div>
            @endif
        </div>
    </section>

    <!-- Tours Grid -->
    <section class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($tours as $tour)
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 group">
                    <!-- Tour Image -->
                    <div class="relative h-64 overflow-hidden">
                        @if($tour->featured_image)
                            <img src="{{ asset('storage/' . $tour->featured_image) }}" 
                                 alt="{{ $tour->title }}" 
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-green-400 to-blue-500 flex items-center justify-center">
                                <i class="fas fa-image text-white text-4xl opacity-50"></i>
                            </div>
                        @endif
                        
                        <!-- Category Badge -->
                        <div class="absolute top-4 left-4">
                            <span class="bg-green-600 text-white px-3 py-1 rounded-full text-sm font-semibold">
                                {{ $tour->category }}
                            </span>
                        </div>
                        
                        <!-- Duration Badge -->
                        <div class="absolute top-4 right-4">
                            <span class="bg-white/90 backdrop-blur text-gray-800 px-3 py-1 rounded-full text-sm font-semibold">
                                {{ count($tour->itineraries) }} Days
                            </span>
                        </div>

                        <!-- Type Badge -->
                        <div class="absolute bottom-4 left-4">
                            <span class="bg-blue-600 text-white px-3 py-1 rounded-full text-xs font-semibold">
                                {{ $tour->type }}
                            </span>
                        </div>
                    </div>

                    <!-- Tour Content -->
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2">
                            {{ $tour->title }}
                        </h3>
                        
                        <p class="text-gray-600 mb-4 line-clamp-3">
                            {{ Str::limit($tour->description, 120) }}
                        </p>

                        <!-- Destinations -->
                        <div class="flex items-center mb-4 text-sm text-gray-500">
                            <i class="fas fa-map-marker-alt text-green-600 mr-2"></i>
                            <span class="line-clamp-1">{{ $tour->destinations }}</span>
                        </div>

                        <!-- Tour Details -->
                        <div class="flex items-center justify-between mb-4 text-xs text-gray-500">
                            <div class="flex items-center">
                                <i class="fas fa-calendar text-green-600 mr-1"></i>
                                <span>{{ count($tour->itineraries) }} Days</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-users text-green-600 mr-1"></i>
                                <span>{{ $tour->type }}</span>
                            </div>
                        </div>

                        <!-- Pricing -->
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                @if($tour->prices->count() > 0)
                                    @php $minPrice = $tour->prices->min('price'); @endphp
                                    <div class="flex items-baseline">
                                        <span class="text-2xl font-bold text-green-600">${{ number_format($minPrice) }}</span>
                                        <span class="text-gray-500 text-sm ml-1">per person</span>
                                    </div>
                                    <p class="text-xs text-gray-400">Starting from</p>
                                @else
                                    <span class="text-green-600 font-semibold">Contact for Pricing</span>
                                @endif
                            </div>
                            
                            <!-- Rating (you can make this dynamic later) -->
                            <div class="flex items-center">
                                <div class="flex text-yellow-400 mr-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star text-sm"></i>
                                    @endfor
                                </div>
                                <span class="text-sm text-gray-600">4.9</span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-3">
                            <a href="{{ route('tours.show', $tour->slug) }}" 
                               class="flex-1 bg-green-600 hover:bg-green-700 text-white text-center py-3 px-4 rounded-lg font-semibold transition-colors duration-300">
                                View Details
                            </a>
                            <button onclick="quickBook('{{ $tour->slug }}')" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg font-semibold transition-colors duration-300">
                                <i class="fas fa-calendar-check"></i>
                            </button>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-16">
                    <i class="fas fa-binoculars text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-2xl font-bold text-gray-600 mb-2">No Tours Available</h3>
                    @if(request()->hasAny(['category', 'type', 'sort']))
                        <p class="text-gray-500 mb-4">No tours match your current filters.</p>
                        <a href="{{ route('tours.index') }}" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition-colors">
                            View All Tours
                        </a>
                    @else
                        <p class="text-gray-500">Check back soon for amazing safari adventures!</p>
                    @endif
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($tours->hasPages())
            <div class="mt-12 flex justify-center">
                {{ $tours->appends(request()->query())->links() }}
            </div>
            @endif
        </div>
    </section>

    <!-- Call to Action -->
    <section class="bg-gradient-to-r from-green-600 to-blue-600 py-16">
        <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-white mb-4">
                Ready for Your Uganda Adventure?
            </h2>
            <p class="text-xl text-green-100 mb-8">
                Let our expert guides take you on an unforgettable journey through the Pearl of Africa
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="#" class="bg-white text-green-600 px-8 py-3 rounded-lg font-bold hover:bg-gray-100 transition-colors">
                    Contact Us
                </a>
                <a href="#" class="border-2 border-white text-white px-8 py-3 rounded-lg font-bold hover:bg-white hover:text-green-600 transition-colors">
                    Call: +256 700 000 000
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4 text-green-400">Safari Uganda</h3>
                    <p class="text-gray-400 mb-4">Experience the Pearl of Africa with our expertly guided tours.</p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-green-400"><i class="fab fa-facebook text-xl"></i></a>
                        <a href="#" class="text-gray-400 hover:text-green-400"><i class="fab fa-twitter text-xl"></i></a>
                        <a href="#" class="text-gray-400 hover:text-green-400"><i class="fab fa-instagram text-xl"></i></a>
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-green-400">Home</a></li>
                        <li><a href="#" class="hover:text-green-400">Tours</a></li>
                        <li><a href="#" class="hover:text-green-400">About Us</a></li>
                        <li><a href="#" class="hover:text-green-400">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Popular Categories</h3>
                    <ul class="space-y-2 text-gray-400">
                        @foreach($categories->take(4) as $category)
                            <li><a href="{{ route('tours.index', ['category' => $category]) }}" class="hover:text-green-400">{{ $category }}</a></li>
                        @endforeach
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Contact Info</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><i class="fas fa-phone text-green-400 mr-2"></i> +256 700 000 000</li>
                        <li><i class="fas fa-envelope text-green-400 mr-2"></i> info@safariuganda.com</li>
                        <li><i class="fas fa-map-marker-alt text-green-400 mr-2"></i> Kampala, Uganda</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} Safari Uganda. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        function quickBook(tourSlug) {
            window.location.href = `/tours/${tourSlug}#booking`;
        }
    </script>
</body>
</html>