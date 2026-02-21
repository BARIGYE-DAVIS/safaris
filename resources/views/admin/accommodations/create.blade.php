@extends('layouts.admin')

@section('title', 'Create Accommodation')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            <i class="fas fa-hotel mr-2"></i>Create Accommodation
        </h1>
        <a href="{{ route('admin.accommodations.index') }}"
           class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded hover:bg-gray-300">
            <i class="fas fa-arrow-left mr-2"></i> Back to list
        </a>
    </div>

    @if ($errors->any())
        <div class="mb-4 bg-red-50 border-l-4 border-red-400 p-4 text-red-700 text-sm">
            <p class="font-bold mb-1">There were some problems with your input:</p>
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.accommodations.store') }}" method="POST" enctype="multipart/form-data"
          class="bg-white rounded-xl shadow p-6 space-y-6">
        @csrf

        {{-- BASIC INFO --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">
                    Name <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 @error('name') border-red-500 @enderror">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Type</label>
                <select name="type" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                    <option value="">Select Type</option>
                    <option value="Lodge"       {{ old('type') == 'Lodge'       ? 'selected' : '' }}>Lodge</option>
                    <option value="Tented Camp" {{ old('type') == 'Tented Camp' ? 'selected' : '' }}>Tented Camp</option>
                    <option value="Hotel"       {{ old('type') == 'Hotel'       ? 'selected' : '' }}>Hotel</option>
                    <option value="Guesthouse"  {{ old('type') == 'Guesthouse'  ? 'selected' : '' }}>Guesthouse</option>
                    <option value="City Hotel"  {{ old('type') == 'City Hotel'  ? 'selected' : '' }}>City Hotel</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">
                    Category <span class="text-red-500">*</span>
                </label>
                <select name="category" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500" required>
                    <option value="">Select Category</option>
                    <option value="budget"    {{ old('category') == 'budget'    ? 'selected' : '' }}>Budget</option>
                    <option value="mid-range" {{ old('category') == 'mid-range' ? 'selected' : '' }}>Mid-range</option>
                    <option value="high-end"  {{ old('category') == 'high-end'  ? 'selected' : '' }}>High-end / Luxury</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">
                    Location (short text)
                </label>
                <input type="text" name="location" value="{{ old('location') }}"
                       placeholder="e.g. Inside Park A, near main gate"
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Country</label>
                    <select name="country_id" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                        <option value="">Select Country</option>
                        @foreach($countries as $country)
                            <option value="{{ $country->id }}" {{ old('country_id') == $country->id ? 'selected' : '' }}>
                                {{ $country->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Destination (optional)</label>
                    <select name="destination_id" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                        <option value="">Select Destination</option>
                        @foreach($destinations as $destination)
                            <option value="{{ $destination->id }}" {{ old('destination_id') == $destination->id ? 'selected' : '' }}>
                                {{ $destination->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- PRICING --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Currency</label>
                <input type="text" name="currency" value="{{ old('currency', 'USD') }}"
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Price From (per person/night)</label>
                <input type="number" step="0.01" min="0" name="price_from" value="{{ old('price_from') }}"
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Price To (per person/night)</label>
                <input type="number" step="0.01" min="0" name="price_to" value="{{ old('price_to') }}"
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
            </div>
        </div>

        {{-- DESCRIPTIONS --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Short Description</label>
                <textarea name="short_description" rows="3"
                          class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500"
                          placeholder="2–3 sentences describing the lodge and why it's great for guests.">{{ old('short_description') }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Full Description (optional)</label>
                <textarea name="full_description" rows="3"
                          class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500"
                          placeholder="More detailed description (used on accommodation detail page).">{{ old('full_description') }}</textarea>
            </div>
        </div>

        {{-- AMENITIES --}}
        @php
            $allAmenities = [
                'En-suite bathrooms', 'Hot showers', '24-hour electricity', 'Solar power',
                'Mosquito nets', 'Ceiling fans', 'Air conditioning', 'Restaurant', 'Bar',
                'Lounge', 'Wi-Fi in public areas', 'Wi-Fi in rooms', 'Swimming pool',
                'Spa / massage services', 'Laundry service', 'Room service', 'Family rooms',
                'Honeymoon suite', 'Conference / meeting facilities', 'Gift shop',
                'Campfire area', 'Guided nature walks', 'Airport transfers',
                'Wheelchair accessible', 'Secure parking',
            ];
            $oldAmenities = old('amenities_list', []);
        @endphp

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Amenities</label>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2">
                @foreach($allAmenities as $amenity)
                    <label class="inline-flex items-center space-x-2 text-sm">
                        <input type="checkbox"
                               name="amenities_list[]"
                               value="{{ $amenity }}"
                               class="rounded text-green-600 focus:ring-green-500"
                               {{ in_array($amenity, $oldAmenities) ? 'checked' : '' }}>
                        <span>{{ $amenity }}</span>
                    </label>
                @endforeach
            </div>
            <p class="text-xs text-gray-500 mt-1">These will be stored as a list of amenities for this accommodation.</p>
        </div>

        {{-- IMAGES --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Featured Image --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Featured Image</label>
                <input type="file" name="featured_image" id="featured_image_input"
                       accept="image/*"
                       class="w-full text-sm text-gray-700 border rounded-lg px-2 py-1">
                <p class="text-xs text-gray-500 mt-1">Main image used in lists and as the header on the detail page. Max 4MB.</p>

                {{-- Featured image preview --}}
                <div id="featured_preview_wrap" class="mt-3 hidden">
                    <p class="text-xs font-semibold text-gray-600 mb-1">Preview:</p>
                    <div class="relative inline-block">
                        <img id="featured_preview_img"
                             src="#"
                             alt="Featured preview"
                             class="w-40 h-28 object-cover rounded-lg border shadow">
                        <button type="button"
                                onclick="clearFeaturedImage()"
                                class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs hover:bg-red-600">
                            &times;
                        </button>
                    </div>
                </div>
            </div>

{{-- Gallery Images --}}
<div>
    <label class="block text-sm font-semibold text-gray-700 mb-1">Gallery Images</label>
    <p class="text-xs text-gray-500 mb-2">
        Upload multiple images for the gallery. Max 4MB each.
    </p>

    {{-- Hidden file input (triggered by "Add Images" button) --}}
    <input type="file"
           name="gallery_images[]"
           id="gallery_images"
           accept="image/*"
           multiple
           class="hidden">

    <button type="button"
            onclick="document.getElementById('gallery_images').click()"
            class="inline-flex items-center px-4 py-2 bg-green-50 border border-green-400 text-green-700 text-sm font-semibold rounded-lg hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-green-500">
        <i class="fas fa-plus mr-2"></i> Add Images
    </button>

    {{-- Gallery previews grid --}}
    <div id="gallery_preview_grid"
         class="mt-3 grid grid-cols-3 gap-2">
        {{-- JS will inject preview items here --}}
    </div>

    <p id="gallery_empty_msg" class="text-xs text-gray-400 mt-2">
        No images selected yet.
    </p>
</div>

        </div>

        {{-- FLAGS --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="flex items-center space-x-2">
                <input type="checkbox" name="is_active" value="1"
                       class="rounded text-green-600 focus:ring-green-500"
                       {{ old('is_active', 1) ? 'checked' : '' }}>
                <span class="text-sm text-gray-700">Active (visible on public site)</span>
            </div>
            <div class="flex items-center space-x-2">
                <input type="checkbox" name="is_featured" value="1"
                       class="rounded text-green-600 focus:ring-green-500"
                       {{ old('is_featured') ? 'checked' : '' }}>
                <span class="text-sm text-gray-700">Featured</span>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Sort Order</label>
                <input type="number" name="sort_order" min="0" value="{{ old('sort_order', 0) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
            </div>
        </div>

        {{-- SUBMIT --}}
        <div class="flex justify-end">
            <button type="submit"
                    class="inline-flex items-center px-6 py-2.5 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                <i class="fas fa-save mr-2"></i> Save Accommodation
            </button>
        </div>
    </form>
</div>

<script>
    /*
    |--------------------------------------------------------------------------
    | Featured Image Preview (unchanged)
    |--------------------------------------------------------------------------
    */
    document.getElementById('featured_image_input')?.addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById('featured_preview_img').src = e.target.result;
            document.getElementById('featured_preview_wrap').classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    });

    function clearFeaturedImage() {
        const input = document.getElementById('featured_image_input');
        if (input) {
            input.value = '';
        }
        document.getElementById('featured_preview_img').src   = '#';
        document.getElementById('featured_preview_wrap').classList.add('hidden');
    }

    /*
    |--------------------------------------------------------------------------
    | Gallery Images – previews + remove, while still submitting files
    |--------------------------------------------------------------------------
    */

    const galleryInput    = document.getElementById('gallery_images');
    const galleryGrid     = document.getElementById('gallery_preview_grid');
    const galleryEmptyMsg = document.getElementById('gallery_empty_msg');

    // Master list of files we want to submit
    let galleryFiles = [];

    if (galleryInput) {
        galleryInput.addEventListener('change', function () {
            const newFiles = Array.from(this.files);

            // Add new files, avoid exact duplicates (same name + size)
            newFiles.forEach(file => {
                const exists = galleryFiles.some(
                    f => f.name === file.name && f.size === file.size && f.lastModified === file.lastModified
                );
                if (!exists) {
                    galleryFiles.push(file);
                }
            });

            // Rebuild FileList for the real input from galleryFiles
            const dt = new DataTransfer();
            galleryFiles.forEach(file => dt.items.add(file));
            galleryInput.files = dt.files;

            renderGalleryPreviews();

            // Reset physical picker so it can be used again
            this.value = '';
        });
    }

    function renderGalleryPreviews() {
        galleryGrid.innerHTML = '';

        if (!galleryFiles.length) {
            galleryEmptyMsg.classList.remove('hidden');
            return;
        }

        galleryEmptyMsg.classList.add('hidden');

        galleryFiles.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = e => {
                const wrapper = document.createElement('div');
                wrapper.className = 'relative group';

                wrapper.innerHTML = `
                    <img src="${e.target.result}"
                         alt="${file.name}"
                         class="w-full h-24 object-cover rounded-lg border shadow">
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 rounded-lg transition"></div>
                    <button type="button"
                            data-index="${index}"
                            class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs hover:bg-red-600 opacity-0 group-hover:opacity-100 transition">
                        &times;
                    </button>
                    <p class="text-xs text-gray-500 mt-1 truncate" title="${file.name}">${file.name}</p>
                `;

                // Attach click handler to remove button
                const btn = wrapper.querySelector('button');
                btn.addEventListener('click', function () {
                    removeGalleryImage(parseInt(this.getAttribute('data-index'), 10));
                });

                galleryGrid.appendChild(wrapper);
            };
            reader.readAsDataURL(file);
        });
    }

    function removeGalleryImage(index) {
        // Remove the file from galleryFiles
        galleryFiles.splice(index, 1);

        // Rebuild FileList for the real input from galleryFiles
        const dt = new DataTransfer();
        galleryFiles.forEach(file => dt.items.add(file));
        galleryInput.files = dt.files;

        renderGalleryPreviews();
    }
</script>
@endsection