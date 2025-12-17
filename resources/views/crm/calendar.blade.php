<x-app-layout>
    <div class="py-6">
        <div class="px-6">
            <div class="premium-card dark:bg-gray-800 sm:rounded-2xl p-6">
                <div class="mb-5 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                    <h3 id="monthYear" class="text-2xl font-extrabold text-gray-900 dark:text-gray-100 capitalize"></h3>
                    <div class="flex gap-2">
                        <button onclick="prevMonth()" class="px-4 py-2 rounded-xl border border-gray-300 bg-white hover:bg-gray-50 transition shadow-sm">←</button>
                        <button onclick="today()" class="px-4 py-2 rounded-xl border border-gray-300 bg-white hover:bg-gray-50 transition shadow-sm font-semibold">Сегодня</button>
                        <button onclick="nextMonth()" class="px-4 py-2 rounded-xl border border-gray-300 bg-white hover:bg-gray-50 transition shadow-sm">→</button>
                    </div>
                </div>

                <div class="grid grid-cols-7 gap-2 mb-2">
                    <div class="text-center font-bold text-gray-600 text-sm py-2 rounded-lg bg-gray-50">Пн</div>
                    <div class="text-center font-bold text-gray-600 text-sm py-2 rounded-lg bg-gray-50">Вт</div>
                    <div class="text-center font-bold text-gray-600 text-sm py-2 rounded-lg bg-gray-50">Ср</div>
                    <div class="text-center font-bold text-gray-600 text-sm py-2 rounded-lg bg-gray-50">Чт</div>
                    <div class="text-center font-bold text-gray-600 text-sm py-2 rounded-lg bg-gray-50">Пт</div>
                    <div class="text-center font-bold text-gray-600 text-sm py-2 rounded-lg bg-gray-50">Сб</div>
                    <div class="text-center font-bold text-gray-600 text-sm py-2 rounded-lg bg-gray-50">Вс</div>
                </div>

                <div id="calendarGrid"
                     class="grid grid-cols-7 gap-2"
                     style="min-height: 70vh; grid-auto-rows: 1fr;"></div>
            </div>
        </div>
    </div>

    <script>
    let currentDate = new Date();
    let activities = [];
    
    async function loadActivities() {
        const r = await fetch('/api/activities?per_page=1000', {
            headers: { 'Accept': 'application/json' },
            credentials: 'same-origin',
            cache: 'no-store',
        });
        const j = await r.json();
        activities = j.data || [];
        renderCalendar();
    }

    function pad2(n) { return String(n).padStart(2, '0'); }
    function ymd(d) {
        return `${d.getFullYear()}-${pad2(d.getMonth()+1)}-${pad2(d.getDate())}`;
    }
    
    function renderCalendar() {
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth();
        const first = new Date(year, month, 1);
        const jsDay = first.getDay(); // 0=Sun..6=Sat
        const firstDow = (jsDay + 6) % 7; // 0=Mon..6=Sun
        
        document.getElementById('monthYear').textContent = 
            currentDate.toLocaleDateString('ru', {month: 'long', year: 'numeric'});
        
        const grid = document.getElementById('calendarGrid');
        grid.innerHTML = '';
        
        // 6 недель (42 ячейки) — чтобы сетка всегда была ровной
        const startDate = new Date(year, month, 1 - firstDow);
        const todayStr = ymd(new Date());

        for (let i = 0; i < 42; i++) {
            const date = new Date(startDate);
            date.setDate(startDate.getDate() + i);
            const dateStr = ymd(date);
            const inMonth = date.getMonth() === month;
            const isToday = dateStr === todayStr;

            const dayActivities = activities.filter(a => {
                if (!a.due_at) return false;
                const aDate = new Date(a.due_at);
                return ymd(aDate) === dateStr;
            });

            const cell = document.createElement('div');
            cell.className = [
                'rounded-xl border p-2 shadow-sm transition',
                'border-gray-200',
                inMonth ? 'bg-white' : 'bg-gray-50',
                isToday ? 'ring-2 ring-blue-500' : 'hover:shadow-md hover:border-blue-200',
                'flex flex-col overflow-hidden'
            ].join(' ');

            const itemsHtml = dayActivities
                .slice(0, 6)
                .map(a => {
                    const t = a.due_at ? new Date(a.due_at) : null;
                    const time = t ? `${pad2(t.getHours())}:${pad2(t.getMinutes())}` : '';
                    const bg = a.done ? 'bg-gray-200 text-gray-700'
                                      : 'bg-blue-100 text-blue-900';
                    const subj = (a.subject || '').toString().slice(0, 60);
                    return `<div class="text-xs px-2 py-1 rounded-lg ${bg} truncate"><span class="opacity-70 mr-1">${time}</span>${subj}</div>`;
                })
                .join('');

            const moreHtml = dayActivities.length > 6
                ? `<div class="text-xs text-gray-500 dark:text-gray-400">+${dayActivities.length - 6}</div>`
                : '';

            cell.innerHTML = `
                <div class="flex items-center justify-between mb-2">
                    <div class="font-extrabold ${inMonth ? 'text-gray-900' : 'text-gray-400'}">${date.getDate()}</div>
                    ${dayActivities.length ? `<div class="text-xs px-2 py-0.5 rounded-full bg-orange-100 text-orange-800 font-semibold">${dayActivities.length}</div>` : ''}
                </div>
                <div class="flex-1 space-y-1 overflow-auto pr-1" style="scrollbar-width: thin;">
                    ${itemsHtml || '<div class="text-xs text-gray-400">—</div>'}
                    ${moreHtml}
                </div>
            `;

            grid.appendChild(cell);
        }
    }
    
    function prevMonth() {
        currentDate.setMonth(currentDate.getMonth() - 1);
        renderCalendar();
    }
    
    function nextMonth() {
        currentDate.setMonth(currentDate.getMonth() + 1);
        renderCalendar();
    }
    
    function today() {
        currentDate = new Date();
        renderCalendar();
    }
    
    loadActivities();
    </script>
</x-app-layout>

