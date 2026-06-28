@extends('layouts.admin')

@section('title', 'Import SEO Page')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Import SEO Page</h1>
        <a href="{{ route('admin.seo-pages.index') }}" class="text-sm text-gray-500 hover:text-gray-800">
            &larr; Back to Pages
        </a>
    </div>

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg p-4 mb-6">
            <ul class="list-disc list-inside text-sm space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <form action="{{ route('admin.seo-pages.import.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Upload DOCX or TXT File <span class="text-red-500">*</span>
                </label>
                <input type="file" name="file" accept=".docx,.txt" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <p class="text-xs text-gray-400 mt-1">Supports .docx and .txt files (Max 5MB)</p>
            </div>

            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <h4 class="text-sm font-semibold text-gray-700 mb-2">📋 File Format Requirements:</h4>
                <ul class="text-xs text-gray-600 space-y-1 list-disc list-inside">
                    <li><strong>SEO Title:</strong> Starts with "SEO Title:"</li>
                    <li><strong>Meta Description:</strong> Starts with "Meta Description:"</li>
                    <li><strong>Focus Keyword:</strong> Starts with "Focus Keyword:"</li>
                    <li><strong>Headings:</strong> Text between <strong>**bold**</strong> tags</li>
                    <li><strong>Lists:</strong> Lines starting with - or • or numbers</li>
                    <li><strong>Paragraphs:</strong> Regular text lines</li>
                </ul>
            </div>

            <div class="flex gap-3">
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-6 rounded-lg text-sm transition">
                    <i class="fas fa-upload mr-2"></i> Import Page
                </button>
                <a href="{{ route('admin.seo-pages.index') }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium py-2.5 px-6 rounded-lg text-sm transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection