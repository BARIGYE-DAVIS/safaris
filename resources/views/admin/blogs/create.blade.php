{{-- resources/views/admin/blogs/create.blade.php --}}
@extends('layouts.admin')

@section('title', 'Create Blog Post')

@section('content')

{{-- Page wrapper --}}
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 pb-44">

    {{-- ── Header ── --}}
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
      <div>
        <div class="flex items-center gap-3 mb-1">
          <div class="w-2 h-8 bg-indigo-500 rounded-full"></div>
          <h1 class="text-3xl font-extrabold text-white tracking-tight">New Blog Post</h1>
        </div>
        <p class="text-slate-400 ml-5">Craft your story — title, excerpt &amp; body all support rich colours</p>
      </div>
      <div class="flex gap-3">
        <button type="button" id="preview-btn"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold rounded-xl transition-all shadow-lg shadow-indigo-900/40 hover:shadow-indigo-700/50">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
          </svg>
          Preview
        </button>
        <a href="{{ route('admin.blogs.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-700 hover:bg-slate-600 text-slate-200 text-sm font-semibold rounded-xl transition-all border border-slate-600">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
          </svg>
          Back
        </a>
      </div>
    </div>

    {{-- ── Validation errors ── --}}
    @if($errors->any())
      <div class="mb-6 bg-red-500/10 border border-red-500/50 text-red-400 px-5 py-4 rounded-xl">
        <p class="font-semibold mb-2 flex items-center gap-2">
          <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
          </svg>
          Please fix the following:
        </p>
        <ul class="list-disc list-inside space-y-1 text-sm">
          @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
      </div>
    @endif

    {{-- ── Main form ── --}}
    <form method="POST"
          action="{{ route('admin.blogs.store') }}"
          enctype="multipart/form-data"
          id="blog-form">
      @csrf

      {{-- Hidden inputs synced on submit --}}
      <input type="hidden" name="title"        id="title-hidden">
      <input type="hidden" name="excerpt"      id="excerpt-hidden">
      <input type="hidden" name="content"      id="content-hidden">
      <input type="hidden" name="content_json" id="content-json-hidden">

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- ════════════════════════════════════════
             LEFT — editors
        ════════════════════════════════════════ --}}
        <div class="lg:col-span-2 space-y-5">

          {{-- ── Title ── --}}
          <div class="bg-slate-800/60 backdrop-blur border border-slate-700 rounded-2xl p-6 ring-1 ring-white/5">
            <label class="flex items-center gap-2 text-sm font-semibold text-slate-300 mb-3">
              <span class="text-red-400">*</span>
              Blog Title
              <span class="text-xs font-normal text-slate-500">— select text, use colour tools below</span>
            </label>
            <div id="title-editor"
                 contenteditable="true"
                 spellcheck="true"
                 data-placeholder="Write a compelling title…"
                 class="colorable-field w-full px-4 py-3
                        bg-slate-900 border border-slate-600 rounded-xl
                        text-white text-xl font-bold
                        focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20
                        transition-all duration-200 cursor-text"
                 style="min-height:3rem;line-height:1.5;white-space:nowrap;overflow-x:auto;"
            >{!! old('title') !!}</div>
          </div>

          {{-- ── Excerpt ── --}}
          <div class="bg-slate-800/60 backdrop-blur border border-slate-700 rounded-2xl p-6 ring-1 ring-white/5">
            <label class="flex items-center gap-2 text-sm font-semibold text-slate-300 mb-3">
              Excerpt
              <span class="text-xs font-normal text-slate-500">— select text, use colour tools below</span>
            </label>
            <div id="excerpt-editor"
                 contenteditable="true"
                 spellcheck="true"
                 data-placeholder="A short summary readers see in listings…"
                 class="colorable-field w-full px-4 py-3
                        bg-slate-900 border border-slate-600 rounded-xl
                        text-white
                        focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20
                        transition-all duration-200 cursor-text"
                 style="min-height:5.5rem;line-height:1.7;white-space:pre-wrap;word-break:break-word;"
            >{!! old('excerpt') !!}</div>
          </div>

          {{-- ── Content Editor ── --}}
          <div class="bg-slate-800/60 backdrop-blur border border-slate-700 rounded-2xl overflow-hidden ring-1 ring-white/5">

            {{-- Header bar --}}
            <div class="bg-slate-800 border-b border-slate-700 px-6 py-4 flex items-center gap-3">
              <div class="flex gap-1.5">
                <div class="w-3 h-3 rounded-full bg-red-500/70"></div>
                <div class="w-3 h-3 rounded-full bg-yellow-500/70"></div>
                <div class="w-3 h-3 rounded-full bg-green-500/70"></div>
              </div>
              <div class="flex-1">
                <h3 class="text-sm font-bold text-white flex items-center gap-2">
                  <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                  </svg>
                  Content
                </h3>
              </div>
              {{-- Active field indicator --}}
              <div class="flex items-center gap-2">
                <span class="text-xs text-slate-500">Editing:</span>
                <span id="field-indicator"
                      class="text-xs font-bold px-2.5 py-1 rounded-full bg-indigo-600/20 border border-indigo-500/40 text-indigo-300 transition-all">
                  — click a field —
                </span>
              </div>
            </div>

            {{-- Editable area --}}
            <div id="editor"
                 contenteditable="true"
                 spellcheck="true"
                 data-placeholder="Start writing your post here…"
                 class="colorable-field min-h-[560px] p-8
                        bg-slate-950 text-slate-200
                        focus:outline-none leading-relaxed cursor-text"
                 style="line-height:1.85;white-space:pre-wrap;word-break:break-word;font-size:1rem;">
              {!! old('content') !!}
            </div>

            {{-- Stats bar --}}
            <div class="bg-slate-800 border-t border-slate-700 px-6 py-2.5 flex gap-6 text-xs text-slate-500">
              <span>
                <span id="char-count" class="font-semibold text-slate-300">0</span> chars
              </span>
              <span>
                <span id="word-count" class="font-semibold text-slate-300">0</span> words
              </span>
              <span>
                <span id="image-count" class="font-semibold text-slate-300">0</span> images
              </span>
              <span class="ml-auto" id="autosave-status"></span>
            </div>
          </div>

        </div>{{-- /left --}}

        {{-- ════════════════════════════════════════
             RIGHT — settings sidebar
        ════════════════════════════════════════ --}}
        <div class="lg:col-span-1 space-y-5">

          {{-- Publish settings --}}
          <div class="bg-slate-800/60 backdrop-blur border border-slate-700 rounded-2xl p-6 ring-1 ring-white/5">
            <h3 class="text-base font-bold text-white mb-4 flex items-center gap-2">
              <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              Publish Settings
            </h3>
            <div class="space-y-4">
              <div>
                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Status</label>
                <select name="status" required
                        class="w-full px-3 py-2.5 bg-slate-900 border border-slate-600 rounded-xl text-white text-sm
                               focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition">
                  <option value="draft"     {{ old('status','draft') == 'draft'     ? 'selected':'' }}>📝 Draft</option>
                  <option value="published" {{ old('status')         == 'published' ? 'selected':'' }}>✅ Published</option>
                  <option value="scheduled" {{ old('status')         == 'scheduled' ? 'selected':'' }}>⏰ Scheduled</option>
                </select>
              </div>
              <div>
                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Publish Date</label>
                <input type="datetime-local" name="published_at"
                       value="{{ old('published_at') }}"
                       class="w-full px-3 py-2.5 bg-slate-900 border border-slate-600 rounded-xl text-white text-sm
                              focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition">
              </div>
              <label class="flex items-center gap-3 p-3 bg-slate-900 rounded-xl border border-slate-700 cursor-pointer hover:border-indigo-500/50 transition">
                <input type="checkbox" name="is_featured" value="1"
                       {{ old('is_featured') ? 'checked' : '' }}
                       class="w-4 h-4 text-indigo-600 bg-slate-900 border-slate-600 rounded focus:ring-indigo-500">
                <span class="text-sm text-white font-medium">⭐ Featured Post</span>
              </label>
            </div>
          </div>

          {{-- Category --}}
          <div class="bg-slate-800/60 backdrop-blur border border-slate-700 rounded-2xl p-6 ring-1 ring-white/5">
            <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Category</label>
            <select name="category_id"
                    class="w-full px-3 py-2.5 bg-slate-900 border border-slate-600 rounded-xl text-white text-sm
                           focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition">
              <option value="">— No Category —</option>
              @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected':'' }}>
                  {{ $category->name }}
                </option>
              @endforeach
            </select>
          </div>

          {{-- Tags --}}
          <div class="bg-slate-800/60 backdrop-blur border border-slate-700 rounded-2xl p-6 ring-1 ring-white/5">
            <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Tags</label>
            <input type="text" name="tags" value="{{ old('tags') }}"
                   placeholder="safari, wildlife, kenya"
                   class="w-full px-3 py-2.5 bg-slate-900 border border-slate-600 rounded-xl text-white text-sm
                          placeholder-slate-600 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition">
            <p class="mt-1.5 text-xs text-slate-600">Separate with commas</p>
          </div>

          {{-- Featured image --}}
          <div class="bg-slate-800/60 backdrop-blur border border-slate-700 rounded-2xl p-6 ring-1 ring-white/5">
            <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3">Featured Image</label>

            {{-- Drop zone --}}
            <label id="featured-drop-zone"
                   class="flex flex-col items-center justify-center w-full h-36 rounded-xl
                          border-2 border-dashed border-slate-600 bg-slate-900
                          hover:border-indigo-500 hover:bg-slate-900/80
                          cursor-pointer transition-all group">
              <svg class="w-8 h-8 text-slate-600 group-hover:text-indigo-400 transition mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
              </svg>
              <span class="text-xs text-slate-500 group-hover:text-slate-300 transition">Click or drag image here</span>
              <input type="file" name="featured_image" id="featured-image-input" accept="image/*" class="hidden">
            </label>

            {{-- Preview --}}
            <div id="featured-preview" class="hidden mt-3 relative group/fp">
              <img id="featured-preview-img" src="" alt="Preview"
                   class="w-full h-36 object-cover rounded-xl border border-slate-700">
              <button type="button" id="remove-featured"
                      class="absolute top-2 right-2 w-7 h-7 bg-red-600 hover:bg-red-500 text-white rounded-full
                             flex items-center justify-center opacity-0 group-hover/fp:opacity-100 transition-opacity text-sm">
                ✕
              </button>
            </div>

            <p class="mt-2 text-xs text-slate-600">Max 5 MB · JPG, PNG, WebP</p>
          </div>

          {{-- Author --}}
          <div class="bg-slate-800/60 backdrop-blur border border-slate-700 rounded-2xl p-6 ring-1 ring-white/5">
            <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Author Name</label>
            <input type="text" name="author_name" value="{{ old('author_name') }}"
                   placeholder="John Doe"
                   class="w-full px-3 py-2.5 bg-slate-900 border border-slate-600 rounded-xl text-white text-sm
                          placeholder-slate-600 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition">
          </div>

          {{-- Reading Time --}}
          <div class="bg-slate-800/60 backdrop-blur border border-slate-700 rounded-2xl p-6 ring-1 ring-white/5">
            <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">
              Reading Time
              <span class="ml-1 normal-case font-normal text-slate-600">(mins — auto-calculated if blank)</span>
            </label>
            <input type="number" name="reading_time" min="1" max="120"
                   value="{{ old('reading_time') }}"
                   placeholder="e.g. 5"
                   class="w-full px-3 py-2.5 bg-slate-900 border border-slate-600 rounded-xl text-white text-sm
                          placeholder-slate-600 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition">
          </div>

          {{-- SEO --}}
          <div class="bg-slate-800/60 backdrop-blur border border-slate-700 rounded-2xl p-6 ring-1 ring-white/5">
            <h3 class="text-base font-bold text-white mb-4 flex items-center gap-2">
              <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
              </svg>
              SEO Settings
            </h3>
            <div class="space-y-4">

              {{-- Meta Title --}}
              <div>
                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">
                  Meta Title
                  <span class="ml-1 normal-case font-normal text-slate-600" id="meta-title-count">(0/60)</span>
                </label>
                <input type="text" name="meta_title" id="meta-title-input" maxlength="60"
                       value="{{ old('meta_title') }}"
                       placeholder="Leave blank to use blog title"
                       class="w-full px-3 py-2.5 bg-slate-900 border border-slate-600 rounded-xl text-white text-sm
                              placeholder-slate-600 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition">
                {{-- Progress bar --}}
                <div class="mt-1.5 h-1 rounded-full bg-slate-700 overflow-hidden">
                  <div id="meta-title-bar" class="h-full rounded-full bg-emerald-500 transition-all" style="width:0%"></div>
                </div>
              </div>

              {{-- Meta Description --}}
              <div>
                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">
                  Meta Description
                  <span class="ml-1 normal-case font-normal text-slate-600" id="meta-desc-count">(0/160)</span>
                </label>
                <textarea name="meta_description" id="meta-desc-input" maxlength="160" rows="3"
                          placeholder="Leave blank to use excerpt"
                          class="w-full px-3 py-2.5 bg-slate-900 border border-slate-600 rounded-xl text-white text-sm
                                 placeholder-slate-600 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition resize-none">{{ old('meta_description') }}</textarea>
                <div class="mt-1.5 h-1 rounded-full bg-slate-700 overflow-hidden">
                  <div id="meta-desc-bar" class="h-full rounded-full bg-emerald-500 transition-all" style="width:0%"></div>
                </div>
              </div>

              {{-- Meta Keywords --}}
              <div>
                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Meta Keywords</label>
                <input type="text" name="meta_keywords" maxlength="500"
                       value="{{ old('meta_keywords') }}"
                       placeholder="keyword1, keyword2, keyword3"
                       class="w-full px-3 py-2.5 bg-slate-900 border border-slate-600 rounded-xl text-white text-sm
                              placeholder-slate-600 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition">
                <p class="mt-1.5 text-xs text-slate-600">Separate with commas</p>
              </div>

              {{-- Google SERP preview --}}
              <div class="mt-2 p-3 bg-white rounded-xl text-left">
                <p class="text-xs text-slate-400 mb-1.5 font-semibold uppercase tracking-wider">Google Preview</p>
                <p id="serp-title" class="text-blue-700 text-sm font-medium truncate leading-snug">
                  {{ old('meta_title', 'Your blog title will appear here') }}
                </p>
                <p class="text-green-700 text-xs truncate">{{ url('/blog/your-post-slug') }}</p>
                <p id="serp-desc" class="text-slate-600 text-xs mt-0.5 leading-relaxed line-clamp-2">
                  {{ old('meta_description', 'Your meta description will appear here. Write something compelling to improve click-through rate.') }}
                </p>
              </div>

            </div>
          </div>

          {{-- Submit --}}
          <button type="submit"
                  class="w-full bg-gradient-to-r from-indigo-600 to-purple-600
                         hover:from-indigo-500 hover:to-purple-500
                         text-white font-bold py-4 px-6 rounded-2xl
                         shadow-lg shadow-indigo-900/40 hover:shadow-indigo-700/50
                         transition-all transform hover:scale-[1.02] active:scale-[0.98]
                         flex items-center justify-center gap-2 text-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 4v16m8-8H4"/>
            </svg>
            Publish Blog Post
          </button>

        </div>{{-- /right --}}
      </div>{{-- /grid --}}
    </form>
  </div>{{-- /container --}}

  {{-- ════════════════════════════════════════════════════════════════════════
       BOTTOM FLOATING TOOLBAR  — pure Tailwind, no custom CSS classes
  ════════════════════════════════════════════════════════════════════════ --}}
  <div class="fixed bottom-0 left-0 right-0
              bg-slate-900/95 backdrop-blur-md
              border-t-2 border-slate-700
              px-4 py-3 shadow-2xl shadow-black/60 z-50">
    <div class="max-w-7xl mx-auto space-y-2">

      {{-- Row 1: Theme swatches --}}
      <div class="flex items-center gap-2 pb-2 border-b border-slate-700 flex-wrap">
        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest mr-1 whitespace-nowrap">Themes:</span>

        {{-- Each swatch is a real Tailwind bg --}}
        <button type="button" data-theme="dark"
                class="theme-swatch w-8 h-8 rounded-lg border-2 border-slate-600 hover:scale-110 transition-transform bg-slate-900 ring-0"
                title="Dark"></button>
        <button type="button" data-theme="light"
                class="theme-swatch w-8 h-8 rounded-lg border-2 border-slate-300 hover:scale-110 transition-transform bg-white ring-0"
                title="Light"></button>
        <button type="button" data-theme="green"
                class="theme-swatch w-8 h-8 rounded-lg border-2 border-emerald-600 hover:scale-110 transition-transform bg-emerald-800 ring-0"
                title="Green"></button>
        <button type="button" data-theme="yellow"
                class="theme-swatch w-8 h-8 rounded-lg border-2 border-amber-500 hover:scale-110 transition-transform bg-amber-800 ring-0"
                title="Sunshine"></button>
        <button type="button" data-theme="blue"
                class="theme-swatch w-8 h-8 rounded-lg border-2 border-blue-500 hover:scale-110 transition-transform bg-blue-800 ring-0"
                title="Ocean Blue"></button>
        <button type="button" data-theme="gray"
                class="theme-swatch w-8 h-8 rounded-lg border-2 border-slate-500 hover:scale-110 transition-transform bg-slate-600 ring-0"
                title="Gray"></button>
        <button type="button" data-theme="purple"
                class="theme-swatch w-8 h-8 rounded-lg border-2 border-purple-500 hover:scale-110 transition-transform bg-purple-800 ring-0"
                title="Purple"></button>
        <button type="button" data-theme="pink"
                class="theme-swatch w-8 h-8 rounded-lg border-2 border-pink-500 hover:scale-110 transition-transform bg-pink-800 ring-0"
                title="Pink"></button>

        {{-- Active field badge (far right) --}}
        <div class="ml-auto flex items-center gap-2 shrink-0">
          <span class="text-xs text-slate-500 hidden sm:block">Coloring:</span>
          <span id="toolbar-field-indicator"
                class="text-xs font-bold px-3 py-1 rounded-full
                       bg-indigo-600/20 border border-indigo-500/50 text-indigo-300
                       transition-all whitespace-nowrap">
            click a field
          </span>
        </div>
      </div>

      {{-- Row 2: Format tools --}}
      <div class="flex items-center gap-1.5 flex-wrap">

        {{-- Bold / Italic / Underline --}}
        <div class="flex gap-1 pr-2 border-r border-slate-700">
          <button type="button" data-format="bold"
                  class="format-btn px-2.5 py-1.5 rounded-lg bg-slate-800 hover:bg-indigo-600 text-slate-300 hover:text-white text-xs font-black border border-slate-700 hover:border-indigo-500 transition-all">
            B
          </button>
          <button type="button" data-format="italic"
                  class="format-btn px-2.5 py-1.5 rounded-lg bg-slate-800 hover:bg-indigo-600 text-slate-300 hover:text-white text-xs italic font-semibold border border-slate-700 hover:border-indigo-500 transition-all">
            I
          </button>
          <button type="button" data-format="underline"
                  class="format-btn px-2.5 py-1.5 rounded-lg bg-slate-800 hover:bg-indigo-600 text-slate-300 hover:text-white text-xs underline font-semibold border border-slate-700 hover:border-indigo-500 transition-all">
            U
          </button>
        </div>

        {{-- Headings --}}
        <div class="flex gap-1 pr-2 border-r border-slate-700 items-baseline">
          <button type="button" data-format="h1"
                  class="format-btn px-2.5 py-1 rounded-lg bg-slate-800 hover:bg-indigo-600 text-slate-300 hover:text-white border border-slate-700 hover:border-indigo-500 transition-all leading-none"
                  style="font-size:1.2rem;font-weight:800;">H1</button>
          <button type="button" data-format="h2"
                  class="format-btn px-2.5 py-1 rounded-lg bg-slate-800 hover:bg-indigo-600 text-slate-300 hover:text-white border border-slate-700 hover:border-indigo-500 transition-all leading-none"
                  style="font-size:0.95rem;font-weight:700;">H2</button>
          <button type="button" data-format="h3"
                  class="format-btn px-2.5 py-1 rounded-lg bg-slate-800 hover:bg-indigo-600 text-slate-300 hover:text-white border border-slate-700 hover:border-indigo-500 transition-all leading-none"
                  style="font-size:0.78rem;font-weight:600;">H3</button>
          <button type="button" data-format="p"
                  class="format-btn px-2.5 py-1 rounded-lg bg-slate-800 hover:bg-indigo-600 text-slate-300 hover:text-white text-xs border border-slate-700 hover:border-indigo-500 transition-all">
            ¶ P
          </button>
        </div>

        {{-- Lists --}}
        <div class="flex gap-1 pr-2 border-r border-slate-700">
          <button type="button" data-format="ul"
                  class="format-btn px-2.5 py-1.5 rounded-lg bg-slate-800 hover:bg-indigo-600 text-slate-300 hover:text-white text-xs border border-slate-700 hover:border-indigo-500 transition-all whitespace-nowrap">
            • List
          </button>
          <button type="button" data-format="ol"
                  class="format-btn px-2.5 py-1.5 rounded-lg bg-slate-800 hover:bg-indigo-600 text-slate-300 hover:text-white text-xs border border-slate-700 hover:border-indigo-500 transition-all whitespace-nowrap">
            1. List
          </button>
        </div>

        {{-- Alignment --}}
        <div class="flex gap-1 pr-2 border-r border-slate-700">
          <button type="button" data-format="justifyLeft"
                  class="format-btn px-2.5 py-1.5 rounded-lg bg-slate-800 hover:bg-indigo-600 text-slate-300 hover:text-white text-xs border border-slate-700 hover:border-indigo-500 transition-all"
                  title="Align left">⬅</button>
          <button type="button" data-format="justifyCenter"
                  class="format-btn px-2.5 py-1.5 rounded-lg bg-slate-800 hover:bg-indigo-600 text-slate-300 hover:text-white text-xs border border-slate-700 hover:border-indigo-500 transition-all"
                  title="Center">⬌</button>
          <button type="button" data-format="justifyRight"
                  class="format-btn px-2.5 py-1.5 rounded-lg bg-slate-800 hover:bg-indigo-600 text-slate-300 hover:text-white text-xs border border-slate-700 hover:border-indigo-500 transition-all"
                  title="Align right">➡</button>
        </div>

        {{-- Text colour --}}
        <div class="flex items-center gap-1.5 px-3 py-1.5 bg-slate-800 border border-slate-700 rounded-xl">
          <span class="text-xs font-black text-slate-400 select-none">A</span>
          <input type="color" id="text-color" value="#ff6b35"
                 class="w-8 h-8 rounded-lg border border-slate-600 cursor-pointer p-0.5 bg-transparent"
                 title="Text colour">
          <button type="button" id="apply-text-color"
                  class="px-2.5 py-1 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold rounded-lg transition-all">
            Apply
          </button>
          <button type="button" id="remove-text-color"
                  class="px-2.5 py-1 bg-slate-700 hover:bg-slate-600 text-slate-300 text-xs font-bold rounded-lg transition-all">
            Clear
          </button>
        </div>

        {{-- Highlight / BG colour --}}
        <div class="flex items-center gap-1.5 px-3 py-1.5 bg-slate-800 border border-slate-700 rounded-xl">
          <span class="text-xs font-bold text-slate-400 select-none">BG</span>
          <input type="color" id="bg-color" value="#fbbf24"
                 class="w-8 h-8 rounded-lg border border-slate-600 cursor-pointer p-0.5 bg-transparent"
                 title="Highlight colour">
          <button type="button" id="apply-bg-color"
                  class="px-2.5 py-1 bg-amber-600 hover:bg-amber-500 text-white text-xs font-bold rounded-lg transition-all">
            Apply
          </button>
          <button type="button" id="remove-bg-color"
                  class="px-2.5 py-1 bg-slate-700 hover:bg-slate-600 text-slate-300 text-xs font-bold rounded-lg transition-all">
            Clear
          </button>
        </div>

        {{-- Image upload (content editor only) --}}
        <button type="button" id="insert-image"
                class="inline-flex items-center gap-1.5 px-3 py-1.5
                       bg-emerald-700 hover:bg-emerald-600
                       text-white text-xs font-bold rounded-xl
                       border border-emerald-600 hover:border-emerald-400
                       transition-all">
          <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"/>
          </svg>
          Image
        </button>
        <input type="file" id="image-upload" accept="image/*" class="hidden">

        {{-- Link --}}
        <button type="button" id="insert-link"
                class="inline-flex items-center gap-1.5 px-3 py-1.5
                       bg-slate-800 hover:bg-indigo-600
                       text-slate-300 hover:text-white text-xs font-bold rounded-xl
                       border border-slate-700 hover:border-indigo-500
                       transition-all">
          🔗 Link
        </button>

      </div>{{-- /row 2 --}}
    </div>
  </div>{{-- /toolbar --}}

