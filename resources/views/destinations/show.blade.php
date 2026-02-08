@extends('layouts.app')

@section('title', $destination->name)

@section('content')
<!-- Hero Section with Image -->
<div class="relative h-96 bg-gray-900">
    @if($destination->image)
        <img src="{{ asset('storage/' . $destination->image) }}" alt="{{ $destination->name }}" class="w-full h-full object-cover opacity-70">
    @else
        <div class="w-full h-full bg-gradient-to-r from-indigo-600 to-purple-600"></div>
    @endif
    <div class="absolute inset-0 flex items-center justify-center">
        <div class="text-center text-white">
            <div class="mb-4">
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-white/20 backdrop-blur-sm">
                    {{ $destination->country->flag_icon }} {{ $destination->country->name }}
                </span>
                @if($destination->is_popular)
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-yellow-500/80 text-white ml-2">
                        ⭐ Popular
                    </span>
                @endif
            </div>
            <h1 class="text-5xl font-bold mb-4">{{ $destination->name }}</h1>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <!-- Description -->
            <div class="bg-white rounded-xl shadow-md p-8 mb-8">
                <h2 class="text-3xl font-bold text-gray-800 mb-4">About {{ $destination->name }}</h2>
                <div class="text-gray-700 leading-relaxed text-lg">
                    {{ $destination->description }}
                </div>
            </div>

            <!-- Activities at this Destination -->
            @if($destination->activities->count() > 0)
            <div class="bg-white rounded-xl shadow-md p-8">
                <h2 class="text-3xl font-bold text-gray-800 mb-6">
                    <i class="fas fa-hiking text-green-600 mr-2"></i>
                    Activities at {{ $destination->name }}
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($destination->activities as $activity)
                    <a href="{{ route('activities.show', $activity->slug) }}" class="group">
                        <div class="border border-gray-200 rounded-lg p-5 hover:border-green-500 hover:shadow-lg transition">
                            <div class="flex items-start gap-4">
                                @if($activity->icon)
                                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center text-green-600 text-xl flex-shrink-0">
                                        <i class="{{ $activity->icon }}"></i>
                                    </div>
                                @elseif($activity->image)
                                    <img src="{{ asset('storage/' . $activity->image) }}" alt="{{ $activity->name }}" class="w-12 h-12 object-cover rounded-lg flex-shrink-0">
                                @endif
                                <div class="flex-1">
                                    <h3 class="font-bold text-gray-800 mb-1 group-hover:text-green-600 transition">
                                        {{ $activity->name }}
                                    </h3>
                                    @if($activity->category)
                                        <span class="inline-block px-2 py-1 bg-purple-100 text-purple-800 text-xs rounded-full mb-2">
                                            {{ $activity->category->name }}
                                        </span>
                                    @endif
                                    <p class="text-gray-600 text-sm line-clamp-2">
                                        {{ $activity->description }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @else
            <div class="bg-white rounded-xl shadow-md p-8 text-center">
                <i class="fas fa-info-circle text-gray-300 text-5xl mb-4"></i>
                <p class="text-gray-500">No activities listed for this destination yet.</p>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Quick Info Card -->
            <div class="bg-white rounded-xl shadow-md p-6 mb-6 sticky top-4">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Quick Info</h3>
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-map-marker-alt text-indigo-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Country</p>
                            <p class="font-semibold text-gray-800">{{ $destination->country->flag_icon }} {{ $destination->country->name }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-hiking text-green-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Activities</p>
                            <p class="font-semibold text-gray-800">{{ $destination->activities->count() }} available</p>
                        </div>
                    </div>
                    @if($destination->is_popular)
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-star text-yellow-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Status</p>
                            <p class="font-semibold text-gray-800">Popular Destination</p>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- CTA Button -->
                <div class="mt-6 pt-6 border-t">
                    <a href="#" class="block w-full bg-indigo-600 hover:bg-indigo-700 text-white text-center py-3 rounded-lg font-semibold transition">
                        Book Now
                    </a>
                    <a href="#" class="block w-full mt-2 border-2 border-indigo-600 text-indigo-600 hover:bg-indigo-50 text-center py-3 rounded-lg font-semibold transition">
                        Contact Us
                    </a>
                </div>
            </div>

            <!-- Related Destinations -->
            @if($relatedDestinations->count() > 0)
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">More in {{ $destination->country->name }}</h3>
                <div class="space-y-4">
                    @foreach($relatedDestinations as $related)
                    <a href="{{ route('destinations.show', $related->slug) }}" class="block group">
                        <div class="flex gap-3 hover:bg-gray-50 p-2 rounded-lg transition">
                            @if($related->image)
                                <img src="{{ asset('storage/' . $related->image) }}" alt="{{ $related->name }}" class="w-16 h-16 object-cover rounded-lg">
                            @else
                                <div class="w-16 h-16 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-mountain text-white"></i>
                                </div>
                            @endif
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-800 group-hover:text-indigo-600 transition">{{ $related->name }}</h4>
                                <p class="text-sm text-gray-500 line-clamp-1">{{ Str::limit($related->description, 50) }}</p>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection