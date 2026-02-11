@extends('layouts.app')

@section('title', 'Request Submitted Successfully')

@section('content')
<div class="bg-gradient-to-br from-green-50 to-blue-50 min-h-screen py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto">
            
            <!-- Success Icon -->
            <div class="text-center mb-8">
                <div class="inline-block bg-green-100 rounded-full p-6 mb-4">
                    <i class="fas fa-check-circle text-green-600 text-6xl"></i>
                </div>
                <h1 class="text-4xl font-bold text-gray-800 mb-2">Request Submitted Successfully!</h1>
                <p class="text-xl text-gray-600">Thank you for choosing us for your safari adventure</p>
            </div>

            <!-- Request Details Card -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
                <div class="border-b border-gray-200 pb-4 mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Your Request Details</h2>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Reference Number</p>
                            <p class="text-2xl font-bold text-green-600">{{ $request->reference_number }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-600">Status</p>
                            <span class="inline-block bg-blue-100 text-blue-800 px-4 py-2 rounded-full text-sm font-semibold">
                                {{ $request->status_label }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                        <i class="fas fa-user text-green-600 mr-2"></i>
                        Contact Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-600">Name:</span>
                            <span class="font-semibold ml-2">{{ $request->name }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Email:</span>
                            <span class="font-semibold ml-2">{{ $request->email }}</span>
                        </div>
                        @if($request->phone)
                        <div>
                            <span class="text-gray-600">Phone:</span>
                            <span class="font-semibold ml-2">{{ $request->phone }}</span>
                        </div>
                        @endif
                        @if($request->country)
                        <div>
                            <span class="text-gray-600">Country:</span>
                            <span class="font-semibold ml-2">{{ $request->country }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Travel Details -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                        <i class="fas fa-calendar text-blue-600 mr-2"></i>
                        Travel Details
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        @if($request->travel_date_from && $request->travel_date_to)
                        <div>
                            <span class="text-gray-600">Travel Dates:</span>
                            <span class="font-semibold ml-2">{{ $request->travel_dates_formatted }}</span>
                        </div>
                        @endif
                        @if($request->duration)
                        <div>
                            <span class="text-gray-600">Duration:</span>
                            <span class="font-semibold ml-2">{{ $request->duration }}</span>
                        </div>
                        @endif
                        <div>
                            <span class="text-gray-600">Travelers:</span>
                            <span class="font-semibold ml-2">{{ $request->total_travelers }} ({{ $request->adults_count }} adults, {{ $request->children_count }} children, {{ $request->infants_count }} infants)</span>
                        </div>
                    </div>
                </div>

                <!-- Selected Destinations -->
                @if($request->destinations_list->count() > 0)
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                        <i class="fas fa-map-marked-alt text-purple-600 mr-2"></i>
                        Selected Destinations ({{ $request->destinations_list->count() }})
                    </h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($request->destinations_list as $destination)
                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                            {{ $destination->name }}
                        </span>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Selected Activities -->
                @if($request->activities_list->count() > 0)
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                        <i class="fas fa-hiking text-orange-600 mr-2"></i>
                        Selected Activities ({{ $request->activities_list->count() }})
                    </h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($request->activities_list as $activity)
                        <span class="bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-sm font-medium">
                            {{ $activity->name }}
                        </span>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Budget -->
                @if($request->budget_category)
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                        <i class="fas fa-dollar-sign text-green-600 mr-2"></i>
                        Budget
                    </h3>
                    <div class="text-sm">
                        <span class="text-gray-600">Category:</span>
                        <span class="font-semibold ml-2 capitalize">{{ $request->budget_category }}</span>
                    </div>
                </div>
                @endif
            </div>

            <!-- What's Next -->
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mb-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                    What Happens Next?
                </h3>
                <ol class="space-y-3 text-gray-700">
                    <li class="flex items-start">
                        <span class="bg-blue-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm font-bold mr-3 mt-0.5 flex-shrink-0">1</span>
                        <span>We'll review your request within <strong>24 hours</strong></span>
                    </li>
                    <li class="flex items-start">
                        <span class="bg-blue-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm font-bold mr-3 mt-0.5 flex-shrink-0">2</span>
                        <span>Our safari experts will create a personalized itinerary for you</span>
                    </li>
                    <li class="flex items-start">
                        <span class="bg-blue-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm font-bold mr-3 mt-0.5 flex-shrink-0">3</span>
                        <span>You'll receive a detailed quote via email at <strong>{{ $request->email }}</strong></span>
                    </li>
                    <li class="flex items-start">
                        <span class="bg-blue-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm font-bold mr-3 mt-0.5 flex-shrink-0">4</span>
                        <span>We'll work together to finalize your perfect safari experience</span>
                    </li>
                </ol>
            </div>

            <!-- Confirmation Email Notice -->
            <div class="bg-green-50 border border-green-200 rounded-xl p-6 mb-6">
                <div class="flex items-start">
                    <i class="fas fa-envelope text-green-600 text-2xl mr-4 mt-1"></i>
                    <div>
                        <h4 class="font-bold text-gray-800 mb-2">Check Your Email</h4>
                        <p class="text-gray-700 text-sm">
                            A confirmation email has been sent to <strong>{{ $request->email }}</strong> with all the details of your request.
                            Please check your spam folder if you don't see it in your inbox.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col md:flex-row gap-4 justify-center">
                <a href="{{ route('index') }}" class="bg-green-600 hover:bg-green-700 text-white px-8 py-4 rounded-lg font-semibold text-center transition shadow-md">
                    <i class="fas fa-home mr-2"></i> Back to Home
                </a>
                <a href="{{ route('destinations.index') }}" class="bg-white hover:bg-gray-50 text-gray-800 border-2 border-gray-300 px-8 py-4 rounded-lg font-semibold text-center transition">
                    <i class="fas fa-compass mr-2"></i> Explore Destinations
                </a>
                <a href="mailto:{{ config('mail.from.address') }}?subject=Custom Tour Request {{ $request->reference_number }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-lg font-semibold text-center transition shadow-md">
                    <i class="fas fa-envelope mr-2"></i> Contact Us
                </a>
            </div>

            <!-- Need Help -->
            <div class="text-center mt-8 text-gray-600">
                <p class="mb-2">Have questions about your request?</p>
                <p class="text-sm">
                    Call us at <a href="tel:+256700000000" class="text-green-600 font-semibold hover:underline">+256 700 000 000</a> or
                    email <a href="mailto:{{ config('mail.from.address') }}" class="text-green-600 font-semibold hover:underline">{{ config('mail.from.address') }}</a>
                </p>
            </div>

        </div>
    </div>
</div>
@endsection