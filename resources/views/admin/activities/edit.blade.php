@extends('layouts.admin')

@section('title', 'Edit Activity')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Edit Activity: {{ $activity->name }}</h1>
        <a href="{{ route('admin.activities.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center transition">
            <i class="fas fa-arrow-left mr-2"></i> Back to List
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.activities.update', $activity) }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-lg rounded-lg">
        @csrf
        @method('PUT')

        <!-- Tabs Navigation -->
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px flex-wrap">
                <button type="button" class="tab-button active px-6 py-3 border-b-2 font-medium text-sm" data-tab="basic">Basic Information</button>
                <button type="button" class="tab-button px-6 py-3 border-b-2 font-medium text-sm" data-tab="detailed">Detailed Content</button>
                <button type="button" class="tab-button px-6 py-3 border-b-2 font-medium text-sm" data-tab="practical">Practical Info</button>
                <button type="button" class="tab-button px-6 py-3 border-b-2 font-medium text-sm" data-tab="pricing">Pricing & Booking</button>
                <button type="button" class="tab-button px-6 py-3 border-b-2 font-medium text-sm" data-tab="images">Images & Media</button>
                <button type="button" class="tab-button px-6 py-3 border-b-2 font-medium text-sm" data-tab="seo">SEO & Settings</button>
            </nav>
        </div>

        <div class="p-6">

            {{-- ══════════════════════════════════════════════════════ --}}
            {{-- TAB 1: Basic Information                               --}}
            {{-- ══════════════════════════════════════════════════════ --}}
            <div id="tab-basic" class="tab-content">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Basic Information</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Activity Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('name') border-red-500 @enderror"
                               value="{{ old('name', $activity->name) }}" placeholder="e.g., Gorilla Trekking">
                        @error('name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">URL Slug</label>
                        <input type="text" name="slug" id="slug"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('slug') border-red-500 @enderror"
                               value="{{ old('slug', $activity->slug) }}" placeholder="auto-generated-from-name">
                        @error('slug')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        <p class="text-gray-500 text-xs mt-1">Leave empty to auto-generate from name</p>
                    </div>

                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                        <select name="category_id" id="category_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('category_id') border-red-500 @enderror">
                            <option value="">Select Category (Optional)</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $activity->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="destination_id" class="block text-sm font-medium text-gray-700 mb-2">Primary Destination</label>
                        <select name="destination_id" id="destination_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('destination_id') border-red-500 @enderror">
                            <option value="">Select Destination (Optional)</option>
                            @foreach($destinations as $destination)
                                <option value="{{ $destination->id }}" {{ old('destination_id', $activity->destination_id) == $destination->id ? 'selected' : '' }}>
                                    {{ $destination->name }} ({{ $destination->country->name }})
                                </option>
                            @endforeach
                        </select>
                        @error('destination_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Destinations where this activity can be carried out
                        </label>
                        <p class="text-xs text-gray-500 mb-2">Select all destinations where this activity is available.</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                            @php $selectedDestinationsOld = old('destinations', $selectedDestinations ?? []); @endphp
                            @foreach($destinations as $destination)
                                <label class="flex items-center space-x-2 text-sm cursor-pointer">
                                    <input type="checkbox" name="destinations[]" value="{{ $destination->id }}"
                                           class="rounded border-gray-300 text-green-600 focus:ring-green-500"
                                           {{ in_array($destination->id, $selectedDestinationsOld) ? 'checked' : '' }}>
                                    <span class="text-gray-700">
                                        {{ $destination->name }}
                                        <span class="text-gray-400 text-xs">({{ $destination->country->name }})</span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                        @error('destinations')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        @error('destinations.*')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">Sort Order</label>
                        <input type="number" name="sort_order" id="sort_order" min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                               value="{{ old('sort_order', $activity->sort_order) }}">
                        <p class="text-gray-500 text-xs mt-1">Lower numbers appear first</p>
                    </div>
                </div>

                <div class="mt-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Short Description</label>
                    <textarea name="description" id="description" rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('description') border-red-500 @enderror"
                              placeholder="Brief overview of the activity (2-3 sentences)">{{ old('description', $activity->description) }}</textarea>
                    @error('description')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Available in Countries</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach($countries as $country)
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" name="countries[]" value="{{ $country->id }}"
                                   class="rounded border-gray-300 text-green-600 focus:ring-green-500"
                                   {{ in_array($country->id, old('countries', $selectedCountries)) ? 'checked' : '' }}>
                            <span class="text-sm text-gray-700">{{ $country->flag_icon }} {{ $country->name }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- ══════════════════════════════════════════════════════ --}}
            {{-- TAB 2: Detailed Content                                --}}
            {{-- ══════════════════════════════════════════════════════ --}}
            <div id="tab-detailed" class="tab-content hidden">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Detailed Content</h2>

                <!-- Overview (plain textarea, no toolbar needed) -->
                <div class="mb-6">
                    <label for="overview" class="block text-sm font-medium text-gray-700 mb-2">Overview</label>
                    <textarea name="overview" id="overview" rows="5"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                              placeholder="Comprehensive overview of the activity...">{{ old('overview', $activity->overview) }}</textarea>
                </div>

                {{-- ────────────────────────────────────────────────────────
                     WHAT TO EXPECT
                     Toolbar is BELOW the textarea so the user never has to
                     scroll up to reach it no matter how long the content is.
                ─────────────────────────────────────────────────────────── --}}
                <div class="mb-6">
                    <label for="what_to_expect" class="block text-sm font-medium text-gray-700 mb-2">
                        What to Expect
                        <span class="ml-2 text-xs font-normal text-gray-400">
                            — type <code class="bg-gray-100 px-1 rounded">**Title**</code> for subheadings,
                            <code class="bg-gray-100 px-1 rounded">- item</code> for bullets
                        </span>
                    </label>

                    {{-- Textarea: bottom border removed so toolbar docks flush --}}
                    <textarea name="what_to_expect" id="what_to_expect" rows="10"
                              class="w-full px-4 py-2 border border-gray-300 border-b-0 rounded-t-lg focus:outline-none focus:ring-2 focus:ring-green-500 font-mono text-sm leading-relaxed"
                              placeholder="Example:&#10;**Morning Experience**&#10;- Meet your guide at the park gate at 6am&#10;- Drive to the forest trailhead&#10;&#10;**The Trek**&#10;- Walk through dense jungle with a ranger&#10;- Encounter the gorilla family group">{{ old('what_to_expect', $activity->what_to_expect) }}</textarea>

                    {{-- Toolbar docked directly below — INSERT buttons + Preview --}}
                    <div class="fmt-toolbar flex items-center flex-wrap gap-1.5 px-3 py-2 bg-gray-50 border border-gray-300 border-t-0 rounded-b-lg">
                        <span class="text-xs text-gray-400 font-semibold tracking-wide mr-1">INSERT:</span>

                        <button type="button" class="fmt-btn"
                                data-target="what_to_expect"
                                data-before="\n**" data-after="**\n"
                                data-placeholder="Section Title"
                                title="Inserts **Section Title** — renders as a green heading on the site">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h10"/>
                            </svg>
                            Subheading
                        </button>

                        <button type="button" class="fmt-btn"
                                data-target="what_to_expect"
                                data-before="\n- " data-after=""
                                data-placeholder="Bullet point text"
                                title="Inserts a - bullet point">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/>
                            </svg>
                            Bullet
                        </button>

                        <button type="button" class="fmt-btn"
                                data-target="what_to_expect"
                                data-before="\n---\n" data-after=""
                                data-placeholder=""
                                title="Inserts a horizontal divider line">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14"/>
                            </svg>
                            Divider
                        </button>

                        <div class="flex-1"></div>

                        <button type="button" class="preview-toggle-btn"
                                data-target="what_to_expect"
                                data-preview="wte_preview">
                            <svg class="w-3 h-3 inline mr-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            Preview
                        </button>
                    </div>

                    <!-- Live preview panel (hidden until toggled) -->
                    <div id="wte_preview" class="hidden mt-2 p-5 bg-teal-50 border border-teal-200 rounded-lg">
                        <div class="flex items-center gap-2 mb-3 pb-2 border-b border-teal-200">
                            <i class="fas fa-eye text-teal-600 text-xs"></i>
                            <span class="text-xs font-semibold text-teal-700 uppercase tracking-wide">
                                Front-end Preview — renders exactly like this on your site
                            </span>
                        </div>
                        <div class="preview-output text-gray-700 text-sm leading-relaxed"></div>
                    </div>
                </div>

                {{-- ────────────────────────────────────────────────────────
                     HIGHLIGHTS — same pattern: toolbar BELOW textarea
                ─────────────────────────────────────────────────────────── --}}
                <div class="mb-6">
                    <label for="highlights" class="block text-sm font-medium text-gray-700 mb-2">
                        Highlights
                        <span class="ml-2 text-xs font-normal text-gray-400">
                            — type <code class="bg-gray-100 px-1 rounded">**Title**</code> for subheadings,
                            <code class="bg-gray-100 px-1 rounded">- item</code> for bullets
                        </span>
                    </label>

                    <textarea name="highlights" id="highlights" rows="10"
                              class="w-full px-4 py-2 border border-gray-300 border-b-0 rounded-t-lg focus:outline-none focus:ring-2 focus:ring-green-500 font-mono text-sm leading-relaxed"
                              placeholder="Example:&#10;**Wildlife Encounters**&#10;- Get within metres of mountain gorillas&#10;- Spot rare Albertine Rift bird species&#10;&#10;**Scenery & Nature**&#10;- Lush ancient Bwindi Impenetrable Forest&#10;- Stunning crater lake panoramas">{{ old('highlights', $activity->highlights) }}</textarea>

                    <div class="fmt-toolbar flex items-center flex-wrap gap-1.5 px-3 py-2 bg-gray-50 border border-gray-300 border-t-0 rounded-b-lg">
                        <span class="text-xs text-gray-400 font-semibold tracking-wide mr-1">INSERT:</span>

                        <button type="button" class="fmt-btn"
                                data-target="highlights"
                                data-before="\n**" data-after="**\n"
                                data-placeholder="Section Title"
                                title="Inserts **Section Title** — renders as a green heading on the site">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h10"/>
                            </svg>
                            Subheading
                        </button>

                        <button type="button" class="fmt-btn"
                                data-target="highlights"
                                data-before="\n- " data-after=""
                                data-placeholder="Bullet point text"
                                title="Inserts a - bullet point">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/>
                            </svg>
                            Bullet
                        </button>

                        <button type="button" class="fmt-btn"
                                data-target="highlights"
                                data-before="\n---\n" data-after=""
                                data-placeholder=""
                                title="Inserts a horizontal divider line">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14"/>
                            </svg>
                            Divider
                        </button>

                        <div class="flex-1"></div>

                        <button type="button" class="preview-toggle-btn"
                                data-target="highlights"
                                data-preview="hl_preview">
                            <svg class="w-3 h-3 inline mr-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            Preview
                        </button>
                    </div>

                    <div id="hl_preview" class="hidden mt-2 p-5 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <div class="flex items-center gap-2 mb-3 pb-2 border-b border-yellow-200">
                            <i class="fas fa-eye text-yellow-600 text-xs"></i>
                            <span class="text-xs font-semibold text-yellow-700 uppercase tracking-wide">
                                Front-end Preview — renders exactly like this on your site
                            </span>
                        </div>
                        <div class="preview-output text-gray-700 text-sm leading-relaxed"></div>
                    </div>
                </div>

                <!-- Reusable options -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Included -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">What's Included (Reusable Options)</label>
                        <div class="border border-gray-200 rounded-lg p-3 max-h-64 overflow-y-auto space-y-2">
                            @php $selectedIncluded = old('included_option_ids', $selectedIncludedOptionIds ?? []); @endphp
                            @forelse($includedOptions as $option)
                                <label class="flex items-center space-x-2 cursor-pointer text-sm">
                                    <input type="checkbox" name="included_option_ids[]" value="{{ $option->id }}"
                                           class="rounded border-gray-300 text-green-600 focus:ring-green-500"
                                           {{ in_array($option->id, $selectedIncluded) ? 'checked' : '' }}>
                                    <span class="text-gray-700">{{ $option->name }}</span>
                                </label>
                            @empty
                                <p class="text-sm text-gray-500">No included options found. Add from Activity Options page.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Excluded -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">What's NOT Included (Reusable Options)</label>
                        <div class="border border-gray-200 rounded-lg p-3 max-h-64 overflow-y-auto space-y-2">
                            @php $selectedExcluded = old('excluded_option_ids', $selectedExcludedOptionIds ?? []); @endphp
                            @forelse($excludedOptions as $option)
                                <label class="flex items-center space-x-2 cursor-pointer text-sm">
                                    <input type="checkbox" name="excluded_option_ids[]" value="{{ $option->id }}"
                                           class="rounded border-gray-300 text-green-600 focus:ring-green-500"
                                           {{ in_array($option->id, $selectedExcluded) ? 'checked' : '' }}>
                                    <span class="text-gray-700">{{ $option->name }}</span>
                                </label>
                            @empty
                                <p class="text-sm text-gray-500">No excluded options found. Add from Activity Options page.</p>
                            @endforelse
                        </div>
                    </div>

                    {{-- ─────────────────────────────────────────────────────
                         EQUIPMENT PROVIDED
                         ▸ Pre-loaded checkbox grid of common tourism gear
                           grouped by activity type — tick what applies.
                         ▸ All ticked items are submitted as
                           equipment_provided[] string values.
                         ▸ Custom text inputs below handle anything not
                           in the preset list.
                         ▸ Backend receives a plain string array — NO changes
                           needed anywhere else.
                    ─────────────────────────────────────────────────────── --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Equipment Provided</label>
                        <p class="text-xs text-gray-500 mb-3">
                            Tick every item that will be provided for this activity.
                            Add anything not listed using the custom fields below.
                        </p>

                        @php
                        /*
                         * Full preset list of tourism / safari / adventure equipment.
                         * Pure frontend — no DB table, no migration, no model change.
                         * Items submit as equipment_provided[] alongside custom rows.
                         */
                        $equipmentPresets = [
                            'Trekking & Hiking' => [
                                'Hiking boots',
                                'Trekking poles',
                                'Gaiters',
                                'Rain poncho / waterproof jacket',
                                'Day backpack (20-30L)',
                                'Hydration pack / water bottle',
                                'Trail map & compass',
                                'Head torch / flashlight',
                                'First aid kit',
                                'Emergency whistle',
                                'Blister plasters',
                                'Energy snacks / trail bars',
                            ],
                            'Safari & Game Drive' => [
                                'Binoculars',
                                'Field guidebook (birds)',
                                'Field guidebook (mammals)',
                                'Safari hat / sun hat',
                                'Sunscreen SPF 50+',
                                'Insect repellent',
                                'Dust mask / buff',
                                'Seat cushion for long drives',
                                'Camera with telephoto lens',
                                'Lens-cleaning cloth',
                                'Charged power bank',
                            ],
                            'Gorilla & Chimp Trekking' => [
                                'Gardening / gorilla gloves',
                                'Face mask (health protocol)',
                                'Knee-high rubber boots (muddy terrain)',
                                'Long-sleeved shirt',
                                'Long trousers / pants',
                                'Porter service available',
                                'Walking stick',
                            ],
                            'Water & Aquatic' => [
                                'Life jacket / PFD',
                                'Paddle',
                                'Wetsuit',
                                'Snorkelling mask',
                                'Snorkelling fins',
                                'Waterproof dry bag',
                                'Aqua shoes',
                                'Helmet (whitewater)',
                                'Kayak',
                                'Canoe',
                                'Stand-up paddleboard',
                                'Buoyancy aid',
                            ],
                            'Cycling & Mountain Biking' => [
                                'Mountain bike',
                                'Cycling helmet',
                                'Cycling gloves',
                                'Knee pads',
                                'Elbow pads',
                                'Repair kit (pump, levers, spare tube)',
                                'Cycling shorts',
                                'High-visibility vest',
                                'Bike lights',
                            ],
                            'Rock Climbing & Abseiling' => [
                                'Climbing harness',
                                'Climbing helmet',
                                'Belay device',
                                'Carabiners',
                                'Climbing rope',
                                'Chalk bag',
                                'Climbing shoes',
                                'Crash pad (bouldering)',
                            ],
                            'Hot Air Balloon' => [
                                'Warm jacket (high altitude)',
                                'Closed-toe shoes',
                                'Flight certificate',
                                'Safety briefing booklet',
                                'Champagne (post-flight)',
                            ],
                            'Camping & Overnight' => [
                                'Tent (2-person)',
                                'Tent (4-person)',
                                'Sleeping bag (-5°C rated)',
                                'Sleeping mat / pad',
                                'Camp stove & fuel',
                                'Mess kit (plate, cup, utensils)',
                                'Headlamp with spare batteries',
                                'Bear canister / food bag',
                                'Trowel & waste bags',
                                'Camp towel',
                                'Portable lantern',
                                'Folding chairs',
                                'Folding table',
                            ],
                            'Bird Watching' => [
                                'Binoculars (8x42 recommended)',
                                'Spotting scope',
                                'Bird field guide',
                                'Bird call audio device',
                                'Notebook & pen',
                                'Camouflage clothing',
                                'Muted-colour hat',
                            ],
                            'Cultural & Community' => [
                                'Notebook & pen',
                                'Language phrasebook',
                                'Small gifts for community',
                                'Photography permission letter',
                                'Traditional attire (provided)',
                            ],
                            'General Safety & Navigation' => [
                                'Personal locator beacon (PLB)',
                                'Satellite communicator',
                                'Snake bite kit',
                                'Malaria prophylaxis info sheet',
                                'Travel insurance documents',
                                'Emergency contact card',
                                'Two-way radio',
                                'GPS device',
                                'Sunglasses (UV400)',
                                'Zip-lock bags (waterproofing)',
                            ],
                        ];

                        // Items already saved for this activity
                        $savedEquipment   = old('equipment_provided', $activity->equipment_provided ?? []);
                        // Flat list of every preset value for comparison
                        $allPresetValues  = collect($equipmentPresets)->flatten()->toArray();
                        // Custom items = saved items that don't appear in any preset group
                        $customEquipment  = array_values(
                            array_filter($savedEquipment, fn($e) => !in_array($e, $allPresetValues))
                        );
                        @endphp

                        <!-- Preset grid with search and clear -->
                        <div class="border border-gray-200 rounded-lg overflow-hidden mb-3">

                            <!-- Search + clear bar -->
                            <div class="flex items-center gap-3 px-4 py-2 bg-gray-50 border-b border-gray-200">
                                <input type="text" id="equipment-search"
                                       class="flex-1 text-sm px-3 py-1.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                       placeholder="Filter equipment…">
                                <button type="button" id="equipment-clear"
                                        class="text-xs text-red-500 hover:text-red-700 font-medium whitespace-nowrap">
                                    Clear all
                                </button>
                            </div>

                            <!-- Grouped checkboxes -->
                            <div class="p-4 space-y-5 max-h-96 overflow-y-auto" id="equipment-preset-grid">
                                @foreach($equipmentPresets as $groupName => $items)
                                <div class="equipment-group">
                                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 flex items-center gap-2">
                                        <span class="flex-1 border-t border-gray-200"></span>
                                        {{ $groupName }}
                                        <span class="flex-1 border-t border-gray-200"></span>
                                    </p>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-y-1.5 gap-x-6">
                                        @foreach($items as $item)
                                        <label class="equipment-item flex items-center gap-2 text-sm cursor-pointer group"
                                               data-label="{{ strtolower($item) }}">
                                            <input type="checkbox"
                                                   name="equipment_provided[]"
                                                   value="{{ $item }}"
                                                   class="rounded border-gray-300 text-green-600 focus:ring-green-500 shrink-0"
                                                   {{ in_array($item, $savedEquipment) ? 'checked' : '' }}>
                                            <span class="text-gray-700 group-hover:text-green-700 transition-colors">{{ $item }}</span>
                                        </label>
                                        @endforeach
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Custom text-input rows for items not in the preset list -->
                        <p class="text-xs font-medium text-gray-600 mb-2">
                            <i class="fas fa-plus-circle text-green-500 mr-1"></i>
                            Custom items not listed above:
                        </p>
                        <div id="equipment-custom-container">
                            @if(count($customEquipment) > 0)
                                @foreach($customEquipment as $item)
                                <div class="flex gap-2 mb-2 equipment-custom-row">
                                    <input type="text" name="equipment_provided[]" value="{{ $item }}"
                                           class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500"
                                           placeholder="Custom equipment item">
                                    <button type="button" class="remove-custom-equipment shrink-0 bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg text-sm transition">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                                @endforeach
                            @else
                                {{--
                                    Always render exactly ONE empty row so the
                                    "Add custom item" button has something to
                                    anchor to and the UI never looks broken.
                                --}}
                                <div class="flex gap-2 mb-2 equipment-custom-row">
                                    <input type="text" name="equipment_provided[]" value=""
                                           class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500"
                                           placeholder="Custom equipment item">
                                    <button type="button" class="remove-custom-equipment shrink-0 bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg text-sm transition">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            @endif
                        </div>

                        <button type="button" id="add-custom-equipment"
                                class="mt-1 inline-flex items-center gap-1.5 text-sm text-green-600 hover:text-green-800 font-medium transition">
                            <i class="fas fa-plus-circle"></i> Add custom item
                        </button>
                    </div>

                    <!-- What to Bring -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">What to Bring (Reusable Options)</label>
                        <div class="border border-gray-200 rounded-lg p-3 max-h-64 overflow-y-auto space-y-2">
                            @php $selectedBring = old('bring_option_ids', $selectedBringOptionIds ?? []); @endphp
                            @forelse($bringOptions as $option)
                                <label class="flex items-center space-x-2 cursor-pointer text-sm">
                                    <input type="checkbox" name="bring_option_ids[]" value="{{ $option->id }}"
                                           class="rounded border-gray-300 text-green-600 focus:ring-green-500"
                                           {{ in_array($option->id, $selectedBring) ? 'checked' : '' }}>
                                    <span class="text-gray-700">{{ $option->name }}</span>
                                </label>
                            @empty
                                <p class="text-sm text-gray-500">No bring options found. Add from Activity Options page.</p>
                            @endforelse
                        </div>
                    </div>

                </div><!-- /.grid -->
            </div>

            {{-- ══════════════════════════════════════════════════════ --}}
            {{-- TAB 3: Practical Info                                  --}}
            {{-- ══════════════════════════════════════════════════════ --}}
            <div id="tab-practical" class="tab-content hidden">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Practical Information</h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label for="duration" class="block text-sm font-medium text-gray-700 mb-2">Duration</label>
                        <input type="text" name="duration" id="duration"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                               value="{{ old('duration', $activity->duration) }}" placeholder="e.g., Full Day, 2-8 hours">
                    </div>
                    <div>
                        <label for="difficulty_level" class="block text-sm font-medium text-gray-700 mb-2">Difficulty Level</label>
                        <select name="difficulty_level" id="difficulty_level"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                            <option value="">Select Difficulty</option>
                            <option value="easy"        {{ old('difficulty_level', $activity->difficulty_level) == 'easy'        ? 'selected' : '' }}>Easy</option>
                            <option value="moderate"    {{ old('difficulty_level', $activity->difficulty_level) == 'moderate'    ? 'selected' : '' }}>Moderate</option>
                            <option value="challenging" {{ old('difficulty_level', $activity->difficulty_level) == 'challenging' ? 'selected' : '' }}>Challenging</option>
                            <option value="extreme"     {{ old('difficulty_level', $activity->difficulty_level) == 'extreme'     ? 'selected' : '' }}>Extreme</option>
                        </select>
                    </div>
                    <div>
                        <label for="min_age" class="block text-sm font-medium text-gray-700 mb-2">Minimum Age</label>
                        <input type="number" name="min_age" id="min_age" min="0" max="100"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                               value="{{ old('min_age', $activity->min_age) }}" placeholder="e.g., 15">
                    </div>
                    <div>
                        <label for="max_group_size" class="block text-sm font-medium text-gray-700 mb-2">Max Group Size</label>
                        <input type="number" name="max_group_size" id="max_group_size" min="1"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                               value="{{ old('max_group_size', $activity->max_group_size) }}" placeholder="e.g., 8">
                    </div>
                </div>

                <div class="mb-6">
                    <label for="regulations" class="block text-sm font-medium text-gray-700 mb-2">Rules & Regulations</label>
                    <textarea name="regulations" id="regulations" rows="5"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                              placeholder="Important rules, permits, and regulations...">{{ old('regulations', $activity->regulations) }}</textarea>
                </div>
                <div class="mb-6">
                    <label for="safety_info" class="block text-sm font-medium text-gray-700 mb-2">Safety Information</label>
                    <textarea name="safety_info" id="safety_info" rows="5"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                              placeholder="Safety guidelines and precautions...">{{ old('safety_info', $activity->safety_info) }}</textarea>
                </div>
                <div class="mb-6">
                    <label for="health_requirements" class="block text-sm font-medium text-gray-700 mb-2">Health Requirements</label>
                    <textarea name="health_requirements" id="health_requirements" rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                              placeholder="Medical requirements, vaccinations, fitness level...">{{ old('health_requirements', $activity->health_requirements) }}</textarea>
                </div>
                <div class="mb-6">
                    <label for="cultural_experience" class="block text-sm font-medium text-gray-700 mb-2">Cultural Experience</label>
                    <textarea name="cultural_experience" id="cultural_experience" rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                              placeholder="Cultural aspects and community involvement...">{{ old('cultural_experience', $activity->cultural_experience) }}</textarea>
                </div>
                <div class="mb-6">
                    <label for="conservation_info" class="block text-sm font-medium text-gray-700 mb-2">Conservation & Sustainability</label>
                    <textarea name="conservation_info" id="conservation_info" rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                              placeholder="Conservation efforts and sustainable practices...">{{ old('conservation_info', $activity->conservation_info) }}</textarea>
                </div>
            </div>

            {{-- ══════════════════════════════════════════════════════ --}}
            {{-- TAB 4: Pricing & Booking                               --}}
            {{-- ══════════════════════════════════════════════════════ --}}
            <div id="tab-pricing" class="tab-content hidden">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Pricing & Booking Information</h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label for="price_from" class="block text-sm font-medium text-gray-700 mb-2">Price From</label>
                        <input type="number" name="price_from" id="price_from" step="0.01" min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                               value="{{ old('price_from', $activity->price_from) }}" placeholder="e.g., 150.00">
                    </div>
                    <div>
                        <label for="price_to" class="block text-sm font-medium text-gray-700 mb-2">Price To</label>
                        <input type="number" name="price_to" id="price_to" step="0.01" min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                               value="{{ old('price_to', $activity->price_to) }}" placeholder="e.g., 300.00">
                    </div>
                    <div>
                        <label for="currency" class="block text-sm font-medium text-gray-700 mb-2">Currency</label>
                        <select name="currency" id="currency"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                            <option value="USD" {{ old('currency', $activity->currency) == 'USD' ? 'selected' : '' }}>USD ($)</option>
                            <option value="EUR" {{ old('currency', $activity->currency) == 'EUR' ? 'selected' : '' }}>EUR (€)</option>
                            <option value="GBP" {{ old('currency', $activity->currency) == 'GBP' ? 'selected' : '' }}>GBP (£)</option>
                            <option value="UGX" {{ old('currency', $activity->currency) == 'UGX' ? 'selected' : '' }}>UGX (USh)</option>
                        </select>
                    </div>
                </div>

                <div class="mb-6">
                    <label for="special_notes" class="block text-sm font-medium text-gray-700 mb-2">Special Notes & Additional Information</label>
                    <textarea name="special_notes" id="special_notes" rows="5"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                              placeholder="Any additional important information...">{{ old('special_notes', $activity->special_notes) }}</textarea>
                </div>
            </div>

            {{-- ══════════════════════════════════════════════════════ --}}
            {{-- TAB 5: Images & Media                                  --}}
            {{-- ══════════════════════════════════════════════════════ --}}
            <div id="tab-images" class="tab-content hidden">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Images & Media</h2>

                <div class="mb-8 p-4 bg-gray-50 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-800 mb-4">Current Images</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @if($activity->icon)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Current Icon</label>
                            <img src="{{ asset('storage/' . $activity->icon) }}" alt="{{ $activity->name }}" class="w-32 h-32 object-cover rounded-lg border-2 border-gray-300">
                        </div>
                        @endif
                        @if($activity->image)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Current Main Image</label>
                            <img src="{{ asset('storage/' . $activity->image) }}" alt="{{ $activity->name }}" class="w-full h-48 object-cover rounded-lg border-2 border-gray-300">
                        </div>
                        @endif
                        @if($activity->featured_image)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Current Featured Image</label>
                            <img src="{{ asset('storage/' . $activity->featured_image) }}" alt="{{ $activity->name }}" class="w-full h-48 object-cover rounded-lg border-2 border-gray-300">
                        </div>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="icon" class="block text-sm font-medium text-gray-700 mb-2">
                            New Activity Icon (Small) {{ $activity->icon ? '(Replace current)' : '' }}
                        </label>
                        <input type="file" name="icon" id="icon" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <div id="icon-preview" class="mt-3"></div>
                    </div>
                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                            New Main Thumbnail {{ $activity->image ? '(Replace current)' : '' }}
                        </label>
                        <input type="file" name="image" id="image" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <div id="image-preview" class="mt-3"></div>
                    </div>
                </div>

                <div class="mb-6">
                    <label for="featured_image" class="block text-sm font-medium text-gray-700 mb-2">
                        New Featured Header Image {{ $activity->featured_image ? '(Replace current)' : '' }}
                    </label>
                    <input type="file" name="featured_image" id="featured_image" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    <div id="featured-preview" class="mt-3"></div>
                </div>

                <div class="border-t pt-6">
                    <h3 class="text-lg font-medium text-gray-800 mb-4">Gallery Images</h3>
                    @if($activity->images->count() > 0)
                    <div class="mb-6">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4" id="existing-gallery">
                            @foreach($activity->images as $image)
                            <div class="relative group gallery-item" data-image-id="{{ $image->id }}">
                                <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $image->title }}"
                                     class="w-full h-40 object-cover rounded-lg border-2 {{ $image->is_featured ? 'border-yellow-400' : 'border-gray-300' }}">
                                <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex items-center justify-center gap-2">
                                    @if(!$image->is_featured)
                                    <button type="button" class="set-featured-btn bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm" data-image-id="{{ $image->id }}">Set Featured</button>
                                    @endif
                                    <button type="button" class="delete-image-btn bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm" data-image-id="{{ $image->id }}">Delete</button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    <div>
                        <label for="gallery_images" class="block text-sm font-medium text-gray-700 mb-2">Add More Gallery Images</label>
                        <input type="file" name="gallery_images[]" id="gallery_images" accept="image/*" multiple class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <div id="gallery-preview" class="mt-3 grid grid-cols-2 md:grid-cols-4 gap-4"></div>
                    </div>
                </div>
            </div>

            {{-- ══════════════════════════════════════════════════════ --}}
            {{-- TAB 6: SEO & Settings                                  --}}
            {{-- ══════════════════════════════════════════════════════ --}}
            <div id="tab-seo" class="tab-content hidden">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">SEO & Settings</h2>

                <div class="mb-6">
                    <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-2">Meta Title</label>
                    <input type="text" name="meta_title" id="meta_title" maxlength="60"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                           value="{{ old('meta_title', $activity->meta_title) }}">
                </div>
                <div class="mb-6">
                    <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
                    <textarea name="meta_description" id="meta_description" rows="3" maxlength="160"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">{{ old('meta_description', $activity->meta_description) }}</textarea>
                </div>
                <div class="mb-6">
                    <label for="meta_keywords" class="block text-sm font-medium text-gray-700 mb-2">Meta Keywords</label>
                    <input type="text" name="meta_keywords" id="meta_keywords"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                           value="{{ old('meta_keywords', $activity->meta_keywords) }}">
                </div>

                <div class="border-t pt-6 space-y-3">
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1"
                               class="w-5 h-5 rounded border-gray-300 text-green-600 focus:ring-green-500"
                               {{ old('is_active', $activity->is_active) ? 'checked' : '' }}>
                        <span class="text-sm font-medium text-gray-700">Active</span>
                    </label>
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" name="is_popular" value="1"
                               class="w-5 h-5 rounded border-gray-300 text-green-600 focus:ring-green-500"
                               {{ old('is_popular', $activity->is_popular) ? 'checked' : '' }}>
                        <span class="text-sm font-medium text-gray-700">Mark as Popular</span>
                    </label>
                </div>
            </div>

        </div><!-- /.p-6 -->

        <!-- Form Actions -->
        <div class="border-t bg-gray-50 px-6 py-4 flex justify-between items-center rounded-b-lg">
            <a href="{{ route('admin.activities.index') }}" class="text-gray-600 hover:text-gray-800 font-medium">Cancel</a>
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium transition flex items-center">
                <i class="fas fa-save mr-2"></i> Update Activity
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ══════════════════════════════════════════════════════════════════
    // 1. TAB SWITCHING
    // ══════════════════════════════════════════════════════════════════
    document.querySelectorAll('.tab-button').forEach(function (button) {
        button.addEventListener('click', function () {
            var targetTab = this.dataset.tab;
            document.querySelectorAll('.tab-button').forEach(function (btn) {
                btn.classList.remove('active', 'border-green-500', 'text-green-600');
                btn.classList.add('border-transparent', 'text-gray-500');
            });
            this.classList.add('active', 'border-green-500', 'text-green-600');
            this.classList.remove('border-transparent', 'text-gray-500');
            document.querySelectorAll('.tab-content').forEach(function (c) {
                c.classList.add('hidden');
            });
            document.getElementById('tab-' + targetTab).classList.remove('hidden');
        });
    });

    // ══════════════════════════════════════════════════════════════════
    // 2. SLUG AUTO-GENERATION  (null-safe — fixes original crash)
    // ══════════════════════════════════════════════════════════════════
    var nameField = document.getElementById('name');
    var slugField = document.getElementById('slug');
    if (nameField && slugField) {
        nameField.addEventListener('input', function () {
            if (!slugField.dataset.manualEdit) {
                slugField.value = this.value
                    .toLowerCase()
                    .replace(/[^\w\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-');
            }
        });
        slugField.addEventListener('input', function () {
            this.dataset.manualEdit = 'true';
        });
    }

    // ══════════════════════════════════════════════════════════════════
    // 3. CUSTOM EQUIPMENT ROWS
    //
    //    BUG FIX: Previously, removing the first/only row left the UI
    //    empty and the "+" button couldn't add more because the container
    //    had no input to clone the field name from.
    //
    //    Fix: if the user tries to remove the LAST row, we just clear the
    //    input value instead of removing the row — there's always at least
    //    one row present for the "Add custom item" button to work with.
    // ══════════════════════════════════════════════════════════════════
    var customContainer = document.getElementById('equipment-custom-container');

    function buildCustomRow(value) {
        var row = document.createElement('div');
        row.className = 'flex gap-2 mb-2 equipment-custom-row';
        row.innerHTML =
            '<input type="text" name="equipment_provided[]" value="' + (value || '') + '" ' +
                'class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500" ' +
                'placeholder="Custom equipment item">' +
            '<button type="button" ' +
                'class="remove-custom-equipment shrink-0 bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg text-sm transition">' +
                '<i class="fas fa-minus"></i>' +
            '</button>';
        return row;
    }

    document.getElementById('add-custom-equipment').addEventListener('click', function () {
        var row = buildCustomRow('');
        customContainer.appendChild(row);
        row.querySelector('input').focus();
    });

    customContainer.addEventListener('click', function (e) {
        var btn = e.target.closest('.remove-custom-equipment');
        if (!btn) return;
        var rows = customContainer.querySelectorAll('.equipment-custom-row');
        if (rows.length <= 1) {
            // Last row — clear input value only, keep the row
            rows[0].querySelector('input').value = '';
            rows[0].querySelector('input').focus();
        } else {
            btn.closest('.equipment-custom-row').remove();
        }
    });

    // ══════════════════════════════════════════════════════════════════
    // 4. EQUIPMENT PRESET SEARCH FILTER
    // ══════════════════════════════════════════════════════════════════
    var equipSearch = document.getElementById('equipment-search');
    if (equipSearch) {
        equipSearch.addEventListener('input', function () {
            var q = this.value.toLowerCase().trim();
            document.querySelectorAll('.equipment-item').forEach(function (lbl) {
                lbl.style.display = (!q || lbl.dataset.label.includes(q)) ? '' : 'none';
            });
            // Hide entire groups if all their items are filtered out
            document.querySelectorAll('.equipment-group').forEach(function (grp) {
                var hasVisible = Array.from(grp.querySelectorAll('.equipment-item'))
                                      .some(function (l) { return l.style.display !== 'none'; });
                grp.style.display = hasVisible ? '' : 'none';
            });
        });
    }

    var equipClear = document.getElementById('equipment-clear');
    if (equipClear) {
        equipClear.addEventListener('click', function () {
            document.querySelectorAll('#equipment-preset-grid input[type="checkbox"]')
                    .forEach(function (cb) { cb.checked = false; });
        });
    }

    // ══════════════════════════════════════════════════════════════════
    // 5. FORMATTING TOOLBAR — What to Expect & Highlights
    //
    //    Toolbar is BELOW the textarea so it stays in view after
    //    scrolling through long content — no need to scroll back up.
    //
    //    **Title**  → saved as plain text with ** markers.
    //    formatContent() in show.blade.php already converts this to a
    //    green <h3> — zero DB or backend changes required.
    //    Preview panel mirrors that transformation exactly.
    // ══════════════════════════════════════════════════════════════════

    /** Mirrors the PHP formatContent() from show.blade.php */
    function formatContentPreview(text) {
        if (!text) return '<em style="color:#9ca3af;">Nothing to preview yet.</em>';

        // **Title** → green h3
        text = text.replace(/\*\*([^*]+)\*\*/g, function (m, title) {
            return '<h3 style="color:#059669;font-size:1.05rem;font-weight:700;' +
                   'margin:1rem 0 0.4rem;padding-bottom:0.35rem;' +
                   'border-bottom:2px solid #d1fae5;">' + title.trim() + '</h3>';
        });

        // "- item" → bullet
        text = text.replace(/^[ \t]*-[ \t]+(.+)$/gm, function (m, content) {
            return '<span style="display:flex;align-items:flex-start;gap:6px;margin:2px 0;">' +
                   '<span style="color:#059669;font-weight:700;flex-shrink:0;line-height:1.6;">•</span>' +
                   '<span>' + content + '</span></span>';
        });

        // --- divider
        text = text.replace(/[-]{3,}/g, '<hr style="border:none;border-top:1px solid #e5e7eb;margin:0.6rem 0;">');

        // Collapse excess blank lines
        text = text.replace(/\n{3,}/g, '\n\n');

        // Wrap plain lines in <p>
        return text.split('\n').map(function (line) {
            if (line.trim() === '') return '<div style="height:0.35rem;"></div>';
            if (line.trim().charAt(0) === '<') return line;
            return '<p style="margin:0 0 0.2rem;">' + line + '</p>';
        }).join('');
    }

    /** Insert text at cursor; wraps selected text if any */
    function insertAtCursor(ta, before, after, placeholder) {
        ta.focus();
        var s   = ta.selectionStart;
        var e   = ta.selectionEnd;
        var sel = ta.value.substring(s, e);
        ta.value = ta.value.substring(0, s) + before + (sel || placeholder) + after + ta.value.substring(e);
        ta.selectionStart = s + before.length;
        ta.selectionEnd   = s + before.length + (sel || placeholder).length;
        ta.focus();
        ta.dispatchEvent(new Event('input'));
    }

    /** Convert escaped \n in data-* attributes to real newlines */
    function decode(s) { return (s || '').replace(/\\n/g, '\n'); }

    // Wire INSERT buttons
    document.querySelectorAll('.fmt-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var ta = document.getElementById(this.dataset.target);
            if (!ta) return;
            insertAtCursor(ta, decode(this.dataset.before), decode(this.dataset.after), this.dataset.placeholder || '');
        });
    });

    // Wire PREVIEW toggle buttons
    document.querySelectorAll('.preview-toggle-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var ta         = document.getElementById(this.dataset.target);
            var previewBox = document.getElementById(this.dataset.preview);
            if (!ta || !previewBox) return;
            var opening = previewBox.classList.contains('hidden');
            previewBox.classList.toggle('hidden', !opening);
            this.innerHTML = opening
                ? '<svg class="w-3 h-3 inline mr-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">' +
                  '<path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>' +
                  '</svg> Hide'
                : '<svg class="w-3 h-3 inline mr-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">' +
                  '<path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>' +
                  '<path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>' +
                  '</svg> Preview';
            if (opening) {
                previewBox.querySelector('.preview-output').innerHTML = formatContentPreview(ta.value);
            }
        });
    });

    // Live preview update while typing
    ['what_to_expect', 'highlights'].forEach(function (id) {
        var ta = document.getElementById(id);
        if (!ta) return;
        ta.addEventListener('input', function () {
            var pid        = id === 'what_to_expect' ? 'wte_preview' : 'hl_preview';
            var previewBox = document.getElementById(pid);
            if (previewBox && !previewBox.classList.contains('hidden')) {
                previewBox.querySelector('.preview-output').innerHTML = formatContentPreview(ta.value);
            }
        });
    });

});
</script>

