{{-- resources/views/blogs/show.blade.php --}}
@extends('layouts.app')

@php
    function clean_title($text) {
        return html_entity_decode(strip_tags($text ?? ''), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
@endphp

@section('title', $blog->meta_title ?: clean_title($blog->title))
@section('description', $blog->meta_description ?: strip_tags($blog->excerpt ?: Str::limit(strip_tags($blog->content), 160)))
@section('keywords', $blog->meta_keywords)

@push('meta')
    <meta property="og:title"       content="{{ $blog->meta_title ?: clean_title($blog->title) }}">
    <meta property="og:description" content="{{ $blog->meta_description ?: strip_tags(Str::limit($blog->excerpt ?: $blog->content, 160)) }}">
    <meta property="og:type"        content="article">
    <meta property="og:url"         content="{{ url()->current() }}">
    @if($blog->featured_image)
        <meta property="og:image"        content="{{ asset('storage/' . $blog->featured_image) }}">
        <meta property="og:image:width"  content="1200">
        <meta property="og:image:height" content="630">
    @endif
    <meta name="twitter:card"        content="summary_large_image">
    <meta name="twitter:title"       content="{{ $blog->meta_title ?: clean_title($blog->title) }}">
    <meta name="twitter:description" content="{{ $blog->meta_description ?: strip_tags(Str::limit($blog->excerpt ?: $blog->content, 160)) }}">
    @if($blog->featured_image)
        <meta name="twitter:image" content="{{ asset('storage/' . $blog->featured_image) }}">
    @endif
    <link rel="canonical" href="{{ url()->current() }}">
@endpush

@section('content')
<article class="min-h-screen bg-white dark:bg-slate-900">

    {{-- ── Hero ─────────────────────────────────────────────────────────── --}}
    <div class="relative overflow-hidden bg-slate-900">

        {{-- Blurred background image --}}
        @if($blog->featured_image)
            <div class="absolute inset-0">
                <img src="{{ asset('storage/' . $blog->featured_image) }}"
                     alt=""
                     aria-hidden="true"
                     class="w-full h-full object-cover  scale-105">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/70 to-slate-900/40"></div>
            </div>
        @else
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-950 to-slate-900"></div>
        @endif

        <div class="relative max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28 text-center">

            {{-- Breadcrumb --}}
            <nav class="flex justify-center items-center gap-2 text-sm text-slate-400 mb-8">
                <a href="{{ route('blogs.index') }}"
                   class="hover:text-white transition-colors">Blog</a>
                @if($blog->category)
                    <span>/</span>
                    <a href="{{ route('blogs.index', ['category' => $blog->category->slug]) }}"
                       class="hover:text-white transition-colors">
                        {{ $blog->category->name }}
                    </a>
                @endif
                <span>/</span>
                <span class="text-slate-500 truncate max-w-[180px]">{{ clean_title($blog->title) }}</span>
            </nav>

            {{-- Category pill --}}
            @if($blog->category)
                <span class="inline-flex items-center px-3 py-1.5 mb-5
                             bg-indigo-500/20 border border-indigo-500/30
                             text-indigo-300 text-xs font-semibold rounded-full uppercase tracking-widest">
                    {{ $blog->category->name }}
                </span>
            @endif

            {{-- Title --}}
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white
                        leading-tight tracking-tight mb-6">
                {{ clean_title($blog->title) }}
            </h1>

            {{-- Meta row --}}
            <div class="flex flex-wrap items-center justify-center gap-5 text-sm text-slate-400">

                @if($blog->author_name)
                    <span class="flex items-center gap-2">
                        <span class="w-8 h-8 rounded-full bg-indigo-600 flex items-center justify-center
                                     text-white text-xs font-bold shrink-0">
                            {{ strtoupper(substr($blog->author_name, 0, 1)) }}
                        </span>
                        {{ $blog->author_name }}
                    </span>
                @endif

                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {{ $blog->published_at?->format('F j, Y') ?? 'Unpublished' }}
                </span>

                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ $blog->reading_time ?? 5 }} min read
                </span>

                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    {{ number_format($blog->views_count) }} views
                </span>

                @if($blog->is_featured)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1
                                 bg-yellow-500 text-white text-xs font-bold rounded-full">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        Featured
                    </span>
                @endif
            </div>
        </div>
    </div>

    {{-- ── Featured image (full-width, below hero) ─────────────────────── --}}
    @if($blog->featured_image)
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 relative z-10 mb-10">
            <img src="{{ asset('storage/' . $blog->featured_image) }}"
                 alt="{{ clean_title($blog->title) }}"
                 class="w-full aspect-video object-cover rounded-2xl shadow-2xl
                        border border-slate-200 dark:border-slate-700">
        </div>
    @endif

    {{-- ── Article body ─────────────────────────────────────────────────── --}}
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">

        {{-- Excerpt / pull quote --}}
        @if($blog->excerpt)
            <div class="flex gap-4 mb-10 p-6
                        bg-indigo-50 dark:bg-indigo-900/20
                        border-l-4 border-indigo-500
                        rounded-r-2xl">
                <svg class="w-6 h-6 text-indigo-400 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
                </svg>
                <p class="text-lg text-slate-700 dark:text-slate-300 font-medium italic leading-relaxed">
                    {{ html_entity_decode(strip_tags($blog->excerpt), ENT_QUOTES | ENT_HTML5, 'UTF-8') }}
                </p>
            </div>
        @endif

        {{-- CONTENT --}}
        <div class="
            text-slate-700 dark:text-slate-300
            text-[1.0625rem] leading-[1.85]

            [&_h1]:text-3xl [&_h1]:font-extrabold [&_h1]:text-slate-900 [&_h1]:dark:text-white
            [&_h1]:mt-10 [&_h1]:mb-4 [&_h1]:leading-tight [&_h1]:tracking-tight
            [&_h1]:border-b [&_h1]:border-slate-200 [&_h1]:dark:border-slate-700 [&_h1]:pb-3

            [&_h2]:text-2xl [&_h2]:font-bold [&_h2]:text-slate-900 [&_h2]:dark:text-white
            [&_h2]:mt-10 [&_h2]:mb-3 [&_h2]:leading-snug

            [&_h3]:text-xl [&_h3]:font-semibold [&_h3]:text-slate-800 [&_h3]:dark:text-slate-100
            [&_h3]:mt-8 [&_h3]:mb-2

            [&_h4]:text-lg [&_h4]:font-semibold [&_h4]:text-slate-700 [&_h4]:dark:text-slate-200
            [&_h4]:mt-6 [&_h4]:mb-2

            [&_p]:mb-5 [&_p]:leading-[1.85]

            [&_ul]:list-disc [&_ul]:pl-7 [&_ul]:mb-5 [&_ul]:space-y-1.5
            [&_ol]:list-decimal [&_ol]:pl-7 [&_ol]:mb-5 [&_ol]:space-y-1.5
            [&_li]:leading-relaxed

            [&_blockquote]:border-l-4 [&_blockquote]:border-indigo-400
            [&_blockquote]:bg-indigo-50 [&_blockquote]:dark:bg-indigo-900/20
            [&_blockquote]:pl-5 [&_blockquote]:pr-4 [&_blockquote]:py-3
            [&_blockquote]:my-6 [&_blockquote]:rounded-r-xl
            [&_blockquote]:italic [&_blockquote]:text-slate-600 [&_blockquote]:dark:text-slate-300

            [&_a]:text-indigo-600 [&_a]:dark:text-indigo-400
            [&_a]:underline [&_a]:underline-offset-2 [&_a]:decoration-indigo-300
            [&_a:hover]:text-indigo-800 [&_a:hover]:dark:text-indigo-300

            [&_strong]:font-bold [&_strong]:text-slate-900 [&_strong]:dark:text-white
            [&_b]:font-bold [&_b]:text-slate-900 [&_b]:dark:text-white
            [&_em]:italic [&_i]:italic

            [&_hr]:my-10 [&_hr]:border-slate-200 [&_hr]:dark:border-slate-700

            [&_img]:max-w-full [&_img]:h-auto [&_img]:rounded-xl [&_img]:my-8
            [&_img]:mx-auto [&_img]:block
            [&_img]:border [&_img]:border-slate-200 [&_img]:dark:border-slate-700
            [&_img]:shadow-lg

            [&_figure]:my-8 [&_figure]:text-center
            [&_figcaption]:text-sm [&_figcaption]:text-slate-400 [&_figcaption]:mt-2 [&_figcaption]:italic

            [&_table]:w-full [&_table]:my-6 [&_table]:text-sm [&_table]:border-collapse
            [&_th]:bg-slate-100 [&_th]:dark:bg-slate-800
            [&_th]:font-semibold [&_th]:text-left [&_th]:px-4 [&_th]:py-2.5
            [&_th]:border [&_th]:border-slate-200 [&_th]:dark:border-slate-700
            [&_td]:px-4 [&_td]:py-2.5
            [&_td]:border [&_td]:border-slate-200 [&_td]:dark:border-slate-700

            [&_code]:font-mono [&_code]:text-sm [&_code]:bg-slate-100 [&_code]:dark:bg-slate-800
            [&_code]:text-rose-600 [&_code]:dark:text-rose-400
            [&_code]:px-1.5 [&_code]:py-0.5 [&_code]:rounded

            [&_pre]:bg-slate-900 [&_pre]:text-slate-200 [&_pre]:rounded-xl
            [&_pre]:p-5 [&_pre]:my-6 [&_pre]:overflow-x-auto [&_pre]:text-sm
            [&_pre_code]:bg-transparent [&_pre_code]:text-inherit [&_pre_code]:p-0

            [&_.image-wrapper]:my-8 [&_.image-wrapper]:block
        ">
            {!! $blog->content !!}
        </div>

        {{-- ── Tags ──────────────────────────────────────────────────── --}}
        @if($blog->tags)
            <div class="mt-12 pt-8 border-t border-slate-100 dark:border-slate-800">
                <h3 class="text-sm font-bold text-slate-500 dark:text-slate-400
                           uppercase tracking-widest mb-4">
                    Related Topics
                </h3>
                <div class="flex flex-wrap gap-2">
                    @foreach(explode(',', $blog->tags) as $tag)
                        @if(trim($tag))
                            <a href="{{ route('blogs.index', ['tag' => trim($tag)]) }}"
                               class="px-4 py-1.5 bg-slate-100 dark:bg-slate-800
                                      hover:bg-indigo-50 dark:hover:bg-indigo-900/30
                                      text-slate-600 dark:text-slate-300
                                      hover:text-indigo-600 dark:hover:text-indigo-400
                                      text-sm font-medium rounded-full
                                      border border-slate-200 dark:border-slate-700
                                      hover:border-indigo-300 dark:hover:border-indigo-600
                                      transition-all">
                                #{{ trim($tag) }}
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif

        {{-- ── Author card ──────────────────────────────────────────── --}}
        @if($blog->author_name)
            <div class="mt-12 p-6 sm:p-8
                        bg-gradient-to-br from-slate-50 to-white
                        dark:from-slate-800/60 dark:to-slate-800/40
                        rounded-2xl border border-slate-200 dark:border-slate-700
                        shadow-md">
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-500 to-indigo-700
                                flex items-center justify-center text-white text-2xl font-extrabold
                                shadow-lg shrink-0">
                        {{ strtoupper(substr($blog->author_name, 0, 1)) }}
                    </div>
                    <div class="flex-1">
                        <p class="text-xs font-bold text-indigo-600 dark:text-indigo-400
                                  uppercase tracking-widest mb-1">Author</p>
                        <h4 class="text-lg font-bold text-slate-900 dark:text-white mb-1">
                            {{ $blog->author_name }}
                        </h4>
                        <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed">
                            Safari expert and wildlife enthusiast with years of experience leading
                            expeditions across East Africa. Passionate about conservation and
                            sharing the magic of the wild.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        {{-- ── Share ────────────────────────────────────────────────── --}}
        <div class="mt-10 p-5 sm:p-6
                    bg-white dark:bg-slate-800
                    rounded-2xl border border-slate-200 dark:border-slate-700
                    shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <p class="text-sm font-bold text-slate-700 dark:text-slate-200">
                    Share this story
                </p>
                <div class="flex items-center gap-2">
                    {{-- Facebook --}}
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                       target="_blank" rel="noopener"
                       class="w-10 h-10 rounded-xl flex items-center justify-center
                              bg-slate-100 dark:bg-slate-700
                              hover:bg-blue-600 hover:text-white
                              text-slate-500 dark:text-slate-400
                              transition-all hover:scale-110"
                       title="Share on Facebook">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.879V14.89h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.989C18.343 21.129 22 16.99 22 12z"/>
                        </svg>
                    </a>
                    {{-- Twitter / X --}}
                    <a href="https://twitter.com/intent/tweet?text={{ urlencode(clean_title($blog->title)) }}&url={{ urlencode(url()->current()) }}"
                       target="_blank" rel="noopener"
                       class="w-10 h-10 rounded-xl flex items-center justify-center
                              bg-slate-100 dark:bg-slate-700
                              hover:bg-sky-500 hover:text-white
                              text-slate-500 dark:text-slate-400
                              transition-all hover:scale-110"
                       title="Share on X / Twitter">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"/>
                        </svg>
                    </a>
                    {{-- LinkedIn --}}
                    <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(url()->current()) }}&title={{ urlencode(clean_title($blog->title)) }}"
                       target="_blank" rel="noopener"
                       class="w-10 h-10 rounded-xl flex items-center justify-center
                              bg-slate-100 dark:bg-slate-700
                              hover:bg-blue-700 hover:text-white
                              text-slate-500 dark:text-slate-400
                              transition-all hover:scale-110"
                       title="Share on LinkedIn">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                        </svg>
                    </a>
                    {{-- WhatsApp --}}
                    <a href="https://wa.me/?text={{ urlencode(clean_title($blog->title) . ' ' . url()->current()) }}"
                       target="_blank" rel="noopener"
                       class="w-10 h-10 rounded-xl flex items-center justify-center
                              bg-slate-100 dark:bg-slate-700
                              hover:bg-green-500 hover:text-white
                              text-slate-500 dark:text-slate-400
                              transition-all hover:scale-110"
                       title="Share on WhatsApp">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                    </a>
                    {{-- Copy link --}}
                    <button id="copy-link-btn"
                            onclick="
                                navigator.clipboard.writeText('{{ url()->current() }}').then(() => {
                                    const btn = document.getElementById('copy-link-btn');
                                    btn.classList.add('bg-emerald-500','text-white');
                                    btn.title = 'Copied!';
                                    setTimeout(() => {
                                        btn.classList.remove('bg-emerald-500','text-white');
                                        btn.title = 'Copy link';
                                    }, 2000);
                                });
                            "
                            class="w-10 h-10 rounded-xl flex items-center justify-center
                                   bg-slate-100 dark:bg-slate-700
                                   hover:bg-emerald-500 hover:text-white
                                   text-slate-500 dark:text-slate-400
                                   transition-all hover:scale-110"
                            title="Copy link">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- ── Related posts ─────────────────────────────────────────── --}}
        @if(isset($relatedBlogs) && $relatedBlogs->count() > 0)
            <div class="mt-16">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white">
                        You Might Also Like
                    </h3>
                    <a href="{{ route('blogs.index') }}"
                       class="text-sm font-semibold text-indigo-600 dark:text-indigo-400
                              hover:text-indigo-700 dark:hover:text-indigo-300 transition-colors
                              flex items-center gap-1 group">
                        View all
                        <svg class="w-4 h-4 group-hover:translate-x-0.5 transition-transform"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    @foreach($relatedBlogs as $related)
                        <a href="{{ route('blogs.show', $related->slug) }}"
                           class="group flex flex-col rounded-2xl overflow-hidden
                                  bg-white dark:bg-slate-800
                                  border border-slate-100 dark:border-slate-700
                                  shadow-sm hover:shadow-lg
                                  hover:border-indigo-200 dark:hover:border-indigo-700
                                  transition-all duration-300">

                            <div class="relative overflow-hidden aspect-video bg-slate-200 dark:bg-slate-700 shrink-0">
                                @if($related->featured_image)
                                    <img src="{{ asset('storage/' . $related->featured_image) }}"
                                         alt="{{ clean_title($related->title) }}"
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-indigo-500 to-indigo-700
                                                flex items-center justify-center">
                                        <svg class="w-10 h-10 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                                @if($related->is_featured)
                                    <span class="absolute top-2 left-2 bg-yellow-500 text-white
                                                 text-xs font-bold px-2 py-0.5 rounded-full">
                                        Featured
                                    </span>
                                @endif
                            </div>

                            <div class="p-4 flex flex-col flex-1">
                                <h4 class="font-bold text-sm text-slate-900 dark:text-white
                                           leading-snug line-clamp-2 mb-2
                                           group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                                    {{ clean_title($related->title) }}
                                </h4>
                                <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2 mb-3 flex-1 leading-relaxed">
                                    {{ html_entity_decode(strip_tags($related->excerpt ?: Str::limit(strip_tags($related->content), 90)), ENT_QUOTES | ENT_HTML5, 'UTF-8') }}
                                </p>
                                <div class="flex items-center justify-between text-xs text-slate-400 pt-3
                                            border-t border-slate-100 dark:border-slate-700">
                                    <span>{{ $related->published_at?->format('M d, Y') }}</span>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $related->reading_time ?? 5 }} min
                                    </span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

    </div>{{-- /max-w-3xl --}}
</article>
@endsection