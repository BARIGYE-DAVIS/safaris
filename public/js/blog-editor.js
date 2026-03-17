/**
 * Blog Editor — Universal Color Edition
 *
 * Key changes vs previous version:
 * - Title and excerpt are now contenteditable divs ("colorable fields")
 * - A single "active field" tracker remembers which of the 3 fields was
 *   last focused so the color / format tools always target the right one
 * - Color apply/clear works identically in all three fields
 * - On form submit: title-hidden, excerpt-hidden and content-hidden are
 *   all synced from their contenteditable counterparts
 * - Title enforces single-line (blocks Enter key)
 */

const BlogEditor = (function () {
    'use strict';

    // ─── Config & State ───────────────────────────────────────────────────────
    let config = {
        blogId: null,
        uploadUrl: '',
        csrfToken: '',
        maxFileSize: 5 * 1024 * 1024,
        autosaveInterval: 5000,
    };

    let state = {
        // The contenteditable div that was last focused (title / excerpt / content)
        activeField: null,
        // Saved selection range inside activeField — preserved so clicking a
        // toolbar button doesn't lose the selection
        savedRange: null,

        editor: null,        // #editor  (content)
        titleEditor: null,   // #title-editor
        excerptEditor: null, // #excerpt-editor

        contentHidden: null,
        titleHidden: null,
        excerptHidden: null,
        contentJsonHidden: null,
        form: null,
        autosaveTimer: null,
        currentTheme: 'dark',
    };

    // ─── Themes ───────────────────────────────────────────────────────────────
    const themes = {
        dark:   { name:'Dark',              bgPrimary:'#0f172a', bgSecondary:'#1e293b', bgCard:'rgba(30,41,59,0.5)',    bgInput:'#0f172a',  bgEditor:'#0a0a0a',  bgToolbar:'#1e293b',  textPrimary:'#f8fafc',  textSecondary:'#cbd5e1', textTertiary:'#94a3b8', textEditor:'#e2e8f0', textToolbar:'#e2e8f0', border:'#334155',  borderToolbar:'#475569', gradient:'linear-gradient(135deg,#0f172a 0%,#1e293b 100%)' },
        light:  { name:'Light',             bgPrimary:'#ffffff', bgSecondary:'#f8fafc', bgCard:'rgba(248,250,252,0.9)', bgInput:'#ffffff',  bgEditor:'#ffffff',  bgToolbar:'#f1f5f9',  textPrimary:'#0f172a',  textSecondary:'#475569', textTertiary:'#64748b', textEditor:'#1e293b', textToolbar:'#334155', border:'#e2e8f0',  borderToolbar:'#cbd5e1', gradient:'linear-gradient(135deg,#ffffff 0%,#f1f5f9 100%)' },
        green:  { name:'Green Forest',      bgPrimary:'#064e3b', bgSecondary:'#065f46', bgCard:'rgba(6,95,70,0.5)',     bgInput:'#064e3b',  bgEditor:'#022c22',  bgToolbar:'#047857',  textPrimary:'#ecfdf5',  textSecondary:'#d1fae5', textTertiary:'#a7f3d0', textEditor:'#d1fae5', textToolbar:'#ecfdf5', border:'#059669',  borderToolbar:'#10b981', gradient:'linear-gradient(135deg,#064e3b 0%,#047857 100%)' },
        yellow: { name:'Sunshine',          bgPrimary:'#78350f', bgSecondary:'#92400e', bgCard:'rgba(146,64,14,0.5)',   bgInput:'#78350f',  bgEditor:'#451a03',  bgToolbar:'#b45309',  textPrimary:'#fef3c7',  textSecondary:'#fde68a', textTertiary:'#fcd34d', textEditor:'#fef3c7', textToolbar:'#fef3c7', border:'#d97706',  borderToolbar:'#f59e0b', gradient:'linear-gradient(135deg,#78350f 0%,#b45309 100%)' },
        blue:   { name:'Ocean Blue',        bgPrimary:'#1e3a8a', bgSecondary:'#1e40af', bgCard:'rgba(30,64,175,0.5)',   bgInput:'#1e3a8a',  bgEditor:'#172554',  bgToolbar:'#2563eb',  textPrimary:'#dbeafe',  textSecondary:'#bfdbfe', textTertiary:'#93c5fd', textEditor:'#dbeafe', textToolbar:'#eff6ff', border:'#3b82f6',  borderToolbar:'#60a5fa', gradient:'linear-gradient(135deg,#1e3a8a 0%,#2563eb 100%)' },
        gray:   { name:'Professional Gray', bgPrimary:'#374151', bgSecondary:'#4b5563', bgCard:'rgba(75,85,99,0.5)',    bgInput:'#374151',  bgEditor:'#1f2937',  bgToolbar:'#6b7280',  textPrimary:'#f9fafb',  textSecondary:'#e5e7eb', textTertiary:'#d1d5db', textEditor:'#f3f4f6', textToolbar:'#f9fafb', border:'#6b7280',  borderToolbar:'#9ca3af', gradient:'linear-gradient(135deg,#374151 0%,#6b7280 100%)' },
        purple: { name:'Purple Dream',      bgPrimary:'#581c87', bgSecondary:'#6b21a8', bgCard:'rgba(107,33,168,0.5)',  bgInput:'#581c87',  bgEditor:'#3b0764',  bgToolbar:'#7c3aed',  textPrimary:'#faf5ff',  textSecondary:'#f3e8ff', textTertiary:'#e9d5ff', textEditor:'#f3e8ff', textToolbar:'#faf5ff', border:'#9333ea',  borderToolbar:'#a855f7', gradient:'linear-gradient(135deg,#581c87 0%,#7c3aed 100%)' },
        pink:   { name:'Pink Blossom',      bgPrimary:'#9f1239', bgSecondary:'#be185d', bgCard:'rgba(190,24,93,0.5)',   bgInput:'#9f1239',  bgEditor:'#831843',  bgToolbar:'#db2777',  textPrimary:'#fce7f3',  textSecondary:'#fbcfe8', textTertiary:'#f9a8d4', textEditor:'#fce7f3', textToolbar:'#fdf2f8', border:'#ec4899',  borderToolbar:'#f472b6', gradient:'linear-gradient(135deg,#9f1239 0%,#db2777 100%)' },
    };

    // ─── Init ─────────────────────────────────────────────────────────────────
    function init(options) {
        Object.assign(config, options);

        state.editor         = document.getElementById('editor');
        state.titleEditor    = document.getElementById('title-editor');
        state.excerptEditor  = document.getElementById('excerpt-editor');
        state.contentHidden  = document.getElementById('content-hidden');
        state.titleHidden    = document.getElementById('title-hidden');
        state.excerptHidden  = document.getElementById('excerpt-hidden');
        state.contentJsonHidden = document.getElementById('content-json-hidden');
        state.form           = document.getElementById('blog-form');

        if (!state.editor) { console.error('Editor not found'); return; }

        injectThemeStyles();
        setupThemeSwitcher();
        setupActiveFieldTracking();
        setupToolbar();
        setupColorPickers();
        setupImageUpload();
        setupImageRemoval();
        setupLinkInsertion();
        setupPreview();
        setupFormSubmit();
        setupAutoSave();
        setupCharacterCount();
        setupDragDrop();
        setupToolbarActiveStates();
        fixListEditability();
        applySavedTheme();

        console.log('✅ Blog Editor (Universal Color) ready');
    }

    // ─── Theme CSS injection ──────────────────────────────────────────────────
    function injectThemeStyles() {
        const s = document.createElement('style');
        s.id = 'theme-dynamic-styles';
        document.head.appendChild(s);
    }

    function updateThemeStyles(theme) {
        const t = themes[theme];
        document.getElementById('theme-dynamic-styles').textContent = `
            #app-theme-container { background: ${t.gradient}; }
            .theme-bg-card       { background-color: ${t.bgCard}; }
            .theme-bg-secondary  { background-color: ${t.bgSecondary}; }
            .theme-bg-input      { background-color: ${t.bgInput}; }
            .theme-bg-editor     { background-color: ${t.bgEditor}; }
            .theme-bg-toolbar    { background-color: ${t.bgToolbar}; }
            .theme-text-primary  { color: ${t.textPrimary}; }
            .theme-text-secondary{ color: ${t.textSecondary}; }
            .theme-text-tertiary { color: ${t.textTertiary}; }
            .theme-text-editor   { color: ${t.textEditor}; }
            .theme-text-toolbar  { color: ${t.textToolbar}; }
            .theme-border        { border-color: ${t.border}; }
            .theme-border-toolbar{ border-color: ${t.borderToolbar}; }

            .toolbar-btn {
                padding:.5rem .75rem;
                background-color:${t.bgCard};
                color:${t.textToolbar};
                border:1px solid ${t.border};
                border-radius:.5rem;
                font-size:.75rem;
                font-weight:600;
                cursor:pointer;
                transition:all .2s;
                white-space:nowrap;
            }
            .toolbar-btn:hover  { background-color:#4f46e5; color:#fff; transform:translateY(-1px); }
            .toolbar-btn.active { background-color:#4f46e5; color:#fff; box-shadow:0 0 0 2px #818cf8; }

            /* ── Colorable fields base ── */
            .colorable-field {
                color: ${t.textPrimary};
                background-color: ${t.bgInput};
                border-color: ${t.border};
            }
            #editor.colorable-field {
                background-color: ${t.bgEditor};
                color: ${t.textEditor};
            }

            /* ── Heading sizes inside the content editor ── */
            #editor h1 { font-size:2.25rem; font-weight:800; line-height:1.2; margin:1.75rem 0 .75rem; color:${t.textPrimary}; border-bottom:2px solid ${t.border}; padding-bottom:.4rem; }
            #editor h2 { font-size:1.65rem; font-weight:700; line-height:1.3; margin:1.5rem  0 .6rem;  color:${t.textPrimary}; }
            #editor h3 { font-size:1.25rem; font-weight:600; line-height:1.4; margin:1.25rem 0 .5rem;  color:${t.textSecondary}; }
            #editor p  { font-size:1rem;    color:${t.textEditor}; margin:.75rem 0; line-height:1.8; }

            /* ── Lists ── */
            #editor ul, #editor ol { padding-left:2rem; margin:.75rem 0; }
            #editor ul { list-style-type:disc; }
            #editor ol { list-style-type:decimal; }
            #editor li { display:list-item; margin:.4rem 0; line-height:1.7; color:${t.textEditor}; }
            #editor ul ul   { list-style-type:circle;      padding-left:1.5rem; margin:.25rem 0; }
            #editor ul ul ul { list-style-type:square; }
            #editor ol ol   { list-style-type:lower-alpha; padding-left:1.5rem; margin:.25rem 0; }

            #editor strong, #editor b { font-weight:700; color:${t.textPrimary}; }
            #editor em, #editor i     { font-style:italic; }
            #editor a                 { color:#60a5fa; text-decoration:underline; }
            #editor blockquote        { border-left:4px solid ${t.border}; padding-left:1rem; margin:1rem 0; color:${t.textTertiary}; font-style:italic; }

            .image-wrapper { position:relative; display:block; margin:1.5rem 0; }
            .image-wrapper img { max-width:100%; height:auto; border-radius:.5rem; border:2px solid ${t.border}; display:block; }
        `;
    }

    // ─── Theme switcher ───────────────────────────────────────────────────────
    function setupThemeSwitcher() {
        document.querySelectorAll('.theme-btn[data-theme]').forEach(btn => {
            btn.addEventListener('click', function () {
                applyTheme(this.dataset.theme);
                document.querySelectorAll('.theme-btn[data-theme] div').forEach(d => d.classList.remove('ring-4','ring-indigo-500','ring-offset-2'));
                this.querySelector('div')?.classList.add('ring-4','ring-indigo-500','ring-offset-2');
            });
        });
    }

    function applyTheme(theme) {
        if (!themes[theme]) return;
        state.currentTheme = theme;
        updateThemeStyles(theme);
        localStorage.setItem('blog_editor_theme', theme);
        showNotification(`Theme: ${themes[theme].name}`, 'success');
    }

    function applySavedTheme() {
        const saved = localStorage.getItem('blog_editor_theme') || 'dark';
        const btn = document.querySelector(`.theme-btn[data-theme="${saved}"]`);
        btn ? btn.click() : applyTheme('dark');
    }

    // ─── Active field tracking ────────────────────────────────────────────────
    /**
     * We track which colorable field the user last touched so that color tools
     * always operate on the correct one — even after the user clicks a toolbar
     * button (which shifts focus away from the field).
     *
     * Strategy:
     *   1. On "focusin" we set state.activeField AND save the current range.
     *   2. On "mousedown" inside any toolbar button we save the range BEFORE
     *      the button steals focus.
     *   3. Color apply functions restore the saved range before inserting spans.
     */
    function setupActiveFieldTracking() {
        const fields = [state.titleEditor, state.excerptEditor, state.editor].filter(Boolean);

        fields.forEach(field => {
            // Track focus
            field.addEventListener('focusin', () => {
                setActiveField(field);
            });

            // Save range on every selection change while inside this field
            field.addEventListener('keyup',   saveCurrentRange);
            field.addEventListener('mouseup', saveCurrentRange);
            field.addEventListener('input',   saveCurrentRange);

            // Title: block Enter to keep it single-line
            if (field === state.titleEditor) {
                field.addEventListener('keydown', e => {
                    if (e.key === 'Enter') { e.preventDefault(); }
                });
            }
        });

        // Save range before toolbar button takes focus
        const toolbar = document.querySelector('.fixed.bottom-0');
        if (toolbar) {
            toolbar.addEventListener('mousedown', () => {
                saveCurrentRange();
            }, true); // capture phase so it fires before button's own mousedown
        }
    }

    function setActiveField(field) {
        if (!field) return;
        // Remove glow from all
        [state.titleEditor, state.excerptEditor, state.editor].forEach(f => f?.classList.remove('color-target'));
        // Highlight the active one
        field.classList.add('color-target');
        state.activeField = field;

        // Update the toolbar indicator label
        const indicator = document.getElementById('toolbar-field-indicator');
        if (indicator) {
            const labels = { 'title-editor':'Title', 'excerpt-editor':'Excerpt', 'editor':'Content' };
            indicator.textContent = labels[field.id] || field.id;
            indicator.style.background = 'rgba(99,102,241,0.3)';
            indicator.style.borderColor = '#6366f1';
            indicator.style.color = '#a5b4fc';
        }
    }

    function saveCurrentRange() {
        const sel = window.getSelection();
        if (sel && sel.rangeCount > 0) {
            const range = sel.getRangeAt(0);
            // Only save if the range is inside a colorable field
            const field = range.commonAncestorContainer;
            if (
                state.titleEditor?.contains(field) ||
                state.excerptEditor?.contains(field) ||
                state.editor?.contains(field)
            ) {
                state.savedRange = range.cloneRange();
            }
        }
    }

    function restoreSavedRange() {
        if (!state.savedRange) return false;
        const sel = window.getSelection();
        sel.removeAllRanges();
        sel.addRange(state.savedRange.cloneRange());
        return true;
    }

    function hasNonEmptySavedRange() {
        return state.savedRange && !state.savedRange.collapsed;
    }

    // ─── Toolbar ──────────────────────────────────────────────────────────────
    function setupToolbar() {
        document.querySelectorAll('[data-format]').forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                applyFormat(this.dataset.format);
            });
        });
    }

    function setupToolbarActiveStates() {
        [state.titleEditor, state.excerptEditor, state.editor].filter(Boolean).forEach(field => {
            field.addEventListener('keyup',   updateActiveStates);
            field.addEventListener('mouseup', updateActiveStates);
        });
        document.addEventListener('selectionchange', () => {
            const active = document.activeElement;
            if ([state.titleEditor, state.excerptEditor, state.editor].includes(active)) {
                updateActiveStates();
            }
        });
    }

    function updateActiveStates() {
        const headingTag = getActiveHeadingTag();
        document.querySelectorAll('[data-format]').forEach(btn => {
            const fmt = btn.dataset.format;
            btn.classList.remove('active');
            const stateMap = { bold:'bold', italic:'italic', underline:'underline', ul:'insertUnorderedList', ol:'insertOrderedList', justifyLeft:'justifyLeft', justifyCenter:'justifyCenter', justifyRight:'justifyRight' };
            if (stateMap[fmt]) {
                try { if (document.queryCommandState(stateMap[fmt])) btn.classList.add('active'); } catch(e){}
            } else if (['h1','h2','h3','p'].includes(fmt)) {
                if (headingTag === fmt) btn.classList.add('active');
            }
        });
    }

    function getActiveHeadingTag() {
        const sel = window.getSelection();
        if (!sel || sel.rangeCount === 0) return null;
        let node = sel.getRangeAt(0).startContainer;
        while (node && node !== state.editor) {
            if (node.nodeType === 1) {
                const tag = node.tagName.toLowerCase();
                if (['h1','h2','h3','p'].includes(tag)) return tag;
            }
            node = node.parentNode;
        }
        return null;
    }

    function applyFormat(format) {
        // Format commands only make sense inside the main content editor
        const target = state.activeField || state.editor;
        target.focus();

        const cmdMap = {
            h1:'formatBlock', h2:'formatBlock', h3:'formatBlock', p:'formatBlock',
            bold:'bold', italic:'italic', underline:'underline',
            ul:'insertUnorderedList', ol:'insertOrderedList',
            justifyLeft:'justifyLeft', justifyCenter:'justifyCenter', justifyRight:'justifyRight',
        };
        const valMap = { h1:'h1', h2:'h2', h3:'h3', p:'p' };

        const cmd = cmdMap[format];
        if (!cmd) return;
        document.execCommand(cmd, false, valMap[format] || null);

        updateActiveStates();
        triggerAutoSave();
        updateCharacterCount();
    }

    // ─── Fix list editability ─────────────────────────────────────────────────
    function fixListEditability() {
        [state.editor, state.titleEditor, state.excerptEditor].filter(Boolean).forEach(field => {
            field.querySelectorAll('*').forEach(el => {
                if (el.getAttribute('contenteditable') === 'false' && !el.classList.contains('image-wrapper')) {
                    el.removeAttribute('contenteditable');
                }
                if (['LI','UL','OL'].includes(el.tagName)) {
                    el.style.removeProperty('pointer-events');
                    el.style.removeProperty('user-select');
                    el.style.removeProperty('-webkit-user-select');
                    if (el.getAttribute('contenteditable') === 'false') el.removeAttribute('contenteditable');
                }
            });
        });
    }

    // ─── Color pickers ────────────────────────────────────────────────────────
    function setupColorPickers() {
        document.getElementById('apply-text-color')?.addEventListener('click', () => {
            applyColor(document.getElementById('text-color').value, 'color');
        });
        document.getElementById('apply-bg-color')?.addEventListener('click', () => {
            applyColor(document.getElementById('bg-color').value, 'backgroundColor');
        });
        document.getElementById('remove-text-color')?.addEventListener('click', () => {
            clearColor();
        });
        document.getElementById('remove-bg-color')?.addEventListener('click', () => {
            clearColor();
        });
    }

    /**
     * Apply a text or background color to the saved selection in the active field.
     *
     * We restore the saved range (captured before the toolbar button stole focus),
     * wrap the selected content in a <span> with the chosen style, then clean up.
     */
    function applyColor(color, property) {
        if (!hasNonEmptySavedRange()) {
            showNotification('⚠️ Select some text in Title, Excerpt or Content first', 'warning');
            return;
        }

        // Restore the selection so execCommand / range operations work
        restoreSavedRange();

        const sel = window.getSelection();
        if (!sel || sel.rangeCount === 0) return;
        const range = sel.getRangeAt(0);

        const span = document.createElement('span');
        if (property === 'color') {
            span.style.color = color;
        } else {
            span.style.backgroundColor = color;
            span.style.padding = '1px 5px';
            span.style.borderRadius = '3px';
        }

        try {
            span.appendChild(range.extractContents());
            range.insertNode(span);
            sel.removeAllRanges();
            // Place cursor after the span
            const after = document.createRange();
            after.setStartAfter(span);
            after.collapse(true);
            sel.addRange(after);
        } catch(e) {
            console.error('Color apply error:', e);
        }

        triggerAutoSave();
        showNotification('✅ Color applied!', 'success');
    }

    /**
     * Strip color spans from the saved selection.
     * We replace the range contents with plain text.
     */
    function clearColor() {
        if (!hasNonEmptySavedRange()) {
            showNotification('⚠️ Select some text first', 'warning');
            return;
        }
        restoreSavedRange();
        const sel = window.getSelection();
        if (!sel || sel.rangeCount === 0) return;
        const range = sel.getRangeAt(0);

        // Extract fragment, strip inline color styles, reinsert
        const fragment = range.extractContents();
        stripColorStyles(fragment);
        range.insertNode(fragment);
        sel.removeAllRanges();

        triggerAutoSave();
        showNotification('✅ Color cleared!', 'success');
    }

    function stripColorStyles(node) {
        if (node.nodeType === 1) {
            node.style && (node.style.color = '');
            node.style && (node.style.backgroundColor = '');
            node.style && (node.style.padding = '');
            node.style && (node.style.borderRadius = '');
            // Unwrap empty spans
            if (node.tagName === 'SPAN' && node.getAttribute('style') === '') {
                node.removeAttribute('style');
            }
        }
        node.childNodes.forEach(stripColorStyles);
    }

    // ─── Image upload ─────────────────────────────────────────────────────────
    function setupImageUpload() {
        const btn   = document.getElementById('insert-image');
        const input = document.getElementById('image-upload');
        btn?.addEventListener('click', () => input?.click());
        input?.addEventListener('change', async function () {
            if (this.files[0]) await handleImageUpload(this.files[0]);
            this.value = '';
        });
    }

    async function handleImageUpload(file) {
        if (!file.type.startsWith('image/'))    { showNotification('❌ Not an image', 'error'); return; }
        if (file.size > config.maxFileSize)      { showNotification('❌ File >5MB', 'error'); return; }
        showNotification('📤 Uploading…', 'info');

        const fd = new FormData();
        fd.append('image', file);
        fd.append('_token', config.csrfToken);
        try {
            const res  = await fetch(config.uploadUrl, { method:'POST', body:fd });
            const data = await res.json();
            if (data.success) { insertImageIntoEditor(data.url, file.name); showNotification('✅ Uploaded!', 'success'); }
            else              { showNotification('❌ Upload failed', 'error'); }
        } catch (e) { showNotification('❌ Upload error', 'error'); }
    }

    function insertImageIntoEditor(url, alt) {
        const wrapper = document.createElement('div');
        wrapper.className = 'image-wrapper group';
        wrapper.contentEditable = 'false';

        const img = document.createElement('img');
        img.src = url; img.alt = alt;

        const delBtn = makeImgBtn('right', 'bg-red-600 hover:bg-red-700', '🗑');
        delBtn.addEventListener('click', e => {
            e.preventDefault(); e.stopPropagation();
            if (confirm('Delete this image?')) { wrapper.remove(); showNotification('✅ Removed','success'); triggerAutoSave(); }
        });

        const replBtn = makeImgBtn('left', 'bg-indigo-600 hover:bg-indigo-700', '🔄');
        replBtn.addEventListener('click', e => {
            e.preventDefault(); e.stopPropagation();
            const tmp = document.createElement('input'); tmp.type='file'; tmp.accept='image/*';
            tmp.addEventListener('change', async function () {
                if (!this.files[0]) return;
                const fd = new FormData(); fd.append('image', this.files[0]); fd.append('_token', config.csrfToken);
                try {
                    showNotification('📤 Replacing…','info');
                    const res = await fetch(config.uploadUrl,{method:'POST',body:fd});
                    const d   = await res.json();
                    if (d.success) { img.src=d.url; img.alt=this.files[0].name; showNotification('✅ Replaced!','success'); triggerAutoSave(); }
                    else showNotification('❌ Replace failed','error');
                } catch(err){ showNotification('❌ Error','error'); }
            });
            tmp.click();
        });

        wrapper.appendChild(img); wrapper.appendChild(delBtn); wrapper.appendChild(replBtn);

        // Insert at cursor or append
        const beforeP = document.createElement('p'); beforeP.innerHTML = '<br>';
        const afterP  = document.createElement('p'); afterP.innerHTML  = '<br>';
        const sel = window.getSelection();
        if (sel && sel.rangeCount > 0) {
            const r = sel.getRangeAt(0); r.collapse(false);
            r.insertNode(afterP); r.insertNode(wrapper); r.insertNode(beforeP);
        } else {
            state.editor.appendChild(beforeP); state.editor.appendChild(wrapper); state.editor.appendChild(afterP);
        }
        triggerAutoSave(); updateCharacterCount();
    }

    function makeImgBtn(side, cls, label) {
        const b = document.createElement('button');
        b.type = 'button';
        b.className = `absolute top-2 ${side==='right'?'right-2':'left-2'} ${cls} text-white rounded-full w-8 h-8 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity shadow-lg z-10 text-base`;
        b.textContent = label;
        return b;
    }

    function setupImageRemoval() {
        state.editor?.querySelectorAll('img').forEach(img => {
            if (img.closest('.image-wrapper')) return;
            const wrapper = document.createElement('div');
            wrapper.className = 'image-wrapper group';
            wrapper.contentEditable = 'false';
            img.parentNode.insertBefore(wrapper, img);
            wrapper.appendChild(img);
            const del = makeImgBtn('right','bg-red-600 hover:bg-red-700','🗑');
            del.addEventListener('click', e => {
                e.preventDefault(); e.stopPropagation();
                if (confirm('Delete?')) { wrapper.remove(); showNotification('✅ Removed','success'); triggerAutoSave(); }
            });
            wrapper.appendChild(del);
        });
        fixListEditability();
    }

    // ─── Link insertion ───────────────────────────────────────────────────────
    function setupLinkInsertion() {
        document.getElementById('insert-link')?.addEventListener('click', () => {
            if (!hasNonEmptySavedRange()) { showNotification('⚠️ Select text first','warning'); return; }
            const url = prompt('Enter URL:', 'https://');
            if (!url?.trim()) return;
            restoreSavedRange();
            document.execCommand('createLink', false, url);
            showNotification('✅ Link inserted!','success');
            triggerAutoSave();
        });
    }

    // ─── Drag & drop ──────────────────────────────────────────────────────────
    function setupDragDrop() {
        state.editor.addEventListener('dragover', e => { e.preventDefault(); state.editor.style.outline='4px dashed #4f46e5'; });
        state.editor.addEventListener('dragleave', () => state.editor.style.outline='none');
        state.editor.addEventListener('drop', async function (e) {
            e.preventDefault(); state.editor.style.outline='none';
            for (const f of e.dataTransfer.files) if (f.type.startsWith('image/')) await handleImageUpload(f);
        });
    }

    // ─── Preview ──────────────────────────────────────────────────────────────
    function setupPreview() {
        const modal   = document.getElementById('preview-modal');
        const content = document.getElementById('preview-content');

        document.getElementById('preview-btn')?.addEventListener('click', () => {
            if (content) {
                // Show title + excerpt + content together
                const titleHtml   = state.titleEditor?.innerHTML   || '';
                const excerptHtml = state.excerptEditor?.innerHTML || '';
                const bodyHtml    = state.editor?.innerHTML        || '';
                content.innerHTML = `
                    <div class="mb-6">
                        <div style="font-size:2rem;font-weight:800;line-height:1.2;margin-bottom:.5rem;">${titleHtml}</div>
                        <div style="font-size:1rem;opacity:.7;margin-bottom:1.5rem;">${excerptHtml}</div>
                        <hr style="border-color:rgba(255,255,255,.1);margin-bottom:1.5rem;">
                        ${bodyHtml}
                    </div>`;
            }
            modal?.classList.remove('hidden');
        });

        document.getElementById('close-preview')?.addEventListener('click', () => modal?.classList.add('hidden'));
        modal?.addEventListener('click', e => { if (e.target === modal) modal.classList.add('hidden'); });
    }

    // ─── Form submit ──────────────────────────────────────────────────────────
    function setupFormSubmit() {
        state.form?.addEventListener('submit', function () {
            // Sync all three contenteditable fields to their hidden inputs
            if (state.titleHidden)    state.titleHidden.value   = state.titleEditor?.innerHTML   || '';
            if (state.excerptHidden)  state.excerptHidden.value = state.excerptEditor?.innerHTML || '';
            const html = state.editor?.innerHTML || '';
            if (state.contentHidden)  state.contentHidden.value = html;
            if (state.contentJsonHidden) {
                state.contentJsonHidden.value = JSON.stringify({
                    version:'4.0', content:html,
                    title:state.titleEditor?.innerHTML || '',
                    excerpt:state.excerptEditor?.innerHTML || '',
                    timestamp: new Date().toISOString(),
                    theme: state.currentTheme,
                    editor:'blog-editor-universal-color',
                });
            }
            if (config.blogId) localStorage.removeItem('blog_autosave_' + config.blogId);
            return true;
        });
    }

    // ─── Auto-save ────────────────────────────────────────────────────────────
    function setupAutoSave() {
        [state.editor, state.titleEditor, state.excerptEditor].filter(Boolean).forEach(f => {
            f.addEventListener('input', () => { triggerAutoSave(); updateCharacterCount(); });
        });
        state.form?.querySelectorAll('input,select,textarea').forEach(el => el.addEventListener('change', triggerAutoSave));
    }

    function triggerAutoSave() {
        clearTimeout(state.autosaveTimer);
        state.autosaveTimer = setTimeout(saveToLocalStorage, config.autosaveInterval);
    }

    function saveToLocalStorage() {
        if (!config.blogId) return;
        try {
            localStorage.setItem('blog_autosave_' + config.blogId, JSON.stringify({
                title:   state.titleEditor?.innerHTML   || '',
                excerpt: state.excerptEditor?.innerHTML || '',
                content: state.editor?.innerHTML        || '',
                theme:   state.currentTheme,
                timestamp: new Date().toISOString(),
            }));
        } catch(e) { console.error('Auto-save failed:', e); }
    }

    // ─── Character count ──────────────────────────────────────────────────────
    function setupCharacterCount() {
        updateCharacterCount();
        state.editor?.addEventListener('input', updateCharacterCount);
    }

    function updateCharacterCount() {
        const text   = state.editor?.innerText || '';
        const chars  = text.length;
        const words  = text.trim().split(/\s+/).filter(w => w).length;
        const images = state.editor?.querySelectorAll('img').length || 0;
        const el = id => document.getElementById(id);
        if (el('char-count'))  el('char-count').textContent  = chars.toLocaleString();
        if (el('word-count'))  el('word-count').textContent  = words.toLocaleString();
        if (el('image-count')) el('image-count').textContent = images.toLocaleString();
    }

    // ─── Notifications ────────────────────────────────────────────────────────
    function showNotification(msg, type = 'info') {
        const colors = { success:'bg-green-500', error:'bg-red-500', warning:'bg-amber-500', info:'bg-indigo-500' };
        const el = document.createElement('div');
        el.className = `fixed top-4 right-4 px-5 py-3 rounded-lg shadow-lg z-[9999] text-white font-medium transition-all duration-300 ${colors[type]||colors.info}`;
        el.textContent = msg;
        document.body.appendChild(el);
        setTimeout(() => { el.style.opacity='0'; setTimeout(() => el.remove(), 300); }, 3000);
    }

    // ─── Keyboard shortcuts ───────────────────────────────────────────────────
    document.addEventListener('keydown', function (e) {
        if ((e.ctrlKey||e.metaKey) && e.key==='s') {
            e.preventDefault(); saveToLocalStorage(); showNotification('💾 Saved!','success');
        }
        if ((e.ctrlKey||e.metaKey) && e.key==='p') {
            e.preventDefault(); document.getElementById('preview-btn')?.click();
        }
        if (e.key==='Escape') {
            const m = document.getElementById('preview-modal');
            if (m && !m.classList.contains('hidden')) document.getElementById('close-preview')?.click();
        }
    });

    // ─── Public API ───────────────────────────────────────────────────────────
    return {
        init,
        applyFormat,
        applyTheme,
        insertImage: insertImageIntoEditor,
        showNotification,
        saveNow: saveToLocalStorage,
        getContent:    () => state.editor?.innerHTML || '',
        getTitleHtml:  () => state.titleEditor?.innerHTML || '',
        getExcerptHtml:() => state.excerptEditor?.innerHTML || '',
        setContent: (html) => { if (state.editor) { state.editor.innerHTML = html; fixListEditability(); } },
    };
})();

window.BlogEditor = BlogEditor;
console.log('✅ Blog Editor (Universal Color) loaded');