<style>
/* ── Tabs ─────────────────────────────────────────────────────────── */
.tab-button.active             { border-color: #10b981; color: #10b981; }
.tab-button:not(.active)       { border-color: transparent; color: #6b7280; }
.tab-button:not(.active):hover { color: #374151; border-color: #d1d5db; }

/* ── Formatting toolbar ───────────────────────────────────────────── */
/* Sits below the textarea — squash any remaining top border/radius   */
.fmt-toolbar {
    border-top:              1px solid #d1d5db !important;
    border-top-left-radius:  0 !important;
    border-top-right-radius: 0 !important;
}
/* Remove bottom radius from the textarea that sits above a toolbar   */
#what_to_expect,
#highlights {
    border-bottom-left-radius:  0;
    border-bottom-right-radius: 0;
    /* Monospaced so markdown markers align cleanly */
    font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
    font-size:   0.8rem;
    line-height: 1.8;
}

/* ── Toolbar buttons ─────────────────────────────────────────────── */
.fmt-btn {
    display:      inline-flex;
    align-items:  center;
    gap:          4px;
    font-size:    0.72rem;
    padding:      3px 9px;
    border:       1px solid #d1d5db;
    border-radius:6px;
    background:   #fff;
    color:        #374151;
    cursor:       pointer;
    white-space:  nowrap;
    transition:   background 0.12s, border-color 0.12s, color 0.12s;
}
.fmt-btn:hover { background: #f0fdf4; border-color: #6ee7b7; color: #065f46; }

.preview-toggle-btn {
    display:      inline-flex;
    align-items:  center;
    gap:          4px;
    font-size:    0.72rem;
    padding:      3px 9px;
    border:       1px solid #93c5fd;
    border-radius:6px;
    background:   #eff6ff;
    color:        #1d4ed8;
    cursor:       pointer;
    white-space:  nowrap;
    transition:   background 0.12s;
}
.preview-toggle-btn:hover { background: #dbeafe; }
</style>
@endpush
@endsection