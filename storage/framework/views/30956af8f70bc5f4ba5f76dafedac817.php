

<?php $__env->startSection('title', 'Accommodations'); ?>

<?php $__env->startSection('content'); ?>

<?php
    $sliderItems = $accommodations->take(5);
?>

<?php if($sliderItems->count()): ?>
    <div x-data="{ current: 0 }" class="mb-10">
        <div class="relative overflow-hidden shadow-lg h-[60vh] min-h-[320px] bg-gray-900">
            <?php $__currentLoopData = $sliderItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $acc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('accommodations.show', $acc->slug)); ?>"
                   x-show="current === <?php echo e($idx); ?>"
                   x-transition
                   class="absolute inset-0 block">
                    <img
                        src="<?php echo e($acc->featured_image_url ?? ($acc->images->first()->url ?? asset('images/placeholder-wide.jpg'))); ?>"
                        alt="<?php echo e($acc->name); ?>"
                        class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>

                    <div class="absolute bottom-8 left-4 md:left-12 text-white max-w-2xl">
                        <p class="text-xs md:text-sm uppercase tracking-wider text-green-300 mb-1">
                            <?php echo e(ucfirst($acc->category ?? 'Accommodation')); ?>

                            <?php if($acc->country): ?> · <?php echo e($acc->country->name); ?> <?php endif; ?>
                            <?php if($acc->destination): ?> · <?php echo e($acc->destination->name); ?> <?php endif; ?>
                        </p>
                        <h2 class="text-2xl md:text-3xl font-bold mb-2">
                            <?php echo e($acc->name); ?>

                        </h2>
                        <?php if($acc->meta_description): ?>
                            <p class="text-sm md:text-base text-gray-100 line-clamp-3">
                                <?php echo e(Str::limit($acc->meta_description, 150)); ?>

                            </p>
                        <?php elseif($acc->short_description): ?>
                            <p class="text-sm md:text-base text-gray-100 line-clamp-3">
                                <?php echo e($acc->short_description); ?>

                            </p>
                        <?php endif; ?>
                        <?php if($acc->price_from || $acc->price_to): ?>
                            <p class="mt-3 text-sm md:text-base font-semibold text-green-200">
                                From <?php echo e($acc->currency ?? 'USD'); ?>

                                <?php echo e(number_format($acc->price_from ?? 0)); ?>

                                <?php if($acc->price_to): ?>
                                    – <?php echo e(number_format($acc->price_to)); ?>

                                <?php endif; ?>
                                <span class="font-normal text-xs text-gray-200">per person / night</span>
                            </p>
                        <?php endif; ?>
                    </div>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            
            <button type="button"
                    @click="current = current === 0 ? <?php echo e($sliderItems->count() - 1); ?> : current - 1"
                    class="absolute left-2 md:left-4 top-1/2 -translate-y-1/2 bg-black/40 hover:bg-black/70 text-white rounded-full w-9 h-9 flex items-center justify-center text-lg">
                ‹
            </button>
            <button type="button"
                    @click="current = current === <?php echo e($sliderItems->count() - 1); ?> ? 0 : current + 1"
                    class="absolute right-2 md:right-4 top-1/2 -translate-y-1/2 bg-black/40 hover:bg-black/70 text-white rounded-full w-9 h-9 flex items-center justify-center text-lg">
                ›
            </button>

            
            <div class="absolute bottom-4 inset-x-0 flex justify-center space-x-1.5">
                <?php $__currentLoopData = $sliderItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $acc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <button type="button"
                            @click="current = <?php echo e($idx); ?>"
                            :class="current === <?php echo e($idx); ?> ? 'bg-white' : 'bg-white/50'"
                            class="w-3 h-3 rounded-full border border-white/70"></button>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="max-w-6xl mx-auto px-4 py-8 pt-0">

    
    <form id="filtersForm" method="GET" action="<?php echo e(route('accommodations.index')); ?>"
          class="mb-6 bg-white rounded-xl shadow px-4 py-3">
        <div class="flex flex-wrap items-end gap-4">

            
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Category</label>
                <select name="category"
                        class="px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-green-500 live-filter">
                    <option value="">All</option>
                    <option value="budget"    <?php echo e(request('category') === 'budget' ? 'selected' : ''); ?>>Budget</option>
                    <option value="mid-range" <?php echo e(request('category') === 'mid-range' ? 'selected' : ''); ?>>Mid-range</option>
                    <option value="high-end"  <?php echo e(request('category') === 'high-end' ? 'selected' : ''); ?>>High-end / Luxury</option>
                </select>
            </div>

            
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Type</label>
                <select name="type"
                        class="px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-green-500 live-filter">
                    <option value="">All</option>
                    <option value="Lodge"       <?php echo e(request('type') === 'Lodge' ? 'selected' : ''); ?>>Lodge</option>
                    <option value="Tented Camp" <?php echo e(request('type') === 'Tented Camp' ? 'selected' : ''); ?>>Tented Camp</option>
                    <option value="Hotel"       <?php echo e(request('type') === 'Hotel' ? 'selected' : ''); ?>>Hotel</option>
                    <option value="Guesthouse"  <?php echo e(request('type') === 'Guesthouse' ? 'selected' : ''); ?>>Guesthouse</option>
                    <option value="City Hotel"  <?php echo e(request('type') === 'City Hotel' ? 'selected' : ''); ?>>City Hotel</option>
                </select>
            </div>

            
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Country</label>
                <select name="country"
                        class="px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-green-500 live-filter">
                    <option value="">All Countries</option>
                    <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($country->name); ?>" <?php echo e(request('country') == $country->name ? 'selected' : ''); ?>>
                            <?php echo e($country->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            
            <div class="ml-auto">
                <a href="<?php echo e(route('accommodations.index')); ?>"
                   class="inline-flex items-center px-3 py-2 bg-gray-100 text-gray-700 text-xs font-semibold rounded-lg hover:bg-gray-200">
                    Reset
                </a>
            </div>
        </div>
    </form>

    
    <?php if($accommodations->count()): ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php $__currentLoopData = $accommodations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $acc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <article class="bg-white rounded-xl shadow hover:shadow-lg transition flex flex-col overflow-hidden">
                    <a href="<?php echo e(route('accommodations.show', $acc->slug)); ?>" class="block relative h-44 bg-gray-100">
                        <img
                            src="<?php echo e($acc->featured_image_url ?? ($acc->images->first()->url ?? asset('images/placeholder-wide.jpg'))); ?>"
                            alt="<?php echo e($acc->name); ?>"
                            class="w-full h-full object-cover">
                        <?php if($acc->category): ?>
                            <span class="absolute top-2 left-2 text-xs px-2 py-0.5 rounded-full
                                <?php if($acc->category === 'budget'): ?> bg-blue-100 text-blue-800
                                <?php elseif($acc->category === 'mid-range'): ?> bg-green-100 text-green-800
                                <?php else: ?> bg-purple-100 text-purple-800 <?php endif; ?>">
                                <?php echo e(ucfirst($acc->category)); ?>

                            </span>
                        <?php endif; ?>
                        <?php if($acc->is_featured): ?>
                            <span class="absolute top-2 right-2 text-xs px-2 py-0.5 rounded-full bg-yellow-100 text-yellow-800">
                                <i class="fas fa-star mr-1"></i> Featured
                            </span>
                        <?php endif; ?>
                    </a>

                    <div class="flex-1 flex flex-col p-4">
                        <a href="<?php echo e(route('accommodations.show', $acc->slug)); ?>"
                           class="text-base font-semibold text-gray-900 hover:text-green-700">
                            <?php echo e($acc->name); ?>

                        </a>
                        <p class="text-xs text-gray-500 mt-1">
                            <?php if($acc->country): ?> <?php echo e($acc->country->name); ?> <?php endif; ?>
                            <?php if($acc->country && $acc->destination): ?> · <?php endif; ?>
                            <?php if($acc->destination): ?> <?php echo e($acc->destination->name); ?> <?php endif; ?>
                            <?php if($acc->type): ?> · <?php echo e($acc->type); ?> <?php endif; ?>
                        </p>

                        <?php if($acc->meta_description): ?>
                            <p class="mt-2 text-sm text-gray-600 line-clamp-3">
                                <?php echo e(Str::limit($acc->meta_description, 120)); ?>

                            </p>
                        <?php elseif($acc->short_description): ?>
                            <p class="mt-2 text-sm text-gray-600 line-clamp-3">
                                <?php echo e($acc->short_description); ?>

                            </p>
                        <?php endif; ?>

                        <div class="mt-3 flex items-center justify-between">
                            <?php if($acc->price_from || $acc->price_to): ?>
                                <p class="text-sm font-semibold text-green-700">
                                    <?php echo e($acc->currency ?? 'USD'); ?>

                                    <?php echo e(number_format($acc->price_from ?? 0)); ?>

                                    <?php if($acc->price_to): ?>
                                        – <?php echo e(number_format($acc->price_to)); ?>

                                    <?php endif; ?>
                                </p>
                            <?php else: ?>
                                <p class="text-xs text-gray-400">Price on request</p>
                            <?php endif; ?>

                            <a href="<?php echo e(route('accommodations.show', $acc->slug)); ?>"
                               class="text-xs font-semibold text-green-700 hover:text-green-800">
                                View details →
                            </a>
                        </div>
                    </div>
                </article>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="mt-8">
            <?php echo e($accommodations->withQueryString()->links()); ?>

        </div>
    <?php else: ?>
        <div class="text-center py-12">
            <p class="text-sm text-gray-500">No accommodations found for these filters.</p>
            <a href="<?php echo e(route('accommodations.index')); ?>" class="text-sm text-green-600 hover:text-green-700 mt-2 inline-block">
                Clear all filters
            </a>
        </div>
    <?php endif; ?>
</div>


<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<script>
    // LIVE FILTERING: auto-submit on change
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('filtersForm');
        if (!form) return;

        const selects = form.querySelectorAll('.live-filter');

        // Auto-submit on select change
        selects.forEach(sel => {
            sel.addEventListener('change', () => {
                form.submit();
            });
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\safaris\resources\views/accommodations/index.blade.php ENDPATH**/ ?>