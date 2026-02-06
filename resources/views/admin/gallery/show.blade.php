@extends('layouts.admin')

@section('title', $gallery->title . ' - Gallery Details')

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
                                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Image Details</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $gallery->title }}</h1>
                <p class="text-gray-600">View and manage image details</p>
            </div>
            <div class="mt-4 sm:mt-0 flex space-x-2">
                <a href="{{ route('admin.gallery.edit', $gallery) }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit
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
            <svg class="w-5 h-5 text-green-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div class="flex-1">
                <p class="font-medium">Success!</p>
                <p class="text-sm">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Image Display -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="p-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Image Preview</h3>
                </div>
                <div class="p-6">
                    <div class="relative">
                        <img src="{{ $gallery->image_url }}" 
                             alt="{{ $gallery->alt_text }}"
                             class="w-full h-auto rounded-lg shadow-lg">
                        
                        <!-- Visibility Badge -->
                        <div class="absolute top-4 right-4">
                            @if($gallery->is_visible)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    Visible
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                    </svg>
                                    Hidden
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Caption -->
                    @if($gallery->caption)
                        <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                            <p class="text-gray-700">{{ $gallery->caption }}</p>
                        </div>
                    @endif

                    <!-- Navigation -->
                    <div class="mt-6 flex justify-between items-center">
                        @if($previousImage)
                            <a href="{{ route('admin.gallery.show', $previousImage) }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                                Previous
                            </a>
                        @else
                            <span class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-400 rounded-md cursor-not-allowed">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                                Previous
                            </span>
                        @endif

                        @if($nextImage)
                            <a href="{{ route('admin.gallery.show', $nextImage) }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition">
                                Next
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        @else
                            <span class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-400 rounded-md cursor-not-allowed">
                                Next
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Details Sidebar -->
        <div class="lg:col-span-1">
            <!-- Basic Information -->
            <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
                <div class="p-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Details</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Category</label>
                        <p class="mt-1 text-gray-900">{{ $gallery->category_label }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500">Slug</label>
                        <p class="mt-1 text-gray-900 text-sm break-all">{{ $gallery->slug }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500">Alt Text</label>
                        <p class="mt-1 text-gray-900">{{ $gallery->alt_text }}</p>
                    </div>

                    @if($gallery->formatted_tags && count($gallery->formatted_tags) > 0)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-2">Tags</label>
                            <div class="flex flex-wrap gap-2">
                                @foreach($gallery->formatted_tags as $tag)
                                    <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">
                                        {{ $tag }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-500">Uploaded</label>
                        <p class="mt-1 text-gray-900">{{ $gallery->created_at->format('M d, Y') }}</p>
                        <p class="text-xs text-gray-500">{{ $gallery->created_at->diffForHumans() }}</p>
                    </div>

                    @if($gallery->updated_at != $gallery->created_at)
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Last Updated</label>
                            <p class="mt-1 text-gray-900">{{ $gallery->updated_at->format('M d, Y') }}</p>
                            <p class="text-xs text-gray-500">{{ $gallery->updated_at->diffForHumans() }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- File Information -->
            <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
                <div class="p-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">File Info</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">File Path</label>
                        <p class="mt-1 text-gray-900 text-xs break-all">{{ $gallery->image_path }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500">File Size</label>
                        <p class="mt-1 text-gray-900">
                            @if($imageInfo['exists'])
                                {{ number_format($imageInfo['size'] / 1024 / 1024, 2) }} MB
                            @else
                                <span class="text-red-600">File not found</span>
                            @endif
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500">MIME Type</label>
                        <p class="mt-1 text-gray-900">{{ $imageInfo['mime_type'] }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500">Public URL</label>
                        <div class="mt-1 flex items-center">
                            <input type="text" 
                                   id="imageUrl" 
                                   value="{{ $gallery->image_url }}" 
                                   readonly
                                   class="flex-1 text-xs px-2 py-1 border border-gray-300 rounded-l-md bg-gray-50">
                            <button onclick="copyToClipboard()" 
                                    class="px-3 py-1 bg-blue-600 text-white rounded-r-md hover:bg-blue-700 text-xs">
                                Copy
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SEO Information -->
            @if($gallery->meta_description || $gallery->meta_keywords)
                <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
                    <div class="p-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">SEO</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        @if($gallery->meta_description)
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Meta Description</label>
                                <p class="mt-1 text-gray-900 text-sm">{{ $gallery->meta_description }}</p>
                            </div>
                        @endif

                        @if($gallery->meta_keywords)
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Meta Keywords</label>
                                <p class="mt-1 text-gray-900 text-sm">{{ $gallery->meta_keywords }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Actions -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="p-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Actions</h3>
                </div>
                <div class="p-6 space-y-3">
                    <a href="{{ route('gallery.show', $gallery->slug) }}" 
                       target="_blank"
                       class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                        View on Website
                    </a>

                    <button onclick="toggleVisibility()" 
                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Toggle Visibility
                    </button>

                    <form action="{{ route('admin.gallery.destroy', $gallery) }}" 
                          method="POST" 
                          onsubmit="return confirm('Are you sure you want to delete this image? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Delete Image
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Images -->
    @if($relatedImages->count() > 0)
        <div class="mt-8">
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="p-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Related Images ({{ $gallery->category_label }})</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
                        @foreach($relatedImages as $related)
                            <a href="{{ route('admin.gallery.show', $related) }}" class="group">
                                <div class="aspect-w-1 aspect-h-1 relative overflow-hidden rounded-lg">
                                    <img src="{{ $related->image_url }}" 
                                         alt="{{ $related->alt_text }}"
                                         class="w-full h-32 object-cover group-hover:scale-110 transition-transform duration-300">
                                </div>
                                <p class="mt-2 text-xs text-gray-600 group-hover:text-gray-900 line-clamp-2">{{ $related->title }}</p>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
function copyToClipboard() {
    const urlInput = document.getElementById('imageUrl');
    urlInput.select();
    document.execCommand('copy');
    
    // Show feedback
    const button = event.target;
    const originalText = button.textContent;
    button.textContent = 'Copied!';
    button.classList.add('bg-green-600');
    button.classList.remove('bg-blue-600');
    
    setTimeout(() => {
        button.textContent = originalText;
        button.classList.remove('bg-green-600');
        button.classList.add('bg-blue-600');
    }, 2000);
}

function toggleVisibility() {
    if (confirm('Are you sure you want to toggle the visibility of this image?')) {
        fetch('{{ route("admin.gallery.toggle-visibility", $gallery) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Failed to toggle visibility');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred');
        });
    }
}
</script>
@endpush
@endsection