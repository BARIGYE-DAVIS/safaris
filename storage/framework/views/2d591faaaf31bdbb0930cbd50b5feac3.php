<?php
    $tourCategories = \App\Models\ActivityCategory::where('is_active', true)->get();
    $tours          = \App\Models\Tour::with('itineraries')->where('status', 'published')->get();
    $destinations   = \App\Models\Destination::where('is_active', true)->with('country')->get();
    $activities     = \App\Models\Activity::where('is_active', true)->with(['category', 'destination'])->get();

    // ── Gorilla Tours: any tour whose tour_type contains "gorilla" (case-insensitive) ──
    $gorillaTours = $tours->filter(function ($tour) {
        return str_contains(strtolower($tour->type ?? ''), 'gorilla');
    })->values();

    // ── Activities ordering: gorilla first, chimp second, game drives third, rest unchanged ──
    $activityPriorityKeywords = ['gorilla', 'chimp', 'game drive'];

    $activities = $activities->sortBy(function ($activity) use ($activityPriorityKeywords) {
        $name = strtolower($activity->name ?? '');
        foreach ($activityPriorityKeywords as $i => $keyword) {
            if (str_contains($name, $keyword)) {
                return $i;
            }
        }
        return count($activityPriorityKeywords);
    })->values();

    // ── Destinations ordering: Uganda top spots first, then all Rwanda, then the rest ──
    $ugandaTopDestinations = [
        'Bwindi Impenetrable Forest',
        'Queen Elizabeth National Park',
        'Murchison Falls National Park',
        'Kibale National Park',
        'Lake Mburo National Park',
    ];
    $ugandaTopDestinationsLower = array_map('strtolower', $ugandaTopDestinations);

    $destinations = $destinations->sortBy(function ($dest) use ($ugandaTopDestinationsLower) {
        $countryName = strtolower($dest->country->name ?? '');
        $destName    = strtolower($dest->name ?? '');

        if ($countryName === 'uganda') {
            $rank = array_search($destName, $ugandaTopDestinationsLower);
            if ($rank !== false) {
                return $rank;
            }
        }

        if ($countryName === 'rwanda') {
            return 100;
        }

        return 200;
    })->values();
?>


<div class="bg-green-800 text-white text-xs sm:text-sm fixed top-0 left-0 right-0 z-50">
    <div class="container mx-auto px-4 lg:px-8 flex items-center justify-between py-1.5">
        <a href="mailto:booking@calmafricasafaris.com"
           class="flex items-center gap-1.5 hover:text-green-200 transition-colors">
            <i class="fas fa-envelope"></i>
            <span>booking@calmafricasafaris.com</span>
        </a>

        <div class="flex items-center gap-4 ml-auto">
            <a href="tel:+256752088768" class="flex items-center gap-1.5 hover:text-green-200 transition-colors">
                <i class="fas fa-phone"></i>
                <span class="hidden sm:inline">+256 752 088 768</span>
            </a>
            <a href="https://wa.me/256752088768" target="_blank" rel="noopener"
               class="flex items-center gap-1.5 hover:text-green-200 transition-colors">
                <i class="fab fa-whatsapp"></i>
                <span class="hidden sm:inline">WhatsApp</span>
            </a>
        </div>
    </div>
</div>


