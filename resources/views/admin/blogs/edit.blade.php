{{-- resources/views/admin/blogs/edit.blade.php --}}
@extends('layouts.admin')

@section('title', 'Edit Safari Story - Advanced Editor')

@section('content')
<div class="min-h-screen bg-slate-900 py-8" data-theme="dark" id="app-container">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" style="padding-bottom: 180px;">
        
        {{-- Header --}}
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center space-x-3">
                <div class="p-2 bg-gradient-to-br from-indigo-600 to-indigo-500 rounded-lg shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-white">Edit Safari Story</h1>
                    <p class="text-sm text-slate-400 mt-1">✏️ Editing: {{ $blog->title }}</p>
                </div>
            </div>
            <div class="mt-4 sm:mt-0 flex space-x-3">
                <button type="button" id="toggle-preview" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    Preview
                </button>
                <a href="{{ route('admin.blogs.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-700 text-white border border-slate-600 rounded-lg hover:bg-slate-600 transition-colors shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back
                </a>
            </div>
        </div>

        {{-- Error Alert --}}
        @if ($errors->any())
        <div class="mb-6 bg-rose-900 bg-opacity-30 border-l-4 border-rose-500 rounded-r-lg shadow-md">
            <div class="flex items-start p-4">
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 text-rose-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-rose-300">Please fix the following errors:</p>
                    <ul class="mt-2 text-sm text-rose-200 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif

        <form id="blog-form" action="{{ route('admin.blogs.update', $blog) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                {{-- MAIN CONTENT COLUMN --}}
                <div class="lg:col-span-2 space-y-5">
                    
                    {{-- Title --}}
                    <div class="bg-slate-800 rounded-xl shadow-sm border border-slate-700 p-5">
                        <label for="title" class="block text-sm font-medium text-slate-300 mb-1">
                            Story Title <span class="text-rose-400">*</span>
                        </label>
                        <input 
                            id="title" 
                            name="title" 
                            value="{{ old('title', $blog->title) }}" 
                            class="mt-1 block w-full bg-slate-900 border border-slate-600 text-white rounded-lg shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 text-lg font-medium px-4 py-3" 
                            placeholder="e.g., The Great Migration"
                            required
                        >
                        <div class="mt-2 flex items-center text-sm text-slate-400">
                            <span class="font-mono bg-slate-900 px-2 py-1 rounded">/blog/</span>
                            <span id="slug-preview" class="font-mono text-indigo-400 ml-1 bg-slate-900 px-2 py-1 rounded">{{ old('slug', $blog->slug) }}</span>
                        </div>
                    </div>

                    {{-- Excerpt --}}
                    <div class="bg-slate-800 rounded-xl shadow-sm border border-slate-700 p-5">
                        <label for="excerpt" class="block text-sm font-medium text-slate-300 mb-1">Excerpt / Summary</label>
                        <textarea 
                            id="excerpt" 
                            name="excerpt" 
                            rows="2" 
                            class="mt-1 block w-full bg-slate-900 border border-slate-600 text-white rounded-lg shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 px-4 py-3"
                            placeholder="A brief summary...">{{ old('excerpt', $blog->excerpt) }}</textarea>
                    </div>

                    {{-- EDITOR --}}
                    <div class="bg-slate-800 rounded-xl shadow-sm border border-slate-700 overflow-hidden">
                        <div class="px-4 py-3 bg-slate-750 border-b border-slate-700 flex items-center justify-between">
                            <div class="flex items-center space-x-2 text-xs text-slate-400">
                                <span class="px-2 py-1 bg-slate-900 rounded font-mono">&lt;/&gt;</span>
                                <span>Nested Tag Editor • Click INSIDE any DIV to insert inside it</span>
                            </div>
                            <div class="text-xs text-slate-400">
                                <span id="word-count">0</span> words • 
                                <span id="image-count">0</span> images
                            </div>
                        </div>
                        
                        {{-- EDITOR CONTENT AREA --}}
                        <div id="editor" class="min-h-[520px] p-6 font-mono text-sm bg-slate-900 text-slate-200 overflow-auto" contenteditable="true">
                            {!! old('content', $blog->content) !!}
                        </div>
                        
                        {{-- Hidden field for form submission --}}
                        <textarea name="content" id="content" class="hidden">{{ old('content', $blog->content) }}</textarea>
                    </div>

                    {{-- SEO & META DATA --}}
                    <div class="bg-slate-800 rounded-xl shadow-sm border border-slate-700 overflow-hidden">
                        <div class="px-5 py-4 bg-slate-750 border-b border-slate-700 flex items-center justify-between cursor-pointer" id="seo-toggle">
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                </svg>
                                <h3 class="text-sm font-medium text-white">SEO & Meta Data</h3>
                                <span class="text-xs bg-green-900 text-green-300 px-2 py-1 rounded-full">Auto-generated</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button type="button" id="regenerate-meta" class="text-xs bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded transition">
                                    Regenerate
                                </button>
                                <svg class="w-5 h-5 text-slate-400 transform transition-transform" id="seo-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                        <div id="seo-panel" class="p-5 space-y-4 hidden">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="meta_title" class="block text-xs font-medium text-slate-400 mb-1">Meta Title</label>
                                    <input id="meta_title" name="meta_title" value="{{ old('meta_title', $blog->meta_title) }}" class="block w-full bg-slate-900 border border-slate-600 text-white rounded-lg shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 text-sm px-3 py-2">
                                    <p class="text-xs text-slate-500 mt-1"><span id="meta-title-length">0</span>/60 characters</p>
                                </div>
                                <div>
                                    <label for="meta_keywords" class="block text-xs font-medium text-slate-400 mb-1">Meta Keywords</label>
                                    <input id="meta_keywords" name="meta_keywords" value="{{ old('meta_keywords', $blog->meta_keywords) }}" class="block w-full bg-slate-900 border border-slate-600 text-white rounded-lg shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 text-sm px-3 py-2" placeholder="safari, wildlife, kenya">
                                </div>
                            </div>
                            <div>
                                <label for="meta_description" class="block text-xs font-medium text-slate-400 mb-1">Meta Description</label>
                                <textarea id="meta_description" name="meta_description" rows="2" class="block w-full bg-slate-900 border border-slate-600 text-white rounded-lg shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 text-sm px-3 py-2">{{ old('meta_description', $blog->meta_description) }}</textarea>
                                <p class="text-xs text-slate-500 mt-1"><span id="meta-desc-length">0</span>/160 characters</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SIDEBAR --}}
                <div class="space-y-5">
                    <div class="bg-slate-800 rounded-xl shadow-sm border border-slate-700 overflow-hidden sticky top-6">
                        <div class="px-5 py-4 bg-slate-750 border-b border-slate-700">
                            <h3 class="text-sm font-semibold text-white flex items-center">
                                <svg class="w-4 h-4 mr-1.5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M12 5l7 7-7 7"></path>
                                </svg>
                                Publish Settings
                            </h3>
                        </div>

                        <div class="p-5 space-y-4">
                            {{-- Author Name --}}
                            <div>
                                <label for="author_name" class="block text-xs font-medium text-slate-400 uppercase tracking-wider mb-1">Author Name</label>
                                <input type="text" id="author_name" name="author_name" value="{{ old('author_name', $blog->author_name) }}" class="mt-1 block w-full bg-slate-900 border border-slate-600 text-white rounded-lg shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 px-3 py-2">
                            </div>

                            {{-- Category --}}
                            <div>
                                <label for="category_id" class="block text-xs font-medium text-slate-400 uppercase tracking-wider mb-1">Category</label>
                                <select id="category_id" name="category_id" class="mt-1 block w-full bg-slate-900 border border-slate-600 text-white rounded-lg shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 px-3 py-2">
                                    <option value="">— Select Category —</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('category_id', $blog->category_id) == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Featured Image --}}
                            <div>
                                <label class="block text-xs font-medium text-slate-400 uppercase tracking-wider mb-1">Featured Image</label>
                                
                                {{-- Current Featured Image Preview --}}
                                @if($blog->featured_image)
                                <div class="mb-3">
                                    <div class="relative rounded-lg overflow-hidden border border-slate-600 group">
                                        <img src="{{ asset('storage/' . $blog->featured_image) }}" alt="Current featured image" class="w-full h-32 object-cover">
                                        <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                                            <span class="text-xs text-white bg-slate-900 px-2 py-1 rounded">Current Image</span>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                
                                <div class="mt-1 flex justify-center px-4 pt-4 pb-4 border-2 border-dashed border-slate-600 rounded-lg hover:border-indigo-500 transition-colors cursor-pointer" id="featured-drop-zone">
                                    <div class="space-y-1 text-center">
                                        <svg class="mx-auto h-10 w-10 text-slate-500" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H8a4 4 0 01-4-4v-8m32 0l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex text-sm text-slate-400">
                                            <label for="featured_image" class="relative cursor-pointer rounded-md font-medium text-indigo-400 hover:text-indigo-300 px-3 py-1">
                                                <span>{{ $blog->featured_image ? 'Replace Image' : 'Upload Image' }}</span>
                                                <input id="featured_image" name="featured_image" type="file" class="sr-only" accept="image/*">
                                            </label>
                                        </div>
                                        <p class="text-xs text-slate-500">PNG, JPG, WebP up to 5MB</p>
                                    </div>
                                </div>
                                <div id="featured-preview-container" class="mt-3 hidden">
                                    <div class="relative rounded-lg overflow-hidden border border-slate-600 group">
                                        <img id="featured-preview" src="" alt="Featured preview" class="w-full h-32 object-cover">
                                        <button type="button" id="remove-featured" class="absolute top-1 right-1 p-1 bg-rose-600 hover:bg-rose-700 rounded-full opacity-0 group-hover:opacity-100 transition">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            {{-- Status --}}
                            <div>
                                <label for="status" class="block text-xs font-medium text-slate-400 uppercase tracking-wider mb-1">Status</label>
                                <select id="status" name="status" class="mt-1 block w-full bg-slate-900 border border-slate-600 text-white rounded-lg shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 px-3 py-2">
                                    <option value="draft" {{ old('status', $blog->status) === 'draft' ? 'selected' : '' }}>📝 Draft</option>
                                    <option value="published" {{ old('status', $blog->status) === 'published' ? 'selected' : '' }}>🚀 Published</option>
                                    <option value="scheduled" {{ old('status', $blog->status) === 'scheduled' ? 'selected' : '' }}>⏰ Scheduled</option>
                                </select>
                            </div>

                            <div id="scheduled-datetime" class="{{ old('status', $blog->status) === 'scheduled' ? '' : 'hidden' }}">
                                <label for="published_at" class="block text-xs font-medium text-slate-400 uppercase tracking-wider mb-1">Schedule Date & Time</label>
                                <input id="published_at" name="published_at" value="{{ old('published_at', $blog->published_at ? $blog->published_at->format('Y-m-d\TH:i') : '') }}" class="mt-1 block w-full bg-slate-900 border border-slate-600 text-white rounded-lg shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 px-3 py-2" type="datetime-local">
                            </div>

                            {{-- Tags --}}
                            <div>
                                <label for="tags" class="block text-xs font-medium text-slate-400 uppercase tracking-wider mb-1">Tags</label>
                                <input id="tags" name="tags" value="{{ old('tags', $blog->tags) }}" class="mt-1 block w-full bg-slate-900 border border-slate-600 text-white rounded-lg shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 px-3 py-2" placeholder="safari, wildlife, kenya">
                            </div>

                            {{-- Featured Toggle --}}
                            <div class="flex items-center justify-between p-3 bg-yellow-900 bg-opacity-20 rounded-lg border border-yellow-700">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-5 h-5 text-yellow-400" fill="{{ old('is_featured', $blog->is_featured) ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                    </svg>
                                    <span class="text-sm font-medium text-white">Feature this post</span>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="hidden" name="is_featured" value="0">
                                    <input type="checkbox" id="is_featured" name="is_featured" class="sr-only peer" value="1" {{ old('is_featured', $blog->is_featured) ? 'checked' : '' }}>
                                    <div class="w-10 h-5 bg-slate-600 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-yellow-800 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-yellow-500"></div>
                                </label>
                            </div>

                            {{-- Submit Buttons --}}
                            <div class="space-y-2 pt-2">
                                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-3 bg-gradient-to-r from-indigo-600 to-indigo-500 text-white font-medium rounded-lg hover:from-indigo-700 hover:to-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all shadow-md">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Update Story
                                </button>
                                <a href="{{ route('blogs.show', $blog->slug) }}" target="_blank" class="w-full inline-flex justify-center items-center px-4 py-2 bg-slate-700 border border-slate-600 text-white font-medium rounded-lg hover:bg-slate-600 transition-colors">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    View Live
                                </a>
                                <button type="button" id="save-draft-btn" class="w-full inline-flex justify-center items-center px-4 py-2 bg-slate-700 border border-slate-600 text-white font-medium rounded-lg hover:bg-slate-600 transition-colors">
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
<div class="bottom-toolbar fixed bottom-0 left-0 right-0 bg-slate-800 border-t-2 border-slate-700 px-5 py-3 z-50 shadow-2xl flex flex-wrap gap-2 items-center max-h-40 overflow-y-auto">
    
    {{-- Theme Switcher --}}
    <div class="flex items-center gap-2 bg-slate-900 px-3 py-2 rounded-lg">
        <span class="text-xs text-slate-400 mr-1">Theme:</span>
        <button type="button" class="theme-btn active w-7 h-7 rounded-full border-2" data-theme="dark" title="Dark" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); border-color: #3b82f6;"></button>
        <button type="button" class="theme-btn w-7 h-7 rounded-full border-2" data-theme="light" title="Light" style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);"></button>
        <button type="button" class="theme-btn w-7 h-7 rounded-full border-2" data-theme="blue" title="Blue" style="background: linear-gradient(135deg, #0c4a6e 0%, #0284c7 100%);"></button>
        <button type="button" class="theme-btn w-7 h-7 rounded-full border-2" data-theme="green" title="Green" style="background: linear-gradient(135deg, #064e3b 0%, #059669 100%);"></button>
        <button type="button" class="theme-btn w-7 h-7 rounded-full border-2" data-theme="purple" title="Purple" style="background: linear-gradient(135deg, #3b0764 0%, #7e22ce 100%);"></button>
    </div>

    <div class="w-px h-8 bg-slate-600"></div>

    {{-- Block Elements --}}
    <div class="flex gap-1 bg-slate-900 px-2 py-1 rounded-lg">
        <button type="button" class="toolbar-btn" data-tag="h1">H1</button>
        <button type="button" class="toolbar-btn" data-tag="h2">H2</button>
        <button type="button" class="toolbar-btn" data-tag="h3">H3</button>
        <button type="button" class="toolbar-btn" data-tag="h4">H4</button>
        <button type="button" class="toolbar-btn" data-tag="h5">H5</button>
        <button type="button" class="toolbar-btn" data-tag="h6">H6</button>
        <button type="button" class="toolbar-btn" data-tag="p">P</button>
        <button type="button" class="toolbar-btn" data-tag="div">DIV</button>
    </div>

    <div class="w-px h-8 bg-slate-600"></div>

    {{-- Lists & Quotes --}}
    <div class="flex gap-1 bg-slate-900 px-2 py-1 rounded-lg">
        <button type="button" class="toolbar-btn" data-tag="ul">UL</button>
        <button type="button" class="toolbar-btn" data-tag="ol">OL</button>
        <button type="button" class="toolbar-btn" data-tag="blockquote">QUOTE</button>
    </div>

    <div class="w-px h-8 bg-slate-600"></div>

    {{-- TABLE TAGS --}}
    <div class="flex gap-1 bg-slate-900 px-2 py-1 rounded-lg">
        <button type="button" class="toolbar-btn" data-tag="table">TABLE</button>
        <button type="button" class="toolbar-btn" data-tag="thead">THEAD</button>
        <button type="button" class="toolbar-btn" data-tag="tbody">TBODY</button>
        <button type="button" class="toolbar-btn" data-tag="tr">TR</button>
        <button type="button" class="toolbar-btn" data-tag="th">TH</button>
        <button type="button" class="toolbar-btn" data-tag="td">TD</button>
    </div>

    <div class="w-px h-8 bg-slate-600"></div>

    {{-- Text Formatting --}}
    <div class="flex gap-1 bg-slate-900 px-2 py-1 rounded-lg">
        <button type="button" class="toolbar-btn" data-tag="strong">STRONG</button>
        <button type="button" class="toolbar-btn" data-tag="em">EM</button>
        <button type="button" class="toolbar-btn" data-tag="u">U</button>
        <button type="button" class="toolbar-btn" data-tag="mark">MARK</button>
        <button type="button" class="toolbar-btn" data-tag="small">SMALL</button>
        <button type="button" class="toolbar-btn" data-tag="del">DEL</button>
        <button type="button" class="toolbar-btn" data-tag="ins">INS</button>
    </div>

    <div class="w-px h-8 bg-slate-600"></div>

    {{-- Code & Pre --}}
    <div class="flex gap-1 bg-slate-900 px-2 py-1 rounded-lg">
        <button type="button" class="toolbar-btn" data-tag="code">CODE</button>
        <button type="button" class="toolbar-btn" data-tag="pre">PRE</button>
        <button type="button" class="toolbar-btn" data-tag="kbd">KBD</button>
        <button type="button" class="toolbar-btn" data-tag="samp">SAMP</button>
    </div>

    <div class="w-px h-8 bg-slate-600"></div>

    {{-- Semantic Elements --}}
    <div class="flex gap-1 bg-slate-900 px-2 py-1 rounded-lg">
        <button type="button" class="toolbar-btn" data-tag="article">ARTICLE</button>
        <button type="button" class="toolbar-btn" data-tag="section">SECTION</button>
        <button type="button" class="toolbar-btn" data-tag="aside">ASIDE</button>
        <button type="button" class="toolbar-btn" data-tag="header">HEADER</button>
        <button type="button" class="toolbar-btn" data-tag="footer">FOOTER</button>
    </div>

    <div class="w-px h-8 bg-slate-600"></div>

    {{-- Media & Links --}}
    <div class="flex gap-1 bg-slate-900 px-2 py-1 rounded-lg">
        <input type="file" id="image-input" accept="image/*" class="hidden">
        <button type="button" class="toolbar-btn" id="insert-image">IMG</button>
        <button type="button" class="toolbar-btn" id="insert-link">A</button>
        <button type="button" class="toolbar-btn" data-tag="span">SPAN</button>
        <button type="button" class="toolbar-btn" data-tag="hr">HR</button>
    </div>
