<?php $__env->startSection('title', 'East Africa Safari Destinations 2026 | National Parks Uganda, Kenya, Tanzania & Rwanda'); ?>
<?php $__env->startSection('meta_description', 'Explore East Africa\'s best safari destinations — Queen Elizabeth, Murchison Falls, Serengeti, Masai Mara, Volcanoes National Park and more. Compare parks, wildlife and best travel seasons across Uganda, Kenya, Tanzania and Rwanda.'); ?>
<?php $__env->startSection('meta_keywords', 'East Africa safari destinations, best safari destinations Africa, Uganda national parks, Kenya wildlife parks, Tanzania safari destinations, Rwanda national parks, Queen Elizabeth National Park, Murchison Falls National Park, Serengeti National Park, Masai Mara, Volcanoes National Park Rwanda, Bwindi Impenetrable Forest, Kidepo Valley National Park, Kibale National Park, best national parks East Africa, Africa wildlife parks 2026'); ?>
<?php $__env->startPush('head'); ?>



<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "WebPage",
      "@id": "{{ url()->current() }}",
      "name": "Best Safari Destinations in East Africa",
      "description": "Discover East Africa's finest safari destinations across Uganda, Kenya, Tanzania and Rwanda. Expertly curated wildlife experiences for international travellers.",
      "url": "{{ url()->current() }}",
      "inLanguage": "en",
      "publisher": {
        "@type": "TravelAgency",
        "name": "Calm Africa Safaris",
        "url": "{{ url('/') }}",
        "telephone": "+256752088768",
        "email": "calmafricasafaris@gmail.com",
        "address": {
          "@type": "PostalAddress",
          "addressCountry": "UG",
          "addressLocality": "Kampala"
        },
        "areaServed": ["Uganda","Kenya","Tanzania","Rwanda","East Africa"],
        "currenciesAccepted": "USD",
        "priceRange": "$$-$$$"
      }
    },
    {
      "@type": "BreadcrumbList",
      "itemListElement": [
        {
          "@type": "ListItem",
          "position": 1,
          "name": "Home",
          "item": "{{ url('/') }}"
        },
        {
          "@type": "ListItem",
          "position": 2,
          "name": "Safari Destinations",
          "item": "{{ url()->current() }}"
        }
      ]
    },
    {
      "@type": "FAQPage",
      "mainEntity": [
        {
          "@type": "Question",
          "name": "What are the best safari destinations in East Africa?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "The best safari destinations in East Africa include Uganda's Bwindi Impenetrable Forest for gorilla trekking, Tanzania's Serengeti for the Great Migration, Kenya's Masai Mara for Big Five wildlife, and Rwanda's Volcanoes National Park for mountain gorillas."
          }
        },
        {
          "@type": "Question",
          "name": "When is the best time to visit East Africa for a safari?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "The best time for an East Africa safari is during the dry seasons: June to October and January to February. The Great Migration in the Serengeti peaks July to September. Gorilla trekking in Uganda and Rwanda is possible year-round."
          }
        },
        {
          "@type": "Question",
          "name": "How do I get to East Africa for a safari from the USA or UK?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "From the USA, fly into Entebbe (Uganda), Nairobi (Kenya) or Kilimanjaro/Dar es Salaam (Tanzania) via major hubs like London, Dubai or Doha. From the UK, direct flights are available to Nairobi and Entebbe. Flight times are approximately 9–13 hours."
          }
        },
        {
          "@type": "Question",
          "name": "Is East Africa safe for international tourists?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Yes, Uganda, Kenya, Tanzania and Rwanda are considered safe safari destinations for international tourists. All four countries have well-established tourism infrastructure, and national parks are managed with ranger-guided access for visitor safety."
          }
        },
        {
          "@type": "Question",
          "name": "What wildlife can I see on an East African safari?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "East Africa is home to the Big Five (lion, leopard, elephant, buffalo and rhino), mountain gorillas, chimpanzees, zebra, wildebeest, hippos, crocodiles, giraffe and hundreds of bird species. The Serengeti hosts the world-famous Great Migration of over 1.5 million wildebeest."
          }
        }
      ]
    }
  ]
}
</script>



<?php $__env->startSection('content'); ?>


<div class="relative hero-section bg-gray-900 overflow-hidden" role="banner">
    <div class="slideshow-container absolute inset-0">
        <?php
            $heroDestinations = \App\Models\Destination::where('is_active', true)
                ->whereNotNull('featured_image')
                ->orWhereNotNull('image')
                ->inRandomOrder()
                ->limit(5)
                ->get();

            if($heroDestinations->isEmpty()) {
                $heroDestinations = $popularDestinations->take(5);
            }
        ?>

        <?php $__currentLoopData = $heroDestinations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $heroDestination): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="slide <?php echo e($index === 0 ? 'slide-active' : ''); ?> absolute inset-0">
            <div class="parallax-bg absolute inset-0" style="top: -30%; height: 160%;">
                <?php if($heroDestination->featured_image): ?>
                    <img src="<?php echo e(asset('storage/' . $heroDestination->featured_image)); ?>"
                         alt="Safari at <?php echo e($heroDestination->name); ?>, <?php echo e($heroDestination->country->name ?? 'East Africa'); ?> — wildlife destination for international tourists"
                         class="parallax-img w-full h-full object-cover">
                <?php elseif($heroDestination->image): ?>
                    <img src="<?php echo e(asset('storage/' . $heroDestination->image)); ?>"
                         alt="Safari at <?php echo e($heroDestination->name); ?>, <?php echo e($heroDestination->country->name ?? 'East Africa'); ?> — wildlife destination for international tourists"
                         class="parallax-img w-full h-full object-cover">
                <?php else: ?>
                    <div class="w-full h-full bg-gradient-to-br from-green-600 via-teal-600 to-blue-600"></div>
                <?php endif; ?>
            </div>
            <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/50 to-black/20"></div>

            <div class="absolute inset-0 flex flex-col justify-end">
                <div class="container mx-auto px-4 pb-12 sm:pb-16 md:pb-20">
                    <div class="max-w-2xl">
                        <div class="flex flex-wrap items-center gap-2 mb-3">
                            <span class="bg-green-600/90 backdrop-blur-sm text-white px-3 py-1.5 rounded-full text-xs sm:text-sm font-semibold">
                                <?php echo e($heroDestination->country->flag_icon ?? '🌍'); ?> <?php echo e($heroDestination->country->name ?? 'East Africa'); ?>

                            </span>
                            <?php if($heroDestination->type): ?>
                            <span class="bg-white/20 backdrop-blur-sm text-white px-3 py-1.5 rounded-full text-xs sm:text-sm font-medium">
                                <?php echo e($heroDestination->type); ?>

                            </span>
                            <?php endif; ?>
                        </div>
                        <h2 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-3 drop-shadow-lg leading-tight">
                            <?php echo e($heroDestination->name); ?>

                        </h2>
                        <p class="text-sm sm:text-base md:text-lg text-gray-200 mb-5 line-clamp-2 max-w-xl">
                            <?php echo e($heroDestination->description); ?>

                        </p>
                        <a href="<?php echo e(route('destinations.show', $heroDestination->slug)); ?>"
                           class="inline-flex items-center bg-green-600 hover:bg-green-700 text-white px-5 py-3 sm:px-7 sm:py-3.5 rounded-lg text-sm sm:text-base font-semibold transition transform hover:scale-105 shadow-lg">
                            Explore <?php echo e($heroDestination->name); ?>

                            <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <button class="slide-control prev absolute left-3 sm:left-5 top-1/2 -translate-y-1/2 bg-black/40 hover:bg-black/60 backdrop-blur-sm text-white p-2.5 sm:p-3.5 rounded-full transition z-10" aria-label="Previous slide">
        <i class="fas fa-chevron-left text-sm sm:text-base text-white"></i>
    </button>
    <button class="slide-control next absolute right-3 sm:right-5 top-1/2 -translate-y-1/2 bg-black/40 hover:bg-black/60 backdrop-blur-sm text-white p-2.5 sm:p-3.5 rounded-full transition z-10" aria-label="Next slide">
        <i class="fas fa-chevron-right text-sm sm:text-base text-white"></i>
    </button>

    <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-2 z-10" role="tablist" aria-label="Slideshow navigation">
        <?php $__currentLoopData = $heroDestinations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $dest): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <button class="slide-indicator h-2 rounded-full bg-white/40 hover:bg-white transition-all duration-300 <?php echo e($index === 0 ? 'active' : ''); ?>"
                data-slide="<?php echo e($index); ?>"
                aria-label="Go to slide <?php echo e($index + 1); ?>: <?php echo e($dest->name); ?>"></button>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div class="absolute bottom-8 right-8 z-10 animate-bounce hidden sm:block" aria-hidden="true">
        <div class="flex flex-col items-center text-white/70 text-xs">
            <svg class="w-5 h-5 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
            </svg>
            Scroll
        </div>
    </div>
</div>


<div class="bg-white border-b border-gray-100 py-6 px-4">
    <div class="container mx-auto max-w-5xl text-center">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">
             East Africa Safari Destinations — Uganda, Kenya, Tanzania &amp; Rwanda National Parks
        </h1>
        <p class="text-gray-600 text-sm sm:text-base max-w-3xl mx-auto leading-relaxed">
         	
          Compare East Africa's finest <strong>safari destinations</strong> across Uganda, Kenya,
		  Tanzania and Rwanda. Each destination page covers wildlife, best time to visit,
		  accommodation options and how to get there — everything international travellers
		  from the USA, UK, Canada, Australia and Europe need to plan their African safari.
		  Looking for gorilla trekking? <a href="/">Visit our gorilla trekking page</a> or
		  browse our <a href="/tours">Uganda safari packages</a>.
         
         </p>
    </div>
</div>


<div class="bg-gray-50 border-b border-gray-200 py-5 px-4">
    <div class="container mx-auto max-w-3xl">
        <form method="GET" action="<?php echo e(route('destinations.index')); ?>"
              class="flex flex-col sm:flex-row gap-2 sm:gap-3"
              role="search"
              aria-label="Search safari destinations">
            <div class="flex-1 relative">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm pointer-events-none" aria-hidden="true"></i>
                <input type="text" name="search"
                       placeholder="Search destinations, parks, countries..."
                       value="<?php echo e(request('search')); ?>"
                       aria-label="Search destinations"
                       class="w-full pl-10 pr-4 py-3 rounded-xl border-2 border-gray-200 focus:border-green-500 focus:outline-none text-gray-800 text-sm sm:text-base bg-white shadow-sm">
            </div>
            <button type="submit"
                    class="bg-green-600 hover:bg-green-700 px-6 py-3 rounded-xl font-semibold transition text-white text-sm sm:text-base whitespace-nowrap flex items-center justify-center gap-2 shadow-sm">
                <i class="fas fa-search" aria-hidden="true"></i>
                <span>Search</span>
            </button>
        </form>
    </div>
</div>


<div class="bg-white shadow-md sticky top-0 z-40 border-b">
    <div class="container mx-auto px-4 py-3 sm:py-4">
        <form method="GET" action="<?php echo e(route('destinations.index')); ?>"
              class="flex flex-wrap gap-2 sm:gap-4 items-center"
              aria-label="Filter destinations">
            <?php if(request('search')): ?>
                <input type="hidden" name="search" value="<?php echo e(request('search')); ?>">
            <?php endif; ?>

            <span class="hidden sm:inline-flex items-center text-gray-500 text-sm font-medium">
                <i class="fas fa-filter mr-2 text-green-600" aria-hidden="true"></i> Filter:
            </span>

            <select name="country"
                    aria-label="Filter by country"
                    class="flex-1 min-w-0 sm:flex-none px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 bg-white"
                    onchange="this.form.submit()">
                <option value="">🌍 All Countries</option>
                <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($country->code); ?>" <?php echo e(request('country') == $country->code ? 'selected' : ''); ?>>
                        <?php echo e($country->flag_icon); ?> <?php echo e($country->name); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>

            <select name="popular"
                    aria-label="Filter by popularity"
                    class="flex-1 min-w-0 sm:flex-none px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 bg-white"
                    onchange="this.form.submit()">
                <option value="">All Destinations</option>
                <option value="1" <?php echo e(request('popular') == '1' ? 'selected' : ''); ?>>⭐ Popular Only</option>
            </select>

            <?php if(request()->hasAny(['search', 'country', 'popular'])): ?>
                <a href="<?php echo e(route('destinations.index')); ?>"
                   class="text-red-600 hover:text-red-800 font-semibold px-3 py-2 text-sm border border-red-200 rounded-lg hover:bg-red-50 transition whitespace-nowrap">
                    <i class="fas fa-times mr-1" aria-hidden="true"></i> Clear
                </a>
            <?php endif; ?>

            <div class="ml-auto text-gray-600 text-xs sm:text-sm font-medium hidden md:block whitespace-nowrap">
                <i class="fas fa-map-marker-alt text-green-600 mr-1" aria-hidden="true"></i>
                <?php echo e($destinations->total()); ?> Destination<?php echo e($destinations->total() != 1 ? 's' : ''); ?> Found
            </div>
        </form>
    </div>
</div>


<?php if(!request()->hasAny(['search', 'country', 'popular'])): ?>
<nav class="bg-white border-b py-4 px-4" aria-label="Browse destinations by country">
    <div class="container mx-auto max-w-5xl">
        <p class="text-xs text-gray-500 uppercase tracking-widest font-semibold mb-3 text-center">Browse by Country</p>
        <div class="flex flex-wrap justify-center gap-2 sm:gap-3">
            <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('destinations.index', ['country' => $country->code])); ?>"
               class="inline-flex items-center gap-2 bg-gray-50 hover:bg-green-50 border border-gray-200 hover:border-green-400 text-gray-700 hover:text-green-700 px-4 py-2 rounded-full text-sm font-semibold transition-all duration-200">
                <span><?php echo e($country->flag_icon); ?></span>
                <span><?php echo e($country->name); ?> Safari</span>
            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</nav>
<?php endif; ?>


<?php if(!request()->hasAny(['search', 'country', 'popular']) && $popularDestinations->count() > 0): ?>
<section class="bg-gradient-to-b from-green-50 to-white py-12 sm:py-16" aria-labelledby="popular-heading">
    <div class="container mx-auto px-4">
        <div class="text-center mb-8 sm:mb-12">
            <span class="inline-block bg-yellow-100 text-yellow-800 px-4 py-2 rounded-full text-xs sm:text-sm font-semibold mb-3">
                ⭐ Most Loved by International Travellers
            </span>
            <h2 id="popular-heading" class="text-3xl sm:text-4xl md:text-5xl font-bold text-gray-800 mb-3">
              Most Visited East Africa National Parks &amp; Wildlife Reserves
            </h2>
            <p class="text-gray-600 text-base sm:text-lg max-w-2xl mx-auto">
                Handpicked favourites by thousands of travellers from the USA, UK, Europe, Australia and Asia
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-8">
            <?php $__currentLoopData = $popularDestinations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $destination): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <article>
                <a href="<?php echo e(route('destinations.show', $destination->slug)); ?>" class="group block h-full">
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden transform transition duration-300 hover:scale-[1.02] hover:shadow-2xl h-full flex flex-col">
                        <div class="relative h-56 sm:h-64 md:h-72 overflow-hidden bg-gray-200 flex-shrink-0">
                            <?php if($destination->featured_image): ?>
                                <img src="<?php echo e(asset('storage/' . $destination->featured_image)); ?>"
                                     alt="<?php echo e($destination->name); ?> safari — wildlife destination in <?php echo e($destination->country->name ?? 'East Africa'); ?> for international tourists"
                                     class="w-full h-full object-cover group-hover:scale-110 transition duration-500"
                                     loading="lazy">
                            <?php elseif($destination->image): ?>
                                <img src="<?php echo e(asset('storage/' . $destination->image)); ?>"
                                     alt="<?php echo e($destination->name); ?> safari — wildlife destination in <?php echo e($destination->country->name ?? 'East Africa'); ?> for international tourists"
                                     class="w-full h-full object-cover group-hover:scale-110 transition duration-500"
                                     loading="lazy">
                            <?php else: ?>
                                <div class="w-full h-full bg-gradient-to-br from-green-400 via-teal-500 to-blue-500 flex items-center justify-center">
                                    <i class="fas fa-map-marker-alt text-white text-6xl opacity-50" aria-hidden="true"></i>
                                </div>
                            <?php endif; ?>

                            <div class="absolute top-3 right-3">
                                <span class="bg-yellow-500 text-white px-2.5 py-1 rounded-full text-xs font-bold shadow-lg flex items-center gap-1">
                                    <i class="fas fa-star text-xs" aria-hidden="true"></i> Popular
                                </span>
                            </div>

                            <?php if($destination->type): ?>
                            <div class="absolute bottom-3 left-3">
                                <span class="bg-white/90 backdrop-blur-sm text-gray-800 px-3 py-1 rounded-full text-xs font-semibold">
                                    <?php echo e($destination->type); ?>

                                </span>
                            </div>
                            <?php endif; ?>

                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        </div>
                        <div class="p-5 flex-1 flex flex-col">
                            <div class="flex items-center justify-between mb-2">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                    <span class="mr-1"><?php echo e($destination->country->flag_icon ?? '🌍'); ?></span>
                                    <?php echo e($destination->country->name ?? 'East Africa'); ?>

                                </span>
                            </div>
                            <h3 class="text-xl sm:text-2xl font-bold text-gray-800 mb-2 group-hover:text-green-600 transition line-clamp-1">
                                <?php echo e($destination->name); ?>

                            </h3>
                            <p class="text-gray-600 text-sm line-clamp-3 mb-4 flex-1">
                                <?php echo e($destination->meta_description ?? $destination->description); ?>

                            </p>
                            <div class="flex items-center justify-between pt-3 border-t mt-auto">
                                <div class="flex items-center text-green-600 font-semibold text-sm">
                                    Explore Now
                                    <i class="fas fa-arrow-right ml-2 group-hover:translate-x-2 transition" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </article>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>
