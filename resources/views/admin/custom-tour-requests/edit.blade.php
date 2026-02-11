@extends('layouts.admin')

@section('title', 'Edit Tour Request - ' . $customTourRequest->reference_number)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 p-4 md:p-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-4">
                    <a href="{{ route('admin.custom-tour-requests.show', $customTourRequest) }}" 
                       class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-200 shadow-sm">
                        <i class="fas fa-arrow-left text-sm"></i>
                        Back to Details
                    </a>
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Edit Tour Request</h1>
                        <div class="flex items-center gap-2 mt-2">
                            <span class="px-3 py-1 bg-gradient-to-r from-indigo-100 to-indigo-200 text-indigo-800 rounded-full text-sm font-medium">
                                {{ $customTourRequest->reference_number }}
                            </span>
                            <span class="text-gray-500">•</span>
                            <span class="text-sm text-gray-500">Editing request details</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Form -->
    <form method="POST" action="{{ route('admin.custom-tour-requests.update', $customTourRequest) }}" id="editForm">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column - Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Status Update Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-indigo-50 to-white">
                        <div class="flex items-center gap-2">
                            <div class="p-2 bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-lg">
                                <i class="fas fa-flag text-white text-sm"></i>
                            </div>
                            <h2 class="text-lg font-semibold text-gray-800">Request Status</h2>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="max-w-md">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <select name="status" 
                                        class="w-full rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 focus:ring-opacity-50 py-3 px-4 appearance-none bg-white @error('status') border-red-300 @enderror"
                                        required>
                                    @foreach($statuses as $value => $label)
                                        <option value="{{ $value }}" 
                                                {{ old('status', $customTourRequest->status) == $value ? 'selected' : '' }}
                                                class="status-option-{{ $value }}">
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400"></i>
                                </div>
                            </div>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <div class="mt-3">
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <i class="fas fa-info-circle text-indigo-500"></i>
                                    <span>Current status: <span class="font-semibold">{{ $customTourRequest->status_label }}</span></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Admin Notes Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-yellow-50 to-white">
                        <div class="flex items-center gap-2">
                            <div class="p-2 bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-lg">
                                <i class="fas fa-sticky-note text-white text-sm"></i>
                            </div>
                            <h2 class="text-lg font-semibold text-gray-800">Admin Notes</h2>
                        </div>
                    </div>
                    <div class="p-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Internal Notes
                            </label>
                            <textarea name="admin_notes" 
                                      class="w-full rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 focus:ring-opacity-50 py-3 px-4 min-h-[200px] @error('admin_notes') border-red-300 @enderror"
                                      rows="8"
                                      placeholder="Add internal notes about this request...">{{ old('admin_notes', $customTourRequest->admin_notes) }}</textarea>
                            @error('admin_notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <div class="mt-3">
                                <div class="flex items-start gap-2 text-sm text-gray-500">
                                    <i class="fas fa-lock mt-0.5 text-gray-400"></i>
                                    <span>These notes are only visible to admins and will not be shared with the customer.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Read-Only Information Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <div class="flex items-center gap-2">
                            <div class="p-2 bg-gradient-to-r from-gray-500 to-gray-600 rounded-lg">
                                <i class="fas fa-info-circle text-white text-sm"></i>
                            </div>
                            <h2 class="text-lg font-semibold text-gray-800">Request Details (Read-Only)</h2>
                        </div>
                    </div>
                    <div class="p-6">
                        <!-- Info Alert -->
                        <div class="mb-6 p-4 bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200 rounded-lg">
                            <div class="flex items-start gap-3">
                                <i class="fas fa-info-circle text-blue-500 mt-0.5"></i>
                                <div>
                                    <p class="text-sm text-blue-800">
                                        The information below is read-only. To modify customer details or request information, 
                                        please contact the development team.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Customer Information -->
                        <div class="mb-8">
                            <h3 class="text-md font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-100">
                                <i class="fas fa-user text-indigo-500 mr-2"></i>Customer Information
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Name</label>
                                    <p class="text-gray-800 font-medium">{{ $customTourRequest->name }}</p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Email</label>
                                    <p class="text-gray-800 font-medium">{{ $customTourRequest->email }}</p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Phone</label>
                                    <p class="text-gray-800 font-medium">{{ $customTourRequest->phone ?? 'N/A' }}</p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Country</label>
                                    <p class="text-gray-800 font-medium">{{ $customTourRequest->country ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Travel Details -->
                        <div class="mb-8">
                            <h3 class="text-md font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-100">
                                <i class="fas fa-plane text-green-500 mr-2"></i>Travel Details
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Travel Dates</label>
                                    <div class="flex items-center gap-2">
                                        <p class="text-gray-800 font-medium">
                                            {{ $customTourRequest->travel_dates_formatted ?? 'Not specified' }}
                                        </p>
                                        @if($customTourRequest->flexible_dates)
                                            <span class="px-2 py-1 bg-gradient-to-r from-yellow-100 to-yellow-200 text-yellow-800 rounded-full text-xs font-medium">
                                                Flexible
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Duration</label>
                                    <p class="text-gray-800 font-medium">{{ $customTourRequest->duration ?? 'N/A' }}</p>
                                </div>
                                <div class="md:col-span-2 bg-gray-50 rounded-lg p-4">
                                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Travelers</label>
                                    <div class="flex items-center gap-6">
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 rounded-full bg-gradient-to-r from-blue-100 to-blue-200 flex items-center justify-center">
                                                <i class="fas fa-user text-blue-600 text-sm"></i>
                                            </div>
                                            <span class="text-gray-800">{{ $customTourRequest->adults_count }} Adults</span>
                                        </div>
                                        @if($customTourRequest->children_count > 0)
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 rounded-full bg-gradient-to-r from-green-100 to-green-200 flex items-center justify-center">
                                                <i class="fas fa-child text-green-600 text-sm"></i>
                                            </div>
                                            <span class="text-gray-800">{{ $customTourRequest->children_count }} Children</span>
                                        </div>
                                        @endif
                                        @if($customTourRequest->infants_count > 0)
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 rounded-full bg-gradient-to-r from-pink-100 to-pink-200 flex items-center justify-center">
                                                <i class="fas fa-baby text-pink-600 text-sm"></i>
                                            </div>
                                            <span class="text-gray-800">{{ $customTourRequest->infants_count }} Infants</span>
                                        </div>
                                        @endif
                                        <div class="ml-auto">
                                            <span class="px-3 py-1 bg-gradient-to-r from-indigo-100 to-indigo-200 text-indigo-800 rounded-full text-sm font-medium">
                                                Total: {{ $customTourRequest->total_travelers }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Preferences -->
                        <div>
                            <h3 class="text-md font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-100">
                                <i class="fas fa-star text-yellow-500 mr-2"></i>Preferences
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Budget</label>
                                    <p class="text-gray-800 font-medium">
                                        {{ $customTourRequest->approximate_budget ?? $customTourRequest->budget_category ?? 'N/A' }}
                                    </p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Accommodation</label>
                                    <p class="text-gray-800 font-medium">{{ $customTourRequest->accommodation_preference ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Sidebar -->
            <div class="space-y-6">
                <!-- Save Actions Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden sticky top-6">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-green-50 to-white">
                        <div class="flex items-center gap-2">
                            <div class="p-2 bg-gradient-to-r from-green-500 to-emerald-600 rounded-lg">
                                <i class="fas fa-save text-white text-sm"></i>
                            </div>
                            <h2 class="text-lg font-semibold text-gray-800">Save Changes</h2>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <button type="submit" 
                                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-lg hover:from-green-600 hover:to-emerald-700 transition-all duration-200 shadow-sm hover:shadow-md">
                                <i class="fas fa-save"></i>
                                Update Request
                            </button>
                            <a href="{{ route('admin.custom-tour-requests.show', $customTourRequest) }}" 
                               class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-200">
                                <i class="fas fa-times"></i>
                                Cancel
                            </a>
                        </div>
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <div class="text-xs text-gray-500">
                                <i class="fas fa-exclamation-triangle text-yellow-500 mr-1"></i>
                                Changes will be saved immediately
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Info Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-indigo-50 to-white">
                        <div class="flex items-center gap-2">
                            <div class="p-2 bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-lg">
                                <i class="fas fa-info-circle text-white text-sm"></i>
                            </div>
                            <h2 class="text-lg font-semibold text-gray-800">Request Info</h2>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div>
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Reference Number</label>
                                <p class="text-lg font-bold text-gray-800">{{ $customTourRequest->reference_number }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Current Status</label>
                                <div class="mt-1">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $customTourRequest->status_class }}">
                                        {{ $customTourRequest->status_label }}
                                    </span>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Submitted</label>
                                    <p class="text-sm font-medium text-gray-800">{{ $customTourRequest->created_at->format('M d, Y') }}</p>
                                    <p class="text-xs text-gray-500">{{ $customTourRequest->created_at->diffForHumans() }}</p>
                                </div>
                                <div>
                                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Last Updated</label>
                                    <p class="text-sm font-medium text-gray-800">{{ $customTourRequest->updated_at->format('M d, Y') }}</p>
                                    <p class="text-xs text-gray-500">{{ $customTourRequest->updated_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tips Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-yellow-50 to-white">
                        <div class="flex items-center gap-2">
                            <div class="p-2 bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-lg">
                                <i class="fas fa-lightbulb text-white text-sm"></i>
                            </div>
                            <h2 class="text-lg font-semibold text-gray-800">Status Guidelines</h2>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <div class="flex items-start gap-2">
                                <div class="w-6 h-6 rounded-full bg-gradient-to-r from-yellow-100 to-yellow-200 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fas fa-star text-yellow-600 text-xs"></i>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-700">New:</span>
                                    <span class="text-sm text-gray-600">Just received request</span>
                                </div>
                            </div>
                            <div class="flex items-start gap-2">
                                <div class="w-6 h-6 rounded-full bg-gradient-to-r from-orange-100 to-orange-200 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fas fa-clock text-orange-600 text-xs"></i>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-700">Pending:</span>
                                    <span class="text-sm text-gray-600">Under review</span>
                                </div>
                            </div>
                            <div class="flex items-start gap-2">
                                <div class="w-6 h-6 rounded-full bg-gradient-to-r from-blue-100 to-blue-200 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fas fa-phone text-blue-600 text-xs"></i>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-700">Contacted:</span>
                                    <span class="text-sm text-gray-600">Customer reached out</span>
                                </div>
                            </div>
                            <div class="flex items-start gap-2">
                                <div class="w-6 h-6 rounded-full bg-gradient-to-r from-indigo-100 to-indigo-200 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fas fa-file-invoice-dollar text-indigo-600 text-xs"></i>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-700">Quoted:</span>
                                    <span class="text-sm text-gray-600">Quote sent to customer</span>
                                </div>
                            </div>
                            <div class="flex items-start gap-2">
                                <div class="w-6 h-6 rounded-full bg-gradient-to-r from-green-100 to-emerald-200 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fas fa-check-circle text-green-600 text-xs"></i>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-700">Booked:</span>
                                    <span class="text-sm text-gray-600">Confirmed booking</span>
                                </div>
                            </div>
                            <div class="flex items-start gap-2">
                                <div class="w-6 h-6 rounded-full bg-gradient-to-r from-red-100 to-red-200 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fas fa-times-circle text-red-600 text-xs"></i>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-700">Cancelled:</span>
                                    <span class="text-sm text-gray-600">Cancelled by customer/admin</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('styles')
<style>
    /* Status option colors */
    .status-option-new {
        background-color: #fef3c7;
        color: #92400e;
    }
    .status-option-pending {
        background-color: #ffedd5;
        color: #9a3412;
    }
    .status-option-quoted {
        background-color: #dbeafe;
        color: #1e40af;
    }
    .status-option-booked {
        background-color: #d1fae5;
        color: #065f46;
    }
    
    /* Custom scrollbar */
    textarea::-webkit-scrollbar {
        width: 8px;
    }
    
    textarea::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 4px;
    }
    
    textarea::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }
    
    textarea::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>
@endpush

@push('scripts')
<script>
    // Confirm before leaving if form is dirty
    let formChanged = false;
    
    const formElements = document.querySelectorAll('#editForm input, #editForm textarea, #editForm select');
    formElements.forEach(element => {
        element.addEventListener('change', () => {
            formChanged = true;
        });
        
        element.addEventListener('input', () => {
            formChanged = true;
        });
    });

    window.addEventListener('beforeunload', (e) => {
        if (formChanged) {
            e.preventDefault();
            e.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
        }
    });

    // Don't warn on form submit
    document.getElementById('editForm').addEventListener('submit', () => {
        formChanged = false;
    });

    // Add loading state on form submit
    document.getElementById('editForm').addEventListener('submit', function(e) {
        const submitBtn = this.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Saving...';
            submitBtn.disabled = true;
        }
    });

    // Status select styling
    const statusSelect = document.querySelector('select[name="status"]');
    if (statusSelect) {
        function updateStatusSelectStyle() {
            const selectedValue = statusSelect.value;
            statusSelect.className = 'w-full rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 focus:ring-opacity-50 py-3 px-4 appearance-none bg-white';
            
            if (selectedValue === 'new') {
                statusSelect.classList.add('bg-yellow-50', 'text-yellow-800', 'border-yellow-200');
            } else if (selectedValue === 'pending') {
                statusSelect.classList.add('bg-orange-50', 'text-orange-800', 'border-orange-200');
            } else if (selectedValue === 'quoted') {
                statusSelect.classList.add('bg-blue-50', 'text-blue-800', 'border-blue-200');
            } else if (selectedValue === 'booked') {
                statusSelect.classList.add('bg-green-50', 'text-green-800', 'border-green-200');
            } else if (selectedValue === 'cancelled') {
                statusSelect.classList.add('bg-red-50', 'text-red-800', 'border-red-200');
            }
        }
        
        statusSelect.addEventListener('change', updateStatusSelectStyle);
        updateStatusSelectStyle(); // Initial call
    }
</script>
@endpush
@endsection