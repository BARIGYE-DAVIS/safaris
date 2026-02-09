@extends('layouts.admin')

@section('title', 'Create Activity Category')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Create Activity Category</h1>
            <p class="text-gray-600 mt-1">Add a new category for organizing activities</p>
        </div>
        <a href="{{ route('admin.activity-categories.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center transition">
            <i class="fas fa-arrow-left mr-2"></i> Back to Categories
        </a>
    </div>

    <!-- Error Messages -->
    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <p class="font-bold mb-2">Please fix the following errors:</p>
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Create Form -->
    <form action="{{ route('admin.activity-categories.store') }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-lg rounded-lg">
        @csrf

        <div class="p-6 space-y-6">
            
            <!-- Basic Information Section -->
            <div>
                <h2 class="text-xl font-semibold text-gray-800 mb-4 pb-2 border-b">Basic Information</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Category Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Category Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('name') border-red-500 @enderror"
                               value="{{ old('name') }}" 
                               placeholder="e.g., Wildlife & Nature">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-gray-500 text-xs mt-1">The display name of the category</p>
                    </div>

                    <!-- Slug -->
                    <div>
                        <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">
                            URL Slug
                        </label>
                        <input type="text" name="slug" id="slug"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('slug') border-red-500 @enderror"
                               value="{{ old('slug') }}" 
                               placeholder="wildlife-nature">
                        @error('slug')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-gray-500 text-xs mt-1">Leave empty to auto-generate from name</p>
                    </div>

                    <!-- Sort Order -->
                    <div>
                        <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">
                            Sort Order
                        </label>
                        <input type="number" name="sort_order" id="sort_order" min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                               value="{{ old('sort_order', 0) }}">
                        <p class="text-gray-500 text-xs mt-1">Lower numbers appear first (0 = highest priority)</p>
                    </div>
                </div>
            </div>

            <!-- Description Section -->
            <div>
                <h2 class="text-xl font-semibold text-gray-800 mb-4 pb-2 border-b">Description</h2>
                
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Category Description
                    </label>
                    <textarea name="description" id="description" rows="5"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('description') border-red-500 @enderror"
                              placeholder="Brief description of this activity category...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-gray-500 text-xs mt-1">Explain what types of activities belong to this category</p>
                </div>
            </div>

            <!-- Icon & Image Section -->
            <div>
                <h2 class="text-xl font-semibold text-gray-800 mb-4 pb-2 border-b">Visual Elements</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Icon Class -->
                    <div>
                        <label for="icon" class="block text-sm font-medium text-gray-700 mb-2">
                            Icon Class (Font Awesome)
                        </label>
                        <div class="flex gap-2">
                            <input type="text" name="icon" id="icon"
                                   class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('icon') border-red-500 @enderror"
                                   value="{{ old('icon') }}" 
                                   placeholder="fas fa-hiking">
                            <div id="icon-preview" class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center text-green-600 text-2xl">
                                <i class="fas fa-question"></i>
                            </div>
                        </div>
                        @error('icon')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-gray-500 text-xs mt-1">
                            Font Awesome icon class 
                            <a href="https://fontawesome.com/icons" target="_blank" class="text-blue-600 hover:underline">
                                (Browse icons)
                            </a>
                        </p>
                        
                        <!-- Common Icons Quick Select -->
                        <div class="mt-3">
                            <p class="text-xs text-gray-600 mb-2">Quick select:</p>
                            <div class="flex flex-wrap gap-2">
                                <button type="button" class="icon-btn px-3 py-2 bg-gray-100 hover:bg-green-100 rounded border text-sm" data-icon="fas fa-hiking">
                                    <i class="fas fa-hiking mr-1"></i> Hiking
                                </button>
                                <button type="button" class="icon-btn px-3 py-2 bg-gray-100 hover:bg-green-100 rounded border text-sm" data-icon="fas fa-binoculars">
                                    <i class="fas fa-binoculars mr-1"></i> Wildlife
                                </button>
                                <button type="button" class="icon-btn px-3 py-2 bg-gray-100 hover:bg-green-100 rounded border text-sm" data-icon="fas fa-water">
                                    <i class="fas fa-water mr-1"></i> Water
                                </button>
                                <button type="button" class="icon-btn px-3 py-2 bg-gray-100 hover:bg-green-100 rounded border text-sm" data-icon="fas fa-mountain">
                                    <i class="fas fa-mountain mr-1"></i> Mountain
                                </button>
                                <button type="button" class="icon-btn px-3 py-2 bg-gray-100 hover:bg-green-100 rounded border text-sm" data-icon="fas fa-camera">
                                    <i class="fas fa-camera mr-1"></i> Photo
                                </button>
                                <button type="button" class="icon-btn px-3 py-2 bg-gray-100 hover:bg-green-100 rounded border text-sm" data-icon="fas fa-users">
                                    <i class="fas fa-users mr-1"></i> Cultural
                                </button>
                                <button type="button" class="icon-btn px-3 py-2 bg-gray-100 hover:bg-green-100 rounded border text-sm" data-icon="fas fa-campground">
                                    <i class="fas fa-campground mr-1"></i> Camping
                                </button>
                                <button type="button" class="icon-btn px-3 py-2 bg-gray-100 hover:bg-green-100 rounded border text-sm" data-icon="fas fa-horse">
                                    <i class="fas fa-horse mr-1"></i> Safari
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Category Image -->
                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                            Category Image
                        </label>
                        <input type="file" name="image" id="image" accept="image/*"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('image') border-red-500 @enderror">
                        @error('image')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-gray-500 text-xs mt-1">Recommended: 800x600px, Max: 2MB (JPG, PNG, WebP)</p>
                        
                        <!-- Image Preview -->
                        <div id="image-preview" class="mt-3"></div>
                    </div>
                </div>
            </div>

            <!-- Status Section -->
            <div>
                <h2 class="text-xl font-semibold text-gray-800 mb-4 pb-2 border-b">Status Settings</h2>
                
                <div>
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1"
                               class="w-5 h-5 rounded border-gray-300 text-green-600 focus:ring-green-500"
                               {{ old('is_active', true) ? 'checked' : '' }}>
                        <div>
                            <span class="text-sm font-medium text-gray-700">Active Category</span>
                            <p class="text-xs text-gray-500">When active, this category will be visible on the website</p>
                        </div>
                    </label>
                </div>
            </div>

        </div>

        <!-- Form Actions -->
        <div class="border-t bg-gray-50 px-6 py-4 flex justify-between items-center rounded-b-lg">
            <a href="{{ route('admin.activity-categories.index') }}" class="text-gray-600 hover:text-gray-800 font-medium">
                <i class="fas fa-times mr-1"></i> Cancel
            </a>
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium transition flex items-center shadow-md">
                <i class="fas fa-save mr-2"></i> Create Category
            </button>
        </div>
    </form>

    <!-- Help Section -->
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex items-start">
            <i class="fas fa-info-circle text-blue-600 text-xl mr-3 mt-1"></i>
            <div>
                <h3 class="font-semibold text-blue-900 mb-1">Category Guidelines</h3>
                <ul class="text-sm text-blue-800 space-y-1">
                    <li>• Choose clear, descriptive names (e.g., "Wildlife & Nature", "Adventure Sports")</li>
                    <li>• Icons help users quickly identify category types</li>
                    <li>• Sort order determines display position (lower = higher priority)</li>
                    <li>• Categories group related activities for better organization</li>
                    <li>• Keep category names concise (2-4 words ideal)</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Auto-generate slug from name