<?php endif; ?>


<?php if(!request()->hasAny(['search', 'country', 'popular'])): ?>
<section class="bg-gray-900 py-10 sm:py-14 px-4" aria-labelledby="visitors-heading">
    <div class="container mx-auto max-w-5xl text-center">
        <h2 id="visitors-heading" class="text-xl sm:text-2xl font-bold text-white mb-2">
            Welcoming Safari Travellers from Across the Globe
        </h2>
        <p class="text-gray-400 text-sm sm:text-base mb-8 max-w-2xl mx-auto">
            Every year we guide wildlife lovers from <strong class="text-green-400">over 40 countries</strong> through East Africa's most iconic landscapes.
            From first-time visitors to seasoned safari-goers, every journey is tailor-made.
        </p>
        <div class="flex flex-wrap justify-center gap-3 sm:gap-4 mb-8">
            <?php
            $visitorCountries = [
                ['flag' => '🇺🇸', 'name' => 'USA'],
                ['flag' => '🇬🇧', 'name' => 'UK'],
                ['flag' => '🇨🇦', 'name' => 'Canada'],
                ['flag' => '🇦🇺', 'name' => 'Australia'],
                ['flag' => '🇩🇪', 'name' => 'Germany'],
                ['flag' => '🇫🇷', 'name' => 'France'],
                ['flag' => '🇳🇱', 'name' => 'Netherlands'],
                ['flag' => '🇮🇹', 'name' => 'Italy'],
                ['flag' => '🇪🇸', 'name' => 'Spain'],
                ['flag' => '🇧🇪', 'name' => 'Belgium'],
                ['flag' => '🇨🇭', 'name' => 'Switzerland'],
                ['flag' => '🇸🇪', 'name' => 'Sweden'],
                ['flag' => '🇳🇴', 'name' => 'Norway'],
                ['flag' => '🇩🇰', 'name' => 'Denmark'],
                ['flag' => '🇯🇵', 'name' => 'Japan'],
                ['flag' => '🇨🇳', 'name' => 'China'],
                ['flag' => '🇦🇪', 'name' => 'UAE'],
                ['flag' => '🇮🇳', 'name' => 'India'],
                ['flag' => '🇧🇷', 'name' => 'Brazil'],
                ['flag' => '🇿🇦', 'name' => 'South Africa'],
            ];
            ?>
            <?php $__currentLoopData = $visitorCountries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="flex items-center gap-1.5 bg-white/10 hover:bg-white/20 transition px-3 py-1.5 rounded-full text-white text-xs sm:text-sm font-medium">
                <span><?php echo e($vc['flag']); ?></span>
                <span><?php echo e($vc['name']); ?></span>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <!-- Key stats -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 sm:gap-6 max-w-3xl mx-auto">
            <div class="text-center">
                <div class="text-3xl sm:text-4xl font-bold text-green-400 mb-1">40+</div>
                <div class="text-gray-400 text-xs sm:text-sm">Countries Served</div>
            </div>
            <div class="text-center">
                <div class="text-3xl sm:text-4xl font-bold text-green-400 mb-1">4</div>
                <div class="text-gray-400 text-xs sm:text-sm">Countries in East Africa</div>
            </div>
            <div class="text-center">
                <div class="text-3xl sm:text-4xl font-bold text-green-400 mb-1"><?php echo e($destinations->total()); ?>+</div>
                <div class="text-gray-400 text-xs sm:text-sm">Safari Destinations</div>
            </div>
            <div class="text-center">
                <div class="text-3xl sm:text-4xl font-bold text-green-400 mb-1">365</div>
                <div class="text-gray-400 text-xs sm:text-sm">Days Open Year-Round</div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>


<?php if(!request()->hasAny(['search', 'country', 'popular'])): ?>
<section class="bg-white py-12 sm:py-16 px-4 border-b" aria-labelledby="why-heading">
    <div class="container mx-auto max-w-6xl">
        <div class="text-center mb-10">
            <h2 id="why-heading" class="text-3xl sm:text-4xl font-bold text-gray-900 mb-3">
              
              What Makes East Africa's Safari Destinations Unique?
    
            </h2>
            <p class="text-gray-600 text-base sm:text-lg max-w-2xl mx-auto">
                East Africa offers wildlife experiences found nowhere else on the planet and is easily accessible for international travellers worldwide.
            </p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="text-center p-6 bg-green-50 rounded-2xl hover:shadow-lg transition group">
                <div class="text-4xl mb-3">🦍</div>
                <h3 class="font-bold text-gray-900 text-base sm:text-lg mb-2">Gorilla Trekking</h3>
                <p class="text-gray-600 text-sm leading-relaxed">
                    Uganda and Rwanda are home to over half the world's remaining <strong>mountain gorillas</strong>.
                    A once-in-a-lifetime experience for visitors from the USA, UK, Europe and beyond.
                </p>
            </div>
            <div class="text-center p-6 bg-yellow-50 rounded-2xl hover:shadow-lg transition group">
                <div class="text-4xl mb-3">🦁</div>
                <h3 class="font-bold text-gray-900 text-base sm:text-lg mb-2">Big Five Safari</h3>
                <p class="text-gray-600 text-sm leading-relaxed">
                    See lion, leopard, elephant, buffalo and rhino in Kenya's <strong>Masai Mara</strong> and
                    Tanzania's <strong>Serengeti</strong>  iconic bucket-list destinations for global travellers.
                </p>
            </div>
            <div class="text-center p-6 bg-blue-50 rounded-2xl hover:shadow-lg transition group">
                <div class="text-4xl mb-3">🐆</div>
                <h3 class="font-bold text-gray-900 text-base sm:text-lg mb-2">Great Migration</h3>
                <p class="text-gray-600 text-sm leading-relaxed">
                    Witness 1.5 million wildebeest cross the Mara River the greatest wildlife spectacle on Earth,
                    drawing international safari tourists every July to October.
                </p>
            </div>
            <div class="text-center p-6 bg-purple-50 rounded-2xl hover:shadow-lg transition group">
                <div class="text-4xl mb-3">🌿</div>
                <h3 class="font-bold text-gray-900 text-base sm:text-lg mb-2">Chimpanzee Tracking</h3>
                <p class="text-gray-600 text-sm leading-relaxed">
                    Trek through Uganda's <strong>Kibale Forest</strong> the world's top destination for
                    chimpanzee tracking, perfect for wildlife enthusiasts from Australia, Canada and Asia.
                </p>
            </div>
        </div>
    </div>
</section>


<section class="bg-gray-50 py-8 px-4  hidden border-b" aria-label="Safari types">
    <div class="container mx-auto max-w-5xl">
        <p class="text-xs text-gray-500 uppercase tracking-widest font-semibold mb-4 text-center">Types of Safari We Offer</p>
        <div class="flex flex-wrap justify-center gap-2 sm:gap-3">
            <?php
            $safariTypes = [
                'Gorilla Trekking Safari', 'Big Five Safari', 'Great Migration Safari',
                'Luxury Safari', 'Budget Safari', 'Family Safari', 'Honeymoon Safari',
                'Chimpanzee Tracking', 'Birding Safari', 'Photography Safari',
                'Cultural Safari', 'Mountain Gorilla Permit', 'Private Safari',
            ];
            ?>
            <?php $__currentLoopData = $safariTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('tours.index', ['search' => $type])); ?>"
               class="inline-block bg-white border border-gray-200 hover:border-green-500 hover:bg-green-50 text-gray-700 hover:text-green-700 px-4 py-2 rounded-full text-xs sm:text-sm font-medium transition-all duration-200 shadow-sm">
                <?php echo e($type); ?>

            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>
<?php endif; ?>


