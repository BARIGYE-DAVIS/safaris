@extends('layouts.app')

@section('title', ucfirst($category) . ' Safaris - Safari Tours | Calm Africa Safaris')
@section('meta_description', 'Discover amazing ' . $category . ' safari tours in East Africa. Experience wildlife, culture, and adventure with our expertly guided ' . $category . ' safari packages.')
@section('meta_keywords', $category . ' safaris, ' . $category . ' tours, safari packages, East Africa tours, Tanzania safaris, Kenya safaris, Uganda safaris, wildlife tours, adventure tours, cultural tours, safari booking, ' . $category . ' safari experiences')

@section('page-header')
<!-- Category Page Header -->
<header class="relative bg-gradient-to-r from-green-600 to-blue-600 py-20 md:py-32">
    <div class="absolute inset-0 bg-black opacity-30"></div>
    <div class="relative z-10 container mx-auto px-4 text-center text-white">
        <div class="max-w-4xl mx-auto">
            <div class="mb-6">
                <span class="inline-block bg-white/20 backdrop-blur text-white px-4 py-2 rounded-full text-sm font-medium mb-4">
                    {{ ucfirst($category) }} Category
                </span>
                <h1 class="text-4xl md:text-6xl font-bold mb-4">{{ ucfirst($category) }} Safaris</h1>
                <p class="text-xl md:text-2xl mb-8 max-w-3xl mx-auto">
                    @switch($category)
                        @case('wildlife')
                            Experience the Big Five and incredible wildlife encounters in their natural habitat
                            @break
                        @case('cultural')
                            Immerse yourself in rich traditions, local communities, and authentic cultural experiences
                            @break
                        @case('adventure')
                            Thrill-seeking expeditions combining wildlife viewing with exciting outdoor activities
                            @break
                        @case('luxury')
                            Premium safari experiences with world-class accommodations and personalized service
                            @break
                        @case('family')
                            Child-friendly safaris designed for unforgettable family bonding and education
                            @break
                        @case('honeymoon')
                            Romantic safari getaways perfect for couples and special celebrations
                            @break
                        @default
                            Discover the best of {{ $category }} safari experiences in East Africa
                    @endswitch
                </p>
            </div>

            <!-- Category Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
                <div class="bg-white/10 backdrop-blur rounded-lg p-4">
                    <div class="text-2xl font-bold text-green-300">{{ $tours->total() }}</div>
                    <div class="text-sm text-gray-200">Available Tours</div>
                </div>
                <div class="bg-white/10 backdrop-blur rounded-lg p-4">
                    <div class="text-2xl font-bold text-green-300">
                        @if($tours->count() > 0)
                            {{ $tours->flatMap(function($tour) { return $tour->prices; })->count() > 0 ? 
                               '$' . number_format($tours->flatMap(function($tour) { return $tour->prices; })->min('price')) : 'Custom' }}
                        @else
                            Contact
                        @endif
                    </div>
                    <div class="text-sm text-gray-200">Starting Price</div>
                </div>
                <div class="bg-white/10 backdrop-blur rounded-lg p-4">
                    <div class="text-2xl font-bold text-green-300">
                        @if($tours->count() > 0)
                            {{ $tours->flatMap(function($tour) { return $tour->itineraries; })->count() > 0 ? 
                               number_format($tours->flatMap(function($tour) { return $tour->itineraries; })->groupBy('tour_id')->avg(function($group) { return $group->count(); }), 1) : 'Multi' }}
                        @else
                            Various
                        @endif
                    </div>
                    <div class="text-sm text-gray-200">Avg Duration (Days)</div>
                </div>
            </div>

            <!-- Breadcrumb -->
            <nav class="text-sm">
                <ol class="flex justify-center space-x-2 text-green-200">
                    <li><a href="{{ route('index') }}" class="hover:text-white transition-colors">Home</a></li>
                    <li class="mx-2">/</li>
                    <li><a href="{{ route('tours.index') }}" class="hover:text-white transition-colors">Tours</a></li>
                    <li class="mx-2">/</li>
                    <li class="text-white">{{ ucfirst($category) }} Safaris</li>
                </ol>
            </nav>
        </div>
    </div>
</header>
@endsection

