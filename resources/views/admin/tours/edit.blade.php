@extends('layouts.admin')

@section('title', 'Edit Tour')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Edit Tour</h1>
        <p class="mt-2 text-sm text-gray-600">Update the details below to modify the tour package</p>
    </div>

    @if (session('success'))
<div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6 rounded-r-lg">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.707a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 10-1.414 1.414L9 13.414l4.707-4.707z" clip-rule="evenodd"/>
            </svg>
        </div>
        <div class="ml-3">
            <p class="text-sm font-medium text-green-800">Success</p>
            <p class="mt-1 text-sm text-green-700">{{ session('success') }}</p>
        </div>
    </div>
</div>
@endif
    @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">There were errors with your submission</h3>
                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <style>
        /* Searchable Accommodation Dropdown Styles */
        .acc-dropdown-wrapper {
            position: relative;
        }
        .acc-search-input {
            width: 100%;
            padding: 0.5rem 2.5rem 0.5rem 1rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            line-height: 1.25rem;
            outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
            background: white;
            cursor: pointer;
        }
        .acc-search-input:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2);
        }
        .acc-search-input.has-value {
            border-color: #6366f1;
            background-color: #eef2ff;
            color: #3730a3;
            font-weight: 500;
        }
        .acc-chevron {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
            color: #9ca3af;
            transition: transform 0.2s;
        }
        .acc-chevron.open {
            transform: translateY(-50%) rotate(180deg);
            color: #6366f1;
        }
        .acc-dropdown-menu {
            position: absolute;
            z-index: 50;
            width: 100%;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05);
            margin-top: 4px;
            overflow: hidden;
            display: none;
        }
        .acc-dropdown-menu.open {
            display: block;
        }
        .acc-search-box {
            padding: 0.5rem;
            border-bottom: 1px solid #f3f4f6;
            position: sticky;
            top: 0;
            background: white;
            z-index: 1;
        }
        .acc-search-box input {
            width: 100%;
            padding: 0.4rem 0.75rem 0.4rem 2rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.375rem;
            font-size: 0.8rem;
            outline: none;
            background: #f9fafb;
        }
        .acc-search-box input:focus {
            border-color: #6366f1;
            background: white;
        }
        .acc-search-icon {
            position: absolute;
            left: 1.25rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            pointer-events: none;
        }
        .acc-options-list {
            max-height: 220px;
            overflow-y: auto;
            padding: 0.25rem 0;
        }
        .acc-option {
            padding: 0.5rem 0.875rem;
            cursor: pointer;
            font-size: 0.875rem;
            color: #374151;
            display: flex;
            flex-direction: column;
            transition: background 0.1s;
        }
        .acc-option:hover, .acc-option.highlighted {
            background: #eef2ff;
            color: #3730a3;
        }
        .acc-option.selected {
            background: #e0e7ff;
            color: #3730a3;
            font-weight: 600;
        }
        .acc-option .acc-option-name {
            font-weight: 500;
        }
        .acc-option .acc-option-meta {
            font-size: 0.75rem;
            color: #6b7280;
            margin-top: 1px;
        }
        .acc-option.highlighted .acc-option-meta,
        .acc-option:hover .acc-option-meta,
        .acc-option.selected .acc-option-meta {
            color: #6366f1;
        }
        .acc-option-none {
            padding: 0.5rem 0.875rem;
            font-size: 0.875rem;
            color: #6b7280;
            font-style: italic;
        }
        .acc-no-results {
            padding: 1rem 0.875rem;
            font-size: 0.875rem;
            color: #9ca3af;
            text-align: center;
        }
        .acc-clear-btn {
            position: absolute;
            right: 2rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            cursor: pointer;
            display: none;
            background: none;
            border: none;
            padding: 0;
            line-height: 1;
        }
        .acc-clear-btn:hover {
            color: #ef4444;
        }
        .acc-clear-btn.visible {
            display: block;
        }
        mark.acc-highlight {
            background: #fef08a;
            color: inherit;
            border-radius: 2px;
            padding: 0 1px;
        }
    </style>

    <form id="edit-tour-form" action="{{ route('admin.tours.update', $tour->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('PUT')
        
        <!-- Basic Information Section -->
        <div class="bg-white shadow-sm rounded-lg border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900">Basic Information</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            Tour Title <span class="text-red-500">*</span>
                        </label>
                        <input 
                            name="title" 
                            id="title" 
                            type="text" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150" 
                            value="{{ old('title', $tour->title) }}" 
                            required
                            placeholder="e.g., 7-Day Safari Adventure">
                    </div>
                    
                    <div>
                        <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">Slug</label>
                        <input 
                            name="slug" 
                            id="slug" 
                            type="text" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150" 
                            value="{{ old('slug', $tour->slug) }}"
                            placeholder="auto-generated-from-title">
                    </div>

                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                            Category <span class="text-red-500">*</span>
                        </label>
                        <input 
                            name="category" 
                            id="category" 
                            type="text" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150" 
                            value="{{ old('category', $tour->category) }}" 
                            required
                            placeholder="e.g., Wildlife Safari">
                    </div>

                    <div>
                        <label for="destinations" class="block text-sm font-medium text-gray-700 mb-2">
                            Destinations <span class="text-red-500">*</span>
                        </label>
                        <input 
                            name="destinations" 
                            id="destinations" 
                            type="text" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150" 
                            value="{{ old('destinations', $tour->destinations) }}" 
                            required
                            placeholder="e.g., Serengeti, Ngorongoro Crater">
                    </div>

                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                            Tour Type <span class="text-red-500">*</span>
                        </label>
                        <input 
                            name="type" 
                            id="type" 
                            type="text" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150" 
                            value="{{ old('type', $tour->type) }}" 
                            required
                            placeholder="e.g., Group Tour, Private Tour">
                    </div>

                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Description <span class="text-red-500">*</span>
                        </label>
                        <textarea 
                            name="description" 
                            id="description" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 resize-y" 
                            rows="4" 
                            required
                            placeholder="Describe the tour experience...">{{ old('description', $tour->description) }}</textarea>
                    </div>

                    <div class="md:col-span-2">
                        <label for="included" class="block text-sm font-medium text-gray-700 mb-2">What's Included</label>
                        <textarea 
                            name="included" 
                            id="included" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 resize-y" 
                            rows="4" 
                            placeholder="Enter included items, each on a new line">{{ old('included', $tour->included) }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">Enter each item on a new line</p>
                    </div>

                    <div class="md:col-span-2">
                        <label for="excluded" class="block text-sm font-medium text-gray-700 mb-2">What's Excluded</label>
                        <textarea 
                            name="excluded" 
                            id="excluded" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 resize-y" 
                            rows="4" 
                            placeholder="Enter excluded items, each on a new line">{{ old('excluded', $tour->excluded) }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">Enter each item on a new line</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- SEO Section -->
        <div class="bg-white shadow-sm rounded-lg border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900">SEO Settings</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Meta Keywords</label>
                        <div class="flex gap-2 mb-3">
                            <input 
                                type="text" 
                                id="meta_keyword_input" 
                                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150" 
                                placeholder="Add keyword and press Enter">
                            <button 
                                type="button" 
                                id="add-meta-keyword" 
                                class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-150 flex items-center gap-2 font-medium">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Add
                            </button>
                        </div>
                        <div id="meta_keywords_tags" class="flex flex-wrap gap-2 min-h-[2rem]"></div>
                        <input type="hidden" name="meta_keywords" id="meta_keywords" value="{{ old('meta_keywords', $tour->meta_keywords) }}">
                        <p class="mt-2 text-sm text-gray-500">Keywords will be separated by commas</p>
                    </div>

                    <div class="md:col-span-2">
                        <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-2">Meta Title</label>
                        <input 
                            name="meta_title" 
                            id="meta_title" 
                            type="text" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150" 
                            value="{{ old('meta_title', $tour->meta_title) }}"
                            placeholder="SEO-friendly title for search engines">
                    </div>

                    <div class="md:col-span-2">
                        <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
                        <textarea 
                            name="meta_description" 
                            id="meta_description" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 resize-y" 
                            rows="3"
                            placeholder="Brief description for search engine results">{{ old('meta_description', $tour->meta_description) }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">Recommended length: 150-160 characters</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Featured Image Section -->
        <div class="bg-white shadow-sm rounded-lg border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900">Featured Image</h2>
            </div>
            <div class="p-6">
                <label for="featured_image" class="block text-sm font-medium text-gray-700 mb-2">Upload New Image (Leave blank to keep current)</label>
                <input 
                    name="featured_image" 
                    id="featured_image" 
                    type="file" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" 
                    accept="image/*">
                
                @if($tour->featured_image)
                <div class="mt-4">
                    <p class="text-sm font-medium text-gray-700 mb-2">Current Image:</p>
                    <div class="relative inline-block">
                        <img src="{{ asset('storage/' . $tour->featured_image) }}" class="h-32 w-auto rounded-lg border-2 border-gray-200 shadow-md">
                        <div class="absolute -top-2 -right-2 bg-blue-500 text-white rounded-full p-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                </div>
                @endif
                
                <div id="featured_image_preview" class="mt-4"></div>
            </div>
        </div>

        <!-- Itinerary Section -->
        <div class="bg-white shadow-sm rounded-lg border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900">Itinerary Days</h2>
            </div>
            <div class="p-6">
                <div id="itinerary-days" class="space-y-4"></div>
                <div id="no-itinerary-message" class="text-center py-8 text-gray-500">
                    <svg class="w-16 h-16 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="font-medium">No itinerary days added yet</p>
                    <p class="text-sm mt-1">Click "Add Day" below to create your tour schedule</p>
                </div>
                <button 
                    type="button" 
                    id="add-itinerary-day" 
                    class="w-full mt-4 px-4 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-150 flex items-center justify-center gap-2 font-medium shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Day
                </button>
            </div>
        </div>

        <!-- Prices Section -->
        <div class="bg-white shadow-sm rounded-lg border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900">Pricing</h2>
            </div>
            <div class="p-6">
                <div id="prices" class="space-y-4"></div>
                <div id="no-prices-message" class="text-center py-8 text-gray-500">
                    <svg class="w-16 h-16 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="font-medium">No pricing tiers added yet</p>
                    <p class="text-sm mt-1">Click "Add Price" below to set pricing for different group sizes</p>
                </div>
                <button 
                    type="button" 
                    id="add-price" 
                    class="w-full mt-4 px-4 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-150 flex items-center justify-center gap-2 font-medium shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Price
                </button>
            </div>
        </div>

        <!-- Images Gallery Section -->
        <div class="bg-white shadow-sm rounded-lg border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900">Image Gallery</h2>
            </div>
            <div class="p-6">
                <div id="images-list" class="space-y-4"></div>
                <div id="no-images-message" class="text-center py-8 text-gray-500">
                    <svg class="w-16 h-16 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="font-medium">No gallery images added yet</p>
                    <p class="text-sm mt-1">Click "Add Image" below to upload photos for the tour gallery</p>
                </div>
                <button 
                    type="button" 
                    id="add-image" 
                    class="w-full mt-4 px-4 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-150 flex items-center justify-center gap-2 font-medium shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Image
                </button>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
            <button 
                type="button" 
                onclick="window.history.back()" 
                class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-150 font-medium">
                Cancel
            </button>
            <button 
                type="submit" 
                class="px-8 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition duration-150 font-semibold shadow-sm flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Update Tour
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const STORAGE_KEY = 'tour_edit_form_data_{{ $tour->id }}';

    // Accommodations data from backend
    const accommodationsData = @json($accommodations);

    // --- small helpers ---
    function uuid() {
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
            const r = Math.random() * 16 | 0, v = c === 'x' ? r : (r & 0x3 | 0x8);
            return v.toString(16);
        });
    }
    function tempId() {
        return 'tmp-' + Math.random().toString(36).substr(2, 9);
    }

    // --- AUTO-RESIZE TEXTAREA ---
    function autoResize(textarea) {
        textarea.style.height = 'auto';
        textarea.style.height = textarea.scrollHeight + 'px';
    }

    // =====================================================================
    // SEARCHABLE ACCOMMODATION DROPDOWN
    // =====================================================================

    /**
     * Creates a fully searchable accommodation dropdown and mounts it into `container`.
     * Returns { getValue(), setValue(id), onChange(callback) }
     * The hidden <input> for form submission uses `inputName`.
     */
    function createAccommodationDropdown(container, inputName, initialValue) {
        let selectedId = null;
        let changeCallbacks = [];
        let highlightedIndex = -1;
        let filteredOptions = [];

        // Build flat option list
        const options = [
            { id: null, name: 'No Accommodation', type: '', location: '', label: 'No Accommodation' },
            ...accommodationsData.map(acc => ({
                id:       acc.id,
                name:     acc.name,
                type:     acc.type     || '',
                location: acc.location || '',
                label:    acc.name + (acc.type ? ' – ' + acc.type : '') + (acc.location ? ' (' + acc.location + ')' : '')
            }))
        ];

        // ---- DOM ----
        const wrapper = document.createElement('div');
        wrapper.className = 'acc-dropdown-wrapper';

        const trigger = document.createElement('input');
        trigger.type = 'text';
        trigger.readOnly = true;
        trigger.className = 'acc-search-input';
        trigger.placeholder = 'Select Accommodation (Optional)';
        trigger.setAttribute('autocomplete', 'off');

        const chevron = document.createElement('span');
        chevron.className = 'acc-chevron';
        chevron.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>`;

        const clearBtn = document.createElement('button');
        clearBtn.type = 'button';
        clearBtn.className = 'acc-clear-btn';
        clearBtn.title = 'Clear selection';
        clearBtn.innerHTML = `<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>`;

        const menu = document.createElement('div');
        menu.className = 'acc-dropdown-menu';

        const searchBox = document.createElement('div');
        searchBox.className = 'acc-search-box';
        searchBox.style.position = 'relative';
        const searchIcon = document.createElement('span');
        searchIcon.className = 'acc-search-icon';
        searchIcon.innerHTML = `<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>`;
        const searchInput = document.createElement('input');
        searchInput.type = 'text';
        searchInput.placeholder = 'Search accommodations…';
        searchInput.setAttribute('autocomplete', 'off');
        searchBox.appendChild(searchIcon);
        searchBox.appendChild(searchInput);

        const optionsList = document.createElement('div');
        optionsList.className = 'acc-options-list';

        menu.appendChild(searchBox);
        menu.appendChild(optionsList);

        const hiddenInput = document.createElement('input');
        hiddenInput.type  = 'hidden';
        hiddenInput.name  = inputName;
        hiddenInput.value = '';

        wrapper.appendChild(trigger);
        wrapper.appendChild(clearBtn);
        wrapper.appendChild(chevron);
        wrapper.appendChild(menu);
        wrapper.appendChild(hiddenInput);
        container.appendChild(wrapper);

        // ---- Helpers ----
        function escapeHtml(str) {
            return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
        }

        function highlightText(text, query) {
            if (!query) return escapeHtml(text);
            const idx = text.toLowerCase().indexOf(query.toLowerCase());
            if (idx === -1) return escapeHtml(text);
            return escapeHtml(text.slice(0, idx))
                 + '<mark class="acc-highlight">' + escapeHtml(text.slice(idx, idx + query.length)) + '</mark>'
                 + escapeHtml(text.slice(idx + query.length));
        }

        function renderOptions(query) {
            query = (query || '').trim();
            optionsList.innerHTML = '';
            highlightedIndex = -1;

            filteredOptions = options.filter(opt => {
                if (!query) return true;
                const q = query.toLowerCase();
                return opt.name.toLowerCase().includes(q)
                    || opt.type.toLowerCase().includes(q)
                    || opt.location.toLowerCase().includes(q);
            });

            if (filteredOptions.length === 0) {
                const noRes = document.createElement('div');
                noRes.className = 'acc-no-results';
                noRes.textContent = 'No accommodations found';
                optionsList.appendChild(noRes);
                return;
            }

            filteredOptions.forEach((opt, idx) => {
                const el = document.createElement('div');
                el.className = 'acc-option' + (opt.id == selectedId ? ' selected' : '') + (opt.id === null ? ' acc-option-none' : '');
                el.dataset.index = idx;

                if (opt.id === null) {
                    el.innerHTML = `<span class="acc-option-name" style="font-style:italic;color:#6b7280">No Accommodation</span>`;
                } else {
                    el.innerHTML = `
                        <span class="acc-option-name">${highlightText(opt.name, query)}</span>
                        ${(opt.type || opt.location) ? `<span class="acc-option-meta">${escapeHtml([opt.type, opt.location].filter(Boolean).join(' · '))}</span>` : ''}
                    `;
                }

                el.addEventListener('mousedown', function(e) {
                    e.preventDefault();
                    selectOption(opt);
                });

                optionsList.appendChild(el);
            });
        }

        function selectOption(opt) {
            selectedId        = opt.id;
            hiddenInput.value = opt.id !== null ? opt.id : '';

            if (opt.id === null) {
                trigger.value = '';
                trigger.classList.remove('has-value');
                clearBtn.classList.remove('visible');
            } else {
                trigger.value = opt.label;
                trigger.classList.add('has-value');
                clearBtn.classList.add('visible');
            }

            closeMenu();
            changeCallbacks.forEach(cb => cb(opt.id, opt.id !== null ? accommodationsData.find(a => a.id == opt.id) : null));
        }

        function openMenu() {
            menu.classList.add('open');
            chevron.classList.add('open');
            searchInput.value = '';
            renderOptions('');
            setTimeout(() => searchInput.focus(), 50);
        }

        function closeMenu() {
            menu.classList.remove('open');
            chevron.classList.remove('open');
            highlightedIndex = -1;
        }

        // ---- Events ----
        trigger.addEventListener('click', function() {
            menu.classList.contains('open') ? closeMenu() : openMenu();
        });

        clearBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            selectOption(options[0]);
        });

        searchInput.addEventListener('input', function() {
            renderOptions(this.value);
        });

        searchInput.addEventListener('keydown', function(e) {
            const items = optionsList.querySelectorAll('.acc-option');
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                highlightedIndex = Math.min(highlightedIndex + 1, items.length - 1);
                updateHighlight(items);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                highlightedIndex = Math.max(highlightedIndex - 1, 0);
                updateHighlight(items);
            } else if (e.key === 'Enter') {
                e.preventDefault();
                if (highlightedIndex >= 0 && filteredOptions[highlightedIndex]) {
                    selectOption(filteredOptions[highlightedIndex]);
                }
            } else if (e.key === 'Escape') {
                closeMenu();
                trigger.focus();
            }
        });

        function updateHighlight(items) {
            items.forEach((el, i) => {
                el.classList.toggle('highlighted', i === highlightedIndex);
                if (i === highlightedIndex) el.scrollIntoView({ block: 'nearest' });
            });
        }

        document.addEventListener('mousedown', function(e) {
            if (!wrapper.contains(e.target)) closeMenu();
        });

        // ---- Public API ----
        function getValue() { return selectedId; }

        function setValue(id) {
            const opt = options.find(o => o.id == id) || options[0];
            selectedId        = opt.id;
            hiddenInput.value = opt.id !== null ? opt.id : '';
            if (opt.id !== null) {
                trigger.value = opt.label;
                trigger.classList.add('has-value');
                clearBtn.classList.add('visible');
            } else {
                trigger.value = '';
                trigger.classList.remove('has-value');
                clearBtn.classList.remove('visible');
            }
        }

        function onChange(cb) { changeCallbacks.push(cb); }

        if (initialValue) setValue(initialValue);

        return { getValue, setValue, onChange };
    }

    // =====================================================================

    // --- LOAD SAVED DATA FROM LOCALSTORAGE ---
    function loadSavedData() {
        const savedData = localStorage.getItem(STORAGE_KEY);
        if (savedData) {
            try {
                const data = JSON.parse(savedData);
                
                if (data.formFields) {
                    Object.keys(data.formFields).forEach(key => {
                        const field = document.querySelector(`[name="${key}"]`);
                        if (field && !field.value) {
                            field.value = data.formFields[key];
                        }
                    });
                }
                
                if (data.itineraryDays && itineraryDays.length === 0) itineraryDays = data.itineraryDays;
                if (data.prices && prices.length === 0) prices = data.prices;
                if (data.metaKeywords && metaKeywords.length === 0) metaKeywords = data.metaKeywords;
                if (data.imagesList) imagesList = data.imagesList;
                
                renderItinerary();
                renderPrices();
                renderKeywords();
                renderImagesList();
            } catch (e) {
                console.error('Error loading saved data:', e);
            }
        }
    }

    // --- SAVE TO LOCALSTORAGE ---
    function saveFormData() {
        const formFields = {};
        const inputs = document.querySelectorAll('input[type="text"], input[type="number"], textarea, select');
        inputs.forEach(input => {
            if (input.name && !input.name.startsWith('itinerary[') && !input.name.startsWith('prices[') && input.name !== 'meta_keywords' && !input.name.includes('images[') && !input.name.includes('delete_images[') && !input.name.includes('uploads[')) {
                formFields[input.name] = input.value;
            }
        });

        const data = {
            formFields,
            itineraryDays,
            prices,
            metaKeywords,
            imagesList: imagesList.map(img => ({preview: img.preview, id: img.id || null, path: img.path || null}))
        };

        localStorage.setItem(STORAGE_KEY, JSON.stringify(data));
    }

    // --- CLEAR STORAGE ON SUBMIT ---
    document.getElementById('edit-tour-form').addEventListener('submit', function(e) {
        buildAndAttachContentBlocks();
        localStorage.removeItem(STORAGE_KEY);
    });

    // --- FEATURED IMAGE PREVIEW ---
    const featuredInput = document.getElementById('featured_image');
    if (featuredInput) {
        featuredInput.addEventListener('change', function(e) {
            let preview = document.getElementById('featured_image_preview');
            preview.innerHTML = '';
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(ev) {
                    preview.innerHTML = `
                        <div>
                            <p class="text-sm font-medium text-gray-700 mb-2">New Image Preview:</p>
                            <div class="relative inline-block">
                                <img src="${ev.target.result}" class="h-32 w-auto rounded-lg border-2 border-gray-200 shadow-md">
                                <div class="absolute -top-2 -right-2 bg-green-500 text-white rounded-full p-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    `;
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // --- LOAD ACCOMMODATION IMAGES ---
    async function loadAccommodationImages(accommodationId) {
        try {
            const response = await fetch(`/admin/api/accommodations/${accommodationId}`);
            const data = await response.json();
            if (data.success) {
                return data.data.images || [];
            }
        } catch (error) {
            console.error('Error loading accommodation images:', error);
        }
        return [];
    }

    // --- PRELOAD ITINERARY DAYS WITH EXISTING DATA ---
    let itineraryDays = [];
    @if($tour->itinerary && count($tour->itinerary) > 0)
        itineraryDays = {!! json_encode($tour->itinerary->map(function($item) {
            $images = [];
            if (method_exists($item, 'images') && $item->images && count($item->images) > 0) {
                $images = $item->images->map(function($img) {
                    return [
                        'existingMediaId' => $img->id,
                        'preview' => $img->thumbnail_path ? asset('storage/' . ltrim($img->thumbnail_path, '/')) : ($img->storage_path ? asset('storage/' . ltrim($img->storage_path, '/')) : null),
                        'caption' => $img->caption,
                        'blockId' => $img->block_id,
                        'storage_path' => $img->storage_path,
                        'thumbnail_path' => $img->thumbnail_path,
                    ];
                })->values()->toArray();
            }
            return [
                'id' => $item->id ?? null,
                'activity' => $item->activity ?? '',
                'day_title' => $item->day_title ?? '',
                'accommodation' => $item->accommodation ?? '',
                'accommodation_id' => $item->accommodation_id ?? null,
                'meals' => $item->meals ?? '',
                'blocks' => $item->content_blocks ?? null,
                'images' => $images
            ];
        })->values()->toArray()) !!};
    @endif

    // --- DYNAMIC ITINERARIES RENDERING ---
    function renderItinerary() {
        const container = document.getElementById('itinerary-days');
        const noMessage = document.getElementById('no-itinerary-message');
        
        if (itineraryDays.length === 0) {
            noMessage.style.display = 'block';
            container.innerHTML = '';
            return;
        }
        
        noMessage.style.display = 'none';
        container.innerHTML = '';
        
        itineraryDays.forEach((day, i) => {
            const dayNum = i + 1;
            if (!Array.isArray(day.images)) day.images = [];
            day.images.forEach(img => {
                if (!img.tempId && !img.existingMediaId) img.tempId = tempId();
                if (!img.blockId) img.blockId = 'blk-' + uuid();
            });

            const el = document.createElement('div');
            el.className = "relative bg-gradient-to-r from-indigo-50 to-purple-50 rounded-lg p-6 border-2 border-indigo-200 hover:border-indigo-300 transition duration-150";
            el.innerHTML = `
                <div class="absolute top-4 left-4 bg-indigo-600 text-white rounded-full w-10 h-10 flex items-center justify-center font-bold text-lg shadow-md">
                    ${dayNum}
                </div>
                <input type="hidden" name="itinerary[${dayNum}][day_number]" value="${dayNum}">
                ${day.id ? `<input type="hidden" name="itinerary[${dayNum}][id]" value="${day.id}">` : ''}
                <input type="hidden" data-contentblock-input name="itinerary[${dayNum}][content_blocks]" value="">
                <div class="ml-14 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Activity <span class="text-red-500">*</span>
                        </label>
                        <textarea 
                            name="itinerary[${dayNum}][activity]" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 resize-none overflow-hidden auto-resize" 
                            required 
                            placeholder="Describe the day's activities..."
                            rows="1">${day.activity || ''}</textarea>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Day Title</label>
                            <input 
                                type="text" 
                                name="itinerary[${dayNum}][day_title]" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150" 
                                value="${day.day_title || ''}"
                                placeholder="e.g., Arrival Day">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Accommodation</label>
                            <div id="acc-dropdown-mount-${i}" class="acc-mount"></div>
                            <input type="hidden" name="itinerary[${dayNum}][accommodation]" value="${day.accommodation || ''}">
                            <div id="accommodation-images-${i}" class="mt-3 hidden">
                                <p class="text-sm text-gray-600 mb-2">Accommodation Images:</p>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-2" id="accommodation-images-grid-${i}"></div>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Meals</label>
                            <input 
                                type="text" 
                                name="itinerary[${dayNum}][meals]" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150" 
                                value="${day.meals || ''}"
                                placeholder="e.g., B, L, D">
                        </div>
                    </div>

                    <!-- Itinerary Day Images -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Day Images (optional)</label>
                        <div id="day-images-${dayNum}" class="space-y-3"></div>
                        <div class="flex gap-2 mt-3">
                            <button type="button" class="px-3 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 add-day-image" data-index="${i}">
                                Add Image
                            </button>
                            <p class="text-sm text-gray-500">Add images to appear inline with this day's description.</p>
                        </div>
                    </div>
                </div>
                
                <button 
                    type="button" 
                    class="absolute top-4 right-4 bg-red-600 hover:bg-red-700 text-white rounded-lg px-3 py-2 transition duration-150 flex items-center gap-2 font-medium text-sm shadow-sm remove-day" 
                    data-index="${i}" 
                    title="Remove Day">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Remove
                </button>
            `;

            // Remove day action
            el.querySelector('.remove-day').onclick = function() {
                itineraryDays.splice(i, 1);
                renderItinerary();
                saveFormData();
            };

            container.appendChild(el);

            // Set content_blocks hidden input value
            const cbInput = el.querySelector('[data-contentblock-input]');
            try {
                cbInput.value = day.blocks && Array.isArray(day.blocks) ? JSON.stringify(day.blocks) : '';
            } catch (err) {
                cbInput.value = '';
                console.error('Error serializing blocks for day', dayNum, err);
            }

            // ---- Mount searchable accommodation dropdown ----
            const mountPoint = el.querySelector(`#acc-dropdown-mount-${i}`);
            const accDropdown = createAccommodationDropdown(
                mountPoint,
                `itinerary[${dayNum}][accommodation_id]`,
                day.accommodation_id
            );

            accDropdown.onChange(async function(accommodationId, accObj) {
                day.accommodation_id = accommodationId || null;

                const imagesContainer = document.getElementById(`accommodation-images-${i}`);
                const imagesGrid      = document.getElementById(`accommodation-images-grid-${i}`);

                if (accommodationId) {
                    const images = await loadAccommodationImages(accommodationId);
                    if (images.length > 0) {
                        imagesGrid.innerHTML = '';
                        images.forEach(img => {
                            const imgDiv = document.createElement('div');
                            imgDiv.className = 'relative group';
                            imgDiv.innerHTML = `
                                <img src="${img.url}" alt="${img.alt_text || ''}" class="w-full h-20 object-cover rounded border border-gray-200">
                                <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white text-xs p-1 rounded-b opacity-0 group-hover:opacity-100 transition">${img.caption || 'No caption'}</div>
                            `;
                            imagesGrid.appendChild(imgDiv);
                        });
                        imagesContainer.classList.remove('hidden');
                    } else {
                        imagesContainer.classList.add('hidden');
                    }
                } else {
                    imagesContainer.classList.add('hidden');
                }
                saveFormData();
            });

            // If accommodation already selected on load, fetch images immediately
            if (day.accommodation_id) {
                (async () => {
                    const imagesContainer = document.getElementById(`accommodation-images-${i}`);
                    const imagesGrid      = document.getElementById(`accommodation-images-grid-${i}`);
                    const images = await loadAccommodationImages(day.accommodation_id);
                    if (images.length > 0) {
                        imagesGrid.innerHTML = '';
                        images.forEach(img => {
                            const imgDiv = document.createElement('div');
                            imgDiv.className = 'relative group';
                            imgDiv.innerHTML = `
                                <img src="${img.url}" alt="${img.alt_text || ''}" class="w-full h-20 object-cover rounded border border-gray-200">
                                <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white text-xs p-1 rounded-b opacity-0 group-hover:opacity-100 transition">${img.caption || 'No caption'}</div>
                            `;
                            imagesGrid.appendChild(imgDiv);
                        });
                        imagesContainer.classList.remove('hidden');
                    }
                })();
            }

            // render day images
            const dayImagesContainer = document.getElementById(`day-images-${dayNum}`);
            function renderDayImages() {
                dayImagesContainer.innerHTML = '';
                day.images.forEach((imgObj, imgIndex) => {
                    if (!imgObj.tempId && !imgObj.existingMediaId) imgObj.tempId = tempId();
                    if (!imgObj.blockId) imgObj.blockId = 'blk-' + uuid();

                    const wrapper = document.createElement('div');
                    wrapper.className = 'flex items-start gap-4 bg-white p-3 rounded-lg border border-gray-200';
                    wrapper.innerHTML = `
                        <div class="w-20 h-20 bg-gray-100 rounded-lg overflow-hidden flex items-center justify-center">
                            <img id="it-${dayNum}-img-preview-${imgIndex}" src="${imgObj.preview || ''}" class="w-full h-full object-cover" style="display:${imgObj.preview ? 'block':'none'}">
                            <svg class="w-8 h-8 text-gray-400" style="display:${imgObj.preview ? 'none':'block'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            ${imgObj.existingMediaId ? `
                                <input type="hidden" name="itinerary[${dayNum}][existing_media_ids][]" value="${imgObj.existingMediaId}">
                                <p class="text-sm font-medium mb-1">Existing image</p>
                                <p class="text-xs text-gray-500 truncate">${imgObj.storage_path || imgObj.preview || ''}</p>
                                <input type="text" name="itinerary[${dayNum}][image_captions][]" value="${imgObj.caption || ''}" placeholder="Caption (optional)" class="mt-2 w-full px-3 py-2 border border-gray-300 rounded-lg caption-input">
                            ` : `
                                <input type="file" accept="image/*" name="itinerary[${dayNum}][uploads][${imgObj.tempId}]" class="w-full image-input" data-day="${i}" data-img="${imgIndex}">
                                <input type="text" name="itinerary[${dayNum}][image_captions][]" value="${imgObj.caption || ''}" placeholder="Caption (optional)" class="mt-2 w-full px-3 py-2 border border-gray-300 rounded-lg caption-input">
                            `}
                        </div>
                        <div class="flex-shrink-0">
                            <button type="button" class="bg-red-600 hover:bg-red-700 text-white rounded-lg px-3 py-2 remove-day-image" data-day="${i}" data-img="${imgIndex}">
                                Remove
                            </button>
                        </div>
                    `;
                    dayImagesContainer.appendChild(wrapper);

                    const fileInput = wrapper.querySelector('.image-input');
                    if (fileInput) {
                        fileInput.addEventListener('change', function() {
                            const file = this.files[0];
                            if (file) {
                                const reader = new FileReader();
                                reader.onload = function(ev) {
                                    imgObj.preview = ev.target.result;
                                    const imgEl = document.getElementById(`it-${dayNum}-img-preview-${imgIndex}`);
                                    imgEl.src = ev.target.result;
                                    imgEl.style.display = 'block';
                                    if (imgEl.nextElementSibling) imgEl.nextElementSibling.style.display = 'none';
                                    saveFormData();
                                };
                                reader.readAsDataURL(file);
                            }
                        });
                    }

                    const captionInput = wrapper.querySelector('.caption-input');
                    if (captionInput) {
                        captionInput.addEventListener('input', function() {
                            imgObj.caption = this.value;
                            saveFormData();
                        });
                    }

                    wrapper.querySelector('.remove-day-image').addEventListener('click', function() {
                        const toRemove = day.images[imgIndex];
                        if (toRemove && toRemove.existingMediaId) {
                            const hiddenInput = document.createElement('input');
                            hiddenInput.type = 'hidden';
                            hiddenInput.name = 'delete_itinerary_image_ids[]';
                            hiddenInput.value = toRemove.existingMediaId;
                            document.querySelector('form').appendChild(hiddenInput);
                        }
                        day.images.splice(imgIndex, 1);
                        renderDayImages();
                        saveFormData();
                    });
                });
            }

            el.querySelector('.add-day-image').addEventListener('click', function() {
                day.images.push({preview: '', caption: '', tempId: tempId(), blockId: 'blk-' + uuid(), existingMediaId: null});
                renderDayImages();
                saveFormData();
            });

            const activityTextarea = el.querySelector('.auto-resize');
            activityTextarea.addEventListener('input', function() {
                autoResize(this);
                day.activity = this.value;
                saveFormData();
            });

            el.querySelectorAll('input[name^="itinerary"], textarea[name^="itinerary"], select[name^="itinerary"]').forEach(input => {
                input.addEventListener('input', function() {
                    const name = this.getAttribute('name');
                    const match = name.match(/itinerary\[\d+\]\[([^\]]+)\]/);
                    if (match) {
                        const key = match[1];
                        if (key === 'day_number' || key === 'id' || key === 'content_blocks') return;
                        if (key !== 'images' && key !== 'image_captions' && key !== 'uploads' && key !== 'existing_media_ids') {
                            day[key] = this.value;
                        }
                    }
                    saveFormData();
                });
            });

            renderDayImages();
            autoResize(activityTextarea);
        });
    }

    document.getElementById('add-itinerary-day').onclick = function() {
        itineraryDays.push({activity:"", day_title:"", accommodation:"", accommodation_id:null, meals:"", images:[], blocks:[]});
        renderItinerary();
        saveFormData();
    };

    // --- BUILD AND ATTACH content_blocks ---
    function buildAndAttachContentBlocks() {
        const container = document.getElementById('itinerary-days');
        const hiddenInputs = container.querySelectorAll('[data-contentblock-input]');

        hiddenInputs.forEach((hiddenInput, idx) => {
            const dayIndex = idx;
            const dayNum = idx + 1;
            const dayData = itineraryDays[dayIndex];
            if (!dayData) return;

            let blocks = [];

            if (Array.isArray(dayData.blocks) && dayData.blocks.length > 0) {
                dayData.blocks.forEach(b => {
                    if (b.type === 'image') {
                        if (!b.id) b.id = 'blk-' + uuid();
                        if (!b.media_id && !b.temp_media_id) {
                            const match = dayData.images.find(im => (im.existingMediaId && im.existingMediaId.toString() === (b.media_id ? b.media_id.toString() : '')) || im.caption === b.caption || im.preview === b.preview);
                            if (match) {
                                if (match.existingMediaId) b.media_id = match.existingMediaId;
                                else if (match.tempId) b.temp_media_id = match.tempId;
                            }
                        }
                    }
                    blocks.push(b);
                });
            } else {
                if (dayData.activity && dayData.activity.trim() !== '') {
                    blocks.push({
                        id: 'blk-' + uuid(),
                        type: 'text',
                        text: dayData.activity.trim()
                    });
                }
                dayData.images.forEach(img => {
                    blocks.push({
                        id: img.blockId || ('blk-' + uuid()),
                        type: 'image',
                        caption: img.caption || '',
                        media_id: img.existingMediaId || undefined,
                        temp_media_id: img.existingMediaId ? undefined : img.tempId
                    });
                });
            }

            hiddenInput.value = JSON.stringify(blocks);
        });
    }

    // --- PRICES ---
    let prices = [];
    @if($tour->prices && count($tour->prices) > 0)
        prices = {!! json_encode($tour->prices->map(function($item) {
            return [
                'id' => $item->id ?? null,
                'group_size' => $item->group_size ?? '',
                'price' => $item->price ?? ''
            ];
        })->values()->toArray()) !!};
    @endif

    function renderPrices() {
        const container = document.getElementById('prices');
        const noMessage = document.getElementById('no-prices-message');
        if (prices.length === 0) {
            noMessage.style.display = 'block';
            container.innerHTML = '';
            return;
        }
        noMessage.style.display = 'none';
        container.innerHTML = '';
        prices.forEach((price, i) => {
            const prNum = i + 1;
            const el = document.createElement('div');
            el.className = "relative bg-green-50 rounded-lg p-6 border-2 border-green-200 hover:border-green-300 transition duration-150";
            el.innerHTML = `
                ${price.id ? `<input type="hidden" name="prices[${prNum}][id]" value="${price.id}">` : ''}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Group Size <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                            <input 
                                type="number" 
                                name="prices[${prNum}][group_size]" 
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-150" 
                                min="1" 
                                required 
                                value="${price.group_size || ''}"
                                placeholder="e.g., 2">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Price (USD) <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 font-medium">$</span>
                            </div>
                            <input 
                                type="number" 
                                step="0.01" 
                                name="prices[${prNum}][price]" 
                                class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-150" 
                                min="0" 
                                required 
                                value="${price.price || ''}"
                                placeholder="0.00">
                        </div>
                    </div>
                </div>
                
                <button 
                    type="button" 
                    class="absolute top-4 right-4 bg-red-600 hover:bg-red-700 text-white rounded-lg px-3 py-2 transition duration-150 flex items-center gap-2 font-medium text-sm shadow-sm remove-price" 
                    data-index="${i}" 
                    title="Remove Price">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Remove
                </button>
            `;
            el.querySelector('.remove-price').onclick = function() {
                prices.splice(i, 1);
                renderPrices();
                saveFormData();
            };
            el.querySelectorAll('input').forEach(input => {
                input.addEventListener('input', function() {
                    if (this.name.includes('[group_size]')) price.group_size = this.value;
                    if (this.name.includes('[price]')) price.price = this.value;
                    saveFormData();
                });
            });
            container.appendChild(el);
        });
    }
    document.getElementById('add-price').onclick = function() {
        prices.push({group_size:"", price:""});
        renderPrices();
        saveFormData();
    };

    // --- META KEYWORDS ---
    let metaKeywords = [];
    @if($tour->meta_keywords)
        metaKeywords = {!! json_encode(array_filter(explode(',', $tour->meta_keywords))) !!};
    @endif
    let $input = document.getElementById('meta_keyword_input');
    let $addBtn = document.getElementById('add-meta-keyword');
    let $tags = document.getElementById('meta_keywords_tags');
    let $hidden = document.getElementById('meta_keywords');

    function renderKeywords() {
        $tags.innerHTML = '';
        metaKeywords.forEach((kw, idx) => {
            let tag = document.createElement('span');
            tag.className = "inline-flex items-center bg-indigo-100 text-indigo-800 rounded-full px-4 py-2 font-medium text-sm border border-indigo-200 hover:bg-indigo-200 transition duration-150";
            tag.innerHTML = `
                ${kw} 
                <button type="button" class="ml-2 text-red-600 hover:text-red-800 transition duration-150 font-bold" title="Remove">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            `;
            tag.querySelector('button').onclick = function() {
                metaKeywords.splice(idx, 1);
                renderKeywords();
                saveFormData();
            };
            $tags.appendChild(tag);
        });
        $hidden.value = metaKeywords.join(',');
    }
    $addBtn.onclick = function() {
        let val = $input.value.trim();
        if(val && !metaKeywords.includes(val)) {
            metaKeywords.push(val);
            renderKeywords();
            $input.value = '';
            saveFormData();
        }
    };
    $input.addEventListener('keydown', function(e){
        if(e.key === 'Enter') {
            e.preventDefault();
            $addBtn.click();
        }
    });

    // --- LOAD EXISTING TOUR-LEVEL IMAGES ---
    let imagesList = [];
    @if($tour->images && count($tour->images) > 0)
        imagesList = {!! json_encode($tour->images->map(function($item) {
            return [
                'id' => $item->id,
                'path' => $item->image_path,
                'isExisting' => true
            ];
        })->toArray()) !!};
    @endif

    function renderImagesList() {
        const imagesListDiv = document.getElementById('images-list');
        const noMessage = document.getElementById('no-images-message');
        
        if (imagesList.length === 0) {
            noMessage.style.display = 'block';
            imagesListDiv.innerHTML = '';
            return;
        }
        
        noMessage.style.display = 'none';
        imagesListDiv.innerHTML = '';
        
        imagesList.forEach(function(imgObj, i) {
            let wrapper = document.createElement('div');
            const isExisting = imgObj.isExisting || false;
            const bgColor = isExisting ? 'bg-blue-50' : 'bg-gray-50';
            const borderColor = isExisting ? 'border-blue-200 hover:border-blue-300' : 'border-gray-200 hover:border-indigo-300';
            
            wrapper.className = `flex items-center gap-4 ${bgColor} p-4 rounded-lg border-2 ${borderColor} transition duration-150`;
            
            if (isExisting) {
                wrapper.innerHTML = `
                    <div class="flex-shrink-0">
                        <div class="w-20 h-20 bg-gray-100 rounded-lg border-2 border-gray-300 overflow-hidden">
                            <img src="{{ asset('storage/') }}/${imgObj.path}" class="w-full h-full object-cover">
                        </div>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-blue-700">Existing Image</p>
                        <p class="text-xs text-gray-500 truncate">${imgObj.path}</p>
                    </div>
                    <button 
                        type="button" 
                        class="flex-shrink-0 bg-red-600 hover:bg-red-700 text-white rounded-lg px-3 py-2 transition duration-150 flex items-center gap-2 font-medium text-sm shadow-sm delete-img" 
                        data-index="${i}" 
                        title="Delete">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Delete
                    </button>
                `;
                
                wrapper.querySelector('.delete-img').onclick = function() {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'delete_images[]';
                    hiddenInput.value = imgObj.id;
                    document.querySelector('form').appendChild(hiddenInput);
                    
                    imagesList.splice(i, 1);
                    renderImagesList();
                    saveFormData();
                };
            } else {
                wrapper.innerHTML = `
                    <div class="flex-shrink-0">
                        <div class="w-20 h-20 bg-gray-100 rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center overflow-hidden">
                            <img src="${imgObj.preview || ''}" class="w-full h-full object-cover" style="display:${imgObj.preview?'block':'none'}" id="img-preview-${i}">
                            <svg class="w-8 h-8 text-gray-400" style="display:${imgObj.preview?'none':'block'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <input 
                            type="file" 
                            accept="image/*" 
                            name="images[]" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 image-input" 
                            data-index="${i}">
                    </div>
                    <button 
                        type="button" 
                        class="flex-shrink-0 bg-red-600 hover:bg-red-700 text-white rounded-lg px-3 py-2 transition duration-150 flex items-center gap-2 font-medium text-sm shadow-sm remove-img" 
                        data-index="${i}" 
                        title="Remove">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                `;
                
                let fileInput = wrapper.querySelector('.image-input');
                fileInput.addEventListener('change', function(e){
                    const file = fileInput.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(ev) {
                            imgObj.preview = ev.target.result;
                            const imgElement = document.getElementById('img-preview-'+i);
                            const svgElement = imgElement.nextElementSibling;
                            imgElement.src = ev.target.result;
                            imgElement.style.display = "block";
                            svgElement.style.display = "none";
                            saveFormData();
                        };
                        reader.readAsDataURL(file);
                    }
                });
                
                wrapper.querySelector('.remove-img').onclick = function() {
                    imagesList.splice(i, 1);
                    renderImagesList();
                    saveFormData();
                };
            }
            
            imagesListDiv.appendChild(wrapper);
        });
    }
    
    document.getElementById('add-image').onclick = function() {
        imagesList.push({preview:"", isExisting: false});
        renderImagesList();
        saveFormData();
    };

    // --- INITIAL RENDER ---
    renderItinerary();
    renderPrices();
    renderKeywords();
    renderImagesList();

    // --- LOAD SAVED DATA IF ANY ---
    loadSavedData();
});
</script>
@endsection