<main id="all-destinations" class="container mx-auto px-4 py-10 sm:py-16">
    <?php if(!request()->hasAny(['search', 'country', 'popular'])): ?>
        <div class="text-center mb-8 sm:mb-12">
            <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold text-gray-800 mb-3">
                All East Africa Safari Destinations &amp; National Parks
            </h2>
            <p class="text-gray-600 text-base sm:text-lg">
                Our complete collection of East Africa wildlife parks, reserves and sanctuaries 
                each one a world-class destination for international safari visitors
            </p>
        </div>
    <?php else: ?>
        <div class="mb-6 sm:mb-8">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-800 mb-2">
                <?php if(request('country')): ?>
                    <span><?php echo e($countries->firstWhere('code', request('country'))->flag_icon ?? ''); ?></span>
                    Safari Destinations in <?php echo e($countries->firstWhere('code', request('country'))->name ?? 'Selected Country'); ?>

                <?php elseif(request('popular')): ?>
                    ⭐ Top-Rated Safari Destinations
                <?php elseif(request('search')): ?>
                    Results for "<?php echo e(request('search')); ?>"
                <?php endif; ?>
            </h2>
            <p class="text-gray-600 text-sm sm:text-base"><?php echo e($destinations->total()); ?> destination(s) found</p>
        </div>
    <?php endif; ?>

    <?php if($destinations->count() > 0): ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
            <?php $__currentLoopData = $destinations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $destination): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <article>
                <a href="<?php echo e(route('destinations.show', $destination->slug)); ?>" class="group block h-full">
                    <div class="bg-white rounded-xl shadow-md overflow-hidden transform transition duration-300 hover:scale-[1.02] hover:shadow-xl h-full flex flex-col">
                        <div class="relative h-48 sm:h-52 md:h-56 overflow-hidden bg-gradient-to-br from-gray-200 to-gray-300 flex-shrink-0">
                            <?php if($destination->featured_image): ?>
                                <img src="<?php echo e(asset('storage/' . $destination->featured_image)); ?>"
                                     alt="<?php echo e($destination->name); ?> — <?php echo e($destination->type ?? 'safari destination'); ?> in <?php echo e($destination->country->name ?? 'East Africa'); ?>"
                                     class="w-full h-full object-cover group-hover:scale-110 transition duration-500"
                                     loading="lazy">
                            <?php elseif($destination->image): ?>
                                <img src="<?php echo e(asset('storage/' . $destination->image)); ?>"
                                     alt="<?php echo e($destination->name); ?> — <?php echo e($destination->type ?? 'safari destination'); ?> in <?php echo e($destination->country->name ?? 'East Africa'); ?>"
                                     class="w-full h-full object-cover group-hover:scale-110 transition duration-500"
                                     loading="lazy">
                            <?php else: ?>
                                <div class="w-full h-full bg-gradient-to-br from-green-400 via-teal-500 to-blue-500 flex items-center justify-center">
                                    <div class="text-center text-white">
                                        <i class="fas fa-map-marked-alt text-4xl sm:text-5xl mb-2 opacity-70" aria-hidden="true"></i>
                                        <p class="text-xs sm:text-sm font-semibold opacity-90 px-2"><?php echo e($destination->name); ?></p>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="absolute top-3 left-3 flex flex-col gap-1.5">
                                <?php if($destination->is_popular): ?>
                                <span class="bg-yellow-500 text-white px-2 py-0.5 rounded-full text-xs font-bold shadow-md">
                                    <i class="fas fa-star text-xs" aria-hidden="true"></i>
                                </span>
                                <?php endif; ?>
                            </div>

                            <?php if($destination->type): ?>
                            <div class="absolute bottom-2 right-2">
                                <span class="bg-white/90 backdrop-blur-sm text-gray-800 px-2.5 py-1 rounded-lg text-xs font-semibold shadow-md">
                                    <?php echo e($destination->type); ?>

                                </span>
                            </div>
                            <?php endif; ?>
                        </div>

                        <div class="p-4 sm:p-5 flex-1 flex flex-col">
                            <div class="flex items-center mb-2">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                    <?php echo e($destination->country->flag_icon ?? '🌍'); ?> <?php echo e($destination->country->name ?? 'East Africa'); ?>

                                </span>
                            </div>
                            <h3 class="text-base sm:text-lg font-bold text-gray-800 mb-2 group-hover:text-green-600 transition line-clamp-2">
                                <?php echo e($destination->name); ?>

                            </h3>
                            <p class="text-gray-600 text-xs sm:text-sm line-clamp-3 mb-3 flex-1">
                                <?php echo e(Str::limit($destination->meta_description ?? $destination->description, 100)); ?>

                            </p>
                            <div class="flex items-center text-green-600 text-xs sm:text-sm font-semibold pt-3 border-t mt-auto">
                                View Details
                                <i class="fas fa-arrow-right ml-2 group-hover:translate-x-2 transition" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </article>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="mt-8 sm:mt-12" id="pagination-links">
            <?php echo e($destinations->appends(request()->query())->links()); ?>

        </div>
    <?php else: ?>
        <div class="text-center py-16 sm:py-20 bg-white rounded-2xl shadow-md">
            <div class="inline-block p-6 sm:p-8 bg-gray-100 rounded-full mb-5">
                <i class="fas fa-search text-gray-400 text-5xl sm:text-6xl" aria-hidden="true"></i>
            </div>
            <h3 class="text-2xl sm:text-3xl font-bold text-gray-700 mb-3">No destinations found</h3>
            <p class="text-gray-500 mb-6 max-w-md mx-auto text-sm sm:text-base px-4">
                We couldn't find any destinations matching your criteria. Try adjusting your filters or browse all East Africa safari destinations below.
            </p>
            <a href="<?php echo e(route('destinations.index')); ?>"
               class="bg-green-600 hover:bg-green-700 text-white px-7 py-3.5 rounded-lg inline-flex items-center font-semibold transition shadow-md text-sm sm:text-base">
                <i class="fas fa-home mr-2" aria-hidden="true"></i> View All Destinations
            </a>
        </div>
    <?php endif; ?>
</main>