@section('content')
    <!-- Category Description -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">
                    Why Choose {{ ucfirst($category) }} Safaris?
                </h2>
                <div class="text-lg text-gray-700 leading-relaxed space-y-4">
                    @switch($category)
                        @case('wildlife')
                            <p>Our wildlife safaris offer unparalleled opportunities to witness the Big Five - lions, leopards, elephants, buffalos, and rhinos - in their natural habitat. From the great migration in the Serengeti to intimate game drives in private conservancies, experience Africa's most magnificent creatures up close.</p>
                            <p>Expert guides with decades of experience ensure you don't miss any wildlife action, while our carefully selected accommodations provide comfort after thrilling days in the field.</p>
                            @break
                        @case('cultural')
                            <p>Immerse yourself in the rich tapestry of East African cultures through authentic interactions with local communities. Visit traditional villages, participate in ancient ceremonies, and learn about time-honored customs that have been passed down through generations.</p>
                            <p>Our cultural safaris support local communities directly, ensuring your visit contributes to sustainable tourism and cultural preservation.</p>
                            @break
                        @case('adventure')
                            <p>For thrill-seekers and outdoor enthusiasts, our adventure safaris combine wildlife viewing with exciting activities like mountain climbing, white-water rafting, zip-lining, and hiking. Experience East Africa's diverse landscapes from unique perspectives.</p>
                            <p>From scaling Mount Kilimanjaro to exploring hidden waterfalls, these tours are designed for those who want to add an adrenaline rush to their African adventure.</p>
                            @break
                        @case('luxury')
                            <p>Indulge in the ultimate safari experience with our luxury tours featuring world-class accommodations, gourmet dining, private vehicles, and personalized service. Every detail is meticulously planned for your comfort and enjoyment.</p>
                            <p>Stay in exclusive tented camps and luxury lodges while enjoying premium amenities and exceptional service that exceeds expectations.</p>
                            @break
                        @case('family')
                            <p>Create lasting memories with our family-friendly safaris designed to educate and entertain travelers of all ages. Child-safe activities, educational programs, and comfortable accommodations ensure everyone enjoys the adventure.</p>
                            <p>Our experienced guides are skilled at engaging children with fascinating wildlife facts and interactive learning experiences.</p>
                            @break
                        @case('honeymoon')
                            <p>Celebrate your love with romantic safari experiences in some of Africa's most breathtaking locations. Private dinners under the stars, couples' spa treatments, and intimate game drives create the perfect honeymoon atmosphere.</p>
                            <p>Specially curated experiences include sunset champagne toasts, private bush breakfasts, and romantic accommodations with stunning views.</p>
                            @break
                        @default
                            <p>Discover the beauty and diversity of {{ $category }} safaris with our expertly crafted tours. Each experience is designed to showcase the best of East Africa while providing comfort, safety, and unforgettable memories.</p>
                            <p>Our local guides and carefully selected accommodations ensure you experience authentic African hospitality and wildlife encounters.</p>
                    @endswitch
                </div>
            </div>
        </div>
    </section>

    <!-- Category Features -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-gray-900 text-center mb-12">
                What Makes Our {{ ucfirst($category) }} Safaris Special
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @switch($category)
                    @case('wildlife')
                        <div class="bg-white rounded-xl p-6 shadow-md">
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-3">Big Five Guarantee</h3>
                            <p class="text-gray-600">Expert guides maximize your chances of spotting lions, leopards, elephants, buffalo, and rhinos.</p>
                        </div>
                        <div class="bg-white rounded-xl p-6 shadow-md">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-3">Conservation Focus</h3>
                            <p class="text-gray-600">Support wildlife conservation through responsible tourism and community involvement.</p>
                        </div>
                        <div class="bg-white rounded-xl p-6 shadow-md">
                            <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-3">Photography Paradise</h3>
                            <p class="text-gray-600">Perfect lighting and positioning for incredible wildlife photography opportunities.</p>
                        </div>
                        @break
                    @case('cultural')
                        <div class="bg-white rounded-xl p-6 shadow-md">
                            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-3">Community Connection</h3>
                            <p class="text-gray-600">Authentic interactions with local communities and traditional leaders.</p>
                        </div>
                        <div class="bg-white rounded-xl p-6 shadow-md">
                            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-3">Cultural Immersion</h3>
                            <p class="text-gray-600">Participate in traditional ceremonies, crafts, and daily life activities.</p>
                        </div>
                        <div class="bg-white rounded-xl p-6 shadow-md">
                            <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-3">Educational Experience</h3>
                            <p class="text-gray-600">Learn about history, traditions, and modern-day challenges of local communities.</p>
                        </div>
                        @break
                    @default
                        <div class="bg-white rounded-xl p-6 shadow-md">
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-3">Expert Guides</h3>
                            <p class="text-gray-600">Professional guides with extensive knowledge and experience in {{ $category }} tours.</p>
                        </div>
                        <div class="bg-white rounded-xl p-6 shadow-md">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-3">Flexible Itineraries</h3>
                            <p class="text-gray-600">Customizable tours adapted to your preferences and schedule.</p>
                        </div>
                        <div class="bg-white rounded-xl p-6 shadow-md">
                            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-3">Premium Service</h3>
                            <p class="text-gray-600">High-quality service and attention to detail for an exceptional experience.</p>
                        </div>
                @endswitch
            </div>
        </div>
    </section>

    <!-- Tours Listing -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">
                    Available {{ ucfirst($category) }} Safari Tours
                </h2>
                <p class="text-lg text-gray-600">
                    Choose from our carefully curated {{ $category }} safari experiences
                </p>
            </div>

            @if($tours->count() > 0)
                <!-- Tours Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                    @foreach($tours as $tour)
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 group">
                        <!-- Tour Image -->
                        <div class="relative h-48 md:h-64 overflow-hidden">
                            @if($tour->featured_image)
                                <img src="{{ asset('storage/' . $tour->featured_image) }}" 
                                     alt="{{ $tour->title }}" 
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-green-400 to-blue-500 flex items-center justify-center">
                                    <svg class="w-16 h-16 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                            
                            <!-- Duration Badge -->
                            <div class="absolute top-3 right-3">
                                <span class="bg-white/90 backdrop-blur text-gray-800 px-3 py-1 rounded-full text-sm font-semibold">
                                    {{ $tour->itineraries->count() ?: 'Multi' }} {{ $tour->itineraries->count() == 1 ? 'Day' : 'Days' }}
                                </span>
                            </div>

                            <!-- Type Badge -->
                            @if($tour->type)
                            <div class="absolute bottom-3 left-3">
                                <span class="bg-blue-600 text-white px-3 py-1 rounded-full text-xs font-semibold">
                                    {{ $tour->type }}
                                </span>
                            </div>
                            @endif
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
                                <svg class="w-4 h-4 text-green-600 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span class="line-clamp-1">{{ $tour->destinations ?: 'East Africa' }}</span>
                            </div>

                            <!-- Pricing -->
                            <div class="flex items-center justify-between mb-6">
                                <div>
                                    @if($tour->prices && $tour->prices->count() > 0)
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
                                
                                <!-- Rating -->
                                <div class="flex items-center">
                                    <div class="flex text-yellow-400 mr-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
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
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($tours->hasPages())
                <div class="flex justify-center">
                    <div class="bg-white rounded-lg shadow-sm">
                        {{ $tours->links() }}
                    </div>
                </div>
                @endif
            @else
                <!-- No Tours Available -->
                <div class="text-center py-16">
                    <svg class="w-24 h-24 text-gray-300 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <h3 class="text-2xl font-bold text-gray-600 mb-4">No {{ ucfirst($category) }} Tours Available Yet</h3>
                    <p class="text-gray-500 mb-8 max-w-2xl mx-auto">
                        We're currently developing amazing {{ $category }} safari experiences. 
                        In the meantime, check out our other tour categories or contact us for custom {{ $category }} safari arrangements.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('tours.index') }}" 
                           class="bg-green-600 text-white px-8 py-3 rounded-lg hover:bg-green-700 transition-colors font-semibold">
                            Browse All Tours
                        </a>
                        <a href="{{ route('contact') }}" 
                           class="border-2 border-green-600 text-green-600 hover:bg-green-600 hover:text-white px-8 py-3 rounded-lg transition-colors font-semibold">
                            Request Custom {{ ucfirst($category) }} Safari
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- Call to Action -->
    <section class="bg-gradient-to-r from-green-600 to-blue-600 py-16">
        <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                Ready for Your {{ ucfirst($category) }} Safari Adventure?
            </h2>
            <p class="text-xl text-green-100 mb-8 max-w-2xl mx-auto">
                Join us for an unforgettable {{ $category }} safari experience. 
                Our expert team is ready to help you plan the perfect {{ $category }} adventure in East Africa.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('booking.create') }}" 
                   class="bg-white text-green-600 px-8 py-4 rounded-lg font-bold hover:bg-gray-100 transition-all duration-300 transform hover:scale-105">
                    Book {{ ucfirst($category) }} Safari
                </a>
                <a href="{{ route('contact') }}" 
                   class="border-2 border-white text-white px-8 py-4 rounded-lg font-bold hover:bg-white hover:text-green-600 transition-all duration-300">
                    Get Custom Quote
                </a>
                <a href="https://wa.me/256700000000" 
                   class="bg-green-500 text-white px-8 py-4 rounded-lg font-bold hover:bg-green-400 transition-all duration-300 flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                    </svg>
                    WhatsApp Us
                </a>
            </div>
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
</style>
@endpush

@push('scripts')
<script>
function quickBook(tourSlug) {
    window.location.href = `/tours/${tourSlug}#booking`;
}
</script>
@endpush
@endsection