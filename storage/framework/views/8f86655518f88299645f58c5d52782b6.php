<?php $__env->startSection('title', 'Edit Accommodation'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 py-6 pb-28">

    
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            <i class="fas fa-hotel mr-2"></i>Edit Accommodation
        </h1>
        <a href="<?php echo e(route('admin.accommodations.index')); ?>"
           class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded hover:bg-gray-300">
            <i class="fas fa-arrow-left mr-2"></i> Back to list
        </a>
    </div>

    <?php if(session('success')): ?>
        <div class="mb-4 bg-green-50 border-l-4 border-green-400 p-4 text-green-700 text-sm">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <div class="mb-4 bg-red-50 border-l-4 border-red-400 p-4 text-red-700 text-sm">
            <p class="font-bold mb-1">There were some problems with your input:</p>
            <ul class="list-disc list-inside">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?php echo e(route('admin.accommodations.update', $accommodation->id)); ?>" 
          method="POST" 
          enctype="multipart/form-data"
          class="space-y-6" 
          id="accommodation-form">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-5 py-4 border-b border-gray-100">
                <h2 class="font-semibold text-gray-700">Featured Image</h2>
            </div>
            <div class="p-5">
                <?php if($accommodation->featured_image): ?>
                    <div id="featured-preview" class="mb-3">
                        <img id="featured-preview-img"
                             src="<?php echo e(asset('storage/' . $accommodation->featured_image)); ?>"
                             class="w-full rounded-lg object-cover border border-gray-200"
                             style="max-height:180px;">
                        <button type="button" onclick="removeFeaturedImage()"
                                class="mt-2 text-xs text-red-500 hover:text-red-700">Remove image</button>
                    </div>
                <?php endif; ?>
                <label id="featured-drop-zone"
                       class="block w-full border-2 border-dashed border-gray-300 rounded-xl p-6 text-center cursor-pointer hover:border-blue-400 hover:bg-blue-50 transition">
                    <input type="file" name="featured_image" id="featured-image-input"
                           class="hidden" accept="image/*"
                           onchange="previewFeaturedImage(this)">
                    <div id="featured-upload-prompt" class="<?php echo e($accommodation->featured_image ? 'hidden' : ''); ?>">
                        <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                        <p class="text-gray-700 text-sm font-medium">Click to upload featured image</p>
                        <p class="text-gray-400 text-xs mt-1">JPG, PNG, WEBP (Max 4MB)</p>
                    </div>
                </label>
            </div>
        </div>

        
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-5 py-4 border-b border-gray-100">
                <h2 class="font-semibold text-gray-700">Basic Information</h2>
            </div>
            <div class="p-5 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name-input" value="<?php echo e(old('name', $accommodation->name)); ?>" required
                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Type</label>
                        <select name="type" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                            <option value="">Select Type</option>
                            <option value="Lodge"       <?php echo e(old('type', $accommodation->type) == 'Lodge' ? 'selected' : ''); ?>>Lodge</option>
                            <option value="Tented Camp" <?php echo e(old('type', $accommodation->type) == 'Tented Camp' ? 'selected' : ''); ?>>Tented Camp</option>
                            <option value="Hotel"       <?php echo e(old('type', $accommodation->type) == 'Hotel' ? 'selected' : ''); ?>>Hotel</option>
                            <option value="Guesthouse"  <?php echo e(old('type', $accommodation->type) == 'Guesthouse' ? 'selected' : ''); ?>>Guesthouse</option>
                            <option value="City Hotel"  <?php echo e(old('type', $accommodation->type) == 'City Hotel' ? 'selected' : ''); ?>>City Hotel</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            Category <span class="text-red-500">*</span>
                        </label>
                        <select name="category" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500" required>
                            <option value="">Select Category</option>
                            <option value="budget"    <?php echo e(old('category', $accommodation->category) == 'budget' ? 'selected' : ''); ?>>Budget</option>
                            <option value="mid-range" <?php echo e(old('category', $accommodation->category) == 'mid-range' ? 'selected' : ''); ?>>Mid-range</option>
                            <option value="high-end"  <?php echo e(old('category', $accommodation->category) == 'high-end' ? 'selected' : ''); ?>>High-end / Luxury</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            Location (short text)
                        </label>
                        <input type="text" name="location" value="<?php echo e(old('location', $accommodation->location)); ?>"
                               placeholder="e.g. Inside Park A, near main gate"
                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Country</label>
                        <select name="country_id" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                            <option value="">Select Country</option>
                            <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($country->id); ?>" <?php echo e(old('country_id', $accommodation->country_id) == $country->id ? 'selected' : ''); ?>>
                                    <?php echo e($country->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Destination (optional)</label>
                        <select name="destination_id" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                            <option value="">Select Destination</option>
                            <?php $__currentLoopData = $destinations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $destination): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($destination->id); ?>" <?php echo e(old('destination_id', $accommodation->destination_id) == $destination->id ? 'selected' : ''); ?>>
                                    <?php echo e($destination->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-5 py-4 border-b border-gray-100">
                <h2 class="font-semibold text-gray-700">Pricing</h2>
            </div>
            <div class="p-5">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Currency</label>
                        <input type="text" name="currency" value="<?php echo e(old('currency', $accommodation->currency ?? 'USD')); ?>"
                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Price From (per person/night)</label>
                        <input type="number" step="0.01" min="0" name="price_from" value="<?php echo e(old('price_from', $accommodation->price_from)); ?>"
                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Price To (per person/night)</label>
                        <input type="number" step="0.01" min="0" name="price_to" value="<?php echo e(old('price_to', $accommodation->price_to)); ?>"
                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                    </div>
                </div>
            </div>
        </div>

        
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-5 py-4 border-b border-gray-100">
                <h2 class="font-semibold text-gray-700">SEO Information</h2>
            </div>
            <div class="p-5 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">SEO Title</label>
                    <input type="text" name="meta_title"
                           value="<?php echo e(old('meta_title', $accommodation->meta_title)); ?>"
                           placeholder="e.g., Luxury Safari Lodge in Bwindi - Uganda"
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Meta Description</label>
                    <textarea name="meta_description" rows="2" maxlength="320"
                              placeholder="160–320 characters shown in Google search results"
                              class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500"><?php echo e(old('meta_description', $accommodation->meta_description)); ?></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Focus Keyword</label>
                    <input type="text" name="focus_keyword"
                           value="<?php echo e(old('focus_keyword', $accommodation->focus_keyword)); ?>"
                           placeholder="e.g., luxury safari lodge Bwindi"
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
            </div>
        </div>

        
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-5 py-4 border-b border-gray-100">
                <h2 class="font-semibold text-gray-700">Page Content</h2>
                <p class="text-xs text-gray-400 mt-0.5">
                    <strong>Right-click</strong> on any icon, quote, or link to remove it. 
                    Or use <strong>Delete/Backspace</strong> key to remove selected elements.
                    <span class="inline-flex items-center gap-1 ml-2">
                        <span class="text-xs bg-yellow-100 px-2 py-0.5 rounded-full">💬 Quote</span>
                        <span class="text-xs bg-blue-100 px-2 py-0.5 rounded-full">🔗 Link</span>
                        <span class="text-xs bg-pink-100 px-2 py-0.5 rounded-full"><i class="fas fa-icons"></i> Icon</span>
                    </span>
                </p>
            </div>
            <div class="p-5">
                <div id="blocks-container">
                    <?php
                        $existingBlocks = $accommodation->content_blocks ?? [];
                        if (is_string($existingBlocks)) {
                            $existingBlocks = json_decode($existingBlocks, true);
                        }
                        if (!is_array($existingBlocks)) {
                            $existingBlocks = [];
                        }
                    ?>

                    <?php if(count($existingBlocks) === 0): ?>
                        <div id="blocks-empty-msg"
                             class="text-center py-10 text-gray-400 border-2 border-dashed border-gray-200 rounded-lg">
                            <p class="text-sm">Your page is empty.</p>
                            <p class="text-xs mt-1">Use the toolbar at the bottom to add content blocks.</p>
                        </div>
                    <?php else: ?>
                        <?php $__currentLoopData = $existingBlocks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $block): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $type = $block['type'] ?? 'text';
                                $blockId = $block['id'] ?? 'blk-' . Str::random(8);
                            ?>
                            <div class="block-item border border-gray-200 rounded-xl p-4 mb-3 bg-gray-50"
                                 data-block-id="<?php echo e($blockId); ?>"
                                 data-index="<?php echo e($index); ?>">
                                <input type="hidden" name="blocks[<?php echo e($index); ?>][id]" value="<?php echo e($blockId); ?>">
                                <input type="hidden" name="blocks[<?php echo e($index); ?>][type]" value="<?php echo e($type); ?>">

                                
                                <?php if($type === 'heading'): ?>
                                    <div class="flex items-center justify-between mb-3">
                                        <span class="text-xs font-semibold uppercase tracking-wide text-gray-500">Heading</span>
                                        <button type="button" onclick="removeBlock(this)" class="text-xs text-red-500 hover:text-red-700">Remove</button>
                                    </div>
                                    <div class="flex gap-3 mb-3">
                                        <div class="w-36">
                                            <label class="block text-xs text-gray-500 mb-1">Level</label>
                                            <select name="blocks[<?php echo e($index); ?>][heading_level]" onchange="updateHeadingPreview(this)"
                                                    class="w-full border border-gray-300 rounded-lg px-2 py-1.5 text-sm">
                                                <?php $__currentLoopData = ['h1','h2','h3','h4','h5','h6']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($hl); ?>" <?php echo e(($block['heading_level'] ?? 'h2') === $hl ? 'selected' : ''); ?>>
                                                        <?php echo e(strtoupper($hl)); ?>

                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                        <div class="flex-1">
                                            <label class="block text-xs text-gray-500 mb-1">Heading Text</label>
                                            <input type="text" name="blocks[<?php echo e($index); ?>][content]"
                                                   class="heading-text w-full border border-gray-300 rounded-lg px-3 py-1.5 text-sm"
                                                   value="<?php echo e($block['content'] ?? ''); ?>"
                                                   placeholder="Enter heading text" oninput="updateHeadingPreview(this)">
                                        </div>
                                    </div>
                                    <div class="heading-preview px-3 py-2 bg-white rounded-lg border border-dashed border-gray-300 text-gray-400 text-sm italic">
                                        <?php
                                            $level = $block['heading_level'] ?? 'h2';
                                            $text = $block['content'] ?: 'Preview appears here...';
                                            $styles = [
                                                'h1' => 'text-3xl font-bold text-gray-900',
                                                'h2' => 'text-2xl font-bold text-gray-800',
                                                'h3' => 'text-xl font-semibold text-gray-800',
                                                'h4' => 'text-lg font-semibold text-gray-700',
                                                'h5' => 'text-base font-semibold text-gray-700',
                                                'h6' => 'text-sm font-semibold text-gray-600',
                                            ];
                                        ?>
                                        <<?php echo e($level); ?> class="<?php echo e($styles[$level] ?? $styles['h2']); ?> not-italic"><?php echo e($text); ?></<?php echo e($level); ?>>
                                    </div>

                                
                                <?php elseif($type === 'text'): ?>
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-xs font-semibold uppercase tracking-wide text-gray-500">Paragraph</span>
                                        <button type="button" onclick="removeBlock(this)" class="text-xs text-red-500 hover:text-red-700">Remove</button>
                                    </div>
                                    <div contenteditable="true"
                                         data-block-type="text"
                                         data-index="<?php echo e($index); ?>"
                                         data-placeholder="Write your paragraph here..."
                                         class="paragraph-editor w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500"
                                         onfocus="setCurrentEditor(this)"
                                         onclick="setCurrentEditor(this)"
                                         onkeyup="saveSelection()"
                                         onmouseup="saveSelection()"
                                         oninput="syncContent(this,<?php echo e($index); ?>)"
                                         oncontextmenu="showRemoveContextMenu(event, this)"><?php echo $block['content'] ?? ''; ?></div>
                                    <input type="hidden" name="blocks[<?php echo e($index); ?>][content]" id="content-<?php echo e($index); ?>" value="<?php echo e($block['content'] ?? ''); ?>">

                                
                                <?php elseif($type === 'list'): ?>
                                    <div class="flex items-center justify-between mb-3">
                                        <span class="text-xs font-semibold uppercase tracking-wide text-gray-500">📋 List</span>
                                        <button type="button" onclick="removeBlock(this)" class="text-xs text-red-500 hover:text-red-700">Remove</button>
                                    </div>
                                    <div class="flex gap-3 mb-3">
                                        <div class="w-36">
                                            <label class="block text-xs text-gray-500 mb-1">Type</label>
                                            <select name="blocks[<?php echo e($index); ?>][list_type]" class="w-full border border-gray-300 rounded-lg px-2 py-1.5 text-sm">
                                                <option value="ul" <?php echo e(($block['list_type'] ?? 'ul') === 'ul' ? 'selected' : ''); ?>>Bullet List</option>
                                                <option value="ol" <?php echo e(($block['list_type'] ?? 'ul') === 'ol' ? 'selected' : ''); ?>>Numbered List</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div contenteditable="true"
                                         data-block-type="list"
                                         data-index="<?php echo e($index); ?>"
                                         class="paragraph-editor w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 min-h-[120px]"
                                         onfocus="setCurrentEditor(this)"
                                         onclick="setCurrentEditor(this)"
                                         onkeyup="saveSelection()"
                                         onmouseup="saveSelection()"
                                         oninput="syncContent(this,<?php echo e($index); ?>)"
                                         oncontextmenu="showRemoveContextMenu(event, this)"
                                         placeholder="• Item 1&#10;• Item 2&#10;• Item 3"><?php echo $block['content'] ?? ''; ?></div>
                                    <input type="hidden" name="blocks[<?php echo e($index); ?>][content]" id="content-<?php echo e($index); ?>" value="<?php echo e($block['content'] ?? ''); ?>">

                                
                                <?php elseif($type === 'image'): ?>
                                    <?php
                                        $blockImages = $accommodation->images->filter(function($img) use ($blockId) {
                                            return $img->block_id === $blockId;
                                        });
                                    ?>
                                    <div class="flex items-center justify-between mb-3">
                                        <span class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                                            Images <span id="img-count-<?php echo e($index); ?>" class="text-blue-500"></span>
                                        </span>
                                        <button type="button" onclick="removeBlock(this)" class="text-xs text-red-500 hover:text-red-700">Remove</button>
                                    </div>

                                    <?php if($blockImages->count() > 0): ?>
                                        <div class="mb-3">
                                            <label class="block text-xs text-gray-500 mb-2">Existing Images</label>
                                            <div class="grid grid-cols-3 gap-2" id="existing-img-grid-<?php echo e($index); ?>">
                                                <?php $__currentLoopData = $blockImages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <div class="relative rounded-lg overflow-hidden border border-gray-200 bg-white existing-image-item"
                                                         data-image-id="<?php echo e($image->id); ?>">
                                                        <img src="<?php echo e(asset('storage/' . $image->path)); ?>"
                                                             class="w-full object-cover" style="height: 90px;">
                                                        <button type="button"
                                                                class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-5 h-5 text-xs flex items-center justify-center hover:bg-red-600"
                                                                onclick="deleteBlockImage(this, <?php echo e($index); ?>, <?php echo e($image->id); ?>)">&times;</button>
                                                        <input type="text"
                                                               name="blocks[<?php echo e($index); ?>][existing_alts][<?php echo e($image->id); ?>]"
                                                               value="<?php echo e($image->alt_text); ?>"
                                                               placeholder="Alt text"
                                                               class="w-full text-xs border-t border-gray-200 px-1 py-0.5 focus:outline-none">
                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <div id="img-grid-<?php echo e($index); ?>" class="grid grid-cols-3 gap-3 mb-3"></div>
                                    <div id="img-transfer-<?php echo e($index); ?>"></div>

                                    <label class="block w-full border-2 border-dashed border-gray-300 rounded-xl p-5 text-center cursor-pointer hover:border-blue-400 hover:bg-blue-50 transition">
                                        <input type="file" class="hidden" accept="image/jpeg,image/png,image/webp" multiple
                                               onchange="accumulateImages(this,<?php echo e($index); ?>)">
                                        <p class="text-gray-500 text-sm">Click to add new images</p>
                                        <p class="text-gray-400 text-xs mt-1">Click multiple times to add more</p>
                                    </label>

                                    <script>if (!window.blockFiles) window.blockFiles = {}; window.blockFiles[<?php echo e($index); ?>] = window.blockFiles[<?php echo e($index); ?>] || [];</script>

                                
                                <?php elseif($type === 'table'): ?>
                                    <div class="flex items-center justify-between mb-3">
                                        <span class="text-xs font-semibold uppercase tracking-wide text-gray-500">📊 Table</span>
                                        <button type="button" onclick="removeBlock(this)" class="text-xs text-red-500 hover:text-red-700">Remove</button>
                                    </div>
                                    
                                    <div class="space-y-3">
                                        <div class="flex flex-wrap gap-3">
                                            <div class="flex-1 min-w-[200px]">
                                                <label class="block text-xs text-gray-500 mb-1">Caption</label>
                                                <input type="text" name="blocks[<?php echo e($index); ?>][caption]" 
                                                       class="w-full border border-gray-300 rounded-lg px-2 py-1.5 text-sm"
                                                       value="<?php echo e($block['caption'] ?? ''); ?>"
                                                       placeholder="Table caption...">
                                            </div>
                                            <div class="flex items-center gap-4 flex-wrap">
                                                <label class="text-xs text-gray-500 flex items-center gap-1">
                                                    <input type="checkbox" name="blocks[<?php echo e($index); ?>][striped]" value="1" 
                                                           <?php echo e(($block['striped'] ?? true) ? 'checked' : ''); ?> 
                                                           onchange="updateTablePreviewStyles(<?php echo e($index); ?>)">
                                                    Striped
                                                </label>
                                                <label class="text-xs text-gray-500 flex items-center gap-1">
                                                    <input type="checkbox" name="blocks[<?php echo e($index); ?>][bordered]" value="1" 
                                                           <?php echo e(($block['bordered'] ?? true) ? 'checked' : ''); ?> 
                                                           onchange="updateTablePreviewStyles(<?php echo e($index); ?>)">
                                                    Bordered
                                                </label>
                                                <label class="text-xs text-gray-500 flex items-center gap-1">
                                                    <input type="checkbox" name="blocks[<?php echo e($index); ?>][hoverable]" value="1" 
                                                           <?php echo e(($block['hoverable'] ?? false) ? 'checked' : ''); ?> 
                                                           onchange="updateTablePreviewStyles(<?php echo e($index); ?>)">
                                                    Hover
                                                </label>
                                                <label class="text-xs text-gray-500 flex items-center gap-1">
                                                    <input type="checkbox" name="blocks[<?php echo e($index); ?>][small]" value="1" 
                                                           <?php echo e(($block['small'] ?? false) ? 'checked' : ''); ?> 
                                                           onchange="updateTablePreviewStyles(<?php echo e($index); ?>)">
                                                    Small
                                                </label>
                                            </div>
                                        </div>

                                        <div class="border rounded-lg overflow-hidden bg-white">
                                            <div id="table-preview-container-<?php echo e($index); ?>" class="p-4">
                                                <table class="table-preview" id="table-preview-<?php echo e($index); ?>">
                                                    <thead>
                                                        <tr id="table-header-<?php echo e($index); ?>">
                                                            <?php
                                                                $headers = $block['headers'] ?? ['Column 1', 'Column 2', 'Column 3'];
                                                            ?>
                                                            <?php $__currentLoopData = $headers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hIndex => $header): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <th>
                                                                    <input type="text" 
                                                                           name="blocks[<?php echo e($index); ?>][headers][<?php echo e($hIndex); ?>]" 
                                                                           value="<?php echo e($header); ?>" 
                                                                           class="w-full border-0 bg-transparent text-sm font-medium focus:ring-1 focus:ring-blue-500 rounded px-1" 
                                                                           oninput="updateTablePreviewStyles(<?php echo e($index); ?>)">
                                                                </th>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            <th style="width:40px;text-align:center;">×</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="table-body-<?php echo e($index); ?>">
                                                        <?php
                                                            $rows = $block['rows'] ?? [
                                                                ['Row 1, Col 1', 'Row 1, Col 2', 'Row 1, Col 3']
                                                            ];
                                                        ?>
                                                        <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rIndex => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <tr>
                                                                <?php $__currentLoopData = $row; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cIndex => $cell): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <td>
                                                                        <input type="text" 
                                                                               name="blocks[<?php echo e($index); ?>][rows][<?php echo e($rIndex); ?>][<?php echo e($cIndex); ?>]" 
                                                                               value="<?php echo e($cell); ?>" 
                                                                               class="w-full border-0 bg-transparent text-sm focus:ring-1 focus:ring-blue-500 rounded px-1" 
                                                                               oninput="updateTablePreviewStyles(<?php echo e($index); ?>)">
                                                                    </td>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                <td style="text-align:center;">
                                                                    <button type="button" onclick="removeTableRow(this, <?php echo e($index); ?>)" 
                                                                            class="text-red-400 hover:text-red-600 text-xs">×</button>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <div class="flex gap-2 flex-wrap">
                                            <button type="button" onclick="addTableRow(<?php echo e($index); ?>)" 
                                                    class="text-xs px-3 py-1.5 rounded border border-gray-300 hover:bg-gray-50 transition">
                                                ➕ Add Row
                                            </button>
                                            <button type="button" onclick="addTableColumn(<?php echo e($index); ?>)" 
                                                    class="text-xs px-3 py-1.5 rounded border border-gray-300 hover:bg-gray-50 transition">
                                                ➕ Add Column
                                            </button>
                                            <button type="button" onclick="removeTableColumn(<?php echo e($index); ?>)" 
                                                    class="text-xs px-3 py-1.5 rounded border border-gray-300 hover:bg-gray-50 transition text-red-500">
                                                ✕ Remove Last Column
                                            </button>
                                            <button type="button" onclick="toggleTableColors(<?php echo e($index); ?>)" 
                                                    class="text-xs px-3 py-1.5 rounded border border-blue-300 bg-blue-50 text-blue-600 hover:bg-blue-100 transition">
                                                ⚙️ Customize Colors
                                            </button>
                                        </div>

                                        <div id="table-colors-<?php echo e($index); ?>" class="hidden mt-2 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="text-xs font-medium text-gray-600">Color Settings</span>
                                                <button type="button" onclick="toggleTableColors(<?php echo e($index); ?>)" 
                                                        class="text-xs text-gray-400 hover:text-gray-600">✕</button>
                                            </div>
                                            <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                                                <div>
                                                    <label class="block text-xs text-gray-500 mb-1">Header BG</label>
                                                    <input type="color" name="blocks[<?php echo e($index); ?>][header_bg_color]" 
                                                           value="<?php echo e($block['header_bg_color'] ?? '#f3f4f6'); ?>" 
                                                           class="w-full h-8 rounded border border-gray-300 cursor-pointer" 
                                                           onchange="updateTablePreviewStyles(<?php echo e($index); ?>)">
                                                </div>
                                                <div>
                                                    <label class="block text-xs text-gray-500 mb-1">Header Text</label>
                                                    <input type="color" name="blocks[<?php echo e($index); ?>][header_text_color]" 
                                                           value="<?php echo e($block['header_text_color'] ?? '#111827'); ?>" 
                                                           class="w-full h-8 rounded border border-gray-300 cursor-pointer" 
                                                           onchange="updateTablePreviewStyles(<?php echo e($index); ?>)">
                                                </div>
                                                <div>
                                                    <label class="block text-xs text-gray-500 mb-1">Row BG</label>
                                                    <input type="color" name="blocks[<?php echo e($index); ?>][row_bg_color]" 
                                                           value="<?php echo e($block['row_bg_color'] ?? '#ffffff'); ?>" 
                                                           class="w-full h-8 rounded border border-gray-300 cursor-pointer" 
                                                           onchange="updateTablePreviewStyles(<?php echo e($index); ?>)">
                                                </div>
                                                <div>
                                                    <label class="block text-xs text-gray-500 mb-1">Row Alt BG</label>
                                                    <input type="color" name="blocks[<?php echo e($index); ?>][row_bg_alt_color]" 
                                                           value="<?php echo e($block['row_bg_alt_color'] ?? '#f9fafb'); ?>" 
                                                           class="w-full h-8 rounded border border-gray-300 cursor-pointer" 
                                                           onchange="updateTablePreviewStyles(<?php echo e($index); ?>)">
                                                </div>
                                                <div>
                                                    <label class="block text-xs text-gray-500 mb-1">Row Text</label>
                                                    <input type="color" name="blocks[<?php echo e($index); ?>][row_text_color]" 
                                                           value="<?php echo e($block['row_text_color'] ?? '#111827'); ?>" 
                                                           class="w-full h-8 rounded border border-gray-300 cursor-pointer" 
                                                           onchange="updateTablePreviewStyles(<?php echo e($index); ?>)">
                                                </div>
                                                <div>
                                                    <label class="block text-xs text-gray-500 mb-1">Border</label>
                                                    <input type="color" name="blocks[<?php echo e($index); ?>][border_color]" 
                                                           value="<?php echo e($block['border_color'] ?? '#d1d5db'); ?>" 
                                                           class="w-full h-8 rounded border border-gray-300 cursor-pointer" 
                                                           onchange="updateTablePreviewStyles(<?php echo e($index); ?>)">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                
                                <?php elseif($type === 'buttons'): ?>
                                    <div class="flex items-center justify-between mb-3">
                                        <span class="text-xs font-semibold uppercase tracking-wide text-gray-500">🔘 Buttons</span>
                                        <button type="button" onclick="removeBlock(this)" class="text-xs text-red-500 hover:text-red-700">Remove</button>
                                    </div>
                                    
                                    <div class="space-y-3">
                                        <div class="flex flex-wrap gap-3">
                                            <div class="flex-1 min-w-[150px] max-w-[200px]">
                                                <label class="block text-xs text-gray-500 mb-1">Alignment</label>
                                                <select name="blocks[<?php echo e($index); ?>][alignment]" class="w-full border border-gray-300 rounded-lg px-2 py-1.5 text-sm" onchange="updateButtonPreview(<?php echo e($index); ?>)">
                                                    <option value="left" <?php echo e(($block['alignment'] ?? 'left') === 'left' ? 'selected' : ''); ?>>Left</option>
                                                    <option value="center" <?php echo e(($block['alignment'] ?? 'left') === 'center' ? 'selected' : ''); ?>>Center</option>
                                                    <option value="right" <?php echo e(($block['alignment'] ?? 'left') === 'right' ? 'selected' : ''); ?>>Right</option>
                                                    <option value="justify" <?php echo e(($block['alignment'] ?? 'left') === 'justify' ? 'selected' : ''); ?>>Justify</option>
                                                </select>
                                            </div>
                                            <div class="flex-1 min-w-[150px] max-w-[200px]">
                                                <label class="block text-xs text-gray-500 mb-1">Direction</label>
                                                <select name="blocks[<?php echo e($index); ?>][direction]" class="w-full border border-gray-300 rounded-lg px-2 py-1.5 text-sm" onchange="updateButtonPreview(<?php echo e($index); ?>)">
                                                    <option value="horizontal" <?php echo e(($block['direction'] ?? 'horizontal') === 'horizontal' ? 'selected' : ''); ?>>Horizontal</option>
                                                    <option value="vertical" <?php echo e(($block['direction'] ?? 'horizontal') === 'vertical' ? 'selected' : ''); ?>>Vertical</option>
                                                </select>
                                            </div>
                                            <div class="flex-1 min-w-[150px] max-w-[200px]">
                                                <label class="block text-xs text-gray-500 mb-1">Gap</label>
                                                <select name="blocks[<?php echo e($index); ?>][gap]" class="w-full border border-gray-300 rounded-lg px-2 py-1.5 text-sm" onchange="updateButtonPreview(<?php echo e($index); ?>)">
                                                    <option value="small" <?php echo e(($block['gap'] ?? 'medium') === 'small' ? 'selected' : ''); ?>>Small</option>
                                                    <option value="medium" <?php echo e(($block['gap'] ?? 'medium') === 'medium' ? 'selected' : ''); ?>>Medium</option>
                                                    <option value="large" <?php echo e(($block['gap'] ?? 'medium') === 'large' ? 'selected' : ''); ?>>Large</option>
                                                </select>
                                            </div>
                                            <div class="flex items-end">
                                                <button type="button" onclick="toggleButtonColors(<?php echo e($index); ?>)" 
                                                        class="text-xs px-3 py-1.5 rounded border border-blue-300 bg-blue-50 text-blue-600 hover:bg-blue-100 transition whitespace-nowrap">
                                                    ⚙️ Colors
                                                </button>
                                            </div>
                                        </div>

                                        <div id="button-colors-<?php echo e($index); ?>" class="hidden p-3 bg-gray-50 rounded-lg border border-gray-200">
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="text-xs font-medium text-gray-600">Default Colors</span>
                                                <button type="button" onclick="toggleButtonColors(<?php echo e($index); ?>)" 
                                                        class="text-xs text-gray-400 hover:text-gray-600">✕</button>
                                            </div>
                                            <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                                                <div>
                                                    <label class="block text-xs text-gray-500 mb-1">Default BG</label>
                                                    <input type="color" name="blocks[<?php echo e($index); ?>][default_bg_color]" 
                                                           value="<?php echo e($block['default_bg_color'] ?? '#2563eb'); ?>" 
                                                           class="w-full h-8 rounded border border-gray-300 cursor-pointer" 
                                                           onchange="updateButtonPreview(<?php echo e($index); ?>)">
                                                </div>
                                                <div>
                                                    <label class="block text-xs text-gray-500 mb-1">Default Text</label>
                                                    <input type="color" name="blocks[<?php echo e($index); ?>][default_text_color]" 
                                                           value="<?php echo e($block['default_text_color'] ?? '#ffffff'); ?>" 
                                                           class="w-full h-8 rounded border border-gray-300 cursor-pointer" 
                                                           onchange="updateButtonPreview(<?php echo e($index); ?>)">
                                                </div>
                                                <div>
                                                    <label class="block text-xs text-gray-500 mb-1">Hover BG</label>
                                                    <input type="color" name="blocks[<?php echo e($index); ?>][default_hover_bg_color]" 
                                                           value="<?php echo e($block['default_hover_bg_color'] ?? '#1d4ed8'); ?>" 
                                                           class="w-full h-8 rounded border border-gray-300 cursor-pointer" 
                                                           onchange="updateButtonPreview(<?php echo e($index); ?>)">
                                                </div>
                                                <div>
                                                    <label class="block text-xs text-gray-500 mb-1">Border Radius</label>
                                                    <input type="text" name="blocks[<?php echo e($index); ?>][default_border_radius]" 
                                                           value="<?php echo e($block['default_border_radius'] ?? '8px'); ?>" 
                                                           class="w-full border border-gray-300 rounded px-2 py-1 text-sm" 
                                                           placeholder="8px" onchange="updateButtonPreview(<?php echo e($index); ?>)">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="space-y-2" id="buttons-list-<?php echo e($index); ?>">
                                            <div class="text-xs text-gray-500">Buttons:</div>
                                            <div id="buttons-container-<?php echo e($index); ?>">
                                                <?php
                                                    $buttons = $block['buttons'] ?? [];
                                                    if (empty($buttons)) {
                                                        $buttons = [
                                                            [
                                                                'text' => 'Button 1',
                                                                'url' => '#',
                                                                'bg_color' => '#2563eb',
                                                                'text_color' => '#ffffff',
                                                                'hover_bg_color' => '#1d4ed8',
                                                                'hover_text_color' => '#ffffff',
                                                                'border_radius' => '8px',
                                                                'size' => 'medium',
                                                                'type' => 'primary',
                                                                'icon' => null,
                                                                'target' => '_self',
                                                                'rel' => ''
                                                            ]
                                                        ];
                                                    }
                                                ?>
                                                <?php $__currentLoopData = $buttons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $btnIndex => $button): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <div class="button-item border border-gray-200 rounded-lg p-3 bg-white mt-2" data-btn-index="<?php echo e($btnIndex); ?>">
                                                        <div class="flex items-center justify-between mb-2">
                                                            <span class="text-xs font-medium text-gray-600">Button <?php echo e($btnIndex + 1); ?></span>
                                                            <button type="button" onclick="removeButtonItem(this, <?php echo e($index); ?>)" 
                                                                    class="text-xs text-red-400 hover:text-red-600">Remove</button>
                                                        </div>
                                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                                            <div>
                                                                <label class="block text-xs text-gray-500">Text</label>
                                                                <input type="text" name="blocks[<?php echo e($index); ?>][buttons][<?php echo e($btnIndex); ?>][text]" 
                                                                       value="<?php echo e($button['text'] ?? 'Button'); ?>" 
                                                                       class="w-full border border-gray-300 rounded px-2 py-1 text-sm" 
                                                                       oninput="updateButtonPreview(<?php echo e($index); ?>)">
                                                            </div>
                                                            <div>
                                                                <label class="block text-xs text-gray-500">URL</label>
                                                                <input type="text" name="blocks[<?php echo e($index); ?>][buttons][<?php echo e($btnIndex); ?>][url]" 
                                                                       value="<?php echo e($button['url'] ?? '#'); ?>" 
                                                                       class="w-full border border-gray-300 rounded px-2 py-1 text-sm" 
                                                                       placeholder="https://..." oninput="updateButtonPreview(<?php echo e($index); ?>)">
                                                            </div>
                                                            <div>
                                                                <label class="block text-xs text-gray-500">Size</label>
                                                                <select name="blocks[<?php echo e($index); ?>][buttons][<?php echo e($btnIndex); ?>][size]" 
                                                                        class="w-full border border-gray-300 rounded px-2 py-1 text-sm" 
                                                                        onchange="updateButtonPreview(<?php echo e($index); ?>)">
                                                                    <option value="small" <?php echo e(($button['size'] ?? 'medium') === 'small' ? 'selected' : ''); ?>>Small</option>
                                                                    <option value="medium" <?php echo e(($button['size'] ?? 'medium') === 'medium' ? 'selected' : ''); ?>>Medium</option>
                                                                    <option value="large" <?php echo e(($button['size'] ?? 'medium') === 'large' ? 'selected' : ''); ?>>Large</option>
                                                                </select>
                                                            </div>
                                                            <div>
                                                                <label class="block text-xs text-gray-500">Type</label>
                                                                <select name="blocks[<?php echo e($index); ?>][buttons][<?php echo e($btnIndex); ?>][type]" 
                                                                        class="w-full border border-gray-300 rounded px-2 py-1 text-sm" 
                                                                        onchange="updateButtonPreview(<?php echo e($index); ?>)">
                                                                    <option value="primary" <?php echo e(($button['type'] ?? 'primary') === 'primary' ? 'selected' : ''); ?>>Primary</option>
                                                                    <option value="secondary" <?php echo e(($button['type'] ?? 'primary') === 'secondary' ? 'selected' : ''); ?>>Secondary</option>
                                                                    <option value="outline" <?php echo e(($button['type'] ?? 'primary') === 'outline' ? 'selected' : ''); ?>>Outline</option>
                                                                    <option value="ghost" <?php echo e(($button['type'] ?? 'primary') === 'ghost' ? 'selected' : ''); ?>>Ghost</option>
                                                                </select>
                                                            </div>
                                                            <div>
                                                                <label class="block text-xs text-gray-500">BG Color</label>
                                                                <input type="color" name="blocks[<?php echo e($index); ?>][buttons][<?php echo e($btnIndex); ?>][bg_color]" 
                                                                       value="<?php echo e($button['bg_color'] ?? '#2563eb'); ?>" 
                                                                       class="w-full h-8 rounded border border-gray-300 cursor-pointer" 
                                                                       onchange="updateButtonPreview(<?php echo e($index); ?>)">
                                                            </div>
                                                            <div>
                                                                <label class="block text-xs text-gray-500">Text Color</label>
                                                                <input type="color" name="blocks[<?php echo e($index); ?>][buttons][<?php echo e($btnIndex); ?>][text_color]" 
                                                                       value="<?php echo e($button['text_color'] ?? '#ffffff'); ?>" 
                                                                       class="w-full h-8 rounded border border-gray-300 cursor-pointer" 
                                                                       onchange="updateButtonPreview(<?php echo e($index); ?>)">
                                                            </div>
                                                            <div>
                                                                <label class="block text-xs text-gray-500">Hover BG</label>
                                                                <input type="color" name="blocks[<?php echo e($index); ?>][buttons][<?php echo e($btnIndex); ?>][hover_bg_color]" 
                                                                       value="<?php echo e($button['hover_bg_color'] ?? '#1d4ed8'); ?>" 
                                                                       class="w-full h-8 rounded border border-gray-300 cursor-pointer" 
                                                                       onchange="updateButtonPreview(<?php echo e($index); ?>)">
                                                            </div>
                                                            <div>
                                                                <label class="block text-xs text-gray-500">Border Radius</label>
                                                                <input type="text" name="blocks[<?php echo e($index); ?>][buttons][<?php echo e($btnIndex); ?>][border_radius]" 
                                                                       value="<?php echo e($button['border_radius'] ?? '8px'); ?>" 
                                                                       class="w-full border border-gray-300 rounded px-2 py-1 text-sm" 
                                                                       placeholder="8px" onchange="updateButtonPreview(<?php echo e($index); ?>)">
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                            <button type="button" onclick="addButtonItem(<?php echo e($index); ?>)" 
                                                    class="text-xs px-3 py-1.5 rounded border border-gray-300 hover:bg-gray-50 transition mt-2">
                                                ➕ Add Button
                                            </button>
                                        </div>

                                        <div class="border rounded-lg p-4 bg-white" id="button-preview-container-<?php echo e($index); ?>">
                                            <label class="block text-xs text-gray-500 mb-2">Live Preview:</label>
                                            <div id="button-preview-<?php echo e($index); ?>" class="btn-preview-group"></div>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                
                                <div class="flex items-center gap-2 mt-3 pt-3 border-t border-gray-100">
                                    <span class="text-xs text-gray-400">Insert below:</span>
                                    <button type="button" onclick="insertBlockAfter('heading',this)"
                                            class="text-xs px-2 py-1 rounded border border-gray-200 hover:bg-gray-100 font-bold">H</button>
                                    <button type="button" onclick="insertBlockAfter('text',this)"
                                            class="text-xs px-2 py-1 rounded border border-gray-200 hover:bg-gray-100">¶</button>
                                    <button type="button" onclick="insertBlockAfter('image',this)"
                                            class="text-xs px-2 py-1 rounded border border-gray-200 hover:bg-gray-100">🖼️</button>
                                    <button type="button" onclick="insertBlockAfter('list',this)"
                                            class="text-xs px-2 py-1 rounded border border-gray-200 hover:bg-gray-100">📋</button>
                                    <button type="button" onclick="insertBlockAfter('table',this)"
                                            class="text-xs px-2 py-1 rounded border border-green-300 hover:bg-green-50">📊</button>
                                    <button type="button" onclick="insertBlockAfter('buttons',this)"
                                            class="text-xs px-2 py-1 rounded border border-purple-300 hover:bg-purple-50">🔘</button>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-5 py-4 border-b border-gray-100">
                <h2 class="font-semibold text-gray-700">Gallery Images</h2>
            </div>
            <div class="p-5">
                <p class="text-xs text-gray-500 mb-3">
                    Upload multiple images for the gallery. Max 4MB each.
                </p>

                <?php
                    $galleryImages = $accommodation->images ?? collect();
                ?>

                <?php if($galleryImages->count() > 0): ?>
                    <div class="mb-4">
                        <label class="block text-xs text-gray-500 mb-2">Existing Gallery Images</label>
                        <div class="grid grid-cols-3 gap-2" id="existing-gallery-grid">
                            <?php $__currentLoopData = $galleryImages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="relative rounded-lg overflow-hidden border border-gray-200 bg-white existing-gallery-item"
                                     data-image-id="<?php echo e($image->id); ?>">
                                    <img src="<?php echo e(asset('storage/' . $image->path)); ?>"
                                         class="w-full object-cover" style="height: 90px;">
                                    <button type="button"
                                            class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-5 h-5 text-xs flex items-center justify-center hover:bg-red-600"
                                            onclick="deleteGalleryImage(this, <?php echo e($image->id); ?>)">&times;</button>
                                    <input type="text"
                                           name="existing_gallery_alts[<?php echo e($image->id); ?>]"
                                           value="<?php echo e($image->alt_text); ?>"
                                           placeholder="Alt text"
                                           class="w-full text-xs border-t border-gray-200 px-1 py-0.5 focus:outline-none">
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                <?php endif; ?>

                <input type="file"
                       name="gallery_images[]"
                       id="gallery_images"
                       accept="image/*"
                       multiple
                       class="hidden">

                <button type="button"
                        onclick="document.getElementById('gallery_images').click()"
                        class="inline-flex items-center px-4 py-2 bg-green-50 border border-green-400 text-green-700 text-sm font-semibold rounded-lg hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-green-500">
                    <i class="fas fa-plus mr-2"></i> Add Images
                </button>

                <div id="gallery_preview_grid"
                     class="mt-3 grid grid-cols-3 gap-2">
                </div>

                <p id="gallery_empty_msg" class="text-xs text-gray-400 mt-2">
                    No new images selected yet.
                </p>
            </div>
        </div>

        
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-5 py-4 border-b border-gray-100">
                <h2 class="font-semibold text-gray-700">Settings</h2>
            </div>
            <div class="p-5">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="flex items-center space-x-2">
                        <input type="checkbox" name="is_active" value="1"
                               class="rounded text-green-600 focus:ring-green-500"
                               <?php echo e(old('is_active', $accommodation->is_active) ? 'checked' : ''); ?>>
                        <span class="text-sm text-gray-700">Active (visible on public site)</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <input type="checkbox" name="is_featured" value="1"
                               class="rounded text-green-600 focus:ring-green-500"
                               <?php echo e(old('is_featured', $accommodation->is_featured) ? 'checked' : ''); ?>>
                        <span class="text-sm text-gray-700">Featured</span>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Sort Order</label>
                        <input type="number" name="sort_order" min="0" value="<?php echo e(old('sort_order', $accommodation->sort_order ?? 0)); ?>"
                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                    </div>
                </div>
            </div>
        </div>

        
        <div class="flex justify-end">
            <button type="submit"
                    class="inline-flex items-center px-6 py-2.5 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                <i class="fas fa-save mr-2"></i> Update Accommodation
            </button>
        </div>
    </form>
