@extends('layouts.admin')

@section('title', 'Activity Options')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap');

    * { font-family: 'DM Sans', sans-serif; }

    .page-bg {
        background: #f8f7f4;
        min-height: 100vh;
    }

    .badge-bring {
        background: #dbeafe;
        color: #1d4ed8;
    }
    .badge-included {
        background: #dcfce7;
        color: #15803d;
    }
    .badge-excluded {
        background: #fef3c7;
        color: #b45309;
    }

    .card-glass {
        background: #ffffff;
        border: 1px solid #eeede9;
        border-radius: 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 4px 16px rgba(0,0,0,0.04);
    }

    .input-clean {
        background: #f8f7f4;
        border: 1.5px solid #e8e6e1;
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 14px;
        color: #1c1917;
        transition: all 0.15s ease;
        width: 100%;
        outline: none;
    }
    .input-clean:focus {
        border-color: #6366f1;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(99,102,241,0.08);
    }

    .select-clean {
        background: #f8f7f4 url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236b7280' d='M6 8L1 3h10z'/%3E%3C/svg%3E") no-repeat right 14px center;
        border: 1.5px solid #e8e6e1;
        border-radius: 10px;
        padding: 10px 36px 10px 14px;
        font-size: 14px;
        color: #1c1917;
        transition: all 0.15s ease;
        width: 100%;
        outline: none;
        appearance: none;
        cursor: pointer;
    }
    .select-clean:focus {
        border-color: #6366f1;
        background-color: #fff;
        box-shadow: 0 0 0 3px rgba(99,102,241,0.08);
    }

    .btn-primary-solid {
        background: #1c1917;
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 11px 20px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.15s ease;
        width: 100%;
        letter-spacing: 0.01em;
    }
    .btn-primary-solid:hover {
        background: #292524;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .btn-primary-solid:active { transform: translateY(0); }

    .btn-update {
        background: #eff6ff;
        color: #2563eb;
        border: 1.5px solid #bfdbfe;
        border-radius: 8px;
        padding: 6px 14px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.15s ease;
        white-space: nowrap;
    }
    .btn-update:hover {
        background: #2563eb;
        color: #fff;
        border-color: #2563eb;
    }

    .btn-delete {
        background: #fff1f2;
        color: #e11d48;
        border: 1.5px solid #fecdd3;
        border-radius: 8px;
        padding: 6px 14px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.15s ease;
        white-space: nowrap;
    }
    .btn-delete:hover {
        background: #e11d48;
        color: #fff;
        border-color: #e11d48;
    }

    .btn-filter {
        background: #1c1917;
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 10px 20px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.15s ease;
        white-space: nowrap;
    }
    .btn-filter:hover { background: #292524; }

    .table-row {
        border-bottom: 1px solid #f0ede8;
        transition: background 0.1s ease;
    }
    .table-row:last-child { border-bottom: none; }
    .table-row:hover { background: #faf9f7; }

    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 40px;
        height: 22px;
    }
    .toggle-switch input { opacity: 0; width: 0; height: 0; }
    .toggle-slider {
        position: absolute;
        cursor: pointer;
        inset: 0;
        background: #e5e7eb;
        border-radius: 22px;
        transition: 0.2s;
    }
    .toggle-slider:before {
        content: '';
        position: absolute;
        height: 16px;
        width: 16px;
        left: 3px;
        bottom: 3px;
        background: #fff;
        border-radius: 50%;
        transition: 0.2s;
        box-shadow: 0 1px 3px rgba(0,0,0,0.2);
    }
    .toggle-switch input:checked + .toggle-slider { background: #22c55e; }
    .toggle-switch input:checked + .toggle-slider:before { transform: translateX(18px); }

    .stat-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 20px;
        padding: 4px 12px;
        font-size: 12px;
        font-weight: 600;
        color: #15803d;
    }

    .section-label {
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #9ca3af;
        margin-bottom: 8px;
    }

    .alert-success-custom {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 12px;
        color: #15803d;
        padding: 14px 18px;
        font-size: 14px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .alert-danger-custom {
        background: #fff1f2;
        border: 1px solid #fecdd3;
        border-radius: 12px;
        color: #be123c;
        padding: 14px 18px;
        font-size: 14px;
        font-weight: 500;
    }

    .empty-state {
        padding: 60px 20px;
        text-align: center;
    }
    .empty-state-icon {
        width: 56px;
        height: 56px;
        background: #f3f4f6;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
    }

    .input-inline {
        background: #f8f7f4;
        border: 1.5px solid #e8e6e1;
        border-radius: 8px;
        padding: 7px 11px;
        font-size: 13px;
        color: #1c1917;
        transition: all 0.15s ease;
        width: 100%;
        outline: none;
        font-family: 'DM Mono', monospace;
    }
    .input-inline:focus {
        border-color: #6366f1;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(99,102,241,0.08);
    }

    .page-title {
        font-size: 24px;
        font-weight: 700;
        color: #1c1917;
        letter-spacing: -0.03em;
    }

    .breadcrumb-item {
        font-size: 13px;
        color: #9ca3af;
    }

    .dismiss-btn {
        background: none;
        border: none;
        cursor: pointer;
        color: inherit;
        opacity: 0.6;
        padding: 0;
        line-height: 1;
    }
    .dismiss-btn:hover { opacity: 1; }
</style>
@endpush

@section('content')
<div class="page-bg px-6 py-8">

    {{-- Page Header --}}
    <div style="max-width: 1200px; margin: 0 auto;">

        {{-- Breadcrumb --}}
        <div class="flex items-center gap-2 mb-6">
            <span class="breadcrumb-item">Admin</span>
            <span class="breadcrumb-item">›</span>
            <span class="breadcrumb-item">Activities</span>
            <span class="breadcrumb-item">›</span>
            <span style="font-size:13px; color:#1c1917; font-weight:600;">Options</span>
        </div>

        {{-- Title Row --}}
        <div class="flex items-start justify-between mb-8">
            <div>
                <h1 class="page-title">Activity Options</h1>
                <p style="color:#78716c; font-size:14px; margin-top:4px;">
                    Manage reusable options for What to Bring, Included, and Not Included.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <div class="stat-chip">
                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                        <circle cx="6" cy="6" r="5" fill="#22c55e"/>
                    </svg>
                    {{ method_exists($options, 'total') ? $options->total() : $options->count() }} options
                </div>
            </div>
        </div>

        {{-- Alerts --}}
        @if(session('success'))
        <div class="alert-success-custom mb-6">
            <svg width="18" height="18" viewBox="0 0 20 20" fill="none">
                <circle cx="10" cy="10" r="9" fill="#22c55e"/>
                <path d="M6.5 10l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            {{ session('success') }}
        </div>
        @endif

        @if($errors->any())
        <div class="alert-danger-custom mb-6">
            <div style="font-weight:600; margin-bottom:6px;">Please fix these errors:</div>
            <ul style="margin:0; padding-left:18px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Main Layout --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- ── Left: Create Form ── --}}
            <div class="lg:col-span-1">
                <div class="card-glass p-6 sticky top-6">
                    <div class="section-label">New Option</div>
                    <h2 style="font-size:16px; font-weight:700; color:#1c1917; margin-bottom:24px;">Add Option</h2>

                    <form action="{{ route('admin.activities.options.store') }}" method="POST">
                        @csrf

                        <div style="margin-bottom:18px;">
                            <label style="display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:6px;">
                                Type <span style="color:#e11d48;">*</span>
                            </label>
                            <select name="type" class="select-clean" required>
                                <option value="">— Select type —</option>
                                <option value="bring"    {{ old('type') === 'bring'    ? 'selected' : '' }}>🎒 What to Bring</option>
                                <option value="included" {{ old('type') === 'included' ? 'selected' : '' }}>✅ Included</option>
                                <option value="excluded" {{ old('type') === 'excluded' ? 'selected' : '' }}>❌ Not Included</option>
                            </select>
                        </div>

                        <div style="margin-bottom:18px;">
                            <label style="display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:6px;">
                                Option Text <span style="color:#e11d48;">*</span>
                            </label>
                            <input
                                type="text"
                                name="name"
                                class="input-clean"
                                maxlength="255"
                                placeholder="e.g. Drinking water"
                                value="{{ old('name') }}"
                                required
                            >
                        </div>

                        <div style="margin-bottom:24px; display:flex; align-items:center; gap:10px;">
                            <label class="toggle-switch">
                                <input type="checkbox" id="is_active" name="is_active" value="1" checked>
                                <span class="toggle-slider"></span>
                            </label>
                            <label for="is_active" style="font-size:13px; font-weight:500; color:#374151; cursor:pointer;">
                                Active
                            </label>
                        </div>

                        <button class="btn-primary-solid" type="submit">
                            Save Option
                        </button>
                    </form>

                    {{-- Type legend --}}
                    <div style="margin-top:28px; padding-top:24px; border-top:1px solid #f0ede8;">
                        <div class="section-label">Type Guide</div>
                        <div style="display:flex; flex-direction:column; gap:8px; margin-top:8px;">
                            <div style="display:flex; align-items:center; gap:8px; font-size:13px; color:#374151;">
                                <span style="font-size:16px;">🎒</span> Things guests should bring
                            </div>
                            <div style="display:flex; align-items:center; gap:8px; font-size:13px; color:#374151;">
                                <span style="font-size:16px;">✅</span> What's covered in the tour
                            </div>
                            <div style="display:flex; align-items:center; gap:8px; font-size:13px; color:#374151;">
                                <span style="font-size:16px;">❌</span> What's not included
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Right: Options Table ── --}}
            <div class="lg:col-span-2">
                <div class="card-glass overflow-hidden">

                    {{-- Filter Bar --}}
                    <div style="padding:20px 24px; border-bottom:1px solid #f0ede8; background:#faf9f7;">
                        <form method="GET" action="{{ route('admin.activities.options.index') }}">
                            <div class="flex items-center gap-3 flex-wrap">
                                <div style="flex:0 0 auto; min-width:160px;">
                                    <select name="type" class="select-clean" style="background-color:#fff;">
                                        <option value="">All types</option>
                                        <option value="bring"    {{ request('type') === 'bring'    ? 'selected' : '' }}>🎒 What to Bring</option>
                                        <option value="included" {{ request('type') === 'included' ? 'selected' : '' }}>✅ Included</option>
                                        <option value="excluded" {{ request('type') === 'excluded' ? 'selected' : '' }}>❌ Not Included</option>
                                    </select>
                                </div>
                                <div style="flex:1; min-width:200px; position:relative;">
                                    <svg style="position:absolute; left:12px; top:50%; transform:translateY(-50%); color:#9ca3af;" width="15" height="15" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                                    </svg>
                                    <input
                                        type="text"
                                        name="search"
                                        value="{{ request('search') }}"
                                        class="input-clean"
                                        style="background:#fff; padding-left:36px;"
                                        placeholder="Search options..."
                                    >
                                </div>
                                <button class="btn-filter" type="submit">Filter</button>
                                @if(request('type') || request('search'))
                                    <a href="{{ route('admin.activities.options.index') }}" style="font-size:13px; color:#9ca3af; text-decoration:none; font-weight:500;">
                                        Clear
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>

                    {{-- Table Header --}}
                    <div style="display:grid; grid-template-columns:140px 1fr 70px 160px; gap:0; padding:10px 24px; background:#faf9f7; border-bottom:1px solid #f0ede8;">
                        <div style="font-size:11px; font-weight:700; letter-spacing:0.06em; text-transform:uppercase; color:#9ca3af;">Type</div>
                        <div style="font-size:11px; font-weight:700; letter-spacing:0.06em; text-transform:uppercase; color:#9ca3af;">Option Text</div>
                        <div style="font-size:11px; font-weight:700; letter-spacing:0.06em; text-transform:uppercase; color:#9ca3af;">Active</div>
                        <div style="font-size:11px; font-weight:700; letter-spacing:0.06em; text-transform:uppercase; color:#9ca3af; text-align:right;">Actions</div>
                    </div>

                    {{-- Rows --}}
                    @forelse($options as $option)
                    <div class="table-row" style="display:grid; grid-template-columns:140px 1fr 70px 160px; align-items:center; padding:12px 24px; gap:0;">

                        {{-- Type Badge --}}
                        <div>
                            @if($option->type === 'bring')
                                <span style="display:inline-flex; align-items:center; gap:4px; padding:3px 10px; border-radius:20px; font-size:12px; font-weight:600; background:#dbeafe; color:#1d4ed8;">
                                    🎒 Bring
                                </span>
                            @elseif($option->type === 'included')
                                <span style="display:inline-flex; align-items:center; gap:4px; padding:3px 10px; border-radius:20px; font-size:12px; font-weight:600; background:#dcfce7; color:#15803d;">
                                    ✅ Included
                                </span>
                            @else
                                <span style="display:inline-flex; align-items:center; gap:4px; padding:3px 10px; border-radius:20px; font-size:12px; font-weight:600; background:#fef3c7; color:#b45309;">
                                    ❌ Excluded
                                </span>
                            @endif
                        </div>

                        {{-- Inline Update Form (spans 3 columns logically) --}}
                        <form action="{{ route('admin.activities.options.update', $option->id) }}" method="POST"
                              style="display:contents;">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="type" value="{{ $option->type }}">

                            {{-- Option text input --}}
                            <div style="padding-right:12px;">
                                <input
                                    type="text"
                                    name="name"
                                    class="input-inline"
                                    value="{{ $option->name }}"
                                    maxlength="255"
                                    required
                                >
                            </div>

                            {{-- Toggle --}}
                            <div>
                                <label class="toggle-switch">
                                    <input type="checkbox" name="is_active" value="1" {{ $option->is_active ? 'checked' : '' }}>
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>

                            {{-- Actions --}}
                            <div style="display:flex; align-items:center; justify-content:flex-end; gap:6px;">
                                <button type="submit" class="btn-update">Update</button>
                        </form>

                                <form action="{{ route('admin.activities.options.destroy', $option->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Delete this option?');"
                                      style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete">Delete</button>
                                </form>
                            </div>
                    </div>
                    @empty
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="1.5">
                                <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <p style="color:#6b7280; font-size:15px; font-weight:500; margin-bottom:4px;">No options found</p>
                        <p style="color:#9ca3af; font-size:13px;">Add your first option using the form on the left.</p>
                    </div>
                    @endforelse

                    {{-- Pagination --}}
                    @if(method_exists($options, 'links') && $options->hasPages())
                    <div style="padding:16px 24px; border-top:1px solid #f0ede8; background:#faf9f7;">
                        {{ $options->links() }}
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endsection