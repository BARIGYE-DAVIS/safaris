

<?php $__env->startSection('title', $accommodation->name); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-5xl mx-auto px-4 py-8">

    
    <nav class="text-xs text-gray-500 mb-4">
        <a href="<?php echo e(route('accommodations.index')); ?>" class="hover:text-green-700">Accommodations</a>
        <?php if($accommodation->country): ?>
            <span class="mx-1">/</span>
            <span><?php echo e($accommodation->country->name); ?></span>
        <?php endif; ?>
        <?php if($accommodation->destination): ?>
            <span class="mx-1">/</span>
            <span><?php echo e($accommodation->destination->name); ?></span>
        <?php endif; ?>
    </nav>

    
    <div class="mb-6">
        <div class="relative overflow-hidden rounded-2xl shadow-lg h-56 md:h-72 bg-gray-200">
            <img
                src="<?php echo e($accommodation->featured_image_url ?? ($accommodation->images->first()->url ?? asset('images/placeholder-wide.jpg'))); ?>"
                alt="<?php echo e($accommodation->name); ?>"
                class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent"></div>

            <div class="absolute bottom-4 left-4 md:bottom-6 md:left-6 text-white max-w-xl">
                <p class="text-xs md:text-sm text-gray-200 mb-1">
                    <?php if($accommodation->category): ?>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] mr-1
                            <?php if($accommodation->category === 'budget'): ?> bg-blue-100 text-blue-800
                            <?php elseif($accommodation->category === 'mid-range'): ?> bg-green-100 text-green-800
                            <?php else: ?> bg-purple-100 text-purple-800 <?php endif; ?>">
                            <?php echo e(ucfirst($accommodation->category)); ?>

                        </span>
                    <?php endif; ?>

                    <?php if($accommodation->type): ?> <?php echo e($accommodation->type); ?> · <?php endif; ?>
                    <?php if($accommodation->country): ?> <?php echo e($accommodation->country->name); ?> <?php endif; ?>
                    <?php if($accommodation->destination): ?> · <?php echo e($accommodation->destination->name); ?> <?php endif; ?>
                </p>
                <h1 class="text-2xl md:text-3xl font-bold">
                    <?php echo e($accommodation->name); ?>

                </h1>
                <?php if($accommodation->location): ?>
                    <p class="mt-1 text-xs md:text-sm text-gray-200">
                        <i class="fas fa-map-marker-alt mr-1 text-red-400"></i>
                        <?php echo e($accommodation->location); ?>

                    </p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

        
        <div class="md:col-span-2 space-y-8">

            
            <section>
                <h2 class="text-lg font-semibold text-gray-900 mb-2 border-b border-gray-100 pb-1">
                    Overview
                </h2>

                <?php if($accommodation->short_description): ?>
                    <p class="text-sm text-gray-700 leading-relaxed">
                        <?php echo e($accommodation->short_description); ?>

                    </p>
                <?php else: ?>
                    <p class="text-sm text-gray-500">
                        A comfortable stay option in <?php echo e(optional($accommodation->destination)->name ?? optional($accommodation->country)->name); ?>.
                    </p>
                <?php endif; ?>
            </section>

            
            <?php if($accommodation->full_description): ?>
                <section>
                    <h2 class="text-lg font-semibold text-gray-900 mb-2 border-b border-gray-100 pb-1">
                        Detailed Description
                    </h2>
                    <div class="prose prose-sm max-w-none text-gray-700">
                        <?php echo nl2br(e($accommodation->full_description)); ?>

                    </div>
                </section>
            <?php endif; ?>

            
            <?php
                $gallery = collect($accommodation->images ?? []);
            ?>

            <?php if($gallery->count()): ?>
                <section x-data="{ current: 0 }">
                    <h2 class="text-lg font-semibold text-gray-900 mb-2 border-b border-gray-100 pb-1">
                        Photos
                    </h2>

                    
                    <div class="relative overflow-hidden rounded-2xl shadow-lg h-56 md:h-64 bg-gray-900 mb-3">
                        <?php $__currentLoopData = $gallery; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div x-show="current === <?php echo e($idx); ?>"
                                 x-transition
                                 class="absolute inset-0">
                                <img src="<?php echo e($photo->url); ?>"
                                     alt="<?php echo e($photo->alt_text ?? $accommodation->name); ?>"
                                     class="w-full h-full object-cover">
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        <?php if($gallery->count() > 1): ?>
                            
                            <button type="button"
                                    @click="current = current === 0 ? <?php echo e($gallery->count() - 1); ?> : current - 1"
                                    class="absolute left-2 top-1/2 -translate-y-1/2 bg-black/40 hover:bg-black/70 text-white rounded-full w-8 h-8 flex items-center justify-center text-sm">
                                ‹
                            </button>
                            <button type="button"
                                    @click="current = current === <?php echo e($gallery->count() - 1); ?> ? 0 : current + 1"
                                    class="absolute right-2 top-1/2 -translate-y-1/2 bg-black/40 hover:bg-black/70 text-white rounded-full w-8 h-8 flex items-center justify-center text-sm">
                                ›
                            </button>

                            
                            <div class="absolute bottom-3 inset-x-0 flex justify-center space-x-1.5">
                                <?php $__currentLoopData = $gallery; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <button type="button"
                                            @click="current = <?php echo e($idx); ?>"
                                            :class="current === <?php echo e($idx); ?> ? 'bg-white' : 'bg-white/50'"
                                            class="w-2.5 h-2.5 rounded-full border border-white/70"></button>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    
                    <?php if($gallery->count() > 1): ?>
                        <div class="flex gap-2 overflow-x-auto pb-1">
                            <?php $__currentLoopData = $gallery; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <button type="button"
                                        @click="current = <?php echo e($idx); ?>"
                                        class="relative w-20 h-14 rounded-lg overflow-hidden border border-transparent hover:border-green-500"
                                        :class="current === <?php echo e($idx); ?> ? 'ring-2 ring-green-500 border-green-500' : ''">
                                    <img src="<?php echo e($photo->url); ?>"
                                         alt="<?php echo e($photo->alt_text ?? $accommodation->name); ?>"
                                         class="w-full h-full object-cover">
                                </button>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endif; ?>
                </section>
            <?php endif; ?>

        </div>

        
        <aside class="md:col-span-1 space-y-4">
            
            <div class="bg-white rounded-xl shadow p-4">
                <h3 class="text-sm font-semibold text-gray-800 mb-2">
                    Price Guide
                </h3>
                <?php if($accommodation->price_from || $accommodation->price_to): ?>
                    <p class="text-lg font-bold text-green-700">
                        <?php echo e($accommodation->currency ?? 'USD'); ?>

                        <?php echo e(number_format($accommodation->price_from ?? 0)); ?>

                        <?php if($accommodation->price_to): ?>
                            – <?php echo e(number_format($accommodation->price_to)); ?>

                        <?php endif; ?>
                    </p>
                    <p class="text-xs text-gray-500 mt-1">
                        Per person per night (indicative). Exact price may vary by season and availability.
                    </p>
                <?php else: ?>
                    <p class="text-sm text-gray-600">
                        Price on request. Contact us for a detailed quote.
                    </p>
                <?php endif; ?>
            </div>

            
            <?php if(is_array($accommodation->amenities) && count($accommodation->amenities)): ?>
                <div class="bg-white rounded-xl shadow p-4">
                    <h3 class="text-sm font-semibold text-gray-800 mb-2">
                        Amenities
                    </h3>
                    <ul class="text-xs text-gray-700 space-y-1">
                        <?php $__currentLoopData = $accommodation->amenities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $amenity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="flex items-start">
                                <span class="w-4 mt-0.5 text-green-600">
                                    <i class="fas fa-check-circle"></i>
                                </span>
                                <span><?php echo e($amenity); ?></span>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>
        </aside>
    </div>

    
    <?php if(isset($related) && $related->count()): ?>
        <div class="mt-10">
            <h2 class="text-lg font-semibold text-gray-900 mb-3">
                More stays <?php echo e($accommodation->destination ? 'near ' . $accommodation->destination->name : 'you may like'); ?>

            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <?php $__currentLoopData = $related; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('accommodations.show', $rel->slug)); ?>"
                       class="bg-white rounded-xl shadow hover:shadow-md transition block overflow-hidden">
                        <div class="h-28 bg-gray-100">
                            <img
                                src="<?php echo e($rel->featured_image_url ?? ($rel->images->first()->url ?? asset('images/placeholder-wide.jpg'))); ?>"
                                alt="<?php echo e($rel->name); ?>"
                                class="w-full h-full object-cover">
                        </div>
                        <div class="p-3">
                            <p class="text-sm font-semibold text-gray-900 line-clamp-1">
                                <?php echo e($rel->name); ?>

                            </p>
                            <p class="text-[11px] text-gray-500 mt-1">
                                <?php if($rel->category): ?> <?php echo e(ucfirst($rel->category)); ?> <?php endif; ?>
                                <?php if($rel->type): ?> · <?php echo e($rel->type); ?> <?php endif; ?>
                            </p>
                        </div>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\safaris\resources\views/accommodations/show.blade.php ENDPATH**/ ?>