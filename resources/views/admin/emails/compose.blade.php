@extends('layouts.admin')

@section('title', 'Compose Email')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <div class="h-10 w-10 rounded-xl bg-indigo-600 text-white flex items-center justify-center font-bold">
                <i class="fas fa-envelope"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Compose Email</h1>
                <p class="text-sm text-gray-600">Draft and send a branded email to manual or existing recipients.</p>
            </div>
        </div>
    </div>

    @if (session('status'))
        <div class="mb-4 p-3 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- LEFT: FORM --}}
        <div class="lg:col-span-2 bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b bg-gray-50">
                <h2 class="font-semibold text-gray-800">Email Details</h2>
            </div>

            <form method="POST" action="{{ route('admin.emails.send') }}" class="p-6 space-y-6">
                @csrf

                {{-- Recipient --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Recipient</label>

                    <div class="flex items-center gap-6">
                        <label class="inline-flex items-center gap-2">
                            <input type="radio" name="recipient_mode" value="manual" class="recipientMode"
                                   {{ old('recipient_mode', 'manual') === 'manual' ? 'checked' : '' }}>
                            <span class="text-sm text-gray-700">Enter email manually</span>
                        </label>

                        <label class="inline-flex items-center gap-2">
                            <input type="radio" name="recipient_mode" value="existing" class="recipientMode"
                                   {{ old('recipient_mode') === 'existing' ? 'checked' : '' }}>
                            <span class="text-sm text-gray-700">Select existing</span>
                        </label>
                    </div>

                    <div class="mt-3 space-y-3">
                        <div id="manualEmailWrap">
                            <input type="email" name="to_email" value="{{ old('to_email') }}"
                                   class="w-full px-3 py-2.5 border rounded-lg border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                   placeholder="customer@example.com">
                        </div>

                        <div id="existingEmailWrap" class="space-y-3">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1">Source</label>
                                    <select name="existing_source" id="existingSource"
                                            class="w-full px-3 py-2.5 border rounded-lg border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="">-- Select source --</option>
                                        <option value="subscribers" {{ old('existing_source') === 'subscribers' ? 'selected' : '' }}>Subscribers</option>
                                        <option value="bookings" {{ old('existing_source') === 'bookings' ? 'selected' : '' }}>Bookings</option>
                                        <option value="contacts" {{ old('existing_source') === 'contacts' ? 'selected' : '' }}>Contact Messages</option>
                                        <option value="tour_requests" {{ old('existing_source') === 'tour_requests' ? 'selected' : '' }}>Custom Tour Requests</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1">Recipient</label>
                                    <select name="existing_id" id="existingId"
                                            class="w-full px-3 py-2.5 border rounded-lg border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="">-- Select recipient --</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Data for JS (passed from controller compose()) --}}
                            <script type="application/json" id="recipientsData">
                                {!! json_encode([
                                    'subscribers' => ($subscribers ?? collect())->map(fn($s) => [
                                        'id' => $s->id,
                                        'label' => $s->email,
                                    ])->values(),
                                    'bookings' => ($bookings ?? collect())->map(fn($b) => [
                                        'id' => $b->id,
                                        'label' => '#'.$b->id.' — '.($b->name ?? 'Customer').' — '.$b->email,
                                    ])->values(),
                                    'contacts' => ($contacts ?? collect())->map(fn($c) => [
                                        'id' => $c->id,
                                        'label' => '#'.$c->id.' — '.($c->name ?? 'Contact').' — '.$c->email,
                                    ])->values(),
                                    'tour_requests' => ($tourRequests ?? collect())->map(fn($t) => [
                                        'id' => $t->id,
                                        'label' => '#'.$t->id.' — '.($t->name ?? 'Customer').' — '.$t->email,
                                    ])->values(),
                                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
                            </script>

                            <p class="text-xs text-gray-500">
                                Pick a source, then select a recipient from your existing data.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Subject --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                    <input type="text" name="subject" value="{{ old('subject') }}" required
                           class="w-full px-3 py-2.5 border rounded-lg border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                           placeholder="Subject">
                </div>

                {{-- Greeting --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Greeting (optional)</label>
                    <input type="text" name="greeting" value="{{ old('greeting') }}"
                           class="w-full px-3 py-2.5 border rounded-lg border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                           placeholder="Hello John,">
                </div>

                {{-- Body (CKEditor) --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                    <textarea name="body" id="editor" rows="10" required
                              class="w-full px-3 py-2.5 border rounded-lg border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                              placeholder="Type your email here...">{!! old('body') !!}</textarea>
                    <p class="text-xs text-gray-500 mt-2">
                        You can highlight text and apply colors / formatting.
                    </p>
                </div>

                {{-- Signature --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Signature (optional)</label>
                    <textarea name="signature" rows="3"
                              class="w-full px-3 py-2.5 border rounded-lg border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                              placeholder="Regards,&#10;Calm Africa Safaris">{!! old('signature') !!}</textarea>
                </div>

                {{-- Branding (no link color) --}}
                <details class="border rounded-lg p-4 bg-gray-50">
                    <summary class="cursor-pointer text-sm font-semibold text-gray-700">Branding options (optional)</summary>

                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Brand Name</label>
                            <input type="text" name="brand_name" value="{{ old('brand_name', config('app.name')) }}"
                                   class="w-full px-3 py-2.5 border rounded-lg border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Header / Button Color</label>
                            <div class="flex items-center gap-3">
                                <input type="color" name="brand_color_picker" id="brandColorPicker"
                                       value="{{ old('brand_color_picker', '#2563EB') }}"
                                       class="h-10 w-14 p-1 rounded border border-gray-300 bg-white">
                                <input type="text" name="brand_color" id="brandColorText"
                                       value="{{ old('brand_color', '#2563EB') }}"
                                       class="flex-1 px-3 py-2.5 border rounded-lg border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                       placeholder="#2563EB">
                            </div>

                            <div class="mt-3 grid grid-cols-6 gap-2" id="brandPalette">
                                @php
                                    $palette = ['#2563EB','#4F46E5','#16A34A','#059669','#0EA5E9','#0891B2','#F97316','#EA580C','#DC2626','#BE123C','#7C3AED','#111827'];
                                    $current = old('brand_color', '#2563EB');
                                @endphp
                                @foreach($palette as $c)
                                    <button type="button"
                                            class="h-8 rounded border {{ strtolower($current) === strtolower($c) ? 'ring-2 ring-indigo-500' : '' }}"
                                            style="background: {{ $c }};"
                                            data-color="{{ $c }}"
                                            title="{{ $c }}"></button>
                                @endforeach
                            </div>

                            <p class="text-xs text-gray-500 mt-2">
                                Pick from palette or choose any color.
                            </p>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Preheader (optional)</label>
                            <input type="text" name="preheader" value="{{ old('preheader') }}"
                                   class="w-full px-3 py-2.5 border rounded-lg border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                   placeholder="Short preview text shown in inbox...">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Footer Note (optional)</label>
                            <input type="text" name="footer_note" value="{{ old('footer_note') }}"
                                   class="w-full px-3 py-2.5 border rounded-lg border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                   placeholder="If you did not expect this message, ignore it.">
                        </div>
                    </div>
                </details>

                <div class="pt-2 flex items-center justify-end gap-3">
                    <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 px-5 rounded-lg transition">
                        Send Email
                    </button>
                </div>
            </form>
        </div>

        {{-- RIGHT: QUICK HELP / NOTE --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b bg-gray-50">
                <h2 class="font-semibold text-gray-800">Tips</h2>
            </div>
            <div class="p-6 space-y-3 text-sm text-gray-600">
                <p><strong>Colors:</strong> Use the palette or picker. This sets the email header + button color.</p>
                <p><strong>Text coloring:</strong> Use the editor toolbar to color highlighted text.</p>
                <p><strong>Recipients:</strong> Choose manual email or select from existing records.</p>
                <p class="text-xs text-gray-500">
                    Note: Some email clients have limited CSS support. Keep formatting simple for best results.
                </p>
            </div>
        </div>
    </div>
</div>

{{-- CKEditor 5 (CDN) --}}
<script src="https://cdn.ckeditor.com/ckeditor5/40.2.0/classic/ckeditor.js"></script>
<script>
(function () {
    // Toggle manual vs existing recipient UI
    const manualWrap = document.getElementById('manualEmailWrap');
    const existingWrap = document.getElementById('existingEmailWrap');
    const radios = document.querySelectorAll('.recipientMode');

    function syncRecipientMode() {
        const mode = document.querySelector('input[name="recipient_mode"]:checked')?.value || 'manual';
        if (mode === 'manual') {
            manualWrap.style.display = '';
            existingWrap.style.display = 'none';
        } else {
            manualWrap.style.display = 'none';
            existingWrap.style.display = '';
        }
    }
    radios.forEach(r => r.addEventListener('change', syncRecipientMode));
    syncRecipientMode();

    // Existing recipients: populate second dropdown based on source
    const sourceEl = document.getElementById('existingSource');
    const idEl = document.getElementById('existingId');
    const dataEl = document.getElementById('recipientsData');

    let recipientsData = {};
    try { recipientsData = JSON.parse(dataEl.textContent || '{}'); } catch (e) { recipientsData = {}; }

    const oldExistingId = "{{ old('existing_id') }}";

    function populateRecipients() {
        const source = sourceEl.value;
        const items = recipientsData[source] || [];

        idEl.innerHTML = '<option value="">-- Select recipient --</option>';
        items.forEach(item => {
            const opt = document.createElement('option');
            opt.value = item.id;
            opt.textContent = item.label;
            if (oldExistingId && String(item.id) === String(oldExistingId)) opt.selected = true;
            idEl.appendChild(opt);
        });
    }
    sourceEl.addEventListener('change', populateRecipients);
    populateRecipients();

    // Brand color: palette + picker + text sync
    const picker = document.getElementById('brandColorPicker');
    const text = document.getElementById('brandColorText');
    const palette = document.getElementById('brandPalette');

    function setBrandColor(val) {
        if (!val) return;
        picker.value = val;
        text.value = val;
    }

    picker.addEventListener('input', () => setBrandColor(picker.value));
    text.addEventListener('input', () => setBrandColor(text.value));

    palette?.querySelectorAll('button[data-color]')?.forEach(btn => {
        btn.addEventListener('click', () => setBrandColor(btn.getAttribute('data-color')));
    });

    // CKEditor with font color + highlight
    ClassicEditor.create(document.querySelector('#editor'), {
        toolbar: [
            'heading',
            '|',
            'bold', 'italic', 'underline', 'strikethrough',
            '|',
            'fontColor', 'fontBackgroundColor',
            '|',
            'bulletedList', 'numberedList',
            '|',
            'alignment',
            '|',
            'link',
            '|',
            'undo', 'redo'
        ]
    }).catch(error => {
        console.error(error);
    });
})();
</script>
@endsection