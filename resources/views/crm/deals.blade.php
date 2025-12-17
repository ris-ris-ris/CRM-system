<x-app-layout>
    <div class="py-6">
        <div class="px-6 space-y-6">
            <div class="flex items-start justify-between gap-4 flex-wrap">
                <div>
                    <h1 class="text-2xl font-extrabold text-gray-900 dark:text-gray-100">Сделки</h1>
                </div>
                <div class="flex items-center gap-3 flex-wrap justify-end">
                    <div class="relative">
                        <input type="text" id="search" placeholder="Поиск по названию" class="pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-xl w-72 focus:ring-2 focus:ring-orange-400 focus:border-orange-400 transition-all shadow-sm bg-white dark:bg-gray-800">
                        <svg class="w-5 h-5 absolute left-3 top-2.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z"/>
                        </svg>
                    </div>
                    @if(auth()->user()->is_admin || auth()->user()->role === 'manager')
                    <button onclick="openModal()" class="px-4 py-2 rounded-xl text-white font-semibold transition-all shadow-lg" style="background: linear-gradient(135deg, #ef4444 0%, #f97316 100%); box-shadow: 0 10px 40px rgba(239, 68, 68, 0.35); border: none;">+ Сделка</button>
                    @endif
                </div>
            </div>

            <!-- Filters -->
            <div class="premium-card rounded-2xl p-4 border border-gray-200 dark:border-gray-700">
                <div class="grid grid-cols-1 md:grid-cols-6 gap-3">
                    <select id="stageFilter" class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-sm font-semibold">
                        <option value="">Все стадии</option>
                    </select>
                    <select id="companyFilter" class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-sm font-semibold">
                        <option value="">Все компании</option>
                    </select>
                    @if(auth()->user()->is_admin || auth()->user()->role === 'manager')
                    <select id="userFilter" class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-sm font-semibold">
                        <option value="">Все исполнители</option>
                    </select>
                    @else
                    <div class="hidden md:block"></div>
                    @endif
                    <input id="minAmount" type="number" inputmode="decimal" placeholder="Сумма от" class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-sm">
                    <input id="maxAmount" type="number" inputmode="decimal" placeholder="Сумма до" class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-sm">
                    <div class="flex gap-2">
                        <button onclick="resetFilters()" class="flex-1 px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-600 text-sm font-semibold hover:bg-gray-50 dark:hover:bg-gray-800">Сброс</button>
                        <button onclick="load(1)" class="flex-1 px-3 py-2 rounded-xl text-white text-sm font-semibold" style="background: linear-gradient(135deg, #ef4444 0%, #f97316 100%); border:none;">Применить</button>
                    </div>
                </div>
            </div>

            <!-- KPI -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="premium-card rounded-2xl p-5 border border-gray-200 dark:border-gray-700">
                    <div class="text-xs font-semibold text-slate-500 dark:text-slate-300 uppercase">Всего</div>
                    <div class="text-2xl font-extrabold text-gray-900 dark:text-gray-100 mt-1" id="kpiTotal">—</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">сделок</div>
                </div>
            </div>

            <div class="premium-card no-card-zoom sm:rounded-2xl p-0 border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between gap-3 flex-wrap">
                    <div class="text-sm text-gray-600 dark:text-gray-400">Нажми на сделку, чтобы открыть карточку.</div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800/60">
                            <tr>
                                <th onclick="sortBy('title')" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase cursor-pointer select-none hover:bg-gray-100/60">
                                    Сделка <span id="sort-title-icon"></span>
                                </th>
                                <th onclick="sortBy('company')" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase cursor-pointer select-none hover:bg-gray-100/60">
                                    Компания <span id="sort-company-icon"></span>
                                </th>
                                <th onclick="sortBy('stage')" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase cursor-pointer select-none hover:bg-gray-100/60">
                                    Стадия <span id="sort-stage-icon"></span>
                                </th>
                                <th onclick="sortBy('amount')" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase cursor-pointer select-none hover:bg-gray-100/60">
                                    Сумма <span id="sort-amount-icon"></span>
                                </th>
                                <th onclick="sortBy('expected_close_date')" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase cursor-pointer select-none hover:bg-gray-100/60">
                                    Закрытие <span id="sort-expected_close_date-icon"></span>
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">Действия</th>
                            </tr>
                        </thead>
                        <tbody id="list" class="bg-white dark:bg-gray-900/20 divide-y divide-gray-200 dark:divide-gray-700"></tbody>
                    </table>
                </div>

                <div id="pagination" class="p-4"></div>
            </div>
        </div>
    </div>

    <!-- Drawer -->
    <div id="drawerBackdrop" class="hidden fixed inset-0 bg-black/40 z-40" onclick="closeDrawer()"></div>
    <aside id="drawer" class="hidden fixed right-0 top-0 h-full w-full sm:w-[520px] z-50">
        <div class="h-full bg-white dark:bg-gray-900 border-l border-gray-200 dark:border-gray-700 shadow-2xl p-6 overflow-y-auto">
            <div class="flex items-start justify-between gap-3">
                <div class="min-w-0">
                    <div class="text-xs text-gray-500 dark:text-gray-400">Сделка</div>
                    <div id="drawerTitle" class="text-xl font-extrabold text-gray-900 dark:text-gray-100 truncate">—</div>
                    <div id="drawerMeta" class="text-sm text-gray-600 dark:text-gray-400 mt-1">—</div>
                </div>
                <button onclick="closeDrawer()" class="text-gray-500 hover:text-gray-800 dark:hover:text-gray-200 text-2xl leading-none">&times;</button>
            </div>

            <div class="mt-5 grid grid-cols-1 gap-3">
                <div class="grid grid-cols-2 gap-3">
                    <div class="premium-card rounded-2xl p-4 border border-gray-200 dark:border-gray-700">
                        <div class="text-xs text-gray-500 dark:text-gray-400">Стадия</div>
                        <div id="drawerStage" class="text-sm font-bold text-gray-900 dark:text-gray-100 mt-1">—</div>
                    </div>
                    <div class="premium-card rounded-2xl p-4 border border-gray-200 dark:border-gray-700">
                        <div class="text-xs text-gray-500 dark:text-gray-400">Сумма</div>
                        <div id="drawerAmount" class="text-sm font-bold text-gray-900 dark:text-gray-100 mt-1">—</div>
                    </div>
                </div>
                <div class="premium-card rounded-2xl p-4 border border-gray-200 dark:border-gray-700 space-y-2">
                    <div class="flex items-center justify-between gap-3">
                        <div class="text-sm text-gray-600 dark:text-gray-300">Компания</div>
                        <div id="drawerCompany" class="text-sm font-semibold text-gray-900 dark:text-gray-100 truncate">—</div>
                    </div>
                    <div class="flex items-center justify-between gap-3">
                        <div class="text-sm text-gray-600 dark:text-gray-300">Контакт</div>
                        <div id="drawerContact" class="text-sm font-semibold text-gray-900 dark:text-gray-100 truncate">—</div>
                    </div>
                    <div class="flex items-center justify-between gap-3">
                        <div class="text-sm text-gray-600 dark:text-gray-300">Дата закрытия</div>
                        <div id="drawerClose" class="text-sm font-semibold text-gray-900 dark:text-gray-100 truncate">—</div>
                    </div>
                </div>
                <div class="premium-card rounded-2xl p-4 border border-gray-200 dark:border-gray-700">
                    <div class="text-xs text-gray-500 dark:text-gray-400">Описание</div>
                    <div id="drawerDesc" class="text-sm text-gray-800 dark:text-gray-200 mt-1 whitespace-pre-wrap">—</div>
                </div>

                <div class="flex gap-2">
                    @if(auth()->user()->is_admin || auth()->user()->role === 'manager')
                    <button id="drawerEditBtn" onclick="" class="px-4 py-2 rounded-xl text-white font-semibold" style="background: linear-gradient(135deg, #ef4444 0%, #f97316 100%); border:none;">Изменить</button>
                    <button id="drawerDeleteBtn" onclick="" class="px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-600 font-semibold hover:bg-gray-50 dark:hover:bg-gray-800">Удалить</button>
                    @endif
                </div>
            </div>
        </div>
    </aside>

    <!-- Modal -->
    <div id="modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="modal-premium p-6 w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <h3 class="text-xl font-bold mb-4 text-gray-900 dark:text-gray-100" id="modalTitle">Добавить сделку</h3>
            <form id="form" class="space-y-4">
                <input type="hidden" id="editId">
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Название *</label>
                        <input type="text" name="title" id="title" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Компания</label>
                        <select name="company_id" id="company_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">Выберите компанию</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Контакт</label>
                        <select name="contact_id" id="contact_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">Выберите контакт</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Стадия</label>
                        <select name="stage_id" id="stage_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">Выберите стадию</option>
                        </select>
                    </div>
                    @if(auth()->user()->is_admin || auth()->user()->role === 'manager')
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Назначить на</label>
                        <select name="user_id" id="user_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">Выберите пользователя</option>
                        </select>
                    </div>
                    @endif
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Сумма</label>
                        <input type="number" name="amount" id="amount" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Валюта</label>
                        <input type="text" name="currency" id="currency" value="RUB" maxlength="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Дата закрытия</label>
                        <input type="date" name="expected_close_date" id="expected_close_date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Описание</label>
                        <textarea name="description" id="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 rounded-lg crm-btn-secondary">Отмена</button>
                    <button type="submit" class="px-4 py-2 rounded-lg text-white font-semibold" style="background: linear-gradient(135deg, #ef4444 0%, #f97316 100%); box-shadow: 0 10px 40px rgba(239, 68, 68, 0.4); border: none;">Сохранить</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    let currentPage = 1;
    let companies = [];
    let contacts = [];
    let stages = [];
    let users = [];
    let lastDeals = [];

    const moneyFmt = new Intl.NumberFormat('ru-RU');
    function fmtMoney(amount, currency) {
        if (amount === null || amount === undefined || amount === '') return '—';
        return `${moneyFmt.format(amount)} ${(currency || 'RUB')}`;
    }
    function stageName(d) { return (d && d.stage && d.stage.name) ? String(d.stage.name) : ''; }
    function isWon(name) { name = (name || '').toLowerCase(); return name === 'won' || name === 'выиграно'; }
    function isLost(name) { name = (name || '').toLowerCase(); return name === 'lost' || name === 'проиграно'; }
    function stageBadgeClass(name) {
        if (isWon(name)) return 'bg-green-100 text-green-800 dark:bg-green-500/15 dark:text-green-200';
        if (isLost(name)) return 'bg-red-100 text-red-800 dark:bg-red-500/15 dark:text-red-200';
        return 'bg-blue-100 text-blue-800 dark:bg-blue-500/15 dark:text-blue-200';
    }
    
    async function loadCompanies() {
        const r = await fetch('/api/companies?per_page=1000');
        const j = await r.json();
        companies = j.data;
        const select = document.getElementById('company_id');
        select.innerHTML = '<option value="">Выберите компанию</option>' + 
            companies.map(c => `<option value="${c.id}">${c.name}</option>`).join('');

        const filter = document.getElementById('companyFilter');
        if (filter) {
            filter.innerHTML = '<option value="">Все компании</option>' + companies.map(c => `<option value="${c.id}">${c.name}</option>`).join('');
        }
    }
    
    async function loadContacts() {
        const r = await fetch('/api/contacts?per_page=1000');
        const j = await r.json();
        contacts = j.data;
        const select = document.getElementById('contact_id');
        select.innerHTML = '<option value="">Выберите контакт</option>' + 
            contacts.map(c => `<option value="${c.id}">${c.first_name} ${c.last_name || ''}</option>`).join('');
    }
    
    async function loadStages() {
        const r = await fetch('/api/stages');
        const j = await r.json();
        stages = Array.isArray(j) ? j : (j.data || []);
        const select = document.getElementById('stage_id');
        select.innerHTML = '<option value="">Выберите стадию</option>' + 
            stages.map(s => `<option value="${s.id}">${s.name}</option>`).join('');

        const filter = document.getElementById('stageFilter');
        if (filter) {
            filter.innerHTML = '<option value="">Все стадии</option>' + stages.map(s => `<option value="${s.id}">${s.name}</option>`).join('');
        }
    }
    
    @if(auth()->user()->is_admin || auth()->user()->role === 'manager')
    async function loadUsers() {
        const r = await fetch('/api/users');
        const j = await r.json();
        users = Array.isArray(j) ? j : (j.data || []);
        const select = document.getElementById('user_id');
        if (select) {
            select.innerHTML = '<option value="">Выберите пользователя</option>' + 
                users.map(u => `<option value="${u.id}">${u.name}</option>`).join('');
        }

        const filter = document.getElementById('userFilter');
        if (filter) {
            filter.innerHTML = '<option value="">Все исполнители</option>' + users.map(u => `<option value="${u.id}">${u.name}</option>`).join('');
        }
    }
    @endif
    
    let sortField = null;
    let sortDirection = 'asc';

    function resetFilters() {
        const ids = ['stageFilter', 'companyFilter', 'minAmount', 'maxAmount', 'userFilter'];
        ids.forEach((id) => {
            const el = document.getElementById(id);
            if (!el) return;
            if (el.tagName === 'SELECT') el.value = '';
            else el.value = '';
        });
        load(1);
    }
    
    async function load(page = 1) {
        try {
            const search = document.getElementById('search').value;
            const sortParam = sortField ? `&sort_by=${sortField}&sort_order=${sortDirection}` : '';
            const stageId = document.getElementById('stageFilter')?.value || '';
            const companyId = document.getElementById('companyFilter')?.value || '';
            const minAmount = document.getElementById('minAmount')?.value || '';
            const maxAmount = document.getElementById('maxAmount')?.value || '';
            const userId = document.getElementById('userFilter')?.value || '';

            const url = `/api/deals?page=${page}&per_page=100` +
                `${search ? '&q=' + encodeURIComponent(search) : ''}` +
                `${stageId ? '&stage_id=' + encodeURIComponent(stageId) : ''}` +
                `${companyId ? '&company_id=' + encodeURIComponent(companyId) : ''}` +
                `${userId ? '&user_id=' + encodeURIComponent(userId) : ''}` +
                `${minAmount !== '' ? '&min_amount=' + encodeURIComponent(minAmount) : ''}` +
                `${maxAmount !== '' ? '&max_amount=' + encodeURIComponent(maxAmount) : ''}` +
                `${sortParam}`;
            const r = await fetch(url, {
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                credentials: 'same-origin'
            });
            if (!r.ok) {
                const errorText = await r.text();
                console.error('Error loading deals:', r.status, errorText);
                const tbody = document.querySelector('#list');
                tbody.innerHTML = `<tr><td colspan="6" class="px-6 py-6 text-center text-red-500">Ошибка загрузки: ${r.status}</td></tr>`;
                return;
            }
            const j = await r.json();
            const tbody = document.querySelector('#list');
            lastDeals = j.data || [];

            // KPI
            document.getElementById('kpiTotal').textContent = (j.total ?? '—');

            if (!lastDeals.length) {
                tbody.innerHTML = `<tr><td colspan="6" class="px-6 py-10 text-center text-gray-500">Нет сделок. Попробуй изменить фильтры.</td></tr>`;
            } else {
                tbody.innerHTML = lastDeals.map(d => {
                    const st = stageName(d) || '—';
                    const close = d.expected_close_date ? new Date(d.expected_close_date).toLocaleDateString('ru-RU') : '—';
                    return `
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 cursor-pointer" onclick="openDrawer(${d.id})">
                            <td class="px-6 py-4">
                                <div class="min-w-0">
                                    <div class="text-sm font-bold text-gray-900 dark:text-gray-100 truncate">${d.title || '—'}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 truncate">${
                                        (() => {
                                            const from = d.contact ? (d.contact.first_name + ' ' + (d.contact.last_name || '')).trim()
                                                : (d.company ? d.company.name : '');
                                            const to = (d.user && d.user.name) ? d.user.name : '';
                                            const parts = [];
                                            if (from) parts.push(`От: ${from}`);
                                            if (to) parts.push(`Кому: ${to}`);
                                            return parts.length ? parts.join(' • ') : '—';
                                        })()
                                    }</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">${d.company ? d.company.name : '—'}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="px-2 py-1 rounded-full text-xs font-bold ${stageBadgeClass(st)}">${st}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 dark:text-gray-100">${fmtMoney(d.amount, d.currency)}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">${close}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm" onclick="event.stopPropagation()">
                                @if(auth()->user()->is_admin || auth()->user()->role === 'manager')
                                <button onclick="edit(${d.id})" class="px-3 py-1.5 rounded-lg bg-blue-100 text-blue-800 hover:bg-blue-200 dark:bg-blue-500/15 dark:text-blue-200 dark:hover:bg-blue-500/25 font-semibold mr-2">Изм.</button>
                                <button onclick="del(${d.id})" class="px-3 py-1.5 rounded-lg bg-red-100 text-red-800 hover:bg-red-200 dark:bg-red-500/15 dark:text-red-200 dark:hover:bg-red-500/25 font-semibold">Удал.</button>
                                @endif
                            </td>
                        </tr>
                    `;
                }).join('');
            }
        } catch (error) {
            console.error('Error:', error);
            const tbody = document.querySelector('#list');
            tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-6 text-center text-red-500">Ошибка загрузки данных</td></tr>';
        }
        
        const pagination = document.getElementById('pagination');
        if (j && j.last_page > 1) {
            let html = '<div class="flex gap-2 justify-center">';
            if (j.current_page > 1) {
                html += `<button onclick="load(${j.current_page - 1})" class="px-3 py-1 border rounded">Назад</button>`;
            }
            html += `<span class="px-3 py-1">Страница ${j.current_page} из ${j.last_page}</span>`;
            if (j.current_page < j.last_page) {
                html += `<button onclick="load(${j.current_page + 1})" class="px-3 py-1 border rounded">Вперед</button>`;
            }
            html += '</div>';
            pagination.innerHTML = html;
        } else {
            pagination.innerHTML = '';
        }
        currentPage = page;
        updateSortIcons();
    }

    function openDrawer(id) {
        const d = lastDeals.find(x => String(x.id) === String(id));
        if (!d) return;
        document.getElementById('drawerTitle').textContent = d.title || '—';
        const from = d.contact ? (d.contact.first_name + ' ' + (d.contact.last_name || '')).trim()
            : (d.company ? d.company.name : '');
        const to = (d.user && d.user.name) ? d.user.name : '';
        const metaParts = [`#${d.id}`];
        if (from) metaParts.push(`От: ${from}`);
        if (to) metaParts.push(`Кому: ${to}`);
        document.getElementById('drawerMeta').textContent = metaParts.join(' • ');
        const st = stageName(d) || '—';
        document.getElementById('drawerStage').innerHTML = `<span class="px-2 py-1 rounded-full text-xs font-bold ${stageBadgeClass(st)}">${st}</span>`;
        document.getElementById('drawerAmount').textContent = fmtMoney(d.amount, d.currency);
        document.getElementById('drawerCompany').textContent = d.company ? d.company.name : '—';
        document.getElementById('drawerContact').textContent = d.contact ? (d.contact.first_name + ' ' + (d.contact.last_name || '')) : '—';
        document.getElementById('drawerClose').textContent = d.expected_close_date ? new Date(d.expected_close_date).toLocaleDateString('ru-RU') : '—';
        document.getElementById('drawerDesc').textContent = d.description || '—';
        @if(auth()->user()->is_admin || auth()->user()->role === 'manager')
        document.getElementById('drawerEditBtn').onclick = () => { closeDrawer(); edit(d.id); };
        document.getElementById('drawerDeleteBtn').onclick = () => { closeDrawer(); del(d.id); };
        @endif
        document.getElementById('drawerBackdrop').classList.remove('hidden');
        document.getElementById('drawer').classList.remove('hidden');
    }

    function closeDrawer() {
        document.getElementById('drawerBackdrop').classList.add('hidden');
        document.getElementById('drawer').classList.add('hidden');
    }
    
    function sortBy(field) {
        if (sortField === field) {
            // Если кликнули по тому же полю - меняем направление
            sortDirection = sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            // Новое поле - начинаем с возрастания
            sortField = field;
            sortDirection = 'asc';
        }
        load(currentPage);
    }
    
    function updateSortIcons() {
        // Сбрасываем все иконки
        document.querySelectorAll('[id^="sort-"]').forEach(el => el.textContent = '');
        if (sortField) {
            const icon = document.getElementById(`sort-${sortField}-icon`);
            if (icon) {
                icon.textContent = sortDirection === 'asc' ? ' ↑' : ' ↓';
            }
        }
    }
    
    async function edit(id) {
        const r = await fetch(`/api/deals/${id}`);
        const d = await r.json();
        document.getElementById('editId').value = id;
        document.getElementById('title').value = d.title || '';
        document.getElementById('amount').value = d.amount || '';
        document.getElementById('currency').value = d.currency || 'RUB';
        document.getElementById('expected_close_date').value = d.expected_close_date || '';
        document.getElementById('description').value = d.description || '';
        document.getElementById('company_id').value = d.company_id || '';
        document.getElementById('contact_id').value = d.contact_id || '';
        document.getElementById('stage_id').value = d.stage_id || '';
        @if(auth()->user()->is_admin || auth()->user()->role === 'manager')
        if (document.getElementById('user_id')) {
            document.getElementById('user_id').value = d.user_id || '';
        }
        @endif
        document.getElementById('modalTitle').textContent = 'Изменить сделку';
        document.getElementById('modal').classList.remove('hidden');
    }
    
    function openModal() {
        document.getElementById('form').reset();
        document.getElementById('editId').value = '';
        document.getElementById('currency').value = 'RUB';
        document.getElementById('modalTitle').textContent = 'Добавить сделку';
        document.getElementById('modal').classList.remove('hidden');
    }
    
    function closeModal() {
        document.getElementById('modal').classList.add('hidden');
    }
    
    async function del(id) {
        if (!confirm('Удалить сделку?')) return;
        await fetch(`/api/deals/${id}`, {method: 'DELETE', headers: {'X-CSRF-TOKEN': csrf}});
        load(currentPage);
    }
    
    document.getElementById('form').onsubmit = async (e) => {
        e.preventDefault();
        const id = document.getElementById('editId').value;
        const url = id ? `/api/deals/${id}` : '/api/deals';
        const method = id ? 'PUT' : 'POST';

        // IMPORTANT: Use JSON body. PHP/Laravel won't reliably parse multipart FormData for PUT.
        const payload = {
            title: document.getElementById('title').value,
            company_id: document.getElementById('company_id').value || null,
            contact_id: document.getElementById('contact_id').value || null,
            stage_id: document.getElementById('stage_id').value || null,
            amount: document.getElementById('amount').value || null,
            currency: document.getElementById('currency').value || 'RUB',
            expected_close_date: document.getElementById('expected_close_date').value || null,
            description: document.getElementById('description').value || null,
        };

        // Admin/manager only: assign
        const userSel = document.getElementById('user_id');
        if (userSel && userSel.value) {
            payload.user_id = parseInt(userSel.value, 10);
        }

        try {
            const res = await fetch(url, {
                method,
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                credentials: 'same-origin',
                body: JSON.stringify(payload),
            });

            if (!res.ok) {
                let msg = `Ошибка ${res.status}`;
                try {
                    const j = await res.json();
                    msg = j.error || j.message || msg;
                } catch (_) {}
                alert(msg);
                return;
            }

            closeModal();
            load(currentPage);
        } catch (err) {
            console.error(err);
            alert('Ошибка сохранения сделки');
        }
    };
    
    document.getElementById('search').addEventListener('input', () => {
        clearTimeout(window.searchTimeout);
        window.searchTimeout = setTimeout(() => load(1), 500);
    });
    
    async function openDealDialog(dealId) {
        const dealResponse = await fetch(`/api/deals/${dealId}`);
        const deal = await dealResponse.json();
        
        const contactsResponse = await fetch(`/api/contacts?per_page=1000`);
        const contactsData = await contactsResponse.json();
        const dealContacts = (contactsData.data || contactsData).filter(c => c.company_id == deal.company_id);
        
        const primaryContact = dealContacts.find(c => c.email) || dealContacts[0];
        
        const emailsUrl = primaryContact
            ? `/api/emails?related_type=Deal&related_id=${dealId}&contact_id=${primaryContact.id}`
            : `/api/emails?related_type=Deal&related_id=${dealId}`;
        const emailsResponse = await fetch(emailsUrl);
        const emails = await emailsResponse.json();
        
        const dialogHtml = `
            <div class="fixed inset-0 bg-black bg-opacity-60 z-50 flex items-center justify-center p-4" id="dealDialogModal">
                <div class="modal-premium rounded-2xl p-6 w-full max-w-4xl max-h-[90vh] overflow-y-auto shadow-2xl">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">${deal.title} - Диалог</h3>
                        <div class="flex items-center gap-3">
                            <button onclick="syncAndReloadDealDialog(${dealId})"
                                    class="px-3 py-1 rounded-lg text-sm font-medium border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700">
                                Обновить
                            </button>
                            <button onclick="closeDealDialog()" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 text-2xl">&times;</button>
                        </div>
                    </div>
                    <div class="mb-4">
                        <button onclick="openEmailComposerDeals(${dealId}, ${primaryContact ? `'${primaryContact.email}', '${(primaryContact.first_name || '') + ' ' + (primaryContact.last_name || '')}', ${primaryContact.id}` : 'null, null, null'})" 
                                class="px-4 py-2 rounded-lg text-white font-semibold transition-all shadow-lg" 
                                style="background: linear-gradient(135deg, #ef4444 0%, #f97316 100%); box-shadow: 0 10px 40px rgba(239, 68, 68, 0.4); border: none;">
                            ${emails.length > 0 ? 'Отправить сообщение' : 'Отправить сообщение'}
                        </button>
                    </div>
                    <div id="emailsListDeals" class="space-y-4 max-h-96 overflow-y-auto">
                        ${emails.length > 0 ? emails.map(email => `
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg ${email.direction === 'outgoing' ? 'border-l-4 border-blue-500' : 'border-l-4 border-green-500'}">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <div class="font-medium text-sm">${email.direction === 'outgoing' ? '→ ' : '← '} ${email.direction === 'outgoing' ? email.to_email : email.from_email}</div>
                                        <div class="text-xs text-gray-500">${email.subject}</div>
                                    </div>
                                    <div class="text-xs text-gray-500">${new Date(email.sent_at || email.received_at).toLocaleString('ru-RU')}</div>
                                </div>
                                <div class="text-sm text-gray-700 dark:text-gray-300 mt-2 whitespace-pre-wrap">${email.body_text || email.body_html || ''}</div>
                            </div>
                        `).join('') : '<p class="text-center text-gray-500 py-8">Нет диалога</p>'}
                    </div>
                </div>
            </div>
        `;
        document.body.insertAdjacentHTML('beforeend', dialogHtml);
    }
    
    function closeDealDialog() {
        const modal = document.getElementById('dealDialogModal');
        if (modal) modal.remove();
    }

    async function syncAndReloadDealDialog(dealId) {
        try {
            await fetch('/api/emails/sync', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                },
                credentials: 'same-origin',
            });
        } catch (e) {
            console.warn('Email sync failed:', e);
        }
        closeDealDialog();
        await openDealDialog(dealId);
    }
    
    function openEmailComposerDeals(dealId, toEmail, toName, contactId = null) {
        const composerHtml = `
            <div class="fixed inset-0 bg-black bg-opacity-60 z-50 flex items-center justify-center p-4" id="emailComposerModalDeals">
                <div class="modal-premium rounded-2xl p-6 w-full max-w-2xl shadow-2xl">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">Отправить сообщение</h3>
                        <button onclick="closeEmailComposerDeals()" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 text-2xl">&times;</button>
                    </div>
                    <form id="emailFormDeals" onsubmit="sendEmailDeals(event, ${dealId}, ${contactId || 'null'})">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Кому</label>
                            <input type="email" id="emailToDeals" value="${toEmail || ''}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Тема</label>
                            <input type="text" id="emailSubjectDeals" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Сообщение</label>
                            <textarea id="emailBodyDeals" rows="8" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500" required></textarea>
                        </div>
                        <div class="flex justify-end gap-2">
                            <button type="button" onclick="closeEmailComposerDeals()" class="px-4 py-2 rounded-lg crm-btn-secondary">Отмена</button>
                            <button type="submit" class="px-4 py-2 rounded-lg text-white font-semibold" style="background: linear-gradient(135deg, #ef4444 0%, #f97316 100%); box-shadow: 0 10px 40px rgba(239, 68, 68, 0.4); border: none;">
                                Отправить
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        `;
        document.body.insertAdjacentHTML('beforeend', composerHtml);
    }
    
    function closeEmailComposerDeals() {
        const modal = document.getElementById('emailComposerModalDeals');
        if (modal) modal.remove();
    }
    
    async function sendEmailDeals(e, dealId, contactId = null) {
        e.preventDefault();
        const toEmail = document.getElementById('emailToDeals').value;
        const subject = document.getElementById('emailSubjectDeals').value;
        const body = document.getElementById('emailBodyDeals').value;
        
        try {
            const response = await fetch('/api/emails/send', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrf
                },
                body: JSON.stringify({
                    to_email: toEmail,
                    subject: subject,
                    body: body,
                    deal_id: dealId,
                    contact_id: contactId || null
                })
            });
            
            const contentType = response.headers.get('content-type');
            if (response.ok) {
                closeEmailComposerDeals();
                closeDealDialog();
                await openDealDialog(dealId);
                alert('Сообщение отправлено!');
            } else {
                let errorMessage = 'Не удалось отправить сообщение';
                if (contentType && contentType.includes('application/json')) {
                    try {
                        const error = await response.json();
                        errorMessage = error.error || error.message || errorMessage;
                    } catch (e) {
                        errorMessage = `Ошибка ${response.status}: ${response.statusText}`;
                    }
                } else {
                    const text = await response.text();
                    errorMessage = `Ошибка ${response.status}: ${text.substring(0, 100)}`;
                }
                alert('Ошибка: ' + errorMessage);
            }
        } catch (error) {
            console.error('Error sending email:', error);
            alert('Ошибка отправки сообщения: ' + error.message);
        }
    }

    // UI events
    document.getElementById('search').addEventListener('input', () => {
        clearTimeout(window.searchTimeout);
        window.searchTimeout = setTimeout(() => load(1), 350);
    });
    ['stageFilter', 'companyFilter', 'userFilter'].forEach((id) => {
        const el = document.getElementById(id);
        if (el) el.addEventListener('change', () => load(1));
    });
    ['minAmount', 'maxAmount'].forEach((id) => {
        const el = document.getElementById(id);
        if (!el) return;
        el.addEventListener('input', () => {
            clearTimeout(window.amountTimeout);
            window.amountTimeout = setTimeout(() => load(1), 450);
        });
    });
    
    loadCompanies();
    loadContacts();
    loadStages();
    @if(auth()->user()->is_admin || auth()->user()->role === 'manager')
    loadUsers();
    @endif
    load();
    </script>
</x-app-layout>