<?php if(!request()->hasAny(['search', 'country', 'popular'])): ?>
<section class="bg-gray-50 py-12 sm:py-16 px-4 border-t" aria-labelledby="faq-heading">
    <div class="container mx-auto max-w-4xl">
        <div class="text-center mb-10">
            <h2 id="faq-heading" class="text-2xl sm:text-3xl font-bold text-gray-900 mb-3">
               Frequently Asked Questions About East Africa Safari Destinations
            </h2>
            <p class="text-gray-600 text-sm sm:text-base">Everything international travellers need to know before booking their African safari</p>
        </div>

        <div class="space-y-4" id="faqAccordion">

            <?php
            $faqs = [
                [
                    'q' => 'What are the best safari destinations in East Africa?',
                    'a' => 'East Africa\'s top safari destinations include Uganda\'s Bwindi Impenetrable Forest (mountain gorillas), Tanzania\'s Serengeti (Great Migration & Big Five), Kenya\'s Masai Mara (lions, cheetahs, wildebeest crossing), Rwanda\'s Volcanoes National Park (mountain gorillas) and Uganda\'s Queen Elizabeth National Park (tree-climbing lions, hippos, chimpanzees). Each destination offers a unique wildlife experience tailored to international visitors.',
                ],
                [
                    'q' => 'When is the best time to visit East Africa for a safari?',
                    'a' => 'The best time for an East Africa safari depends on your experience. For the Great Wildebeest Migration (Serengeti/Masai Mara), July to October is peak season. For gorilla trekking in Uganda and Rwanda, June–September and December–February offer drier conditions and clearer forest trails. For birding and lush landscapes, the green season (November–May) is excellent. East Africa is a year-round safari destination.',
                ],
                [
                    'q' => 'How do tourists from the USA, UK and Europe travel to East Africa?',
                    'a' => 'From the USA, fly into Entebbe International Airport (Uganda), Jomo Kenyatta International Airport (Nairobi, Kenya), or Kilimanjaro International Airport (Tanzania) via hubs like London Heathrow, Amsterdam, Dubai or Doha. From the UK, there are direct flights to Nairobi and Entebbe. From Europe (Germany, France, Netherlands, Italy, Spain), connect through Dubai, Doha or Istanbul. Flight times range from 9 to 14 hours depending on origin.',
                ],
                [
                    'q' => 'Do I need a visa to visit Uganda, Kenya, Tanzania or Rwanda?',
                    'a' => 'Most international travellers can obtain a visa on arrival or an e-Visa online. Uganda, Kenya, Rwanda and Tanzania offer East Africa Tourist Visas, allowing entry to multiple countries on a single permit — ideal for multi-country safari itineraries. Citizens from the USA, UK, Canada, Australia, EU countries and most other nations qualify. Always check your country\'s specific requirements before travel.',
                ],
                [
                    'q' => 'Is East Africa safe for international tourists?',
                    'a' => 'Yes. Uganda, Kenya, Tanzania and Rwanda are well-established, tourist-friendly safari destinations visited annually by hundreds of thousands of international travellers. National parks are staffed by professional rangers and guides. Our team provides full pre-trip safety briefings, accompanies all guests on game drives and treks, and maintains 24/7 emergency support throughout your safari.',
                ],
                [
                    'q' => 'What vaccinations and health precautions are needed for an East Africa safari?',
                    'a' => 'Recommended vaccinations include Yellow Fever (required for Uganda and some other East African countries), Typhoid, Hepatitis A & B, and Tetanus. Anti-malaria medication is strongly advised. Consult your GP or a travel health clinic at least 6–8 weeks before departure. Gorilla trekking requires visitors to be symptom-free of respiratory illnesses to protect the gorillas.',
                ],
            ];
            ?>

            <?php $__currentLoopData = $faqs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fi => $faq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <button class="faq-toggle w-full px-5 sm:px-6 py-4 text-left flex items-center justify-between gap-4 hover:bg-gray-50 transition focus:outline-none focus:ring-2 focus:ring-inset focus:ring-green-500"
                        aria-expanded="false"
                        aria-controls="faq-panel-<?php echo e($fi); ?>"
                        id="faq-btn-<?php echo e($fi); ?>"
                        type="button">
                    <span class="font-semibold text-gray-900 text-sm sm:text-base pr-2"><?php echo e($faq['q']); ?></span>
                    <span class="faq-icon flex-shrink-0 w-7 h-7 rounded-full border-2 border-gray-300 flex items-center justify-center relative transition-colors duration-300">
                        <span class="faq-h absolute w-3 h-0.5 bg-gray-500 rounded transition-colors duration-300"></span>
                        <span class="faq-v absolute w-0.5 h-3 bg-gray-500 rounded transition-all duration-300 origin-center"></span>
                    </span>
                </button>
                <div id="faq-panel-<?php echo e($fi); ?>"
                     role="region"
                     aria-labelledby="faq-btn-<?php echo e($fi); ?>"
                     class="faq-panel"
                     style="max-height:0;overflow:hidden;opacity:0;transition:max-height 0.4s cubic-bezier(0.4,0,0.2,1),opacity 0.3s ease;">
                    <div class="px-5 sm:px-6 pb-5 pt-2 text-gray-600 text-sm sm:text-base leading-relaxed border-t border-gray-100">
                        <?php echo e($faq['a']); ?>

                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>
<?php endif; ?>


<section class="bg-gradient-to-r from-green-600 via-teal-600 to-blue-600 text-white py-12 sm:py-16" aria-labelledby="cta-heading">
    <div class="container mx-auto px-4 text-center">
        <h2 id="cta-heading" class="text-3xl sm:text-4xl font-bold mb-3 sm:mb-4">
          Plan Your East Africa Safari — Choose Your Destination
        </h2>
        <p class="text-base sm:text-xl mb-6 sm:mb-8 max-w-2xl mx-auto opacity-90">
            Join thousands of international travellers from the USA, UK, Canada, Australia and Europe
            who have explored East Africa with our expert guides. Your dream safari is one click away.
        </p>
        <div class="flex flex-col sm:flex-row justify-center items-center gap-3 sm:gap-4">
            <a href="<?php echo e(route('contact')); ?>"
               class="w-full sm:w-auto bg-white text-green-600 px-7 py-3.5 rounded-lg font-bold hover:bg-gray-100 transition shadow-lg text-sm sm:text-base text-center">
                <i class="fas fa-envelope mr-2" aria-hidden="true"></i> Contact Us
            </a>
            <a href="<?php echo e(route('tours.index')); ?>"
               class="w-full sm:w-auto bg-transparent border-2 border-white text-white px-7 py-3.5 rounded-lg font-bold hover:bg-white hover:text-green-600 transition text-sm sm:text-base text-center">
                <i class="fas fa-binoculars mr-2" aria-hidden="true"></i> Browse Safari Tours
            </a>
        </div>
    </div>
</section>

