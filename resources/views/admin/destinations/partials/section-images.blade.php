@php
    $existingImages = is_array($existingImages) ? $existingImages : [];
@endphp

<!-- Existing Images -->
@if(count($existingImages) > 0)
<div class="mb-6">
    <h4 class="text-sm font-semibold text-gray-700 mb-3">Current {{ $sectionLabel }} Images</h4>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($existingImages as $index => $imageData)
            @php
                $imagePath = is_array($imageData) ? ($imageData['image'] ?? '') : $imageData;
                $section = is_array($imageData) ? ($imageData['section'] ?? '') : '';
                $caption = is_array($imageData) ? ($imageData['caption'] ?? '') : '';
            @endphp
            @if($imagePath)
            <div class="border border-gray-200 rounded-lg p-3 bg-gray-50">
                <div class="relative mb-2">
                    <img src="{{ asset('storage/' . $imagePath) }}" 
                         alt="{{ $caption }}" 
                         class="w-full h-40 object-cover rounded">
                    <button type="button" 
                            class="delete-section-image absolute top-2 right-2 bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs"
                            data-section="{{ $sectionName }}"
                            data-index="{{ $index }}"
                            data-path="{{ $imagePath }}">
                        <i class="fas fa-trash"></i> Remove
                    </button>
                </div>
                <div class="text-xs space-y-1">
                    @if($section)
                    <p class="text-gray-700">
                        <strong>Section:</strong> {{ $section }}
                    </p>
                    @endif
                    @if($caption)
                    <p class="text-gray-600">
                        <strong>Caption:</strong> {{ $caption }}
                    </p>
                    @endif
                </div>
            </div>
            @endif
        @endforeach
    </div>
</div>
@endif

<!-- Upload New Images -->
<div class="border-2 border-dashed border-gray-300 rounded-lg p-6 bg-gray-50">
    <h4 class="text-sm font-semibold text-gray-700 mb-4">
        <i class="fas fa-plus-circle text-green-600 mr-2"></i>
        Add New {{ $sectionLabel }} Images
    </h4>
    
    <div id="{{ $sectionName }}-upload-container">
        <!-- Initial Upload Form -->
        <div class="section-image-item mb-4 p-4 bg-white rounded border border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Image File *</label>
                    <input type="file" 
                           name="{{ $sectionName }}[]" 
                           accept="image/*"
                           class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-green-500">
                    <p class="text-xs text-gray-500 mt-1">Max: 2MB</p>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Section Heading</label>
                    <input type="text" 
                           name="{{ $sectionName }}_sections[]" 
                           placeholder="e.g., Wildlife Game Drives"
                           class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-green-500">
                    <p class="text-xs text-gray-500 mt-1">Where to display this image</p>
                </div>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Caption (Optional)</label>
                <input type="text" 
                       name="{{ $sectionName }}_captions[]" 
                       placeholder="Brief description of the image"
                       class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-green-500">
            </div>
        </div>
    </div>

    <button type="button" 
            class="add-section-image-btn text-sm bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded transition"
            data-section="{{ $sectionName }}">
        <i class="fas fa-plus mr-2"></i>Add Another {{ $sectionLabel }} Image
    </button>
</div>

<!-- Hidden field to track deleted section images -->
<input type="hidden" name="delete_{{ $sectionName }}" id="delete_{{ $sectionName }}" value="">