</div>


<div class="fixed bottom-0 left-0 right-0 z-40 bg-white border-t border-gray-200 shadow-lg px-4 py-3">
    <div class="max-w-7xl mx-auto flex items-center justify-center gap-2 flex-wrap">
        <span class="text-xs text-gray-400 mr-2 hidden sm:block">Add block:</span>
        <button type="button" onclick="addBlock('heading')"
                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg border border-gray-300 text-sm text-gray-700 hover:bg-gray-50 transition">
            <span class="font-bold">H</span>
        </button>
        <button type="button" onclick="addBlock('text')"
                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg border border-gray-300 text-sm text-gray-700 hover:bg-gray-50 transition">
            ¶
        </button>
        <button type="button" onclick="addBlock('image')"
                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg border border-gray-300 text-sm text-gray-700 hover:bg-gray-50 transition">
            🖼️
        </button>
        <button type="button" onclick="addBlock('list')"
                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg border border-gray-300 text-sm text-gray-700 hover:bg-gray-50 transition">
            📋
        </button>
        <button type="button" onclick="addBlock('table')"
                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg border border-green-300 bg-green-50 text-sm text-green-700 hover:bg-green-100 transition font-medium">
            📊 Table
        </button>
        <button type="button" onclick="addBlock('buttons')"
                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg border border-purple-300 bg-purple-50 text-sm text-purple-700 hover:bg-purple-100 transition font-medium">
            🔘 Buttons
        </button>
        
        
        <span class="text-xs text-gray-400 mx-2 hidden sm:block">| Inline:</span>
        <button type="button" id="inline-quote-btn"
                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg border border-yellow-300 bg-yellow-50 text-sm text-yellow-700 hover:bg-yellow-100 transition font-medium">
            💬 Quote
        </button>
        <button type="button" id="global-add-link-btn"
                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg border border-blue-300 bg-blue-50 text-sm text-blue-700 hover:bg-blue-100 transition font-medium">
            🔗 Link
        </button>
        <button type="button" id="global-icon-btn"
                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg border border-pink-300 bg-pink-50 text-sm text-pink-700 hover:bg-pink-100 transition font-medium">
            <i class="fas fa-icons"></i> Icon
        </button>
        <button type="button" id="clear-formatting-btn"
                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg border border-red-300 bg-red-50 text-sm text-red-700 hover:bg-red-100 transition font-medium">
            <i class="fas fa-eraser"></i> Clear
        </button>
    </div>
