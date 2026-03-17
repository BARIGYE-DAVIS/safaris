{{-- resources/views/admin/blogs/edit.blade.php --}}
@extends('layouts.admin')

@section('title', 'Edit Blog Post')

@section('content')
<div class="min-h-screen transition-all duration-300" id="app-theme-container">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 pb-44">

        {{-- Header --}}
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold theme-text-primary mb-2">Edit Blog Post</h1>
                <p class="theme-text-secondary">Editing: <span class="text-indigo-400 font-semibold">{{ $blog->title }}</span></p>
            </div>
            <div class="flex gap-3">
                <button type="button" id="preview-btn" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    Preview
                </button>
                <a href="{{ route('admin.blogs.index') }}" class="px-4 py-2 theme-bg-card theme-text-primary rounded-lg transition flex items-center gap-2 border theme-border">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back
                </a>
            </div>
        </div>

        {{-- Error Messages --}}
        @if($errors->any())
            <div class="mb-6 bg-red-500/10 border border-red-500 text-red-400 px-4 py-3 rounded-lg">
                <p class="font-semibold mb-2">Please fix the following errors:</p>
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        @if(session('success'))
            <div class="mb-6 bg-green-500/10 border border-green-500 text-green-400 px-4 py-3 rounded-lg">
                ✓ {{ session('success') }}
            </div>
        @endif

        {{-- Main Form --}}
        <form method="POST" action="{{ route('admin.blogs.update', $blog) }}" enctype="multipart/form-data" id="blog-form">
            @csrf
            @method('PUT')

            {{-- Hidden inputs — ALL synced by JS on submit --}}
            <input type="hidden" name="title"        id="title-hidden">
            <input type="hidden" name="excerpt"      id="excerpt-hidden">
            <input type="hidden" name="content"      id="content-hidden">
            <input type="hidden" name="content_json" id="content-json-hidden">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Left Column --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- TITLE: contenteditable so color tools work --}}
                    <div class="theme-bg-card backdrop-blur-sm theme-border rounded-xl p-6">
                        <label class="block text-sm font-semibold theme-text-secondary mb-3">
                            <span class="text-red-400">*</span> Blog Title
                            <span class="ml-2 text-xs font-normal theme-text-tertiary">— select text, then apply color below</span>
                        </label>
                        <div id="title-editor"
                             contenteditable="true"
                             spellcheck="true"
                             data-field="title"
                             data-placeholder="Enter your blog title..."
                             class="colorable-field w-full px-4 py-3 theme-bg-input border theme-border rounded-lg theme-text-primary focus:outline-none transition text-xl font-bold"
                             style="min-height:3rem; line-height:1.5; white-space:nowrap; overflow-x:auto;"
                        >{!! old('title', $blog->title) !!}</div>
                    </div>

                    {{-- EXCERPT: contenteditable so color tools work --}}
                    <div class="theme-bg-card backdrop-blur-sm theme-border rounded-xl p-6">
                        <label class="block text-sm font-semibold theme-text-secondary mb-3">
                            Excerpt
                            <span class="ml-2 text-xs font-normal theme-text-tertiary">— select text, then apply color below</span>
                        </label>
                        <div id="excerpt-editor"
                             contenteditable="true"
                             spellcheck="true"
                             data-field="excerpt"
                             data-placeholder="Brief summary of your blog post..."
                             class="colorable-field w-full px-4 py-3 theme-bg-input border theme-border rounded-lg theme-text-primary focus:outline-none transition"
                             style="min-height:5rem; line-height:1.7; white-space:pre-wrap; word-break:break-word;"
                        >{!! old('excerpt', $blog->excerpt) !!}</div>
                    </div>

                    {{-- CONTENT EDITOR --}}
                    <div class="theme-bg-card backdrop-blur-sm theme-border rounded-xl overflow-hidden">
                        <div class="theme-bg-secondary border-b theme-border px-6 py-4">
                            <h3 class="text-lg font-bold theme-text-primary flex items-center gap-2">
                                <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Content Editor
                            </h3>
                            <p class="text-sm theme-text-tertiary mt-1">
                                Title, excerpt and content all support colors. Click any field, select text, then use the color tools.
                            </p>
                        </div>

                        <div id="editor"
                             contenteditable="true"
                             spellcheck="true"
                             data-field="content"
                             class="colorable-field min-h-[600px] p-8 theme-bg-editor theme-text-editor focus:outline-none leading-relaxed"
                             style="line-height:1.8; white-space:pre-wrap; word-break:break-word;">
                            {!! old('content', $blog->content) !!}
                        </div>

                        <div class="theme-bg-secondary border-t theme-border px-6 py-3 text-sm theme-text-tertiary flex gap-6 flex-wrap">
                            <span><span id="char-count" class="font-semibold theme-text-primary">0</span> chars</span>
                            <span><span id="word-count" class="font-semibold theme-text-primary">0</span> words</span>
                            <span><span id="image-count" class="font-semibold theme-text-primary">0</span> images</span>
                        </div>
                    </div>

                </div>

                {{-- Right Column --}}
                <div class="lg:col-span-1 space-y-6">

                    <div class="theme-bg-card backdrop-blur-sm theme-border rounded-xl p-6">
                        <h3 class="text-lg font-bold theme-text-primary mb-4">Publish Settings</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold theme-text-secondary mb-2">Status</label>
                                <select name="status" required class="w-full px-4 py-2 theme-bg-input theme-border rounded-lg theme-text-primary focus:outline-none focus:border-indigo-500">
                                    <option value="draft"     {{ old('status', $blog->status) == 'draft'     ? 'selected' : '' }}>📝 Draft</option>
                                    <option value="published" {{ old('status', $blog->status) == 'published' ? 'selected' : '' }}>✅ Published</option>
                                    <option value="scheduled" {{ old('status', $blog->status) == 'scheduled' ? 'selected' : '' }}>⏰ Scheduled</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold theme-text-secondary mb-2">Publish Date</label>
                                <input type="datetime-local" name="published_at"
                                       value="{{ old('published_at', $blog->published_at ? $blog->published_at->format('Y-m-d\TH:i') : '') }}"
                                       class="w-full px-4 py-2 theme-bg-input theme-border rounded-lg theme-text-primary focus:outline-none focus:border-indigo-500">
                            </div>
                            <div class="flex items-center gap-3 p-3 theme-bg-input rounded-lg theme-border">
                                <input type="checkbox" name="is_featured" id="is_featured" value="1"
                                       {{ old('is_featured', $blog->is_featured) ? 'checked' : '' }}
                                       class="w-5 h-5 text-indigo-600 rounded cursor-pointer">
                                <label for="is_featured" class="text-sm theme-text-primary cursor-pointer">Featured Post</label>
                            </div>
                        </div>
                    </div>

                    <div class="theme-bg-card backdrop-blur-sm theme-border rounded-xl p-6">
                        <label class="block text-sm font-semibold theme-text-secondary mb-3">Category</label>
                        <select name="category_id" class="w-full px-4 py-2 theme-bg-input theme-border rounded-lg theme-text-primary focus:outline-none focus:border-indigo-500">
                            <option value="">-- No Category --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $blog->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="theme-bg-card backdrop-blur-sm theme-border rounded-xl p-6">
                        <label class="block text-sm font-semibold theme-text-secondary mb-3">Tags</label>
                        <input type="text" name="tags"
                               value="{{ old('tags', $blog->tags) }}"
                               class="w-full px-4 py-2 theme-bg-input theme-border rounded-lg theme-text-primary placeholder-slate-500 focus:outline-none focus:border-indigo-500"
                               placeholder="safari, wildlife, kenya">
                        <p class="mt-2 text-xs theme-text-tertiary">Separate with commas</p>
                    </div>

                    <div class="theme-bg-card backdrop-blur-sm theme-border rounded-xl p-6">
                        <label class="block text-sm font-semibold theme-text-secondary mb-3">Featured Image</label>
                        @if($blog->featured_image)
                            <div class="mb-4">
                                <img src="{{ asset('storage/' . $blog->featured_image) }}" alt="Current" class="w-full h-48 object-cover rounded-lg border-2 theme-border">
                            </div>
                        @endif
                        <input type="file" name="featured_image" accept="image/*"
                               class="w-full text-sm theme-text-tertiary file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 file:cursor-pointer cursor-pointer">
                        <p class="mt-2 text-xs theme-text-tertiary">Max 5MB · JPG, PNG, WebP</p>
                    </div>

                    <div class="theme-bg-card backdrop-blur-sm theme-border rounded-xl p-6">
                        <label class="block text-sm font-semibold theme-text-secondary mb-3">Author Name</label>
                        <input type="text" name="author_name"
                               value="{{ old('author_name', $blog->author_name) }}"
                               class="w-full px-4 py-2 theme-bg-input theme-border rounded-lg theme-text-primary placeholder-slate-500 focus:outline-none focus:border-indigo-500"
                               placeholder="John Doe">
                    </div>

                    {{-- Reading Time --}}
                    <div class="theme-bg-card backdrop-blur-sm theme-border rounded-xl p-6">
                        <label class="block text-sm font-semibold theme-text-secondary mb-3">
                            Reading Time
                            <span class="ml-1 text-xs font-normal theme-text-tertiary">(mins — auto-calculated if blank)</span>
                        </label>
                        <input type="number" name="reading_time" min="1" max="120"
                               value="{{ old('reading_time', $blog->reading_time) }}"
                               class="w-full px-4 py-2 theme-bg-input theme-border rounded-lg theme-text-primary placeholder-slate-500 focus:outline-none focus:border-indigo-500"
                               placeholder="e.g. 5">
                    </div>

                    {{-- SEO --}}
                    <div class="theme-bg-card backdrop-blur-sm theme-border rounded-xl p-6">
                        <h3 class="text-base font-bold theme-text-primary mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            SEO Settings
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-semibold theme-text-tertiary uppercase tracking-wider mb-1.5">
                                    Meta Title
                                    <span class="ml-1 normal-case font-normal" id="meta-title-count">
                                        ({{ strlen(old('meta_title', $blog->meta_title ?? '')) }}/60)
                                    </span>
                                </label>
                                <input type="text" name="meta_title" maxlength="60"
                                       value="{{ old('meta_title', $blog->meta_title) }}"
                                       id="meta-title-input"
                                       class="w-full px-4 py-2 theme-bg-input theme-border rounded-lg theme-text-primary placeholder-slate-500 focus:outline-none focus:border-indigo-500 text-sm"
                                       placeholder="Leave blank to use blog title">
                                <div class="mt-1.5 h-1 rounded-full bg-slate-700 overflow-hidden">
                                    <div id="meta-title-bar" class="h-full rounded-full transition-all"
                                         style="width:{{ min(100, round(strlen(old('meta_title', $blog->meta_title ?? '')) / 60 * 100)) }}%;
                                                background-color:{{ strlen(old('meta_title', $blog->meta_title ?? '')) > 55 ? '#ef4444' : (strlen(old('meta_title', $blog->meta_title ?? '')) > 40 ? '#f59e0b' : '#22c55e') }};"></div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold theme-text-tertiary uppercase tracking-wider mb-1.5">
                                    Meta Description
                                    <span class="ml-1 normal-case font-normal" id="meta-desc-count">
                                        ({{ strlen(old('meta_description', $blog->meta_description ?? '')) }}/160)
                                    </span>
                                </label>
                                <textarea name="meta_description" maxlength="160" rows="3"
                                          id="meta-desc-input"
                                          class="w-full px-4 py-2 theme-bg-input theme-border rounded-lg theme-text-primary placeholder-slate-500 focus:outline-none focus:border-indigo-500 text-sm resize-none"
                                          placeholder="Leave blank to use excerpt">{{ old('meta_description', $blog->meta_description) }}</textarea>
                                <div class="mt-1.5 h-1 rounded-full bg-slate-700 overflow-hidden">
                                    <div id="meta-desc-bar" class="h-full rounded-full transition-all"
                                         style="width:{{ min(100, round(strlen(old('meta_description', $blog->meta_description ?? '')) / 160 * 100)) }}%;
                                                background-color:{{ strlen(old('meta_description', $blog->meta_description ?? '')) > 150 ? '#ef4444' : (strlen(old('meta_description', $blog->meta_description ?? '')) > 120 ? '#f59e0b' : '#22c55e') }};"></div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold theme-text-tertiary uppercase tracking-wider mb-1.5">Meta Keywords</label>
                                <input type="text" name="meta_keywords" maxlength="500"
                                       value="{{ old('meta_keywords', $blog->meta_keywords) }}"
                                       class="w-full px-4 py-2 theme-bg-input theme-border rounded-lg theme-text-primary placeholder-slate-500 focus:outline-none focus:border-indigo-500 text-sm"
                                       placeholder="keyword1, keyword2, keyword3">
                                <p class="mt-1.5 text-xs theme-text-tertiary">Separate with commas</p>
                            </div>

                            {{-- Google SERP preview --}}
                            <div class="mt-4 p-3 bg-white rounded-lg text-left">
                                <p class="text-xs text-slate-500 mb-1.5 font-semibold uppercase tracking-wider">Google Preview</p>
                                <p id="serp-title"
                                   class="text-blue-700 text-base font-medium truncate leading-snug">
                                    {{ old('meta_title', $blog->meta_title ?: $blog->title) }}
                                </p>
                                <p class="text-green-700 text-xs truncate">{{ url('/blog/' . $blog->slug) }}</p>
                                <p id="serp-desc"
                                   class="text-slate-600 text-xs mt-0.5 leading-relaxed line-clamp-2">
                                    {{ old('meta_description', $blog->meta_description ?: $blog->excerpt) }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <button type="submit"
                            class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold py-4 px-6 rounded-xl shadow-lg transition transform hover:scale-105 active:scale-95 flex items-center justify-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Update Blog Post
                    </button>

                </div>
            </div>
        </form>
    </div>

    {{-- ============================================================
         BOTTOM FLOATING TOOLBAR
         ============================================================ --}}
    <div class="fixed bottom-0 left-0 right-0 theme-bg-toolbar border-t-2 theme-border-toolbar px-4 py-3 shadow-2xl z-50">
        <div class="max-w-7xl mx-auto space-y-2">

            {{-- Row 1: Themes + active field badge --}}
            <div class="flex items-center gap-3 pb-2 border-b theme-border-toolbar">
                <span class="text-xs font-bold theme-text-toolbar uppercase tracking-wider mr-1 whitespace-nowrap">Themes:</span>
                @foreach([
                    'dark'   => ['#1e293b','#0f172a'],
                    'light'  => ['#ffffff','#f1f5f9'],
                    'green'  => ['#065f46','#047857'],
                    'yellow' => ['#f59e0b','#fbbf24'],
                    'blue'   => ['#1e40af','#3b82f6'],
                    'gray'   => ['#4b5563','#6b7280'],
                    'purple' => ['#6b21a8','#7c3aed'],
                    'pink'   => ['#be185d','#ec4899'],
                ] as $themeKey => $colors)
                <button type="button" class="theme-btn" data-theme="{{ $themeKey }}" title="{{ ucfirst($themeKey) }}">
                    <div class="w-9 h-9 rounded-lg border-2 border-white/20 transition-transform hover:scale-110"
                         style="background: linear-gradient(135deg, {{ $colors[0] }} 0%, {{ $colors[1] }} 100%);"></div>
                </button>
                @endforeach

                {{-- Shows which field is currently targeted for color --}}
                <div class="ml-auto flex items-center gap-2 shrink-0">
                    <span class="text-xs theme-text-tertiary whitespace-nowrap">Coloring:</span>
                    <span id="toolbar-field-indicator"
                          class="text-xs font-bold px-3 py-1 rounded-full border transition-all"
                          style="background:rgba(99,102,241,0.15); border-color:#6366f1; color:#a5b4fc;">
                        click a field first
                    </span>
                </div>
            </div>

            {{-- Row 2: Format tools --}}
            <div class="flex items-center gap-2 flex-wrap">

                {{-- Bold / Italic / Underline --}}
                <div class="flex gap-1 border-r theme-border-toolbar pr-3">
                    <button type="button" class="toolbar-btn font-bold"   data-format="bold">B</button>
                    <button type="button" class="toolbar-btn italic"      data-format="italic"><em>I</em></button>
                    <button type="button" class="toolbar-btn underline"   data-format="underline"><u>U</u></button>
                </div>

                {{-- Headings (content editor only) --}}
                <div class="flex gap-1 items-baseline border-r theme-border-toolbar pr-3">
                    <button type="button" class="toolbar-btn px-3 py-1" data-format="h1" style="font-size:1.35rem;font-weight:800;line-height:1.1;">H1</button>
                    <button type="button" class="toolbar-btn px-3 py-1" data-format="h2" style="font-size:1.05rem;font-weight:700;line-height:1.2;">H2</button>
                    <button type="button" class="toolbar-btn px-3 py-1" data-format="h3" style="font-size:0.85rem;font-weight:600;line-height:1.3;">H3</button>
                    <button type="button" class="toolbar-btn px-3 py-1" data-format="p"  style="font-size:0.75rem;font-weight:400;">¶P</button>
                </div>

                {{-- Lists --}}
                <div class="flex gap-1 border-r theme-border-toolbar pr-3">
                    <button type="button" class="toolbar-btn" data-format="ul">• List</button>
                    <button type="button" class="toolbar-btn" data-format="ol">1. List</button>
                </div>

                {{-- Align --}}
                <div class="flex gap-1 border-r theme-border-toolbar pr-3">
                    <button type="button" class="toolbar-btn" data-format="justifyLeft">⬅</button>
                    <button type="button" class="toolbar-btn" data-format="justifyCenter">⬌</button>
                    <button type="button" class="toolbar-btn" data-format="justifyRight">➡</button>
                </div>

                {{-- TEXT COLOR — works on title, excerpt AND content --}}
                <div class="flex items-center gap-2 theme-bg-card px-3 py-2 rounded-lg theme-border">
                    <span class="text-xs font-bold theme-text-toolbar">A</span>
                    <input type="color" id="text-color" value="#ff6b35"
                           class="w-9 h-9 rounded border-2 theme-border cursor-pointer p-0.5"
                           title="Pick text color">
                    <button type="button" id="apply-text-color"
                            class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded transition">
                        Apply
                    </button>
                    <button type="button" id="remove-text-color"
                            class="px-3 py-1.5 bg-slate-600 hover:bg-slate-500 text-white text-xs font-bold rounded transition">
                        Clear
                    </button>
                </div>

                {{-- HIGHLIGHT COLOR — works on title, excerpt AND content --}}
                <div class="flex items-center gap-2 theme-bg-card px-3 py-2 rounded-lg theme-border">
                    <span class="text-xs font-bold theme-text-toolbar">BG</span>
                    <input type="color" id="bg-color" value="#fbbf24"
                           class="w-9 h-9 rounded border-2 theme-border cursor-pointer p-0.5"
                           title="Pick highlight color">
                    <button type="button" id="apply-bg-color"
                            class="px-3 py-1.5 bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold rounded transition">
                        Apply
                    </button>
                    <button type="button" id="remove-bg-color"
                            class="px-3 py-1.5 bg-slate-600 hover:bg-slate-500 text-white text-xs font-bold rounded transition">
                        Clear
                    </button>
                </div>

                {{-- Image (content only) --}}
                <button type="button" id="insert-image"
                        class="flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition font-semibold text-sm">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"/>
                    </svg>
                    Image
                </button>
                <input type="file" id="image-upload" accept="image/*" class="hidden">

                <button type="button" id="insert-link" class="toolbar-btn text-sm">🔗 Link</button>

            </div>
        </div>
    </div>
</div>

{{-- Preview Modal --}}
<div id="preview-modal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="theme-bg-card rounded-xl max-w-4xl w-full max-h-[90vh] overflow-hidden">
        <div class="flex items-center justify-between p-6 border-b theme-border">
            <h3 class="text-xl font-bold theme-text-primary">Preview</h3>
            <button type="button" id="close-preview" class="theme-text-tertiary hover:theme-text-primary transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div id="preview-content"
             class="p-8 overflow-y-auto max-h-[calc(90vh-80px)] theme-text-primary"
             style="font-size:1rem;line-height:1.8;"></div>
    </div>
</div>

<style>
    /* Placeholder for contenteditable fields */
    .colorable-field:empty::before {
        content: attr(data-placeholder);
        color: #64748b;
        pointer-events: none;
        display: block;
    }
    .colorable-field:focus { outline: none; }
    /* Active target glow */
    .colorable-field.color-target {
        box-shadow: 0 0 0 3px rgba(99,102,241,0.45), 0 0 16px rgba(99,102,241,0.15) !important;
        border-color: #6366f1 !important;
    }

    /* Preview */
    #preview-content h1 { font-size:2.25rem;font-weight:800;margin:1.5rem 0 .75rem;border-bottom:2px solid rgba(255,255,255,.15);padding-bottom:.4rem; }
    #preview-content h2 { font-size:1.65rem;font-weight:700;margin:1.25rem 0 .5rem; }
    #preview-content h3 { font-size:1.25rem;font-weight:600;margin:1rem 0 .4rem; }
    #preview-content p  { margin:.75rem 0;line-height:1.8; }
    #preview-content ul { list-style-type:disc;padding-left:2rem;margin:.75rem 0; }
    #preview-content ol { list-style-type:decimal;padding-left:2rem;margin:.75rem 0; }
    #preview-content li { margin:.35rem 0;line-height:1.7; }
    #preview-content a  { color:#60a5fa;text-decoration:underline; }
    #preview-content strong, #preview-content b { font-weight:700; }
    #preview-content blockquote { border-left:4px solid rgba(255,255,255,.2);padding-left:1rem;font-style:italic;margin:1rem 0; }
    .image-wrapper { position:relative;display:block;margin:1.5rem 0; }
    .image-wrapper img { max-width:100%;height:auto;border-radius:.5rem;display:block; }
</style>

@endsection

@push('scripts')
<script src="{{ asset('js/blog-editor.js') }}"></script>
<script>
    window.addEventListener('DOMContentLoaded', function() {
        if (typeof BlogEditor !== 'undefined') {
            BlogEditor.init({
                blogId: {{ $blog->id }},
                uploadUrl: '{{ route("admin.blogs.uploadImage") }}',
                csrfToken: '{{ csrf_token() }}'
            });
        }

        // ── SEO live counters & SERP preview ──────────────────────────
        function seoBar(barId, countId, input, max) {
            const bar   = document.getElementById(barId);
            const count = document.getElementById(countId);
            if (!bar || !count || !input) return;
            const len = input.value.length;
            count.textContent = `(${len}/${max})`;
            bar.style.width = Math.min(100, Math.round(len / max * 100)) + '%';
            bar.style.backgroundColor = len > max * 0.92 ? '#ef4444'
                                       : len > max * 0.70 ? '#f59e0b'
                                       : '#22c55e';
        }

        const metaTitle = document.getElementById('meta-title-input');
        const metaDesc  = document.getElementById('meta-desc-input');
        const serpTitle = document.getElementById('serp-title');
        const serpDesc  = document.getElementById('serp-desc');
        const blogTitle = document.getElementById('title-editor');

        metaTitle?.addEventListener('input', function () {
            seoBar('meta-title-bar', 'meta-title-count', this, 60);
            if (serpTitle) serpTitle.textContent = this.value || blogTitle?.innerText || '—';
        });

        metaDesc?.addEventListener('input', function () {
            seoBar('meta-desc-bar', 'meta-desc-count', this, 160);
            if (serpDesc) serpDesc.textContent = this.value || '—';
        });

        // Update SERP title from blog title when meta_title is empty
        blogTitle?.addEventListener('input', function () {
            if (!metaTitle?.value && serpTitle) serpTitle.textContent = this.innerText;
        });
    });
</script>
@endpush