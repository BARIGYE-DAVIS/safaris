{{-- resources/views/blogs/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Safari Stories & Travel Guides')
@section('description', 'Discover expert tips, wildlife encounters, and unforgettable adventures from the heart of Africa.')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-slate-50 to-white dark:from-slate-950 dark:to-slate-900 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Hero Header --}}
        <div class="text-center mb-12">
            <div class="inline-flex items-center px-4 py-2 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400 rounded-full text-sm font-medium mb-4">
                <span class="w-2 h-2 bg-indigo-500 rounded-full mr-2 animate-pulse"></span>
                Discover the Wild
            </div>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-slate-900 dark:text-white mb-6 leading-tight">
                Safari Stories & <br>Travel Guides
            </h1>
            <p class="text-xl text-slate-600 dark:text-slate-300 max-w-3xl mx-auto">
                Expert tips, wildlife encounters, and unforgettable adventures from the heart of Africa.
            </p>
        </div>

        {{-- Categories Filter --}}
        <div class="mb-12 flex flex-wrap items-center justify-center gap-3">
            <a href="{{ route('blogs.index') }}" 
               class="px-5 py-2.5 rounded-full text-sm font-medium transition-all duration-200 {{ !request('category') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30 scale-105' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 border border-slate-200 dark:border-slate-700' }}">
                All Stories
            </a>
            @foreach($categories as $category)
                <a href="{{ route('blogs.index', ['category' => $category->slug]) }}" 
                   class="px-5 py-2.5 rounded-full text-sm font-medium transition-all duration-200 {{ request('category') == $category->slug ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30 scale-105' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 border border-slate-200 dark:border-slate-700' }}">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>

        {{-- Blog Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($blogs as $blog)
                <div class="group bg-white dark:bg-slate-800 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden flex flex-col h-full border border-slate-100 dark:border-slate-700 hover:border-indigo-200 dark:hover:border-indigo-700">
                    
                    {{-- Image Container --}}
                    <a href="{{ route('blogs.show', $blog->slug) }}" class="relative overflow-hidden aspect-video bg-slate-200 dark:bg-slate-700">
                        @if($blog->featured_image)
                            <img src="{{ asset('storage/' . $blog->featured_image) }}" 
                                 alt="{{ $blog->title }}" 
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-indigo-500 via-indigo-600 to-indigo-700">
                                <svg class="w-16 h-16 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif
                        
                        {{-- Category Badge --}}
                        @if($blog->category)
                            <span class="absolute top-3 left-3 bg-white/95 dark:bg-slate-900/95 backdrop-blur-sm text-slate-800 dark:text-white text-xs font-medium px-3 py-1.5 rounded-full shadow-lg">
                                {{ $blog->category->name }}
                            </span>
                        @endif
                        
                        {{-- Featured Badge --}}
                        @if($blog->is_featured)
                            <span class="absolute top-3 right-3 bg-yellow-500 text-white text-xs font-bold px-3 py-1.5 rounded-full flex items-center shadow-lg">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                Featured
                            </span>
                        @endif
                        
                        {{-- Reading Time Overlay --}}
                        <span class="absolute bottom-3 right-3 bg-black/70 backdrop-blur-sm text-white text-xs px-2.5 py-1.5 rounded-full flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $blog->reading_time ?? 5 }} min
                        </span>
                    </a>

                    {{-- Content --}}
                    <div class="p-6 flex flex-col flex-grow">
                        <a href="{{ route('blogs.show', $blog->slug) }}" class="block mb-3">
                            <h3 class="text-xl font-bold text-slate-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors line-clamp-2">
                                {{ $blog->title }}
                            </h3>
                        </a>
                        
                        <p class="text-sm text-slate-600 dark:text-slate-400 mb-4 line-clamp-3 flex-grow leading-relaxed">
                            {{ $blog->excerpt ?: Str::limit(strip_tags($blog->content), 120) }}
                        </p>
                        
                        <div class="flex items-center justify-between pt-4 border-t border-slate-100 dark:border-slate-700">
                            <div class="flex items-center text-xs text-slate-500">
                                @if($blog->author_name)
                                    <span class="flex items-center mr-3">
                                        <span class="w-5 h-5 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-700 dark:text-indigo-400 text-xs font-semibold mr-1.5">
                                            {{ substr($blog->author_name, 0, 1) }}
                                        </span>
                                        {{ $blog->author_name }}
                                    </span>
                                @endif
                                <span class="flex items-center">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    {{ $blog->published_at?->format('M d, Y') ?: 'Draft' }}
                                </span>
                            </div>
                            <a href="{{ route('blogs.show', $blog->slug) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 text-sm font-medium flex items-center group/btn">
                                Read
                                <svg class="w-4 h-4 ml-1 group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-20">
                    <div class="bg-white dark:bg-slate-800 rounded-3xl p-12 max-w-2xl mx-auto border border-slate-200 dark:border-slate-700 shadow-xl">
                        <div class="w-24 h-24 bg-indigo-100 dark:bg-indigo-900/30 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-12 h-12 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H15"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-3">No stories found</h3>
                        <p class="text-slate-600 dark:text-slate-400 mb-8 text-lg">Try adjusting your search or filter to find what you're looking for.</p>
                        <a href="{{ route('blogs.index') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-xl transition-all duration-200 hover:shadow-lg hover:shadow-indigo-500/30 transform hover:scale-105">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Clear all filters
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($blogs->hasPages())
            <div class="mt-16">
                {{ $blogs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection