@extends('layouts.admin')

@section('title', 'Create Destination')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Create New Destination</h1>
            <p class="text-gray-600 mt-1">Add a new safari destination with comprehensive details</p>
        </div>
        <a href="{{ route('admin.destinations.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center transition">
            <i class="fas fa-arrow-left mr-2"></i> Back to List
        </a>
    </div>

    <!-- Error Messages -->
    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <p class="font-bold mb-2">Please fix the following errors:</p>
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Create Form -->
    <form action="{{ route('admin.destinations.store') }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-lg rounded-lg">
        @csrf

        <!-- Tabs Navigation -->
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px overflow-x-auto">
                <button type="button" class="tab-button active px-6 py-3 border-b-2 font-medium text-sm whitespace-nowrap" data-tab="basic">
                    <i class="fas fa-info-circle mr-2"></i>Basic Info
                </button>
                <button type="button" class="tab-button px-6 py-3 border-b-2 font-medium text-sm whitespace-nowrap" data-tab="overview">
                    <i class="fas fa-book-open mr-2"></i>Overview
                </button>
                <button type="button" class="tab-button px-6 py-3 border-b-2 font-medium text-sm whitespace-nowrap" data-tab="activities">
                    <i class="fas fa-hiking mr-2"></i>Activities
                </button>
                <button type="button" class="tab-button px-6 py-3 border-b-2 font-medium text-sm whitespace-nowrap" data-tab="wildlife">
                    <i class="fas fa-paw mr-2"></i>Wildlife
                </button>
                <button type="button" class="tab-button px-6 py-3 border-b-2 font-medium text-sm whitespace-nowrap" data-tab="geography">
                    <i class="fas fa-map mr-2"></i>Geography
                </button>
                <button type="button" class="tab-button px-6 py-3 border-b-2 font-medium text-sm whitespace-nowrap" data-tab="practical">
                    <i class="fas fa-compass mr-2"></i>Practical Info
                </button>
                <button type="button" class="tab-button px-6 py-3 border-b-2 font-medium text-sm whitespace-nowrap" data-tab="accommodation">
                    <i class="fas fa-hotel mr-2"></i>Accommodation
                </button>
                <button type="button" class="tab-button px-6 py-3 border-b-2 font-medium text-sm whitespace-nowrap" data-tab="extras">
                    <i class="fas fa-plus-circle mr-2"></i>Extras
                </button>
                <button type="button" class="tab-button px-6 py-3 border-b-2 font-medium text-sm whitespace-nowrap" data-tab="images">
                    <i class="fas fa-images mr-2"></i>Images
                </button>
                <button type="button" class="tab-button px-6 py-3 border-b-2 font-medium text-sm whitespace-nowrap" data-tab="seo">
                    <i class="fas fa-search mr-2"></i>SEO
                </button>
            </nav>
        </div>

        <div class="p-6">
            <!-- TAB 1: Basic Information -->
            <div id="tab-basic" class="tab-content">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Basic Information</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Country -->
                    <div>
                        <label for="country_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Country <span class="text-red-500">*</span>
                        </label>
                        <select name="country_id" id="country_id" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('country_id') border-red-500 @enderror">
                            <option value="">Select Country</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}" {{ old('country_id') == $country->id ? 'selected' : '' }}>
                                    {{ $country->flag_icon }} {{ $country->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('country_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Destination Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Destination Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('name') border-red-500 @enderror"
                               value="{{ old('name') }}" 
                               placeholder="e.g., Murchison Falls National Park">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Slug -->
                    <div>
                        <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">
                            URL Slug
                        </label>
                        <input type="text" name="slug" id="slug"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('slug') border-red-500 @enderror"
                               value="{{ old('slug') }}" 
                               placeholder="murchison-falls-national-park">
                        @error('slug')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-gray-500 text-xs mt-1">Leave empty to auto-generate from name</p>
                    </div>

                    <!-- Region -->
                    <div>
                        <label for="region" class="block text-sm font-medium text-gray-700 mb-2">
                            Region/Province
                        </label>
                        <input type="text" name="region" id="region"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                               value="{{ old('region') }}" 
                               placeholder="e.g., Northwestern Uganda">
                        <p class="text-gray-500 text-xs mt-1">Geographic region or province</p>
                    </div>

                    <!-- Type -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                            Destination Type
                        </label>
                        <select name="type" id="type"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="">Select Type</option>
                            <option value="National Park" {{ old('type') == 'National Park' ? 'selected' : '' }}>National Park</option>
                            <option value="Wildlife Reserve" {{ old('type') == 'Wildlife Reserve' ? 'selected' : '' }}>Wildlife Reserve</option>
                            <option value="Forest Reserve" {{ old('type') == 'Forest Reserve' ? 'selected' : '' }}>Forest Reserve</option>
                            <option value="Game Reserve" {{ old('type') == 'Game Reserve' ? 'selected' : '' }}>Game Reserve</option>
                            <option value="Conservation Area" {{ old('type') == 'Conservation Area' ? 'selected' : '' }}>Conservation Area</option>
                            <option value="Wildlife Sanctuary" {{ old('type') == 'Wildlife Sanctuary' ? 'selected' : '' }}>Wildlife Sanctuary</option>
                            <option value="City" {{ old('type') == 'City' ? 'selected' : '' }}>City</option>
                            <option value="Lake" {{ old('type') == 'Lake' ? 'selected' : '' }}>Lake</option>
                            <option value="Mountain" {{ old('type') == 'Mountain' ? 'selected' : '' }}>Mountain</option>
                            <option value="Island" {{ old('type') == 'Island' ? 'selected' : '' }}>Island</option>
                        </select>
                    </div>

                    <!-- Sort Order -->
                    <div>
                        <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">
                            Sort Order
                        </label>
                        <input type="number" name="sort_order" id="sort_order" min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                               value="{{ old('sort_order', 0) }}">
                        <p class="text-gray-500 text-xs mt-1">Lower numbers appear first (0 = highest priority)</p>
                    </div>
                </div>

                <!-- Short Description -->
                <div class="mt-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Short Description
                    </label>
                    <textarea name="description" id="description" rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('description') border-red-500 @enderror"
                              placeholder="Brief overview of the destination (2-3 sentences for listings)">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status Checkboxes -->
                <div class="mt-6 space-y-3">
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1"
                               class="w-5 h-5 rounded border-gray-300 text-green-600 focus:ring-green-500"
                               {{ old('is_active', true) ? 'checked' : '' }}>
                        <div>
                            <span class="text-sm font-medium text-gray-700">Active</span>
                            <p class="text-xs text-gray-500">Make this destination visible on the website</p>
                        </div>
                    </label>

                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" name="is_popular" value="1"
                               class="w-5 h-5 rounded border-gray-300 text-green-600 focus:ring-green-500"
                               {{ old('is_popular') ? 'checked' : '' }}>
                        <div>
                            <span class="text-sm font-medium text-gray-700">Mark as Popular</span>
                            <p class="text-xs text-gray-500">Feature this destination prominently on homepage</p>
                        </div>
                    </label>
                </div>
            </div>

            <!-- TAB 2: Detailed Overview -->
            <div id="tab-overview" class="tab-content hidden">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Detailed Overview</h2>
                
                <div>
                    <label for="detailed_overview" class="block text-sm font-medium text-gray-700 mb-2">
                        Comprehensive Overview
                    </label>
                    <textarea name="detailed_overview" id="detailed_overview" rows="15"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent font-mono text-sm"
                              placeholder="Write a comprehensive overview of the destination. Include history, significance, key features, and what makes it special...">{{ old('detailed_overview') }}</textarea>
                    <p class="text-gray-500 text-xs mt-1">This is the main content that appears on the destination detail page</p>
                </div>
            </div>

            <!-- TAB 3: Activities & What to See/Do -->
            <div id="tab-activities" class="tab-content hidden">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Activities & What to See/Do</h2>
                
                <div>
                    <label for="what_to_see_do" class="block text-sm font-medium text-gray-700 mb-2">
                        Activities Content
                    </label>
                    <textarea name="what_to_see_do" id="what_to_see_do" rows="20"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent font-mono text-sm"
                              placeholder="Use headings and subheadings:

**Wildlife Game Drives:**
Description of game drives...

**Launch Cruise to the Falls:**
Description of boat safari...

**Chimpanzee Tracking:**
Description of chimp tracking...">{{ old('what_to_see_do') }}</textarea>
                    <p class="text-gray-500 text-xs mt-1">Use **Heading:** for main activities. You can upload images for each activity in the Images tab.</p>
                </div>
            </div>

            <!-- TAB 4: Wildlife Highlights -->
            <div id="tab-wildlife" class="tab-content hidden">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Wildlife Highlights</h2>
                
                <div>
                    <label for="wildlife_highlights" class="block text-sm font-medium text-gray-700 mb-2">
                        Wildlife Content
                    </label>
                    <textarea name="wildlife_highlights" id="wildlife_highlights" rows="20"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent font-mono text-sm"
                              placeholder="Use headings for organization:

**Mammals (76 species):**

**Big Five Status:** Four of the Big Five present...

**Predators:**
- African Lion
- Leopard
- Spotted Hyena

**Herbivores:**
- African Elephant - over 1,300 individuals
- Rothschild's Giraffe

**Birds (451 species):**
- Shoebill Stork
- African Fish Eagle">{{ old('wildlife_highlights') }}</textarea>
                    <p class="text-gray-500 text-xs mt-1">List all wildlife with details. Use headings like **Mammals:**, **Birds:**, **Reptiles:**</p>
                </div>
            </div>

            <!-- TAB 5: Geography & Landscape -->
            <div id="tab-geography" class="tab-content hidden">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Geography & Landscape</h2>
                
                <!-- Geography Content -->
                <div class="mb-6">
                    <label for="geography_landscape" class="block text-sm font-medium text-gray-700 mb-2">
                        Geography Description
                    </label>
                    <textarea name="geography_landscape" id="geography_landscape" rows="12"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent font-mono text-sm"
                              placeholder="**Location & Size:**
- Northwestern Uganda, approximately 305 km from Kampala
- Total area: 3,893 square kilometers

**Topography:**
Description of landscape features...

**Vegetation Zones:**
- Savanna grassland (60%)
- Woodland and forest (30%)">{{ old('geography_landscape') }}</textarea>
                </div>

                <!-- Geographic Data -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Latitude -->
                    <div>
                        <label for="latitude" class="block text-sm font-medium text-gray-700 mb-2">
                            Latitude
                        </label>
                        <input type="number" name="latitude" id="latitude" step="0.00000001"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                               value="{{ old('latitude') }}" 
                               placeholder="2.25000000">
                        <p class="text-gray-500 text-xs mt-1">Decimal format (e.g., 2.250000)</p>
                    </div>

                    <!-- Longitude -->
                    <div>
                        <label for="longitude" class="block text-sm font-medium text-gray-700 mb-2">
                            Longitude
                        </label>
                        <input type="number" name="longitude" id="longitude" step="0.00000001"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                               value="{{ old('longitude') }}" 
                               placeholder="31.75000000">
                        <p class="text-gray-500 text-xs mt-1">Decimal format (e.g., 31.750000)</p>
                    </div>

                    <!-- Area Size -->
                    <div>
                        <label for="area_size" class="block text-sm font-medium text-gray-700 mb-2">
                            Area Size (km²)
                        </label>
                        <input type="number" name="area_size" id="area_size" min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                               value="{{ old('area_size') }}" 
                               placeholder="3893">
                        <p class="text-gray-500 text-xs mt-1">Size in square kilometers</p>
                    </div>

                    <!-- Altitude Min -->
                    <div>
                        <label for="altitude_min" class="block text-sm font-medium text-gray-700 mb-2">
                            Min Altitude (m)
                        </label>
                        <input type="number" name="altitude_min" id="altitude_min"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                               value="{{ old('altitude_min') }}" 
                               placeholder="619">
                        <p class="text-gray-500 text-xs mt-1">Meters above sea level</p>
                    </div>

                    <!-- Altitude Max -->
                    <div>
                        <label for="altitude_max" class="block text-sm font-medium text-gray-700 mb-2">
                            Max Altitude (m)
                        </label>
                        <input type="number" name="altitude_max" id="altitude_max"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                               value="{{ old('altitude_max') }}" 
                               placeholder="1292">
                        <p class="text-gray-500 text-xs mt-1">Meters above sea level</p>
                    </div>
                </div>
            </div>

            <!-- TAB 6: Practical Information -->
            <div id="tab-practical" class="tab-content hidden">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Practical Information</h2>
                
                <!-- Best Time to Visit -->
                <div class="mb-6">
                    <label for="best_time_visit" class="block text-sm font-medium text-gray-700 mb-2">
                        Best Time to Visit
                    </label>
                    <textarea name="best_time_visit" id="best_time_visit" rows="12"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 font-mono text-sm"
                              placeholder="**Dry Seasons (Best for Wildlife Viewing):**

**December to February:**
- Excellent wildlife viewing
- Clear skies perfect for photography

**June to September:**
- Prime game viewing season
- Best conditions for hiking">{{ old('best_time_visit') }}</textarea>
                </div>

                <!-- How to Get There -->
                <div class="mb-6">
                    <label for="how_to_get_there" class="block text-sm font-medium text-gray-700 mb-2">
                        How to Get There
                    </label>
                    <textarea name="how_to_get_there" id="how_to_get_there" rows="12"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 font-mono text-sm"
                              placeholder="**By Road from Kampala (305 km, 5-6 hours):**

**Route 1: Via Masindi:**
- Kampala → Luwero → Nakasongola → Masindi

**By Air:**
- Charter flights available
- Flight time: 1.5 hours from Entebbe">{{ old('how_to_get_there') }}</textarea>
                </div>

                <!-- Practical Information -->
                <div class="mb-6">
                    <label for="practical_information" class="block text-sm font-medium text-gray-700 mb-2">
                        Practical Tips & Information
                    </label>
                    <textarea name="practical_information" id="practical_information" rows="12"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 font-mono text-sm"
                              placeholder="**Park Fees:**
- Foreign Non-Resident: $40 per person per day

**What to Pack:**
- Binoculars
- Camera with telephoto lens
- Sunscreen SPF 50+

**Health Requirements:**
- Yellow Fever vaccination required
- Malaria prophylaxis essential">{{ old('practical_information') }}</textarea>
                </div>

                <!-- Pricing Information -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div>
                        <label for="entry_fee_foreign" class="block text-sm font-medium text-gray-700 mb-2">
                            Entry Fee (Foreign)
                        </label>
                        <input type="number" name="entry_fee_foreign" id="entry_fee_foreign" step="0.01" min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                               value="{{ old('entry_fee_foreign') }}" 
                               placeholder="40.00">
                    </div>

                    <div>
                        <label for="entry_fee_resident" class="block text-sm font-medium text-gray-700 mb-2">
                            Entry Fee (Resident)
                        </label>
                        <input type="number" name="entry_fee_resident" id="entry_fee_resident" step="0.01" min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                               value="{{ old('entry_fee_resident') }}" 
                               placeholder="30.00">
                    </div>

                    <div>
                        <label for="entry_fee_local" class="block text-sm font-medium text-gray-700 mb-2">
                            Entry Fee (Local)
                        </label>
                        <input type="number" name="entry_fee_local" id="entry_fee_local" step="0.01" min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                               value="{{ old('entry_fee_local') }}" 
                               placeholder="20000">
                    </div>

                    <div>
                        <label for="currency" class="block text-sm font-medium text-gray-700 mb-2">
                            Currency
                        </label>
                        <select name="currency" id="currency"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                            <option value="USD" {{ old('currency', 'USD') == 'USD' ? 'selected' : '' }}>USD ($)</option>
                            <option value="EUR" {{ old('currency') == 'EUR' ? 'selected' : '' }}>EUR (€)</option>
                            <option value="GBP" {{ old('currency') == 'GBP' ? 'selected' : '' }}>GBP (£)</option>
                            <option value="UGX" {{ old('currency') == 'UGX' ? 'selected' : '' }}>UGX (USh)</option>
                        </select>
                    </div>
                </div>

                <!-- Contact & Other Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                            Phone Number
                        </label>
                        <input type="text" name="phone" id="phone"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                               value="{{ old('phone') }}" 
                               placeholder="+256-700-000000">
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email Address
                        </label>
                        <input type="email" name="email" id="email"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                               value="{{ old('email') }}" 
                               placeholder="info@park.go.ug">
                    </div>

                    <div>
                        <label for="website" class="block text-sm font-medium text-gray-700 mb-2">
                            Website URL
                        </label>
                        <input type="url" name="website" id="website"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                               value="{{ old('website') }}" 
                               placeholder="https://www.ugandawildlife.org">
                    </div>

                    <div>
                        <label for="opening_hours" class="block text-sm font-medium text-gray-700 mb-2">
                            Opening Hours
                        </label>
                        <input type="text" name="opening_hours" id="opening_hours"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                               value="{{ old('opening_hours') }}" 
                               placeholder="6:00 AM - 7:00 PM Daily">
                    </div>

                    <div>
                        <label for="established_year" class="block text-sm font-medium text-gray-700 mb-2">
                            Established Year
                        </label>
                        <input type="text" name="established_year" id="established_year" maxlength="4"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                               value="{{ old('established_year') }}" 
                               placeholder="1952">
                    </div>

                    <div>
                        <label for="annual_visitors" class="block text-sm font-medium text-gray-700 mb-2">
                            Annual Visitors
                        </label>
                        <input type="number" name="annual_visitors" id="annual_visitors" min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                               value="{{ old('annual_visitors') }}" 
                               placeholder="50000">
                    </div>
                </div>

                <!-- Climate Info -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-6">
                    <div>
                        <label for="climate" class="block text-sm font-medium text-gray-700 mb-2">
                            Climate Type
                        </label>
                        <input type="text" name="climate" id="climate"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                               value="{{ old('climate') }}" 
                               placeholder="Tropical Savanna">
                    </div>

                    <div>
                        <label for="avg_temp_high" class="block text-sm font-medium text-gray-700 mb-2">
                            Avg Temp High (°C)
                        </label>
                        <input type="number" name="avg_temp_high" id="avg_temp_high"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                               value="{{ old('avg_temp_high') }}" 
                               placeholder="28">
                    </div>

                    <div>
                        <label for="avg_temp_low" class="block text-sm font-medium text-gray-700 mb-2">
                            Avg Temp Low (°C)
                        </label>
                        <input type="number" name="avg_temp_low" id="avg_temp_low"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                               value="{{ old('avg_temp_low') }}" 
                               placeholder="18">
                    </div>

                    <div>
                        <label for="rainfall_annual" class="block text-sm font-medium text-gray-700 mb-2">
                            Annual Rainfall (mm)
                        </label>
                        <input type="number" name="rainfall_annual" id="rainfall_annual" min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                               value="{{ old('rainfall_annual') }}" 
                               placeholder="1200">
                    </div>
                </div>
            </div>

            <!-- TAB 7: Accommodation Options -->
            <div id="tab-accommodation" class="tab-content hidden">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Accommodation Options</h2>
                
                <div>
                    <label for="accommodation_options" class="block text-sm font-medium text-gray-700 mb-2">
                        Accommodation Content
                    </label>
                    <textarea name="accommodation_options" id="accommodation_options" rows="20"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 font-mono text-sm"
                              placeholder="**Luxury Lodges ($300-800 per night):**

**Paraa Safari Lodge:**
- Location: Northern bank at Paraa
- 54 rooms with private balconies
- Swimming pool, restaurant, bar

**Mid-Range Lodges ($100-300 per night):**

**Murchison River Lodge:**
- 18 comfortable en-suite rooms
- River views

**Budget Options ($30-100 per night):**

**Red Chilli Rest Camp:**
- Budget-friendly camping and bandas">{{ old('accommodation_options') }}</textarea>
                    <p class="text-gray-500 text-xs mt-1">Use headings like **Luxury Lodges:**, **Mid-Range:**, **Budget:** to organize accommodation tiers</p>
                </div>
            </div>

            <!-- TAB 8: Extras (Culture, Photography, Nearby) -->
            <div id="tab-extras" class="tab-content hidden">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Additional Information</h2>
                
                <!-- Cultural Significance -->
                <div class="mb-6">
                    <label for="cultural_significance" class="block text-sm font-medium text-gray-700 mb-2">
                        Cultural Significance
                    </label>
                    <textarea name="cultural_significance" id="cultural_significance" rows="8"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 font-mono text-sm"
                              placeholder="**Bunyoro Kingdom:**
The park lies within traditional Bunyoro-Kitara Kingdom territory...

**Local Communities:**
- Bagungu fishing community
- Traditional practices">{{ old('cultural_significance') }}</textarea>
                </div>

                <!-- Photography Tips -->
                <div class="mb-6">
                    <label for="photography_tips" class="block text-sm font-medium text-gray-700 mb-2">
                        Photography Tips
                    </label>
                    <textarea name="photography_tips" id="photography_tips" rows="8"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 font-mono text-sm"
                              placeholder="**Best Photography Spots:**
- Top of Murchison Falls
- Base of falls from boat

**Equipment Recommendations:**
- Wide-angle lens (16-35mm)
- Telephoto lens (200-600mm)

**Best Times:**
- Golden hour: 6:00-8:00 AM">{{ old('photography_tips') }}</textarea>
                </div>

                <!-- Nearby Attractions -->
                <div class="mb-6">
                    <label for="nearby_attractions" class="block text-sm font-medium text-gray-700 mb-2">
                        Nearby Attractions & Extensions
                    </label>
                    <textarea name="nearby_attractions" id="nearby_attractions" rows="8"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 font-mono text-sm"
                              placeholder="**Budongo Forest Reserve (Adjacent):**
- 825 chimpanzees
- Chimpanzee tracking

**Ziwa Rhino Sanctuary (En route):**
- Only place to see rhinos in Uganda
- 176 km from Kampala">{{ old('nearby_attractions') }}</textarea>
                </div>

                <!-- Interesting Facts -->
                <div>
                    <label for="interesting_facts" class="block text-sm font-medium text-gray-700 mb-2">
                        Interesting Facts & Trivia
                    </label>
                    <textarea name="interesting_facts" id="interesting_facts" rows="8"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 font-mono text-sm"
                              placeholder="1. The Nile River forces 300 cubic meters of water per second through a gap only 7 meters wide!

2. Ernest Hemingway survived two plane crashes here in 1954.

3. Winston Churchill visited in 1907 and called it 'the most beautiful sight the Nile has to offer.'">{{ old('interesting_facts') }}</textarea>
                </div>
            </div>

            <!-- TAB 9: Images & Media -->
            <div id="tab-images" class="tab-content hidden">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Images & Media</h2>
                
                <!-- Main Thumbnail Image -->
                <div class="mb-6">
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                        Main Thumbnail Image
                    </label>
                    <input type="file" name="image" id="image" accept="image/*"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                    <p class="text-gray-500 text-xs mt-1">Used in listings. Recommended: 800x600px, Max: 2MB</p>
                    <div id="image-preview" class="mt-3"></div>
                </div>

                <!-- Featured Header Image -->
                <div class="mb-6">
                    <label for="featured_image" class="block text-sm font-medium text-gray-700 mb-2">
                        Featured Header Image
                    </label>
                    <input type="file" name="featured_image" id="featured_image" accept="image/*"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                    <p class="text-gray-500 text-xs mt-1">Hero/header background for detail page. Recommended: 1920x1080px, Max: 5MB</p>
                    <div id="featured-preview" class="mt-3"></div>
                </div>

                <!-- Gallery Images -->
                <div class="mb-6">
                    <label for="gallery_images" class="block text-sm font-medium text-gray-700 mb-2">
                        Gallery Images
                    </label>
                    <input type="file" name="gallery_images[]" id="gallery_images" accept="image/*" multiple
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                    <p class="text-gray-500 text-xs mt-1">Multiple images for photo gallery. Max: 2MB each</p>
                    <div id="gallery-preview" class="mt-3 grid grid-cols-2 md:grid-cols-4 gap-4"></div>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-sm text-blue-800">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>Note:</strong> Section-specific images (for activities, wildlife, accommodation) can be uploaded after creating the destination by editing it.
                    </p>
                </div>
            </div>

            <!-- TAB 10: SEO & Meta -->
            <div id="tab-seo" class="tab-content hidden">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">SEO & Meta Information</h2>
                
                <div class="space-y-6">
                    <!-- Meta Title -->
                    <div>
                        <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-2">
                            Meta Title
                        </label>
                        <input type="text" name="meta_title" id="meta_title" maxlength="60"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                               value="{{ old('meta_title') }}" 
                               placeholder="Murchison Falls National Park | Uganda Safari Destination">
                        <p class="text-gray-500 text-xs mt-1">Recommended: 50-60 characters. Leave empty to use destination name.</p>
                    </div>

                    <!-- Meta Description -->
                    <div>
                        <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-2">
                            Meta Description
                        </label>
                        <textarea name="meta_description" id="meta_description" rows="3" maxlength="160"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                                  placeholder="Visit Murchison Falls National Park, Uganda's largest wildlife reserve...">{{ old('meta_description') }}</textarea>
                        <p class="text-gray-500 text-xs mt-1">Recommended: 150-160 characters</p>
                    </div>

                    <!-- Meta Keywords -->
                    <div>
                        <label for="meta_keywords" class="block text-sm font-medium text-gray-700 mb-2">
                            Meta Keywords
                        </label>
                        <input type="text" name="meta_keywords" id="meta_keywords"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                               value="{{ old('meta_keywords') }}" 
                               placeholder="murchison falls, uganda safari, wildlife park, nile river">
                        <p class="text-gray-500 text-xs mt-1">Comma-separated keywords</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="border-t bg-gray-50 px-6 py-4 flex justify-between items-center rounded-b-lg">
            <a href="{{ route('admin.destinations.index') }}" class="text-gray-600 hover:text-gray-800 font-medium">
                <i class="fas fa-times mr-1"></i> Cancel
            </a>
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium transition flex items-center shadow-md">
                <i class="fas fa-save mr-2"></i> Create Destination
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
// Tab Switching
document.querySelectorAll('.tab-button').forEach(button => {
    button.addEventListener('click', function() {
        const targetTab = this.dataset.tab;
        
        // Update button styles
        document.querySelectorAll('.tab-button').forEach(btn => {
            btn.classList.remove('active', 'border-green-500', 'text-green-600');
            btn.classList.add('border-transparent', 'text-gray-500');
        });
        this.classList.add('active', 'border-green-500', 'text-green-600');
        this.classList.remove('border-transparent', 'text-gray-500');
        
        // Show/hide content
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.add('hidden');
        });
        document.getElementById('tab-' + targetTab).classList.remove('hidden');
    });
});

// Auto-generate slug from name
document.getElementById('name').addEventListener('input', function() {
    const slugField = document.getElementById('slug');
    if (!slugField.dataset.manualEdit) {
        let slug = this.value.toLowerCase()
            .replace(/[^\w\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim();
        slugField.value = slug;
    }
});

document.getElementById('slug').addEventListener('input', function() {
    this.dataset.manualEdit = 'true';
});

// Image Preview - Main Image
document.getElementById('image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('image-preview');
    
    if (file) {
        if (file.size > 2097152) {
            preview.innerHTML = '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded"><p class="text-sm"><i class="fas fa-exclamation-triangle mr-2"></i>File size exceeds 2MB.</p></div>';
            this.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `
                <div class="relative inline-block">
                    <img src="${e.target.result}" class="rounded-lg shadow-md border border-gray-300" 
                         style="max-width: 300px; max-height: 200px; object-fit: cover;">
                    <div class="absolute top-2 right-2 bg-blue-500 text-white px-2 py-1 rounded text-xs font-bold">
                        Preview
                    </div>
                </div>
            `;
        }
        reader.readAsDataURL(file);
    }
});

// Image Preview - Featured Image
document.getElementById('featured_image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('featured-preview');
    
    if (file) {
        if (file.size > 5242880) {
            preview.innerHTML = '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded"><p class="text-sm"><i class="fas fa-exclamation-triangle mr-2"></i>File size exceeds 5MB.</p></div>';
            this.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `
                <div class="relative inline-block">
                    <img src="${e.target.result}" class="rounded-lg shadow-md border border-gray-300" 
                         style="max-width: 600px; max-height: 400px; object-fit: cover;">
                    <div class="absolute top-2 right-2 bg-blue-500 text-white px-2 py-1 rounded text-xs font-bold">
                        Featured Preview
                    </div>
                </div>
            `;
        }
        reader.readAsDataURL(file);
    }
});

// Gallery Images Preview
document.getElementById('gallery_images').addEventListener('change', function(e) {
    const files = e.target.files;
    const preview = document.getElementById('gallery-preview');
    preview.innerHTML = '';
    
    for(let i = 0; i < files.length; i++) {
        const file = files[i];
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const div = document.createElement('div');
            div.className = 'relative';
            div.innerHTML = `
                <img src="${e.target.result}" class="w-full h-32 object-cover rounded-lg shadow border border-gray-300">
                <div class="absolute top-1 right-1 bg-blue-500 text-white px-2 py-1 rounded text-xs font-bold">
                    ${i + 1}
                </div>
            `;
            preview.appendChild(div);
        }
        
        reader.readAsDataURL(file);
    }
});

// Form validation
document.querySelector('form').addEventListener('submit', function(e) {
    const name = document.getElementById('name').value.trim();
    const country = document.getElementById('country_id').value;
    
    if (!name || !country) {
        e.preventDefault();
        alert('Please fill in all required fields (Country and Destination Name)');
        document.querySelector('.tab-button[data-tab="basic"]').click();
        return false;
    }
});
</script>

<style>
.tab-button.active {
    border-color: #10b981;
    color: #10b981;
}
.tab-button:not(.active) {
    border-color: transparent;
    color: #6b7280;
}
.tab-button:not(.active):hover {
    color: #374151;
    border-color: #d1d5db;
}
</style>
@endpush
@endsection