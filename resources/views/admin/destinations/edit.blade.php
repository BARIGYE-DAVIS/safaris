@extends('layouts.admin')

@section('title', 'Edit Destination')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Edit Destination</h1>
            <p class="text-gray-600 mt-1">Modify destination details and inline content</p>
        </div>
        <a href="{{ route('admin.destinations.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center transition">
            <i class="fas fa-arrow-left mr-2"></i> Back to List
        </a>
    </div>

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            {{ session('error') }}
        </div>
    @endif

    <form id="destinationEditForm" action="{{ route('admin.destinations.update', $destination->id) }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-lg rounded-lg">
        @csrf
        @method('PUT')

        <div class="border-b border-gray-200">
            <nav class="flex -mb-px overflow-x-auto">
                <button type="button" class="tab-button active px-6 py-3 border-b-2 font-medium text-sm whitespace-nowrap" data-tab="basic">Basic Info</button>
                <button type="button" class="tab-button px-6 py-3 border-b-2 font-medium text-sm whitespace-nowrap" data-tab="overview">Overview</button>
                <button type="button" class="tab-button px-6 py-3 border-b-2 font-medium text-sm whitespace-nowrap" data-tab="activities">Activities</button>
                <button type="button" class="tab-button px-6 py-3 border-b-2 font-medium text-sm whitespace-nowrap" data-tab="wildlife">Wildlife</button>
                <button type="button" class="tab-button px-6 py-3 border-b-2 font-medium text-sm whitespace-nowrap" data-tab="geography">Geography</button>
                <button type="button" class="tab-button px-6 py-3 border-b-2 font-medium text-sm whitespace-nowrap" data-tab="practical">Practical Info</button>
                <button type="button" class="tab-button px-6 py-3 border-b-2 font-medium text-sm whitespace-nowrap" data-tab="accommodation">Accommodation</button>
                <button type="button" class="tab-button px-6 py-3 border-b-2 font-medium text-sm whitespace-nowrap" data-tab="extras">Extras</button>
                <button type="button" class="tab-button px-6 py-3 border-b-2 font-medium text-sm whitespace-nowrap" data-tab="images">Images</button>
                <button type="button" class="tab-button px-6 py-3 border-b-2 font-medium text-sm whitespace-nowrap" data-tab="seo">SEO</button>
            </nav>
        </div>

        <div class="p-6">
            <!-- Basic Info -->
            <div id="tab-basic" class="tab-content">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Basic Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="country_id" class="block text-sm font-medium text-gray-700 mb-2">Country <span class="text-red-500">*</span></label>
                        <select name="country_id" id="country_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            <option value="">Select Country</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}" {{ old('country_id', $destination->country_id) == $country->id ? 'selected' : '' }}>
                                    {!! $country->flag_icon !!} {{ $country->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Destination Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="{{ old('name', $destination->name) }}" placeholder="e.g., Murchison Falls National Park">
                    </div>

                    <div>
                        <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">URL Slug</label>
                        <input type="text" name="slug" id="slug" class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="{{ old('slug', $destination->slug) }}" placeholder="murchison-falls-national-park">
                        <p class="text-gray-500 text-xs mt-1">Leave empty to auto-generate from name</p>
                    </div>

                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Destination Type</label>
                        <select name="type" id="type" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            <option value="">Select Type</option>
                            @foreach(['National Park','Wildlife Reserve','Forest Reserve','Game Reserve','Conservation Area','Wildlife Sanctuary','City','Lake','Mountain','Island'] as $t)
                                <option value="{{ $t }}" {{ old('type', $destination->type) == $t ? 'selected' : '' }}>{{ $t }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">Sort Order</label>
                        <input type="number" name="sort_order" id="sort_order" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="{{ old('sort_order', $destination->sort_order) }}">
                        <p class="text-gray-500 text-xs mt-1">Lower numbers appear first</p>
                    </div>
                </div>

                <div class="mt-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Short Description</label>
                    <textarea name="description" id="description" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="Short overview...">{{ old('description', $destination->description) }}</textarea>
                </div>

                <div class="mt-6 space-y-3">
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" class="w-5 h-5 rounded border-gray-300 text-green-600" {{ old('is_active', $destination->is_active) ? 'checked' : '' }}>
                        <div><span class="text-sm font-medium text-gray-700">Active</span><p class="text-xs text-gray-500">Visible on site</p></div>
                    </label>

                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" name="is_popular" value="1" class="w-5 h-5 rounded border-gray-300 text-green-600" {{ old('is_popular', $destination->is_popular) ? 'checked' : '' }}>
                        <div><span class="text-sm font-medium text-gray-700">Mark as Popular</span><p class="text-xs text-gray-500">Feature on homepage</p></div>
                    </label>
                </div>
            </div>

            {{-- Sections rendering --}}
            @php
                $sectionsList = [
                    'overview' => ['label'=>'Detailed Overview','textarea'=>'detailed_overview'],
                    'activities' => ['label'=>'Activities','textarea'=>'what_to_see_do'],
                    'wildlife' => ['label'=>'Wildlife Highlights','textarea'=>'wildlife_highlights'],
                    'geography' => ['label'=>'Geography & Landscape','textarea'=>'geography_landscape'],
                    'practical' => ['label'=>'Practical Information','textarea'=>'practical_information'],
                    'accommodation' => ['label'=>'Accommodation Options','textarea'=>'accommodation_options'],
                    'extras' => ['label'=>'Additional Information','textarea'=>'interesting_facts'],
                ];
            @endphp

            @foreach($sectionsList as $sectionKey => $cfg)
                @php
                    // build textarea content from existing sections_content if present, otherwise fall back to legacy field
                    $textareaId = $cfg['textarea'];
                    $initialText = old($textareaId, '');
                    $sectionsContent = $destination->sections_content ?? [];
                    if (empty($initialText)) {
                        if (!empty($sectionsContent[$sectionKey]) && is_array($sectionsContent[$sectionKey])) {
                            // convert blocks into textarea tokens (headings, text, image tokens)
                            $parts = [];
                            foreach ($sectionsContent[$sectionKey] as $block) {
                                $type = $block['type'] ?? 'text';
                                if ($type === 'heading') {
                                    $parts[] = '# ' . ($block['text'] ?? '');
                                } elseif ($type === 'subheading') {
                                    $parts[] = '## ' . ($block['text'] ?? '');
                                } elseif ($type === 'text') {
                                    $parts[] = $block['text'] ?? '';
                                } elseif ($type === 'image') {
                                    // existing images reference media_id or block_id
                                    if (!empty($block['media_id'])) {
                                        $parts[] = '[[' . 'image:media-' . $block['media_id'] . '|' . ($block['caption'] ?? '') . ']]';
                                    } else {
                                        $parts[] = '[[' . 'image:block-' . ($block['id'] ?? Str::random(6)) . '|' . ($block['caption'] ?? '') . ']]';
                                    }
                                }
                            }
                            $initialText = implode("\n\n", $parts);
                        } else {
                            // fallback to legacy fields
                            $initialText = old($textareaId, $destination->{$textareaId} ?? '');
                        }
                    }
                @endphp

                <div id="tab-{{ $sectionKey }}" class="tab-content {{ $sectionKey !== 'overview' ? 'hidden' : '' }}">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">{{ $cfg['label'] }}</h2>

                    <div>
                        <label for="{{ $textareaId }}" class="block text-sm font-medium text-gray-700 mb-2">{{ $cfg['label'] }} Content</label>
                        <textarea name="{{ $textareaId }}" id="{{ $textareaId }}" rows="12" class="section-textarea w-full px-4 py-2 border border-gray-300 rounded-lg font-mono text-sm" placeholder="Write content...">{{ $initialText }}</textarea>
                        <p class="text-gray-500 text-xs mt-1">Use headings (# Heading), subheadings (## Subheading), paragraphs, and image placeholders like <code>[[image:media-123|Caption]]</code>.</p>
                    </div>

                    <div class="mt-4">
                        <div class="flex flex-wrap gap-2 items-center mb-3">
                            <button type="button" class="px-3 py-2 bg-indigo-600 text-white rounded-lg insert-heading" data-section="{{ $sectionKey }}">Add Heading</button>
                            <button type="button" class="px-3 py-2 bg-indigo-500 text-white rounded-lg insert-subheading" data-section="{{ $sectionKey }}">Add Subheading</button>
                            <button type="button" class="px-3 py-2 bg-gray-700 text-white rounded-lg insert-paragraph" data-section="{{ $sectionKey }}">Add Paragraph</button>
                            <button type="button" class="px-3 py-2 bg-green-600 text-white rounded-lg insert-image" data-section="{{ $sectionKey }}">Add Image</button>
                            <span class="text-sm text-gray-500 ml-3">Image placeholders will appear in the textarea and upload blocks are shown below.</span>
                        </div>

                        <div id="section-uploads-{{ $sectionKey }}" class="space-y-3 mb-4">
                            {{-- Prepopulate upload preview blocks for existing images referenced in sections_content --}}
                            @if(!empty($sectionsContent[$sectionKey]) && is_array($sectionsContent[$sectionKey]))
                                @foreach($sectionsContent[$sectionKey] as $block)
                                    @if(!empty($block['type']) && $block['type'] === 'image' && !empty($block['media_id']))
                                        @php
                                            $img = \App\Models\DestinationImage::find($block['media_id']);
                                            $url = $img ? ($img->thumbnail_path ? asset('storage/' . $img->thumbnail_path) : asset('storage/' . $img->storage_path)) : null;
                                        @endphp
                                        <div class="flex items-center gap-3 bg-white p-3 rounded border existing-upload" data-media-id="{{ $block['media_id'] }}" data-block-id="{{ $block['id'] ?? '' }}">
                                            <div class="w-28 h-20 bg-gray-100 rounded overflow-hidden flex items-center justify-center">
                                                @if($url)
                                                    <img id="media-preview-{{ $block['media_id'] }}" src="{{ $url }}" class="w-full h-full object-cover">
                                                @else
                                                    <svg class="w-8 h-8 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                @endif
                                            </div>
                                            <div class="flex-1">
                                                <label class="block text-xs text-gray-600 mb-1">Caption</label>
                                                <input type="text" class="w-full px-3 py-2 border rounded caption-input" value="{{ $block['caption'] ?? '' }}" placeholder="Caption (optional)">
                                                <div class="mt-2">
                                                    <input type="file" accept="image/*" class="replace-upload-file" data-media-id="{{ $block['media_id'] }}" name="sections[{{ $sectionKey }}][uploads][media-{{ $block['media_id'] }}]">
                                                </div>
                                            </div>
                                            <div class="flex-shrink-0 flex flex-col gap-2">
                                                <button type="button" class="text-sm px-2 py-1 bg-red-600 text-white rounded remove-existing-media" data-media-id="{{ $block['media_id'] }}">Remove</button>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>

                        {{-- Hidden content_blocks JSON input --}}
                        <input type="hidden" data-contentblock-input name="sections[{{ $sectionKey }}][content_blocks]" value="">
                    </div>
                </div>
            @endforeach

            <!-- Images tab (top-level) -->
            <div id="tab-images" class="tab-content hidden">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Images & Media</h2>

                <div class="mb-6">
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Main Thumbnail Image</label>
                    <input type="file" name="image" id="image" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    <p class="text-gray-500 text-xs mt-1">Used in listings. Recommended: 800x600px, Max: 2MB</p>
                    <div id="image-preview" class="mt-3">
                        @if($destination->image)
                            <img src="{{ asset('storage/' . $destination->image) }}" class="w-64 h-40 object-cover rounded">
                        @endif
                    </div>
                </div>

                <div class="mb-6">
                    <label for="featured_image" class="block text-sm font-medium text-gray-700 mb-2">Featured Header Image</label>
                    <input type="file" name="featured_image" id="featured_image" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    <p class="text-gray-500 text-xs mt-1">Hero/header. Recommended: 1920x1080px, Max: 5MB</p>
                    <div id="featured-preview" class="mt-3">
                        @if($destination->featured_image)
                            <img src="{{ asset('storage/' . $destination->featured_image) }}" class="w-96 h-64 object-cover rounded">
                        @endif
                    </div>
                </div>

                <div class="mb-6">
                    <label for="gallery_images" class="block text-sm font-medium text-gray-700 mb-2">Gallery Images</label>
                    <input type="file" name="gallery_images[]" id="gallery_images" accept="image/*" multiple class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    <p class="text-gray-500 text-xs mt-1">Multiple images for photo gallery. Max: 2MB each</p>
                    <div id="gallery-preview" class="mt-3 grid grid-cols-2 md:grid-cols-4 gap-4">
                        @if(!empty($destination->gallery_images) && is_array($destination->gallery_images))
                            @foreach($destination->gallery_images as $g)
                                <div class="relative"><img src="{{ asset('storage/' . $g['image']) }}" class="w-full h-32 object-cover rounded"></div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <!-- SEO -->
            <div id="tab-seo" class="tab-content hidden">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">SEO & Meta Information</h2>
                <div class="space-y-6">
                    <div>
                        <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-2">Meta Title</label>
                        <input type="text" name="meta_title" id="meta_title" maxlength="60" class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="{{ old('meta_title', $destination->meta_title) }}">
                    </div>

                    <div>
                        <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
                        <textarea name="meta_description" id="meta_description" rows="3" maxlength="160" class="w-full px-4 py-2 border border-gray-300 rounded-lg">{{ old('meta_description', $destination->meta_description) }}</textarea>
                    </div>

                    <div>
                        <label for="meta_keywords" class="block text-sm font-medium text-gray-700 mb-2">Meta Keywords</label>
                        <input type="text" name="meta_keywords" id="meta_keywords" class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="{{ old('meta_keywords', $destination->meta_keywords) }}">
                    </div>
                </div>
            </div>
        </div>

        <div class="border-t bg-gray-50 px-6 py-4 flex justify-between items-center rounded-b-lg">
            <a href="{{ route('admin.destinations.index') }}" class="text-gray-600 hover:text-gray-800 font-medium"><i class="fas fa-times mr-1"></i> Cancel</a>

            <div class="flex gap-3">
                <button type="button" id="saveDraftBtn" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg font-medium">Save Draft</button>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium transition flex items-center shadow-md"><i class="fas fa-save mr-2"></i> Save Changes</button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Helpers
    function uuid(){ return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g,function(c){const r=Math.random()*16|0,v=c==='x'?r:(r&0x3|0x8);return v.toString(16);}); }
    function tempId(){ return 'tmp-' + Math.random().toString(36).substr(2,9); }
    function insertAtCursor(textarea, text) {
        const start = textarea.selectionStart, end = textarea.selectionEnd, val = textarea.value;
        textarea.value = val.slice(0, start) + text + val.slice(end);
        textarea.selectionStart = textarea.selectionEnd = start + text.length;
        textarea.focus();
        triggerAutoSave();
    }
    function slugify(v){ return v.toLowerCase().replace(/[^\w\s-]/g,'').replace(/\s+/g,'-').replace(/-+/g,'-').trim(); }

    // Tabs
    document.querySelectorAll('.tab-button').forEach(button => {
        button.addEventListener('click', function() {
            const targetTab = this.dataset.tab;
            document.querySelectorAll('.tab-button').forEach(btn => { btn.classList.remove('active','border-green-500','text-green-600'); btn.classList.add('border-transparent','text-gray-500'); });
            this.classList.add('active','border-green-500','text-green-600'); this.classList.remove('border-transparent','text-gray-500');
            document.querySelectorAll('.tab-content').forEach(content => content.classList.add('hidden'));
            const el = document.getElementById('tab-' + targetTab);
            if (el) el.classList.remove('hidden');
            el && window.scrollTo({ top: el.getBoundingClientRect().top + window.scrollY - 120, behavior: 'smooth' });
        });
    });

    // Slug auto-generate
    const nameInput = document.getElementById('name'), slugInput = document.getElementById('slug');
    if (nameInput && slugInput) {
        nameInput.addEventListener('input', function(){ if (!slugInput.dataset.manualEdit) slugInput.value = slugify(this.value); });
        slugInput.addEventListener('input', () => slugInput.dataset.manualEdit = 'true');
    }

    // Insert controls (Option A)
    document.querySelectorAll('.insert-heading').forEach(btn => btn.addEventListener('click', function(){
        const section = this.dataset.section;
        const ta = document.querySelector(`#tab-${section} .section-textarea`);
        const text = prompt('Enter heading text');
        if (!text) return;
        insertAtCursor(ta, `\n# ${text}\n\n`);
    }));
    document.querySelectorAll('.insert-subheading').forEach(btn => btn.addEventListener('click', function(){
        const section = this.dataset.section;
        const ta = document.querySelector(`#tab-${section} .section-textarea`);
        const text = prompt('Enter subheading text');
        if (!text) return;
        insertAtCursor(ta, `\n## ${text}\n\n`);
    }));
    document.querySelectorAll('.insert-paragraph').forEach(btn => btn.addEventListener('click', function(){
        const section = this.dataset.section;
        const ta = document.querySelector(`#tab-${section} .section-textarea`);
        insertAtCursor(ta, `\n\n`);
    }));

    // Image insertion for edit: create tmp token and upload block
    document.querySelectorAll('.insert-image').forEach(btn => btn.addEventListener('click', function(){
        const section = this.dataset.section;
        const ta = document.querySelector(`#tab-${section} .section-textarea`);
        const caption = prompt('Enter image caption (optional)') || '';
        const tmp = tempId();
        const token = `[[image:${tmp}|${caption}]]`;
        insertAtCursor(ta, `\n${token}\n\n`);
        addUploadBlock(section, tmp, caption);
    }));

    // Add upload block (used for new tmp tokens)
    function addUploadBlock(section, tmpId, caption) {
        const container = document.getElementById('section-uploads-' + section);
        if (!container) return;
        const wrapper = document.createElement('div');
        wrapper.className = 'flex items-center gap-3 bg-white p-3 rounded border';
        wrapper.dataset.tmpId = tmpId;
        wrapper.innerHTML = `
            <div class="w-28 h-20 bg-gray-100 rounded overflow-hidden flex items-center justify-center">
                <img id="upload-preview-${tmpId}" src="" class="w-full h-full object-cover" style="display:none">
                <svg id="upload-svg-${tmpId}" class="w-8 h-8 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <div class="flex-1">
                <label class="block text-xs text-gray-600 mb-1">Caption</label>
                <input type="text" class="w-full px-3 py-2 border rounded caption-input" value="${caption || ''}" placeholder="Caption (optional)">
                <div class="mt-2">
                    <input type="file" accept="image/*" name="sections[${section}][uploads][${tmpId}]" class="upload-file-input">
                </div>
            </div>
            <div class="flex-shrink-0 flex flex-col gap-2">
                <button type="button" class="text-sm px-2 py-1 bg-red-600 text-white rounded remove-upload">Remove</button>
            </div>
        `;
        container.appendChild(wrapper);

        const fileInput = wrapper.querySelector('.upload-file-input');
        const previewImg = document.getElementById('upload-preview-' + tmpId);
        const svgPlace = document.getElementById('upload-svg-' + tmpId);
        const captionInput = wrapper.querySelector('.caption-input');

        fileInput.addEventListener('change', function(){
            const f = this.files[0];
            if (!f) return;
            if (f.size > 2*1024*1024) { alert('Image exceeds 2MB'); this.value=''; return; }
            const reader = new FileReader();
            reader.onload = function(ev){ previewImg.src = ev.target.result; previewImg.style.display = 'block'; if (svgPlace) svgPlace.style.display = 'none'; };
            reader.readAsDataURL(f);
            triggerAutoSave();
        });

        captionInput.addEventListener('input', function(){ syncPlaceholdersWithUploads(section); triggerAutoSave(); });

        wrapper.querySelector('.remove-upload').addEventListener('click', function(){
            wrapper.remove();
            // remove corresponding token from textarea
            const ta = document.querySelector(`#tab-${section} .section-textarea`);
            const re = new RegExp(`\\[\\[image:${tmpId}(?:\\|[^\\]]*)?\\]\\]`, 'g');
            ta.value = ta.value.replace(re, '');
            triggerAutoSave();
        });
    }

    // When admin picks a replacement file for an existing media, convert the corresponding token from media-<id> to a tmp token
    document.querySelectorAll('.replace-upload-file').forEach(input => {
        input.addEventListener('change', function(){
            const mediaId = this.dataset.mediaId;
            if (!mediaId) return;
            const sectionContainer = this.closest('.tab-content');
            const sectionKey = sectionContainer.id.replace(/^tab-/, '');
            const ta = sectionContainer.querySelector('.section-textarea');
            // find token [[image:media-<id>|caption]]
            const tokenRe = new RegExp(`\\[\\[image:media-${mediaId}(?:\\|([^\\]]*))?\\]\\]`, 'g');
            const match = tokenRe.exec(ta.value);
            const caption = match && match[1] ? match[1] : '';
            // create tmp id and replace token
            const tmp = tempId();
            ta.value = ta.value.replace(tokenRe, `[[image:${tmp}|${caption}]]`);
            // update this input name attribute to use tmp (so controller picks it up)
            this.name = `sections[${sectionKey}][uploads][${tmp}]`;
            // set wrapper dataset.tmpId for removal code
            const wrapper = this.closest('.existing-upload');
            if (wrapper) {
                wrapper.dataset.tmpId = tmp;
                wrapper.dataset.replacedMediaId = mediaId; // keep track of original if you need to delete it server-side later
            }
            // move preview handling to tmp scheme
            const reader = new FileReader();
            reader.onload = ev => {
                // try to find an img preview element or create
                let img = wrapper.querySelector('img');
                if (!img) {
                    img = document.createElement('img');
                    img.className = 'w-full h-full object-cover';
                    wrapper.querySelector('.w-28').appendChild(img);
                }
                img.src = ev.target.result;
            };
            reader.readAsDataURL(this.files[0]);
            triggerAutoSave();
        });
    });

    // Remove existing media: remove token and optionally mark for deletion (we won't auto-delete on server unless you implement)
    document.querySelectorAll('.remove-existing-media').forEach(btn => {
        btn.addEventListener('click', function(){
            const mediaId = this.dataset.mediaId;
            const wrapper = this.closest('.existing-upload');
            const sectionContainer = this.closest('.tab-content');
            const sectionKey = sectionContainer.id.replace(/^tab-/, '');
            const ta = sectionContainer.querySelector('.section-textarea');
            // remove tokens referencing media
            const tokenRe = new RegExp(`\\[\\[image:media-${mediaId}(?:\\|[^\\]]*)?\\]\\]`, 'g');
            ta.value = ta.value.replace(tokenRe, '');
            // remove UI block
            wrapper.remove();
            // optionally add a hidden input to request server to delete that media id on save
            const delInput = document.createElement('input');
            delInput.type = 'hidden';
            delInput.name = `sections[${sectionKey}][delete_media][]`;
            delInput.value = mediaId;
            sectionContainer.appendChild(delInput);
            triggerAutoSave();
        });
    });

    // Sync placeholder captions with upload caption inputs
    function syncPlaceholdersWithUploads(section) {
        const ta = document.querySelector(`#tab-${section} .section-textarea`);
        const container = document.getElementById('section-uploads-' + section);
        if (!ta || !container) return;
        let text = ta.value;
        Array.from(container.querySelectorAll('div[data-tmp-id], div.existing-upload')).forEach(w => {
            const tmp = w.dataset.tmpId || w.dataset.mediaId ? (w.dataset.tmpId || ('media-' + w.dataset.mediaId)) : null;
            if (!tmp) return;
            const captionInput = w.querySelector('.caption-input');
            const caption = captionInput ? captionInput.value : '';
            const tokenRe = new RegExp(`\\[\\[image:${tmp}(?:\\|[^\\]]*)?\\]\\]`, 'g');
            text = text.replace(tokenRe, `[[image:${tmp}|${caption}]]`);
        });
        ta.value = text;
    }

    // Auto-save to localStorage so refresh doesn't lose data
    const AUTO_KEY = 'destination_edit_{{ $destination->id }}_draft_v1';
    function gatherFormState() {
        const state = { meta: {}, sections: {} };
        ['name','slug','description','country_id','type','sort_order','is_active','is_popular','meta_title','meta_description','meta_keywords'].forEach(k => {
            const el = document.querySelector(`[name="${k}"]`);
            if (el) state.meta[k] = el.type === 'checkbox' ? el.checked : el.value;
        });
        document.querySelectorAll('.section-textarea').forEach(ta => state.sections[ta.id] = ta.value);
        return state;
    }
    function restoreFormState() {
        try {
            const raw = localStorage.getItem(AUTO_KEY);
            if (!raw) return;
            const state = JSON.parse(raw);
            if (!state) return;
            Object.keys(state.meta || {}).forEach(k => {
                const el = document.querySelector(`[name="${k}"]`);
                if (el) el.type === 'checkbox' ? (el.checked = !!state.meta[k]) : (el.value = state.meta[k]);
            });
            Object.keys(state.sections || {}).forEach(id => {
                const ta = document.getElementById(id);
                if (ta) ta.value = state.sections[id];
            });
        } catch (e) { console.error('restore failed', e); }
    }
    function triggerAutoSave() {
        const data = gatherFormState();
        localStorage.setItem(AUTO_KEY, JSON.stringify(data));
    }
    document.querySelectorAll('input, textarea, select').forEach(el => { el.addEventListener('input', triggerAutoSave); el.addEventListener('change', triggerAutoSave); });
    restoreFormState();

    // Save Draft button — send to update route with draft=1 and keep on page
    const saveDraftBtn = document.getElementById('saveDraftBtn');
    saveDraftBtn && saveDraftBtn.addEventListener('click', async function(){
        buildAndAttachSectionContentBlocks();
        const formEl = document.getElementById('destinationEditForm');
        const fd = new FormData(formEl);
        fd.append('draft', '1');

        // include file inputs inside section upload containers
        document.querySelectorAll('#destinationEditForm input[type="file"]').forEach(f => {
            if (f.files && f.files[0]) fd.set(f.name, f.files[0]);
        });

        saveDraftBtn.disabled = true;
        const orig = saveDraftBtn.innerHTML;
        saveDraftBtn.innerHTML = 'Saving...';

        try {
            const resp = await fetch(formEl.action, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                body: fd
            });
            const json = await resp.json().catch(()=>null);
            if (resp.ok && json && json.success) {
                alert('Draft saved successfully.');
            } else {
                if (json && json.errors) {
                    let msg = 'Please fix these errors:\\n';
                    Object.values(json.errors).forEach(e => { msg += '• ' + e[0] + '\\n'; });
                    alert(msg);
                } else {
                    alert('Failed to save draft. Check the console.');
                }
            }
        } catch (err) {
            console.error(err);
            alert('Network or server error when saving draft.');
        } finally {
            saveDraftBtn.disabled = false;
            saveDraftBtn.innerHTML = orig;
        }
    });

    // Build content_blocks JSON for each section before full submit
    function buildAndAttachSectionContentBlocks() {
        document.querySelectorAll('.tab-content').forEach(tab => {
            const sectionKey = tab.id.replace(/^tab-/, '');
            if (!sectionKey) return;
            const ta = tab.querySelector('.section-textarea');
            if (!ta) return;
            const blocks = [];
            const text = ta.value || '';
            const lines = text.replace(/\r\n/g,'\n').split('\n');
            let paragraphBuffer = [];
            function flushParagraph() {
                const joined = paragraphBuffer.join('\n').trim();
                if (joined) blocks.push({ id: 'blk-' + uuid(), type: 'text', text: joined });
                paragraphBuffer = [];
            }
            const tokenRe = /\[\[image:(tmp-[a-z0-9]+|media-[0-9]+|block-[^\|]+)(?:\|([^\]]*))?\]\]/ig;
            lines.forEach(line => {
                const l = line.trim();
                if (l === '') { flushParagraph(); return; }
                if (l.startsWith('# ')) { flushParagraph(); blocks.push({ id: 'blk-' + uuid(), type: 'heading', text: l.slice(2).trim() }); return; }
                if (l.startsWith('## ')) { flushParagraph(); blocks.push({ id: 'blk-' + uuid(), type: 'subheading', text: l.slice(3).trim() }); return; }
                const m = l.match(tokenRe);
                if (m) {
                    flushParagraph();
                    // execute tokenRe to capture last match
                    let mm;
                    tokenRe.lastIndex = 0;
                    while ((mm = tokenRe.exec(l)) !== null) {
                        const idToken = mm[1];
                        const caption = mm[2] || '';
                        if (idToken.startsWith('tmp-')) {
                            blocks.push({ id: 'blk-' + uuid(), type: 'image', temp_media_id: idToken, caption: caption });
                        } else if (idToken.startsWith('media-')) {
                            const mediaId = parseInt(idToken.replace('media-',''),10);
                            blocks.push({ id: 'blk-' + uuid(), type: 'image', media_id: mediaId, caption: caption });
                        } else {
                            // unknown block id -> keep as block reference
                            blocks.push({ id: idToken, type: 'image', caption: caption });
                        }
                    }
                    return;
                }
                paragraphBuffer.push(line);
            });
            flushParagraph();
            const hidden = tab.querySelector('input[data-contentblock-input]');
            if (hidden) hidden.value = JSON.stringify(blocks);
        });
    }

    // Before full submit, call buildAndAttachSectionContentBlocks (to set hidden inputs)
    const editForm = document.getElementById('destinationEditForm');
    editForm.addEventListener('submit', function(e){
        buildAndAttachSectionContentBlocks();
        // also ensure uploads are included - browser will include file inputs automatically
    });

    // Initialize first tab visible
    document.querySelector('.tab-button.active').click();
});
</script>
@endpush

@push('styles')
<style>
.tab-button.active { border-color: #10b981; color: #10b981; }
.tab-button:not(.active) { border-color: transparent; color: #6b7280; }
.tab-button:not(.active):hover { color: #374151; border-color: #d1d5db; }
</style>
@endpush
@endsection