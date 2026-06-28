

<?php $__env->startSection('title', 'Pages'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">

    <div class="max-w-7xl mx-auto">
        
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Pages</h1>
                <p class="mt-1 text-sm text-gray-500">Manage your SEO pages</p>
            </div>
            <a href="<?php echo e(route('admin.seo-pages.create')); ?>"
               class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition-colors shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                New Page
            </a>
        </div>

        
        <?php if(session('success')): ?>
            <div class="mb-6 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 text-sm px-4 py-3 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        
        <?php
            $total     = $pages->count();
            $published = $pages->where('status', 'published')->count();
            $drafts    = $pages->where('status', 'draft')->count();
            $archived  = $pages->where('status', 'archived')->count();
        ?>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-xl border border-gray-200 px-5 py-4 cursor-pointer hover:border-indigo-300 transition-colors stat-card"
                 data-status="">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total</p>
                <p class="mt-1 text-2xl font-bold text-gray-900" id="stat-total"><?php echo e($total); ?></p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 px-5 py-4 cursor-pointer hover:border-green-300 transition-colors stat-card"
                 data-status="published">
                <p class="text-xs font-medium text-green-600 uppercase tracking-wide">Published</p>
                <p class="mt-1 text-2xl font-bold text-gray-900" id="stat-published"><?php echo e($published); ?></p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 px-5 py-4 cursor-pointer hover:border-amber-300 transition-colors stat-card"
                 data-status="draft">
                <p class="text-xs font-medium text-amber-600 uppercase tracking-wide">Drafts</p>
                <p class="mt-1 text-2xl font-bold text-gray-900" id="stat-drafts"><?php echo e($drafts); ?></p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 px-5 py-4 cursor-pointer hover:border-gray-300 transition-colors stat-card"
                 data-status="archived">
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">Archived</p>
                <p class="mt-1 text-2xl font-bold text-gray-900" id="stat-archived"><?php echo e($archived); ?></p>
            </div>
        </div>

        
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
            
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex items-center gap-4 flex-wrap">
                <div class="relative flex-1 min-w-[200px] max-w-md">
                    <input type="text"
                           id="live-search"
                           placeholder="Search by page name, keyword, or slug..."
                           autocomplete="off"
                           class="w-full pl-10 pr-4 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white">
                    <svg class="absolute left-3 top-2.5 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <div id="search-loading" class="absolute right-3 top-2.5 hidden">
                        <svg class="animate-spin h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </div>

                
                <div id="active-filter-badge" class="hidden items-center gap-2 text-xs text-indigo-700 bg-indigo-50 border border-indigo-200 px-3 py-1.5 rounded-full">
                    Filtered: <span id="active-filter-label" class="font-semibold"></span>
                    <button id="clear-filter" class="ml-1 text-indigo-400 hover:text-indigo-700">&times;</button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">#</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Page Name</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Keyword</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Created</th>
                            <th class="px-6 py-3.5 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="pages-table-body">
                        <?php echo $__env->make('admin.seo_pages.partials.pages-table', ['pages' => $pages], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput      = document.getElementById('live-search');
    const searchLoading    = document.getElementById('search-loading');
    const tableBody        = document.getElementById('pages-table-body');
    const filterBadge      = document.getElementById('active-filter-badge');
    const filterLabel      = document.getElementById('active-filter-label');
    const clearFilterBtn   = document.getElementById('clear-filter');

    let searchTimeout;
    let activeStatus = '';

    // ── PERFORM SEARCH ──────────────────────────────────────
    function performSearch() {
        const query = searchInput.value.trim();

        searchLoading.classList.remove('hidden');

        const params = new URLSearchParams();
        if (query)        params.set('search', query);
        if (activeStatus) params.set('status', activeStatus);

        fetch(`<?php echo e(route('admin.seo-pages.index')); ?>?${params.toString()}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            tableBody.innerHTML = data.html;
            document.getElementById('stat-total').textContent     = data.stats.total;
            document.getElementById('stat-published').textContent = data.stats.published;
            document.getElementById('stat-drafts').textContent    = data.stats.drafts;
            document.getElementById('stat-archived').textContent  = data.stats.archived;
            searchLoading.classList.add('hidden');
        })
        .catch(() => searchLoading.classList.add('hidden'));
    }

    // ── SEARCH INPUT ────────────────────────────────────────
    searchInput.addEventListener('input', function () {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(performSearch, 300);
    });

    searchInput.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            searchInput.value = '';
            performSearch();
        }
    });

    // ── STAT CARD FILTERS ───────────────────────────────────
    document.querySelectorAll('.stat-card').forEach(card => {
        card.addEventListener('click', function () {
            const status = this.dataset.status;

            // Toggle off if already active
            if (activeStatus === status && status !== '') {
                activeStatus = '';
                filterBadge.classList.add('hidden');
                filterBadge.classList.remove('flex');
                document.querySelectorAll('.stat-card').forEach(c => c.classList.remove('ring-2', 'ring-indigo-400'));
            } else {
                activeStatus = status;

                document.querySelectorAll('.stat-card').forEach(c => c.classList.remove('ring-2', 'ring-indigo-400'));

                if (status !== '') {
                    this.classList.add('ring-2', 'ring-indigo-400');
                    filterLabel.textContent = status.charAt(0).toUpperCase() + status.slice(1);
                    filterBadge.classList.remove('hidden');
                    filterBadge.classList.add('flex');
                } else {
                    filterBadge.classList.add('hidden');
                    filterBadge.classList.remove('flex');
                }
            }

            performSearch();
        });
    });

    // ── CLEAR FILTER BADGE ──────────────────────────────────
    clearFilterBtn.addEventListener('click', function () {
        activeStatus = '';
        filterBadge.classList.add('hidden');
        filterBadge.classList.remove('flex');
        document.querySelectorAll('.stat-card').forEach(c => c.classList.remove('ring-2', 'ring-indigo-400'));
        performSearch();
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\safaris\resources\views/admin/seo_pages/index.blade.php ENDPATH**/ ?>