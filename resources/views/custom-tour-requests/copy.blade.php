@extends('layouts.app')

@section('title', 'Plan Your Custom Safari Tour')

@section('content')
<div class="bg-gradient-to-br from-green-50 to-blue-50 py-12">
    <div class="container mx-auto px-4">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">
                <i class="fas fa-map-marked-alt text-green-600 mr-3"></i>
                Plan Your Dream Safari
            </h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Tell us about your dream trip and we'll create a personalized itinerary just for you
            </p>
        </div>

        <form action="{{ route('custom-tour-requests.store') }}" method="POST" id="customTourForm" class="max-w-5xl mx-auto">
            @csrf

            <!-- Progress Steps -->
            <div class="mb-12">
                <div class="flex justify-between items-center">
                    <div class="step-indicator active" data-step="1">
                        <div class="step-number">1</div>
                        <div class="step-label">Contact Info</div>
                    </div>
                    <div class="step-line"></div>
                    <div class="step-indicator" data-step="2">
                        <div class="step-number">2</div>
                        <div class="step-label">Travel Details</div>
                    </div>
                    <div class="step-line"></div>
                    <div class="step-indicator" data-step="3">
                        <div class="step-number">3</div>
                        <div class="step-label">Destinations</div>
                    </div>
                    <div class="step-line"></div>
                    <div class="step-indicator" data-step="4">
                        <div class="step-number">4</div>
                        <div class="step-label">Activities</div>
                    </div>
                    <div class="step-line"></div>
                    <div class="step-indicator" data-step="5">
                        <div class="step-number">5</div>
                        <div class="step-label">Preferences</div>
                    </div>
                </div>
            </div>

            <!-- Step 1: Contact Information -->
            <div class="form-step active" id="step-1">
                <div class="bg-white rounded-2xl shadow-lg p-8 md:p-12">
                    <h2 class="text-3xl font-bold text-gray-800 mb-6 flex items-center">
                        <i class="fas fa-user-circle text-green-600 mr-3"></i>
                        Contact Information
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Full Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('name') border-red-500 @enderror"
                                   value="{{ old('name') }}"
                                   placeholder="John Doe">
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Email Address <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('email') border-red-500 @enderror"
                                   value="{{ old('email') }}"
                                   placeholder="john@example.com">
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Phone Number
                            </label>
                            <input type="tel" name="phone"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   value="{{ old('phone') }}"
                                   placeholder="+1 234 567 8900">
                        </div>

<div class="relative">
    <label class="block text-sm font-semibold text-gray-700 mb-2">
        Country of Residence
    </label>
    <input type="text" 
           name="country" 
           id="countryInput"
           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
           placeholder="Start typing your country..."
           value="{{ old('country') }}"
           autocomplete="off">
    
    <!-- Dropdown suggestions -->
    <div id="countryDropdown" class="hidden absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-y-auto">
        <!-- Results will be populated here -->
    </div>
</div>

@push('scripts')
<script>
    const countries = @json($countries->pluck('name', 'flag_icon'));
    const countryInput = document.getElementById('countryInput');
    const countryDropdown = document.getElementById('countryDropdown');
    
    // Convert to array for easier filtering
    const countriesArray = @json($countries->map(function($country) {
        return [
            'name' => $country->name,
            'flag' => $country->flag_icon
        ];
    })->values());

    countryInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase().trim();
        
        if (searchTerm.length === 0) {
            countryDropdown.classList.add('hidden');
            return;
        }
        
        // Filter countries
        const filtered = countriesArray.filter(country => 
            country.name.toLowerCase().includes(searchTerm)
        );
        
        if (filtered.length === 0) {
            countryDropdown.classList.add('hidden');
            return;
        }
        
        // Display results
        countryDropdown.innerHTML = filtered.map(country => `
            <div class="px-4 py-2 hover:bg-green-50 cursor-pointer country-option" data-country="${country.name}">
                <span class="mr-2">${country.flag}</span>
                <span>${country.name}</span>
            </div>
        `).join('');
        
        countryDropdown.classList.remove('hidden');
        
        // Add click handlers
        document.querySelectorAll('.country-option').forEach(option => {
            option.addEventListener('click', function() {
                countryInput.value = this.dataset.country;
                countryDropdown.classList.add('hidden');
            });
        });
    });
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!countryInput.contains(e.target) && !countryDropdown.contains(e.target)) {
            countryDropdown.classList.add('hidden');
        }
    });
    
    // Handle keyboard navigation
    countryInput.addEventListener('keydown', function(e) {
        const options = countryDropdown.querySelectorAll('.country-option');
        if (options.length === 0) return;
        
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            options[0].focus();
        } else if (e.key === 'Escape') {
            countryDropdown.classList.add('hidden');
        }
    });
