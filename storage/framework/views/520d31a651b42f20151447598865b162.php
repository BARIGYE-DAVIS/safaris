<?php $__env->startSection('title', 'Safari Activities in Uganda & East Africa 2026 | Chimp Tracking, Game Drives & More'); ?>
<?php $__env->startSection('meta_description', 'Discover 30+ wildlife activities across Uganda and East Africa — chimp tracking Kibale, game drives Queen Elizabeth, boat cruises Murchison Falls, birding, cultural tours and adventure sports. Browse and book today.'); ?>
<?php $__env->startSection('meta_keywords', 'safari activities Uganda, wildlife activities East Africa, chimp tracking Kibale, game drives Uganda, boat cruise Kazinga Channel, Murchison Falls boat trip, birding Uganda, cultural tours Uganda, white water rafting Nile, Rwenzori mountain trekking, Ziwa rhino tracking, Uganda adventure activities, East Africa wildlife experiences 2026'); ?>
<?php $__env->startSection('og_title', 'Uganda & East Africa Safari Activities 2026 | Chimp Tracking, Game Drives & Adventure'); ?>
<?php $__env->startSection('og_description', 'Browse 30+ safari activities in Uganda and East Africa. Chimp tracking, game drives, boat cruises, birding and adventure sports. Book with Calm Africa Safaris.'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-gray-50 min-h-screen">

    
    <div class="relative h-[70vh] md:h-[80vh] overflow-hidden">
        <div id="hero-carousel" class="relative h-full">
            <?php
                $heroActivities         = $featuredActivities ?? $activities->take(5);
                $totalSlides            = $heroActivities->count();
                $maxIndicators          = 5;
                $showGroupedIndicators  = $totalSlides > $maxIndicators;
            ?>

            <?php $__currentLoopData = $heroActivities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $heroActivity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="carousel-slide <?php echo e($index === 0 ? 'active opacity-100' : 'opacity-0'); ?> absolute inset-0 transition-opacity duration-1000 ease-in-out">
                <div class="absolute inset-0">
                    <?php if($heroActivity->featured_image): ?>
                        <img src="<?php echo e(asset('storage/' . $heroActivity->featured_image)); ?>" alt="<?php echo e($heroActivity->name); ?>" class="w-full h-full object-cover">
                    <?php elseif($heroActivity->image): ?>
                        <img src="<?php echo e(asset('storage/' . $heroActivity->image)); ?>" alt="<?php echo e($heroActivity->name); ?>" class="w-full h-full object-cover">
                    <?php else: ?>
                        <div class="w-full h-full bg-gradient-to-br from-green-600 via-teal-600 to-blue-600"></div>
                    <?php endif; ?>
                </div>
                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/50 to-black/20"></div>
                <div class="absolute inset-0 flex items-center">
                    <div class="container mx-auto px-4 md:px-8 lg:px-16">
                        <div class="max-w-3xl">
                            <div class="flex flex-wrap gap-2 mb-4 animate-fade-in">
                                <?php if($heroActivity->is_popular): ?>
                                <span class="bg-yellow-500 text-white px-4 py-2 rounded-full text-sm font-bold shadow-lg">
                                    <i class="fas fa-star mr-1"></i> Popular
                                </span>
                                <?php endif; ?>
                                <?php if($heroActivity->category): ?>
                                <span class="bg-purple-600/90 backdrop-blur-sm text-white px-4 py-2 rounded-full text-sm font-medium">
                                    <?php if($heroActivity->category->icon): ?><i class="<?php echo e($heroActivity->category->icon); ?> mr-1"></i><?php endif; ?>
                                    <?php echo e($heroActivity->category->name); ?>

                                </span>
                                <?php endif; ?>
                                <?php if($heroActivity->difficulty_level): ?>
                                <span class="backdrop-blur-sm text-white px-4 py-2 rounded-full text-sm font-bold
                                    <?php echo e($heroActivity->difficulty_level == 'easy'        ? 'bg-green-600/90'  : ''); ?>

                                    <?php echo e($heroActivity->difficulty_level == 'moderate'    ? 'bg-blue-600/90'   : ''); ?>

                                    <?php echo e($heroActivity->difficulty_level == 'challenging' ? 'bg-orange-600/90' : ''); ?>

                                    <?php echo e($heroActivity->difficulty_level == 'extreme'     ? 'bg-red-600/90'    : ''); ?>">
                                    <i class="fas fa-chart-line mr-1"></i> <?php echo e(ucfirst($heroActivity->difficulty_level)); ?>

                                </span>
                                <?php endif; ?>
                            </div>
                            <h1 class="text-3xl md:text-5xl lg:text-6xl font-bold text-white mb-4 leading-tight drop-shadow-lg animate-slide-up">
                                <?php echo e($heroActivity->name); ?>

                            </h1>
                            <p class="text-base md:text-lg lg:text-xl text-white/90 mb-6 leading-relaxed max-w-2xl animate-slide-up" style="animation-delay:.2s">
                                <?php echo e(Str::limit($heroActivity->description ?? $heroActivity->overview, 150)); ?>

                            </p>
                            <div class="flex flex-wrap gap-4 text-white/90 text-sm md:text-base mb-6 animate-slide-up" style="animation-delay:.3s">
                                <?php if($heroActivity->destination): ?>
                                <div class="flex items-center"><i class="fas fa-map-marker-alt text-green-400 mr-2"></i><span><?php echo e($heroActivity->destination->name); ?></span></div>
                                <?php endif; ?>
                                <?php if($heroActivity->duration): ?>
                                <div class="flex items-center"><i class="far fa-clock text-blue-400 mr-2"></i><span><?php echo e($heroActivity->duration); ?></span></div>
                                <?php endif; ?>
                                <?php if($heroActivity->price_from): ?>
                                <div class="flex items-center"><i class="fas fa-tag text-yellow-400 mr-2"></i><span>From <?php echo e($heroActivity->currency); ?> <?php echo e(number_format($heroActivity->price_from, 0)); ?></span></div>
                                <?php endif; ?>
                            </div>
                            <div class="flex flex-wrap gap-3 md:gap-4 animate-slide-up" style="animation-delay:.4s">
                                <a href="<?php echo e(route('activities.show', $heroActivity->slug)); ?>"
                                   class="bg-green-600 hover:bg-green-700 text-white px-6 md:px-8 py-2 md:py-3 rounded-lg font-bold transition shadow-lg inline-flex items-center text-sm md:text-base">
                                    <i class="fas fa-info-circle mr-2"></i> Learn More
                                </a>
                                <a href="<?php echo e(route('contact', ['activity' => $heroActivity->slug])); ?>"
                                   class="bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white px-6 md:px-8 py-2 md:py-3 rounded-lg font-bold transition border-2 border-white inline-flex items-center text-sm md:text-base">
                                    <i class="fas fa-envelope mr-2"></i> Book Now
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <?php if($totalSlides > 1): ?>
        <button onclick="previousSlide()" class="absolute left-2 md:left-4 lg:left-8 top-1/2 -translate-y-1/2 hover:bg-green-700 w-10 h-10 md:w-14 md:h-14 rounded-full backdrop-blur-sm transition-all z-30 shadow-lg hover:scale-110 flex items-center justify-center">
            <i class="fas fa-chevron-left text-base md:text-xl text-white"></i>
        </button>
        <button onclick="nextSlide()" class="absolute right-2 md:right-4 lg:right-8 top-1/2 -translate-y-1/2 hover:bg-green-700 w-10 h-10 md:w-14 md:h-14 rounded-full backdrop-blur-sm transition-all z-30 shadow-lg hover:scale-110 flex items-center justify-center">
            <i class="fas fa-chevron-right text-base md:text-xl text-white"></i>
        </button>
        <?php endif; ?>

        <?php if($totalSlides > 1): ?>
        <div class="absolute bottom-20 md:bottom-24 lg:bottom-28 left-1/2 -translate-x-1/2 z-30">
            <div class="bg-black/40 backdrop-blur-md rounded-full px-3 md:px-6 py-2 md:py-4">
                <div class="flex items-center gap-2 md:gap-3">
                    <?php if($showGroupedIndicators): ?>
                        <?php for($i = 0; $i < $maxIndicators; $i++): ?>
                            <button onclick="goToSlideGroup(<?php echo e($i); ?>)"
                                    class="carousel-indicator-group transition-all rounded-full <?php echo e($i === 0 ? 'bg-white w-6 md:w-10 h-2' : 'bg-white/40 w-4 md:w-8 h-2'); ?>"
                                    data-group="<?php echo e($i); ?>"></button>
                        <?php endfor; ?>
                        <span class="text-white text-xs md:text-sm font-medium ml-1 md:ml-3 min-w-[50px] text-center">
                            <span id="current-slide">1</span>/<?php echo e($totalSlides); ?>

                        </span>
                    <?php else: ?>
                        <?php $__currentLoopData = $heroActivities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $heroActivity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <button onclick="goToSlide(<?php echo e($index); ?>)"
                                    class="carousel-indicator transition-all rounded-full <?php echo e($index === 0 ? 'bg-white w-6 md:w-10 h-2' : 'bg-white/40 w-4 md:w-8 h-2'); ?>"
                                    data-index="<?php echo e($index); ?>"></button>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <span class="text-white text-xs md:text-sm font-medium ml-1 md:ml-3">
                            <span id="current-slide">1</span>/<?php echo e($totalSlides); ?>

                        </span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="absolute bottom-4 md:bottom-6 right-2 md:right-4 lg:right-8 z-20">
            <div class="bg-black/50 backdrop-blur-md rounded-xl p-2 md:p-6 text-white">
                <div class="flex flex-row md:flex-row gap-3 md:gap-6">
                    <div class="text-center">
                        <div class="text-lg md:text-3xl font-bold"><?php echo e($activities->total()); ?>+</div>
                        <div class="text-[9px] md:text-sm text-white/80">Activities</div>
                    </div>
                    <div class="w-px bg-white/30"></div>
                    <div class="text-center">
                        <div class="text-lg md:text-3xl font-bold">4</div>
                        <div class="text-[9px] md:text-sm text-white/80">Countries</div>
                    </div>
                    <div class="w-px bg-white/30"></div>
                    <div class="text-center">
                        <div class="text-lg md:text-3xl font-bold">100%</div>
                        <div class="text-[9px] md:text-sm text-white/80">Adventure</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="absolute bottom-2 md:bottom-4 left-1/2 -translate-x-1/2 animate-bounce z-20 hidden md:block">
            <i class="fas fa-chevron-down text-white text-xl md:text-2xl opacity-70"></i>
        </div>
    </div>

    
    <nav class="bg-white py-3 border-b shadow-sm">
        <div class="container mx-auto px-4">
            <ol class="flex items-center space-x-2 text-sm">
                <li><a href="<?php echo e(route('index')); ?>" class="text-green-600 hover:underline flex items-center"><i class="fas fa-home mr-1"></i>Home</a></li>
                <li><i class="fas fa-chevron-right text-gray-400 text-xs"></i></li>
                <li class="text-gray-700 font-medium">Activities</li>
            </ol>
        </div>
    </nav>

    
    <div id="filter-bar" class="bg-white shadow-md py-4 md:py-6 sticky top-0 z-40">
        <div class="container mx-auto px-4">
            <form id="filter-form" method="GET" action="<?php echo e(route('activities.index')); ?>" class="space-y-3">

                
                <div class="flex gap-2 items-center">
                    <div class="relative flex-1">
                        <span id="search-icon" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                            <i class="fas fa-search text-sm"></i>
                        </span>
                        <span id="search-spinner" class="hidden absolute left-3 top-1/2 -translate-y-1/2 text-green-500 pointer-events-none">
                            <i class="fas fa-circle-notch fa-spin text-sm"></i>
                        </span>
                        <input id="search-input" type="text" name="search"
                               placeholder="Search activities…"
                               value="<?php echo e(request('search')); ?>"
                               autocomplete="off"
                               class="w-full pl-9 pr-8 py-2.5 md:py-3 text-sm md:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition">
                        <button type="button" id="clear-search-btn"
                                class="<?php echo e(request('search') ? '' : 'hidden'); ?> absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-red-500 transition">
                            <i class="fas fa-times-circle"></i>
                        </button>
                    </div>

                    <?php if(request()->hasAny(['search','category','country','destination','difficulty'])): ?>
                    <a href="<?php echo e(route('activities.index')); ?>"
                       class="shrink-0 text-red-600 px-3 md:px-5 py-2.5 md:py-3 text-sm hover:bg-red-50 rounded-lg transition border border-red-200 flex items-center gap-1 whitespace-nowrap">
                        <i class="fas fa-times"></i><span class="hidden sm:inline">Clear All</span>
                    </a>
                    <?php endif; ?>
                </div>

                
                <div class="flex gap-2 overflow-x-auto pb-1 scrollbar-hide -mx-4 px-4 md:mx-0 md:px-0 md:flex-wrap">
                    <select id="filter-category" name="category"
                            class="live-filter shrink-0 px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 cursor-pointer bg-white min-w-[140px] md:min-w-[180px]">
                        <option value="">All Categories</option>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($category->id); ?>" <?php echo e(request('category') == $category->id ? 'selected' : ''); ?>><?php echo e($category->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>

                    <select id="filter-country" name="country"
                            class="live-filter shrink-0 px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 cursor-pointer bg-white min-w-[140px] md:min-w-[180px]">
                        <option value="">All Countries</option>
                        <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($country->id); ?>" <?php echo e(request('country') == $country->id ? 'selected' : ''); ?>><?php echo e($country->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>

                    <select id="destination-filter" name="destination"
                            class="live-filter shrink-0 px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 cursor-pointer bg-white min-w-[160px] md:min-w-[200px]">
                        <option value="">All Destinations</option>
                        <?php $__currentLoopData = $destinations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $destination): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($destination->id); ?>"
                                    data-country="<?php echo e($destination->country_id ?? ''); ?>"
                                    <?php echo e(request('destination') == $destination->id ? 'selected' : ''); ?>>
                                <?php echo e($destination->name); ?><?php if($destination->country): ?> (<?php echo e($destination->country->name); ?>)<?php endif; ?>
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>

                    <select id="filter-difficulty" name="difficulty"
                            class="live-filter shrink-0 px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 cursor-pointer bg-white min-w-[130px] md:min-w-[160px]">
                        <option value="">All Levels</option>
                        <option value="easy"        <?php echo e(request('difficulty') == 'easy'        ? 'selected' : ''); ?>>Easy</option>
                        <option value="moderate"    <?php echo e(request('difficulty') == 'moderate'    ? 'selected' : ''); ?>>Moderate</option>
                        <option value="challenging" <?php echo e(request('difficulty') == 'challenging' ? 'selected' : ''); ?>>Challenging</option>
                        <option value="extreme"     <?php echo e(request('difficulty') == 'extreme'     ? 'selected' : ''); ?>>Extreme</option>
                    </select>
                </div>
            </form>
        </div>
    </div>

    
    <?php if(request()->hasAny(['search','category','country','destination','difficulty'])): ?>
    <div class="bg-blue-50 border-b border-blue-100 py-2 md:py-3">
        <div class="container mx-auto px-4">
            <div class="flex items-center flex-wrap gap-2 text-sm">
                <span class="text-gray-600 font-medium text-xs md:text-sm">Filters:</span>

                <?php if(request('search')): ?>
                <span class="inline-flex items-center bg-white px-2.5 py-1 rounded-full text-xs md:text-sm border gap-1">
                    <i class="fas fa-search text-green-600"></i> "<?php echo e(request('search')); ?>"
                    <a href="<?php echo e(route('activities.index', array_merge(request()->except('search')))); ?>" class="text-red-400 hover:text-red-600 ml-1"><i class="fas fa-times"></i></a>
                </span>
                <?php endif; ?>
                <?php if(request('category')): ?>
                <span class="inline-flex items-center bg-white px-2.5 py-1 rounded-full text-xs md:text-sm border gap-1">
                    <i class="fas fa-tag text-purple-600"></i> <?php echo e($categories->find(request('category'))->name ?? ''); ?>

                    <a href="<?php echo e(route('activities.index', array_merge(request()->except('category')))); ?>" class="text-red-400 hover:text-red-600 ml-1"><i class="fas fa-times"></i></a>
                </span>
                <?php endif; ?>
                <?php if(request('country')): ?>
                <span class="inline-flex items-center bg-white px-2.5 py-1 rounded-full text-xs md:text-sm border gap-1">
                    <i class="fas fa-globe text-teal-600"></i> <?php echo e($countries->find(request('country'))->name ?? ''); ?>

                    <a href="<?php echo e(route('activities.index', array_merge(request()->except('country')))); ?>" class="text-red-400 hover:text-red-600 ml-1"><i class="fas fa-times"></i></a>
                </span>
                <?php endif; ?>
                <?php if(request('destination')): ?>
                <span class="inline-flex items-center bg-white px-2.5 py-1 rounded-full text-xs md:text-sm border gap-1">
                    <i class="fas fa-map-marker-alt text-blue-600"></i> <?php echo e($destinations->find(request('destination'))->name ?? ''); ?>

                    <a href="<?php echo e(route('activities.index', array_merge(request()->except('destination')))); ?>" class="text-red-400 hover:text-red-600 ml-1"><i class="fas fa-times"></i></a>
                </span>
                <?php endif; ?>
                <?php if(request('difficulty')): ?>
                <span class="inline-flex items-center bg-white px-2.5 py-1 rounded-full text-xs md:text-sm border gap-1">
                    <i class="fas fa-chart-line text-orange-600"></i> <?php echo e(ucfirst(request('difficulty'))); ?>

                    <a href="<?php echo e(route('activities.index', array_merge(request()->except('difficulty')))); ?>" class="text-red-400 hover:text-red-600 ml-1"><i class="fas fa-times"></i></a>
                </span>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    
    <div id="results-section" class="container mx-auto px-4 py-8 md:py-12">
        <div class="mb-6 md:mb-8">
            <h2 class="text-2xl md:text-4xl font-bold text-gray-800">
                <?php if(request('search')): ?>
                    Results for "<?php echo e(request('search')); ?>"
                <?php elseif(request('country') && request('destination')): ?>
                    Activities in <?php echo e($destinations->find(request('destination'))->name ?? ''); ?>, <?php echo e($countries->find(request('country'))->name ?? ''); ?>

                <?php elseif(request('country')): ?>
                    Activities in <?php echo e($countries->find(request('country'))->name ?? ''); ?>

                <?php elseif(request('category')): ?>
                    <?php echo e($categories->find(request('category'))->name ?? ''); ?> Activities
                <?php elseif(request('destination')): ?>
                    Activities in <?php echo e($destinations->find(request('destination'))->name ?? ''); ?>

                <?php else: ?>
                    All Safari Activities
                <?php endif; ?>
            </h2>
            <p class="text-gray-600 mt-1 text-sm md:text-base flex items-center gap-2">
                <i class="fas fa-info-circle text-green-600"></i>
                Showing <strong><?php echo e($activities->count()); ?></strong> of <strong><?php echo e($activities->total()); ?></strong> activities
            </p>
        </div>

        <?php if($activities->count() > 0): ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-6">
                <?php $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <article class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-2xl transition duration-300 transform hover:-translate-y-1 flex flex-col">
                        <a href="<?php echo e(route('activities.show', $activity->slug)); ?>" class="block flex-1 flex flex-col">
                            <div class="relative h-44 md:h-52 bg-gradient-to-br from-gray-200 to-gray-300 overflow-hidden">
                                <?php if($activity->featured_image): ?>
                                    <img src="<?php echo e(asset('storage/' . $activity->featured_image)); ?>" alt="<?php echo e($activity->name); ?>" class="w-full h-full object-cover hover:scale-110 transition duration-500" loading="lazy">
                                <?php elseif($activity->image): ?>
                                    <img src="<?php echo e(asset('storage/' . $activity->image)); ?>" alt="<?php echo e($activity->name); ?>" class="w-full h-full object-cover hover:scale-110 transition duration-500" loading="lazy">
                                <?php elseif($activity->icon): ?>
                                    <div class="w-full h-full bg-gradient-to-br from-green-400 to-blue-500 flex items-center justify-center">
                                        <img src="<?php echo e(asset('storage/' . $activity->icon)); ?>" alt="<?php echo e($activity->name); ?>" class="w-20 h-20 object-contain">
                                    </div>
                                <?php else: ?>
                                    <div class="w-full h-full bg-gradient-to-br from-green-300 to-teal-400 flex items-center justify-center">
                                        <i class="fas fa-hiking text-white text-5xl opacity-50"></i>
                                    </div>
                                <?php endif; ?>

                                <div class="absolute top-2 left-2 flex flex-col gap-1.5">
                                    <?php if($activity->is_popular): ?>
                                        <span class="bg-yellow-500 text-white px-2 py-0.5 rounded-full text-xs font-bold shadow flex items-center">
                                            <i class="fas fa-star mr-1"></i> Popular
                                        </span>
                                    <?php endif; ?>
                                    <?php if($activity->difficulty_level): ?>
                                        <span class="px-2 py-0.5 rounded-full text-xs font-bold shadow
                                            <?php echo e($activity->difficulty_level == 'easy'        ? 'bg-green-500 text-white'  : ''); ?>

                                            <?php echo e($activity->difficulty_level == 'moderate'    ? 'bg-blue-500 text-white'   : ''); ?>

                                            <?php echo e($activity->difficulty_level == 'challenging' ? 'bg-orange-500 text-white' : ''); ?>

                                            <?php echo e($activity->difficulty_level == 'extreme'     ? 'bg-red-500 text-white'    : ''); ?>">
                                            <?php echo e(ucfirst($activity->difficulty_level)); ?>

                                        </span>
                                    <?php endif; ?>
                                </div>

                                <?php if($activity->images->count() > 0): ?>
                                <div class="absolute bottom-2 right-2">
                                    <span class="bg-black/70 text-white px-2 py-0.5 rounded text-xs flex items-center gap-1">
                                        <i class="fas fa-images"></i> <?php echo e($activity->images->count()); ?>

                                    </span>
                                </div>
                                <?php endif; ?>
                            </div>

                            <div class="p-4 md:p-5 flex-1 flex flex-col">
                                <?php if($activity->category): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 bg-purple-100 text-purple-800 text-xs rounded-full mb-2 self-start">
                                        <?php if($activity->category->icon): ?><i class="<?php echo e($activity->category->icon); ?> mr-1"></i><?php endif; ?>
                                        <?php echo e($activity->category->name); ?>

                                    </span>
                                <?php endif; ?>

                                <h3 class="text-base md:text-xl font-bold text-gray-800 mb-1.5 hover:text-green-600 transition line-clamp-2">
                                    <?php echo e($activity->name); ?>

                                </h3>

                                <?php if($activity->destination): ?>
                                    <p class="text-xs md:text-sm text-gray-600 mb-2 flex items-center">
                                        <i class="fas fa-map-marker-alt text-green-600 mr-1.5"></i>
                                        <?php echo e($activity->destination->name); ?><?php if($activity->destination->country): ?>, <?php echo e($activity->destination->country->name); ?><?php endif; ?>
                                    </p>
                                <?php endif; ?>

                                <p class="text-xs md:text-sm text-gray-600 line-clamp-3 mb-3 flex-1">
                                    <?php echo e($activity->overview ? Str::limit($activity->overview, 100) : Str::limit($activity->description, 100)); ?>

                                </p>

                                <div class="flex flex-wrap gap-1.5 mb-3 text-xs text-gray-600">
                                    <?php if($activity->duration): ?>
                                    <span class="flex items-center bg-gray-100 px-2 py-0.5 rounded"><i class="far fa-clock text-blue-600 mr-1"></i><?php echo e($activity->duration); ?></span>
                                    <?php endif; ?>
                                    <?php if($activity->min_age): ?>
                                    <span class="flex items-center bg-gray-100 px-2 py-0.5 rounded"><i class="fas fa-child text-green-600 mr-1"></i><?php echo e($activity->min_age); ?>+</span>
                                    <?php endif; ?>
                                    <?php if($activity->max_group_size): ?>
                                    <span class="flex items-center bg-gray-100 px-2 py-0.5 rounded"><i class="fas fa-users text-purple-600 mr-1"></i>Max <?php echo e($activity->max_group_size); ?></span>
                                    <?php endif; ?>
                                </div>

                                <div class="flex items-center justify-between pt-3 border-t">
                                    <?php if($activity->price_from): ?>
                                    <div>
                                        <p class="text-xs text-gray-500">From</p>
                                        <p class="text-base md:text-lg font-bold text-green-600"><?php echo e($activity->currency); ?> <?php echo e(number_format($activity->price_from, 0)); ?></p>
                                    </div>
                                    <?php else: ?>
                                    <p class="text-xs text-gray-500 italic">Price on request</p>
                                    <?php endif; ?>
                                    <span class="text-green-600 text-xs md:text-sm font-semibold flex items-center gap-1">View Details <i class="fas fa-arrow-right"></i></span>
                                </div>
                            </div>
                        </a>
                    </article>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <nav class="mt-10 md:mt-12" aria-label="Activities pagination">
                <?php echo e($activities->appends(request()->query())->links()); ?>

            </nav>
        <?php else: ?>
            <div class="text-center py-16 md:py-20 bg-white rounded-xl shadow-md">
                <div class="inline-block p-6 md:p-8 bg-gray-100 rounded-full mb-4 md:mb-6">
                    <i class="fas fa-search text-gray-400 text-5xl md:text-6xl"></i>
                </div>
                <h3 class="text-2xl md:text-3xl font-bold text-gray-700 mb-2 md:mb-3">No activities found</h3>
                <p class="text-gray-500 mb-5 md:mb-6 max-w-md mx-auto text-sm md:text-base">
                    We couldn't find any activities matching your criteria. Try adjusting your filters.
                </p>
                <div class="flex justify-center gap-3 flex-wrap">
                    <a href="<?php echo e(route('activities.index')); ?>" class="bg-green-600 text-white px-5 md:px-6 py-2.5 md:py-3 text-sm md:text-base rounded-lg inline-flex items-center hover:bg-green-700 transition shadow">
                        <i class="fas fa-list mr-2"></i> View All
                    </a>
                    <a href="<?php echo e(route('contact')); ?>" class="bg-blue-600 text-white px-5 md:px-6 py-2.5 md:py-3 text-sm md:text-base rounded-lg inline-flex items-center hover:bg-blue-700 transition shadow">
                        <i class="fas fa-envelope mr-2"></i> Contact Us
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>

    
    <div class="bg-gradient-to-r from-green-600 to-teal-600 text-white py-12 md:py-16">
        
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-2xl md:text-4xl font-bold mb-3 md:mb-4">Ready for Your East African Adventure?</h2>
            <p class="text-base md:text-xl mb-6 md:mb-8 max-w-2xl mx-auto">
                Let us create a customized safari experience tailored to your interests and preferences
            </p>
            <div class="flex justify-center gap-3 md:gap-4 flex-wrap">
                <a href="<?php echo e(route('custom-tour-requests.create')); ?>" class="bg-white text-green-600 px-6 md:px-8 py-3 md:py-4 rounded-lg font-bold hover:bg-gray-100 transition shadow-lg text-sm md:text-base">
                    <i class="fas fa-paper-plane mr-2"></i> Plan Your Safari
                </a>
                <a href="<?php echo e(route('tours.index')); ?>" class="bg-transparent border-2 border-white text-white px-6 md:px-8 py-3 md:py-4 rounded-lg font-bold hover:bg-white hover:text-green-600 transition text-sm md:text-base">
                    <i class="fas fa-binoculars mr-2"></i> Browse Tours
                </a>
            </div>
        </div>
    </div>

