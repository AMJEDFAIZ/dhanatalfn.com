<hr class="my-3">
<h3 class="h6 fw-bold mb-3">الكلمات المفتاحية والوسوم</h3>

@php
$kwBlockId = 'kw_block_' . uniqid();
$metaLimit = (int) \App\Services\KeywordService::META_LIMIT;
$contentLimit = (int) \App\Services\KeywordService::CONTENT_LIMIT;

$metaSelected = old('meta_keyword_ids', $metaKeywordIds ?? []) ?? [];
$contentSelected = old('content_keyword_ids', $contentKeywordIds ?? []) ?? [];
$selectedIds = collect(array_merge($metaSelected, $contentSelected))
->map(fn ($v) => (int) $v)
->filter()
->unique()
->values();
$keywordsById = $keywords->keyBy('id');
$primarySelected = collect(old('keyword_primary_ids', $keywordPrimaryIds ?? []) ?? [])
->map(fn ($v) => (int) $v)
->values()
->all();
$weightsSelected = old('keyword_weights', $keywordWeights ?? []) ?? [];
@endphp

<style>
    .kw-badges {
        max-height: 140px;
        overflow: auto;
    }

    .kw-badge {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        max-width: 100%;
        text-align: right;
    }

    .kw-badge span:first-child {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        max-width: 220px;
    }

    .kw-badge .kw-x {
        line-height: 1;
    }
</style>

