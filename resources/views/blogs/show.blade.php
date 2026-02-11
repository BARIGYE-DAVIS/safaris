{{-- resources/views/blogs/show.blade.php --}}
@extends('layouts.app')

@section('title', $blog->meta_title ?: $blog->title)
@section('description', $blog->meta_description ?: strip_tags($blog->excerpt ?: Str::limit($blog->content, 160)))
@section('keywords', $blog->meta_keywords)

@push('meta')
    <meta property="og:title" content="{{ $blog->meta_title ?: $blog->title }}">
    <meta property="og:description" content="{{ $blog->meta_description ?: strip_tags($blog->excerpt ?: Str::limit($blog->content, 160)) }}">
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ url()->current() }}">
    @if($blog->featured_image)
        <meta property="og:image" content="{{ asset('storage/' . $blog->featured_image) }}">
        <meta property="og:image:width" content="1200">
        <meta property="og:image:height" content="630">
    @endif
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $blog->meta_title ?: $blog->title }}">
    <meta name="twitter:description" content="{{ $blog->meta_description ?: strip_tags($blog->excerpt ?: Str::limit($blog->content, 160)) }}">
    @if($blog->featured_image)
        <meta name="twitter:image" content="{{ asset('storage/' . $blog->featured_image) }}">
    @endif
    <link rel="canonical" href="{{ url()->current() }}">
@endpush

@push('styles')
<style>
    /* Blog Content Styling - Properly renders all HTML tags from your editor */
    .blog-content {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        font-size: 1.125rem;
        line-height: 1.8;
        color: #1e293b;
    }
    
    .dark .blog-content {
        color: #e2e8f0;
    }
    
    /* Headings */
    .blog-content h1 {
        font-size: 2.5rem;
        font-weight: 800;
        margin-top: 2.5rem;
        margin-bottom: 1.25rem;
        color: #0f172a;
        letter-spacing: -0.02em;
        line-height: 1.2;
    }
    
    .dark .blog-content h1 {
        color: #f8fafc;
    }
    
    .blog-content h2 {
        font-size: 2rem;
        font-weight: 700;
        margin-top: 2.5rem;
        margin-bottom: 1rem;
        color: #0f172a;
        letter-spacing: -0.01em;
        line-height: 1.3;
        border-bottom: 2px solid #e2e8f0;
        padding-bottom: 0.75rem;
    }
    
    .dark .blog-content h2 {
        color: #f1f5f9;
        border-bottom-color: #334155;
    }
    
    .blog-content h3 {
        font-size: 1.5rem;
        font-weight: 600;
        margin-top: 2rem;
        margin-bottom: 0.75rem;
        color: #1e293b;
        line-height: 1.4;
    }
    
    .dark .blog-content h3 {
        color: #e2e8f0;
    }
    
    .blog-content h4 {
        font-size: 1.25rem;
        font-weight: 600;
        margin-top: 1.5rem;
        margin-bottom: 0.5rem;
        color: #334155;
    }
    
    .dark .blog-content h4 {
        color: #cbd5e1;
    }
    
    /* Paragraphs */
    .blog-content p {
        margin-bottom: 1.5rem;
        line-height: 1.8;
    }
    
    /* Lists */
    .blog-content ul {
        list-style-type: disc;
        margin-top: 1rem;
        margin-bottom: 1.5rem;
        padding-left: 1.75rem;
    }
    
    .blog-content ol {
        list-style-type: decimal;
        margin-top: 1rem;
        margin-bottom: 1.5rem;
        padding-left: 1.75rem;
    }
    
    .blog-content li {
        margin-bottom: 0.5rem;
        line-height: 1.7;
    }
    
    .blog-content li > ul,
    .blog-content li > ol {
        margin-top: 0.5rem;
        margin-bottom: 0.5rem;
    }
    
    /* Images - Proper sizing and styling */
    .blog-content img {
        max-width: 100%;
        height: auto;
        border-radius: 0.75rem;
        margin: 2rem auto;
        display: block;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        border: 1px solid #e2e8f0;
    }
    
    .dark .blog-content img {
        border-color: #334155;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3);
    }
    
    /* Figure and Caption */
    .blog-content figure {
        margin: 2.5rem 0;
        text-align: center;
    }
    
    .blog-content figcaption {
        font-size: 0.875rem;
        color: #64748b;
        margin-top: 0.75rem;
        font-style: italic;
        text-align: center;
    }
    
    .dark .blog-content figcaption {
        color: #94a3b8;
    }
    
    /* Blockquotes */
    .blog-content blockquote {
        border-left: 4px solid #6366f1;
        padding: 1rem 0 1rem 1.5rem;
        margin: 2rem 0;
        font-style: italic;
        color: #475569;
        background: #f8fafc;
        border-radius: 0 0.5rem 0.5rem 0;
    }
    
    .dark .blog-content blockquote {
        background: #1e293b;
        color: #cbd5e1;
        border-left-color: #818cf8;
    }
    
    .blog-content blockquote p {
        margin-bottom: 0.5rem;
    }
    
    .blog-content blockquote p:last-child {
        margin-bottom: 0;
    }
    
    /* Tables */
    .blog-content table {
        width: 100%;
        border-collapse: collapse;
        margin: 2rem 0;
        font-size: 0.9375rem;
    }
    
    .blog-content th {
        background: #f1f5f9;
        font-weight: 600;
        padding: 0.75rem 1rem;
        text-align: left;
        border: 1px solid #e2e8f0;
    }
    
    .dark .blog-content th {
        background: #1e293b;
        border-color: #334155;
        color: #f1f5f9;
    }
    
    .blog-content td {
        padding: 0.75rem 1rem;
        border: 1px solid #e2e8f0;
    }
    
    .dark .blog-content td {
        border-color: #334155;
    }
    
    /* Code */
    .blog-content code {
        font-family: 'Fira Code', 'Courier New', monospace;
        font-size: 0.875rem;
        background: #f1f5f9;
        color: #dc2626;
        padding: 0.2rem 0.4rem;
        border-radius: 0.25rem;
    }
    
    .dark .blog-content code {
        background: #1e293b;
        color: #f87171;
    }
    
    .blog-content pre {
        background: #0f172a;
        color: #e2e8f0;
        padding: 1.25rem;
        border-radius: 0.5rem;
        overflow-x: auto;
        margin: 1.5rem 0;
        font-size: 0.875rem;
        line-height: 1.7;
    }
    
    .blog-content pre code {
        background: transparent;
        color: inherit;
        padding: 0;
        font-size: inherit;
    }
    
    /* Links */
    .blog-content a {
        color: #4f46e5;
        text-decoration: underline;
        text-decoration-thickness: 1px;
        text-underline-offset: 2px;
        font-weight: 500;
    }
    
    .dark .blog-content a {
        color: #818cf8;
    }
    
    .blog-content a:hover {
        color: #4338ca;
        text-decoration-thickness: 2px;
    }
    
    .dark .blog-content a:hover {
        color: #a5b4fc;
    }
    
    /* Horizontal Rule */
    .blog-content hr {
        margin: 3rem 0;
        border: 0;
        border-top: 1px solid #e2e8f0;
    }
    
    .dark .blog-content hr {
        border-top-color: #334155;
    }
    
    /* Custom classes for your editor */
    .blog-content .tag-wrapper {
        all: revert;
    }
    
    .blog-content .image-figure img {
        max-width: 100%;
        height: auto;
    }
    
    /* First paragraph after hero */
    .blog-content > p:first-of-type {
        font-size: 1.25rem;
        color: #334155;
    }
    
    .dark .blog-content > p:first-of-type {
        color: #cbd5e1;
    }
