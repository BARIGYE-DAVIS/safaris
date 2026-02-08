@extends('layouts.admin')

@section('title', 'Custom Tour Requests')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-green-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">Custom Tour Requests</h1>
                    <p class="text-gray-600">Manage and track all custom tour inquiries</p>
                </div>
                <div class="mt-4 md:mt-0">
                    <a href="{{ route('admin.custom-tour-requests.export', request()->all()) }}" 
                       class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Export to CSV
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Requests -->
            <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-shadow duration-300 overflow-hidden">
                <div class="p-6 border-l-4 border-indigo-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-indigo-600 uppercase tracking-wide mb-1">Total Requests</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                        </div>
                        <div class="bg-indigo-100 rounded-full p-4">
                            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- New -->
            <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-shadow duration-300 overflow-hidden">
                <div class="p-6 border-l-4 border-yellow-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-yellow-600 uppercase tracking-wide mb-1">New</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $stats['new'] }}</p>
                        </div>
                        <div class="bg-yellow-100 rounded-full p-4">
                            <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending -->
            <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-shadow duration-300 overflow-hidden">
                <div class="p-6 border-l-4 border-indigo-400">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-indigo-600 uppercase tracking-wide mb-1">Pending</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $stats['pending'] }}</p>
                        </div>
                        <div class="bg-indigo-100 rounded-full p-4">
                            <svg class="w-8 h-8 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booked -->
            <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-shadow duration-300 overflow-hidden">
                <div class="p-6 border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-green-600 uppercase tracking-wide mb-1">Booked</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $stats['booked'] }}</p>
                        </div>
                        <div class="bg-green-100 rounded-full p-4">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
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
                    Filter Requests
                </h2>
            </div>
            <div class="p-6">
                <form method="GET" action="{{ route('admin.custom-tour-requests.index') }}">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                        <div class="lg:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                            <input type="text" 
                                   name="search" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all" 
                                   placeholder="Name, email, reference..." 
                                   value="{{ request('search') }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                                <option value="">All Status</option>
                                @foreach($statuses as $key => $label)
                                    <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Country</label>
                            <select name="country" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                                <option value="">All Countries</option>
                                @foreach($countries as $name => $countryName)
                                    <option value="{{ $name }}" {{ request('country') == $name ? 'selected' : '' }}>{{ $countryName }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">From Date</label>
                            <input type="date" 
                                   name="date_from" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all" 
                                   value="{{ request('date_from') }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">To Date</label>
                            <input type="date" 
                                   name="date_to" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all" 
                                   value="{{ request('date_to') }}">
                        </div>
                    </div>
                    <div class="flex items-center gap-3 mt-6">
                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                            Apply Filters
                        </button>
                        <a href="{{ route('admin.custom-tour-requests.index') }}" class="px-6 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition-colors duration-200">
                            Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Requests Table -->
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
                                <th class="px-6 py-4 text-left text-xs font-bold text-indigo-900 uppercase tracking-wider">Reference</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-indigo-900 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-indigo-900 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-indigo-900 uppercase tracking-wider">Country</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-indigo-900 uppercase tracking-wider">Travel Dates</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-indigo-900 uppercase tracking-wider">Travelers</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-indigo-900 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-indigo-900 uppercase tracking-wider">Created</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-indigo-900 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($requests as $request)
                            <tr class="hover:bg-indigo-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="checkbox" class="select-item w-4 h-4 text-indigo-600 rounded focus:ring-indigo-500 border-gray-300" value="{{ $request->id }}">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-bold text-indigo-600">{{ $request->reference_number }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-medium text-gray-900">{{ $request->name }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-600">{{ $request->email }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-900">{{ $request->country }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($request->travel_date_from && $request->travel_date_to)
                                        <span class="text-sm text-gray-900">{{ $request->travel_date_from->format('M d') }} - {{ $request->travel_date_to->format('M d, Y') }}</span>
                                    @else
                                        <span class="text-sm text-gray-400 italic">Flexible</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                        {{ $request->total_travelers }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold 
                                        @if($request->status_color == 'success') bg-green-100 text-green-800
                                        @elseif($request->status_color == 'warning') bg-yellow-100 text-yellow-800
                                        @elseif($request->status_color == 'primary') bg-indigo-100 text-indigo-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ $request->status_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-600">{{ $request->created_at->format('M d, Y') }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.custom-tour-requests.show', $request) }}" 
                                           class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 hover:bg-indigo-200 transition-colors" 
                                           title="View Details">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
                                        <a href="{{ route('admin.custom-tour-requests.edit', $request) }}" 
                                           class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-yellow-100 text-yellow-600 hover:bg-yellow-200 transition-colors" 
                                           title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        <form action="{{ route('admin.custom-tour-requests.destroy', $request) }}" 
                                              method="POST" 
                                              style="display: inline;" 
                                              onsubmit="return confirm('Are you sure you want to delete this request?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-100 text-red-600 hover:bg-red-200 transition-colors" 
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
                                <td colspan="10" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <p class="text-gray-500 text-lg font-medium">No tour requests found</p>
                                        <p class="text-gray-400 text-sm mt-1">Try adjusting your filters</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $requests->links() }}
                </div>
            </div>
        </div>

        <!-- Bulk Actions -->
        <div class="mt-8 bg-white rounded-2xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Bulk Actions</h3>
            <form method="POST" id="bulk-action-form">
                @csrf
                <input type="hidden" name="ids" id="bulk-ids">
                <div class="flex flex-wrap items-center gap-4">
                    <div class="flex-1 min-w-[200px]">
                        <select name="status" 
                                id="bulk-status" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                            <option value="">-- Select Status --</option>
                            @foreach($statuses as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="button" 
                            id="bulk-update-status" 
                            class="px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Update Status
                        </span>
                    </button>
                    <button type="button" 
                            id="bulk-delete" 
                            class="px-6 py-2.5 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Delete Selected
                        </span>
                    </button>
                </div>
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

    // Get selected IDs
    function getSelectedIds() {
        return Array.from(document.querySelectorAll('.select-item:checked')).map(cb => cb.value);
    }

    // Bulk update status
    document.getElementById('bulk-update-status').addEventListener('click', function() {
        let selected = getSelectedIds();
        let status = document.getElementById('bulk-status').value;
        
        if (selected.length === 0) {
            alert('Please select at least one request');
            return;
        }
        
        if (!status) {
            alert('Please select a status');
            return;
        }
        
        let form = document.getElementById('bulk-action-form');
        form.action = '{{ route("admin.custom-tour-requests.bulk-update-status") }}';
        document.getElementById('bulk-ids').value = JSON.stringify(selected);
        form.submit();
    });

    // Bulk delete
    document.getElementById('bulk-delete').addEventListener('click', function() {
        let selected = getSelectedIds();
        
        if (selected.length === 0) {
            alert('Please select at least one request');
            return;
        }
        
        if (!confirm('Are you sure you want to delete ' + selected.length + ' request(s)?')) {
            return;
        }
        
        let form = document.getElementById('bulk-action-form');
        form.action = '{{ route("admin.custom-tour-requests.bulk-delete") }}';
        document.getElementById('bulk-ids').value = JSON.stringify(selected);
        form.submit();
    });
</script>
@endpush
@endsection