document.getElementById('name').addEventListener('input', function() {
    const slugField = document.getElementById('slug');
    if (!slugField.dataset.manualEdit) {
        let slug = this.value.toLowerCase()
            .replace(/[^\w\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim();
        slugField.value = slug;
    }
});

// Mark slug as manually edited if user types in it
document.getElementById('slug').addEventListener('input', function() {
    this.dataset.manualEdit = 'true';
});

// Icon preview
document.getElementById('icon').addEventListener('input', function() {
    const iconClass = this.value.trim();
    const preview = document.getElementById('icon-preview');
    
    if (iconClass) {
        preview.innerHTML = `<i class="${iconClass}"></i>`;
    } else {
        preview.innerHTML = '<i class="fas fa-question"></i>';
    }
});

// Quick icon select buttons
document.querySelectorAll('.icon-btn').forEach(button => {
    button.addEventListener('click', function() {
        const iconClass = this.dataset.icon;
        const iconInput = document.getElementById('icon');
        const preview = document.getElementById('icon-preview');
        
        iconInput.value = iconClass;
        preview.innerHTML = `<i class="${iconClass}"></i>`;
        
        // Highlight selected button
        document.querySelectorAll('.icon-btn').forEach(btn => {
            btn.classList.remove('bg-green-200', 'border-green-500');
        });
        this.classList.add('bg-green-200', 'border-green-500');
    });
});

// Image preview
document.getElementById('image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('image-preview');
    
    if (file) {
        // Check file size (2MB = 2097152 bytes)
        if (file.size > 2097152) {
            preview.innerHTML = `
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <p class="text-sm"><i class="fas fa-exclamation-triangle mr-2"></i>File size exceeds 2MB. Please choose a smaller image.</p>
                </div>
            `;
            this.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `
                <div class="relative inline-block">
                    <img src="${e.target.result}" class="rounded-lg shadow-md border border-gray-300 max-w-full" 
                         style="max-width: 300px; max-height: 200px; object-fit: cover;">
                    <div class="absolute top-2 right-2 bg-green-500 text-white px-2 py-1 rounded text-xs font-bold">
                        <i class="fas fa-check mr-1"></i> Preview
                    </div>
                </div>
            `;
        }
        reader.readAsDataURL(file);
    } else {
        preview.innerHTML = '';
    }
});

// Form validation before submit
document.querySelector('form').addEventListener('submit', function(e) {
    const name = document.getElementById('name').value.trim();
    
    if (!name) {
        e.preventDefault();
        alert('Please enter a category name');
        document.getElementById('name').focus();
        return false;
    }
});
</script>
@endpush

<style>
.icon-btn {
    transition: all 0.2s ease;
}

.icon-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

#icon-preview {
    transition: all 0.3s ease;
}
</style>
@endsection