</style>
@endpush

@section('content')
<article class="bg-white dark:bg-slate-900 min-h-screen">
    
    {{-- Hero Section with Featured Image --}}
    <div class="relative bg-gradient-to-r from-indigo-900 via-indigo-800 to-indigo-900 dark:from-slate-950 dark:via-slate-900 dark:to-slate-950 py-20 lg:py-28 overflow-hidden">
        @if($blog->featured_image)
            <div class="absolute inset-0">
                <img src="{{ asset('storage/' . $blog->featured_image) }}" 
                     alt="{{ $blog->title }}" 
                     class="w-full h-full object-cover opacity-20 scale-105 transform transition-transform duration-[20s] hover:scale-110"
                     style="filter: blur(2px);">
                <div class="absolute inset-0 bg-gradient-to-t from-indigo-900/90 via-indigo-800/80 to-transparent dark:from-slate-950/95 dark:via-slate-900/90"></div>
            </div>
        @else
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-600 to-indigo-800 dark:from-slate-800 dark:to-slate-900"></div>
        @endif
        
        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            {{-- Breadcrumbs --}}
            <nav class="flex justify-center mb-6 text-sm" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2 text-indigo-200 dark:text-indigo-300">
                    <li>
                        <a href="{{ route('blogs.index') }}" class="hover:text-white transition-colors flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            Home
                        </a>
                    </li>
                    <li>
                        <span class="mx-2">/</span>
                    </li>
                    <li>
                        <a href="{{ route('blogs.index') }}" class="hover:text-white transition-colors">Blog</a>
                    </li>
                    @if($blog->category)
                    <li>
                        <span class="mx-2">/</span>
                    </li>
                    <li>
                        <a href="{{ route('blogs.index', ['category' => $blog->category->slug]) }}" class="hover:text-white transition-colors">
                            {{ $blog->category->name }}
                        </a>
                    </li>
                    @endif
                </ol>
            </nav>
            
            {{-- Category Badge --}}
            @if($blog->category)
            <div class="mb-4">
                <span class="inline-flex items-center px-4 py-1.5 bg-white/10 backdrop-blur-sm text-white text-sm font-medium rounded-full border border-white/20">
                    {{ $blog->category->name }}
                </span>
            </div>
            @endif
            
            {{-- Title --}}
            <h1 class="text-3xl md:text-4xl lg:text-5xl xl:text-6xl font-bold text-white mb-6 leading-tight tracking-tight">
                {{ $blog->title }}
            </h1>
            
            {{-- Meta Info --}}
            <div class="flex flex-wrap items-center justify-center gap-4 md:gap-6 text-indigo-100 dark:text-indigo-200 text-sm md:text-base">
                @if($blog->author_name)
                <div class="flex items-center">
                    <div class="w-8 h-8 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center text-white font-semibold mr-2">
                        {{ substr($blog->author_name, 0, 1) }}
                    </div>
                    <span>{{ $blog->author_name }}</span>
                </div>
                @endif
                
                <div class="flex items-center">
                    <svg class="w-4 h-4 md:w-5 md:h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span>{{ $blog->published_at ? $blog->published_at->format('F j, Y') : 'Not published' }}</span>
                </div>
                
                <div class="flex items-center">
                    <svg class="w-4 h-4 md:w-5 md:h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ $blog->reading_time ?? 5 }} min read</span>
                </div>
                
                <div class="flex items-center">
                    <svg class="w-4 h-4 md:w-5 md:h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    <span>{{ number_format($blog->views_count) }} views</span>
                </div>
            </div>
            
            {{-- Featured Badge --}}
            @if($blog->is_featured)
            <div class="mt-6">
                <span class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white text-sm font-bold rounded-full shadow-lg">
                    <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                    </svg>
                    FEATURED STORY
                </span>
            </div>
            @endif
        </div>
    </div>

    {{-- Main Content Container --}}
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16 lg:py-20">
        
        {{-- Excerpt --}}
        @if($blog->excerpt)
        <div class="relative mb-12">
            <div class="absolute -left-3 top-0 bottom-0 w-1 bg-gradient-to-b from-indigo-500 to-indigo-600 rounded-full"></div>
            <div class="pl-6">
                <p class="text-xl md:text-2xl text-slate-700 dark:text-slate-300 font-medium italic leading-relaxed">
                    "{{ $blog->excerpt }}"
                </p>
            </div>
        </div>
        @endif
        
        {{-- CONTENT - THIS RENDERS ALL YOUR HTML TAGS PROPERLY --}}
        <div class="blog-content">
            {!! $blog->content !!}
        </div>
        
        {{-- Tags Section --}}
        @if($blog->tags)
        <div class="mt-16 pt-8 border-t-2 border-slate-100 dark:border-slate-800">
            <div class="flex items-center mb-4">
                <svg class="w-5 h-5 text-indigo-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                </svg>
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Related Topics</h3>
            </div>
            <div class="flex flex-wrap gap-2">
                @foreach(explode(',', $blog->tags) as $tag)
                    @if(trim($tag))
                        <a href="{{ route('blogs.index', ['tag' => trim($tag)]) }}" 
                           class="group px-4 py-2 bg-slate-100 dark:bg-slate-800 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 text-slate-700 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400 rounded-full text-sm font-medium transition-all duration-200 hover:shadow-md">
                            <span class="group-hover:scale-105 inline-block transition-transform">#</span>{{ trim($tag) }}
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
        @endif
        
        {{-- Author Bio --}}
        @if($blog->author_name)
        <div class="mt-16 bg-gradient-to-br from-slate-50 to-white dark:from-slate-800/50 dark:to-slate-900/50 rounded-2xl p-8 border border-slate-200 dark:border-slate-700 shadow-lg">
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6">
                <div class="flex-shrink-0">
                    <div class="w-20 h-20 rounded-full bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center text-white text-3xl font-bold shadow-lg">
                        {{ substr($blog->author_name, 0, 1) }}
                    </div>
                </div>
                <div class="flex-1">
                    <h4 class="text-xl font-bold text-slate-900 dark:text-white mb-2">
                        {{ $blog->author_name }}
                    </h4>
                    <p class="text-slate-600 dark:text-slate-400 mb-4 leading-relaxed">
                        Safari expert and wildlife enthusiast with years of experience leading expeditions across East Africa. Passionate about conservation and sharing the magic of the wild.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.879V14.89h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.989C18.343 21.129 22 16.99 22 12z"/>
                            </svg>
                        </a>
                        <a href="#" class="text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endif
        
        {{-- Share Section --}}
        <div class="mt-16 bg-white dark:bg-slate-800 rounded-xl p-6 border border-slate-200 dark:border-slate-700 shadow-lg">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>
                        </svg>
                    </div>
                    <h4 class="text-lg font-semibold text-slate-900 dark:text-white">
                        Share this story
                    </h4>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" 
                       target="_blank"
                       class="w-12 h-12 bg-indigo-50 dark:bg-indigo-900/30 hover:bg-indigo-600 text-indigo-600 dark:text-indigo-400 hover:text-white rounded-xl flex items-center justify-center transition-all duration-200 hover:scale-110 hover:shadow-lg group">
                        <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.879V14.89h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.989C18.343 21.129 22 16.99 22 12z"/>
                        </svg>
                    </a>
                    <a href="https://twitter.com/intent/tweet?text={{ urlencode($blog->title) }}&url={{ urlencode(url()->current()) }}" 
                       target="_blank"
                       class="w-12 h-12 bg-indigo-50 dark:bg-indigo-900/30 hover:bg-indigo-600 text-indigo-600 dark:text-indigo-400 hover:text-white rounded-xl flex items-center justify-center transition-all duration-200 hover:scale-110 hover:shadow-lg group">
                        <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"/>
                        </svg>
                    </a>
                    <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(url()->current()) }}&title={{ urlencode($blog->title) }}" 
                       target="_blank"
                       class="w-12 h-12 bg-indigo-50 dark:bg-indigo-900/30 hover:bg-indigo-600 text-indigo-600 dark:text-indigo-400 hover:text-white rounded-xl flex items-center justify-center transition-all duration-200 hover:scale-110 hover:shadow-lg group">
                        <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                        </svg>
                    </a>
                    <button onclick="navigator.clipboard.writeText('{{ url()->current() }}').then(() => { this.classList.add('bg-green-500', 'text-white'); setTimeout(() => this.classList.remove('bg-green-500', 'text-white'), 2000); })"
                            class="w-12 h-12 bg-slate-100 dark:bg-slate-700 hover:bg-indigo-600 text-slate-600 dark:text-slate-400 hover:text-white rounded-xl flex items-center justify-center transition-all duration-200 hover:scale-110 hover:shadow-lg group">
                        <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- Related Posts --}}
        @if(isset($relatedBlogs) && $relatedBlogs->count() > 0)
        <div class="mt-16">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-2xl md:text-3xl font-bold text-slate-900 dark:text-white flex items-center">
                    <svg class="w-8 h-8 mr-3 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H15"></path>
                    </svg>
                    You Might Also Like
                </h3>
                <a href="{{ route('blogs.index') }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 font-medium flex items-center group">
                    View all
                    <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8">
                @foreach($relatedBlogs as $related)
                    <div class="group bg-white dark:bg-slate-800 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden">
                        <a href="{{ route('blogs.show', $related->slug) }}" class="block">
                            @if($related->featured_image)
                                <div class="relative overflow-hidden aspect-video">
                                    <img src="{{ asset('storage/' . $related->featured_image) }}" 
                                         alt="{{ $related->title }}" 
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                    @if($related->is_featured)
                                        <span class="absolute top-2 left-2 bg-yellow-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                                            Featured
                                        </span>
                                    @endif
                                </div>
                            @endif
                            <div class="p-5">
                                <h4 class="font-bold text-slate-900 dark:text-white mb-2 line-clamp-2 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition">
                                    {{ $related->title }}
                                </h4>
                                <p class="text-sm text-slate-600 dark:text-slate-400 mb-3 line-clamp-2">
                                    {{ $related->excerpt ?: Str::limit(strip_tags($related->content), 80) }}
                                </p>
                                <div class="flex items-center justify-between text-xs text-slate-500">
                                    <span>{{ $related->published_at?->format('M d, Y') }}</span>
                                    <span class="flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $related->reading_time ?? 5 }} min
                                    </span>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</article>
@endsection