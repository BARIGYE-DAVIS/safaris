

<?php $__env->startSection('title', 'Accommodations'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            <i class="fas fa-hotel mr-2"></i>Accommodations
        </h1>
        <a href="<?php echo e(route('admin.accommodations.create')); ?>"
           class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-green-600 rounded hover:bg-green-700">
            <i class="fas fa-plus mr-2"></i> New Accommodation
        </a>
    </div>

    <?php if(session('success')): ?>
        <div class="mb-4 bg-green-50 border-l-4 border-green-400 p-4 text-green-800 text-sm">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    
    <form method="GET" action="<?php echo e(route('admin.accommodations.index')); ?>" class="mb-4 bg-white rounded-lg shadow p-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">

            
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">
                    Search (name, type, location)
                </label>
                <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                       placeholder="Search..."
                       class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-green-500">
            </div>

            
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Country</label>
                <select name="country_id"
                        class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-green-500">
                    <option value="">All Countries</option>
                    <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($country->id); ?>"
                                <?php echo e(request('country_id') == $country->id ? 'selected' : ''); ?>>
                            <?php echo e($country->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Destination</label>
                <select name="destination_id"
                        class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-green-500">
                    <option value="">All Destinations</option>
                    <?php $__currentLoopData = $destinations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $destination): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($destination->id); ?>"
                                <?php echo e(request('destination_id') == $destination->id ? 'selected' : ''); ?>>
                            <?php echo e($destination->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            
            <div class="flex items-end space-x-2">

                
                <div class="flex-1">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Status</label>
                    <select name="is_active"
                            class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-green-500">
                        <option value="">All</option>
                        <option value="1" <?php echo e(request('is_active') === '1' ? 'selected' : ''); ?>>Active</option>
                        <option value="0" <?php echo e(request('is_active') === '0' ? 'selected' : ''); ?>>Inactive</option>
                    </select>
                </div>

                
                <div class="flex-1">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Category</label>
                    <select name="category"
                            class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-green-500">
                        <option value="">All Categories</option>
                        <option value="budget"    <?php echo e(request('category') === 'budget'    ? 'selected' : ''); ?>>Budget</option>
                        <option value="mid-range" <?php echo e(request('category') === 'mid-range' ? 'selected' : ''); ?>>Mid-range</option>
                        <option value="high-end"  <?php echo e(request('category') === 'high-end'  ? 'selected' : ''); ?>>High-end / Luxury</option>
                    </select>
                </div>

                
                <div class="flex space-x-2 pb-0.5">
                    <button type="submit"
                            class="inline-flex items-center px-3 py-2 bg-green-600 text-white text-xs font-semibold rounded-lg hover:bg-green-700">
                        <i class="fas fa-filter mr-1"></i> Filter
                    </button>
                    <a href="<?php echo e(route('admin.accommodations.index')); ?>"
                       class="inline-flex items-center px-3 py-2 bg-gray-200 text-gray-700 text-xs font-semibold rounded-lg hover:bg-gray-300">
                        <i class="fas fa-times mr-1"></i> Reset
                    </a>
                </div>

            </div>
        </div>
    </form>

    
    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full text-sm text-gray-800">
            <thead class="bg-gray-100 text-xs font-semibold uppercase text-gray-600">
                <tr>
                    <th class="px-4 py-3 text-left">Name</th>
                    <th class="px-4 py-3 text-left">Type</th>
                    <th class="px-4 py-3 text-left">Category</th>
                    <th class="px-4 py-3 text-left">Location</th>
                    <th class="px-4 py-3 text-left">Country / Destination</th>
                    <th class="px-4 py-3 text-left">Price Range</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $accommodations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $accommodation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="border-t border-gray-100 hover:bg-gray-50">

                        
                        <td class="px-4 py-3">
                            <div class="flex items-center space-x-3">
                                <?php if($accommodation->featured_image): ?>
                                    <img src="<?php echo e($accommodation->featured_image_url); ?>"
                                         alt="<?php echo e($accommodation->name); ?>"
                                         class="w-10 h-10 rounded object-cover">
                                <?php else: ?>
                                    <div class="w-10 h-10 rounded bg-gray-200 flex items-center justify-center text-xs text-gray-500">
                                        N/A
                                    </div>
                                <?php endif; ?>
                                <div>
                                    <div class="font-semibold"><?php echo e($accommodation->name); ?></div>
                                    <div class="text-xs text-gray-500">Slug: <?php echo e($accommodation->slug); ?></div>
                                </div>
                            </div>
                        </td>

                        
                        <td class="px-4 py-3">
                            <span class="text-xs px-2 py-1 rounded bg-gray-100 text-gray-700">
                                <?php echo e($accommodation->type ?? '—'); ?>

                            </span>
                        </td>

                        
                        <td class="px-4 py-3">
                            <?php $cat = $accommodation->category; ?>
                            <?php if($cat): ?>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs
                                    <?php if($cat === 'budget'): ?> bg-blue-100 text-blue-800
                                    <?php elseif($cat === 'mid-range'): ?> bg-green-100 text-green-800
                                    <?php else: ?> bg-purple-100 text-purple-800
                                    <?php endif; ?>">
                                    <?php echo e(ucfirst($cat)); ?>

                                </span>
                            <?php else: ?>
                                <span class="text-xs text-gray-400">Not set</span>
                            <?php endif; ?>
                        </td>

                        
                        <td class="px-4 py-3">
                            <div class="text-xs text-gray-700"><?php echo e($accommodation->location ?? '—'); ?></div>
                        </td>

                        
                        <td class="px-4 py-3">
                            <div class="text-xs text-gray-700">
                                <?php if($accommodation->country): ?>
                                    <span class="font-semibold"><?php echo e($accommodation->country->name); ?></span>
                                <?php else: ?>
                                    <span class="text-gray-400">No country</span>
                                <?php endif; ?>
                            </div>
                            <div class="text-xs text-gray-500">
                                <?php if($accommodation->destination): ?>
                                    Destination: <?php echo e($accommodation->destination->name); ?>

                                <?php else: ?>
                                    <span class="text-gray-400">No destination</span>
                                <?php endif; ?>
                            </div>
                        </td>

                        
                        <td class="px-4 py-3">
                            <div class="text-xs text-gray-700">
                                <?php if($accommodation->display_price_range): ?>
                                    <?php echo e($accommodation->display_price_range); ?>

                                <?php else: ?>
                                    <span class="text-gray-400">Not set</span>
                                <?php endif; ?>
                            </div>
                        </td>

                        
                        <td class="px-4 py-3">
                            <div class="flex flex-col space-y-1 text-xs">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full
                                    <?php echo e($accommodation->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'); ?>">
                                    <span class="w-1.5 h-1.5 mr-1 rounded-full
                                        <?php echo e($accommodation->is_active ? 'bg-green-500' : 'bg-red-500'); ?>"></span>
                                    <?php echo e($accommodation->is_active ? 'Active' : 'Inactive'); ?>

                                </span>
                                <?php if($accommodation->is_featured): ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-star mr-1 text-xs"></i> Featured
                                    </span>
                                <?php endif; ?>
                            </div>
                        </td>

                        
                        <td class="px-4 py-3 text-right">
                            <div class="inline-flex items-center space-x-2">
                                <a href="<?php echo e(route('admin.accommodations.edit', $accommodation)); ?>"
                                   class="inline-flex items-center px-2 py-1 text-xs font-semibold text-blue-700 bg-blue-100 rounded hover:bg-blue-200">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </a>
                                <form action="<?php echo e(route('admin.accommodations.destroy', $accommodation)); ?>"
                                      method="POST"
                                      onsubmit="return confirm('Are you sure you want to delete this accommodation?');">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit"
                                            class="inline-flex items-center px-2 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded hover:bg-red-200">
                                        <i class="fas fa-trash mr-1"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </td>

                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        
                        <td colspan="8" class="px-4 py-6 text-center text-sm text-gray-500">
                            No accommodations found.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    
    <div class="mt-4">
        <?php echo e($accommodations->withQueryString()->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\safaris\resources\views/admin/accommodations/index.blade.php ENDPATH**/ ?>