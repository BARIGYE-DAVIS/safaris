@extends('layouts.admin')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Edit Special Tour</h1>
            <p class="mt-1 text-sm text-gray-600">Update details, upload images, and manage status.</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.special-tours.index') }}"
               class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">
                Back
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="mt-6 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mt-6 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mt-6 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
            <div class="font-semibold">Please fix the errors below:</div>
            <ul class="mt-2 list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Quick actions (separate forms; NOT nested) --}}
    <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex flex-wrap items-center gap-2">
            @if ($specialTour->is_active)
                <form action="{{ route('admin.special-tours.deactivate', $specialTour->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit"
                            class="rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-black">
                        Deactivate
                    </button>
                </form>
            @else
                <form action="{{ route('admin.special-tours.activate', $specialTour->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit"
                            class="rounded-md bg-green-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-green-700">
                        Activate
                    </button>
                </form>
            @endif
        </div>

        <form action="{{ route('admin.special-tours.destroy', $specialTour->id) }}"
              method="POST"
              onsubmit="return confirm('Delete this special tour permanently?');">
            @csrf
            @method('DELETE')
            <button type="submit"
                    class="rounded-md border border-red-200 bg-red-50 px-4 py-2 text-sm font-medium text-red-700 shadow-sm hover:bg-red-100">
                Delete Tour
            </button>
        </form>
    </div>

    {{-- UPDATE form (only this form contains the save button) --}}
    <form action="{{ route('admin.special-tours.update', $specialTour->id) }}"
          method="POST"
          enctype="multipart/form-data"
          class="mt-6 space-y-6">
        @csrf
        @method('PUT')

        <div class="rounded-lg border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-200 px-4 py-4 sm:px-6">
                <h2 class="text-sm font-semibold text-gray-900">Details</h2>
            </div>

            <div class="px-4 py-5 sm:px-6">
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Title *</label>
                        <input name="title" type="text" required
                               value="{{ old('title', $specialTour->title) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Slug</label>
                        <input name="slug" type="text"
                               value="{{ old('slug', $specialTour->slug) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Description *</label>
                        <textarea name="description" rows="7" required
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $specialTour->description) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">What's Included</label>
                        <textarea name="whats_included" rows="6"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('whats_included', $specialTour->whats_included) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">What's Not Included</label>
                        <textarea name="whats_not_included" rows="6"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('whats_not_included', $specialTour->whats_not_included) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Price</label>
                        <input name="price" type="number" min="0" step="1"
                               value="{{ old('price', $specialTour->price) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Currency</label>
                        <input name="currency" type="text"
                               value="{{ old('currency', $specialTour->currency ?? 'UGX') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Price Note</label>
                        <input name="price_note" type="text"
                               value="{{ old('price_note', $specialTour->price_note) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="inline-flex items-center gap-2 text-sm font-medium text-gray-900">
                            <input type="checkbox" name="is_active" value="1"
                                   {{ old('is_active', $specialTour->is_active ? '1' : '') ? 'checked' : '' }}
                                   class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            Active (visible on website)
                        </label>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Upload additional images</label>
                        <input name="images[]" type="file" multiple accept="image/*"
                               class="mt-1 block w-full text-sm text-gray-700 file:mr-4 file:rounded-md file:border-0 file:bg-indigo-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-indigo-700 hover:file:bg-indigo-100">
                    </div>
                </div>
            </div>

            <div class="flex justify-end border-t border-gray-200 px-4 py-4 sm:px-6">
                <button type="submit"
                        class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700">
                    Save Changes
                </button>
            </div>
        </div>
    </form>

    {{-- Existing images (delete forms here; still NOT nested) --}}
    <div class="mt-6 rounded-lg border border-gray-200 bg-white shadow-sm">
        <div class="border-b border-gray-200 px-4 py-4 sm:px-6">
            <h2 class="text-sm font-semibold text-gray-900">Existing Images</h2>
        </div>

        <div class="px-4 py-5 sm:px-6">
            @if ($specialTour->images->count() === 0)
                <div class="rounded-md border border-dashed border-gray-300 bg-gray-50 p-6 text-sm text-gray-600">
                    No images uploaded yet.
                </div>
            @else
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($specialTour->images as $img)
                        <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
                            <div class="aspect-[4/3] bg-gray-100">
                                <img src="{{ asset('storage/' . $img->image_path) }}"
                                     class="h-full w-full object-cover"
                                     alt="{{ $img->alt_text ?? $specialTour->title }}">
                            </div>

                            <div class="p-3 flex items-center justify-end">
                                <form action="{{ route('admin.special-tours.images.destroy', $img->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Delete this image?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="rounded-md border border-red-200 bg-red-50 px-3 py-2 text-xs font-medium text-red-700 hover:bg-red-100">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

</div>
@endsection