@extends('layouts.admin')

@section('title', 'Countries')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-green-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">Countries Management</h1>
                    <p class="text-gray-600">Manage countries, destinations, and activities</p>
                </div>
                <div class="mt-4 md:mt-0">
                    <a href="{{ route('admin.countries.create') }}" 
                       class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Add New Country
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Countries -->
            <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-shadow duration-300 overflow-hidden">
                <div class="p-6 border-l-4 border-indigo-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-indigo-600 uppercase tracking-wide mb-1">Total Countries</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $countries->total() }}</p>
                        </div>
                        <div class="bg-indigo-100 rounded-full p-4">
                            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Countries -->
            <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-shadow duration-300 overflow-hidden">
                <div class="p-6 border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-green-600 uppercase tracking-wide mb-1">Active</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $countries->where('is_active', true)->count() }}</p>
                        </div>
                        <div class="bg-green-100 rounded-full p-4">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Inactive Countries -->
            <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-shadow duration-300 overflow-hidden">
                <div class="p-6 border-l-4 border-yellow-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-yellow-600 uppercase tracking-wide mb-1">Inactive</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $countries->where('is_active', false)->count() }}</p>
                        </div>
                        <div class="bg-yellow-100 rounded-full p-4">
                            <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Destinations -->
            <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-shadow duration-300 overflow-hidden">
                <div class="p-6 border-l-4 border-indigo-400">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-indigo-600 uppercase tracking-wide mb-1">Destinations</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $countries->sum('destinations_count') }}</p>
                        </div>
                        <div class="bg-indigo-100 rounded-full p-4">
                            <svg class="w-8 h-8 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-2xl shadow-lg mb-8 overflow-hidden">
            <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-4">
                <h2 class="text-lg font-semibold text-white flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    Filter Countries
                </h2>
            </div>
            <div class="p-6">
                <form method="GET" action="{{ route('admin.countries.index') }}">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-1">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                                <input type="text" 
                                       name="search" 
                                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all" 
                                       placeholder="Search countries..." 
                                       value="{{ request('search') }}">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                                <option value="">All Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <div class="flex items-end gap-3">
                            <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                                Apply Filters
                            </button>
                            <a href="{{ route('admin.countries.index') }}" class="px-6 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition-colors duration-200">
                                Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Countries Table -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="p-6">
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-500 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-green-800 font-medium">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-red-800 font-medium">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-indigo-50 to-indigo-100">
                            <tr>
                                <th class="px-6 py-4 text-left">
                                    <input type="checkbox" id="select-all" class="w-4 h-4 text-indigo-600 rounded focus:ring-indigo-500 border-gray-300">
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-indigo-900 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-indigo-900 uppercase tracking-wider">Code</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-indigo-900 uppercase tracking-wider">Flag</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-indigo-900 uppercase tracking-wider">Destinations</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-indigo-900 uppercase tracking-wider">Activities</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-indigo-900 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-indigo-900 uppercase tracking-wider">Sort Order</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-indigo-900 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($countries as $country)
                            <tr class="hover:bg-indigo-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="checkbox" class="select-item w-4 h-4 text-indigo-600 rounded focus:ring-indigo-500 border-gray-300" value="{{ $country->id }}">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-indigo-100 to-indigo-200 rounded-full flex items-center justify-center">
                                            <span class="text-indigo-700 font-bold text-sm">{{ substr($country->name, 0, 2) }}</span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-semibold text-gray-900">{{ $country->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-800">
                                        {{ $country->code }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-2xl">{{ $country->flag_icon }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        {{ $country->destinations_count }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $country->activities_count }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold 
                                        {{ $country->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        <span class="w-2 h-2 mr-2 rounded-full {{ $country->is_active ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                                        {{ $country->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                                        {{ $country->sort_order }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.countries.edit', $country) }}" 
                                           class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-yellow-100 text-yellow-600 hover:bg-yellow-200 transition-colors" 
                                           title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        <form action="{{ route('admin.countries.toggle-status', $country) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="inline-flex items-center justify-center w-9 h-9 rounded-lg transition-colors
                                                    {{ $country->is_active ? 'bg-orange-100 text-orange-600 hover:bg-orange-200' : 'bg-green-100 text-green-600 hover:bg-green-200' }}" 
                                                    title="{{ $country->is_active ? 'Deactivate' : 'Activate' }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    @if($country->is_active)
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                                    @else
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    @endif
                                                </svg>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.countries.destroy', $country) }}" 
                                              method="POST" 
                                              style="display: inline;" 
                                              onsubmit="return confirm('Are you sure you want to delete this country?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-red-100 text-red-600 hover:bg-red-200 transition-colors" 
                                                    title="Delete">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <p class="text-gray-500 text-lg font-medium">No countries found</p>
                                        <p class="text-gray-400 text-sm mt-1">Try adjusting your filters or add a new country</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $countries->links() }}
                </div>
            </div>
        </div>

        <!-- Bulk Actions -->
        <div class="mt-8 bg-white rounded-2xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Bulk Actions</h3>
            <form action="{{ route('admin.countries.bulk-delete') }}" 
                  method="POST" 
                  id="bulk-delete-form" 
                  onsubmit="return confirm('Are you sure you want to delete the selected countries?')">
                @csrf
                <input type="hidden" name="ids" id="bulk-ids">
                <button type="submit" 
                        class="px-6 py-2.5 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Delete Selected
                    </span>
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Select all checkboxes
    document.getElementById('select-all').addEventListener('change', function() {
        let checkboxes = document.querySelectorAll('.select-item');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });

    // Bulk delete
    document.getElementById('bulk-delete-form').addEventListener('submit', function(e) {
        let selected = Array.from(document.querySelectorAll('.select-item:checked')).map(cb => cb.value);
        if (selected.length === 0) {
            e.preventDefault();
            alert('Please select at least one country');
            return false;
        }
        document.getElementById('bulk-ids').value = JSON.stringify(selected);
    });
</script>
@endpush
@endsection