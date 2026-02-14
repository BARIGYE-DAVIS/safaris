@extends('layouts.admin')

@section('title', 'Create Destination')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Create New Destination</h1>
            <p class="text-gray-600 mt-1">Add a new safari destination with comprehensive details</p>
        </div>
        <a href="{{ route('admin.destinations.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center transition">
            <i class="fas fa-arrow-left mr-2"></i> Back to List
        </a>
    </div>

    <!-- Error Messages -->
    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <p class="font-bold mb-2">Please fix the following errors:</p>
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="destinationCreateForm" action="{{ route('admin.destinations.store') }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-lg rounded-lg">
        @csrf

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
                        <select name="country_id" id="country_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="">Select Country</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}" {!! old('country_id') == $country->id ? 'selected' : '' !!}>{!! $country->flag_icon !!} {{ $country->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Destination Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="{{ old('name') }}" placeholder="e.g., Murchison Falls National Park">
                    </div>

                    <div>
                        <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">URL Slug</label>
                        <input type="text" name="slug" id="slug" class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="{{ old('slug') }}" placeholder="murchison-falls-national-park">
                        <p class="text-gray-500 text-xs mt-1">Leave empty to auto-generate from name</p>
                    </div>

                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Destination Type</label>
                        <select name="type" id="type" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            <option value="">Select Type</option>
                            @foreach(['National Park','Wildlife Reserve','Forest Reserve','Game Reserve','Conservation Area','Wildlife Sanctuary','City','Lake','Mountain','Island'] as $t)
                                <option value="{{ $t }}" {!! old('type') == $t ? 'selected' : '' !!}>{{ $t }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">Sort Order</label>
                        <input type="number" name="sort_order" id="sort_order" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="{{ old('sort_order', 0) }}">
                        <p class="text-gray-500 text-xs mt-1">Lower numbers appear first</p>
                    </div>
                </div>

                <div class="mt-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Short Description</label>
                    <textarea name="description" id="description" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="Short overview...">{{ old('description') }}</textarea>
                </div>

                <div class="mt-6 space-y-3">
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" class="w-5 h-5 rounded border-gray-300 text-green-600" {{ old('is_active', true) ? 'checked' : '' }}>
                        <div><span class="text-sm font-medium text-gray-700">Active</span><p class="text-xs text-gray-500">Visible on site</p></div>
                    </label>

                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" name="is_popular" value="1" class="w-5 h-5 rounded border-gray-300 text-green-600" {{ old('is_popular') ? 'checked' : '' }}>
                        <div><span class="text-sm font-medium text-gray-700">Mark as Popular</span><p class="text-xs text-gray-500">Feature on homepage</p></div>
                    </label>
                </div>
            </div>

            {{-- Sections list used for rendering repeated sections --}}
            @php
                $sectionsList = [
                    'overview' => ['label'=>'Detailed Overview', 'textarea'=>'detailed_overview'],
                    'activities' => ['label'=>'Activities', 'textarea'=>'what_to_see_do'],
                    'wildlife' => ['label'=>'Wildlife Highlights', 'textarea'=>'wildlife_highlights'],
                    'geography' => ['label'=>'Geography & Landscape', 'textarea'=>'geography_landscape'],
                    'practical' => ['label'=>'Practical Information', 'textarea'=>'practical_information'],
                    'accommodation' => ['label'=>'Accommodation Options', 'textarea'=>'accommodation_options'],
                    'extras' => ['label'=>'Additional Information', 'textarea'=>'interesting_facts'],
                ];
            @endphp

            {{-- Loop sections and render textarea + toolbar --}}
            @foreach($sectionsList as $sectionKey => $cfg)
            <div id="tab-{{ $sectionKey }}" class="tab-content {{ $sectionKey !== 'overview' ? 'hidden' : '' }}">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">{{ $cfg['label'] }}</h2>

                <div>
                    <label for="{{ $cfg['textarea'] }}" class="block text-sm font-medium text-gray-700 mb-2">{{ $cfg['label'] }} Content</label>
                    <textarea name="{{ $cfg['textarea'] }}" id="{{ $cfg['textarea'] }}" rows="12" class="section-textarea w-full px-4 py-2 border border-gray-300 rounded-lg font-mono text-sm" placeholder="Write content...">{{ old($cfg['textarea']) }}</textarea>
                    <p class="text-gray-500 text-xs mt-1">Use headings (# Heading), subheadings (## Subheading), paragraphs, and insert images with the toolbar below.</p>
                </div>

                <div class="mt-4">
                    <div class="flex flex-wrap gap-2 items-center mb-3">
                        <button type="button" class="px-3 py-2 bg-indigo-600 text-white rounded-lg insert-heading" data-section="{{ $sectionKey }}">Add Heading</button>
                        <button type="button" class="px-3 py-2 bg-indigo-500 text-white rounded-lg insert-subheading" data-section="{{ $sectionKey }}">Add Subheading</button>
                        <button type="button" class="px-3 py-2 bg-gray-700 text-white rounded-lg insert-paragraph" data-section="{{ $sectionKey }}">Add Paragraph</button>
                        <button type="button" class="px-3 py-2 bg-green-600 text-white rounded-lg insert-image" data-section="{{ $sectionKey }}">Add Image</button>
                        <span class="text-sm text-gray-500 ml-3">Image placeholders will appear in the textarea as <code>[[image:tmp-abc123|Caption]]</code> and a file upload block is created below.</span>
                    </div>

                    <div id="section-uploads-{{ $sectionKey }}" class="space-y-3 mb-4"></div>

                    {{-- Hidden content_blocks JSON input (populated before submit) --}}
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
                    <div id="image-preview" class="mt-3"></div>
                </div>

                <div class="mb-6">
                    <label for="featured_image" class="block text-sm font-medium text-gray-700 mb-2">Featured Header Image</label>
                    <input type="file" name="featured_image" id="featured_image" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    <p class="text-gray-500 text-xs mt-1">Hero/header. Recommended: 1920x1080px, Max: 5MB</p>
                    <div id="featured-preview" class="mt-3"></div>
                </div>

                <div class="mb-6">
                    <label for="gallery_images" class="block text-sm font-medium text-gray-700 mb-2">Gallery Images</label>
                    <input type="file" name="gallery_images[]" id="gallery_images" accept="image/*" multiple class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    <p class="text-gray-500 text-xs mt-1">Multiple images for photo gallery. Max: 2MB each</p>
                    <div id="gallery-preview" class="mt-3 grid grid-cols-2 md:grid-cols-4 gap-4"></div>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-sm text-blue-800"><i class="fas fa-info-circle mr-2"></i><strong>Note:</strong> Inline images inserted via textarea placeholders are uploaded along with the form; the controller will map temp ids to uploaded files.</p>
                </div>
            </div>

            <!-- SEO tab -->
            <div id="tab-seo" class="tab-content hidden">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">SEO & Meta Information</h2>
                <div class="space-y-6">
                    <div>
                        <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-2">Meta Title</label>
                        <input type="text" name="meta_title" id="meta_title" maxlength="60" class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="{{ old('meta_title') }}" placeholder="Murchison Falls National Park | Uganda Safari Destination">
                        <p class="text-gray-500 text-xs mt-1">Recommended: 50-60 characters</p>
                    </div>

                    <div>
                        <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
                        <textarea name="meta_description" id="meta_description" rows="3" maxlength="160" class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="Visit Murchison Falls...">{{ old('meta_description') }}</textarea>
                        <p class="text-gray-500 text-xs mt-1">Recommended: 150-160 characters</p>
                    </div>

                    <div>
                        <label for="meta_keywords" class="block text-sm font-medium text-gray-700 mb-2">Meta Keywords</label>
                        <input type="text" name="meta_keywords" id="meta_keywords" class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="{{ old('meta_keywords') }}" placeholder="murchison falls, uganda safari">
                        <p class="text-gray-500 text-xs mt-1">Comma-separated keywords</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="border-t bg-gray-50 px-6 py-4 flex justify-between items-center rounded-b-lg">
            <a href="{{ route('admin.destinations.index') }}" class="text-gray-600 hover:text-gray-800 font-medium"><i class="fas fa-times mr-1"></i> Cancel</a>

            <div class="flex gap-3">
                <button type="button" id="saveDraftBtn" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg font-medium">Save Draft</button>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium transition flex items-center shadow-md"><i class="fas fa-save mr-2"></i> Create Destination</button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ---------- helpers ----------
    function uuid(){ return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g,function(c){const r=Math.random()*16|0,v=c==='x'?r:(r&0x3|0x8);return v.toString(16);}); }
    function tempId(){ return 'tmp-' + Math.random().toString(36).substr(2,9); }
    function insertAtCursor(textarea, text) {
        const start = textarea.selectionStart;
        const end = textarea.selectionEnd;
        const val = textarea.value;
        textarea.value = val.slice(0, start) + text + val.slice(end);
        textarea.selectionStart = textarea.selectionEnd = start + text.length;
        textarea.focus();
        triggerAutoSave();
    }
    function slugify(v){ return v.toLowerCase().replace(/[^\w\s-]/g,'').replace(/\s+/g,'-').replace(/-+/g,'-').trim(); }

    // ---------- tabs ----------
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

    // ---------- slug auto -->
    const nameInput = document.getElementById('name'), slugInput = document.getElementById('slug');
    if (nameInput && slugInput) {
        nameInput.addEventListener('input', function(){ if (!slugInput.dataset.manualEdit) slugInput.value = slugify(this.value); });
        slugInput.addEventListener('input', () => slugInput.dataset.manualEdit = 'true');
    }

    // ---------- insertion buttons (Option A) ----------
    // Insert heading/subheading/paragraph/image tokens into textarea
    function makeImageToken(tmpId, caption) {
        // caption may contain ']' so escape; we'll keep it simple
        return `[[image:${tmpId}|${caption || ''}]]`;
    }

    document.querySelectorAll('.insert-heading').forEach(btn => {
        btn.addEventListener('click', function(){
            const section = this.dataset.section;
            const ta = document.getElementById(document.querySelector(`#tab-${section} .section-textarea`).id);
            // prompt for heading text
            const text = prompt('Enter heading text');
            if (!text) return;
            insertAtCursor(ta, `\n# ${text}\n\n`);
            triggerAutoSave();
        });
    });

    document.querySelectorAll('.insert-subheading').forEach(btn => {
        btn.addEventListener('click', function(){
            const section = this.dataset.section;
            const ta = document.getElementById(document.querySelector(`#tab-${section} .section-textarea`).id);
            const text = prompt('Enter subheading text');
            if (!text) return;
            insertAtCursor(ta, `\n## ${text}\n\n`);
            triggerAutoSave();
        });
    });

    document.querySelectorAll('.insert-paragraph').forEach(btn => {
        btn.addEventListener('click', function(){
            const section = this.dataset.section;
            const ta = document.getElementById(document.querySelector(`#tab-${section} .section-textarea`).id);
            insertAtCursor(ta, `\n\n`);
            triggerAutoSave();
        });
    });

    // Add image placeholder token and create a visible upload block for the tmp id
    document.querySelectorAll('.insert-image').forEach(btn => {
        btn.addEventListener('click', function(){
            const section = this.dataset.section;
            const ta = document.getElementById(document.querySelector(`#tab-${section} .section-textarea`).id);
            const caption = prompt('Enter image caption (optional)') || '';
            const tmp = tempId();
            // insert placeholder token at cursor
            const token = makeImageToken(tmp, caption);
            insertAtCursor(ta, `\n${token}\n\n`);
            // create upload UI
            addUploadBlock(section, tmp, caption);
            triggerAutoSave();
        });
    });

    // ---------- upload blocks management ----------
    // container: #section-uploads-<section>
    function addUploadBlock(section, tmpId, caption) {
        const container = document.getElementById('section-uploads-' + section);
        if (!container) return;
        // create wrapper
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
            reader.onload = function(ev){
                previewImg.src = ev.target.result; previewImg.style.display = 'block'; if (svgPlace) svgPlace.style.display = 'none';
            };
            reader.readAsDataURL(f);
            triggerAutoSave();
        });

        captionInput.addEventListener('input', function(){ // reflect caption into textarea token if present
            syncPlaceholdersWithUploads(section);
            triggerAutoSave();
        });

        wrapper.querySelector('.remove-upload').addEventListener('click', function(){
            // remove upload block
            wrapper.remove();
            // remove corresponding placeholder token(s) from textarea
            removeImageTokensByTmpId(section, tmpId);
            triggerAutoSave();
        });
    }

    // Remove token occurrences that reference the tmp id
    function removeImageTokensByTmpId(section, tmpId) {
        const ta = document.querySelector(`#tab-${section} .section-textarea`);
        if (!ta) return;
        const re = new RegExp(`\\[\\[image:${tmpId}(?:\\|[^\\]]*)?\\]\\]`, 'g');
        ta.value = ta.value.replace(re, '');
    }

    // Sync upload blocks to tokens present in textarea:
    // - Create upload block for tokens with no corresponding upload block
    // - Remove upload blocks that have no token in textarea
    // Also reconcile captions (if token has caption it will set caption input)
    function syncUploadsFromTextarea(section) {
        const ta = document.querySelector(`#tab-${section} .section-textarea`);
        if (!ta) return;
        const text = ta.value;
        const tokenRe = /\[\[image:(tmp-[a-z0-9]+)(?:\|([^\]]*))?\]\]/g;
        const found = {};
        let m;
        while ((m = tokenRe.exec(text)) !== null) {
            const tmp = m[1];
            const caption = m[2] || '';
            found[tmp] = caption;
            // ensure an upload block exists for this tmp
            const container = document.getElementById('section-uploads-' + section);
            if (container && !container.querySelector(`[data-tmp-id="${tmp}"], [data-tmp-id='${tmp}']`) && !container.querySelector(`div[data-tmp-id="${tmp}"]`)) {
                // double-check no wrapper exists by dataset
                // we built wrappers with dataset.tmpId; query by attribute:
                if (!container.querySelector(`div[data-tmp-id="${tmp}"]`)) {
                    addUploadBlock(section, tmp, caption);
                }
            } else {
                // if exists, update caption input to match token caption if different
                const wrapper = document.querySelector(`#section-uploads-${section} [data-tmp-id="${tmp}"]`);
                if (wrapper) {
                    const capInput = wrapper.querySelector('.caption-input');
                    if (capInput && capInput.value !== caption) capInput.value = caption;
                } else {
                    // also try find by name attribute input
                    const inputByName = document.querySelector(`#section-uploads-${section} input[name="sections[${section}][uploads][${tmp}]"]`);
                    if (inputByName) {
                        const parent = inputByName.closest('div');
                        if (parent) {
                            const wrapper2 = parent.closest('div');
                            if (wrapper2 && wrapper2.dataset) wrapper2.dataset.tmpId = tmp;
                        }
                    }
                }
            }
        }

        // Remove upload blocks for tmp ids not present
        const container = document.getElementById('section-uploads-' + section);
        if (!container) return;
        Array.from(container.querySelectorAll('div[data-tmp-id]')).forEach(w => {
            const t = w.dataset.tmpId;
            if (!found[t]) w.remove();
        });
    }

    // After changing upload captions we want to reflect any differences back into textarea tokens
    function syncPlaceholdersWithUploads(section) {
        const ta = document.querySelector(`#tab-${section} .section-textarea`);
        const container = document.getElementById('section-uploads-' + section);
        if (!ta || !container) return;
        let text = ta.value;
        Array.from(container.querySelectorAll('div[data-tmp-id]')).forEach(w => {
            const tmp = w.dataset.tmpId;
            const capInput = w.querySelector('.caption-input');
            const caption = capInput ? capInput.value : '';
            // replace any image token for this tmp with updated caption
            const tokenRe = new RegExp(`\\[\\[image:${tmp}(?:\\|[^\\]]*)?\\]\\]`, 'g');
            text = text.replace(tokenRe, `[[image:${tmp}|${caption}]]`);
        });
        ta.value = text;
    }

    // Utility to find all tokens in textarea and return array of {tmpId, caption}
    function parseImageTokensFromText(text) {
        const tokens = [];
        const re = /\[\[image:(tmp-[a-z0-9]+)(?:\|([^\]]*))?\]\]/g;
        let m;
        while ((m = re.exec(text)) !== null) tokens.push({ tmp: m[1], caption: m[2] || '' });
        return tokens;
    }

    // ---------- autosave (localStorage) ----------
    const AUTO_KEY = 'destination_create_draft_v1';
    function gatherFormState() {
        const state = {
            meta: {},
            sections: {}
        };
        // gather basic fields
        ['name','slug','description','country_id','type','sort_order','is_active','is_popular'].forEach(k => {
            const el = document.querySelector(`[name="${k}"]`);
            if (el) {
                if (el.type === 'checkbox') state.meta[k] = el.checked;
                else state.meta[k] = el.value;
            }
        });
        // section textareas
        document.querySelectorAll('.section-textarea').forEach(ta => {
            state.sections[ta.id] = ta.value;
        });
        // gallery filenames not stored (files cannot store), but we can store info about image placeholders
        return state;
    }

    function restoreFormState() {
        try {
            const raw = localStorage.getItem(AUTO_KEY);
            if (!raw) return;
            const state = JSON.parse(raw);
            if (!state) return;
            // restore meta
            Object.keys(state.meta || {}).forEach(k => {
                const el = document.querySelector(`[name="${k}"]`);
                if (el) {
                    if (el.type === 'checkbox') el.checked = !!state.meta[k];
                    else el.value = state.meta[k];
                }
            });
            // restore textareas
            Object.keys(state.sections || {}).forEach(id => {
                const ta = document.getElementById(id);
                if (ta) {
                    ta.value = state.sections[id];
                    // sync uploads for each section according to tokens found
                    const sectionKey = ta.closest('.tab-content').id.replace(/^tab-/, '');
                    parseImageTokensFromText(ta.value).forEach(t => {
                        // create upload block if none
                        const container = document.getElementById('section-uploads-' + sectionKey);
                        if (container && !container.querySelector(`div[data-tmp-id="${t.tmp}"]`)) {
                            addUploadBlock(sectionKey, t.tmp, t.caption);
                        }
                    });
                }
            });
        } catch (e) {
            console.error('restore failed', e);
        }
    }

    function triggerAutoSave() {
        const data = gatherFormState();
        localStorage.setItem(AUTO_KEY, JSON.stringify(data));
    }

    // attach auto-save listeners
    document.querySelectorAll('input, textarea, select').forEach(el => {
        el.addEventListener('input', triggerAutoSave);
        el.addEventListener('change', triggerAutoSave);
    });

    // sync uploads when textarea changes (tokens may have been edited)
    document.querySelectorAll('.section-textarea').forEach(ta => {
        ta.addEventListener('input', function(){
            const sectionKey = this.closest('.tab-content').id.replace(/^tab-/, '');
            syncUploadsFromTextarea(sectionKey);
            triggerAutoSave();
        });
    });

    // restore from localStorage on load
    restoreFormState();

    // ---------- Save Draft via fetch ----------
    const saveDraftBtn = document.getElementById('saveDraftBtn');
    saveDraftBtn && saveDraftBtn.addEventListener('click', async function(){
        // build content_blocks for each section and add to a FormData
        buildAndAttachSectionContentBlocks(); // populates hidden inputs
        const formEl = document.getElementById('destinationCreateForm');
        const fd = new FormData(formEl);
        fd.append('draft', '1');

        // also include any upload files that are in section upload containers
        document.querySelectorAll('[name^="sections"][name$="uploads"]').forEach(el => {
            // this selector won't find dynamic inputs; instead gather file inputs inside upload containers
        });
        // gather all file inputs inside section-uploads containers and main images
        document.querySelectorAll('#destinationCreateForm input[type="file"]').forEach(f => {
            if (f.files && f.files[0]) fd.set(f.name, f.files[0]); // overrides if needed
        });

        // show feedback
        saveDraftBtn.disabled = true;
        const orig = saveDraftBtn.innerHTML;
        saveDraftBtn.innerHTML = 'Saving...';

        try {
            const resp = await fetch('{{ route("admin.destinations.store") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: fd
            });
            const json = await resp.json().catch(() => null);
            if (resp.ok && json && json.success) {
                alert('Draft saved successfully.');
                // do not clear localStorage so you can continue editing
            } else {
                // show validation errors or fallback
                if (json && json.errors) {
                    let msg = 'Please fix these errors:\\n';
                    Object.values(json.errors).forEach(e => { msg += '• ' + e[0] + '\\n'; });
                    alert(msg);
                } else {
                    alert('Failed to save draft. Check the console for details.');
                }
            }
        } catch (err) {
            console.error('draft save error', err);
            alert('Network or server error when saving draft. Please try again.');
        } finally {
            saveDraftBtn.disabled = false;
            saveDraftBtn.innerHTML = orig;
        }
    });

    // ---------- Before full submit: build content_blocks JSON for each section ----------
    function buildAndAttachSectionContentBlocks() {
        // parse headings, subheadings, paragraphs, image tokens in the textarea
        document.querySelectorAll('.tab-content').forEach(tab => {
            const sectionKey = tab.id.replace(/^tab-/, '');
            if (!sectionKey) return;
            const ta = tab.querySelector('.section-textarea');
            if (!ta) return;
            const blocks = [];
            const text = ta.value || '';
            // We'll parse by lines, grouping paragraphs. Headings (# ) and subheadings (## ) are explicit.
            const lines = text.replace(/\r\n/g, '\n').split('\n');
            let paragraphBuffer = [];
            function flushParagraph() {
                const joined = paragraphBuffer.join('\n').trim();
                if (joined) blocks.push({ id: 'blk-' + uuid(), type: 'text', text: joined });
                paragraphBuffer = [];
            }
            const tokenRe = /\[\[image:(tmp-[a-z0-9]+)(?:\|([^\]]*))?\]\]/i;
            lines.forEach(line => {
                const l = line.trim();
                if (l === '') {
                    flushParagraph();
                    return;
                }
                // heading
                if (l.startsWith('# ')) {
                    flushParagraph();
                    blocks.push({ id: 'blk-' + uuid(), type: 'heading', text: l.slice(2).trim() });
                    return;
                }
                if (l.startsWith('## ')) {
                    flushParagraph();
                    blocks.push({ id: 'blk-' + uuid(), type: 'subheading', text: l.slice(3).trim() });
                    return;
                }
                // image token?
                const m = l.match(tokenRe);
                if (m) {
                    flushParagraph();
                    const tmp = m[1];
                    const caption = m[2] || '';
                    blocks.push({ id: 'blk-' + uuid(), type: 'image', temp_media_id: tmp, caption: caption });
                    return;
                }
                // otherwise a normal paragraph line -> buffer
                paragraphBuffer.push(line);
            });
            flushParagraph();

            // attach JSON into hidden input
            const hidden = tab.querySelector('input[data-contentblock-input]');
            if (hidden) hidden.value = JSON.stringify(blocks);
            // also sync uploads: remove upload blocks that don't have tokens
            removeOrphanUploads(sectionKey, blocks);
        });
    }

    // Remove upload blocks that no longer have a matching token
    function removeOrphanUploads(sectionKey, blocks) {
        const container = document.getElementById('section-uploads-' + sectionKey);
        if (!container) return;
        const presentTmp = new Set(blocks.filter(b => b.type === 'image' && b.temp_media_id).map(b => b.temp_media_id));
        Array.from(container.querySelectorAll('div[data-tmp-id]')).forEach(w => {
            const tmp = w.dataset.tmpId;
            if (!presentTmp.has(tmp)) w.remove();
        });
    }

    // Ensure upload blocks created earlier have dataset.tmpId attribute to be found by removal code
    // (When addUploadBlock creates wrappers we must set data-tmp-id)
    // We need to set that; but earlier addUploadBlock created wrapper.dataset.tmpId - verify.

    // ---------- previews for top-level images ----------
    const mainImage = document.getElementById('image');
    mainImage && mainImage.addEventListener('change', function(e){
        const f = this.files[0]; const preview = document.getElementById('image-preview');
        if (!f) { preview.innerHTML = ''; return; }
        if (f.size > 2*1024*1024) { preview.innerHTML = '<div class="text-red-500">File too large</div>'; this.value=''; return; }
        const r = new FileReader(); r.onload = e=> preview.innerHTML = `<img src="${e.target.result}" class="w-64 h-40 object-cover rounded">`; r.readAsDataURL(f);
    });

    const featured = document.getElementById('featured_image');
    featured && featured.addEventListener('change', function(e){
        const f = this.files[0]; const preview = document.getElementById('featured-preview');
        if (!f) { preview.innerHTML = ''; return; }
        if (f.size > 5*1024*1024) { preview.innerHTML = '<div class="text-red-500">File too large</div>'; this.value=''; return; }
        const r = new FileReader(); r.onload = e=> preview.innerHTML = `<img src="${e.target.result}" class="w-96 h-64 object-cover rounded">`; r.readAsDataURL(f);
    });

    const gallery = document.getElementById('gallery_images');
    gallery && gallery.addEventListener('change', function(e){
        const files = Array.from(this.files || []); const preview = document.getElementById('gallery-preview'); preview.innerHTML = '';
        files.forEach((f,idx)=>{
            if (f.size > 2*1024*1024) return;
            const r = new FileReader(); r.onload = ev=> {
                const div = document.createElement('div'); div.className='relative'; div.innerHTML = `<img src="${ev.target.result}" class="w-full h-32 object-cover rounded"><div class="absolute top-1 right-1 bg-blue-500 text-white px-2 py-1 rounded text-xs">${idx+1}</div>`; preview.appendChild(div);
            }; r.readAsDataURL(f);
        });
    });

    // ---------- keep local draft on failure: DO NOT clear localStorage on submit ----------
    // On real successful creation you may want to clear storage in the controller redirect.
    // For now we preserve drafts until the user manually clears them or we add a 'clear' action.

    // Optionally, restore first visible tab
    document.querySelector('.tab-button.active').click();
});
</script>

<style>
.tab-button.active { border-color: #10b981; color: #10b981; }
.tab-button:not(.active) { border-color: transparent; color: #6b7280; }
.tab-button:not(.active):hover { color: #374151; border-color: #d1d5db; }
</style>
@endpush
@endsection