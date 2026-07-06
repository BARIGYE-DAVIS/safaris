<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">

    
    <url>
        <loc><?php echo e(url('/')); ?></loc>
        <lastmod><?php echo e(now()->toAtomString()); ?></lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>

    
    
    <url>
        <loc><?php echo e(route('tours.index')); ?></loc>
        <lastmod><?php echo e(now()->toAtomString()); ?></lastmod>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>

    <url>
        <loc><?php echo e(route('budget-tours.index')); ?></loc>
        <lastmod><?php echo e(now()->toAtomString()); ?></lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>
    </url>

    <url>
        <loc><?php echo e(route('tours.budget')); ?></loc>
        <lastmod><?php echo e(now()->toAtomString()); ?></lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>
    </url>

    <url>
        <loc><?php echo e(route('tours.midrange')); ?></loc>
        <lastmod><?php echo e(now()->toAtomString()); ?></lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>
    </url>

    <url>
        <loc><?php echo e(route('tours.luxury')); ?></loc>
        <lastmod><?php echo e(now()->toAtomString()); ?></lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>
    </url>

    <url>
        <loc><?php echo e(route('blogs.index')); ?></loc>
        <lastmod><?php echo e(now()->toAtomString()); ?></lastmod>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>

    <url>
        <loc><?php echo e(route('gallery.index')); ?></loc>
        <lastmod><?php echo e(now()->toAtomString()); ?></lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>

    <url>
        <loc><?php echo e(route('countries.index')); ?></loc>
        <lastmod><?php echo e(now()->toAtomString()); ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>

    <url>
        <loc><?php echo e(route('activities.index')); ?></loc>
        <lastmod><?php echo e(now()->toAtomString()); ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>

    <url>
        <loc><?php echo e(route('destinations.index')); ?></loc>
        <lastmod><?php echo e(now()->toAtomString()); ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>

    
    
    <url>
        <loc><?php echo e(route('booking.create')); ?></loc>
        <lastmod><?php echo e(now()->toAtomString()); ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.9</priority>
    </url>

    <url>
        <loc><?php echo e(route('custom-tour-requests.create')); ?></loc>
        <lastmod><?php echo e(now()->toAtomString()); ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>

    <url>
        <loc><?php echo e(route('contact')); ?></loc>
        <lastmod><?php echo e(now()->toAtomString()); ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>

    
    
    <url>
        <loc><?php echo e(route('about')); ?></loc>
        <lastmod><?php echo e(now()->toAtomString()); ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>

    
    
    <?php $__currentLoopData = $tours; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tour): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <url>
        <loc><?php echo e(route('tours.show', $tour->slug)); ?></loc>
        <lastmod><?php echo e($tour->updated_at->toAtomString()); ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
        <?php if($tour->featured_image): ?>
        <image:image>
            <image:loc><?php echo e(asset('storage/' . $tour->featured_image)); ?></image:loc>
            <image:title><![CDATA[<?php echo e($tour->title); ?>]]></image:title>
            <image:caption><![CDATA[<?php echo e(Str::limit($tour->description ?? '', 100)); ?>]]></image:caption>
        </image:image>
        <?php endif; ?>
    </url>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    
    
    <?php $__currentLoopData = $blogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $blog): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <url>
        <loc><?php echo e(route('blogs.show', $blog->slug)); ?></loc>
        <lastmod><?php echo e($blog->updated_at->toAtomString()); ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
        <?php if($blog->featured_image): ?>
        <image:image>
            <image:loc><?php echo e(asset('storage/' . $blog->featured_image)); ?></image:loc>
            <image:title><![CDATA[<?php echo e($blog->title); ?>]]></image:title>
            <?php if($blog->excerpt): ?>
            <image:caption><![CDATA[<?php echo e(Str::limit(strip_tags($blog->excerpt), 100)); ?>]]></image:caption>
            <?php endif; ?>
        </image:image>
        <?php endif; ?>
    </url>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    
    
    <?php $__currentLoopData = $destinations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $destination): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <url>
        <loc><?php echo e(route('destinations.show', $destination->slug)); ?></loc>
        <lastmod><?php echo e($destination->updated_at->toAtomString()); ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
        <?php if($destination->featured_image): ?>
        <image:image>
            <image:loc><?php echo e(asset('storage/' . $destination->featured_image)); ?></image:loc>
            <image:title><![CDATA[<?php echo e($destination->name); ?>]]></image:title>
            <?php if($destination->description): ?>
            <image:caption><![CDATA[<?php echo e(Str::limit(strip_tags($destination->description ?? ''), 100)); ?>]]></image:caption>
            <?php endif; ?>
        </image:image>
        <?php endif; ?>
    </url>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    
    
    <?php $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <url>
        <loc><?php echo e(route('activities.show', $activity->slug)); ?></loc>
        <lastmod><?php echo e($activity->updated_at->toAtomString()); ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
        <?php if($activity->featured_image): ?>
        <image:image>
            <image:loc><?php echo e(asset('storage/' . $activity->featured_image)); ?></image:loc>
            <image:title><![CDATA[<?php echo e($activity->name); ?>]]></image:title>
            <?php if($activity->description): ?>
            <image:caption><![CDATA[<?php echo e(Str::limit(strip_tags($activity->description ?? ''), 100)); ?>]]></image:caption>
            <?php endif; ?>
        </image:image>
        <?php endif; ?>
    </url>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    
    
    <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <url>
        <loc><?php echo e(route('countries.show', $country->code)); ?></loc>
        <lastmod><?php echo e($country->updated_at->toAtomString()); ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    
    
    <?php $__currentLoopData = $galleries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gallery): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <url>
        <loc><?php echo e(route('gallery.show', $gallery->slug)); ?></loc>
        <lastmod><?php echo e($gallery->updated_at->toAtomString()); ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
        <image:image>
            <image:loc><?php echo e($gallery->image_url); ?></image:loc>
            <image:title><![CDATA[<?php echo e($gallery->title); ?>]]></image:title>
            <?php if($gallery->caption): ?>
            <image:caption><![CDATA[<?php echo e(Str::limit($gallery->caption ?? '', 100)); ?>]]></image:caption>
            <?php endif; ?>
        </image:image>
    </url>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    
    
    <?php $__currentLoopData = $seoPages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <url>
        <loc><?php echo e(route('seo-pages.show', $page->slug)); ?></loc>
        <lastmod><?php echo e($page->updated_at->toAtomString()); ?></lastmod>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
        <?php if($page->featured_image): ?>
        <image:image>
            <image:loc><?php echo e(asset('storage/' . $page->featured_image)); ?></image:loc>
            <image:title><![CDATA[<?php echo e($page->title); ?>]]></image:title>
            <?php if($page->meta_description): ?>
            <image:caption><![CDATA[<?php echo e(Str::limit(strip_tags($page->meta_description ?? ''), 100)); ?>]]></image:caption>
            <?php endif; ?>
        </image:image>
        <?php endif; ?>
    </url>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    
    
    <url>
        <loc><?php echo e(route('privacy-policy')); ?></loc>
        <lastmod><?php echo e(now()->toAtomString()); ?></lastmod>
        <changefreq>yearly</changefreq>
        <priority>0.3</priority>
    </url>

    <url>
        <loc><?php echo e(route('terms-of-service')); ?></loc>
        <lastmod><?php echo e(now()->toAtomString()); ?></lastmod>
        <changefreq>yearly</changefreq>
        <priority>0.3</priority>
    </url>

    <url>
        <loc><?php echo e(route('cookie-policy')); ?></loc>
        <lastmod><?php echo e(now()->toAtomString()); ?></lastmod>
        <changefreq>yearly</changefreq>
        <priority>0.3</priority>
    </url>

    <url>
        <loc><?php echo e(route('refund-policy')); ?></loc>
        <lastmod><?php echo e(now()->toAtomString()); ?></lastmod>
        <changefreq>yearly</changefreq>
        <priority>0.3</priority>
    </url>

</urlset><?php /**PATH C:\xampp\htdocs\safaris\resources\views/sitemap.blade.php ENDPATH**/ ?>