<nav class="bg-white shadow-lg fixed left-0 right-0 z-40" id="main-nav">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="flex justify-between items-center h-16">

            
            <a href="<?php echo e(route('index')); ?>" class="flex items-center gap-2 shrink-0">
                <img class="h-9 w-auto" src="<?php echo e(asset('images/logo.png')); ?>" alt="Calm Africa Safaris">
                <div class="flex flex-col leading-tight">
                    <span class="text-lg font-bold text-green-700">Calm Africa</span>
                    <span class="text-xs font-medium text-gray-500 -mt-0.5">Safaris</span>
                </div>
            </a>

            
            <div class="hidden lg:flex items-center gap-1">

                <a href="<?php echo e(route('index')); ?>"
                   class="px-3 py-2 text-sm font-medium rounded-md transition-colors
                          <?php echo e(request()->routeIs('index') ? 'text-green-700 bg-green-50' : 'text-gray-700 hover:text-green-700 hover:bg-gray-50'); ?>">
                    Home
                </a>

                
                <div class="relative group">
                    <button class="flex items-center gap-1 px-3 py-2 text-sm font-medium rounded-md transition-colors
                                   <?php echo e(request()->routeIs('tours.*') ? 'text-green-700 bg-green-50' : 'text-gray-700 hover:text-green-700 hover:bg-gray-50'); ?>">
                        Tours
                        <svg class="h-3.5 w-3.5 transition-transform duration-200 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div class="absolute left-0 top-full mt-1 w-80 bg-white rounded-xl shadow-xl border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible group-hover:translate-y-0 translate-y-2 transition-all duration-200 z-50">
                        <div class="p-4 max-h-[75vh] overflow-y-auto">
                            <?php if($tourCategories->count() > 0): ?>
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">By Category</p>
                            <div class="space-y-1 mb-4">
                                <?php $__currentLoopData = $tourCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php $catTours = $tours->where('category_id', $category->id); ?>
                                    <?php if($catTours->count() > 0): ?>
                                    <div class="px-3 py-2 hover:bg-green-50 rounded-lg">
                                        <p class="text-sm font-semibold text-gray-800 mb-1">
                                            <i class="<?php echo e($category->icon); ?> mr-1.5 text-green-600"></i><?php echo e($category->name); ?>

                                        </p>
                                        <div class="ml-5 space-y-0.5">
                                            <?php $__currentLoopData = $catTours->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tour): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <a href="<?php echo e(route('tours.show', $tour->slug)); ?>" class="block text-xs text-gray-600 hover:text-green-700 py-0.5">→ <?php echo e($tour->title); ?></a>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            <?php endif; ?>
                            <?php if($tours->count() > 0): ?>
                            <div class="border-t pt-3">
                                <a href="<?php echo e(route('tours.index')); ?>" class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 hover:text-green-600">All Tours</a>
                                <div class="space-y-1">
                                    <?php $__currentLoopData = $tours->take(8); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tour): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <a href="<?php echo e(route('tours.show', $tour->slug)); ?>" class="block px-3 py-1.5 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 rounded-lg transition-colors">
                                        <p class="font-medium truncate"><?php echo e($tour->title); ?></p>
                                        <p class="text-xs text-gray-400"><?php echo e($tour->itineraries->count()); ?> Days Safari<?php echo e($tour->destination ? ' in '.$tour->destination : ''); ?></p>
                                    </a>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                                <?php if($tours->count() > 8): ?>
                                <a href="<?php echo e(route('tours.index')); ?>" class="block mt-2 text-center text-xs text-green-600 hover:text-green-700 font-medium">View All <?php echo e($tours->count()); ?> Tours →</a>
                                <?php endif; ?>
                            </div>
                            <?php else: ?>
                            <p class="text-center text-gray-400 py-4 text-sm">No tours available yet</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                
                <div class="relative group">
                    <button class="flex items-center gap-1 px-3 py-2 text-sm font-medium rounded-md transition-colors
                                   text-gray-700 hover:text-green-700 hover:bg-gray-50">
                        Gorilla Tours
                        <svg class="h-3.5 w-3.5 transition-transform duration-200 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div class="absolute left-0 top-full mt-1 w-80 bg-white rounded-xl shadow-xl border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible group-hover:translate-y-0 translate-y-2 transition-all duration-200 z-50">
                        <div class="p-4 max-h-[75vh] overflow-y-auto">
                            <?php if($gorillaTours->count() > 0): ?>
                            <a href="<?php echo e(route('tours.index', ['tour_type' => 'gorilla'])); ?>"
                               class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3 hover:text-green-600">
                                All Gorilla Tours (<?php echo e($gorillaTours->count()); ?>)
                            </a>
                            <div class="space-y-1">
                                <?php $__currentLoopData = $gorillaTours; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tour): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <a href="<?php echo e(route('tours.show', $tour->slug)); ?>" class="block px-3 py-1.5 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 rounded-lg transition-colors">
                                    <p class="font-medium truncate"><?php echo e($tour->title); ?></p>
                                    <div class="flex items-center gap-2 mt-0.5">
                                        <span class="text-xs text-gray-400"><?php echo e($tour->itineraries->count()); ?> Days</span>
                                        <?php if($tour->tour_type): ?>
                                        <span class="text-xs text-green-600 bg-green-50 px-1.5 py-0.5 rounded"><?php echo e($tour->tour_type); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            <?php else: ?>
                            <div class="text-center py-6">
                                <i class="fas fa-paw text-3xl text-gray-300 mb-2"></i>
                                <p class="text-gray-400 text-sm">No gorilla tours available yet</p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                
                <div class="relative group">
                    <button class="flex items-center gap-1 px-3 py-2 text-sm font-medium rounded-md transition-colors
                                   <?php echo e(request()->routeIs('destinations.*') ? 'text-green-700 bg-green-50' : 'text-gray-700 hover:text-green-700 hover:bg-gray-50'); ?>">
                        Destinations
                        <svg class="h-3.5 w-3.5 transition-transform duration-200 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div class="absolute left-0 top-full mt-1 w-80 bg-white rounded-xl shadow-xl border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible group-hover:translate-y-0 translate-y-2 transition-all duration-200 z-50">
                        <div class="p-4">
                            <?php if($destinations->count() > 0): ?>
                            <a href="<?php echo e(route('destinations.index')); ?>" class="block text-center text-sm font-medium text-green-600 hover:text-green-700 mb-3">
                                View All <?php echo e($destinations->count()); ?> Destinations →
                            </a>
                            <div class="grid grid-cols-2 gap-2 max-h-72 overflow-y-auto">
                                <?php $__currentLoopData = $destinations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dest): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <a href="<?php echo e(route('destinations.show', $dest->slug)); ?>" class="group/d bg-gray-50 hover:bg-green-50 rounded-lg p-2 transition-colors">
                                    <?php if($dest->image): ?>
                                    <img src="<?php echo e(asset('storage/'.$dest->image)); ?>" alt="<?php echo e($dest->name); ?>" class="w-full h-16 object-cover rounded mb-1.5">
                                    <?php else: ?>
                                    <div class="w-full h-16 bg-gradient-to-br from-green-400 to-blue-500 rounded mb-1.5 flex items-center justify-center">
                                        <i class="fas fa-map-marker-alt text-white text-xl"></i>
                                    </div>
                                    <?php endif; ?>
                                    <p class="text-xs font-semibold text-gray-800 group-hover/d:text-green-700 truncate"><?php echo e($dest->name); ?></p>
                                    <p class="text-xs text-gray-400"><?php echo e($dest->country->flag_icon ?? ''); ?> <?php echo e($dest->country->name); ?></p>
                                </a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            <?php else: ?>
                            <p class="text-center text-gray-400 py-4 text-sm">No destinations available yet</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                
                <div class="relative group">
                    <button class="flex items-center gap-1 px-3 py-2 text-sm font-medium rounded-md transition-colors
                                   <?php echo e(request()->routeIs('activities.*') ? 'text-green-700 bg-green-50' : 'text-gray-700 hover:text-green-700 hover:bg-gray-50'); ?>">
                        Activities
                        <svg class="h-3.5 w-3.5 transition-transform duration-200 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div class="absolute left-0 top-full mt-1 w-80 bg-white rounded-xl shadow-xl border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible group-hover:translate-y-0 translate-y-2 transition-all duration-200 z-50">
                        <div class="p-4">
                            <?php if($activities->count() > 0): ?>
                            <a href="<?php echo e(route('activities.index')); ?>" class="block text-center text-sm font-medium text-green-600 hover:text-green-700 mb-3">
                                View All <?php echo e($activities->count()); ?> Activities →
                            </a>
                            <div class="space-y-1.5 max-h-72 overflow-y-auto">
                                <?php $__currentLoopData = $activities->take(8); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <a href="<?php echo e(route('activities.show', $activity->slug)); ?>" class="group/a flex items-center gap-2.5 p-2 bg-gray-50 hover:bg-green-50 rounded-lg transition-colors">
                                    <?php if($activity->image): ?>
                                    <img src="<?php echo e(asset('storage/'.$activity->image)); ?>" alt="<?php echo e($activity->name); ?>" class="w-12 h-12 object-cover rounded shrink-0">
                                    <?php elseif($activity->icon): ?>
                                    <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-blue-500 rounded shrink-0 flex items-center justify-center">
                                        <i class="<?php echo e($activity->icon); ?> text-white"></i>
                                    </div>
                                    <?php else: ?>
                                    <div class="w-12 h-12 bg-gray-200 rounded shrink-0 flex items-center justify-center">
                                        <i class="fas fa-hiking text-gray-400"></i>
                                    </div>
                                    <?php endif; ?>
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold text-gray-800 group-hover/a:text-green-700 truncate transition-colors"><?php echo e($activity->name); ?></p>
                                        <?php if($activity->category): ?>
                                        <p class="text-xs text-purple-600 truncate"><i class="<?php echo e($activity->category->icon); ?> mr-1"></i><?php echo e($activity->category->name); ?></p>
                                        <?php endif; ?>
                                        <?php if($activity->destination): ?>
                                        <p class="text-xs text-gray-400 truncate"><i class="fas fa-map-marker-alt mr-1"></i><?php echo e($activity->destination->name); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php if($activities->count() > 8): ?>
                                <a href="<?php echo e(route('activities.index')); ?>" class="block text-center text-xs text-green-600 hover:text-green-700 font-medium py-1 border-t">+<?php echo e($activities->count() - 8); ?> more activities</a>
                                <?php endif; ?>
                            </div>
                            <?php else: ?>
                            <p class="text-center text-gray-400 py-4 text-sm">No activities available yet</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <a href="<?php echo e(route('gallery.index')); ?>"
                   class="px-3 py-2 text-sm font-medium rounded-md transition-colors
                          <?php echo e(request()->routeIs('gallery.index') ? 'text-green-700 bg-green-50' : 'text-gray-700 hover:text-green-700 hover:bg-gray-50'); ?>">
                    Gallery
                </a>

                <a href="<?php echo e(route('blogs.index')); ?>"
                   class="px-3 py-2 text-sm font-medium rounded-md transition-colors
                          <?php echo e(request()->routeIs('blogs.*') ? 'text-green-700 bg-green-50' : 'text-gray-700 hover:text-green-700 hover:bg-gray-50'); ?>">
                    Blog
                </a>

                <a href="<?php echo e(route('accommodations.index')); ?>"
                   class="px-3 py-2 text-sm font-medium rounded-md transition-colors
                          <?php echo e(request()->routeIs('accommodations.*') ? 'text-green-700 bg-green-50' : 'text-gray-700 hover:text-green-700 hover:bg-gray-50'); ?>">
                    <i class="fas fa-bed text-green-600"></i>
                </a>

                <a href="<?php echo e(route('contact')); ?>"
                   class="ml-2 px-4 py-2 text-sm font-semibold rounded-lg transition-colors border border-green-600 bg-green-600 hover:bg-green-700 text-white whitespace-nowrap
                          <?php echo e(request()->routeIs('contact') ? 'bg-green-700' : ''); ?>">
                    <i class="fas fa-paper-plane mr-1.5"></i>Get a Quote
                </a>

            </div>

            
            <button id="mobile-toggle"
                    class="lg:hidden flex flex-col justify-center items-center w-9 h-9 gap-1.5 hover:bg-gray-100 transition-colors focus:outline-none"
                    aria-label="Toggle menu" aria-expanded="false">
                <span class="ham-line block w-6 h-0.5 bg-gray-800 transition-all duration-300"></span>
                <span class="ham-line block w-6 h-0.5 bg-gray-800 transition-all duration-300"></span>
                <span class="ham-line block w-6 h-0.5 bg-gray-800 transition-all duration-300"></span>
            </button>
        </div>

        
        <div id="mobile-menu"
             class="lg:hidden hidden border-t border-gray-100 bg-white">
            <div class="py-3 space-y-0.5 max-h-[80vh] overflow-y-auto">

                <a href="<?php echo e(route('index')); ?>"
                   class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg mx-2 transition-colors
                          <?php echo e(request()->routeIs('index') ? 'bg-green-50 text-green-700 border-l-4 border-green-600' : 'text-gray-700 hover:bg-gray-50 hover:text-green-700'); ?>">
                    <i class="fas fa-home mr-2"></i> Home
                </a>

                
                <div class="mx-2">
                    <button class="mobile-accordion w-full flex items-center justify-between px-4 py-3 text-sm font-medium rounded-lg transition-colors
                                   <?php echo e(request()->routeIs('tours.*') ? 'bg-green-50 text-green-700 border-l-4 border-green-600' : 'text-gray-700 hover:bg-gray-50 hover:text-green-700'); ?>">
                        <span><i class="fas fa-binoculars mr-2"></i> Tours</span>
                        <svg class="accordion-icon h-4 w-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div class="accordion-panel hidden pl-4 pr-2 pb-2 space-y-0.5">
                        <a href="<?php echo e(route('tours.index')); ?>" class="block px-4 py-2 text-sm text-green-600 font-medium hover:bg-green-50 rounded-lg">View All Tours →</a>
                        <?php $__currentLoopData = $tours->take(6); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tour): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(route('tours.show', $tour->slug)); ?>" class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 hover:text-green-700 rounded-lg transition-colors">
                            <?php echo e($tour->title); ?>

                            <span class="block text-xs text-gray-400"><?php echo e($tour->itineraries->count()); ?> Days</span>
                        </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                
                <div class="mx-2">
                    <button class="mobile-accordion w-full flex items-center justify-between px-4 py-3 text-sm font-medium rounded-lg transition-colors
                                   text-gray-700 hover:bg-gray-50 hover:text-green-700">
                        <span><i class="fas fa-paw mr-2"></i> Gorilla Tours</span>
                        <svg class="accordion-icon h-4 w-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div class="accordion-panel hidden pl-4 pr-2 pb-2 space-y-0.5">
                        <a href="<?php echo e(route('tours.index', ['tour_type' => 'gorilla'])); ?>" class="block px-4 py-2 text-sm text-green-600 font-medium hover:bg-green-50 rounded-lg">All Gorilla Tours →</a>
                        <?php $__empty_1 = true; $__currentLoopData = $gorillaTours; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tour): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <a href="<?php echo e(route('tours.show', $tour->slug)); ?>" class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 hover:text-green-700 rounded-lg transition-colors">
                            <?php echo e($tour->title); ?>

                            <span class="block text-xs text-gray-400"><?php echo e($tour->itineraries->count()); ?> Days</span>
                        </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="px-4 py-2 text-sm text-gray-400">No gorilla tours yet</p>
                        <?php endif; ?>
                    </div>
                </div>

                
                <div class="mx-2">
                    <button class="mobile-accordion w-full flex items-center justify-between px-4 py-3 text-sm font-medium rounded-lg transition-colors
                                   <?php echo e(request()->routeIs('destinations.*') ? 'bg-green-50 text-green-700 border-l-4 border-green-600' : 'text-gray-700 hover:bg-gray-50 hover:text-green-700'); ?>">
                        <span><i class="fas fa-map-marked-alt mr-2"></i>Destinations</span>
                        <svg class="accordion-icon h-4 w-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div class="accordion-panel hidden pl-4 pr-2 pb-2 space-y-0.5">
                        <a href="<?php echo e(route('destinations.index')); ?>" class="block px-4 py-2 text-sm text-green-600 font-medium hover:bg-green-50 rounded-lg">View All Destinations →</a>
                        <?php $__currentLoopData = $destinations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dest): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(route('destinations.show', $dest->slug)); ?>" class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 hover:text-green-700 rounded-lg transition-colors">
                            <?php echo e($dest->country->flag_icon ?? ''); ?> <?php echo e($dest->name); ?>

                        </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                
                <div class="mx-2">
                    <button class="mobile-accordion w-full flex items-center justify-between px-4 py-3 text-sm font-medium rounded-lg transition-colors
                                   <?php echo e(request()->routeIs('activities.*') ? 'bg-green-50 text-green-700 border-l-4 border-green-600' : 'text-gray-700 hover:bg-gray-50 hover:text-green-700'); ?>">
                        <span><i class="fas fa-running mr-2"></i>Activities</span>
                        <svg class="accordion-icon h-4 w-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div class="accordion-panel hidden pl-4 pr-2 pb-2 space-y-0.5">
                        <a href="<?php echo e(route('activities.index')); ?>" class="block px-4 py-2 text-sm text-green-600 font-medium hover:bg-green-50 rounded-lg">View All Activities →</a>
                        <?php $__currentLoopData = $activities->take(8); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(route('activities.show', $activity->slug)); ?>" class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 hover:text-green-700 rounded-lg transition-colors">
                            <?php echo e($activity->name); ?>

                            <?php if($activity->destination): ?><span class="block text-xs text-gray-400"><?php echo e($activity->destination->name); ?></span><?php endif; ?>
                        </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                <a href="<?php echo e(route('gallery.index')); ?>"
                   class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg mx-2 transition-colors
                          <?php echo e(request()->routeIs('gallery.index') ? 'bg-green-50 text-green-700 border-l-4 border-green-600' : 'text-gray-700 hover:bg-gray-50 hover:text-green-700'); ?>">
                    <i class="fas fa-images"></i> Gallery
                </a>

                <a href="<?php echo e(route('blogs.index')); ?>"
                   class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg mx-2 transition-colors
                          <?php echo e(request()->routeIs('blogs.*') ? 'bg-green-50 text-green-700 border-l-4 border-green-600' : 'text-gray-700 hover:bg-gray-50 hover:text-green-700'); ?>">
                    <i class="fas fa-blog"></i> Blog
                </a>

                <a href="<?php echo e(route('accommodations.index')); ?>"
                   class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg mx-2 transition-colors
                          <?php echo e(request()->routeIs('accommodations.*') ? 'bg-green-50 text-green-700 border-l-4 border-green-600' : 'text-gray-700 hover:bg-gray-50 hover:text-green-700'); ?>">
                    <i class="fas fa-bed"></i> Stays
                </a>

                <div class="px-2 pt-2 pb-1">
                    <a href="<?php echo e(route('contact')); ?>"
                       class="flex items-center justify-center gap-2 w-full bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-lg text-sm font-semibold transition-colors">
                        <i class="fas fa-paper-plane"></i> Get a Quote
                    </a>
                </div>

            </div>
        </div>

    </div>
