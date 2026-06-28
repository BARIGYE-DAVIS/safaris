<?php
    function clean_title($text) {
        return html_entity_decode(strip_tags($text ?? ''), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
?>

<?php $__env->startSection('title', 'Safari Stories & Travel Guides'); ?>
<?php $__env->startSection('description', 'Discover expert tips, wildlife encounters, and unforgettable adventures from the heart of Africa.'); ?>

<?php $__env->startSection('content'); ?>


<div class="relative bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 overflow-hidden">

    
    <div class="absolute inset-0 opacity-10"
         style="background-image:radial-gradient(circle, #818cf8 1px, transparent 1px);background-size:32px 32px;"></div>

    <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center">

        
        <span class="inline-flex items-center gap-2 px-4 py-1.5
                     bg-indigo-500/20 border border-indigo-500/30
                     text-indigo-300 text-sm font-medium rounded-full mb-6">
            <span class="w-2 h-2 rounded-full bg-indigo-400 animate-pulse"></span>
            Discover the Wild
        </span>

        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white tracking-tight leading-tight mb-5">
            Safari Stories &<br class="hidden sm:block">
            <span class="text-indigo-400">Travel Guides</span>
        </h1>

        <p class="text-lg sm:text-xl text-slate-400 max-w-2xl mx-auto mb-10">
            Expert tips, wildlife encounters, and unforgettable adventures
            from the heart of Africa.
        </p>

        
        <form method="GET" action="<?php echo e(route('blogs.index')); ?>"
              class="max-w-xl mx-auto flex gap-2">
            <div class="flex-1 relative">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                       placeholder="Search stories…"
                       class="w-full pl-11 pr-4 py-3 bg-white/10 border border-white/20
                              text-white placeholder-slate-400 rounded-xl
                              focus:outline-none focus:border-indigo-400 focus:bg-white/15
                              transition text-sm">
            </div>
            <button type="submit"
                    class="px-5 py-3 bg-indigo-600 hover:bg-indigo-500 text-white
                           font-semibold rounded-xl transition text-sm whitespace-nowrap">
                Search
            </button>
        </form>
    </div>
</div>

<div class="bg-slate-50 dark:bg-slate-950 min-h-screen">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    
    <div class="flex flex-wrap items-center justify-center gap-2 mb-12">
        <a href="<?php echo e(route('blogs.index', array_filter(['search' => request('search')]))); ?>"
           class="px-4 py-2 rounded-full text-sm font-semibold transition-all
                  <?php echo e(!request('category')
                     ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/30'
                     : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:border-indigo-400 hover:text-indigo-600 dark:hover:text-indigo-400'); ?>">
            All Stories
        </a>
        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('blogs.index', array_filter(['category' => $category->slug, 'search' => request('search')]))); ?>"
               class="px-4 py-2 rounded-full text-sm font-semibold transition-all
                      <?php echo e(request('category') == $category->slug
                         ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/30'
                         : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:border-indigo-400 hover:text-indigo-600 dark:hover:text-indigo-400'); ?>">
                <?php echo e($category->name); ?>

            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    
    <?php if(isset($featuredBlogs) && $featuredBlogs->count() > 0): ?>
        <?php $hero = $featuredBlogs->first(); ?>
        <div class="mb-14 group rounded-3xl overflow-hidden
                    bg-white dark:bg-slate-800
                    border border-slate-100 dark:border-slate-700
                    shadow-xl hover:shadow-2xl transition-all duration-500">
            <a href="<?php echo e(route('blogs.show', $hero->slug)); ?>"
               class="grid grid-cols-1 lg:grid-cols-2">

                
                <div class="relative overflow-hidden aspect-video lg:aspect-auto lg:min-h-[420px] bg-slate-200 dark:bg-slate-700">
                    <?php if($hero->featured_image): ?>
                        <img src="<?php echo e(asset('storage/' . $hero->featured_image)); ?>"
                             alt="<?php echo e(clean_title($hero->title)); ?>"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center
                                    bg-gradient-to-br from-indigo-600 to-indigo-800">
                            <svg class="w-20 h-20 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    <?php endif; ?>
                    <span class="absolute top-4 left-4 inline-flex items-center gap-1.5 px-3 py-1.5
                                 bg-yellow-500 text-white text-xs font-bold rounded-full shadow-lg">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        Featured Story
                    </span>
                </div>

                
                <div class="p-8 lg:p-12 flex flex-col justify-center">
                    <?php if($hero->category): ?>
                        <span class="text-xs font-bold text-indigo-600 dark:text-indigo-400
                                     uppercase tracking-widest mb-3">
                            <?php echo e($hero->category->name); ?>

                        </span>
                    <?php endif; ?>

                    <h2 class="text-2xl lg:text-3xl font-extrabold text-slate-900 dark:text-white
                               leading-tight mb-4 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                        <?php echo e(clean_title($hero->title)); ?>

                    </h2>

                    <p class="text-slate-500 dark:text-slate-400 leading-relaxed mb-6 line-clamp-3">
                        <?php echo e(html_entity_decode(strip_tags($hero->excerpt ?: Str::limit(strip_tags($hero->content), 200)), ENT_QUOTES | ENT_HTML5, 'UTF-8')); ?>

                    </p>

                    <div class="flex items-center gap-4 text-xs text-slate-400 mb-8">
                        <?php if($hero->author_name): ?>
                            <span class="flex items-center gap-1.5">
                                <span class="w-6 h-6 rounded-full bg-indigo-100 dark:bg-indigo-900/40
                                             text-indigo-700 dark:text-indigo-300 flex items-center justify-center
                                             text-xs font-bold">
                                    <?php echo e(substr($hero->author_name, 0, 1)); ?>

                                </span>
                                <?php echo e($hero->author_name); ?>

                            </span>
                        <?php endif; ?>
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <?php echo e($hero->published_at?->format('M d, Y')); ?>

                        </span>
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <?php echo e($hero->reading_time ?? 5); ?> min read
                        </span>
                    </div>

                    <span class="inline-flex items-center gap-2 self-start px-5 py-2.5
                                 bg-indigo-600 group-hover:bg-indigo-500
                                 text-white text-sm font-semibold rounded-xl transition-all">
                        Read Story
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </span>
                </div>
            </a>
        </div>
    <?php endif; ?>

    
    <?php if(request('search')): ?>
        <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">
            <?php echo e($blogs->total()); ?> result<?php echo e($blogs->total() !== 1 ? 's' : ''); ?>

            for <span class="font-semibold text-slate-700 dark:text-slate-200">"<?php echo e(request('search')); ?>"</span>
        </p>
    <?php endif; ?>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-7">
        <?php $__empty_1 = true; $__currentLoopData = $blogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $blog): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <article class="group flex flex-col
                            bg-white dark:bg-slate-800
                            rounded-2xl overflow-hidden
                            border border-slate-100 dark:border-slate-700
                            shadow-md hover:shadow-xl
                            hover:border-indigo-200 dark:hover:border-indigo-700
                            transition-all duration-300">

                
                <a href="<?php echo e(route('blogs.show', $blog->slug)); ?>"
                   class="relative block overflow-hidden aspect-video bg-slate-200 dark:bg-slate-700 shrink-0">
                    <?php if($blog->featured_image): ?>
                        <img src="<?php echo e(asset('storage/' . $blog->featured_image)); ?>"
                             alt="<?php echo e(clean_title($blog->title)); ?>"
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center
                                    bg-gradient-to-br from-indigo-500 to-indigo-700">
                            <svg class="w-12 h-12 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    <?php endif; ?>

                    
                    <?php if($blog->category): ?>
                        <span class="absolute top-3 left-3
                                     bg-white/95 dark:bg-slate-900/95 backdrop-blur-sm
                                     text-slate-800 dark:text-white text-xs font-semibold
                                     px-2.5 py-1 rounded-full shadow">
                            <?php echo e($blog->category->name); ?>

                        </span>
                    <?php endif; ?>

                    
                    <?php if($blog->is_featured): ?>
                        <span class="absolute top-3 right-3
                                     bg-yellow-500 text-white text-xs font-bold
                                     px-2.5 py-1 rounded-full flex items-center gap-1 shadow">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            Featured
                        </span>
                    <?php endif; ?>

                    
                    <span class="absolute bottom-3 right-3
                                 bg-black/60 backdrop-blur-sm text-white text-xs
                                 px-2.5 py-1 rounded-full flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <?php echo e($blog->reading_time ?? 5); ?> min
                    </span>
                </a>

                
                <div class="flex flex-col flex-1 p-5">
                    <a href="<?php echo e(route('blogs.show', $blog->slug)); ?>" class="block mb-2">
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white leading-snug
                                   group-hover:text-indigo-600 dark:group-hover:text-indigo-400
                                   transition-colors line-clamp-2">
                            <?php echo e(clean_title($blog->title)); ?>

                        </h3>
                    </a>

                    <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed
                               line-clamp-3 flex-1 mb-4">
                        <?php echo e(html_entity_decode(strip_tags($blog->excerpt ?: Str::limit(strip_tags($blog->content), 130)), ENT_QUOTES | ENT_HTML5, 'UTF-8')); ?>

                    </p>

                    
                    <div class="flex items-center justify-between pt-4
                                border-t border-slate-100 dark:border-slate-700">
                        <div class="flex items-center gap-3 text-xs text-slate-400">
                            <?php if($blog->author_name): ?>
                                <span class="flex items-center gap-1.5">
                                    <span class="w-5 h-5 rounded-full bg-indigo-100 dark:bg-indigo-900/40
                                                 text-indigo-700 dark:text-indigo-300 flex items-center
                                                 justify-center text-xs font-bold">
                                        <?php echo e(substr($blog->author_name, 0, 1)); ?>

                                    </span>
                                    <?php echo e($blog->author_name); ?>

                                </span>
                            <?php endif; ?>
                            <span><?php echo e($blog->published_at?->format('M d, Y') ?: 'Draft'); ?></span>
                        </div>

                        <a href="<?php echo e(route('blogs.show', $blog->slug)); ?>"
                           class="inline-flex items-center gap-1 text-indigo-600 dark:text-indigo-400
                                  hover:text-indigo-700 dark:hover:text-indigo-300
                                  text-xs font-semibold transition-colors group/btn">
                            Read
                            <svg class="w-3.5 h-3.5 group-hover/btn:translate-x-0.5 transition-transform"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </article>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-span-full py-24 text-center">
                <div class="max-w-sm mx-auto">
                    <div class="w-20 h-20 rounded-2xl bg-indigo-50 dark:bg-indigo-900/20
                                flex items-center justify-center mx-auto mb-5">
                        <svg class="w-10 h-10 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H15"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">No stories found</h3>
                    <p class="text-slate-500 dark:text-slate-400 mb-6 text-sm">
                        Try adjusting your search or browse all categories.
                    </p>
                    <a href="<?php echo e(route('blogs.index')); ?>"
                       class="inline-flex items-center gap-2 px-5 py-2.5
                              bg-indigo-600 hover:bg-indigo-500 text-white
                              font-semibold text-sm rounded-xl transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Clear filters
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>

    
    <?php if($blogs->hasPages()): ?>
        <div class="mt-14 flex justify-center">
            <?php echo e($blogs->appends(request()->query())->links()); ?>

        </div>
    <?php endif; ?>

</div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\safaris\resources\views/blogs/index.blade.php ENDPATH**/ ?>