</script>
@endpush

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Preferred Language
                            </label>
                            <select name="language"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <option value="">Select Language</option>
                                <option value="English" {{ old('language') == 'English' ? 'selected' : '' }}>English</option>
                                <option value="French" {{ old('language') == 'French' ? 'selected' : '' }}>Français</option>
                                <option value="Spanish" {{ old('language') == 'Spanish' ? 'selected' : '' }}>Español</option>
                                <option value="German" {{ old('language') == 'German' ? 'selected' : '' }}>Deutsch</option>
                                <option value="Italian" {{ old('language') == 'Italian' ? 'selected' : '' }}>Italiano</option>
                                <option value="Chinese" {{ old('language') == 'Chinese' ? 'selected' : '' }}>中文</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end mt-8">
                        <button type="button" class="next-btn bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg font-semibold transition shadow-md">
                            Next <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Step 2: Travel Details -->
            <div class="form-step" id="step-2">
                <div class="bg-white rounded-2xl shadow-lg p-8 md:p-12">
                    <h2 class="text-3xl font-bold text-gray-800 mb-6 flex items-center">
                        <i class="fas fa-calendar-alt text-blue-600 mr-3"></i>
                        Travel Details
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Preferred Start Date
                            </label>
                            <input type="date" name="travel_date_from"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                                   value="{{ old('travel_date_from') }}"
                                   min="{{ date('Y-m-d') }}">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Preferred End Date
                            </label>
                            <input type="date" name="travel_date_to"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                                   value="{{ old('travel_date_to') }}"
                                   min="{{ date('Y-m-d') }}">
                        </div>

                        <div class="md:col-span-2">
                            <label class="flex items-center space-x-3 cursor-pointer">
                                <input type="checkbox" name="flexible_dates" value="1"
                                       class="w-5 h-5 rounded border-gray-300 text-green-600 focus:ring-green-500"
                                       {{ old('flexible_dates') ? 'checked' : '' }}>
                                <span class="text-sm font-medium text-gray-700">
                                    My dates are flexible (±3 days)
                                </span>
                            </label>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Approximate Duration
                            </label>
                            <select name="duration"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                                <option value="">Select Duration</option>
                                <option value="3-5 days" {{ old('duration') == '3-5 days' ? 'selected' : '' }}>3-5 days</option>
                                <option value="6-9 days" {{ old('duration') == '6-9 days' ? 'selected' : '' }}>6-9 days</option>
                                <option value="10-14 days" {{ old('duration') == '10-14 days' ? 'selected' : '' }}>10-14 days</option>
                                <option value="15+ days" {{ old('duration') == '15+ days' ? 'selected' : '' }}>15+ days</option>
                                <option value="Flexible" {{ old('duration') == 'Flexible' ? 'selected' : '' }}>Flexible</option>
                            </select>
                        </div>

                        <div></div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Number of Adults <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="adults_count" min="1" max="50" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                                   value="{{ old('adults_count', 2) }}">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Number of Children (2-12 years)
                            </label>
                            <input type="number" name="children_count" min="0" max="50"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                                   value="{{ old('children_count', 0) }}">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Number of Infants (Under 2 years)
                            </label>
                            <input type="number" name="infants_count" min="0" max="50"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                                   value="{{ old('infants_count', 0) }}">
                        </div>
                    </div>

                    <div class="flex justify-between mt-8">
                        <button type="button" class="prev-btn bg-gray-500 hover:bg-gray-600 text-white px-8 py-3 rounded-lg font-semibold transition">
                            <i class="fas fa-arrow-left mr-2"></i> Previous
                        </button>
                        <button type="button" class="next-btn bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg font-semibold transition shadow-md">
                            Next <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Step 3: Select Destinations -->
            <div class="form-step" id="step-3">
                <div class="bg-white rounded-2xl shadow-lg p-8 md:p-12">
                    <h2 class="text-3xl font-bold text-gray-800 mb-2 flex items-center">
                        <i class="fas fa-map-marked-alt text-purple-600 mr-3"></i>
                        Choose Your Destinations
                    </h2>
                    <p class="text-gray-600 mb-8">Select all the places you'd like to visit. You can choose destinations from multiple countries.</p>

                    <!-- Search & Filter -->
                    <div class="mb-6">
                        <div class="flex flex-col md:flex-row gap-4">
                            <div class="flex-1">
                                <input type="text" id="destination-search" 
                                       placeholder="🔍 Search destinations..."
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                            </div>
                            <div class="md:w-64">
                                <select id="country-filter" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                                    <option value="">All Countries</option>
                                    @foreach($countries as $country)
                                        @if($country->destinations->count() > 0)
                                        <option value="{{ $country->id }}">{{ $country->flag_icon }} {{ $country->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Selected Destinations Summary -->
                    <div id="selected-destinations-summary" class="mb-6 hidden">
                        <div class="bg-green-50 border-2 border-green-300 rounded-lg p-4">
                            <p class="font-semibold text-green-800 mb-3 flex items-center">
                                <i class="fas fa-check-circle mr-2"></i>
                                <span id="selected-count">0</span> Destination(s) Selected
                            </p>
                            <div id="selected-destinations-list" class="flex flex-wrap gap-2"></div>
                        </div>
                    </div>

                    <!-- Destinations List (Checkbox Style) -->
                    <div id="destinations-container" class="space-y-4 max-h-[500px] overflow-y-auto pr-2">
                        @foreach($countries as $country)
                            @if($country->destinations->count() > 0)
                            <div class="country-group" data-country="{{ $country->id }}">
                                <h3 class="text-lg font-bold text-gray-700 mb-3 flex items-center sticky top-0 bg-white py-2 z-10">
                                    {{ $country->flag_icon }} {{ $country->name }}
                                </h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 mb-6">
                                    @foreach($country->destinations as $destination)
                                    <label class="destination-item flex items-start p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-green-400 hover:bg-green-50 transition-all duration-200"
                                           data-country="{{ $country->id }}"
                                           data-name="{{ strtolower($destination->name) }}">
                                        <input type="checkbox" 
                                               name="destinations[]" 
                                               value="{{ $destination->id }}"
                                               class="destination-checkbox mt-1 w-5 h-5 rounded border-gray-300 text-green-600 focus:ring-green-500 flex-shrink-0"
                                               {{ in_array($destination->id, old('destinations', [])) ? 'checked' : '' }}>
                                        
                                        <div class="ml-3 flex-1">
                                            <div class="flex items-start justify-between">
                                                <div class="flex-1">
                                                    <h4 class="font-bold text-gray-800 text-sm">{{ $destination->name }}</h4>
                                                    <p class="text-xs text-gray-600 mt-1 line-clamp-2">{{ Str::limit($destination->description, 100) }}</p>
                                                </div>
                                                @if($destination->image)
                                                <div class="ml-2 flex-shrink-0">
                                                    <img src="{{ asset('storage/' . $destination->image) }}" 
                                                         alt="{{ $destination->name }}"
                                                         class="w-12 h-12 rounded object-cover">
                                                </div>
                                                @endif
                                            </div>
                                            @if($destination->type)
                                            <span class="inline-block mt-2 text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded">
                                                <i class="fas fa-tag mr-1"></i>{{ $destination->type }}
                                            </span>
                                            @endif
                                        </div>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        @endforeach
                    </div>

                    <!-- No results message -->
                    <div id="no-destinations-message" class="hidden text-center py-8">
                        <i class="fas fa-search text-gray-400 text-4xl mb-3"></i>
                        <p class="text-gray-600">No destinations found matching your search.</p>
                    </div>

                    <div class="flex flex-col sm:flex-row justify-between gap-4 mt-8">
                        <button type="button" class="prev-btn bg-gray-500 hover:bg-gray-600 text-white px-8 py-3 rounded-lg font-semibold transition order-2 sm:order-1">
                            <i class="fas fa-arrow-left mr-2"></i> Previous
                        </button>
                        <button type="button" class="next-btn bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg font-semibold transition shadow-md order-1 sm:order-2">
                            Next <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Step 4: Select Activities -->
            <div class="form-step" id="step-4">
                <div class="bg-white rounded-2xl shadow-lg p-8 md:p-12">
                    <h2 class="text-3xl font-bold text-gray-800 mb-2 flex items-center">
                        <i class="fas fa-hiking text-orange-600 mr-3"></i>
                        Choose Your Activities
                    </h2>
                    <p class="text-gray-600 mb-8">Select all the activities you'd like to experience during your safari.</p>

                    <!-- Search & Filter -->
                    <div class="mb-6">
                        <div class="flex flex-col md:flex-row gap-4">
                            <div class="flex-1">
                                <input type="text" id="activity-search" 
                                       placeholder="🔍 Search activities..."
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                            </div>
                            <div class="md:w-64">
                                <select id="category-filter" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                                    <option value="">All Categories</option>
                                    @foreach($activityCategories as $category)
                                        @if($category->activities->count() > 0)
                                        <option value="{{ $category->id }}">
                                            @if($category->icon)<i class="{{ $category->icon }} mr-2"></i>@endif
                                            {{ $category->name }}
                                        </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Selected Activities Summary -->
                    <div id="selected-activities-summary" class="mb-6 hidden">
                        <div class="bg-orange-50 border-2 border-orange-300 rounded-lg p-4">
                            <p class="font-semibold text-orange-800 mb-3 flex items-center">
                                <i class="fas fa-check-circle mr-2"></i>
                                <span id="selected-activities-count">0</span> Activity/Activities Selected
                            </p>
                            <div id="selected-activities-list" class="flex flex-wrap gap-2"></div>
                        </div>
                    </div>

                    <!-- Activities List (Checkbox Style) -->
                    <div id="activities-container" class="space-y-4 max-h-[500px] overflow-y-auto pr-2">
                        @foreach($activityCategories as $category)
                            @if($category->activities->count() > 0)
                            <div class="category-group" data-category="{{ $category->id }}">
                                <h3 class="text-lg font-bold text-gray-700 mb-3 flex items-center sticky top-0 bg-white py-2 z-10">
                                    @if($category->icon)<i class="{{ $category->icon }} mr-2"></i>@endif
                                    {{ $category->name }}
                                </h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 mb-6">
                                    @foreach($category->activities as $activity)
                                    <label class="activity-item flex items-start p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-orange-400 hover:bg-orange-50 transition-all duration-200"
                                           data-category="{{ $category->id }}"
                                           data-name="{{ strtolower($activity->name) }}">
                                        <input type="checkbox" 
                                               name="activities[]" 
                                               value="{{ $activity->id }}"
                                               class="activity-checkbox mt-1 w-5 h-5 rounded border-gray-300 text-orange-600 focus:ring-orange-500 flex-shrink-0"
                                               {{ in_array($activity->id, old('activities', [])) ? 'checked' : '' }}>
                                        
                                        <div class="ml-3 flex-1">
                                            <div class="flex items-start justify-between">
                                                <div class="flex-1">
                                                    <h4 class="font-bold text-gray-800 text-sm">{{ $activity->name }}</h4>
                                                    <p class="text-xs text-gray-600 mt-1 line-clamp-2">{{ Str::limit($activity->description, 100) }}</p>
                                                </div>
                                                @if($activity->image || $activity->icon)
                                                <div class="ml-2 flex-shrink-0">
                                                    @if($activity->image)
                                                    <img src="{{ asset('storage/' . $activity->image) }}" 
                                                         alt="{{ $activity->name }}"
                                                         class="w-12 h-12 rounded object-cover">
                                                    @elseif($activity->icon)
                                                    <img src="{{ asset('storage/' . $activity->icon) }}" 
                                                         alt="{{ $activity->name }}"
                                                         class="w-12 h-12 rounded object-contain">
                                                    @endif
                                                </div>
                                                @endif
                                            </div>
                                            @if($activity->duration)
                                            <span class="inline-block mt-2 text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded">
                                                <i class="far fa-clock mr-1"></i>{{ $activity->duration }}
                                            </span>
                                            @endif
                                        </div>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        @endforeach
                    </div>

                    <!-- No results message -->
                    <div id="no-activities-message" class="hidden text-center py-8">
                        <i class="fas fa-search text-gray-400 text-4xl mb-3"></i>
                        <p class="text-gray-600">No activities found matching your search.</p>
                    </div>

                    <div class="flex flex-col sm:flex-row justify-between gap-4 mt-8">
                        <button type="button" class="prev-btn bg-gray-500 hover:bg-gray-600 text-white px-8 py-3 rounded-lg font-semibold transition order-2 sm:order-1">
                            <i class="fas fa-arrow-left mr-2"></i> Previous
                        </button>
                        <button type="button" class="next-btn bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg font-semibold transition shadow-md order-1 sm:order-2">
                            Next <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Step 5: Preferences & Requirements -->
            <div class="form-step" id="step-5">
                <div class="bg-white rounded-2xl shadow-lg p-8 md:p-12">
                    <h2 class="text-3xl font-bold text-gray-800 mb-6 flex items-center">
                        <i class="fas fa-sliders-h text-indigo-600 mr-3"></i>
                        Preferences & Special Requirements
                    </h2>

                    <div class="space-y-6">
                        <!-- Budget Category -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                Budget Category
                            </label>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <label class="budget-option border-2 border-gray-200 rounded-lg p-4 cursor-pointer hover:border-green-500 transition">
                                    <input type="radio" name="budget_category" value="budget" class="hidden"
                                           {{ old('budget_category') == 'budget' ? 'checked' : '' }}>
                                    <div class="text-center">
                                        <i class="fas fa-dollar-sign text-3xl text-blue-600 mb-2"></i>
                                        <h4 class="font-bold text-gray-800">Budget</h4>
                                        <p class="text-sm text-gray-600">$100-200/day</p>
                                    </div>
                                </label>

                                <label class="budget-option border-2 border-gray-200 rounded-lg p-4 cursor-pointer hover:border-green-500 transition">
                                    <input type="radio" name="budget_category" value="mid-range" class="hidden"
                                           {{ old('budget_category') == 'mid-range' ? 'checked' : '' }}>
                                    <div class="text-center">
                                        <i class="fas fa-star text-3xl text-yellow-600 mb-2"></i>
                                        <h4 class="font-bold text-gray-800">Mid-Range</h4>
                                        <p class="text-sm text-gray-600">$200-400/day</p>
                                    </div>
                                </label>

                                <label class="budget-option border-2 border-gray-200 rounded-lg p-4 cursor-pointer hover:border-green-500 transition">
                                    <input type="radio" name="budget_category" value="luxury" class="hidden"
                                           {{ old('budget_category') == 'luxury' ? 'checked' : '' }}>
                                    <div class="text-center">
                                        <i class="fas fa-gem text-3xl text-purple-600 mb-2"></i>
                                        <h4 class="font-bold text-gray-800">Luxury</h4>
                                        <p class="text-sm text-gray-600">$400+/day</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Approximate Budget -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Approximate Total Budget (Optional)
                            </label>
                            <input type="text" name="approximate_budget"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                                   value="{{ old('approximate_budget') }}"
                                   placeholder="e.g., $5000 - $8000">
                        </div>

                        <!-- Accommodation Preference -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Accommodation Preference
                            </label>
                            <select name="accommodation_preference"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                                <option value="">No Preference</option>
                                <option value="Camping" {{ old('accommodation_preference') == 'Camping' ? 'selected' : '' }}>Camping</option>
                                <option value="Budget Lodges" {{ old('accommodation_preference') == 'Budget Lodges' ? 'selected' : '' }}>Budget Lodges</option>
                                <option value="Mid-Range Lodges" {{ old('accommodation_preference') == 'Mid-Range Lodges' ? 'selected' : '' }}>Mid-Range Lodges</option>
                                <option value="Luxury Lodges" {{ old('accommodation_preference') == 'Luxury Lodges' ? 'selected' : '' }}>Luxury Lodges</option>
                                <option value="Mix of Options" {{ old('accommodation_preference') == 'Mix of Options' ? 'selected' : '' }}>Mix of Options</option>
                            </select>
                        </div>

                        <!-- Special Requirements -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                Special Requirements (Check all that apply)
                            </label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <label class="flex items-center space-x-3 p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-green-400 hover:bg-green-50 transition">
                                    <input type="checkbox" name="special_requirements[]" value="Wheelchair Accessible"
                                           class="w-5 h-5 rounded border-gray-300 text-green-600 focus:ring-green-500"
                                           {{ in_array('Wheelchair Accessible', old('special_requirements', [])) ? 'checked' : '' }}>
                                    <span class="text-sm">Wheelchair Accessible</span>
                                </label>
                                <label class="flex items-center space-x-3 p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-green-400 hover:bg-green-50 transition">
                                    <input type="checkbox" name="special_requirements[]" value="Family Friendly"
                                           class="w-5 h-5 rounded border-gray-300 text-green-600 focus:ring-green-500"
                                           {{ in_array('Family Friendly', old('special_requirements', [])) ? 'checked' : '' }}>
                                    <span class="text-sm">Family Friendly</span>
                                </label>
                                <label class="flex items-center space-x-3 p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-green-400 hover:bg-green-50 transition">
                                    <input type="checkbox" name="special_requirements[]" value="Solo Traveler"
                                           class="w-5 h-5 rounded border-gray-300 text-green-600 focus:ring-green-500"
                                           {{ in_array('Solo Traveler', old('special_requirements', [])) ? 'checked' : '' }}>
                                    <span class="text-sm">Solo Traveler</span>
                                </label>
                                <label class="flex items-center space-x-3 p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-green-400 hover:bg-green-50 transition">
                                    <input type="checkbox" name="special_requirements[]" value="Honeymoon Package"
                                           class="w-5 h-5 rounded border-gray-300 text-green-600 focus:ring-green-500"
                                           {{ in_array('Honeymoon Package', old('special_requirements', [])) ? 'checked' : '' }}>
                                    <span class="text-sm">Honeymoon Package</span>
                                </label>
                                <label class="flex items-center space-x-3 p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-green-400 hover:bg-green-50 transition">
                                    <input type="checkbox" name="special_requirements[]" value="Photography Focus"
                                           class="w-5 h-5 rounded border-gray-300 text-green-600 focus:ring-green-500"
                                           {{ in_array('Photography Focus', old('special_requirements', [])) ? 'checked' : '' }}>
                                    <span class="text-sm">Photography Focus</span>
                                </label>
                                <label class="flex items-center space-x-3 p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-green-400 hover:bg-green-50 transition">
                                    <input type="checkbox" name="special_requirements[]" value="Private Tour"
                                           class="w-5 h-5 rounded border-gray-300 text-green-600 focus:ring-green-500"
                                           {{ in_array('Private Tour', old('special_requirements', [])) ? 'checked' : '' }}>
                                    <span class="text-sm">Private Tour</span>
                                </label>
                            </div>
                        </div>

                        <!-- Dietary Restrictions -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Dietary Restrictions
                            </label>
                            <textarea name="dietary_restrictions" rows="2"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                                      placeholder="e.g., Vegetarian, Vegan, Gluten-free, Allergies...">{{ old('dietary_restrictions') }}</textarea>
                        </div>

                        <!-- Medical Conditions -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Medical Conditions / Accessibility Needs
                            </label>
                            <textarea name="medical_conditions" rows="2"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                                      placeholder="Please let us know if you have any medical conditions or accessibility needs we should be aware of...">{{ old('medical_conditions') }}</textarea>
                        </div>

                        <!-- Special Requests -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Additional Comments / Special Requests
                            </label>
                            <textarea name="special_requests" rows="4"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                                      placeholder="Tell us anything else you'd like us to know about your dream safari...">{{ old('special_requests') }}</textarea>
                        </div>

                        <!-- How did you hear about us -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                How did you hear about us?
                            </label>
                            <select name="heard_from"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                                <option value="">Please select</option>
                                <option value="Google Search" {{ old('heard_from') == 'Google Search' ? 'selected' : '' }}>Google Search</option>
                                <option value="Social Media" {{ old('heard_from') == 'Social Media' ? 'selected' : '' }}>Social Media</option>
                                <option value="Friend/Family Referral" {{ old('heard_from') == 'Friend/Family Referral' ? 'selected' : '' }}>Friend/Family Referral</option>
                                <option value="Travel Agent" {{ old('heard_from') == 'Travel Agent' ? 'selected' : '' }}>Travel Agent</option>
                                <option value="Online Advertisement" {{ old('heard_from') == 'Online Advertisement' ? 'selected' : '' }}>Online Advertisement</option>
                                <option value="Travel Blog/Website" {{ old('heard_from') == 'Travel Blog/Website' ? 'selected' : '' }}>Travel Blog/Website</option>
                                <option value="Previous Customer" {{ old('heard_from') == 'Previous Customer' ? 'selected' : '' }}>Previous Customer</option>
                                <option value="Other" {{ old('heard_from') == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row justify-between gap-4 mt-8">
                        <button type="button" class="prev-btn bg-gray-500 hover:bg-gray-600 text-white px-8 py-3 rounded-lg font-semibold transition order-2 sm:order-1">
                            <i class="fas fa-arrow-left mr-2"></i> Previous
                        </button>
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg font-semibold transition shadow-md order-1 sm:order-2">
                            <i class="fas fa-paper-plane mr-2"></i> Submit Request
                        </button>
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/custom-tour-wizard.js') }}"></script>
@endpush

@push('styles')
<style>
    /* Custom styles for the wizard */
    .form-step {
        display: none;
    }
    .form-step.active {
        display: block;
        animation: fadeIn 0.3s;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .step-indicator {
        text-align: center;
        position: relative;
        flex: 1;
    }
    .step-number {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 8px;
        font-weight: bold;
        color: #6b7280;
        transition: all 0.3s;
    }
    .step-indicator.active .step-number {
        background: #10b981;
        color: white;
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }
    .step-indicator.completed .step-number {
        background: #10b981;
        color: white;
    }
    .step-indicator.completed .step-number::after {
        content: '✓';
    }
    .step-label {
        font-size: 0.875rem;
        color: #6b7280;
        font-weight: 500;
    }
    .step-indicator.active .step-label {
        color: #10b981;
        font-weight: 600;
    }
    .step-line {
        flex: 1;
        height: 2px;
        background: #e5e7eb;
        margin: 0 8px;
        align-self: center;
        position: relative;
        top: -20px;
    }

    /* Checkbox item styles */
    .destination-item:has(.destination-checkbox:checked),
    .activity-item:has(.activity-checkbox:checked) {
        border-color: #10b981;
        background: #f0fdf4;
    }

    .budget-option:has(input:checked) {
        border-color: #10b981;
        background: #f0fdf4;
    }

    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Responsive adjustments */
    @media (max-width: 640px) {
        .step-label {
            font-size: 0.75rem;
        }
        .step-number {
            width: 40px;
            height: 40px;
            font-size: 0.875rem;
        }
        .step-line {
            margin: 0 4px;
        }
    }

    /* Scrollbar styling */
    #destinations-container::-webkit-scrollbar,
    #activities-container::-webkit-scrollbar {
        width: 8px;
    }

    #destinations-container::-webkit-scrollbar-track,
    #activities-container::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    #destinations-container::-webkit-scrollbar-thumb,
    #activities-container::-webkit-scrollbar-thumb {
        background: #10b981;
        border-radius: 10px;
    }

    #destinations-container::-webkit-scrollbar-thumb:hover,
    #activities-container::-webkit-scrollbar-thumb:hover {
        background: #059669;
    }

    /* Smooth transitions */
    .destination-item,
    .activity-item {
        transition: all 0.2s ease-in-out;
    }

    .destination-item:hover,
    .activity-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
</style>
@endpush
@endsection