</div>


<div id="icon-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-4xl mx-4 p-6 max-h-[90vh] overflow-hidden flex flex-col">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Choose an Icon</h3>
            <button type="button" onclick="closeIconModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <p class="text-sm text-gray-500 mb-4">Click an icon to insert it at your cursor position.</p>
        
        <div class="mb-4">
            <input type="text" id="icon-search" 
                   placeholder="Search icons..." 
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                   oninput="filterIcons(this.value)">
        </div>
        
        <div class="grid grid-cols-6 sm:grid-cols-8 md:grid-cols-10 lg:grid-cols-12 gap-3 overflow-y-auto flex-1 p-2" id="icon-grid">
            <?php
                $fontAwesomeIcons = [
                    'fa-check-circle', 'fa-check', 'fa-times-circle', 'fa-times', 'fa-plus-circle', 'fa-plus',
                    'fa-minus-circle', 'fa-minus', 'fa-star', 'fa-heart', 'fa-fire', 'fa-rocket', 'fa-lightbulb',
                    'fa-info-circle', 'fa-exclamation-triangle', 'fa-exclamation-circle', 'fa-check-double',
                    'fa-flag', 'fa-trophy', 'fa-medal', 'fa-crown', 'fa-gem', 'fa-diamond', 'fa-ring',
                    'fa-bolt', 'fa-bell', 'fa-envelope', 'fa-phone', 'fa-map-marker-alt', 'fa-location-dot',
                    'fa-globe', 'fa-calendar', 'fa-clock', 'fa-hourglass', 'fa-sun', 'fa-moon', 'fa-cloud',
                    'fa-umbrella', 'fa-tree', 'fa-leaf', 'fa-seedling', 'fa-paw', 'fa-dog', 'fa-cat',
                    'fa-hippo', 'fa-elephant', 'fa-fish', 'fa-dove', 'fa-otter', 'fa-dragon', 'fa-unicorn',
                    'fa-star', 'fa-heart', 'fa-circle', 'fa-square', 'fa-check-circle', 'fa-times-circle',
                    'fa-facebook', 'fa-twitter', 'fa-instagram', 'fa-youtube', 'fa-whatsapp', 'fa-telegram',
                    'fa-tripadvisor', 'fa-airbnb', 'fa-google', 'fa-apple', 'fa-android', 'fa-windows',
                    'fa-linux', 'fa-github', 'fa-linkedin', 'fa-pinterest', 'fa-snapchat', 'fa-tiktok',
                    'fa-arrow-right', 'fa-arrow-left', 'fa-arrow-up', 'fa-arrow-down', 'fa-long-arrow-alt-right',
                    'fa-chevron-right', 'fa-chevron-left', 'fa-chevron-up', 'fa-chevron-down', 'fa-angle-double-right',
                    'fa-angle-double-left', 'fa-angle-double-up', 'fa-angle-double-down', 'fa-play', 'fa-pause',
                    'fa-stop', 'fa-video', 'fa-film', 'fa-camera', 'fa-image', 'fa-images', 'fa-photo-video',
                    'fa-wifi', 'fa-bluetooth', 'fa-usb', 'fa-plug', 'fa-battery-full', 'fa-battery-three-quarters',
                    'fa-battery-half', 'fa-battery-quarter', 'fa-battery-empty', 'fa-shopping-cart', 'fa-shopping-bag',
                    'fa-shopping-basket', 'fa-credit-card', 'fa-wallet', 'fa-money-bill', 'fa-money-bill-wave',
                    'fa-coins', 'fa-dollar-sign', 'fa-euro-sign', 'fa-pound-sign', 'fa-yen-sign', 'fa-bitcoin',
                    'fa-home', 'fa-building', 'fa-city', 'fa-store', 'fa-warehouse', 'fa-factory', 'fa-industry',
                    'fa-tools', 'fa-wrench', 'fa-hammer', 'fa-screwdriver', 'fa-toolbox', 'fa-paint-brush',
                    'fa-paint-roller', 'fa-ruler', 'fa-ruler-combined', 'fa-ruler-horizontal', 'fa-ruler-vertical',
                    'fa-compass', 'fa-map', 'fa-map-pin', 'fa-location-arrow', 'fa-crosshairs', 'fa-binoculars',
                    'fa-search', 'fa-search-plus', 'fa-search-minus', 'fa-filter', 'fa-sliders-h',
                ];
            ?>
            <?php $__currentLoopData = $fontAwesomeIcons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $icon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <button type="button" onclick="insertFontAwesomeIcon('<?php echo e($icon); ?>')"
                        class="icon-item text-2xl hover:bg-gray-100 rounded-lg p-3 transition hover:scale-110 hover:text-blue-600"
                        data-icon="<?php echo e($icon); ?>">
                    <i class="fas <?php echo e($icon); ?>"></i>
                </button>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <div class="mt-4 flex justify-end border-t pt-4">
            <button type="button" onclick="closeIconModal()"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-sm hover:bg-gray-50 transition">Close</button>
        </div>
    </div>
