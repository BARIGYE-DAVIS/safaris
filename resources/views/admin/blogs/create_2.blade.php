{{-- resources/views/admin/blogs/create.blade.php --}}
@extends('layouts.admin')

@section('title', 'Create Safari Story - Complete Theme System')

@push('styles')
<style>
    /* ==========================================================================
       COMPLETE THEME SYSTEM - APPLIES TO EVERYTHING
       ========================================================================== */
    
    /* Dark Theme (Default) */
    [data-theme="dark"] {
        --bg-primary: #0f172a;
        --bg-secondary: #1e293b;
        --bg-tertiary: #334155;
        --bg-card: #1f2937;
        --bg-editor: #0a0a0a;
        --bg-toolbar: #18181b;
        --bg-input: #2d2d2d;
        --bg-tag: #2d2d2d;
        
        --text-primary: #f8fafc;
        --text-secondary: #e2e8f0;
        --text-tertiary: #94a3b8;
        --text-muted: #64748b;
        
        --border-primary: #334155;
        --border-secondary: #475569;
        
        --accent-primary: #3b82f6;
        --accent-secondary: #2563eb;
        --accent-success: #10b981;
        --accent-warning: #f59e0b;
        --accent-danger: #ef4444;
        
        --tag-h1: #ff7b72;
        --tag-h2: #ffa657;
        --tag-h3: #ffc58b;
        --tag-p: #79c0ff;
        --tag-li: #7ee787;
        --tag-figure: #ffa198;
        --tag-div: #d2a8ff;
    }

    /* Light Theme */
    [data-theme="light"] {
        --bg-primary: #f8fafc;
        --bg-secondary: #f1f5f9;
        --bg-tertiary: #e2e8f0;
        --bg-card: #ffffff;
        --bg-editor: #ffffff;
        --bg-toolbar: #f1f5f9;
        --bg-input: #ffffff;
        --bg-tag: #f1f5f9;
        
        --text-primary: #0f172a;
        --text-secondary: #1e293b;
        --text-tertiary: #334155;
        --text-muted: #64748b;
        
        --border-primary: #cbd5e1;
        --border-secondary: #94a3b8;
        
        --accent-primary: #2563eb;
        --accent-secondary: #1d4ed8;
        --accent-success: #059669;
        --accent-warning: #d97706;
        --accent-danger: #dc2626;
        
        --tag-h1: #b91c1c;
        --tag-h2: #c2410c;
        --tag-h3: #b45309;
        --tag-p: #1e40af;
        --tag-li: #065f46;
        --tag-figure: #b45309;
        --tag-div: #6b21a8;
    }

    /* Blue Theme */
    [data-theme="blue"] {
        --bg-primary: #0c4a6e;
        --bg-secondary: #075985;
        --bg-tertiary: #0369a1;
        --bg-card: #0284c7;
        --bg-editor: #082f49;
        --bg-toolbar: #0369a1;
        --bg-input: #075985;
        --bg-tag: #0e7490;
        
        --text-primary: #f0f9ff;
        --text-secondary: #e0f2fe;
        --text-tertiary: #bae6fd;
        --text-muted: #7dd3fc;
        
        --border-primary: #38bdf8;
        --border-secondary: #7dd3fc;
        
        --accent-primary: #facc15;
        --accent-secondary: #eab308;
        --accent-success: #4ade80;
        --accent-warning: #fb923c;
        --accent-danger: #f87171;
        
        --tag-h1: #fde047;
        --tag-h2: #fef08a;
        --tag-h3: #fef9c3;
        --tag-p: #bae6fd;
        --tag-li: #bbf7d0;
        --tag-figure: #fed7aa;
        --tag-div: #e9d5ff;
    }

    /* Green Theme */
    [data-theme="green"] {
        --bg-primary: #064e3b;
        --bg-secondary: #065f46;
        --bg-tertiary: #047857;
        --bg-card: #059669;
        --bg-editor: #022c22;
        --bg-toolbar: #047857;
        --bg-input: #065f46;
        --bg-tag: #047857;
        
        --text-primary: #ecfdf5;
        --text-secondary: #d1fae5;
        --text-tertiary: #a7f3d0;
        --text-muted: #6ee7b7;
        
        --border-primary: #34d399;
        --border-secondary: #6ee7b7;
        
        --accent-primary: #fbbf24;
        --accent-secondary: #f59e0b;
        --accent-success: #4ade80;
        --accent-warning: #fb923c;
        --accent-danger: #f87171;
        
        --tag-h1: #fcd34d;
        --tag-h2: #fde68a;
        --tag-h3: #fef9c3;
        --tag-p: #a7f3d0;
        --tag-li: #d1fae5;
        --tag-figure: #fed7aa;
        --tag-div: #e9d5ff;
    }

    /* Purple Theme */
    [data-theme="purple"] {
        --bg-primary: #3b0764;
        --bg-secondary: #581c87;
        --bg-tertiary: #6b21a8;
        --bg-card: #7e22ce;
        --bg-editor: #1e1b4b;
        --bg-toolbar: #6b21a8;
        --bg-input: #581c87;
        --bg-tag: #6b21a8;
        
        --text-primary: #faf5ff;
        --text-secondary: #f3e8ff;
        --text-tertiary: #e9d5ff;
        --text-muted: #d8b4fe;
        
        --border-primary: #c084fc;
        --border-secondary: #d8b4fe;
        
        --accent-primary: #fde047;
        --accent-secondary: #facc15;
        --accent-success: #86efac;
        --accent-warning: #fdba74;
        --accent-danger: #fca5a5;
        
        --tag-h1: #fef08a;
        --tag-h2: #fde047;
        --tag-h3: #facc15;
        --tag-p: #d8b4fe;
        --tag-li: #bbf7d0;
        --tag-figure: #fed7aa;
        --tag-div: #f5d0fe;
    }

    /* ==========================================================================
       APPLY THEME TO EVERY SINGLE ELEMENT
       ========================================================================== */
    
    * {
        transition: background-color 0.2s ease, 
                    color 0.2s ease, 
                    border-color 0.2s ease,
                    box-shadow 0.2s ease;
    }

    html, body {
        background-color: var(--bg-primary) !important;
        color: var(--text-primary) !important;
        min-height: 100vh;
    }

    /* Backgrounds */
    .bg-gradient-to-br,
    .min-h-screen,
    .bg-gray-800,
    .bg-gray-900,
    .bg-gray-700,
    .bg-slate-800,
    .bg-slate-900,
    [class*="bg-"] {
        background-color: var(--bg-secondary) !important;
        background-image: none !important;
    }

    /* Cards */
    .rounded-xl,
    .bg-white,
    .bg-gray-50,
    [class*="card"] {
        background-color: var(--bg-card) !important;
        border-color: var(--border-primary) !important;
    }

    /* Text colors - EVERY text element */
    h1, h2, h3, h4, h5, h6,
    p, span, div, a,
    label, input, textarea, select,
    .text-white, .text-black,
    .text-gray-50, .text-gray-100, .text-gray-200,
    .text-gray-300, .text-gray-400, .text-gray-500,
    .text-gray-600, .text-gray-700, .text-gray-800,
    .text-gray-900, .text-slate-50, .text-slate-100,
    .text-slate-200, .text-slate-300, .text-slate-400,
    .text-slate-500, .text-slate-600, .text-slate-700,
    .text-slate-800, .text-slate-900 {
        color: var(--text-primary) !important;
    }

    /* Secondary text */
    .text-muted,
    .text-secondary,
    small, .small,
    .text-sm, .text-xs,
    figcaption, .figcaption {
        color: var(--text-secondary) !important;
    }

    /* Inputs and form elements */
    input, textarea, select,
    .form-input, .form-select, .form-textarea {
        background-color: var(--bg-input) !important;
        border-color: var(--border-primary) !important;
        color: var(--text-primary) !important;
    }

    input::placeholder,
    textarea::placeholder {
        color: var(--text-muted) !important;
        opacity: 0.7;
    }

    /* Borders and dividers */
    .border, .border-t, .border-b, .border-l, .border-r,
    .border-gray-100, .border-gray-200, .border-gray-300,
    .border-gray-400, .border-gray-500, .border-gray-600,
    .border-gray-700, .border-gray-800, .border-gray-900,
    .border-slate-100, .border-slate-200, .border-slate-300,
    .border-slate-400, .border-slate-500, .border-slate-600,
    .border-slate-700, .border-slate-800, .border-slate-900 {
        border-color: var(--border-primary) !important;
    }

    /* ==========================================================================
       EDITOR STYLES - THEME AWARE
       ========================================================================== */
    
    #editor {
        min-height: 520px;
        line-height: 1.8;
        outline: none;
        font-family: 'Courier New', Consolas, Monaco, monospace;
        font-size: 15px;
        background-color: var(--bg-editor) !important;
        color: var(--text-primary) !important;
        padding: 1.75rem;
        border-radius: 0.75rem 0.75rem 0 0;
        transition: all 0.3s ease;
        position: relative;
    }

    /* Tag Wrapper - Universal */
    .tag-wrapper {
        display: block;
        position: relative;
        margin: 16px 0;
        padding: 12px;
        border: 1px solid transparent;
        border-radius: 8px;
        transition: all 0.2s ease;
    }

    .tag-wrapper:hover {
        border-color: var(--border-primary);
        background-color: var(--bg-tag);
        opacity: 0.8;
    }

    /* Tag labels - COLOR CODED BY TAG TYPE */
    .tag-label {
        font-family: 'Courier New', Consolas, monospace;
        font-size: 11px;
        padding: 2px 8px;
        border-radius: 4px;
        display: inline-block;
        font-weight: 600;
        margin: 2px;
        user-select: none;
        background-color: var(--bg-tag);
        border: 1px solid var(--border-secondary);
    }

    /* Color coded tags */
    .tag-wrapper[data-tag="h1"] .tag-label,
    [data-tag="h1"] .tag-label {
        color: var(--tag-h1) !important;
        border-left: 3px solid var(--tag-h1);
    }

    .tag-wrapper[data-tag="h2"] .tag-label {
        color: var(--tag-h2) !important;
        border-left: 3px solid var(--tag-h2);
    }

    .tag-wrapper[data-tag="h3"] .tag-label {
        color: var(--tag-h3) !important;
        border-left: 3px solid var(--tag-h3);
    }

    .tag-wrapper[data-tag="p"] .tag-label {
        color: var(--tag-p) !important;
        border-left: 3px solid var(--tag-p);
    }

    .tag-wrapper[data-tag="li"] .tag-label,
    .list-item-wrapper .tag-label {
        color: var(--tag-li) !important;
        border-left: 3px solid var(--tag-li);
    }

    .tag-wrapper[data-tag="figure"] .tag-label {
        color: var(--tag-figure) !important;
        border-left: 3px solid var(--tag-figure);
    }

    .tag-wrapper[data-tag="div"] .tag-label {
        color: var(--tag-div) !important;
        border-left: 3px solid var(--tag-div);
    }

    /* Tag content area */
    .tag-content {
        display: block;
        padding: 8px 12px;
        margin: 6px 0;
        min-height: 28px;
        outline: none;
        color: var(--text-primary);
        background-color: var(--bg-editor);
        border-radius: 4px;
        font-family: inherit;
        line-height: 1.6;
    }

    .tag-content:empty::before {
        content: 'Type here...';
        color: var(--text-muted);
        font-style: italic;
    }

    .tag-content:focus {
        background-color: var(--bg-input);
    }

    /* Delete button - UNIVERSAL */
    .delete-btn,
    .list-item-delete,
    .inline-delete {
        position: absolute;
        top: -8px;
        right: -8px;
        width: 22px;
        height: 22px;
        background-color: var(--accent-danger);
        color: white;
        border-radius: 50%;
        display: none;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: bold;
        cursor: pointer;
        border: 2px solid var(--bg-editor);
        z-index: 100;
        box-shadow: 0 2px 8px rgba(0,0,0,0.3);
    }

    .tag-wrapper:hover .delete-btn,
    .list-item-wrapper:hover .list-item-delete,
    .inline-wrapper:hover .inline-delete {
        display: flex;
    }

    .delete-btn:hover,
    .list-item-delete:hover,
    .inline-delete:hover {
        background-color: #b91c1c;
        transform: scale(1.15);
    }

    /* ==========================================================================
       LIST STYLES
       ========================================================================== */

    .list-item-wrapper {
        display: flex;
        align-items: flex-start;
        margin: 8px 0;
        padding: 6px 12px;
        border-radius: 6px;
        position: relative;
        transition: all 0.2s ease;
        background-color: var(--bg-editor);
    }

    .list-item-wrapper:hover {
        background-color: var(--bg-tag);
    }

    .list-item-content {
        flex: 1;
        outline: none;
        min-height: 24px;
        color: var(--text-primary);
        padding: 2px 8px;
        margin: 0 8px;
        background-color: transparent;
        border-radius: 3px;
    }

    .list-item-content:focus {
        background-color: var(--bg-input);
    }

    .list-item-content:empty::before {
        content: 'List item...';
        color: var(--text-muted);
        font-style: italic;
    }

    .list-add-btn {
        display: inline-block;
        font-size: 11px;
        padding: 5px 12px;
        background-color: var(--accent-success);
        color: white;
        border-radius: 4px;
        cursor: pointer;
        margin: 8px 0 4px 0;
        border: 1px solid transparent;
        font-weight: 500;
    }

    .list-add-btn:hover {
        background-color: var(--accent-secondary);
        transform: translateY(-1px);
    }

    /* ==========================================================================
       INLINE ELEMENTS
       ========================================================================== */

    .inline-wrapper {
        display: inline-block;
        position: relative;
        padding: 2px 4px;
        margin: 0 2px;
        border-radius: 3px;
        border: 1px solid transparent;
        background-color: transparent;
    }

    .inline-wrapper:hover {
        background-color: var(--bg-tag);
        border-color: var(--border-primary);
    }

    .inline-wrapper .tag-label {
        font-size: 10px;
        padding: 1px 4px;
    }

    .inline-wrapper [contenteditable="true"] {
        padding: 0 2px;
        outline: none;
        color: var(--text-primary);
    }

    /* ==========================================================================
       IMAGE FIGURE
       ========================================================================== */

    .image-figure {
        display: block;
        margin: 20px 0;
        padding: 16px;
        background-color: var(--bg-tag);
        border-left: 4px solid var(--tag-figure);
        border-radius: 8px;
        position: relative;
        width: fit-content;
        max-width: 100%;
    }

    .image-figure img {
        max-width: 300px;
        max-height: 200px;
        width: auto;
        height: auto;
        border-radius: 6px;
        border: 1px solid var(--border-primary);
        display: block;
        margin: 12px 0;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    .image-caption {
        font-size: 12px;
        color: var(--text-secondary);
        padding: 6px 12px;
        background-color: var(--bg-input);
        border-radius: 4px;
        margin-top: 8px;
        outline: none;
        min-width: 200px;
    }

    .image-caption:empty::before {
        content: 'Add caption...';
        color: var(--text-muted);
        font-style: italic;
    }

    .image-caption:focus {
        background-color: var(--bg-editor);
        border: 1px solid var(--border-primary);
    }

    /* ==========================================================================
       FLOATING BOTTOM TOOLBAR - THEME AWARE
       ========================================================================== */

    .bottom-toolbar {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background-color: var(--bg-toolbar) !important;
        border-top: 2px solid var(--border-primary);
        padding: 12px 20px;
        z-index: 9999;
        box-shadow: 0 -4px 12px rgba(0, 0, 0, 0.2);
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        align-items: center;
        max-height: 140px;
        overflow-y: auto;
    }

    .toolbar-section {
        display: flex;
        gap: 4px;
        align-items: center;
        padding: 4px 8px;
        background-color: var(--bg-secondary);
        border-radius: 8px;
    }

    .toolbar-divider {
        width: 1px;
        height: 30px;
        background-color: var(--border-primary);
        margin: 0 4px;
    }

    .bottom-toolbar button {
        padding: 6px 12px;
        font-size: 11px;
        border-radius: 4px;
        background-color: var(--bg-tertiary);
        color: var(--text-primary) !important;
        border: 1px solid var(--border-secondary);
        cursor: pointer;
        transition: all 0.2s ease;
        font-family: 'Courier New', monospace;
        white-space: nowrap;
        font-weight: 500;
    }

    .bottom-toolbar button:hover {
        background-color: var(--accent-primary);
        color: white !important;
        transform: translateY(-2px);
        border-color: var(--accent-primary);
    }

    .bottom-toolbar button.active {
        background-color: var(--accent-primary);
        color: white !important;
    }

    /* Theme switcher */
    .theme-switcher {
        display: flex;
        gap: 6px;
        align-items: center;
        padding: 4px;
    }

    .theme-btn {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        border: 2px solid var(--border-primary);
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .theme-btn:hover {
        transform: scale(1.15);
        border-color: var(--accent-primary);
    }

    .theme-btn.active {
        border-color: var(--accent-primary);
        box-shadow: 0 0 0 2px var(--accent-primary);
    }

    .theme-btn[data-theme="dark"] { background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); }
    .theme-btn[data-theme="light"] { background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); }
    .theme-btn[data-theme="blue"] { background: linear-gradient(135deg, #0c4a6e 0%, #0284c7 100%); }
    .theme-btn[data-theme="green"] { background: linear-gradient(135deg, #064e3b 0%, #059669 100%); }
    .theme-btn[data-theme="purple"] { background: linear-gradient(135deg, #3b0764 0%, #7e22ce 100%); }

    /* Drag overlay */
    .drag-over::after {
        content: '⬇️ DROP IMAGE HERE ⬇️';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: var(--accent-primary);
        opacity: 0.9;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        font-weight: 600;
        border-radius: 0.5rem;
        pointer-events: none;
        z-index: 1000;
    }

    /* Word/Image counter */
    .editor-footer {
        background-color: var(--bg-toolbar);
        color: var(--text-secondary);
        border-top: 1px solid var(--border-primary);
    }

    /* Scrollbar styling */
    ::-webkit-scrollbar {
        width: 10px;
        height: 10px;
    }

    ::-webkit-scrollbar-track {
        background: var(--bg-secondary);
    }

    ::-webkit-scrollbar-thumb {
        background: var(--border-secondary);
        border-radius: 5px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: var(--accent-primary);
    }
</style>
@endpush

@section('content')
<div class="min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" style="padding-bottom: 160px;">
        {{-- Header --}}
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center space-x-3">
                <div class="p-2 bg-gradient-to-br from-accent-primary to-accent-secondary rounded-lg shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold">Create Safari Story</h1>
                    <p class="text-sm mt-1">✨ Complete Editor - Double-click to exit, Full page theme</p>
                </div>
            </div>
            <div class="mt-4 sm:mt-0 flex space-x-3">
                <a href="{{ route('admin.blogs.index') }}" class="inline-flex items-center px-4 py-2 border rounded-lg transition-colors shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back
                </a>
            </div>
        </div>

        {{-- Error Alert --}}
        @if ($errors->any())
        <div class="mb-6 bg-danger bg-opacity-10 border-l-4 border-danger rounded-r-lg shadow-md">
            <div class="flex items-start p-4">
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 text-danger" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-danger">Please fix the following errors:</p>
                    <ul class="mt-2 text-sm list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif

        <form id="blog-form" action="{{ route('admin.blogs.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Main Content Column --}}
                <div class="lg:col-span-2 space-y-5">
                    {{-- Title --}}
                    <div class="rounded-xl shadow-sm border p-5">
                        <label for="title" class="block text-sm font-medium mb-1">
                            Story Title <span class="text-danger">*</span>
                        </label>
                        <input 
                            id="title" 
                            name="title" 
                            value="{{ old('title') }}" 
                            class="mt-1 block w-full rounded-lg shadow-sm focus:border-accent-primary focus:ring focus:ring-accent-primary focus:ring-opacity-50 text-lg font-medium" 
                            placeholder="e.g., The Great Migration: A Once-in-a-Lifetime Experience"
                            required
                        >
                        <div class="mt-2 flex items-center text-sm">
                            <span class="font-mono px-2 py-1 rounded">/blog/</span>
                            <span id="slug-preview" class="font-mono text-accent-primary ml-1 px-2 py-1 rounded">{{ Str::slug(old('title', 'your-post-slug')) }}</span>
                        </div>
                    </div>

                    {{-- Excerpt --}}
                    <div class="rounded-xl shadow-sm border p-5">
                        <label for="excerpt" class="block text-sm font-medium mb-1">Excerpt / Summary</label>
                        <textarea 
                            id="excerpt" 
                            name="excerpt" 
                            rows="2" 
                            class="mt-1 block w-full rounded-lg shadow-sm focus:border-accent-primary focus:ring focus:ring-accent-primary focus:ring-opacity-50"
                            placeholder="A brief summary of your safari story...">{{ old('excerpt') }}</textarea>
                    </div>

                    {{-- EDITOR --}}
                    <div class="rounded-xl shadow-lg border overflow-hidden">
                        {{-- Top Info Bar --}}
                        <div class="px-4 py-3 border-b flex items-center justify-between editor-footer">
                            <div class="flex items-center space-x-4 text-xs">
                                <span>🗑️ <span class="text-accent-warning">Hover</span> to delete</span>
                                <span>⬇️ <span class="text-accent-warning">Toolbar</span> at bottom</span>
                                <span>✏️ <span class="text-accent-warning">Double-click</span> to exit</span>
                            </div>
                            <div class="text-xs">
                                <span id="word-count">0</span> words • 
                                <span id="image-count">0</span> images
                            </div>
                        </div>

                        {{-- EDITOR CONTENT AREA --}}
                        <div class="relative">
                            <div id="editor" contenteditable="true" aria-label="Blog content editor">
                                <!-- Content inserted by JavaScript -->
                            </div>
                        </div>

                        {{-- Hidden textarea --}}
                        <textarea name="content" id="content" class="hidden">{{ old('content') }}</textarea>
                    </div>

                    {{-- SEO Section --}}
                    <div class="rounded-xl shadow-sm border overflow-hidden">
                        <div class="px-5 py-4 border-b flex items-center justify-between cursor-pointer" id="seo-toggle">
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                <h3 class="text-sm font-medium">SEO & Meta Data</h3>
                            </div>
                            <svg class="w-5 h-5 transform transition-transform" id="seo-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                        <div id="seo-panel" class="p-5 space-y-4 hidden">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="meta_title" class="block text-xs font-medium mb-1">Meta Title</label>
                                    <input id="meta_title" name="meta_title" value="{{ old('meta_title') }}" class="block w-full rounded-lg shadow-sm focus:border-accent-primary focus:ring focus:ring-accent-primary focus:ring-opacity-50 text-sm">
                                </div>
                                <div>
                                    <label for="meta_keywords" class="block text-xs font-medium mb-1">Meta Keywords</label>
                                    <input id="meta_keywords" name="meta_keywords" value="{{ old('meta_keywords') }}" class="block w-full rounded-lg shadow-sm focus:border-accent-primary focus:ring focus:ring-accent-primary focus:ring-opacity-50 text-sm">
                                </div>
                            </div>
                            <div>
                                <label for="meta_description" class="block text-xs font-medium mb-1">Meta Description</label>
                                <textarea id="meta_description" name="meta_description" rows="2" class="block w-full rounded-lg shadow-sm focus:border-accent-primary focus:ring focus:ring-accent-primary focus:ring-opacity-50 text-sm">{{ old('meta_description') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Sidebar --}}
                <div class="space-y-5">
                    <div class="rounded-xl shadow-sm border overflow-hidden sticky top-6">
                        <div class="px-5 py-4 border-b">
                            <h3 class="text-sm font-semibold flex items-center">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M12 5l7 7-7 7"></path>
                                </svg>
                                Publish
                            </h3>
                        </div>

                        <div class="form-group">
                            <label for="author_name">Author Name</label>
                            <input type="text" id="author_name" name="author_name" class="form-control" value="{{ old('author_name') }}">
                        </div>
                        <div class="p-5 space-y-4">
                            {{-- Category --}}
                            <div>
                                <label for="category_id" class="block text-xs font-medium uppercase tracking-wider mb-1">Category</label>
                                <select id="category_id" name="category_id" class="mt-1 block w-full rounded-lg shadow-sm focus:border-accent-primary focus:ring focus:ring-accent-primary focus:ring-opacity-50">
                                    <option value="">— Select Category —</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Featured Image --}}
                            <div>
                                <label class="block text-xs font-medium uppercase tracking-wider mb-1">Featured Image</label>
                                <div class="mt-1 flex justify-center px-4 pt-4 pb-4 border-2 border-dashed rounded-lg transition-colors">
                                    <div class="space-y-1 text-center">
                                        <svg class="mx-auto h-10 w-10" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H8a4 4 0 01-4-4v-8m32 0l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex text-sm">
                                            <label for="featured_image" class="relative cursor-pointer rounded-md font-medium text-accent-primary hover:text-accent-secondary px-3 py-1">
                                                <span>Upload</span>
                                                <input id="featured_image" name="featured_image" type="file" class="sr-only" accept="image/*">
                                            </label>
                                        </div>
                                        <p class="text-xs">PNG, JPG, WebP up to 5MB</p>
                                    </div>
                                </div>
                                <div id="featured-preview-container" class="mt-3 hidden">
                                    <div class="relative rounded-lg overflow-hidden border group">
                                        <img id="featured-preview" src="" alt="Featured preview" class="w-full h-32 object-cover">
                                        <button type="button" id="remove-featured" class="absolute top-1 right-1 p-1 rounded-full opacity-0 group-hover:opacity-100 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            {{-- Status --}}
                            <div>
                                <label for="status" class="block text-xs font-medium uppercase tracking-wider mb-1">Status</label>
                                <select id="status" name="status" class="mt-1 block w-full rounded-lg shadow-sm focus:border-accent-primary focus:ring focus:ring-accent-primary focus:ring-opacity-50">
                                    <option value="draft" {{ old('status', 'draft') === 'draft' ? 'selected' : '' }}>📝 Draft</option>
                                    <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>🚀 Published</option>
                                    <option value="scheduled" {{ old('status') === 'scheduled' ? 'selected' : '' }}>⏰ Scheduled</option>
                                </select>
                            </div>

                            <div id="scheduled-datetime" class="{{ old('status') === 'scheduled' ? '' : 'hidden' }}">
                                <label for="published_at" class="block text-xs font-medium uppercase tracking-wider mb-1">Schedule Date & Time</label>
                                <input id="published_at" name="published_at" value="{{ old('published_at') }}" class="mt-1 block w-full rounded-lg shadow-sm" type="datetime-local">
                            </div>

                            {{-- Tags --}}
                            <div>
                                <label for="tags" class="block text-xs font-medium uppercase tracking-wider mb-1">Tags</label>
                                <input id="tags" name="tags" value="{{ old('tags') }}" class="mt-1 block w-full rounded-lg shadow-sm" placeholder="safari, wildlife, kenya">
                            </div>

                            {{-- Featured Toggle --}}
                            <div class="flex items-center justify-between p-3 bg-warning bg-opacity-10 rounded-lg border border-warning">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-5 h-5 text-warning" fill="{{ old('is_featured') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                    </svg>
                                    <span class="text-sm font-medium">Feature this post</span>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" id="is_featured" name="is_featured" class="sr-only peer" {{ old('is_featured') ? 'checked' : '' }}>
                                    <div class="w-10 h-5 bg-gray-600 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-warning rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-warning"></div>
                                </label>
                            </div>

                            {{-- Submit Buttons --}}
                            <div class="space-y-2 pt-2">
                                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-3 bg-gradient-to-r from-accent-primary to-accent-secondary text-white font-medium rounded-lg hover:from-accent-secondary hover:to-accent-primary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent-primary transition-all shadow-md">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Create Story
                                </button>
                                <button type="button" id="save-draft-btn" class="w-full inline-flex justify-center items-center px-4 py-2 border font-medium rounded-lg transition-colors">
                                    Save as Draft
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- FLOATING BOTTOM TOOLBAR --}}
<div class="bottom-toolbar">
    {{-- Theme Switcher --}}
    <div class="toolbar-section theme-switcher">
        <span class="text-xs mr-2">Theme:</span>
        <button type="button" class="theme-btn active" data-theme="dark" title="Dark Theme"></button>
        <button type="button" class="theme-btn" data-theme="light" title="Light Theme"></button>
        <button type="button" class="theme-btn" data-theme="blue" title="Blue Theme"></button>
        <button type="button" class="theme-btn" data-theme="green" title="Green Theme"></button>
        <button type="button" class="theme-btn" data-theme="purple" title="Purple Theme"></button>
    </div>

    <div class="toolbar-divider"></div>

    {{-- Block Elements --}}
    <div class="toolbar-section">
        <button type="button" data-tag="h1">H1</button>
        <button type="button" data-tag="h2">H2</button>
        <button type="button" data-tag="h3">H3</button>
        <button type="button" data-tag="h4">H4</button>
        <button type="button" data-tag="h5">H5</button>
        <button type="button" data-tag="h6">H6</button>
        <button type="button" data-tag="p">P</button>
        <button type="button" data-tag="div">DIV</button>
    </div>

    <div class="toolbar-divider"></div>

    {{-- Lists & Quotes --}}
    <div class="toolbar-section">
        <button type="button" data-tag="ul">UL</button>
        <button type="button" data-tag="ol">OL</button>
        <button type="button" data-tag="blockquote">QUOTE</button>
    </div>

    <div class="toolbar-divider"></div>

    {{-- Text Formatting --}}
    <div class="toolbar-section">
        <button type="button" data-tag="strong">STRONG</button>
        <button type="button" data-tag="em">EM</button>
        <button type="button" data-tag="u">U</button>
        <button type="button" data-tag="mark">MARK</button>
        <button type="button" data-tag="small">SMALL</button>
        <button type="button" data-tag="del">DEL</button>
        <button type="button" data-tag="ins">INS</button>
    </div>

    <div class="toolbar-divider"></div>

    {{-- Code & Pre --}}
    <div class="toolbar-section">
        <button type="button" data-tag="code">CODE</button>
        <button type="button" data-tag="pre">PRE</button>
        <button type="button" data-tag="kbd">KBD</button>
        <button type="button" data-tag="samp">SAMP</button>
    </div>

    <div class="toolbar-divider"></div>

    {{-- Semantic Elements --}}
    <div class="toolbar-section">
        <button type="button" data-tag="article">ARTICLE</button>
        <button type="button" data-tag="section">SECTION</button>
        <button type="button" data-tag="aside">ASIDE</button>
        <button type="button" data-tag="header">HEADER</button>
        <button type="button" data-tag="footer">FOOTER</button>
    </div>

    <div class="toolbar-divider"></div>

    {{-- Media & Links --}}
    <div class="toolbar-section">
        <input type="file" id="image-input" accept="image/*" class="hidden">
        <button type="button" id="insert-image">IMG</button>
        <button type="button" id="insert-link">A</button>
        <button type="button" data-tag="span">SPAN</button>
        <button type="button" data-tag="hr">HR</button>
    </div>
</div>

<script>
(function() {
    'use strict';

    const editor = document.getElementById('editor');
    const contentField = document.getElementById('content');
    const blogForm = document.getElementById('blog-form');
    const titleInput = document.getElementById('title');
    const slugPreview = document.getElementById('slug-preview');
    const imageInput = document.getElementById('image-input');
    const insertImageBtn = document.getElementById('insert-image');
    const wordCountSpan = document.getElementById('word-count');
    const imageCountSpan = document.getElementById('image-count');

    const csrfToken = '{{ csrf_token() }}';
    const uploadUrl = "{{ route('admin.blogs.uploadImage') }}";
    const MAX_FILE_SIZE = 5 * 1024 * 1024;

    // ==========================================================================
    // 1) COMPLETE THEME SYSTEM - APPLIES TO ENTIRE PAGE, EVERY ELEMENT
    // ==========================================================================
    
    function setupThemeSwitcher() {
        const themeButtons = document.querySelectorAll('.theme-btn');
        
        // Apply theme to HTML and BODY - EVERYTHING
        function applyTheme(theme) {
            // Set on html and body for complete coverage
            document.documentElement.setAttribute('data-theme', theme);
            document.body.setAttribute('data-theme', theme);
            
            // Also set on all major containers
            const containers = document.querySelectorAll('.min-h-screen, .bottom-toolbar, .rounded-xl');
            containers.forEach(el => {
                el.setAttribute('data-theme', theme);
            });
            
            // Save to localStorage
            localStorage.setItem('editorTheme', theme);
            
            // Force repaint to ensure all colors update
            document.body.style.display = 'none';
            document.body.offsetHeight;
            document.body.style.display = '';
        }
        
        themeButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const theme = this.dataset.theme;
                
                themeButtons.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                
                applyTheme(theme);
            });
        });
        
        // Load saved theme
        const savedTheme = localStorage.getItem('editorTheme') || 'dark';
        const savedBtn = document.querySelector(`.theme-btn[data-theme="${savedTheme}"]`);
        if (savedBtn) {
            savedBtn.click();
        } else {
            // Default to dark
            document.documentElement.setAttribute('data-theme', 'dark');
            document.body.setAttribute('data-theme', 'dark');
        }
    }

    // ==========================================================================
    // 2) CREATE TAG WRAPPER - WITH DATA ATTRIBUTES FOR COLOR CODING
    // ==========================================================================
    
    function createTagWrapper(tagName, initialContent = '') {
        const isInline = ['strong', 'em', 'u', 'mark', 'small', 'del', 'ins', 'code', 'kbd', 'samp', 'span', 'a'].includes(tagName);
        
        if (isInline) {
            return createInlineWrapper(tagName, initialContent);
        }
        
        const wrapper = document.createElement('div');
        wrapper.className = 'tag-wrapper';
        wrapper.setAttribute('data-tag', tagName);
        
        const openTag = document.createElement('span');
        openTag.className = 'tag-label';
        openTag.textContent = `<${tagName}>`;
        
        const content = document.createElement('div');
        content.className = 'tag-content';
        content.contentEditable = 'true';
        content.innerHTML = initialContent || '';
        
        const closeTag = document.createElement('span');
        closeTag.className = 'tag-label';
        closeTag.textContent = `</${tagName}>`;
        
        const deleteBtn = document.createElement('div');
        deleteBtn.className = 'delete-btn';
        deleteBtn.innerHTML = '×';
        deleteBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            wrapper.remove();
            updateCounts();
        });
        
        wrapper.appendChild(deleteBtn);
        wrapper.appendChild(openTag);
        wrapper.appendChild(content);
        wrapper.appendChild(closeTag);
        
        return wrapper;
    }

    // ==========================================================================
    // 3) CREATE INLINE WRAPPER
    // ==========================================================================
    
    function createInlineWrapper(tagName, initialContent = '') {
        const wrapper = document.createElement('span');
        wrapper.className = 'inline-wrapper';
        wrapper.setAttribute('data-tag', tagName);
        
        const openTag = document.createElement('span');
        openTag.className = 'tag-label';
        openTag.textContent = `<${tagName}>`;
        
        const content = document.createElement('span');
        content.contentEditable = 'true';
        content.textContent = initialContent || 'text';
        
        const closeTag = document.createElement('span');
        closeTag.className = 'tag-label';
        closeTag.textContent = `</${tagName}>`;
        
        const deleteBtn = document.createElement('span');
        deleteBtn.className = 'inline-delete';
        deleteBtn.innerHTML = '×';
        deleteBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            wrapper.remove();
            updateCounts();
        });
        
        wrapper.appendChild(openTag);
        wrapper.appendChild(content);
        wrapper.appendChild(closeTag);
        wrapper.appendChild(deleteBtn);
        
        return wrapper;
    }

    // ==========================================================================
    // 4) CREATE LIST WRAPPER
    // ==========================================================================
    
    function createListWrapper(listType = 'ul') {
        const wrapper = document.createElement('div');
        wrapper.className = 'tag-wrapper';
        wrapper.setAttribute('data-tag', listType);
        
        const openTag = document.createElement('span');
        openTag.className = 'tag-label';
        openTag.textContent = `<${listType}>`;
        
        const listContainer = document.createElement('div');
        listContainer.className = 'list-container';
        
        const firstItem = createListItem();
        listContainer.appendChild(firstItem);
        
        const closeTag = document.createElement('span');
        closeTag.className = 'tag-label';
        closeTag.textContent = `</${listType}>`;
        
        const addBtn = document.createElement('div');
        addBtn.className = 'list-add-btn';
        addBtn.innerHTML = '+ Add Item';
        addBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            const newItem = createListItem();
            listContainer.appendChild(newItem);
            newItem.querySelector('.list-item-content').focus();
        });
        
        const deleteBtn = document.createElement('div');
        deleteBtn.className = 'delete-btn';
        deleteBtn.innerHTML = '×';
        deleteBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            wrapper.remove();
            updateCounts();
        });
        
        wrapper.appendChild(deleteBtn);
        wrapper.appendChild(openTag);
        wrapper.appendChild(listContainer);
        wrapper.appendChild(addBtn);
        wrapper.appendChild(closeTag);
        
        return wrapper;
    }

    // ==========================================================================
    // 5) CREATE LIST ITEM
    // ==========================================================================
    
    function createListItem(initialContent = '') {
        const itemWrapper = document.createElement('div');
        itemWrapper.className = 'list-item-wrapper';
        itemWrapper.setAttribute('data-tag', 'li');
        
        const liTag = document.createElement('span');
        liTag.className = 'tag-label';
        liTag.textContent = '<li>';
        
        const content = document.createElement('div');
        content.className = 'list-item-content';
        content.contentEditable = 'true';
        content.innerHTML = initialContent || '';
        
        const liCloseTag = document.createElement('span');
        liCloseTag.className = 'tag-label';
        liCloseTag.textContent = '</li>';
        
        const deleteBtn = document.createElement('div');
        deleteBtn.className = 'list-item-delete';
        deleteBtn.innerHTML = '×';
        deleteBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            itemWrapper.remove();
            updateCounts();
        });
        
        itemWrapper.appendChild(liTag);
        itemWrapper.appendChild(content);
        itemWrapper.appendChild(liCloseTag);
        itemWrapper.appendChild(deleteBtn);
        
        return itemWrapper;
    }

    // ==========================================================================
    // 6) DOUBLE CLICK TO EXIT - CRITICAL FEATURE
    // ==========================================================================
    
    function setupDoubleClickExit() {
        // When double-clicked on ANY editable area, exit to parent wrapper
        editor.addEventListener('dblclick', function(e) {
            const target = e.target;
            
            // If clicked on content editable area
            if (target.classList.contains('tag-content') || 
                target.classList.contains('list-item-content') ||
                target.classList.contains('image-caption') ||
                target.contentEditable === 'true') {
                
                e.preventDefault();
                
                // Find the parent tag wrapper
                const wrapper = target.closest('.tag-wrapper, .list-item-wrapper, .image-figure, .inline-wrapper');
                
                if (wrapper) {
                    // Create a new paragraph after the wrapper
                    const p = createTagWrapper('p', '');
                    if (wrapper.nextSibling) {
                        wrapper.parentNode.insertBefore(p, wrapper.nextSibling);
                    } else {
                        wrapper.parentNode.appendChild(p);
                    }
                    
                    // Focus on the new paragraph
                    const contentArea = p.querySelector('.tag-content');
                    if (contentArea) {
                        contentArea.focus();
                    }
                }
            }
        });
        
        // Also handle double click on wrapper itself
        editor.addEventListener('dblclick', function(e) {
            const wrapper = e.target.closest('.tag-wrapper, .list-item-wrapper, .image-figure, .inline-wrapper');
            
            if (wrapper && !e.target.classList.contains('tag-content') && 
                !e.target.classList.contains('list-item-content')) {
                
                e.preventDefault();
                
                // Create a new paragraph after the wrapper
                const p = createTagWrapper('p', '');
                if (wrapper.nextSibling) {
                    wrapper.parentNode.insertBefore(p, wrapper.nextSibling);
                } else {
                    wrapper.parentNode.appendChild(p);
                }
                
                // Focus on the new paragraph
                const contentArea = p.querySelector('.tag-content');
                if (contentArea) {
                    contentArea.focus();
                }
            }
        });
    }

    // ==========================================================================
    // 7) INSERT AT CURSOR
    // ==========================================================================
    
    function insertAtCursor(element) {
        const selection = window.getSelection();
        
        if (selection.rangeCount > 0) {
            const range = selection.getRangeAt(0);
            range.deleteContents();
            range.insertNode(element);
            
            // Try to focus on content area
            const contentArea = element.querySelector('.tag-content, .list-item-content, [contenteditable="true"]:not(.tag-label)');
            if (contentArea) {
                placeCaretAtEnd(contentArea);
            }
        } else {
            editor.appendChild(element);
        }
        
        updateCounts();
    }

    // ==========================================================================
    // 8) IMAGE UPLOAD AND INSERT
    // ==========================================================================
    
    async function handleImageUpload(file) {
        if (!file.type.startsWith('image/')) {
            alert('Please select an image file.');
            return;
        }

        if (file.size > MAX_FILE_SIZE) {
            alert('Image size exceeds 5MB limit.');
            return;
        }

        const localUrl = URL.createObjectURL(file);
        
        const wrapper = document.createElement('div');
        wrapper.className = 'tag-wrapper image-figure';
        wrapper.setAttribute('data-tag', 'figure');
        
        const openTag = document.createElement('span');
        openTag.className = 'tag-label';
        openTag.textContent = '<figure>';
        
        const img = document.createElement('img');
        img.src = localUrl;
        img.alt = file.name;
        
        const caption = document.createElement('div');
        caption.className = 'image-caption';
        caption.contentEditable = 'true';
        caption.innerHTML = '';
        
        const closeTag = document.createElement('span');
        closeTag.className = 'tag-label';
        closeTag.textContent = '</figure>';
        
        const deleteBtn = document.createElement('div');
        deleteBtn.className = 'delete-btn';
        deleteBtn.innerHTML = '×';
        deleteBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            wrapper.remove();
            updateCounts();
        });
        
        wrapper.appendChild(deleteBtn);
        wrapper.appendChild(openTag);
        wrapper.appendChild(img);
        wrapper.appendChild(caption);
        wrapper.appendChild(closeTag);
        
        insertAtCursor(wrapper);
        updateCounts();
        
        // Upload to server
        const formData = new FormData();
        formData.append('image', file);
        
        try {
            const response = await fetch(uploadUrl, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken },
                body: formData
            });
            const data = await response.json();
            if (data.success) {
                img.src = data.url;
            }
        } catch (error) {
            console.error('Upload failed:', error);
        }
    }

    // ==========================================================================
    // 9) PLACE CARET AT END
    // ==========================================================================
    
    function placeCaretAtEnd(el) {
        el.focus();
        const range = document.createRange();
        range.selectNodeContents(el);
        range.collapse(false);
        const sel = window.getSelection();
        sel.removeAllRanges();
        sel.addRange(range);
    }

    // ==========================================================================
    // 10) UPDATE COUNTS
    // ==========================================================================
    
    function updateCounts() {
        const text = editor.innerText || editor.textContent;
        const words = text.trim().split(/\s+/).filter(w => w.length > 0).length;
        wordCountSpan.textContent = words;
        
        const images = editor.querySelectorAll('img').length;
        imageCountSpan.textContent = images;
    }

    // ==========================================================================
    // 11) SETUP TOOLBAR
    // ==========================================================================
    
    function setupToolbar() {
        // All tag buttons
        document.querySelectorAll('.bottom-toolbar button[data-tag]').forEach(btn => {
            btn.addEventListener('click', function() {
                const tag = this.dataset.tag;
                
                if (tag === 'ul' || tag === 'ol') {
                    const wrapper = createListWrapper(tag);
                    insertAtCursor(wrapper);
                } else if (tag === 'hr') {
                    const hr = document.createElement('hr');
                    insertAtCursor(hr);
                } else {
                    const wrapper = createTagWrapper(tag);
                    insertAtCursor(wrapper);
                }
            });
        });

        // Image button
        insertImageBtn.addEventListener('click', () => imageInput.click());
        imageInput.addEventListener('change', async function() {
            if (this.files && this.files[0]) {
                await handleImageUpload(this.files[0]);
                this.value = '';
            }
        });

        // Link button
        document.getElementById('insert-link')?.addEventListener('click', function() {
            const url = prompt('Enter URL:');
            if (url) {
                const link = createTagWrapper('a', url);
                insertAtCursor(link);
            }
        });
    }

    // ==========================================================================
    // 12) DRAG & DROP
    // ==========================================================================
    
    function setupDragDrop() {
        editor.addEventListener('dragover', (e) => {
            e.preventDefault();
            editor.classList.add('drag-over');
        });

        editor.addEventListener('dragleave', () => {
            editor.classList.remove('drag-over');
        });

        editor.addEventListener('drop', async (e) => {
            e.preventDefault();
            editor.classList.remove('drag-over');
            
            const files = e.dataTransfer.files;
            if (files.length) {
                const range = document.caretRangeFromPoint(e.clientX, e.clientY);
                if (range) {
                    const sel = window.getSelection();
                    sel.removeAllRanges();
                    sel.addRange(range);
                }
                
                for (let file of files) {
                    if (file.type.startsWith('image/')) {
                        await handleImageUpload(file);
                    }
                }
            }
        });
    }

    // ==========================================================================
    // 13) ENTER KEY - NORMAL BEHAVIOR
    // ==========================================================================
    
    function setupEnterKey() {
        editor.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                // Allow normal Enter behavior - no interference
                return true;
            }
        });
    }

    // ==========================================================================
    // 14) FORM SUBMIT
    // ==========================================================================
    
    function setupFormSubmit() {
        blogForm.addEventListener('submit', function(e) {
            contentField.value = editor.innerHTML;
            return true;
        });
    }

    // ==========================================================================
    // 15) INITIALIZATION
    // ==========================================================================
    
    function init() {
        // Set initial content
        if (!editor.innerHTML.trim()) {
            const initialP = createTagWrapper('p', 'Start writing your safari adventure...');
            editor.appendChild(initialP);
        }
        
        // Setup all systems
        setupThemeSwitcher();
        setupToolbar();
        setupDragDrop();
        setupEnterKey();
        setupDoubleClickExit(); // CRITICAL: Double click to exit
        setupFormSubmit();
        
        // Counters
        updateCounts();
        editor.addEventListener('input', updateCounts);
        
        // Title to slug
        titleInput.addEventListener('input', function() {
            const slug = this.value.toLowerCase()
                .replace(/[^\w\s-]/g, '')
                .replace(/[\s_-]+/g, '-')
                .replace(/^-+|-+$/g, '');
            slugPreview.textContent = slug || 'your-post-slug';
        });
        
        // Status toggle
        const statusSelect = document.getElementById('status');
        const scheduledDatetime = document.getElementById('scheduled-datetime');
        statusSelect.addEventListener('change', function() {
            scheduledDatetime.classList.toggle('hidden', this.value !== 'scheduled');
        });
        
        // Featured image
        const featuredInput = document.getElementById('featured_image');
        const featuredPreview = document.getElementById('featured-preview');
        const featuredPreviewContainer = document.getElementById('featured-preview-container');
        const removeFeaturedBtn = document.getElementById('remove-featured');
        
        featuredInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    featuredPreview.src = e.target.result;
                    featuredPreviewContainer.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        });
        
        if (removeFeaturedBtn) {
            removeFeaturedBtn.addEventListener('click', function() {
                featuredInput.value = '';
                featuredPreviewContainer.classList.add('hidden');
                featuredPreview.src = '';
            });
        }
        
        // SEO toggle
        const seoToggle = document.getElementById('seo-toggle');
        const seoPanel = document.getElementById('seo-panel');
        const seoChevron = document.getElementById('seo-chevron');
        
        if (seoToggle) {
            seoToggle.addEventListener('click', function() {
                seoPanel.classList.toggle('hidden');
                seoChevron.classList.toggle('rotate-180');
            });
        }
        
        // Save draft
        const saveDraftBtn = document.getElementById('save-draft-btn');
        if (saveDraftBtn) {
            saveDraftBtn.addEventListener('click', function() {
                document.getElementById('status').value = 'draft';
                blogForm.submit();
            });
        }
    }

    // Start everything
    init();
})();
</script>
@endsection