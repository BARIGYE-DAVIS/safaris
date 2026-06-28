@forelse($pages as $page)
    <tr class="hover:bg-gray-50 transition-colors">
        <td class="px-6 py-4 text-sm text-gray-400 font-mono">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</td>
        <td class="px-6 py-4">
            <p class="text-sm font-semibold text-gray-900">{{ $page->title }}</p>
        </td>
        <td class="px-6 py-4 text-sm text-gray-500">
            {{ $page->focus_keyword ?? '—' }}
        </td>
        <td class="px-6 py-4">
            @if($page->status === 'published')
                <span class="inline-flex items-center gap-1.5 text-xs font-medium bg-green-50 text-green-700 border border-green-200 px-2.5 py-1 rounded-full">
                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 inline-block"></span>
                    Published
                </span>
            @elseif($page->status === 'draft')
                <span class="inline-flex items-center gap-1.5 text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200 px-2.5 py-1 rounded-full">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-400 inline-block"></span>
                    Draft
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 text-xs font-medium bg-gray-100 text-gray-500 border border-gray-200 px-2.5 py-1 rounded-full">
                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400 inline-block"></span>
                    Archived
                </span>
            @endif
        </td>
        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
            {{ $page->created_at->format('d M Y') }}
        </td>
        <td class="px-6 py-4">
            <div class="flex items-center justify-end gap-2">
                @if($page->status === 'published')
                    <a href="{{ route('seo-pages.show', $page->slug) }}"
                       target="_blank"
                       class="inline-flex items-center gap-1 text-xs font-medium text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 border border-indigo-200 px-3 py-1.5 rounded-lg">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                        View
                    </a>
                @endif

                <a href="{{ route('admin.seo-pages.edit', $page) }}"
                   class="inline-flex items-center gap-1 text-xs font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-100 border border-gray-200 px-3 py-1.5 rounded-lg">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit
                </a>

                <form action="{{ route('admin.seo-pages.toggle-status', $page) }}" method="POST" class="inline">
                    @csrf
                    @method('PATCH')
                    @if($page->status === 'published')
                        <button type="submit" class="inline-flex items-center gap-1 text-xs font-medium text-amber-600 hover:text-amber-800 hover:bg-amber-50 border border-amber-200 px-3 py-1.5 rounded-lg">
                            Deactivate
                        </button>
                    @else
                        <button type="submit" class="inline-flex items-center gap-1 text-xs font-medium text-green-600 hover:text-green-800 hover:bg-green-50 border border-green-200 px-3 py-1.5 rounded-lg">
                            Publish
                        </button>
                    @endif
                </form>

                <form action="{{ route('admin.seo-pages.destroy', $page) }}"
                      method="POST"
                      class="inline"
                      onsubmit="return confirm('Delete this page? This cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center gap-1 text-xs font-medium text-red-500 hover:text-red-700 hover:bg-red-50 border border-red-200 px-3 py-1.5 rounded-lg">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Delete
                    </button>
                </form>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6" class="px-6 py-16 text-center">
            <div class="flex flex-col items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center">
                    <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <p class="text-sm font-medium text-gray-900">No pages found</p>
                <p class="text-xs text-gray-500">Try a different search term or create a new page.</p>
                <a href="{{ route('admin.seo-pages.create') }}" class="mt-2 text-sm font-medium text-indigo-600 hover:text-indigo-800 underline">
                    Create a new page
                </a>
            </div>
        </td>
    </tr>
@endforelse