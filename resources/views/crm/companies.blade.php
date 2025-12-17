<x-app-layout>
    <div class="py-6">
        <div class="px-6 space-y-6">
            <div class="flex items-start justify-between gap-4 flex-wrap">
                <div>
                    <h1 class="text-2xl font-extrabold text-gray-900 dark:text-gray-100">Компании</h1>
                </div>
                <div class="flex items-center gap-3 flex-wrap justify-end">
                    <div class="relative">
                        <input type="text" id="search" placeholder="Поиск по названию" class="pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-xl w-72 focus:ring-2 focus:ring-orange-400 focus:border-orange-400 transition-all shadow-sm bg-white dark:bg-gray-800">
                        <svg class="w-5 h-5 absolute left-3 top-2.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z"/>
                        </svg>
                    </div>
                    @if(auth()->user()->is_admin || auth()->user()->role === 'manager')
                    <button onclick="openModal()" class="px-4 py-2 rounded-xl text-white font-semibold transition-all shadow-lg" style="background: linear-gradient(135deg, #ef4444 0%, #f97316 100%); box-shadow: 0 10px 40px rgba(239, 68, 68, 0.35); border: none;">+ Компания</button>
                    @endif
                </div>
            </div>

            <!-- KPI -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="premium-card rounded-2xl p-5 border border-gray-200 dark:border-gray-700">
                    <div class="text-xs font-semibold text-slate-500 dark:text-slate-300 uppercase">Всего компаний</div>
                    <div class="text-2xl font-extrabold text-gray-900 dark:text-gray-100 mt-1" id="kpiTotal">—</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">в базе / по фильтру</div>
                </div>
            </div>

            <div class="premium-card no-card-zoom sm:rounded-2xl p-0 border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between gap-3 flex-wrap">
                    <div class="text-sm text-gray-600 dark:text-gray-400">Нажми на строку, чтобы открыть карточку.</div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800/60">
                            <tr>
                                <th onclick="sortBy('name')" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase cursor-pointer select-none hover:bg-gray-100/60">
                                    Компания <span id="sort-name-icon"></span>
                                </th>
                                <th onclick="sortBy('city')" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase cursor-pointer select-none hover:bg-gray-100/60">
                                    Город <span id="sort-city-icon"></span>
                                </th>
                                <th onclick="sortBy('phone')" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase cursor-pointer select-none hover:bg-gray-100/60">
                                    Телефон <span id="sort-phone-icon"></span>
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
    <aside id="drawer" class="hidden fixed right-0 top-0 h-full w-full sm:w-[460px] z-50">
        <div class="h-full bg-white dark:bg-gray-900 border-l border-gray-200 dark:border-gray-700 shadow-2xl p-6 overflow-y-auto">
            <div class="flex items-start justify-between gap-3">
                <div class="min-w-0">
                    <div class="text-xs text-gray-500 dark:text-gray-400">Компания</div>
                    <div id="drawerTitle" class="text-xl font-extrabold text-gray-900 dark:text-gray-100 truncate">—</div>
                    <div id="drawerMeta" class="text-sm text-gray-600 dark:text-gray-400 mt-1">—</div>
                </div>
                <button onclick="closeDrawer()" class="text-gray-500 hover:text-gray-800 dark:hover:text-gray-200 text-2xl leading-none">&times;</button>
            </div>

            <div class="mt-5 grid grid-cols-1 gap-3">
                <div class="premium-card rounded-2xl p-4 border border-gray-200 dark:border-gray-700 space-y-2">
                    <div class="flex items-center justify-between gap-3">
                        <div class="text-sm text-gray-600 dark:text-gray-300">Email</div>
                        <a id="drawerEmail" class="text-sm font-semibold text-orange-600 hover:text-orange-700 truncate" href="#" target="_blank" rel="noreferrer">—</a>
                    </div>
                    <div class="flex items-center justify-between gap-3">
                        <div class="text-sm text-gray-600 dark:text-gray-300">Телефон</div>
                        <a id="drawerPhone" class="text-sm font-semibold text-orange-600 hover:text-orange-700 truncate" href="#" target="_blank" rel="noreferrer">—</a>
                    </div>
                    <div class="flex items-center justify-between gap-3">
                        <div class="text-sm text-gray-600 dark:text-gray-300">Сайт</div>
                        <a id="drawerWebsite" class="text-sm font-semibold text-orange-600 hover:text-orange-700 truncate" href="#" target="_blank" rel="noreferrer">—</a>
                    </div>
                </div>
                <div class="premium-card rounded-2xl p-4 border border-gray-200 dark:border-gray-700">
                    <div class="text-xs text-gray-500 dark:text-gray-400">Адрес</div>
                    <div id="drawerAddress" class="text-sm text-gray-800 dark:text-gray-200 mt-1 whitespace-pre-wrap">—</div>
                </div>

                @if(auth()->user()->is_admin || auth()->user()->role === 'manager')
                <div class="flex gap-2">
                    <button id="drawerEditBtn" onclick="" class="flex-1 px-4 py-2 rounded-xl text-white font-semibold" style="background: linear-gradient(135deg, #ef4444 0%, #f97316 100%); border:none;">Изменить</button>
                    <button id="drawerDeleteBtn" onclick="" class="px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-600 font-semibold hover:bg-gray-50 dark:hover:bg-gray-800">Удалить</button>
                </div>
                @endif
            </div>
        </div>
    </aside>

    <!-- Modal -->
    <div id="modal" class="hidden fixed inset-0 bg-black bg-opacity-70 z-50 flex items-center justify-center modal-backdrop">
        <div class="modal-premium dark:bg-gray-800 p-8 w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <h3 class="text-xl font-bold mb-4 text-gray-900 dark:text-gray-100" id="modalTitle">Добавить компанию</h3>
            <form id="form" class="space-y-4">
                <input type="hidden" id="editId">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Название *</label>
                        <input type="text" name="name" id="name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                        <input type="email" name="email" id="email" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Телефон</label>
                        <input type="text" name="phone" id="phone" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Сайт</label>
                        <input type="url" name="website" id="website" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Отрасль</label>
                        <input type="text" name="industry" id="industry" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Город</label>
                        <input type="text" name="city" id="city" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Страна</label>
                        <input type="text" name="country" id="country" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Адрес</label>
                    <textarea name="address" id="address" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"></textarea>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeModal()" class="px-6 py-2 rounded-xl crm-btn-secondary transition-all font-medium">Отмена</button>
                    <button type="submit" class="px-4 py-2 rounded-lg text-white font-semibold" style="background: linear-gradient(135deg, #ef4444 0%, #f97316 100%); box-shadow: 0 10px 40px rgba(239, 68, 68, 0.4); border: none;">Сохранить</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    let currentPage = 1;
    let sortField = null;
    let sortDirection = 'asc';
    let lastCompanies = [];
    
    function sortBy(field) {
        if (sortField === field) {
            sortDirection = sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            sortField = field;
            sortDirection = 'asc';
        }
        load(currentPage);
    }
    
    function updateSortIcons() {
        document.querySelectorAll('[id^="sort-"]').forEach(el => el.textContent = '');
        if (sortField) {
            const icon = document.getElementById(`sort-${sortField}-icon`);
            if (icon) {
                icon.textContent = sortDirection === 'asc' ? ' ↑' : ' ↓';
            }
        }
    }
    
    async function load(page = 1) {
        const search = document.getElementById('search').value;
        const sortParam = sortField ? `&sort_by=${sortField}&sort_order=${sortDirection}` : '';
        const url = `/api/companies?page=${page}&per_page=100${search ? '&q=' + encodeURIComponent(search) : ''}${sortParam}`;
        const r = await fetch(url);
        const j = await r.json();
        const tbody = document.querySelector('#list');
        lastCompanies = j.data || [];

        // KPI
        document.getElementById('kpiTotal').textContent = (j.total ?? '—');

        if (!lastCompanies.length) {
            tbody.innerHTML = `<tr><td colspan="4" class="px-6 py-10 text-center text-gray-500">Нет компаний. Попробуй изменить поиск.</td></tr>`;
        } else {
            tbody.innerHTML = lastCompanies.map(c => `
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 cursor-pointer" onclick="openDrawer(${c.id})">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-orange-500/10 flex items-center justify-center">
                                <span class="text-orange-600 font-extrabold">${(c.name || '?').trim().slice(0,1).toUpperCase()}</span>
                            </div>
                            <div class="min-w-0">
                                <div class="text-sm font-bold text-gray-900 dark:text-gray-100 truncate">${c.name}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 truncate">${c.website ? c.website : '—'}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">${c.city || '—'}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">${c.phone || '—'}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm" onclick="event.stopPropagation()">
                        @if(auth()->user()->is_admin || auth()->user()->role === 'manager')
                        <button onclick="edit(${c.id})" class="px-3 py-1.5 rounded-lg bg-blue-100 text-blue-800 hover:bg-blue-200 dark:bg-blue-500/15 dark:text-blue-200 dark:hover:bg-blue-500/25 font-semibold mr-2">Изменить</button>
                        <button onclick="del(${c.id})" class="px-3 py-1.5 rounded-lg bg-red-100 text-red-800 hover:bg-red-200 dark:bg-red-500/15 dark:text-red-200 dark:hover:bg-red-500/25 font-semibold">Удалить</button>
                        @else
                        <span class="text-xs text-gray-400">—</span>
                        @endif
                    </td>
                </tr>
            `).join('');
        }
        
        // Pagination
        const pagination = document.getElementById('pagination');
        if (j.last_page > 1) {
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
        const c = lastCompanies.find(x => String(x.id) === String(id));
        if (!c) return;
        document.getElementById('drawerTitle').textContent = c.name || '—';
        document.getElementById('drawerMeta').textContent = [c.industry, c.city, c.country].filter(Boolean).join(' • ') || '—';

        const emailEl = document.getElementById('drawerEmail');
        if (c.email) { emailEl.textContent = c.email; emailEl.href = `mailto:${c.email}`; }
        else { emailEl.textContent = '—'; emailEl.href = '#'; }

        const phoneEl = document.getElementById('drawerPhone');
        if (c.phone) { phoneEl.textContent = c.phone; phoneEl.href = `tel:${c.phone}`; }
        else { phoneEl.textContent = '—'; phoneEl.href = '#'; }

        const webEl = document.getElementById('drawerWebsite');
        if (c.website) {
            webEl.textContent = c.website.replace(/^https?:\/\//, '');
            webEl.href = c.website.startsWith('http') ? c.website : `https://${c.website}`;
        } else { webEl.textContent = '—'; webEl.href = '#'; }

        document.getElementById('drawerAddress').textContent = [c.address, c.city, c.country].filter(Boolean).join('\n') || '—';

        @if(auth()->user()->is_admin || auth()->user()->role === 'manager')
        document.getElementById('drawerEditBtn').onclick = () => { closeDrawer(); edit(id); };
        document.getElementById('drawerDeleteBtn').onclick = () => { closeDrawer(); del(id); };
        @endif

        document.getElementById('drawerBackdrop').classList.remove('hidden');
        document.getElementById('drawer').classList.remove('hidden');
    }

    function closeDrawer() {
        document.getElementById('drawerBackdrop').classList.add('hidden');
        document.getElementById('drawer').classList.add('hidden');
    }
    
    async function edit(id) {
        const r = await fetch(`/api/companies/${id}`);
        const c = await r.json();
        document.getElementById('editId').value = id;
        document.getElementById('name').value = c.name || '';
        document.getElementById('email').value = c.email || '';
        document.getElementById('phone').value = c.phone || '';
        document.getElementById('website').value = c.website || '';
        document.getElementById('industry').value = c.industry || '';
        document.getElementById('city').value = c.city || '';
        document.getElementById('country').value = c.country || '';
        document.getElementById('address').value = c.address || '';
        document.getElementById('modalTitle').textContent = 'Изменить компанию';
        document.getElementById('modal').classList.remove('hidden');
    }
    
    function openModal() {
        document.getElementById('form').reset();
        document.getElementById('editId').value = '';
        document.getElementById('modalTitle').textContent = 'Добавить компанию';
        document.getElementById('modal').classList.remove('hidden');
    }
    
    function closeModal() {
        document.getElementById('modal').classList.add('hidden');
    }
    
    async function del(id) {
        if (!confirm('Удалить компанию?')) return;
        await fetch(`/api/companies/${id}`, {method: 'DELETE', headers: {'X-CSRF-TOKEN': csrf}});
        load(currentPage);
    }
    
    document.getElementById('form').onsubmit = async (e) => {
        e.preventDefault();
        const id = document.getElementById('editId').value;
        const url = id ? `/api/companies/${id}` : '/api/companies';
        const method = id ? 'PUT' : 'POST';

        // IMPORTANT: Use JSON body (multipart FormData for PUT is unreliable in PHP).
        const payload = {
            name: document.getElementById('name').value,
            email: document.getElementById('email').value || null,
            phone: document.getElementById('phone').value || null,
            website: document.getElementById('website').value || null,
            industry: document.getElementById('industry').value || null,
            city: document.getElementById('city').value || null,
            country: document.getElementById('country').value || null,
            address: document.getElementById('address').value || null,
        };

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
            alert('Ошибка сохранения компании');
        }
    };
    
    document.getElementById('search').addEventListener('input', () => {
        clearTimeout(window.searchTimeout);
        window.searchTimeout = setTimeout(() => load(1), 500);
    });
    
    load();
    </script>
</x-app-layout>
