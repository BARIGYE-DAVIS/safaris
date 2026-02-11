@props(['images', 'columns' => 2])

@php
    // Handle both JSON string and array
    if (is_string($images)) {
        $images = json_decode($images, true);
    }
    $images = is_array($images) ? $images : [];
@endphp

@if(count($images) > 0)
<div class="grid grid-cols-1 md:grid-cols-{{ $columns }} gap-4 mb-6">
    @foreach($images as $imageData)
        @php
            $imagePath = is_array($imageData) ? ($imageData['image'] ?? '') : $imageData;
            $section = is_array($imageData) ? ($imageData['section'] ?? '') : '';
            $caption = is_array($imageData) ? ($imageData['caption'] ?? '') : '';
        @endphp
        @if($imagePath)
        <div class="inline-image-wrapper group">
            <div class="relative overflow-hidden rounded-lg shadow-md cursor-pointer" onclick="openSectionImageModal('{{ asset('storage/' . $imagePath) }}', '{{ addslashes($caption) }}', '{{ addslashes($section) }}')">
                <img src="{{ asset('storage/' . $imagePath) }}" 
                     alt="{{ $caption ?: $section ?: 'Image' }}" 
                     class="w-full h-56 object-cover group-hover:scale-110 transition duration-300"
                     loading="lazy">
                
                <!-- Hover overlay -->
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition duration-300">
                    <div class="absolute bottom-0 left-0 right-0 p-3">
                        <i class="fas fa-search-plus text-white text-xl"></i>
                    </div>
                </div>
            </div>
            
            @if($section || $caption)
            <div class="mt-2 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg p-3 border border-gray-200">
                @if($section)
                <p class="text-sm font-semibold text-gray-800 mb-1">
                    <i class="fas fa-tag text-green-600 mr-1 text-xs"></i>{{ $section }}
                </p>
                @endif
                @if($caption)
                <p class="text-xs text-gray-600 italic leading-relaxed">{{ $caption }}</p>
                @endif
            </div>
            @endif
        </div>
        @endif
    @endforeach
</div>
@endif