</div>

{{-- PREVIEW MODAL --}}
<div id="preview-modal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="preview-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-slate-900 bg-opacity-90" aria-hidden="true" id="preview-overlay"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block w-full max-w-4xl px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:p-6">
            <div class="absolute top-0 right-0 pt-4 pr-4">
                <button type="button" id="close-preview" class="text-slate-400 bg-white rounded-md hover:text-slate-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <span class="sr-only">Close</span>
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="sm:flex sm:items-start">
                <div class="w-full mt-3 text-center sm:mt-0 sm:text-left">
                    <h3 class="text-lg font-medium leading-6 text-slate-900 mb-4" id="preview-title">
                        📰 Live Preview
                    </h3>
                    <div class="mt-4 border-t border-slate-200 pt-4">
                        <h1 id="preview-blog-title" class="text-3xl font-bold text-slate-900 mb-2"></h1>
                        <div class="flex items-center space-x-4 text-sm text-slate-600 mb-4 pb-4 border-b border-slate-200">
                            <span id="preview-author" class="flex items-center"></span>
                            <span id="preview-category" class="flex items-center"></span>
                            <span id="preview-date" class="flex items-center"></span>
                        </div>
                        <div id="preview-featured-image-container" class="mb-6 hidden">
                            <img id="preview-featured-image" src="" alt="" class="w-full rounded-lg shadow-lg">
                        </div>
                        <p id="preview-excerpt" class="text-lg text-slate-700 italic mb-6 pb-6 border-b border-slate-200"></p>
                        <div id="preview-content" class="prose prose-slate prose-lg max-w-none"></div>
                        <div id="preview-tags-container" class="mt-6 pt-6 border-t border-slate-200 hidden">
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                                <div id="preview-tags" class="flex flex-wrap gap-2"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
/* Toolbar button styles */
.toolbar-btn {
    @apply px-3 py-1 text-xs font-mono bg-slate-700 text-slate-300 border border-slate-600 rounded hover:bg-indigo-600 hover:text-white hover:border-indigo-500 transition-all cursor-pointer;
}
.toolbar-btn.active {
    @apply bg-indigo-600 text-white border-indigo-500;
}
.theme-btn:hover {
    @apply scale-110 border-indigo-500;
}
.theme-btn.active {
    @apply border-indigo-500 ring-2 ring-indigo-500 ring-offset-2 ring-offset-slate-800;
}

