@extends('layouts.admin')

@section('title', 'Create Activity')

@section('content')
<div class="container mx-auto px-4 py-6">

    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Create New Activity</h1>
        <a href="{{ route('admin.activities.index') }}"
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center transition">
            <i class="fas fa-arrow-left mr-2"></i> Back to List
        </a>
    </div>

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.activities.store') }}" method="POST"
          enctype="multipart/form-data" class="bg-white shadow-lg rounded-lg">
        @csrf

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

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Activity Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('name') border-red-500 @enderror"
                               value="{{ old('name') }}" placeholder="e.g., Gorilla Trekking">
                        @error('name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- Slug -->
                    <div>
                        <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">URL Slug</label>
                        <input type="text" name="slug" id="slug"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('slug') border-red-500 @enderror"
                               value="{{ old('slug') }}" placeholder="auto-generated-from-name">
                        @error('slug')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        <p class="text-gray-500 text-xs mt-1">Leave empty to auto-generate from name</p>
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                        <select name="category_id" id="category_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('category_id') border-red-500 @enderror">
                            <option value="">Select Category (Optional)</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- Primary Destination -->
                    <div>
                        <label for="destination_id" class="block text-sm font-medium text-gray-700 mb-2">Primary Destination</label>
                        <select name="destination_id" id="destination_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('destination_id') border-red-500 @enderror">
                            <option value="">Select Destination (Optional)</option>
                            @foreach($destinations as $destination)
                                <option value="{{ $destination->id }}" {{ old('destination_id') == $destination->id ? 'selected' : '' }}>
                                    {{ $destination->name }} ({{ $destination->country->name }})
                                </option>
                            @endforeach
                        </select>
                        @error('destination_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- Multi-destination checkboxes -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Destinations where this activity can be carried out
                        </label>
                        <p class="text-xs text-gray-500 mb-2">Select all destinations where this activity is available.</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                            @foreach($destinations as $destination)
                                <label class="flex items-center space-x-2 text-sm cursor-pointer">
                                    <input type="checkbox" name="destinations[]" value="{{ $destination->id }}"
                                           class="rounded border-gray-300 text-green-600 focus:ring-green-500"
                                           {{ in_array($destination->id, old('destinations', [])) ? 'checked' : '' }}>
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

                    <!-- Sort Order -->
                    <div>
                        <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">Sort Order</label>
                        <input type="number" name="sort_order" id="sort_order" min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                               value="{{ old('sort_order', 0) }}">
                        <p class="text-gray-500 text-xs mt-1">Lower numbers appear first</p>
                    </div>
                </div>

                <!-- Short Description -->
                <div class="mt-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Short Description</label>
                    <textarea name="description" id="description" rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('description') border-red-500 @enderror"
                              placeholder="Brief overview of the activity (2-3 sentences)">{{ old('description') }}</textarea>
                    @error('description')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <!-- Countries -->
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Available in Countries</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach($countries as $country)
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" name="countries[]" value="{{ $country->id }}"
                                   class="rounded border-gray-300 text-green-600 focus:ring-green-500"
                                   {{ in_array($country->id, old('countries', [])) ? 'checked' : '' }}>
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

                <!-- Overview -->
                <div class="mb-6">
                    <label for="overview" class="block text-sm font-medium text-gray-700 mb-2">Overview</label>
                    <textarea name="overview" id="overview" rows="5"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                              placeholder="Comprehensive overview of the activity...">{{ old('overview') }}</textarea>
                </div>

                {{-- ────────────────────────────────────────────────────────
                     WHAT TO EXPECT — toolbar docked BELOW textarea
                ─────────────────────────────────────────────────────────── --}}
                <div class="mb-6">
                    <label for="what_to_expect" class="block text-sm font-medium text-gray-700 mb-2">
                        What to Expect
                        <span class="ml-2 text-xs font-normal text-gray-400">
                            — type <code class="bg-gray-100 px-1 rounded">**Title**</code> for subheadings,
                            <code class="bg-gray-100 px-1 rounded">- item</code> for bullets
                        </span>
                    </label>

                    <textarea name="what_to_expect" id="what_to_expect" rows="10"
                              class="w-full px-4 py-2 border border-gray-300 border-b-0 rounded-t-lg focus:outline-none focus:ring-2 focus:ring-green-500 font-mono text-sm leading-relaxed"
                              placeholder="Example:&#10;**Morning Experience**&#10;- Meet your guide at the park gate at 6am&#10;- Drive to the forest trailhead&#10;&#10;**The Trek**&#10;- Walk through dense jungle with a ranger&#10;- Encounter the gorilla family group">{{ old('what_to_expect') }}</textarea>

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
                                title="Inserts a bullet point">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/>
                            </svg>
                            Bullet
                        </button>

                        <button type="button" class="fmt-btn"
                                data-target="what_to_expect"
                                data-before="\n---\n" data-after=""
                                data-placeholder=""
                                title="Inserts a horizontal divider">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14"/>
                            </svg>
                            Divider
                        </button>

                        <div class="flex-1"></div>

                        <button type="button" class="preview-toggle-btn"
                                data-target="what_to_expect" data-preview="wte_preview">
                            <svg class="w-3 h-3 inline mr-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            Preview
                        </button>
                    </div>

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
                     HIGHLIGHTS — toolbar docked BELOW textarea
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
                              placeholder="Example:&#10;**Wildlife Encounters**&#10;- Get within metres of mountain gorillas&#10;- Spot rare Albertine Rift bird species&#10;&#10;**Scenery & Nature**&#10;- Lush ancient Bwindi Impenetrable Forest&#10;- Stunning crater lake panoramas">{{ old('highlights') }}</textarea>

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
                                title="Inserts a bullet point">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/>
                            </svg>
                            Bullet
                        </button>

                        <button type="button" class="fmt-btn"
                                data-target="highlights"
                                data-before="\n---\n" data-after=""
                                data-placeholder=""
                                title="Inserts a horizontal divider">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14"/>
                            </svg>
                            Divider
                        </button>

                        <div class="flex-1"></div>

                        <button type="button" class="preview-toggle-btn"
                                data-target="highlights" data-preview="hl_preview">
                            <svg class="w-3 h-3 inline mr-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            Preview
                        </button>
                    </div>

                <!-- Dynamic Fields (Inclusions, Exclusions, etc.) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Inclusions -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            What's Included
                        </label>
                        <div id="inclusions-container">
                            <div class="flex gap-2 mb-2">
                                <input type="text" name="inclusions[]" 
                                       class="flex-1 px-4 py-2 border border-gray-300 rounded-lg"
                                       placeholder="e.g., Professional guide">
                                <button type="button" class="add-field bg-green-500 text-white px-3 py-2 rounded-lg" data-container="inclusions-container">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Exclusions -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            What's NOT Included
                        </label>
                        <div id="exclusions-container">
                            <div class="flex gap-2 mb-2">
                                <input type="text" name="exclusions[]" 
                                       class="flex-1 px-4 py-2 border border-gray-300 rounded-lg"
                                       placeholder="e.g., Personal expenses">
                                <button type="button" class="add-field bg-green-500 text-white px-3 py-2 rounded-lg" data-container="exclusions-container">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Equipment Provided -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Equipment Provided
                        </label>
                        <div id="equipment-container">
                            <div class="flex gap-2 mb-2">
                                <input type="text" name="equipment_provided[]" 
                                       class="flex-1 px-4 py-2 border border-gray-300 rounded-lg"
                                       placeholder="e.g., Fishing rods">
                                <button type="button" class="add-field bg-green-500 text-white px-3 py-2 rounded-lg" data-container="equipment-container">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- What to Bring (Reusable) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            What to Bring
                        </label>
                        <div id="bring-container">
                            <div class="flex gap-2 mb-2">
                                <input type="text" name="what_to_bring[]" 
                                       class="flex-1 px-4 py-2 border border-gray-300 rounded-lg"
                                       placeholder="e.g., Sunscreen">
                                <button type="button" class="add-field bg-green-500 text-white px-3 py-2 rounded-lg" data-container="bring-container">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
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
                               value="{{ old('duration') }}" placeholder="e.g., Full Day, 2-8 hours">
                    </div>
                    <div>
                        <label for="difficulty_level" class="block text-sm font-medium text-gray-700 mb-2">Difficulty Level</label>
                        <select name="difficulty_level" id="difficulty_level"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                            <option value="">Select Difficulty</option>
                            <option value="easy"        {{ old('difficulty_level') == 'easy'        ? 'selected' : '' }}>Easy</option>
                            <option value="moderate"    {{ old('difficulty_level') == 'moderate'    ? 'selected' : '' }}>Moderate</option>
                            <option value="challenging" {{ old('difficulty_level') == 'challenging' ? 'selected' : '' }}>Challenging</option>
                            <option value="extreme"     {{ old('difficulty_level') == 'extreme'     ? 'selected' : '' }}>Extreme</option>
                        </select>
                    </div>
                    <div>
                        <label for="min_age" class="block text-sm font-medium text-gray-700 mb-2">Minimum Age</label>
                        <input type="number" name="min_age" id="min_age" min="0" max="100"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                               value="{{ old('min_age') }}" placeholder="e.g., 15">
                    </div>
                    <div>
                        <label for="max_group_size" class="block text-sm font-medium text-gray-700 mb-2">Max Group Size</label>
                        <input type="number" name="max_group_size" id="max_group_size" min="1"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                               value="{{ old('max_group_size') }}" placeholder="e.g., 8">
                    </div>
                </div>

                <div class="mb-6">
                    <label for="regulations" class="block text-sm font-medium text-gray-700 mb-2">Rules & Regulations</label>
                    <textarea name="regulations" id="regulations" rows="5"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                              placeholder="Important rules, permits, and regulations...">{{ old('regulations') }}</textarea>
                </div>
                <div class="mb-6">
                    <label for="safety_info" class="block text-sm font-medium text-gray-700 mb-2">Safety Information</label>
                    <textarea name="safety_info" id="safety_info" rows="5"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                              placeholder="Safety guidelines and precautions...">{{ old('safety_info') }}</textarea>
                </div>
                <div class="mb-6">
                    <label for="health_requirements" class="block text-sm font-medium text-gray-700 mb-2">Health Requirements</label>
                    <textarea name="health_requirements" id="health_requirements" rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                              placeholder="Medical requirements, vaccinations, fitness level...">{{ old('health_requirements') }}</textarea>
                </div>
                <div class="mb-6">
                    <label for="cultural_experience" class="block text-sm font-medium text-gray-700 mb-2">Cultural Experience</label>
                    <textarea name="cultural_experience" id="cultural_experience" rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                              placeholder="Cultural aspects and community involvement...">{{ old('cultural_experience') }}</textarea>
                </div>
                <div class="mb-6">
                    <label for="conservation_info" class="block text-sm font-medium text-gray-700 mb-2">Conservation & Sustainability</label>
                    <textarea name="conservation_info" id="conservation_info" rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                              placeholder="Conservation efforts and sustainable practices...">{{ old('conservation_info') }}</textarea>
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
                               value="{{ old('price_from') }}" placeholder="e.g., 150.00">
                    </div>
                    <div>
                        <label for="price_to" class="block text-sm font-medium text-gray-700 mb-2">Price To</label>
                        <input type="number" name="price_to" id="price_to" step="0.01" min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                               value="{{ old('price_to') }}" placeholder="e.g., 300.00">
                    </div>
                    <div>
                        <label for="currency" class="block text-sm font-medium text-gray-700 mb-2">Currency</label>
                        <select name="currency" id="currency"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                            <option value="USD" {{ old('currency', 'USD') == 'USD' ? 'selected' : '' }}>USD ($)</option>
                            <option value="EUR" {{ old('currency') == 'EUR' ? 'selected' : '' }}>EUR (€)</option>
                            <option value="GBP" {{ old('currency') == 'GBP' ? 'selected' : '' }}>GBP (£)</option>
                            <option value="UGX" {{ old('currency') == 'UGX' ? 'selected' : '' }}>UGX (USh)</option>
                        </select>
                    </div>
                </div>

                <div class="mb-6">
                    <label for="special_notes" class="block text-sm font-medium text-gray-700 mb-2">Special Notes & Additional Information</label>
                    <textarea name="special_notes" id="special_notes" rows="5"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                              placeholder="Any additional important information...">{{ old('special_notes') }}</textarea>
                </div>
            </div>

            {{-- ══════════════════════════════════════════════════════ --}}
            {{-- TAB 5: Images & Media                                  --}}
            {{-- ══════════════════════════════════════════════════════ --}}
            <div id="tab-images" class="tab-content hidden">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Images & Media</h2>

                <!-- Tip banner -->
                <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg flex items-start gap-3">
                    <i class="fas fa-info-circle text-blue-500 mt-0.5 flex-shrink-0"></i>
                    <p class="text-sm text-blue-700">
                        You can upload images now or add them later by editing this activity.
                        The <strong>Featured Header Image</strong> is shown at the top of the activity page — use a wide, high-quality landscape photo.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="icon" class="block text-sm font-medium text-gray-700 mb-2">
                            Activity Icon <span class="text-gray-400 text-xs">(small, square — shown in listings)</span>
                        </label>
                        <input type="file" name="icon" id="icon" accept="image/*"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                        <div id="icon-preview" class="mt-3"></div>
                    </div>
                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                            Main Thumbnail <span class="text-gray-400 text-xs">(card image in activity lists)</span>
                        </label>
                        <input type="file" name="image" id="image" accept="image/*"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                        <div id="image-preview" class="mt-3"></div>
                    </div>
                </div>

                <div class="mb-6">
                    <label for="featured_image" class="block text-sm font-medium text-gray-700 mb-2">
                        Featured Header Image <span class="text-gray-400 text-xs">(full-width hero at top of activity page)</span>
                    </label>
                    <input type="file" name="featured_image" id="featured_image" accept="image/*"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                    <div id="featured-preview" class="mt-3"></div>
                </div>

                <div class="border-t pt-6">
                    <h3 class="text-lg font-medium text-gray-800 mb-2">Gallery Images</h3>
                    <p class="text-xs text-gray-500 mb-4">Upload multiple images at once. The first image will be set as the gallery featured image.</p>
                    <input type="file" name="gallery_images[]" id="gallery_images" accept="image/*" multiple
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                    <div id="gallery-preview" class="mt-3 grid grid-cols-2 md:grid-cols-4 gap-4"></div>
                </div>
            </div>

            {{-- ══════════════════════════════════════════════════════ --}}
            {{-- TAB 6: SEO & Settings                                  --}}
            {{-- ══════════════════════════════════════════════════════ --}}
            <div id="tab-seo" class="tab-content hidden">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">SEO & Settings</h2>

                <div class="mb-6">
                    <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-2">
                        Meta Title
                        <span class="text-gray-400 text-xs ml-1">(max 60 chars — leave blank to auto-use activity name)</span>
                    </label>
                    <input type="text" name="meta_title" id="meta_title" maxlength="60"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                           value="{{ old('meta_title') }}" placeholder="e.g., Gorilla Trekking in Bwindi | Safari">
                    <p class="text-xs text-gray-400 mt-1" id="meta-title-count">0 / 60</p>
                </div>

                <div class="mb-6">
                    <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-2">
                        Meta Description
                        <span class="text-gray-400 text-xs ml-1">(max 160 chars)</span>
                    </label>
                    <textarea name="meta_description" id="meta_description" rows="3" maxlength="160"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                              placeholder="A compelling 1-2 sentence description for search engines...">{{ old('meta_description') }}</textarea>
                    <p class="text-xs text-gray-400 mt-1" id="meta-desc-count">0 / 160</p>
                </div>

                <div class="mb-6">
                    <label for="meta_keywords" class="block text-sm font-medium text-gray-700 mb-2">Meta Keywords</label>
                    <input type="text" name="meta_keywords" id="meta_keywords"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                           value="{{ old('meta_keywords') }}" placeholder="gorilla trekking, bwindi, uganda, africa safari">
                </div>

                <div class="border-t pt-6 space-y-3">
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1"
                               class="w-5 h-5 rounded border-gray-300 text-green-600 focus:ring-green-500"
                               {{ old('is_active', '1') ? 'checked' : '' }}>
                        <div>
                            <span class="text-sm font-medium text-gray-700">Active</span>
                            <p class="text-xs text-gray-500">Activity is publicly visible on the website</p>
                        </div>
                    </label>
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" name="is_popular" value="1"
                               class="w-5 h-5 rounded border-gray-300 text-green-600 focus:ring-green-500"
                               {{ old('is_popular') ? 'checked' : '' }}>
                        <div>
                            <span class="text-sm font-medium text-gray-700">Mark as Popular</span>
                            <p class="text-xs text-gray-500">Adds a "Popular Activity" badge and boosts visibility</p>
                        </div>
                    </label>
                </div>
            </div>

        </div><!-- /.p-6 -->

        <!-- Form Actions -->
        <div class="border-t bg-gray-50 px-6 py-4 flex justify-between items-center rounded-b-lg">
            <a href="{{ route('admin.activities.index') }}"
               class="text-gray-600 hover:text-gray-800 font-medium">Cancel</a>
            <div class="flex items-center gap-3">
                {{-- Tab completion indicator --}}
                <span id="tab-hint" class="text-xs text-gray-400 hidden">
                    <i class="fas fa-info-circle mr-1"></i> Fill in other tabs for more detail
                </span>
                <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg font-medium transition flex items-center shadow-md">
                    <i class="fas fa-plus-circle mr-2"></i> Create Activity
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ══════════════════════════════════════════════════════════════════
    // 1. TAB SWITCHING
    //    Extra: show the "fill in other tabs" hint once the user has
    //    visited at least the first tab and is about to submit.
    // ══════════════════════════════════════════════════════════════════
    var visitedTabs = new Set(['basic']);

    document.querySelectorAll('.tab-button').forEach(function (button) {
        button.addEventListener('click', function () {
            var targetTab = this.dataset.tab;
            visitedTabs.add(targetTab);

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

            // Show hint if user hasn't visited all tabs yet
            var tabHint = document.getElementById('tab-hint');
            if (tabHint && visitedTabs.size < 6) {
                tabHint.classList.remove('hidden');
            } else if (tabHint) {
                tabHint.classList.add('hidden');
            }
        });
    });

    // ══════════════════════════════════════════════════════════════════
    // 2. SLUG AUTO-GENERATION
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
    // 3. SEO CHARACTER COUNTERS
    // ══════════════════════════════════════════════════════════════════
    function wireCounter(fieldId, counterId, max) {
        var field   = document.getElementById(fieldId);
        var counter = document.getElementById(counterId);
        if (!field || !counter) return;
        function update() {
            var len = field.value.length;
            counter.textContent = len + ' / ' + max;
            counter.className   = 'text-xs mt-1 ' + (len > max * 0.9 ? 'text-orange-500' : 'text-gray-400');
        }
        field.addEventListener('input', update);
        update();
    }
    wireCounter('meta_title',       'meta-title-count', 60);
    wireCounter('meta_description', 'meta-desc-count',  160);

    // ══════════════════════════════════════════════════════════════════
    // 4. IMAGE UPLOAD PREVIEWS
    // ══════════════════════════════════════════════════════════════════
    function wireImagePreview(inputId, previewId, thumbClass) {
        var input   = document.getElementById(inputId);
        var preview = document.getElementById(previewId);
        if (!input || !preview) return;
        input.addEventListener('change', function () {
            preview.innerHTML = '';
            Array.from(this.files).forEach(function (file) {
                if (!file.type.startsWith('image/')) return;
                var reader = new FileReader();
                reader.onload = function (e) {
                    var img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = thumbClass;
                    preview.appendChild(img);
                };
                reader.readAsDataURL(file);
            });
        });
    }
    wireImagePreview('icon',           'icon-preview',     'w-24 h-24 object-cover rounded-lg border border-gray-300');
    wireImagePreview('image',          'image-preview',    'w-full h-40 object-cover rounded-lg border border-gray-300');
    wireImagePreview('featured_image', 'featured-preview', 'w-full h-48 object-cover rounded-lg border border-gray-300');
    wireImagePreview('gallery_images', 'gallery-preview',  'w-full h-32 object-cover rounded-lg border border-gray-300');

    // ══════════════════════════════════════════════════════════════════
    // 5. CUSTOM EQUIPMENT ROWS
    //    Same bug-safe logic as edit.blade.php:
    //    removing the last row clears the input instead of deleting it.
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
            rows[0].querySelector('input').value = '';
            rows[0].querySelector('input').focus();
        } else {
            btn.closest('.equipment-custom-row').remove();
        }
    });

    // ══════════════════════════════════════════════════════════════════
    // 6. EQUIPMENT PRESET SEARCH & CLEAR
    // ══════════════════════════════════════════════════════════════════
    var equipSearch = document.getElementById('equipment-search');
    if (equipSearch) {
        equipSearch.addEventListener('input', function () {
            var q = this.value.toLowerCase().trim();
            document.querySelectorAll('.equipment-item').forEach(function (lbl) {
                lbl.style.display = (!q || lbl.dataset.label.includes(q)) ? '' : 'none';
            });
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
    // 7. FORMATTING TOOLBAR — What to Expect & Highlights
    // ══════════════════════════════════════════════════════════════════

    function formatContentPreview(text) {
        if (!text) return '<em style="color:#9ca3af;">Nothing to preview yet.</em>';

        text = text.replace(/\*\*([^*]+)\*\*/g, function (m, title) {
            return '<h3 style="color:#059669;font-size:1.05rem;font-weight:700;' +
                   'margin:1rem 0 0.4rem;padding-bottom:0.35rem;' +
                   'border-bottom:2px solid #d1fae5;">' + title.trim() + '</h3>';
        });
        text = text.replace(/^[ \t]*-[ \t]+(.+)$/gm, function (m, content) {
            return '<span style="display:flex;align-items:flex-start;gap:6px;margin:3px 0;">' +
                   '<span style="display:inline-block;width:1rem;height:1rem;margin-top:0.18em;border-radius:3px;' +
                   'background-image:url(\'data:image/svg+xml,%3Csvg xmlns=\\\'http://www.w3.org/2000/svg\\\' viewBox=\\\'0 0 20 20\\\'%3E%3Crect width=\\\'20\\\' height=\\\'20\\\' rx=\\\'4\\\' fill=\\\'%23059669\\\'/%3E%3Cpath d=\\\'M5 10.5l3.5 3.5L15 7\\\' stroke=\\\'white\\\' stroke-width=\\\'2.2\\\' stroke-linecap=\\\'round\\\' stroke-linejoin=\\\'round\\\' fill=\\\'none\\\'/%3E%3C/svg%3E\');background-size:cover;flex-shrink:0;"></span>' +
                   '<span>' + content + '</span></span>';
        });
        text = text.replace(/[-]{3,}/g, '<hr style="border:none;border-top:1px solid #e5e7eb;margin:0.6rem 0;">');
        text = text.replace(/\n{3,}/g, '\n\n');
        return text.split('\n').map(function (line) {
            if (line.trim() === '') return '<div style="height:0.35rem;"></div>';
            if (line.trim().charAt(0) === '<') return line;
            return '<p style="margin:0 0 0.2rem;">' + line + '</p>';
        }).join('');
    }

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
});

// Dynamic Field Addition
document.querySelectorAll('.add-field').forEach(button => {
    button.addEventListener('click', function() {
        const containerId = this.dataset.container;
        const container = document.getElementById(containerId);
        const fieldName = container.querySelector('input').name;
        
        const newField = document.createElement('div');
        newField.className = 'flex gap-2 mb-2';
        newField.innerHTML = `
            <input type="text" name="${fieldName}" 
                   class="flex-1 px-4 py-2 border border-gray-300 rounded-lg"
                   placeholder="Add item...">
            <button type="button" class="remove-field bg-red-500 text-white px-3 py-2 rounded-lg">
                <i class="fas fa-minus"></i>
            </button>
        `;
        
        container.appendChild(newField);
        
        // Add remove functionality
        newField.querySelector('.remove-field').addEventListener('click', function() {
            newField.remove();
        });
    });
});

// Remove field functionality for initial fields
document.addEventListener('click', function(e) {
    if (e.target.closest('.remove-field')) {
        e.target.closest('.flex').remove();
    }
});
</script>

<style>
/* ── Tabs ─────────────────────────────────────────────────────────── */
.tab-button.active             { border-color: #10b981; color: #10b981; }
.tab-button:not(.active)       { border-color: transparent; color: #6b7280; }
.tab-button:not(.active):hover { color: #374151; border-color: #d1d5db; }

/* ── Toolbar: sits BELOW textarea, flush ─────────────────────────── */
.fmt-toolbar {
    border-top:              1px solid #d1d5db !important;
    border-top-left-radius:  0 !important;
    border-top-right-radius: 0 !important;
}
#what_to_expect,
#highlights {
    border-bottom-left-radius:  0;
    border-bottom-right-radius: 0;
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