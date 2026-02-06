@extends('layouts.admin')

@section('title', 'Edit Gallery Image - Admin Dashboard')

@section('content')
<div class="container-fluid px-4 py-6">
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
                                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Edit: {{ Str::limit($gallery->title, 30) }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Edit Gallery Image</h1>
                <p class="text-gray-600">Update image details and SEO information</p>
            </div>
            <div class="mt-4 sm:mt-0 flex space-x-3">
                <a href="{{ route('gallery.show', $gallery->slug) }}" 
                   target="_blank"
                   class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    View Image
                </a>
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

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-start">
            <svg class="w-5 h-5 text-green-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>
                <p class="font-medium">Success!</p>
                <p class="text-sm">{{ session('success') }}</p>
            </div>
        </div>
    @endif

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

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Current Image Preview -->
        <div class="lg:col-span-1">
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Current Image</h3>
                </div>
                <div class="p-6">
                    <div class="aspect-w-1 aspect-h-1 mb-4">
                        <img src="{{ $gallery->image_url }}" 
                             alt="{{ $gallery->alt_text }}" 
                             class="w-full h-64 object-cover rounded-lg">
                    </div>
                    
                    <!-- Image Info -->
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Status:</span>
                            @if($gallery->is_visible)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    Visible
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029"></path>
                                    </svg>
                                    Hidden
                                </span>
                            @endif
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">Category:</span>
                            <span class="font-medium">{{ $gallery->category_label }}</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">Created:</span>
                            <span>{{ $gallery->created_at->format('M d, Y') }}</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">Updated:</span>
                            <span>{{ $gallery->updated_at->format('M d, Y') }}</span>
                        </div>
                    </div>

                    <!-- Current URL -->
                    <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                        <p class="text-xs text-gray-500 mb-1">Public URL:</p>
                        <a href="{{ route('gallery.show', $gallery->slug) }}" 
                           target="_blank"
                           class="text-sm text-blue-600 hover:text-blue-800 break-all">
                            {{ route('gallery.show', $gallery->slug) }}
                        </a>
                    </div>

                    <!-- Quick Actions -->
                    <div class="mt-4 flex space-x-2">
                         <form action="{{ route('admin.gallery.toggle-visibility', $gallery) }}" 
                    method="POST" class="inline-block">
                  @csrf
            <button type="submit" 
            class="w-10 h-10 rounded-full flex items-center justify-center text-white transition-colors {{ $gallery->is_visible ? 'bg-green-600 hover:bg-green-700' : 'bg-gray-600 hover:bg-gray-700' }}"
            title="{{ $gallery->is_visible ? 'Hide' : 'Show' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            @if($gallery->is_visible)
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
            @else
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
            @endif
        </svg>
     </button>

        </form>
         </div>
        </div>
        </div>
        </div>

        <!-- Edit Form -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Edit Image Details</h3>
                    <p class="text-sm text-gray-500 mt-1">Update image information and SEO settings</p>
                </div>

                <form action="{{ route('admin.gallery.update', $gallery) }}" method="POST" enctype="multipart/form-data" class="px-6 py-6 space-y-6" id="galleryForm">
                    @csrf
                    @method('PUT')

                    <!-- Replace Image (Optional) -->
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6">
                        <div class="text-center">
                            <svg class="mx-auto h-8 w-8 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                            <h4 class="mt-2 text-sm font-medium text-gray-900">Replace Image (Optional)</h4>
                            <p class="mt-1 text-xs text-gray-500">Upload a new image to replace the current one</p>
                            
                            <input type="file" 
                                   id="image" 
                                   name="image" 
                                   accept="image/jpeg,image/jpg,image/png,image/webp"
                                   class="hidden">
                            
                            <button type="button" 
                                    onclick="document.getElementById('image').click()"
                                    class="mt-3 inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                Choose New Image
                            </button>
                        </div>
                        
                        <!-- New Image Preview -->
                        <div id="newImagePreview" class="hidden mt-4 text-center">
                            <img id="newPreviewImg" class="w-32 h-32 object-cover rounded-lg mx-auto shadow-md" src="" alt="New Preview">
                            <p id="newImageInfo" class="mt-2 text-sm text-gray-600"></p>
                            <button type="button" 
                                    id="removeNewImage"
                                    class="mt-2 text-sm text-red-600 hover:text-red-800">
                                Remove new image
                            </button>
                        </div>
                        
                        @error('image')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Basic Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                Image Title <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title', $gallery->title) }}" 
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
                                    <option value="{{ $key }}" {{ old('category', $gallery->category) == $key ? 'selected' : '' }}>
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
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Slug -->
                            <div>
                                <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">
                                    URL Slug <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="slug" 
                                       name="slug" 
                                       value="{{ old('slug', $gallery->slug) }}" 
                                       required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-150 @error('slug') border-red-500 @enderror"
                                       placeholder="url-friendly-slug">
                                <p class="mt-1 text-xs text-gray-500">This will be part of the image URL</p>
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
                                       value="{{ old('alt_text', $gallery->alt_text) }}" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-150 @error('alt_text') border-red-500 @enderror"
                                       placeholder="Describe what's in the image">
                                <p class="mt-1 text-xs text-gray-500">Used for accessibility and SEO</p>
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
                                   value="{{ old('meta_keywords', $gallery->meta_keywords) }}" 
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
                                   value="{{ old('tags', implode(', ', $gallery->formatted_tags)) }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-150 @error('tags') border-red-500 @enderror"
                                   placeholder="adventure, big five, sunset, landscape">
                            <p class="mt-1 text-xs text-gray-500">Comma-separated tags for categorization</p>
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
                                      placeholder="Write a brief description or caption for this image...">{{ old('caption', $gallery->caption) }}</textarea>
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
                                      placeholder="SEO description for search engines and social media sharing...">{{ old('meta_description', $gallery->meta_description) }}</textarea>
                            <div class="flex justify-between mt-1">
                                <p class="text-xs text-gray-500">Used for SEO and social sharing</p>
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
                                   {{ old('is_visible', $gallery->is_visible) ? 'checked' : '' }}
                                   class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                            <label for="is_visible" class="ml-3 block text-sm font-medium text-gray-700">
                                Make this image visible to website visitors
                            </label>
                        </div>
                        <p class="mt-2 text-sm text-gray-500">Uncheck to hide this image from public view</p>
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span id="submitText">Update Image</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .aspect-w-1 {
        position: relative;
        padding-bottom: 100%;
    }
    .aspect-w-1 > * {
        position: absolute;
        height: 100%;
        width: 100%;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.getElementById('image');
    const newImagePreview = document.getElementById('newImagePreview');
    const newPreviewImg = document.getElementById('newPreviewImg');
    const newImageInfo = document.getElementById('newImageInfo');
    const removeNewImageBtn = document.getElementById('removeNewImage');
    const titleInput = document.getElementById('title');
    const slugInput = document.getElementById('slug');
    const captionTextarea = document.getElementById('caption');
    const metaTextarea = document.getElementById('meta_description');
    const captionCount = document.getElementById('captionCount');
    const metaCount = document.getElementById('metaCount');
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    const galleryForm = document.getElementById('galleryForm');

    let originalSlug = '{{ $gallery->slug }}';

    // File input change
    imageInput.addEventListener('change', handleFileSelect);

    // Remove new image
    removeNewImageBtn.addEventListener('click', clearNewImageSelection);

    // Auto-generate slug from title (only if slug hasn't been manually changed)
    titleInput.addEventListener('input', function() {
        const newSlug = generateSlug(this.value);
        if (slugInput.value === originalSlug || slugInput.dataset.autoGenerated === 'true') {
            slugInput.value = newSlug;
            slugInput.dataset.autoGenerated = 'true';
        }
    });

    // Manual slug editing
    slugInput.addEventListener('input', function() {
        if (this.value !== originalSlug && this.value !== generateSlug(titleInput.value)) {
            this.dataset.autoGenerated = 'false';
        }
    });

    // Character counters
    captionTextarea.addEventListener('input', function() {
        updateCharCount(this, captionCount, 1000);
    });

    metaTextarea.addEventListener('input', function() {
        updateCharCount(this, metaCount, 500);
    });

    // Form submission
    galleryForm.addEventListener('submit', function(e) {
        submitBtn.disabled = true;
        submitText.textContent = 'Updating...';
        
        // Add loading spinner
        const spinner = document.createElement('svg');
        spinner.className = 'w-4 h-4 mr-2 animate-spin';
        spinner.innerHTML = '<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>';
        
        submitBtn.insertBefore(spinner, submitText);
    });

    // Initialize character counts
    updateCharCount(captionTextarea, captionCount, 1000);
    updateCharCount(metaTextarea, metaCount, 500);

    function handleFileSelect(e) {
        const file = e.target.files[0];
        if (file) {
            processNewFile(file);
        }
    }

    function processNewFile(file) {
        // Validate file type
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
        if (!allowedTypes.includes(file.type)) {
            alert('Please select a JPEG, PNG, or WebP image.');
            return;
        }

        // Validate file size (10MB max)
        const maxSize = 10 * 1024 * 1024; // 10MB
        if (file.size > maxSize) {
            alert('Image size must not exceed 10MB.');
            return;
        }

        // Show preview
        const reader = new FileReader();
        reader.onload = function(e) {
            newPreviewImg.src = e.target.result;
            newImagePreview.classList.remove('hidden');
            
            // Show file info
            const fileSizeMB = (file.size / 1024 / 1024).toFixed(2);
            newImageInfo.innerHTML = `
                <strong>New image:</strong> ${file.name} (${fileSizeMB} MB)
            `;
        };
        reader.readAsDataURL(file);
    }

    function clearNewImageSelection() {
        imageInput.value = '';
        newImagePreview.classList.add('hidden');
        newPreviewImg.src = '';
        newImageInfo.innerHTML = '';
    }

    function generateSlug(text) {
        return text
            .toLowerCase()
            .trim()
            .replace(/[^\w\s-]/g, '') // Remove special characters
            .replace(/[\s_-]+/g, '-') // Replace spaces and underscores with hyphens
            .replace(/^-+|-+$/g, ''); // Remove leading/trailing hyphens
    }

    function updateCharCount(textarea, countElement, maxLength) {
        const currentLength = textarea.value.length;
        countElement.textContent = `${currentLength} / ${maxLength}`;
        
        if (currentLength > maxLength * 0.9) {
            countElement.classList.add('text-yellow-600');
        }
        if (currentLength > maxLength * 0.95) {
            countElement.classList.remove('text-yellow-600');
            countElement.classList.add('text-red-600');
        }
        if (currentLength <= maxLength * 0.9) {
            countElement.classList.remove('text-yellow-600', 'text-red-600');
            countElement.classList.add('text-gray-400');
        }
    }
});
</script>
@endpush
@endsection