<div id="{{ $kwBlockId }}">
    <div class="row g-3">
        <div class="col-lg-6">
            <div class="card p-3" data-kw-scope="meta">
                <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
                    <div class="fw-bold">كلمات SEO</div>
                    <div class="badge text-bg-secondary" data-kw-count></div>
                </div>

                <div class="input-group input-group-sm mb-2">
                    <span class="input-group-text">بحث</span>
                    <input class="form-control" type="text" autocomplete="off" placeholder="اكتب للبحث داخل الكلمات" data-kw-search>
                    <button class="btn btn-outline-secondary" type="button" data-kw-clear-search>مسح</button>
                </div>

                <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
                    <div class="small text-muted">نتائج البحث تظهر داخل القائمة</div>
                    <button class="btn btn-sm btn-outline-primary" type="button" data-kw-select-visible>تحديد الظاهر</button>
                </div>

                <select name="meta_keyword_ids[]" class="form-select" multiple size="10" data-kw-select data-kw-limit="{{ $metaLimit }}">
                    @foreach ($keywords as $k)
                    <option value="{{ $k->id }}" {{ in_array((int) $k->id, old('meta_keyword_ids', $metaKeywordIds ?? []) ?? []) ? 'selected' : '' }}>
                        {{ $k->name }}
                    </option>
                    @endforeach
                </select>

                <div class="d-flex align-items-center justify-content-between gap-2 mt-2">
                    <div class="small text-muted">حد أقصى {{ $metaLimit }} | اضغط Ctrl/⌘ للاختيار المتعدد</div>
                    <button class="btn btn-sm btn-outline-danger" type="button" data-kw-clear-selection>إزالة التحديد</button>
                </div>

                <div class="text-danger small mt-2 d-none" data-kw-limit-msg>
                    تم الوصول للحد الأقصى.
                </div>

                <div class="kw-badges d-flex flex-wrap gap-1 mt-2" data-kw-badges></div>

                @error('meta_keyword_ids')
                <div class="text-danger small mt-2">{{ $message }}</div>
                @enderror

                <div class="mt-3">
                    <label class="form-label" for="meta_keyword_names">إضافة كلمات جديدة</label>
                    <textarea class="form-control" id="meta_keyword_names" name="meta_keyword_names" rows="3" placeholder="افصل بفاصلة أو سطر جديد&#10;مثال: دهانات داخلية, بديل الرخام">{{ old('meta_keyword_names') }}</textarea>
                    <div class="form-text">يمكنك استخدام فاصلة عربية/إنجليزية أو سطر جديد.</div>
                    <div class="list-group mt-2 d-none" data-kw-suggest></div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card p-3" data-kw-scope="content">
                <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
                    <div class="fw-bold">وسوم المحتوى</div>
                    <div class="badge text-bg-secondary" data-kw-count></div>
                </div>

                <div class="input-group input-group-sm mb-2">
                    <span class="input-group-text">بحث</span>
                    <input class="form-control" type="text" autocomplete="off" placeholder="اكتب للبحث داخل الوسوم" data-kw-search>
                    <button class="btn btn-outline-secondary" type="button" data-kw-clear-search>مسح</button>
                </div>

                <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
                    <div class="small text-muted">نتائج البحث تظهر داخل القائمة</div>
                    <button class="btn btn-sm btn-outline-primary" type="button" data-kw-select-visible>تحديد الظاهر</button>
                </div>

                <select name="content_keyword_ids[]" class="form-select" multiple size="10" data-kw-select data-kw-limit="{{ $contentLimit }}">
                    @foreach ($keywords as $k)
                    <option value="{{ $k->id }}" {{ in_array((int) $k->id, old('content_keyword_ids', $contentKeywordIds ?? []) ?? []) ? 'selected' : '' }}>
                        {{ $k->name }}
                    </option>
                    @endforeach
                </select>

                <div class="d-flex align-items-center justify-content-between gap-2 mt-2">
                    <div class="small text-muted">حد أقصى {{ $contentLimit }} | اضغط Ctrl/⌘ للاختيار المتعدد</div>
                    <button class="btn btn-sm btn-outline-danger" type="button" data-kw-clear-selection>إزالة التحديد</button>
                </div>

                <div class="text-danger small mt-2 d-none" data-kw-limit-msg>
                    تم الوصول للحد الأقصى.
                </div>

                <div class="kw-badges d-flex flex-wrap gap-1 mt-2" data-kw-badges></div>

                @error('content_keyword_ids')
                <div class="text-danger small mt-2">{{ $message }}</div>
                @enderror

                <div class="mt-3">
                    <label class="form-label" for="content_keyword_names">إضافة كلمات جديدة</label>
                    <textarea class="form-control" id="content_keyword_names" name="content_keyword_names" rows="3" placeholder="افصل بفاصلة أو سطر جديد&#10;مثال: تشطيبات, ورق جدران">{{ old('content_keyword_names') }}</textarea>
                    <div class="form-text">يمكنك استخدام فاصلة عربية/إنجليزية أو سطر جديد.</div>
                    <div class="list-group mt-2 d-none" data-kw-suggest></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card p-3 mt-3">
        <div class="fw-bold mb-2">أولوية وترتيب الكلمات</div>
        <div class="text-muted small mb-3" data-empty>
            اختر كلمات من القوائم بالأعلى لتظهر هنا، ثم حدد الأساسية والوزن.
        </div>

        <div class="table-responsive {{ $selectedIds->count() > 0 ? '' : 'd-none' }}" data-table-wrap>
            <table class="table table-sm align-middle mb-0">
                <thead>
                    <tr>
                        <th>الكلمة</th>
                        <th style="width:140px">أساسية</th>
                        <th style="width:160px">الوزن</th>
                    </tr>
                </thead>
                <tbody data-tbody>
                    @foreach ($selectedIds as $id)
                    @php $k = $keywordsById->get($id); @endphp
                    @if ($k)
                    <tr data-kw-row="{{ (int) $k->id }}">
                        <td class="fw-bold">{{ $k->name }}</td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="keyword_primary_ids[]" value="{{ $k->id }}" id="kw_primary_{{ $kwBlockId }}_{{ $k->id }}" {{ in_array((int) $k->id, $primarySelected, true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="kw_primary_{{ $kwBlockId }}_{{ $k->id }}">نعم</label>
                            </div>
                        </td>
                        <td>
                            <input class="form-control form-control-sm" type="number" min="0" max="65535" name="keyword_weights[{{ $k->id }}]" value="{{ (int) ($weightsSelected[$k->id] ?? 0) }}">
                        </td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        (function() {
            const init = function() {
                const block = document.getElementById(@json($kwBlockId));
                if (!block) return;

                const form = block.closest('form') || document;
                const metaSelect = block.querySelector('select[name="meta_keyword_ids[]"]');
                const contentSelect = block.querySelector('select[name="content_keyword_ids[]"]');
                const tbody = block.querySelector('[data-tbody]');
                const emptyHint = block.querySelector('[data-empty]');
                const tableWrap = block.querySelector('[data-table-wrap]');

                if (!tbody || !emptyHint || !tableWrap || !metaSelect || !contentSelect) return;

                function normalize(s) {
                    return (s || '').toString().trim().toLowerCase();
                }

                function selectedIdsFromSelect(selectEl) {
                    return Array.from(selectEl.selectedOptions || [])
                        .map(opt => parseInt(opt.value, 10))
                        .filter(v => !Number.isNaN(v) && v > 0);
                }

                function optionTextById(selectEl) {
                    const map = new Map();
                    Array.from(selectEl.options || []).forEach(opt => {
                        const id = parseInt(opt.value, 10);
                        if (!Number.isNaN(id) && id > 0) {
                            map.set(id, (opt.textContent || '').trim());
                        }
                    });
                    return map;
                }

                function setupPicker(scope) {
                    const card = block.querySelector('[data-kw-scope="' + scope + '"]');
                    if (!card) return null;

                    const selectEl = card.querySelector('[data-kw-select]');
                    const limit = parseInt(selectEl?.getAttribute('data-kw-limit') || '0', 10);
                    const searchEl = card.querySelector('[data-kw-search]');
                    const clearSearchBtn = card.querySelector('[data-kw-clear-search]');
                    const clearSelectionBtn = card.querySelector('[data-kw-clear-selection]');
                    const badgesEl = card.querySelector('[data-kw-badges]');
                    const countEl = card.querySelector('[data-kw-count]');
                    const limitMsg = card.querySelector('[data-kw-limit-msg]');
                    const suggestEl = card.querySelector('[data-kw-suggest]');
                    const addField = card.querySelector('textarea[name="' + scope + '_keyword_names"]');
                    const selectVisibleBtn = card.querySelector('[data-kw-select-visible]');

                    if (!selectEl || !searchEl || !clearSearchBtn || !clearSelectionBtn || !badgesEl || !countEl || !limitMsg || !suggestEl || !addField || !selectVisibleBtn) return null;

                    let prevSelected = new Set(selectedIdsFromSelect(selectEl));

                    function updateCount() {
                        const count = selectedIdsFromSelect(selectEl).length;
                        countEl.textContent = count + '/' + limit;
                        countEl.classList.toggle('text-bg-danger', count >= limit);
                        countEl.classList.toggle('text-bg-secondary', count < limit);
                    }

                    function renderBadges() {
                        badgesEl.replaceChildren();
                        const selected = Array.from(selectEl.selectedOptions || []);
                        if (selected.length === 0) return;

                        selected.forEach(opt => {
                            const id = parseInt(opt.value, 10);
                            if (Number.isNaN(id) || id <= 0) return;

                            const btn = document.createElement('button');
                            btn.type = 'button';
                            btn.className = 'btn btn-sm btn-outline-secondary kw-badge';

                            const label = document.createElement('span');
                            label.textContent = (opt.textContent || '').trim();

                            const x = document.createElement('span');
                            x.className = 'kw-x';
                            x.setAttribute('aria-hidden', 'true');
                            x.textContent = '×';

                            btn.appendChild(label);
                            btn.appendChild(x);

                            btn.addEventListener('click', function() {
                                opt.selected = false;
                                selectEl.dispatchEvent(new Event('change', {
                                    bubbles: true
                                }));
                            });

                            badgesEl.appendChild(btn);
                        });
                    }

                    function enforceLimit() {
                        if (!Number.isFinite(limit) || limit <= 0) {
                            prevSelected = new Set(selectedIdsFromSelect(selectEl));
                            limitMsg.classList.add('d-none');
                            return;
                        }

                        const current = new Set(selectedIdsFromSelect(selectEl));
                        if (current.size <= limit) {
                            prevSelected = current;
                            limitMsg.classList.add('d-none');
                            return;
                        }

                        Array.from(selectEl.options || []).forEach(opt => {
                            const id = parseInt(opt.value, 10);
                            if (!Number.isNaN(id)) {
                                opt.selected = prevSelected.has(id);
                            }
                        });

                        limitMsg.classList.remove('d-none');
                    }

                    function trimToLimit() {
                        if (!Number.isFinite(limit) || limit <= 0) return;
                        const selected = Array.from(selectEl.selectedOptions || []);
                        if (selected.length <= limit) {
                            prevSelected = new Set(selectedIdsFromSelect(selectEl));
                            limitMsg.classList.add('d-none');
                            return;
                        }

                        selected.forEach((opt, idx) => {
                            if (idx >= limit) opt.selected = false;
                        });

                        prevSelected = new Set(selectedIdsFromSelect(selectEl));
                        limitMsg.classList.remove('d-none');
                    }

                    function filterOptions() {
                        const q = normalize(searchEl.value);
                        Array.from(selectEl.options || []).forEach(opt => {
                            const text = normalize(opt.textContent || '');
                            opt.hidden = q !== '' && !text.includes(q);
                        });
                    }

                    function clearSearch() {
                        searchEl.value = '';
                        Array.from(selectEl.options || []).forEach(opt => {
                            opt.hidden = false;
                        });
                    }

                    function clearSelection() {
                        Array.from(selectEl.options || []).forEach(opt => {
                            opt.selected = false;
                        });
                        selectEl.dispatchEvent(new Event('change', {
                            bubbles: true
                        }));
                    }

                    function selectVisible() {
                        if (!Number.isFinite(limit) || limit <= 0) {
                            Array.from(selectEl.options || []).forEach(opt => {
                                if (!opt.hidden) opt.selected = true;
                            });
                            selectEl.dispatchEvent(new Event('change', {
                                bubbles: true
                            }));
                            return;
                        }

                        const current = new Set(selectedIdsFromSelect(selectEl));
                        if (current.size >= limit) return;

                        Array.from(selectEl.options || []).forEach(opt => {
                            if (opt.hidden) return;
                            const id = parseInt(opt.value, 10);
                            if (Number.isNaN(id) || id <= 0) return;
                            if (current.size >= limit) return;
                            if (!current.has(id)) {
                                opt.selected = true;
                                current.add(id);
                            }
                        });

                        selectEl.dispatchEvent(new Event('change', {
                            bubbles: true
                        }));
                    }

                    function currentToken(text) {
                        const s = (text || '').toString();
                        let i = -1;
                        [',', '\n', '\r', ';', '|', '،'].forEach(ch => {
                            i = Math.max(i, s.lastIndexOf(ch));
                        });
                        return s.slice(i + 1).trim();
                    }

                    function renderSuggestions() {
                        suggestEl.replaceChildren();
                        const token = currentToken(addField.value);
                        const q = normalize(token);

                        if (q.length < 2) {
                            suggestEl.classList.add('d-none');
                            return;
                        }

                        const options = Array.from(selectEl.options || [])
                            .map(opt => ({
                                id: parseInt(opt.value, 10),
                                label: (opt.textContent || '').trim(),
                                n: normalize(opt.textContent || ''),
                                opt
                            }))
                            .filter(x => !Number.isNaN(x.id) && x.id > 0 && x.n.includes(q))
                            .slice(0, 8);

                        if (options.length === 0) {
                            suggestEl.classList.add('d-none');
                            return;
                        }

                        options.forEach(item => {
                            const btn = document.createElement('button');
                            btn.type = 'button';
                            btn.className = 'list-group-item list-group-item-action py-2';
                            btn.textContent = item.label;
                            btn.addEventListener('click', function() {
                                item.opt.selected = true;
                                selectEl.dispatchEvent(new Event('change', {
                                    bubbles: true
                                }));
                                suggestEl.classList.add('d-none');
                            });
                            suggestEl.appendChild(btn);
                        });

                        suggestEl.classList.remove('d-none');
                    }

                    searchEl.addEventListener('input', filterOptions);
                    clearSearchBtn.addEventListener('click', function() {
                        clearSearch();
                        searchEl.focus();
                    });
                    clearSelectionBtn.addEventListener('click', clearSelection);
                    selectVisibleBtn.addEventListener('click', selectVisible);

                    selectEl.addEventListener('change', function() {
                        enforceLimit();
                        updateCount();
                        renderBadges();
                        renderPriorityTable();
                    });

                    addField.addEventListener('input', renderSuggestions);
                    addField.addEventListener('blur', function() {
                        window.setTimeout(function() {
                            suggestEl.classList.add('d-none');
                        }, 150);
                    });

                    trimToLimit();
                    updateCount();
                    renderBadges();
                    renderSuggestions();
                    return {
                        selectEl,
                        limit
                    };
                }

                const metaPicker = setupPicker('meta');
                const contentPicker = setupPicker('content');

                function snapshotPriorityState() {
                    const primary = new Set();
                    const weights = new Map();

                    tbody.querySelectorAll('input[name="keyword_primary_ids[]"]').forEach(el => {
                        const id = parseInt(el.value, 10);
                        if (!Number.isNaN(id) && el.checked) primary.add(id);
                    });

                    tbody.querySelectorAll('input[name^="keyword_weights["]').forEach(el => {
                        const name = el.getAttribute('name') || '';
                        const match = name.match(/^keyword_weights\[(\d+)\]$/);
                        if (!match) return;
                        const id = parseInt(match[1], 10);
                        if (Number.isNaN(id)) return;
                        const v = parseInt(el.value, 10);
                        weights.set(id, Number.isNaN(v) ? 0 : v);
                    });

                    return {
                        primary,
                        weights
                    };
                }

                function renderPriorityTable() {
                    const optionText = new Map([
                        ...optionTextById(metaSelect).entries(),
                        ...optionTextById(contentSelect).entries(),
                    ]);

                    const selectedIds = Array.from(new Set([
                        ...selectedIdsFromSelect(metaSelect),
                        ...selectedIdsFromSelect(contentSelect),
                    ]));

                    const state = snapshotPriorityState();
                    tbody.replaceChildren();

                    if (selectedIds.length === 0) {
                        tableWrap.classList.add('d-none');
                        emptyHint.classList.remove('d-none');
                        return;
                    }

                    tableWrap.classList.remove('d-none');
                    emptyHint.classList.add('d-none');

                    selectedIds.forEach(id => {
                        const label = optionText.get(id) || ('#' + id);
                        const tr = document.createElement('tr');
                        tr.setAttribute('data-kw-row', String(id));

                        const nameTd = document.createElement('td');
                        nameTd.className = 'fw-bold';
                        nameTd.textContent = label;

                        const primaryTd = document.createElement('td');
                        const checkWrap = document.createElement('div');
                        checkWrap.className = 'form-check';
                        const check = document.createElement('input');
                        check.className = 'form-check-input';
                        check.type = 'checkbox';
                        check.name = 'keyword_primary_ids[]';
                        check.value = String(id);
                        check.id = block.id + '_primary_' + String(id);
                        check.checked = state.primary.has(id);
                        const checkLabel = document.createElement('label');
                        checkLabel.className = 'form-check-label';
                        checkLabel.setAttribute('for', check.id);
                        checkLabel.textContent = 'نعم';
                        checkWrap.appendChild(check);
                        checkWrap.appendChild(checkLabel);
                        primaryTd.appendChild(checkWrap);

                        const weightTd = document.createElement('td');
                        const wInput = document.createElement('input');
                        wInput.className = 'form-control form-control-sm';
                        wInput.type = 'number';
                        wInput.min = '0';
                        wInput.max = '65535';
                        wInput.name = 'keyword_weights[' + String(id) + ']';
                        wInput.value = String(state.weights.has(id) ? state.weights.get(id) : 0);
                        weightTd.appendChild(wInput);

                        tr.appendChild(nameTd);
                        tr.appendChild(primaryTd);
                        tr.appendChild(weightTd);
                        tbody.appendChild(tr);
                    });
                }

                metaSelect.addEventListener('change', renderPriorityTable);
                contentSelect.addEventListener('change', renderPriorityTable);
                renderPriorityTable();
            };

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', init);
            } else {
                init();
            }
        })();
    </script>

</div>