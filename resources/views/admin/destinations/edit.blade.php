@extends('layouts.admin')

@section('title', 'Edit Destination')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Edit Destination</h1>
            <p class="text-gray-600 mt-1">Modify destination details and inline content</p>
        </div>
        <a href="{{ route('admin.destinations.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center transition">
            <i class="fas fa-arrow-left mr-2"></i> Back to List
        </a>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 flex items-center justify-between">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    {{-- Error Message --}}
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6 flex items-center justify-between">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <span>{{ session('error') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-red-700 hover:text-red-900">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <div class="flex items-center mb-2">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <strong>Please fix the following errors:</strong>
            </div>
            <ul class="list-disc list-inside ml-4">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="destinationEditForm" action="{{ route('admin.destinations.update', $destination->id) }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-lg rounded-lg">
        @csrf
        @method('PUT')

        <div class="border-b border-gray-200">
            <nav class="flex -mb-px overflow-x-auto">
                <button type="button" class="tab-button active px-6 py-3 border-b-2 font-medium text-sm whitespace-nowrap" data-tab="basic">Basic Info</button>
                <button type="button" class="tab-button px-6 py-3 border-b-2 font-medium text-sm whitespace-nowrap" data-tab="overview">Overview</button>
                <button type="button" class="tab-button px-6 py-3 border-b-2 font-medium text-sm whitespace-nowrap" data-tab="activities">Activities</button>
                <button type="button" class="tab-button px-6 py-3 border-b-2 font-medium text-sm whitespace-nowrap" data-tab="wildlife">Wildlife</button>
                <button type="button" class="tab-button px-6 py-3 border-b-2 font-medium text-sm whitespace-nowrap" data-tab="geography">Geography</button>
                <button type="button" class="tab-button px-6 py-3 border-b-2 font-medium text-sm whitespace-nowrap" data-tab="practical">Practical Info</button>
                <button type="button" class="tab-button px-6 py-3 border-b-2 font-medium text-sm whitespace-nowrap" data-tab="accommodation">Accommodation</button>
                <button type="button" class="tab-button px-6 py-3 border-b-2 font-medium text-sm whitespace-nowrap" data-tab="extras">Extras</button>
                <button type="button" class="tab-button px-6 py-3 border-b-2 font-medium text-sm whitespace-nowrap" data-tab="images">Images</button>
                <button type="button" class="tab-button px-6 py-3 border-b-2 font-medium text-sm whitespace-nowrap" data-tab="seo">SEO</button>
            </nav>
        </div>

        <div class="p-6">
            <!-- Basic Info -->
            <div id="tab-basic" class="tab-content">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Basic Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="country_id" class="block text-sm font-medium text-gray-700 mb-2">Country <span class="text-red-500">*</span></label>
                        <select name="country_id" id="country_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            <option value="">Select Country</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}" {{ old('country_id', $destination->country_id) == $country->id ? 'selected' : '' }}>
                                    {!! $country->flag_icon !!} {{ $country->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Destination Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="{{ old('name', $destination->name) }}" placeholder="e.g., Murchison Falls National Park">
                    </div>

                    <div>
                        <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">URL Slug</label>
                        <input type="text" name="slug" id="slug" class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="{{ old('slug', $destination->slug) }}" placeholder="murchison-falls-national-park">
                        <p class="text-gray-500 text-xs mt-1">Leave empty to auto-generate from name</p>
                    </div>

                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Destination Type</label>
                        <select name="type" id="type" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            <option value="">Select Type</option>
                            @foreach(['National Park','Wildlife Reserve','Forest Reserve','Game Reserve','Conservation Area','Wildlife Sanctuary','City','Lake','Mountain','Island'] as $t)
                                <option value="{{ $t }}" {{ old('type', $destination->type) == $t ? 'selected' : '' }}>{{ $t }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">Sort Order</label>
                        <input type="number" name="sort_order" id="sort_order" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="{{ old('sort_order', $destination->sort_order) }}">
                        <p class="text-gray-500 text-xs mt-1">Lower numbers appear first</p>
                    </div>
                </div>

                <div class="mt-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Short Description</label>
                    <textarea name="description" id="description" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="Short overview...">{{ old('description', $destination->description) }}</textarea>
                </div>

                <div class="mt-6 space-y-3">
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" class="w-5 h-5 rounded border-gray-300 text-green-600" {{ old('is_active', $destination->is_active) ? 'checked' : '' }}>
                        <div><span class="text-sm font-medium text-gray-700">Active</span><p class="text-xs text-gray-500">Visible on site</p></div>
                    </label>

                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" name="is_popular" value="1" class="w-5 h-5 rounded border-gray-300 text-green-600" {{ old('is_popular', $destination->is_popular) ? 'checked' : '' }}>
                        <div><span class="text-sm font-medium text-gray-700">Mark as Popular</span><p class="text-xs text-gray-500">Feature on homepage</p></div>
                    </label>
                </div>
            </div>

            {{-- Sections rendering --}}
            @php
                $sectionsList = [
                    'overview' => ['label'=>'Detailed Overview','textarea'=>'detailed_overview'],
                    'activities' => ['label'=>'Activities','textarea'=>'what_to_see_do'],
                    'wildlife' => ['label'=>'Wildlife Highlights','textarea'=>'wildlife_highlights'],
                    'geography' => ['label'=>'Geography & Landscape','textarea'=>'geography_landscape'],
                    'practical' => ['label'=>'Practical Information','textarea'=>'practical_information'],
                    'accommodation' => ['label'=>'Accommodation Options','textarea'=>'accommodation_options'],
                    'extras' => ['label'=>'Additional Information','textarea'=>'interesting_facts'],
                ];
            @endphp

            @foreach($sectionsList as $sectionKey => $cfg)
                @php
                    $textareaId = $cfg['textarea'];
                    $initialText = old($textareaId, '');
                    $sectionsContent = $destination->sections_content ?? [];
                    
                    // Store block metadata for JavaScript to use
                    $blockMetadata = [];
                    
                    if (empty($initialText)) {
                        if (!empty($sectionsContent[$sectionKey]) && is_array($sectionsContent[$sectionKey])) {
                            $parts = [];
                            foreach ($sectionsContent[$sectionKey] as $block) {
                                $type = $block['type'] ?? 'text';
                                $blockId = $block['id'] ?? 'blk-' . Str::uuid();
                                
                                if ($type === 'heading') {
                                    $parts[] = '# ' . ($block['text'] ?? '');
                                } elseif ($type === 'subheading') {
                                    $parts[] = '## ' . ($block['text'] ?? '');
                                } elseif ($type === 'text') {
                                    $parts[] = $block['text'] ?? '';
                                } elseif ($type === 'image') {
                                    $mediaId = $block['media_id'] ?? null;
                                    $caption = $block['caption'] ?? '';
                                    
                                    if ($mediaId) {
                                        $token = "block-{$blockId}";
                                        $parts[] = "[[image:{$token}|{$caption}]]";
                                        
                                        $blockMetadata[$token] = [
                                            'block_id' => $blockId,
                                            'media_id' => $mediaId,
                                            'caption' => $caption
                                        ];
                                    }
                                }
                            }
                            $initialText = implode("\n\n", $parts);
                        } else {
                            $initialText = old($textareaId, $destination->{$textareaId} ?? '');
                        }
                    }
                @endphp

                <div id="tab-{{ $sectionKey }}" class="tab-content {{ $sectionKey !== 'overview' ? 'hidden' : '' }}" data-section="{{ $sectionKey }}" data-block-metadata="{{ json_encode($blockMetadata) }}">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">{{ $cfg['label'] }}</h2>

                    <div>
                        <label for="{{ $textareaId }}" class="block text-sm font-medium text-gray-700 mb-2">{{ $cfg['label'] }} Content</label>
                        <textarea name="{{ $textareaId }}" id="{{ $textareaId }}" rows="12" class="section-textarea w-full px-4 py-2 border border-gray-300 rounded-lg font-mono text-sm" placeholder="Write content...">{{ $initialText }}</textarea>
                        <p class="text-gray-500 text-xs mt-1">Use headings (# Heading), subheadings (## Subheading), and paragraphs. Click "Add Image" button below to insert images.</p>
                    </div>

                    <div class="mt-4">
                        <div class="flex flex-wrap gap-2 items-center mb-3">
                            <button type="button" class="px-3 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 insert-heading" data-section="{{ $sectionKey }}">
                                <i class="fas fa-heading mr-1"></i> Add Heading
                            </button>
                            <button type="button" class="px-3 py-2 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 insert-subheading" data-section="{{ $sectionKey }}">
                                <i class="fas fa-heading mr-1 text-sm"></i> Add Subheading
                            </button>
                            <button type="button" class="px-3 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 insert-image" data-section="{{ $sectionKey }}">
                                <i class="fas fa-image mr-1"></i> Add Image
                            </button>
                                <button type="button" class="px-3 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 insert-icon" data-section="{{ $sectionKey }}">
                                <i class="fas fa-icons mr-1"></i> Add Icon
                                </button>
                        </div>

                        {{-- Image upload blocks container --}}
                        <div id="section-uploads-{{ $sectionKey }}" class="space-y-4 mb-4">
                            {{-- Prepopulate upload preview blocks for existing images --}}
                            @if(!empty($sectionsContent[$sectionKey]) && is_array($sectionsContent[$sectionKey]))
                                @foreach($sectionsContent[$sectionKey] as $block)
                                    @if(!empty($block['type']) && $block['type'] === 'image' && !empty($block['media_id']))
                                        @php
                                            $img = \App\Models\DestinationImage::find($block['media_id']);
                                            $url = $img ? ($img->thumbnail_path ? asset('storage/' . $img->thumbnail_path) : asset('storage/' . $img->storage_path)) : null;
                                            $blockId = $block['id'] ?? 'blk-' . Str::uuid();
                                            $tokenId = "block-{$blockId}";
                                        @endphp
                                        <div class="bg-gray-50 border-2 border-gray-200 rounded-lg p-4 existing-upload" 
                                             data-block-id="{{ $blockId }}"
                                             data-token-id="{{ $tokenId }}"
                                             data-media-id="{{ $block['media_id'] }}">
                                            <div class="flex items-start gap-4">
                                                <div class="w-32 h-24 bg-gray-200 rounded-lg overflow-hidden flex-shrink-0">
                                                    @if($url)
                                                        <img src="{{ $url }}" class="w-full h-full object-cover">
                                                    @else
                                                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                            <i class="fas fa-image text-3xl"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="flex-1">
                                                    <label class="block text-sm font-medium text-gray-700 mb-2">Caption</label>
                                                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg caption-input" value="{{ $block['caption'] ?? '' }}" placeholder="Enter caption (optional)">
                                                    <div class="mt-3">
                                                        <label class="block text-sm font-medium text-gray-700 mb-2">Replace Image</label>
                                                        <input type="file" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 replace-upload-file" data-media-id="{{ $block['media_id'] }}" data-block-id="{{ $blockId }}" name="sections[{{ $sectionKey }}][uploads][media-{{ $block['media_id'] }}]">
                                                    </div>
                                                </div>
                                                <button type="button" class="px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm remove-existing-media" data-media-id="{{ $block['media_id'] }}" data-block-id="{{ $blockId }}">
                                                    <i class="fas fa-trash mr-1"></i> Remove
                                                </button>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>

                        {{-- Hidden content_blocks JSON input --}}
                        <input type="hidden" data-contentblock-input name="sections[{{ $sectionKey }}][content_blocks]" value="">
                    </div>
                </div>
            @endforeach

            <!-- Images tab (top-level) -->
            <div id="tab-images" class="tab-content hidden">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Images & Media</h2>

                <div class="mb-6">
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Main Thumbnail Image</label>
                    <input type="file" name="image" id="image" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    <p class="text-gray-500 text-xs mt-1">Used in listings. Recommended: 800x600px, Max: 2MB</p>
                    <div id="image-preview" class="mt-3">
                        @if($destination->image)
                            <img src="{{ asset('storage/' . $destination->image) }}" class="w-64 h-40 object-cover rounded">
                        @endif
                    </div>
                </div>

                <div class="mb-6">
                    <label for="featured_image" class="block text-sm font-medium text-gray-700 mb-2">Featured Header Image</label>
                    <input type="file" name="featured_image" id="featured_image" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    <p class="text-gray-500 text-xs mt-1">Hero/header. Recommended: 1920x1080px, Max: 5MB</p>
                    <div id="featured-preview" class="mt-3">
                        @if($destination->featured_image)
                            <img src="{{ asset('storage/' . $destination->featured_image) }}" class="w-96 h-64 object-cover rounded">
                        @endif
                    </div>
                </div>

                <div class="mb-6">
                    <label for="gallery_images" class="block text-sm font-medium text-gray-700 mb-2">Gallery Images</label>
                    <input type="file" name="gallery_images[]" id="gallery_images" accept="image/*" multiple class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    <p class="text-gray-500 text-xs mt-1">Multiple images for photo gallery. Max: 2MB each</p>
                    <div id="gallery-preview" class="mt-3 grid grid-cols-2 md:grid-cols-4 gap-4">
                        @if(!empty($destination->gallery_images) && is_array($destination->gallery_images))
                            @foreach($destination->gallery_images as $g)
                                <div class="relative"><img src="{{ asset('storage/' . $g['image']) }}" class="w-full h-32 object-cover rounded"></div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <!-- SEO -->
            <div id="tab-seo" class="tab-content hidden">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">SEO & Meta Information</h2>
                <div class="space-y-6">
                    <div>
                        <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-2">Meta Title</label>
                        <input type="text" name="meta_title" id="meta_title" maxlength="60" class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="{{ old('meta_title', $destination->meta_title) }}">
                    </div>

                    <div>
                        <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
                        <textarea name="meta_description" id="meta_description" rows="3" maxlength="160" class="w-full px-4 py-2 border border-gray-300 rounded-lg">{{ old('meta_description', $destination->meta_description) }}</textarea>
                    </div>

                    <div>
                        <label for="meta_keywords" class="block text-sm font-medium text-gray-700 mb-2">Meta Keywords</label>
                        <input type="text" name="meta_keywords" id="meta_keywords" class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="{{ old('meta_keywords', $destination->meta_keywords) }}">
                    </div>
                </div>
            </div>
        </div>

        <div class="border-t bg-gray-50 px-6 py-4 flex justify-between items-center rounded-b-lg">
            <a href="{{ route('admin.destinations.index') }}" class="text-gray-600 hover:text-gray-800 font-medium"><i class="fas fa-times mr-1"></i> Cancel</a>

            <div class="flex gap-3">
                <button type="button" id="saveDraftBtn" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg font-medium">Save Draft</button>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium transition flex items-center shadow-md"><i class="fas fa-save mr-2"></i> Save Changes</button>
            </div>
        </div>
    </form>
</div>

{{-- Icon Picker Modal --}}
<div id="iconPickerModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl max-h-[90vh] overflow-hidden">
        <div class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white px-6 py-4 flex items-center justify-between">
            <h3 class="text-xl font-bold"><i class="fas fa-icons mr-2"></i> Choose an Icon</h3>
            <button type="button" id="closeIconPicker" class="text-white hover:text-gray-200">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        
        <div class="p-4">
            <input type="text" id="iconSearch" placeholder="Search icons... (e.g., 'star', 'animal', 'tree')" class="w-full px-4 py-2 border border-gray-300 rounded-lg mb-4 focus:ring-2 focus:ring-purple-500">
            
            <div id="iconGrid" class="grid grid-cols-6 sm:grid-cols-8 md:grid-cols-10 gap-3 overflow-y-auto max-h-[60vh] p-2">
                <!-- Icons will be populated here -->
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Store block metadata globally for each section
    const sectionBlockMetadata = {};
    document.querySelectorAll('[data-block-metadata]').forEach(tab => {
        const section = tab.dataset.section;
        try {
            sectionBlockMetadata[section] = JSON.parse(tab.dataset.blockMetadata || '{}');
        } catch(e) {
            sectionBlockMetadata[section] = {};
        }
    });

    // Helpers
    function uuid(){ return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g,function(c){const r=Math.random()*16|0,v=c==='x'?r:(r&0x3|0x8);return v.toString(16);}); }
    function tempId(){ return 'tmp-' + Math.random().toString(36).substr(2,9); }
    function insertAtCursor(textarea, text) {
        const start = textarea.selectionStart, end = textarea.selectionEnd, val = textarea.value;
        textarea.value = val.slice(0, start) + text + val.slice(end);
        textarea.selectionStart = textarea.selectionEnd = start + text.length;
        textarea.focus();
    }
    function slugify(v){ return v.toLowerCase().replace(/[^\w\s-]/g,'').replace(/\s+/g,'-').replace(/-+/g,'-').trim(); }

    // ✅ Popular FontAwesome Icons Database
    const iconDatabase = [
        // Animals & Nature
        { name: 'paw', icon: 'fas fa-paw', category: 'animals', keywords: 'animal pet dog cat' },
        { name: 'horse', icon: 'fas fa-horse', category: 'animals', keywords: 'animal wildlife' },
        { name: 'dove', icon: 'fas fa-dove', category: 'animals', keywords: 'bird animal' },
        { name: 'fish', icon: 'fas fa-fish', category: 'animals', keywords: 'water animal' },
        { name: 'frog', icon: 'fas fa-frog', category: 'animals', keywords: 'water animal' },
        { name: 'hippo', icon: 'fas fa-hippo', category: 'animals', keywords: 'animal wildlife' },
        { name: 'crow', icon: 'fas fa-crow', category: 'animals', keywords: 'bird animal' },
        { name: 'spider', icon: 'fas fa-spider', category: 'animals', keywords: 'insect animal' },
        { name: 'dragon', icon: 'fas fa-dragon', category: 'animals', keywords: 'animal mythical' },
        
        // Nature
        { name: 'tree', icon: 'fas fa-tree', category: 'nature', keywords: 'forest plant nature' },
        { name: 'leaf', icon: 'fas fa-leaf', category: 'nature', keywords: 'plant nature green' },
        { name: 'seedling', icon: 'fas fa-seedling', category: 'nature', keywords: 'plant nature grow' },
        { name: 'mountain', icon: 'fas fa-mountain', category: 'nature', keywords: 'landscape hill' },
        { name: 'water', icon: 'fas fa-water', category: 'nature', keywords: 'river lake ocean' },
        { name: 'sun', icon: 'fas fa-sun', category: 'nature', keywords: 'weather day' },
        { name: 'moon', icon: 'fas fa-moon', category: 'nature', keywords: 'weather night' },
        { name: 'cloud', icon: 'fas fa-cloud', category: 'nature', keywords: 'weather sky' },
        { name: 'snowflake', icon: 'fas fa-snowflake', category: 'nature', keywords: 'weather cold' },
        { name: 'fire', icon: 'fas fa-fire', category: 'nature', keywords: 'hot flame' },
        
        // Location & Travel
        { name: 'map-marker', icon: 'fas fa-map-marker-alt', category: 'location', keywords: 'location place pin' },
        { name: 'map', icon: 'fas fa-map', category: 'location', keywords: 'location navigation' },
        { name: 'compass', icon: 'fas fa-compass', category: 'location', keywords: 'direction navigation' },
        { name: 'globe', icon: 'fas fa-globe-africa', category: 'location', keywords: 'world earth africa' },
        { name: 'route', icon: 'fas fa-route', category: 'location', keywords: 'path direction' },
        { name: 'plane', icon: 'fas fa-plane', category: 'travel', keywords: 'flight travel airplane' },
        { name: 'car', icon: 'fas fa-car', category: 'travel', keywords: 'vehicle travel' },
        { name: 'bus', icon: 'fas fa-bus', category: 'travel', keywords: 'vehicle travel transport' },
        { name: 'suitcase', icon: 'fas fa-suitcase-rolling', category: 'travel', keywords: 'luggage travel' },
        { name: 'passport', icon: 'fas fa-passport', category: 'travel', keywords: 'travel document' },
        
        // Accommodation
        { name: 'hotel', icon: 'fas fa-hotel', category: 'accommodation', keywords: 'lodge stay accommodation' },
        { name: 'bed', icon: 'fas fa-bed', category: 'accommodation', keywords: 'sleep rest accommodation' },
        { name: 'campground', icon: 'fas fa-campground', category: 'accommodation', keywords: 'camping tent outdoor' },
        { name: 'home', icon: 'fas fa-home', category: 'accommodation', keywords: 'house building' },
        { name: 'building', icon: 'fas fa-building', category: 'accommodation', keywords: 'hotel structure' },
        
        // Activities
        { name: 'binoculars', icon: 'fas fa-binoculars', category: 'activities', keywords: 'safari viewing wildlife' },
        { name: 'camera', icon: 'fas fa-camera', category: 'activities', keywords: 'photo photography' },
        { name: 'hiking', icon: 'fas fa-hiking', category: 'activities', keywords: 'walking trek' },
        { name: 'swimming', icon: 'fas fa-swimming-pool', category: 'activities', keywords: 'pool water' },
        { name: 'biking', icon: 'fas fa-biking', category: 'activities', keywords: 'cycling bicycle' },
        
        // Food & Dining
        { name: 'utensils', icon: 'fas fa-utensils', category: 'food', keywords: 'food restaurant dining' },
        { name: 'coffee', icon: 'fas fa-coffee', category: 'food', keywords: 'drink cafe' },
        { name: 'wine', icon: 'fas fa-wine-glass', category: 'food', keywords: 'drink bar' },
        { name: 'apple', icon: 'fas fa-apple-alt', category: 'food', keywords: 'fruit food' },
        
        // Money & Payment
        { name: 'dollar', icon: 'fas fa-dollar-sign', category: 'money', keywords: 'money price cost fee' },
        { name: 'money', icon: 'fas fa-money-bill-wave', category: 'money', keywords: 'cash payment' },
        { name: 'credit-card', icon: 'fas fa-credit-card', category: 'money', keywords: 'payment card' },
        { name: 'coins', icon: 'fas fa-coins', category: 'money', keywords: 'money currency' },
        
        // Health & Safety
        { name: 'medkit', icon: 'fas fa-briefcase-medical', category: 'health', keywords: 'medical health first-aid' },
        { name: 'shield', icon: 'fas fa-shield-alt', category: 'safety', keywords: 'protection security safe' },
        { name: 'heartbeat', icon: 'fas fa-heartbeat', category: 'health', keywords: 'medical health' },
        { name: 'pills', icon: 'fas fa-pills', category: 'health', keywords: 'medicine medication' },
        
        // Communication
        { name: 'phone', icon: 'fas fa-phone', category: 'communication', keywords: 'call contact' },
        { name: 'envelope', icon: 'fas fa-envelope', category: 'communication', keywords: 'email mail message' },
        { name: 'wifi', icon: 'fas fa-wifi', category: 'communication', keywords: 'internet connection' },
        { name: 'mobile', icon: 'fas fa-mobile-alt', category: 'communication', keywords: 'cellphone smartphone' },
        
        // Time & Calendar
        { name: 'clock', icon: 'fas fa-clock', category: 'time', keywords: 'time hours' },
        { name: 'calendar', icon: 'fas fa-calendar-alt', category: 'time', keywords: 'date schedule booking' },
        { name: 'calendar-check', icon: 'fas fa-calendar-check', category: 'time', keywords: 'booking reservation' },
        
        // Weather
        { name: 'temperature-high', icon: 'fas fa-temperature-high', category: 'weather', keywords: 'hot warm' },
        { name: 'temperature-low', icon: 'fas fa-temperature-low', category: 'weather', keywords: 'cold cool' },
        { name: 'umbrella', icon: 'fas fa-umbrella', category: 'weather', keywords: 'rain protection' },
        
        // General
        { name: 'star', icon: 'fas fa-star', category: 'general', keywords: 'favorite rating luxury' },
        { name: 'heart', icon: 'fas fa-heart', category: 'general', keywords: 'love favorite' },
        { name: 'check', icon: 'fas fa-check-circle', category: 'general', keywords: 'correct yes done' },
        { name: 'info', icon: 'fas fa-info-circle', category: 'general', keywords: 'information help' },
        { name: 'warning', icon: 'fas fa-exclamation-triangle', category: 'general', keywords: 'alert danger caution' },
        { name: 'ban', icon: 'fas fa-ban', category: 'general', keywords: 'prohibited forbidden no' },
        { name: 'eye', icon: 'fas fa-eye', category: 'general', keywords: 'view see watch' },
        { name: 'users', icon: 'fas fa-users', category: 'general', keywords: 'people group family' },
        { name: 'user', icon: 'fas fa-user', category: 'general', keywords: 'person profile' },
        { name: 'arrow-right', icon: 'fas fa-arrow-right', category: 'general', keywords: 'direction next' },
        { name: 'arrow-left', icon: 'fas fa-arrow-left', category: 'general', keywords: 'direction back' },
        { name: 'bookmark', icon: 'fas fa-bookmark', category: 'general', keywords: 'save mark' },
        { name: 'gift', icon: 'fas fa-gift', category: 'general', keywords: 'present package' },
    ];

    // Current textarea reference for icon insertion
    let currentIconTextarea = null;

    // ✅ Open Icon Picker Modal
    function openIconPicker(textarea) {
        currentIconTextarea = textarea;
        const modal = document.getElementById('iconPickerModal');
        const grid = document.getElementById('iconGrid');
        
        // Populate icon grid
        grid.innerHTML = iconDatabase.map(item => `
            <div class="icon-item flex flex-col items-center justify-center p-3 border-2 border-gray-200 rounded-lg hover:border-purple-500 hover:bg-purple-50 cursor-pointer transition" data-icon="${item.icon}" title="${item.name}">
                <i class="${item.icon} text-2xl text-gray-700 mb-1"></i>
                <span class="text-xs text-gray-600 text-center">${item.name}</span>
            </div>
        `).join('');
        
        // Show modal
        modal.classList.remove('hidden');
        
        // Setup icon click handlers
        grid.querySelectorAll('.icon-item').forEach(item => {
            item.addEventListener('click', function() {
                const iconClass = this.dataset.icon;
                insertIcon(iconClass);
            });
        });
        
        // Setup search
        document.getElementById('iconSearch').focus();
    }

    // ✅ Close Icon Picker
    document.getElementById('closeIconPicker').addEventListener('click', function() {
        document.getElementById('iconPickerModal').classList.add('hidden');
        currentIconTextarea = null;
    });

    // ✅ Icon Search Filter
    document.getElementById('iconSearch').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const grid = document.getElementById('iconGrid');
        
        const filtered = iconDatabase.filter(item => 
            item.name.includes(searchTerm) || 
            item.keywords.includes(searchTerm) ||
            item.category.includes(searchTerm)
        );
        
        grid.innerHTML = filtered.map(item => `
            <div class="icon-item flex flex-col items-center justify-center p-3 border-2 border-gray-200 rounded-lg hover:border-purple-500 hover:bg-purple-50 cursor-pointer transition" data-icon="${item.icon}" title="${item.name}">
                <i class="${item.icon} text-2xl text-gray-700 mb-1"></i>
                <span class="text-xs text-gray-600 text-center">${item.name}</span>
            </div>
        `).join('');
        
        // Re-attach click handlers
        grid.querySelectorAll('.icon-item').forEach(item => {
            item.addEventListener('click', function() {
                const iconClass = this.dataset.icon;
                insertIcon(iconClass);
            });
        });
    });

    // ✅ Insert Icon Token into Textarea
    function insertIcon(iconClass) {
        if (!currentIconTextarea) return;
        
        const token = `[[icon:${iconClass}]] `;
        insertAtCursor(currentIconTextarea, token);
        
        // Close modal
        document.getElementById('iconPickerModal').classList.add('hidden');
        currentIconTextarea = null;
    }

    // Close modal on outside click
    document.getElementById('iconPickerModal').addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.add('hidden');
            currentIconTextarea = null;
        }
    });

    // Tabs
    document.querySelectorAll('.tab-button').forEach(button => {
        button.addEventListener('click', function() {
            const targetTab = this.dataset.tab;
            document.querySelectorAll('.tab-button').forEach(btn => { btn.classList.remove('active','border-green-500','text-green-600'); btn.classList.add('border-transparent','text-gray-500'); });
            this.classList.add('active','border-green-500','text-green-600'); this.classList.remove('border-transparent','text-gray-500');
            document.querySelectorAll('.tab-content').forEach(content => content.classList.add('hidden'));
            const el = document.getElementById('tab-' + targetTab);
            if (el) el.classList.remove('hidden');
        });
    });

    // Slug auto-generate
    const nameInput = document.getElementById('name'), slugInput = document.getElementById('slug');
    if (nameInput && slugInput) {
        nameInput.addEventListener('input', function(){ if (!slugInput.dataset.manualEdit) slugInput.value = slugify(this.value); });
        slugInput.addEventListener('input', () => slugInput.dataset.manualEdit = 'true');
    }

    // Insert heading
    document.querySelectorAll('.insert-heading').forEach(btn => btn.addEventListener('click', function(){
        const section = this.dataset.section;
        const ta = document.querySelector(`#tab-${section} .section-textarea`);
        insertAtCursor(ta, `\n# Heading Text Here\n\n`);
    }));
    
    // Insert subheading
    document.querySelectorAll('.insert-subheading').forEach(btn => btn.addEventListener('click', function(){
        const section = this.dataset.section;
        const ta = document.querySelector(`#tab-${section} .section-textarea`);
        insertAtCursor(ta, `\n## Subheading Text Here\n\n`);
    }));

    // ✅ Insert Icon Button Handler
    document.querySelectorAll('.insert-icon').forEach(btn => btn.addEventListener('click', function(){
        const section = this.dataset.section;
        const ta = document.querySelector(`#tab-${section} .section-textarea`);
        openIconPicker(ta);
    }));

    // Add Image
    document.querySelectorAll('.insert-image').forEach(btn => btn.addEventListener('click', function(){
        const section = this.dataset.section;
        const ta = document.querySelector(`#tab-${section} .section-textarea`);
        const container = document.getElementById('section-uploads-' + section);
        
        const tmp = tempId();
        const token = `[[image:${tmp}|]]`;
        
        insertAtCursor(ta, `\n${token}\n\n`);
        
        const wrapper = document.createElement('div');
        wrapper.className = 'bg-blue-50 border-2 border-blue-300 rounded-lg p-4 new-upload';
        wrapper.dataset.tmpId = tmp;
        
        wrapper.innerHTML = `
            <div class="flex items-start gap-4">
                <div class="w-32 h-24 bg-gray-200 rounded-lg overflow-hidden flex-shrink-0">
                    <img id="upload-preview-${tmp}" src="" class="w-full h-full object-cover" style="display:none">
                    <div id="upload-placeholder-${tmp}" class="w-full h-full flex items-center justify-center text-gray-400">
                        <i class="fas fa-image text-3xl"></i>
                    </div>
                </div>
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Upload Image *</label>
                    <input type="file" accept="image/*" required name="sections[${section}][uploads][${tmp}]" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 upload-file-input mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Caption</label>
                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg caption-input" placeholder="Enter caption (optional)">
                </div>
                <button type="button" class="px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm remove-upload">
                    <i class="fas fa-trash mr-1"></i> Remove
                </button>
            </div>
        `;
        
        container.appendChild(wrapper);

        const fileInput = wrapper.querySelector('.upload-file-input');
        const previewImg = document.getElementById('upload-preview-' + tmp);
        const placeholder = document.getElementById('upload-placeholder-' + tmp);
        
        fileInput.addEventListener('change', function(){
            const f = this.files[0];
            if (!f) return;
            if (f.size > 2*1024*1024) { 
                alert('Image exceeds 2MB'); 
                this.value=''; 
                return; 
            }
            const reader = new FileReader();
            reader.onload = function(ev){ 
                previewImg.src = ev.target.result; 
                previewImg.style.display = 'block'; 
                if (placeholder) placeholder.style.display = 'none'; 
            };
            reader.readAsDataURL(f);
        });

        wrapper.querySelector('.remove-upload').addEventListener('click', function(){
            wrapper.remove();
            const re = new RegExp(`\\[\\[image:${tmp}(?:\\|[^\\]]*)?\\]\\]`, 'g');
            ta.value = ta.value.replace(re, '');
        });

        wrapper.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }));

    // Handle replacement of existing media
    document.querySelectorAll('.replace-upload-file').forEach(input => {
        input.addEventListener('change', function(){
            const wrapper = this.closest('.existing-upload');
            const reader = new FileReader();
            reader.onload = ev => {
                let img = wrapper.querySelector('img');
                if (img) img.src = ev.target.result;
            };
            if (this.files[0]) reader.readAsDataURL(this.files[0]);
        });
    });

    // Remove existing media
    document.querySelectorAll('.remove-existing-media').forEach(btn => {
        btn.addEventListener('click', function(){
            const mediaId = this.dataset.mediaId;
            const blockId = this.dataset.blockId;
            const wrapper = this.closest('.existing-upload');
            const sectionContainer = this.closest('.tab-content');
            const sectionKey = sectionContainer.dataset.section;
            const ta = sectionContainer.querySelector('.section-textarea');
            const tokenId = wrapper.dataset.tokenId;
            
            const tokenRe = new RegExp(`\\[\\[image:${tokenId}(?:\\|[^\\]]*)?\\]\\]`, 'g');
            ta.value = ta.value.replace(tokenRe, '');
            wrapper.remove();
            
            const delInput = document.createElement('input');
            delInput.type = 'hidden';
            delInput.name = `sections[${sectionKey}][delete_media][]`;
            delInput.value = mediaId;
            sectionContainer.appendChild(delInput);
        });
    });

    // Save Draft button
    const saveDraftBtn = document.getElementById('saveDraftBtn');
    saveDraftBtn && saveDraftBtn.addEventListener('click', async function(){
        buildAndAttachSectionContentBlocks();
        const formEl = document.getElementById('destinationEditForm');
        const fd = new FormData(formEl);
        fd.append('draft', '1');

        saveDraftBtn.disabled = true;
        const orig = saveDraftBtn.innerHTML;
        saveDraftBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';

        try {
            const resp = await fetch(formEl.action, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                body: fd
            });
            const json = await resp.json().catch(()=>null);
            if (resp.ok && json && json.success) {
                alert('Draft saved successfully.');
            } else {
                alert('Failed to save draft.');
            }
        } catch (err) {
            alert('Network error.');
        } finally {
            saveDraftBtn.disabled = false;
            saveDraftBtn.innerHTML = orig;
        }
    });

    // Build content_blocks JSON
    function buildAndAttachSectionContentBlocks() {
        document.querySelectorAll('.tab-content[data-section]').forEach(tab => {
            const sectionKey = tab.dataset.section;
            const ta = tab.querySelector('.section-textarea');
            if (!ta) return;
            
            const blocks = [];
            const text = ta.value || '';
            const lines = text.replace(/\r\n/g,'\n').split('\n');
            let paragraphBuffer = [];
            
            function flushParagraph() {
                const joined = paragraphBuffer.join('\n').trim();
                if (joined) blocks.push({ id: 'blk-' + uuid(), type: 'text', text: joined });
                paragraphBuffer = [];
            }
            
            const tokenRe = /\[\[image:(tmp-[a-z0-9]+|block-[a-z0-9\-]+|media-[0-9]+)(?:\|([^\]]*))?\]\]/ig;
            
            lines.forEach(line => {
                const l = line.trim();
                if (l === '') { flushParagraph(); return; }
                if (l.startsWith('# ')) { flushParagraph(); blocks.push({ id: 'blk-' + uuid(), type: 'heading', text: l.slice(2).trim() }); return; }
                if (l.startsWith('## ')) { flushParagraph(); blocks.push({ id: 'blk-' + uuid(), type: 'subheading', text: l.slice(3).trim() }); return; }
                
                const m = l.match(tokenRe);
                if (m) {
                    flushParagraph();
                    tokenRe.lastIndex = 0;
                    let mm;
                    while ((mm = tokenRe.exec(l)) !== null) {
                        const idToken = mm[1];
                        const caption = mm[2] || '';
                        
                        if (idToken.startsWith('tmp-')) {
                            blocks.push({ id: 'blk-' + uuid(), type: 'image', temp_media_id: idToken, caption: caption });
                        } else if (idToken.startsWith('block-')) {
                            const metadata = sectionBlockMetadata[sectionKey]?.[idToken];
                            if (metadata) {
                                blocks.push({ 
                                    id: metadata.block_id,
                                    type: 'image', 
                                    media_id: metadata.media_id,
                                    block_id: metadata.block_id,
                                    caption: caption 
                                });
                            } else {
                                const blockId = idToken.replace('block-', '');
                                blocks.push({ id: blockId, type: 'image', caption: caption });
                            }
                        } else if (idToken.startsWith('media-')) {
                            const mediaId = parseInt(idToken.replace('media-',''),10);
                            blocks.push({ id: 'blk-' + uuid(), type: 'image', media_id: mediaId, caption: caption });
                        }
                    }
                    return;
                }
                paragraphBuffer.push(line);
            });
            flushParagraph();
            
            const hidden = tab.querySelector('input[data-contentblock-input]');
            if (hidden) hidden.value = JSON.stringify(blocks);
        });
    }

    // Before submit
    const editForm = document.getElementById('destinationEditForm');
    editForm.addEventListener('submit', function(e){
        buildAndAttachSectionContentBlocks();
    });

    // Initialize first tab
    document.querySelector('.tab-button.active')?.click();

    // Auto-hide alerts
    setTimeout(() => {
        document.querySelectorAll('.bg-green-100, .bg-red-100').forEach(alert => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        });
    }, 5000);
});
</script>
@endpush

@push('styles')
<style>
.tab-button.active { border-color: #10b981; color: #10b981; }
.tab-button:not(.active) { border-color: transparent; color: #6b7280; }
.tab-button:not(.active):hover { color: #374151; border-color: #d1d5db; }
</style>
@endpush
@endsection