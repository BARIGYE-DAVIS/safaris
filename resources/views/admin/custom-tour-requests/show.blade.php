@extends('layouts.admin')

@section('title', 'Tour Request Details - ' . $customTourRequest->reference_number)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 p-4 md:p-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-3">
                    <a href="{{ route('admin.custom-tour-requests.index') }}" 
                       class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-200 shadow-sm">
                        <i class="fas fa-arrow-left text-sm"></i>
                        Back to List
                    </a>
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">{{ $customTourRequest->reference_number }}</h1>
                        <div class="flex items-center gap-2 mt-2">
                            <span class="px-3 py-1 rounded-full text-sm font-medium {{ $customTourRequest->status_class }}">
                                <i class="fas {{ $customTourRequest->status_icon }} mr-1"></i>
                                {{ $customTourRequest->status_label }}
                            </span>
                            @if($customTourRequest->isUrgent())
                            <span class="px-3 py-1 bg-gradient-to-r from-red-100 to-red-200 text-red-800 rounded-full text-sm font-medium">
                                <i class="fas fa-exclamation-triangle mr-1"></i> Urgent
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-3 text-gray-600">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-calendar text-gray-400"></i>
                        <span class="text-sm">Submitted {{ $customTourRequest->created_at->format('F j, Y \a\t g:i A') }}</span>
                    </div>
                    <span class="text-gray-300">•</span>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-clock text-gray-400"></i>
                        <span class="text-sm">{{ $customTourRequest->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.custom-tour-requests.edit', $customTourRequest) }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-indigo-500 to-indigo-600 text-white rounded-lg hover:from-indigo-600 hover:to-indigo-700 transition-all duration-200 shadow-sm hover:shadow-md">
                    <i class="fas fa-edit"></i>
                    Edit Request
                </a>
                <button type="button" 
                        onclick="openDeleteModal()"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:from-red-600 hover:to-red-700 transition-all duration-200 shadow-sm hover:shadow-md">
                    <i class="fas fa-trash"></i>
                    Delete
                </button>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Customer Information Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-indigo-50 to-white">
                    <div class="flex items-center gap-2">
                        <div class="p-2 bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-lg">
                            <i class="fas fa-user text-white text-sm"></i>
                        </div>
                        <h2 class="text-lg font-semibold text-gray-800">Customer Information</h2>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Full Name</label>
                            <p class="text-gray-800 font-medium text-lg">{{ $customTourRequest->name }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Email Address</label>
                            <a href="mailto:{{ $customTourRequest->email }}" 
                               class="text-indigo-600 hover:text-indigo-700 font-medium text-lg inline-flex items-center gap-1">
                                <i class="fas fa-envelope text-sm"></i>
                                {{ $customTourRequest->email }}
                            </a>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Phone Number</label>
                            <div class="flex items-center gap-2">
                                @if($customTourRequest->phone)
                                <a href="tel:{{ $customTourRequest->phone }}" 
                                   class="text-gray-800 font-medium text-lg inline-flex items-center gap-1 hover:text-green-600">
                                    <i class="fas fa-phone text-sm"></i>
                                    {{ $customTourRequest->phone }}
                                </a>
                                @else
                                <span class="text-gray-400 font-medium">Not provided</span>
                                @endif
                            </div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Country</label>
                            <p class="text-gray-800 font-medium text-lg">{{ $customTourRequest->country ?? 'Not specified' }}</p>
                        </div>
                        @if($customTourRequest->language)
                        <div class="bg-gray-50 rounded-lg p-4">
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Preferred Language</label>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-language text-indigo-500"></i>
                                <span class="text-gray-800 font-medium">{{ $customTourRequest->language }}</span>
                            </div>
                        </div>
                        @endif
                        @if($customTourRequest->heard_from)
                        <div class="bg-gray-50 rounded-lg p-4">
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Source</label>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-bullhorn text-green-500"></i>
                                <span class="text-gray-800 font-medium">{{ $customTourRequest->heard_from }}</span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Travel Details Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-white">
                    <div class="flex items-center gap-2">
                        <div class="p-2 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg">
                            <i class="fas fa-plane text-white text-sm"></i>
                        </div>
                        <h2 class="text-lg font-semibold text-gray-800">Travel Details</h2>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="bg-blue-50 rounded-lg p-4">
                            <label class="text-xs font-medium text-blue-600 uppercase tracking-wider mb-1">Travel Dates</label>
                            @if($customTourRequest->travel_date_from)
                            <div class="flex items-center gap-2">
                                <i class="fas fa-calendar text-blue-500"></i>
                                <span class="text-gray-800 font-medium">{{ $customTourRequest->travel_dates_formatted }}</span>
                                @if($customTourRequest->flexible_dates)
                                <span class="px-2 py-1 bg-gradient-to-r from-yellow-100 to-yellow-200 text-yellow-800 rounded-full text-xs font-medium">
                                    Flexible
                                </span>
                                @endif
                            </div>
                            @else
                            <p class="text-gray-400">Not specified</p>
                            @endif
                        </div>
                        <div class="bg-blue-50 rounded-lg p-4">
                            <label class="text-xs font-medium text-blue-600 uppercase tracking-wider mb-1">Duration</label>
                            @if($customTourRequest->duration)
                            <div class="flex items-center gap-2">
                                <i class="fas fa-clock text-blue-500"></i>
                                <span class="text-gray-800 font-medium">{{ $customTourRequest->duration }}</span>
                            </div>
                            @else
                            <p class="text-gray-400">Not specified</p>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Travelers -->
                    <div>
                        <label class="text-sm font-medium text-gray-700 mb-3 block">Number of Travelers</label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-white border border-gray-200 rounded-lg p-4 text-center hover:shadow-md transition-shadow duration-200">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-r from-indigo-100 to-indigo-200 flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-user text-indigo-600 text-xl"></i>
                                </div>
                                <p class="text-2xl font-bold text-gray-800">{{ $customTourRequest->adults_count }}</p>
                                <p class="text-sm text-gray-600">Adults</p>
                            </div>
                            @if($customTourRequest->children_count > 0)
                            <div class="bg-white border border-gray-200 rounded-lg p-4 text-center hover:shadow-md transition-shadow duration-200">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-r from-green-100 to-green-200 flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-child text-green-600 text-xl"></i>
                                </div>
                                <p class="text-2xl font-bold text-gray-800">{{ $customTourRequest->children_count }}</p>
                                <p class="text-sm text-gray-600">Children</p>
                            </div>
                            @endif
                            @if($customTourRequest->infants_count > 0)
                            <div class="bg-white border border-gray-200 rounded-lg p-4 text-center hover:shadow-md transition-shadow duration-200">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-r from-pink-100 to-pink-200 flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-baby text-pink-600 text-xl"></i>
                                </div>
                                <p class="text-2xl font-bold text-gray-800">{{ $customTourRequest->infants_count }}</p>
                                <p class="text-sm text-gray-600">Infants</p>
                            </div>
                            @endif
                        </div>
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <div class="text-center">
                                <span class="px-4 py-2 bg-gradient-to-r from-indigo-500 to-indigo-600 text-white rounded-lg font-medium">
                                    Total: {{ $customTourRequest->total_travelers }} Travelers
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Destinations & Activities Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-green-50 to-white">
                    <div class="flex items-center gap-2">
                        <div class="p-2 bg-gradient-to-r from-green-500 to-emerald-600 rounded-lg">
                            <i class="fas fa-map-marked-alt text-white text-sm"></i>
                        </div>
                        <h2 class="text-lg font-semibold text-gray-800">Destinations & Activities</h2>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Destinations -->
                        <div>
                            <label class="text-sm font-medium text-gray-700 mb-3 block">Selected Destinations</label>
                            @if($customTourRequest->destinations_details && $customTourRequest->destinations_details->count() > 0)
                                <div class="space-y-2">
                                    @foreach($customTourRequest->destinations_details as $destination)
                                    <div class="flex items-center gap-3 p-3 bg-gradient-to-r from-indigo-50 to-indigo-100 rounded-lg hover:from-indigo-100 hover:to-indigo-200 transition-all duration-200">
                                        <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center">
                                            <i class="fas fa-map-pin text-indigo-600"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-800">{{ $destination->name }}</p>
                                            @if($destination->country)
                                            <p class="text-xs text-gray-500">{{ $destination->country->name ?? '' }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8 bg-gray-50 rounded-lg">
                                    <i class="fas fa-map text-gray-300 text-3xl mb-2"></i>
                                    <p class="text-gray-500">No destinations selected</p>
                                </div>
                            @endif
                        </div>

                        <!-- Activities -->
                        <div>
                            <label class="text-sm font-medium text-gray-700 mb-3 block">Selected Activities</label>
                            @if($customTourRequest->activities_details && $customTourRequest->activities_details->count() > 0)
                                <div class="space-y-2">
                                    @foreach($customTourRequest->activities_details as $activity)
                                    <div class="flex items-center gap-3 p-3 bg-gradient-to-r from-green-50 to-emerald-100 rounded-lg hover:from-green-100 hover:to-emerald-200 transition-all duration-200">
                                        <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center">
                                            <i class="fas fa-hiking text-green-600"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-800">{{ $activity->name }}</p>
                                            @if($activity->category)
                                            <p class="text-xs text-gray-500">{{ $activity->category->name ?? '' }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8 bg-gray-50 rounded-lg">
                                    <i class="fas fa-hiking text-gray-300 text-3xl mb-2"></i>
                                    <p class="text-gray-500">No activities selected</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Preferences & Requirements Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-yellow-50 to-white">
                    <div class="flex items-center gap-2">
                        <div class="p-2 bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-lg">
                            <i class="fas fa-sliders-h text-white text-sm"></i>
                        </div>
                        <h2 class="text-lg font-semibold text-gray-800">Preferences & Requirements</h2>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Budget -->
                        <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div class="bg-yellow-50 rounded-lg p-4">
                                <label class="text-xs font-medium text-yellow-600 uppercase tracking-wider mb-1">Budget Category</label>
                                <p class="text-gray-800 font-medium">{{ $customTourRequest->budget_category ?? 'Not specified' }}</p>
                            </div>
                            <div class="bg-yellow-50 rounded-lg p-4">
                                <label class="text-xs font-medium text-yellow-600 uppercase tracking-wider mb-1">Approximate Budget</label>
                                <p class="text-gray-800 font-medium">{{ $customTourRequest->approximate_budget ?? 'Not specified' }}</p>
                            </div>
                        </div>

                        <!-- Accommodation -->
                        <div class="md:col-span-2">
                            <div class="bg-gray-50 rounded-lg p-4 mb-4">
                                <label class="text-xs font-medium text-gray-600 uppercase tracking-wider mb-1">Accommodation Preference</label>
                                <p class="text-gray-800 font-medium">{{ $customTourRequest->accommodation_preference ?? 'Not specified' }}</p>
                            </div>
                        </div>

                        <!-- Special Requirements -->
                        @if($customTourRequest->special_requirements && count($customTourRequest->special_requirements) > 0)
                        <div class="md:col-span-2">
                            <label class="text-sm font-medium text-gray-700 mb-3 block">Special Requirements</label>
                            <div class="flex flex-wrap gap-2">
                                @foreach($customTourRequest->special_requirements as $requirement)
                                <span class="px-3 py-2 bg-gradient-to-r from-orange-100 to-orange-200 text-orange-800 rounded-lg text-sm font-medium">
                                    <i class="fas fa-star mr-1"></i>{{ $requirement }}
                                </span>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Dietary Restrictions -->
                        @if($customTourRequest->dietary_restrictions)
                        <div class="bg-green-50 rounded-lg p-4">
                            <label class="text-xs font-medium text-green-600 uppercase tracking-wider mb-1">Dietary Restrictions</label>
                            <div class="flex items-start gap-2">
                                <i class="fas fa-utensils text-green-500 mt-1"></i>
                                <p class="text-gray-800">{{ $customTourRequest->dietary_restrictions }}</p>
                            </div>
                        </div>
                        @endif

                        <!-- Medical Conditions -->
                        @if($customTourRequest->medical_conditions)
                        <div class="bg-red-50 rounded-lg p-4">
                            <label class="text-xs font-medium text-red-600 uppercase tracking-wider mb-1">Medical Conditions</label>
                            <div class="flex items-start gap-2">
                                <i class="fas fa-heartbeat text-red-500 mt-1"></i>
                                <p class="text-gray-800">{{ $customTourRequest->medical_conditions }}</p>
                            </div>
                        </div>
                        @endif

                        <!-- Special Requests -->
                        @if($customTourRequest->special_requests)
                        <div class="md:col-span-2">
                            <div class="bg-blue-50 rounded-lg p-4">
                                <label class="text-xs font-medium text-blue-600 uppercase tracking-wider mb-1">Special Requests</label>
                                <div class="flex items-start gap-2">
                                    <i class="fas fa-comment-alt text-blue-500 mt-1"></i>
                                    <p class="text-gray-800">{{ $customTourRequest->special_requests }}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="space-y-6">
            <!-- Quick Actions Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden sticky top-6">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-indigo-50 to-white">
                    <div class="flex items-center gap-2">
                        <div class="p-2 bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-lg">
                            <i class="fas fa-bolt text-white text-sm"></i>
                        </div>
                        <h2 class="text-lg font-semibold text-gray-800">Quick Actions</h2>
                    </div>
                </div>
                <div class="p-6">
                    <!-- Status Update -->
                    <form method="POST" action="{{ route('admin.custom-tour-requests.update-status', $customTourRequest) }}">
                        @csrf
                        <label class="block text-sm font-medium text-gray-700 mb-2">Update Status</label>
                        <div class="flex gap-2 mb-4">
                            <select name="status" 
                                    class="flex-1 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 focus:ring-opacity-50 py-2.5 px-4 bg-white">
                                @foreach($statuses ?? \App\Models\CustomTourRequest::getStatuses() as $value => $label)
                                <option value="{{ $value }}" {{ $customTourRequest->status == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                                @endforeach
                            </select>
                            <button type="submit" 
                                    class="px-4 py-2.5 bg-gradient-to-r from-indigo-500 to-indigo-600 text-white rounded-lg hover:from-indigo-600 hover:to-indigo-700 transition-all duration-200">
                                Update
                            </button>
                        </div>
                    </form>

                    <!-- Action Buttons -->
                    <div class="space-y-3">
                        <a href="mailto:{{ $customTourRequest->email }}" 
                           class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 border border-indigo-300 text-indigo-600 rounded-lg hover:bg-indigo-50 transition-all duration-200">
                            <i class="fas fa-envelope"></i>
                            Send Email
                        </a>
                        @if($customTourRequest->phone)
                        <a href="tel:{{ $customTourRequest->phone }}" 
                           class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 border border-green-300 text-green-600 rounded-lg hover:bg-green-50 transition-all duration-200">
                            <i class="fas fa-phone"></i>
                            Call Customer
                        </a>
                        @endif
                        <button onclick="copyReference()"
                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 border border-gray-300 text-gray-600 rounded-lg hover:bg-gray-50 transition-all duration-200">
                            <i class="fas fa-copy"></i>
                            Copy Reference
                        </button>
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
                    @if($customTourRequest->admin_notes)
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Existing Notes</label>
                            <div class="bg-gray-50 rounded-lg p-4 max-h-48 overflow-y-auto">
                                @if(isset($formattedAdminNotes) && count($formattedAdminNotes) > 0)
                                    <div class="space-y-3">
                                        @foreach($formattedAdminNotes as $note)
                                        <div class="pb-3 border-b border-gray-100 last:border-0 last:pb-0">
                                            <div class="flex items-center justify-between mb-1">
                                                <span class="text-xs text-gray-500">{{ $note['date'] }} at {{ $note['time'] }}</span>
                                                @if($note['is_urgent'])
                                                <span class="px-2 py-0.5 bg-red-100 text-red-800 text-xs rounded">New</span>
                                                @endif
                                            </div>
                                            <p class="text-gray-700 text-sm">{{ $note['note'] }}</p>
                                        </div>
                                        @endforeach
                                    </div>
                                @else
                                    <pre class="text-sm text-gray-600 whitespace-pre-wrap">{{ $customTourRequest->admin_notes }}</pre>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="mb-4 text-center py-4 bg-gray-50 rounded-lg">
                            <i class="fas fa-sticky-note text-gray-300 text-2xl mb-2"></i>
                            <p class="text-gray-500 text-sm">No notes yet</p>
                        </div>
                    @endif

                    <!-- Add Note Form -->
                    <form method="POST" action="{{ route('admin.custom-tour-requests.add-note', $customTourRequest) }}">
                        @csrf
                        <label class="block text-sm font-medium text-gray-700 mb-2">Add New Note</label>
                        <textarea name="admin_notes" 
                                  class="w-full rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 focus:ring-opacity-50 py-3 px-4 mb-3"
                                  rows="3"
                                  placeholder="Enter your note here..."
                                  required></textarea>
                        <button type="submit" 
                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-yellow-500 to-yellow-600 text-white rounded-lg hover:from-yellow-600 hover:to-yellow-700 transition-all duration-200">
                            <i class="fas fa-plus"></i>
                            Add Note
                        </button>
                    </form>
                </div>
            </div>

            <!-- Request Info Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                    <div class="flex items-center gap-2">
                        <div class="p-2 bg-gradient-to-r from-gray-500 to-gray-600 rounded-lg">
                            <i class="fas fa-info-circle text-white text-sm"></i>
                        </div>
                        <h2 class="text-lg font-semibold text-gray-800">Request Information</h2>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div>
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Reference Number</label>
                            <div class="flex items-center gap-2">
                                <p class="text-lg font-bold text-gray-800">{{ $customTourRequest->reference_number }}</p>
                                <button onclick="copyReference()" class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-copy text-sm"></i>
                                </button>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Created</label>
                                <p class="text-sm font-medium text-gray-800">{{ $customTourRequest->created_at->format('M d, Y') }}</p>
                                <p class="text-xs text-gray-500">{{ $customTourRequest->created_at->format('h:i A') }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Updated</label>
                                <p class="text-sm font-medium text-gray-800">{{ $customTourRequest->updated_at->format('M d, Y') }}</p>
                                <p class="text-xs text-gray-500">{{ $customTourRequest->updated_at->format('h:i A') }}</p>
                            </div>
                        </div>
                        <div class="pt-4 border-t border-gray-100">
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Age</label>
                            <p class="text-sm text-gray-600">{{ $customTourRequest->created_at->diffForHumans() }}</p>
                        </div>
                        @if($customTourRequest->destinations_count > 0 || $customTourRequest->activities_count > 0)
                        <div class="pt-4 border-t border-gray-100">
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Selections</label>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="text-center">
                                    <div class="text-lg font-bold text-indigo-600">{{ $customTourRequest->destinations_count }}</div>
                                    <div class="text-xs text-gray-500">Destinations</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-lg font-bold text-green-600">{{ $customTourRequest->activities_count }}</div>
                                    <div class="text-xs text-gray-500">Activities</div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal (Tailwind CSS) -->
<div id="deleteModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-xl bg-white">
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
            <div class="px-4 py-3">
                <div class="mb-4">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-r from-red-100 to-red-200 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-exclamation-triangle text-red-500 text-2xl"></i>
                    </div>
                    <p class="text-center text-gray-700 mb-2">Are you sure you want to delete this tour request?</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4 mb-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-medium text-gray-500">Reference</label>
                            <p class="font-medium">{{ $customTourRequest->reference_number }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500">Customer</label>
                            <p class="font-medium">{{ $customTourRequest->name }}</p>
                        </div>
                    </div>
                </div>
                <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex items-start gap-2">
                        <i class="fas fa-exclamation-circle text-red-500 mt-0.5"></i>
                        <div>
                            <p class="text-sm font-medium text-red-700">This action cannot be undone!</p>
                            <p class="text-xs text-red-600">All data associated with this request will be permanently deleted.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="flex items-center gap-3 px-4 py-3">
                <button onclick="closeDeleteModal()" 
                        class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 text-sm font-medium rounded-lg hover:bg-gray-300 transition-colors">
                    Cancel
                </button>
                <form method="POST" action="{{ route('admin.custom-tour-requests.destroy', $customTourRequest) }}" class="flex-1">
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
    /* Custom scrollbar for notes */
    .overflow-y-auto::-webkit-scrollbar {
        width: 6px;
    }
    
    .overflow-y-auto::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 3px;
    }
    
    .overflow-y-auto::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }
    
    .overflow-y-auto::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
    
    /* Status badges */
    .status-new {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        color: #92400e;
        border: 1px solid #fbbf24;
    }
    
    .status-contacted {
        background: linear-gradient(135deg, #ffedd5 0%, #fed7aa 100%);
        color: #9a3412;
        border: 1px solid #fb923c;
    }
    
    .status-quoted {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        color: #1e40af;
        border: 1px solid #60a5fa;
    }
    
    .status-booked {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        color: #065f46;
        border: 1px solid #34d399;
    }
    
    .status-cancelled {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: #991b1b;
        border: 1px solid #f87171;
    }
    
    /* Sticky sidebar on large screens */
    @media (min-width: 1024px) {
        .sticky {
            position: sticky;
            top: 1rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Modal Functions
    function openDeleteModal() {
        const modal = document.getElementById('deleteModal');
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
    
    function copyReference() {
        const reference = "{{ $customTourRequest->reference_number }}";
        navigator.clipboard.writeText(reference).then(() => {
            // Show success message
            const button = event.target.closest('button');
            const originalHTML = button.innerHTML;
            button.innerHTML = '<i class="fas fa-check mr-1"></i>Copied!';
            button.classList.remove('text-gray-400');
            button.classList.add('text-green-500');
            
            setTimeout(() => {
                button.innerHTML = originalHTML;
                button.classList.remove('text-green-500');
                button.classList.add('text-gray-400');
            }, 2000);
        }).catch(err => {
            console.error('Failed to copy: ', err);
            alert('Failed to copy reference number');
        });
    }
    
    // Add confirmation for status change if changing to cancelled
    document.querySelector('select[name="status"]')?.addEventListener('change', function(e) {
        if (this.value === 'cancelled') {
            if (!confirm('Are you sure you want to mark this request as cancelled?')) {
                this.value = "{{ $customTourRequest->status }}";
            }
        }
    });
    
    // Auto-expand textarea on focus
    document.querySelector('textarea[name="admin_notes"]')?.addEventListener('focus', function() {
        this.style.minHeight = '100px';
    });
</script>
@endpush
@endsection