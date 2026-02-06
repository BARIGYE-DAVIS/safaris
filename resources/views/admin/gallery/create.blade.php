@extends('layouts.admin')

@section('title', 'Add New Gallery Image - Admin Dashboard')

@section('content')
<div class="container-fluid px-4 py-6">

<!-- Success Message -->
@if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-start animate-fade-in">
        <svg class="w-5 h-5 text-green-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <div class="flex-1">
            <p class="font-medium">Success!</p>
            <p class="text-sm">{{ session('success') }}</p>
        </div>
        <button onclick="this.parentElement.remove()" class="ml-4 text-green-600 hover:text-green-800">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
@endif

<!-- Error Messages -->
@if(session('error'))
    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-start animate-fade-in">
        <svg class="w-5 h-5 text-red-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <div class="flex-1">
            <p class="font-medium">Error!</p>
            <p class="text-sm">{{ session('error') }}</p>
        </div>
        <button onclick="this.parentElement.remove()" class="ml-4 text-red-600 hover:text-red-800">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
@endif
    <!-- Header Section -->
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <nav class="flex mb-3" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('admin.gallery.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Gallery Management
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Add New Image</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Add New Gallery Image</h1>
                <p class="text-gray-600">Upload and configure a new image for your gallery</p>
            </div>
            <div class="mt-4 sm:mt-0">
                <a href="{{ route('admin.gallery.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Gallery
                </a>
            </div>
        </div>
    </div>

    <!-- Error Messages -->
    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-start">
            <svg class="w-5 h-5 text-red-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.667-.833-2.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
            <div>
                <p class="font-medium">Error!</p>
                <p class="text-sm">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- Upload Form -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Image Upload & Details</h3>
            <p class="text-sm text-gray-500 mt-1">All fields marked with * are required</p>
        </div>

        <form action="{{ route('admin.gallery.store') }}" method="POST" enctype="multipart/form-data" class="px-6 py-6 space-y-6" id="galleryForm">
            @csrf

            <!-- Image Upload Section -->
            <div class="col-span-full">
                <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                    Upload Image <span class="text-red-500">*</span>
                </label>
                
                <!-- Drag & Drop Area -->
                <div class="relative">
                    <input type="file" 
                           id="image" 
                           name="image" 
                           accept="image/jpeg,image/jpg,image/png,image/webp"
                           class="hidden"
                           required>
                    
                    <div id="dropZone" 
                         class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-gray-400 transition-colors cursor-pointer @error('image') border-red-500 @enderror">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                        <div class="mt-4">
                            <p class="text-lg text-gray-600 font-medium">Drop your image here or click to browse</p>
                            <p class="text-sm text-gray-500 mt-2">
                                Supports: JPEG, PNG, WebP (max 10MB)
                            </p>
                        </div>
                        
                        <!-- Upload Button -->
                        <button type="button" 
                                onclick="document.getElementById('image').click()"
                                class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            Choose File
                        </button>
                    </div>
                    
                    <!-- Image Preview -->
                    <div id="imagePreview" class="hidden mt-4">
                        <div class="relative inline-block">
                            <img id="previewImg" class="w-48 h-48 object-cover rounded-lg shadow-md" src="" alt="Preview">
                            <button type="button" 
                                    id="removeImage"
                                    class="absolute -top-2 -right-2 w-6 h-6 bg-red-600 text-white rounded-full flex items-center justify-center text-xs hover:bg-red-700 focus:outline-none">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        <div id="imageInfo" class="mt-2 text-sm text-gray-600"></div>
                    </div>
                </div>
                
                @error('image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Basic Information -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                        Image Title <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="title" 
                           name="title" 
                           value="{{ old('title') }}" 
                           required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-150 @error('title') border-red-500 @enderror"
                           placeholder="Enter a descriptive title">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category -->
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                        Category <span class="text-red-500">*</span>
                    </label>
                    <select id="category" 
                            name="category" 
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-150 @error('category') border-red-500 @enderror">
                        <option value="">Select a category</option>
                        @foreach($categories as $key => $label)
                            <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('category')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- SEO Section -->
            <div class="border-t border-gray-200 pt-6">
                <h4 class="text-lg font-medium text-gray-900 mb-4">SEO Information</h4>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Slug -->
                    <div>
                        <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">
                            URL Slug <span class="text-gray-400">(Optional)</span>
                        </label>
                        <input type="text" 
                               id="slug" 
                               name="slug" 
                               value="{{ old('slug') }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-150 @error('slug') border-red-500 @enderror"
                               placeholder="auto-generated-from-title">
                        <p class="mt-1 text-xs text-gray-500">Leave empty to auto-generate from title</p>
                        @error('slug')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Alt Text -->
                    <div>
                        <label for="alt_text" class="block text-sm font-medium text-gray-700 mb-2">
                            Alt Text <span class="text-gray-400">(Optional)</span>
                        </label>
                        <input type="text" 
                               id="alt_text" 
                               name="alt_text" 
                               value="{{ old('alt_text') }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-150 @error('alt_text') border-red-500 @enderror"
                               placeholder="Describe what's in the image">
                        <p class="mt-1 text-xs text-gray-500">Used for accessibility and SEO (falls back to title)</p>
                        @error('alt_text')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Meta Keywords -->
                <div class="mt-6">
                    <label for="meta_keywords" class="block text-sm font-medium text-gray-700 mb-2">
                        Meta Keywords <span class="text-gray-400">(Optional)</span>
                    </label>
                    <input type="text" 
                           id="meta_keywords" 
                           name="meta_keywords" 
                           value="{{ old('meta_keywords') }}" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-150 @error('meta_keywords') border-red-500 @enderror"
                           placeholder="wildlife, safari, africa, lion, nature">
                    <p class="mt-1 text-xs text-gray-500">Comma-separated keywords for SEO</p>
                    @error('meta_keywords')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tags -->
                <div class="mt-6">
                    <label for="tags" class="block text-sm font-medium text-gray-700 mb-2">
                        Tags <span class="text-gray-400">(Optional)</span>
                    </label>
                    <input type="text" 
                           id="tags" 
                           name="tags" 
                           value="{{ old('tags') }}" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-150 @error('tags') border-red-500 @enderror"
                           placeholder="adventure, big five, sunset, landscape">
                    <p class="mt-1 text-xs text-gray-500">Comma-separated tags for categorization and search</p>
                    @error('tags')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Description Section -->
            <div class="border-t border-gray-200 pt-6">
                <h4 class="text-lg font-medium text-gray-900 mb-4">Content & Description</h4>
                
                <!-- Caption -->
                <div class="mb-6">
                    <label for="caption" class="block text-sm font-medium text-gray-700 mb-2">
                        Caption <span class="text-gray-400">(Optional)</span>
                    </label>
                    <textarea id="caption" 
                              name="caption" 
                              rows="3"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-150 resize-y @error('caption') border-red-500 @enderror"
                              placeholder="Write a brief description or caption for this image...">{{ old('caption') }}</textarea>
                    <div class="flex justify-between mt-1">
                        <p class="text-xs text-gray-500">Visible caption shown with the image</p>
                        <p class="text-xs text-gray-400" id="captionCount">0 / 1000</p>
                    </div>
                    @error('caption')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Meta Description -->
                <div>
                    <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-2">
                        Meta Description <span class="text-gray-400">(Optional)</span>
                    </label>
                    <textarea id="meta_description" 
                              name="meta_description" 
                              rows="3"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-150 resize-y @error('meta_description') border-red-500 @enderror"
                              placeholder="SEO description for search engines and social media sharing...">{{ old('meta_description') }}</textarea>
                    <div class="flex justify-between mt-1">
                        <p class="text-xs text-gray-500">Used for SEO and social sharing (falls back to caption)</p>
                        <p class="text-xs text-gray-400" id="metaCount">0 / 500</p>
                    </div>
                    @error('meta_description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Visibility Settings -->
            <div class="border-t border-gray-200 pt-6">
                <h4 class="text-lg font-medium text-gray-900 mb-4">Visibility Settings</h4>
                
                <div class="flex items-center">
                    <input id="is_visible" 
                           name="is_visible" 
                           type="checkbox" 
                           value="1"
                           {{ old('is_visible', true) ? 'checked' : '' }}
                           class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                    <label for="is_visible" class="ml-3 block text-sm font-medium text-gray-700">
                        Make this image visible to website visitors
                    </label>
                </div>
                <p class="mt-2 text-sm text-gray-500">Uncheck to hide this image from public view (you can change this later)</p>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                <a href="{{ route('admin.gallery.index') }}" 
                   class="inline-flex items-center px-6 py-3 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    Cancel
                </a>
                
                <button type="submit" 
                        id="submitBtn"
                        class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50 disabled:cursor-not-allowed">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span id="submitText">Add to Gallery</span>
                </button>
            </div>
        </form>
    </div>
</div>

@push('styles')
<style>
    .drop-zone-dragover {
        border-color: #10B981 !important;
        background-color: #F0FDF4 !important;
    }

      @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-fade-in {
        animation: fadeIn 0.3s ease-out;
    }
</style>
@endpush


<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get all DOM elements
    const imageInput = document.getElementById('image');
    const dropZone = document.getElementById('dropZone');
    const imagePreview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    const imageInfo = document.getElementById('imageInfo');
    const removeImageBtn = document.getElementById('removeImage');
    const titleInput = document.getElementById('title');
    const slugInput = document.getElementById('slug');
    const captionTextarea = document.getElementById('caption');
    const metaTextarea = document.getElementById('meta_description');
    const captionCount = document.getElementById('captionCount');
    const metaCount = document.getElementById('metaCount');
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    const galleryForm = document.getElementById('galleryForm');

    // File input change event
    imageInput.addEventListener('change', function(e) {
        console.log('File input change event triggered');
        console.log('Files:', e.target.files);
        handleFileSelect(e);
    });

    // Drag and drop functionality
    dropZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        e.stopPropagation();
        dropZone.classList.add('drop-zone-dragover');
    });

    dropZone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        e.stopPropagation();
        dropZone.classList.remove('drop-zone-dragover');
    });

    dropZone.addEventListener('drop', function(e) {
        e.preventDefault();
        e.stopPropagation();
        dropZone.classList.remove('drop-zone-dragover');
        
        const files = e.dataTransfer.files;
        console.log('Files dropped:', files);
        
        if (files.length > 0) {
            const file = files[0];
            if (file.type.startsWith('image/')) {
                // Create a new FileList-like object
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                imageInput.files = dataTransfer.files;
                processFile(file);
            } else {
                alert('Please select a valid image file.');
            }
        }
    });

    // Remove image button
    removeImageBtn.addEventListener('click', function(e) {
        e.preventDefault();
        clearImageSelection();
    });

    // Auto-generate slug from title
    titleInput.addEventListener('input', function() {
        if (!slugInput.value || slugInput.dataset.autoGenerated === 'true') {
            const slug = generateSlug(this.value);
            slugInput.value = slug;
            slugInput.dataset.autoGenerated = 'true';
        }
    });

    // Manual slug editing
    slugInput.addEventListener('input', function() {
        if (this.value) {
            this.dataset.autoGenerated = 'false';
        } else {
            this.dataset.autoGenerated = 'true';
        }
    });

    // Character counters
    if (captionTextarea) {
        captionTextarea.addEventListener('input', function() {
            updateCharCount(this, captionCount, 1000);
        });
    }

    if (metaTextarea) {
        metaTextarea.addEventListener('input', function() {
            updateCharCount(this, metaCount, 500);
        });
    }

    // Form submission
    galleryForm.addEventListener('submit', function(e) {
        // Check if image is selected
        if (!imageInput.files || imageInput.files.length === 0) {
            e.preventDefault();
            alert('Please select an image to upload.');
            return false;
        }

        // Disable submit button
        submitBtn.disabled = true;
        submitText.textContent = 'Uploading...';
        
        // Add loading spinner
        const spinner = document.createElement('svg');
        spinner.className = 'w-4 h-4 mr-2 animate-spin';
        spinner.setAttribute('viewBox', '0 0 24 24');
        spinner.innerHTML = '<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>';
        
        // Remove any existing spinner
        const existingSpinner = submitBtn.querySelector('.animate-spin');
        if (existingSpinner) {
            existingSpinner.remove();
        }
        
        submitBtn.insertBefore(spinner, submitText);
    });

    // Initialize character counts
    if (captionTextarea && captionCount) {
        updateCharCount(captionTextarea, captionCount, 1000);
    }
    if (metaTextarea && metaCount) {
        updateCharCount(metaTextarea, metaCount, 500);
    }

    /**
     * Handle file selection from input
     */
    function handleFileSelect(e) {
        const file = e.target.files[0];
        console.log('Processing file:', file);
        
        if (file) {
            processFile(file);
        } else {
            console.log('No file selected');
        }
    }

    /**
     * Process and validate the selected file
     */
    function processFile(file) {
        console.log('Processing file:', file.name, file.type, file.size);

        // Validate file type
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
        if (!allowedTypes.includes(file.type)) {
            alert('Please select a JPEG, PNG, or WebP image.');
            clearImageSelection();
            return;
        }

        // Validate file size (10MB max)
        const maxSize = 10 * 1024 * 1024; // 10MB
        if (file.size > maxSize) {
            alert('Image size must not exceed 10MB.');
            clearImageSelection();
            return;
        }

        // Show preview
        const reader = new FileReader();
        
        reader.onload = function(e) {
            console.log('FileReader loaded successfully');
            previewImg.src = e.target.result;
            imagePreview.classList.remove('hidden');
            dropZone.style.display = 'none';
            
            // Show file info
            const fileSizeMB = (file.size / 1024 / 1024).toFixed(2);
            const fileType = file.type.split('/')[1].toUpperCase();
            
            imageInfo.innerHTML = `
                <div class="space-y-1">
                    <p><strong>File:</strong> ${file.name}</p>
                    <p><strong>Size:</strong> ${fileSizeMB} MB</p>
                    <p><strong>Type:</strong> ${fileType}</p>
                </div>
            `;
        };

        reader.onerror = function(e) {
            console.error('FileReader error:', e);
            alert('Error reading file. Please try again.');
            clearImageSelection();
        };
        
        reader.readAsDataURL(file);
    }

    /**
     * Clear image selection and reset preview
     */
    function clearImageSelection() {
        console.log('Clearing image selection');
        imageInput.value = '';
        imagePreview.classList.add('hidden');
        dropZone.style.display = 'block';
        previewImg.src = '';
        imageInfo.innerHTML = '';
    }

    /**
     * Generate URL-friendly slug from text
     */
    function generateSlug(text) {
        return text
            .toLowerCase()
            .trim()
            .replace(/[^\w\s-]/g, '') // Remove special characters
            .replace(/[\s_-]+/g, '-') // Replace spaces and underscores with hyphens
            .replace(/^-+|-+$/g, ''); // Remove leading/trailing hyphens
    }

    /**
     * Update character count display
     */
    function updateCharCount(textarea, countElement, maxLength) {
        const currentLength = textarea.value.length;
        countElement.textContent = `${currentLength} / ${maxLength}`;
        
        // Reset classes
        countElement.classList.remove('text-yellow-600', 'text-red-600', 'text-gray-400');
        
        if (currentLength > maxLength * 0.95) {
            countElement.classList.add('text-red-600');
        } else if (currentLength > maxLength * 0.9) {
            countElement.classList.add('text-yellow-600');
        } else {
            countElement.classList.add('text-gray-400');
        }
    }

    // Make browse button work without drop zone interference
    const browseButton = dropZone.querySelector('button[onclick]');
    if (browseButton) {
        browseButton.addEventListener('click', function(e) {
            e.stopPropagation(); // Prevent event bubbling
        });
    }

    console.log('Gallery upload script initialized');
});
</script>

@endsection