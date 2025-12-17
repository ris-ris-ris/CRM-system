<x-app-layout>
    <div class="py-6">
        <div class="px-6">
            <div class="premium-card no-card-zoom dark:bg-gray-800 sm:rounded-2xl p-6">
                <div id="kanban" data-can-see-assigned="{{ (auth()->user()->is_admin || auth()->user()->role === 'manager') ? '1' : '0' }}" class="flex gap-4 overflow-x-auto pb-4"></div>
            </div>
        </div>
    </div>

    <!-- Красивое подтверждение переноса (вместо confirm браузера) -->
    <div id="confirmMoveModal" class="hidden fixed inset-0 bg-black bg-opacity-60 z-50 items-center justify-center p-4">
        <div class="modal-premium rounded-2xl p-6 w-full max-w-md shadow-2xl">
            <div class="flex justify-between items-start gap-4 mb-3">
                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Подтверждение</h3>
                <button type="button" onclick="closeConfirmMove(false)" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 text-2xl leading-none">&times;</button>
            </div>
            <div class="text-sm text-gray-700 dark:text-gray-300 space-y-2">
                <div id="confirmMoveTitle" class="font-medium"></div>
                <div id="confirmMoveText" class="text-gray-600 dark:text-gray-400"></div>
            </div>
            <div class="mt-5 flex justify-end gap-2">
                <button type="button" onclick="closeConfirmMove(false)" class="px-4 py-2 rounded-lg crm-btn-secondary">
                    Отмена
                </button>
                <button type="button" onclick="closeConfirmMove(true)" class="px-4 py-2 rounded-lg text-white font-semibold" style="background: linear-gradient(135deg, #ef4444 0%, #f97316 100%); border: none;">
                    Переместить
                </button>
            </div>
        </div>
    </div>

    <!-- Modal для деталей сделки -->
    <div id="dealModal" class="hidden fixed inset-0 bg-black bg-opacity-60 z-50 flex items-center justify-center p-4">
        <div class="modal-premium rounded-2xl p-8 w-full max-w-6xl max-h-[90vh] overflow-y-auto shadow-2xl">
            <div class="flex justify-between items-start mb-6">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100" id="modalDealTitle">Детали сделки</h3>
                <button onclick="closeDealModal()" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 text-2xl">&times;</button>
            </div>
            <div class="flex gap-4 mb-4 border-b border-gray-200 dark:border-gray-700">
                <button onclick="showDealInfo()" id="tabInfo" class="px-4 py-2 font-medium text-gray-700 dark:text-gray-300 border-b-2 border-blue-500">Информация</button>
                <button onclick="showDealEmails()" id="tabEmails" class="px-4 py-2 font-medium text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">Диалог</button>
            </div>
            <div id="dealModalContent"></div>
            <div id="dealEmailsContent" class="hidden"></div>
        </div>
    </div>

    <script>
    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    let stages = [];
    let deals = [];
    let allContacts = [];
    let allNotes = [];
    let allUsers = [];
    const __kanbanCanSeeAssigned = (document.getElementById('kanban')?.dataset?.canSeeAssigned === '1');
    
    async function loadStages() {
        const r = await fetch('/api/stages');
        stages = await r.json();
        renderKanban();
    }
    
    async function loadDeals() {
        try {
            // Менеджеры и админы видят все сделки, обычные пользователи - только свои
            const r = await fetch('/api/deals?per_page=1000', {
                headers: { 'Accept': 'application/json' },
                credentials: 'same-origin',
                cache: 'no-store',
            });
            if (!r.ok) {
                console.error('Error loading deals:', r.status);
                document.getElementById('kanban').innerHTML = '<div class="text-center text-red-500 p-8">Ошибка загрузки сделок</div>';
                return;
            }
            const j = await r.json();
            deals = j.data || [];
            if (deals.length === 0) {
                document.getElementById('kanban').innerHTML = '<div class="text-center text-gray-500 p-8">Нет сделок для отображения</div>';
                return;
            }
            
            renderKanban();
        } catch (error) {
            console.error('Error:', error);
            document.getElementById('kanban').innerHTML = '<div class="text-center text-red-500 p-8">Ошибка загрузки данных</div>';
        }
    }
    
    async function loadContacts() {
        const r = await fetch('/api/contacts?per_page=1000');
        const j = await r.json();
        allContacts = j.data || [];
    }
    
    async function loadNotes() {
        const r = await fetch('/api/notes?per_page=1000');
        const j = await r.json();
        allNotes = j.data || [];
    }
    
    async function loadUsers() {
        const r = await fetch('/api/users');
        allUsers = await r.json();
    }
    
    function renderKanban() {
        if (!stages.length || !deals.length) return;
        
        const container = document.getElementById('kanban');
        container.innerHTML = stages.map(stage => {
            const stageDeals = deals.filter(d => d.stage_id == stage.id);
            const stageNames = {
                'Lead': 'Лид', 'Qualified': 'Квалификация', 'Proposal': 'Предложение',
                'Negotiation': 'Переговоры', 'Won': 'Выиграно', 'Lost': 'Проиграно',
                'Лид': 'Лид', 'Квалификация': 'Квалификация', 'Предложение': 'Предложение',
                'Переговоры': 'Переговоры', 'Выиграно': 'Выиграно', 'Проиграно': 'Проиграно'
            };
            const displayName = stageNames[stage.name] || stage.name;
            // Определяем цвет фона для столбцов
            let bgColor = 'bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800';
            if (stage.name === 'Won' || stage.name === 'Выиграно') {
                bgColor = 'bg-gradient-to-br from-green-50 to-green-100';
            } else if (stage.name === 'Lost' || stage.name === 'Проиграно') {
                bgColor = 'bg-gradient-to-br from-red-50 to-red-100';
            }
            return `
                <div class="flex-shrink-0 w-80 ${bgColor} rounded-xl p-4 shadow-lg border border-gray-200 dark:border-gray-600">
                    <h3 class="font-bold text-lg mb-4 text-gray-800 dark:text-gray-100">${displayName} (${stageDeals.length})</h3>
                    <div class="space-y-2 min-h-[200px] kanban-stage" data-stage-id="${stage.id}">
                        ${stageDeals.map(deal => {
                            const userInfo = __kanbanCanSeeAssigned
                                ? (deal.user
                                    ? `<div class="text-xs text-gray-500 dark:text-gray-400 mt-1">👤 ${deal.user.name}</div>`
                                    : `<div class="text-xs text-gray-400 dark:text-gray-500 mt-1">👤 Не назначено</div>`)
                                : '';
                            
                            return `
                            <div class="bg-white dark:bg-gray-800 p-3 rounded shadow cursor-pointer hover:shadow-lg transition kanban-deal group relative border border-gray-200 dark:border-gray-700 hover:border-orange-300 dark:hover:border-orange-500/40"
                                 onclick="openDealModal(${deal.id})"
                                 data-deal-id="${deal.id}">
                                <div class="absolute top-2 right-2 flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity z-10">
                                    <button type="button"
                                            onclick="event.stopPropagation(); moveDealStep(${deal.id}, -1)"
                                            class="px-2 py-1 text-xs font-bold rounded bg-white border border-gray-200 kanban-move-btn">
                                        ←
                                    </button>
                                    <button type="button"
                                            onclick="event.stopPropagation(); moveDealStep(${deal.id}, 1)"
                                            class="px-2 py-1 text-xs font-bold rounded bg-white border border-gray-200 kanban-move-btn">
                                        →
                                    </button>
                                </div>
                                <div class="font-semibold text-gray-900 dark:text-gray-100">${deal.title}</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    ${deal.company ? deal.company.name : ''}
                                </div>
                                <div class="text-sm font-medium text-blue-600 dark:text-blue-400 mt-1">
                                    ${deal.amount ? new Intl.NumberFormat('ru-RU').format(deal.amount) + ' ' + (deal.currency || 'RUB') : ''}
                                </div>
                                ${userInfo}
                            </div>
                        `;
                        }).join('')}
                    </div>
                </div>
            `;
        }).join('');
    }
    
    let currentDealId = null;
    
    function showDealInfo() {
        document.getElementById('dealModalContent').classList.remove('hidden');
        document.getElementById('dealEmailsContent').classList.add('hidden');
        document.getElementById('tabInfo').classList.add('border-b-2', 'border-blue-500', 'text-gray-700', 'dark:text-gray-300');
        document.getElementById('tabInfo').classList.remove('text-gray-500', 'dark:text-gray-400');
        document.getElementById('tabEmails').classList.remove('border-b-2', 'border-blue-500', 'text-gray-700', 'dark:text-gray-300');
        document.getElementById('tabEmails').classList.add('text-gray-500', 'dark:text-gray-400');
    }
    
    async function showDealEmails() {
        document.getElementById('dealModalContent').classList.add('hidden');
        document.getElementById('dealEmailsContent').classList.remove('hidden');
        document.getElementById('tabEmails').classList.add('border-b-2', 'border-blue-500', 'text-gray-700', 'dark:text-gray-300');
        document.getElementById('tabEmails').classList.remove('text-gray-500', 'dark:text-gray-400');
        document.getElementById('tabInfo').classList.remove('border-b-2', 'border-blue-500', 'text-gray-700', 'dark:text-gray-300');
        document.getElementById('tabInfo').classList.add('text-gray-500', 'dark:text-gray-400');
        
        if (currentDealId) {
            await loadDealEmails(currentDealId, null);
        }
    }
    
    async function loadDealEmails(dealId, contactId = null) {
        const emailsContainer = document.getElementById('dealEmailsContent');
        emailsContainer.innerHTML = '<div class="text-center py-4">Загрузка диалога...</div>';
        
        try {
            let url = `/api/emails?related_type=Deal&related_id=${dealId}`;
            if (contactId) {
                url += `&contact_id=${contactId}`;
            }
            const response = await fetch(url);
            const emails = await response.json();
            
            const deal = deals.find(d => d.id == dealId);
            const dealContacts = allContacts.filter(c => c.company_id == deal.company_id);
            const primaryContact = contactId ? dealContacts.find(c => c.id == contactId) : (dealContacts.find(c => c.email) || dealContacts[0]);
            
            emailsContainer.innerHTML = `
                <div class="mb-4">
                    <button onclick="openEmailComposer(${dealId}, ${primaryContact ? `'${primaryContact.email}', '${(primaryContact.first_name || '') + ' ' + (primaryContact.last_name || '')}', ${primaryContact.id}` : 'null, null, null'})" 
                            class="px-4 py-2 rounded-lg text-white font-semibold transition-all shadow-lg" 
                            style="background: linear-gradient(135deg, #ef4444 0%, #f97316 100%); box-shadow: 0 10px 40px rgba(239, 68, 68, 0.4); border: none;">
                        ${emails.length > 0 ? 'Отправить сообщение' : 'Отправить сообщение'}
                    </button>
                    <button onclick="syncAndReloadDealEmails(${dealId}, ${contactId || 'null'})"
                            class="ml-2 px-4 py-2 rounded-lg font-semibold border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700">
                        Обновить
                    </button>
                </div>
                <div id="emailsList" class="space-y-4 max-h-96 overflow-y-auto">
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
            `;
        } catch (error) {
            emailsContainer.innerHTML = `<div class="text-red-500">Ошибка загрузки: ${error.message}</div>`;
        }
    }

    async function syncAndReloadDealEmails(dealId, contactId = null) {
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
        await loadDealEmails(dealId, contactId);
    }
    
    function openEmailComposer(dealId, toEmail, toName, contactId = null) {
        const deal = deals.find(d => d.id == dealId);
        // Контакты для выбора адресата:
        // 1) контакт сделки (deal.contact / contact_id)
        // 2) контакты компании сделки (company_id)
        const byId = new Map();
        const add = (c) => {
            if (!c || !c.id) return;
            byId.set(String(c.id), c);
        };
        if (deal && deal.contact) add(deal.contact);
        if (deal && deal.contact_id) add(allContacts.find(c => String(c.id) === String(deal.contact_id)));
        if (deal && deal.company_id) {
            allContacts
                .filter(c => String(c.company_id) === String(deal.company_id))
                .forEach(add);
        }
        const dealContacts = Array.from(byId.values());
        const emailContacts = dealContacts.filter(c => c.email);
        
        const composerHtml = `
            <div class="fixed inset-0 bg-black bg-opacity-60 z-50 flex items-center justify-center p-4" id="emailComposerModal">
                <div class="modal-premium rounded-2xl p-6 w-full max-w-2xl shadow-2xl">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">Отправить сообщение</h3>
                        <button onclick="closeEmailComposer()" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 text-2xl">&times;</button>
                    </div>
                    <form id="emailForm" onsubmit="sendEmail(event, ${dealId}, ${contactId || 'null'})">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Кому</label>
                            ${
                                emailContacts.length > 0
                                    ? `<select id="emailTo" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                                        ${emailContacts.map(c => `<option value="${c.email}" data-contact-id="${c.id}" data-name="${(c.first_name || '') + ' ' + (c.last_name || '')}" ${c.email === toEmail ? 'selected' : ''}>${c.first_name} ${c.last_name || ''} (${c.email})</option>`).join('')}
                                       </select>`
                                    : `<input type="email" id="emailToInput" value="${toEmail || ''}" placeholder="email@example.com" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500" required>`
                            }
                            ${emailContacts.length === 0 ? '<div class="mt-2 text-xs text-gray-500 dark:text-gray-400">Нет контактов с email у этой сделки/компании — введи email вручную.</div>' : ''}
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Тема</label>
                            <input type="text" id="emailSubject" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Сообщение</label>
                            <textarea id="emailBody" rows="8" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500" required></textarea>
                        </div>
                        <div class="flex justify-end gap-2">
                            <button type="button" onclick="closeEmailComposer()" class="px-4 py-2 rounded-lg crm-btn-secondary">Отмена</button>
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
    
    function closeEmailComposer() {
        const modal = document.getElementById('emailComposerModal');
        if (modal) modal.remove();
    }
    
    async function sendEmail(e, dealId, contactId = null) {
        e.preventDefault();
        const toSelect = document.getElementById('emailTo');
        const toInput = document.getElementById('emailToInput');
        const toEmail = toSelect ? toSelect.value : (toInput ? toInput.value : '');
        const selectedOption = toSelect ? toSelect.options[toSelect.selectedIndex] : null;
        const toName = selectedOption ? (selectedOption.dataset.name || '') : '';
        const selectedContactId = selectedOption ? (selectedOption.dataset.contactId || contactId) : contactId;
        const subject = document.getElementById('emailSubject').value;
        const body = document.getElementById('emailBody').value;
        
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
                    to_name: toName,
                    subject: subject,
                    body: body,
                    deal_id: dealId,
                    contact_id: selectedContactId || null
                })
            });
            
            const contentType = response.headers.get('content-type');
            if (response.ok) {
                closeEmailComposer();
                await loadDealEmails(dealId, selectedContactId);
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
            console.error('Email send error:', error);
            alert('Ошибка отправки: ' + error.message);
        }
    }
    
    async function openDealModal(dealId) {
        // Если был drag (тач/мышь) — не открываем модалку кликом после отпускания.
        if (window.__crmSuppressNextClick) {
            window.__crmSuppressNextClick = false;
            return;
        }
        currentDealId = dealId;
        const deal = deals.find(d => d.id == dealId);
        if (!deal) return;
        
        const dealContacts = allContacts.filter(c => c.company_id == deal.company_id);
        const dealNotes = allNotes.filter(n => 
            (n.notable_type === 'App\\Models\\Deal' && n.notable_id == dealId) ||
            (n.notable_type === 'App\\Models\\Company' && n.notable_id == deal.company_id)
        );
        
        const activities = await fetch(`/api/activities?deal_id=${dealId}`).then(r => r.json()).then(j => j.data || []);
        
        document.getElementById('modalDealTitle').textContent = deal.title;
        
        // AI Score display
        document.getElementById('dealModalContent').innerHTML = `
            <div class="grid grid-cols-2 gap-6 mb-6">
                <div>
                    <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-2">Информация о сделке</h4>
                    <div class="space-y-2 text-sm">
                        <div><span class="font-medium">Компания:</span> ${deal.company ? deal.company.name : '-'}</div>
                        <div><span class="font-medium">Сумма:</span> ${deal.amount ? new Intl.NumberFormat('ru-RU').format(deal.amount) + ' ' + (deal.currency || 'RUB') : '-'}</div>
                        <div><span class="font-medium">Стадия:</span> ${deal.stage ? deal.stage.name : '-'}</div>
                        <div><span class="font-medium">Дата закрытия:</span> ${deal.expected_close_date ? new Date(deal.expected_close_date).toLocaleDateString('ru-RU') : '-'}</div>
                        @if(auth()->user()->is_admin || auth()->user()->role === 'manager')
                        <div><span class="font-medium">Назначено:</span> ${deal.user ? deal.user.name : 'Не назначено'}</div>
                        @endif
                    </div>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-2">Описание</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400">${deal.description || 'Нет описания'}</p>
                </div>
            </div>
            
            <div class="mb-6">
                <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-3">Контакты</h4>
                <div class="space-y-2">
                    ${dealContacts.length > 0 ? dealContacts.map(c => `
                        <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded flex justify-between items-center">
                            <div>
                                <div class="font-medium">${c.first_name} ${c.last_name || ''}</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">${c.position || ''} | ${c.email || ''} | ${c.phone || ''}</div>
                            </div>
                        </div>
                    `).join('') : '<p class="text-sm text-gray-500">Нет контактов</p>'}
                </div>
            </div>
            
            <div class="mb-6">
                <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-3">Заметки</h4>
                <div class="space-y-2">
                    ${dealNotes.length > 0 ? dealNotes.map(n => `
                        <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded">
                            <p class="text-sm">${n.body}</p>
                            <p class="text-xs text-gray-500 mt-1">${new Date(n.created_at).toLocaleDateString('ru-RU')}</p>
                        </div>
                    `).join('') : '<p class="text-sm text-gray-500">Нет заметок</p>'}
                </div>
            </div>
            
            <div>
                <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-3">Активности</h4>
                <div class="space-y-2">
                    ${activities.length > 0 ? activities.map(a => `
                        <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded">
                            <div class="font-medium text-sm">${a.subject || 'Без темы'}</div>
                            <div class="text-xs text-gray-500 mt-1">
                                ${a.type === 'call' ? 'Звонок' : a.type === 'meeting' ? 'Встреча' : a.type === 'email' ? 'Письмо' : 'Задача'} | 
                                ${a.due_at ? new Date(a.due_at).toLocaleDateString('ru-RU') : ''} | 
                                ${a.done ? 'Выполнено' : 'В процессе'}
                            </div>
                        </div>
                    `).join('') : '<p class="text-sm text-gray-500">Нет активностей</p>'}
                </div>
            </div>
        `;
        
        document.getElementById('dealModal').classList.remove('hidden');
    }
    
    function closeDealModal() {
        document.getElementById('dealModal').classList.add('hidden');
    }
    
    function allowDrop(ev) {
        ev.preventDefault();
    }
    
    function drag(ev) {
        // target может быть дочерним узлом без dataset → используем currentTarget (draggable div)
        ev.dataTransfer.setData("dealId", ev.currentTarget.dataset.dealId);
    }

    async function updateDealStage(dealId, stageId) {
        const res = await fetch(`/api/deals/${dealId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf,
            },
            credentials: 'same-origin',
            body: JSON.stringify({stage_id: stageId})
        });

        if (!res.ok) {
            let msg = `Ошибка ${res.status}`;
            try {
                const j = await res.json();
                msg = j.error || j.message || msg;
            } catch (_) {}
            throw new Error(msg);
        }
    }
    
    function stageIndexById(stageId) {
        return stages.findIndex(s => String(s.id) === String(stageId));
    }

    function stageNameById(stageId) {
        const s = stages.find(x => String(x.id) === String(stageId));
        return s ? s.name : String(stageId);
    }

    let __confirmMoveResolve = null;

    function openConfirmMoveModal(dealTitle, fromName, toName) {
        const modal = document.getElementById('confirmMoveModal');
        if (!modal) return Promise.resolve(false);
        document.getElementById('confirmMoveTitle').textContent = `Сделка: ${dealTitle}`;
        document.getElementById('confirmMoveText').textContent = `${fromName} → ${toName}`;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        return new Promise(resolve => { __confirmMoveResolve = resolve; });
    }

    function closeConfirmMove(ok) {
        const modal = document.getElementById('confirmMoveModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
        if (__confirmMoveResolve) {
            const r = __confirmMoveResolve;
            __confirmMoveResolve = null;
            r(!!ok);
        }
    }

    async function moveDealStep(dealId, delta) {
        const deal = deals.find(d => String(d.id) === String(dealId));
        if (!deal) return;

        const idx = stageIndexById(deal.stage_id);
        if (idx === -1) return;

        const next = stages[idx + delta];
        if (!next) return;

        const fromName = stageNameById(deal.stage_id);
        const toName = stageNameById(next.id);
        const ok = await openConfirmMoveModal(deal.title, fromName, toName);
        if (!ok) return;

        // Сразу перемещаем в UI (без перезагрузки), затем подтверждаем на сервере.
        const prevStageId = deal.stage_id;
        deal.stage_id = next.id;
        renderKanban();

        try {
            await updateDealStage(dealId, next.id);
            // подстрахуем актуализацией без перезагрузки страницы
            loadDeals();
        } catch (e) {
            // откат UI
            deal.stage_id = prevStageId;
            renderKanban();
            alert('Не удалось переместить сделку: ' + e.message);
        }
    }
    
    // Закрытие модального окна при клике вне его
    document.getElementById('dealModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeDealModal();
        }
    });
    
    loadStages();
    loadDeals();
    loadContacts();
    loadNotes();
    loadUsers();
    </script>
    
    <style>
        /* Kanban: disable any hover zoom on the board container */
        .premium-card.no-card-zoom:hover{
            transform: none !important;
        }

        /* Kanban move arrows: highlight hovered arrow with CRM orange gradient */
        .kanban-move-btn{
            transition: background-color .18s ease, color .18s ease, border-color .18s ease, box-shadow .18s ease;
            user-select: none;
        }
        .kanban-move-btn:hover,
        .kanban-move-btn:focus-visible{
            background: linear-gradient(135deg, #ef4444 0%, #f97316 100%) !important;
            border-color: transparent !important;
            color: #ffffff !important;
            box-shadow: 0 10px 30px rgba(239, 68, 68, 0.25);
            outline: none;
        }
        html.dark .kanban-move-btn{
            border-color: rgba(255,255,255,0.12) !important;
        }

        /* Kanban deal card hover: keep "highlight" without any zoom */
        .kanban-deal:hover{
            transform: none !important;
            box-shadow: 0 18px 50px rgba(0,0,0,0.18) !important;
        }
        html.dark .kanban-deal:hover{
            box-shadow: 0 18px 60px rgba(0,0,0,0.55) !important;
        }

        /* Свечение для элементов в Воронке продаж (самое минимальное) */
        html.dark .premium-card[class*="kanban"],
        html.dark #kanban {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3),
                        0 2px 4px -1px rgba(0, 0, 0, 0.2),
                        0 0 0 1px rgba(16, 185, 129, 0.04);
            transition: box-shadow 0.3s ease;
        }
        
        /* Свечение для колонок стадий */
        html.dark #kanban > div {
            filter: drop-shadow(0 0 2px rgba(16, 185, 129, 0.12)) 
                    drop-shadow(0 0 4px rgba(16, 185, 129, 0.06));
        }
        
        /* Свечение для карточек сделок в воронке */
        html.dark #kanban .kanban-deal,
        html.dark #kanban [class*="bg-white"][class*="dark:bg-gray-800"] {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2),
                        0 0 0 1px rgba(16, 185, 129, 0.04);
            transition: box-shadow 0.3s ease;
        }
        
        html.dark #kanban .kanban-deal:hover,
        html.dark #kanban [class*="bg-white"][class*="dark:bg-gray-800"]:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3),
                        0 0 0 1px rgba(16, 185, 129, 0.08),
                        0 0 4px rgba(16, 185, 129, 0.04);
        }
        
        /* Свечение для заголовков стадий */
        html.dark #kanban [class*="font-bold"][class*="text-gray-900"] {
            text-shadow: 0 0 3px rgba(16, 185, 129, 0.15),
                         0 0 6px rgba(16, 185, 129, 0.08);
        }
    </style>
</x-app-layout>
