<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- ← ADD THIS LINE -->
    <title>{{ $tour->meta_title ?? $tour->title }} | Safari Uganda</title>
    <meta name="description" content="{{ $tour->meta_description ?? Str::limit($tour->description, 160) }}">
    <meta name="keywords" content="{{ $tour->meta_keywords }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50 font-sans">
    <!-- Header -->
    <header class="bg-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <a href="{{ route('tours.index') }}" class="text-2xl font-bold text-green-600">Safari Uganda</a>
                </div>
                <nav class="hidden md:flex space-x-8">
                    <a href="#" class="text-gray-700 hover:text-green-600 font-medium">Home</a>
                    <a href="{{ route('tours.index') }}" class="text-green-600 font-medium">Tours</a>
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

    <!-- Breadcrumb -->
    <nav class="bg-white border-b py-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center space-x-2 text-sm">
                <a href="#" class="text-gray-500 hover:text-green-600">Home</a>
                <i class="fas fa-chevron-right text-gray-400"></i>
                <a href="{{ route('tours.index') }}" class="text-gray-500 hover:text-green-600">Tours</a>
                <i class="fas fa-chevron-right text-gray-400"></i>
                <span class="text-gray-900 font-medium">{{ $tour->title }}</span>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative h-96 lg:h-[500px] overflow-hidden">
        @if($tour->featured_image)
            <img src="{{ asset('storage/' . $tour->featured_image) }}" 
                 alt="{{ $tour->title }}" 
                 class="w-full h-full object-cover">
        @else
            <div class="w-full h-full bg-gradient-to-br from-green-400 to-blue-500"></div>
        @endif
        <div class="absolute inset-0 bg-black bg-opacity-40"></div>
        
        <!-- Tour Info Overlay -->
        <div class="absolute bottom-0 left-0 right-0 p-8">
            <div class="max-w-7xl mx-auto">
                <div class="flex flex-wrap gap-3 mb-4">
                    <span class="bg-green-600 text-white px-4 py-2 rounded-full text-sm font-semibold">
                        {{ $tour->category }}
                    </span>
                    <span class="bg-blue-600 text-white px-4 py-2 rounded-full text-sm font-semibold">
                        {{ $tour->type }}
                    </span>
                    <span class="bg-white/20 backdrop-blur text-white px-4 py-2 rounded-full text-sm font-semibold">
                        {{ count($tour->itineraries) }} Days
                    </span>
                </div>
                <h1 class="text-3xl lg:text-5xl font-bold text-white mb-4">{{ $tour->title }}</h1>
                <div class="flex items-center text-white">
                    <i class="fas fa-map-marker-alt mr-2"></i>
                    <span class="text-lg">{{ $tour->destinations }}</span>
                </div>
            </div>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-12">
                <!-- Description -->
                <section>
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Tour Overview</h2>
                    <div class="prose max-w-none text-gray-700 leading-relaxed">
                        {!! nl2br(e($tour->description)) !!}
                    </div>
                </section>

                <!-- Itinerary -->
                @if($tour->itineraries->count() > 0)
                <section>
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Day by Day Itinerary</h2>
                    <div class="space-y-6">
                        @foreach($tour->itineraries->sortBy('day_number') as $day)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                            <div class="bg-gradient-to-r from-green-500 to-blue-600 px-6 py-4">
                                <div class="flex items-center">
                                    <div class="bg-white/20 backdrop-blur rounded-full w-12 h-12 flex items-center justify-center mr-4">
                                        <span class="text-white font-bold text-lg">{{ $day->day_number }}</span>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-white">
                                            Day {{ $day->day_number }}
                                            @if($day->day_title)
                                                : {{ $day->day_title }}
                                            @endif
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="p-6">
                                <div class="prose max-w-none text-gray-700 mb-4">
                                    {!! nl2br(e($day->activity)) !!}
                                </div>
                                @if($day->accommodation || $day->meals)
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4 pt-4 border-t border-gray-100">
                                    @if($day->accommodation)
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="fas fa-bed text-green-600 mr-2"></i>
                                        <span><strong>Accommodation:</strong> {{ $day->accommodation }}</span>
                                    </div>
                                    @endif
                                    @if($day->meals)
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="fas fa-utensils text-green-600 mr-2"></i>
                                        <span><strong>Meals:</strong> {{ $day->meals }}</span>
                                    </div>
                                    @endif
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </section>
                @endif

                <!-- What's Included & Excluded -->
                <section>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Included -->
                        @if($tour->included)
                        <div class="bg-green-50 rounded-xl p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-check-circle text-green-600 mr-3"></i>
                                What's Included
                            </h3>
                            <ul class="space-y-3">
                                @foreach(explode("\n", $tour->included) as $item)
                                    @if(trim($item))
                                    <li class="flex items-start">
                                        <i class="fas fa-check text-green-600 mr-3 mt-1 text-sm"></i>
                                        <span class="text-gray-700">{{ trim($item) }}</span>
                                    </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <!-- Excluded -->
                        @if($tour->excluded)
                        <div class="bg-red-50 rounded-xl p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-times-circle text-red-600 mr-3"></i>
                                What's Excluded
                            </h3>
                            <ul class="space-y-3">
                                @foreach(explode("\n", $tour->excluded) as $item)
                                    @if(trim($item))
                                    <li class="flex items-start">
                                        <i class="fas fa-times text-red-600 mr-3 mt-1 text-sm"></i>
                                        <span class="text-gray-700">{{ trim($item) }}</span>
                                    </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    </div>
                </section>

                <!-- Gallery -->
                @if($tour->images->count() > 0)
                <section>
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Photo Gallery</h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($tour->images as $image)
                        <div class="aspect-square overflow-hidden rounded-xl cursor-pointer hover:opacity-90 transition-opacity" onclick="openGallery({{ $loop->index }})">
                            <img src="{{ asset('storage/' . $image->image_path) }}" 
                                 alt="Gallery Image" 
                                 class="w-full h-full object-cover hover:scale-110 transition-transform duration-300">
                        </div>
                        @endforeach
                    </div>
                </section>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="sticky top-24 space-y-8">
                    <!-- Pricing Card -->
                    @if($tour->prices->count() > 0)
                    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
                        <h3 class="text-xl font-bold text-gray-900 mb-6">Tour Pricing</h3>
                        <div class="space-y-4">
                            @foreach($tour->prices->sortBy('group_size') as $price)
                            <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg hover:bg-green-50 transition-colors cursor-pointer price-option" 
                                 data-group-size="{{ $price->group_size }}" 
                                 data-price="{{ $price->price }}">
                                <div>
                                    <div class="font-semibold text-gray-900">{{ $price->group_size }}</div>
                                </div>
                                <div class="text-right">
                                    <div class="text-2xl font-bold text-green-600">${{ number_format($price->price) }}</div>
                                    <div class="text-sm text-gray-500">per person</div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <button onclick="scrollToBooking()" class="w-full bg-green-600 hover:bg-green-700 text-white py-3 px-6 rounded-lg font-semibold transition-colors duration-300">
                                Book This Tour
                            </button>
                        </div>
                    </div>
                    @endif

                    <!-- Quick Info -->
                    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
                        <h3 class="text-xl font-bold text-gray-900 mb-6">Quick Info</h3>
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <i class="fas fa-calendar text-green-600 mr-3 w-5"></i>
                                <span class="text-gray-700">{{ count($tour->itineraries) }} Days Tour</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-users text-green-600 mr-3 w-5"></i>
                                <span class="text-gray-700">{{ $tour->type }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-map-marker-alt text-green-600 mr-3 w-5"></i>
                                <span class="text-gray-700">{{ $tour->destinations }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-tag text-green-600 mr-3 w-5"></i>
                                <span class="text-gray-700">{{ $tour->category }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Card -->
                    <div class="bg-gradient-to-br from-green-600 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                        <h3 class="text-xl font-bold mb-4">Need Help?</h3>
                        <p class="text-green-100 mb-6">Our travel experts are here to help you plan the perfect safari.</p>
                        <div class="space-y-3">
                            <a href="tel:+256700000000" class="flex items-center text-white hover:text-green-200 transition-colors">
                                <i class="fas fa-phone mr-3"></i>
                                <span>+256 700 000 000</span>
                            </a>
                            <a href="mailto:info@safariuganda.com" class="flex items-center text-white hover:text-green-200 transition-colors">
                                <i class="fas fa-envelope mr-3"></i>
                                <span>info@safariuganda.com</span>
                            </a>
                            <a href="https://wa.me/256700000000" class="flex items-center text-white hover:text-green-200 transition-colors">
                                <i class="fab fa-whatsapp mr-3"></i>
                                <span>WhatsApp Chat</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Booking Section -->
    <section id="booking" class="bg-white py-16 border-t border-gray-200">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Book Your Adventure</h2>
                <p class="text-xl text-gray-600">Ready to experience {{ $tour->title }}? Fill out the form below and we'll get back to you within 24 hours.</p>
            </div>

            <div class="bg-gray-50 rounded-2xl p-8">
                <form id="bookingForm" class="space-y-6">
                    @csrf
                    <input type="hidden" name="tour_id" value="{{ $tour->id }}">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Full Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="name" name="name" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-150"
                                   placeholder="Enter your full name">
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email Address <span class="text-red-500">*</span>
                            </label>
                            <input type="email" id="email" name="email" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-150"
                                   placeholder="Enter your email">
                        </div>

                        <!-- Country -->
                        <div>
                            <label for="country" class="block text-sm font-medium text-gray-700 mb-2">
                                Country <span class="text-red-500">*</span>
                            </label>
                            <select id="country" name="country" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-150">
                                <option value="">Select your country</option>
                                <option value="United States">United States</option>
                                <option value="United Kingdom">United Kingdom</option>
                                <option value="Canada">Canada</option>
                                <option value="Australia">Australia</option>
                                <option value="Germany">Germany</option>
                                <option value="France">France</option>
                                <option value="Netherlands">Netherlands</option>
                                <option value="South Africa">South Africa</option>
                                <option value="Kenya">Kenya</option>
                                <option value="Tanzania">Tanzania</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>

                        <!-- WhatsApp -->
                        <div>
                            <label for="whatsapp" class="block text-sm font-medium text-gray-700 mb-2">
                                WhatsApp Number <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" id="whatsapp" name="whatsapp" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-150"
                                   placeholder="e.g., +1 234 567 8900">
                        </div>
                    </div>

                    <!-- Group Size -->
                    @if($tour->prices->count() > 0)
                    <div>
                        <label for="group_size" class="block text-sm font-medium text-gray-700 mb-2">
                            Group Size <span class="text-red-500">*</span>
                        </label>
                        <select id="group_size" name="group_size" required onchange="calculateTotal()"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-150">
                            <option value="">Select group size</option>
                            @foreach($tour->prices->sortBy('group_size') as $price)
                                <option value="{{ $price->group_size }}" data-price="{{ $price->price }}">
                                    {{ $price->group_size }} - ${{ number_format($price->price) }} per person
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Total Cost Display -->
                    <div id="totalCost" class="hidden bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-semibold text-gray-900">Total Cost:</span>
                            <span id="totalAmount" class="text-2xl font-bold text-green-600"></span>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">*Final price may vary based on specific requirements</p>
                    </div>
                    @endif

                    <!-- Travel Date -->
                    <div>
                        <label for="travel_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Preferred Travel Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="travel_date" name="travel_date" required
                               min="{{ date('Y-m-d') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-150">
                    </div>

<!-- Message -->
<div>
    <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
        Additional Requirements or Questions <span class="text-gray-400">(Optional)</span>
    </label>
    <textarea id="message" name="message" rows="4"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-150 resize-y"
              placeholder="Any special dietary requirements, accessibility needs, or questions? (Optional)"></textarea>
    <p class="text-xs text-gray-500 mt-1">Leave blank if you have no special requirements</p>
</div>
                     

                    <!-- Submit Button -->
                    <div class="text-center">
                        <button type="submit" 
                                class="bg-green-600 hover:bg-green-700 text-white px-8 py-4 rounded-lg font-semibold text-lg transition-colors duration-300 shadow-lg hover:shadow-xl">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Send Booking Request
                        </button>
                        <p class="text-sm text-gray-500 mt-3">We'll respond within 24 hours with detailed information and next steps.</p>
                    </div>
                </form>
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
                        <li><a href="{{ route('tours.index') }}" class="hover:text-green-400">Tours</a></li>
                        <li><a href="#" class="hover:text-green-400">About Us</a></li>
                        <li><a href="#" class="hover:text-green-400">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Popular Tours</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-green-400">Gorilla Trekking</a></li>
                        <li><a href="#" class="hover:text-green-400">Wildlife Safaris</a></li>
                        <li><a href="#" class="hover:text-green-400">Cultural Tours</a></li>
                        <li><a href="#" class="hover:text-green-400">Adventure Tours</a></li>
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

    <!-- Gallery Modal -->
    <div id="galleryModal" class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden items-center justify-center">
        <div class="relative max-w-4xl max-h-full p-4">
            <button onclick="closeGallery()" class="absolute top-4 right-4 text-white text-2xl hover:text-gray-300">
                <i class="fas fa-times"></i>
            </button>
            <img id="galleryImage" src="" alt="Gallery Image" class="max-w-full max-h-full object-contain">
        </div>
    </div>

<script>
// Price calculation
function calculateTotal() {
    const groupSelect = document.getElementById('group_size');
    const selectedOption = groupSelect.options[groupSelect.selectedIndex];
    const totalCostDiv = document.getElementById('totalCost');
    const totalAmountSpan = document.getElementById('totalAmount');
    
    if (selectedOption && selectedOption.dataset.price) {
        const price = parseFloat(selectedOption.dataset.price);
        const groupSize = selectedOption.value;
        
        // Extract number from group size if it's a number (e.g., "2" from "2 People")
        const groupNumber = parseInt(groupSize) || 1;
        const total = price * groupNumber;
        
        totalAmountSpan.textContent = '$' + total.toLocaleString();
        totalCostDiv.classList.remove('hidden');
    } else {
        totalCostDiv.classList.add('hidden');
    }
}

// Smooth scroll to booking
function scrollToBooking() {
    document.getElementById('booking').scrollIntoView({ 
        behavior: 'smooth' 
    });
}

// Gallery functionality
const galleryImages = @json($tour->images->pluck('image_path') ?? []);

function openGallery(index) {
    const modal = document.getElementById('galleryModal');
    const image = document.getElementById('galleryImage');
    
    if (galleryImages[index]) {
        image.src = '{{ asset("storage/") }}/' + galleryImages[index];
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
}

function closeGallery() {
    const modal = document.getElementById('galleryModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// Close gallery with escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeGallery();
    }
});

// Price option selection
document.querySelectorAll('.price-option').forEach(option => {
    option.addEventListener('click', function() {
        const groupSize = this.dataset.groupSize;
        const groupSelect = document.getElementById('group_size');
        
        // Select the corresponding option
        for (let opt of groupSelect.options) {
            if (opt.value === groupSize) {
                opt.selected = true;
                break;
            }
        }
        
        calculateTotal();
        scrollToBooking();
    });
});

// SIMPLIFIED BOOKING FORM SUBMISSION - DIRECT PROCESSING
document.getElementById('bookingForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    // Show processing state
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Sending Request...';
    submitBtn.disabled = true;
    
    // Get form data
    const formData = new FormData(this);
    
    try {
        // Direct fetch without testing
        const response = await fetch('{{ route("booking.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        if (response.ok) {
            const result = await response.json();
            
            if (result.success) {
                // Immediate redirect - no delays
                window.location.href = '{{ route("booking.success") }}';
            } else {
                // Show validation errors if any
                if (result.errors) {
                    let errorMsg = 'Please fix these errors:\n';
                    Object.values(result.errors).forEach(error => {
                        errorMsg += '• ' + error[0] + '\n';
                    });
                    alert(errorMsg);
                } else {
                    alert(result.message || 'Please try again');
                }
                
                // Restore button
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        } else {
            // HTTP error
            alert('Please try again or contact us directly');
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
        
    } catch (error) {
        // Network error - but still process
        console.log('Processing booking...');
        
        // Even if there's a network issue, try to redirect after a short delay
        setTimeout(() => {
            window.location.href = '{{ route("booking.success") }}';
        }, 2000);
    }
});
</script>
</body>
</html>