</nav>


<div id="header-spacer"></div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Position nav directly below the contact bar & keep spacer in sync ──
    function positionNav() {
        var contactBar = document.querySelector('.bg-green-800.fixed');
        var nav        = document.getElementById('main-nav');
        var spacer     = document.getElementById('header-spacer');

        if (!contactBar || !nav || !spacer) return;

        var barH = contactBar.offsetHeight;
        nav.style.top = barH + 'px';
        spacer.style.height = (barH + nav.offsetHeight) + 'px';
    }

    positionNav();
    window.addEventListener('resize', positionNav);

    // ── Hamburger toggle ─────────────────────────────────────────
    var toggle     = document.getElementById('mobile-toggle');
    var mobileMenu = document.getElementById('mobile-menu');
    var lines      = toggle.querySelectorAll('.ham-line');

    toggle.addEventListener('click', function () {
        var open = toggle.getAttribute('aria-expanded') === 'true';
        toggle.setAttribute('aria-expanded', !open);
        mobileMenu.classList.toggle('hidden');

        // Re-measure spacer after drawer open/close
        setTimeout(function () {
            var contactBar = document.querySelector('.bg-green-800.fixed');
            var nav        = document.getElementById('main-nav');
            var spacer     = document.getElementById('header-spacer');
            if (contactBar && nav && spacer) {
                spacer.style.height = (contactBar.offsetHeight + nav.offsetHeight) + 'px';
            }
        }, 10);

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
            var panel = btn.nextElementSibling;
            var icon  = btn.querySelector('.accordion-icon');
            var open  = !panel.classList.contains('hidden');

            // Close all others
            document.querySelectorAll('.accordion-panel').forEach(function (p) { p.classList.add('hidden'); });
            document.querySelectorAll('.accordion-icon').forEach(function (i) { i.style.transform = ''; });

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
</script><?php /**PATH C:\xampp\htdocs\safaris\resources\views/components/navigation.blade.php ENDPATH**/ ?>