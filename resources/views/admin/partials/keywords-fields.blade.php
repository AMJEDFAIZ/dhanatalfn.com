<hr class="my-3">
<h3 class="h6 fw-bold mb-3">الكلمات المفتاحية والوسوم</h3>

<div class="row g-3">
    <div class="col-lg-6">
        <div class="card p-3">
            <div class="fw-bold mb-2">كلمات SEO (حد أقصى {{ \App\Services\KeywordService::META_LIMIT }})</div>
            <select name="meta_keyword_ids[]" class="form-select" multiple size="10">
                @foreach ($keywords as $k)
                <option value="{{ $k->id }}" {{ in_array((int) $k->id, old('meta_keyword_ids', $metaKeywordIds ?? []) ?? []) ? 'selected' : '' }}>
                    {{ $k->name }}
                </option>
                @endforeach
            </select>
            @error('meta_keyword_ids')
            <div class="text-danger small mt-2">{{ $message }}</div>
            @enderror

            <div class="mt-3">
                <label class="form-label" for="meta_keyword_names">إضافة كلمات جديدة (مفصولة بفواصل)</label>
                <input class="form-control" id="meta_keyword_names" name="meta_keyword_names" type="text" value="{{ old('meta_keyword_names') }}" placeholder="مثال: دهانات داخلية, بديل الرخام">
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card p-3">
            <div class="fw-bold mb-2">وسوم المحتوى (حد أقصى {{ \App\Services\KeywordService::CONTENT_LIMIT }})</div>
            <select name="content_keyword_ids[]" class="form-select" multiple size="10">
                @foreach ($keywords as $k)
                <option value="{{ $k->id }}" {{ in_array((int) $k->id, old('content_keyword_ids', $contentKeywordIds ?? []) ?? []) ? 'selected' : '' }}>
                    {{ $k->name }}
                </option>
                @endforeach
            </select>
            @error('content_keyword_ids')
            <div class="text-danger small mt-2">{{ $message }}</div>
            @enderror

            <div class="mt-3">
                <label class="form-label" for="content_keyword_names">إضافة كلمات جديدة (مفصولة بفواصل)</label>
                <input class="form-control" id="content_keyword_names" name="content_keyword_names" type="text" value="{{ old('content_keyword_names') }}" placeholder="مثال: تشطيبات, ورق جدران">
            </div>
        </div>
    </div>
</div>

@php
$kwBlockId = 'kw_block_' . uniqid();
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

<div class="card p-3 mt-3" id="{{ $kwBlockId }}">
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
    document.addEventListener('DOMContentLoaded', function() {
        const block = document.getElementById(@json($kwBlockId));
        if (!block) return;

        const form = block.closest('form') || document;
        const metaSelect = form.querySelector('select[name="meta_keyword_ids[]"]');
        const contentSelect = form.querySelector('select[name="content_keyword_ids[]"]');
        const tbody = block.querySelector('[data-tbody]');
        const emptyHint = block.querySelector('[data-empty]');
        const tableWrap = block.querySelector('[data-table-wrap]');

        if (!tbody || !emptyHint || !tableWrap || !metaSelect || !contentSelect) return;

        function collectOptionTextMap(selectEl) {
            const map = new Map();
            Array.from(selectEl.options || []).forEach(opt => {
                const id = parseInt(opt.value, 10);
                if (!Number.isNaN(id) && id > 0) {
                    map.set(id, (opt.textContent || '').trim());
                }
            });
            return map;
        }

        function collectSelectedIds(selectEl) {
            return Array.from(selectEl.selectedOptions || [])
                .map(opt => parseInt(opt.value, 10))
                .filter(v => !Number.isNaN(v) && v > 0);
        }

        function snapshotState() {
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

        function render() {
            const optionText = new Map([
                ...collectOptionTextMap(metaSelect).entries(),
                ...collectOptionTextMap(contentSelect).entries(),
            ]);

            const selectedIds = Array.from(new Set([
                ...collectSelectedIds(metaSelect),
                ...collectSelectedIds(contentSelect),
            ]));

            const state = snapshotState();

            tbody.innerHTML = '';

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

                const checked = state.primary.has(id);
                const w = state.weights.has(id) ? state.weights.get(id) : 0;

                tr.innerHTML = `
                    <td class="fw-bold"></td>
                    <td>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="keyword_primary_ids[]" value="${id}" id="${block.id}_primary_${id}" ${checked ? 'checked' : ''}>
                            <label class="form-check-label" for="${block.id}_primary_${id}">نعم</label>
                        </div>
                    </td>
                    <td>
                        <input class="form-control form-control-sm" type="number" min="0" max="65535" name="keyword_weights[${id}]" value="${w}">
                    </td>
                `;

                tr.querySelector('td').textContent = label;
                tbody.appendChild(tr);
            });
        }

        metaSelect.addEventListener('change', render);
        contentSelect.addEventListener('change', render);

        render();
    });
</script>