</div>


<div id="context-menu" class="fixed z-50 hidden bg-white rounded-lg shadow-xl border border-gray-200 py-1 min-w-[180px]">
    <button id="context-remove" 
            class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 flex items-center gap-2 transition">
        <i class="fas fa-trash-alt"></i> Remove this element
    </button>
    <hr class="my-1">
    <button id="context-clear-formatting" 
            class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 flex items-center gap-2 transition">
        <i class="fas fa-eraser"></i> Clear formatting
    </button>
</div>


<div id="link-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md mx-4 p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Insert Link</h3>
        <div class="space-y-3">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Link Text</label>
                <input type="text" id="modal-link-text" 
                       placeholder="e.g. Click here to learn more"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">URL</label>
                <input type="url" id="modal-link-url" 
                       placeholder="https://..."
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <p id="modal-link-error" class="text-red-500 text-xs hidden">Please fill in both fields.</p>
        </div>
        <div class="flex gap-3 mt-5">
            <button type="button" onclick="closeLinkModal()"
                    class="flex-1 border border-gray-300 text-gray-700 rounded-lg py-2 text-sm hover:bg-gray-50 transition">Cancel</button>
            <button type="button" onclick="insertInlineLink()"
                    class="flex-1 bg-blue-600 hover:bg-blue-700 text-white rounded-lg py-2 text-sm font-medium transition">Insert Link</button>
        </div>
    </div>
</div>

<div id="edit-link-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md mx-4 p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Edit Link</h3>
        <div class="space-y-3">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Link Text</label>
                <input type="text" id="edit-link-text" 
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">URL</label>
                <input type="url" id="edit-link-url" 
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </div>
        <div class="flex gap-3 mt-5">
            <button type="button" onclick="closeEditLinkModal()"
                    class="flex-1 border border-gray-300 text-gray-700 rounded-lg py-2 text-sm hover:bg-gray-50 transition">Cancel</button>
            <button type="button" onclick="saveEditedLink()"
                    class="flex-1 bg-blue-600 hover:bg-blue-700 text-white rounded-lg py-2 text-sm font-medium transition">Save</button>
            <button type="button" onclick="removeCurrentLink()"
                    class="flex-1 bg-red-600 hover:bg-red-700 text-white rounded-lg py-2 text-sm font-medium transition">Remove Link</button>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<!-- Font Awesome 6 (Free) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
.paragraph-editor {
    white-space: pre-wrap;
    word-break: break-word;
    line-height: 1.7;
    min-height: 140px;
    outline: none;
}
.paragraph-editor:empty:before {
    content: attr(data-placeholder);
    color: #9ca3af;
    pointer-events: none;
    display: block;
}
.paragraph-editor a {
    color: #2563eb;
    text-decoration: underline;
    cursor: pointer;
    position: relative;
}
.paragraph-editor a:hover {
    text-decoration: none;
    background-color: #eff6ff;
    border-radius: 2px;
}
.paragraph-editor a::after {
    content: "🔗";
    font-size: 0.6rem;
    margin-left: 2px;
    opacity: 0.5;
}
.paragraph-editor blockquote {
    border-left: 4px solid #f59e0b;
    padding: 0.75rem 1.5rem;
    margin: 0.75rem 0;
    background-color: #fefce8;
    border-radius: 0.5rem;
    font-style: italic;
    color: #78350f;
    position: relative;
}
.paragraph-editor blockquote:hover {
    background-color: #fef3c7;
}
.paragraph-editor blockquote::before {
    content: "💬";
    position: absolute;
    left: -0.5rem;
    top: -0.5rem;
    font-size: 1rem;
}
.paragraph-editor blockquote p {
    margin: 0;
}
.paragraph-editor ul,
.paragraph-editor ol {
    padding-left: 2rem;
    margin: 0.5rem 0;
}
.paragraph-editor li {
    margin-bottom: 0.25rem;
}
.paragraph-editor li a {
    color: #2563eb;
    text-decoration: underline;
}
.paragraph-editor .inline-icon {
    display: inline-block;
    margin: 0 4px;
    font-size: 1.1em;
    cursor: pointer;
    padding: 2px 4px;
    border-radius: 4px;
    transition: background-color 0.2s;
}
.paragraph-editor .inline-icon:hover {
    background-color: #fce4ec;
}
.heading-preview {
    min-height: 40px;
}

/* Table Preview Styles */
.table-preview {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.875rem;
}
.table-preview th,
.table-preview td {
    padding: 0.5rem 0.75rem;
    text-align: left;
    border: 1px solid #d1d5db;
}
.table-preview th {
    background-color: #f3f4f6;
    font-weight: 600;
}
.table-preview.striped tbody tr:nth-child(even) {
    background-color: #f9fafb;
}
.table-preview.bordered th,
.table-preview.bordered td {
    border: 1px solid #d1d5db;
}
.table-preview.hoverable tbody tr:hover {
    background-color: #f3f4f6;
}
.table-preview.small th,
.table-preview.small td {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
}

/* Button Preview Styles */
.btn-preview-group {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
    align-items: center;
}
.btn-preview-group.vertical {
    flex-direction: column;
    align-items: flex-start;
}
.btn-preview-group.justify {
    justify-content: space-between;
    width: 100%;
}
.btn-preview-group.center {
    justify-content: center;
}
.btn-preview-group.right {
    justify-content: flex-end;
}
.btn-preview-group.gap-small { gap: 0.5rem; }
.btn-preview-group.gap-medium { gap: 0.75rem; }
.btn-preview-group.gap-large { gap: 1rem; }

.btn-preview {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1.25rem;
    border: none;
    border-radius: 8px;
    font-size: 0.875rem;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s ease;
    cursor: pointer;
}
.btn-preview.small { padding: 0.25rem 0.75rem; font-size: 0.75rem; }
.btn-preview.medium { padding: 0.5rem 1.25rem; font-size: 0.875rem; }
.btn-preview.large { padding: 0.75rem 1.75rem; font-size: 1rem; }

/* Icon picker modal */
#icon-grid .icon-item {
    font-size: 1.5rem;
    width: 3.5rem;
    height: 3.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
}
#icon-grid .icon-item:hover {
    background-color: #f3f4f6;
    transform: scale(1.1);
    color: #2563eb;
}
#icon-grid .icon-item i {
    font-size: 1.5rem;
}

/* Context menu */
#context-menu {
    min-width: 200px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.15);
}
#context-menu button:hover {
    background-color: #f9fafb;
}
#context-menu #context-remove:hover {
    background-color: #fef2f2;
}
</style>

