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
                <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
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
        /* ── Accommodation dropdown ──────────────────────────────────────── */
        .acc-dropdown-wrapper { position: relative; }
        .acc-search-input {
            width: 100%; padding: 0.5rem 2.5rem 0.5rem 1rem;
            border: 1px solid #d1d5db; border-radius: 0.5rem;
            font-size: 0.875rem; line-height: 1.25rem; outline: none;
            transition: border-color .15s, box-shadow .15s; background: white; cursor: pointer;
        }
        .acc-search-input:focus { border-color: #6366f1; box-shadow: 0 0 0 2px rgba(99,102,241,.2); }
        .acc-search-input.has-value { border-color: #6366f1; background-color: #eef2ff; color: #3730a3; font-weight: 500; }
        .acc-chevron {
            position: absolute; right: .75rem; top: 50%; transform: translateY(-50%);
            pointer-events: none; color: #9ca3af; transition: transform .2s;
        }
        .acc-chevron.open { transform: translateY(-50%) rotate(180deg); color: #6366f1; }
        .acc-dropdown-menu {
            position: absolute; z-index: 50; width: 100%; background: white;
            border: 1px solid #e5e7eb; border-radius: .5rem;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,.1), 0 4px 6px -2px rgba(0,0,0,.05);
            margin-top: 4px; overflow: hidden; display: none;
        }
        .acc-dropdown-menu.open { display: block; }
        .acc-search-box {
            padding: .5rem; border-bottom: 1px solid #f3f4f6;
            position: sticky; top: 0; background: white; z-index: 1;
        }
        .acc-search-box input {
            width: 100%; padding: .4rem .75rem .4rem 2rem;
            border: 1px solid #e5e7eb; border-radius: .375rem;
            font-size: .8rem; outline: none; background: #f9fafb;
        }
        .acc-search-box input:focus { border-color: #6366f1; background: white; }
        .acc-search-icon {
            position: absolute; left: 1.25rem; top: 50%; transform: translateY(-50%);
            color: #9ca3af; pointer-events: none;
        }
        .acc-options-list { max-height: 220px; overflow-y: auto; padding: .25rem 0; }
        .acc-option {
            padding: .5rem .875rem; cursor: pointer; font-size: .875rem;
            color: #374151; display: flex; flex-direction: column; transition: background .1s;
        }
        .acc-option:hover, .acc-option.highlighted { background: #eef2ff; color: #3730a3; }
        .acc-option.selected { background: #e0e7ff; color: #3730a3; font-weight: 600; }
        .acc-option .acc-option-name { font-weight: 500; }
        .acc-option .acc-option-meta { font-size: .75rem; color: #6b7280; margin-top: 1px; }
        .acc-option.highlighted .acc-option-meta,
        .acc-option:hover .acc-option-meta,
        .acc-option.selected .acc-option-meta { color: #6366f1; }
        .acc-no-results { padding: 1rem .875rem; font-size: .875rem; color: #9ca3af; text-align: center; }
        .acc-clear-btn {
            position: absolute; right: 2rem; top: 50%; transform: translateY(-50%);
            color: #9ca3af; cursor: pointer; display: none;
            background: none; border: none; padding: 0; line-height: 1;
        }
        .acc-clear-btn:hover { color: #ef4444; }
        .acc-clear-btn.visible { display: block; }
        mark.acc-highlight { background: #fef08a; color: inherit; border-radius: 2px; padding: 0 1px; }

        /* ── Keyword picker (destination-style) ─────────────────────────── */
        #kwGrid::-webkit-scrollbar       { width: 6px; }
        #kwGrid::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 4px; }
        #kwGrid::-webkit-scrollbar-thumb { background: #94a3b8; border-radius: 4px; }
    </style>

    <form id="edit-tour-form" action="{{ route('admin.tours.update', $tour->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('PUT')

        {{-- ═══════════════════════════════════════════════════════════════ --}}
        {{-- BASIC INFORMATION                                               --}}
        {{-- ═══════════════════════════════════════════════════════════════ --}}
        <div class="bg-white shadow-sm rounded-lg border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900">Basic Information</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Tour Title <span class="text-red-500">*</span></label>
                        <input name="title" id="title" type="text"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150"
                            value="{{ old('title', $tour->title) }}" required placeholder="e.g., 7-Day Safari Adventure">
                    </div>
                    <div>
                        <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">Slug</label>
                        <input name="slug" id="slug" type="text"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150"
                            value="{{ old('slug', $tour->slug) }}" placeholder="auto-generated-from-title">
                        <p class="text-gray-500 text-xs mt-1">Leave empty to auto-generate from title</p>
                    </div>
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category <span class="text-red-500">*</span></label>
                        <input name="category" id="category" type="text"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150"
                            value="{{ old('category', $tour->category) }}" required placeholder="e.g., Wildlife Safari">
                    </div>
                    <div>
                        <label for="destinations" class="block text-sm font-medium text-gray-700 mb-2">Destinations <span class="text-red-500">*</span></label>
                        <input name="destinations" id="destinations" type="text"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150"
                            value="{{ old('destinations', $tour->destinations) }}" required placeholder="e.g., Serengeti, Ngorongoro Crater">
                    </div>
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Tour Type <span class="text-red-500">*</span></label>
                        <input name="type" id="type" type="text"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150"
                            value="{{ old('type', $tour->type) }}" required placeholder="e.g., Group Tour, Private Tour">
                    </div>
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description <span class="text-red-500">*</span></label>
                        <textarea name="description" id="description"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 resize-y"
                            rows="4" required placeholder="Describe the tour experience...">{{ old('description', $tour->description) }}</textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label for="included" class="block text-sm font-medium text-gray-700 mb-2">What's Included</label>
                        <textarea name="included" id="included"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 resize-y"
                            rows="4" placeholder="Enter included items, each on a new line">{{ old('included', $tour->included) }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">Enter each item on a new line</p>
                    </div>
                    <div class="md:col-span-2">
                        <label for="excluded" class="block text-sm font-medium text-gray-700 mb-2">What's Excluded</label>
                        <textarea name="excluded" id="excluded"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 resize-y"
                            rows="4" placeholder="Enter excluded items, each on a new line">{{ old('excluded', $tour->excluded) }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">Enter each item on a new line</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════════════════════ --}}
        {{-- SEO SETTINGS  — DESTINATION-STYLE KEYWORD PICKER               --}}
        {{-- ═══════════════════════════════════════════════════════════════ --}}
        <div class="bg-white shadow-sm rounded-lg border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900">SEO Settings</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- ── META TITLE ── --}}
                    <div class="md:col-span-2">
                        <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-2">Meta Title</label>
                        <input name="meta_title" id="meta_title" type="text" maxlength="60"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150"
                            value="{{ old('meta_title', $tour->meta_title) }}" placeholder="SEO-friendly title for search engines">
                    </div>

                    {{-- ── META DESCRIPTION ── --}}
                    <div class="md:col-span-2">
                        <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
                        <textarea name="meta_description" id="meta_description"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 resize-y"
                            rows="3" maxlength="160" placeholder="Brief description for search engine results (150-160 chars)">{{ old('meta_description', $tour->meta_description) }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">Recommended length: 150–160 characters</p>
                    </div>

                    {{-- ── KEYWORD PICKER (destination-style) ── --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Meta Keywords</label>
                        <p class="text-xs text-gray-500 mb-3">
                            Tick the keywords you want — they are joined automatically.
                            Add any extras in the box at the bottom. Your existing saved keywords are pre-selected.
                        </p>

                        {{-- Filter / search box --}}
                        <div class="relative mb-3">
                            <input type="text" id="kwSearch"
                                   placeholder="Filter keywords…"
                                   class="w-full pl-9 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 focus:outline-none">
                            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        </div>

                        {{-- Bulk actions + count --}}
                        <div class="flex gap-3 mb-3">
                            <button type="button" id="kwSelectAll"
                                    class="text-xs px-3 py-1 bg-indigo-100 text-indigo-700 border border-indigo-300 rounded-lg hover:bg-indigo-200 transition">
                                <i class="fas fa-check-double mr-1"></i> Select all visible
                            </button>
                            <button type="button" id="kwClearAll"
                                    class="text-xs px-3 py-1 bg-red-100 text-red-700 border border-red-300 rounded-lg hover:bg-red-200 transition">
                                <i class="fas fa-times mr-1"></i> Clear all
                            </button>
                            <span id="kwCount" class="text-xs text-gray-500 self-center ml-auto">0 selected</span>
                        </div>

                        {{-- Checkbox grid --}}
                        @php
                        $tourBuiltInKeywords = [
                            'Uganda safari tours',
                            'Uganda safari packages',
                            'Uganda tours and safaris',
                            'Uganda wildlife safari',
                            'Uganda big five safari',
                            'Queen Elizabeth national park safari',
                            'gorilla trekking Uganda',
                            'Uganda gorilla trekking safari',
                            'Bwindi gorilla trekking',
                            'Bwindi gorilla safari',
                            'chimpanzee trekking Uganda',
                            'chimpanzee tracking Kibale forest',
                            'Uganda primates tour',
                            'Murchison falls safari',
                            'Murchison falls national park tour',
                            '3 days Uganda safari',
                            '7 days Uganda safari',
                            '10 days Uganda safari',
                            'budget Uganda safari',
                            'budget gorilla trekking Uganda',
                            'affordable Uganda safari packages',
                            'fly in Uganda safari',
                            'luxury Uganda safari',
                            'family Uganda safari',
                            'honeymoon safari Uganda',
                            'source of the Nile tours',
                            'Uganda gorilla permits price',
                            'best Uganda safari tours 2026',
                            'private Uganda safari tours',
                            'Uganda group safari deals',
                            'chimpanzee trekking Kibale Forest',
                            'safari in Uganda national parks',
                            'boat cruise Kazinga Channel safari',
                            'Kibale chimpanzee trekking safari',
                            'chimpanzee habituation experience Uganda',
                            '2 day chimpanzee trekking safari Uganda',
                            'budget Uganda safari packages',
                            'family safari Uganda itinerary',
                            'best country for gorilla trekking in Africa',
                            'book Uganda wildlife safari',
                            'Uganda safari tour packages',
                            'cheap Uganda safari tours',
                            'Uganda safari deals',
                            'private Uganda safari tour',
                            'all inclusive Uganda safari',
                            'Uganda multi day safari package',
                            'book Uganda gorilla trekking',
                            'Uganda gorilla trekking permit',
                            'Uganda gorilla trek booking',
                            'cheap Uganda gorilla trekking',
                            'Uganda gorilla safari package',
                            'gorilla trek Uganda',
                            'Chimpanzee Tracking',
                            'book Uganda chimpanzee tracking',
                            'Uganda chimpanzee tour',
                            'Uganda chimpanzee safari',
                            'book Uganda birding safari',
                            'Uganda bird watching tour',
                            'Uganda birding package',
                            'Boat and River Safaris',
                            'book boat safari Uganda',
                            'Uganda boat cruise deals',
                            'Uganda river safari package',
                            'Walking Safaris',
                            'book walking safari Uganda',
                            'Uganda walking safari tour',
                            'Photography Safaris',
                            'book Uganda photography safari',
                            'Uganda wildlife photography tour',
                            'Uganda cultural tour',
                            'Uganda community safari booking',
                            'Uganda village tour packages',
                            'Uganda safari package deals',
                            'Uganda safari tours booking',
                            'Uganda safari package',
                            'Uganda luxury safari deals',
                            'Uganda cheap safari package',
                        ];

                        $savedKwString  = old('meta_keywords', $tour->meta_keywords ?? '');
                        $savedKwList    = array_values(array_filter(array_map('trim', explode(',', $savedKwString))));
                        $builtInLower   = array_map('strtolower', $tourBuiltInKeywords);
                        $extraKwList    = array_values(array_filter($savedKwList, fn($k) => !in_array(strtolower($k), $builtInLower)));
                        @endphp

                        <div id="kwGrid"
                             class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2 max-h-72 overflow-y-auto border border-gray-200 rounded-lg p-3 bg-gray-50">
                            @foreach($tourBuiltInKeywords as $kw)
                                <label class="kw-item flex items-center gap-2 px-3 py-2 bg-white border border-gray-200 rounded-lg cursor-pointer hover:border-indigo-400 hover:bg-indigo-50 transition text-sm select-none {{ in_array($kw, $savedKwList) ? 'border-indigo-400 bg-indigo-50' : '' }}"
                                       data-label="{{ strtolower($kw) }}">
                                    <input type="checkbox"
                                           class="kw-checkbox w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-400"
                                           value="{{ $kw }}"
                                           {{ in_array($kw, $savedKwList) ? 'checked' : '' }}>
                                    <span>{{ $kw }}</span>
                                </label>
                            @endforeach
                        </div>

                        {{-- Extra / custom keywords --}}
                        <div class="mt-4">
                            <label for="kwExtra" class="block text-xs font-medium text-gray-600 mb-1">
                                Add extra keywords <span class="font-normal text-gray-400">(comma-separated)</span>
                            </label>
                            <input type="text" id="kwExtra"
                                   placeholder="e.g. Uganda safari 2026, gorilla permit cost"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 focus:outline-none"
                                   value="{{ implode(', ', $extraKwList) }}">
                        </div>

                        {{-- Live preview --}}
                        <div class="mt-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                            <p class="text-xs font-medium text-blue-700 mb-1"><i class="fas fa-eye mr-1"></i> Keywords preview:</p>
                            <p id="kwPreview" class="text-xs text-blue-600 break-words italic">None selected yet.</p>
                        </div>

                        {{-- Hidden input submitted with the form --}}
                        <input type="hidden" name="meta_keywords" id="meta_keywords"
                               value="{{ old('meta_keywords', $tour->meta_keywords) }}">
                    </div>
                    {{-- ── END KEYWORD PICKER ── --}}

                </div>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════════════════════ --}}
        {{-- FEATURED IMAGE                                                  --}}
        {{-- ═══════════════════════════════════════════════════════════════ --}}
        <div class="bg-white shadow-sm rounded-lg border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900">Featured Image</h2>
            </div>
            <div class="p-6">
                <label for="featured_image" class="block text-sm font-medium text-gray-700 mb-2">Upload New Image (Leave blank to keep current)</label>
                <input name="featured_image" id="featured_image" type="file"
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

        {{-- ═══════════════════════════════════════════════════════════════ --}}
        {{-- ITINERARY                                                        --}}
        {{-- ═══════════════════════════════════════════════════════════════ --}}
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
                <button type="button" id="add-itinerary-day"
                    class="w-full mt-4 px-4 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-150 flex items-center justify-center gap-2 font-medium shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Day
                </button>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════════════════════ --}}
        {{-- PRICING                                                          --}}
        {{-- ═══════════════════════════════════════════════════════════════ --}}
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
                <button type="button" id="add-price"
                    class="w-full mt-4 px-4 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-150 flex items-center justify-center gap-2 font-medium shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Price
                </button>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════════════════════ --}}
        {{-- IMAGE GALLERY                                                    --}}
        {{-- ═══════════════════════════════════════════════════════════════ --}}
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
                <button type="button" id="add-image"
                    class="w-full mt-4 px-4 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-150 flex items-center justify-center gap-2 font-medium shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Image
                </button>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════════════════════ --}}
        {{-- SUBMIT                                                           --}}
        {{-- ═══════════════════════════════════════════════════════════════ --}}
        <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
            <button type="button" onclick="window.history.back()"
                class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-150 font-medium">
                Cancel
            </button>
            <button type="submit"
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
document.addEventListener('DOMContentLoaded', function () {

    var STORAGE_KEY         = 'tour_edit_form_data_{{ $tour->id }}';
    var accommodationsData  = @json($accommodations);

    // ── helpers ───────────────────────────────────────────────────────────
    function uuid() {
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
            var r = Math.random() * 16 | 0, v = c === 'x' ? r : (r & 0x3 | 0x8);
            return v.toString(16);
        });
    }
    function tempId() { return 'tmp-' + Math.random().toString(36).substr(2, 9); }
    function autoResize(ta) { ta.style.height = 'auto'; ta.style.height = ta.scrollHeight + 'px'; }
    function escHtml(s) {
        return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    // ── slug auto-generation ──────────────────────────────────────────────
    var titleField = document.getElementById('title');
    var slugField  = document.getElementById('slug');
    if (titleField && slugField) {
        titleField.addEventListener('input', function () {
            if (!slugField.dataset.manualEdit) {
                slugField.value = this.value.toLowerCase()
                    .replace(/[^\w\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-');
            }
        });
        slugField.addEventListener('input', function () { this.dataset.manualEdit = 'true'; });
    }

    // ══════════════════════════════════════════════════════════════════════
    // KEYWORD PICKER  (destination-style)
    // ══════════════════════════════════════════════════════════════════════
    var kwCheckboxes = document.querySelectorAll('.kw-checkbox');
    var kwHidden     = document.getElementById('meta_keywords');
    var kwPreview    = document.getElementById('kwPreview');
    var kwCount      = document.getElementById('kwCount');
    var kwExtra      = document.getElementById('kwExtra');   // pre-filled by PHP

    function syncKeywords() {
        var checked = Array.from(kwCheckboxes)
            .filter(function (c) { return c.checked; })
            .map(function (c) { return c.value.trim(); });

        var extras = kwExtra.value
            .split(',')
            .map(function (k) { return k.trim(); })
            .filter(Boolean);

        // merge: checked list first, then extras not already in checked list
        var all = checked.concat(
            extras.filter(function (e) { return !checked.includes(e); })
        );

        kwHidden.value       = all.join(', ');
        kwPreview.textContent = all.length ? all.join(', ') : 'None selected yet.';
        kwCount.textContent   = checked.length + ' selected';

        // visual highlight on labels
        kwCheckboxes.forEach(function (cb) {
            var lbl = cb.closest('label');
            lbl.classList.toggle('border-indigo-400', cb.checked);
            lbl.classList.toggle('bg-indigo-50',      cb.checked);
        });
    }

    // wire events
    kwCheckboxes.forEach(function (cb) { cb.addEventListener('change', syncKeywords); });
    kwExtra.addEventListener('input', syncKeywords);

    document.getElementById('kwSearch').addEventListener('input', function () {
        var q = this.value.toLowerCase();
        document.querySelectorAll('.kw-item').forEach(function (item) {
            item.style.display = item.dataset.label.includes(q) ? '' : 'none';
        });
    });

    document.getElementById('kwSelectAll').addEventListener('click', function () {
        document.querySelectorAll('.kw-item').forEach(function (item) {
            if (item.style.display !== 'none') {
                item.querySelector('.kw-checkbox').checked = true;
            }
        });
        syncKeywords();
    });

    document.getElementById('kwClearAll').addEventListener('click', function () {
        kwCheckboxes.forEach(function (cb) { cb.checked = false; });
        kwExtra.value = '';
        syncKeywords();
    });

    syncKeywords(); // initialise on page load

    // ══════════════════════════════════════════════════════════════════════
    // ACCOMMODATION DROPDOWN
    // ══════════════════════════════════════════════════════════════════════
    function createAccommodationDropdown(container, inputName, initialValue) {
        var selectedId       = null;
        var changeCallbacks  = [];
        var highlightedIndex = -1;
        var filteredOptions  = [];

        var options = [{ id: null, name: 'No Accommodation', type: '', location: '', label: 'No Accommodation' }]
            .concat(accommodationsData.map(function (acc) {
                return {
                    id: acc.id, name: acc.name,
                    type: acc.type || '', location: acc.location || '',
                    label: acc.name + (acc.type ? ' – ' + acc.type : '') + (acc.location ? ' (' + acc.location + ')' : '')
                };
            }));

        var wrapper    = document.createElement('div'); wrapper.className = 'acc-dropdown-wrapper';
        var trigger    = document.createElement('input');
        trigger.type = 'text'; trigger.readOnly = true;
        trigger.className = 'acc-search-input'; trigger.placeholder = 'Select Accommodation (Optional)';
        trigger.setAttribute('autocomplete', 'off');

        var chevron = document.createElement('span'); chevron.className = 'acc-chevron';
        chevron.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>';

        var clearBtn = document.createElement('button');
        clearBtn.type = 'button'; clearBtn.className = 'acc-clear-btn'; clearBtn.title = 'Clear selection';
        clearBtn.innerHTML = '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>';

        var menu      = document.createElement('div'); menu.className = 'acc-dropdown-menu';
        var searchBox = document.createElement('div'); searchBox.className = 'acc-search-box'; searchBox.style.position = 'relative';
        var searchIcon = document.createElement('span'); searchIcon.className = 'acc-search-icon';
        searchIcon.innerHTML = '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>';
        var searchInput = document.createElement('input');
        searchInput.type = 'text'; searchInput.placeholder = 'Search accommodations…';
        searchInput.setAttribute('autocomplete', 'off');
        searchBox.appendChild(searchIcon); searchBox.appendChild(searchInput);

        var optionsList = document.createElement('div'); optionsList.className = 'acc-options-list';
        menu.appendChild(searchBox); menu.appendChild(optionsList);

        var hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden'; hiddenInput.name = inputName; hiddenInput.value = '';

        wrapper.appendChild(trigger); wrapper.appendChild(clearBtn);
        wrapper.appendChild(chevron); wrapper.appendChild(menu); wrapper.appendChild(hiddenInput);
        container.appendChild(wrapper);

        function hlAcc(text, q) {
            if (!q) return escHtml(text);
            var i = text.toLowerCase().indexOf(q.toLowerCase());
            if (i < 0) return escHtml(text);
            return escHtml(text.slice(0,i)) + '<mark class="acc-highlight">' + escHtml(text.slice(i,i+q.length)) + '</mark>' + escHtml(text.slice(i+q.length));
        }

        function renderOptions(query) {
            query = (query || '').trim();
            optionsList.innerHTML = ''; highlightedIndex = -1;
            filteredOptions = options.filter(function (opt) {
                if (!query) return true;
                var q = query.toLowerCase();
                return opt.name.toLowerCase().includes(q) || opt.type.toLowerCase().includes(q) || opt.location.toLowerCase().includes(q);
            });
            if (!filteredOptions.length) { optionsList.innerHTML = '<div class="acc-no-results">No accommodations found</div>'; return; }
            filteredOptions.forEach(function (opt, idx) {
                var el = document.createElement('div');
                el.className = 'acc-option' + (opt.id == selectedId ? ' selected' : '');
                el.dataset.index = idx;
                if (opt.id === null) {
                    el.innerHTML = '<span class="acc-option-name" style="font-style:italic;color:#6b7280">No Accommodation</span>';
                } else {
                    el.innerHTML = '<span class="acc-option-name">' + hlAcc(opt.name, query) + '</span>'
                        + ((opt.type || opt.location) ? '<span class="acc-option-meta">' + escHtml([opt.type, opt.location].filter(Boolean).join(' · ')) + '</span>' : '');
                }
                el.addEventListener('mousedown', function (e) { e.preventDefault(); selectOption(opt); });
                optionsList.appendChild(el);
            });
        }

        function selectOption(opt) {
            selectedId = opt.id; hiddenInput.value = opt.id !== null ? opt.id : '';
            if (opt.id === null) { trigger.value = ''; trigger.classList.remove('has-value'); clearBtn.classList.remove('visible'); }
            else { trigger.value = opt.label; trigger.classList.add('has-value'); clearBtn.classList.add('visible'); }
            closeMenu();
            changeCallbacks.forEach(function (cb) { cb(opt.id, opt.id !== null ? accommodationsData.find(function (a) { return a.id == opt.id; }) : null); });
        }

        function openMenu() { menu.classList.add('open'); chevron.classList.add('open'); searchInput.value = ''; renderOptions(''); setTimeout(function () { searchInput.focus(); }, 50); }
        function closeMenu() { menu.classList.remove('open'); chevron.classList.remove('open'); highlightedIndex = -1; }

        trigger.addEventListener('click', function () { menu.classList.contains('open') ? closeMenu() : openMenu(); });
        clearBtn.addEventListener('click', function (e) { e.stopPropagation(); selectOption(options[0]); });
        searchInput.addEventListener('input', function () { renderOptions(this.value); });
        searchInput.addEventListener('keydown', function (e) {
            var items = optionsList.querySelectorAll('.acc-option');
            if (e.key === 'ArrowDown') { e.preventDefault(); highlightedIndex = Math.min(highlightedIndex+1, items.length-1); updateHL(items); }
            else if (e.key === 'ArrowUp') { e.preventDefault(); highlightedIndex = Math.max(highlightedIndex-1, 0); updateHL(items); }
            else if (e.key === 'Enter') { e.preventDefault(); if (highlightedIndex >= 0 && filteredOptions[highlightedIndex]) selectOption(filteredOptions[highlightedIndex]); }
            else if (e.key === 'Escape') { closeMenu(); trigger.focus(); }
        });
        function updateHL(items) {
            items.forEach(function (el, i) {
                el.classList.toggle('highlighted', i === highlightedIndex);
                if (i === highlightedIndex) el.scrollIntoView({ block: 'nearest' });
            });
        }
        document.addEventListener('mousedown', function (e) { if (!wrapper.contains(e.target)) closeMenu(); });

        function getValue() { return selectedId; }
        function setValue(id) {
            var opt = options.find(function (o) { return o.id == id; }) || options[0];
            selectedId = opt.id; hiddenInput.value = opt.id !== null ? opt.id : '';
            if (opt.id !== null) { trigger.value = opt.label; trigger.classList.add('has-value'); clearBtn.classList.add('visible'); }
            else { trigger.value = ''; trigger.classList.remove('has-value'); clearBtn.classList.remove('visible'); }
        }
        function onChange(cb) { changeCallbacks.push(cb); }
        if (initialValue) setValue(initialValue);
        return { getValue: getValue, setValue: setValue, onChange: onChange };
    }

    // ══════════════════════════════════════════════════════════════════════
    // LOCAL STORAGE
    // ══════════════════════════════════════════════════════════════════════
    function saveFormData() {
        var formFields = {};
        document.querySelectorAll('input[type="text"], input[type="number"], textarea, select').forEach(function (input) {
            if (input.name && !input.name.startsWith('itinerary[') && !input.name.startsWith('prices[')
                && input.name !== 'meta_keywords' && !input.name.includes('images[')
                && !input.name.includes('delete_images[') && !input.name.includes('uploads[')) {
                formFields[input.name] = input.value;
            }
        });
        localStorage.setItem(STORAGE_KEY, JSON.stringify({
            formFields:    formFields,
            itineraryDays: itineraryDays,
            prices:        prices,
            imagesList:    imagesList.map(function (img) { return { preview: img.preview, id: img.id||null, path: img.path||null }; })
        }));
    }

    function loadSavedData() {
        var savedData = localStorage.getItem(STORAGE_KEY);
        if (!savedData) return;
        try {
            var data = JSON.parse(savedData);
            if (data.formFields) {
                Object.keys(data.formFields).forEach(function (key) {
                    var field = document.querySelector('[name="' + key + '"]');
                    if (field && !field.value) field.value = data.formFields[key];
                });
            }
            if (data.itineraryDays && itineraryDays.length === 0) itineraryDays = data.itineraryDays;
            if (data.prices && prices.length === 0) prices = data.prices;
            if (data.imagesList) imagesList = data.imagesList;
            renderItinerary(); renderPrices(); renderImagesList();
        } catch(e) { console.error('Error loading saved data:', e); }
    }

    document.getElementById('edit-tour-form').addEventListener('submit', function () {
        buildAndAttachContentBlocks();
        localStorage.removeItem(STORAGE_KEY);
    });

    // featured image preview
    var featuredInput = document.getElementById('featured_image');
    if (featuredInput) {
        featuredInput.addEventListener('change', function () {
            var preview = document.getElementById('featured_image_preview');
            preview.innerHTML = '';
            var file = this.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function (ev) {
                    preview.innerHTML = '<div><p class="text-sm font-medium text-gray-700 mb-2">New Image Preview:</p>'
                        + '<div class="relative inline-block"><img src="' + ev.target.result + '" class="h-32 w-auto rounded-lg border-2 border-gray-200 shadow-md">'
                        + '<div class="absolute -top-2 -right-2 bg-green-500 text-white rounded-full p-1">'
                        + '<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>'
                        + '</div></div></div>';
                };
                reader.readAsDataURL(file);
            }
        });
    }

    async function loadAccommodationImages(accommodationId) {
        try {
            var response = await fetch('/admin/api/accommodations/' + accommodationId);
            var data = await response.json();
            if (data.success) return data.data.images || [];
        } catch(e) { console.error('Error loading accommodation images:', e); }
        return [];
    }

    // ══════════════════════════════════════════════════════════════════════
    // ITINERARY
    // ══════════════════════════════════════════════════════════════════════
    var itineraryDays = [];
    @if($tour->itinerary && count($tour->itinerary) > 0)
        itineraryDays = {!! json_encode($tour->itinerary->map(function($item) {
            $images = [];
            if (method_exists($item, 'images') && $item->images && count($item->images) > 0) {
                $images = $item->images->map(function($img) {
                    return [
                        'existingMediaId' => $img->id,
                        'preview'         => $img->thumbnail_path ? asset('storage/' . ltrim($img->thumbnail_path, '/')) : ($img->storage_path ? asset('storage/' . ltrim($img->storage_path, '/')) : null),
                        'caption'         => $img->caption,
                        'blockId'         => $img->block_id,
                        'storage_path'    => $img->storage_path,
                        'thumbnail_path'  => $img->thumbnail_path,
                    ];
                })->values()->toArray();
            }
            return [
                'id'               => $item->id ?? null,
                'activity'         => $item->activity ?? '',
                'day_title'        => $item->day_title ?? '',
                'accommodation'    => $item->accommodation ?? '',
                'accommodation_id' => $item->accommodation_id ?? null,
                'meals'            => $item->meals ?? '',
                'blocks'           => $item->content_blocks ?? null,
                'images'           => $images,
            ];
        })->values()->toArray()) !!};
    @endif

    function renderItinerary() {
        var container = document.getElementById('itinerary-days');
        var noMsg     = document.getElementById('no-itinerary-message');
        if (itineraryDays.length === 0) { noMsg.style.display = 'block'; container.innerHTML = ''; return; }
        noMsg.style.display = 'none'; container.innerHTML = '';

        itineraryDays.forEach(function (day, i) {
            var dayNum = i + 1;
            if (!Array.isArray(day.images)) day.images = [];
            day.images.forEach(function (img) {
                if (!img.tempId && !img.existingMediaId) img.tempId = tempId();
                if (!img.blockId) img.blockId = 'blk-' + uuid();
            });

            var el = document.createElement('div');
            el.className = 'relative bg-gradient-to-r from-indigo-50 to-purple-50 rounded-lg p-6 border-2 border-indigo-200 hover:border-indigo-300 transition duration-150';
            el.innerHTML =
                '<div class="absolute top-4 left-4 bg-indigo-600 text-white rounded-full w-10 h-10 flex items-center justify-center font-bold text-lg shadow-md">' + dayNum + '</div>'
                + '<input type="hidden" name="itinerary[' + dayNum + '][day_number]" value="' + dayNum + '">'
                + (day.id ? '<input type="hidden" name="itinerary[' + dayNum + '][id]" value="' + day.id + '">' : '')
                + '<input type="hidden" data-contentblock-input name="itinerary[' + dayNum + '][content_blocks]" value="">'
                + '<div class="ml-14 space-y-4">'
                + '<div><label class="block text-sm font-medium text-gray-700 mb-2">Activity <span class="text-red-500">*</span></label>'
                + '<textarea name="itinerary[' + dayNum + '][activity]" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 resize-none overflow-hidden auto-resize" required placeholder="Describe the day\'s activities..." rows="1">' + escHtml(day.activity || '') + '</textarea></div>'
                + '<div class="grid grid-cols-1 md:grid-cols-3 gap-4">'
                + '<div><label class="block text-sm font-medium text-gray-700 mb-2">Day Title</label>'
                + '<input type="text" name="itinerary[' + dayNum + '][day_title]" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150" value="' + escHtml(day.day_title || '') + '" placeholder="e.g., Arrival Day"></div>'
                + '<div><label class="block text-sm font-medium text-gray-700 mb-2">Accommodation</label>'
                + '<div id="acc-dropdown-mount-' + i + '" class="acc-mount"></div>'
                + '<input type="hidden" name="itinerary[' + dayNum + '][accommodation]" value="' + escHtml(day.accommodation || '') + '">'
                + '<div id="accommodation-images-' + i + '" class="mt-3 hidden"><p class="text-sm text-gray-600 mb-2">Accommodation Images:</p>'
                + '<div class="grid grid-cols-2 md:grid-cols-4 gap-2" id="accommodation-images-grid-' + i + '"></div></div></div>'
                + '<div><label class="block text-sm font-medium text-gray-700 mb-2">Meals</label>'
                + '<input type="text" name="itinerary[' + dayNum + '][meals]" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150" value="' + escHtml(day.meals || '') + '" placeholder="e.g., B, L, D"></div></div>'
                + '<div><label class="block text-sm font-medium text-gray-700 mb-2">Day Images (optional)</label>'
                + '<div id="day-images-' + dayNum + '" class="space-y-3"></div>'
                + '<div class="flex gap-2 mt-3"><button type="button" class="px-3 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 add-day-image" data-index="' + i + '">Add Image</button>'
                + '<p class="text-sm text-gray-500">Add images to appear inline with this day\'s description.</p></div></div>'
                + '</div>'
                + '<button type="button" class="absolute top-4 right-4 bg-red-600 hover:bg-red-700 text-white rounded-lg px-3 py-2 transition duration-150 flex items-center gap-2 font-medium text-sm shadow-sm remove-day" data-index="' + i + '" title="Remove Day">'
                + '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>Remove</button>';

            el.querySelector('.remove-day').onclick = function () { itineraryDays.splice(i,1); renderItinerary(); saveFormData(); };
            container.appendChild(el);

            var cbInput = el.querySelector('[data-contentblock-input]');
            try { cbInput.value = day.blocks && Array.isArray(day.blocks) ? JSON.stringify(day.blocks) : ''; }
            catch(err) { cbInput.value = ''; }

            // accommodation dropdown
            var mountPoint  = el.querySelector('#acc-dropdown-mount-' + i);
            var accDropdown = createAccommodationDropdown(mountPoint, 'itinerary[' + dayNum + '][accommodation_id]', day.accommodation_id);
            accDropdown.onChange(async function (accommodationId) {
                day.accommodation_id = accommodationId || null;
                var imagesContainer = document.getElementById('accommodation-images-' + i);
                var imagesGrid      = document.getElementById('accommodation-images-grid-' + i);
                if (accommodationId) {
                    var images = await loadAccommodationImages(accommodationId);
                    if (images.length > 0) {
                        imagesGrid.innerHTML = '';
                        images.forEach(function (img) {
                            var imgDiv = document.createElement('div'); imgDiv.className = 'relative group';
                            imgDiv.innerHTML = '<img src="' + img.url + '" alt="' + escHtml(img.alt_text||'') + '" class="w-full h-20 object-cover rounded border border-gray-200">'
                                + '<div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white text-xs p-1 rounded-b opacity-0 group-hover:opacity-100 transition">' + escHtml(img.caption||'No caption') + '</div>';
                            imagesGrid.appendChild(imgDiv);
                        });
                        imagesContainer.classList.remove('hidden');
                    } else { imagesContainer.classList.add('hidden'); }
                } else { imagesContainer.classList.add('hidden'); }
                saveFormData();
            });

            if (day.accommodation_id) {
                (async function () {
                    var imagesContainer = document.getElementById('accommodation-images-' + i);
                    var imagesGrid      = document.getElementById('accommodation-images-grid-' + i);
                    var images = await loadAccommodationImages(day.accommodation_id);
                    if (images.length > 0) {
                        imagesGrid.innerHTML = '';
                        images.forEach(function (img) {
                            var imgDiv = document.createElement('div'); imgDiv.className = 'relative group';
                            imgDiv.innerHTML = '<img src="' + img.url + '" alt="' + escHtml(img.alt_text||'') + '" class="w-full h-20 object-cover rounded border border-gray-200">'
                                + '<div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white text-xs p-1 rounded-b opacity-0 group-hover:opacity-100 transition">' + escHtml(img.caption||'No caption') + '</div>';
                            imagesGrid.appendChild(imgDiv);
                        });
                        imagesContainer.classList.remove('hidden');
                    }
                })();
            }

            var dayImagesContainer = document.getElementById('day-images-' + dayNum);

            function renderDayImages() {
                dayImagesContainer.innerHTML = '';
                day.images.forEach(function (imgObj, imgIndex) {
                    if (!imgObj.tempId && !imgObj.existingMediaId) imgObj.tempId = tempId();
                    if (!imgObj.blockId) imgObj.blockId = 'blk-' + uuid();
                    var wrapper = document.createElement('div');
                    wrapper.className = 'flex items-start gap-4 bg-white p-3 rounded-lg border border-gray-200';
                    wrapper.innerHTML =
                        '<div class="w-20 h-20 bg-gray-100 rounded-lg overflow-hidden flex items-center justify-center">'
                        + '<img id="it-' + dayNum + '-img-preview-' + imgIndex + '" src="' + (imgObj.preview||'') + '" class="w-full h-full object-cover" style="display:' + (imgObj.preview?'block':'none') + '">'
                        + '<svg class="w-8 h-8 text-gray-400" style="display:' + (imgObj.preview?'none':'block') + '" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></div>'
                        + '<div class="flex-1">'
                        + (imgObj.existingMediaId
                            ? '<input type="hidden" name="itinerary[' + dayNum + '][existing_media_ids][]" value="' + imgObj.existingMediaId + '">'
                              + '<p class="text-sm font-medium mb-1">Existing image</p>'
                              + '<p class="text-xs text-gray-500 truncate">' + escHtml(imgObj.storage_path||imgObj.preview||'') + '</p>'
                              + '<input type="text" name="itinerary[' + dayNum + '][image_captions][]" value="' + escHtml(imgObj.caption||'') + '" placeholder="Caption (optional)" class="mt-2 w-full px-3 py-2 border border-gray-300 rounded-lg caption-input">'
                            : '<input type="file" accept="image/*" name="itinerary[' + dayNum + '][uploads][' + imgObj.tempId + ']" class="w-full image-input" data-day="' + i + '" data-img="' + imgIndex + '">'
                              + '<input type="text" name="itinerary[' + dayNum + '][image_captions][]" value="' + escHtml(imgObj.caption||'') + '" placeholder="Caption (optional)" class="mt-2 w-full px-3 py-2 border border-gray-300 rounded-lg caption-input">'
                        )
                        + '</div>'
                        + '<div class="flex-shrink-0"><button type="button" class="bg-red-600 hover:bg-red-700 text-white rounded-lg px-3 py-2 remove-day-image">Remove</button></div>';
                    dayImagesContainer.appendChild(wrapper);

                    var fileInput = wrapper.querySelector('.image-input');
                    if (fileInput) {
                        fileInput.addEventListener('change', function () {
                            var file = this.files[0];
                            if (file) {
                                var reader = new FileReader();
                                reader.onload = function (ev) {
                                    imgObj.preview = ev.target.result;
                                    var imgEl = document.getElementById('it-' + dayNum + '-img-preview-' + imgIndex);
                                    imgEl.src = ev.target.result; imgEl.style.display = 'block';
                                    if (imgEl.nextElementSibling) imgEl.nextElementSibling.style.display = 'none';
                                    saveFormData();
                                };
                                reader.readAsDataURL(file);
                            }
                        });
                    }
                    var captionInput = wrapper.querySelector('.caption-input');
                    if (captionInput) captionInput.addEventListener('input', function () { imgObj.caption = this.value; saveFormData(); });
                    wrapper.querySelector('.remove-day-image').addEventListener('click', function () {
                        var toRemove = day.images[imgIndex];
                        if (toRemove && toRemove.existingMediaId) {
                            var hi = document.createElement('input'); hi.type = 'hidden';
                            hi.name = 'delete_itinerary_image_ids[]'; hi.value = toRemove.existingMediaId;
                            document.querySelector('form').appendChild(hi);
                        }
                        day.images.splice(imgIndex, 1); renderDayImages(); saveFormData();
                    });
                });
            }

            el.querySelector('.add-day-image').addEventListener('click', function () {
                day.images.push({ preview:'', caption:'', tempId: tempId(), blockId:'blk-'+uuid(), existingMediaId:null });
                renderDayImages(); saveFormData();
            });

            var actTA = el.querySelector('.auto-resize');
            actTA.addEventListener('input', function () { autoResize(this); day.activity = this.value; saveFormData(); });
            el.querySelectorAll('input[name^="itinerary"], textarea[name^="itinerary"], select[name^="itinerary"]').forEach(function (input) {
                input.addEventListener('input', function () {
                    var match = this.getAttribute('name').match(/itinerary\[\d+\]\[([^\]]+)\]/);
                    if (match) {
                        var key = match[1];
                        if (key === 'day_number' || key === 'id' || key === 'content_blocks') return;
                        if (key !== 'images' && key !== 'image_captions' && key !== 'uploads' && key !== 'existing_media_ids') day[key] = this.value;
                    }
                    saveFormData();
                });
            });

            renderDayImages();
            autoResize(actTA);
        });
    }

    document.getElementById('add-itinerary-day').onclick = function () {
        itineraryDays.push({ activity:'', day_title:'', accommodation:'', accommodation_id:null, meals:'', images:[], blocks:[] });
        renderItinerary(); saveFormData();
    };

    function buildAndAttachContentBlocks() {
        document.getElementById('itinerary-days').querySelectorAll('[data-contentblock-input]').forEach(function (hiddenInput, idx) {
            var dayData = itineraryDays[idx];
            if (!dayData) return;
            var blocks = [];
            if (Array.isArray(dayData.blocks) && dayData.blocks.length > 0) {
                dayData.blocks.forEach(function (b) {
                    if (b.type === 'image') {
                        if (!b.id) b.id = 'blk-' + uuid();
                        if (!b.media_id && !b.temp_media_id) {
                            var match = dayData.images.find(function (im) {
                                return (im.existingMediaId && im.existingMediaId.toString() === (b.media_id ? b.media_id.toString() : ''))
                                    || im.caption === b.caption || im.preview === b.preview;
                            });
                            if (match) { if (match.existingMediaId) b.media_id = match.existingMediaId; else if (match.tempId) b.temp_media_id = match.tempId; }
                        }
                    }
                    blocks.push(b);
                });
            } else {
                if (dayData.activity && dayData.activity.trim()) blocks.push({ id:'blk-'+uuid(), type:'text', text:dayData.activity.trim() });
                dayData.images.forEach(function (img) {
                    blocks.push({ id: img.blockId||('blk-'+uuid()), type:'image', caption:img.caption||'', media_id:img.existingMediaId||undefined, temp_media_id:img.existingMediaId?undefined:img.tempId });
                });
            }
            hiddenInput.value = JSON.stringify(blocks);
        });
    }

    // ══════════════════════════════════════════════════════════════════════
    // PRICES
    // ══════════════════════════════════════════════════════════════════════
    var prices = [];
    @if($tour->prices && count($tour->prices) > 0)
        prices = {!! json_encode($tour->prices->map(function($item) {
            return [ 'id' => $item->id ?? null, 'group_size' => $item->group_size ?? '', 'price' => $item->price ?? '' ];
        })->values()->toArray()) !!};
    @endif

    function renderPrices() {
        var container = document.getElementById('prices');
        var noMsg     = document.getElementById('no-prices-message');
        if (prices.length === 0) { noMsg.style.display = 'block'; container.innerHTML = ''; return; }
        noMsg.style.display = 'none'; container.innerHTML = '';
        prices.forEach(function (price, i) {
            var prNum = i + 1;
            var el = document.createElement('div');
            el.className = 'relative bg-green-50 rounded-lg p-6 border-2 border-green-200 hover:border-green-300 transition duration-150';
            el.innerHTML =
                (price.id ? '<input type="hidden" name="prices[' + prNum + '][id]" value="' + price.id + '">' : '')
                + '<div class="grid grid-cols-1 md:grid-cols-2 gap-4">'
                + '<div><label class="block text-sm font-medium text-gray-700 mb-2">Group Size <span class="text-red-500">*</span></label>'
                + '<div class="relative"><div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">'
                + '<svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg></div>'
                + '<input type="number" name="prices[' + prNum + '][group_size]" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-150" min="1" required value="' + escHtml(String(price.group_size||'')) + '" placeholder="e.g., 2"></div></div>'
                + '<div><label class="block text-sm font-medium text-gray-700 mb-2">Price (USD) <span class="text-red-500">*</span></label>'
                + '<div class="relative"><div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"><span class="text-gray-500 font-medium">$</span></div>'
                + '<input type="number" step="0.01" name="prices[' + prNum + '][price]" class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-150" min="0" required value="' + escHtml(String(price.price||'')) + '" placeholder="0.00"></div></div>'
                + '</div>'
                + '<button type="button" class="absolute top-4 right-4 bg-red-600 hover:bg-red-700 text-white rounded-lg px-3 py-2 transition duration-150 flex items-center gap-2 font-medium text-sm shadow-sm remove-price">'
                + '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>Remove</button>';
            el.querySelector('.remove-price').onclick = function () { prices.splice(i,1); renderPrices(); saveFormData(); };
            el.querySelectorAll('input').forEach(function (input) {
                input.addEventListener('input', function () {
                    if (this.name.includes('[group_size]')) price.group_size = this.value;
                    if (this.name.includes('[price]'))      price.price      = this.value;
                    saveFormData();
                });
            });
            container.appendChild(el);
        });
    }
    document.getElementById('add-price').onclick = function () { prices.push({ group_size:'', price:'' }); renderPrices(); saveFormData(); };

    // ══════════════════════════════════════════════════════════════════════
    // IMAGE GALLERY
    // ══════════════════════════════════════════════════════════════════════
    var imagesList = [];
    @if($tour->images && count($tour->images) > 0)
        imagesList = {!! json_encode($tour->images->map(function($item) {
            return [ 'id' => $item->id, 'path' => $item->image_path, 'isExisting' => true ];
        })->toArray()) !!};
    @endif

    function renderImagesList() {
        var div   = document.getElementById('images-list');
        var noMsg = document.getElementById('no-images-message');
        if (imagesList.length === 0) { noMsg.style.display = 'block'; div.innerHTML = ''; return; }
        noMsg.style.display = 'none'; div.innerHTML = '';
        imagesList.forEach(function (imgObj, i) {
            var wrapper    = document.createElement('div');
            var isExisting = imgObj.isExisting || false;
            wrapper.className = 'flex items-center gap-4 ' + (isExisting ? 'bg-blue-50' : 'bg-gray-50')
                + ' p-4 rounded-lg border-2 ' + (isExisting ? 'border-blue-200 hover:border-blue-300' : 'border-gray-200 hover:border-indigo-300') + ' transition duration-150';

            if (isExisting) {
                wrapper.innerHTML =
                    '<div class="flex-shrink-0"><div class="w-20 h-20 bg-gray-100 rounded-lg border-2 border-gray-300 overflow-hidden">'
                    + '<img src="{{ asset("storage/") }}/' + imgObj.path + '" class="w-full h-full object-cover"></div></div>'
                    + '<div class="flex-1"><p class="text-sm font-medium text-blue-700">Existing Image</p><p class="text-xs text-gray-500 truncate">' + escHtml(imgObj.path) + '</p></div>'
                    + '<button type="button" class="flex-shrink-0 bg-red-600 hover:bg-red-700 text-white rounded-lg px-3 py-2 transition duration-150 flex items-center gap-2 font-medium text-sm shadow-sm delete-img">'
                    + '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>Delete</button>';
                wrapper.querySelector('.delete-img').onclick = function () {
                    var hi = document.createElement('input'); hi.type = 'hidden'; hi.name = 'delete_images[]'; hi.value = imgObj.id;
                    document.querySelector('form').appendChild(hi);
                    imagesList.splice(i, 1); renderImagesList(); saveFormData();
                };
            } else {
                wrapper.innerHTML =
                    '<div class="flex-shrink-0"><div class="w-20 h-20 bg-gray-100 rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center overflow-hidden">'
                    + '<img src="' + (imgObj.preview||'') + '" class="w-full h-full object-cover" style="display:' + (imgObj.preview?'block':'none') + '" id="img-preview-' + i + '">'
                    + '<svg class="w-8 h-8 text-gray-400" style="display:' + (imgObj.preview?'none':'block') + '" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></div></div>'
                    + '<div class="flex-1"><input type="file" accept="image/*" name="images[]" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 image-input" data-index="' + i + '"></div>'
                    + '<button type="button" class="flex-shrink-0 bg-red-600 hover:bg-red-700 text-white rounded-lg px-3 py-2 transition duration-150 flex items-center gap-2 font-medium text-sm shadow-sm remove-img">'
                    + '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>';
                wrapper.querySelector('.image-input').addEventListener('change', function () {
                    var file = this.files[0];
                    if (file) {
                        var reader = new FileReader();
                        reader.onload = function (ev) {
                            imgObj.preview = ev.target.result;
                            var imgEl = document.getElementById('img-preview-' + i);
                            imgEl.src = ev.target.result; imgEl.style.display = 'block';
                            imgEl.nextElementSibling.style.display = 'none';
                            saveFormData();
                        };
                        reader.readAsDataURL(file);
                    }
                });
                wrapper.querySelector('.remove-img').onclick = function () { imagesList.splice(i,1); renderImagesList(); saveFormData(); };
            }
            div.appendChild(wrapper);
        });
    }
    document.getElementById('add-image').onclick = function () { imagesList.push({ preview:'', isExisting:false }); renderImagesList(); saveFormData(); };

    // ══════════════════════════════════════════════════════════════════════
    // INITIAL RENDER
    // ══════════════════════════════════════════════════════════════════════
    renderItinerary();
    renderPrices();
    renderImagesList();
    loadSavedData();
});
</script>
@endsection