</div>

<!-- /bg-gray-50 -->

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Refs ─────────────────────────────────────────────────────
    const form           = document.getElementById('filter-form');
    const searchInput    = document.getElementById('search-input');
    const searchIcon     = document.getElementById('search-icon');
    const searchSpinner  = document.getElementById('search-spinner');
    const clearSearchBtn = document.getElementById('clear-search-btn');
    const countrySelect  = document.getElementById('filter-country');
    const destSelect     = document.getElementById('destination-filter');
    const filterBar      = document.getElementById('filter-bar');

    // ── Destination → country client-side filter ─────────────────
    const allDestOptions = Array.from(destSelect.options).map(o => ({
        value:   o.value,
        text:    o.textContent,
        country: o.dataset.country || '',
        node:    o.cloneNode(true)
    }));

    function applyDestFilter() {
        const sel      = countrySelect.value;
        const prevDest = destSelect.value;
        while (destSelect.options.length > 1) destSelect.remove(1);
        allDestOptions.forEach(o => {
            if (!o.value) return;
            if (!sel || o.country === sel || o.country === '') {
                destSelect.appendChild(o.node.cloneNode(true));
            }
        });
        if ([...destSelect.options].find(o => o.value === prevDest)) {
            destSelect.value = prevDest;
        }
    }
    if (countrySelect.value) applyDestFilter();

    // ── Scroll helper: keep user at the filter bar ───────────────
    // We store the filter-bar's top offset in the URL hash so that
    // after the full-page GET the browser restores position there.
    function submitAndStay(delay) {
        clearTimeout(debounceTimer);

        if (delay > 0) {
            searchIcon.classList.add('hidden');
            searchSpinner.classList.remove('hidden');
        }

        debounceTimer = setTimeout(function () {
            searchSpinner.classList.add('hidden');
            searchIcon.classList.remove('hidden');

            // Append #filter-bar so browser scrolls to that element after load
            form.action = '<?php echo e(route('activities.index')); ?>' + '#filter-bar';
            form.submit();
        }, delay);
    }

    let debounceTimer = null;

    // ── Search input (debounced 450ms) ───────────────────────────
    searchInput.addEventListener('input', function () {
        clearSearchBtn.classList.toggle('hidden', this.value === '');
        submitAndStay(450);
    });

    searchInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter')  { e.preventDefault(); submitAndStay(0); }
        if (e.key === 'Escape') { this.value = ''; clearSearchBtn.classList.add('hidden'); submitAndStay(0); }
    });

    // ── Inline × clear ───────────────────────────────────────────
    clearSearchBtn.addEventListener('click', function () {
        searchInput.value = '';
        this.classList.add('hidden');
        submitAndStay(0);
    });

    // ── Dropdowns ────────────────────────────────────────────────
    countrySelect.addEventListener('change', function () {
        applyDestFilter();
        submitAndStay(0);
    });

    document.querySelectorAll('.live-filter').forEach(function (el) {
        if (el.id === 'filter-country') return; // handled above
        el.addEventListener('change', function () { submitAndStay(0); });
    });

    // ── On page load: if #filter-bar is in URL, scroll there ─────
    if (window.location.hash === '#filter-bar' && filterBar) {
        // Small delay lets the sticky bar settle after paint
        setTimeout(function () {
            filterBar.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }, 80);
    }

    // ─────────────────────────────────────────────────────────────
    // HERO CAROUSEL
    // ─────────────────────────────────────────────────────────────
    let currentSlide = 0;
    const slides      = document.querySelectorAll('.carousel-slide');
    const totalSlides = slides.length;
    const maxInd      = 5;
    const grouped     = totalSlides > maxInd;
    let autoPlay;

    function updateCounter() {
        const el = document.getElementById('current-slide');
        if (el) el.textContent = currentSlide + 1;
    }

    function updateDots() {
        if (grouped) {
            const gs  = Math.ceil(totalSlides / maxInd);
            const ag  = Math.floor(currentSlide / gs);
            document.querySelectorAll('.carousel-indicator-group').forEach((g, i) => {
                g.classList.toggle('bg-white',    i === ag);
                g.classList.toggle('w-6',         i === ag);
                g.classList.toggle('bg-white/40', i !== ag);
                g.classList.toggle('w-4',         i !== ag);
            });
        } else {
            document.querySelectorAll('.carousel-indicator').forEach((d, i) => {
                d.classList.toggle('bg-white',    i === currentSlide);
                d.classList.toggle('w-6',         i === currentSlide);
                d.classList.toggle('bg-white/40', i !== currentSlide);
                d.classList.toggle('w-4',         i !== currentSlide);
            });
        }
    }

    function showSlide(n) {
        slides.forEach(s => { s.classList.remove('active','opacity-100'); s.classList.add('opacity-0'); });
        if (slides[n]) { slides[n].classList.add('active','opacity-100'); slides[n].classList.remove('opacity-0'); }
        updateDots(); updateCounter();
    }

    window.nextSlide      = () => { currentSlide = (currentSlide + 1) % totalSlides; showSlide(currentSlide); resetAP(); };
    window.previousSlide  = () => { currentSlide = (currentSlide - 1 + totalSlides) % totalSlides; showSlide(currentSlide); resetAP(); };
    window.goToSlide      = i  => { currentSlide = i; showSlide(currentSlide); resetAP(); };
    window.goToSlideGroup = g  => {
        const gs = Math.ceil(totalSlides / maxInd);
        currentSlide = Math.min(g * gs, totalSlides - 1);
        showSlide(currentSlide); resetAP();
    };

    function startAP() { if (totalSlides > 1) autoPlay = setInterval(window.nextSlide, 5000); }
    function resetAP() { clearInterval(autoPlay); startAP(); }

    document.addEventListener('keydown', e => {
        if (e.target === searchInput) return;
        if (e.key === 'ArrowLeft')  window.previousSlide();
        if (e.key === 'ArrowRight') window.nextSlide();
    });

    let tx = 0;
    const carousel = document.getElementById('hero-carousel');
    if (carousel) {
        carousel.addEventListener('touchstart', e => { tx = e.changedTouches[0].screenX; });
        carousel.addEventListener('touchend',   e => {
            const dx = e.changedTouches[0].screenX - tx;
            if (dx < -50) window.nextSlide();
            if (dx >  50) window.previousSlide();
        });
        carousel.addEventListener('mouseenter', () => clearInterval(autoPlay));
        carousel.addEventListener('mouseleave', () => { if (totalSlides > 1) startAP(); });
    }

    if (totalSlides > 1) startAP();
    updateDots(); updateCounter();
});
</script>
<?php $__env->stopPush(); ?>

<style>
/* ── Utility ── */
.line-clamp-2 { display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; }
.line-clamp-3 { display:-webkit-box; -webkit-line-clamp:3; -webkit-box-orient:vertical; overflow:hidden; }
.scrollbar-hide::-webkit-scrollbar { display:none; }
.scrollbar-hide { -ms-overflow-style:none; scrollbar-width:none; }

/* ── Carousel ── */
.carousel-slide { transition:opacity 1000ms ease-in-out; }
.carousel-slide.active { opacity:1 !important; }

@keyframes slideUp { from{opacity:0;transform:translateY(30px)} to{opacity:1;transform:translateY(0)} }
@keyframes fadeIn  { from{opacity:0} to{opacity:1} }
.animate-slide-up { animation:slideUp .8s ease-out forwards; opacity:0; }
.animate-fade-in  { animation:fadeIn  .6s ease-out forwards; }

.carousel-indicator,.carousel-indicator-group {
    transition:all .3s ease; cursor:pointer; height:.5rem;
}
.carousel-indicator:hover,.carousel-indicator-group:hover {
    background-color:rgba(255,255,255,.7)!important; transform:scaleY(1.3);
}
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\safaris\resources\views/activities/index.blade.php ENDPATH**/ ?>