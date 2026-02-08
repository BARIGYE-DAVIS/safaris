@extends('layouts.admin')

@section('title', 'Edit Destination')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-green-50 py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">Edit Destination</h1>
                    <p class="text-gray-600">{{ $destination->name }}</p>
                </div>
                <div class="mt-4 md:mt-0">
                    <a href="{{ route('admin.destinations.index') }}" 
                       class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to List
                    </a>
                </div>
            </div>
        </div>

        <!-- Edit Form -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="p-8">
                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-red-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="flex-1">
                                <h3 class="text-red-800 font-semibold mb-2">There were some errors with your submission:</h3>
                                <ul class="list-disc list-inside text-red-700 space-y-1">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-green-800 font-medium">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                <form action="{{ route('admin.destinations.update', $destination) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Basic Information Section -->
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-gray-900 mb-6 pb-3 border-b-2 border-indigo-500 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Basic Information
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Country -->
                            <div>
                                <label for="country_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Country <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <select name="country_id" 
                                            id="country_id" 
                                            class="w-full px-4 py-3 border-2 @error('country_id') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" 
                                            required>
                                        <option value="">Select Country</option>
                                        @foreach($countries as $country)
                                            <option value="{{ $country->id }}" {{ old('country_id', $destination->country_id) == $country->id ? 'selected' : '' }}>
                                                {{ $country->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                                @error('country_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Destination Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="name" 
                                       id="name" 
                                       class="w-full px-4 py-3 border-2 @error('name') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" 
                                       value="{{ old('name', $destination->name) }}" 
                                       required
                                       placeholder="Enter destination name">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Slug -->
                            <div>
                                <label for="slug" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Slug
                                </label>
                                <input type="text" 
                                       name="slug" 
                                       id="slug" 
                                       class="w-full px-4 py-3 border-2 @error('slug') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" 
                                       value="{{ old('slug', $destination->slug) }}" 
                                       placeholder="auto-generated if empty">
                                @error('slug')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-gray-500">URL-friendly version (leave empty to auto-generate)</p>
                            </div>

                            <!-- Sort Order -->
                            <div>
                                <label for="sort_order" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Sort Order
                                </label>
                                <input type="number" 
                                       name="sort_order" 
                                       id="sort_order" 
                                       class="w-full px-4 py-3 border-2 @error('sort_order') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" 
                                       value="{{ old('sort_order', $destination->sort_order) }}" 
                                       min="0"
                                       placeholder="0">
                                @error('sort_order')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-gray-500">Lower numbers appear first</p>
                            </div>

                            <!-- Description -->
                            <div class="md:col-span-2">
                                <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Description
                                </label>
                                <textarea name="description" 
                                          id="description" 
                                          class="w-full px-4 py-3 border-2 @error('description') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" 
                                          rows="5"
                                          placeholder="Enter a brief description of the destination">{{ old('description', $destination->description) }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-gray-500">Brief description of the destination</p>
                            </div>
                        </div>
                    </div>

                    <!-- Image Section -->
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-gray-900 mb-6 pb-3 border-b-2 border-green-500 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Destination Image
                        </h2>

                        <!-- Current Image -->
                        @if($destination->image)
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">Current Image</label>
                            <div class="relative inline-block">
                                <img src="{{ asset('storage/' . $destination->image) }}" 
                                     alt="{{ $destination->name }}" 
                                     class="rounded-xl shadow-lg border-4 border-indigo-100"
                                     style="max-width: 400px; max-height: 300px; object-fit: cover;">
                                <div class="absolute top-2 right-2 bg-green-500 text-white px-3 py-1 rounded-full text-xs font-semibold shadow-lg">
                                    Current
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Upload New Image -->
                        <div>
                            <label for="image" class="block text-sm font-semibold text-gray-700 mb-2">
                                {{ $destination->image ? 'Upload New Image (Replace Current)' : 'Upload Image' }}
                            </label>
                            <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-indigo-500 transition-colors">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                            <span>Upload a file</span>
                                            <input id="image" name="image" type="file" class="sr-only" accept="image/*">
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                                    <p class="text-xs text-gray-500 font-semibold">Recommended: 1200x800px</p>
                                </div>
                            </div>
                            @error('image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            
                            <!-- Image Preview -->
                            <div id="image-preview" class="mt-4"></div>
                        </div>
                    </div>

                    <!-- Settings Section -->
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-gray-900 mb-6 pb-3 border-b-2 border-yellow-500 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Display Settings
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Popular -->
                            <div class="bg-yellow-50 border-2 border-yellow-200 rounded-xl p-6 hover:shadow-md transition-shadow">
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input type="checkbox" 
                                               id="is_popular" 
                                               name="is_popular" 
                                               class="w-5 h-5 text-yellow-600 border-gray-300 rounded focus:ring-yellow-500" 
                                               {{ old('is_popular', $destination->is_popular) ? 'checked' : '' }}>
                                    </div>
                                    <div class="ml-3">
                                        <label for="is_popular" class="font-semibold text-gray-900 cursor-pointer">
                                            Mark as Popular
                                        </label>
                                        <p class="text-sm text-gray-600 mt-1">Popular destinations are featured prominently on the website</p>
                                        <div class="mt-2 inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                            Featured Badge
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Active -->
                            <div class="bg-green-50 border-2 border-green-200 rounded-xl p-6 hover:shadow-md transition-shadow">
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input type="checkbox" 
                                               id="is_active" 
                                               name="is_active" 
                                               class="w-5 h-5 text-green-600 border-gray-300 rounded focus:ring-green-500" 
                                               {{ old('is_active', $destination->is_active) ? 'checked' : '' }}>
                                    </div>
                                    <div class="ml-3">
                                        <label for="is_active" class="font-semibold text-gray-900 cursor-pointer">
                                            Active Status
                                        </label>
                                        <p class="text-sm text-gray-600 mt-1">Inactive destinations won't be visible on the website</p>
                                        <div class="mt-2 inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                            Public Visibility
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-6 border-t-2 border-gray-200">
                        <div class="flex flex-wrap gap-3">
                            <button type="submit" 
                                    class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                                </svg>
                                Update Destination
                            </button>
                            <a href="{{ route('admin.destinations.index') }}" 
                               class="inline-flex items-center px-8 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-xl transition-colors duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Cancel
                            </a>
                        </div>
                        <button type="button" 
                                onclick="document.getElementById('deleteModal').classList.remove('hidden')"
                                class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Delete Destination
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-75 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">
    <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-4 rounded-t-2xl">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold text-white flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    Confirm Deletion
                </h3>
                <button type="button" 
                        onclick="document.getElementById('deleteModal').classList.add('hidden')"
                        class="text-white hover:text-gray-200 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        
        <!-- Modal Body -->
        <div class="p-6">
            <div class="text-center mb-4">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                    <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <p class="text-gray-700 text-lg mb-2">Are you sure you want to delete</p>
                <p class="text-xl font-bold text-gray-900 mb-3">{{ $destination->name }}</p>
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                    <p class="text-red-800 font-semibold flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        This action cannot be undone!
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Modal Footer -->
        <div class="bg-gray-50 px-6 py-4 rounded-b-2xl flex flex-col sm:flex-row gap-3 justify-end">
            <button type="button" 
                    onclick="document.getElementById('deleteModal').classList.add('hidden')"
                    class="px-6 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition-colors duration-200">
                Cancel
            </button>
            <form action="{{ route('admin.destinations.destroy', $destination) }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="px-6 py-2.5 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                    Delete Permanently
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Auto-generate slug from name (only if slug is empty)
    document.getElementById('name').addEventListener('input', function() {
        let slugField = document.getElementById('slug');
        if (slugField.value === '' || slugField.dataset.autoGenerated === 'true') {
            let slug = this.value.toLowerCase()
                .replace(/[^\w ]+/g, '')
                .replace(/ +/g, '-');
            slugField.value = slug;
            slugField.dataset.autoGenerated = 'true';
        }
    });

    // Image preview
    document.getElementById('image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('image-preview').innerHTML = 
                    '<div class="relative inline-block">' +
                    '<img src="' + e.target.result + '" class="rounded-xl shadow-lg border-4 border-green-100" style="max-width: 400px; max-height: 300px; object-fit: cover;">' +
                    '<div class="absolute top-2 right-2 bg-green-500 text-white px-3 py-1 rounded-full text-xs font-semibold shadow-lg">New Preview</div>' +
                    '</div>';
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
</script>
@endpush
@endsection