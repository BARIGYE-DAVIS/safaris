@extends('layouts.app')

@section('title', $destination->meta_title ?? $destination->name)
@section('meta_description', $destination->meta_description ?? \Illuminate\Support\Str::limit($destination->description ?? '', 160))
@section('meta_keywords', $destination->meta_keywords ?? $destination->focus_keyword ?? '')

@section('content')
<div class="bg-gray-50">
    {{-- Hero Section with Featured Image as Background --}}
    <section class="relative w-full min-h-screen flex items-center justify-center bg-no-repeat bg-center bg-fixed"
             style="background-image: url('{{ $destination->featured_image ? asset('storage/' . $destination->featured_image) : ($destination->image ? asset('storage/' . $destination->image) : '') }}'); background-size: cover; background-color: #1a3c34; will-change: transform;">
        {{-- Overlay --}}
        <div class="absolute inset-0 bg-black/60"></div>
        
        <div class="relative z-10 container mx-auto px-4 text-center text-white py-16">
            <div class="max-w-4xl mx-auto">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white leading-tight animate-fade-in">{{ $destination->name }}</h1>

                {{-- Type & Region Badges --}}
                <div class="flex flex-wrap justify-center gap-3 mt-4 animate-fade-in-up">
                    @if($destination->type)
                        <span class="px-3 py-1.5 md:px-4 md:py-2 bg-white/20 backdrop-blur-sm rounded-full text-xs md:text-sm font-semibold text-white border border-white/30">
                            {{ $destination->type }}
                        </span>
                    @endif
                    @if($destination->region)
                        <span class="px-3 py-1.5 md:px-4 md:py-2 bg-white/20 backdrop-blur-sm rounded-full text-xs md:text-sm font-semibold text-white border border-white/30">
                            {{ $destination->region }}
                        </span>
                    @endif
                    @if($destination->country)
                        <span class="px-3 py-1.5 md:px-4 md:py-2 bg-white/20 backdrop-blur-sm rounded-full text-xs md:text-sm font-semibold text-white border border-white/30">
                            {{ $destination->country->name }}
                        </span>
                    @endif
                </div>

                {{-- Breadcrumb --}}
                <nav class="mt-4 text-sm md:text-base animate-fade-in-up">
                    <ol class="flex justify-center space-x-2 text-green-200">
                        <li><a href="{{ route('index') }}" class="hover:text-white transition-colors duration-300 text-green-300">Home</a></li>
                        <li class="mx-1">/</li>
                        <li><a href="{{ route('destinations.index') }}" class="hover:text-white transition-colors duration-300 text-green-300">Destinations</a></li>
                        <li class="mx-1">/</li>
                        <li class="text-white font-medium">{{ $destination->name }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </section>

    {{-- Content Section with smooth scroll effect --}}
    <section class="relative py-12 md:py-16 bg-white/95 backdrop-blur-sm mt-[-2px] rounded-t-3xl shadow-2xl">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                <div class="p-6 md:p-10 lg:p-12">
                    
                    {{-- Description / Intro --}}
                    @if($destination->description)
                        <div class="text-gray-700 leading-relaxed mb-12 scroll-fade">
                            <p class="text-lg md:text-xl lg:text-2xl font-light text-gray-600 leading-relaxed border-l-4 border-green-500 pl-6">{{ $destination->description }}</p>
                        </div>
                    @endif

                    {{-- Render Content Blocks --}}
                    @php
                        // Ensure blocks is always an array
                        $blocks = $destination->content_blocks ?? [];
                        
                        if (is_string($blocks)) {
                            $blocks = json_decode($blocks, true);
                        }
                        
                        if (!is_array($blocks)) {
                            $blocks = [];
                        }
                        
                        $images = $destination->destinationImages ?? collect();
                        $imagesById = $images->keyBy('id');
                        $imagesByBlockId = $images->filter(function($i) { return !empty($i->block_id); })->keyBy('block_id');
                    @endphp

                    @forelse($blocks as $block)
                        @php
                            $type = $block['type'] ?? 'text';
                        @endphp

                        {{-- ─── HEADING BLOCK ─────────────────────────────── --}}
                        @if($type === 'heading')
                            @php
                                $level = $block['heading_level'] ?? 'h2';
                                $headingClasses = [
                                    'h1' => 'text-3xl md:text-4xl lg:text-5xl font-extrabold text-green-800 mb-6 mt-12 tracking-tight',
                                    'h2' => 'text-2xl md:text-3xl lg:text-4xl font-bold text-green-800 mb-4 mt-10 tracking-tight',
                                    'h3' => 'text-xl md:text-2xl lg:text-3xl font-bold text-gray-800 mb-3 mt-8',
                                    'h4' => 'text-lg md:text-xl lg:text-2xl font-semibold text-gray-800 mb-3 mt-6',
                                    'h5' => 'text-base md:text-lg lg:text-xl font-semibold text-gray-800 mb-2 mt-5',
                                    'h6' => 'text-sm md:text-base lg:text-lg font-semibold text-gray-800 mb-2 mt-4',
                                ];
                            @endphp
                            <{{ $level }} class="{{ $headingClasses[$level] ?? $headingClasses['h2'] }} scroll-fade">
                                {{ $block['content'] ?? '' }}
                            </{{ $level }}>

                        {{-- ─── TEXT BLOCK ───────────────────────────────── --}}
                        @elseif($type === 'text')
                            <div class="prose prose-lg prose-green max-w-none text-gray-700 leading-relaxed mb-8 scroll-fade">
                                {!! $block['content'] ?? '' !!}
                            </div>

                        {{-- ─── LIST BLOCK ───────────────────────────────── --}}
                        @elseif($type === 'list')
                            @php
                                $listType = $block['list_type'] ?? 'ul';
                                $listClasses = 'mb-8 space-y-2 text-gray-700 text-base md:text-lg lg:text-xl scroll-fade';
                            @endphp
                            @if($listType === 'ul')
                                <ul class="list-disc list-inside {{ $listClasses }} space-y-2">
                                    {!! $block['content'] ?? '' !!}
                                </ul>
                            @else
                                <ol class="list-decimal list-inside {{ $listClasses }} space-y-2">
                                    {!! $block['content'] ?? '' !!}
                                </ol>
                            @endif

                        {{-- ─── IMAGE BLOCK ───────────────────────────────── --}}
                        @elseif($type === 'image')
                            @php
                                // Get images for this block
                                $blockImages = collect();
                                
                                // Check if block has media_id
                                if (!empty($block['media_id']) && isset($imagesById[$block['media_id']])) {
                                    $blockImages->push($imagesById[$block['media_id']]);
                                }
                                
                                // Check if block has block_id
                                if (!empty($block['block_id']) && isset($imagesByBlockId[$block['block_id']])) {
                                    $blockImages->push($imagesByBlockId[$block['block_id']]);
                                }
                                
                                // Check if there are any images with this block_id in the database
                                if ($blockImages->isEmpty() && !empty($block['id'])) {
                                    $dbImages = $images->filter(function($img) use ($block) {
                                        return $img->block_id === $block['id'];
                                    });
                                    $blockImages = $blockImages->merge($dbImages);
                                }
                                
                                // If still empty, try using the block's ID as block_id
                                if ($blockImages->isEmpty() && !empty($block['id'])) {
                                    $dbImages = $images->filter(function($img) use ($block) {
                                        return $img->block_id === $block['id'];
                                    });
                                    $blockImages = $blockImages->merge($dbImages);
                                }
                                
                                $count = $blockImages->count();
                                
                                if ($count === 0) {
                                    // Try to find any image with matching block_id
                                    $blockImages = $images->filter(function($img) use ($block) {
                                        return $img->block_id === ($block['id'] ?? $block['block_id'] ?? null);
                                    });
                                    $count = $blockImages->count();
                                }
                                
                                if ($count === 1) {
                                    $gridClass = 'grid grid-cols-1';
                                    $imgHeight = 'h-[50vh] md:h-[60vh]';
                                } elseif ($count === 2) {
                                    $gridClass = 'grid grid-cols-1 md:grid-cols-2';
                                    $imgHeight = 'h-64 md:h-80';
                                } elseif ($count >= 3) {
                                    $gridClass = 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3';
                                    $imgHeight = 'h-56 md:h-64 lg:h-72';
                                } else {
                                    $gridClass = 'grid grid-cols-1';
                                    $imgHeight = 'h-56';
                                }
                            @endphp
                            
                            @if($blockImages->isNotEmpty())
                                <div class="{{ $gridClass }} gap-6 mb-10 scroll-fade">
                                    @foreach($blockImages as $image)
                                        <div class="relative overflow-hidden rounded-xl shadow-lg hover:shadow-2xl transition-all duration-500 group">
                                            <img src="{{ asset('storage/' . ltrim($image->storage_path, '/')) }}" 
                                                 alt="{{ $image->alt_text ?? $block['caption'] ?? $destination->name }}" 
                                                 class="w-full {{ $imgHeight }} object-cover transition-transform duration-700 group-hover:scale-110">
                                            @if($image->alt_text || $image->caption)
                                                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent text-white text-xs md:text-sm p-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                                    {{ $image->caption ?? $image->alt_text }}
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                        {{-- ─── TABLE BLOCK ───────────────────────────────── --}}
                        @elseif($type === 'table')
                            @php
                                $tableClasses = 'w-full text-sm md:text-base border-collapse scroll-fade shadow-sm rounded-lg overflow-hidden';
                                if ($block['striped'] ?? false) $tableClasses .= ' striped';
                                if ($block['bordered'] ?? true) $tableClasses .= ' bordered';
                                if ($block['hoverable'] ?? false) $tableClasses .= ' hoverable';
                                if ($block['small'] ?? false) $tableClasses .= ' small';
                                
                                $headerBg = $block['header_bg_color'] ?? '#1a3c34';
                                $headerText = $block['header_text_color'] ?? '#ffffff';
                                $rowBg = $block['row_bg_color'] ?? '#ffffff';
                                $rowAltBg = $block['row_bg_alt_color'] ?? '#f8fafc';
                                $rowText = $block['row_text_color'] ?? '#1e293b';
                                $borderColor = $block['border_color'] ?? '#e2e8f0';
                            @endphp
                            
                            <div class="mb-10 overflow-x-auto scroll-fade">
                                @if(!empty($block['caption']))
                                    <p class="text-sm text-green-800 mb-3 italic">{{ $block['caption'] }}</p>
                                @endif
                                
                                <table class="min-w-full {{ $tableClasses }}" style="border-color: {{ $borderColor }};">
                                    @if(!empty($block['headers']) && is_array($block['headers']))
                                        <thead>
                                            <tr>
                                                @foreach($block['headers'] as $header)
                                                    <th style="background-color: {{ $headerBg }}; color: {{ $headerText }}; border-color: {{ $borderColor }}; padding: 0.75rem 1.25rem; text-align: left; font-weight: 600; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;">
                                                        {{ $header }}
                                                    </th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                    @endif
                                    
                                    @if(!empty($block['rows']) && is_array($block['rows']))
                                        <tbody>
                                            @foreach($block['rows'] as $rowIndex => $row)
                                                <tr>
                                                    @foreach($row as $cellIndex => $cell)
                                                        @php
                                                            $isEven = ($rowIndex % 2 === 0);
                                                            $bgColor = ($block['striped'] ?? false) ? ($isEven ? $rowBg : $rowAltBg) : $rowBg;
                                                        @endphp
                                                        <td style="background-color: {{ $bgColor }}; color: {{ $rowText }}; border-color: {{ $borderColor }}; padding: 0.75rem 1.25rem;">
                                                            {{ $cell }}
                                                        </td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    @endif
                                </table>
                            </div>
                            
                            {{-- Add custom styles for table --}}
                            @push('styles')
                            <style>
                                .striped tbody tr:nth-child(even) {
                                    background-color: {{ $rowAltBg }};
                                }
                                .bordered th,
                                .bordered td {
                                    border: 1px solid {{ $borderColor }};
                                }
                                .hoverable tbody tr:hover {
                                    background-color: #f1f5f9 !important;
                                }
                                .small th,
                                .small td {
                                    padding: 0.5rem 0.75rem !important;
                                    font-size: 0.75rem !important;
                                }
                            </style>
                            @endpush

                        {{-- ─── BUTTONS BLOCK ─────────────────────────────── --}}
                        @elseif($type === 'buttons')
                            @php
                                $alignment = $block['alignment'] ?? 'left';
                                $direction = $block['direction'] ?? 'horizontal';
                                $gap = $block['gap'] ?? 'medium';
                                
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
                                
                                // Larger button sizes
                                $buttonSizeClasses = [
                                    'small' => 'px-6 py-3 text-sm',
                                    'medium' => 'px-8 py-4 text-base',
                                    'large' => 'px-10 py-5 text-lg'
                                ];
                            @endphp
                            
                            <div class="flex flex-wrap {{ $directionClasses[$direction] ?? 'flex-row' }} {{ $gapClasses[$gap] ?? 'gap-3' }} {{ $alignmentClasses[$alignment] ?? 'justify-start' }} mb-10 scroll-fade w-full">
                                @foreach($block['buttons'] ?? [] as $button)
                                    @php
                                        // Get the exact colors from the block data
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
                                    @endphp
                                    
                                    <a href="{{ $url }}" 
                                       target="{{ $target }}" 
                                       rel="{{ $rel }}"
                                       class="inline-flex items-center justify-center font-semibold transition-all duration-300 hover:transform hover:-translate-y-1 hover:shadow-xl {{ $buttonSizeClasses[$size] ?? 'px-8 py-4 text-base' }}"
                                       style="
                                           background-color: {{ $bgColor }};
                                           color: {{ $textColor }};
                                           border-radius: {{ $borderRadius }};
                                           border: none;
                                           min-width: {{ $direction === 'vertical' ? '100%' : '120px' }};
                                           {{ $direction === 'vertical' ? 'width: 100%;' : '' }}
                                           {{ $alignment === 'justify' ? 'flex: 1;' : '' }}
                                       "
                                       onmouseover="this.style.backgroundColor='{{ $hoverBg }}'; this.style.color='{{ $hoverText }}';"
                                       onmouseout="this.style.backgroundColor='{{ $bgColor }}'; this.style.color='{{ $textColor }}';">
                                        
                                        @if(!empty($icon))
                                            <i class="{{ $icon }} mr-2" style="color: {{ $textColor }};"></i>
                                        @endif
                                        
                                        {{ $button['text'] ?? 'Button' }}
                                        <i class="fas fa-arrow-right ml-3 text-sm opacity-75 group-hover:translate-x-1 transition-transform" style="color: {{ $textColor }};"></i>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    @empty
                        <div class="text-center py-12 text-gray-500 text-lg">
                            <p>No content available for this destination.</p>
                        </div>
                    @endforelse

                    {{-- Request Quote Button --}}
                    <div class="mt-12 pt-8 border-t-2 border-gray-200 scroll-fade">
                        <div class="bg-gradient-to-r from-green-50 to-blue-50 rounded-2xl p-8 md:p-10 text-center shadow-inner hover:shadow-xl transition-shadow duration-500">
                            <h3 class="text-2xl md:text-3xl font-bold text-gray-800 mb-3">Ready to Visit {{ $destination->name }}?</h3>
                            <p class="text-gray-600 text-lg md:text-xl mb-6 max-w-2xl mx-auto">Contact us today and let our expert team help you plan the perfect safari adventure.</p>
                            <a href="{{ route('contact') }}" 
                               class="inline-flex items-center bg-green-600 hover:bg-green-700 text-white px-8 py-4 md:px-10 md:py-5 rounded-xl font-bold text-base md:text-lg transition-all duration-300 shadow-lg hover:shadow-2xl transform hover:scale-105 hover:-translate-y-1">
                                Request a Quote
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('styles')
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
    
    /* ===== INLINE ELEMENTS - KEEP INLINE ===== */
    /* Links inside text - stay inline, display blue */
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
    
    /* Inline icons - stay inline with text */
    .prose .inline-icon,
    .text-gray-700 .inline-icon {
        display: inline-block !important;
        margin: 0 2px;
        font-size: 1.1em;
        vertical-align: middle;
    }
    
    /* Blockquotes - stay in flow where placed */
    .prose blockquote,
    .text-gray-700 blockquote {
        display: block;
        border-left: 4px solid #059669;
        padding: 0.75rem 1.5rem;
        margin: 1rem 0;
        font-style: italic;
        color: #475569;
        background-color: #f0fdf4;
        border-radius: 0.5rem;
    }
    
    .prose blockquote p,
    .text-gray-700 blockquote p {
        margin: 0;
        display: inline;
    }
    
    /* Inline blockquotes (if someone wraps inline text) */
    .prose blockquote.inline,
    .text-gray-700 blockquote.inline {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        margin: 0 0.25rem;
        border-left-width: 3px;
    }
    
    /* Lists - keep inline items */
    .prose ul, .prose ol,
    .text-gray-700 ul, .text-gray-700 ol {
        margin: 1rem 0;
        padding-left: 1.5rem;
    }
    .prose li, .text-gray-700 li {
        margin-bottom: 0.5rem;
        line-height: 1.8;
    }
    .prose li a, .text-gray-700 li a {
        display: inline !important;
    }
    
    /* ===== PROSE STYLES ===== */
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
    
    /* ===== TABLE STYLES ===== */
    .table-preview {
        width: 100%;
        border-collapse: collapse;
    }
    .table-preview th {
        font-weight: 600;
        text-align: left;
    }
    
    /* ===== BUTTON STYLES ===== */
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
    
    /* ===== RESPONSIVE ===== */
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
@endpush