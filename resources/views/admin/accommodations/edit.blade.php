@extends('layouts.admin')

@section('title', 'Edit Accommodation')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            <i class="fas fa-hotel mr-2"></i>Edit Accommodation
        </h1>
        <a href="{{ route('admin.accommodations.index') }}"
           class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded hover:bg-gray-300">
            <i class="fas fa-arrow-left mr-2"></i> Back to list
        </a>
    </div>

    @if (session('success'))
        <div class="mb-4 bg-green-50 border-l-4 border-green-400 p-4 text-green-800 text-sm">
            {{ session('success') }}
        </div>
    @endif

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

    <form action="{{ route('admin.accommodations.update', $accommodation) }}"
          method="POST" enctype="multipart/form-data"
          class="bg-white rounded-xl shadow p-6 space-y-6">
        @csrf
        @method('PUT')

        {{-- BASIC INFO --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">
                    Name <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name"
                       value="{{ old('name', $accommodation->name) }}" required
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 @error('name') border-red-500 @enderror">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Type</label>
                @php $typeOld = old('type', $accommodation->type); @endphp
                <select name="type" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                    <option value="">Select Type</option>
                    <option value="Lodge"       {{ $typeOld == 'Lodge'       ? 'selected' : '' }}>Lodge</option>
                    <option value="Tented Camp" {{ $typeOld == 'Tented Camp' ? 'selected' : '' }}>Tented Camp</option>
                    <option value="Hotel"       {{ $typeOld == 'Hotel'       ? 'selected' : '' }}>Hotel</option>
                    <option value="Guesthouse"  {{ $typeOld == 'Guesthouse'  ? 'selected' : '' }}>Guesthouse</option>
                    <option value="City Hotel"  {{ $typeOld == 'City Hotel'  ? 'selected' : '' }}>City Hotel</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">
                    Category <span class="text-red-500">*</span>
                </label>
                @php $categoryOld = old('category', $accommodation->category); @endphp
                <select name="category"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500"
                        required>
                    <option value="">Select Category</option>
                    <option value="budget"    {{ $categoryOld == 'budget'    ? 'selected' : '' }}>Budget</option>
                    <option value="mid-range" {{ $categoryOld == 'mid-range' ? 'selected' : '' }}>Mid-range</option>
                    <option value="high-end"  {{ $categoryOld == 'high-end'  ? 'selected' : '' }}>High-end / Luxury</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Location (short text)</label>
                <input type="text" name="location"
                       value="{{ old('location', $accommodation->location) }}"
                       placeholder="e.g. Inside Park A, near main gate"
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Country</label>
                    <select name="country_id"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                        <option value="">Select Country</option>
                        @foreach($countries as $country)
                            <option value="{{ $country->id }}"
                                {{ old('country_id', $accommodation->country_id) == $country->id ? 'selected' : '' }}>
                                {{ $country->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Destination (optional)</label>
                    <select name="destination_id"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                        <option value="">Select Destination</option>
                        @foreach($destinations as $destination)
                            <option value="{{ $destination->id }}"
                                {{ old('destination_id', $accommodation->destination_id) == $destination->id ? 'selected' : '' }}>
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
                <input type="text" name="currency"
                       value="{{ old('currency', $accommodation->currency ?? 'USD') }}"
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Price From (per person/night)</label>
                <input type="number" step="0.01" min="0" name="price_from"
                       value="{{ old('price_from', $accommodation->price_from) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Price To (per person/night)</label>
                <input type="number" step="0.01" min="0" name="price_to"
                       value="{{ old('price_to', $accommodation->price_to) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
            </div>
        </div>

        {{-- DESCRIPTIONS --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Short Description</label>
                <textarea name="short_description" rows="3"
                          class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500"
                          placeholder="2–3 sentences describing the lodge.">{{ old('short_description', $accommodation->short_description) }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Full Description (optional)</label>
                <textarea name="full_description" rows="3"
                          class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500"
                          placeholder="More detailed description.">{{ old('full_description', $accommodation->full_description) }}</textarea>
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
            $currentAmenities = is_array($accommodation->amenities) ? $accommodation->amenities : [];
            $oldAmenities     = old('amenities_list', $currentAmenities);
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
                               {{ in_array($amenity, $oldAmenities ?? []) ? 'checked' : '' }}>
                        <span>{{ $amenity }}</span>
                    </label>
                @endforeach
            </div>
            <p class="text-xs text-gray-500 mt-1">
                These will be stored as a list of amenities for this accommodation.
            </p>
        </div>

        {{-- IMAGES --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Featured Image --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Featured Image</label>

                @if($accommodation->featured_image_url)
                    <div class="mb-2" id="current_featured_wrap">
                        <p class="text-xs text-gray-500 mb-1">Current image:</p>
                        <img src="{{ $accommodation->featured_image_url }}"
                             alt="{{ $accommodation->name }}"
                             id="current_featured_img"
                             class="w-40 h-28 object-cover rounded-lg border shadow">
                    </div>
                @endif

                <input type="file" name="featured_image" id="featured_image_input"
                       accept="image/*"
                       class="w-full text-sm text-gray-700 border rounded-lg px-2 py-1">
                <p class="text-xs text-gray-500 mt-1">
                    Upload to replace the current featured image. Max 4MB.
                </p>

                <div id="featured_preview_wrap" class="mt-3 hidden">
                    <p class="text-xs font-semibold text-gray-600 mb-1">New image preview:</p>
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

                {{-- Existing saved gallery images --}}
                @if($accommodation->images->count())
                    <p class="text-xs font-semibold text-gray-600 mb-1">Existing images — click to mark for deletion:</p>
                    <div class="grid grid-cols-3 gap-2 mb-4">
                        @foreach($accommodation->images as $image)
                            <div class="relative group gallery-item" data-image-id="{{ $image->id }}">
                                <img src="{{ $image->url }}"
                                     alt="{{ $image->alt_text ?? $accommodation->name }}"
                                     class="gallery-thumb w-full h-24 object-cover rounded-lg border shadow cursor-pointer transition">

                                {{-- Hidden checkbox submitted with form --}}
                                <input type="checkbox"
                                       name="delete_images[]"
                                       value="{{ $image->id }}"
                                       class="delete-checkbox hidden">

                                {{-- Delete badge shown when marked --}}
                                <div class="delete-badge absolute inset-0 rounded-lg hidden flex-col items-center justify-center bg-red-500 bg-opacity-60">
                                    <span class="text-white text-2xl leading-none">🗑</span>
                                    <span class="text-white text-xs font-bold mt-1">Delete</span>
                                </div>

                                {{-- Hover hint when NOT marked --}}
                                <div class="hover-hint absolute inset-0 rounded-lg flex flex-col items-center justify-center
                                            bg-black bg-opacity-0 group-hover:bg-opacity-30 transition pointer-events-none">
                                    <span class="text-white text-xs font-semibold opacity-0 group-hover:opacity-100 transition">Click to delete</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-gray-500 mb-2">No gallery images uploaded yet.</p>
                @endif

                {{-- Add new images --}}
                <p class="text-xs text-gray-500 mb-2">
                    Add more images to the gallery. Max 4MB each.
                </p>

                <input type="file"
                       name="gallery_images[]"
                       id="edit_gallery_images"
                       accept="image/*"
                       multiple
                       class="hidden">

                <button type="button"
                        onclick="document.getElementById('edit_gallery_images').click()"
                        class="inline-flex items-center px-4 py-2 bg-green-50 border border-green-400 text-green-700 text-sm font-semibold rounded-lg hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-green-500">
                    <i class="fas fa-plus mr-2"></i> Add Images
                </button>

                <div id="edit_gallery_preview_grid" class="mt-3 grid grid-cols-3 gap-2"></div>
                <p id="edit_gallery_empty_msg" class="text-xs text-gray-400 mt-2 hidden">
                    No new images selected.
                </p>
            </div>

        </div>

        {{-- FLAGS --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="flex items-center space-x-2">
                <input type="checkbox" name="is_active" value="1"
                       class="rounded text-green-600 focus:ring-green-500"
                       {{ old('is_active', $accommodation->is_active) ? 'checked' : '' }}>
                <span class="text-sm text-gray-700">Active (visible on public site)</span>
            </div>
            <div class="flex items-center space-x-2">
                <input type="checkbox" name="is_featured" value="1"
                       class="rounded text-green-600 focus:ring-green-500"
                       {{ old('is_featured', $accommodation->is_featured) ? 'checked' : '' }}>
                <span class="text-sm text-gray-700">Featured</span>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Sort Order</label>
                <input type="number" name="sort_order" min="0"
                       value="{{ old('sort_order', $accommodation->sort_order) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
            </div>
        </div>

        {{-- SUBMIT --}}
        <div class="flex justify-end">
            <button type="submit"
                    class="inline-flex items-center px-6 py-2.5 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                <i class="fas fa-save mr-2"></i> Update Accommodation
            </button>
        </div>
    </form>
</div>

<script>
    /*
    |--------------------------------------------------------------------------
    | Featured Image Preview
    |--------------------------------------------------------------------------
    */
    const featuredInput = document.getElementById('featured_image_input');
    if (featuredInput) {
        featuredInput.addEventListener('change', function () {
            const file = this.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('featured_preview_img').src = e.target.result;
                document.getElementById('featured_preview_wrap').classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        });
    }

    function clearFeaturedImage() {
        const input = document.getElementById('featured_image_input');
        if (input) input.value = '';
        document.getElementById('featured_preview_img').src = '#';
        document.getElementById('featured_preview_wrap').classList.add('hidden');
    }

    /*
    |--------------------------------------------------------------------------
    | Existing Gallery Images — click image to toggle delete
    |--------------------------------------------------------------------------
    */
    document.querySelectorAll('.gallery-item').forEach(function (item) {
        const thumb    = item.querySelector('.gallery-thumb');
        const checkbox = item.querySelector('.delete-checkbox');
        const badge    = item.querySelector('.delete-badge');
        const hint     = item.querySelector('.hover-hint');

        thumb.addEventListener('click', function () {
            // Toggle the checkbox state
            checkbox.checked = !checkbox.checked;

            if (checkbox.checked) {
                // Show red delete overlay, dim the image
                badge.classList.remove('hidden');
                badge.classList.add('flex');
                thumb.classList.add('opacity-40');
                hint.classList.add('hidden');
            } else {
                // Remove delete overlay
                badge.classList.add('hidden');
                badge.classList.remove('flex');
                thumb.classList.remove('opacity-40');
                hint.classList.remove('hidden');
            }
        });
    });

    /*
    |--------------------------------------------------------------------------
    | New Gallery Images — accumulate across multiple "Add Images" clicks
    |--------------------------------------------------------------------------
    */
    const editGalleryInput    = document.getElementById('edit_gallery_images');
    const editGalleryGrid     = document.getElementById('edit_gallery_preview_grid');
    const editGalleryEmptyMsg = document.getElementById('edit_gallery_empty_msg');

    let editGalleryFiles = [];

    if (editGalleryInput) {
        editGalleryInput.addEventListener('change', function () {
            const newFiles = Array.from(this.files);

            newFiles.forEach(file => {
                const exists = editGalleryFiles.some(
                    f => f.name === file.name &&
                         f.size === file.size &&
                         f.lastModified === file.lastModified
                );
                if (!exists) {
                    editGalleryFiles.push(file);
                }
            });

            const dt = new DataTransfer();
            editGalleryFiles.forEach(file => dt.items.add(file));
            editGalleryInput.files = dt.files;

            renderEditGalleryPreviews();
        });
    }

    function renderEditGalleryPreviews() {
        editGalleryGrid.innerHTML = '';

        if (!editGalleryFiles.length) {
            editGalleryEmptyMsg.classList.remove('hidden');
            return;
        }

        editGalleryEmptyMsg.classList.add('hidden');

        editGalleryFiles.forEach((file, index) => {
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
                            class="remove-new-img absolute top-1 right-1 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs hover:bg-red-600 opacity-0 group-hover:opacity-100 transition">
                        &times;
                    </button>
                    <p class="text-xs text-gray-500 mt-1 truncate" title="${file.name}">${file.name}</p>
                `;

                wrapper.querySelector('.remove-new-img').addEventListener('click', function () {
                    removeEditGalleryImage(parseInt(this.getAttribute('data-index'), 10));
                });

                editGalleryGrid.appendChild(wrapper);
            };
            reader.readAsDataURL(file);
        });
    }

    function removeEditGalleryImage(index) {
        editGalleryFiles.splice(index, 1);

        const dt = new DataTransfer();
        editGalleryFiles.forEach(file => dt.items.add(file));
        editGalleryInput.files = dt.files;

        renderEditGalleryPreviews();
    }
</script>
@endsection