@extends('layouts.admin')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

    {{-- Header --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Create Special Tour</h1>
            <p class="mt-1 text-sm text-gray-600">
                Add a new special tour and upload multiple images.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <a
                href="{{ route('admin.special-tours.index') }}"
                class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
            >
                Back
            </a>
        </div>
    </div>

    {{-- Errors --}}
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

    <form
        action="{{ route('admin.special-tours.store') }}"
        method="POST"
        enctype="multipart/form-data"
        class="mt-6 space-y-6"
    >
        @csrf

        {{-- Main card --}}
        <div class="rounded-lg border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-200 px-4 py-4 sm:px-6">
                <h2 class="text-sm font-semibold text-gray-900">Tour Details</h2>
                <p class="mt-1 text-sm text-gray-600">Title, description and pricing details.</p>
            </div>

            <div class="px-4 py-5 sm:px-6">
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    {{-- Title --}}
                    <div class="sm:col-span-2">
                        <label for="title" class="block text-sm font-medium text-gray-700">Title <span class="text-red-600">*</span></label>
                        <input
                            id="title"
                            name="title"
                            type="text"
                            value="{{ old('title') }}"
                            required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="e.g. 1 Day Jinja Adventure"
                        >
                        <p class="mt-1 text-xs text-gray-500">This is the name shown on the website.</p>
                    </div>

                    {{-- Slug --}}
                    <div class="sm:col-span-2">
                        <label for="slug" class="block text-sm font-medium text-gray-700">Slug (optional)</label>
                        <input
                            id="slug"
                            name="slug"
                            type="text"
                            value="{{ old('slug') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="auto-generated if empty"
                        >
                        <p class="mt-1 text-xs text-gray-500">Used in the URL. If empty, it will be generated from the title.</p>
                    </div>

                    {{-- Description --}}
                    <div class="sm:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700">Description <span class="text-red-600">*</span></label>
                        <textarea
                            id="description"
                            name="description"
                            rows="6"
                            required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="Write a clear description of the experience..."
                        >{{ old('description') }}</textarea>
                    </div>

                    {{-- Included --}}
                    <div>
                        <label for="whats_included" class="block text-sm font-medium text-gray-700">What's Included</label>
                        <textarea
                            id="whats_included"
                            name="whats_included"
                            rows="6"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="- Transport&#10;- Lunch&#10;- Guide"
                        >{{ old('whats_included') }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">Use bullet points for clarity.</p>
                    </div>

                    {{-- Not Included --}}
                    <div>
                        <label for="whats_not_included" class="block text-sm font-medium text-gray-700">What's Not Included</label>
                        <textarea
                            id="whats_not_included"
                            name="whats_not_included"
                            rows="6"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="- Tips&#10;- Personal expenses"
                        >{{ old('whats_not_included') }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">Optional.</p>
                    </div>

                    {{-- Price --}}
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700">Price (UGX)</label>
                        <input
                            id="price"
                            name="price"
                            type="number"
                            min="0"
                            step="1"
                            value="{{ old('price') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="e.g. 250000"
                        >
                    </div>

                    {{-- Currency --}}
                    <div>
                        <label for="currency" class="block text-sm font-medium text-gray-700">Currency</label>
                        <input
                            id="currency"
                            name="currency"
                            type="text"
                            value="{{ old('currency', 'UGX') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                        <p class="mt-1 text-xs text-gray-500">Default is UGX.</p>
                    </div>

                    {{-- Price note --}}
                    <div class="sm:col-span-2">
                        <label for="price_note" class="block text-sm font-medium text-gray-700">Price Note</label>
                        <input
                            id="price_note"
                            name="price_note"
                            type="text"
                            value="{{ old('price_note') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="e.g. per person / from"
                        >
                    </div>

                    {{-- Active --}}
                    <div class="sm:col-span-2">
                        <div class="flex items-start gap-3 rounded-md border border-gray-200 bg-gray-50 px-4 py-3">
                            <div class="pt-0.5">
                                <input
                                    id="is_active"
                                    name="is_active"
                                    type="checkbox"
                                    value="1"
                                    {{ old('is_active', '1') ? 'checked' : '' }}
                                    class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                >
                            </div>
                            <div>
                                <label for="is_active" class="text-sm font-medium text-gray-900">Active</label>
                                <p class="text-sm text-gray-600">If unchecked, the tour will be saved as inactive (hidden on the website).</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Images card --}}
        <div class="rounded-lg border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-200 px-4 py-4 sm:px-6">
                <h2 class="text-sm font-semibold text-gray-900">Images</h2>
                <p class="mt-1 text-sm text-gray-600">Upload multiple images (you can manage/delete/reorder in Edit).</p>
            </div>

            <div class="px-4 py-5 sm:px-6">
                <label for="images" class="block text-sm font-medium text-gray-700">Upload Images</label>
                <input
                    id="images"
                    name="images[]"
                    type="file"
                    multiple
                    accept="image/*"
                    class="mt-1 block w-full text-sm text-gray-700 file:mr-4 file:rounded-md file:border-0 file:bg-indigo-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-indigo-700 hover:file:bg-indigo-100"
                >
                <p class="mt-2 text-xs text-gray-500">Supported: JPG, PNG, WEBP. Max 5MB per image.</p>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-end gap-3">
            <a
                href="{{ route('admin.special-tours.index') }}"
                class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="inline-flex items-center justify-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
            >
                Create Tour
            </button>
        </div>
    </form>
</div>
@endsection