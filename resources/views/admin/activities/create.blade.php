@extends('layouts.admin')

@section('title', 'Create Activity')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Create New Activity</h1>
        <a href="{{ route('admin.activities.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center transition">
            <i class="fas fa-arrow-left mr-2"></i> Back to List
        </a>
    </div>

    <!-- Error Messages -->
    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Create Form -->
    <form action="{{ route('admin.activities.store') }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-lg rounded-lg">
        @csrf

        <!-- Tabs Navigation -->
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px">
                <button type="button" class="tab-button active px-6 py-3 border-b-2 font-medium text-sm" data-tab="basic">
                    Basic Information
                </button>
                <button type="button" class="tab-button px-6 py-3 border-b-2 font-medium text-sm" data-tab="detailed">
                    Detailed Content
                </button>
                <button type="button" class="tab-button px-6 py-3 border-b-2 font-medium text-sm" data-tab="practical">
                    Practical Info
                </button>
                <button type="button" class="tab-button px-6 py-3 border-b-2 font-medium text-sm" data-tab="pricing">
                    Pricing & Booking
                </button>
                <button type="button" class="tab-button px-6 py-3 border-b-2 font-medium text-sm" data-tab="images">
                    Images & Media
                </button>
                <button type="button" class="tab-button px-6 py-3 border-b-2 font-medium text-sm" data-tab="seo">
                    SEO & Settings
                </button>
            </nav>
        </div>

        <div class="p-6">
            <!-- TAB 1: Basic Information -->
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
                               value="{{ old('slug') }}" placeholder="auto-generated-from-name">
                        @error('slug')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-gray-500 text-xs mt-1">Leave empty to auto-generate from name</p>
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Category
                        </label>
                        <select name="category_id" id="category_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('category_id') border-red-500 @enderror">
                            <option value="">Select Category (Optional)</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Primary Destination -->
                    <div>
                        <label for="destination_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Primary Destination
                        </label>
                        <select name="destination_id" id="destination_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('destination_id') border-red-500 @enderror">
                            <option value="">Select Destination (Optional)</option>
                            @foreach($destinations as $destination)
                                <option value="{{ $destination->id }}" {{ old('destination_id') == $destination->id ? 'selected' : '' }}>
                                    {{ $destination->name }} ({{ $destination->country->name }})
                                </option>
                            @endforeach
                        </select>
                        @error('destination_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- NEW: Multi-destination checkboxes -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Destinations where this activity can be carried out
                        </label>
                        <p class="text-xs text-gray-500 mb-2">
                            Select all destinations where this activity is available. This is separate from the single “Primary Destination” above.
                        </p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                            @foreach($destinations as $destination)
                                <label class="flex items-center space-x-2 text-sm cursor-pointer">
                                    <input
                                        type="checkbox"
                                        name="destinations[]"
                                        value="{{ $destination->id }}"
                                        class="rounded border-gray-300 text-green-600 focus:ring-green-500"
                                        {{ in_array($destination->id, old('destinations', [])) ? 'checked' : '' }}
                                    >
                                    <span class="text-gray-700">
                                        {{ $destination->name }}
                                        <span class="text-gray-400 text-xs">
                                            ({{ $destination->country->name }})
                                        </span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                        @error('destinations')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        @error('destinations.*')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Sort Order -->
                    <div>
                        <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">
                            Sort Order
                        </label>
                        <input type="number" name="sort_order" id="sort_order" min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                               value="{{ old('sort_order', 0) }}">
                        <p class="text-gray-500 text-xs mt-1">Lower numbers appear first</p>
                    </div>
                </div>

                <!-- Description -->
                <div class="mt-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Short Description
                    </label>
                    <textarea name="description" id="description" rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('description') border-red-500 @enderror"
                              placeholder="Brief overview of the activity (2-3 sentences)">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Countries -->
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Available in Countries
                    </label>
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

            <!-- TAB 2: Detailed Content -->
            <div id="tab-detailed" class="tab-content hidden">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Detailed Content</h2>

                <!-- Overview -->
                <div class="mb-6">
                    <label for="overview" class="block text-sm font-medium text-gray-700 mb-2">
                        Overview
                    </label>
                    <textarea name="overview" id="overview" rows="5"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                              placeholder="Comprehensive overview of the activity...">{{ old('overview') }}</textarea>
                    <p class="text-gray-500 text-xs mt-1">Detailed introduction to the activity</p>
                </div>

                <!-- What to Expect -->
                <div class="mb-6">
                    <label for="what_to_expect" class="block text-sm font-medium text-gray-700 mb-2">
                        What to Expect
                    </label>
                    <textarea name="what_to_expect" id="what_to_expect" rows="6"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                              placeholder="What participants can expect during this activity...">{{ old('what_to_expect') }}</textarea>
                </div>

                <!-- Highlights -->
                <div class="mb-6">
                    <label for="highlights" class="block text-sm font-medium text-gray-700 mb-2">
                        Highlights
                    </label>
                    <textarea name="highlights" id="highlights" rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                              placeholder="Key highlights and unique features...">{{ old('highlights') }}</textarea>
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

                    <!-- What to Bring -->
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

            <!-- TAB 3: Practical Info -->
            <div id="tab-practical" class="tab-content hidden">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Practical Information</h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <!-- Duration -->
                    <div>
                        <label for="duration" class="block text-sm font-medium text-gray-700 mb-2">
                            Duration
                        </label>
                        <input type="text" name="duration" id="duration"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                               value="{{ old('duration') }}" placeholder="e.g., Full Day, 2-8 hours">
                    </div>

                    <!-- Difficulty Level -->
                    <div>
                        <label for="difficulty_level" class="block text-sm font-medium text-gray-700 mb-2">
                            Difficulty Level
                        </label>
                        <select name="difficulty_level" id="difficulty_level"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                            <option value="">Select Difficulty</option>
                            <option value="easy" {{ old('difficulty_level') == 'easy' ? 'selected' : '' }}>Easy</option>
                            <option value="moderate" {{ old('difficulty_level') == 'moderate' ? 'selected' : '' }}>Moderate</option>
                            <option value="challenging" {{ old('difficulty_level') == 'challenging' ? 'selected' : '' }}>Challenging</option>
                            <option value="extreme" {{ old('difficulty_level') == 'extreme' ? 'selected' : '' }}>Extreme</option>
                        </select>
                    </div>

                    <!-- Min Age -->
                    <div>
                        <label for="min_age" class="block text-sm font-medium text-gray-700 mb-2">
                            Minimum Age
                        </label>
                        <input type="number" name="min_age" id="min_age" min="0" max="100"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                               value="{{ old('min_age') }}" placeholder="e.g., 15">
                    </div>

                    <!-- Max Group Size -->
                    <div>
                        <label for="max_group_size" class="block text-sm font-medium text-gray-700 mb-2">
                            Max Group Size
                        </label>
                        <input type="number" name="max_group_size" id="max_group_size" min="1"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                               value="{{ old('max_group_size') }}" placeholder="e.g., 8">
                    </div>
                </div>

                <!-- Regulations -->
                <div class="mb-6">
                    <label for="regulations" class="block text-sm font-medium text-gray-700 mb-2">
                        Rules & Regulations
                    </label>
                    <textarea name="regulations" id="regulations" rows="5"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                              placeholder="Important rules, permits, and regulations...">{{ old('regulations') }}</textarea>
                </div>

                <!-- Safety Info -->
                <div class="mb-6">
                    <label for="safety_info" class="block text-sm font-medium text-gray-700 mb-2">
                        Safety Information
                    </label>
                    <textarea name="safety_info" id="safety_info" rows="5"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                              placeholder="Safety guidelines and precautions...">{{ old('safety_info') }}</textarea>
                </div>

                <!-- Health Requirements -->
                <div class="mb-6">
                    <label for="health_requirements" class="block text-sm font-medium text-gray-700 mb-2">
                        Health Requirements
                    </label>
                    <textarea name="health_requirements" id="health_requirements" rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                              placeholder="Medical requirements, vaccinations, fitness level...">{{ old('health_requirements') }}</textarea>
                </div>

                <!-- Cultural Experience -->
                <div class="mb-6">
                    <label for="cultural_experience" class="block text-sm font-medium text-gray-700 mb-2">
                        Cultural Experience
                    </label>
                    <textarea name="cultural_experience" id="cultural_experience" rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                              placeholder="Cultural aspects and community involvement...">{{ old('cultural_experience') }}</textarea>
                </div>

                <!-- Conservation Info -->
                <div class="mb-6">
                    <label for="conservation_info" class="block text-sm font-medium text-gray-700 mb-2">
                        Conservation & Sustainability
                    </label>
                    <textarea name="conservation_info" id="conservation_info" rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                              placeholder="Conservation efforts and sustainable practices...">{{ old('conservation_info') }}</textarea>
                </div>
            </div>

            <!-- TAB 4: Pricing & Booking -->
            <div id="tab-pricing" class="tab-content hidden">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Pricing & Booking Information</h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <!-- Price From -->
                    <div>
                        <label for="price_from" class="block text-sm font-medium text-gray-700 mb-2">
                            Price From
                        </label>
                        <input type="number" name="price_from" id="price_from" step="0.01" min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                               value="{{ old('price_from') }}" placeholder="e.g., 150.00">
                    </div>

                    <!-- Price To -->
                    <div>
                        <label for="price_to" class="block text-sm font-medium text-gray-700 mb-2">
                            Price To
                        </label>
                        <input type="number" name="price_to" id="price_to" step="0.01" min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                               value="{{ old('price_to') }}" placeholder="e.g., 300.00">
                    </div>

                    <!-- Currency -->
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

                <!-- Special Notes -->
                <div class="mb-6">
                    <label for="special_notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Special Notes & Additional Information
                    </label>
                    <textarea name="special_notes" id="special_notes" rows="5"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                              placeholder="Any additional important information...">{{ old('special_notes') }}</textarea>
                </div>
            </div>

            <!-- TAB 5: Images & Media -->
            <div id="tab-images" class="tab-content hidden">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Images & Media</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Icon -->
                    <div>
                        <label for="icon" class="block text-sm font-medium text-gray-700 mb-2">
                            Activity Icon (Small)
                        </label>
                        <input type="file" name="icon" id="icon" accept="image/*"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                        <p class="text-gray-500 text-xs mt-1">Recommended: 100x100px, Max: 1MB</p>
                        <div id="icon-preview" class="mt-3"></div>
                    </div>

                    <!-- Main Image -->
                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                            Main Thumbnail Image
                        </label>
                        <input type="file" name="image" id="image" accept="image/*"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                        <p class="text-gray-500 text-xs mt-1">Recommended: 800x600px, Max: 2MB</p>
                        <div id="image-preview" class="mt-3"></div>
                    </div>
                </div>

                <!-- Featured Image (Header Background) -->
                <div class="mb-6">
                    <label for="featured_image" class="block text-sm font-medium text-gray-700 mb-2">
                        Featured Header Image (Large)
                    </label>
                    <input type="file" name="featured_image" id="featured_image" accept="image/*"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                    <p class="text-gray-500 text-xs mt-1">This will be used as the hero/header background image. Recommended: 1920x1080px, Max: 5MB</p>
                    <div id="featured-preview" class="mt-3"></div>
                </div>

                <!-- Gallery Images -->
                <div>
                    <label for="gallery_images" class="block text-sm font-medium text-gray-700 mb-2">
                        Gallery Images (Multiple)
                    </label>
                    <input type="file" name="gallery_images[]" id="gallery_images" accept="image/*" multiple
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                    <p class="text-gray-500 text-xs mt-1">Select multiple images. Recommended: 1200x800px each, Max: 5MB per image</p>
                    <div id="gallery-preview" class="mt-3 grid grid-cols-2 md:grid-cols-4 gap-4"></div>
                </div>
            </div>

            <!-- TAB 6: SEO & Settings -->
            <div id="tab-seo" class="tab-content hidden">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">SEO & Settings</h2>

                <!-- Meta Title -->
                <div class="mb-6">
                    <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-2">
                        Meta Title
                    </label>
                    <input type="text" name="meta_title" id="meta_title" maxlength="60"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                           value="{{ old('meta_title') }}" placeholder="SEO title (leave empty to use activity name)">
                    <p class="text-gray-500 text-xs mt-1">Recommended: 50-60 characters</p>
                </div>

                <!-- Meta Description -->
                <div class="mb-6">
                    <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-2">
                        Meta Description
                    </label>
                    <textarea name="meta_description" id="meta_description" rows="3" maxlength="160"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                              placeholder="SEO description (leave empty to use activity description)">{{ old('meta_description') }}</textarea>
                    <p class="text-gray-500 text-xs mt-1">Recommended: 150-160 characters</p>
                </div>

                <!-- Meta Keywords -->
                <div class="mb-6">
                    <label for="meta_keywords" class="block text-sm font-medium text-gray-700 mb-2">
                        Meta Keywords
                    </label>
                    <input type="text" name="meta_keywords" id="meta_keywords"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                           value="{{ old('meta_keywords') }}" placeholder="keyword1, keyword2, keyword3">
                    <p class="text-gray-500 text-xs mt-1">Comma-separated keywords</p>
                </div>

                <!-- Status Checkboxes -->
                <div class="border-t pt-6">
                    <h3 class="text-lg font-medium text-gray-800 mb-4">Status Settings</h3>
                    
                    <div class="space-y-3">
                        <!-- Is Active -->
                        <label class="flex items-center space-x-3 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1"
                                   class="w-5 h-5 rounded border-gray-300 text-green-600 focus:ring-green-500"
                                   {{ old('is_active', true) ? 'checked' : '' }}>
                            <div>
                                <span class="text-sm font-medium text-gray-700">Active</span>
                                <p class="text-xs text-gray-500">Make this activity visible on the website</p>
                            </div>
                        </label>

                        <!-- Is Popular -->
                        <label class="flex items-center space-x-3 cursor-pointer">
                            <input type="checkbox" name="is_popular" value="1"
                                   class="w-5 h-5 rounded border-gray-300 text-green-600 focus:ring-green-500"
                                   {{ old('is_popular') ? 'checked' : '' }}>
                            <div>
                                <span class="text-sm font-medium text-gray-700">Mark as Popular</span>
                                <p class="text-xs text-gray-500">Feature this activity prominently on homepage</p>
                            </div>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="border-t bg-gray-50 px-6 py-4 flex justify-between items-center rounded-b-lg">
            <a href="{{ route('admin.activities.index') }}" class="text-gray-600 hover:text-gray-800 font-medium">
                Cancel
            </a>
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium transition flex items-center">
                <i class="fas fa-save mr-2"></i> Create Activity
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
        
        // Update buttons
        document.querySelectorAll('.tab-button').forEach(btn => {
            btn.classList.remove('active', 'border-green-500', 'text-green-600');
            btn.classList.add('border-transparent', 'text-gray-500');
        });
        this.classList.add('active', 'border-green-500', 'text-green-600');
        this.classList.remove('border-transparent', 'text-gray-500');
        
        // Update content
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
            .replace(/-+/g, '-');
        slugField.value = slug;
    }
});

document.getElementById('slug').addEventListener('input', function() {
    this.dataset.manualEdit = 'true';
});

// Image Previews
function previewImage(inputId, previewId, maxWidth = 300, maxHeight = 200) {
    document.getElementById(inputId).addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById(previewId);
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = `
                    <div class="relative inline-block">
                        <img src="${e.target.result}" class="rounded-lg shadow-md border border-gray-300" 
                             style="max-width: ${maxWidth}px; max-height: ${maxHeight}px; object-fit: cover;">
                        <div class="absolute top-2 right-2 bg-green-500 text-white px-2 py-1 rounded text-xs">
                            Preview
                        </div>
                    </div>
                `;
            }
            reader.readAsDataURL(file);
        } else {
            preview.innerHTML = '';
        }
    });
}

previewImage('icon', 'icon-preview', 100, 100);
previewImage('image', 'image-preview', 300, 200);
previewImage('featured_image', 'featured-preview', 600, 400);

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
                <div class="absolute top-1 right-1 bg-green-500 text-white px-2 py-1 rounded text-xs">
                    ${i + 1}
                </div>
            `;
            preview.appendChild(div);
        }
        
        reader.readAsDataURL(file);
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