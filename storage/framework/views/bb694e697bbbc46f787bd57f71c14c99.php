

<?php $__env->startSection('title', $page->seo_title ?: $page->title); ?>
<?php $__env->startSection('meta_description', $page->meta_description); ?>
<?php $__env->startSection('meta_keywords', $page->focus_keyword); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-gray-50">
    
    <section class="relative w-full min-h-screen flex items-center justify-center bg-no-repeat bg-center bg-fixed"
             style="background-image: url('<?php echo e($page->featured_image ? asset('storage/' . $page->featured_image) : ''); ?>'); background-size: cover; background-color: #1a3c34; will-change: transform;">
        
        <div class="absolute inset-0 bg-black/60"></div>
        
        <div class="relative z-10 container mx-auto px-4 text-center text-white py-16">
            <div class="max-w-4xl mx-auto">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold leading-tight animate-fade-in"><?php echo e($page->title); ?></h1>

                
                <nav class="mt-4 text-sm md:text-base animate-fade-in-up">
                    <ol class="flex justify-center space-x-2 text-green-200">
                        <li><a href="<?php echo e(route('index')); ?>" class="hover:text-white transition-colors duration-300 text-green-300">Home</a></li>
                        <li class="mx-1">/</li>
                        <li class="text-white font-medium"><?php echo e($page->title); ?></li>
                    </ol>
                </nav>
            </div>
        </div>
    </section>

    
    <section class="relative py-12 md:py-16 bg-white/95 backdrop-blur-sm mt-[-2px] rounded-t-3xl shadow-2xl">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                <div class="p-6 md:p-10 lg:p-12">
                    <?php $__empty_1 = true; $__currentLoopData = $page->blocks->sortBy('sort_order'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $block): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php if($block->block_type === 'heading'): ?>
                            <?php
                                $headingClasses = [
                                    'h1' => 'text-3xl md:text-4xl lg:text-5xl font-extrabold text-gray-900 mb-6',
                                    'h2' => 'text-2xl md:text-3xl lg:text-4xl font-bold text-gray-800 mb-4 mt-8',
                                    'h3' => 'text-xl md:text-2xl lg:text-3xl font-bold text-gray-800 mb-3 mt-6',
                                    'h4' => 'text-lg md:text-xl lg:text-2xl font-semibold text-gray-700 mb-3 mt-5',
                                    'h5' => 'text-base md:text-lg lg:text-xl font-semibold text-gray-700 mb-2 mt-4',
                                    'h6' => 'text-sm md:text-base lg:text-lg font-semibold text-gray-600 mb-2 mt-3',
                                ];
                            ?>
                            <<?php echo e($block->heading_level ?? 'h2'); ?> class="<?php echo e($headingClasses[$block->heading_level ?? 'h2']); ?> scroll-fade">
                                <?php echo e($block->content); ?>

                            </<?php echo e($block->heading_level ?? 'h2'); ?>>

                        <?php elseif($block->block_type === 'text'): ?>
                            <div class="prose prose-lg prose-green max-w-none text-gray-700 leading-relaxed mb-6 scroll-fade">
                                <?php echo $block->content; ?>

                            </div>

                        <?php elseif($block->block_type === 'list'): ?>
                            <?php
                                $listType = $block->list_type ?? 'ul';
                                $listClasses = 'mb-6 space-y-2 text-gray-700 text-base md:text-lg lg:text-xl scroll-fade';
                            ?>
                            <?php if($listType === 'ul'): ?>
                                <ul class="list-disc list-inside <?php echo e($listClasses); ?> space-y-2">
                                    <?php echo $block->content; ?>

                                </ul>
                            <?php else: ?>
                                <ol class="list-decimal list-inside <?php echo e($listClasses); ?> space-y-2">
                                    <?php echo $block->content; ?>

                                </ol>
                            <?php endif; ?>

                        <?php elseif($block->block_type === 'image'): ?>
                            <?php
                                $images = $block->images->sortBy('sort_order');
                                $count = $images->count();

                                if ($count === 1) {
                                    $gridClass = 'grid grid-cols-1';
                                    $imgHeight = 'h-[50vh] md:h-[60vh]';
                                } elseif ($count === 2) {
                                    $gridClass = 'grid grid-cols-1 md:grid-cols-2';
                                    $imgHeight = 'h-64 md:h-80';
                                } else {
                                    $gridClass = 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3';
                                    $imgHeight = 'h-56 md:h-64 lg:h-72';
                                }
                            ?>
                            <div class="<?php echo e($gridClass); ?> gap-6 mb-8">
                                <?php $__currentLoopData = $images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="relative overflow-hidden rounded-xl shadow-lg hover:shadow-2xl transition-all duration-500 group scroll-fade">
                                        <img src="<?php echo e(asset('storage/' . $image->image_path)); ?>" 
                                             alt="<?php echo e($image->alt_text ?? $page->title); ?>" 
                                             class="w-full <?php echo e($imgHeight); ?> object-cover transition-transform duration-700 group-hover:scale-110">
                                        <?php if($image->alt_text): ?>
                                            <div class="absolute bottom-0 left-0 right-0 bg-black/70 text-white text-xs md:text-sm p-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300 backdrop-blur-sm">
                                                <?php echo e($image->alt_text); ?>

                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>

                        <?php elseif($block->block_type === 'links'): ?>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                <?php $__currentLoopData = $block->links; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <a href="<?php echo e($link->linked_page_url); ?>" 
                                       target="_blank" 
                                       rel="noopener noreferrer"
                                       class="flex items-center p-4 md:p-5 bg-gray-50 rounded-xl border-2 border-green-200 hover:border-green-500 transition-all duration-300 group shadow-sm hover:shadow-lg scroll-fade hover:bg-green-50">
                                        <div class="flex-1">
                                            <h4 class="text-base md:text-lg font-semibold text-green-600 group-hover:text-green-800 transition-colors">
                                                <?php echo e($link->linked_page_title); ?>

                                            </h4>
                                            <p class="text-xs md:text-sm text-gray-500 truncate mt-1 group-hover:text-gray-700 transition-colors"><?php echo e($link->linked_page_url); ?></p>
                                        </div>
                                        <svg class="w-5 h-5 text-green-500 group-hover:text-green-700 transition-colors ml-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                        </svg>
                                    </a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>

                        
                        <?php elseif($block->block_type === 'table'): ?>
                            <?php
                                $blockData = is_string($block->content) ? json_decode($block->content, true) : [];
                                $headers = $blockData['headers'] ?? [];
                                $rows = $blockData['rows'] ?? [];
                                $caption = $blockData['caption'] ?? '';
                                
                                $tableClasses = 'min-w-full text-sm md:text-base border-collapse shadow-sm rounded-lg overflow-hidden';
                                if ($blockData['striped'] ?? false) $tableClasses .= ' striped';
                                if ($blockData['bordered'] ?? true) $tableClasses .= ' bordered';
                                if ($blockData['hoverable'] ?? false) $tableClasses .= ' hoverable';
                                if ($blockData['small'] ?? false) $tableClasses .= ' small';
                                
                                $headerBg = $blockData['header_bg_color'] ?? '#1a3c34';
                                $headerText = $blockData['header_text_color'] ?? '#ffffff';
                                $rowBg = $blockData['row_bg_color'] ?? '#ffffff';
                                $rowAltBg = $blockData['row_bg_alt_color'] ?? '#f8fafc';
                                $rowText = $blockData['row_text_color'] ?? '#1e293b';
                                $borderColor = $blockData['border_color'] ?? '#e2e8f0';
                            ?>
                            
                            <div class="mb-8 overflow-x-auto scroll-fade">
                                <?php if(!empty($caption)): ?>
                                    <p class="text-sm text-gray-500 mb-3 italic"><?php echo e($caption); ?></p>
                                <?php endif; ?>
                                
                                <table class="<?php echo e($tableClasses); ?>" style="border-color: <?php echo e($borderColor); ?>;">
                                    <?php if(!empty($headers)): ?>
                                        <thead>
                                            <tr>
                                                <?php $__currentLoopData = $headers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $header): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <th style="background-color: <?php echo e($headerBg); ?>; color: <?php echo e($headerText); ?>; border-color: <?php echo e($borderColor); ?>; padding: 0.75rem 1.25rem; text-align: left; font-weight: 600; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;">
                                                        <?php echo e($header); ?>

                                                    </th>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </tr>
                                        </thead>
                                    <?php endif; ?>
                                    
                                    <?php if(!empty($rows)): ?>
                                        <tbody>
                                            <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rowIndex => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <?php $__currentLoopData = $row; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cell): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <?php
                                                            $isEven = ($rowIndex % 2 === 0);
                                                            $bgColor = ($blockData['striped'] ?? false) ? ($isEven ? $rowBg : $rowAltBg) : $rowBg;
                                                        ?>
                                                        <td style="background-color: <?php echo e($bgColor); ?>; color: <?php echo e($rowText); ?>; border-color: <?php echo e($borderColor); ?>; padding: 0.75rem 1.25rem;">
                                                            <?php echo e($cell); ?>

                                                        </td>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    <?php endif; ?>
                                </table>
                            </div>

                        
                        <?php elseif($block->block_type === 'buttons'): ?>
                            <?php
                                $blockData = is_string($block->content) ? json_decode($block->content, true) : [];
                                $buttons = $blockData['buttons'] ?? [];
                                $alignment = $blockData['alignment'] ?? 'left';
                                $direction = $blockData['direction'] ?? 'horizontal';
                                $gap = $blockData['gap'] ?? 'medium';
                                
                                $gapClasses = [
                                    'small' => 'gap-2',
                                    'medium' => 'gap-3',
                                    'large' => 'gap-4'
                                ];
                                
                                $alignmentClasses = [
                                    'left' => 'justify-start',
                                    'center' => 'justify-center',
                                    'right' => 'justify-end',
                                    'justify' => 'justify-between'
                                ];
                                
                                $directionClasses = [
                                    'horizontal' => 'flex-row',
                                    'vertical' => 'flex-col'
                                ];
                                
                                $buttonSizeClasses = [
                                    'small' => 'px-6 py-3 text-sm',
                                    'medium' => 'px-8 py-4 text-base',
                                    'large' => 'px-10 py-5 text-lg'
                                ];
                            ?>
                            
                            <?php if(!empty($buttons)): ?>
                                <div class="flex flex-wrap <?php echo e($directionClasses[$direction] ?? 'flex-row'); ?> <?php echo e($gapClasses[$gap] ?? 'gap-3'); ?> <?php echo e($alignmentClasses[$alignment] ?? 'justify-start'); ?> mb-8 scroll-fade w-full">
                                    <?php $__currentLoopData = $buttons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $button): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $bgColor = $button['bg_color'] ?? '#2563eb';
                                            $textColor = $button['text_color'] ?? '#ffffff';
                                            $hoverBg = $button['hover_bg_color'] ?? $bgColor;
                                            $hoverText = $button['hover_text_color'] ?? $textColor;
                                            $borderRadius = $button['border_radius'] ?? '8px';
                                            $size = $button['size'] ?? 'medium';
                                            $url = $button['url'] ?? '#';
                                            $target = $button['target'] ?? '_self';
                                            $rel = $button['rel'] ?? '';
                                            $icon = $button['icon'] ?? null;
                                        ?>
                                        
                                        <a href="<?php echo e($url); ?>" 
                                           target="<?php echo e($target); ?>" 
                                           rel="<?php echo e($rel); ?>"
                                           class="inline-flex items-center justify-center font-semibold transition-all duration-300 hover:transform hover:-translate-y-1 hover:shadow-xl <?php echo e($buttonSizeClasses[$size] ?? 'px-8 py-4 text-base'); ?>"
                                           style="
                                               background-color: <?php echo e($bgColor); ?>;
                                               color: <?php echo e($textColor); ?>;
                                               border-radius: <?php echo e($borderRadius); ?>;
                                               border: none;
                                               min-width: <?php echo e($direction === 'vertical' ? '100%' : '120px'); ?>;
                                               <?php echo e($direction === 'vertical' ? 'width: 100%;' : ''); ?>

                                               <?php echo e($alignment === 'justify' ? 'flex: 1;' : ''); ?>

                                           "
                                           onmouseover="this.style.backgroundColor='<?php echo e($hoverBg); ?>'; this.style.color='<?php echo e($hoverText); ?>';"
                                           onmouseout="this.style.backgroundColor='<?php echo e($bgColor); ?>'; this.style.color='<?php echo e($textColor); ?>';">
                                            
                                            <?php if(!empty($icon)): ?>
                                                <i class="<?php echo e($icon); ?> mr-2" style="color: <?php echo e($textColor); ?>;"></i>
                                            <?php endif; ?>
                                            
                                            <?php echo e($button['text'] ?? 'Button'); ?>

                                            <i class="fas fa-arrow-right ml-3 text-sm opacity-75 group-hover:translate-x-1 transition-transform" style="color: <?php echo e($textColor); ?>;"></i>
                                        </a>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="text-center py-12 text-gray-500 text-lg">
                            <p>No content available for this page.</p>
                        </div>
                    <?php endif; ?>

                    
                    <div class="mt-12 pt-8 border-t-2 border-gray-200 scroll-fade">
                        <div class="bg-gradient-to-r from-green-50 to-blue-50 rounded-2xl p-8 md:p-10 text-center shadow-inner hover:shadow-xl transition-shadow duration-500">
                            <h3 class="text-2xl md:text-3xl font-bold text-gray-800 mb-3">Ready to Book Your Safari?</h3>
                            <p class="text-gray-600 text-lg md:text-xl mb-6 max-w-2xl mx-auto">Contact us today and let our expert team help you plan the perfect African adventure.</p>
                            <a href="<?php echo e(route('contact')); ?>" 
                               class="inline-flex items-center bg-green-600 hover:bg-green-700 text-white px-6 py-3 md:px-8 md:py-4 rounded-xl font-bold text-base md:text-lg transition-all duration-300 shadow-lg hover:shadow-2xl transform hover:scale-105 hover:-translate-y-1">
                                Request a Quote
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* Google Font for better typography */
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');
    
    * {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }
    
    /* Smooth scrolling for the entire page */
    html {
        scroll-behavior: smooth;
    }
    
    /* Parallax effect - background stays fixed while content scrolls over */
    .bg-fixed {
        background-attachment: fixed;
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
    }
    
    /* Fade animations for content */
    .animate-fade-in {
        animation: fadeIn 1.2s ease-out forwards;
    }
    
    .animate-fade-in-up {
        animation: fadeInUp 1.2s ease-out 0.3s forwards;
        opacity: 0;
    }
    
    .scroll-fade {
        opacity: 0;
        transform: translateY(30px);
        animation: scrollFadeIn 0.8s ease-out forwards;
    }
    
    /* Stagger animation for child elements */
    .scroll-fade:nth-child(1) { animation-delay: 0.1s; }
    .scroll-fade:nth-child(2) { animation-delay: 0.2s; }
    .scroll-fade:nth-child(3) { animation-delay: 0.3s; }
    .scroll-fade:nth-child(4) { animation-delay: 0.4s; }
    .scroll-fade:nth-child(5) { animation-delay: 0.5s; }
    .scroll-fade:nth-child(6) { animation-delay: 0.6s; }
    .scroll-fade:nth-child(7) { animation-delay: 0.7s; }
    .scroll-fade:nth-child(8) { animation-delay: 0.8s; }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: scale(0.95);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes scrollFadeIn {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Smooth hover transitions */
    .transition-all {
        transition-property: all;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 300ms;
    }
    
    /* Parallax scroll effect - content overlay */
    .rounded-t-3xl {
        border-top-left-radius: 1.5rem;
        border-top-right-radius: 1.5rem;
    }
    
    /* Prose styles for rich text */
    .prose {
        max-width: 100%;
    }
    .prose p {
        margin-bottom: 1.25rem;
        line-height: 1.8;
        font-size: 1.125rem;
    }
    .prose strong {
        color: #1e293b;
        font-weight: 600;
    }
    .prose em {
        color: #475569;
        font-style: italic;
    }
    .prose ul, .prose ol {
        margin: 1rem 0;
        padding-left: 1.5rem;
    }
    .prose li {
        margin-bottom: 0.5rem;
        line-height: 1.8;
    }
    .prose blockquote {
        border-left: 4px solid #059669;
        padding-left: 1.5rem;
        margin: 1.5rem 0;
        font-style: italic;
        color: #475569;
        background-color: #f0fdf4;
        padding: 1rem 1.5rem;
        border-radius: 0.5rem;
    }
    .prose blockquote p {
        margin: 0;
    }
    .prose h2 {
        margin-top: 2.5rem;
        margin-bottom: 1rem;
    }
    .prose h3 {
        margin-top: 2rem;
        margin-bottom: 0.75rem;
    }
    .prose img {
        border-radius: 0.75rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    
    /* Link styles - BLUE links */
    .prose a,
    .text-gray-700 a {
        color: #2563eb !important;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
        border-bottom: 2px solid transparent;
        display: inline !important;
    }
    
    .prose a:hover,
    .text-gray-700 a:hover {
        color: #1d4ed8 !important;
        border-bottom-color: #2563eb;
    }
    
    /* Table styles */
    .striped tbody tr:nth-child(even) {
        background-color: #f8fafc;
    }
    .bordered th,
    .bordered td {
        border: 1px solid #e2e8f0;
    }
    .hoverable tbody tr:hover {
        background-color: #f1f5f9 !important;
    }
    .small th,
    .small td {
        padding: 0.5rem 0.75rem !important;
        font-size: 0.75rem !important;
    }
    
    /* Button hover effects */
    .btn-preview-group a {
        position: relative;
        overflow: hidden;
    }
    .btn-preview-group a::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        transform: translate(-50%, -50%);
        transition: width 0.6s ease, height 0.6s ease;
    }
    .btn-preview-group a:hover::after {
        width: 300px;
        height: 300px;
    }
    
    /* Make buttons fill space in justify alignment */
    .justify-between .btn-preview-group a {
        flex: 1;
        text-align: center;
    }
    
    /* Responsive button sizing */
    @media (max-width: 640px) {
        .btn-preview-group a {
            min-width: 100% !important;
            width: 100% !important;
            justify-content: center;
        }
        .flex-wrap {
            flex-direction: column;
        }
    }
    
    /* Reduce motion for users who prefer it */
    @media (prefers-reduced-motion: reduce) {
        * {
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.01ms !important;
            scroll-behavior: auto !important;
        }
    }
</style>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\safaris\resources\views/seo_pages/show.blade.php ENDPATH**/ ?>