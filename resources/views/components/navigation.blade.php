@php
    $tourCategories = \App\Models\ActivityCategory::where('is_active', true)->get();
    $tours          = \App\Models\Tour::with('itineraries')->where('status', 'published')->get();
    $destinations   = \App\Models\Destination::where('is_active', true)->with('country')->get();
    $activities     = \App\Models\Activity::where('is_active', true)->with(['category', 'destination'])->get();
@endphp

<nav class="bg-white shadow-lg sticky top-0 z-50">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="flex justify-between items-center h-16">

            {{-- LOGO --}}
            <a href="{{ route('index') }}" class="flex items-center gap-2 shrink-0">
                <img class="h-9 w-auto" src="{{ asset('images/logo.png') }}" alt="Calm Africa Safaris">
                <div class="flex flex-col leading-tight">
                    <span class="text-lg font-bold text-green-700">Calm Africa</span>
                    <span class="text-xs font-medium text-gray-500 -mt-0.5">Safaris</span>
                </div>
            </a>

            {{-- DESKTOP NAV (hidden below lg) --}}
            <div class="hidden lg:flex items-center gap-1">

                <a href="{{ route('index') }}"
                   class="px-3 py-2 text-sm font-medium rounded-md transition-colors
                          {{ request()->routeIs('index') ? 'text-green-700 bg-green-50' : 'text-gray-700 hover:text-green-700 hover:bg-gray-50' }}">
                    Home
                </a>

                {{-- Tours --}}
                <div class="relative group">
                    <button class="flex items-center gap-1 px-3 py-2 text-sm font-medium rounded-md transition-colors
                                   {{ request()->routeIs('tours.*') ? 'text-green-700 bg-green-50' : 'text-gray-700 hover:text-green-700 hover:bg-gray-50' }}">
                        Tours
                        <svg class="h-3.5 w-3.5 transition-transform duration-200 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div class="absolute left-0 top-full mt-1 w-80 bg-white rounded-xl shadow-xl border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible group-hover:translate-y-0 translate-y-2 transition-all duration-200 z-50">
                        <div class="p-4 max-h-[75vh] overflow-y-auto">
                            @if($tourCategories->count() > 0)
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">By Category</p>
                            <div class="space-y-1 mb-4">
                                @foreach($tourCategories as $category)
                                    @php $catTours = $tours->where('category_id', $category->id); @endphp
                                    @if($catTours->count() > 0)
                                    <div class="px-3 py-2 hover:bg-green-50 rounded-lg">
                                        <p class="text-sm font-semibold text-gray-800 mb-1">
                                            <i class="{{ $category->icon }} mr-1.5 text-green-600"></i>{{ $category->name }}
                                        </p>
                                        <div class="ml-5 space-y-0.5">
                                            @foreach($catTours->take(5) as $tour)
                                            <a href="{{ route('tours.show', $tour->slug) }}" class="block text-xs text-gray-600 hover:text-green-700 py-0.5">→ {{ $tour->title }}</a>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                            @endif
                            @if($tours->count() > 0)
                            <div class="border-t pt-3">
                                <a href="{{ route('tours.index') }}" class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 hover:text-green-600">All Tours</a>
                                <div class="space-y-1">
                                    @foreach($tours->take(8) as $tour)
                                    <a href="{{ route('tours.show', $tour->slug) }}" class="block px-3 py-1.5 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 rounded-lg transition-colors">
                                        <p class="font-medium truncate">{{ $tour->title }}</p>
                                        <p class="text-xs text-gray-400">{{ $tour->itineraries->count() }} Days Safari{{ $tour->destination ? ' in '.$tour->destination : '' }}</p>
                                    </a>
                                    @endforeach
                                </div>
                                @if($tours->count() > 8)
                                <a href="{{ route('tours.index') }}" class="block mt-2 text-center text-xs text-green-600 hover:text-green-700 font-medium">View All {{ $tours->count() }} Tours →</a>
                                @endif
                            </div>
                            @else
                            <p class="text-center text-gray-400 py-4 text-sm">No tours available yet</p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Destinations --}}
                <div class="relative group">
                    <button class="flex items-center gap-1 px-3 py-2 text-sm font-medium rounded-md transition-colors
                                   {{ request()->routeIs('destinations.*') ? 'text-green-700 bg-green-50' : 'text-gray-700 hover:text-green-700 hover:bg-gray-50' }}">
                        Destinations
                        <svg class="h-3.5 w-3.5 transition-transform duration-200 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div class="absolute left-0 top-full mt-1 w-80 bg-white rounded-xl shadow-xl border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible group-hover:translate-y-0 translate-y-2 transition-all duration-200 z-50">
                        <div class="p-4">
                            @if($destinations->count() > 0)
                            <a href="{{ route('destinations.index') }}" class="block text-center text-sm font-medium text-green-600 hover:text-green-700 mb-3">
                                View All {{ $destinations->count() }} Destinations →
                            </a>
                            <div class="grid grid-cols-2 gap-2 max-h-72 overflow-y-auto">
                                @foreach($destinations as $dest)
                                <a href="{{ route('destinations.show', $dest->slug) }}" class="group/d bg-gray-50 hover:bg-green-50 rounded-lg p-2 transition-colors">
                                    @if($dest->image)
                                    <img src="{{ asset('storage/'.$dest->image) }}" alt="{{ $dest->name }}" class="w-full h-16 object-cover rounded mb-1.5">
                                    @else
                                    <div class="w-full h-16 bg-gradient-to-br from-green-400 to-blue-500 rounded mb-1.5 flex items-center justify-center">
                                        <i class="fas fa-map-marker-alt text-white text-xl"></i>
                                    </div>
                                    @endif
                                    <p class="text-xs font-semibold text-gray-800 group-hover/d:text-green-700 truncate">{{ $dest->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $dest->country->flag_icon ?? '' }} {{ $dest->country->name }}</p>
                                </a>
                                @endforeach
                            </div>
                            @else
                            <p class="text-center text-gray-400 py-4 text-sm">No destinations available yet</p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Activities --}}
                <div class="relative group">
                    <button class="flex items-center gap-1 px-3 py-2 text-sm font-medium rounded-md transition-colors
                                   {{ request()->routeIs('activities.*') ? 'text-green-700 bg-green-50' : 'text-gray-700 hover:text-green-700 hover:bg-gray-50' }}">
                        Activities
                        <svg class="h-3.5 w-3.5 transition-transform duration-200 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div class="absolute left-0 top-full mt-1 w-80 bg-white rounded-xl shadow-xl border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible group-hover:translate-y-0 translate-y-2 transition-all duration-200 z-50">
                        <div class="p-4">
                            @if($activities->count() > 0)
                            <a href="{{ route('activities.index') }}" class="block text-center text-sm font-medium text-green-600 hover:text-green-700 mb-3">
                                View All {{ $activities->count() }} Activities →
                            </a>
                            <div class="space-y-1.5 max-h-72 overflow-y-auto">
                                @foreach($activities->take(8) as $activity)
                                <a href="{{ route('activities.show', $activity->slug) }}" class="group/a flex items-center gap-2.5 p-2 bg-gray-50 hover:bg-green-50 rounded-lg transition-colors">
                                    @if($activity->image)
                                    <img src="{{ asset('storage/'.$activity->image) }}" alt="{{ $activity->name }}" class="w-12 h-12 object-cover rounded shrink-0">
                                    @elseif($activity->icon)
                                    <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-blue-500 rounded shrink-0 flex items-center justify-center">
                                        <i class="{{ $activity->icon }} text-white"></i>
                                    </div>
                                    @else
                                    <div class="w-12 h-12 bg-gray-200 rounded shrink-0 flex items-center justify-center">
                                        <i class="fas fa-hiking text-gray-400"></i>
                                    </div>
                                    @endif
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold text-gray-800 group-hover/a:text-green-700 truncate transition-colors">{{ $activity->name }}</p>
                                        @if($activity->category)
                                        <p class="text-xs text-purple-600 truncate"><i class="{{ $activity->category->icon }} mr-1"></i>{{ $activity->category->name }}</p>
                                        @endif
                                        @if($activity->destination)
                                        <p class="text-xs text-gray-400 truncate"><i class="fas fa-map-marker-alt mr-1"></i>{{ $activity->destination->name }}</p>
                                        @endif
                                    </div>
                                </a>
                                @endforeach
                                @if($activities->count() > 8)
                                <a href="{{ route('activities.index') }}" class="block text-center text-xs text-green-600 hover:text-green-700 font-medium py-1 border-t">+{{ $activities->count() - 8 }} more activities</a>
                                @endif
                            </div>
                            @else
                            <p class="text-center text-gray-400 py-4 text-sm">No activities available yet</p>
                            @endif
                        </div>
                    </div>
                </div>

                <a href="{{ route('gallery.index') }}"
                   class="px-3 py-2 text-sm font-medium rounded-md transition-colors
                          {{ request()->routeIs('gallery.index') ? 'text-green-700 bg-green-50' : 'text-gray-700 hover:text-green-700 hover:bg-gray-50' }}">
                    Gallery
                </a>

                <a href="{{ route('blogs.index') }}"
                   class="px-3 py-2 text-sm font-medium rounded-md transition-colors
                          {{ request()->routeIs('blogs.*') ? 'text-green-700 bg-green-50' : 'text-gray-700 hover:text-green-700 hover:bg-gray-50' }}">
                    Blog
                </a>

                <a href="{{ route('accommodations.index') }}"
                   class="px-3 py-2 text-sm font-medium rounded-md transition-colors
                          {{ request()->routeIs('accommodations.*') ? 'text-green-700 bg-green-50' : 'text-gray-700 hover:text-green-700 hover:bg-gray-50' }}">
                    <i class="fas fa-bed text-green-600"></i>
                </a>

                <a href="{{ route('contact') }}"
                   class="px-3 py-2 text-sm font-medium rounded-md transition-colors
                          {{ request()->routeIs('contact') ? 'text-green-700 bg-green-50' : 'text-gray-700 hover:text-green-700 hover:bg-gray-50' }}">
                    Contact
                </a>

                <a href="{{ route('custom-tour-requests.create') }}"
                   class="ml-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors whitespace-nowrap">
                    <i class="fas fa-map-marked-alt mr-1.5"></i>Plan Custom Tour
                </a>
            </div>

            {{-- HAMBURGER (shown below lg) --}}
            <button id="mobile-toggle"
                    class="lg:hidden flex flex-col justify-center items-center w-9 h-9 gap-1.5  hover:bg-gray-100 transition-colors focus:outline-none"
                    aria-label="Toggle menu" aria-expanded="false">
                <span class="ham-line block w-6 h-0.5 bg-gray-800 transition-all duration-300"></span>
                <span class="ham-line block w-6 h-0.5 bg-gray-800 transition-all duration-300"></span>
                <span class="ham-line block w-6 h-0.5 bg-gray-800 transition-all duration-300"></span>
            </button>
        </div>

        {{-- MOBILE / TABLET DRAWER (shown below lg) --}}
        <div id="mobile-menu"
             class="lg:hidden hidden border-t border-gray-100 bg-white">
            <div class="py-3 space-y-0.5 max-h-[80vh] overflow-y-auto">

                <a href="{{ route('index') }}"
                   class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg mx-2 transition-colors
                          {{ request()->routeIs('index') ? 'bg-green-50 text-green-700 border-l-4 border-green-600' : 'text-gray-700 hover:bg-gray-50 hover:text-green-700' }}">
                    <i class="fas fa-home mr-2"></i> Home
                </a>

                {{-- Mobile: Tours accordion --}}
                <div class="mx-2">
                    <button class="mobile-accordion w-full flex items-center justify-between px-4 py-3 text-sm font-medium rounded-lg transition-colors
                                   {{ request()->routeIs('tours.*') ? 'bg-green-50 text-green-700 border-l-4 border-green-600' : 'text-gray-700 hover:bg-gray-50 hover:text-green-700' }}">
                        <span><i class="fas fa-binoculars mr-2"></i> Tours</span>
                        <svg class="accordion-icon h-4 w-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div class="accordion-panel hidden pl-4 pr-2 pb-2 space-y-0.5">
                        <a href="{{ route('tours.index') }}" class="block px-4 py-2 text-sm text-green-600 font-medium hover:bg-green-50 rounded-lg">View All Tours →</a>
                        @foreach($tours->take(6) as $tour)
                        <a href="{{ route('tours.show', $tour->slug) }}" class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 hover:text-green-700 rounded-lg transition-colors">
                            {{ $tour->title }}
                            <span class="block text-xs text-gray-400">{{ $tour->itineraries->count() }} Days</span>
                        </a>
                        @endforeach
                    </div>
                </div>

                {{-- Mobile: Destinations accordion --}}
                <div class="mx-2">
                    <button class="mobile-accordion w-full flex items-center justify-between px-4 py-3 text-sm font-medium rounded-lg transition-colors
                                   {{ request()->routeIs('destinations.*') ? 'bg-green-50 text-green-700 border-l-4 border-green-600' : 'text-gray-700 hover:bg-gray-50 hover:text-green-700' }}">
                        <span><i class="fas fa-map-marked-alt mr-2"></i>Destinations</span>
                        <svg class="accordion-icon h-4 w-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div class="accordion-panel hidden pl-4 pr-2 pb-2 space-y-0.5">
                        <a href="{{ route('destinations.index') }}" class="block px-4 py-2 text-sm text-green-600 font-medium hover:bg-green-50 rounded-lg">View All Destinations →</a>
                        @foreach($destinations as $dest)
                        <a href="{{ route('destinations.show', $dest->slug) }}" class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 hover:text-green-700 rounded-lg transition-colors">
                            {{ $dest->country->flag_icon ?? '' }} {{ $dest->name }}
                        </a>
                        @endforeach
                    </div>
                </div>

                {{-- Mobile: Activities accordion --}}
                <div class="mx-2">
                    <button class="mobile-accordion w-full flex items-center justify-between px-4 py-3 text-sm font-medium rounded-lg transition-colors
                                   {{ request()->routeIs('activities.*') ? 'bg-green-50 text-green-700 border-l-4 border-green-600' : 'text-gray-700 hover:bg-gray-50 hover:text-green-700' }}">
                        <span><i class="fas fa-running mr-2"></i>Activities</span>
                        <svg class="accordion-icon h-4 w-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div class="accordion-panel hidden pl-4 pr-2 pb-2 space-y-0.5">
                        <a href="{{ route('activities.index') }}" class="block px-4 py-2 text-sm text-green-600 font-medium hover:bg-green-50 rounded-lg">View All Activities →</a>
                        @foreach($activities->take(8) as $activity)
                        <a href="{{ route('activities.show', $activity->slug) }}" class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 hover:text-green-700 rounded-lg transition-colors">
                            {{ $activity->name }}
                            @if($activity->destination)<span class="block text-xs text-gray-400">{{ $activity->destination->name }}</span>@endif
                        </a>
                        @endforeach
                    </div>
                </div>

                <a href="{{ route('gallery.index') }}"
                   class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg mx-2 transition-colors
                          {{ request()->routeIs('gallery.index') ? 'bg-green-50 text-green-700 border-l-4 border-green-600' : 'text-gray-700 hover:bg-gray-50 hover:text-green-700' }}">
                    <i class="fas fa-images"></i> Gallery
                </a>

                <a href="{{ route('blogs.index') }}"
                   class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg mx-2 transition-colors
                          {{ request()->routeIs('blogs.*') ? 'bg-green-50 text-green-700 border-l-4 border-green-600' : 'text-gray-700 hover:bg-gray-50 hover:text-green-700' }}">
                    <i class="fas fa-blog"></i> Blog
                </a>

                <a href="{{ route('accommodations.index') }}"
                   class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg mx-2 transition-colors
                          {{ request()->routeIs('accommodations.*') ? 'bg-green-50 text-green-700 border-l-4 border-green-600' : 'text-gray-700 hover:bg-gray-50 hover:text-green-700' }}">
                    <i class="fas fa-bed"></i> Stays
                </a>

                <a href="{{ route('contact') }}"
                   class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg mx-2 transition-colors
                          {{ request()->routeIs('contact') ? 'bg-green-50 text-green-700 border-l-4 border-green-600' : 'text-gray-700 hover:bg-gray-50 hover:text-green-700' }}">
                    <i class="fas fa-phone"></i> Contact
                </a>

                <div class="px-2 pt-2 pb-1">
                    <a href="{{ route('custom-tour-requests.create') }}"
                       class="flex items-center justify-center gap-2 w-full bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-lg text-sm font-semibold transition-colors">
                        <i class="fas fa-map-marked-alt"></i> Plan Custom Tour
                    </a>
                </div>
            </div>
        </div>

    </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Hamburger toggle ─────────────────────────────────────────
    const toggle     = document.getElementById('mobile-toggle');
    const mobileMenu = document.getElementById('mobile-menu');
    const lines      = toggle.querySelectorAll('.ham-line');

    toggle.addEventListener('click', function () {
        const open = toggle.getAttribute('aria-expanded') === 'true';
        toggle.setAttribute('aria-expanded', !open);
        mobileMenu.classList.toggle('hidden');

        // Animate to X
        if (!open) {
            lines[0].style.cssText = 'transform:translateY(8px) rotate(45deg)';
            lines[1].style.cssText = 'opacity:0';
            lines[2].style.cssText = 'transform:translateY(-8px) rotate(-45deg)';
        } else {
            lines[0].style.cssText = '';
            lines[1].style.cssText = '';
            lines[2].style.cssText = '';
        }
    });

    // ── Mobile accordions ────────────────────────────────────────
    document.querySelectorAll('.mobile-accordion').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const panel = btn.nextElementSibling;
            const icon  = btn.querySelector('.accordion-icon');
            const open  = !panel.classList.contains('hidden');

            // Close all others
            document.querySelectorAll('.accordion-panel').forEach(p => p.classList.add('hidden'));
            document.querySelectorAll('.accordion-icon').forEach(i => i.style.transform = '');

            if (!open) {
                panel.classList.remove('hidden');
                icon.style.transform = 'rotate(180deg)';
            }
        });
    });

    // ── Close mobile menu on resize to desktop ───────────────────
    window.addEventListener('resize', function () {
        if (window.innerWidth >= 1024) {
            mobileMenu.classList.add('hidden');
            toggle.setAttribute('aria-expanded', 'false');
            lines[0].style.cssText = '';
            lines[1].style.cssText = '';
            lines[2].style.cssText = '';
        }
    });
});
</script>