<script>
(function () {
    // Start block index after existing blocks
    let blockIndex = <?php echo e(count($existingBlocks)); ?>;
    const blockFiles = window.blockFiles || {};
    window.blockFiles = blockFiles;

    let currentEditorForLink = null;
    let currentEditingLink = null;
    let currentEditingEditor = null;
    let savedSelectionRange = null;
    let contextTargetElement = null;

    // ─── TOGGLE FUNCTIONS ──────────────────────────────────────
    window.toggleTableColors = function(idx) {
        const panel = document.getElementById('table-colors-' + idx);
        if (panel) panel.classList.toggle('hidden');
    };

    window.toggleButtonColors = function(idx) {
        const panel = document.getElementById('button-colors-' + idx);
        if (panel) panel.classList.toggle('hidden');
    };

    // ─── CONTEXT MENU FUNCTIONS ───────────────────────────────
    window.showRemoveContextMenu = function(event, editor) {
        event.preventDefault();
        const target = event.target;
        
        const removable = target.closest('a, blockquote, .inline-icon');
        if (!removable) {
            const parent = target.parentElement?.closest('a, blockquote, .inline-icon');
            if (parent) {
                contextTargetElement = parent;
            } else {
                return;
            }
        } else {
            contextTargetElement = removable;
        }
        
        const menu = document.getElementById('context-menu');
        menu.style.left = event.pageX + 'px';
        menu.style.top = event.pageY + 'px';
        menu.classList.remove('hidden');
        menu.dataset.editorIndex = editor.dataset.index;
    };

    document.getElementById('context-remove').addEventListener('click', function() {
        const menu = document.getElementById('context-menu');
        const idx = menu.dataset.editorIndex;
        
        if (contextTargetElement) {
            const parent = contextTargetElement.parentNode;
            const text = contextTargetElement.textContent;
            const textNode = document.createTextNode(text);
            parent.replaceChild(textNode, contextTargetElement);
            
            const hiddenInput = document.getElementById('content-' + idx);
            if (hiddenInput) {
                const editor = document.querySelector(`.paragraph-editor[data-index="${idx}"]`);
                if (editor) hiddenInput.value = editor.innerHTML;
            }
            contextTargetElement = null;
        }
        menu.classList.add('hidden');
    });

    document.getElementById('context-clear-formatting').addEventListener('click', function() {
        const menu = document.getElementById('context-menu');
        const idx = menu.dataset.editorIndex;
        const editor = document.querySelector(`.paragraph-editor[data-index="${idx}"]`);
        
        if (editor) {
            const elements = editor.querySelectorAll('a, blockquote, .inline-icon, span, b, i, strong, em, u');
            elements.forEach(el => {
                const text = el.textContent;
                const textNode = document.createTextNode(text);
                el.parentNode.replaceChild(textNode, el);
            });
            
            const hiddenInput = document.getElementById('content-' + idx);
            if (hiddenInput) hiddenInput.value = editor.innerHTML;
        }
        menu.classList.add('hidden');
        contextTargetElement = null;
    });

    document.addEventListener('click', function(e) {
        const menu = document.getElementById('context-menu');
        if (!menu.contains(e.target)) {
            menu.classList.add('hidden');
            contextTargetElement = null;
        }
    });

    // ─── CLEAR FORMATTING BUTTON ──────────────────────────────
    document.getElementById('clear-formatting-btn').addEventListener('click', function() {
        const editor = currentEditorForLink;
        if (!editor) {
            alert('Please click inside a paragraph first.');
            return;
        }
        
        if (confirm('Remove all formatting (links, icons, quotes, bold, italic) from this paragraph?')) {
            const elements = editor.querySelectorAll('a, blockquote, .inline-icon, span, b, i, strong, em, u');
            elements.forEach(el => {
                const text = el.textContent;
                const textNode = document.createTextNode(text);
                el.parentNode.replaceChild(textNode, el);
            });
            
            const idx = editor.dataset.index;
            const hiddenInput = document.getElementById('content-' + idx);
            if (hiddenInput) hiddenInput.value = editor.innerHTML;
        }
    });

    // ─── BUILD BLOCK ─────────────────────────────────────────
    function buildBlock(type, idx) {
        const div = document.createElement('div');
        div.className = 'block-item border border-gray-200 rounded-xl p-4 mb-3 bg-gray-50';
        div.dataset.index = idx;

        let inner = `<input type="hidden" name="blocks[${idx}][type]" value="${type}">`;

        // ─── HEADING BLOCK ──────────────────────────────────────
        if (type === 'heading') {
            inner += `
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-wide text-gray-500">Heading</span>
                <button type="button" onclick="removeBlock(this)" class="text-xs text-red-500 hover:text-red-700">Remove</button>
            </div>
            <div class="flex gap-3 mb-3">
                <div class="w-36">
                    <label class="block text-xs text-gray-500 mb-1">Level</label>
                    <select name="blocks[${idx}][heading_level]" onchange="updateHeadingPreview(this)"
                            class="w-full border border-gray-300 rounded-lg px-2 py-1.5 text-sm">
                        <option value="h1">H1</option>
                        <option value="h2" selected>H2</option>
                        <option value="h3">H3</option>
                        <option value="h4">H4</option>
                        <option value="h5">H5</option>
                        <option value="h6">H6</option>
                    </select>
                </div>
                <div class="flex-1">
                    <label class="block text-xs text-gray-500 mb-1">Heading Text</label>
                    <input type="text" name="blocks[${idx}][content]"
                           class="heading-text w-full border border-gray-300 rounded-lg px-3 py-1.5 text-sm"
                           placeholder="Enter heading text" oninput="updateHeadingPreview(this)">
                </div>
            </div>
            <div class="heading-preview px-3 py-2 bg-white rounded-lg border border-dashed border-gray-300 text-gray-400 text-sm italic">Preview appears here...</div>`;
        }

        // ─── TEXT BLOCK ─────────────────────────────────────────
        if (type === 'text') {
            inner += `
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold uppercase tracking-wide text-gray-500">Paragraph</span>
                <button type="button" onclick="removeBlock(this)" class="text-xs text-red-500 hover:text-red-700">Remove</button>
            </div>
            <div contenteditable="true"
                 data-block-type="text"
                 data-index="${idx}"
                 data-placeholder="Write your paragraph here..."
                 class="paragraph-editor w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500"
                 onfocus="setCurrentEditor(this)"
                 onclick="setCurrentEditor(this)"
                 onkeyup="saveSelection()"
                 onmouseup="saveSelection()"
                 oninput="syncContent(this,${idx})"
                 oncontextmenu="showRemoveContextMenu(event, this)"></div>
            <input type="hidden" name="blocks[${idx}][content]" id="content-${idx}">`;
        }

        // ─── IMAGE BLOCK ────────────────────────────────────────
        if (type === 'image') {
            blockFiles[idx] = [];
            inner += `
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-wide text-gray-500">Images <span id="img-count-${idx}" class="text-blue-500"></span></span>
                <button type="button" onclick="removeBlock(this)" class="text-xs text-red-500 hover:text-red-700">Remove</button>
            </div>
            <div id="img-grid-${idx}" class="grid grid-cols-3 gap-3 mb-3"></div>
            <div id="img-transfer-${idx}"></div>
            <label class="block w-full border-2 border-dashed border-gray-300 rounded-xl p-5 text-center cursor-pointer hover:border-blue-400 hover:bg-blue-50 transition">
                <input type="file" class="hidden" accept="image/jpeg,image/png,image/webp" multiple
                       onchange="accumulateImages(this,${idx})">
                <p class="text-gray-500 text-sm">Click to add images</p>
                <p class="text-gray-400 text-xs mt-1">Click multiple times to add more</p>
            </label>`;
        }

        // ─── LIST BLOCK ─────────────────────────────────────────
        if (type === 'list') {
            inner += `
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-wide text-gray-500">📋 List</span>
                <button type="button" onclick="removeBlock(this)" class="text-xs text-red-500 hover:text-red-700">Remove</button>
            </div>
            <div class="flex gap-3 mb-3">
                <div class="w-36">
                    <label class="block text-xs text-gray-500 mb-1">Type</label>
                    <select name="blocks[${idx}][list_type]" class="w-full border border-gray-300 rounded-lg px-2 py-1.5 text-sm">
                        <option value="ul">Bullet List</option>
                        <option value="ol">Numbered List</option>
                    </select>
                </div>
            </div>
            <div contenteditable="true"
                 data-block-type="list"
                 data-index="${idx}"
                 class="paragraph-editor w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 min-h-[120px]"
                 onfocus="setCurrentEditor(this)"
                 onclick="setCurrentEditor(this)"
                 onkeyup="saveSelection()"
                 onmouseup="saveSelection()"
                 oninput="syncContent(this,${idx})"
                 oncontextmenu="showRemoveContextMenu(event, this)"
                 placeholder="• Item 1&#10;• Item 2&#10;• Item 3"></div>
            <input type="hidden" name="blocks[${idx}][content]" id="content-${idx}">`;
        }

        // ─── TABLE BLOCK ────────────────────────────────────────
        if (type === 'table') {
            inner += `
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-wide text-gray-500">📊 Table</span>
                <button type="button" onclick="removeBlock(this)" class="text-xs text-red-500 hover:text-red-700">Remove</button>
            </div>
            <div class="space-y-3">
                <div class="flex flex-wrap gap-3">
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-xs text-gray-500 mb-1">Caption</label>
                        <input type="text" name="blocks[${idx}][caption]" 
                               class="w-full border border-gray-300 rounded-lg px-2 py-1.5 text-sm" placeholder="Table caption...">
                    </div>
                    <div class="flex items-center gap-4 flex-wrap">
                        <label class="text-xs text-gray-500 flex items-center gap-1">
                            <input type="checkbox" name="blocks[${idx}][striped]" value="1" checked onchange="updateTablePreviewStyles(${idx})"> Striped
                        </label>
                        <label class="text-xs text-gray-500 flex items-center gap-1">
                            <input type="checkbox" name="blocks[${idx}][bordered]" value="1" checked onchange="updateTablePreviewStyles(${idx})"> Bordered
                        </label>
                        <label class="text-xs text-gray-500 flex items-center gap-1">
                            <input type="checkbox" name="blocks[${idx}][hoverable]" value="1" onchange="updateTablePreviewStyles(${idx})"> Hover
                        </label>
                        <label class="text-xs text-gray-500 flex items-center gap-1">
                            <input type="checkbox" name="blocks[${idx}][small]" value="1" onchange="updateTablePreviewStyles(${idx})"> Small
                        </label>
                    </div>
                </div>
                <div class="border rounded-lg overflow-hidden bg-white">
                    <div id="table-preview-container-${idx}" class="p-4">
                        <table class="table-preview" id="table-preview-${idx}">
                            <thead>
                                <tr id="table-header-${idx}">
                                    <th><input type="text" name="blocks[${idx}][headers][0]" value="Column 1" class="w-full border-0 bg-transparent text-sm font-medium focus:ring-1 focus:ring-blue-500 rounded px-1" oninput="updateTablePreviewStyles(${idx})"></th>
                                    <th><input type="text" name="blocks[${idx}][headers][1]" value="Column 2" class="w-full border-0 bg-transparent text-sm font-medium focus:ring-1 focus:ring-blue-500 rounded px-1" oninput="updateTablePreviewStyles(${idx})"></th>
                                    <th><input type="text" name="blocks[${idx}][headers][2]" value="Column 3" class="w-full border-0 bg-transparent text-sm font-medium focus:ring-1 focus:ring-blue-500 rounded px-1" oninput="updateTablePreviewStyles(${idx})"></th>
                                    <th style="width:40px;text-align:center;">×</th>
                                </tr>
                            </thead>
                            <tbody id="table-body-${idx}">
                                <tr>
                                    <td><input type="text" name="blocks[${idx}][rows][0][0]" value="Row 1, Col 1" class="w-full border-0 bg-transparent text-sm focus:ring-1 focus:ring-blue-500 rounded px-1" oninput="updateTablePreviewStyles(${idx})"></td>
                                    <td><input type="text" name="blocks[${idx}][rows][0][1]" value="Row 1, Col 2" class="w-full border-0 bg-transparent text-sm focus:ring-1 focus:ring-blue-500 rounded px-1" oninput="updateTablePreviewStyles(${idx})"></td>
                                    <td><input type="text" name="blocks[${idx}][rows][0][2]" value="Row 1, Col 3" class="w-full border-0 bg-transparent text-sm focus:ring-1 focus:ring-blue-500 rounded px-1" oninput="updateTablePreviewStyles(${idx})"></td>
                                    <td style="text-align:center;"><button type="button" onclick="removeTableRow(this, ${idx})" class="text-red-400 hover:text-red-600 text-xs">×</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="flex gap-2 flex-wrap">
                    <button type="button" onclick="addTableRow(${idx})" class="text-xs px-3 py-1.5 rounded border border-gray-300 hover:bg-gray-50 transition">➕ Add Row</button>
                    <button type="button" onclick="addTableColumn(${idx})" class="text-xs px-3 py-1.5 rounded border border-gray-300 hover:bg-gray-50 transition">➕ Add Column</button>
                    <button type="button" onclick="removeTableColumn(${idx})" class="text-xs px-3 py-1.5 rounded border border-gray-300 hover:bg-gray-50 transition text-red-500">✕ Remove Last Column</button>
                    <button type="button" onclick="toggleTableColors(${idx})" class="text-xs px-3 py-1.5 rounded border border-blue-300 bg-blue-50 text-blue-600 hover:bg-blue-100 transition">⚙️ Customize Colors</button>
                </div>
                <div id="table-colors-${idx}" class="hidden mt-2 p-3 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-medium text-gray-600">Color Settings</span>
                        <button type="button" onclick="toggleTableColors(${idx})" class="text-xs text-gray-400 hover:text-gray-600">✕</button>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                        <div><label class="block text-xs text-gray-500 mb-1">Header BG</label><input type="color" name="blocks[${idx}][header_bg_color]" value="#f3f4f6" class="w-full h-8 rounded border border-gray-300 cursor-pointer" onchange="updateTablePreviewStyles(${idx})"></div>
                        <div><label class="block text-xs text-gray-500 mb-1">Header Text</label><input type="color" name="blocks[${idx}][header_text_color]" value="#111827" class="w-full h-8 rounded border border-gray-300 cursor-pointer" onchange="updateTablePreviewStyles(${idx})"></div>
                        <div><label class="block text-xs text-gray-500 mb-1">Row BG</label><input type="color" name="blocks[${idx}][row_bg_color]" value="#ffffff" class="w-full h-8 rounded border border-gray-300 cursor-pointer" onchange="updateTablePreviewStyles(${idx})"></div>
                        <div><label class="block text-xs text-gray-500 mb-1">Row Alt BG</label><input type="color" name="blocks[${idx}][row_bg_alt_color]" value="#f9fafb" class="w-full h-8 rounded border border-gray-300 cursor-pointer" onchange="updateTablePreviewStyles(${idx})"></div>
                        <div><label class="block text-xs text-gray-500 mb-1">Row Text</label><input type="color" name="blocks[${idx}][row_text_color]" value="#111827" class="w-full h-8 rounded border border-gray-300 cursor-pointer" onchange="updateTablePreviewStyles(${idx})"></div>
                        <div><label class="block text-xs text-gray-500 mb-1">Border</label><input type="color" name="blocks[${idx}][border_color]" value="#d1d5db" class="w-full h-8 rounded border border-gray-300 cursor-pointer" onchange="updateTablePreviewStyles(${idx})"></div>
                    </div>
                </div>
            </div>`;
        }

        // ─── BUTTONS BLOCK ──────────────────────────────────────
        if (type === 'buttons') {
            inner += `
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-wide text-gray-500">🔘 Buttons</span>
                <button type="button" onclick="removeBlock(this)" class="text-xs text-red-500 hover:text-red-700">Remove</button>
            </div>
            <div class="space-y-3">
                <div class="flex flex-wrap gap-3">
                    <div class="flex-1 min-w-[150px] max-w-[200px]">
                        <label class="block text-xs text-gray-500 mb-1">Alignment</label>
                        <select name="blocks[${idx}][alignment]" class="w-full border border-gray-300 rounded-lg px-2 py-1.5 text-sm" onchange="updateButtonPreview(${idx})">
                            <option value="left">Left</option>
                            <option value="center">Center</option>
                            <option value="right">Right</option>
                            <option value="justify">Justify</option>
                        </select>
                    </div>
                    <div class="flex-1 min-w-[150px] max-w-[200px]">
                        <label class="block text-xs text-gray-500 mb-1">Direction</label>
                        <select name="blocks[${idx}][direction]" class="w-full border border-gray-300 rounded-lg px-2 py-1.5 text-sm" onchange="updateButtonPreview(${idx})">
                            <option value="horizontal">Horizontal</option>
                            <option value="vertical">Vertical</option>
                        </select>
                    </div>
                    <div class="flex-1 min-w-[150px] max-w-[200px]">
                        <label class="block text-xs text-gray-500 mb-1">Gap</label>
                        <select name="blocks[${idx}][gap]" class="w-full border border-gray-300 rounded-lg px-2 py-1.5 text-sm" onchange="updateButtonPreview(${idx})">
                            <option value="small">Small</option>
                            <option value="medium" selected>Medium</option>
                            <option value="large">Large</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="button" onclick="toggleButtonColors(${idx})" 
                                class="text-xs px-3 py-1.5 rounded border border-blue-300 bg-blue-50 text-blue-600 hover:bg-blue-100 transition whitespace-nowrap">
                            ⚙️ Colors
                        </button>
                    </div>
                </div>

                <div id="button-colors-${idx}" class="hidden p-3 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-medium text-gray-600">Default Colors</span>
                        <button type="button" onclick="toggleButtonColors(${idx})" 
                                class="text-xs text-gray-400 hover:text-gray-600">✕</button>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                        <div><label class="block text-xs text-gray-500 mb-1">Default BG</label><input type="color" name="blocks[${idx}][default_bg_color]" value="#2563eb" class="w-full h-8 rounded border border-gray-300 cursor-pointer" onchange="updateButtonPreview(${idx})"></div>
                        <div><label class="block text-xs text-gray-500 mb-1">Default Text</label><input type="color" name="blocks[${idx}][default_text_color]" value="#ffffff" class="w-full h-8 rounded border border-gray-300 cursor-pointer" onchange="updateButtonPreview(${idx})"></div>
                        <div><label class="block text-xs text-gray-500 mb-1">Hover BG</label><input type="color" name="blocks[${idx}][default_hover_bg_color]" value="#1d4ed8" class="w-full h-8 rounded border border-gray-300 cursor-pointer" onchange="updateButtonPreview(${idx})"></div>
                        <div><label class="block text-xs text-gray-500 mb-1">Border Radius</label><input type="text" name="blocks[${idx}][default_border_radius]" value="8px" class="w-full border border-gray-300 rounded px-2 py-1 text-sm" placeholder="8px" onchange="updateButtonPreview(${idx})"></div>
                    </div>
                </div>

                <div class="space-y-2" id="buttons-list-${idx}">
                    <div class="text-xs text-gray-500">Buttons:</div>
                    <div id="buttons-container-${idx}">
                        <div class="button-item border border-gray-200 rounded-lg p-3 bg-white" data-btn-index="0">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-medium text-gray-600">Button 1</span>
                                <button type="button" onclick="removeButtonItem(this, ${idx})" class="text-xs text-red-400 hover:text-red-600">Remove</button>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                <div><label class="block text-xs text-gray-500">Text</label><input type="text" name="blocks[${idx}][buttons][0][text]" value="Button 1" class="w-full border border-gray-300 rounded px-2 py-1 text-sm" oninput="updateButtonPreview(${idx})"></div>
                                <div><label class="block text-xs text-gray-500">URL</label><input type="text" name="blocks[${idx}][buttons][0][url]" value="#" class="w-full border border-gray-300 rounded px-2 py-1 text-sm" placeholder="https://..." oninput="updateButtonPreview(${idx})"></div>
                                <div><label class="block text-xs text-gray-500">Size</label><select name="blocks[${idx}][buttons][0][size]" class="w-full border border-gray-300 rounded px-2 py-1 text-sm" onchange="updateButtonPreview(${idx})"><option value="small">Small</option><option value="medium" selected>Medium</option><option value="large">Large</option></select></div>
                                <div><label class="block text-xs text-gray-500">Type</label><select name="blocks[${idx}][buttons][0][type]" class="w-full border border-gray-300 rounded px-2 py-1 text-sm" onchange="updateButtonPreview(${idx})"><option value="primary">Primary</option><option value="secondary">Secondary</option><option value="outline">Outline</option><option value="ghost">Ghost</option></select></div>
                                <div><label class="block text-xs text-gray-500">BG Color</label><input type="color" name="blocks[${idx}][buttons][0][bg_color]" value="#2563eb" class="w-full h-8 rounded border border-gray-300 cursor-pointer" onchange="updateButtonPreview(${idx})"></div>
                                <div><label class="block text-xs text-gray-500">Text Color</label><input type="color" name="blocks[${idx}][buttons][0][text_color]" value="#ffffff" class="w-full h-8 rounded border border-gray-300 cursor-pointer" onchange="updateButtonPreview(${idx})"></div>
                                <div><label class="block text-xs text-gray-500">Hover BG</label><input type="color" name="blocks[${idx}][buttons][0][hover_bg_color]" value="#1d4ed8" class="w-full h-8 rounded border border-gray-300 cursor-pointer" onchange="updateButtonPreview(${idx})"></div>
                                <div><label class="block text-xs text-gray-500">Border Radius</label><input type="text" name="blocks[${idx}][buttons][0][border_radius]" value="8px" class="w-full border border-gray-300 rounded px-2 py-1 text-sm" placeholder="8px" onchange="updateButtonPreview(${idx})"></div>
                            </div>
                        </div>
                    </div>
                    <button type="button" onclick="addButtonItem(${idx})" class="text-xs px-3 py-1.5 rounded border border-gray-300 hover:bg-gray-50 transition mt-2">➕ Add Button</button>
                </div>

                <div class="border rounded-lg p-4 bg-white" id="button-preview-container-${idx}">
                    <label class="block text-xs text-gray-500 mb-2">Live Preview:</label>
                    <div id="button-preview-${idx}" class="btn-preview-group"></div>
                </div>
            </div>`;
        }

        // ─── INSERT CONTROLS ────────────────────────────────────
        inner += `
        <div class="flex items-center gap-2 mt-3 pt-3 border-t border-gray-100">
            <span class="text-xs text-gray-400">Insert below:</span>
            <button type="button" onclick="insertBlockAfter('heading',this)" class="text-xs px-2 py-1 rounded border border-gray-200 hover:bg-gray-100 font-bold">H</button>
            <button type="button" onclick="insertBlockAfter('text',this)" class="text-xs px-2 py-1 rounded border border-gray-200 hover:bg-gray-100">¶</button>
            <button type="button" onclick="insertBlockAfter('image',this)" class="text-xs px-2 py-1 rounded border border-gray-200 hover:bg-gray-100">🖼️</button>
            <button type="button" onclick="insertBlockAfter('list',this)" class="text-xs px-2 py-1 rounded border border-gray-200 hover:bg-gray-100">📋</button>
            <button type="button" onclick="insertBlockAfter('table',this)" class="text-xs px-2 py-1 rounded border border-green-300 hover:bg-green-50">📊</button>
            <button type="button" onclick="insertBlockAfter('buttons',this)" class="text-xs px-2 py-1 rounded border border-purple-300 hover:bg-purple-50">🔘</button>
        </div>`;

        div.innerHTML = inner;
        return div;
    }

    // ─── ADD / INSERT / REMOVE ────────────────────────────────
    window.addBlock = function(type) {
        document.getElementById('blocks-empty-msg')?.remove();
        const newBlock = buildBlock(type, blockIndex++);
        document.getElementById('blocks-container').appendChild(newBlock);
        if (type === 'text' || type === 'list') {
            const editor = newBlock.querySelector('.paragraph-editor');
            if (editor) { editor.focus(); currentEditorForLink = editor; }
        }
        if (type === 'table') setTimeout(() => updateTablePreviewStyles(blockIndex - 1), 100);
        if (type === 'buttons') setTimeout(() => updateButtonPreview(blockIndex - 1), 100);
    };

    window.insertBlockAfter = function(type, btn) {
        document.getElementById('blocks-empty-msg')?.remove();
        const ref = btn.closest('.block-item');
        const div = buildBlock(type, blockIndex++);
        if (ref) ref.insertAdjacentElement('afterend', div);
        else document.getElementById('blocks-container').appendChild(div);
        if (type === 'text' || type === 'list') {
            const editor = div.querySelector('.paragraph-editor');
            if (editor) editor.focus();
        }
        if (type === 'table') setTimeout(() => updateTablePreviewStyles(blockIndex - 1), 100);
        if (type === 'buttons') setTimeout(() => updateButtonPreview(blockIndex - 1), 100);
    };

    window.removeBlock = function(btn) {
        const block = btn.closest('.block-item');
        delete blockFiles[block.dataset.index];
        block.remove();
        if (!document.querySelector('.block-item')) {
            document.getElementById('blocks-container').innerHTML = `
                <div id="blocks-empty-msg" class="text-center py-10 text-gray-400 border-2 border-dashed border-gray-200 rounded-lg">
                    <p class="text-sm">Your page is empty.</p>
                    <p class="text-xs mt-1">Use the toolbar at the bottom to add content blocks.</p>
                </div>`;
        }
    };

    // ─── CONTENT SYNC ──────────────────────────────────────────
    window.syncContent = function(el, idx) {
        const h = document.getElementById('content-' + idx);
        if (h) h.value = el.innerHTML;
    };

    // ─── HEADING PREVIEW ──────────────────────────────────────
    window.updateHeadingPreview = function(el) {
        const block = el.closest('.block-item');
        const level = block.querySelector('select').value;
        const text = block.querySelector('.heading-text').value || 'Preview appears here...';
        const styles = {
            h1: 'text-3xl font-bold text-gray-900',
            h2: 'text-2xl font-bold text-gray-800',
            h3: 'text-xl font-semibold text-gray-800',
            h4: 'text-lg font-semibold text-gray-700',
            h5: 'text-base font-semibold text-gray-700',
            h6: 'text-sm font-semibold text-gray-600'
        };
        block.querySelector('.heading-preview').innerHTML = `<${level} class="${styles[level]} not-italic">${escapeHtml(text)}</${level}>`;
    };

    // ─── IMAGE HELPER FUNCTIONS ───────────────────────────────
    window.accumulateImages = function(input, idx) {
        if (!blockFiles[idx]) blockFiles[idx] = [];
        Array.from(input.files).forEach(f => {
            if (!blockFiles[idx].some(x => x.name === f.name && x.size === f.size)) blockFiles[idx].push(f);
        });
        input.value = '';
        renderImageGrid(idx);
        syncFiles(idx);
    };

    function syncFiles(idx) {
        const t = document.getElementById('img-transfer-' + idx);
        if (t) {
            t.innerHTML = '';
            const dt = new DataTransfer();
            (blockFiles[idx] || []).forEach(f => dt.items.add(f));
            const inp = document.createElement('input');
            inp.type = 'file';
            inp.name = `blocks[${idx}][images][]`;
            inp.multiple = true;
            inp.className = 'hidden';
            inp.files = dt.files;
            t.appendChild(inp);
        }
    }

    function renderImageGrid(idx) {
        const grid = document.getElementById('img-grid-' + idx);
        const count = document.getElementById('img-count-' + idx);
        const files = blockFiles[idx] || [];
        if (count) count.textContent = files.length ? `(${files.length})` : '';
        if (!grid) return;
        grid.innerHTML = '';
        files.forEach((file, i) => {
            const wrapper = document.createElement('div');
            wrapper.className = 'border border-gray-200 rounded-lg overflow-hidden bg-white';
            wrapper.innerHTML = `
                <div class="relative">
                    <img src="${URL.createObjectURL(file)}" class="w-full object-cover" style="height:100px;">
                    <button type="button" class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-5 h-5 text-xs flex items-center justify-center hover:bg-red-600" 
                            onclick="(() => { blockFiles[${idx}].splice(${i}, 1); renderImageGrid(${idx}); syncFiles(${idx}); })()">&times;</button>
                </div>
                <div class="p-2 bg-gray-50">
                    <input type="text" name="blocks[${idx}][alts][${i}]" placeholder="Alt text" class="w-full border border-gray-200 rounded px-2 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>
            `;
            grid.appendChild(wrapper);
        });
    }

    // ─── DELETE BLOCK IMAGE (FIXED) ──────────────────────────
    window.deleteBlockImage = function(btn, idx, imageId) {
        if (!confirm('Delete this image from the content block?')) return;
        const item = btn.closest('.existing-image-item');
        const block = document.querySelector(`.block-item[data-index="${idx}"]`);
        
        // Add hidden input to mark for deletion
        const flagInput = document.createElement('input');
        flagInput.type = 'hidden';
        flagInput.name = `blocks[${idx}][delete_images][]`;
        flagInput.value = imageId;
        block.appendChild(flagInput);
        
        // Visual feedback
        item.style.opacity = '0.3';
        item.style.pointerEvents = 'none';
        item.style.textDecoration = 'line-through';
        
        // Show deleted badge
        const badge = document.createElement('span');
        badge.className = 'absolute top-1 left-1 bg-red-500 text-white text-xs px-2 py-0.5 rounded';
        badge.textContent = 'Will be deleted';
        item.appendChild(badge);
        
        // Hide the delete button
        btn.style.display = 'none';
        
        console.log('Block image marked for deletion:', imageId);
    };

    // ─── DELETE GALLERY IMAGE ──────────────────────────────────
    window.deleteGalleryImage = function(btn, imageId) {
        if (!confirm('Delete this gallery image?')) return;
        const item = btn.closest('.existing-gallery-item');
        
        const form = document.getElementById('accommodation-form');
        const flagInput = document.createElement('input');
        flagInput.type = 'hidden';
        flagInput.name = 'delete_images[]';
        flagInput.value = imageId;
        form.appendChild(flagInput);
        
        item.style.opacity = '0.3';
        item.style.pointerEvents = 'none';
        item.style.textDecoration = 'line-through';
        
        const badge = document.createElement('span');
        badge.className = 'absolute top-1 left-1 bg-red-500 text-white text-xs px-2 py-0.5 rounded';
        badge.textContent = 'Will be deleted';
        item.appendChild(badge);
        
        btn.style.display = 'none';
    };

    // ─── TABLE HELPER FUNCTIONS ───────────────────────────────
    window.addTableRow = function(idx) {
        const tbody = document.getElementById('table-body-' + idx);
        if (!tbody) return;
        const rowCount = tbody.querySelectorAll('tr').length;
        const headerCells = document.querySelectorAll('#table-header-' + idx + ' th');
        const colCount = headerCells.length - 1;
        const tr = document.createElement('tr');
        for (let c = 0; c < colCount; c++) {
            const td = document.createElement('td');
            const input = document.createElement('input');
            input.type = 'text';
            input.name = `blocks[${idx}][rows][${rowCount}][${c}]`;
            input.value = `Row ${rowCount + 1}, Col ${c + 1}`;
            input.className = 'w-full border-0 bg-transparent text-sm focus:ring-1 focus:ring-blue-500 rounded px-1';
            input.oninput = function() { updateTablePreviewStyles(idx); };
            td.appendChild(input);
            tr.appendChild(td);
        }
        const tdAction = document.createElement('td');
        tdAction.style.textAlign = 'center';
        tdAction.innerHTML = `<button type="button" onclick="removeTableRow(this, ${idx})" class="text-red-400 hover:text-red-600 text-xs">×</button>`;
        tr.appendChild(tdAction);
        tbody.appendChild(tr);
        updateTablePreviewStyles(idx);
    };

    window.removeTableRow = function(btn, idx) {
        const tr = btn.closest('tr');
        if (tr && tr.parentElement.children.length > 1) { tr.remove(); updateTablePreviewStyles(idx); }
    };

    window.addTableColumn = function(idx) {
        const headerRow = document.getElementById('table-header-' + idx);
        const tbody = document.getElementById('table-body-' + idx);
        if (!headerRow || !tbody) return;
        const colCount = headerRow.querySelectorAll('th').length - 1;
        const th = document.createElement('th');
        const input = document.createElement('input');
        input.type = 'text';
        input.name = `blocks[${idx}][headers][${colCount}]`;
        input.value = `Column ${colCount + 1}`;
        input.className = 'w-full border-0 bg-transparent text-sm font-medium focus:ring-1 focus:ring-blue-500 rounded px-1';
        input.oninput = function() { updateTablePreviewStyles(idx); };
        th.appendChild(input);
        headerRow.insertBefore(th, headerRow.lastElementChild);
        const rows = tbody.querySelectorAll('tr');
        rows.forEach((row, rowIndex) => {
            const td = document.createElement('td');
            const input2 = document.createElement('input');
            input2.type = 'text';
            input2.name = `blocks[${idx}][rows][${rowIndex}][${colCount}]`;
            input2.value = `Row ${rowIndex + 1}, Col ${colCount + 1}`;
            input2.className = 'w-full border-0 bg-transparent text-sm focus:ring-1 focus:ring-blue-500 rounded px-1';
            input2.oninput = function() { updateTablePreviewStyles(idx); };
            td.appendChild(input2);
            row.insertBefore(td, row.lastElementChild);
        });
        updateTablePreviewStyles(idx);
    };

    window.removeTableColumn = function(idx) {
        const headerRow = document.getElementById('table-header-' + idx);
        const tbody = document.getElementById('table-body-' + idx);
        if (!headerRow || !tbody) return;
        const colCount = headerRow.querySelectorAll('th').length - 1;
        if (colCount <= 1) return;
        headerRow.removeChild(headerRow.children[colCount - 1]);
        const rows = tbody.querySelectorAll('tr');
        rows.forEach(row => { if (row.children.length > 2) row.removeChild(row.children[row.children.length - 2]); });
        updateTablePreviewStyles(idx);
    };

    window.updateTablePreviewStyles = function(idx) {
        const preview = document.getElementById('table-preview-' + idx);
        if (!preview) return;
        const block = document.querySelector(`.block-item[data-index="${idx}"]`);
        if (!block) return;
        const striped = block.querySelector('input[name*="[striped]"]')?.checked || false;
        const bordered = block.querySelector('input[name*="[bordered]"]')?.checked || true;
        const hoverable = block.querySelector('input[name*="[hoverable]"]')?.checked || false;
        const small = block.querySelector('input[name*="[small]"]')?.checked || false;
        const headerBg = block.querySelector('input[name*="[header_bg_color]"]')?.value || '#f3f4f6';
        const headerText = block.querySelector('input[name*="[header_text_color]"]')?.value || '#111827';
        const rowBg = block.querySelector('input[name*="[row_bg_color]"]')?.value || '#ffffff';
        const rowAltBg = block.querySelector('input[name*="[row_bg_alt_color]"]')?.value || '#f9fafb';
        const rowText = block.querySelector('input[name*="[row_text_color]"]')?.value || '#111827';
        const borderColor = block.querySelector('input[name*="[border_color]"]')?.value || '#d1d5db';
        preview.className = 'table-preview';
        if (striped) preview.classList.add('striped');
        if (bordered) preview.classList.add('bordered');
        if (hoverable) preview.classList.add('hoverable');
        if (small) preview.classList.add('small');
        preview.querySelectorAll('thead th').forEach(th => {
            th.style.backgroundColor = headerBg;
            th.style.color = headerText;
            th.style.borderColor = bordered ? borderColor : 'transparent';
            const input = th.querySelector('input');
            if (input) input.style.color = headerText;
        });
        preview.querySelectorAll('tbody tr').forEach((tr, i) => {
            tr.querySelectorAll('td').forEach(td => {
                td.style.color = rowText;
                td.style.backgroundColor = (striped && i % 2 === 1) ? rowAltBg : rowBg;
                td.style.borderColor = bordered ? borderColor : 'transparent';
                const input = td.querySelector('input');
                if (input) { input.style.color = rowText; input.style.backgroundColor = 'transparent'; }
            });
        });
    };

    // ─── BUTTON HELPER FUNCTIONS ──────────────────────────────
    window.addButtonItem = function(idx) {
        const container = document.getElementById('buttons-container-' + idx);
        if (!container) return;
        const btnCount = container.querySelectorAll('.button-item').length;
        const btnDiv = document.createElement('div');
        btnDiv.className = 'button-item border border-gray-200 rounded-lg p-3 bg-white mt-2';
        btnDiv.dataset.btnIndex = btnCount;
        btnDiv.innerHTML = `
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-medium text-gray-600">Button ${btnCount + 1}</span>
                <button type="button" onclick="removeButtonItem(this, ${idx})" class="text-xs text-red-400 hover:text-red-600">Remove</button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                <div><label class="block text-xs text-gray-500">Text</label><input type="text" name="blocks[${idx}][buttons][${btnCount}][text]" value="Button ${btnCount + 1}" class="w-full border border-gray-300 rounded px-2 py-1 text-sm" oninput="updateButtonPreview(${idx})"></div>
                <div><label class="block text-xs text-gray-500">URL</label><input type="text" name="blocks[${idx}][buttons][${btnCount}][url]" value="#" class="w-full border border-gray-300 rounded px-2 py-1 text-sm" placeholder="https://..." oninput="updateButtonPreview(${idx})"></div>
                <div><label class="block text-xs text-gray-500">Size</label><select name="blocks[${idx}][buttons][${btnCount}][size]" class="w-full border border-gray-300 rounded px-2 py-1 text-sm" onchange="updateButtonPreview(${idx})"><option value="small">Small</option><option value="medium" selected>Medium</option><option value="large">Large</option></select></div>
                <div><label class="block text-xs text-gray-500">Type</label><select name="blocks[${idx}][buttons][${btnCount}][type]" class="w-full border border-gray-300 rounded px-2 py-1 text-sm" onchange="updateButtonPreview(${idx})"><option value="primary">Primary</option><option value="secondary">Secondary</option><option value="outline">Outline</option><option value="ghost">Ghost</option></select></div>
                <div><label class="block text-xs text-gray-500">BG Color</label><input type="color" name="blocks[${idx}][buttons][${btnCount}][bg_color]" value="#2563eb" class="w-full h-8 rounded border border-gray-300 cursor-pointer" onchange="updateButtonPreview(${idx})"></div>
                <div><label class="block text-xs text-gray-500">Text Color</label><input type="color" name="blocks[${idx}][buttons][${btnCount}][text_color]" value="#ffffff" class="w-full h-8 rounded border border-gray-300 cursor-pointer" onchange="updateButtonPreview(${idx})"></div>
                <div><label class="block text-xs text-gray-500">Hover BG</label><input type="color" name="blocks[${idx}][buttons][${btnCount}][hover_bg_color]" value="#1d4ed8" class="w-full h-8 rounded border border-gray-300 cursor-pointer" onchange="updateButtonPreview(${idx})"></div>
                <div><label class="block text-xs text-gray-500">Border Radius</label><input type="text" name="blocks[${idx}][buttons][${btnCount}][border_radius]" value="8px" class="w-full border border-gray-300 rounded px-2 py-1 text-sm" placeholder="8px" onchange="updateButtonPreview(${idx})"></div>
            </div>
        `;
        container.appendChild(btnDiv);
        updateButtonPreview(idx);
    };

    window.removeButtonItem = function(btn, idx) {
        const item = btn.closest('.button-item');
        if (item) {
            const container = document.getElementById('buttons-container-' + idx);
            if (container.querySelectorAll('.button-item').length > 1) {
                item.remove();
                container.querySelectorAll('.button-item').forEach((el, i) => {
                    el.dataset.btnIndex = i;
                    const label = el.querySelector('.text-xs.font-medium');
                    if (label) label.textContent = `Button ${i + 1}`;
                });
                updateButtonPreview(idx);
            }
        }
    };

    window.updateButtonPreview = function(idx) {
        const previewContainer = document.getElementById('button-preview-' + idx);
        if (!previewContainer) return;
        const block = document.querySelector(`.block-item[data-index="${idx}"]`);
        if (!block) return;
        const alignment = block.querySelector('select[name*="[alignment]"]')?.value || 'left';
        const direction = block.querySelector('select[name*="[direction]"]')?.value || 'horizontal';
        const gap = block.querySelector('select[name*="[gap]"]')?.value || 'medium';
        const defaultBg = block.querySelector('input[name*="[default_bg_color]"]')?.value || '#2563eb';
        const defaultText = block.querySelector('input[name*="[default_text_color]"]')?.value || '#ffffff';
        const defaultHoverBg = block.querySelector('input[name*="[default_hover_bg_color]"]')?.value || '#1d4ed8';
        const defaultRadius = block.querySelector('input[name*="[default_border_radius]"]')?.value || '8px';
        const buttonItems = block.querySelectorAll('.button-item');
        previewContainer.className = 'btn-preview-group';
        previewContainer.style.display = 'flex';
        previewContainer.style.flexDirection = direction === 'vertical' ? 'column' : 'row';
        previewContainer.style.alignItems = direction === 'vertical' ? 'flex-start' : 'center';
        previewContainer.classList.add('gap-' + gap);
        if (alignment === 'center') previewContainer.style.justifyContent = 'center';
        else if (alignment === 'right') previewContainer.style.justifyContent = 'flex-end';
        else if (alignment === 'justify') { previewContainer.style.justifyContent = 'space-between'; previewContainer.style.width = '100%'; }
        else previewContainer.style.justifyContent = 'flex-start';
        previewContainer.innerHTML = '';
        buttonItems.forEach((item, i) => {
            const text = item.querySelector('input[name*="[text]"]')?.value || `Button ${i + 1}`;
            const url = item.querySelector('input[name*="[url]"]')?.value || '#';
            const size = item.querySelector('select[name*="[size]"]')?.value || 'medium';
            const type = item.querySelector('select[name*="[type]"]')?.value || 'primary';
            const bgColor = item.querySelector('input[name*="[bg_color]"]')?.value || defaultBg;
            const textColor = item.querySelector('input[name*="[text_color]"]')?.value || defaultText;
            const hoverBg = item.querySelector('input[name*="[hover_bg_color]"]')?.value || defaultHoverBg;
            const radius = item.querySelector('input[name*="[border_radius]"]')?.value || defaultRadius;
            const a = document.createElement('a');
            a.href = url;
            a.target = '_blank';
            a.rel = 'noopener noreferrer';
            a.className = `btn-preview ${size} ${type}`;
            a.textContent = text;
            if (type === 'outline') {
                a.style.backgroundColor = 'transparent';
                a.style.border = `2px solid ${bgColor}`;
                a.style.color = bgColor;
                a.style.borderRadius = radius;
                a.onmouseover = function() { this.style.backgroundColor = bgColor; this.style.color = textColor; };
                a.onmouseout = function() { this.style.backgroundColor = 'transparent'; this.style.color = bgColor; };
            } else if (type === 'ghost') {
                a.style.backgroundColor = 'transparent';
                a.style.border = 'none';
                a.style.color = bgColor;
                a.style.borderRadius = radius;
                a.onmouseover = function() { this.style.backgroundColor = '#eff6ff'; };
                a.onmouseout = function() { this.style.backgroundColor = 'transparent'; };
            } else {
                a.style.backgroundColor = bgColor;
                a.style.color = textColor;
                a.style.border = 'none';
                a.style.borderRadius = radius;
                a.onmouseover = function() { this.style.backgroundColor = hoverBg; };
                a.onmouseout = function() { this.style.backgroundColor = bgColor; };
            }
            previewContainer.appendChild(a);
        });
    };

    // ─── INLINE QUOTE FUNCTION ─────────────────────────────────
    window.insertInlineQuote = function() {
        const editor = currentEditorForLink;
        if (!editor) {
            alert('Please click inside a paragraph first, then click Quote.');
            return;
        }
        editor.focus();
        const sel = window.getSelection();
        if (!sel || sel.isCollapsed || !sel.toString().trim()) {
            alert('Please highlight some text first.');
            return;
        }
        
        const range = sel.getRangeAt(0);
        const selectedText = range.extractContents();
        
        const blockquote = document.createElement('blockquote');
        blockquote.appendChild(selectedText);
        
        range.insertNode(blockquote);
        
        range.setStartAfter(blockquote);
        range.collapse(true);
        sel.removeAllRanges();
        sel.addRange(range);
        
        const idx = editor.dataset.index;
        const hiddenInput = document.getElementById('content-' + idx);
        if (hiddenInput) hiddenInput.value = editor.innerHTML;
    };

    // ─── FONT AWESOME ICON FUNCTIONS ──────────────────────────
    window.openIconModal = function() {
        const editor = currentEditorForLink;
        if (!editor) {
            alert('Please click inside a paragraph first, then click Icon.');
            return;
        }
        editor.focus();
        document.getElementById('icon-modal').classList.remove('hidden');
        document.getElementById('icon-modal').classList.add('flex');
        document.getElementById('icon-search').value = '';
        document.querySelectorAll('#icon-grid .icon-item').forEach(el => el.style.display = '');
    };

    window.closeIconModal = function() {
        document.getElementById('icon-modal').classList.add('hidden');
        document.getElementById('icon-modal').classList.remove('flex');
    };

    window.filterIcons = function(searchTerm) {
        const term = searchTerm.toLowerCase().trim();
        document.querySelectorAll('#icon-grid .icon-item').forEach(el => {
            const iconName = el.dataset.icon.toLowerCase();
            if (term === '' || iconName.includes(term)) {
                el.style.display = '';
            } else {
                el.style.display = 'none';
            }
        });
    };

    window.insertFontAwesomeIcon = function(iconClass) {
        const editor = currentEditorForLink;
        if (!editor) {
            closeIconModal();
            alert('Please click inside a paragraph first.');
            return;
        }
        editor.focus();
        
        const sel = window.getSelection();
        const range = sel.getRangeAt(0);
        
        const span = document.createElement('span');
        span.className = 'inline-icon';
        span.innerHTML = `<i class="fas ${iconClass}"></i>`;
        span.title = 'Right-click to remove this icon';
        
        range.insertNode(span);
        
        range.setStartAfter(span);
        range.collapse(true);
        sel.removeAllRanges();
        sel.addRange(range);
        
        const idx = editor.dataset.index;
        const hiddenInput = document.getElementById('content-' + idx);
        if (hiddenInput) hiddenInput.value = editor.innerHTML;
        
        closeIconModal();
    };

    // ─── LINK HANDLING ─────────────────────────────────────────
    window.saveSelection = function() {
        const sel = window.getSelection();
        if (sel && !sel.isCollapsed && sel.toString().trim()) {
            savedSelectionRange = sel.getRangeAt(0).cloneRange();
            const editor = sel.anchorNode?.nodeType === 3 ? sel.anchorNode.parentElement?.closest('[data-block-type="text"], [data-block-type="list"]') : sel.anchorNode?.closest('[data-block-type="text"], [data-block-type="list"]');
            if (editor) currentEditorForLink = editor;
        } else savedSelectionRange = null;
    };

    window.setCurrentEditor = function(el) { currentEditorForLink = el; };

    function openLinkModal() {
        if (!currentEditorForLink) { alert('Please click inside a paragraph or list first.'); return; }
        currentEditorForLink.focus();
        const sel = window.getSelection();
        const selectedText = sel && !sel.isCollapsed ? sel.toString().trim() : '';
        document.getElementById('modal-link-text').value = selectedText || '';
        document.getElementById('modal-link-url').value = '';
        document.getElementById('modal-link-error').classList.add('hidden');
        document.getElementById('link-modal').classList.remove('hidden');
        document.getElementById('link-modal').classList.add('flex');
    }

    window.closeLinkModal = function() {
        document.getElementById('link-modal').classList.add('hidden');
        document.getElementById('link-modal').classList.remove('flex');
        savedSelectionRange = null;
    };

    window.insertInlineLink = function() {
        const linkText = document.getElementById('modal-link-text').value.trim();
        const linkUrl = document.getElementById('modal-link-url').value.trim();
        if (!linkText || !linkUrl) { document.getElementById('modal-link-error').classList.remove('hidden'); return; }
        if (!currentEditorForLink) { closeLinkModal(); return; }
        currentEditorForLink.focus();
        if (savedSelectionRange) { const sel = window.getSelection(); sel.removeAllRanges(); sel.addRange(savedSelectionRange); }
        const linkHtml = `<a href="${escapeHtml(linkUrl)}" target="_blank" rel="noopener noreferrer">${escapeHtml(linkText)}</a>`;
        document.execCommand('insertHTML', false, linkHtml);
        const idx = currentEditorForLink.dataset.index;
        const hiddenInput = document.getElementById('content-' + idx);
        if (hiddenInput) hiddenInput.value = currentEditorForLink.innerHTML;
        savedSelectionRange = null;
        closeLinkModal();
    };

    window.closeEditLinkModal = function() {
        document.getElementById('edit-link-modal').classList.add('hidden');
        document.getElementById('edit-link-modal').classList.remove('flex');
        currentEditingLink = null;
        currentEditingEditor = null;
    };

    window.saveEditedLink = function() {
        if (!currentEditingLink || !currentEditingEditor) return;
        const newText = document.getElementById('edit-link-text').value.trim();
        const newUrl = document.getElementById('edit-link-url').value.trim();
        if (!newText || !newUrl) return;
        const newLink = document.createElement('a');
        newLink.href = newUrl;
        newLink.target = '_blank';
        newLink.rel = 'noopener noreferrer';
        newLink.textContent = newText;
        currentEditingLink.parentNode.replaceChild(newLink, currentEditingLink);
        const idx = currentEditingEditor.dataset.index;
        const hiddenInput = document.getElementById('content-' + idx);
        if (hiddenInput) hiddenInput.value = currentEditingEditor.innerHTML;
        closeEditLinkModal();
    };

    window.removeCurrentLink = function() {
        if (!currentEditingLink || !currentEditingEditor) return;
        const text = document.createTextNode(currentEditingLink.textContent);
        currentEditingLink.parentNode.replaceChild(text, currentEditingLink);
        const idx = currentEditingEditor.dataset.index;
        const hiddenInput = document.getElementById('content-' + idx);
        if (hiddenInput) hiddenInput.value = currentEditingEditor.innerHTML;
        closeEditLinkModal();
    };

    document.addEventListener('dblclick', function(e) {
        const link = e.target.closest('a');
        if (link && link.closest('[data-block-type="text"], [data-block-type="list"]')) {
            e.preventDefault();
            e.stopPropagation();
            currentEditingLink = link;
            currentEditingEditor = link.closest('[data-block-type="text"], [data-block-type="list"]');
            document.getElementById('edit-link-text').value = link.textContent;
            document.getElementById('edit-link-url').value = link.href;
            document.getElementById('edit-link-modal').classList.remove('hidden');
            document.getElementById('edit-link-modal').classList.add('flex');
        }
    });

    // ─── MODAL BACKDROP CLOSE ──────────────────────────────────
    document.getElementById('link-modal').addEventListener('click', function(e) { if (e.target === this) closeLinkModal(); });
    document.getElementById('edit-link-modal').addEventListener('click', function(e) { if (e.target === this) closeEditLinkModal(); });
    document.getElementById('icon-modal').addEventListener('click', function(e) { if (e.target === this) closeIconModal(); });

    // ─── ADD INLINE BUTTONS ──────────────────────────────────
    document.getElementById('global-add-link-btn').addEventListener('click', openLinkModal);
    document.getElementById('inline-quote-btn').addEventListener('click', window.insertInlineQuote);
    document.getElementById('global-icon-btn').addEventListener('click', window.openIconModal);

    // ─── KEYBOARD SHORTCUTS ────────────────────────────────────
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey && e.shiftKey && e.key === 'Q') {
            e.preventDefault();
            window.insertInlineQuote();
        }
        if (e.ctrlKey && e.shiftKey && e.key === 'I') {
            e.preventDefault();
            window.openIconModal();
        }
        if (e.ctrlKey && e.key === 'k') {
            e.preventDefault();
            openLinkModal();
        }
        if (e.key === 'Escape') {
            closeLinkModal();
            closeEditLinkModal();
            closeIconModal();
            document.getElementById('context-menu').classList.add('hidden');
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        const nameInput = document.getElementById('name-input');
        const slugInput = document.getElementById('slug-input');
        if (nameInput && slugInput) {
            nameInput.addEventListener('input', function() {
                if (slugInput.dataset.manual !== 'true') {
                    slugInput.value = this.value.toLowerCase().trim().replace(/[^a-z0-9\s-]/g, '').replace(/\s+/g, '-');
                }
            });
            slugInput.addEventListener('input', function() { this.dataset.manual = 'true'; });
        }
        document.querySelectorAll('.paragraph-editor').forEach(editor => {
            editor.addEventListener('keyup', saveSelection);
            editor.addEventListener('mouseup', saveSelection);
            editor.addEventListener('click', () => setCurrentEditor(editor));
            editor.addEventListener('focus', () => setCurrentEditor(editor));
        });
        document.querySelectorAll('.block-item').forEach(block => {
            const idx = block.dataset.index;
            const type = block.querySelector('input[name*="[type]"]')?.value;
            if (type === 'table') setTimeout(() => updateTablePreviewStyles(idx), 200);
            if (type === 'buttons') setTimeout(() => updateButtonPreview(idx), 200);
        });
    });

    // ─── FEATURED IMAGE ───────────────────────────────────────
    window.previewFeaturedImage = function(input) {
        if (!input.files[0]) return;
        let preview = document.getElementById('featured-preview');
        const prompt = document.getElementById('featured-upload-prompt');
        if (!preview) {
            const dropZone = document.getElementById('featured-drop-zone');
            preview = document.createElement('div');
            preview.id = 'featured-preview';
            preview.className = 'mb-3';
            preview.innerHTML = `<img id="featured-preview-img" class="w-full rounded-lg object-cover border border-gray-200" style="max-height:180px;"><button type="button" onclick="removeFeaturedImage()" class="mt-2 text-xs text-red-500 hover:text-red-700">Remove image</button>`;
            dropZone.parentNode.insertBefore(preview, dropZone);
        }
        document.getElementById('featured-preview-img').src = URL.createObjectURL(input.files[0]);
        preview.classList.remove('hidden');
        if (prompt) prompt.classList.add('hidden');
    };

    window.removeFeaturedImage = function() {
        document.getElementById('featured-image-input').value = '';
        const preview = document.getElementById('featured-preview');
        const prompt = document.getElementById('featured-upload-prompt');
        if (preview) preview.classList.add('hidden');
        if (prompt) prompt.classList.remove('hidden');
    };

    // ─── GALLERY IMAGES ────────────────────────────────────────
    const galleryInput = document.getElementById('gallery_images');
    const galleryGrid = document.getElementById('gallery_preview_grid');
    const galleryEmptyMsg = document.getElementById('gallery_empty_msg');
    let galleryFiles = [];

    if (galleryInput) {
        galleryInput.addEventListener('change', function() {
            const newFiles = Array.from(this.files);

            newFiles.forEach(file => {
                const exists = galleryFiles.some(
                    f => f.name === file.name && f.size === file.size && f.lastModified === file.lastModified
                );
                if (!exists) {
                    galleryFiles.push(file);
                }
            });

            const dt = new DataTransfer();
            galleryFiles.forEach(file => dt.items.add(file));
            galleryInput.files = dt.files;

            renderGalleryPreviews();
            this.value = '';
        });
    }

    function renderGalleryPreviews() {
        galleryGrid.innerHTML = '';

        if (!galleryFiles.length) {
            galleryEmptyMsg.classList.remove('hidden');
            return;
        }

        galleryEmptyMsg.classList.add('hidden');

        galleryFiles.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = e => {
                const wrapper = document.createElement('div');
                wrapper.className = 'relative group';

                wrapper.innerHTML = `
                    <img src="${e.target.result}"
                         alt="${file.name}"
                         class="w-full h-24 object-cover rounded-lg border shadow">
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 rounded-lg transition"></div>
                    <button type="button"
                            data-index="${index}"
                            class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs hover:bg-red-600 opacity-0 group-hover:opacity-100 transition">
                        &times;
                    </button>
                    <p class="text-xs text-gray-500 mt-1 truncate" title="${file.name}">${file.name}</p>
                `;

                const btn = wrapper.querySelector('button');
                btn.addEventListener('click', function() {
                    removeGalleryImage(parseInt(this.getAttribute('data-index'), 10));
                });

                galleryGrid.appendChild(wrapper);
            };
            reader.readAsDataURL(file);
        });
    }

    function removeGalleryImage(index) {
        galleryFiles.splice(index, 1);

        const dt = new DataTransfer();
        galleryFiles.forEach(file => dt.items.add(file));
        galleryInput.files = dt.files;

        renderGalleryPreviews();
    }

    // ─── PASTE CLEANUP ─────────────────────────────────────────
    document.addEventListener('paste', function(e) {
        const target = e.target;
        if (target && target.matches && target.matches('[data-block-type="text"], [data-block-type="list"]')) {
            e.preventDefault();
            const text = (e.clipboardData || window.clipboardData).getData('text/plain');
            document.execCommand('insertText', false, text);
        }
    });

    // ─── FORM SUBMIT ───────────────────────────────────────────
    document.getElementById('accommodation-form').addEventListener('submit', function(e) {
        // Log delete images for debugging
        const deleteInputs = document.querySelectorAll('input[name="delete_images[]"]');
        console.log('Delete images to be sent:', Array.from(deleteInputs).map(el => el.value));
        
        Object.keys(blockFiles).forEach(idx => syncFiles(idx));
        document.querySelectorAll('.paragraph-editor').forEach(el => {
            const idx = el.dataset.index;
            const h = document.getElementById('content-' + idx);
            if (h) h.value = el.innerHTML;
        });
    });

    window.escapeHtml = function(str) {
        if (!str) return '';
        return str.replace(/[&<>"']/g, function(m) { return { '&':'&amp;', '<':'&lt;', '>':'&gt;', '"':'&quot;', "'": '&#39;' }[m]; });
    };

})();
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\safaris\resources\views/admin/accommodations/edit.blade.php ENDPATH**/ ?>