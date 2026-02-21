@extends('layouts.admin')

@section('title', 'Accommodations')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            <i class="fas fa-hotel mr-2"></i>Accommodations
        </h1>
        <a href="{{ route('admin.accommodations.create') }}"
           class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-green-600 rounded hover:bg-green-700">
            <i class="fas fa-plus mr-2"></i> New Accommodation
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 bg-green-50 border-l-4 border-green-400 p-4 text-green-800 text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- Filters --}}
    <form method="GET" action="{{ route('admin.accommodations.index') }}" class="mb-4 bg-white rounded-lg shadow p-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">

            {{-- Search --}}
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">
                    Search (name, type, location)
                </label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search..."
                       class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-green-500">
            </div>

            {{-- Country --}}
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Country</label>
                <select name="country_id"
                        class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-green-500">
                    <option value="">All Countries</option>
                    @foreach($countries as $country)
                        <option value="{{ $country->id }}"
                                {{ request('country_id') == $country->id ? 'selected' : '' }}>
                            {{ $country->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Destination --}}
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Destination</label>
                <select name="destination_id"
                        class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-green-500">
                    <option value="">All Destinations</option>
                    @foreach($destinations as $destination)
                        <option value="{{ $destination->id }}"
                                {{ request('destination_id') == $destination->id ? 'selected' : '' }}>
                            {{ $destination->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Status + Category + Buttons --}}
            <div class="flex items-end space-x-2">

                {{-- Status --}}
                <div class="flex-1">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Status</label>
                    <select name="is_active"
                            class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-green-500">
                        <option value="">All</option>
                        <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                {{-- Category --}}
                <div class="flex-1">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Category</label>
                    <select name="category"
                            class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-green-500">
                        <option value="">All Categories</option>
                        <option value="budget"    {{ request('category') === 'budget'    ? 'selected' : '' }}>Budget</option>
                        <option value="mid-range" {{ request('category') === 'mid-range' ? 'selected' : '' }}>Mid-range</option>
                        <option value="high-end"  {{ request('category') === 'high-end'  ? 'selected' : '' }}>High-end / Luxury</option>
                    </select>
                </div>

                {{-- Buttons --}}
                <div class="flex space-x-2 pb-0.5">
                    <button type="submit"
                            class="inline-flex items-center px-3 py-2 bg-green-600 text-white text-xs font-semibold rounded-lg hover:bg-green-700">
                        <i class="fas fa-filter mr-1"></i> Filter
                    </button>
                    <a href="{{ route('admin.accommodations.index') }}"
                       class="inline-flex items-center px-3 py-2 bg-gray-200 text-gray-700 text-xs font-semibold rounded-lg hover:bg-gray-300">
                        <i class="fas fa-times mr-1"></i> Reset
                    </a>
                </div>

            </div>
        </div>
    </form>

    {{-- Table --}}
    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full text-sm text-gray-800">
            <thead class="bg-gray-100 text-xs font-semibold uppercase text-gray-600">
                <tr>
                    <th class="px-4 py-3 text-left">Name</th>
                    <th class="px-4 py-3 text-left">Type</th>
                    <th class="px-4 py-3 text-left">Category</th>
                    <th class="px-4 py-3 text-left">Location</th>
                    <th class="px-4 py-3 text-left">Country / Destination</th>
                    <th class="px-4 py-3 text-left">Price Range</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($accommodations as $accommodation)
                    <tr class="border-t border-gray-100 hover:bg-gray-50">

                        {{-- Name + Thumbnail --}}
                        <td class="px-4 py-3">
                            <div class="flex items-center space-x-3">
                                @if($accommodation->featured_image)
                                    <img src="{{ $accommodation->featured_image_url }}"
                                         alt="{{ $accommodation->name }}"
                                         class="w-10 h-10 rounded object-cover">
                                @else
                                    <div class="w-10 h-10 rounded bg-gray-200 flex items-center justify-center text-xs text-gray-500">
                                        N/A
                                    </div>
                                @endif
                                <div>
                                    <div class="font-semibold">{{ $accommodation->name }}</div>
                                    <div class="text-xs text-gray-500">Slug: {{ $accommodation->slug }}</div>
                                </div>
                            </div>
                        </td>

                        {{-- Type --}}
                        <td class="px-4 py-3">
                            <span class="text-xs px-2 py-1 rounded bg-gray-100 text-gray-700">
                                {{ $accommodation->type ?? '—' }}
                            </span>
                        </td>

                        {{-- Category --}}
                        <td class="px-4 py-3">
                            @php $cat = $accommodation->category; @endphp
                            @if($cat)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs
                                    @if($cat === 'budget') bg-blue-100 text-blue-800
                                    @elseif($cat === 'mid-range') bg-green-100 text-green-800
                                    @else bg-purple-100 text-purple-800
                                    @endif">
                                    {{ ucfirst($cat) }}
                                </span>
                            @else
                                <span class="text-xs text-gray-400">Not set</span>
                            @endif
                        </td>

                        {{-- Location --}}
                        <td class="px-4 py-3">
                            <div class="text-xs text-gray-700">{{ $accommodation->location ?? '—' }}</div>
                        </td>

                        {{-- Country / Destination --}}
                        <td class="px-4 py-3">
                            <div class="text-xs text-gray-700">
                                @if($accommodation->country)
                                    <span class="font-semibold">{{ $accommodation->country->name }}</span>
                                @else
                                    <span class="text-gray-400">No country</span>
                                @endif
                            </div>
                            <div class="text-xs text-gray-500">
                                @if($accommodation->destination)
                                    Destination: {{ $accommodation->destination->name }}
                                @else
                                    <span class="text-gray-400">No destination</span>
                                @endif
                            </div>
                        </td>

                        {{-- Price Range --}}
                        <td class="px-4 py-3">
                            <div class="text-xs text-gray-700">
                                @if($accommodation->display_price_range)
                                    {{ $accommodation->display_price_range }}
                                @else
                                    <span class="text-gray-400">Not set</span>
                                @endif
                            </div>
                        </td>

                        {{-- Status --}}
                        <td class="px-4 py-3">
                            <div class="flex flex-col space-y-1 text-xs">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full
                                    {{ $accommodation->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    <span class="w-1.5 h-1.5 mr-1 rounded-full
                                        {{ $accommodation->is_active ? 'bg-green-500' : 'bg-red-500' }}"></span>
                                    {{ $accommodation->is_active ? 'Active' : 'Inactive' }}
                                </span>
                                @if($accommodation->is_featured)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-star mr-1 text-xs"></i> Featured
                                    </span>
                                @endif
                            </div>
                        </td>

                        {{-- Actions --}}
                        <td class="px-4 py-3 text-right">
                            <div class="inline-flex items-center space-x-2">
                                <a href="{{ route('admin.accommodations.edit', $accommodation) }}"
                                   class="inline-flex items-center px-2 py-1 text-xs font-semibold text-blue-700 bg-blue-100 rounded hover:bg-blue-200">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </a>
                                <form action="{{ route('admin.accommodations.destroy', $accommodation) }}"
                                      method="POST"
                                      onsubmit="return confirm('Are you sure you want to delete this accommodation?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center px-2 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded hover:bg-red-200">
                                        <i class="fas fa-trash mr-1"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </td>

                    </tr>
                @empty
                    <tr>
                        {{-- FIX: colspan updated to 8 to match the 8 header columns --}}
                        <td colspan="8" class="px-4 py-6 text-center text-sm text-gray-500">
                            No accommodations found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $accommodations->withQueryString()->links() }}
    </div>
</div>
@endsection