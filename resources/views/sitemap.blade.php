<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">

    {{-- ======================================== 
         HOMEPAGE - HIGHEST PRIORITY
         ======================================== --}}
    <url>
        <loc>{{ url('/') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>

    {{-- ======================================== 
         MAIN CATEGORY PAGES
         ======================================== --}}
    
    {{-- Tours Main --}}
    <url>
        <loc>{{ route('tours.index') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>

    {{-- Budget Safaris --}}
    <url>
        <loc>{{ route('budget-tours.index') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>
    </url>

    {{-- Tours by Budget Category --}}
    <url>
        <loc>{{ route('tours.budget') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>
    </url>

    <url>
        <loc>{{ route('tours.midrange') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>
    </url>

    <url>
        <loc>{{ route('tours.luxury') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>
    </url>

    {{-- Blogs Index --}}
    <url>
        <loc>{{ route('blogs.index') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>

    {{-- Gallery --}}
    <url>
        <loc>{{ route('gallery.index') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>

    {{-- Countries --}}
    <url>
        <loc>{{ route('countries.index') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>

    {{-- Activities --}}
    <url>
        <loc>{{ route('activities.index') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>

    {{-- Destinations --}}
    <url>
        <loc>{{ route('destinations.index') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>

    {{-- ======================================== 
         BOOKING & CONVERSION PAGES
         ======================================== --}}
    
    <url>
        <loc>{{ route('booking.create') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.9</priority>
    </url>

    <url>
        <loc>{{ route('custom-tour-requests.create') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>

    <url>
        <loc>{{ route('contact') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>

    {{-- ======================================== 
         INFORMATIONAL PAGES
         ======================================== --}}
    
    <url>
        <loc>{{ route('about') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>

    {{-- ======================================== 
         DYNAMIC TOURS (from database)
         ======================================== --}}
    
    @foreach($tours as $tour)
    <url>
        <loc>{{ route('tours.show', $tour->slug) }}</loc>
        <lastmod>{{ $tour->updated_at->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
        @if($tour->featured_image)
        <image:image>
            <image:loc>{{ asset('storage/' . $tour->featured_image) }}</image:loc>
            <image:title><![CDATA[{{ $tour->title }}]]></image:title>
            <image:caption><![CDATA[{{ Str::limit($tour->description ?? '', 100) }}]]></image:caption>
        </image:image>
        @endif
    </url>
    @endforeach

    {{-- ======================================== 
         DYNAMIC BLOG POSTS (from database)
         ======================================== --}}
    
    @foreach($blogs as $blog)
    <url>
        <loc>{{ route('blogs.show', $blog->slug) }}</loc>
        <lastmod>{{ $blog->updated_at->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
        @if($blog->featured_image)
        <image:image>
            <image:loc>{{ asset('storage/' . $blog->featured_image) }}</image:loc>
            <image:title><![CDATA[{{ $blog->title }}]]></image:title>
            @if($blog->excerpt)
            <image:caption><![CDATA[{{ Str::limit(strip_tags($blog->excerpt), 100) }}]]></image:caption>
            @endif
        </image:image>
        @endif
    </url>
    @endforeach

    {{-- ======================================== 
         DYNAMIC DESTINATIONS (from database)
         ======================================== --}}
    
    @foreach($destinations as $destination)
    <url>
        <loc>{{ route('destinations.show', $destination->slug) }}</loc>
        <lastmod>{{ $destination->updated_at->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
        @if($destination->featured_image)
        <image:image>
            <image:loc>{{ asset('storage/' . $destination->featured_image) }}</image:loc>
            <image:title><![CDATA[{{ $destination->name }}]]></image:title>
            @if($destination->description)
            <image:caption><![CDATA[{{ Str::limit(strip_tags($destination->description ?? ''), 100) }}]]></image:caption>
            @endif
        </image:image>
        @endif
    </url>
    @endforeach

    {{-- ======================================== 
         DYNAMIC ACTIVITIES (from database)
         ======================================== --}}
    
    @foreach($activities as $activity)
    <url>
        <loc>{{ route('activities.show', $activity->slug) }}</loc>
        <lastmod>{{ $activity->updated_at->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
        @if($activity->featured_image)
        <image:image>
            <image:loc>{{ asset('storage/' . $activity->featured_image) }}</image:loc>
            <image:title><![CDATA[{{ $activity->name }}]]></image:title>
            @if($activity->description)
            <image:caption><![CDATA[{{ Str::limit(strip_tags($activity->description ?? ''), 100) }}]]></image:caption>
            @endif
        </image:image>
        @endif
    </url>
    @endforeach

    {{-- ======================================== 
         DYNAMIC COUNTRIES (from database)
         ======================================== --}}
    
    @foreach($countries as $country)
    <url>
        <loc>{{ route('countries.show', $country->code) }}</loc>
        <lastmod>{{ $country->updated_at->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    @endforeach

    {{-- ======================================== 
         DYNAMIC GALLERY IMAGES (from database)
         ======================================== --}}
    
    @foreach($galleries as $gallery)
    <url>
        <loc>{{ route('gallery.show', $gallery->slug) }}</loc>
        <lastmod>{{ $gallery->updated_at->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
        <image:image>
            <image:loc>{{ $gallery->image_url }}</image:loc>
            <image:title><![CDATA[{{ $gallery->title }}]]></image:title>
            @if($gallery->caption)
            <image:caption><![CDATA[{{ Str::limit($gallery->caption ?? '', 100) }}]]></image:caption>
            @endif
        </image:image>
    </url>
    @endforeach

    {{-- ======================================== 
         LEGAL & POLICY PAGES
         ======================================== --}}
    
    <url>
        <loc>{{ route('privacy-policy') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>yearly</changefreq>
        <priority>0.3</priority>
    </url>

    <url>
        <loc>{{ route('terms-of-service') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>yearly</changefreq>
        <priority>0.3</priority>
    </url>

    <url>
        <loc>{{ route('cookie-policy') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>yearly</changefreq>
        <priority>0.3</priority>
    </url>

    <url>
        <loc>{{ route('refund-policy') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>yearly</changefreq>
        <priority>0.3</priority>
    </url>

</urlset>