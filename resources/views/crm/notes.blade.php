<x-app-layout>
    <div class="py-6">
        <div class="px-6">
            <div class="premium-card no-card-zoom dark:bg-gray-800 sm:rounded-2xl">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        @if(auth()->user()->is_admin || auth()->user()->role === 'manager')
                        <button onclick="openModal()" class="px-4 py-2 rounded-lg text-white font-semibold transition-all shadow-lg" style="background: linear-gradient(135deg, #ef4444 0%, #f97316 100%); box-shadow: 0 10px 40px rgba(239, 68, 68, 0.4); border: none;">+ Добавить заметку</button>
                        @else
                        <div></div>
                        @endif
                    </div>
                    
                    <div id="list" class="space-y-4"></div>
                    <div id="pagination" class="mt-4"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-2xl">
            <h3 class="text-xl font-bold mb-4 text-gray-900 dark:text-gray-100" id="modalTitle">Добавить заметку</h3>
            <form id="form" class="space-y-4">
                <input type="hidden" id="editId">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Тип *</label>
                    <select name="notable_type" id="notable_type" required onchange="loadItems()" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        <option value="">Выберите тип</option>
                        <option value="App\Models\Company">Компания</option>
                        <option value="App\Models\Contact">Контакт</option>
                        <option value="App\Models\Deal">Сделка</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Объект *</label>
                    <select name="notable_id" id="notable_id" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        <option value="">Сначала выберите тип</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Текст заметки *</label>
                    <textarea name="body" id="body" rows="5" required class="w-full px-3 py-2 border border-gray-300 rounded-lg"></textarea>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 border rounded-lg">Отмена</button>
                    <button type="submit" class="px-4 py-2 rounded-lg text-white font-semibold" style="background: linear-gradient(135deg, #ef4444 0%, #f97316 100%); box-shadow: 0 10px 40px rgba(239, 68, 68, 0.4); border: none;">Сохранить</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    let currentPage = 1;
    let items = {};

    function normalizeType(t) {
        // Поддержка старых/ошибочных значений вида "App\\Models\\Company"
        return (t || '').replace(/\\\\/g, '\\');
    }
    
    async function loadItems(selectedId = null) {
        const type = normalizeType(document.getElementById('notable_type').value);
        const select = document.getElementById('notable_id');
        
        if (!type) {
            select.innerHTML = '<option value="">Сначала выберите тип</option>';
            select.value = '';
            return;
        }
        
        // Показываем загрузку
        select.innerHTML = '<option value="">Загрузка...</option>';
        select.disabled = true;
        
        try {
            let url = '';
            if (type === 'App\\Models\\Company') url = '/api/companies?per_page=1000';
            else if (type === 'App\\Models\\Contact') url = '/api/contacts?per_page=1000';
            else if (type === 'App\\Models\\Deal') url = '/api/deals?per_page=1000';
            else {
                select.innerHTML = '<option value="">Неизвестный тип</option>';
                select.disabled = false;
                return;
            }
            
            const r = await fetch(url, {
                headers: { 'Accept': 'application/json' },
                credentials: 'same-origin',
                cache: 'no-store'
            });
            
            if (!r.ok) {
                const errorText = await r.text();
                console.error('API error:', r.status, errorText);
                throw new Error(`Ошибка ${r.status}: ${r.statusText}`);
            }
            
            const j = await r.json();
            const data = Array.isArray(j) ? j : (j.data || (Array.isArray(j.items) ? j.items : []));
            
            if (!Array.isArray(data) || data.length === 0) {
                select.innerHTML = '<option value="">Нет доступных объектов</option>';
                select.disabled = false;
                return;
            }
            
            select.innerHTML = '<option value="">Выберите объект</option>' + 
                data.map(item => {
                    if (!item || !item.id) return '';
                    const name = item.name || item.title || `${(item.first_name || '')} ${(item.last_name || '')}`.trim() || 'Без имени';
                    return `<option value="${item.id}">${name}</option>`;
                }).filter(opt => opt !== '').join('');
            
            // Устанавливаем выбранное значение после загрузки опций
            if (selectedId !== null && selectedId !== undefined) {
                select.value = selectedId;
            }
        } catch (error) {
            console.error('Error loading items:', error);
            select.innerHTML = '<option value="">Ошибка загрузки. Проверьте консоль.</option>';
        } finally {
            select.disabled = false;
        }
    }
    
    async function load(page = 1) {
        const url = `/api/notes?page=${page}&per_page=100`;
        const r = await fetch(url);
        const j = await r.json();
        const notes = j.data || [];
        
        const list = document.getElementById('list');
        list.innerHTML = notes.map(n => `
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <span class="text-xs text-gray-500">${normalizeType(n.notable_type).replace('App\\Models\\', '')}</span>
                        <div class="text-sm text-gray-600 dark:text-gray-400">${new Date(n.created_at).toLocaleString('ru')}</div>
                    </div>
                    @if(auth()->user()->is_admin || auth()->user()->role === 'manager')
                    <div>
                        <button onclick="edit(${n.id})" class="text-blue-600 mr-2">Изменить</button>
                        <button onclick="del(${n.id})" class="text-red-600">Удалить</button>
                    </div>
                    @endif
                </div>
                <div class="text-gray-900 dark:text-gray-100 whitespace-pre-wrap">${n.body}</div>
            </div>
        `).join('');
        
        currentPage = page;
    }
    
    async function edit(id) {
        const r = await fetch(`/api/notes/${id}`);
        const n = await r.json();
        document.getElementById('editId').value = id;
        document.getElementById('notable_type').value = normalizeType(n.notable_type);
        document.getElementById('body').value = n.body;
        // Передаем notable_id в loadItems, чтобы он установился после загрузки опций
        await loadItems(n.notable_id);
        document.getElementById('modalTitle').textContent = 'Изменить заметку';
        document.getElementById('modal').classList.remove('hidden');
    }
    
    function openModal() {
        document.getElementById('form').reset();
        document.getElementById('editId').value = '';
        document.getElementById('notable_id').innerHTML = '<option value="">Сначала выберите тип</option>';
        document.getElementById('notable_id').disabled = false;
        document.getElementById('modalTitle').textContent = 'Добавить заметку';
        document.getElementById('modal').classList.remove('hidden');
    }
    
    function closeModal() {
        document.getElementById('modal').classList.add('hidden');
    }
    
    async function del(id) {
        if (!confirm('Удалить заметку?')) return;
        await fetch(`/api/notes/${id}`, {method: 'DELETE', headers: {'X-CSRF-TOKEN': csrf}});
        load(currentPage);
    }
    
    document.getElementById('form').onsubmit = async (e) => {
        e.preventDefault();
        const fd = new FormData(e.target);
        const id = document.getElementById('editId').value;
        const url = id ? `/api/notes/${id}` : '/api/notes';
        const method = id ? 'PUT' : 'POST';
        await fetch(url, {method, headers: {'X-CSRF-TOKEN': csrf}, body: fd});
        closeModal();
        load(currentPage);
    };
    
    load();
    </script>
</x-app-layout>

