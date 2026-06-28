/**
 * tour-edit.js
 * Place this file at: public/js/tour-edit.js
 *
 * Requires window.TOUR_EDIT_CONFIG to be set before this file loads.
 */

document.addEventListener('DOMContentLoaded', function () {

    /* =========================================================
       TINY DOM HELPERS  — defined first, everything below uses them
    ========================================================= */
    function el(tag, props) {
        var node = document.createElement(tag);
        Object.keys(props || {}).forEach(function (k) {
            if (k === 'dataset') {
                Object.keys(props[k]).forEach(function (dk) { node.dataset[dk] = props[k][dk]; });
            } else if (k in node) {
                node[k] = props[k];
            } else {
                node.setAttribute(k, props[k]);
            }
        });
        return node;
    }

    function escStr(s) {
        return String(s || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    function icon(name) {
        var icons = {
            chevron: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>',
            x:       '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>',
            search:  '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>',
            trash:   '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>',
            users:   '<svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>',
        };
        return icons[name] || '';
    }

    /* =========================================================
       CONFIG
    ========================================================= */
    var CFG           = window.TOUR_EDIT_CONFIG || {};
    var TOUR_ID       = CFG.tourId || 'unknown';
    var STORAGE_KEY   = 'tour_edit_form_data_' + TOUR_ID;
    var accData       = Array.isArray(CFG.accommodations)     ? CFG.accommodations     : [];
    var PREDEFINED_KW = Array.isArray(CFG.predefinedKeywords) ? CFG.predefinedKeywords : [];

    /* =========================================================
       UTILITY
    ========================================================= */
    function uuid() {
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
            var r = Math.random() * 16 | 0, v = c === 'x' ? r : (r & 0x3 | 0x8);
            return v.toString(16);
        });
    }
    function tempId() { return 'tmp-' + Math.random().toString(36).substr(2, 9); }

    function autoResize(textarea) {
        textarea.style.height = 'auto';
        textarea.style.height = textarea.scrollHeight + 'px';
    }

    function toArray(val) {
        if (Array.isArray(val)) return val.map(String).filter(Boolean);
        if (typeof val === 'string' && val.trim())
            return val.split(',').map(function (s) { return s.trim(); }).filter(Boolean);
        return [];
    }

    /* =========================================================
       STATE
    ========================================================= */
    var itineraryDays = Array.isArray(CFG.itineraryDays) ? CFG.itineraryDays : [];
    var prices        = Array.isArray(CFG.prices)        ? CFG.prices        : [];
    var metaKeywords  = toArray(CFG.metaKeywords);
    var imagesList    = Array.isArray(CFG.imagesList)    ? CFG.imagesList    : [];

    /* =========================================================
       LOCAL STORAGE
    ========================================================= */
    function saveFormData() {
        try {
            var inputs = document.querySelectorAll('input[type="text"], input[type="number"], textarea, select');
            var formFields = {};
            inputs.forEach(function (input) {
                if (!input.name) return;
                var skip = ['itinerary[', 'prices[', 'meta_keywords', 'images[', 'delete_images[', 'uploads['];
                if (skip.some(function (p) { return input.name.startsWith(p); })) return;
                formFields[input.name] = input.value;
            });
            localStorage.setItem(STORAGE_KEY, JSON.stringify({
                formFields:    formFields,
                itineraryDays: itineraryDays,
                prices:        prices,
                metaKeywords:  Array.isArray(metaKeywords) ? metaKeywords : [],
                imagesList:    imagesList.map(function (img) {
                    return { preview: img.preview || null, id: img.id || null, path: img.path || null };
                })
            }));
        } catch (e) { console.warn('saveFormData error:', e); }
    }

    function loadSavedData() {
        try {
            var raw = localStorage.getItem(STORAGE_KEY);
            if (!raw) return;
            var data = JSON.parse(raw);

            if (data.formFields) {
                Object.keys(data.formFields).forEach(function (key) {
                    var field = document.querySelector('[name="' + key + '"]');
                    if (field && !field.value) field.value = data.formFields[key];
                });
            }
            if (data.itineraryDays && itineraryDays.length === 0) itineraryDays = data.itineraryDays;
            if (data.prices        && prices.length === 0)        prices        = data.prices;

            var savedKw = toArray(data.metaKeywords);
            if (savedKw.length > 0 && metaKeywords.length === 0) metaKeywords = savedKw;

            if (data.imagesList) imagesList = data.imagesList;

            renderItinerary();
            renderPrices();
            renderKeywords();
            renderPredefinedPanel('');
            renderImagesList();
        } catch (e) { console.error('loadSavedData error:', e); }
    }

    /* =========================================================
       SEARCHABLE ACCOMMODATION DROPDOWN
    ========================================================= */
    function createAccommodationDropdown(container, inputName, initialValue) {
        var selectedId      = null;
        var changeCallbacks = [];
        var highlightedIdx  = -1;
        var filteredOptions = [];

        var options = [{ id: null, name: 'No Accommodation', type: '', location: '', label: 'No Accommodation' }].concat(
            accData.map(function (acc) {
                return {
                    id:       acc.id,
                    name:     acc.name,
                    type:     acc.type     || '',
                    location: acc.location || '',
                    label:    acc.name + (acc.type ? ' \u2013 ' + acc.type : '') + (acc.location ? ' (' + acc.location + ')' : '')
                };
            })
        );

        var wrapper     = el('div',    { className: 'acc-dropdown-wrapper' });
        var trigger     = el('input',  { type: 'text', readOnly: true, className: 'acc-search-input', placeholder: 'Select Accommodation (Optional)' });
        var chevron     = el('span',   { className: 'acc-chevron', innerHTML: icon('chevron') });
        var clearBtn    = el('button', { type: 'button', className: 'acc-clear-btn', title: 'Clear', innerHTML: icon('x') });
        var menu        = el('div',    { className: 'acc-dropdown-menu' });
        var searchBox   = el('div',    { className: 'acc-search-box' });
        searchBox.style.position = 'relative';
        var searchIcon  = el('span',   { className: 'acc-search-icon', innerHTML: icon('search') });
        var searchInput = el('input',  { type: 'text', placeholder: 'Search accommodations\u2026' });
        var optList     = el('div',    { className: 'acc-options-list' });
        var hiddenInput = el('input',  { type: 'hidden', name: inputName, value: '' });

        searchBox.appendChild(searchIcon);
        searchBox.appendChild(searchInput);
        menu.appendChild(searchBox);
        menu.appendChild(optList);
        wrapper.appendChild(trigger);
        wrapper.appendChild(clearBtn);
        wrapper.appendChild(chevron);
        wrapper.appendChild(menu);
        wrapper.appendChild(hiddenInput);
        container.appendChild(wrapper);

        function escHtml(s) {
            return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
        }
        function hlText(text, q) {
            if (!q) return escHtml(text);
            var idx = text.toLowerCase().indexOf(q.toLowerCase());
            if (idx === -1) return escHtml(text);
            return escHtml(text.slice(0,idx)) + '<mark class="acc-highlight">' + escHtml(text.slice(idx,idx+q.length)) + '</mark>' + escHtml(text.slice(idx+q.length));
        }

        function renderOptions(query) {
            query = (query || '').trim();
            optList.innerHTML = '';
            highlightedIdx = -1;
            filteredOptions = options.filter(function (o) {
                if (!query) return true;
                var q = query.toLowerCase();
                return o.name.toLowerCase().indexOf(q) !== -1 || o.type.toLowerCase().indexOf(q) !== -1 || o.location.toLowerCase().indexOf(q) !== -1;
            });
            if (!filteredOptions.length) {
                optList.appendChild(el('div', { className: 'acc-no-results', textContent: 'No accommodations found' }));
                return;
            }
            filteredOptions.forEach(function (opt, idx2) {
                var item = el('div', { className: 'acc-option' + (opt.id == selectedId ? ' selected' : '') + (opt.id === null ? ' acc-option-none' : '') });
                item.innerHTML = opt.id === null
                    ? '<span class="acc-option-name" style="font-style:italic;color:#6b7280">No Accommodation</span>'
                    : '<span class="acc-option-name">' + hlText(opt.name, query) + '</span>' +
                      ((opt.type || opt.location) ? '<span class="acc-option-meta">' + escHtml([opt.type, opt.location].filter(Boolean).join(' \xb7 ')) + '</span>' : '');
                item.addEventListener('mousedown', function (e) { e.preventDefault(); selectOption(opt); });
                optList.appendChild(item);
            });
        }

        function selectOption(opt) {
            selectedId = opt.id;
            hiddenInput.value = opt.id !== null ? opt.id : '';
            if (opt.id === null) {
                trigger.value = ''; trigger.classList.remove('has-value'); clearBtn.classList.remove('visible');
            } else {
                trigger.value = opt.label; trigger.classList.add('has-value'); clearBtn.classList.add('visible');
            }
            closeMenu();
            changeCallbacks.forEach(function (cb) {
                cb(opt.id, opt.id !== null ? accData.find(function (a) { return a.id == opt.id; }) : null);
            });
        }

        function openMenu()  { menu.classList.add('open'); chevron.classList.add('open'); searchInput.value = ''; renderOptions(''); setTimeout(function () { searchInput.focus(); }, 50); }
        function closeMenu() { menu.classList.remove('open'); chevron.classList.remove('open'); highlightedIdx = -1; }

        trigger.addEventListener('click', function () { menu.classList.contains('open') ? closeMenu() : openMenu(); });
        clearBtn.addEventListener('click', function (e) { e.stopPropagation(); selectOption(options[0]); });
        searchInput.addEventListener('input', function () { renderOptions(this.value); });
        searchInput.addEventListener('keydown', function (e) {
            var items = optList.querySelectorAll('.acc-option');
            if      (e.key === 'ArrowDown') { e.preventDefault(); highlightedIdx = Math.min(highlightedIdx + 1, items.length - 1); updateHL(items); }
            else if (e.key === 'ArrowUp')   { e.preventDefault(); highlightedIdx = Math.max(highlightedIdx - 1, 0); updateHL(items); }
            else if (e.key === 'Enter')     { e.preventDefault(); if (highlightedIdx >= 0 && filteredOptions[highlightedIdx]) selectOption(filteredOptions[highlightedIdx]); }
            else if (e.key === 'Escape')    { closeMenu(); trigger.focus(); }
        });
        function updateHL(items) {
            items.forEach(function (item, i) {
                item.classList.toggle('highlighted', i === highlightedIdx);
                if (i === highlightedIdx) item.scrollIntoView({ block: 'nearest' });
            });
        }
        document.addEventListener('mousedown', function (e) { if (!wrapper.contains(e.target)) closeMenu(); });

        function getValue()  { return selectedId; }
        function setValue(id) {
            var opt = null;
            for (var i = 0; i < options.length; i++) { if (options[i].id == id) { opt = options[i]; break; } }
            if (!opt) opt = options[0];
            selectedId = opt.id; hiddenInput.value = opt.id !== null ? opt.id : '';
            if (opt.id !== null) { trigger.value = opt.label; trigger.classList.add('has-value'); clearBtn.classList.add('visible'); }
            else                 { trigger.value = '';        trigger.classList.remove('has-value'); clearBtn.classList.remove('visible'); }
        }
        function onChange(cb) { changeCallbacks.push(cb); }

        if (initialValue) setValue(initialValue);
        return { getValue: getValue, setValue: setValue, onChange: onChange };
    }

    /* =========================================================
       ACCOMMODATION IMAGE LOADER
    ========================================================= */
    function loadAccommodationImages(id) {
        return fetch((CFG.apiBase || '') + '/accommodations/' + id)
            .then(function (r) { return r.json(); })
            .then(function (d) { return d.success ? (d.data.images || []) : []; })
            .catch(function (e) { console.error('loadAccommodationImages', e); return []; });
    }

    /* =========================================================
       ITINERARY RENDER
    ========================================================= */
    function renderItinerary() {
        var container = document.getElementById('itinerary-days');
        var noMessage = document.getElementById('no-itinerary-message');
        if (!container) return;

        if (itineraryDays.length === 0) {
            noMessage.style.display = 'block';
            container.innerHTML = '';
            return;
        }
        noMessage.style.display = 'none';
        container.innerHTML = '';

        itineraryDays.forEach(function (day, i) {
            var dayNum = i + 1;
            if (!Array.isArray(day.images)) day.images = [];
            day.images.forEach(function (img) {
                if (!img.tempId && !img.existingMediaId) img.tempId = tempId();
                if (!img.blockId) img.blockId = 'blk-' + uuid();
            });

            var wrap = document.createElement('div');
            wrap.className = 'relative bg-gradient-to-r from-indigo-50 to-purple-50 rounded-lg p-6 border-2 border-indigo-200 hover:border-indigo-300 transition duration-150';

            wrap.innerHTML =
                '<div class="absolute top-4 left-4 bg-indigo-600 text-white rounded-full w-10 h-10 flex items-center justify-center font-bold text-lg shadow-md">' + dayNum + '</div>' +
                '<input type="hidden" name="itinerary[' + dayNum + '][day_number]" value="' + dayNum + '">' +
                (day.id ? '<input type="hidden" name="itinerary[' + dayNum + '][id]" value="' + escStr(day.id) + '">' : '') +
                '<input type="hidden" data-contentblock-input name="itinerary[' + dayNum + '][content_blocks]" value="">' +

                '<div class="ml-14 space-y-4">' +
                    '<div>' +
                        '<label class="block text-sm font-medium text-gray-700 mb-2">Activity <span class="text-red-500">*</span></label>' +
                        '<textarea name="itinerary[' + dayNum + '][activity]" ' +
                            'class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 resize-none overflow-hidden auto-resize" ' +
                            'required placeholder="Describe the day\'s activities..." rows="1">' + escStr(day.activity || '') + '</textarea>' +
                    '</div>' +

                    '<div class="grid grid-cols-1 md:grid-cols-3 gap-4">' +
                        '<div>' +
                            '<label class="block text-sm font-medium text-gray-700 mb-2">Day Title</label>' +
                            '<input type="text" name="itinerary[' + dayNum + '][day_title]" ' +
                                'class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" ' +
                                'value="' + escStr(day.day_title || '') + '" placeholder="e.g., Arrival Day">' +
                        '</div>' +
                        '<div>' +
                            '<label class="block text-sm font-medium text-gray-700 mb-2">Accommodation</label>' +
                            '<div id="acc-dropdown-mount-' + i + '" class="acc-mount"></div>' +
                            '<input type="hidden" name="itinerary[' + dayNum + '][accommodation]" value="' + escStr(day.accommodation || '') + '">' +
                            '<div id="accommodation-images-' + i + '" class="mt-3 hidden">' +
                                '<p class="text-sm text-gray-600 mb-2">Accommodation Images:</p>' +
                                '<div class="grid grid-cols-2 md:grid-cols-4 gap-2" id="accommodation-images-grid-' + i + '"></div>' +
                            '</div>' +
                        '</div>' +
                        '<div>' +
                            '<label class="block text-sm font-medium text-gray-700 mb-2">Meals</label>' +
                            '<input type="text" name="itinerary[' + dayNum + '][meals]" ' +
                                'class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" ' +
                                'value="' + escStr(day.meals || '') + '" placeholder="e.g., B, L, D">' +
                        '</div>' +
                    '</div>' +

                    '<div>' +
                        '<label class="block text-sm font-medium text-gray-700 mb-2">Day Images (optional)</label>' +
                        '<div id="day-images-' + dayNum + '" class="space-y-3"></div>' +
                        '<div class="flex gap-2 mt-3">' +
                            '<button type="button" class="px-3 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 add-day-image" data-index="' + i + '">Add Image</button>' +
                            '<p class="text-sm text-gray-500 self-center">Add images to appear inline with this day\'s description.</p>' +
                        '</div>' +
                    '</div>' +
                '</div>' +

                '<button type="button" ' +
                    'class="absolute top-4 right-4 bg-red-600 hover:bg-red-700 text-white rounded-lg px-3 py-2 transition duration-150 flex items-center gap-2 font-medium text-sm shadow-sm remove-day" ' +
                    'data-index="' + i + '" title="Remove Day">' +
                    icon('trash') + ' Remove' +
                '</button>';

            wrap.querySelector('.remove-day').onclick = function () {
                itineraryDays.splice(i, 1);
                renderItinerary();
                saveFormData();
            };

            container.appendChild(wrap);

            // content_blocks hidden input
            var cbInput = wrap.querySelector('[data-contentblock-input]');
            try { cbInput.value = (day.blocks && Array.isArray(day.blocks)) ? JSON.stringify(day.blocks) : ''; }
            catch (err) { cbInput.value = ''; }

            // mount accommodation dropdown
            var mountPoint = wrap.querySelector('#acc-dropdown-mount-' + i);
            var accDD = createAccommodationDropdown(mountPoint, 'itinerary[' + dayNum + '][accommodation_id]', day.accommodation_id);

            accDD.onChange(function (accommodationId) {
                day.accommodation_id = accommodationId || null;
                var imgsCont = document.getElementById('accommodation-images-' + i);
                var imgsGrid = document.getElementById('accommodation-images-grid-' + i);
                if (accommodationId) {
                    loadAccommodationImages(accommodationId).then(function (imgs) {
                        if (imgs.length) {
                            imgsGrid.innerHTML = '';
                            imgs.forEach(function (img) {
                                var d = document.createElement('div');
                                d.className = 'relative group';
                                d.innerHTML = '<img src="' + escStr(img.url) + '" alt="' + escStr(img.alt_text || '') + '" class="w-full h-20 object-cover rounded border border-gray-200">' +
                                    '<div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white text-xs p-1 rounded-b opacity-0 group-hover:opacity-100 transition">' + escStr(img.caption || 'No caption') + '</div>';
                                imgsGrid.appendChild(d);
                            });
                            imgsCont.classList.remove('hidden');
                        } else { imgsCont.classList.add('hidden'); }
                    });
                } else { imgsCont.classList.add('hidden'); }
                saveFormData();
            });

            // preload accommodation images if already selected
            if (day.accommodation_id) {
                (function (idx) {
                    loadAccommodationImages(day.accommodation_id).then(function (imgs) {
                        var imgsCont = document.getElementById('accommodation-images-' + idx);
                        var imgsGrid = document.getElementById('accommodation-images-grid-' + idx);
                        if (imgs.length) {
                            imgsGrid.innerHTML = '';
                            imgs.forEach(function (img) {
                                var d = document.createElement('div');
                                d.className = 'relative group';
                                d.innerHTML = '<img src="' + escStr(img.url) + '" alt="' + escStr(img.alt_text || '') + '" class="w-full h-20 object-cover rounded border border-gray-200">' +
                                    '<div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white text-xs p-1 rounded-b opacity-0 group-hover:opacity-100 transition">' + escStr(img.caption || 'No caption') + '</div>';
                                imgsGrid.appendChild(d);
                            });
                            imgsCont.classList.remove('hidden');
                        }
                    });
                }(i));
            }

            // day images
            var dayImgsContainer = document.getElementById('day-images-' + dayNum);

            function renderDayImages() {
                dayImgsContainer.innerHTML = '';
                day.images.forEach(function (imgObj, imgIndex) {
                    if (!imgObj.tempId && !imgObj.existingMediaId) imgObj.tempId = tempId();
                    if (!imgObj.blockId) imgObj.blockId = 'blk-' + uuid();

                    var w = document.createElement('div');
                    w.className = 'flex items-start gap-4 bg-white p-3 rounded-lg border border-gray-200';

                    var previewSrc = escStr(imgObj.preview || '');
                    var showImg    = imgObj.preview ? 'block' : 'none';
                    var showSvg    = imgObj.preview ? 'none'  : 'block';

                    var inputsHtml = imgObj.existingMediaId
                        ? '<input type="hidden" name="itinerary[' + dayNum + '][existing_media_ids][]" value="' + escStr(imgObj.existingMediaId) + '">' +
                          '<p class="text-sm font-medium mb-1">Existing image</p>' +
                          '<p class="text-xs text-gray-500 truncate">' + escStr(imgObj.storage_path || imgObj.preview || '') + '</p>' +
                          '<input type="text" name="itinerary[' + dayNum + '][image_captions][]" value="' + escStr(imgObj.caption || '') + '" placeholder="Caption (optional)" class="mt-2 w-full px-3 py-2 border border-gray-300 rounded-lg caption-input">'
                        : '<input type="file" accept="image/*" name="itinerary[' + dayNum + '][uploads][' + escStr(imgObj.tempId) + ']" class="w-full image-input" data-day="' + i + '" data-img="' + imgIndex + '">' +
                          '<input type="text" name="itinerary[' + dayNum + '][image_captions][]" value="' + escStr(imgObj.caption || '') + '" placeholder="Caption (optional)" class="mt-2 w-full px-3 py-2 border border-gray-300 rounded-lg caption-input">';

                    w.innerHTML =
                        '<div class="w-20 h-20 bg-gray-100 rounded-lg overflow-hidden flex items-center justify-center">' +
                            '<img id="it-' + dayNum + '-img-preview-' + imgIndex + '" src="' + previewSrc + '" class="w-full h-full object-cover" style="display:' + showImg + '">' +
                            '<svg class="w-8 h-8 text-gray-400" style="display:' + showSvg + '" fill="none" stroke="currentColor" viewBox="0 0 24 24">' +
                                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>' +
                            '</svg>' +
                        '</div>' +
                        '<div class="flex-1">' + inputsHtml + '</div>' +
                        '<div class="flex-shrink-0">' +
                            '<button type="button" class="bg-red-600 hover:bg-red-700 text-white rounded-lg px-3 py-2 remove-day-image">Remove</button>' +
                        '</div>';

                    var fileInput = w.querySelector('.image-input');
                    if (fileInput) {
                        fileInput.addEventListener('change', function () {
                            var file = this.files[0];
                            if (file) {
                                var reader = new FileReader();
                                reader.onload = function (ev) {
                                    imgObj.preview = ev.target.result;
                                    var imgEl = document.getElementById('it-' + dayNum + '-img-preview-' + imgIndex);
                                    imgEl.src = ev.target.result;
                                    imgEl.style.display = 'block';
                                    if (imgEl.nextElementSibling) imgEl.nextElementSibling.style.display = 'none';
                                    saveFormData();
                                };
                                reader.readAsDataURL(file);
                            }
                        });
                    }

                    var capInput = w.querySelector('.caption-input');
                    if (capInput) {
                        capInput.addEventListener('input', function () { imgObj.caption = this.value; saveFormData(); });
                    }

                    w.querySelector('.remove-day-image').addEventListener('click', function () {
                        var toRemove = day.images[imgIndex];
                        if (toRemove && toRemove.existingMediaId) {
                            var h = document.createElement('input');
                            h.type = 'hidden'; h.name = 'delete_itinerary_image_ids[]'; h.value = toRemove.existingMediaId;
                            document.querySelector('form').appendChild(h);
                        }
                        day.images.splice(imgIndex, 1);
                        renderDayImages();
                        saveFormData();
                    });

                    dayImgsContainer.appendChild(w);
                });
            }

            wrap.querySelector('.add-day-image').addEventListener('click', function () {
                day.images.push({ preview: '', caption: '', tempId: tempId(), blockId: 'blk-' + uuid(), existingMediaId: null });
                renderDayImages();
                saveFormData();
            });

            var actTextarea = wrap.querySelector('.auto-resize');
            actTextarea.addEventListener('input', function () { autoResize(this); day.activity = this.value; saveFormData(); });

            wrap.querySelectorAll('input[name^="itinerary"], textarea[name^="itinerary"], select[name^="itinerary"]').forEach(function (inp) {
                inp.addEventListener('input', function () {
                    var m = this.getAttribute('name').match(/itinerary\[\d+\]\[([^\]]+)\]/);
                    if (m) {
                        var k = m[1];
                        if (['day_number','id','content_blocks','images','image_captions','uploads','existing_media_ids'].indexOf(k) !== -1) return;
                        day[k] = this.value;
                    }
                    saveFormData();
                });
            });

            renderDayImages();
            autoResize(actTextarea);
        });
    }

    var addDayBtn = document.getElementById('add-itinerary-day');
    if (addDayBtn) {
        addDayBtn.onclick = function () {
            itineraryDays.push({ activity: '', day_title: '', accommodation: '', accommodation_id: null, meals: '', images: [], blocks: [] });
            renderItinerary();
            saveFormData();
        };
    }

    /* =========================================================
       BUILD CONTENT BLOCKS (called on form submit)
    ========================================================= */
    function buildAndAttachContentBlocks() {
        var container = document.getElementById('itinerary-days');
        if (!container) return;
        container.querySelectorAll('[data-contentblock-input]').forEach(function (hiddenInput, idx) {
            var dayData = itineraryDays[idx];
            if (!dayData) return;
            var blocks = [];
            if (Array.isArray(dayData.blocks) && dayData.blocks.length > 0) {
                dayData.blocks.forEach(function (b) {
                    if (b.type === 'image' && !b.media_id && !b.temp_media_id) {
                        var match = dayData.images.find(function (im) { return im.caption === b.caption || im.preview === b.preview; });
                        if (match) {
                            if (match.existingMediaId) b.media_id = match.existingMediaId;
                            else if (match.tempId)     b.temp_media_id = match.tempId;
                        }
                    }
                    if (!b.id) b.id = 'blk-' + uuid();
                    blocks.push(b);
                });
            } else {
                if (dayData.activity && dayData.activity.trim()) {
                    blocks.push({ id: 'blk-' + uuid(), type: 'text', text: dayData.activity.trim() });
                }
                dayData.images.forEach(function (img) {
                    blocks.push({
                        id:            img.blockId || ('blk-' + uuid()),
                        type:          'image',
                        caption:       img.caption || '',
                        media_id:      img.existingMediaId || undefined,
                        temp_media_id: img.existingMediaId ? undefined : img.tempId
                    });
                });
            }
            hiddenInput.value = JSON.stringify(blocks);
        });
    }

    /* =========================================================
       PRICES
    ========================================================= */
    function renderPrices() {
        var container = document.getElementById('prices');
        var noMessage = document.getElementById('no-prices-message');
        if (!container) return;
        if (prices.length === 0) { noMessage.style.display = 'block'; container.innerHTML = ''; return; }
        noMessage.style.display = 'none';
        container.innerHTML = '';

        prices.forEach(function (price, i) {
            var prNum = i + 1;
            var wrap  = document.createElement('div');
            wrap.className = 'relative bg-green-50 rounded-lg p-6 border-2 border-green-200 hover:border-green-300 transition duration-150';
            wrap.innerHTML =
                (price.id ? '<input type="hidden" name="prices[' + prNum + '][id]" value="' + escStr(price.id) + '">' : '') +
                '<div class="grid grid-cols-1 md:grid-cols-2 gap-4">' +
                    '<div>' +
                        '<label class="block text-sm font-medium text-gray-700 mb-2">Group Size <span class="text-red-500">*</span></label>' +
                        '<div class="relative">' +
                            '<div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">' + icon('users') + '</div>' +
                            '<input type="number" name="prices[' + prNum + '][group_size]" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" min="1" required value="' + escStr(price.group_size || '') + '" placeholder="e.g., 2">' +
                        '</div>' +
                    '</div>' +
                    '<div>' +
                        '<label class="block text-sm font-medium text-gray-700 mb-2">Price (USD) <span class="text-red-500">*</span></label>' +
                        '<div class="relative">' +
                            '<div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"><span class="text-gray-500 font-medium">$</span></div>' +
                            '<input type="number" step="0.01" name="prices[' + prNum + '][price]" class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" min="0" required value="' + escStr(price.price || '') + '" placeholder="0.00">' +
                        '</div>' +
                    '</div>' +
                '</div>' +
                '<button type="button" class="absolute top-4 right-4 bg-red-600 hover:bg-red-700 text-white rounded-lg px-3 py-2 flex items-center gap-2 font-medium text-sm shadow-sm remove-price" data-index="' + i + '" title="Remove Price">' + icon('trash') + ' Remove</button>';

            wrap.querySelector('.remove-price').onclick = function () { prices.splice(i, 1); renderPrices(); saveFormData(); };
            wrap.querySelectorAll('input').forEach(function (inp) {
                inp.addEventListener('input', function () {
                    if (this.name.indexOf('[group_size]') !== -1) price.group_size = this.value;
                    if (this.name.indexOf('[price]')      !== -1) price.price      = this.value;
                    saveFormData();
                });
            });
            container.appendChild(wrap);
        });
    }

    var addPriceBtn = document.getElementById('add-price');
    if (addPriceBtn) {
        addPriceBtn.onclick = function () {
            prices.push({ group_size: '', price: '' });
            renderPrices();
            saveFormData();
        };
    }

    /* =========================================================
       META KEYWORDS
    ========================================================= */
    var $kwInput  = document.getElementById('meta_keyword_input');
    var $kwAddBtn = document.getElementById('add-meta-keyword');
    var $kwTags   = document.getElementById('meta_keywords_tags');
    var $kwHidden = document.getElementById('meta_keywords');
    var $kwSearch = document.getElementById('predefined_keyword_search');
    var $kwPanel  = document.getElementById('predefined_keywords_panel');

    function renderKeywords() {
        if (!Array.isArray(metaKeywords)) metaKeywords = [];
        if (!$kwTags) return;
        $kwTags.innerHTML = '';
        metaKeywords.forEach(function (kw, idx) {
            var tag = document.createElement('span');
            tag.className = 'inline-flex items-center bg-indigo-100 text-indigo-800 rounded-full px-4 py-2 font-medium text-sm border border-indigo-200 hover:bg-indigo-200 transition duration-150';
            tag.innerHTML = escStr(kw) + ' <button type="button" class="ml-2 text-red-600 hover:text-red-800 font-bold" title="Remove">' + icon('x') + '</button>';
            tag.querySelector('button').onclick = function () {
                metaKeywords.splice(idx, 1);
                renderKeywords();
                renderPredefinedPanel($kwSearch ? $kwSearch.value : '');
                saveFormData();
            };
            $kwTags.appendChild(tag);
        });
        if ($kwHidden) $kwHidden.value = metaKeywords.join(',');
    }

    function renderPredefinedPanel(filter) {
        if (!$kwPanel) return;
        filter = (filter || '').toLowerCase().trim();
        $kwPanel.innerHTML = '';
        var filtered = PREDEFINED_KW.filter(function (kw) {
            return !filter || kw.toLowerCase().indexOf(filter) !== -1;
        });
        if (!filtered.length) {
            $kwPanel.innerHTML = '<p class="text-sm text-gray-400 italic p-2">No matching keywords</p>';
            return;
        }
        filtered.forEach(function (kw) {
            var isSelected = metaKeywords.indexOf(kw) !== -1;
            var btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-sm font-medium border transition duration-150 ' +
                (isSelected
                    ? 'bg-indigo-600 text-white border-indigo-600 cursor-default'
                    : 'bg-white text-gray-700 border-gray-300 hover:border-indigo-400 hover:text-indigo-700 hover:bg-indigo-50');
            btn.textContent = (isSelected ? '\u2713 ' : '+ ') + kw;
            if (!isSelected) {
                btn.onclick = function () {
                    if (metaKeywords.indexOf(kw) === -1) {
                        metaKeywords.push(kw);
                        renderKeywords();
                        renderPredefinedPanel($kwSearch ? $kwSearch.value : '');
                        saveFormData();
                    }
                };
            }
            $kwPanel.appendChild(btn);
        });
    }

    function addCustomKeyword() {
        if (!$kwInput) return;
        var val = $kwInput.value.trim();
        if (val && metaKeywords.indexOf(val) === -1) {
            metaKeywords.push(val);
            renderKeywords();
            renderPredefinedPanel($kwSearch ? $kwSearch.value : '');
            $kwInput.value = '';
            saveFormData();
        }
    }
    if ($kwAddBtn) $kwAddBtn.onclick = addCustomKeyword;
    if ($kwInput)  $kwInput.addEventListener('keydown', function (e) { if (e.key === 'Enter') { e.preventDefault(); addCustomKeyword(); } });
    if ($kwSearch) $kwSearch.addEventListener('input',  function ()  { renderPredefinedPanel(this.value); });

    /* =========================================================
       GALLERY IMAGES
    ========================================================= */
    function renderImagesList() {
        var listDiv    = document.getElementById('images-list');
        var noMessage  = document.getElementById('no-images-message');
        var storageUrl = (CFG.storageBaseUrl || '/storage').replace(/\/$/, '');
        if (!listDiv) return;
        if (imagesList.length === 0) { noMessage.style.display = 'block'; listDiv.innerHTML = ''; return; }
        noMessage.style.display = 'none';
        listDiv.innerHTML = '';

        imagesList.forEach(function (imgObj, i) {
            var isExisting = imgObj.isExisting || false;
            var wrap = document.createElement('div');
            wrap.className = 'flex items-center gap-4 ' +
                (isExisting ? 'bg-blue-50 border-blue-200 hover:border-blue-300' : 'bg-gray-50 border-gray-200 hover:border-indigo-300') +
                ' p-4 rounded-lg border-2 transition duration-150';

            if (isExisting) {
                wrap.innerHTML =
                    '<div class="flex-shrink-0"><div class="w-20 h-20 bg-gray-100 rounded-lg border-2 border-gray-300 overflow-hidden">' +
                        '<img src="' + storageUrl + '/' + escStr(imgObj.path || '') + '" class="w-full h-full object-cover">' +
                    '</div></div>' +
                    '<div class="flex-1"><p class="text-sm font-medium text-blue-700">Existing Image</p><p class="text-xs text-gray-500 truncate">' + escStr(imgObj.path || '') + '</p></div>' +
                    '<button type="button" class="flex-shrink-0 bg-red-600 hover:bg-red-700 text-white rounded-lg px-3 py-2 flex items-center gap-2 font-medium text-sm shadow-sm delete-img" title="Delete">' + icon('trash') + ' Delete</button>';

                wrap.querySelector('.delete-img').onclick = function () {
                    var h = document.createElement('input');
                    h.type = 'hidden'; h.name = 'delete_images[]'; h.value = imgObj.id;
                    document.querySelector('form').appendChild(h);
                    imagesList.splice(i, 1); renderImagesList(); saveFormData();
                };
            } else {
                var showImg = imgObj.preview ? 'block' : 'none';
                var showSvg = imgObj.preview ? 'none'  : 'block';
                wrap.innerHTML =
                    '<div class="flex-shrink-0"><div class="w-20 h-20 bg-gray-100 rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center overflow-hidden">' +
                        '<img src="' + escStr(imgObj.preview || '') + '" class="w-full h-full object-cover" style="display:' + showImg + '" id="img-preview-' + i + '">' +
                        '<svg class="w-8 h-8 text-gray-400" style="display:' + showSvg + '" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>' +
                    '</div></div>' +
                    '<div class="flex-1"><input type="file" accept="image/*" name="images[]" ' +
                        'class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 image-input" ' +
                        'data-index="' + i + '"></div>' +
                    '<button type="button" class="flex-shrink-0 bg-red-600 hover:bg-red-700 text-white rounded-lg px-3 py-2 flex items-center gap-2 font-medium text-sm shadow-sm remove-img" title="Remove">' + icon('trash') + '</button>';

                wrap.querySelector('.image-input').addEventListener('change', function () {
                    var file = this.files[0];
                    if (file) {
                        var reader = new FileReader();
                        reader.onload = function (ev) {
                            imgObj.preview = ev.target.result;
                            var imgEl = document.getElementById('img-preview-' + i);
                            imgEl.src = ev.target.result; imgEl.style.display = 'block';
                            if (imgEl.nextElementSibling) imgEl.nextElementSibling.style.display = 'none';
                            saveFormData();
                        };
                        reader.readAsDataURL(file);
                    }
                });
                wrap.querySelector('.remove-img').onclick = function () { imagesList.splice(i, 1); renderImagesList(); saveFormData(); };
            }
            listDiv.appendChild(wrap);
        });
    }

    var addImageBtn = document.getElementById('add-image');
    if (addImageBtn) {
        addImageBtn.onclick = function () {
            imagesList.push({ preview: '', isExisting: false });
            renderImagesList();
            saveFormData();
        };
    }

    /* =========================================================
       FEATURED IMAGE PREVIEW
    ========================================================= */
    var featuredInput = document.getElementById('featured_image');
    if (featuredInput) {
        featuredInput.addEventListener('change', function () {
            var preview = document.getElementById('featured_image_preview');
            preview.innerHTML = '';
            var file = this.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function (ev) {
                    preview.innerHTML =
                        '<div><p class="text-sm font-medium text-gray-700 mb-2">New Image Preview:</p>' +
                        '<div class="relative inline-block">' +
                            '<img src="' + ev.target.result + '" class="h-32 w-auto rounded-lg border-2 border-gray-200 shadow-md">' +
                            '<div class="absolute -top-2 -right-2 bg-green-500 text-white rounded-full p-1">' +
                                '<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>' +
                            '</div>' +
                        '</div></div>';
                };
                reader.readAsDataURL(file);
            }
        });
    }

    /* =========================================================
       SLUG AUTO-GENERATE
    ========================================================= */
    var titleInput = document.getElementById('title');
    var slugInput  = document.getElementById('slug');
    if (titleInput && slugInput) {
        titleInput.addEventListener('input', function () {
            if (!slugInput.dataset.manuallyEdited) {
                slugInput.value = this.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
            }
        });
        slugInput.addEventListener('input', function () { this.dataset.manuallyEdited = '1'; });
    }

    /* =========================================================
       FORM SUBMIT
    ========================================================= */
    var form = document.getElementById('edit-tour-form');
    if (form) {
        form.addEventListener('submit', function () {
            buildAndAttachContentBlocks();
            localStorage.removeItem(STORAGE_KEY);
        });
    }

    /* =========================================================
       INIT
    ========================================================= */
    renderItinerary();
    renderPrices();
    renderKeywords();
    renderPredefinedPanel('');
    renderImagesList();
    loadSavedData();

});