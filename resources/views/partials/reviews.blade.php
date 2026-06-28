@php
    /**
     * Reusable partial:
     * @include('partials.reviews', ['reviews' => $reviews ?? [], 'title' => 'TripAdvisor Reviews', 'subtitle' => '...'])
     *
     * $reviews format:
     * [
     *   ['name' => 'John', 'date' => 'Jan 2026', 'rating' => 5, 'text' => 'Amazing...', 'url' => 'https://...'],
     * ]
     */
    $title = $title ?? 'What Travelers Say';
    $subtitle = $subtitle ?? 'Verified reviews from our guests';
    $reviews = $reviews ?? [];
@endphp

<section class="py-12 sm:py-16 bg-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-6 sm:mb-8">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $title }}</h2>
            <p class="text-gray-600 mt-2 text-sm sm:text-base">{{ $subtitle }}</p>
        </div>

        {{-- CASE A: Local reviews array exists -> render slider cards --}}
        @if(count($reviews) > 0)
            <div class="relative">
                <div id="reviews-slider" class="flex overflow-x-auto snap-x snap-mandatory gap-4 pb-2 scroll-smooth scrollbar-hide">
                    @foreach($reviews as $review)
                        @php
                            $rating = max(1, min(5, (int)($review['rating'] ?? 5)));
                        @endphp

                        <article class="snap-start shrink-0 w-[88%] sm:w-[48%] lg:w-[32%] bg-gray-50 border border-gray-200 rounded-2xl p-4 sm:p-5 shadow-sm">
                            <div class="flex items-start justify-between gap-2 mb-2">
                                <h3 class="font-semibold text-gray-900 text-sm sm:text-base">
                                    {{ $review['name'] ?? 'Guest Traveler' }}
                                </h3>
                                <span class="text-xs text-gray-500 whitespace-nowrap">
                                    {{ $review['date'] ?? '' }}
                                </span>
                            </div>

                            <div class="mb-3 text-green-600 text-sm tracking-wide" aria-label="Rating {{ $rating }} out of 5">
                                @for($i = 1; $i <= 5; $i++)
                                    <span>{{ $i <= $rating ? '★' : '☆' }}</span>
                                @endfor
                            </div>

                            <p class="text-sm text-gray-700 leading-relaxed line-clamp-5">
                                {{ $review['text'] ?? '' }}
                            </p>

                            @if(!empty($review['url']))
                                <a href="{{ $review['url'] }}" target="_blank" rel="noopener noreferrer"
                                   class="inline-block mt-3 text-sm text-green-700 hover:text-green-900 font-medium">
                                    Read full review →
                                </a>
                            @endif
                        </article>
                    @endforeach
                </div>

                @if(count($reviews) > 1)
                    <div class="flex items-center justify-center gap-3 mt-4">
                        <button type="button" id="reviews-prev"
                                class="px-3 py-2 rounded-lg border border-gray-300 text-sm hover:bg-gray-100">
                            Prev
                        </button>
                        <button type="button" id="reviews-next"
                                class="px-3 py-2 rounded-lg border border-gray-300 text-sm hover:bg-gray-100">
                            Next
                        </button>
                    </div>
                @endif
            </div>
        @else
            {{-- CASE B: No local reviews -> fallback to official TripAdvisor widget --}}
            <div class="bg-gray-50 border border-gray-200 rounded-2xl shadow-sm p-4 sm:p-6 flex justify-center">
                <div id="TA_selfserveprop216" class="TA_selfserveprop">
                    <ul id="YZ4o0ykhZIju" class="TA_links eH6XapOG">
                        <li id="aXQNeFyS" class="9ndXKZL">
                            <a target="_blank"
                               href="https://www.tripadvisor.com/Attraction_Review-g293841-d27764793-Reviews-Calm_Africa_Safaris-Kampala_Central_Region.html"
                               rel="noopener noreferrer">
                                <img src="https://www.tripadvisor.com/img/cdsi/img2/branding/v2/Tripadvisor_lockup_horizontal_secondary_registered-11900-2.svg"
                                     alt="TripAdvisor" />
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        @endif
    </div>
</section>

@once
    @push('styles')
        <style>
            .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
            .scrollbar-hide::-webkit-scrollbar { display: none; }
            .line-clamp-5 {
                display: -webkit-box;
                -webkit-line-clamp: 5;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Slider controls (only if local review slider exists)
                const slider = document.getElementById('reviews-slider');
                const prev = document.getElementById('reviews-prev');
                const next = document.getElementById('reviews-next');

                if (slider && prev && next) {
                    const step = () => Math.max(280, Math.floor(slider.clientWidth * 0.9));

                    prev.addEventListener('click', function () {
                        slider.scrollBy({ left: -step(), behavior: 'smooth' });
                    });

                    next.addEventListener('click', function () {
                        slider.scrollBy({ left: step(), behavior: 'smooth' });
                    });
                }
            });
        </script>

        {{-- TripAdvisor script (needed for fallback widget rendering) --}}
        <script async
                src="https://www.jscache.com/wejs?wtype=selfserveprop&amp;uniq=216&amp;locationId=27764793&amp;lang=en_US&amp;rating=true&amp;nreviews=5&amp;writereviewlink=false&amp;popIdx=true&amp;iswide=false&amp;border=true&amp;display_version=2"
                data-loadtrk
                onload="this.loadtrk=true"></script>
    @endpush
@endonce