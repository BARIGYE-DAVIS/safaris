/**
 * BlogCreate — JS for admin/blogs/create.blade.php
 *
 * Design rules:
 *  - NO dynamic CSS injection or custom CSS classes written from JS
 *  - All visual states use real Tailwind utility classes toggled via classList
 *  - Three contenteditable fields: #title-editor, #excerpt-editor, #editor
 *  - Universal colour picker works across all three fields
 *  - Selection is saved before toolbar buttons steal focus
 *  - On submit: hidden inputs are synced from contenteditable innerHTML
 */

const BlogCreate = (function () {
  'use strict';

  // ─── Config ──────────────────────────────────────────────────────────────
  const cfg = {
    uploadUrl: '',
    csrfToken: '',
    maxFileSize: 5 * 1024 * 1024, // 5 MB
    autosaveKey: 'blog_create_draft',
    autosaveDelay: 4000,
  };

  // ─── State ────────────────────────────────────────────────────────────────
  const s = {
    titleEl:   null,
    excerptEl: null,
    editorEl:  null,
    form:      null,

    activeField: null,   // which colorable div last had focus
    savedRange:  null,   // saved Selection range before toolbar click
    autosaveTimer: null,

    // Theme colour maps — only used to patch body / editor bg
    // (page chrome stays dark Tailwind slate, only editor area tinted)
    themes: {
      dark:   { page: '#0f172a', editor: '#0a0a0a',  editorText: '#e2e8f0' },
      light:  { page: '#f8fafc', editor: '#ffffff',   editorText: '#1e293b' },
      green:  { page: '#064e3b', editor: '#022c22',   editorText: '#d1fae5' },
      yellow: { page: '#78350f', editor: '#451a03',   editorText: '#fef3c7' },
      blue:   { page: '#1e3a8a', editor: '#172554',   editorText: '#dbeafe' },
      gray:   { page: '#374151', editor: '#1f2937',   editorText: '#f3f4f6' },
      purple: { page: '#581c87', editor: '#3b0764',   editorText: '#f3e8ff' },
      pink:   { page: '#9f1239', editor: '#831843',   editorText: '#fce7f3' },
    },
  };

  // ─── Init ─────────────────────────────────────────────────────────────────
  function init(options) {
    Object.assign(cfg, options);

    s.titleEl   = document.getElementById('title-editor');
    s.excerptEl = document.getElementById('excerpt-editor');
    s.editorEl  = document.getElementById('editor');
    s.form      = document.getElementById('blog-form');

    if (!s.editorEl) { console.error('[BlogCreate] #editor not found'); return; }

    _setupActiveFieldTracking();
    _setupFormatButtons();
    _setupColorPickers();
    _setupThemeSwatches();
    _setupImageUpload();
    _setupFeaturedImageDropzone();
    _setupLinkInsertion();
    _setupPreview();
    _setupFormSubmit();
    _setupAutoSave();
    _setupCharCount();
    _setupKeyboardShortcuts();
    _restoreDraft();

    console.log('[BlogCreate] ready');
  }

  // ─── Active field tracking ───────────────────────────────────────────────
  /**
   * We track which contenteditable was last focused so colour tools know
   * where to apply. We also save the selection range right before a toolbar
   * button can steal focus (capture phase mousedown on the toolbar).
   */
  function _setupActiveFieldTracking() {
    const fields = [s.titleEl, s.excerptEl, s.editorEl].filter(Boolean);

    fields.forEach(field => {
      field.addEventListener('focusin',  () => _setActiveField(field));
      field.addEventListener('keyup',    _saveRange);
      field.addEventListener('mouseup',  _saveRange);
      field.addEventListener('input',    _saveRange);

      // Block Enter in title — keep it single-line
      if (field === s.titleEl) {
        field.addEventListener('keydown', e => {
          if (e.key === 'Enter') { e.preventDefault(); }
        });
      }
    });

    // Save range BEFORE toolbar buttons can move focus (capture phase)
    const toolbar = document.querySelector('.fixed.bottom-0');
    toolbar?.addEventListener('mousedown', _saveRange, true);
  }

  function _setActiveField(field) {
    if (!field) return;

    // Remove glow from all fields
    [s.titleEl, s.excerptEl, s.editorEl].forEach(f => {
      f?.classList.remove('color-target');
    });

    field.classList.add('color-target');
    s.activeField = field;

    // Update toolbar badge
    const labels = {
      'title-editor':   'Title',
      'excerpt-editor': 'Excerpt',
      'editor':         'Content',
    };
    const badge = document.getElementById('toolbar-field-indicator');
    const fieldBadge = document.getElementById('field-indicator');
    const label = labels[field.id] || field.id;

    if (badge) {
      badge.textContent = label;
      badge.className = badge.className
        .replace('bg-indigo-600/20','bg-indigo-600/40')
        .replace('text-slate-400','text-indigo-300');
      badge.classList.add('text-indigo-300');
    }
    if (fieldBadge) fieldBadge.textContent = label;
  }

  function _saveRange() {
    const sel = window.getSelection();
    if (!sel || sel.rangeCount === 0) return;
    const range = sel.getRangeAt(0);
    const container = range.commonAncestorContainer;
    const inEditor = [s.titleEl, s.excerptEl, s.editorEl].some(f => f?.contains(container));
    if (inEditor) s.savedRange = range.cloneRange();
  }

  function _restoreRange() {
    if (!s.savedRange) return false;
    const sel = window.getSelection();
    sel.removeAllRanges();
    sel.addRange(s.savedRange.cloneRange());
    return true;
  }

  function _hasSelection() {
    return s.savedRange && !s.savedRange.collapsed;
  }

  // ─── Format buttons ───────────────────────────────────────────────────────
  function _setupFormatButtons() {
    document.querySelectorAll('[data-format]').forEach(btn => {
      btn.addEventListener('click', function (e) {
        e.preventDefault();
        _applyFormat(this.dataset.format);
      });
    });

    // Update active states on selection change
    [s.titleEl, s.excerptEl, s.editorEl].filter(Boolean).forEach(f => {
      f.addEventListener('keyup',   _updateFormatStates);
      f.addEventListener('mouseup', _updateFormatStates);
    });
    document.addEventListener('selectionchange', () => {
      const active = document.activeElement;
      if ([s.titleEl, s.excerptEl, s.editorEl].includes(active)) _updateFormatStates();
    });
  }

  function _applyFormat(format) {
    const target = s.activeField || s.editorEl;
    target.focus();

    const cmdMap = {
      bold: 'bold', italic: 'italic', underline: 'underline',
      ul: 'insertUnorderedList', ol: 'insertOrderedList',
      justifyLeft: 'justifyLeft', justifyCenter: 'justifyCenter', justifyRight: 'justifyRight',
      h1: 'formatBlock', h2: 'formatBlock', h3: 'formatBlock', p: 'formatBlock',
    };
    const valMap = { h1: 'h1', h2: 'h2', h3: 'h3', p: 'p' };

    const cmd = cmdMap[format];
    if (!cmd) return;
    document.execCommand(cmd, false, valMap[format] ?? null);

    _updateFormatStates();
    _triggerAutoSave();
    _updateCharCount();
  }

  function _updateFormatStates() {
    const headingTag = _getHeadingAtCursor();

    document.querySelectorAll('[data-format]').forEach(btn => {
      const fmt = btn.dataset.format;
      const stateMap = {
        bold: 'bold', italic: 'italic', underline: 'underline',
        ul: 'insertUnorderedList', ol: 'insertOrderedList',
        justifyLeft: 'justifyLeft', justifyCenter: 'justifyCenter', justifyRight: 'justifyRight',
      };

      if (stateMap[fmt]) {
        try {
          const on = document.queryCommandState(stateMap[fmt]);
          btn.classList.toggle('is-active', on);
        } catch (e) { /* ignore */ }
      } else if (['h1','h2','h3','p'].includes(fmt)) {
        btn.classList.toggle('is-active', headingTag === fmt);
      }
    });
  }

  function _getHeadingAtCursor() {
    const sel = window.getSelection();
    if (!sel || sel.rangeCount === 0) return null;
    let node = sel.getRangeAt(0).startContainer;
    while (node && node !== s.editorEl) {
      if (node.nodeType === 1) {
        const tag = node.tagName.toLowerCase();
        if (['h1','h2','h3','p'].includes(tag)) return tag;
      }
      node = node.parentNode;
    }
    return null;
  }

  // ─── Colour pickers ───────────────────────────────────────────────────────
  function _setupColorPickers() {
    document.getElementById('apply-text-color')?.addEventListener('click', () => {
      _applyColor(document.getElementById('text-color').value, 'color');
    });
    document.getElementById('apply-bg-color')?.addEventListener('click', () => {
      _applyColor(document.getElementById('bg-color').value, 'backgroundColor');
    });
    document.getElementById('remove-text-color')?.addEventListener('click', _clearColor);
    document.getElementById('remove-bg-color')?.addEventListener('click', _clearColor);
  }

  function _applyColor(color, property) {
    if (!_hasSelection()) {
      _toast('⚠️ Select text in the Title, Excerpt or Content first', 'warning');
      return;
    }
    _restoreRange();
    const sel = window.getSelection();
    if (!sel || !sel.rangeCount) return;

    const range = sel.getRangeAt(0);
    const span  = document.createElement('span');

    if (property === 'color') {
      span.style.color = color;
    } else {
      span.style.backgroundColor = color;
      span.style.padding          = '1px 5px';
      span.style.borderRadius     = '3px';
    }

    try {
      span.appendChild(range.extractContents());
      range.insertNode(span);
      // Move cursor after span
      const after = document.createRange();
      after.setStartAfter(span);
      after.collapse(true);
      sel.removeAllRanges();
      sel.addRange(after);
      _triggerAutoSave();
      _toast('✅ Colour applied', 'success');
    } catch (err) {
      console.error('[BlogCreate] colour apply error', err);
    }
  }

  function _clearColor() {
    if (!_hasSelection()) { _toast('⚠️ Select text first', 'warning'); return; }
    _restoreRange();
    const sel = window.getSelection();
    if (!sel || !sel.rangeCount) return;

    const range = sel.getRangeAt(0);
    const frag  = range.extractContents();
    _stripColorStyles(frag);
    range.insertNode(frag);
    sel.removeAllRanges();
    _triggerAutoSave();
    _toast('✅ Colour cleared', 'success');
  }

  function _stripColorStyles(node) {
    if (node.nodeType === 1 && node.style) {
      node.style.color           = '';
      node.style.backgroundColor = '';
      node.style.padding         = '';
      node.style.borderRadius    = '';
    }
    node.childNodes.forEach(_stripColorStyles);
  }

  // ─── Theme swatches ───────────────────────────────────────────────────────
  /**
   * Themes only tint the editor background & text colour.
   * The page chrome stays dark slate (Tailwind classes on the markup).
   * We patch the inline style of the editor div only.
   */
  function _setupThemeSwatches() {
    document.querySelectorAll('.theme-swatch').forEach(btn => {
      btn.addEventListener('click', function () {
        const theme = this.dataset.theme;
        _applyTheme(theme);
        // Active ring: remove from all, add to clicked
        document.querySelectorAll('.theme-swatch').forEach(b => {
          b.classList.remove('ring-2', 'ring-indigo-400', 'ring-offset-2', 'ring-offset-slate-900');
        });
        this.classList.add('ring-2', 'ring-indigo-400', 'ring-offset-2', 'ring-offset-slate-900');
        localStorage.setItem('blog_create_theme', theme);
      });
    });

    // Restore saved theme
    const saved = localStorage.getItem('blog_create_theme') || 'dark';
    document.querySelector(`.theme-swatch[data-theme="${saved}"]`)?.click();
  }

  function _applyTheme(theme) {
    const t = s.themes[theme];
    if (!t) return;
    if (s.editorEl) {
      s.editorEl.style.backgroundColor = t.editor;
      s.editorEl.style.color           = t.editorText;
    }
    _toast(`Theme: ${theme.charAt(0).toUpperCase() + theme.slice(1)}`, 'info');
  }

  // ─── Image upload into content editor ─────────────────────────────────────
  function _setupImageUpload() {
    const btn   = document.getElementById('insert-image');
    const input = document.getElementById('image-upload');

    btn?.addEventListener('click', () => input?.click());
    input?.addEventListener('change', async function () {
      if (this.files[0]) await _uploadImage(this.files[0], true);
      this.value = '';
    });

    // Drag & drop onto the editor
    s.editorEl?.addEventListener('dragover', e => {
      e.preventDefault();
      s.editorEl.classList.add('ring-2', 'ring-indigo-500', 'ring-inset');
    });
    s.editorEl?.addEventListener('dragleave', () => {
      s.editorEl.classList.remove('ring-2', 'ring-indigo-500', 'ring-inset');
    });
    s.editorEl?.addEventListener('drop', async function (e) {
      e.preventDefault();
      s.editorEl.classList.remove('ring-2', 'ring-indigo-500', 'ring-inset');
      for (const file of e.dataTransfer.files) {
        if (file.type.startsWith('image/')) await _uploadImage(file, true);
      }
    });
  }

  async function _uploadImage(file, insertIntoEditor = false) {
    if (!file.type.startsWith('image/'))  { _toast('❌ Not an image file', 'error');  return null; }
    if (file.size > cfg.maxFileSize)       { _toast('❌ File exceeds 5 MB',  'error');  return null; }

    _toast('📤 Uploading…', 'info');

    const fd = new FormData();
    fd.append('image',  file);
    fd.append('_token', cfg.csrfToken);

    try {
      const res  = await fetch(cfg.uploadUrl, { method: 'POST', body: fd });
      const data = await res.json();
      if (!data.success) { _toast('❌ Upload failed', 'error'); return null; }
      if (insertIntoEditor) _insertImageNode(data.url, file.name);
      _toast('✅ Uploaded!', 'success');
      return data.url;
    } catch (err) {
      console.error('[BlogCreate] upload error', err);
      _toast('❌ Network error', 'error');
      return null;
    }
  }

  function _insertImageNode(url, alt) {
    const wrapper = document.createElement('div');
    // Tailwind classes on the wrapper — no inline styles needed
    wrapper.className = 'image-wrapper group relative block my-6';
    wrapper.contentEditable = 'false'; // only this wrapper is locked

    const img = document.createElement('img');
    img.src = url; img.alt = alt;
    img.className = 'max-w-full h-auto rounded-xl border border-slate-700 block';

    // Delete button
    const delBtn = document.createElement('button');
    delBtn.type = 'button';
    delBtn.className = 'absolute top-2 right-2 w-8 h-8 flex items-center justify-center '
                     + 'bg-red-600 hover:bg-red-500 text-white rounded-full '
                     + 'opacity-0 group-hover:opacity-100 transition-opacity shadow-lg z-10 text-sm';
    delBtn.textContent = '✕';
    delBtn.addEventListener('click', e => {
      e.preventDefault(); e.stopPropagation();
      if (confirm('Remove this image?')) {
        wrapper.remove();
        _toast('✅ Image removed', 'success');
        _triggerAutoSave();
        _updateCharCount();
      }
    });

    // Replace button
    const replBtn = document.createElement('button');
    replBtn.type = 'button';
    replBtn.className = 'absolute top-2 left-2 w-8 h-8 flex items-center justify-center '
                      + 'bg-indigo-600 hover:bg-indigo-500 text-white rounded-full '
                      + 'opacity-0 group-hover:opacity-100 transition-opacity shadow-lg z-10 text-sm';
    replBtn.textContent = '🔄';
    replBtn.addEventListener('click', e => {
      e.preventDefault(); e.stopPropagation();
      const tmp = document.createElement('input');
      tmp.type = 'file'; tmp.accept = 'image/*';
      tmp.addEventListener('change', async function () {
        if (!this.files[0]) return;
        const newUrl = await _uploadImage(this.files[0]);
        if (newUrl) { img.src = newUrl; img.alt = this.files[0].name; }
      });
      tmp.click();
    });

    wrapper.appendChild(img);
    wrapper.appendChild(delBtn);
    wrapper.appendChild(replBtn);

    // Insert at cursor position
    const before = document.createElement('p'); before.innerHTML = '<br>';
    const after  = document.createElement('p'); after.innerHTML  = '<br>';
    const sel = window.getSelection();
    if (sel && sel.rangeCount > 0) {
      const r = sel.getRangeAt(0); r.collapse(false);
      r.insertNode(after); r.insertNode(wrapper); r.insertNode(before);
    } else {
      s.editorEl.appendChild(before);
      s.editorEl.appendChild(wrapper);
      s.editorEl.appendChild(after);
    }
    _updateCharCount();
    _triggerAutoSave();
  }

  // ─── Featured image dropzone ───────────────────────────────────────────────
  function _setupFeaturedImageDropzone() {
    const input   = document.getElementById('featured-image-input');
    const zone    = document.getElementById('featured-drop-zone');
    const preview = document.getElementById('featured-preview');
    const preImg  = document.getElementById('featured-preview-img');
    const remove  = document.getElementById('remove-featured');

    function showPreview(file) {
      const reader = new FileReader();
      reader.onload = e => {
        preImg.src = e.target.result;
        preview.classList.remove('hidden');
        zone.classList.add('hidden');
      };
      reader.readAsDataURL(file);
    }

    input?.addEventListener('change', function () {
      if (this.files[0]) showPreview(this.files[0]);
    });

    // Drag & drop on the zone
    zone?.addEventListener('dragover', e => { e.preventDefault(); zone.classList.add('border-indigo-500'); });
    zone?.addEventListener('dragleave', () => zone.classList.remove('border-indigo-500'));
    zone?.addEventListener('drop', function (e) {
      e.preventDefault();
      zone.classList.remove('border-indigo-500');
      const file = e.dataTransfer.files[0];
      if (file?.type.startsWith('image/')) {
        // Put file into the hidden input via DataTransfer
        const dt = new DataTransfer();
        dt.items.add(file);
        input.files = dt.files;
        showPreview(file);
      }
    });

    remove?.addEventListener('click', () => {
      input.value = '';
      preImg.src  = '';
      preview.classList.add('hidden');
      zone.classList.remove('hidden');
    });
  }

  // ─── Link insertion ───────────────────────────────────────────────────────
  function _setupLinkInsertion() {
    document.getElementById('insert-link')?.addEventListener('click', () => {
      if (!_hasSelection()) { _toast('⚠️ Select text first', 'warning'); return; }
      const url = prompt('Enter URL:', 'https://');
      if (!url?.trim()) return;
      _restoreRange();
      document.execCommand('createLink', false, url);
      _toast('✅ Link inserted', 'success');
      _triggerAutoSave();
    });
  }

  // ─── Preview modal ────────────────────────────────────────────────────────
  function _setupPreview() {
    const modal   = document.getElementById('preview-modal');
    const content = document.getElementById('preview-content');

    document.getElementById('preview-btn')?.addEventListener('click', () => {
      if (content) {
        const title   = s.titleEl?.innerHTML   || '<em>No title</em>';
        const excerpt = s.excerptEl?.innerHTML || '';
        const body    = s.editorEl?.innerHTML  || '';
        content.innerHTML = `
          <div class="mb-2 text-3xl font-extrabold text-white" style="line-height:1.2;">${title}</div>
          ${excerpt ? `<div class="mb-5 text-slate-400 italic text-sm leading-relaxed">${excerpt}</div>` : ''}
          <hr class="border-slate-700 mb-6">
          ${body}`;
      }
      if (modal) { modal.style.display = 'flex'; }
    });

    document.getElementById('close-preview')?.addEventListener('click', () => {
      if (modal) modal.style.display = 'none';
    });
    modal?.addEventListener('click', e => {
      if (e.target === modal) modal.style.display = 'none';
    });
    document.addEventListener('keydown', e => {
      if (e.key === 'Escape' && modal?.style.display !== 'none') modal.style.display = 'none';
    });
  }

  // ─── Form submit ──────────────────────────────────────────────────────────
  function _setupFormSubmit() {
    s.form?.addEventListener('submit', function () {
      const titleHtml   = s.titleEl?.innerHTML   || '';
      const excerptHtml = s.excerptEl?.innerHTML || '';
      const contentHtml = s.editorEl?.innerHTML  || '';

      _setHidden('title-hidden',        titleHtml);
      _setHidden('excerpt-hidden',      excerptHtml);
      _setHidden('content-hidden',      contentHtml);
      _setHidden('content-json-hidden', JSON.stringify({
        version:   '1.0',
        title:     titleHtml,
        excerpt:   excerptHtml,
        content:   contentHtml,
        timestamp: new Date().toISOString(),
        editor:    'blog-create',
      }));

      // Clear draft on intentional submit
      localStorage.removeItem(cfg.autosaveKey);
      return true;
    });
  }

  function _setHidden(id, val) {
    const el = document.getElementById(id);
    if (el) el.value = val;
  }

  // ─── Auto-save draft to localStorage ─────────────────────────────────────
  function _setupAutoSave() {
    [s.titleEl, s.excerptEl, s.editorEl].filter(Boolean).forEach(f => {
      f.addEventListener('input', () => { _triggerAutoSave(); _updateCharCount(); });
    });
    s.form?.querySelectorAll('input,select,textarea').forEach(el => {
      el.addEventListener('change', _triggerAutoSave);
    });
  }

  function _triggerAutoSave() {
    clearTimeout(s.autosaveTimer);
    s.autosaveTimer = setTimeout(_saveDraft, cfg.autosaveDelay);
  }

  function _saveDraft() {
    try {
      const draft = {
        title:     s.titleEl?.innerHTML   || '',
        excerpt:   s.excerptEl?.innerHTML || '',
        content:   s.editorEl?.innerHTML  || '',
        timestamp: new Date().toISOString(),
      };
      localStorage.setItem(cfg.autosaveKey, JSON.stringify(draft));
      const statusEl = document.getElementById('autosave-status');
      if (statusEl) {
        statusEl.textContent = `Draft saved ${_timeAgo(draft.timestamp)}`;
        statusEl.className = 'ml-auto text-xs text-emerald-500';
      }
    } catch (e) { console.warn('[BlogCreate] autosave failed', e); }
  }

  function _restoreDraft() {
    try {
      const raw = localStorage.getItem(cfg.autosaveKey);
      if (!raw) return;
      const draft = JSON.parse(raw);
      // Only restore if editor is empty (no old() data from server)
      if (s.titleEl   && !s.titleEl.innerText.trim()   && draft.title)   s.titleEl.innerHTML   = draft.title;
      if (s.excerptEl && !s.excerptEl.innerText.trim() && draft.excerpt) s.excerptEl.innerHTML = draft.excerpt;
      if (s.editorEl  && !s.editorEl.innerText.trim()  && draft.content) s.editorEl.innerHTML  = draft.content;
      if (draft.title || draft.content) {
        _toast(`📋 Draft from ${_timeAgo(draft.timestamp)} restored`, 'info');
        _updateCharCount();
      }
    } catch (e) { /* ignore corrupt draft */ }
  }

  function _timeAgo(iso) {
    const secs = Math.floor((Date.now() - new Date(iso)) / 1000);
    if (secs < 60)   return 'just now';
    if (secs < 3600) return `${Math.floor(secs/60)}m ago`;
    return `${Math.floor(secs/3600)}h ago`;
  }

  // ─── Character / word / image count ──────────────────────────────────────
  function _setupCharCount() {
    _updateCharCount();
    s.editorEl?.addEventListener('input', _updateCharCount);
  }

  function _updateCharCount() {
    const text   = s.editorEl?.innerText || '';
    const chars  = text.length;
    const words  = text.trim() ? text.trim().split(/\s+/).length : 0;
    const images = s.editorEl?.querySelectorAll('img').length || 0;

    const el = id => document.getElementById(id);
    if (el('char-count'))  el('char-count').textContent  = chars.toLocaleString();
    if (el('word-count'))  el('word-count').textContent  = words.toLocaleString();
    if (el('image-count')) el('image-count').textContent = images.toLocaleString();
  }

  // ─── Keyboard shortcuts ───────────────────────────────────────────────────
  function _setupKeyboardShortcuts() {
    document.addEventListener('keydown', e => {
      if ((e.ctrlKey || e.metaKey) && e.key === 's') {
        e.preventDefault();
        _saveDraft();
        _toast('💾 Draft saved', 'success');
      }
      if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
        e.preventDefault();
        document.getElementById('preview-btn')?.click();
      }
    });
  }

  // ─── Toast notifications ──────────────────────────────────────────────────
  /**
   * Uses only Tailwind utility classes — no custom CSS needed.
   */
  function _toast(message, type = 'info') {
    const colours = {
      success: 'bg-emerald-600 text-white',
      error:   'bg-red-600    text-white',
      warning: 'bg-amber-500  text-white',
      info:    'bg-indigo-600 text-white',
    };

    const el = document.createElement('div');
    el.className = [
      'fixed top-4 right-4 z-[9999]',
      'px-5 py-3 rounded-xl shadow-2xl',
      'text-sm font-semibold',
      'transition-all duration-300 translate-y-0 opacity-100',
      colours[type] || colours.info,
    ].join(' ');
    el.textContent = message;
    document.body.appendChild(el);

    // Fade out
    setTimeout(() => {
      el.classList.add('opacity-0', '-translate-y-2');
      setTimeout(() => el.remove(), 350);
    }, 3000);
  }

  // ─── Public API ───────────────────────────────────────────────────────────
  return {
    init,
    toast:      _toast,
    saveDraft:  _saveDraft,
    applyColor: _applyColor,
    getContent: () => s.editorEl?.innerHTML || '',
    getTitle:   () => s.titleEl?.innerHTML  || '',
    getExcerpt: () => s.excerptEl?.innerHTML || '',
  };

})();

window.BlogCreate = BlogCreate;
console.log('[BlogCreate] module loaded');