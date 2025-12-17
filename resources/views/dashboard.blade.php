<x-app-layout>
    <div class="py-6 dashboard">
        <div class="px-6">
            <!-- Первая строка: 3 карточки - лаконичный премиум -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                <a href="{{ route('companies.page') }}" class="premium-card sm:rounded-2xl p-6 border border-gray-200 dark:border-gray-700 hover:border-blue-500/70 dark:hover:border-blue-400/70 transition-all cursor-pointer block group">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-slate-900/5 dark:bg-white/5 flex items-center justify-center">
                            <svg class="w-6 h-6 text-slate-700 dark:text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7a2 2 0 012-2h6l4 4v9a2 2 0 01-2 2H6a2 2 0 01-2-2V7z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5v4h4"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-xs font-semibold text-slate-500 dark:text-slate-300 uppercase tracking-wide">Компании</div>
                            <div class="text-3xl font-bold text-gray-900 dark:text-gray-100 leading-tight">{{ $stats['companies'] }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Всего в базе</div>
                        </div>
                        <span class="text-xs px-3 py-1 rounded-full bg-slate-900/5 dark:bg-white/10 text-slate-600 dark:text-slate-200">Открыть</span>
                    </div>
                </a>
                <a href="{{ route('contacts.page') }}" class="premium-card sm:rounded-2xl p-6 border border-gray-200 dark:border-gray-700 hover:border-purple-500/70 dark:hover:border-purple-400/70 transition-all cursor-pointer block group">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-slate-900/5 dark:bg-white/5 flex items-center justify-center">
                            <svg class="w-6 h-6 text-slate-700 dark:text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-xs font-semibold text-slate-500 dark:text-slate-300 uppercase tracking-wide">Контакты</div>
                            <div class="text-3xl font-bold text-gray-900 dark:text-gray-100 leading-tight">{{ $stats['contacts'] }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Активных</div>
                        </div>
                        <span class="text-xs px-3 py-1 rounded-full bg-slate-900/5 dark:bg-white/10 text-slate-600 dark:text-slate-200">Открыть</span>
                    </div>
                </a>
                <a href="{{ route('deals.page') }}" class="premium-card sm:rounded-2xl p-6 border border-gray-200 dark:border-gray-700 hover:border-emerald-500/70 dark:hover:border-emerald-400/70 transition-all cursor-pointer block group">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-slate-900/5 dark:bg-white/5 flex items-center justify-center">
                            <svg class="w-6 h-6 text-slate-700 dark:text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-xs font-semibold text-slate-500 dark:text-slate-300 uppercase tracking-wide">Сделки</div>
                            <div class="text-3xl font-bold text-gray-900 dark:text-gray-100 leading-tight">{{ $stats['deals'] }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">+{{ $stats['deals_this_month'] }} в этом месяце</div>
                        </div>
                        <span class="text-xs px-3 py-1 rounded-full bg-slate-900/5 dark:bg-white/10 text-slate-600 dark:text-slate-200">Открыть</span>
                    </div>
                </a>
            </div>
            
            <!-- Вторая строка: часы (по центру) -->
            <div class="mb-6">
                <div class="relative py-6 flex justify-center">
                    <div class="crm-clock-float flex flex-col md:flex-row items-center gap-6 md:gap-10">
                        <div class="crm-analog-clock" role="img" aria-label="Часы">
                            <div class="crm-analog-face">
                                <span class="crm-analog-tick t12"></span>
                                <span class="crm-analog-tick t3"></span>
                                <span class="crm-analog-tick t6"></span>
                                <span class="crm-analog-tick t9"></span>
                                <div id="handHour" class="crm-analog-hand hour"></div>
                                <div id="handMin" class="crm-analog-hand min"></div>
                                <div id="handSec" class="crm-analog-hand sec"></div>
                                <div class="crm-analog-center"></div>
                            </div>
                        </div>

                        <div class="text-center md:text-left">
                            <div id="clockTimeText" class="text-4xl md:text-5xl font-extrabold text-gray-900 dark:text-gray-100 leading-none tabular-nums">--:--:--</div>
                            <div id="clockDate" class="text-sm md:text-base text-gray-600 dark:text-gray-400 mt-2">—</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Окна под часами -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <a href="{{ route('activities.page') }}" class="premium-card sm:rounded-2xl p-6 border border-gray-200 dark:border-gray-700 hover:border-indigo-500/70 dark:hover:border-indigo-400/70 transition-all cursor-pointer block group">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-slate-900/5 dark:bg-white/5 flex items-center justify-center">
                            <svg class="w-6 h-6 text-slate-700 dark:text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-xs font-semibold text-slate-500 dark:text-slate-300 uppercase tracking-wide">Задачи на сегодня</div>
                            <div class="text-3xl font-bold text-gray-900 dark:text-gray-100 leading-tight">{{ $stats['activities_today'] }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400 mb-2">Требуют внимания</div>
                            @if($stats['activities_today'] > 0 && isset($stats['activities_today_list']) && $stats['activities_today_list']->count() > 0)
                                <div class="space-y-2">
                                    @foreach($stats['activities_today_list']->take(3) as $activity)
                                        <div class="text-xs text-gray-600 dark:text-gray-400 rounded px-2 py-1.5 bg-gray-50 dark:bg-gray-800/50">
                                            {{ $activity->subject ?? 'Без названия' }}
                                        </div>
                                    @endforeach
                                    @if($stats['activities_today_list']->count() > 3)
                                        <div class="text-xs text-gray-500 dark:text-gray-500">+{{ $stats['activities_today_list']->count() - 3 }} еще</div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </a>
                <div class="premium-card sm:rounded-2xl p-6 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-slate-900/5 dark:bg-white/5 flex items-center justify-center">
                            <svg class="w-6 h-6 text-slate-700 dark:text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-xs font-semibold text-slate-500 dark:text-slate-300 uppercase tracking-wide">Общий оборот</div>
                            <div class="text-3xl font-bold text-gray-900 dark:text-gray-100 leading-tight">{{ number_format($stats['total_amount'], 0, ',', ' ') }} ₽</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Все сделки</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Третья строка: График прибыли на всю ширину -->
            <div class="mb-6">
                <div class="premium-card sm:rounded-2xl p-6 border border-gray-200 dark:border-gray-700 hover:border-orange-500/70 dark:hover:border-orange-400/70 transition-all">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 rounded-xl bg-orange-500/10 dark:bg-orange-400/10 flex items-center justify-center">
                            <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-xs font-semibold text-slate-500 dark:text-slate-300 uppercase tracking-wide">Прибыль</div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-gray-100 leading-tight">{{ number_format($stats['net_profit'] ?? 0, 0, ',', ' ') }} ₽</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Выигранные сделки</div>
                        </div>
                    </div>
                    <div style="height: 300px; position: relative;">
                        <canvas id="profitChart"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Четвертая строка: График конверсии на всю ширину -->
            <div class="mb-6">
                <div class="premium-card sm:rounded-2xl p-6 border border-gray-200 dark:border-gray-700 hover:border-pink-500/70 dark:hover:border-pink-400/70 transition-all">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 rounded-xl bg-pink-500/10 dark:bg-pink-400/10 flex items-center justify-center">
                            <svg class="w-6 h-6 text-pink-600 dark:text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-xs font-semibold text-slate-500 dark:text-slate-300 uppercase tracking-wide">Конверсия</div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-gray-100 leading-tight">
                                @php
                                    $totalDeals = $stats['deals'] ?? 0;
                                    $wonDeals = $stats['won_deals'] ?? 0;
                                    $conversion = $totalDeals > 0 ? round(($wonDeals / $totalDeals) * 100, 1) : 0;
                                @endphp
                                {{ $conversion }}%
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Выиграно из всех сделок</div>
                        </div>
                    </div>
                    <div style="height: 300px; position: relative;">
                        <canvas id="conversionChart"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Пятая строка: Таблица последних сделок -->
            <div class="premium-card dark:bg-gray-800 sm:rounded-2xl p-6">
                <h3 class="text-xl font-bold mb-6 text-gray-900 dark:text-gray-100 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-slate-900/5 dark:bg-white/5 flex items-center justify-center">
                        <svg class="w-5 h-5 text-slate-700 dark:text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    Последние сделки
                </h3>
                <div class="overflow-x-auto" style="scrollbar-width: none; -ms-overflow-style: none;">
                    <style>
                        .overflow-x-auto::-webkit-scrollbar {
                            display: none;
                        }
                    </style>
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 table-enhanced">
                        <thead class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Название</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Компания</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Сумма</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Стадия</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($stats['recent_deals'] as $deal)
                            <tr class="hover:bg-blue-50 dark:hover:bg-gray-700 transition-all">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $deal->title }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ $deal->company->name ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm font-semibold text-green-600 dark:text-green-400">{{ number_format($deal->amount ?? 0, 0, ',', ' ') }} ₽</td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $deal->stage && ($deal->stage->name === 'Won' || $deal->stage->name === 'Выиграно') ? 'bg-green-100 text-green-800' : ($deal->stage && ($deal->stage->name === 'Lost' || $deal->stage->name === 'Проиграно') ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800') }}">
                                        {{ $deal->stage->name ?? '-' }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400 text-center">Нет сделок</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        const csrf = document.querySelector('meta[name="csrf-token"]').content;
        
        // Данные для графиков
        const profitData = <?php echo json_encode($stats['monthly_profit'] ?? []); ?>;
        const conversionData = <?php echo json_encode($stats['monthly_conversion'] ?? []); ?>;
        
        // Инициализация графиков после загрузки DOM
        document.addEventListener('DOMContentLoaded', function() {
            const months = ['Янв', 'Фев', 'Мар', 'Апр', 'Май', 'Июн', 'Июл', 'Авг', 'Сен', 'Окт', 'Ноя', 'Дек'];
            
            // Подготовка данных для графика прибыли
            const profitValues = [];
            for (let i = 1; i <= 12; i++) {
                profitValues.push(profitData[i] ?? 0);
            }
            
            // График прибыли по месяцам
            const profitCtx = document.getElementById('profitChart');
            if (profitCtx) {
                new Chart(profitCtx, {
                    type: 'line',
                    data: {
                        labels: months,
                        datasets: [{
                            label: 'Прибыль (₽)',
                            data: profitValues,
                            borderColor: 'rgb(249, 115, 22)',
                            backgroundColor: 'rgba(249, 115, 22, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 5,
                            pointHoverRadius: 7,
                            pointBackgroundColor: 'rgb(249, 115, 22)',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { 
                                display: false 
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                padding: 12,
                                titleFont: { size: 14, weight: 'bold' },
                                bodyFont: { size: 13 },
                                callbacks: {
                                    label: function(context) {
                                        return 'Прибыль: ' + new Intl.NumberFormat('ru-RU').format(context.parsed.y) + ' ₽';
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: { 
                                    color: 'rgb(107, 114, 128)',
                                    font: { size: 11 },
                                    callback: function(value) {
                                        return new Intl.NumberFormat('ru-RU', { 
                                            notation: 'compact',
                                            maximumFractionDigits: 1
                                        }).format(value);
                                    }
                                },
                                grid: { 
                                    color: 'rgba(0, 0, 0, 0.05)',
                                    drawBorder: false
                                }
                            },
                            x: {
                                ticks: { 
                                    color: 'rgb(107, 114, 128)',
                                    font: { size: 11 }
                                },
                                grid: { display: false }
                            }
                        }
                    }
                });
            }
            
            // Подготовка данных для графика конверсии
            const conversionValues = [];
            for (let i = 1; i <= 12; i++) {
                conversionValues.push(conversionData[i] ?? 0);
            }
            
            // График конверсии по месяцам
            const conversionCtx = document.getElementById('conversionChart');
            if (conversionCtx) {
                new Chart(conversionCtx, {
                    type: 'line',
                    data: {
                        labels: months,
                        datasets: [{
                            label: 'Конверсия (%)',
                            data: conversionValues,
                            borderColor: 'rgb(236, 72, 153)',
                            backgroundColor: 'rgba(236, 72, 153, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 5,
                            pointHoverRadius: 7,
                            pointBackgroundColor: 'rgb(236, 72, 153)',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { 
                                display: false 
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                padding: 12,
                                titleFont: { size: 14, weight: 'bold' },
                                bodyFont: { size: 13 },
                                callbacks: {
                                    label: function(context) {
                                        return 'Конверсия: ' + context.parsed.y.toFixed(1) + '%';
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 100,
                                ticks: { 
                                    color: 'rgb(107, 114, 128)',
                                    font: { size: 11 },
                                    callback: function(value) {
                                        return value + '%';
                                    }
                                },
                                grid: { 
                                    color: 'rgba(0, 0, 0, 0.05)',
                                    drawBorder: false
                                }
                            },
                            x: {
                                ticks: { 
                                    color: 'rgb(107, 114, 128)',
                                    font: { size: 11 }
                                },
                                grid: { display: false }
                            }
                        }
                    }
                });
            }

            // Analog clock
            const dateEl = document.getElementById('clockDate');
            const timeTextEl = document.getElementById('clockTimeText');
            const hEl = document.getElementById('handHour');
            const mEl = document.getElementById('handMin');
            const sEl = document.getElementById('handSec');
            if (dateEl && timeTextEl && hEl && mEl && sEl) {
                const pad = (n) => String(n).padStart(2, '0');
                const tick = () => {
                    const d = new Date();
                    const h = d.getHours();
                    const m = d.getMinutes();
                    const s = d.getSeconds();
                    timeTextEl.textContent = `${pad(h)}:${pad(m)}:${pad(s)}`;
                    dateEl.textContent = d.toLocaleDateString('ru-RU', { weekday:'long', year:'numeric', month:'long', day:'numeric' });

                    const secDeg = (s / 60) * 360;
                    const minDeg = ((m + s/60) / 60) * 360;
                    const hourDeg = (((h % 12) + m/60) / 12) * 360;
                    sEl.style.transform = `rotate(${secDeg}deg)`;
                    mEl.style.transform = `rotate(${minDeg}deg)`;
                    hEl.style.transform = `rotate(${hourDeg}deg)`;
                };
                tick();
                window.setInterval(tick, 1000);
            }
        });
    </script>
    
    <style>
        /* Analog clock styles (dashboard) */
        .crm-analog-clock{
            width: 220px;
            height: 220px;
            border-radius: 999px;
            padding: 14px;
            background:
                radial-gradient(circle at 30% 30%, rgba(249,115,22,0.16), rgba(0,0,0,0) 55%),
                radial-gradient(circle at 70% 70%, rgba(239,68,68,0.12), rgba(0,0,0,0) 55%),
                linear-gradient(135deg, rgba(255,255,255,0.92), rgba(255,255,255,0.98));
            border: 0;
            box-shadow:
                0 24px 90px rgba(0,0,0,0.10),
                0 0 0 1px rgba(255,255,255,0.40) inset;
        }
        html.dark .crm-analog-clock{
            background:
                radial-gradient(circle at 30% 30%, rgba(249,115,22,0.18), rgba(0,0,0,0) 55%),
                radial-gradient(circle at 70% 70%, rgba(239,68,68,0.12), rgba(0,0,0,0) 55%),
                linear-gradient(135deg, rgba(15,23,42,0.94), rgba(15,23,42,0.78));
            border-color: rgba(255,255,255,0.10);
            box-shadow: 0 18px 70px rgba(0,0,0,0.55);
        }
        /* make the whole clock area feel attached to background */
        .crm-clock-float{
            position: relative;
        }
        .crm-clock-float::before{
            content:'';
            position:absolute;
            inset: -22px -28px;
            pointer-events:none;
            background:
                radial-gradient(380px 220px at 30% 30%, rgba(249,115,22,0.14), rgba(0,0,0,0) 60%),
                radial-gradient(380px 220px at 70% 60%, rgba(239,68,68,0.10), rgba(0,0,0,0) 62%);
            filter: blur(0px);
            opacity: .9;
            z-index: -1;
        }
        .crm-analog-face{
            width: 100%;
            height: 100%;
            border-radius: 999px;
            position: relative;
            background:
                radial-gradient(circle at 50% 50%, rgba(255,255,255,0.9), rgba(255,255,255,0) 60%),
                radial-gradient(circle at 50% 50%, rgba(0,0,0,0.06), rgba(0,0,0,0) 60%);
        }
        html.dark .crm-analog-face{
            background:
                radial-gradient(circle at 50% 50%, rgba(255,255,255,0.06), rgba(255,255,255,0) 60%),
                radial-gradient(circle at 50% 50%, rgba(0,0,0,0.55), rgba(0,0,0,0) 62%);
        }
        .crm-analog-tick{
            position:absolute;
            left: 50%;
            top: 50%;
            width: 4px;
            height: 16px;
            border-radius: 999px;
            background: rgba(15,23,42,0.35);
            transform-origin: 50% 92px;
            transform: translate(-50%, -92px);
        }
        html.dark .crm-analog-tick{ background: rgba(226,232,240,0.25); }
        .crm-analog-tick.t12{ transform: translate(-50%, -92px) rotate(0deg); }
        .crm-analog-tick.t3{ transform: translate(-50%, -92px) rotate(90deg); }
        .crm-analog-tick.t6{ transform: translate(-50%, -92px) rotate(180deg); }
        .crm-analog-tick.t9{ transform: translate(-50%, -92px) rotate(270deg); }

        .crm-analog-hand{
            position:absolute;
            left: 50%;
            top: 50%;
            transform-origin: 50% 100%;
            transform: rotate(0deg);
            border-radius: 999px;
        }
        .crm-analog-hand.hour{
            width: 6px;
            height: 54px;
            background: linear-gradient(180deg, rgba(15,23,42,0.92), rgba(15,23,42,0.55));
            margin-left: -3px;
            margin-top: -54px;
        }
        html.dark .crm-analog-hand.hour{
            background: linear-gradient(180deg, rgba(226,232,240,0.92), rgba(226,232,240,0.45));
        }
        .crm-analog-hand.min{
            width: 4px;
            height: 74px;
            background: linear-gradient(180deg, rgba(15,23,42,0.85), rgba(15,23,42,0.35));
            margin-left: -2px;
            margin-top: -74px;
        }
        html.dark .crm-analog-hand.min{
            background: linear-gradient(180deg, rgba(226,232,240,0.80), rgba(226,232,240,0.28));
        }
        .crm-analog-hand.sec{
            width: 2px;
            height: 82px;
            background: linear-gradient(180deg, #ef4444, #f97316);
            margin-left: -1px;
            margin-top: -82px;
            box-shadow: 0 0 14px rgba(249,115,22,0.25);
        }
        .crm-analog-center{
            position:absolute;
            left:50%;
            top:50%;
            width: 10px;
            height: 10px;
            transform: translate(-50%, -50%);
            border-radius: 999px;
            background: linear-gradient(135deg, #ef4444 0%, #f97316 100%);
            box-shadow: 0 0 0 3px rgba(255,255,255,0.75);
        }
        html.dark .crm-analog-center{
            box-shadow: 0 0 0 3px rgba(15,23,42,0.65);
        }

        /* Свечение для графиков в Дашборде (меньше чем в Аналитике) */
        html.dark #profitChart,
        html.dark #conversionChart {
            filter: drop-shadow(0 0 4px rgba(249, 115, 22, 0.2)) 
                    drop-shadow(0 0 8px rgba(249, 115, 22, 0.12));
        }
        
        html.dark #conversionChart {
            filter: drop-shadow(0 0 4px rgba(236, 72, 153, 0.2)) 
                    drop-shadow(0 0 8px rgba(236, 72, 153, 0.12));
        }
        
        /* Свечение для карточек в Дашборде */
        html.dark .dashboard .premium-card {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3),
                        0 2px 4px -1px rgba(0, 0, 0, 0.2),
                        0 0 0 1px rgba(16, 185, 129, 0.06);
            transition: box-shadow 0.3s ease;
        }
        
        html.dark .dashboard .premium-card:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.4),
                        0 4px 6px -2px rgba(0, 0, 0, 0.3),
                        0 0 0 1px rgba(16, 185, 129, 0.12),
                        0 0 10px rgba(16, 185, 129, 0.06);
        }
        
        /* Свечение для иконок в карточках дашборда */
        html.dark .dashboard .premium-card .w-12.h-12 {
            box-shadow: 0 0 6px rgba(16, 185, 129, 0.15),
                        0 0 10px rgba(16, 185, 129, 0.08);
            transition: box-shadow 0.3s ease;
        }
        
        html.dark .dashboard .premium-card:hover .w-12.h-12 {
            box-shadow: 0 0 10px rgba(16, 185, 129, 0.25),
                        0 0 15px rgba(16, 185, 129, 0.15);
        }
        
        /* Свечение для больших цифр в карточках */
        html.dark .dashboard .premium-card .text-3xl {
            text-shadow: 0 0 4px rgba(16, 185, 129, 0.2),
                         0 0 8px rgba(16, 185, 129, 0.1);
        }
    </style>
</x-app-layout>