/* THEME SYSTEM - Copy all your existing theme CSS here */
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
    --tag-table: #f97316;
}

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
    --tag-table: #ea580c;
}

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
    --tag-table: #fdba74;
}

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
    --tag-table: #fde68a;
}

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
    --tag-table: #fcd34d;
}

/* Tag Wrapper Styles - Copy all your existing tag wrapper CSS here */
.tag-wrapper {
    display: block;
    position: relative;
    margin: 8px 0;
    padding: 12px;
    border: 1px solid transparent;
    border-radius: 8px;
    transition: all 0.2s ease;
    background-color: var(--bg-tag);
}

.tag-wrapper:hover {
    border-color: var(--border-primary);
    opacity: 0.95;
}

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
    cursor: default;
}

.tag-wrapper[data-tag="h1"] .tag-label { color: var(--tag-h1) !important; border-left: 3px solid var(--tag-h1); }
.tag-wrapper[data-tag="h2"] .tag-label { color: var(--tag-h2) !important; border-left: 3px solid var(--tag-h2); }
.tag-wrapper[data-tag="h3"] .tag-label { color: var(--tag-h3) !important; border-left: 3px solid var(--tag-h3); }
.tag-wrapper[data-tag="h4"] .tag-label { color: var(--tag-h3) !important; border-left: 3px solid var(--tag-h3); }
.tag-wrapper[data-tag="h5"] .tag-label { color: var(--tag-h3) !important; border-left: 3px solid var(--tag-h3); }
.tag-wrapper[data-tag="h6"] .tag-label { color: var(--tag-h3) !important; border-left: 3px solid var(--tag-h3); }
.tag-wrapper[data-tag="p"] .tag-label { color: var(--tag-p) !important; border-left: 3px solid var(--tag-p); }
.tag-wrapper[data-tag="li"] .tag-label { color: var(--tag-li) !important; border-left: 3px solid var(--tag-li); }
.tag-wrapper[data-tag="figure"] .tag-label { color: var(--tag-figure) !important; border-left: 3px solid var(--tag-figure); }
.tag-wrapper[data-tag="div"] .tag-label { color: var(--tag-div) !important; border-left: 3px solid var(--tag-div); }
.tag-wrapper[data-tag="table"] .tag-label,
.tag-wrapper[data-tag="thead"] .tag-label,
.tag-wrapper[data-tag="tbody"] .tag-label,
.tag-wrapper[data-tag="tr"] .tag-label,
.tag-wrapper[data-tag="th"] .tag-label,
.tag-wrapper[data-tag="td"] .tag-label { 
    color: var(--tag-table) !important; 
    border-left: 3px solid var(--tag-table); 
}

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
    cursor: text;
}

