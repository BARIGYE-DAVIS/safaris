

<?php $__env->startSection('title', 'Destinations'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Destinations</h1>
        <a href="<?php echo e(route('admin.destinations.create')); ?>" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg flex items-center gap-2 transition shadow-md">
            <i class="fas fa-plus"></i> Add New Destination
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-5 mb-6">
        <form method="GET" action="<?php echo e(route('admin.destinations.index')); ?>">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <input type="text" name="search" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent" placeholder="Search destinations..." value="<?php echo e(request('search')); ?>">
                </div>
                <div>
                    <select name="country_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="">All Countries</option>
                        <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($country->id); ?>" <?php echo e(request('country_id') == $country->id ? 'selected' : ''); ?>>
                                <?php echo e($country->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="">All Status</option>
                        <option value="active" <?php echo e(request('status') == 'active' ? 'selected' : ''); ?>>Active</option>
                        <option value="inactive" <?php echo e(request('status') == 'inactive' ? 'selected' : ''); ?>>Inactive</option>
                    </select>
                </div>
                <div>
                    <select name="popular" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="">All Popularity</option>
                        <option value="yes" <?php echo e(request('popular') == 'yes' ? 'selected' : ''); ?>>Popular</option>
                        <option value="no" <?php echo e(request('popular') == 'no' ? 'selected' : ''); ?>>Regular</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg transition flex-1">
                        Filter
                    </button>
                    <a href="<?php echo e(route('admin.destinations.index')); ?>" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Destinations Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-5">
            <?php if(session('success')): ?>
                <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded">
                    <p class="flex items-center gap-2">
                        <i class="fas fa-check-circle"></i>
                        <?php echo e(session('success')); ?>

                    </p>
                </div>
            <?php endif; ?>
            <?php if(session('error')): ?>
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded">
                    <p class="flex items-center gap-2">
                        <i class="fas fa-exclamation-circle"></i>
                        <?php echo e(session('error')); ?>

                    </p>
                </div>
            <?php endif; ?>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left">
                                <input type="checkbox" id="select-all" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Image</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Country</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Slug</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Popular</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Sort Order</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php $__empty_1 = true; $__currentLoopData = $destinations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $destination): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <input type="checkbox" class="select-item rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" value="<?php echo e($destination->id); ?>">
                            </td>
                            <td class="px-6 py-4">
                                <?php if($destination->image): ?>
                                    <img src="<?php echo e(asset('storage/' . $destination->image)); ?>" alt="<?php echo e($destination->name); ?>" class="w-16 h-16 object-cover rounded-lg shadow">
                                <?php else: ?>
                                    <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-image text-gray-400"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-semibold text-gray-900"><?php echo e($destination->name); ?></div>
                            </td>
                            <td class="px-6 py-4">
                                <?php if($destination->country): ?>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                        <?php echo e($destination->country->flag_icon); ?> <?php echo e($destination->country->name); ?>

                                    </span>
                                <?php else: ?>
                                    <span class="text-gray-400 text-sm">No country</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs text-gray-500"><?php echo e($destination->slug); ?></span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium <?php echo e($destination->is_popular ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800'); ?>">
                                    <?php echo e($destination->is_popular ? '⭐ Popular' : 'Regular'); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium <?php echo e($destination->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'); ?>">
                                    <?php echo e($destination->is_active ? '✓ Active' : 'Inactive'); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                <?php echo e($destination->sort_order); ?>

                            </td>
                            <td class="px-6 py-4 text-sm font-medium space-x-2">
                                <a href="<?php echo e(route('admin.destinations.edit', $destination)); ?>" class="inline-flex items-center px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded transition">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="<?php echo e(route('admin.destinations.toggle-status', $destination)); ?>" method="POST" class="inline">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PATCH'); ?>
                                    <button type="submit" class="inline-flex items-center px-3 py-1 <?php echo e($destination->is_active ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-green-600 hover:bg-green-700'); ?> text-white rounded transition" title="Toggle Status">
                                        <i class="fas fa-<?php echo e($destination->is_active ? 'eye-slash' : 'eye'); ?>"></i>
                                    </button>
                                </form>
                                <form action="<?php echo e(route('admin.destinations.toggle-popular', $destination)); ?>" method="POST" class="inline">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PATCH'); ?>
                                    <button type="submit" class="inline-flex items-center px-3 py-1 <?php echo e($destination->is_popular ? 'bg-gray-600 hover:bg-gray-700' : 'bg-yellow-600 hover:bg-yellow-700'); ?> text-white rounded transition" title="Toggle Popular">
                                        <i class="fas fa-star"></i>
                                    </button>
                                </form>
                                <form action="<?php echo e(route('admin.destinations.destroy', $destination)); ?>" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="inline-flex items-center px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded transition">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-map-marker-alt text-4xl mb-3 text-gray-300"></i>
                                <p class="text-lg">No destinations found</p>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                <?php echo e($destinations->links()); ?>

            </div>
        </div>
    </div>

    <!-- Bulk Actions -->
    <div class="mt-4">
        <form action="<?php echo e(route('admin.destinations.bulk-delete')); ?>" method="POST" id="bulk-delete-form" onsubmit="return confirm('Are you sure?')">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="ids" id="bulk-ids">
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-5 py-2 rounded-lg flex items-center gap-2 transition">
                <i class="fas fa-trash"></i> Delete Selected
            </button>
        </form>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    // Select all checkboxes
    document.getElementById('select-all').addEventListener('change', function() {
        let checkboxes = document.querySelectorAll('.select-item');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });

    // Bulk delete
    document.getElementById('bulk-delete-form').addEventListener('submit', function(e) {
        let selected = Array.from(document.querySelectorAll('.select-item:checked')).map(cb => cb.value);
        if (selected.length === 0) {
            e.preventDefault();
            alert('Please select at least one destination');
            return false;
        }
        document.getElementById('bulk-ids').value = JSON.stringify(selected);
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\safaris\resources\views/admin/destinations/index.blade.php ENDPATH**/ ?>