</div>{{-- /page wrapper --}}

{{-- ════════════════════════════════════════════════════════════════════════
     PREVIEW MODAL
════════════════════════════════════════════════════════════════════════ --}}
<div id="preview-modal"
     class="fixed inset-0 bg-black/85 backdrop-blur-sm z-[60] hidden items-center justify-center p-4"
     style="display:none;">
  <div class="bg-slate-900 border border-slate-700 rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden shadow-2xl">
    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-700">
      <h3 class="text-lg font-bold text-white">Preview</h3>
      <button type="button" id="close-preview"
              class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white transition-all">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
    </div>
    <div id="preview-content"
         class="px-8 py-6 overflow-y-auto max-h-[calc(90vh-4rem)] text-slate-200"
         style="font-size:1rem;line-height:1.8;"></div>
  </div>
</div>

{{-- Minimal CSS only for things Tailwind can't do (pseudo-elements, editor typography) --}}
<style>
  /* Placeholder via ::before on empty contenteditable */
  .colorable-field:empty::before {
    content: attr(data-placeholder);
    color: #475569;
    pointer-events: none;
    display: block;
  }
  /* Active glow ring */
  .colorable-field.color-target {
    outline: none !important;
    box-shadow: 0 0 0 3px rgba(99,102,241,0.5), 0 0 20px rgba(99,102,241,0.12) !important;
    border-color: #6366f1 !important;
  }
  /* Theme-aware active format button */
  .format-btn.is-active {
    background-color: #4f46e5 !important;
    color: #fff !important;
    border-color: #818cf8 !important;
    box-shadow: 0 0 0 2px rgba(129,140,248,0.4);
  }

  /* ── Editor typography ──────────────────────── */
  #editor h1 { font-size:2.1rem;  font-weight:800; line-height:1.2;  margin:1.5rem 0 .7rem;  color:#f1f5f9; border-bottom:1px solid #334155; padding-bottom:.35rem; }
  #editor h2 { font-size:1.6rem;  font-weight:700; line-height:1.3;  margin:1.3rem 0 .55rem; color:#f1f5f9; }
  #editor h3 { font-size:1.2rem;  font-weight:600; line-height:1.4;  margin:1.1rem 0 .45rem; color:#cbd5e1; }
  #editor p  { font-size:1rem;    line-height:1.85; margin:.65rem 0; color:#cbd5e1; }
  #editor ul, #editor ol { padding-left:2rem; margin:.65rem 0; }
  #editor ul { list-style-type: disc; }
  #editor ol { list-style-type: decimal; }
  #editor li { display:list-item; margin:.35rem 0; line-height:1.7; color:#cbd5e1; }
  #editor ul ul   { list-style-type:circle;      padding-left:1.4rem; margin:.2rem 0; }
  #editor ol ol   { list-style-type:lower-alpha;  padding-left:1.4rem; margin:.2rem 0; }
  #editor strong, #editor b { font-weight:700; color:#f8fafc; }
  #editor em, #editor i     { font-style:italic; }
  #editor a  { color:#60a5fa; text-decoration:underline; }
  #editor blockquote { border-left:3px solid #475569; padding-left:1rem; margin:1rem 0; color:#94a3b8; font-style:italic; }

  /* Same for preview */
  #preview-content h1 { font-size:2.1rem;  font-weight:800; line-height:1.2;  margin:1.5rem 0 .7rem;  border-bottom:1px solid #334155; padding-bottom:.35rem; }
  #preview-content h2 { font-size:1.6rem;  font-weight:700; margin:1.3rem 0 .55rem; }
  #preview-content h3 { font-size:1.2rem;  font-weight:600; margin:1.1rem 0 .45rem; color:#cbd5e1; }
  #preview-content p  { margin:.65rem 0; line-height:1.85; }
  #preview-content ul { list-style-type:disc;    padding-left:2rem; margin:.65rem 0; }
  #preview-content ol { list-style-type:decimal; padding-left:2rem; margin:.65rem 0; }
  #preview-content li { margin:.3rem 0; line-height:1.7; }
  #preview-content a  { color:#60a5fa; text-decoration:underline; }
  #preview-content strong, #preview-content b { font-weight:700; }
  #preview-content blockquote { border-left:3px solid #475569; padding-left:1rem; font-style:italic; margin:1rem 0; color:#94a3b8; }

  /* Image wrappers inside editor */
  .image-wrapper { position:relative; display:block; margin:1.5rem 0; }
  .image-wrapper img { max-width:100%; height:auto; border-radius:.6rem; display:block; border:1px solid #334155; }
</style>

@endsection

@push('scripts')
<script src="{{ asset('js/blog-create.js') }}"></script>
<script>
  window.addEventListener('DOMContentLoaded', function () {
    if (typeof BlogCreate !== 'undefined') {
      BlogCreate.init({
        uploadUrl:  '{{ route("admin.blogs.uploadImage") }}',
        csrfToken:  '{{ csrf_token() }}'
      });
    }

    // ── SEO live counters & SERP preview ──────────────────────────────
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

    const metaTitle   = document.getElementById('meta-title-input');
    const metaDesc    = document.getElementById('meta-desc-input');
    const serpTitle   = document.getElementById('serp-title');
    const serpDesc    = document.getElementById('serp-desc');
    const titleEditor = document.getElementById('title-editor');

    // Meta title typing → bar + SERP
    metaTitle?.addEventListener('input', function () {
      seoBar('meta-title-bar', 'meta-title-count', this, 60);
      if (serpTitle) serpTitle.textContent = this.value || titleEditor?.innerText || 'Your blog title will appear here';
    });

    // Meta description typing → bar + SERP
    metaDesc?.addEventListener('input', function () {
      seoBar('meta-desc-bar', 'meta-desc-count', this, 160);
      if (serpDesc) serpDesc.textContent = this.value
        || 'Your meta description will appear here. Write something compelling.';
    });

    // Blog title typing → update SERP title when meta_title is empty
    titleEditor?.addEventListener('input', function () {
      if (!metaTitle?.value && serpTitle) serpTitle.textContent = this.innerText || 'Your blog title will appear here';
    });
  });
</script>
    
<script src="{{ asset('js/blog-import.js') }}"></script>
@endpush