.tag-content:empty::before {
    content: 'Type here...';
    color: var(--text-muted);
    font-style: italic;
}

.tag-content:focus {
    background-color: var(--bg-input);
    border: 1px solid var(--border-primary);
}

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
    display: inline;
    min-width: 20px;
}

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
    max-width: 560px;
    max-height: 360px;
    width: auto;
    height: auto;
    border-radius: 6px;
    border: 1px solid var(--border-primary);
    display: block;
    margin: 12px 0;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    object-fit: contain;
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

.table-wrapper {
    overflow-x: auto;
    margin: 16px 0;
    border: 1px solid var(--border-primary);
    border-radius: 8px;
    background-color: var(--bg-tag);
    padding: 12px;
}

.table-wrapper table {
    width: 100%;
    border-collapse: collapse;
}

.table-wrapper th,
.table-wrapper td {
    border: 1px solid var(--border-primary);
    padding: 8px 12px;
    min-width: 100px;
}

.table-wrapper th {
    background-color: var(--bg-tertiary);
    font-weight: 600;
    color: var(--text-primary);
}

.table-wrapper td {
    background-color: var(--bg-editor);
    color: var(--text-primary);
}

.table-add-row,
.table-add-col {
    display: inline-block;
    font-size: 11px;
    padding: 4px 10px;
    background-color: var(--accent-success);
    color: white;
    border-radius: 4px;
    cursor: pointer;
    margin: 4px 2px;
    border: none;
}

.table-add-row:hover,
.table-add-col:hover {
    background-color: var(--accent-secondary);
}

.drag-over {
    position: relative;
    outline: 2px dashed var(--accent-primary);
    outline-offset: 2px;
}

.drag-over::after {
    content: '⬇️ DROP IMAGE HERE ⬇️';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: var(--accent-primary);
    opacity: 0.9;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    font-weight: 600;
    padding: 1rem 2rem;
    border-radius: 0.5rem;
    pointer-events: none;
    z-index: 1000;
}

/* Preview content styles */
#preview-content h1 { @apply text-3xl font-bold text-slate-900 mt-8 mb-4; }
#preview-content h2 { @apply text-2xl font-bold text-slate-800 mt-6 mb-3; }
#preview-content h3 { @apply text-xl font-bold text-slate-800 mt-5 mb-2; }
#preview-content h4 { @apply text-lg font-semibold text-slate-700 mt-4 mb-2; }
#preview-content h5 { @apply text-base font-semibold text-slate-700 mt-3 mb-2; }
#preview-content h6 { @apply text-sm font-semibold text-slate-700 mt-2 mb-1; }
#preview-content p { @apply text-slate-700 leading-relaxed mb-4; }
#preview-content ul { @apply list-disc list-inside mb-4 text-slate-700; }
#preview-content ol { @apply list-decimal list-inside mb-4 text-slate-700; }
#preview-content blockquote { @apply border-l-4 border-indigo-500 pl-4 italic text-slate-600 my-4; }
#preview-content img { @apply rounded-lg shadow-md my-4 max-w-full h-auto; }
#preview-content table { @apply w-full border-collapse border border-slate-300 my-4; }
#preview-content th { @apply bg-slate-100 border border-slate-300 px-4 py-2 text-left font-semibold; }
#preview-content td { @apply border border-slate-300 px-4 py-2; }
#preview-content code { @apply bg-slate-100 text-rose-600 px-1 py-0.5 rounded text-sm font-mono; }
#preview-content pre { @apply bg-slate-900 text-slate-100 p-4 rounded-lg overflow-x-auto my-4; }
#preview-content strong { @apply font-bold text-slate-900; }
#preview-content em { @apply italic; }
#preview-content a { @apply text-indigo-600 hover:text-indigo-800 underline; }
</style>
@endpush

