@extends('layouts.admin')

@section('title', 'Edit Destination')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Edit Destination</h1>
            <p class="text-gray-600 mt-1">{{ $destination->name }}</p>
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

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            <p class="font-bold">{{ session('success') }}</p>
        </div>
    @endif

    <!-- Edit Form -->
    <form action="{{ route('admin.destinations.update', $destination) }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-lg rounded-lg">
        @csrf
        @method('PUT')

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
                                <option value="{{ $country->id }}" {{ old('country_id', $destination->country_id) == $country->id ? 'selected' : '' }}>
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
                               value="{{ old('name', $destination->name) }}" 
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
                               value="{{ old('slug', $destination->slug) }}" 
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
                               value="{{ old('region', $destination->region) }}" 
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
                            <option value="National Park" {{ old('type', $destination->type) == 'National Park' ? 'selected' : '' }}>National Park</option>
                            <option value="Wildlife Reserve" {{ old('type', $destination->type) == 'Wildlife Reserve' ? 'selected' : '' }}>Wildlife Reserve</option>
                            <option value="Forest Reserve" {{ old('type', $destination->type) == 'Forest Reserve' ? 'selected' : '' }}>Forest Reserve</option>
                            <option value="Game Reserve" {{ old('type', $destination->type) == 'Game Reserve' ? 'selected' : '' }}>Game Reserve</option>
                            <option value="Conservation Area" {{ old('type', $destination->type) == 'Conservation Area' ? 'selected' : '' }}>Conservation Area</option>
                            <option value="Wildlife Sanctuary" {{ old('type', $destination->type) == 'Wildlife Sanctuary' ? 'selected' : '' }}>Wildlife Sanctuary</option>
                            <option value="City" {{ old('type', $destination->type) == 'City' ? 'selected' : '' }}>City</option>
                            <option value="Lake" {{ old('type', $destination->type) == 'Lake' ? 'selected' : '' }}>Lake</option>
                            <option value="Mountain" {{ old('type', $destination->type) == 'Mountain' ? 'selected' : '' }}>Mountain</option>
                            <option value="Island" {{ old('type', $destination->type) == 'Island' ? 'selected' : '' }}>Island</option>
                        </select>
                    </div>

                    <!-- Sort Order -->
                    <div>
                        <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">
                            Sort Order
                        </label>
                        <input type="number" name="sort_order" id="sort_order" min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                               value="{{ old('sort_order', $destination->sort_order ?? 0) }}">
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
                              placeholder="Brief overview of the destination (2-3 sentences for listings)">{{ old('description', $destination->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status Checkboxes -->
                <div class="mt-6 space-y-3">
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1"
                               class="w-5 h-5 rounded border-gray-300 text-green-600 focus:ring-green-500"
                               {{ old('is_active', $destination->is_active) ? 'checked' : '' }}>
                        <div>
                            <span class="text-sm font-medium text-gray-700">Active</span>
                            <p class="text-xs text-gray-500">Make this destination visible on the website</p>
                        </div>
                    </label>

                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" name="is_popular" value="1"
                               class="w-5 h-5 rounded border-gray-300 text-green-600 focus:ring-green-500"
                               {{ old('is_popular', $destination->is_popular) ? 'checked' : '' }}>
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
                              placeholder="Write a comprehensive overview of the destination. Include history, significance, key features, and what makes it special...">{{ old('detailed_overview', $destination->detailed_overview) }}</textarea>
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
Description of chimp tracking...">{{ old('what_to_see_do', $destination->what_to_see_do) }}</textarea>
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
- African Fish Eagle">{{ old('wildlife_highlights', $destination->wildlife_highlights) }}</textarea>
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
- Woodland and forest (30%)">{{ old('geography_landscape', $destination->geography_landscape) }}</textarea>
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
                               value="{{ old('latitude', $destination->latitude) }}" 
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
                               value="{{ old('longitude', $destination->longitude) }}" 
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
                               value="{{ old('area_size', $destination->area_size) }}" 
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
                               value="{{ old('altitude_min', $destination->altitude_min) }}" 
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
                               value="{{ old('altitude_max', $destination->altitude_max) }}" 
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
- Best conditions for hiking">{{ old('best_time_visit', $destination->best_time_visit) }}</textarea>
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
- Flight time: 1.5 hours from Entebbe">{{ old('how_to_get_there', $destination->how_to_get_there) }}</textarea>
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
- Malaria prophylaxis essential">{{ old('practical_information', $destination->practical_information) }}</textarea>
                </div>

                <!-- Pricing Information -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div>
                        <label for="entry_fee_foreign" class="block text-sm font-medium text-gray-700 mb-2">
                            Entry Fee (Foreign)
                        </label>
                        <input type="number" name="entry_fee_foreign" id="entry_fee_foreign" step="0.01" min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                               value="{{ old('entry_fee_foreign', $destination->entry_fee_foreign) }}" 
                               placeholder="40.00">
                    </div>

                    <div>
                        <label for="entry_fee_resident" class="block text-sm font-medium text-gray-700 mb-2">
                            Entry Fee (Resident)
                        </label>
                        <input type="number" name="entry_fee_resident" id="entry_fee_resident" step="0.01" min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                               value="{{ old('entry_fee_resident', $destination->entry_fee_resident) }}" 
                               placeholder="30.00">
                    </div>

                    <div>
                        <label for="entry_fee_local" class="block text-sm font-medium text-gray-700 mb-2">
                            Entry Fee (Local)
                        </label>
                        <input type="number" name="entry_fee_local" id="entry_fee_local" step="0.01" min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                               value="{{ old('entry_fee_local', $destination->entry_fee_local) }}" 
                               placeholder="20000">
                    </div>

                    <div>
                        <label for="currency" class="block text-sm font-medium text-gray-700 mb-2">
                            Currency
                        </label>
                        <select name="currency" id="currency"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                            <option value="USD" {{ old('currency', $destination->currency ?? 'USD') == 'USD' ? 'selected' : '' }}>USD ($)</option>
                            <option value="EUR" {{ old('currency', $destination->currency) == 'EUR' ? 'selected' : '' }}>EUR (€)</option>
                            <option value="GBP" {{ old('currency', $destination->currency) == 'GBP' ? 'selected' : '' }}>GBP (£)</option>
                            <option value="UGX" {{ old('currency', $destination->currency) == 'UGX' ? 'selected' : '' }}>UGX (USh)</option>
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
                               value="{{ old('phone', $destination->phone) }}" 
                               placeholder="+256-700-000000">
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email Address
                        </label>
                        <input type="email" name="email" id="email"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                               value="{{ old('email', $destination->email) }}" 
                               placeholder="info@park.go.ug">
                    </div>

                    <div>
                        <label for="website" class="block text-sm font-medium text-gray-700 mb-2">
                            Website URL
                        </label>
                        <input type="url" name="website" id="website"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                               value="{{ old('website', $destination->website) }}" 
                               placeholder="https://www.ugandawildlife.org">
                    </div>

                    <div>
                        <label for="opening_hours" class="block text-sm font-medium text-gray-700 mb-2">
                            Opening Hours
                        </label>
                        <input type="text" name="opening_hours" id="opening_hours"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                               value="{{ old('opening_hours', $destination->opening_hours) }}" 
                               placeholder="6:00 AM - 7:00 PM Daily">
                    </div>

                    <div>
                        <label for="established_year" class="block text-sm font-medium text-gray-700 mb-2">
                            Established Year
                        </label>
                        <input type="text" name="established_year" id="established_year" maxlength="4"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                               value="{{ old('established_year', $destination->established_year) }}" 
                               placeholder="1952">
                    </div>

                    <div>
                        <label for="annual_visitors" class="block text-sm font-medium text-gray-700 mb-2">
                            Annual Visitors
                        </label>
                        <input type="number" name="annual_visitors" id="annual_visitors" min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                               value="{{ old('annual_visitors', $destination->annual_visitors) }}" 
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
                               value="{{ old('climate', $destination->climate) }}" 
                               placeholder="Tropical Savanna">
                    </div>

                    <div>
                        <label for="avg_temp_high" class="block text-sm font-medium text-gray-700 mb-2">
                            Avg Temp High (°C)
                        </label>
                        <input type="number" name="avg_temp_high" id="avg_temp_high"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                               value="{{ old('avg_temp_high', $destination->avg_temp_high) }}" 
                               placeholder="28">
                    </div>

                    <div>
                        <label for="avg_temp_low" class="block text-sm font-medium text-gray-700 mb-2">
                            Avg Temp Low (°C)
                        </label>
                        <input type="number" name="avg_temp_low" id="avg_temp_low"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                               value="{{ old('avg_temp_low', $destination->avg_temp_low) }}" 
                               placeholder="18">
                    </div>

                    <div>
                        <label for="rainfall_annual" class="block text-sm font-medium text-gray-700 mb-2">
                            Annual Rainfall (mm)
                        </label>
                        <input type="number" name="rainfall_annual" id="rainfall_annual" min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                               value="{{ old('rainfall_annual', $destination->rainfall_annual) }}" 
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
- Budget-friendly camping and bandas">{{ old('accommodation_options', $destination->accommodation_options) }}</textarea>
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
- Traditional practices">{{ old('cultural_significance', $destination->cultural_significance) }}</textarea>
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
- Golden hour: 6:00-8:00 AM">{{ old('photography_tips', $destination->photography_tips) }}</textarea>
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
- 176 km from Kampala">{{ old('nearby_attractions', $destination->nearby_attractions) }}</textarea>
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

3. Winston Churchill visited in 1907 and called it 'the most beautiful sight the Nile has to offer.'">{{ old('interesting_facts', $destination->interesting_facts) }}</textarea>
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
        
        @if($destination->image)
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-600 mb-2">Current Image</label>
                <div class="relative inline-block">
                    <img src="{{ asset('storage/' . $destination->image) }}" 
                         alt="{{ $destination->name }}" 
                         class="rounded-lg shadow-md border-2 border-gray-300"
                         style="max-width: 400px; max-height: 300px; object-fit: cover;">
                    <div class="absolute top-2 right-2 bg-blue-500 text-white px-3 py-1 rounded-full text-xs font-semibold">
                        Current
                    </div>
                </div>
            </div>
        @endif
        
        <input type="file" name="image" id="image" accept="image/*"
               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
        <p class="text-gray-500 text-xs mt-1">{{ $destination->image ? 'Upload new to replace current. ' : '' }}Recommended: 800x600px, Max: 2MB</p>
        <div id="image-preview" class="mt-3"></div>
    </div>

    <!-- Featured Header Image -->
    <div class="mb-6">
        <label for="featured_image" class="block text-sm font-medium text-gray-700 mb-2">
            Featured Header Image
        </label>
        
        @if($destination->featured_image)
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-600 mb-2">Current Featured Image</label>
                <div class="relative inline-block">
                    <img src="{{ asset('storage/' . $destination->featured_image) }}" 
                         alt="{{ $destination->name }} Featured" 
                         class="rounded-lg shadow-md border-2 border-gray-300"
                         style="max-width: 600px; max-height: 400px; object-fit: cover;">
                    <div class="absolute top-2 right-2 bg-blue-500 text-white px-3 py-1 rounded-full text-xs font-semibold">
                        Current Featured
                    </div>
                </div>
            </div>
        @endif
        
        <input type="file" name="featured_image" id="featured_image" accept="image/*"
               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
        <p class="text-gray-500 text-xs mt-1">{{ $destination->featured_image ? 'Upload new to replace current. ' : '' }}Hero/header background. Recommended: 1920x1080px, Max: 5MB</p>
        <div id="featured-preview" class="mt-3"></div>
    </div>

    <!-- Gallery Images -->
    <div class="mb-6">
        <label for="gallery_images" class="block text-sm font-medium text-gray-700 mb-2">
            Add Gallery Images
        </label>
        
        @php
            $galleryImages = $destination->gallery_images;
            // Handle if it's JSON string
            if (is_string($galleryImages)) {
                $galleryImages = json_decode($galleryImages, true);
            }
            // Ensure it's an array
            if (!is_array($galleryImages)) {
                $galleryImages = [];
            }
        @endphp
        
        @if(count($galleryImages) > 0)
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-600 mb-2">Current Gallery ({{ count($galleryImages) }} images)</label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($galleryImages as $index => $galleryItem)
                        @php
                            // Handle both formats: simple array of paths or array of objects
                            $imagePath = is_array($galleryItem) ? ($galleryItem['image'] ?? $galleryItem['path'] ?? '') : $galleryItem;
                        @endphp
                        @if($imagePath)
                            <div class="relative group">
                                <img src="{{ asset('storage/' . $imagePath) }}" 
                                     alt="Gallery {{ $index + 1 }}" 
                                     class="w-full h-32 object-cover rounded-lg shadow border-2 border-gray-300">
                                <div class="absolute top-1 right-1 bg-blue-500 text-white px-2 py-1 rounded text-xs font-bold">
                                    {{ $index + 1 }}
                                </div>
                                
                                <!-- Delete Button -->
                                <button type="button" 
                                        class="delete-gallery-image absolute bottom-2 right-2 bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs opacity-0 group-hover:opacity-100 transition"
                                        data-index="{{ $index }}"
                                        data-path="{{ $imagePath }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        @endif
                    @endforeach
                </div>
                
                <!-- Hidden field to track deleted images -->
                <input type="hidden" name="delete_gallery_images" id="delete_gallery_images" value="">
            </div>
        @endif
        
        <input type="file" name="gallery_images[]" id="gallery_images" accept="image/*" multiple
               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
        <p class="text-gray-500 text-xs mt-1">Select multiple images to add to gallery. Max: 2MB each</p>
        <div id="gallery-preview" class="mt-3 grid grid-cols-2 md:grid-cols-4 gap-4"></div>
    </div>

    <!-- Section-Specific Images Info -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-6 space-y-4">
        <div class="flex items-start">
            <i class="fas fa-info-circle text-blue-600 text-xl mr-3 mt-1"></i>
            <div>
                <h3 class="font-semibold text-blue-900 mb-2">Section-Specific Images</h3>
                <p class="text-sm text-blue-800 mb-3">
                    You can also upload images specific to different content sections (Activities, Wildlife, Accommodation, etc.)
                </p>
                
                <div class="space-y-2 text-sm text-blue-700">
                    @php
                        $sections = [
                            'overview_images' => 'Overview Images',
                            'activities_images' => 'Activities Images',
                            'wildlife_images' => 'Wildlife Images',
                            'landscape_images' => 'Landscape Images',
                            'accommodation_images' => 'Accommodation Images',
                        ];
                        $totalSectionImages = 0;
                        foreach($sections as $key => $label) {
                            $sectionImages = $destination->$key;
                            if (is_string($sectionImages)) {
                                $sectionImages = json_decode($sectionImages, true);
                            }
                            if (is_array($sectionImages)) {
                                $totalSectionImages += count($sectionImages);
                            }
                        }
                    @endphp
                    
                    <p class="flex items-center">
                        <i class="fas fa-check-circle text-green-600 mr-2"></i>
                        <strong>{{ $totalSectionImages }}</strong> section-specific images currently uploaded
                    </p>
                    
                    @foreach($sections as $key => $label)
                        @php
                            $sectionImages = $destination->$key;
                            if (is_string($sectionImages)) {
                                $sectionImages = json_decode($sectionImages, true);
                            }
                            $count = is_array($sectionImages) ? count($sectionImages) : 0;
                        @endphp
                        @if($count > 0)
                        <p class="ml-6 text-xs">
                            • {{ $label }}: <span class="font-semibold">{{ $count }}</span> image(s)
                        </p>
                        @endif
                    @endforeach
                </div>
                
                <div class="mt-4 pt-3 border-t border-blue-300">
                    <p class="text-xs text-blue-700">
                        <i class="fas fa-lightbulb mr-1"></i>
                        <em>To add section-specific images, use the dedicated upload tool below or contact your developer for advanced image management.</em>
                    </p>
                </div>
            </div>
        </div>

                                
                                    <!-- After the Gallery Images section, add this: -->

<!-- Section-Specific Images Upload -->
<div class="mt-8 border-t pt-8">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">
        <i class="fas fa-layer-group text-purple-600 mr-2"></i>
        Section-Specific Images
    </h3>
    <p class="text-sm text-gray-600 mb-6">
        Upload images that will appear inline with specific content sections. Each image can be tagged to a section heading.
    </p>

    <!-- Accordion for each section -->
    <div class="space-y-4">
        
        <!-- Overview Images -->
        <div class="border border-gray-300 rounded-lg overflow-hidden">
            <button type="button" class="section-accordion w-full bg-gray-50 hover:bg-gray-100 px-4 py-3 flex justify-between items-center transition" data-section="overview">
                <span class="font-medium text-gray-800">
                    <i class="fas fa-book-open text-blue-600 mr-2"></i>
                    Overview Images
                    @php
                        $overviewImages = $destination->overview_images;
                        if (is_string($overviewImages)) $overviewImages = json_decode($overviewImages, true);
                        $overviewCount = is_array($overviewImages) ? count($overviewImages) : 0;
                    @endphp
                    <span class="ml-2 text-sm text-gray-500">({{ $overviewCount }} images)</span>
                </span>
                <i class="fas fa-chevron-down transform transition-transform"></i>
            </button>
            <div class="section-content hidden p-4 bg-white" id="section-overview">
                @include('admin.destinations.partials.section-images', [
                    'sectionName' => 'overview_images',
                    'sectionLabel' => 'Overview',
                    'existingImages' => $overviewImages ?? []
                ])
            </div>
        </div>

        <!-- Activities Images -->
        <div class="border border-gray-300 rounded-lg overflow-hidden">
            <button type="button" class="section-accordion w-full bg-gray-50 hover:bg-gray-100 px-4 py-3 flex justify-between items-center transition" data-section="activities">
                <span class="font-medium text-gray-800">
                    <i class="fas fa-hiking text-green-600 mr-2"></i>
                    Activities Images
                    @php
                        $activitiesImages = $destination->activities_images;
                        if (is_string($activitiesImages)) $activitiesImages = json_decode($activitiesImages, true);
                        $activitiesCount = is_array($activitiesImages) ? count($activitiesImages) : 0;
                    @endphp
                    <span class="ml-2 text-sm text-gray-500">({{ $activitiesCount }} images)</span>
                </span>
                <i class="fas fa-chevron-down transform transition-transform"></i>
            </button>
            <div class="section-content hidden p-4 bg-white" id="section-activities">
                @include('admin.destinations.partials.section-images', [
                    'sectionName' => 'activities_images',
                    'sectionLabel' => 'Activities',
                    'existingImages' => $activitiesImages ?? []
                ])
            </div>
        </div>

        <!-- Wildlife Images -->
        <div class="border border-gray-300 rounded-lg overflow-hidden">
            <button type="button" class="section-accordion w-full bg-gray-50 hover:bg-gray-100 px-4 py-3 flex justify-between items-center transition" data-section="wildlife">
                <span class="font-medium text-gray-800">
                    <i class="fas fa-paw text-orange-600 mr-2"></i>
                    Wildlife Images
                    @php
                        $wildlifeImages = $destination->wildlife_images;
                        if (is_string($wildlifeImages)) $wildlifeImages = json_decode($wildlifeImages, true);
                        $wildlifeCount = is_array($wildlifeImages) ? count($wildlifeImages) : 0;
                    @endphp
                    <span class="ml-2 text-sm text-gray-500">({{ $wildlifeCount }} images)</span>
                </span>
                <i class="fas fa-chevron-down transform transition-transform"></i>
            </button>
            <div class="section-content hidden p-4 bg-white" id="section-wildlife">
                @include('admin.destinations.partials.section-images', [
                    'sectionName' => 'wildlife_images',
                    'sectionLabel' => 'Wildlife',
                    'existingImages' => $wildlifeImages ?? []
                ])
            </div>
        </div>

        <!-- Landscape Images -->
        <div class="border border-gray-300 rounded-lg overflow-hidden">
            <button type="button" class="section-accordion w-full bg-gray-50 hover:bg-gray-100 px-4 py-3 flex justify-between items-center transition" data-section="landscape">
                <span class="font-medium text-gray-800">
                    <i class="fas fa-mountain text-teal-600 mr-2"></i>
                    Landscape Images
                    @php
                        $landscapeImages = $destination->landscape_images;
                        if (is_string($landscapeImages)) $landscapeImages = json_decode($landscapeImages, true);
                        $landscapeCount = is_array($landscapeImages) ? count($landscapeImages) : 0;
                    @endphp
                    <span class="ml-2 text-sm text-gray-500">({{ $landscapeCount }} images)</span>
                </span>
                <i class="fas fa-chevron-down transform transition-transform"></i>
            </button>
            <div class="section-content hidden p-4 bg-white" id="section-landscape">
                @include('admin.destinations.partials.section-images', [
                    'sectionName' => 'landscape_images',
                    'sectionLabel' => 'Landscape',
                    'existingImages' => $landscapeImages ?? []
                ])
            </div>
        </div>

        <!-- Accommodation Images -->
        <div class="border border-gray-300 rounded-lg overflow-hidden">
            <button type="button" class="section-accordion w-full bg-gray-50 hover:bg-gray-100 px-4 py-3 flex justify-between items-center transition" data-section="accommodation">
                <span class="font-medium text-gray-800">
                    <i class="fas fa-hotel text-purple-600 mr-2"></i>
                    Accommodation Images
                    @php
                        $accommodationImages = $destination->accommodation_images;
                        if (is_string($accommodationImages)) $accommodationImages = json_decode($accommodationImages, true);
                        $accommodationCount = is_array($accommodationImages) ? count($accommodationImages) : 0;
                    @endphp
                    <span class="ml-2 text-sm text-gray-500">({{ $accommodationCount }} images)</span>
                </span>
                <i class="fas fa-chevron-down transform transition-transform"></i>
            </button>
            <div class="section-content hidden p-4 bg-white" id="section-accommodation">
                @include('admin.destinations.partials.section-images', [
                    'sectionName' => 'accommodation_images',
                    'sectionLabel' => 'Accommodation',
                    'existingImages' => $accommodationImages ?? []
                ])
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
// Gallery image deletion tracking
let deletedGalleryImages = [];

document.querySelectorAll('.delete-gallery-image').forEach(button => {
    button.addEventListener('click', function() {
        const imagePath = this.dataset.path;
        const index = this.dataset.index;
        
        if (confirm('Are you sure you want to remove this gallery image?')) {
            // Add to deletion list
            deletedGalleryImages.push(imagePath);
            document.getElementById('delete_gallery_images').value = JSON.stringify(deletedGalleryImages);
            
            // Remove visual element
            this.closest('.relative').remove();
            
            // Show success message
            const container = this.closest('.mb-4');
            const currentCount = container.querySelectorAll('.relative').length;
            const label = container.querySelector('label');
            if (currentCount === 0) {
                container.remove();
            } else {
                label.textContent = `Current Gallery (${currentCount} images)`;
            }
        }
    });
});

// Gallery Images Preview (new uploads)
document.getElementById('gallery_images').addEventListener('change', function(e) {
    const files = e.target.files;
    const preview = document.getElementById('gallery-preview');
    preview.innerHTML = '';
    
    if (files.length > 0) {
        preview.innerHTML = '<p class="col-span-full text-sm font-semibold text-gray-700 mb-2">New Images to Upload:</p>';
    }
    
    for(let i = 0; i < files.length; i++) {
        const file = files[i];
        
        // Check file size
        if (file.size > 2097152) {
            const errorDiv = document.createElement('div');
            errorDiv.className = 'col-span-full bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded';
            errorDiv.innerHTML = `<p class="text-sm"><i class="fas fa-exclamation-triangle mr-2"></i>File "${file.name}" exceeds 2MB limit.</p>`;
            preview.appendChild(errorDiv);
            continue;
        }
        
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const div = document.createElement('div');
            div.className = 'relative';
            div.innerHTML = `
                <img src="${e.target.result}" class="w-full h-32 object-cover rounded-lg shadow border border-gray-300">
                <div class="absolute top-1 right-1 bg-green-500 text-white px-2 py-1 rounded text-xs font-bold">
                    New
                </div>
                <div class="absolute bottom-1 left-1 bg-black/70 text-white px-2 py-1 rounded text-xs truncate max-w-full">
                    ${file.name}
                </div>
            `;
            preview.appendChild(div);
        }
        
        reader.readAsDataURL(file);
    }
});
</script>
@endpush


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
                               value="{{ old('meta_title', $destination->meta_title) }}" 
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
                                  placeholder="Visit Murchison Falls National Park, Uganda's largest wildlife reserve...">{{ old('meta_description', $destination->meta_description) }}</textarea>
                        <p class="text-gray-500 text-xs mt-1">Recommended: 150-160 characters</p>
                    </div>

                    <!-- Meta Keywords -->
                    <div>
                        <label for="meta_keywords" class="block text-sm font-medium text-gray-700 mb-2">
                            Meta Keywords
                        </label>
                        <input type="text" name="meta_keywords" id="meta_keywords"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                               value="{{ old('meta_keywords', $destination->meta_keywords) }}" 
                               placeholder="murchison falls, uganda safari, wildlife park, nile river">
                        <p class="text-gray-500 text-xs mt-1">Comma-separated keywords</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="border-t bg-gray-50 px-6 py-4 flex justify-between items-center rounded-b-lg">
            <div class="flex gap-3">
                <a href="{{ route('admin.destinations.index') }}" class="text-gray-600 hover:text-gray-800 font-medium">
                    <i class="fas fa-times mr-1"></i> Cancel
                </a>
                <button type="button" 
                        onclick="document.getElementById('deleteModal').classList.remove('hidden')"
                        class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg font-medium transition flex items-center">
                    <i class="fas fa-trash mr-2"></i> Delete
                </button>
            </div>
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium transition flex items-center shadow-md">
                <i class="fas fa-save mr-2"></i> Update Destination
            </button>
        </div>
    </form>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-75 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">
    <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full">
        <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-4 rounded-t-2xl">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold text-white flex items-center">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Confirm Deletion
                </h3>
                <button type="button" 
                        onclick="document.getElementById('deleteModal').classList.add('hidden')"
                        class="text-white hover:text-gray-200 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>
        
        <div class="p-6">
            <div class="text-center mb-4">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                    <i class="fas fa-exclamation-triangle text-3xl text-red-600"></i>
                </div>
                <p class="text-gray-700 text-lg mb-2">Are you sure you want to delete</p>
                <p class="text-xl font-bold text-gray-900 mb-3">{{ $destination->name }}</p>
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                    <p class="text-red-800 font-semibold">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        This action cannot be undone!
                    </p>
                </div>
            </div>
        </div>
        
        <div class="bg-gray-50 px-6 py-4 rounded-b-2xl flex gap-3 justify-end">
            <button type="button" 
                    onclick="document.getElementById('deleteModal').classList.add('hidden')"
                    class="px-6 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition-colors">
                Cancel
            </button>
            <form action="{{ route('admin.destinations.destroy', $destination) }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="px-6 py-2.5 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all">
                    <i class="fas fa-trash mr-2"></i> Delete Permanently
                </button>
            </form>
        </div>
    </div>
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
                    <div class="absolute top-2 right-2 bg-green-500 text-white px-2 py-1 rounded text-xs font-bold">
                        New Preview
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
                    <div class="absolute top-2 right-2 bg-green-500 text-white px-2 py-1 rounded text-xs font-bold">
                        New Featured Preview
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
                <div class="absolute top-1 right-1 bg-green-500 text-white px-2 py-1 rounded text-xs font-bold">
                    New ${i + 1}
                </div>
            `;
            preview.appendChild(div);
        }
        
        reader.readAsDataURL(file);
    }
});

// Close modal on outside click
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        this.classList.add('hidden');
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

document.querySelectorAll('.section-accordion').forEach(button => {
    button.addEventListener('click', function() {
        const section = this.dataset.section;
        const content = document.getElementById('section-' + section);
        const icon = this.querySelector('.fa-chevron-down');
        
        // Toggle visibility
        content.classList.toggle('hidden');
        icon.classList.toggle('rotate-180');
    });
});

// Add More Section Images
document.querySelectorAll('.add-section-image-btn').forEach(button => {
    button.addEventListener('click', function() {
        const sectionName = this.dataset.section;
        const container = document.getElementById(sectionName + '-upload-container');
        
        const newItem = document.createElement('div');
        newItem.className = 'section-image-item mb-4 p-4 bg-white rounded border border-gray-200 relative';
        newItem.innerHTML = `
            <button type="button" class="remove-section-item absolute top-2 right-2 text-red-600 hover:text-red-800">
                <i class="fas fa-times-circle"></i>
            </button>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Image File *</label>
                    <input type="file" 
                           name="${sectionName}[]" 
                           accept="image/*"
                           class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-green-500">
                    <p class="text-xs text-gray-500 mt-1">Max: 2MB</p>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Section Heading</label>
                    <input type="text" 
                           name="${sectionName}_sections[]" 
                           placeholder="e.g., Wildlife Game Drives"
                           class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-green-500">
                    <p class="text-xs text-gray-500 mt-1">Where to display this image</p>
                </div>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Caption (Optional)</label>
                <input type="text" 
                       name="${sectionName}_captions[]" 
                       placeholder="Brief description of the image"
                       class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-green-500">
            </div>
        `;
        
        container.appendChild(newItem);
        
        // Attach remove handler
        newItem.querySelector('.remove-section-item').addEventListener('click', function() {
            newItem.remove();
        });
    });
});

// Remove Section Image Item
document.addEventListener('click', function(e) {
    if (e.target.closest('.remove-section-item')) {
        e.target.closest('.section-image-item').remove();
    }
});

// Delete Section Images Tracking
let deletedSectionImages = {
    overview_images: [],
    activities_images: [],
    wildlife_images: [],
    landscape_images: [],
    accommodation_images: []
};

document.querySelectorAll('.delete-section-image').forEach(button => {
    button.addEventListener('click', function() {
        const section = this.dataset.section;
        const imagePath = this.dataset.path;
        const index = this.dataset.index;
        
        if (confirm('Remove this image?')) {
            // Track deletion
            deletedSectionImages[section].push({index: index, path: imagePath});
            document.getElementById('delete_' + section).value = JSON.stringify(deletedSectionImages[section]);
            
            // Remove from UI
            this.closest('.border').remove();
            
            // Update count
            const accordion = document.querySelector(`.section-accordion[data-section="${section.replace('_images', '')}"]`);
            const countSpan = accordion.querySelector('.text-gray-500');
            const currentCount = parseInt(countSpan.textContent.match(/\d+/)[0]);
            countSpan.textContent = `(${currentCount - 1} images)`;
        }
    });
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