@extends('layouts.app')

@section('title', 'Plan Your Custom Safari Tour')

@section('content')
<div class="safari-bg py-8 sm:py-12">
    <div class="container mx-auto px-3 sm:px-4 lg:px-6">

        <!-- Header -->
        <div class="text-center mb-8 sm:mb-12 px-2">
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-800 mb-3 sm:mb-4 leading-tight">
                <i class="fas fa-map-marked-alt text-green-600 mr-2 sm:mr-3"></i>
                <span class="block sm:inline">Plan Your Dream Safari</span>
            </h1>
            <p class="text-base sm:text-xl text-gray-600 max-w-3xl mx-auto">
                Tell us about your dream trip and we'll create a personalized itinerary just for you
            </p>
        </div>

        <form action="{{ route('custom-tour-requests.store') }}" method="POST" id="customTourForm" class="max-w-5xl mx-auto">
            @csrf

            <!-- ===== PROGRESS STEPS ===== -->
            <div class="mb-8 sm:mb-12 overflow-x-auto">
                <div class="flex justify-between items-center min-w-[320px] px-1">

                    <div class="step-indicator active" data-step="1">
                        <div class="step-number">1</div>
                        <div class="step-label">Contact</div>
                    </div>
                    <div class="step-line"></div>

                    <div class="step-indicator" data-step="2">
                        <div class="step-number">2</div>
                        <div class="step-label">Travel</div>
                    </div>
                    <div class="step-line"></div>

                    <div class="step-indicator" data-step="3">
                        <div class="step-number">3</div>
                        <div class="step-label">Places</div>
                    </div>
                    <div class="step-line"></div>

                    <div class="step-indicator" data-step="4">
                        <div class="step-number">4</div>
                        <div class="step-label">Activities</div>
                    </div>
                    <div class="step-line"></div>

                    <div class="step-indicator" data-step="5">
                        <div class="step-number">5</div>
                        <div class="step-label">Prefs</div>
                    </div>

                </div>
            </div>

            <!-- ===== STEP 1: CONTACT INFO ===== -->
            <div class="form-step active" id="step-1">
                <div class="card-panel">
                    <h2 class="step-title">
                        <i class="fas fa-user-circle text-green-600 mr-2 sm:mr-3"></i>
                        Contact Information
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">

                        <div class="form-group">
                            <label class="form-label">Full Name <span class="text-red-500">*</span></label>
                            <input type="text" name="name" required
                                   class="form-input @error('name') border-red-500 @enderror"
                                   value="{{ old('name') }}"
                                   placeholder="John Doe">
                            @error('name')<p class="form-error">{{ $message }}</p>@enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Email Address <span class="text-red-500">*</span></label>
                            <input type="email" name="email" required
                                   class="form-input @error('email') border-red-500 @enderror"
                                   value="{{ old('email') }}"
                                   placeholder="john@example.com">
                            @error('email')<p class="form-error">{{ $message }}</p>@enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Phone Number</label>
                            <input type="tel" name="phone"
                                   class="form-input"
                                   value="{{ old('phone') }}"
                                   placeholder="+1 234 567 8900">
                        </div>

                        <div class="form-group relative">
                            <label class="form-label">Country of Residence</label>
                            <input type="text"
                                   name="country"
                                   id="countryInput"
                                   class="form-input"
                                   placeholder="Start typing your country..."
                                   value="{{ old('country') }}"
                                   autocomplete="off">
                            <div id="countryDropdown" class="hidden absolute z-20 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-48 sm:max-h-60 overflow-y-auto"></div>
                        </div>

                        <div class="form-group sm:col-span-2 md:col-span-1">
                            <label class="form-label">Preferred Language</label>
                            <select name="language" class="form-input">
                                <option value="">Select Language</option>
                                <option value="English"  {{ old('language') == 'English'  ? 'selected' : '' }}>English</option>
                                <option value="French"   {{ old('language') == 'French'   ? 'selected' : '' }}>Français</option>
                                <option value="Spanish"  {{ old('language') == 'Spanish'  ? 'selected' : '' }}>Español</option>
                                <option value="German"   {{ old('language') == 'German'   ? 'selected' : '' }}>Deutsch</option>
                                <option value="Italian"  {{ old('language') == 'Italian'  ? 'selected' : '' }}>Italiano</option>
                                <option value="Chinese"  {{ old('language') == 'Chinese'  ? 'selected' : '' }}>中文</option>
                            </select>
                        </div>

                    </div>

                    <div class="step-footer justify-end">
                        <button type="button" class="next-btn btn-primary">
                            Next <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- ===== STEP 2: TRAVEL DETAILS ===== -->
            <div class="form-step" id="step-2">
                <div class="card-panel">
                    <h2 class="step-title">
                        <i class="fas fa-calendar-alt text-blue-600 mr-2 sm:mr-3"></i>
                        Travel Details
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">

                        <div class="form-group">
                            <label class="form-label">Preferred Start Date</label>
                            <input type="date" name="travel_date_from" id="travel_date_from"
                                   class="form-input"
                                   value="{{ old('travel_date_from') }}"
                                   min="{{ date('Y-m-d') }}">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Preferred End Date</label>
                            <input type="date" name="travel_date_to" id="travel_date_to"
                                   class="form-input bg-gray-100 cursor-not-allowed"
                                   value="{{ old('travel_date_to') }}"
                                   readonly>
                            <p class="text-xs text-gray-500 mt-1">Calculated from start date + number of days.</p>
                        </div>

                        <div class="form-group sm:col-span-2">
                            <label class="flex items-center space-x-3 cursor-pointer">
                                <input type="checkbox" name="flexible_dates" value="1"
                                       class="w-5 h-5 rounded border-gray-300 text-green-600 focus:ring-green-500 flex-shrink-0"
                                       {{ old('flexible_dates') ? 'checked' : '' }}>
                                <span class="text-sm font-medium text-gray-700">My dates are flexible (±3 days)</span>
                            </label>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Number of Days</label>
                            <input type="number" name="duration_days" id="duration_days"
                                   min="1" max="60"
                                   class="form-input"
                                   value="{{ old('duration_days') }}"
                                   placeholder="e.g., 7">
                            <p class="text-xs text-gray-500 mt-1">Total number of days for your safari.</p>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Number of Adults <span class="text-red-500">*</span></label>
                            <input type="number" name="adults_count" min="1" max="50" required
                                   class="form-input"
                                   value="{{ old('adults_count', 2) }}">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Children <span class="text-gray-400 font-normal">(2–12 yrs)</span></label>
                            <input type="number" name="children_count" min="0" max="50"
                                   class="form-input"
                                   value="{{ old('children_count', 0) }}">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Infants <span class="text-gray-400 font-normal">(Under 2)</span></label>
                            <input type="number" name="infants_count" min="0" max="50"
                                   class="form-input"
                                   value="{{ old('infants_count', 0) }}">
                        </div>

                    </div>

                    <div class="step-footer">
                        <button type="button" class="prev-btn btn-secondary">
                            <i class="fas fa-arrow-left mr-2"></i> Previous
                        </button>
                        <button type="button" class="next-btn btn-primary">
                            Next <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- ===== STEP 3: DESTINATIONS ===== -->
            <div class="form-step" id="step-3">
                <div class="card-panel">
                    <h2 class="step-title">
                        <i class="fas fa-map-marked-alt text-purple-600 mr-2 sm:mr-3"></i>
                        Choose Your Destinations
                    </h2>
                    <p class="text-sm sm:text-base text-gray-600 mb-6">
                        Select all the places you'd like to visit. You can choose destinations from multiple countries.
                    </p>

                    <!-- Search & Filter -->
                    <div class="mb-5 flex flex-col sm:flex-row gap-3">
                        <div class="flex-1">
                            <input type="text" id="destination-search"
                                   placeholder="🔍 Search destinations..."
                                   class="form-input">
                        </div>
                        <div class="w-full sm:w-56 lg:w-64">
                            <select id="country-filter" class="form-input">
                                <option value="">All Countries</option>
                                @foreach($countries as $country)
                                    @if($country->destinations->count() > 0)
                                    <option value="{{ $country->id }}">{{ $country->flag_icon }} {{ $country->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Selected Destinations Summary -->
                    <div id="selected-destinations-summary" class="mb-5 hidden">
                        <div class="bg-green-50 border-2 border-green-300 rounded-lg p-3 sm:p-4">
                            <p class="font-semibold text-green-800 mb-2 sm:mb-3 flex items-center text-sm sm:text-base">
                                <i class="fas fa-check-circle mr-2"></i>
                                <span id="selected-count">0</span>&nbsp;Destination(s) Selected
                            </p>
                            <div id="selected-destinations-list" class="flex flex-wrap gap-2"></div>
                        </div>
                    </div>

                    <!-- Destinations List -->
                    <div id="destinations-container" class="space-y-4 max-h-[420px] sm:max-h-[500px] overflow-y-auto pr-1 sm:pr-2">
                        @foreach($countries as $country)
                            @if($country->destinations->count() > 0)
                            <div class="country-group" data-country="{{ $country->id }}">
                                <h3 class="text-base sm:text-lg font-bold text-gray-700 mb-2 sm:mb-3 flex items-center sticky top-0 bg-white py-2 z-10">
                                    {{ $country->flag_icon }} {{ $country->name }}
                                </h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2 sm:gap-3 mb-4 sm:mb-6">
                                    @foreach($country->destinations as $destination)
                                    <label class="destination-item flex items-start p-2.5 sm:p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-green-400 hover:bg-green-50 transition-all duration-200"
                                           data-country="{{ $country->id }}"
                                           data-name="{{ strtolower($destination->name) }}">
                                        <input type="checkbox"
                                               name="destinations[]"
                                               value="{{ $destination->id }}"
                                               class="destination-checkbox mt-0.5 sm:mt-1 w-4 h-4 sm:w-5 sm:h-5 rounded border-gray-300 text-green-600 focus:ring-green-500 flex-shrink-0"
                                               {{ in_array($destination->id, old('destinations', [])) ? 'checked' : '' }}>
                                        <div class="ml-2 sm:ml-3 flex-1 min-w-0">
                                            <div class="flex items-start justify-between gap-1">
                                                <div class="flex-1 min-w-0">
                                                    <h4 class="font-bold text-gray-800 text-xs sm:text-sm leading-tight">{{ $destination->name }}</h4>
                                                    <p class="text-xs text-gray-600 mt-0.5 sm:mt-1 line-clamp-2 hidden sm:block">{{ Str::limit($destination->description, 80) }}</p>
                                                </div>
                                                @if($destination->image)
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('storage/' . $destination->image) }}"
                                                         alt="{{ $destination->name }}"
                                                         class="w-10 h-10 sm:w-12 sm:h-12 rounded object-cover">
                                                </div>
                                                @endif
                                            </div>
                                            @if($destination->type)
                                            <span class="inline-block mt-1 sm:mt-2 text-xs bg-gray-100 text-gray-700 px-1.5 sm:px-2 py-0.5 sm:py-1 rounded">
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

                    <!-- No results -->
                    <div id="no-destinations-message" class="hidden text-center py-8">
                        <i class="fas fa-search text-gray-400 text-3xl sm:text-4xl mb-3"></i>
                        <p class="text-gray-600 text-sm sm:text-base">No destinations found matching your search.</p>
                    </div>

                    <div class="step-footer">
                        <button type="button" class="prev-btn btn-secondary order-2 sm:order-1">
                            <i class="fas fa-arrow-left mr-2"></i> Previous
                        </button>
                        <button type="button" class="next-btn btn-primary order-1 sm:order-2">
                            Next <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- ===== STEP 4: ACTIVITIES ===== -->
            <div class="form-step" id="step-4">
                <div class="card-panel">
                    <h2 class="step-title">
                        <i class="fas fa-hiking text-orange-600 mr-2 sm:mr-3"></i>
                        Choose Your Activities
                    </h2>
                    <p class="text-sm sm:text-base text-gray-600 mb-6">
                        Activities shown are available at your selected destinations.
                    </p>

                    <!-- Search & Filter -->
                    <div class="mb-5 flex flex-col sm:flex-row gap-3">
                        <div class="flex-1">
                            <input type="text" id="activity-search"
                                   placeholder="🔍 Search activities..."
                                   class="form-input">
                        </div>
                        <div class="w-full sm:w-56 lg:w-64">
                            <select id="category-filter" class="form-input">
                                <option value="">All Categories</option>
                                @foreach($activityCategories as $category)
                                    @if($category->activities->count() > 0)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Selected Activities Summary -->
                    <div id="selected-activities-summary" class="mb-5 hidden">
                        <div class="bg-orange-50 border-2 border-orange-300 rounded-lg p-3 sm:p-4">
                            <p class="font-semibold text-orange-800 mb-2 sm:mb-3 flex items-center text-sm sm:text-base">
                                <i class="fas fa-check-circle mr-2"></i>
                                <span id="selected-activities-count">0</span>&nbsp;Activity/Activities Selected
                            </p>
                            <div id="selected-activities-list" class="flex flex-wrap gap-2"></div>
                        </div>
                    </div>

                    <!-- No destinations warning -->
                    <div id="no-activities-message" class="hidden text-center py-8">
                        <i class="fas fa-map-marker-alt text-gray-400 text-3xl sm:text-4xl mb-3"></i>
                        <p class="text-gray-600 text-sm sm:text-base font-medium">No activities available.</p>
                        <p class="text-gray-400 text-xs sm:text-sm mt-1">Please go back and select at least one destination first.</p>
                    </div>

                    <!-- Activities List -->
                    <div id="activities-container" class="space-y-4 max-h-[420px] sm:max-h-[500px] overflow-y-auto pr-1 sm:pr-2">
                        @foreach($activityCategories as $category)
                            @if($category->activities->count() > 0)
                            <div class="category-group" data-category="{{ $category->id }}">
                                <h3 class="text-base sm:text-lg font-bold text-gray-700 mb-2 sm:mb-3 flex items-center sticky top-0 bg-white py-2 z-10">
                                    @if($category->icon)<i class="{{ $category->icon }} mr-2"></i>@endif
                                    {{ $category->name }}
                                </h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2 sm:gap-3 mb-4 sm:mb-6">
                                    @foreach($category->activities as $activity)
                                    @php $destinationIds = $activity->destinations->pluck('id')->implode(','); @endphp
                                    <label class="activity-item flex items-start p-2.5 sm:p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-orange-400 hover:bg-orange-50 transition-all duration-200"
                                           data-category="{{ $category->id }}"
                                           data-name="{{ strtolower($activity->name) }}"
                                           data-destinations="{{ $destinationIds }}">
                                        <input type="checkbox"
                                               name="activities[]"
                                               value="{{ $activity->id }}"
                                               class="activity-checkbox mt-0.5 sm:mt-1 w-4 h-4 sm:w-5 sm:h-5 rounded border-gray-300 text-orange-600 focus:ring-orange-500 flex-shrink-0"
                                               {{ in_array($activity->id, old('activities', [])) ? 'checked' : '' }}>
                                        <div class="ml-2 sm:ml-3 flex-1 min-w-0">
                                            <div class="flex items-start justify-between gap-1">
                                                <div class="flex-1 min-w-0">
                                                    <h4 class="font-bold text-gray-800 text-xs sm:text-sm leading-tight">{{ $activity->name }}</h4>
                                                    <p class="text-xs text-gray-600 mt-0.5 sm:mt-1 line-clamp-2 hidden sm:block">{{ Str::limit($activity->description, 80) }}</p>
                                                </div>
                                                @if($activity->image)
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('storage/' . $activity->image) }}"
                                                         alt="{{ $activity->name }}"
                                                         class="w-10 h-10 sm:w-12 sm:h-12 rounded object-cover">
                                                </div>
                                                @endif
                                            </div>
                                            @if($activity->duration)
                                            <span class="inline-block mt-1 sm:mt-2 text-xs bg-gray-100 text-gray-700 px-1.5 sm:px-2 py-0.5 sm:py-1 rounded">
                                                <i class="far fa-clock mr-1"></i>{{ $activity->duration }}
                                            </span>
                                            @endif
                                            @if($activity->destinations->count())
                                            <div class="mt-1 text-[10px] sm:text-[11px] text-gray-500 truncate">
                                                <i class="fas fa-map-marker-alt mr-1 text-orange-500"></i>
                                                {{ $activity->destinations->pluck('name')->join(', ') }}
                                            </div>
                                            @endif
                                        </div>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        @endforeach
                    </div>

                    <div class="step-footer">
                        <button type="button" class="prev-btn btn-secondary order-2 sm:order-1">
                            <i class="fas fa-arrow-left mr-2"></i> Previous
                        </button>
                        <button type="button" class="next-btn btn-primary order-1 sm:order-2">
                            Next <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- ===== STEP 5: PREFERENCES ===== -->
            <div class="form-step" id="step-5">
                <div class="card-panel">
                    <h2 class="step-title">
                        <i class="fas fa-sliders-h text-indigo-600 mr-2 sm:mr-3"></i>
                        Preferences & Requirements
                    </h2>

                    <div class="space-y-5 sm:space-y-6">

                        <!-- Budget Category -->
                        <div>
                            <label class="form-label mb-3">Budget Category</label>
                            <div class="grid grid-cols-3 gap-2 sm:gap-4">

                                <label class="budget-option border-2 border-gray-200 rounded-lg p-3 sm:p-4 cursor-pointer hover:border-green-500 transition">
                                    <input type="radio" name="budget_category" value="budget" class="hidden"
                                           {{ old('budget_category') == 'budget' ? 'checked' : '' }}>
                                    <div class="text-center">
                                        <i class="fas fa-dollar-sign text-xl sm:text-3xl text-blue-600 mb-1 sm:mb-2"></i>
                                        <h4 class="font-bold text-gray-800 text-xs sm:text-sm">Budget</h4>
                                        <p class="text-xs text-gray-600 hidden sm:block">$100–200/day</p>
                                    </div>
                                </label>

                                <label class="budget-option border-2 border-gray-200 rounded-lg p-3 sm:p-4 cursor-pointer hover:border-green-500 transition">
                                    <input type="radio" name="budget_category" value="mid-range" class="hidden"
                                           {{ old('budget_category') == 'mid-range' ? 'checked' : '' }}>
                                    <div class="text-center">
                                        <i class="fas fa-star text-xl sm:text-3xl text-yellow-600 mb-1 sm:mb-2"></i>
                                        <h4 class="font-bold text-gray-800 text-xs sm:text-sm">Mid-Range</h4>
                                        <p class="text-xs text-gray-600 hidden sm:block">$200–400/day</p>
                                    </div>
                                </label>

                                <label class="budget-option border-2 border-gray-200 rounded-lg p-3 sm:p-4 cursor-pointer hover:border-green-500 transition">
                                    <input type="radio" name="budget_category" value="luxury" class="hidden"
                                           {{ old('budget_category') == 'luxury' ? 'checked' : '' }}>
                                    <div class="text-center">
                                        <i class="fas fa-gem text-xl sm:text-3xl text-purple-600 mb-1 sm:mb-2"></i>
                                        <h4 class="font-bold text-gray-800 text-xs sm:text-sm">Luxury</h4>
                                        <p class="text-xs text-gray-600 hidden sm:block">$400+/day</p>
                                    </div>
                                </label>

                            </div>
                        </div>

                        <!-- Approximate Budget -->
                        <div class="form-group">
                            <label class="form-label">Approximate Total Budget <span class="text-gray-400 font-normal">(Optional)</span></label>
                            <input type="text" name="approximate_budget"
                                   class="form-input"
                                   value="{{ old('approximate_budget') }}"
                                   placeholder="e.g., $5,000 – $8,000">
                        </div>

                        <!-- Accommodation -->
                        <div class="form-group">
                            <label class="form-label">Accommodation Preference</label>
                            <select name="accommodation_preference" class="form-input">
                                <option value="">No Preference</option>
                                <option value="Camping"         {{ old('accommodation_preference') == 'Camping'         ? 'selected' : '' }}>Camping</option>
                                <option value="Budget Lodges"   {{ old('accommodation_preference') == 'Budget Lodges'   ? 'selected' : '' }}>Budget Lodges</option>
                                <option value="Mid-Range Lodges"{{ old('accommodation_preference') == 'Mid-Range Lodges'? 'selected' : '' }}>Mid-Range Lodges</option>
                                <option value="Luxury Lodges"   {{ old('accommodation_preference') == 'Luxury Lodges'   ? 'selected' : '' }}>Luxury Lodges</option>
                                <option value="Mix of Options"  {{ old('accommodation_preference') == 'Mix of Options'  ? 'selected' : '' }}>Mix of Options</option>
                            </select>
                        </div>

                        <!-- Special Requirements -->
                        <div>
                            <label class="form-label mb-3">Special Requirements</label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-3">
                                @foreach([
                                    'Wheelchair Accessible',
                                    'Family Friendly',
                                    'Solo Traveler',
                                    'Honeymoon Package',
                                    'Photography Focus',
                                    'Private Tour',
                                ] as $req)
                                <label class="flex items-center space-x-2 sm:space-x-3 p-2.5 sm:p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-green-400 hover:bg-green-50 transition">
                                    <input type="checkbox" name="special_requirements[]" value="{{ $req }}"
                                           class="w-4 h-4 sm:w-5 sm:h-5 rounded border-gray-300 text-green-600 focus:ring-green-500 flex-shrink-0"
                                           {{ in_array($req, old('special_requirements', [])) ? 'checked' : '' }}>
                                    <span class="text-xs sm:text-sm">{{ $req }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Dietary Restrictions -->
                        <div class="form-group">
                            <label class="form-label">Dietary Restrictions</label>
                            <textarea name="dietary_restrictions" rows="2" class="form-input"
                                      placeholder="e.g., Vegetarian, Vegan, Gluten-free, Allergies...">{{ old('dietary_restrictions') }}</textarea>
                        </div>

                        <!-- Medical Conditions -->
                        <div class="form-group">
                            <label class="form-label">Medical Conditions / Accessibility Needs</label>
                            <textarea name="medical_conditions" rows="2" class="form-input"
                                      placeholder="Any medical conditions or accessibility needs we should know about...">{{ old('medical_conditions') }}</textarea>
                        </div>

                        <!-- Special Requests -->
                        <div class="form-group">
                            <label class="form-label">Additional Comments / Special Requests</label>
                            <textarea name="special_requests" rows="3 sm:rows-4" class="form-input"
                                      placeholder="Tell us anything else about your dream safari...">{{ old('special_requests') }}</textarea>
                        </div>

                        <!-- How did you hear -->
                        <div class="form-group">
                            <label class="form-label">How did you hear about us?</label>
                            <select name="heard_from" class="form-input">
                                <option value="">Please select</option>
                                @foreach(['Google Search','Social Media','Friend/Family Referral','Travel Agent','Online Advertisement','Travel Blog/Website','Previous Customer','Other'] as $source)
                                <option value="{{ $source }}" {{ old('heard_from') == $source ? 'selected' : '' }}>{{ $source }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <div class="step-footer">
                        <button type="button" class="prev-btn btn-secondary order-2 sm:order-1">
                            <i class="fas fa-arrow-left mr-2"></i> Previous
                        </button>
                        <button type="submit" class="btn-submit order-1 sm:order-2">
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
<script>
const countriesArray = @json($countries->map(fn($c) => ['name' => $c->name, 'flag' => $c->flag_icon])->values());
const countryInput    = document.getElementById('countryInput');
const countryDropdown = document.getElementById('countryDropdown');

if (countryInput && countryDropdown) {
    countryInput.addEventListener('input', function () {
        const q = this.value.toLowerCase().trim();
        if (!q) { countryDropdown.classList.add('hidden'); return; }

        const filtered = countriesArray.filter(c => c.name.toLowerCase().includes(q));
        if (!filtered.length) { countryDropdown.classList.add('hidden'); return; }

        countryDropdown.innerHTML = filtered.map(c => `
            <div class="px-4 py-2 hover:bg-green-50 cursor-pointer country-option text-sm" data-country="${c.name}">
                <span class="mr-2">${c.flag}</span><span>${c.name}</span>
            </div>`).join('');
        countryDropdown.classList.remove('hidden');

        countryDropdown.querySelectorAll('.country-option').forEach(opt => {
            opt.addEventListener('click', function () {
                countryInput.value = this.dataset.country;
                countryDropdown.classList.add('hidden');
            });
        });
    });

    document.addEventListener('click', e => {
        if (!countryInput.contains(e.target) && !countryDropdown.contains(e.target))
            countryDropdown.classList.add('hidden');
    });

    countryInput.addEventListener('keydown', e => {
        if (e.key === 'Escape') countryDropdown.classList.add('hidden');
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            countryDropdown.querySelector('.country-option')?.focus();
        }
    });
}
</script>
@endpush

@push('styles')
<style>
    /* ── Layout ── */
    .safari-bg { background: linear-gradient(135deg, #f0fdf4 0%, #eff6ff 100%); min-height: 100vh; }

    /* ── Card ── */
    .card-panel { background: #fff; border-radius: 1rem; box-shadow: 0 4px 24px rgba(0,0,0,.08); padding: 1.5rem; }
    @media (min-width: 640px)  { .card-panel { padding: 2rem; } }
    @media (min-width: 768px)  { .card-panel { padding: 3rem; } }

    /* ── Step title ── */
    .step-title { font-size: 1.4rem; font-weight: 700; color: #1f2937; margin-bottom: 1.25rem; display: flex; align-items: center; flex-wrap: wrap; gap: .25rem; }
    @media (min-width: 640px) { .step-title { font-size: 1.75rem; margin-bottom: 1.5rem; } }

    /* ── Forms ── */
    .form-group  { display: flex; flex-direction: column; }
    .form-label  { display: block; font-size: .8125rem; font-weight: 600; color: #374151; margin-bottom: .4rem; }
    @media (min-width: 640px) { .form-label { font-size: .875rem; } }
    .form-input  { width: 100%; padding: .65rem 1rem; border: 1px solid #d1d5db; border-radius: .5rem; font-size: .875rem; color: #1f2937; transition: box-shadow .15s, border-color .15s; background: #fff; }
    .form-input:focus { outline: none; border-color: #10b981; box-shadow: 0 0 0 3px rgba(16,185,129,.15); }
    @media (min-width: 640px) { .form-input { padding: .75rem 1rem; font-size: .9375rem; } }
    textarea.form-input { resize: vertical; }
    .form-error  { color: #ef4444; font-size: .75rem; margin-top: .25rem; }

    /* ── Buttons ── */
    .btn-primary  { background: #10b981; color: #fff; padding: .65rem 1.5rem; border-radius: .5rem; font-weight: 600; font-size: .875rem; border: none; cursor: pointer; transition: background .2s, box-shadow .2s; box-shadow: 0 2px 8px rgba(16,185,129,.3); display: inline-flex; align-items: center; }
    .btn-primary:hover  { background: #059669; }
    .btn-secondary { background: #6b7280; color: #fff; padding: .65rem 1.5rem; border-radius: .5rem; font-weight: 600; font-size: .875rem; border: none; cursor: pointer; transition: background .2s; display: inline-flex; align-items: center; }
    .btn-secondary:hover { background: #4b5563; }
    .btn-submit   { background: #10b981; color: #fff; padding: .65rem 1.75rem; border-radius: .5rem; font-weight: 600; font-size: .9rem; border: none; cursor: pointer; transition: background .2s, box-shadow .2s; box-shadow: 0 2px 8px rgba(16,185,129,.3); display: inline-flex; align-items: center; }
    .btn-submit:hover { background: #059669; }
    @media (min-width: 640px) {
        .btn-primary, .btn-secondary, .btn-submit { padding: .75rem 2rem; font-size: .9375rem; }
    }

    /* ── Step footer ── */
    .step-footer { display: flex; flex-direction: column; gap: .75rem; margin-top: 1.75rem; }
    @media (min-width: 640px) { .step-footer { flex-direction: row; justify-content: space-between; margin-top: 2rem; } }

    /* ── Progress indicator ── */
    .form-step { display: none; }
    .form-step.active { display: block; animation: fadeIn .3s ease; }
    @keyframes fadeIn { from { opacity:0; transform:translateY(8px); } to { opacity:1; transform:translateY(0); } }

    .step-indicator { text-align: center; flex: 1; }
    .step-number {
        width: 36px; height: 36px; border-radius: 50%;
        background: #e5e7eb; display: flex; align-items: center; justify-content: center;
        margin: 0 auto 4px; font-weight: 700; font-size: .8rem; color: #6b7280; transition: all .3s;
    }
    @media (min-width: 480px) { .step-number { width: 44px; height: 44px; font-size: .9rem; } }
    @media (min-width: 640px) { .step-number { width: 50px; height: 50px; font-size: 1rem;  } }

    .step-indicator.active   .step-number { background: #10b981; color: #fff; transform: scale(1.1); box-shadow: 0 4px 12px rgba(16,185,129,.3); }
    .step-indicator.completed .step-number { background: #10b981; color: #fff; }
    .step-indicator.completed .step-number::after { content: '✓'; }

    .step-label { font-size: .65rem; color: #6b7280; font-weight: 500; }
    @media (min-width: 480px) { .step-label { font-size: .75rem; } }
    @media (min-width: 640px) { .step-label { font-size: .875rem; } }
    .step-indicator.active .step-label { color: #10b981; font-weight: 600; }

    .step-line { flex: 1; height: 2px; background: #e5e7eb; margin: 0 4px; align-self: center; position: relative; top: -12px; }
    @media (min-width: 480px) { .step-line { top: -16px; margin: 0 6px; } }
    @media (min-width: 640px) { .step-line { top: -20px; margin: 0 8px; } }

    /* ── Selection states ── */
    .destination-item:has(.destination-checkbox:checked),
    .activity-item:has(.activity-checkbox:checked) { border-color: #10b981; background: #f0fdf4; }
    .budget-option:has(input:checked) { border-color: #10b981; background: #f0fdf4; }

    /* ── Card hover ── */
    .destination-item, .activity-item {
        transition: transform .2s ease, box-shadow .2s ease;
    }
    @media (hover: hover) {
        .destination-item:hover, .activity-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,.08);
        }
    }

    /* ── Line clamp ── */
    .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }

    /* ── Scrollbar ── */
    #destinations-container, #activities-container { scrollbar-width: thin; scrollbar-color: #10b981 #f1f1f1; }
    #destinations-container::-webkit-scrollbar, #activities-container::-webkit-scrollbar { width: 6px; }
    #destinations-container::-webkit-scrollbar-track, #activities-container::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
    #destinations-container::-webkit-scrollbar-thumb, #activities-container::-webkit-scrollbar-thumb { background: #10b981; border-radius: 10px; }

    /* ── Touch targets ── */
    @media (max-width: 640px) {
        .destination-item, .activity-item { min-height: 56px; }
        .budget-option { min-height: 70px; }
    }
</style>
@endpush
@endsection