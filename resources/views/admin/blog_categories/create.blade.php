@extends('layouts.admin')

@section('title', 'Create Blog Category')

@section('content')
<div class="min-h-screen py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Create Category</h1>
                <p class="mt-1 text-sm text-slate-500">Add a new blog category. Fill in name, optional slug, description and icon.</p>
            </div>

            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.blog-categories.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-700 text-white rounded-lg shadow hover:bg-slate-600">
                    Back to list
                </a>
            </div>
        </div>

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="mb-4 p-4 rounded-lg bg-rose-50 border border-rose-200 text-rose-800">
                <strong class="block font-medium">Please fix the following:</strong>
                <ul class="mt-2 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form Card --}}
        <form id="category-form" action="{{ route('admin.blog-categories.store') }}" method="POST" enctype="multipart/form-data" class="bg-white border border-slate-200 rounded-lg shadow-sm p-6">
            @csrf

            <div class="grid grid-cols-1 gap-6">
                {{-- Name --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700">Name <span class="text-rose-500">*</span></label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" required
                           class="mt-2 block w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                {{-- Slug --}}
                <div>
                    <label for="slug" class="block text-sm font-medium text-slate-700">Slug (optional)</label>
                    <div class="mt-2 flex gap-2">
                        <input id="slug" name="slug" type="text" value="{{ old('slug') }}" placeholder="auto-generated from name"
                               class="flex-1 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <button id="generate-slug" type="button" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Generate</button>
                    </div>
                    <p class="mt-1 text-xs text-slate-500">Only letters, numbers, dashes and underscores allowed.</p>
                </div>

                {{-- Description --}}
                <div>
                    <label for="description" class="block text-sm font-medium text-slate-700">Description</label>
                    <textarea id="description" name="description" rows="4" class="mt-2 block w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('description') }}</textarea>
                </div>

                {{-- Icon (class or upload) --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="icon" class="block text-sm font-medium text-slate-700">Icon (CSS class)</label>
                        <input id="icon" name="icon" type="text" value="{{ old('icon') }}" placeholder="e.g. fas fa-leaf or heroicon-class"
                               class="mt-2 block w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <p class="mt-1 text-xs text-slate-500">Enter a font icon class (optional). If you upload an image below it will be used instead.</p>
                    </div>

                    <div>
                        <label for="icon_upload" class="block text-sm font-medium text-slate-700">Upload Icon (image)</label>
                        <div class="mt-2 flex items-center gap-3">
                            <label class="cursor-pointer inline-flex items-center gap-2 px-3 py-2 border rounded-lg bg-white hover:bg-slate-50">
                                <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v6a4 4 0 004 4h8m0 0l-3-3m3 3l3-3"></path>
                                </svg>
                                <span class="text-sm text-slate-700">Choose file</span>
                                <input id="icon_upload" name="icon_upload" type="file" accept="image/*" class="sr-only">
                            </label>

                            <div id="icon-preview" class="h-12 w-12 rounded border bg-slate-50 flex items-center justify-center overflow-hidden text-slate-500" aria-hidden="true">
                                <span class="text-xs">No file</span>
                            </div>
                        </div>
                        <p class="mt-1 text-xs text-slate-500">PNG/JPG/WebP, up to 5MB. Uploaded image will be stored as category icon.</p>
                    </div>
                </div>

                {{-- Order --}}
                <div>
                    <label for="order" class="block text-sm font-medium text-slate-700">Order</label>
                    <input id="order" name="order" type="number" value="{{ old('order', 0) }}" class="mt-2 w-40 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <p class="mt-1 text-xs text-slate-500">Lower numbers appear first. Defaults to 0.</p>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-3 pt-4">
                    <button type="submit" class="px-5 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700">Create Category</button>
                    <a href="{{ route('admin.blog-categories.index') }}" class="px-4 py-2 bg-slate-200 rounded-lg hover:bg-slate-300">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');
    const genBtn = document.getElementById('generate-slug');
    const iconUpload = document.getElementById('icon_upload');
    const iconPreview = document.getElementById('icon-preview');

    // Slug generator from name
    function slugify(str) {
        return str.toString().toLowerCase()
            .normalize('NFKD') // remove diacritics
            .replace(/[\u0300-\u036f]/g, '')
            .replace(/[^a-z0-9-_]+/g, '-') // replace invalid chars with hyphen
            .replace(/--+/g, '-')
            .replace(/^-+|-+$/g, '');
    }

    genBtn?.addEventListener('click', function () {
        const src = nameInput?.value || '';
        if (!src) {
            alert('Enter a category name first to generate a slug.');
            return;
        }
        slugInput.value = slugify(src);
    });

    nameInput?.addEventListener('blur', function () {
        // auto-populate slug when empty
        if (slugInput && (!slugInput.value || slugInput.value.trim() === '')) {
            slugInput.value = slugify(nameInput.value || '');
        }
    });

    // Icon upload preview
    iconUpload?.addEventListener('change', function () {
        const file = this.files && this.files[0];
        if (!file) {
            iconPreview.innerHTML = '<span class="text-xs">No file</span>';
            return;
        }

        if (!file.type.startsWith('image/')) {
            iconPreview.innerHTML = '<span class="text-xs text-rose-600">Invalid file</span>';
            this.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function (e) {
            iconPreview.innerHTML = '';
            const img = document.createElement('img');
            img.src = e.target.result;
            img.alt = 'icon preview';
            img.className = 'h-full w-full object-contain';
            iconPreview.appendChild(img);
        };
        reader.readAsDataURL(file);
    });

    // Simple client-side file size check (5MB)
    document.getElementById('category-form')?.addEventListener('submit', function (e) {
        const f = iconUpload && iconUpload.files && iconUpload.files[0];
        if (f && f.size > 5 * 1024 * 1024) {
            e.preventDefault();
            alert('Icon file must be 5MB or smaller.');
            return false;
        }
    });
});
</script>
@endpush