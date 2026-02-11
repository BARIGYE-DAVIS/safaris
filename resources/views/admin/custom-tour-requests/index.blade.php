@extends('layouts.admin')

@section('title', 'Custom Tour Requests')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 p-4 md:p-6">

  
    <!-- Header Section -->
    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-lg shadow-sm">
                        <i class="fas fa-calendar-edit text-white text-xl"></i>
                    </div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Custom Tour Requests</h1>
                </div>
                <p class="text-gray-500">Manage and track all custom tour inquiries and requests</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.custom-tour-requests.export', request()->query()) }}" 
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-lg hover:from-green-600 hover:to-emerald-700 transition-all duration-200 shadow-sm hover:shadow-md">
                    <i class="fas fa-file-excel"></i>
                    Export CSV
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
        <!-- Total -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow duration-200">
            <div class="p-5">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Requests</p>
                        <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($stats['total']) }}</h3>
                    </div>
                    <div class="p-3 bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-lg">
                        <i class="fas fa-list text-indigo-500 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="h-1 w-full bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-indigo-500 to-indigo-600" style="width: 100%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- New -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow duration-200">
            <div class="p-5">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm font-medium text-gray-500">New</p>
                        <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($stats['new']) }}</h3>
                    </div>
                    <div class="p-3 bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-lg">
                        <i class="fas fa-star text-yellow-500 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="h-1 w-full bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-yellow-400 to-yellow-500" style="width: {{ $stats['total'] > 0 ? ($stats['new']/$stats['total'])*100 : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow duration-200">
            <div class="p-5">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Pending</p>
                        <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($stats['pending']) }}</h3>
                    </div>
                    <div class="p-3 bg-gradient-to-br from-orange-50 to-orange-100 rounded-lg">
                        <i class="fas fa-clock text-orange-500 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="h-1 w-full bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-orange-400 to-orange-500" style="width: {{ $stats['total'] > 0 ? ($stats['pending']/$stats['total'])*100 : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quoted -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow duration-200">
            <div class="p-5">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Quoted</p>
                        <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($stats['quoted']) }}</h3>
                    </div>
                    <div class="p-3 bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg">
                        <i class="fas fa-file-invoice-dollar text-blue-500 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="h-1 w-full bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-blue-400 to-blue-500" style="width: {{ $stats['total'] > 0 ? ($stats['quoted']/$stats['total'])*100 : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Booked -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow duration-200">
            <div class="p-5">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Booked</p>
                        <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($stats['booked']) }}</h3>
                    </div>
                    <div class="p-3 bg-gradient-to-br from-green-50 to-emerald-100 rounded-lg">
                        <i class="fas fa-check-circle text-green-500 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="h-1 w-full bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-green-400 to-emerald-500" style="width: {{ $stats['total'] > 0 ? ($stats['booked']/$stats['total'])*100 : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="p-6">
            <form method="GET" action="{{ route('admin.custom-tour-requests.index') }}" id="filterForm">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-4">
                    <!-- Search -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text" 
                                   name="search" 
                                   class="pl-10 w-full rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 focus:ring-opacity-50 transition-colors duration-200 py-2.5 px-4" 
                                   placeholder="Reference, name, email..." 
                                   value="{{ request('search') }}">
                        </div>
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <div class="relative">
                            <select name="status" class="w-full rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 focus:ring-opacity-50 transition-colors duration-200 py-2.5 px-4 appearance-none bg-white">
                                <option value="">All Statuses</option>
                                @foreach($statuses as $value => $label)
                                    <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Country Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Country</label>
                        <div class="relative">
                            <select name="country" class="w-full rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 focus:ring-opacity-50 transition-colors duration-200 py-2.5 px-4 appearance-none bg-white">
                                <option value="">All Countries</option>
                                @foreach($countries as $countryName)
                                    <option value="{{ $countryName }}" {{ request('country') == $countryName ? 'selected' : '' }}>
                                        {{ $countryName }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <i class="fas fa-globe text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Date From -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">From Date</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-calendar text-gray-400"></i>
                            </div>
                            <input type="date" 
                                   name="date_from" 
                                   class="pl-10 w-full rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 focus:ring-opacity-50 transition-colors duration-200 py-2.5 px-4" 
                                   value="{{ request('date_from') }}">
                        </div>
                    </div>

                    <!-- Date To -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">To Date</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-calendar text-gray-400"></i>
                            </div>
                            <input type="date" 
                                   name="date_to" 
                                   class="pl-10 w-full rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 focus:ring-opacity-50 transition-colors duration-200 py-2.5 px-4" 
                                   value="{{ request('date_to') }}">
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                    <button type="submit" 
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-indigo-500 to-indigo-600 text-white rounded-lg hover:from-indigo-600 hover:to-indigo-700 transition-all duration-200 shadow-sm hover:shadow-md">
                        <i class="fas fa-filter"></i>
                        Apply Filters
                    </button>
                    <a href="{{ route('admin.custom-tour-requests.index') }}" 
                       class="inline-flex items-center gap-2 px-5 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-200">
                        <i class="fas fa-times"></i>
                        Clear Filters
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Bulk Actions Bar -->
    <div class="bg-gradient-to-r from-indigo-50 to-indigo-100 border border-indigo-200 rounded-xl shadow-sm mb-4 hidden" id="bulkActionsBar">
        <div class="p-4">
            <form method="POST" id="bulkActionForm">
                @csrf
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <span class="px-3 py-1 bg-white rounded-full text-sm font-medium text-indigo-700">
                            <span id="selectedCount">0</span> selected
                        </span>
                        <div class="w-px h-6 bg-indigo-200"></div>
                        <div class="flex items-center gap-3">
                            <select name="status" class="rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 focus:ring-opacity-50 py-2 px-4 bg-white" id="bulkStatus">
                                <option value="">Change Status</option>
                                @foreach($statuses as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            <button type="button" 
                                    onclick="bulkUpdateStatus()"
                                    class="px-4 py-2 bg-gradient-to-r from-yellow-400 to-yellow-500 text-white rounded-lg hover:from-yellow-500 hover:to-yellow-600 transition-all duration-200 shadow-sm hover:shadow-md">
                                Apply
                            </button>
                        </div>
                    </div>
                    <button type="button" 
                            onclick="bulkDelete()"
                            class="px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:from-red-600 hover:to-red-700 transition-all duration-200 shadow-sm hover:shadow-md">
                        <i class="fas fa-trash mr-2"></i>
                        Delete Selected
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Main Table Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        @if($requests->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left">
                                <div class="flex items-center">
                                    <input type="checkbox" 
                                           class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 h-4 w-4"
                                           id="selectAll">
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Reference
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Customer
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Contact
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Travel Details
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($requests as $request)
                        <tr class="hover:bg-gradient-to-r hover:from-gray-50 hover:to-white transition-colors duration-150">
                            <td class="px-6 py-4">
                                <input type="checkbox" 
                                       class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 h-4 w-4 request-checkbox"
                                       value="{{ $request->id }}">
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-indigo-100 to-indigo-200 text-indigo-800">
                                        {{ $request->reference_number }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gradient-to-r from-indigo-100 to-blue-100 flex items-center justify-center">
                                        <span class="font-medium text-indigo-600">{{ substr($request->name, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $request->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $request->country ?? 'N/A' }}</div>
                                        @if($request->language)
                                            <span class="inline-flex items-center gap-1 mt-1">
                                                <i class="fas fa-language text-gray-400 text-xs"></i>
                                                <span class="text-xs text-gray-500">{{ $request->language }}</span>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-envelope text-gray-400 text-sm"></i>
                                        <span class="text-sm text-gray-900">{{ $request->email }}</span>
                                    </div>
                                    @if($request->phone)
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-phone text-gray-400 text-sm"></i>
                                        <span class="text-sm text-gray-900">{{ $request->phone }}</span>
                                    </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="space-y-2">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-calendar-alt text-gray-400 text-sm"></i>
                                        <span class="text-sm text-gray-900">{{ $request->travel_dates_formatted ?? 'Not specified' }}</span>
                                        @if($request->flexible_dates)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gradient-to-r from-yellow-100 to-yellow-200 text-yellow-800">
                                                Flexible
                                            </span>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-4 text-xs">
                                        <span class="flex items-center gap-1">
                                            <i class="fas fa-user text-gray-400"></i>
                                            <span class="text-gray-600">{{ $request->adults_count }} Adults</span>
                                        </span>
                                        @if($request->children_count > 0)
                                        <span class="flex items-center gap-1">
                                            <i class="fas fa-child text-gray-400"></i>
                                            <span class="text-gray-600">{{ $request->children_count }} Children</span>
                                        </span>
                                        @endif
                                        @if($request->approximate_budget)
                                        <span class="flex items-center gap-1">
                                            <i class="fas fa-dollar-sign text-gray-400"></i>
                                            <span class="text-gray-600">{{ $request->approximate_budget }}</span>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <form method="POST" 
                                      action="{{ route('admin.custom-tour-requests.update-status', $request) }}" 
                                      class="status-form">
                                    @csrf
                                    <select name="status" 
                                            onchange="this.form.submit()"
                                            class="rounded-lg border-0 text-sm font-medium py-1.5 px-3 focus:ring-2 focus:ring-opacity-50 transition-all duration-200 {{ $request->status_class }}">
                                        @foreach($statuses as $value => $label)
                                            <option value="{{ $value }}" {{ $request->status == $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </form>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.custom-tour-requests.show', $request) }}" 
                                       class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-gradient-to-r from-blue-50 to-blue-100 text-blue-600 hover:from-blue-100 hover:to-blue-200 transition-all duration-200">
                                        <i class="fas fa-eye text-sm"></i>
                                    </a>
                                    <a href="{{ route('admin.custom-tour-requests.edit', $request) }}" 
                                       class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-gradient-to-r from-indigo-50 to-indigo-100 text-indigo-600 hover:from-indigo-100 hover:to-indigo-200 transition-all duration-200">
                                        <i class="fas fa-edit text-sm"></i>
                                    </a>
                                    <button onclick="openDeleteModal({{ $request->id }})"
                                            class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-gradient-to-r from-red-50 to-red-100 text-red-600 hover:from-red-100 hover:to-red-200 transition-all duration-200">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-100">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="text-sm text-gray-500">
                        Showing <span class="font-medium">{{ $requests->firstItem() }}</span> to 
                        <span class="font-medium">{{ $requests->lastItem() }}</span> of 
                        <span class="font-medium">{{ $requests->total() }}</span> results
                    </div>
                    <div>
                        {{ $requests->links() }}
                    </div>
                </div>
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="mx-auto w-24 h-24 mb-6 rounded-full bg-gradient-to-r from-gray-100 to-gray-200 flex items-center justify-center">
                    <i class="fas fa-inbox text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No tour requests found</h3>
                <p class="text-gray-500 max-w-md mx-auto mb-6">
                    @if(request()->hasAny(['search', 'status', 'country', 'date_from', 'date_to']))
                        Try adjusting your filters to find what you're looking for.
                    @else
                        Custom tour requests will appear here once submitted by customers.
                    @endif
                </p>
                @if(request()->hasAny(['search', 'status', 'country', 'date_from', 'date_to']))
                <a href="{{ route('admin.custom-tour-requests.index') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-indigo-500 to-indigo-600 text-white rounded-lg hover:from-indigo-600 hover:to-indigo-700 transition-all duration-200">
                    <i class="fas fa-times"></i>
                    Clear Filters
                </a>
                @endif
            </div>
        @endif
    </div>
</div>

<!-- Delete Confirmation Modal (Tailwind CSS) -->
<div id="deleteModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-xl bg-white">
        <div class="mt-3">
            <!-- Header -->
            <div class="flex items-center justify-between p-4 bg-gradient-to-r from-red-50 to-red-100 rounded-t-xl -m-5 mb-4">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                    Confirm Delete
                </h3>
                <button onclick="closeDeleteModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <!-- Body -->
            <div class="mt-2 px-4 py-3">
                <p class="text-sm text-gray-600">
                    Are you sure you want to delete this tour request? This action cannot be undone.
                </p>
            </div>
            
            <!-- Footer -->
            <div class="flex items-center gap-3 px-4 py-3">
                <button onclick="closeDeleteModal()" 
                        class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 text-sm font-medium rounded-lg hover:bg-gray-300 transition-colors">
                    Cancel
                </button>
                <form method="POST" id="deleteForm" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="w-full px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white text-sm font-medium rounded-lg hover:from-red-600 hover:to-red-700 transition-all">
                        <i class="fas fa-trash mr-1"></i> Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Status badge colors with gradients */
    .status-new {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        color: #92400e;
    }
    .status-pending {
        background: linear-gradient(135deg, #ffedd5 0%, #fed7aa 100%);
        color: #9a3412;
    }
    .status-quoted {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        color: #1e40af;
    }
    .status-booked {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        color: #065f46;
    }
    .status-cancelled {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: #991b1b;
    }
    
    /* Custom select styles */
    select.status-select {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 0.5rem center;
        background-repeat: no-repeat;
        background-size: 1.5em 1.5em;
        padding-right: 2.5rem;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    
    /* Custom hover effects */
    .hover-lift:hover {
        transform: translateY(-2px);
        transition: transform 0.2s ease-in-out;
    }
</style>
@endpush

@push('scripts')
<script>
    // Modal Functions
    function openDeleteModal(id) {
        const modal = document.getElementById('deleteModal');
        const form = document.getElementById('deleteForm');
        form.action = `/admin/custom-tour-requests/${id}`;
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Close modal on background click
    document.getElementById('deleteModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeleteModal();
        }
    });

    // Close modal on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDeleteModal();
        }
    });

    // Select all checkboxes
    const selectAllCheckbox = document.getElementById('selectAll');
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.request-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateBulkActionsBar();
        });
    }

    // Individual checkbox change
    document.querySelectorAll('.request-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActionsBar);
    });

    // Update bulk actions bar visibility
    function updateBulkActionsBar() {
        const checkedBoxes = document.querySelectorAll('.request-checkbox:checked');
        const bulkBar = document.getElementById('bulkActionsBar');
        const count = document.getElementById('selectedCount');
        
        if (checkedBoxes.length > 0) {
            bulkBar.classList.remove('hidden');
            count.textContent = checkedBoxes.length;
        } else {
            bulkBar.classList.add('hidden');
        }
    }

    // Bulk update status
    function bulkUpdateStatus() {
        const checkedBoxes = document.querySelectorAll('.request-checkbox:checked');
        const status = document.getElementById('bulkStatus').value;
        
        if (!status) {
            alert('Please select a status');
            return;
        }
        
        if (checkedBoxes.length === 0) {
            alert('Please select at least one request');
            return;
        }
        
        const ids = Array.from(checkedBoxes).map(cb => cb.value);
        const form = document.getElementById('bulkActionForm');
        
        // Clear previous inputs
        form.querySelectorAll('input[name="ids[]"]').forEach(input => input.remove());
        
        // Add hidden inputs for IDs
        ids.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'ids[]';
            input.value = id;
            form.appendChild(input);
        });
        
        form.action = '{{ route("admin.custom-tour-requests.bulk-update-status") }}';
        form.submit();
    }

    // Bulk delete
    function bulkDelete() {
        const checkedBoxes = document.querySelectorAll('.request-checkbox:checked');
        
        if (checkedBoxes.length === 0) {
            alert('Please select at least one request');
            return;
        }
        
        if (!confirm(`Are you sure you want to delete ${checkedBoxes.length} request(s)? This action cannot be undone.`)) {
            return;
        }
        
        const ids = Array.from(checkedBoxes).map(cb => cb.value);
        const form = document.getElementById('bulkActionForm');
        
        // Clear previous inputs
        form.querySelectorAll('input[name="ids[]"]').forEach(input => input.remove());
        
        // Add hidden inputs for IDs
        ids.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'ids[]';
            input.value = id;
            form.appendChild(input);
        });
        
        form.action = '{{ route("admin.custom-tour-requests.bulk-delete") }}';
        form.submit();
    }

    // Auto-submit status form with loading state
    document.querySelectorAll('.status-form select').forEach(select => {
        select.addEventListener('change', function() {
            const form = this.closest('form');
            this.disabled = true;
            
            // Create loading indicator
            const loadingSpan = document.createElement('span');
            loadingSpan.className = 'text-xs text-gray-500 ml-2';
            loadingSpan.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            this.parentNode.appendChild(loadingSpan);
            
            form.submit();
        });
    });
</script>
@endpush
@endsection