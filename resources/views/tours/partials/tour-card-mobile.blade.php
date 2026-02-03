<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <!-- Compact mobile layout -->
    <div class="flex">
        <!-- Image -->
        <div class="w-32 h-32 flex-shrink-0 relative">
            @if($tour->featured_image)
                <img src="{{ asset('storage/' . $tour->featured_image) }}" 
                     alt="{{ $tour->title }}" 
                     class="w-full h-full object-cover">
            @else
                <div class="w-full h-full bg-gradient-to-br from-green-400 to-blue-500 flex items-center justify-center">
                    <svg class="w-8 h-8 text-white opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            @endif
            <div class="absolute top-2 left-2">
                <span class="bg-green-600 text-white px-2 py-1 rounded text-xs font-medium">
                    {{ $tour->category ?? 'Safari' }}
                </span>
            </div>
        </div>
        
        <!-- Content -->
        <div class="flex-1 p-4">
            <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2">
                {{ $tour->title }}
            </h3>
            
            <div class="flex items-center mb-2 text-sm text-gray-500">
                <svg class="w-4 h-4 text-green-600 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                </svg>
                <span class="line-clamp