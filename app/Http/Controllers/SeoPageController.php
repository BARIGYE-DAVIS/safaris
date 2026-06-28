<?php

namespace App\Http\Controllers;

use App\Models\SeoPage;
use App\Models\SeoPageBlock;
use App\Models\SeoPageImage;
use App\Models\SeoPageLink;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class SeoPageController extends Controller
{
    // ── INDEX ─────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = SeoPage::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('focus_keyword', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pages = $query->orderBy('created_at', 'desc')->get();

        if ($request->ajax()) {
            $html = view('admin.seo_pages.partials.pages-table', compact('pages'))->render();

            return response()->json([
                'html'  => $html,
                'stats' => [
                    'total'     => $pages->count(),
                    'published' => $pages->where('status', 'published')->count(),
                    'drafts'    => $pages->where('status', 'draft')->count(),
                    'archived'  => $pages->where('status', 'archived')->count(),
                ],
            ]);
        }

        return view('admin.seo_pages.index', compact('pages'));
    }

    // ── CREATE ────────────────────────────────────────────
    public function create()
    {
        return view('admin.seo_pages.create');
    }

    // ── STORE ─────────────────────────────────────────────
    public function store(Request $request)
    {
        $this->validatePageData($request);

        $featuredImagePath = $this->handleFeaturedImageUpload($request, null);

        $slug = $request->slug ? Str::slug($request->slug) : Str::slug($request->title);

        $page = SeoPage::create([
            'title'            => $request->title,
            'slug'             => $slug,
            'meta_description' => $request->meta_description,
            'seo_title'        => $request->seo_title,
            'focus_keyword'    => $request->focus_keyword,
            'featured_image'   => $featuredImagePath,
            'status'           => $request->status,
        ]);

        $this->saveBlocks($page->id, $request->blocks ?? []);

        return redirect()->route('admin.seo-pages.index')
            ->with('success', 'SEO page created successfully.');
    }

    // ── EDIT ──────────────────────────────────────────────
    public function edit(SeoPage $seoPage)
    {
        $seoPage->load(['blocks.images', 'blocks.links']);
        return view('admin.seo_pages.edit', compact('seoPage'));
    }

    // ── UPDATE ────────────────────────────────────────────
    public function update(Request $request, SeoPage $seoPage)
    {
        $this->validatePageData($request, $seoPage->id);

        $featuredImagePath = $this->handleFeaturedImageUpload($request, $seoPage);

        $slug = $request->slug ? Str::slug($request->slug) : Str::slug($request->title);

        $seoPage->update([
            'title'            => $request->title,
            'slug'             => $slug,
            'meta_description' => $request->meta_description,
            'seo_title'        => $request->seo_title,
            'focus_keyword'    => $request->focus_keyword,
            'featured_image'   => $featuredImagePath,
            'status'           => $request->status,
        ]);

        $this->syncBlocks($seoPage, $request->blocks ?? []);

        return redirect()->route('admin.seo-pages.edit', $seoPage->id)
            ->with('success', 'SEO page updated successfully.');
    }

    // ── TOGGLE STATUS ─────────────────────────────────────
    public function toggleStatus(SeoPage $seoPage)
    {
        $seoPage->update([
            'status' => $seoPage->status === 'published' ? 'archived' : 'published',
        ]);

        return redirect()->route('admin.seo-pages.index')
            ->with('success', 'Page status updated.');
    }

    // ── DESTROY ───────────────────────────────────────────
    public function destroy(SeoPage $seoPage)
    {
        if ($seoPage->featured_image) {
            $this->deleteImage($seoPage->featured_image);
        }

        foreach ($seoPage->blocks as $block) {
            foreach ($block->images as $image) {
                $this->deleteImage($image->image_path);
            }
        }

        $seoPage->delete();

        return redirect()->route('admin.seo-pages.index')
            ->with('success', 'SEO page deleted successfully.');
    }

    // ── PUBLIC SHOW ───────────────────────────────────────
    public function show($slug)
    {
        $page = SeoPage::where('slug', $slug)
            ->where('status', 'published')
            ->with(['blocks.images', 'blocks.links'])
            ->firstOrFail();

        return view('seo_pages.show', compact('page'));
    }

    // ──────────────────────────────────────────────────────
    // PRIVATE METHODS
    // ──────────────────────────────────────────────────────

    private function validatePageData(Request $request, ?int $pageId = null): void
    {
        $slugUnique = $pageId 
            ? 'unique:seo_pages,slug,' . $pageId 
            : 'unique:seo_pages,slug';

        $request->validate([
            'title'                     => 'required|string|max:255',
            'slug'                      => 'nullable|string|max:255|' . $slugUnique,
            'meta_description'          => 'nullable|string|max:320',
            'seo_title'                 => 'nullable|string|max:255',
            'focus_keyword'             => 'nullable|string|max:255',
            'featured_image'            => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3048',
            'status'                    => 'required|in:draft,published,archived',
            'blocks'                    => 'nullable|array',
            'blocks.*.type'             => 'required|in:text,heading,image,links,list,table,buttons',
            'blocks.*.content'          => 'nullable|string',
            'blocks.*.heading_level'    => 'nullable|in:h1,h2,h3,h4,h5,h6',
            'blocks.*.list_type'        => 'nullable|in:ul,ol',
            // Table fields
            'blocks.*.caption'          => 'nullable|string',
            'blocks.*.headers'          => 'nullable|array',
            'blocks.*.rows'             => 'nullable|array',
            'blocks.*.striped'          => 'nullable|boolean',
            'blocks.*.bordered'         => 'nullable|boolean',
            'blocks.*.hoverable'        => 'nullable|boolean',
            'blocks.*.small'            => 'nullable|boolean',
            'blocks.*.header_bg_color'  => 'nullable|string',
            'blocks.*.header_text_color'=> 'nullable|string',
            'blocks.*.row_bg_color'     => 'nullable|string',
            'blocks.*.row_bg_alt_color' => 'nullable|string',
            'blocks.*.row_text_color'   => 'nullable|string',
            'blocks.*.border_color'     => 'nullable|string',
            // Buttons fields
            'blocks.*.buttons'          => 'nullable|array',
            'blocks.*.alignment'        => 'nullable|in:left,center,right,justify',
            'blocks.*.direction'        => 'nullable|in:horizontal,vertical',
            'blocks.*.gap'              => 'nullable|in:small,medium,large',
            'blocks.*.default_bg_color' => 'nullable|string',
            'blocks.*.default_text_color' => 'nullable|string',
            'blocks.*.default_hover_bg_color' => 'nullable|string',
            'blocks.*.default_border_radius' => 'nullable|string',
            'blocks.*.buttons.*.text'   => 'nullable|string',
            'blocks.*.buttons.*.url'    => 'nullable|url|max:500',
            'blocks.*.buttons.*.bg_color' => 'nullable|string',
            'blocks.*.buttons.*.text_color' => 'nullable|string',
            'blocks.*.buttons.*.hover_bg_color' => 'nullable|string',
            'blocks.*.buttons.*.hover_text_color' => 'nullable|string',
            'blocks.*.buttons.*.border_radius' => 'nullable|string',
            'blocks.*.buttons.*.size'   => 'nullable|in:small,medium,large',
            'blocks.*.buttons.*.type'   => 'nullable|in:primary,secondary,outline,ghost',
            'blocks.*.buttons.*.icon'   => 'nullable|string',
            'blocks.*.buttons.*.target' => 'nullable|string',
            'blocks.*.buttons.*.rel'    => 'nullable|string',
            // Image fields
            'blocks.*.images'           => 'nullable|array',
            'blocks.*.images.*'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3048',
            'blocks.*.replace_images'   => 'nullable|array',
            'blocks.*.replace_images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3048',
            'blocks.*.delete_images'    => 'nullable|array',
            'blocks.*.delete_images.*'  => 'nullable|string',
            'blocks.*.alts'             => 'nullable|array',
            'blocks.*.existing_images'  => 'nullable|array',
            'blocks.*.existing_images.*'=> 'nullable|exists:seo_page_images,id',
            // Link fields
            'blocks.*.link_texts'       => 'nullable|array',
            'blocks.*.link_urls'        => 'nullable|array',
            'blocks.*.link_texts.*'     => 'nullable|string|max:255',
            'blocks.*.link_urls.*'      => 'nullable|url|max:500',
            'blocks.*.new_images_alt'   => 'nullable|string|max:255',
        ]);
    }

    private function handleFeaturedImageUpload(Request $request, ?SeoPage $seoPage): ?string
    {
        if (!$request->hasFile('featured_image')) {
            return $seoPage?->featured_image;
        }

        if ($seoPage && $seoPage->featured_image) {
            $this->deleteImage($seoPage->featured_image);
        }

        return $this->storeImage($request->file('featured_image'), 'seo_pages/featured');
    }

    private function saveBlocks(int $pageId, array $blocksData): void
    {
        $sortOrder = 0;
        foreach ($blocksData as $blockData) {
            $this->createBlock($pageId, $blockData, $sortOrder);
            $sortOrder++;
        }
    }

    private function syncBlocks(SeoPage $page, array $blocksData): void
    {
        $existingBlockIds = $page->blocks->pluck('id')->toArray();
        $newBlockIds = [];
        $sortOrder = 0;

        foreach ($blocksData as $blockData) {
            $blockId = $blockData['id'] ?? null;

            if ($blockId && in_array($blockId, $existingBlockIds)) {
                $this->updateBlock($page, $blockId, $blockData, $sortOrder);
                $newBlockIds[] = $blockId;
            } else {
                $block = $this->createBlock($page->id, $blockData, $sortOrder);
                $newBlockIds[] = $block->id;
            }
            $sortOrder++;
        }

        $blocksToDelete = array_diff($existingBlockIds, $newBlockIds);
        foreach ($blocksToDelete as $blockId) {
            $block = SeoPageBlock::find($blockId);
            if ($block) {
                foreach ($block->images as $image) {
                    $this->deleteImage($image->image_path);
                }
                $block->delete();
            }
        }
    }

    private function createBlock(int $pageId, array $blockData, int $sortOrder): SeoPageBlock
    {
        $block = SeoPageBlock::create([
            'seo_page_id'   => $pageId,
            'block_type'    => $blockData['type'],
            'heading_level' => $blockData['type'] === 'heading'
                                ? ($blockData['heading_level'] ?? 'h2')
                                : null,
            'list_type'     => $blockData['type'] === 'list'
                                ? ($blockData['list_type'] ?? 'ul')
                                : null,
            'content'       => in_array($blockData['type'], ['text', 'heading', 'list'])
                                ? ($blockData['content'] ?? null)
                                : null,
            'sort_order'    => $sortOrder,
        ]);

        if ($blockData['type'] === 'image') {
            $this->syncBlockImages($block, $blockData);
        }

        if ($blockData['type'] === 'links') {
            $this->syncBlockLinks($block, $blockData);
        }

        // --- NEW: TABLE BLOCK ---
        if ($blockData['type'] === 'table') {
            $this->saveTableBlockData($block, $blockData);
        }

        // --- NEW: BUTTONS BLOCK ---
        if ($blockData['type'] === 'buttons') {
            $this->saveButtonsBlockData($block, $blockData);
        }

        return $block;
    }

    private function updateBlock(SeoPage $page, int $blockId, array $blockData, int $sortOrder): void
    {
        $block = SeoPageBlock::find($blockId);

        if (!$block) {
            return;
        }

        $block->update([
            'heading_level' => $blockData['type'] === 'heading'
                                ? ($blockData['heading_level'] ?? 'h2')
                                : null,
            'list_type'     => $blockData['type'] === 'list'
                                ? ($blockData['list_type'] ?? 'ul')
                                : null,
            'content'       => in_array($blockData['type'], ['text', 'heading', 'list'])
                                ? ($blockData['content'] ?? null)
                                : null,
            'sort_order'    => $sortOrder,
        ]);

        if ($blockData['type'] === 'image') {
            $this->syncBlockImages($block, $blockData);
        }

        if ($blockData['type'] === 'links') {
            $this->syncBlockLinks($block, $blockData);
        }

        // --- NEW: TABLE BLOCK ---
        if ($blockData['type'] === 'table') {
            $this->saveTableBlockData($block, $blockData);
        }

        // --- NEW: BUTTONS BLOCK ---
        if ($blockData['type'] === 'buttons') {
            $this->saveButtonsBlockData($block, $blockData);
        }
    }

    /**
     * Save Table Block Data to the block's content field as JSON
     */
    private function saveTableBlockData(SeoPageBlock $block, array $blockData): void
    {
        $tableData = [
            'caption'           => $blockData['caption'] ?? null,
            'headers'           => $blockData['headers'] ?? [],
            'rows'              => $blockData['rows'] ?? [],
            'striped'           => isset($blockData['striped']),
            'bordered'          => isset($blockData['bordered']) ? true : false,
            'hoverable'         => isset($blockData['hoverable']),
            'small'             => isset($blockData['small']),
            'header_bg_color'   => $blockData['header_bg_color'] ?? '#f3f4f6',
            'header_text_color' => $blockData['header_text_color'] ?? '#111827',
            'row_bg_color'      => $blockData['row_bg_color'] ?? '#ffffff',
            'row_bg_alt_color'  => $blockData['row_bg_alt_color'] ?? '#f9fafb',
            'row_text_color'    => $blockData['row_text_color'] ?? '#111827',
            'border_color'      => $blockData['border_color'] ?? '#d1d5db',
        ];

        $block->content = json_encode($tableData);
        $block->save();
    }

    /**
     * Save Buttons Block Data to the block's content field as JSON
     */
    private function saveButtonsBlockData(SeoPageBlock $block, array $blockData): void
    {
        $buttons = [];
        if (!empty($blockData['buttons']) && is_array($blockData['buttons'])) {
            foreach ($blockData['buttons'] as $btn) {
                $buttons[] = [
                    'text'              => $btn['text'] ?? 'Button',
                    'url'               => $btn['url'] ?? '#',
                    'bg_color'          => $btn['bg_color'] ?? '#2563eb',
                    'text_color'        => $btn['text_color'] ?? '#ffffff',
                    'hover_bg_color'    => $btn['hover_bg_color'] ?? '#1d4ed8',
                    'hover_text_color'  => $btn['hover_text_color'] ?? '#ffffff',
                    'border_radius'     => $btn['border_radius'] ?? '8px',
                    'size'              => $btn['size'] ?? 'medium',
                    'type'              => $btn['type'] ?? 'primary',
                    'icon'              => $btn['icon'] ?? null,
                    'target'            => $btn['target'] ?? '_self',
                    'rel'               => $btn['rel'] ?? '',
                ];
            }
        }

        $buttonsData = [
            'buttons'                   => $buttons,
            'alignment'                 => $blockData['alignment'] ?? 'left',
            'direction'                 => $blockData['direction'] ?? 'horizontal',
            'gap'                       => $blockData['gap'] ?? 'medium',
            'default_bg_color'          => $blockData['default_bg_color'] ?? '#2563eb',
            'default_text_color'        => $blockData['default_text_color'] ?? '#ffffff',
            'default_hover_bg_color'    => $blockData['default_hover_bg_color'] ?? '#1d4ed8',
            'default_hover_text_color'  => $blockData['default_hover_text_color'] ?? '#ffffff',
            'default_border_radius'     => $blockData['default_border_radius'] ?? '8px',
        ];

        $block->content = json_encode($buttonsData);
        $block->save();
    }

    private function syncBlockImages(SeoPageBlock $block, array $blockData): void
    {
        $existingImageIds = $block->images->pluck('id')->toArray();
        $deleteImageIds = $blockData['delete_images'] ?? [];
        $deleteImageIds = array_filter($deleteImageIds);
        $remainingImageIds = [];

        $existingImagesData = $blockData['existing_images'] ?? [];
        $replaceImagesData = $blockData['replace_images'] ?? [];
        $altTextsData = $blockData['alts'] ?? [];

        foreach ($existingImagesData as $imageId => $imageIdentifier) {
            if (in_array($imageId, $deleteImageIds)) {
                continue;
            }

            $image = SeoPageImage::find($imageId);
            if (!$image) {
                continue;
            }

            $replacementFile = $replaceImagesData[$imageId] ?? null;
            if ($replacementFile && $replacementFile instanceof UploadedFile) {
                $this->deleteImage($image->image_path);
                $newPath = $this->storeImage($replacementFile, 'seo_pages/blocks');
                $image->image_path = $newPath;
            }

            $altText = $altTextsData[$imageId] ?? null;
            if ($altText !== null) {
                $image->alt_text = $altText;
            }

            $image->save();
            $remainingImageIds[] = $imageId;
        }

        foreach ($deleteImageIds as $imageId) {
            $image = SeoPageImage::find($imageId);
            if ($image) {
                $this->deleteImage($image->image_path);
                $image->delete();
            }
        }

        $newImages = $blockData['images'] ?? [];
        if (!empty($newImages) && is_array($newImages)) {
            $currentMaxOrder = $block->images()->max('sort_order') ?? 0;
            $orderCounter = $currentMaxOrder + 1;

            foreach ($newImages as $i => $newImage) {
                if ($newImage && $newImage instanceof UploadedFile && $newImage->isValid()) {
                    $path = $this->storeImage($newImage, 'seo_pages/blocks');

                    $altText = $altTextsData[$i] ?? ($altTextsData['new_' . $i] ?? null);

                    SeoPageImage::create([
                        'block_id'   => $block->id,
                        'image_path' => $path,
                        'alt_text'   => $altText,
                        'sort_order' => $orderCounter++,
                    ]);
                }
            }
        }
    }

    private function syncBlockLinks(SeoPageBlock $block, array $blockData): void
    {
        $block->links()->delete();

        $linkTexts = $blockData['link_texts'] ?? [];
        $linkUrls = $blockData['link_urls'] ?? [];

        foreach ($linkTexts as $i => $linkText) {
            $linkUrl = $linkUrls[$i] ?? null;
            if ($linkText && $linkUrl) {
                SeoPageLink::create([
                    'seo_page_id'       => $block->seo_page_id,
                    'block_id'          => $block->id,
                    'linked_page_title' => $linkText,
                    'linked_page_url'   => $linkUrl,
                ]);
            }
        }
    }

    private function storeImage(UploadedFile $file, string $folder): string
    {
        $destination = public_path('storage/' . $folder);

        if (!file_exists($destination)) {
            mkdir($destination, 0755, true);
        }

        $extension = $file->getClientOriginalExtension() ?: 'jpg';
        $filename  = Str::random(40) . '.' . $extension;

        $file->move($destination, $filename);

        return $folder . '/' . $filename;
    }

    private function deleteImage(string $relativePath): void
    {
        $fullPath = public_path('storage/' . $relativePath);

        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
    }
}