@extends('layouts.admin')

@section('title', 'Booking #' . $booking->id . ' - Admin Dashboard')

@section('content')
<div class="container-fluid px-4 py-6">
    <!-- Header Section -->
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <nav class="flex mb-3" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('admin.bookings.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                                </svg>
                                Tour Bookings
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Booking #{{ $booking->id }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Safari Booking Details</h1>
                <p class="text-gray-600">Manage and process this safari booking</p>
            </div>
            <div class="mt-4 sm:mt-0 flex space-x-3">
                <a href="{{ route('admin.bookings.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Bookings
                </a>
            </div>
        </div>
    </div>

    <!-- Status Alert -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-start">
            <svg class="w-5 h-5 text-green-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>
                <p class="font-medium">Success!</p>
                <p class="text-sm">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Tour Information -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Tour Information</h3>
                </div>
                <div class="px-6 py-6">
                    @if($booking->tour)
                        <div class="flex items-start space-x-4">
                            @if($booking->tour->featured_image)
                                <img src="{{ asset('storage/' . $booking->tour->featured_image) }}" 
                                     alt="{{ $booking->tour->title }}" 
                                     class="w-20 h-20 rounded-lg object-cover flex-shrink-0">
                            @else
                                <div class="w-20 h-20 bg-gradient-to-br from-green-400 to-blue-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                            <div class="flex-1">
                                <h4 class="text-xl font-semibold text-gray-900 mb-2">{{ $booking->tour->title }}</h4>
                                <p class="text-gray-600 mb-4">{{ Str::limit($booking->tour->description, 200) }}</p>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="font-medium text-gray-700">Duration:</span>
                                        <span class="text-gray-600">{{ $booking->tour->itineraries->count() ?: 'Multi' }} {{ $booking->tour->itineraries->count() == 1 ? 'Day' : 'Days' }}</span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-700">Category:</span>
                                        <span class="text-gray-600">{{ ucfirst($booking->tour->category) ?? 'Safari' }}</span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-700">Destinations:</span>
                                        <span class="text-gray-600">{{ $booking->tour->destinations ?: 'East Africa' }}</span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-700">Tour ID:</span>
                                        <span class="text-gray-600">#{{ $booking->tour->id }}</span>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <a href="{{ route('tours.show', $booking->tour->slug) }}" target="_blank"
                                       class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800">
                                        View Tour Details
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8 border-2 border-dashed border-gray-300 rounded-lg">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                            </svg>
                            <h4 class="text-lg font-medium text-gray-900 mb-2">Custom Safari Request</h4>
                            <p class="text-gray-600">This is a custom safari booking request without a specific tour package.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Booking Requirements -->
            @if($booking->message)
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Special Requirements & Message</h3>
                </div>
                <div class="px-6 py-6">
                    <div class="bg-gray-50 p-4 rounded-lg border">
                        <div class="prose max-w-none text-gray-800 leading-relaxed whitespace-pre-wrap">{{ $booking->message }}</div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Quick Actions -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Quick Actions</h3>
                </div>
                <div class="px-6 py-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Email Customer -->
                        <a href="mailto:{{ $booking->email }}?subject=Re: Your Safari Booking ({{ $booking->tour ? $booking->tour->title : 'Custom Safari' }})&body=Dear {{ $booking->name }},%0D%0A%0D%0AThank you for your safari booking inquiry.%0D%0A%0D%0ABooking Details:%0D%0ABooking ID: {{ $booking->id }}%0D%0ATravel Date: {{ Carbon\Carbon::parse($booking->travel_date)->format('F d, Y') }}%0D%0AGroup Size: {{ $booking->group_size }}%0D%0A%0D%0A" 
                           class="block p-4 border-2 border-gray-200 rounded-lg hover:border-green-500 hover:bg-green-50 transition-all duration-200 group">
                            <div class="flex items-center mb-3">
                                <svg class="w-8 h-8 text-gray-400 group-hover:text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                <h4 class="text-lg font-semibold text-gray-900 group-hover:text-green-800">Email Customer</h4>
                            </div>
                            <p class="text-sm text-gray-600 group-hover:text-green-700">
                                Send a personalized email response to {{ $booking->email }}
                            </p>
                        </a>

                        <!-- WhatsApp Contact -->
                        <a href="https://wa.me/{{ preg_replace('/[^0-9+]/', '', $booking->whatsapp) }}?text=Hi {{ $booking->name }}! Thank you for your safari booking inquiry (Booking ID: {{ $booking->id }}). I'm reaching out to discuss your {{ $booking->tour ? $booking->tour->title : 'custom safari' }} request for {{ Carbon\Carbon::parse($booking->travel_date)->format('F d, Y') }}." 
                           class="block p-4 border-2 border-gray-200 rounded-lg hover:border-green-500 hover:bg-green-50 transition-all duration-200 group">
                            <div class="flex items-center mb-3">
                                <svg class="w-8 h-8 text-gray-400 group-hover:text-green-600 mr-3" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                                </svg>
                                <h4 class="text-lg font-semibold text-gray-900 group-hover:text-green-800">WhatsApp Chat</h4>
                            </div>
                            <p class="text-sm text-gray-600 group-hover:text-green-700">
                                Start a WhatsApp conversation with {{ $booking->name }}
                            </p>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Customer Information -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Customer Information</h3>
                </div>
                <div class="px-6 py-4 space-y-4">
                    <!-- Avatar & Name -->
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-12 w-12">
                            <div class="h-12 w-12 rounded-full bg-{{ $booking->status === 'pending' ? 'yellow' : 'green' }}-100 flex items-center justify-center">
                                <svg class="w-8 h-8 text-{{ $booking->status === 'pending' ? 'yellow' : 'green' }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-lg font-semibold text-gray-900">{{ $booking->name }}</h4>
                            <p class="text-sm text-gray-500">Booking #{{ $booking->id }}</p>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-gray-400 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-gray-700">Email</p>
                            <a href="mailto:{{ $booking->email }}" class="text-sm text-blue-600 hover:text-blue-800 break-all">
                                {{ $booking->email }}
                            </a>
                        </div>
                    </div>

                    <!-- Country -->
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-gray-400 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-gray-700">Country</p>
                            <p class="text-sm text-gray-600">{{ $booking->country }}</p>
                        </div>
                    </div>

                    <!-- WhatsApp -->
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-gray-400 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-gray-700">WhatsApp</p>
                            <a href="https://wa.me/{{ preg_replace('/[^0-9+]/', '', $booking->whatsapp) }}" class="text-sm text-green-600 hover:text-green-800">
                                {{ $booking->whatsapp }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking Details -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Booking Details</h3>
                </div>
                <div class="px-6 py-4 space-y-4">
                    <!-- Travel Date -->
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-gray-400 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-gray-700">Travel Date</p>
                            <p class="text-sm text-gray-600">{{ Carbon\Carbon::parse($booking->travel_date)->format('F d, Y') }}</p>
                            <p class="text-xs text-gray-500">{{ Carbon\Carbon::parse($booking->travel_date)->diffForHumans() }}</p>
                        </div>
                    </div>

                    <!-- Group Size -->
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-gray-400 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-gray-700">Group Size</p>
                            <p class="text-sm text-gray-600">{{ $booking->group_size }}</p>
                        </div>
                    </div>

                    <!-- Total Cost -->
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-gray-400 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-gray-700">Total Cost</p>
                            @if($booking->total_cost)
                                <p class="text-sm font-semibold text-green-600">${{ number_format($booking->total_cost) }}</p>
                            @else
                                <p class="text-sm text-gray-500 italic">To be determined</p>
                            @endif
                        </div>
                    </div>

                    <!-- Booking Date -->
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-gray-400 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-gray-700">Booking Date</p>
                            <p class="text-sm text-gray-600">{{ $booking->created_at->format('F d, Y H:i A') }}</p>
                            <p class="text-xs text-gray-500">{{ $booking->created_at->diffForHumans() }}</p>
                        </div>
                    </div>

                    <!-- Current Status -->
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-gray-400 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-gray-700">Status</p>
                            @switch($booking->status)
                                @case('pending')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Pending
                                    </span>
                                    @break
                                @case('confirmed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Confirmed
                                    </span>
                                    @break
                                @case('cancelled')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        Cancelled
                                    </span>
                                    @break
                                @case('completed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Completed
                                    </span>
                                    @break
                            @endswitch
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Booking Actions</h3>
                </div>
                <div class="px-6 py-4 space-y-3">
                    <!-- Status Update -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Update Status</label>
                        <form id="statusUpdateForm" class="flex space-x-2">
                            @csrf
                            @method('PUT')
                            <select name="status" id="statusSelect" class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 text-sm">
                                <option value="pending" {{ $booking->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ $booking->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="cancelled" {{ $booking->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                <option value="completed" {{ $booking->status === 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                            <button type="button" id="updateStatusBtn" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Update
                            </button>
                        </form>
                    </div>

                    <!-- Delete Booking -->
                    <form action="{{ route('admin.bookings.destroy', $booking->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this booking? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full flex items-center justify-center px-4 py-2 border border-red-300 rounded-md shadow-sm bg-white text-sm font-medium text-red-700 hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Delete Booking
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Status update functionality
    const updateStatusBtn = document.getElementById('updateStatusBtn');
    const statusSelect = document.getElementById('statusSelect');
    const originalStatus = '{{ $booking->status }}';

    if (updateStatusBtn && statusSelect) {
        updateStatusBtn.addEventListener('click', function() {
            const newStatus = statusSelect.value;
            
            if (newStatus === originalStatus) {
                alert('Status is already set to ' + newStatus);
                return;
            }

            if (!confirm('Are you sure you want to change the status to "' + newStatus + '"?')) {
                statusSelect.value = originalStatus;
                return;
            }

            // Show loading state
            updateStatusBtn.disabled = true;
            updateStatusBtn.textContent = 'Updating...';

            fetch(`/admin/bookings/{{ $booking->id }}/status`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    status: newStatus
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Failed to update status. Please try again.');
                    statusSelect.value = originalStatus;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
                statusSelect.value = originalStatus;
            })
            .finally(() => {
                updateStatusBtn.disabled = false;
                updateStatusBtn.textContent = 'Update';
            });
        });
    }
});
</script>
@endpush
@endsection