@push('scripts')
<script>
(function() {
    'use strict';

    console.log('🚀 Editor initializing for EDIT mode...');

    // ==========================================================================
    // ELEMENTS & CONFIG
    // ==========================================================================
    const editor = document.getElementById('editor');
    const contentField = document.getElementById('content');
    const blogForm = document.getElementById('blog-form');
    const titleInput = document.getElementById('title');
    const excerptInput = document.getElementById('excerpt');
    const slugPreview = document.getElementById('slug-preview');
    const imageInput = document.getElementById('image-input');
    const insertImageBtn = document.getElementById('insert-image');
    const insertLinkBtn = document.getElementById('insert-link');
    const wordCountSpan = document.getElementById('word-count');
    const imageCountSpan = document.getElementById('image-count');
    const blogId = {{ $blog->id }};

    const csrfToken = '{{ csrf_token() }}';
    const uploadUrl = "{{ route('admin.blogs.uploadImage') }}";
    const MAX_FILE_SIZE = 5 * 1024 * 1024;
    
    const AUTOSAVE_KEY = 'safari_blog_autosave_edit_' + blogId;
    const AUTOSAVE_INTERVAL = 5000;

    let autosaveTimer = null;

    // Verify elements exist
    if (!editor) {
        console.error('❌ Editor element not found!');
        return;
    }

    console.log('✅ All required elements found');

    // ==========================================================================
    // 1) THEME SWITCHER
    // ==========================================================================
    function setupThemeSwitcher() {
        const themeButtons = document.querySelectorAll('.theme-btn');
        
        function applyTheme(theme) {
            document.documentElement.setAttribute('data-theme', theme);
            document.body.setAttribute('data-theme', theme);
            const container = document.getElementById('app-container');
            if (container) container.setAttribute('data-theme', theme);
            if (editor) editor.setAttribute('data-theme', theme);
            localStorage.setItem('editorTheme', theme);
            console.log(`🎨 Theme changed to: ${theme}`);
        }
        
        themeButtons.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const theme = this.dataset.theme;
                themeButtons.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                applyTheme(theme);
            });
        });
        
        const savedTheme = localStorage.getItem('editorTheme') || 'dark';
        const savedBtn = document.querySelector(`.theme-btn[data-theme="${savedTheme}"]`);
        if (savedBtn) {
            savedBtn.click();
        } else {
            applyTheme('dark');
        }
    }

    // ==========================================================================
    // 2) CREATE TAG WRAPPER - Copy ALL functions from your create.blade.php
    // ==========================================================================
    function createTagWrapper(tagName, initialContent = '') {
        const isInline = ['strong', 'em', 'u', 'mark', 'small', 'del', 'ins', 'code', 'kbd', 'samp', 'span', 'a'].includes(tagName);
        if (isInline) return createInlineWrapper(tagName, initialContent);
        if (tagName === 'table') return createTableWrapper();

        const wrapper = document.createElement('div');
        wrapper.className = 'tag-wrapper';
        wrapper.setAttribute('data-tag', tagName);
        wrapper.setAttribute('contenteditable', 'false');
        
        const openTag = document.createElement('span');
        openTag.className = 'tag-label';
        openTag.textContent = `<${tagName}>`;
        openTag.setAttribute('contenteditable', 'false');
        
        const content = document.createElement('div');
        content.className = 'tag-content';
        content.contentEditable = 'true';
        content.innerHTML = initialContent || '';
        content.setAttribute('data-placeholder', 'Type here...');
        
        const closeTag = document.createElement('span');
        closeTag.className = 'tag-label';
        closeTag.textContent = `</${tagName}>`;
        closeTag.setAttribute('contenteditable', 'false');
        
        const deleteBtn = document.createElement('div');
        deleteBtn.className = 'delete-btn';
        deleteBtn.innerHTML = '×';
        deleteBtn.setAttribute('contenteditable', 'false');
        deleteBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            wrapper.remove();
            updateCounts();
            triggerAutosave();
        });
        
        wrapper.appendChild(deleteBtn);
        wrapper.appendChild(openTag);
        wrapper.appendChild(content);
        wrapper.appendChild(closeTag);
        
        return wrapper;
    }

    // ==========================================================================
    // 3) CREATE TABLE WRAPPER
    // ==========================================================================
    function createTableWrapper() {
        const wrapper = document.createElement('div');
        wrapper.className = 'tag-wrapper table-wrapper';
        wrapper.setAttribute('data-tag', 'table');
        wrapper.setAttribute('contenteditable', 'false');
        
        const openTag = document.createElement('span');
        openTag.className = 'tag-label';
        openTag.textContent = '<table>';
        openTag.setAttribute('contenteditable', 'false');
        
        const table = document.createElement('table');
        table.setAttribute('contenteditable', 'false');
        
        const thead = document.createElement('thead');
        const headerRow = document.createElement('tr');
        for (let i = 0; i < 2; i++) {
            const th = document.createElement('th');
            th.contentEditable = 'true';
            th.textContent = `Header ${i + 1}`;
            headerRow.appendChild(th);
        }
        thead.appendChild(headerRow);
        
        const tbody = document.createElement('tbody');
        const bodyRow = document.createElement('tr');
        for (let i = 0; i < 2; i++) {
            const td = document.createElement('td');
            td.contentEditable = 'true';
            td.textContent = `Cell ${i + 1}`;
            bodyRow.appendChild(td);
        }
        tbody.appendChild(bodyRow);
        
        table.appendChild(thead);
        table.appendChild(tbody);
        
        const closeTag = document.createElement('span');
        closeTag.className = 'tag-label';
        closeTag.textContent = '</table>';
        closeTag.setAttribute('contenteditable', 'false');
        
        const controls = document.createElement('div');
        controls.className = 'mt-2 flex gap-2';
        controls.setAttribute('contenteditable', 'false');
        
        const addRowBtn = document.createElement('button');
        addRowBtn.type = 'button';
        addRowBtn.className = 'table-add-row';
        addRowBtn.textContent = '+ Add Row';
        addRowBtn.setAttribute('contenteditable', 'false');
        addRowBtn.addEventListener('click', (e) => {
            e.preventDefault();
            const newRow = document.createElement('tr');
            const colCount = table.querySelector('tr').children.length;
            for (let i = 0; i < colCount; i++) {
                const td = document.createElement('td');
                td.contentEditable = 'true';
                td.textContent = 'New cell';
                newRow.appendChild(td);
            }
            tbody.appendChild(newRow);
            triggerAutosave();
        });
        
        const addColBtn = document.createElement('button');
        addColBtn.type = 'button';
        addColBtn.className = 'table-add-col';
        addColBtn.textContent = '+ Add Column';
        addColBtn.setAttribute('contenteditable', 'false');
        addColBtn.addEventListener('click', (e) => {
            e.preventDefault();
            const headerRow = thead.querySelector('tr');
            const th = document.createElement('th');
            th.contentEditable = 'true';
            th.textContent = 'New Header';
            headerRow.appendChild(th);
            
            tbody.querySelectorAll('tr').forEach(row => {
                const td = document.createElement('td');
                td.contentEditable = 'true';
                td.textContent = 'New cell';
                row.appendChild(td);
            });
            triggerAutosave();
        });
        
        controls.appendChild(addRowBtn);
        controls.appendChild(addColBtn);
        
        const deleteBtn = document.createElement('div');
        deleteBtn.className = 'delete-btn';
        deleteBtn.innerHTML = '×';
        deleteBtn.setAttribute('contenteditable', 'false');
        deleteBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            wrapper.remove();
            updateCounts();
            triggerAutosave();
        });
        
        wrapper.appendChild(deleteBtn);
        wrapper.appendChild(openTag);
        wrapper.appendChild(table);
        wrapper.appendChild(controls);
        wrapper.appendChild(closeTag);
        
        return wrapper;
    }

    // ==========================================================================
    // 4) CREATE INLINE WRAPPER
    // ==========================================================================
    function createInlineWrapper(tagName, initialContent = '') {
        const wrapper = document.createElement('span');
        wrapper.className = 'inline-wrapper';
        wrapper.setAttribute('data-tag', tagName);
        wrapper.setAttribute('contenteditable', 'false');
        
        const openTag = document.createElement('span');
        openTag.className = 'tag-label';
        openTag.textContent = `<${tagName}>`;
        openTag.setAttribute('contenteditable', 'false');
        
        const content = document.createElement('span');
        content.contentEditable = 'true';
        content.textContent = initialContent || 'text';
        content.setAttribute('data-placeholder', 'text');
        
        const closeTag = document.createElement('span');
        closeTag.className = 'tag-label';
        closeTag.textContent = `</${tagName}>`;
        closeTag.setAttribute('contenteditable', 'false');
        
        const deleteBtn = document.createElement('span');
        deleteBtn.className = 'inline-delete';
        deleteBtn.innerHTML = '×';
        deleteBtn.setAttribute('contenteditable', 'false');
        deleteBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            wrapper.remove();
            updateCounts();
            triggerAutosave();
        });
        
        wrapper.appendChild(openTag);
        wrapper.appendChild(content);
        wrapper.appendChild(closeTag);
        wrapper.appendChild(deleteBtn);
        
        return wrapper;
    }

    // ==========================================================================
    // 5) CREATE LIST WRAPPER
    // ==========================================================================
    function createListWrapper(listType = 'ul') {
        const wrapper = document.createElement('div');
        wrapper.className = 'tag-wrapper';
        wrapper.setAttribute('data-tag', listType);
        wrapper.setAttribute('contenteditable', 'false');
        
        const openTag = document.createElement('span');
        openTag.className = 'tag-label';
        openTag.textContent = `<${listType}>`;
        openTag.setAttribute('contenteditable', 'false');
        
        const listContainer = document.createElement('div');
        listContainer.className = 'list-container';
        listContainer.setAttribute('contenteditable', 'false');
        
        const firstItem = createListItem();
        listContainer.appendChild(firstItem);
        
        const closeTag = document.createElement('span');
        closeTag.className = 'tag-label';
        closeTag.textContent = `</${listType}>`;
        closeTag.setAttribute('contenteditable', 'false');
        
        const addBtn = document.createElement('div');
        addBtn.className = 'list-add-btn';
        addBtn.innerHTML = '+ Add Item';
        addBtn.setAttribute('contenteditable', 'false');
        addBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            const newItem = createListItem();
            listContainer.appendChild(newItem);
            newItem.querySelector('.list-item-content').focus();
            triggerAutosave();
        });
        
        const deleteBtn = document.createElement('div');
        deleteBtn.className = 'delete-btn';
        deleteBtn.innerHTML = '×';
        deleteBtn.setAttribute('contenteditable', 'false');
        deleteBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            wrapper.remove();
            updateCounts();
            triggerAutosave();
        });
        
        wrapper.appendChild(deleteBtn);
        wrapper.appendChild(openTag);
        wrapper.appendChild(listContainer);
        wrapper.appendChild(addBtn);
        wrapper.appendChild(closeTag);
        
        return wrapper;
    }

    // ==========================================================================
    // 6) CREATE LIST ITEM
    // ==========================================================================
    function createListItem(initialContent = '') {
        const itemWrapper = document.createElement('div');
        itemWrapper.className = 'list-item-wrapper';
        itemWrapper.setAttribute('data-tag', 'li');
        itemWrapper.setAttribute('contenteditable', 'false');
        
        const liTag = document.createElement('span');
        liTag.className = 'tag-label';
        liTag.textContent = '<li>';
        liTag.setAttribute('contenteditable', 'false');
        
        const content = document.createElement('div');
        content.className = 'list-item-content';
        content.contentEditable = 'true';
        content.innerHTML = initialContent || '';
        content.setAttribute('data-placeholder', 'List item...');
        
        const liCloseTag = document.createElement('span');
        liCloseTag.className = 'tag-label';
        liCloseTag.textContent = '</li>';
        liCloseTag.setAttribute('contenteditable', 'false');
        
        const deleteBtn = document.createElement('div');
        deleteBtn.className = 'list-item-delete';
        deleteBtn.innerHTML = '×';
        deleteBtn.setAttribute('contenteditable', 'false');
        deleteBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            itemWrapper.remove();
            updateCounts();
            triggerAutosave();
        });
        
        itemWrapper.appendChild(liTag);
        itemWrapper.appendChild(content);
        itemWrapper.appendChild(liCloseTag);
        itemWrapper.appendChild(deleteBtn);
        
        return itemWrapper;
    }

    // ==========================================================================
    // 7) INSERT AT CURSOR OR INSIDE FOCUSED PARENT
    // ==========================================================================
    function insertAtCursor(element) {
        if (!editor) return;
        
        const selection = window.getSelection();
        
        if (selection.rangeCount > 0) {
            const range = selection.getRangeAt(0);
            let container = range.commonAncestorContainer;
            
            if (container.nodeType === Node.TEXT_NODE) {
                container = container.parentElement;
            }
            
            const tagContent = container.closest('.tag-content');
            if (tagContent) {
                const br = document.createElement('br');
                range.deleteContents();
                range.insertNode(br);
                range.setStartAfter(br);
                range.collapse(true);
                selection.removeAllRanges();
                selection.addRange(range);
                
                if (br.nextSibling) {
                    br.parentNode.insertBefore(element, br.nextSibling);
                } else {
                    tagContent.appendChild(element);
                }
                
                updateCounts();
                triggerAutosave();
                
                setTimeout(() => {
                    const contentArea = element.querySelector('.tag-content, .list-item-content, [contenteditable="true"]:not(.tag-label)');
                    if (contentArea) {
                        contentArea.focus();
                        placeCaretAtEnd(contentArea);
                    }
                }, 10);
                
                return;
            }
        }
        
        editor.appendChild(element);
        
        setTimeout(() => {
            const contentArea = element.querySelector('.tag-content, .list-item-content, [contenteditable="true"]:not(.tag-label)');
            if (contentArea) {
                contentArea.focus();
                placeCaretAtEnd(contentArea);
            }
        }, 10);
        
        updateCounts();
        triggerAutosave();
    }

    // ==========================================================================
    // 8) PLACE CARET AT END
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
    // 9) IMAGE UPLOAD
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
        wrapper.setAttribute('contenteditable', 'false');
        
        const openTag = document.createElement('span');
        openTag.className = 'tag-label';
        openTag.textContent = '<figure>';
        openTag.setAttribute('contenteditable', 'false');
        
        const img = document.createElement('img');
        img.src = localUrl;
        img.alt = file.name;
        img.setAttribute('contenteditable', 'false');
        
        const caption = document.createElement('div');
        caption.className = 'image-caption';
        caption.contentEditable = 'true';
        caption.innerHTML = '';
        caption.setAttribute('data-placeholder', 'Add caption...');
        
        const closeTag = document.createElement('span');
        closeTag.className = 'tag-label';
        closeTag.textContent = '</figure>';
        closeTag.setAttribute('contenteditable', 'false');
        
        const deleteBtn = document.createElement('div');
        deleteBtn.className = 'delete-btn';
        deleteBtn.innerHTML = '×';
        deleteBtn.setAttribute('contenteditable', 'false');
        deleteBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            wrapper.remove();
            updateCounts();
            triggerAutosave();
        });
        
        wrapper.appendChild(deleteBtn);
        wrapper.appendChild(openTag);
        wrapper.appendChild(img);
        wrapper.appendChild(caption);
        wrapper.appendChild(closeTag);
        
        insertAtCursor(wrapper);
        
        const formData = new FormData();
        formData.append('image', file);
        formData.append('blog_id', blogId);
        formData.append('alt_text', file.name);
        formData.append('caption', '');
        
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
    // 10) DOUBLE CLICK TO EXIT - FIXED: ONLY changes focus, NO new tag
    // ==========================================================================
    function setupDoubleClickExit() {
        editor.addEventListener('dblclick', function(e) {
            const target = e.target;
            
            if (target.classList.contains('tag-content') || 
                target.classList.contains('list-item-content') ||
                target.classList.contains('image-caption')) {
                
                e.preventDefault();
                e.stopPropagation();
                
                const wrapper = target.closest('.tag-wrapper, .list-item-wrapper, .image-figure');
                
                if (wrapper) {
                    const parentWrapper = wrapper.parentNode.closest?.('.tag-wrapper') || null;
                    
                    if (parentWrapper) {
                        const parentContent = parentWrapper.querySelector('.tag-content');
                        if (parentContent) {
                            parentContent.focus();
                            placeCaretAtEnd(parentContent);
                        }
                    } else {
                        editor.focus();
                        const range = document.createRange();
                        range.selectNodeContents(editor);
                        range.collapse(false);
                        const sel = window.getSelection();
                        sel.removeAllRanges();
                        sel.addRange(range);
                    }
                    
                    triggerAutosave();
                }
            }
        });
    }

    // ==========================================================================
    // 11) UPDATE COUNTS
    // ==========================================================================
    function updateCounts() {
        if (!editor) return;
        const text = editor.innerText || editor.textContent;
        const words = text.trim().split(/\s+/).filter(w => w.length > 0).length;
        if (wordCountSpan) wordCountSpan.textContent = words;
        
        const images = editor.querySelectorAll('img').length;
        if (imageCountSpan) imageCountSpan.textContent = images;
    }

    // ==========================================================================
    // 12) AUTO-SAVE
    // ==========================================================================
    function triggerAutosave() {
        clearTimeout(autosaveTimer);
        autosaveTimer = setTimeout(saveToLocalStorage, AUTOSAVE_INTERVAL);
    }

    function saveToLocalStorage() {
        const data = {
            title: titleInput?.value || '',
            excerpt: excerptInput?.value || '',
            content: editor?.innerHTML || '',
            author_name: document.getElementById('author_name')?.value || '',
            category_id: document.getElementById('category_id')?.value || '',
            status: document.getElementById('status')?.value || 'draft',
            tags: document.getElementById('tags')?.value || '',
            is_featured: document.getElementById('is_featured')?.checked || false,
            meta_title: document.getElementById('meta_title')?.value || '',
            meta_description: document.getElementById('meta_description')?.value || '',
            meta_keywords: document.getElementById('meta_keywords')?.value || '',
            timestamp: new Date().toISOString()
        };
        
        localStorage.setItem(AUTOSAVE_KEY, JSON.stringify(data));
        console.log('✅ Auto-saved');
    }

    // ==========================================================================
    // 13) META DATA GENERATION
    // ==========================================================================
    function generateMetaData() {
        const title = titleInput?.value.trim() || '';
        const excerpt = excerptInput?.value.trim() || '';
        const content = editor?.innerText || editor?.textContent || '';
        
        let metaTitle = title;
        if (metaTitle.length > 60) metaTitle = metaTitle.substring(0, 57) + '...';
        document.getElementById('meta_title').value = metaTitle;
        
        let metaDesc = excerpt || content.substring(0, 200);
        metaDesc = metaDesc.replace(/\s+/g, ' ').trim();
        if (metaDesc.length > 160) metaDesc = metaDesc.substring(0, 157) + '...';
        document.getElementById('meta_description').value = metaDesc;
        
        const words = content.toLowerCase()
            .replace(/[^\w\s]/g, '')
            .split(/\s+/)
            .filter(w => w.length > 4);
        
        const wordCount = {};
        words.forEach(word => { wordCount[word] = (wordCount[word] || 0) + 1; });
        
        const topWords = Object.entries(wordCount)
            .sort((a, b) => b[1] - a[1])
            .slice(0, 5)
            .map(entry => entry[0]);
        
        document.getElementById('meta_keywords').value = topWords.join(', ');
        
        updateMetaCharCounts();
    }

    function updateMetaCharCounts() {
        const metaTitle = document.getElementById('meta_title')?.value || '';
        const metaDesc = document.getElementById('meta_description')?.value || '';
        
        const titleLen = document.getElementById('meta-title-length');
        const descLen = document.getElementById('meta-desc-length');
        
        if (titleLen) titleLen.textContent = metaTitle.length;
        if (descLen) descLen.textContent = metaDesc.length;
    }

    // ==========================================================================
    // 14) PREVIEW
    // ==========================================================================
    function setupPreview() {
        const previewBtn = document.getElementById('toggle-preview');
        const previewModal = document.getElementById('preview-modal');
        const closePreviewBtn = document.getElementById('close-preview');
        const previewOverlay = document.getElementById('preview-overlay');
        
        if (!previewBtn || !previewModal) return;
        
        function openPreview() {
            document.getElementById('preview-blog-title').textContent = titleInput?.value || 'Untitled Post';
            
            const authorName = document.getElementById('author_name')?.value || 'Unknown Author';
            document.getElementById('preview-author').innerHTML = `
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                ${authorName}
            `;
            
            const categorySelect = document.getElementById('category_id');
            const categoryText = categorySelect?.options[categorySelect.selectedIndex]?.text || 'Uncategorized';
            document.getElementById('preview-category').innerHTML = `
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                </svg>
                ${categoryText}
            `;
            
            document.getElementById('preview-date').innerHTML = `
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                ${new Date().toLocaleDateString()}
            `;
            
            const featuredPreview = document.getElementById('featured-preview');
            const featuredContainer = document.getElementById('preview-featured-image-container');
            const featuredImage = document.getElementById('preview-featured-image');
            
            if (featuredPreview?.src && featuredContainer && featuredImage) {
                featuredImage.src = featuredPreview.src;
                featuredContainer.classList.remove('hidden');
            } else {
                const currentFeatured = '{{ $blog->featured_image ? asset("storage/" . $blog->featured_image) : "" }}';
                if (currentFeatured && featuredContainer && featuredImage) {
                    featuredImage.src = currentFeatured;
                    featuredContainer.classList.remove('hidden');
                } else if (featuredContainer) {
                    featuredContainer.classList.add('hidden');
                }
            }
            
            const excerptText = excerptInput?.value.trim();
            const previewExcerpt = document.getElementById('preview-excerpt');
            if (previewExcerpt) {
                if (excerptText) {
                    previewExcerpt.textContent = excerptText;
                    previewExcerpt.classList.remove('hidden');
                } else {
                    previewExcerpt.classList.add('hidden');
                }
            }
            
            const previewContent = document.getElementById('preview-content');
            if (previewContent) previewContent.innerHTML = extractCleanContent();
            
            const tagsInput = document.getElementById('tags')?.value || '';
            const tagsContainer = document.getElementById('preview-tags-container');
            const tagsDiv = document.getElementById('preview-tags');
            
            if (tagsInput.trim() && tagsContainer && tagsDiv) {
                const tagsArray = tagsInput.split(',').map(t => t.trim()).filter(t => t);
                const tagsHtml = tagsArray.map(tag => 
                    `<span class="inline-block bg-indigo-100 text-indigo-800 text-xs px-2 py-1 rounded">${tag}</span>`
                ).join('');
                tagsDiv.innerHTML = tagsHtml;
                tagsContainer.classList.remove('hidden');
            } else if (tagsContainer) {
                tagsContainer.classList.add('hidden');
            }
            
            previewModal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }
        
        function closePreview() {
            if (previewModal) previewModal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
        
        if (previewBtn) previewBtn.addEventListener('click', openPreview);
        if (closePreviewBtn) closePreviewBtn.addEventListener('click', closePreview);
        if (previewOverlay) previewOverlay.addEventListener('click', closePreview);
    }

    function extractCleanContent() {
        if (!editor) return '';
        
        const clone = editor.cloneNode(true);
        
        clone.querySelectorAll('.tag-label, .delete-btn, .list-item-delete, .inline-delete, .list-add-btn, .table-add-row, .table-add-col').forEach(el => el.remove());
        
        clone.querySelectorAll('.tag-content, .list-item-content').forEach(el => {
            const tagName = el.closest('[data-tag]')?.dataset.tag || 'div';
            const replacement = document.createElement(tagName);
            replacement.innerHTML = el.innerHTML;
            if (el.classList.contains('list-item-content')) {
                replacement.style.marginLeft = '20px';
            }
            el.replaceWith(replacement);
        });
        
        clone.querySelectorAll('.table-wrapper').forEach(wrapper => {
            const table = wrapper.querySelector('table');
            if (table) wrapper.replaceWith(table.cloneNode(true));
        });
        
        clone.querySelectorAll('.image-figure').forEach(figure => {
            const img = figure.querySelector('img');
            const caption = figure.querySelector('.image-caption');
            const newFigure = document.createElement('figure');
            if (img) newFigure.appendChild(img.cloneNode(true));
            if (caption && caption.textContent.trim()) {
                const figcaption = document.createElement('figcaption');
                figcaption.textContent = caption.textContent;
                newFigure.appendChild(figcaption);
            }
            figure.replaceWith(newFigure);
        });
        
        clone.querySelectorAll('.tag-wrapper, .list-item-wrapper').forEach(wrapper => {
            const children = Array.from(wrapper.childNodes);
            children.forEach(child => {
                if (child.nodeType === Node.ELEMENT_NODE && 
                    !child.classList?.contains('tag-label') && 
                    !child.classList?.contains('delete-btn') &&
                    !child.classList?.contains('list-add-btn')) {
                    wrapper.parentNode.insertBefore(child.cloneNode(true), wrapper);
                } else if (child.nodeType === Node.TEXT_NODE && child.textContent.trim()) {
                    wrapper.parentNode.insertBefore(document.createTextNode(child.textContent), wrapper);
                }
            });
            wrapper.remove();
        });
        
        return clone.innerHTML;
    }

    // ==========================================================================
    // 15) SETUP TOOLBAR
    // ==========================================================================
    function setupToolbar() {
        document.querySelectorAll('.toolbar-btn[data-tag]').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const tag = this.dataset.tag;
                
                if (tag === 'ul' || tag === 'ol') {
                    insertAtCursor(createListWrapper(tag));
                } else if (tag === 'hr') {
                    const hr = document.createElement('hr');
                    hr.className = 'my-4 border-slate-600';
                    hr.setAttribute('contenteditable', 'false');
                    insertAtCursor(hr);
                } else if (tag === 'table') {
                    insertAtCursor(createTableWrapper());
                } else {
                    insertAtCursor(createTagWrapper(tag));
                }
                
                editor.focus();
            });
        });

        if (insertImageBtn) {
            insertImageBtn.addEventListener('click', (e) => {
                e.preventDefault();
                imageInput?.click();
            });
        }
        
        if (imageInput) {
            imageInput.addEventListener('change', async function() {
                if (this.files && this.files[0]) {
                    await handleImageUpload(this.files[0]);
                    this.value = '';
                }
            });
        }

        if (insertLinkBtn) {
            insertLinkBtn.addEventListener('click', (e) => {
                e.preventDefault();
                const url = prompt('Enter URL:');
                if (url) insertAtCursor(createTagWrapper('a', url));
            });
        }
    }

    // ==========================================================================
    // 16) DRAG & DROP
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
            
            const range = document.caretRangeFromPoint(e.clientX, e.clientY);
            if (range) {
                const sel = window.getSelection();
                sel.removeAllRanges();
                sel.addRange(range);
            }
            
            if (files.length) {
                for (let file of files) {
                    if (file.type.startsWith('image/')) {
                        await handleImageUpload(file);
                    }
                }
            }
        });
    }

    // ==========================================================================
    // 17) FORM SUBMIT
    // ==========================================================================
    function setupFormSubmit() {
        blogForm.addEventListener('submit', function(e) {
            contentField.value = extractCleanContent();
            localStorage.removeItem(AUTOSAVE_KEY);
            return true;
        });
        
        const saveDraftBtn = document.getElementById('save-draft-btn');
        if (saveDraftBtn) {
            saveDraftBtn.addEventListener('click', function() {
                const statusSelect = document.getElementById('status');
                if (statusSelect) statusSelect.value = 'draft';
                blogForm.submit();
            });
        }
    }

    // ==========================================================================
    // 18) TITLE TO SLUG
    // ==========================================================================
    function setupTitleSlug() {
        if (!titleInput || !slugPreview) return;
        
        titleInput.addEventListener('input', function() {
            const slug = this.value.toLowerCase()
                .replace(/[^\w\s-]/g, '')
                .replace(/[\s_-]+/g, '-')
                .replace(/^-+|-+$/g, '');
            slugPreview.textContent = slug || 'your-post-slug';
            triggerAutosave();
        });
    }

    // ==========================================================================
    // 19) STATUS TOGGLE
    // ==========================================================================
    function setupStatusToggle() {
        const statusSelect = document.getElementById('status');
        const scheduledDatetime = document.getElementById('scheduled-datetime');
        
        if (statusSelect && scheduledDatetime) {
            statusSelect.addEventListener('change', function() {
                if (this.value === 'scheduled') {
                    scheduledDatetime.classList.remove('hidden');
                } else {
                    scheduledDatetime.classList.add('hidden');
                }
                triggerAutosave();
            });
        }
    }

    // ==========================================================================
    // 20) FEATURED IMAGE
    // ==========================================================================
    function setupFeaturedImage() {
        const featuredInput = document.getElementById('featured_image');
        const featuredPreview = document.getElementById('featured-preview');
        const featuredPreviewContainer = document.getElementById('featured-preview-container');
        const removeFeaturedBtn = document.getElementById('remove-featured');
        const featuredDropZone = document.getElementById('featured-drop-zone');
        
        if (!featuredInput) return;
        
        featuredInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    if (featuredPreview) featuredPreview.src = e.target.result;
                    if (featuredPreviewContainer) featuredPreviewContainer.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
                triggerAutosave();
            }
        });
        
        if (removeFeaturedBtn) {
            removeFeaturedBtn.addEventListener('click', function() {
                featuredInput.value = '';
                if (featuredPreviewContainer) featuredPreviewContainer.classList.add('hidden');
                if (featuredPreview) featuredPreview.src = '';
                triggerAutosave();
            });
        }
        
        if (featuredDropZone) {
            featuredDropZone.addEventListener('dragover', (e) => {
                e.preventDefault();
                featuredDropZone.classList.add('border-indigo-500', 'bg-indigo-900', 'bg-opacity-10');
            });
            
            featuredDropZone.addEventListener('dragleave', () => {
                featuredDropZone.classList.remove('border-indigo-500', 'bg-indigo-900', 'bg-opacity-10');
            });
            
            featuredDropZone.addEventListener('drop', (e) => {
                e.preventDefault();
                featuredDropZone.classList.remove('border-indigo-500', 'bg-indigo-900', 'bg-opacity-10');
                
                const files = e.dataTransfer.files;
                if (files.length && files[0].type.startsWith('image/')) {
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(files[0]);
                    featuredInput.files = dataTransfer.files;
                    featuredInput.dispatchEvent(new Event('change'));
                }
            });
        }
    }

    // ==========================================================================
    // 21) SEO TOGGLE
    // ==========================================================================
    function setupSEOToggle() {
        const seoToggle = document.getElementById('seo-toggle');
        const seoPanel = document.getElementById('seo-panel');
        const seoChevron = document.getElementById('seo-chevron');
        
        if (seoToggle && seoPanel) {
            seoToggle.addEventListener('click', function() {
                seoPanel.classList.toggle('hidden');
                if (seoChevron) seoChevron.classList.toggle('rotate-180');
            });
        }
        
        const regenerateMetaBtn = document.getElementById('regenerate-meta');
        if (regenerateMetaBtn) {
            regenerateMetaBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                generateMetaData();
            });
        }
        
        const metaTitle = document.getElementById('meta_title');
        const metaDesc = document.getElementById('meta_description');
        
        if (metaTitle) metaTitle.addEventListener('input', updateMetaCharCounts);
        if (metaDesc) metaDesc.addEventListener('input', updateMetaCharCounts);
    }

    // ==========================================================================
    // 22) AUTO-SAVE TRIGGERS
    // ==========================================================================
    function setupAutoSaveTriggers() {
        if (titleInput) titleInput.addEventListener('input', triggerAutosave);
        if (excerptInput) excerptInput.addEventListener('input', triggerAutosave);
        if (editor) editor.addEventListener('input', () => {
            triggerAutosave();
            updateCounts();
        });
        
        const formInputs = blogForm?.querySelectorAll('input, select, textarea') || [];
        formInputs.forEach(input => {
            input.addEventListener('change', triggerAutosave);
        });
    }

    // ==========================================================================
    // 23) AUTO-META GENERATION
    // ==========================================================================
    function setupAutoMetaGeneration() {
        let metaGenerationTimer = null;
        
        function scheduleMetaGeneration() {
            clearTimeout(metaGenerationTimer);
            metaGenerationTimer = setTimeout(generateMetaData, 2000);
        }
        
        if (titleInput) titleInput.addEventListener('input', scheduleMetaGeneration);
        if (excerptInput) excerptInput.addEventListener('input', scheduleMetaGeneration);
        if (editor) editor.addEventListener('input', scheduleMetaGeneration);
    }

    // ==========================================================================
    // 24) KEYBOARD SHORTCUTS
    // ==========================================================================
    function setupKeyboardShortcuts() {
        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                e.preventDefault();
                saveToLocalStorage();
                alert('✅ Saved to localStorage');
            }
            
            if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                e.preventDefault();
                document.getElementById('toggle-preview')?.click();
            }
            
            if (e.key === 'Escape') {
                const previewModal = document.getElementById('preview-modal');
                if (previewModal && !previewModal.classList.contains('hidden')) {
                    document.getElementById('close-preview')?.click();
                }
            }
        });
    }

    // ==========================================================================
    // 25) INITIALIZATION
    // ==========================================================================
    function init() {
        console.log('🚀 Starting initialization for EDIT mode...');
        
        // Parse existing content into tag wrappers
        // This assumes your content is already in the tag wrapper format
        // If not, you may need to add a parser here
        
        setupThemeSwitcher();
        setupToolbar();
        setupDragDrop();
        setupDoubleClickExit();
        setupFormSubmit();
        setupTitleSlug();
        setupStatusToggle();
        setupFeaturedImage();
        setupSEOToggle();
        setupAutoSaveTriggers();
        setupAutoMetaGeneration();
        setupPreview();
        setupKeyboardShortcuts();
        
        updateCounts();
        updateMetaCharCounts();
        
        if (titleInput?.value.trim()) generateMetaData();
        
        const observer = new MutationObserver(() => {
            updateCounts();
        });
        
        observer.observe(editor, { childList: true, subtree: true, characterData: true });
        
        console.log('✅ Editor fully initialized for EDIT mode!');
    }

    // Start initialization when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
</script>
@endpush

@endsection