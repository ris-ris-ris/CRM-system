<x-app-layout>
    <div class="py-6">
        <div class="px-6">
            <div class="premium-card dark:bg-gray-800 sm:rounded-2xl">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <div class="flex gap-2">
                            <button onclick="filterTasks('all')" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Все</button>
                            <button onclick="filterTasks('pending')" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg">Активные</button>
                            <button onclick="filterTasks('done')" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg">Выполненные</button>
                        </div>
                        <button onclick="openModal()" class="px-4 py-2 rounded-lg text-white font-semibold transition-all shadow-lg" style="background: linear-gradient(135deg, #ef4444 0%, #f97316 100%); box-shadow: 0 10px 40px rgba(239, 68, 68, 0.4); border: none;">+ Добавить задачу</button>
                    </div>
                    
                    <div id="list" class="space-y-3"></div>
                    <div id="pagination" class="mt-4"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-2xl">
            <h3 class="text-xl font-bold mb-4 text-gray-900 dark:text-gray-100" id="modalTitle">Добавить задачу</h3>
            <form id="form" class="space-y-4">
                <input type="hidden" id="editId">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Тип *</label>
                    <select name="type" id="type" required class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        <option value="call">Звонок</option>
                        <option value="meeting">Встреча</option>
                        <option value="email">Письмо</option>
                        <option value="task">Задача</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Тема *</label>
                    <input type="text" name="subject" id="subject" required class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Сделка</label>
                        <select name="deal_id" id="deal_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                            <option value="">Выберите сделку</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Срок</label>
                        <input type="datetime-local" name="due_at" id="due_at" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>
                </div>
                @if(auth()->user()->is_admin || auth()->user()->role === 'manager')
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Назначить пользователю</label>
                    <select name="user_id" id="user_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        <option value="">Выберите пользователя (оставить пустым = себе)</option>
                    </select>
                </div>
                @endif
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 border border-gray-300 rounded-lg bg-white hover:bg-gray-50">Отмена</button>
                    <button type="submit" class="px-4 py-2 rounded-lg text-white font-semibold" style="background: linear-gradient(135deg, #ef4444 0%, #f97316 100%); box-shadow: 0 10px 40px rgba(239, 68, 68, 0.4); border: none;">Сохранить</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
    let currentPage = 1;
    let currentFilter = 'all';
    let deals = [];
    let users = [];
    
    async function loadDeals() {
        const r = await fetch('/api/deals?per_page=1000');
        const j = await r.json();
        deals = j.data || [];
        const select = document.getElementById('deal_id');
        select.innerHTML = '<option value="">Выберите сделку</option>' + 
            deals.map(d => `<option value="${d.id}">${d.title}</option>`).join('');
    }
    
    @if(auth()->user()->is_admin || auth()->user()->role === 'manager')
    async function loadUsers() {
        const r = await fetch('/api/users');
        const j = await r.json();
        users = Array.isArray(j) ? j : (j.data || []);
        const select = document.getElementById('user_id');
        if (select) {
            select.innerHTML = '<option value="">Выберите пользователя (оставить пустым = себе)</option>' + 
                users.map(u => `<option value="${u.id}">${u.name}</option>`).join('');
        }
    }
    @endif
    
    function getTypeName(type) {
        const types = { 'call': 'Звонок', 'meeting': 'Встреча', 'email': 'Письмо', 'task': 'Задача' };
        return types[type] || type;
    }
    
    async function load(page = 1) {
        const url = `/api/activities?page=${page}&per_page=20`;
        const r = await fetch(url);
        const j = await r.json();
        let items = j.data || [];
        
        if (currentFilter === 'pending') items = items.filter(i => !i.done);
        if (currentFilter === 'done') items = items.filter(i => i.done);
        
        const list = document.getElementById('list');
        list.innerHTML = items.map(a => `
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <input type="checkbox" ${a.done ? 'checked' : ''} onchange="toggleDone(${a.id}, this.checked)" class="w-5 h-5">
                    <div>
                        <div class="font-semibold ${a.done ? 'line-through text-gray-400' : 'text-gray-900 dark:text-gray-100'}">${a.subject || '-'}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            ${getTypeName(a.type)} ${a.due_at ? '• ' + new Date(a.due_at).toLocaleString('ru') : ''}
                            ${a.user ? ' • Назначено: ' + a.user.name : ''}
                            ${a.created_by && a.created_by.id !== (a.user ? a.user.id : null) ? ' • От: ' + a.created_by.name : ''}
                        </div>
                    </div>
                </div>
                <div>
                    <button onclick="edit(${a.id})" class="text-blue-600 mr-2">Изменить</button>
                    <button onclick="del(${a.id})" class="text-red-600">Удалить</button>
                </div>
            </div>
        `).join('');
        
        currentPage = page;
    }
    
    async function toggleDone(id, done) {
        await fetch(`/api/activities/${id}`, {
            method: 'PUT',
            headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf},
            body: JSON.stringify({done})
        });
        load(currentPage);
    }
    
    function filterTasks(filter) {
        currentFilter = filter;
        document.querySelectorAll('button[onclick^="filterTasks"]').forEach(b => {
            b.className = 'px-4 py-2 bg-gray-200 text-gray-700 rounded-lg';
        });
        event.target.className = 'px-4 py-2 bg-blue-600 text-white rounded-lg';
        load(1);
    }
    
    async function edit(id) {
        const r = await fetch(`/api/activities/${id}`);
        const a = await r.json();
        document.getElementById('editId').value = id;
        document.getElementById('type').value = a.type || '';
        document.getElementById('subject').value = a.subject || '';
        document.getElementById('deal_id').value = a.deal_id || '';
        document.getElementById('due_at').value = a.due_at ? a.due_at.slice(0, 16) : '';
        @if(auth()->user()->is_admin || auth()->user()->role === 'manager')
        if (document.getElementById('user_id')) {
            document.getElementById('user_id').value = a.user_id || '';
        }
        @endif
        document.getElementById('modalTitle').textContent = 'Изменить задачу';
        document.getElementById('modal').classList.remove('hidden');
    }
    
    function openModal() {
        document.getElementById('form').reset();
        document.getElementById('editId').value = '';
        document.getElementById('modalTitle').textContent = 'Добавить задачу';
        document.getElementById('modal').classList.remove('hidden');
    }
    
    function closeModal() {
        document.getElementById('modal').classList.add('hidden');
    }
    
    async function del(id) {
        if (!confirm('Удалить задачу?')) return;
        await fetch(`/api/activities/${id}`, {method: 'DELETE', headers: {'X-CSRF-TOKEN': csrf}});
        load(currentPage);
    }
    
    document.getElementById('form').onsubmit = async (e) => {
        e.preventDefault();
        const id = document.getElementById('editId').value;
        const url = id ? `/api/activities/${id}` : '/api/activities';
        const method = id ? 'PUT' : 'POST';
        
        // Собираем данные в JSON
        const data = {
            type: document.getElementById('type').value,
            subject: document.getElementById('subject').value,
            deal_id: document.getElementById('deal_id').value || null,
            due_at: document.getElementById('due_at').value || null,
        };
        
        @if(auth()->user()->is_admin || auth()->user()->role === 'manager')
        const userId = document.getElementById('user_id')?.value || '';
        if (userId) {
            data.user_id = parseInt(userId);
        }
        @endif
        
        try {
            const response = await fetch(url, {
                method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            });
            
            if (!response.ok) {
                const error = await response.json();
                alert('Ошибка: ' + (error.message || 'Не удалось сохранить задачу'));
                return;
            }
            
            const result = await response.json();
            closeModal();
            load(currentPage);
        } catch (error) {
            console.error('Error:', error);
            alert('Ошибка при сохранении задачи');
        }
    };
    
    loadDeals();
    @if(auth()->user()->is_admin || auth()->user()->role === 'manager')
    loadUsers();
    @endif
    load();
    </script>
</x-app-layout>