<?php $__env->startPush('scripts'); ?>
<script>
/* ── Slideshow ────────────────────────────────────────────── */
(function () {
    const slides     = Array.from(document.querySelectorAll('.slide'));
    const indicators = Array.from(document.querySelectorAll('.slide-indicator'));
    const total      = slides.length;
    if (total === 0) return;

    let current = 0, timer = null;

    function goTo(idx) {
        idx = ((idx % total) + total) % total;
        slides[current].classList.remove('slide-active');
        if (indicators[current]) indicators[current].classList.remove('active');
        current = idx;
        slides[current].classList.add('slide-active');
        if (indicators[current]) indicators[current].classList.add('active');
    }
    function startAutoplay() { stopAutoplay(); timer = setInterval(() => goTo(current + 1), 5000); }
    function stopAutoplay()  { if (timer) { clearInterval(timer); timer = null; } }

    document.querySelector('.slide-control.next')?.addEventListener('click', () => { goTo(current + 1); startAutoplay(); });
    document.querySelector('.slide-control.prev')?.addEventListener('click', () => { goTo(current - 1); startAutoplay(); });
    indicators.forEach((dot, i) => dot.addEventListener('click', () => { goTo(i); startAutoplay(); }));
    document.querySelector('.slideshow-container')?.addEventListener('mouseenter', stopAutoplay);
    document.querySelector('.slideshow-container')?.addEventListener('mouseleave', startAutoplay);

    goTo(0); startAutoplay();
})();

/* ── Parallax ────────────────────────────────────────────── */
(function () {
    const heroSection = document.querySelector('.hero-section');
    if (!heroSection) return;
    const isMobile = window.matchMedia('(max-width: 768px)').matches || /iPad|iPhone|iPod|Android/.test(navigator.userAgent);
    if (isMobile) return;
    const parallaxBgs = document.querySelectorAll('.parallax-bg');
    function applyParallax() {
        const heroRect = heroSection.getBoundingClientRect();
        const heroH    = heroSection.offsetHeight;
        if (heroRect.bottom < 0 || heroRect.top > window.innerHeight) return;
        const scrollProgress = Math.max(0, -heroRect.top) / heroH;
        parallaxBgs.forEach(bg => { bg.style.transform = `translateY(${scrollProgress * 30}%)`; });
    }
    window.addEventListener('scroll', applyParallax, { passive: true });
    applyParallax();
})();

/* ── Pagination scroll ───────────────────────────────────── */
(function () {
    const section = document.getElementById('all-destinations');
    if (!section) return;
    if (new URLSearchParams(window.location.search).has('page')) {
        setTimeout(() => section.scrollIntoView({ behavior: 'smooth', block: 'start' }), 120);
    }
    const paginationWrap = document.getElementById('pagination-links');
    if (!paginationWrap) return;
    paginationWrap.addEventListener('click', function (e) {
        const link = e.target.closest('a[href]');
        if (!link) return;
        e.preventDefault();
        window.location.href = new URL(link.href).toString();
    });
})();

/* ── FAQ Accordion ───────────────────────────────────────── */
(function () {
    const toggles = document.querySelectorAll('.faq-toggle');

    function openFaq(btn) {
        const panel = document.getElementById(btn.getAttribute('aria-controls'));
        if (!panel) return;
        btn.setAttribute('aria-expanded', 'true');
        btn.querySelector('.faq-icon').style.borderColor = '#16a34a';
        btn.querySelector('.faq-h').style.backgroundColor = '#16a34a';
        btn.querySelector('.faq-v').style.transform = 'translate(-50%, -50%) scaleY(0)';
        btn.querySelector('.faq-v').style.opacity = '0';
        panel.style.maxHeight = panel.scrollHeight + 'px';
        panel.style.opacity = '1';
    }

    function closeFaq(btn) {
        const panel = document.getElementById(btn.getAttribute('aria-controls'));
        if (!panel) return;
        btn.setAttribute('aria-expanded', 'false');
        btn.querySelector('.faq-icon').style.borderColor = '';
        btn.querySelector('.faq-h').style.backgroundColor = '';
        btn.querySelector('.faq-v').style.transform = '';
        btn.querySelector('.faq-v').style.opacity = '';
        panel.style.maxHeight = '0';
        panel.style.opacity = '0';
    }

    toggles.forEach(btn => {
        btn.addEventListener('click', function () {
            const isOpen = this.getAttribute('aria-expanded') === 'true';
            if (isOpen) {
                closeFaq(this);
            } else {
                toggles.forEach(o => { if (o !== this) closeFaq(o); });
                openFaq(this);
            }
        });
    });
})();
</script>

<style>
/* Hero height */
.hero-section {
    height: 75vw; min-height: 340px; max-height: 600px;
}
@media (min-width: 640px) {
    .hero-section { height: 60vw; min-height: 420px; max-height: 680px; }
}
@media (min-width: 1024px) {
    .hero-section { height: 80vh; min-height: 520px; max-height: 800px; }
}
.parallax-bg { will-change: transform; transition: transform 0.05s linear; }
.slide { opacity: 0; transition: opacity 1s ease-in-out; pointer-events: none; }
.slide.slide-active { opacity: 1; pointer-events: auto; }
.slide-indicator { width: 8px; background-color: rgba(255,255,255,0.4); transition: width 0.3s ease, background-color 0.3s ease; }
.slide-indicator.active { width: 28px; background-color: white !important; }
.line-clamp-1 { display:-webkit-box;-webkit-line-clamp:1;-webkit-box-orient:vertical;overflow:hidden; }
.line-clamp-2 { display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden; }
.line-clamp-3 { display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden; }

/* FAQ icon */
.faq-v {
    position: absolute; top: 50%; left: 50%;
    transform: translate(-50%, -50%) scaleY(1);
    transition: transform 0.3s ease, opacity 0.3s ease, background-color 0.25s ease;
    opacity: 1;
}
.faq-h {
    position: absolute; top: 50%; left: 50%;
    transform: translate(-50%, -50%);
    transition: background-color 0.25s ease;
}
.faq-panel { will-change: max-height, opacity; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\safaris\resources\views/destinations/index.blade.php ENDPATH**/ ?>