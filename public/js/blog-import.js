/**
 * blog-import.js
 * ─────────────────────────────────────────────────────────────────────────────
 * Handles document import (PDF + DOCX) into the blog create / edit form.
 *
 * ► UPLOAD:  PDF or DOCX → auto-fills Title, Excerpt, Content as if typed.
 *   Structure-aware: headings, bullet lists, numbered lists, bold text
 *   are all detected and rendered as proper HTML in the editor.
 *
 * ► DOWNLOAD TEMPLATES:  PDF template  |  Word (DOCX) template — both in-browser.
 * ► PLACEMENT:  Card is injected above the Title editor (top of left column).
 *
 * ► ZERO npm / composer dependencies to install.
 *   Reading PDF   → PDF.js loaded from CDN on first use only.
 *   Reading DOCX  → pure browser APIs (ZIP + DOMParser).
 *   Writing PDF   → jsPDF loaded from CDN on first use only.
 *   Writing DOCX  → pure browser ZIP builder (built-in, no library).
 *
 * ► Your Laravel controller is completely untouched.
 *
 * Usage — copy to public/js/ then add ONE line after blog-create.js:
 *   <script src="{{ asset('js/blog-import.js') }}"></script>
 * ─────────────────────────────────────────────────────────────────────────────
 */

(function () {
    'use strict';

    /* ══════════════════════════════════════════════════════════════════════
       1.  CONFIG
    ══════════════════════════════════════════════════════════════════════ */
    var CFG = {
        titleEditor   : '#title-editor',
        excerptEditor : '#excerpt-editor',
        contentEditor : '#editor',

        /* CDN libs loaded on-demand — never installed */
        pdfReadCDN    : 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js',
        pdfWorkerCDN  : 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js',
        jsPdfCDN      : 'https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js',

        maxFileMB     : 20,
        excerptWords  : 60,
    };

    /* ══════════════════════════════════════════════════════════════════════
       2.  HELPERS
    ══════════════════════════════════════════════════════════════════════ */
    function qs(sel, root) { return (root || document).querySelector(sel); }

    function esc(str) {
        return String(str)
            .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    }

    /* ══════════════════════════════════════════════════════════════════════
       3.  STRUCTURE-AWARE TEXT → HTML CONVERTER
       ──────────────────────────────────────────────────────────────────
       Detects and converts:
         • H1 — ALL CAPS lines, very short (≤12 words), standalone
         • H2 — Title Case lines ending without punctuation, short (≤10 words)
         • H3 — Lines starting with a digit+dot (e.g. "3. Section") OR
                 lines that look like sub-headers (short, no trailing punct)
         • Bullet lists  — lines starting with •, -, *, –, —
         • Numbered lists — lines starting with digit. or digit)
         • Bold inline    — **text** or lines that are short+all-caps inline
         • Paragraphs     — everything else, blank lines = paragraph break
    ══════════════════════════════════════════════════════════════════════ */

    /**
     * Apply inline formatting within a plain-text line:
     *   **bold**, *italic*, `code`
     */
    function inlineFormat(text) {
        var s = esc(text);
        // **bold**
        s = s.replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>');
        // *italic* (not preceded/followed by *)
        s = s.replace(/(?<!\*)\*(?!\*)(.+?)(?<!\*)\*(?!\*)/g, '<em>$1</em>');
        // `code`
        s = s.replace(/`(.+?)`/g, '<code>$1</code>');
        return s;
    }

    /**
     * Heading detection helpers
     */
    function wordCount(line) {
        return line.trim().split(/\s+/).filter(Boolean).length;
    }
    function isAllCaps(line) {
        return line === line.toUpperCase() && /[A-Z]/.test(line);
    }
    function isTitleCase(line) {
        // Most words start with uppercase
        var words = line.split(/\s+/).filter(Boolean);
        if (words.length < 2) return false;
        var upper = words.filter(function(w) { return /^[A-Z]/.test(w); }).length;
        return upper / words.length >= 0.6;
    }
    function endsWithPunct(line) {
        return /[.!?;:,]$/.test(line.trim());
    }
    function startsNumbered(line) {
        return /^\d+[\.\)]\s+/.test(line.trim());
    }
    function isBulletLine(line) {
        return /^[\u2022\u2013\u2014\-\*]\s+/.test(line.trim());
    }

    /**
     * Classify a single line into a type:
     * 'h1' | 'h2' | 'h3' | 'bullet' | 'numbered' | 'blank' | 'text'
     */
    function classifyLine(line) {
        var t = line.trim();
        if (!t) return 'blank';
        if (isBulletLine(t)) return 'bullet';
        if (startsNumbered(t)) return 'numbered';

        var wc = wordCount(t);

        // H1: ALL CAPS, short, no trailing punctuation
        if (isAllCaps(t) && wc <= 12 && !endsWithPunct(t)) return 'h1';

        // Numbered heading like "Part 1 — Title" or "1. Title"
        if (/^(Part|Section|Chapter)\s+\d+/i.test(t) && wc <= 10) return 'h2';

        // H2: Title Case, short, no trailing punctuation
        if (isTitleCase(t) && wc <= 10 && !endsWithPunct(t)) return 'h2';

        // H3: short line (≤8 words), starts with digit+dot, or ends with colon
        if (startsNumbered(t) && wc <= 10 && !endsWithPunct(t)) return 'h3';
        if (t.endsWith(':') && wc <= 10) return 'h3';

        return 'text';
    }

    /**
     * Strip bullet/number prefix from a line and return the content
     */
    function stripListPrefix(line) {
        return line.trim()
            .replace(/^[\u2022\u2013\u2014\-\*]\s+/, '')   // bullet
            .replace(/^\d+[\.\)]\s+/, '');                   // numbered
    }

    /**
     * Main converter: plain text → structured HTML
     */
    function textToStructuredHtml(text) {
        var lines = text.split('\n');
        var html  = '';
        var i     = 0;

        while (i < lines.length) {
            var line = lines[i];
            var type = classifyLine(line);

            if (type === 'blank') {
                i++;
                continue;
            }

            // ── Bullet list ──────────────────────────────────────────────
            if (type === 'bullet') {
                html += '<ul>';
                while (i < lines.length && (classifyLine(lines[i]) === 'bullet' || (lines[i].trim() && classifyLine(lines[i]) === 'text' && lines[i].match(/^\s+/)))) {
                    if (classifyLine(lines[i]) === 'bullet') {
                        html += '<li>' + inlineFormat(stripListPrefix(lines[i])) + '</li>';
                    }
                    i++;
                }
                html += '</ul>';
                continue;
            }

            // ── Numbered list ────────────────────────────────────────────
            if (type === 'numbered') {
                html += '<ol>';
                while (i < lines.length && classifyLine(lines[i]) === 'numbered') {
                    html += '<li>' + inlineFormat(stripListPrefix(lines[i])) + '</li>';
                    i++;
                }
                html += '</ol>';
                continue;
            }

            // ── Headings ─────────────────────────────────────────────────
            if (type === 'h1') {
                html += '<h1>' + inlineFormat(line.trim()) + '</h1>';
                i++;
                continue;
            }
            if (type === 'h2') {
                html += '<h2>' + inlineFormat(line.trim()) + '</h2>';
                i++;
                continue;
            }
            if (type === 'h3') {
                html += '<h3>' + inlineFormat(line.trim()) + '</h3>';
                i++;
                continue;
            }

            // ── Regular paragraph ────────────────────────────────────────
            // Collect consecutive non-blank, non-heading, non-list lines
            // (wrapping them together into one <p> if they follow immediately)
            var para = '';
            while (i < lines.length) {
                var lt = classifyLine(lines[i]);
                if (lt === 'blank' || lt === 'bullet' || lt === 'numbered' ||
                    lt === 'h1' || lt === 'h2' || lt === 'h3') break;
                para += (para ? ' ' : '') + lines[i].trim();
                i++;
                // Stop collecting if the next line is blank (paragraph break)
                if (i < lines.length && classifyLine(lines[i]) === 'blank') break;
            }
            if (para) {
                html += '<p>' + inlineFormat(para) + '</p>';
            }
        }

        return html;
    }

    /* ══════════════════════════════════════════════════════════════════════
       4.  EXCERPT EXTRACTOR
       Pull the first meaningful paragraph (not a heading) for the excerpt
    ══════════════════════════════════════════════════════════════════════ */
    function extractExcerpt(text) {
        var lines = text.split('\n');
        for (var i = 0; i < lines.length; i++) {
            var t    = lines[i].trim();
            var type = classifyLine(t);
            if (type === 'text' && t.length > 40) {
                var words = t.split(/\s+/).filter(Boolean);
                return words.slice(0, CFG.excerptWords).join(' ') +
                       (words.length > CFG.excerptWords ? '\u2026' : '');
            }
        }
        // Fallback: first 60 words of whole text
        var words = text.split(/\s+/).filter(Boolean);
        return words.slice(0, CFG.excerptWords).join(' ') +
               (words.length > CFG.excerptWords ? '\u2026' : '');
    }

    /* ══════════════════════════════════════════════════════════════════════
       5.  TITLE GUESSER
       First H1 / H2 line wins; otherwise first non-blank line
    ══════════════════════════════════════════════════════════════════════ */
    function guessTitle(text) {
        var lines = text.split('\n');
        for (var i = 0; i < lines.length; i++) {
            var t    = lines[i].trim();
            var type = classifyLine(t);
            if ((type === 'h1' || type === 'h2') && t.length > 3) {
                return t.slice(0, 200);
            }
        }
        // Fallback: first non-blank line
        for (var j = 0; j < lines.length; j++) {
            if (lines[j].trim()) return lines[j].trim().slice(0, 200);
        }
        return '';
    }

    /* ══════════════════════════════════════════════════════════════════════
       6.  POPULATE ALL FIELDS
    ══════════════════════════════════════════════════════════════════════ */
    /* Fill a contenteditable and fire the events existing listeners expect */
    function fillEditable(el, html) {
        if (!el) return;
        el.focus();
        el.innerHTML = html;
        ['input', 'keyup', 'change'].forEach(function (name) {
            el.dispatchEvent(new Event(name, { bubbles: true }));
        });
    }

    function populateFields(plainText) {
        var title   = guessTitle(plainText);
        var excerpt = extractExcerpt(plainText);

        // Remove title line from body before converting
        var bodyText = plainText;
        if (title) {
            bodyText = plainText.replace(title, '').trim();
        }

        var bodyHtml = textToStructuredHtml(bodyText);

        fillEditable(qs(CFG.titleEditor),   esc(title));
        fillEditable(qs(CFG.excerptEditor), esc(excerpt));
        fillEditable(qs(CFG.contentEditor), bodyHtml);

        showStatus('&#9989; Document imported &mdash; title, excerpt &amp; content filled with structure preserved.', 'success');
    }

    /* ══════════════════════════════════════════════════════════════════════
       7.  LAZY SCRIPT LOADER
    ══════════════════════════════════════════════════════════════════════ */
    var _loaded = {};
    function loadScript(url) {
        if (_loaded[url]) return _loaded[url];
        _loaded[url] = new Promise(function (resolve, reject) {
            var s    = document.createElement('script');
            s.src    = url;
            s.onload = resolve;
            s.onerror = function () { reject(new Error('Failed to load: ' + url)); };
            document.head.appendChild(s);
        });
        return _loaded[url];
    }

    /* ══════════════════════════════════════════════════════════════════════
       8.  EXTRACT TEXT FROM PDF  (PDF.js via CDN)
       Enhanced: preserves line breaks using y-position tracking so
       headings and list items land on their own lines.
    ══════════════════════════════════════════════════════════════════════ */
    async function extractPdf(arrayBuffer) {
        await loadScript(CFG.pdfReadCDN);
        window.pdfjsLib.GlobalWorkerOptions.workerSrc = CFG.pdfWorkerCDN;

        var pdf  = await window.pdfjsLib.getDocument({ data: arrayBuffer }).promise;
        var text = '';

        for (var i = 1; i <= pdf.numPages; i++) {
            var page    = await pdf.getPage(i);
            var tc      = await page.getTextContent();
            var items   = tc.items;
            var lastY   = null;
            var line    = '';
            var pageText = '';

            items.forEach(function (item) {
                var y = item.transform ? Math.round(item.transform[5]) : null;

                if (lastY !== null && y !== null && Math.abs(y - lastY) > 2) {
                    // New line detected (y-position changed)
                    if (line.trim()) pageText += line.trim() + '\n';
                    line = '';
                }

                line  += item.str;
                lastY  = y;
            });

            if (line.trim()) pageText += line.trim() + '\n';
            text += pageText + '\n';
        }

        return text.trim();
    }

    /* ══════════════════════════════════════════════════════════════════════
       9.  EXTRACT TEXT FROM DOCX  (pure browser APIs)
       Enhanced: reads heading styles (w:pStyle) and list types (w:numPr)
       from document.xml, converting them directly to markdown-like tokens
       that our textToStructuredHtml() understands.
    ══════════════════════════════════════════════════════════════════════ */
    async function extractDocx(arrayBuffer) {
        var bytes  = new Uint8Array(arrayBuffer);
        var xmlStr = await unzipEntry(bytes, 'word/document.xml');
        if (!xmlStr) throw new Error('Cannot find document.xml inside the DOCX.');

        var doc   = new DOMParser().parseFromString(xmlStr, 'application/xml');
        var paras = Array.from(doc.getElementsByTagNameNS('*', 'p'));
        var lines = [];

        paras.forEach(function (p) {
            // Get paragraph style
            var pStyleEl = p.querySelector
                ? p.querySelector('[*|val]')
                : null;

            // Try to find w:pStyle value
            var styleVal = '';
            var pPr = p.getElementsByTagNameNS('*', 'pPr')[0];
            if (pPr) {
                var pStyle = pPr.getElementsByTagNameNS('*', 'pStyle')[0];
                if (pStyle) styleVal = (pStyle.getAttribute('w:val') || '').toLowerCase();
            }

            // Check for list (numPr means it's a list item)
            var isList   = pPr && pPr.getElementsByTagNameNS('*', 'numPr').length > 0;

            // Check for bold runs (all runs bold = heading-like)
            var runs  = Array.from(p.getElementsByTagNameNS('*', 'r'));
            var allBold = runs.length > 0 && runs.every(function (r) {
                return r.getElementsByTagNameNS('*', 'b').length > 0;
            });

            // Collect text
            var lineText = runs.map(function (r) {
                return Array.from(r.getElementsByTagNameNS('*', 't'))
                    .map(function (t) { return t.textContent; }).join('');
            }).join('').trim();

            if (!lineText) return;

            // Apply prefix based on style
            if (/^heading1/.test(styleVal) || styleVal === 'title') {
                lines.push(lineText.toUpperCase()); // Will be detected as H1
            } else if (/^heading2/.test(styleVal)) {
                lines.push(lineText); // Title-case detection handles H2
            } else if (/^heading3/.test(styleVal)) {
                lines.push(lineText + ':'); // Colon suffix → H3
            } else if (isList) {
                lines.push('• ' + lineText); // Bullet prefix → list
            } else {
                lines.push(lineText);
            }
        });

        return lines.join('\n');
    }

    /* Minimal ZIP reader — finds one named entry and decompresses it */
    async function unzipEntry(bytes, target) {
        var i = 0;
        while (i < bytes.length - 30) {
            if (bytes[i] !== 0x50 || bytes[i+1] !== 0x4B ||
                bytes[i+2] !== 0x03 || bytes[i+3] !== 0x04) { i++; continue; }

            var method     = bytes[i+8]  | (bytes[i+9]  << 8);
            var compressed = (bytes[i+18] | (bytes[i+19]<<8) |
                              (bytes[i+20]<<16) | (bytes[i+21]<<24)) >>> 0;
            var nameLen    = bytes[i+26] | (bytes[i+27] << 8);
            var extraLen   = bytes[i+28] | (bytes[i+29] << 8);
            var name       = new TextDecoder().decode(bytes.slice(i+30, i+30+nameLen));
            var dataStart  = i + 30 + nameLen + extraLen;
            var raw        = bytes.slice(dataStart, dataStart + compressed);

            if (name === target) {
                if (method === 0) return new TextDecoder().decode(raw);
                if (method === 8) {
                    var ds     = new DecompressionStream('deflate-raw');
                    var writer = ds.writable.getWriter();
                    writer.write(raw); writer.close();
                    var reader = ds.readable.getReader();
                    var chunks = [];
                    while (true) {
                        var r = await reader.read();
                        if (r.done) break;
                        chunks.push(r.value);
                    }
                    var total = chunks.reduce(function (s, c) { return s + c.length; }, 0);
                    var out   = new Uint8Array(total);
                    var off   = 0;
                    chunks.forEach(function (c) { out.set(c, off); off += c.length; });
                    return new TextDecoder().decode(out);
                }
                throw new Error('Unsupported ZIP compression method: ' + method);
            }
            i = dataStart + compressed;
        }
        return null;
    }

    /* ══════════════════════════════════════════════════════════════════════
       10.  FILE HANDLER
    ══════════════════════════════════════════════════════════════════════ */
    async function handleFile(file) {
        if (!file) return;

        if (file.size > CFG.maxFileMB * 1024 * 1024) {
            showStatus('&#10060; File too large &mdash; max ' + CFG.maxFileMB + ' MB.', 'error');
            return;
        }

        var ext = file.name.split('.').pop().toLowerCase();
        if (!['pdf', 'doc', 'docx'].includes(ext)) {
            showStatus('&#10060; Unsupported type &mdash; please upload a PDF or DOCX file.', 'error');
            return;
        }

        showStatus('&#9203; Reading document&hellip;', 'loading');

        try {
            var buffer = await file.arrayBuffer();
            var text   = (ext === 'pdf') ? await extractPdf(buffer) : await extractDocx(buffer);

            if (!text || text.trim().length < 5) {
                showStatus('&#9888;&#65039; Document appears empty or unreadable. Try copy-pasting instead.', 'error');
                return;
            }
            populateFields(text);
        } catch (err) {
            console.error('[blog-import]', err);
            showStatus('&#10060; Error: ' + esc(err.message), 'error');
        }
    }

    /* ══════════════════════════════════════════════════════════════════════
       11.  DOWNLOAD — PDF TEMPLATE  (jsPDF via CDN, no install)
    ══════════════════════════════════════════════════════════════════════ */
    async function downloadPdfTemplate() {
        showStatus('&#9203; Generating PDF template&hellip;', 'loading');
        try {
            await loadScript(CFG.jsPdfCDN);
            var jsPDF  = window.jspdf.jsPDF;
            var doc    = new jsPDF({ unit: 'mm', format: 'a4' });
            var margin = 20;
            var pageW  = doc.internal.pageSize.getWidth();
            var cw     = pageW - margin * 2;

            doc.setFillColor(79, 70, 229);
            doc.rect(0, 0, pageW, 16, 'F');
            doc.setTextColor(255, 255, 255);
            doc.setFontSize(8.5);
            doc.setFont('helvetica', 'bold');
            doc.text('BLOG POST TEMPLATE', margin, 10.5);
            doc.setFont('helvetica', 'normal');
            doc.text('calmafricasafaris.com', pageW - margin, 10.5, { align: 'right' });

            var y = 26;

            doc.setFillColor(238, 242, 255);
            doc.setDrawColor(199, 210, 254);
            doc.roundedRect(margin, y - 4, cw, 14, 2, 2, 'FD');
            doc.setTextColor(79, 70, 229);
            doc.setFontSize(8);
            doc.setFont('helvetica', 'normal');
            doc.text(
                'Fill in the sections below, save the PDF, then upload it via Import on the blog editor.',
                margin + 3, y + 4.5, { maxWidth: cw - 6 }
            );
            y += 20;

            function section(label, hint, placeholder, rows) {
                doc.setFontSize(8);
                doc.setFont('helvetica', 'bold');
                doc.setTextColor(100, 116, 139);
                doc.text(label.toUpperCase(), margin, y);
                if (hint) {
                    doc.setFont('helvetica', 'normal');
                    doc.setFontSize(7.5);
                    doc.setTextColor(148, 163, 184);
                    doc.text('  ' + hint, margin + doc.getTextWidth(label.toUpperCase()), y);
                }
                y += 5;

                var boxH = rows * 8 + 4;
                doc.setFillColor(248, 250, 252);
                doc.setDrawColor(203, 213, 225);
                doc.roundedRect(margin, y, cw, boxH, 2, 2, 'FD');

                doc.setFont('helvetica', 'italic');
                doc.setFontSize(9);
                doc.setTextColor(148, 163, 184);
                doc.text(placeholder, margin + 4, y + 7.5, { maxWidth: cw - 8 });

                y += boxH + 11;
            }

            section('Blog Title', '(required)',
                'Write your blog post title here \u2014 keep it clear and compelling', 2);
            section('Excerpt', '(optional \u2014 auto-generated from content if blank)',
                'A short 1\u20132 sentence summary shown in listing pages and search results.', 3);
            section('Content', '(required)',
                'Start your blog post here.\n\nUse ALL CAPS lines for main headings (H1).\nTitle Case Lines Like This become H2 headings.\nLines ending with a colon: become H3.\n\n- Lines starting with a dash become bullet lists\n1. Lines starting with a number become numbered lists\n\nSeparate paragraphs with a blank line.',
                14);

            var fY = doc.internal.pageSize.getHeight() - 10;
            doc.setDrawColor(226, 232, 240);
            doc.line(margin, fY - 3, pageW - margin, fY - 3);
            doc.setFontSize(7);
            doc.setFont('helvetica', 'normal');
            doc.setTextColor(148, 163, 184);
            doc.text('calmafricasafaris.com \u00b7 Blog post template', margin, fY);
            doc.text('Admin \u2192 Blogs \u2192 Create \u2192 Import from Document', pageW - margin, fY, { align: 'right' });

            doc.save('blog-template.pdf');
            showStatus('&#9989; PDF template downloaded.', 'success');
        } catch (err) {
            console.error('[blog-import] PDF template:', err);
            showStatus('&#10060; Could not generate PDF: ' + esc(err.message), 'error');
        }
    }

    /* ══════════════════════════════════════════════════════════════════════
       12.  DOWNLOAD — DOCX TEMPLATE  (pure browser ZIP, no library)
    ══════════════════════════════════════════════════════════════════════ */
    async function downloadDocxTemplate() {
        showStatus('&#9203; Generating Word template&hellip;', 'loading');
        try {
            var docXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>\n' +
'<w:document xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main">\n' +
'  <w:body>\n' +
'\n' +
'    <!-- INSTRUCTIONS -->\n' +
'    <w:p>\n' +
'      <w:pPr><w:shd w:val="clear" w:color="auto" w:fill="EEF2FF"/></w:pPr>\n' +
'      <w:r><w:rPr><w:color w:val="4F46E5"/><w:sz w:val="18"/></w:rPr>\n' +
'        <w:t xml:space="preserve">Fill in each section below. Use Word heading styles (Heading 1, Heading 2, Heading 3) and bullet/numbered lists — they will be preserved when imported.</w:t>\n' +
'      </w:r>\n' +
'    </w:p>\n' +
'    <w:p><w:r><w:t></w:t></w:r></w:p>\n' +
'\n' +
'    <!-- TITLE -->\n' +
'    <w:p>\n' +
'      <w:pPr><w:pStyle w:val="Heading1"/></w:pPr>\n' +
'      <w:r><w:t>Write Your Blog Post Title Here</w:t></w:r>\n' +
'    </w:p>\n' +
'\n' +
'    <!-- EXCERPT -->\n' +
'    <w:p>\n' +
'      <w:pPr><w:pStyle w:val="Heading2"/></w:pPr>\n' +
'      <w:r><w:t>Excerpt (Optional)</w:t></w:r>\n' +
'    </w:p>\n' +
'    <w:p>\n' +
'      <w:r><w:rPr><w:i/><w:color w:val="94A3B8"/></w:rPr>\n' +
'        <w:t>A short 1-2 sentence summary shown in listing pages and search results.</w:t></w:r>\n' +
'    </w:p>\n' +
'    <w:p><w:r><w:t></w:t></w:r></w:p>\n' +
'\n' +
'    <!-- CONTENT -->\n' +
'    <w:p>\n' +
'      <w:pPr><w:pStyle w:val="Heading2"/></w:pPr>\n' +
'      <w:r><w:t>Introduction</w:t></w:r>\n' +
'    </w:p>\n' +
'    <w:p>\n' +
'      <w:r><w:t xml:space="preserve">Introduction paragraph. Start your blog post here. Write naturally.</w:t></w:r>\n' +
'    </w:p>\n' +
'    <w:p><w:r><w:t></w:t></w:r></w:p>\n' +
'    <w:p>\n' +
'      <w:pPr><w:pStyle w:val="Heading3"/></w:pPr>\n' +
'      <w:r><w:t>A Sub-Section Heading</w:t></w:r>\n' +
'    </w:p>\n' +
'    <w:p>\n' +
'      <w:r><w:t xml:space="preserve">Second paragraph. Each blank line between paragraphs becomes a new paragraph in the editor.</w:t></w:r>\n' +
'    </w:p>\n' +
'    <w:p><w:r><w:t></w:t></w:r></w:p>\n' +
'    <w:p>\n' +
'      <w:r><w:t xml:space="preserve">Third paragraph. Add as many sections as you need.</w:t></w:r>\n' +
'    </w:p>\n' +
'    <w:p><w:r><w:t></w:t></w:r></w:p>\n' +
'    <w:p>\n' +
'      <w:r><w:t xml:space="preserve">Closing paragraph or call to action.</w:t></w:r>\n' +
'    </w:p>\n' +
'\n' +
'    <w:sectPr/>\n' +
'  </w:body>\n' +
'</w:document>';

            var relsXml =
'<?xml version="1.0" encoding="UTF-8" standalone="yes"?>\n' +
'<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">\n' +
'  <Relationship Id="rId1"\n' +
'    Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument"\n' +
'    Target="word/document.xml"/>\n' +
'</Relationships>';

            var wordRelsXml =
'<?xml version="1.0" encoding="UTF-8" standalone="yes"?>\n' +
'<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">\n' +
'</Relationships>';

            var ctXml =
'<?xml version="1.0" encoding="UTF-8" standalone="yes"?>\n' +
'<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">\n' +
'  <Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>\n' +
'  <Default Extension="xml"  ContentType="application/xml"/>\n' +
'  <Override PartName="/word/document.xml"\n' +
'    ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.document.main+xml"/>\n' +
'</Types>';

            var enc = new TextEncoder();
            var zip = await buildZip([
                { name: '[Content_Types].xml',          data: enc.encode(ctXml)       },
                { name: '_rels/.rels',                  data: enc.encode(relsXml)     },
                { name: 'word/_rels/document.xml.rels', data: enc.encode(wordRelsXml) },
                { name: 'word/document.xml',            data: enc.encode(docXml)      },
            ]);

            triggerDownload(
                new Blob([zip], {
                    type: 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                }),
                'blog-template.docx'
            );
            showStatus('&#9989; Word template downloaded.', 'success');
        } catch (err) {
            console.error('[blog-import] DOCX template:', err);
            showStatus('&#10060; Could not generate DOCX: ' + esc(err.message), 'error');
        }
    }

    /* ══════════════════════════════════════════════════════════════════════
       13.  PURE-BROWSER ZIP BUILDER  (stored / method-0)
    ══════════════════════════════════════════════════════════════════════ */
    async function buildZip(entries) {
        function w32(n, a, o) { a[o]=n&0xFF; a[o+1]=(n>>8)&0xFF; a[o+2]=(n>>16)&0xFF; a[o+3]=(n>>24)&0xFF; }
        function w16(n, a, o) { a[o]=n&0xFF; a[o+1]=(n>>8)&0xFF; }

        var tbl = new Uint32Array(256);
        for (var i = 0; i < 256; i++) {
            var c = i;
            for (var k = 0; k < 8; k++) c = (c & 1) ? (0xEDB88320 ^ (c >>> 1)) : (c >>> 1);
            tbl[i] = c;
        }
        function crc32(data) {
            var c = 0xFFFFFFFF;
            for (var j = 0; j < data.length; j++) c = tbl[(c ^ data[j]) & 0xFF] ^ (c >>> 8);
            return (c ^ 0xFFFFFFFF) >>> 0;
        }

        var locals = [], dirs = [], offset = 0;

        entries.forEach(function (e) {
            var name = new TextEncoder().encode(e.name);
            var crc  = crc32(e.data);

            var lh = new Uint8Array(30 + name.length + e.data.length);
            w32(0x04034B50, lh, 0); w16(20,lh,4); w16(0,lh,6); w16(0,lh,8);
            w16(0,lh,10); w16(0,lh,12);
            w32(crc,lh,14); w32(e.data.length,lh,18); w32(e.data.length,lh,22);
            w16(name.length,lh,26); w16(0,lh,28);
            lh.set(name,30); lh.set(e.data,30+name.length);

            var cd = new Uint8Array(46 + name.length);
            w32(0x02014B50,cd,0); w16(20,cd,4); w16(20,cd,6); w16(0,cd,8); w16(0,cd,10);
            w16(0,cd,12); w16(0,cd,14);
            w32(crc,cd,16); w32(e.data.length,cd,20); w32(e.data.length,cd,24);
            w16(name.length,cd,28); w16(0,cd,30); w16(0,cd,32);
            w16(0,cd,34); w16(0,cd,36); w32(0,cd,38); w32(offset,cd,42);
            cd.set(name,46);

            locals.push(lh); dirs.push(cd); offset += lh.length;
        });

        function concat(arrays) {
            var out = new Uint8Array(arrays.reduce(function (s, a) { return s + a.length; }, 0));
            var off = 0;
            arrays.forEach(function (a) { out.set(a, off); off += a.length; });
            return out;
        }

        var cdBuf = concat(dirs);
        var eocd  = new Uint8Array(22);
        w32(0x06054B50,eocd,0); w16(0,eocd,4); w16(0,eocd,6);
        w16(entries.length,eocd,8); w16(entries.length,eocd,10);
        w32(cdBuf.length,eocd,12); w32(offset,eocd,16); w16(0,eocd,20);

        return concat(locals.concat(dirs, [eocd]));
    }

    function triggerDownload(blob, filename) {
        var url = URL.createObjectURL(blob);
        var a   = document.createElement('a');
        a.href = url; a.download = filename;
        document.body.appendChild(a);
        a.click();
        setTimeout(function () { document.body.removeChild(a); URL.revokeObjectURL(url); }, 1500);
    }

    /* ══════════════════════════════════════════════════════════════════════
       14.  STATUS BAR
    ══════════════════════════════════════════════════════════════════════ */
    function showStatus(html, type) {
        var bar = document.getElementById('import-status-bar');
        if (!bar) return;
        var cls = {
            success : 'bg-emerald-500/15 border-emerald-500/40 text-emerald-300',
            error   : 'bg-red-500/15 border-red-500/40 text-red-300',
            info    : 'bg-indigo-500/15 border-indigo-500/40 text-indigo-300',
            loading : 'bg-amber-500/15 border-amber-500/40 text-amber-300',
        };
        bar.className     = 'mt-3 px-4 py-2.5 rounded-xl border text-sm font-medium ' +
                            (cls[type] || cls.info);
        bar.innerHTML     = html;
        bar.style.display = 'block';
        if (type === 'success') {
            setTimeout(function () { bar.style.display = 'none'; }, 5000);
        }
    }

    /* ══════════════════════════════════════════════════════════════════════
       15.  BUILD THE IMPORT CARD HTML
    ══════════════════════════════════════════════════════════════════════ */
    function buildCard() {
        var card = document.createElement('div');
        card.id = 'blog-import-card';
        card.className = 'bg-slate-800/60 backdrop-blur border border-slate-700 rounded-2xl p-5 ring-1 ring-white/5 mb-5';
        card.innerHTML =
          '<div class="flex items-center gap-2 mb-4">' +
            '<svg class="w-4 h-4 text-indigo-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">' +
              '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>' +
            '</svg>' +
            '<span class="text-sm font-bold text-white">Import from Document</span>' +
            '<span class="text-xs text-slate-500">\u2014 headings, lists &amp; paragraphs auto-detected</span>' +
          '</div>' +

          '<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">' +

            '<label id="import-drop-zone" class="flex flex-col items-center justify-center rounded-xl ' +
              'border-2 border-dashed border-slate-600 bg-slate-900 hover:border-indigo-500 ' +
              'hover:bg-slate-900/80 cursor-pointer transition-all group" style="min-height:9rem;">' +
              '<svg class="w-7 h-7 text-slate-600 group-hover:text-indigo-400 transition mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">' +
                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>' +
              '</svg>' +
              '<span class="text-sm font-semibold text-slate-300 group-hover:text-white transition">Click or drag to upload</span>' +
              '<span class="text-xs text-slate-600 mt-1">' +
                '<span class="text-indigo-400 font-semibold">PDF</span> or ' +
                '<span class="text-indigo-400 font-semibold">DOCX</span>' +
                ' &nbsp;&middot;&nbsp; max ' + CFG.maxFileMB + ' MB' +
              '</span>' +
              '<input type="file" id="import-file-input" accept=".pdf,.doc,.docx" class="hidden">' +
            '</label>' +

            '<div class="flex flex-col justify-center gap-3">' +
              '<p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Download blank template</p>' +

              '<button type="button" id="dl-pdf-template" class="flex items-center gap-3 px-4 py-3 ' +
                'bg-slate-900 hover:bg-red-600/10 border border-slate-700 hover:border-red-500/50 ' +
                'rounded-xl transition-all group/pdf text-left">' +
                '<span class="flex items-center justify-center w-9 h-9 rounded-lg bg-red-500/10 ' +
                  'group-hover/pdf:bg-red-500/20 transition shrink-0">' +
                  '<svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">' +
                    '<path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>' +
                  '</svg>' +
                '</span>' +
                '<div>' +
                  '<p class="text-sm font-semibold text-white leading-tight">PDF Template</p>' +
                  '<p class="text-xs text-slate-500 mt-0.5">blog-template.pdf</p>' +
                '</div>' +
                '<svg class="w-4 h-4 text-slate-600 group-hover/pdf:text-red-400 transition ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">' +
                  '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>' +
                '</svg>' +
              '</button>' +

              '<button type="button" id="dl-docx-template" class="flex items-center gap-3 px-4 py-3 ' +
                'bg-slate-900 hover:bg-blue-600/10 border border-slate-700 hover:border-blue-500/50 ' +
                'rounded-xl transition-all group/docx text-left">' +
                '<span class="flex items-center justify-center w-9 h-9 rounded-lg bg-blue-500/10 ' +
                  'group-hover/docx:bg-blue-500/20 transition shrink-0">' +
                  '<svg class="w-5 h-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">' +
                    '<path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>' +
                  '</svg>' +
                '</span>' +
                '<div>' +
                  '<p class="text-sm font-semibold text-white leading-tight">Word Template</p>' +
                  '<p class="text-xs text-slate-500 mt-0.5">blog-template.docx</p>' +
                '</div>' +
                '<svg class="w-4 h-4 text-slate-600 group-hover/docx:text-blue-400 transition ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">' +
                  '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>' +
                '</svg>' +
              '</button>' +

            '</div>' +
          '</div>' +

          '<div id="import-status-bar" style="display:none;"></div>';

        return card;
    }

    /* ══════════════════════════════════════════════════════════════════════
       16.  INJECT CARD ABOVE TITLE EDITOR
    ══════════════════════════════════════════════════════════════════════ */
    function injectCard() {
        var titleEl = qs(CFG.titleEditor);
        if (!titleEl) return null;

        var titleCard = titleEl.parentElement;
        while (titleCard && titleCard !== document.body) {
            if (titleCard.classList.contains('rounded-2xl')) break;
            titleCard = titleCard.parentElement;
        }

        var column = titleCard && titleCard.parentElement;
        if (!column) return null;

        var card = buildCard();
        column.insertBefore(card, column.firstChild);
        return card;
    }

    /* ══════════════════════════════════════════════════════════════════════
       17.  WIRE UP ALL EVENTS
    ══════════════════════════════════════════════════════════════════════ */
    function init() {
        var card = injectCard();
        if (!card) return;

        var fileInput = qs('#import-file-input', card);
        if (fileInput) {
            fileInput.addEventListener('change', function () {
                handleFile(this.files[0]);
                this.value = '';
            });
        }

        var dz = qs('#import-drop-zone', card);
        if (dz) {
            dz.addEventListener('dragover', function (e) {
                e.preventDefault();
                dz.classList.add('border-indigo-500', 'bg-indigo-900/10');
            });
            ['dragleave', 'dragend'].forEach(function (ev) {
                dz.addEventListener(ev, function () {
                    dz.classList.remove('border-indigo-500', 'bg-indigo-900/10');
                });
            });
            dz.addEventListener('drop', function (e) {
                e.preventDefault();
                dz.classList.remove('border-indigo-500', 'bg-indigo-900/10');
                handleFile(e.dataTransfer.files[0]);
            });
        }

        var pdfBtn  = qs('#dl-pdf-template',  card);
        var docxBtn = qs('#dl-docx-template', card);
        if (pdfBtn)  pdfBtn.addEventListener('click',  downloadPdfTemplate);
        if (docxBtn) docxBtn.addEventListener('click', downloadDocxTemplate);
    }

    /* Boot */
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();