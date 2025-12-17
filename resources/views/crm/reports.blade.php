<x-app-layout>
    <div class="py-6">
        <div class="px-6 space-y-6">
            <!-- Карточки с ключевыми метриками -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="premium-card sm:rounded-2xl p-5 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-semibold text-slate-500 dark:text-slate-300 uppercase">Всего сделок</span>
                        <div class="w-8 h-8 rounded-lg bg-emerald-500/10 dark:bg-emerald-400/10 flex items-center justify-center">
                            <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3l9 5-9 5-9-5 9-5z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l9 5 9-5"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 17l9 5 9-5"/>
                            </svg>
                        </div>
                    </div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-gray-100" id="totalDealsMetric">0</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1" id="dealsChange">-</div>
                </div>
                
                <div class="premium-card sm:rounded-2xl p-5 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-semibold text-slate-500 dark:text-slate-300 uppercase">Выиграно</span>
                        <div class="w-8 h-8 rounded-lg bg-green-500/10 dark:bg-green-400/10 flex items-center justify-center">
                            <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 21h8"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 17v4"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4h10v3a5 5 0 01-10 0V4z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 7h2a5 5 0 01-2-4v4z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7h-2a5 5 0 002-4v4z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-gray-100" id="wonDealsMetric">0</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1" id="wonChange">-</div>
                </div>
                
                <div class="premium-card sm:rounded-2xl p-5 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-semibold text-slate-500 dark:text-slate-300 uppercase">Общий оборот</span>
                        <div class="w-8 h-8 rounded-lg bg-amber-500/10 dark:bg-amber-400/10 flex items-center justify-center">
                            <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7h16v10H4V7z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11a2 2 0 11-4 0 2 2 0 014 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 9h.01M7 15h.01M17 9h.01M17 15h.01"/>
                            </svg>
                        </div>
                    </div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-gray-100" id="totalProfitMetric">0 ₽</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1" id="profitChange">-</div>
                </div>
                
                <div class="premium-card sm:rounded-2xl p-5 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-semibold text-slate-500 dark:text-slate-300 uppercase">Конверсия</span>
                        <div class="w-8 h-8 rounded-lg bg-purple-500/10 dark:bg-purple-400/10 flex items-center justify-center">
                            <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2v3"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19v3"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2 12h3"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12h3"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8a4 4 0 100 8 4 4 0 000-8z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 12h.01"/>
                            </svg>
                        </div>
                    </div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-gray-100" id="conversionMetric">0%</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1" id="conversionChange">-</div>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="premium-card sm:rounded-2xl p-6 border border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Статистика по стадиям</h3>
                    <div id="stagesChart" class="h-64"></div>
                </div>
                <div class="premium-card sm:rounded-2xl p-6 border border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Прибыль по месяцам</h3>
                    <div id="monthlyChart" class="h-64"></div>
                </div>
                <div class="premium-card sm:rounded-2xl p-6 border border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Конверсия</h3>
                    <div id="conversionChart" class="h-64"></div>
                </div>
            </div>
            
            <!-- Разделитель секции -->
            <div class="mt-8 mb-6">
                <div class="flex items-center gap-4">
                    <div class="flex-1 h-px crm-shimmer-line-white"></div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100 px-4">Анализ динамики и активности</h2>
                    <div class="flex-1 h-px crm-shimmer-line-white"></div>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-3 text-center max-w-3xl mx-auto">
                    Ниже представлены графики, отражающие динамику сделок, активность по дням недели и распределение по компаниям. 
                    Эти данные помогают выявить закономерности в работе и оптимизировать процессы продаж.
                </p>
            </div>
            
            <!-- Дополнительные графики - асимметричное расположение -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 mt-6">
                <div class="md:col-span-8 premium-card rounded-3xl p-6 border border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Динамика сделок</h3>
                    <div id="dealsTrendChart" class="h-72"></div>
                </div>
                <div class="md:col-span-4 premium-card rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                    <h3 class="text-base font-semibold mb-4 text-gray-900 dark:text-gray-100">Топ компаний</h3>
                    <div id="companiesChart" class="h-72"></div>
                </div>
            </div>
            
            <!-- Информационный блок -->
            <div class="mt-8 mb-6">
                <div class="premium-card rounded-2xl p-6 border border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-gray-700">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-xl bg-blue-500/10 dark:bg-blue-400/10 flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 18h6"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 22h4"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2a7 7 0 00-4 12.7V17a1 1 0 001 1h6a1 1 0 001-1v-2.3A7 7 0 0012 2z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 17h4"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Ключевые выводы</h3>
                            <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                                Анализ динамики сделок показывает общую тенденцию развития бизнеса. Обратите внимание на пиковые периоды активности 
                                и дни недели с наибольшей эффективностью. Это поможет оптимизировать распределение ресурсов и планирование работы команды.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 mt-6">
                <div class="md:col-span-5 premium-card rounded-2xl p-6 border border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Воронка продаж</h3>
                    <div id="funnelChart" class="h-80"></div>
                </div>
                <div class="md:col-span-7 premium-card rounded-3xl p-6 border border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Активность по дням недели</h3>
                    <div id="weekdayChart" class="h-80"></div>
                </div>
            </div>

            <!-- Разделитель секции -->
            <div class="mt-8 mb-6">
                <div class="flex items-center gap-4">
                    <div class="flex-1 h-px crm-shimmer-line-white"></div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100 px-4">Финансовые показатели</h2>
                    <div class="flex-1 h-px crm-shimmer-line-white"></div>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-3 text-center max-w-3xl mx-auto">
                    Анализ среднего чека, распределения сумм и финансовых трендов позволяет оценить эффективность работы 
                    и выявить возможности для роста прибыльности.
                </p>
            </div>

            <!-- Новые графики - сложная компоновка -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 mt-6">
                <div class="md:col-span-4 premium-card rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                    <h3 class="text-base font-semibold mb-4 text-gray-900 dark:text-gray-100">Средний чек</h3>
                    <div id="avgTicketChart" class="h-64"></div>
                </div>
                <div class="md:col-span-8 premium-card rounded-3xl p-6 border border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Распределение по суммам</h3>
                    <div id="amountDistributionChart" class="h-64"></div>
                </div>
            </div>

            <!-- Статистический блок -->
            <div class="mt-8 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="premium-card rounded-xl p-5 border border-gray-200 dark:border-gray-700 bg-gradient-to-br from-emerald-50 to-teal-50 dark:from-gray-800 dark:to-gray-700">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-10 h-10 rounded-lg bg-emerald-500/10 dark:bg-emerald-400/10 flex items-center justify-center">
                                <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase">Средний чек</p>
                                <p class="text-lg font-bold text-gray-900 dark:text-gray-100" id="avgTicketStat">-</p>
                            </div>
                        </div>
                    </div>
                    <div class="premium-card rounded-xl p-5 border border-gray-200 dark:border-gray-700 bg-gradient-to-br from-purple-50 to-pink-50 dark:from-gray-800 dark:to-gray-700">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-10 h-10 rounded-lg bg-purple-500/10 dark:bg-purple-400/10 flex items-center justify-center">
                                <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase">Максимальная сумма</p>
                                <p class="text-lg font-bold text-gray-900 dark:text-gray-100" id="maxAmountStat">-</p>
                            </div>
                        </div>
                    </div>
                    <div class="premium-card rounded-xl p-5 border border-gray-200 dark:border-gray-700 bg-gradient-to-br from-amber-50 to-orange-50 dark:from-gray-800 dark:to-gray-700">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-10 h-10 rounded-lg bg-amber-500/10 dark:bg-amber-400/10 flex items-center justify-center">
                                <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase">Рост за период</p>
                                <p class="text-lg font-bold text-gray-900 dark:text-gray-100" id="growthStat">-</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 mt-6">
                <div class="md:col-span-7 premium-card rounded-3xl p-6 border border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Тренд конверсии</h3>
                    <div id="conversionTrendChart" class="h-72"></div>
                </div>
                <div class="md:col-span-5 premium-card rounded-2xl p-6 border border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Эффективность стадий</h3>
                    <div id="stageEfficiencyChart" class="h-72"></div>
                </div>
            </div>

            <!-- Разделитель секции -->
            <div class="mt-8 mb-6">
                <div class="flex items-center gap-4">
                    <div class="flex-1 h-px crm-shimmer-line-white"></div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100 px-4">Отраслевой и временной анализ</h2>
                    <div class="flex-1 h-px crm-shimmer-line-white"></div>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-3 text-center max-w-3xl mx-auto">
                    Детальный анализ по отраслям и времени активности помогает выявить наиболее перспективные направления 
                    и оптимальные часы для работы с клиентами.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 mt-6">
                <div class="md:col-span-6 premium-card rounded-2xl p-6 border border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Прибыльность по отраслям</h3>
                    <div id="industryChart" class="h-80"></div>
                </div>
                <div class="md:col-span-6 premium-card rounded-2xl p-6 border border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Активность по времени суток</h3>
                    <div id="hourlyChart" class="h-80"></div>
                </div>
            </div>

            <!-- Информационный блок с выводами -->
            <div class="mt-8 mb-6">
                <div class="premium-card rounded-2xl p-6 border border-gray-200 dark:border-gray-700 bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-gray-800 dark:to-gray-700">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-xl bg-indigo-500/10 dark:bg-indigo-400/10 flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Рекомендации</h3>
                            <ul class="text-sm text-gray-700 dark:text-gray-300 space-y-2 list-disc list-inside">
                                <li>Сфокусируйтесь на отраслях с наибольшей прибыльностью для увеличения общего оборота</li>
                                <li>Оптимизируйте рабочее время команды в соответствии с пиками активности клиентов</li>
                                <li>Проанализируйте скорость закрытия сделок для улучшения процесса продаж</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Разделитель секции -->
            <div class="mt-8 mb-6">
                <div class="flex items-center gap-4">
                    <div class="flex-1 h-px crm-shimmer-line-white"></div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100 px-4">Сравнительный анализ</h2>
                    <div class="flex-1 h-px crm-shimmer-line-white"></div>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-3 text-center max-w-3xl mx-auto">
                    Сравнение различных периодов и анализ скорости закрытия сделок позволяет оценить эффективность 
                    и выявить области для улучшения.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 mt-6">
                <div class="md:col-span-5 premium-card rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                    <h3 class="text-base font-semibold mb-4 text-gray-900 dark:text-gray-100">Скорость закрытия</h3>
                    <div id="dealVelocityChart" class="h-64"></div>
                </div>
                <div class="md:col-span-7 premium-card rounded-3xl p-6 border border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Сравнение периодов</h3>
                    <div id="periodComparisonChart" class="h-64"></div>
                </div>
            </div>

            <!-- Разделитель секции -->
            <div class="mt-8 mb-6">
                <div class="flex items-center gap-4">
                    <div class="flex-1 h-px crm-shimmer-line-white"></div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100 px-4">Детальная статистика</h2>
                    <div class="flex-1 h-px crm-shimmer-line-white"></div>
                </div>
            </div>

            <div class="premium-card sm:rounded-2xl p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-start justify-between gap-4 mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Сделки по сотрудникам</h3>
                        <button id="sortEmployeesBtn"
                                onclick="toggleEmployeeSort()"
                                class="mt-2 px-3 py-1.5 rounded-lg border border-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 text-sm font-semibold">
                            Сортировка: ↓
                        </button>
                    </div>
                    <div class="text-right">
                        <div class="text-xs text-gray-500 dark:text-gray-400">за сегодня <span id="empTodayDelta" class="text-green-600 dark:text-green-400 font-extrabold">+0</span></div>
                    </div>
                </div>

                <div id="employeeTable" class="divide-y divide-gray-200 dark:divide-gray-700"></div>
            </div>
            
            <div class="premium-card sm:rounded-2xl p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Детальный отчет</h3>
                    <button onclick="exportData()" class="px-4 py-2 rounded-lg text-white font-semibold transition-all shadow-lg" style="background: linear-gradient(135deg, #ef4444 0%, #f97316 100%); box-shadow: 0 10px 40px rgba(239, 68, 68, 0.4); border: none;">Экспорт CSV</button>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead>
                            <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Стадия</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Количество</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Сумма</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Средняя сумма</th>
                            </tr>
                        </thead>
                        <tbody id="reportTable" class="divide-y divide-gray-200 dark:divide-gray-700">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
    let stagesChart, monthlyChart, conversionChart, dealsTrendChart, companiesChart, funnelChart, weekdayChart;
    let avgTicketChart, amountDistributionChart, conversionTrendChart, stageEfficiencyChart, industryChart, hourlyChart, dealVelocityChart, periodComparisonChart;
    let employeeSortDir = 'desc'; // desc = больше успешных сверху
    let __lastUsers = [];
    let __lastDeals = [];

    // Функция для создания градиента
    function createGradient(ctx, color1, color2, direction = 'vertical') {
        if (direction === 'radial') {
            const gradient = ctx.createRadialGradient(200, 200, 0, 200, 200, 200);
            gradient.addColorStop(0, color1);
            gradient.addColorStop(1, color2);
            return gradient;
        } else if (direction === 'horizontal') {
            const gradient = ctx.createLinearGradient(0, 0, 400, 0);
            gradient.addColorStop(0, color1);
            gradient.addColorStop(1, color2);
            return gradient;
        } else {
            const gradient = ctx.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, color1);
            gradient.addColorStop(1, color2);
            return gradient;
        }
    }

    function getReportTheme() {
        const dark = document.documentElement.classList.contains('dark');
        return {
            dark,
            text: dark ? 'rgba(226,232,240,0.92)' : '#111827',
            muted: dark ? 'rgba(148,163,184,0.90)' : '#6b7280',
            grid: dark ? 'rgba(148,163,184,0.18)' : 'rgba(148,163,184,0.25)',
            tooltipBg: dark ? 'rgba(2,6,23,0.92)' : 'rgba(17,24,39,0.92)',
            tooltipBorder: dark ? 'rgba(255,255,255,0.10)' : 'rgba(255,255,255,0.15)',
            // Премиальные цвета для темной темы с градиентами
            success: dark ? '#10b981' : '#10b981',
            emerald: dark ? '#34d399' : '#10b981',
            emeraldGradient: dark ? ['#10b981', '#34d399'] : ['#10b981', '#10b981'],
            gold: dark ? '#fbbf24' : '#f59e0b',
            goldGradient: dark ? ['#fbbf24', '#f59e0b'] : ['#f59e0b', '#f59e0b'],
            sapphire: dark ? '#3b82f6' : '#2563eb',
            sapphireGradient: dark ? ['#3b82f6', '#2563eb'] : ['#2563eb', '#2563eb'],
            purple: dark ? '#a78bfa' : '#8b5cf6',
            purpleGradient: dark ? ['#a78bfa', '#8b5cf6'] : ['#8b5cf6', '#8b5cf6'],
            rose: dark ? '#f472b6' : '#ec4899',
            roseGradient: dark ? ['#f472b6', '#ec4899'] : ['#ec4899', '#ec4899'],
            cyan: dark ? '#06b6d4' : '#0891b2',
            cyanGradient: dark ? ['#06b6d4', '#0891b2'] : ['#0891b2', '#0891b2'],
            teal: dark ? '#14b8a6' : '#0d9488',
            tealGradient: dark ? ['#14b8a6', '#0d9488'] : ['#0d9488', '#0d9488'],
            indigo: dark ? '#6366f1' : '#4f46e5',
            indigoGradient: dark ? ['#6366f1', '#4f46e5'] : ['#4f46e5', '#4f46e5'],
            pink: dark ? '#ec4899' : '#db2777',
            orange: dark ? '#f97316' : '#ea580c',
            orangeGradient: dark ? ['#f97316', '#ea580c'] : ['#ea580c', '#ea580c'],
            red: dark ? '#ef4444' : '#dc2626',
            redGradient: dark ? ['#ef4444', '#dc2626'] : ['#dc2626', '#dc2626'],
            neutral: dark ? 'rgba(148,163,184,0.22)' : '#e5e7eb',
            // Премиальная палитра с градиентами для темной темы
            premiumColors: dark ? [
                '#10b981', '#fbbf24', '#3b82f6', '#a78bfa', '#f472b6',
                '#06b6d4', '#14b8a6', '#6366f1', '#f59e0b', '#ec4899'
            ] : [
                '#10b981', '#f59e0b', '#2563eb', '#8b5cf6', '#ec4899',
                '#0891b2', '#0d9488', '#4f46e5', '#f97316', '#db2777'
            ],
            premiumGradients: dark ? [
                ['#10b981', '#34d399'], ['#fbbf24', '#f59e0b'], ['#3b82f6', '#2563eb'],
                ['#a78bfa', '#8b5cf6'], ['#f472b6', '#ec4899'], ['#06b6d4', '#0891b2'],
                ['#14b8a6', '#0d9488'], ['#6366f1', '#4f46e5'], ['#f59e0b', '#f97316'],
                ['#ec4899', '#db2777']
            ] : null
        };
    }

    function restyleChart(chart, kind) {
        if (!chart) return;
        const t = getReportTheme();

        chart.options.plugins = chart.options.plugins || {};
        chart.options.plugins.legend = chart.options.plugins.legend || {};
        chart.options.plugins.legend.labels = chart.options.plugins.legend.labels || {};
        chart.options.plugins.legend.labels.color = t.muted;

        chart.options.plugins.tooltip = chart.options.plugins.tooltip || {};
        chart.options.plugins.tooltip.backgroundColor = t.tooltipBg;
        chart.options.plugins.tooltip.borderColor = t.tooltipBorder;
        chart.options.plugins.tooltip.borderWidth = 1;
        chart.options.plugins.tooltip.titleColor = t.text;
        chart.options.plugins.tooltip.bodyColor = t.text;

        if (chart.options.scales) {
            Object.values(chart.options.scales).forEach((sc) => {
                sc.ticks = sc.ticks || {};
                sc.ticks.color = t.muted;
                sc.grid = sc.grid || {};
                sc.grid.color = t.grid;
                sc.border = sc.border || {};
                sc.border.color = t.grid;
            });
        }

        if (kind === 'monthly' && chart.data?.datasets?.[0]) {
            if (t.dark) {
                const ctx = chart.canvas.getContext('2d');
                chart.data.datasets[0].backgroundColor = createGradient(ctx, t.emeraldGradient[0], t.emeraldGradient[1]);
            } else {
                chart.data.datasets[0].backgroundColor = t.success;
            }
        }
        if (kind === 'conversion' && chart.data?.datasets?.[0]) {
            chart.data.datasets[0].backgroundColor = t.dark ? [t.emerald, 'rgba(148,163,184,0.15)'] : [t.success, t.neutral];
            chart.data.datasets[0].borderColor = t.dark ? 'rgba(15,23,42,0.0)' : '#ffffff';
            chart.data.datasets[0].borderWidth = t.dark ? 0 : 2;
        }
        if (kind === 'stages' && chart.data?.datasets?.[0]) {
            if (t.dark && chart.data.datasets[0].backgroundColor) {
                const ctx = chart.canvas.getContext('2d');
                const colors = Array.isArray(chart.data.datasets[0].backgroundColor) 
                    ? chart.data.datasets[0].backgroundColor 
                    : new Array(chart.data.labels.length).fill(t.premiumColors[0]);
                chart.data.datasets[0].backgroundColor = colors.map((_, i) => {
                    if (t.premiumGradients && t.premiumGradients[i % t.premiumGradients.length]) {
                        const grad = t.premiumGradients[i % t.premiumGradients.length];
                        return createGradient(ctx, grad[0], grad[1], 'radial');
                    }
                    return t.premiumColors[i % t.premiumColors.length];
                });
            }
            chart.data.datasets[0].borderColor = t.dark ? 'rgba(15,23,42,0.0)' : '#ffffff';
            chart.data.datasets[0].borderWidth = t.dark ? 0 : 2;
        }
        if (kind === 'trend' && chart.data?.datasets) {
            chart.data.datasets.forEach((ds, i) => {
                if (i === 0) {
                    if (t.dark) {
                        const ctx = chart.canvas.getContext('2d');
                        ds.borderColor = t.emerald;
                        ds.backgroundColor = createGradient(ctx, t.emeraldGradient[0] + '40', t.emeraldGradient[1] + '10');
                    } else {
                        ds.borderColor = t.success;
                        ds.backgroundColor = t.success + '20';
                    }
                } else if (i === 1) {
                    if (t.dark) {
                        const ctx = chart.canvas.getContext('2d');
                        ds.borderColor = t.sapphire;
                        ds.backgroundColor = createGradient(ctx, t.sapphireGradient[0] + '40', t.sapphireGradient[1] + '10');
                    } else {
                        ds.borderColor = '#3b82f6';
                        ds.backgroundColor = '#3b82f620';
                    }
                }
            });
        }
        if (kind === 'companies' && chart.data?.datasets?.[0]) {
            if (t.dark && chart.data.datasets[0].backgroundColor) {
                const ctx = chart.canvas.getContext('2d');
                chart.data.datasets[0].backgroundColor = chart.data.datasets[0].data.map((_, i) => {
                    if (t.premiumGradients && t.premiumGradients[i % t.premiumGradients.length]) {
                        const grad = t.premiumGradients[i % t.premiumGradients.length];
                        return createGradient(ctx, grad[0], grad[1]);
                    }
                    return t.premiumColors[i % t.premiumColors.length];
                });
            }
        }
        if (kind === 'funnel' && chart.data?.datasets?.[0]) {
            if (t.dark && chart.data.datasets[0].backgroundColor) {
                const ctx = chart.canvas.getContext('2d');
                const gradients = [
                    t.redGradient, t.orangeGradient, t.goldGradient, t.emeraldGradient, t.sapphireGradient
                ];
                chart.data.datasets[0].backgroundColor = gradients.slice(0, chart.data.datasets[0].backgroundColor.length).map(grad => 
                    createGradient(ctx, grad[0], grad[1])
                );
            }
        }
        if (kind === 'weekday' && chart.data?.datasets?.[0]) {
            if (t.dark) {
                const ctx = chart.canvas.getContext('2d');
                chart.data.datasets[0].backgroundColor = createGradient(ctx, t.emeraldGradient[0], t.emeraldGradient[1]);
            } else {
                chart.data.datasets[0].backgroundColor = t.success;
            }
        }
        if (kind === 'avgTicket' && chart.data?.datasets?.[0]) {
            if (t.dark) {
                const ctx = chart.canvas.getContext('2d');
                chart.data.datasets[0].borderColor = t.gold;
                chart.data.datasets[0].backgroundColor = createGradient(ctx, t.goldGradient[0] + '40', t.goldGradient[1] + '10');
            } else {
                chart.data.datasets[0].backgroundColor = '#f59e0b';
                chart.data.datasets[0].borderColor = '#f59e0b';
            }
        }
        if (kind === 'amountDistribution' && chart.data?.datasets?.[0]) {
            if (t.dark && Array.isArray(chart.data.datasets[0].backgroundColor)) {
                const ctx = chart.canvas.getContext('2d');
                chart.data.datasets[0].backgroundColor = chart.data.datasets[0].backgroundColor.map((_, i) => {
                    if (t.premiumGradients && t.premiumGradients[i % t.premiumGradients.length]) {
                        const grad = t.premiumGradients[i % t.premiumGradients.length];
                        return createGradient(ctx, grad[0], grad[1], 'radial');
                    }
                    return t.premiumColors[i % t.premiumColors.length];
                });
            }
        }
        if (kind === 'conversionTrend' && chart.data?.datasets?.[0]) {
            if (t.dark) {
                const ctx = chart.canvas.getContext('2d');
                chart.data.datasets[0].borderColor = t.rose;
                chart.data.datasets[0].backgroundColor = createGradient(ctx, t.roseGradient[0] + '40', t.roseGradient[1] + '10');
            } else {
                chart.data.datasets[0].borderColor = '#ec4899';
                chart.data.datasets[0].backgroundColor = '#ec489920';
            }
        }
        if (kind === 'stageEfficiency' && chart.data?.datasets?.[0]) {
            if (t.dark && chart.data.datasets[0].backgroundColor) {
                chart.data.datasets[0].backgroundColor = chart.data.datasets[0].data.map((_, i) => {
                    const gradients = [
                        'linear-gradient(135deg, #10b981 0%, #34d399 100%)',
                        'linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%)',
                        'linear-gradient(135deg, #3b82f6 0%, #2563eb 100%)',
                        'linear-gradient(135deg, #a78bfa 0%, #8b5cf6 100%)',
                        'linear-gradient(135deg, #f472b6 0%, #ec4899 100%)'
                    ];
                    return gradients[i % gradients.length];
                });
            }
        }
        if (kind === 'industry' && chart.data?.datasets?.[0]) {
            if (t.dark && chart.data.datasets[0].backgroundColor) {
                const ctx = chart.canvas.getContext('2d');
                chart.data.datasets[0].backgroundColor = chart.data.datasets[0].data.map((_, i) => {
                    if (t.premiumGradients && t.premiumGradients[i % t.premiumGradients.length]) {
                        const grad = t.premiumGradients[i % t.premiumGradients.length];
                        return createGradient(ctx, grad[0], grad[1]);
                    }
                    return t.premiumColors[i % t.premiumColors.length];
                });
            }
        }
        if (kind === 'hourly' && chart.data?.datasets?.[0]) {
            if (t.dark) {
                const ctx = chart.canvas.getContext('2d');
                chart.data.datasets[0].backgroundColor = createGradient(ctx, t.cyanGradient[0], t.cyanGradient[1]);
            } else {
                chart.data.datasets[0].backgroundColor = '#0891b2';
            }
        }
        if (kind === 'dealVelocity' && chart.data?.datasets?.[0]) {
            if (t.dark) {
                const ctx = chart.canvas.getContext('2d');
                chart.data.datasets[0].borderColor = t.indigo;
                chart.data.datasets[0].backgroundColor = createGradient(ctx, t.indigoGradient[0] + '40', t.indigoGradient[1] + '10');
            } else {
                chart.data.datasets[0].borderColor = '#4f46e5';
                chart.data.datasets[0].backgroundColor = '#4f46e520';
            }
        }
        if (kind === 'periodComparison' && chart.data?.datasets) {
            chart.data.datasets.forEach((ds, i) => {
                if (t.dark) {
                    const ctx = chart.canvas.getContext('2d');
                    if (i === 0) {
                        ds.backgroundColor = createGradient(ctx, t.emeraldGradient[0], t.emeraldGradient[1]);
                    } else {
                        ds.backgroundColor = createGradient(ctx, t.sapphireGradient[0], t.sapphireGradient[1]);
                    }
                } else {
                    if (i === 0) {
                        ds.backgroundColor = t.success;
                    } else {
                        ds.backgroundColor = '#3b82f6';
                    }
                }
            });
        }

        chart.update('none');
    }

    function restyleAllReportCharts() {
        if (monthlyChart) restyleChart(monthlyChart, 'monthly');
        if (conversionChart) restyleChart(conversionChart, 'conversion');
        if (stagesChart) restyleChart(stagesChart, 'stages');
        if (dealsTrendChart) restyleChart(dealsTrendChart, 'trend');
        if (companiesChart) restyleChart(companiesChart, 'companies');
        if (funnelChart) restyleChart(funnelChart, 'funnel');
        if (weekdayChart) restyleChart(weekdayChart, 'weekday');
        if (avgTicketChart) restyleChart(avgTicketChart, 'avgTicket');
        if (amountDistributionChart) restyleChart(amountDistributionChart, 'amountDistribution');
        if (conversionTrendChart) restyleChart(conversionTrendChart, 'conversionTrend');
        if (contactsChart) restyleChart(contactsChart, 'contacts');
        if (industryChart) restyleChart(industryChart, 'industry');
        if (hourlyChart) restyleChart(hourlyChart, 'hourly');
        if (dealVelocityChart) restyleChart(dealVelocityChart, 'dealVelocity');
        if (periodComparisonChart) restyleChart(periodComparisonChart, 'periodComparison');
    }
    
    async function loadReports() {
        // Проверка что Chart.js загружен
        if (typeof Chart === 'undefined' || !Chart.Chart) {
            console.error('Chart.js not available');
            setTimeout(loadReports, 500);
            return;
        }
        
        try {
            // Все видят общую статистику по всем сделкам
            const [stagesRes, dealsRes, usersRes] = await Promise.all([
                fetch('/api/stages', { headers: { 'Accept': 'application/json' }, credentials: 'same-origin' }).catch(err => {
                    console.error('Error fetching stages:', err);
                    return { ok: false, json: async () => [] };
                }),
                fetch('/api/deals?per_page=10000&for_reports=1', { headers: { 'Accept': 'application/json' }, credentials: 'same-origin', cache: 'no-store' }).catch(err => {
                    console.error('Error fetching deals:', err);
                    return { ok: false, json: async () => ({ data: [] }) };
                }),
                fetch('/api/users', { headers: { 'Accept': 'application/json' }, credentials: 'same-origin' }).catch(err => {
                    console.error('Error fetching users:', err);
                    return { ok: false, json: async () => [] };
                })
            ]);
            
            let stages = [];
            let deals = [];
            let users = [];
            
            try {
                stages = stagesRes.ok ? await stagesRes.json() : [];
                if (!Array.isArray(stages)) stages = [];
            } catch (e) {
                console.error('Error parsing stages:', e);
                stages = [];
            }
            
            try {
                const dealsData = dealsRes.ok ? await dealsRes.json() : { data: [] };
                deals = Array.isArray(dealsData.data) ? dealsData.data : (Array.isArray(dealsData) ? dealsData : []);
            } catch (e) {
                console.error('Error parsing deals:', e);
                deals = [];
            }
            
            try {
                users = usersRes.ok ? await usersRes.json() : [];
                if (!Array.isArray(users)) users = [];
            } catch (e) {
                console.error('Error parsing users:', e);
                users = [];
            }
            
            __lastUsers = users;
            __lastDeals = deals;
            
            // Обновление метрик СРАЗУ - до всех графиков
            try {
                updateMetrics(deals);
            } catch (e) {
                console.error('Error updating metrics:', e);
            }
            
            // Статистика по стадиям - всегда рендерим
            setTimeout(() => {
                try {
                    renderStageStats(stages, deals);
                } catch (e) {
                    console.error('Error rendering stageStats:', e);
                    const stagesEl = document.getElementById('stagesChart');
                    if (stagesEl) {
                        stagesEl.innerHTML = '<div class="flex items-center justify-center h-full text-gray-500 dark:text-gray-400">Ошибка загрузки</div>';
                    }
                }
            }, 100);
            
            // По месяцам - только выигранные сделки (чистая прибыль)
            setTimeout(() => {
                try {
                    const monthly = {};
                    const wonDeals = deals.filter(d => d && d.stage && (d.stage.name === 'Won' || d.stage.name === 'Выиграно'));
                    wonDeals.forEach(d => {
                        if (d && d.created_at && d.amount) {
                            const date = new Date(d.created_at);
                            const monthKey = date.getFullYear() + '-' + String(date.getMonth() + 1).padStart(2, '0');
                            const monthName = date.toLocaleDateString('ru-RU', {month: 'long', year: 'numeric'});
                            if (!monthly[monthKey]) {
                                monthly[monthKey] = { name: monthName, amount: 0 };
                            }
                            monthly[monthKey].amount += parseFloat(d.amount) || 0;
                        }
                    });
                    
                    const monthlySorted = Object.keys(monthly).sort().map(k => ({
                        name: monthly[k].name,
                        amount: monthly[k].amount
                    }));
                    
                    if (monthlyChart) monthlyChart.destroy();
                    const monthlyEl = document.getElementById('monthlyChart');
                    if (monthlyEl && typeof Chart !== 'undefined') {
            let canvas = monthlyEl.querySelector('canvas');
            if (!canvas) {
                canvas = document.createElement('canvas');
                monthlyEl.innerHTML = '';
                monthlyEl.appendChild(canvas);
            }
            const t = getReportTheme();
            const monthlyCtx = canvas.getContext('2d');
            const monthlyBg = t.dark 
                ? createGradient(monthlyCtx, t.emeraldGradient[0], t.emeraldGradient[1])
                : t.success;
            
            monthlyChart = new Chart(canvas, {
                type: 'bar',
                data: {
                    labels: monthlySorted.length > 0 ? monthlySorted.map(m => m.name) : ['Нет данных'],
                    datasets: [{
                        label: 'Прибыль (₽)',
                        data: monthlySorted.length > 0 ? monthlySorted.map(m => m.amount) : [0],
                        backgroundColor: monthlyBg,
                        borderRadius: 10,
                        maxBarThickness: 56
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
                        setTimeout(() => restyleChart(monthlyChart, 'monthly'), 50);
                    }
                } catch (e) {
                    console.error('Error rendering monthlyChart:', e);
                }
            }, 150);
            
            // Конверсия - только за текущий месяц
            setTimeout(() => {
                try {
                    const now = new Date();
                    const monthStart = new Date(now.getFullYear(), now.getMonth(), 1);
                    const dealsMonth = deals.filter(d => {
                        if (!d || !d.created_at) return false;
                        const dt = new Date(d.created_at);
                        return dt >= monthStart && dt <= now;
                    });
                    const total = dealsMonth.length;
                    const won = dealsMonth.filter(d => d && d.stage && (d.stage.name === 'Won' || d.stage.name === 'Выиграно')).length;
                    const conversion = total > 0 ? (won / total * 100).toFixed(1) : 0;
                    
                    if (conversionChart) conversionChart.destroy();
                    const conversionEl = document.getElementById('conversionChart');
                    if (conversionEl && typeof Chart !== 'undefined') {
            let canvas = conversionEl.querySelector('canvas');
            if (!canvas) {
                canvas = document.createElement('canvas');
                conversionEl.innerHTML = '';
                conversionEl.appendChild(canvas);
            }
            const t = getReportTheme();
            const convCtx = canvas.getContext('2d');
            const convColors = t.dark 
                ? [
                    createGradient(convCtx, t.emeraldGradient[0], t.emeraldGradient[1], 'radial'),
                    'rgba(148,163,184,0.15)'
                ]
                : [t.success, t.neutral];
            
            conversionChart = new Chart(canvas, {
                type: 'doughnut',
                data: {
                    labels: ['Выиграно', 'Остальные'],
                    datasets: [{
                        data: [won, Math.max(0, total - won)],
                        backgroundColor: convColors,
                        cutout: '62%',
                        borderWidth: t.dark ? 0 : 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
                        setTimeout(() => restyleChart(conversionChart, 'conversion'), 50);
                    }
                } catch (e) {
                    console.error('Error rendering conversionChart:', e);
                }
            }, 200);
            
            // Таблица детального отчета
            setTimeout(() => {
                try {
                    if (Array.isArray(stages) && stages.length > 0) {
                        const stageNames = {
                            'Lead': 'Лид', 'Qualified': 'Квалификация', 'Proposal': 'Предложение',
                            'Negotiation': 'Переговоры', 'Won': 'Выиграно', 'Lost': 'Проиграно',
                            'Лид': 'Лид', 'Квалификация': 'Квалификация', 'Предложение': 'Предложение',
                            'Переговоры': 'Переговоры', 'Выиграно': 'Выиграно', 'Проиграно': 'Проиграно'
                        };
                        
                        const stageStats = stages.map(s => ({
                            name: stageNames[s.name] || s.name,
                            count: deals.filter(d => d && d.stage_id == s.id).length,
                            amount: deals.filter(d => d && d.stage_id == s.id).reduce((sum, d) => sum + (parseFloat(d && d.amount ? d.amount : 0) || 0), 0)
                        }));
                        
                        const table = document.getElementById('reportTable');
                        if (table) {
                            table.innerHTML = stageStats.map(s => `
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">${s.name}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">${s.count}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">${Math.round(s.amount).toLocaleString('ru-RU')} ₽</td>
                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">${s.count > 0 ? Math.round(s.amount / s.count).toLocaleString('ru-RU') : 0} ₽</td>
                                </tr>
                            `).join('');
                        }
                    }
                } catch (e) {
                    console.error('Error rendering report table:', e);
                }
            }, 250);

            // Рендеринг всех графиков с задержками для стабильности
            setTimeout(() => {
                try {
                    renderDealsByEmployees(__lastUsers, __lastDeals);
                } catch (e) { console.error('Error rendering dealsByEmployees:', e); }
            }, 300);
            
            // Дополнительные графики
            setTimeout(() => {
                try {
                    renderDealsTrendChart(deals);
                } catch (e) { console.error('Error rendering dealsTrendChart:', e); }
            }, 350);
            
            setTimeout(() => {
                try {
                    renderCompaniesChart(deals);
                } catch (e) { console.error('Error rendering companiesChart:', e); }
            }, 400);
            
            setTimeout(() => {
                try {
                    if (Array.isArray(stages) && stages.length > 0) {
                        renderFunnelChart(stages, deals);
                    }
                } catch (e) { console.error('Error rendering funnelChart:', e); }
            }, 450);
            
            setTimeout(() => {
                try {
                    renderWeekdayChart(deals);
                } catch (e) { console.error('Error rendering weekdayChart:', e); }
            }, 500);
        
            // Новые графики
            setTimeout(() => {
                try {
                    renderAvgTicketChart(deals);
                } catch (e) { console.error('Error rendering avgTicketChart:', e); }
            }, 550);
            
            setTimeout(() => {
                try {
                    renderAmountDistributionChart(deals);
                } catch (e) { console.error('Error rendering amountDistributionChart:', e); }
            }, 600);
            
            setTimeout(() => {
                try {
                    renderConversionTrendChart(deals);
                } catch (e) { console.error('Error rendering conversionTrendChart:', e); }
            }, 650);
            
            setTimeout(() => {
                try {
                    if (Array.isArray(stages) && stages.length > 0) {
                        renderStageEfficiencyChart(stages, deals);
                    }
                } catch (e) { console.error('Error rendering stageEfficiencyChart:', e); }
            }, 700);
            
            setTimeout(() => {
                try {
                    renderIndustryChart(deals);
                } catch (e) { console.error('Error rendering industryChart:', e); }
            }, 750);
            
            setTimeout(() => {
                try {
                    renderHourlyChart(deals);
                } catch (e) { console.error('Error rendering hourlyChart:', e); }
            }, 800);
            
            setTimeout(() => {
                try {
                    renderDealVelocityChart(deals);
                } catch (e) { console.error('Error rendering dealVelocityChart:', e); }
            }, 850);
            
            setTimeout(() => {
                try {
                    renderPeriodComparisonChart(deals);
                } catch (e) { console.error('Error rendering periodComparisonChart:', e); }
            }, 900);
        } catch (error) {
            console.error('Error in loadReports:', error);
        }
    }
    
    function updateMetrics(deals) {
        if (!Array.isArray(deals)) {
            deals = [];
        }
        
        const now = new Date();
        const currentMonth = new Date(now.getFullYear(), now.getMonth(), 1);
        const lastMonth = new Date(now.getFullYear(), now.getMonth() - 1, 1);
        const lastMonthEnd = new Date(now.getFullYear(), now.getMonth(), 0);
        
        const totalDeals = deals.length || 0;
        const wonDeals = deals.filter(d => d && d.stage && (d.stage.name === 'Won' || d.stage.name === 'Выиграно')).length || 0;
        // Общий оборот - сумма всех сделок (не только выигранных)
        const totalProfit = deals.reduce((sum, d) => sum + (parseFloat(d && d.amount ? d.amount : 0) || 0), 0);
        const conversion = totalDeals > 0 ? ((wonDeals / totalDeals) * 100).toFixed(1) : '0.0';
        
        // Текущий месяц
        const currentMonthDeals = deals.filter(d => {
            if (!d || !d.created_at) return false;
            const date = new Date(d.created_at);
            return date >= currentMonth && date <= now;
        });
        const currentMonthWon = currentMonthDeals.filter(d => d && d.stage && (d.stage.name === 'Won' || d.stage.name === 'Выиграно')).length || 0;
        // Общий оборот текущего месяца - сумма всех сделок
        const currentMonthProfit = currentMonthDeals.reduce((sum, d) => sum + (parseFloat(d && d.amount ? d.amount : 0) || 0), 0);
        
        // Прошлый месяц
        const lastMonthDeals = deals.filter(d => {
            if (!d || !d.created_at) return false;
            const date = new Date(d.created_at);
            return date >= lastMonth && date <= lastMonthEnd;
        });
        const lastMonthWon = lastMonthDeals.filter(d => d && d.stage && (d.stage.name === 'Won' || d.stage.name === 'Выиграно')).length || 0;
        // Общий оборот прошлого месяца - сумма всех сделок
        const lastMonthProfit = lastMonthDeals.reduce((sum, d) => sum + (parseFloat(d && d.amount ? d.amount : 0) || 0), 0);
        
        // Обновление DOM
        const totalDealsEl = document.getElementById('totalDealsMetric');
        const dealsChangeEl = document.getElementById('dealsChange');
        if (totalDealsEl) totalDealsEl.textContent = totalDeals.toLocaleString('ru-RU');
        if (dealsChangeEl) {
            const change = currentMonthDeals.length - lastMonthDeals.length;
            const changePct = lastMonthDeals.length > 0 ? ((change / lastMonthDeals.length) * 100).toFixed(1) : 0;
            dealsChangeEl.innerHTML = change >= 0 
                ? `<span class="text-green-600 dark:text-green-400">+${change} (${changePct}%)</span> vs прошлый месяц`
                : `<span class="text-red-600 dark:text-red-400">${change} (${changePct}%)</span> vs прошлый месяц`;
        }
        
        const wonDealsEl = document.getElementById('wonDealsMetric');
        const wonChangeEl = document.getElementById('wonChange');
        if (wonDealsEl) wonDealsEl.textContent = wonDeals.toLocaleString('ru-RU');
        if (wonChangeEl) {
            const change = currentMonthWon - lastMonthWon;
            const changePct = lastMonthWon > 0 ? ((change / lastMonthWon) * 100).toFixed(1) : 0;
            wonChangeEl.innerHTML = change >= 0 
                ? `<span class="text-green-600 dark:text-green-400">+${change} (${changePct}%)</span> vs прошлый месяц`
                : `<span class="text-red-600 dark:text-red-400">${change} (${changePct}%)</span> vs прошлый месяц`;
        }
        
        const totalProfitEl = document.getElementById('totalProfitMetric');
        const profitChangeEl = document.getElementById('profitChange');
        if (totalProfitEl) totalProfitEl.textContent = Math.round(totalProfit).toLocaleString('ru-RU') + ' ₽';
        if (profitChangeEl) {
            const change = currentMonthProfit - lastMonthProfit;
            const changePct = lastMonthProfit > 0 ? ((change / lastMonthProfit) * 100).toFixed(1) : 0;
            profitChangeEl.innerHTML = change >= 0 
                ? `<span class="text-green-600 dark:text-green-400">+${Math.round(change).toLocaleString('ru-RU')} ₽ (${changePct}%)</span> vs прошлый месяц`
                : `<span class="text-red-600 dark:text-red-400">${Math.round(change).toLocaleString('ru-RU')} ₽ (${changePct}%)</span> vs прошлый месяц`;
        }
        
        const conversionEl = document.getElementById('conversionMetric');
        const conversionChangeEl = document.getElementById('conversionChange');
        if (conversionEl) conversionEl.textContent = conversion + '%';
        if (conversionChangeEl) {
            const currentConv = currentMonthDeals.length > 0 ? (currentMonthWon / currentMonthDeals.length * 100).toFixed(1) : 0;
            const lastConv = lastMonthDeals.length > 0 ? (lastMonthWon / lastMonthDeals.length * 100).toFixed(1) : 0;
            const change = parseFloat(currentConv) - parseFloat(lastConv);
            conversionChangeEl.innerHTML = change >= 0 
                ? `<span class="text-green-600 dark:text-green-400">+${change.toFixed(1)}%</span> vs прошлый месяц`
                : `<span class="text-red-600 dark:text-red-400">${change.toFixed(1)}%</span> vs прошлый месяц`;
        }
        
        // Обновление дополнительных статистических блоков
        const avgTicketStatEl = document.getElementById('avgTicketStat');
        const maxAmountStatEl = document.getElementById('maxAmountStat');
        const growthStatEl = document.getElementById('growthStat');
        
        if (avgTicketStatEl) {
            const avgTicket = wonDeals > 0 ? totalProfit / wonDeals : 0;
            avgTicketStatEl.textContent = Math.round(avgTicket).toLocaleString('ru-RU') + ' ₽';
        }
        
        if (maxAmountStatEl) {
            const maxAmount = deals.length > 0 
                ? Math.max(...deals.map(d => parseFloat(d && d.amount ? d.amount : 0) || 0))
                : 0;
            maxAmountStatEl.textContent = Math.round(maxAmount).toLocaleString('ru-RU') + ' ₽';
        }
        
        if (growthStatEl) {
            const growth = lastMonthProfit > 0 
                ? ((currentMonthProfit - lastMonthProfit) / lastMonthProfit * 100).toFixed(1)
                : (currentMonthProfit > 0 ? '100.0' : '0.0');
            growthStatEl.innerHTML = parseFloat(growth) >= 0
                ? `<span class="text-green-600 dark:text-green-400">+${growth}%</span>`
                : `<span class="text-red-600 dark:text-red-400">${growth}%</span>`;
        }
    }

    function renderStageStats(stages, deals) {
        if (!Array.isArray(stages)) {
            stages = [];
        }
        if (!Array.isArray(deals)) {
            deals = [];
        }
        
        const stageNames = {
            'Lead': 'Лид', 'Qualified': 'Квалификация', 'Proposal': 'Предложение',
            'Negotiation': 'Переговоры', 'Won': 'Выиграно', 'Lost': 'Проиграно',
            'Лид': 'Лид', 'Квалификация': 'Квалификация', 'Предложение': 'Предложение',
            'Переговоры': 'Переговоры', 'Выиграно': 'Выиграно', 'Проиграно': 'Проиграно'
        };
        
        const stageStats = stages.map(s => ({
            name: stageNames[s.name] || s.name,
            count: deals.filter(d => d && d.stage_id == s.id).length,
            amount: deals.filter(d => d && d.stage_id == s.id).reduce((sum, d) => sum + (parseFloat(d.amount) || 0), 0)
        })); // Показываем все стадии, даже без сделок
        
        if (stagesChart) stagesChart.destroy();
        const stagesEl = document.getElementById('stagesChart');
        if (stagesEl) {
            if (stageStats.length === 0) {
                stagesEl.innerHTML = '<div class="flex items-center justify-center h-full text-gray-500 dark:text-gray-400">Нет данных</div>';
                return;
            }
            
            let canvas = stagesEl.querySelector('canvas');
            if (!canvas) {
                canvas = document.createElement('canvas');
                stagesEl.innerHTML = '';
                stagesEl.appendChild(canvas);
            }
            const t = getReportTheme();
            const stagesCtx = canvas.getContext('2d');
            const stagesColors = t.dark && t.premiumGradients
                ? stageStats.map((_, i) => {
                    const grad = t.premiumGradients[i % t.premiumGradients.length];
                    return createGradient(stagesCtx, grad[0], grad[1], 'radial');
                })
                : (t.dark ? t.premiumColors.slice(0, stageStats.length) : ['#60a5fa', '#34d399', '#fbbf24', '#fb7185', '#a78bfa', '#f472b6']);
            
            stagesChart = new Chart(canvas, {
                type: 'doughnut',
                data: {
                    labels: stageStats.map(s => s.name),
                    datasets: [{
                        data: stageStats.map(s => s.count),
                        backgroundColor: stagesColors,
                        cutout: '58%',
                        borderWidth: t.dark ? 0 : 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
            setTimeout(() => restyleChart(stagesChart, 'stages'), 50);
        }
    }


    function toggleEmployeeSort() {
        employeeSortDir = employeeSortDir === 'desc' ? 'asc' : 'desc';
        const btn = document.getElementById('sortEmployeesBtn');
        if (btn) btn.textContent = employeeSortDir === 'desc' ? 'Сортировка: ↓' : 'Сортировка: ↑';
        renderDealsByEmployees(__lastUsers, __lastDeals);
    }

    function monthKey(d) {
        const y = d.getFullYear();
        const m = String(d.getMonth() + 1).padStart(2, '0');
        return `${y}-${m}`;
    }

    function monthLabelFromKey(key) {
        const [y, m] = key.split('-').map(x => parseInt(x, 10));
        const d = new Date(y, (m || 1) - 1, 1);
        return d.toLocaleDateString('ru-RU', { month: 'short', year: '2-digit' });
    }

    function renderDealsByEmployees(users, deals) {
        const now = new Date();
        const monthStart = new Date(now.getFullYear(), now.getMonth(), 1);

        const wonDeals = deals.filter(d => d.stage && (d.stage.name === 'Won' || d.stage.name === 'Выиграно'));
        // Статистика за текущий месяц
        const wonDealsMonth = wonDeals.filter(d => {
            if (!d.created_at) return false;
            const dt = new Date(d.created_at);
            return dt >= monthStart && dt <= now;
        });

        const today = new Date();
        const todayKey = `${today.getFullYear()}-${String(today.getMonth()+1).padStart(2,'0')}-${String(today.getDate()).padStart(2,'0')}`;
        const toYmd = (dt) => `${dt.getFullYear()}-${String(dt.getMonth()+1).padStart(2,'0')}-${String(dt.getDate()).padStart(2,'0')}`;
        const todayWon = wonDealsMonth.filter(d => d.created_at && toYmd(new Date(d.created_at)) === todayKey).length;
        const deltaEl = document.getElementById('empTodayDelta');
        if (deltaEl) deltaEl.textContent = `+${todayWon}`;

        // userId -> { name, totalWon, perMonthCount, perMonthAmount }
        const userMap = new Map();
        (Array.isArray(users) ? users : []).forEach(u => {
            // В аналитике показываем только обычных юзеров (без админов и менеджеров)
            if (!u || u.is_admin || u.role === 'manager' || u.role === 'admin') return;
            userMap.set(String(u.id), {
                id: String(u.id),
                name: u.name || `User ${u.id}`,
                totalWon: 0,
                totalAmount: 0,
            });
        });

        wonDealsMonth.forEach(d => {
            const uid = d.user_id != null ? String(d.user_id) : null;
            if (!uid || !userMap.has(uid)) return;
            const rec = userMap.get(uid);
            rec.totalWon += 1;
            rec.totalAmount += (parseFloat(d.amount) || 0);
        });

        const userRows = Array.from(userMap.values())
            .filter(u => u.totalWon > 0);

        userRows.sort((a, b) => employeeSortDir === 'desc'
            ? (b.totalWon - a.totalWon)
            : (a.totalWon - b.totalWon)
        );

        const maxWon = Math.max(1, ...userRows.map(u => u.totalWon));
        const tableEl = document.getElementById('employeeTable');
        if (!tableEl) return;

        const rowsHtml = userRows.map(u => {
            const pct = Math.round((u.totalWon / maxWon) * 100);
            return `
                <div class="py-3 flex items-center justify-between gap-4">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-baseline gap-2">
                            <div class="font-semibold text-gray-900 dark:text-gray-100 truncate">${u.name}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400 whitespace-nowrap">${u.totalWon} сделок</div>
                        </div>
                        <div class="mt-2 h-1.5 rounded-full bg-gray-200 dark:bg-gray-700 overflow-hidden">
                            <div class="h-full rounded-full" style="width:${pct}%; background: linear-gradient(135deg, #ef4444 0%, #f97316 100%);"></div>
                        </div>
                    </div>
                    <div class="text-right font-semibold text-gray-900 dark:text-gray-100 whitespace-nowrap">
                        ${Math.round(u.totalAmount).toLocaleString('ru-RU')} ₽
                    </div>
                </div>
            `;
        }).join('');

        tableEl.innerHTML = rowsHtml || '<div class="py-4 text-sm text-gray-500 dark:text-gray-400">Нет данных</div>';
    }
    
    function renderDealsTrendChart(deals) {
        // Динамика сделок за последние 30 дней
        const days = [];
        const counts = [];
        const amounts = [];
        const now = new Date();
        
        for (let i = 29; i >= 0; i--) {
            const date = new Date(now);
            date.setDate(date.getDate() - i);
            const dateKey = date.toISOString().split('T')[0];
            days.push(date.toLocaleDateString('ru-RU', { day: 'numeric', month: 'short' }));
            
            const dayDeals = deals.filter(d => {
                if (!d.created_at) return false;
                return d.created_at.startsWith(dateKey);
            });
            counts.push(dayDeals.length);
            amounts.push(dayDeals.reduce((sum, d) => sum + (parseFloat(d.amount) || 0), 0));
        }
        
        if (dealsTrendChart) dealsTrendChart.destroy();
        const el = document.getElementById('dealsTrendChart');
        if (el) {
            let canvas = el.querySelector('canvas');
            if (!canvas) {
                canvas = document.createElement('canvas');
                el.innerHTML = '';
                el.appendChild(canvas);
            }
            const t = getReportTheme();
            const trendCtx = canvas.getContext('2d');
            const trendBg1 = t.dark 
                ? createGradient(trendCtx, t.emeraldGradient[0] + '40', t.emeraldGradient[1] + '10')
                : t.success + '20';
            const trendBg2 = t.dark 
                ? createGradient(trendCtx, t.sapphireGradient[0] + '40', t.sapphireGradient[1] + '10')
                : '#3b82f620';
            
            dealsTrendChart = new Chart(canvas, {
                type: 'line',
                data: {
                    labels: days,
                    datasets: [{
                        label: 'Количество сделок',
                        data: counts,
                        borderColor: t.dark ? t.emerald : t.success,
                        backgroundColor: trendBg1,
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        pointBackgroundColor: t.dark ? t.emerald : t.success,
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        yAxisID: 'y'
                    }, {
                        label: 'Сумма (₽)',
                        data: amounts,
                        borderColor: t.dark ? t.sapphire : '#3b82f6',
                        backgroundColor: trendBg2,
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        pointBackgroundColor: t.dark ? t.sapphire : '#3b82f6',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        yAxisID: 'y1'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { labels: { color: t.muted } }
                    },
                    scales: {
                        y: {
                            type: 'linear',
                            position: 'left',
                            ticks: { color: t.muted },
                            grid: { color: t.grid }
                        },
                        y1: {
                            type: 'linear',
                            position: 'right',
                            ticks: { color: t.muted },
                            grid: { drawOnChartArea: false }
                        },
                        x: {
                            ticks: { color: t.muted },
                            grid: { color: t.grid }
                        }
                    }
                }
            });
            setTimeout(() => restyleChart(dealsTrendChart, 'trend'), 50);
        }
    }
    
    function renderCompaniesChart(deals) {
        // Топ-10 компаний по количеству сделок
        const companyMap = new Map();
        deals.forEach(d => {
            if (!d.company || !d.company.name) return;
            const name = d.company.name;
            if (!companyMap.has(name)) {
                companyMap.set(name, { count: 0, amount: 0 });
            }
            const rec = companyMap.get(name);
            rec.count += 1;
            rec.amount += (parseFloat(d.amount) || 0);
        });
        
        const topCompanies = Array.from(companyMap.entries())
            .map(([name, data]) => ({ name, ...data }))
            .sort((a, b) => b.count - a.count)
            .slice(0, 10);
        
        if (companiesChart) companiesChart.destroy();
        const el = document.getElementById('companiesChart');
        if (el) {
            let canvas = el.querySelector('canvas');
            if (!canvas) {
                canvas = document.createElement('canvas');
                el.innerHTML = '';
                el.appendChild(canvas);
            }
            const t = getReportTheme();
            const compCtx = canvas.getContext('2d');
            const compColors = t.dark && t.premiumGradients
                ? topCompanies.map((_, i) => {
                    const grad = t.premiumGradients[i % t.premiumGradients.length];
                    return createGradient(compCtx, grad[0], grad[1]);
                })
                : topCompanies.map((_, i) => t.premiumColors[i % t.premiumColors.length]);
            
            companiesChart = new Chart(canvas, {
                type: 'bar',
                data: {
                    labels: topCompanies.map(c => c.name.length > 15 ? c.name.substring(0, 15) + '...' : c.name),
                    datasets: [{
                        label: 'Сделок',
                        data: topCompanies.map(c => c.count),
                        backgroundColor: compColors,
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { color: t.muted },
                            grid: { color: t.grid }
                        },
                        x: {
                            ticks: { color: t.muted, maxRotation: 45, minRotation: 45 },
                            grid: { display: false }
                        }
                    }
                }
            });
            setTimeout(() => restyleChart(companiesChart, 'companies'), 50);
        }
    }
    
    function renderFunnelChart(stages, deals) {
        // Воронка продаж - количество сделок на каждой стадии
        const stageOrder = ['Lead', 'Лид', 'Qualified', 'Квалификация', 'Proposal', 'Предложение', 'Negotiation', 'Переговоры', 'Won', 'Выиграно'];
        const stageNames = {
            'Lead': 'Лид', 'Qualified': 'Квалификация', 'Proposal': 'Предложение',
            'Negotiation': 'Переговоры', 'Won': 'Выиграно', 'Lost': 'Проиграно',
            'Лид': 'Лид', 'Квалификация': 'Квалификация', 'Предложение': 'Предложение',
            'Переговоры': 'Переговоры', 'Выиграно': 'Выиграно', 'Проиграно': 'Проиграно'
        };
        
        const orderedStages = stageOrder
            .map(name => stages.find(s => s.name === name))
            .filter(Boolean)
            .slice(0, 5);
        
        const labels = orderedStages.map(s => stageNames[s.name] || s.name);
        const data = orderedStages.map(s => deals.filter(d => d.stage_id == s.id).length);
        
        if (funnelChart) funnelChart.destroy();
        const el = document.getElementById('funnelChart');
        if (el) {
            let canvas = el.querySelector('canvas');
            if (!canvas) {
                canvas = document.createElement('canvas');
                el.innerHTML = '';
                el.appendChild(canvas);
            }
            const t = getReportTheme();
            const funnelCtx = canvas.getContext('2d');
            const funnelColors = t.dark 
                ? [
                    createGradient(funnelCtx, t.redGradient[0], t.redGradient[1]),
                    createGradient(funnelCtx, t.orangeGradient[0], t.orangeGradient[1]),
                    createGradient(funnelCtx, t.goldGradient[0], t.goldGradient[1]),
                    createGradient(funnelCtx, t.emeraldGradient[0], t.emeraldGradient[1]),
                    createGradient(funnelCtx, t.sapphireGradient[0], t.sapphireGradient[1])
                ].slice(0, data.length)
                : ['#ef4444', '#f97316', '#fbbf24', '#84cc16', '#10b981'].slice(0, data.length);
            
            funnelChart = new Chart(canvas, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Сделок',
                        data: data,
                        backgroundColor: funnelColors,
                        borderRadius: 8
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            ticks: { color: t.muted },
                            grid: { color: t.grid }
                        },
                        y: {
                            ticks: { color: t.muted },
                            grid: { display: false }
                        }
                    }
                }
            });
            setTimeout(() => restyleChart(funnelChart, 'funnel'), 50);
        }
    }
    
    function renderWeekdayChart(deals) {
        // Активность по дням недели
        const weekdays = ['Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс'];
        const weekdayCounts = [0, 0, 0, 0, 0, 0, 0];
        
        deals.forEach(d => {
            if (!d.created_at) return;
            const date = new Date(d.created_at);
            const day = date.getDay(); // 0 = воскресенье, 1 = понедельник
            const index = day === 0 ? 6 : day - 1; // Преобразуем в Пн=0, Вс=6
            weekdayCounts[index] += 1;
        });
        
        if (weekdayChart) weekdayChart.destroy();
        const el = document.getElementById('weekdayChart');
        if (el) {
            let canvas = el.querySelector('canvas');
            if (!canvas) {
                canvas = document.createElement('canvas');
                el.innerHTML = '';
                el.appendChild(canvas);
            }
            const t = getReportTheme();
            const weekdayCtx = canvas.getContext('2d');
            const weekdayBg = t.dark 
                ? createGradient(weekdayCtx, t.emeraldGradient[0], t.emeraldGradient[1])
                : t.success;
            
            weekdayChart = new Chart(canvas, {
                type: 'bar',
                data: {
                    labels: weekdays,
                    datasets: [{
                        label: 'Сделок',
                        data: weekdayCounts,
                        backgroundColor: weekdayBg,
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { color: t.muted },
                            grid: { color: t.grid }
                        },
                        x: {
                            ticks: { color: t.muted },
                            grid: { display: false }
                        }
                    }
                }
            });
            setTimeout(() => restyleChart(weekdayChart, 'weekday'), 50);
        }
    }
    
    function renderAvgTicketChart(deals) {
        const monthly = {};
        deals.forEach(d => {
            if (d.created_at && d.amount) {
                const date = new Date(d.created_at);
                const monthKey = date.getFullYear() + '-' + String(date.getMonth() + 1).padStart(2, '0');
                const monthName = date.toLocaleDateString('ru-RU', {month: 'short', year: 'numeric'});
                if (!monthly[monthKey]) {
                    monthly[monthKey] = { name: monthName, total: 0, count: 0 };
                }
                monthly[monthKey].total += parseFloat(d.amount) || 0;
                monthly[monthKey].count += 1;
            }
        });
        
        const monthlySorted = Object.keys(monthly).sort().map(k => ({
            name: monthly[k].name,
            avg: monthly[k].count > 0 ? monthly[k].total / monthly[k].count : 0
        }));
        
        if (avgTicketChart) avgTicketChart.destroy();
        const el = document.getElementById('avgTicketChart');
        if (el) {
            let canvas = el.querySelector('canvas');
            if (!canvas) {
                canvas = document.createElement('canvas');
                el.innerHTML = '';
                el.appendChild(canvas);
            }
            const t = getReportTheme();
            const avgTicketCtx = canvas.getContext('2d');
            const avgTicketBg = t.dark 
                ? createGradient(avgTicketCtx, t.goldGradient[0] + '40', t.goldGradient[1] + '10')
                : '#f59e0b20';
            
            avgTicketChart = new Chart(canvas, {
                type: 'line',
                data: {
                    labels: monthlySorted.length > 0 ? monthlySorted.map(m => m.name) : ['Нет данных'],
                    datasets: [{
                        label: 'Средний чек (₽)',
                        data: monthlySorted.length > 0 ? monthlySorted.map(m => m.avg) : [0],
                        borderColor: t.dark ? t.gold : '#f59e0b',
                        backgroundColor: avgTicketBg,
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        pointBackgroundColor: t.dark ? t.gold : '#f59e0b',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, ticks: { color: t.muted }, grid: { color: t.grid } },
                        x: { ticks: { color: t.muted }, grid: { display: false } }
                    }
                }
            });
            setTimeout(() => restyleChart(avgTicketChart, 'avgTicket'), 50);
        }
    }
    
    function renderAmountDistributionChart(deals) {
        const ranges = [
            { label: '0-50k', min: 0, max: 50000 },
            { label: '50k-100k', min: 50000, max: 100000 },
            { label: '100k-250k', min: 100000, max: 250000 },
            { label: '250k-500k', min: 250000, max: 500000 },
            { label: '500k+', min: 500000, max: Infinity }
        ];
        
        const counts = ranges.map(r => 
            deals.filter(d => {
                const amount = parseFloat(d.amount) || 0;
                return amount >= r.min && (r.max === Infinity || amount < r.max);
            }).length
        );
        
        if (amountDistributionChart) amountDistributionChart.destroy();
        const el = document.getElementById('amountDistributionChart');
        if (el) {
            let canvas = el.querySelector('canvas');
            if (!canvas) {
                canvas = document.createElement('canvas');
                el.innerHTML = '';
                el.appendChild(canvas);
            }
            const t = getReportTheme();
            const distCtx = canvas.getContext('2d');
            const distColors = t.dark && t.premiumGradients
                ? ranges.map((_, i) => {
                    const grad = t.premiumGradients[i % t.premiumGradients.length];
                    return createGradient(distCtx, grad[0], grad[1], 'radial');
                })
                : t.premiumColors.slice(0, ranges.length);
            
            amountDistributionChart = new Chart(canvas, {
                type: 'doughnut',
                data: {
                    labels: ranges.map(r => r.label),
                    datasets: [{
                        data: counts,
                        backgroundColor: distColors,
                        cutout: '60%',
                        borderWidth: t.dark ? 0 : 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'bottom', labels: { color: t.muted, padding: 15 } } }
                }
            });
            setTimeout(() => restyleChart(amountDistributionChart, 'amountDistribution'), 50);
        }
    }
    
    function renderConversionTrendChart(deals) {
        const monthly = {};
        const now = new Date();
        for (let i = 11; i >= 0; i--) {
            const date = new Date(now.getFullYear(), now.getMonth() - i, 1);
            const monthKey = date.getFullYear() + '-' + String(date.getMonth() + 1).padStart(2, '0');
            const monthName = date.toLocaleDateString('ru-RU', {month: 'short', year: '2-digit'});
            monthly[monthKey] = { name: monthName, total: 0, won: 0 };
        }
        
        deals.forEach(d => {
            if (d.created_at) {
                const date = new Date(d.created_at);
                const monthKey = date.getFullYear() + '-' + String(date.getMonth() + 1).padStart(2, '0');
                if (monthly[monthKey]) {
                    monthly[monthKey].total += 1;
                    if (d.stage && (d.stage.name === 'Won' || d.stage.name === 'Выиграно')) {
                        monthly[monthKey].won += 1;
                    }
                }
            }
        });
        
        const monthlySorted = Object.keys(monthly).sort().map(k => ({
            name: monthly[k].name,
            conversion: monthly[k].total > 0 ? (monthly[k].won / monthly[k].total * 100) : 0
        }));
        
        if (conversionTrendChart) conversionTrendChart.destroy();
        const el = document.getElementById('conversionTrendChart');
        if (el) {
            let canvas = el.querySelector('canvas');
            if (!canvas) {
                canvas = document.createElement('canvas');
                el.innerHTML = '';
                el.appendChild(canvas);
            }
            const t = getReportTheme();
            const convTrendCtx = canvas.getContext('2d');
            const convTrendBg = t.dark 
                ? createGradient(convTrendCtx, t.roseGradient[0] + '40', t.roseGradient[1] + '10')
                : '#ec489920';
            
            conversionTrendChart = new Chart(canvas, {
                type: 'line',
                data: {
                    labels: monthlySorted.map(m => m.name),
                    datasets: [{
                        label: 'Конверсия (%)',
                        data: monthlySorted.map(m => m.conversion),
                        borderColor: t.dark ? t.rose : '#ec4899',
                        backgroundColor: convTrendBg,
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        pointBackgroundColor: t.dark ? t.rose : '#ec4899',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, max: 100, ticks: { color: t.muted, callback: v => v + '%' }, grid: { color: t.grid } },
                        x: { ticks: { color: t.muted }, grid: { display: false } }
                    }
                }
            });
            setTimeout(() => restyleChart(conversionTrendChart, 'conversionTrend'), 50);
        }
    }
    
    function renderStageEfficiencyChart(stages, deals) {
        const stageNames = {
            'Lead': 'Лид', 'Qualified': 'Квалификация', 'Proposal': 'Предложение',
            'Negotiation': 'Переговоры', 'Won': 'Выиграно', 'Lost': 'Проиграно',
            'Лид': 'Лид', 'Квалификация': 'Квалификация', 'Предложение': 'Предложение',
            'Переговоры': 'Переговоры', 'Выиграно': 'Выиграно', 'Проиграно': 'Проиграно'
        };
        
        const stageData = stages.map(s => {
            const stageDeals = deals.filter(d => d.stage_id == s.id);
            const total = stageDeals.length;
            const avgAmount = total > 0 
                ? stageDeals.reduce((sum, d) => sum + (parseFloat(d.amount) || 0), 0) / total 
                : 0;
            return {
                name: stageNames[s.name] || s.name,
                count: total,
                avgAmount: avgAmount,
                totalAmount: stageDeals.reduce((sum, d) => sum + (parseFloat(d.amount) || 0), 0)
            };
        }).filter(s => s.count > 0).slice(0, 5);
        
        if (stageEfficiencyChart) stageEfficiencyChart.destroy();
        const el = document.getElementById('stageEfficiencyChart');
        if (el) {
            let canvas = el.querySelector('canvas');
            if (!canvas) {
                canvas = document.createElement('canvas');
                el.innerHTML = '';
                el.appendChild(canvas);
            }
            const t = getReportTheme();
            const ctx = canvas.getContext('2d');
            
            stageEfficiencyChart = new Chart(ctx, {
                type: 'radar',
                data: {
                    labels: stageData.map(s => s.name),
                    datasets: [{
                        label: 'Количество сделок',
                        data: stageData.map(s => s.count),
                        borderColor: t.dark ? t.purple : '#8b5cf6',
                        backgroundColor: t.dark 
                            ? createGradient(ctx, t.purpleGradient[0] + '40', t.purpleGradient[1] + '20', 'radial')
                            : '#8b5cf620',
                        borderWidth: 3,
                        pointBackgroundColor: t.dark ? t.purple : '#8b5cf6',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5
                    }, {
                        label: 'Средняя сумма (норм.)',
                        data: stageData.map(s => {
                            const max = Math.max(...stageData.map(s2 => s2.avgAmount));
                            return max > 0 ? (s.avgAmount / max) * Math.max(...stageData.map(s2 => s2.count)) : 0;
                        }),
                        borderColor: t.dark ? t.cyan : '#0891b2',
                        backgroundColor: t.dark 
                            ? createGradient(ctx, t.cyanGradient[0] + '30', t.cyanGradient[1] + '10', 'radial')
                            : '#0891b220',
                        borderWidth: 2,
                        pointBackgroundColor: t.dark ? t.cyan : '#0891b2',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { 
                            labels: { color: t.muted, padding: 15, font: { size: 11 } }
                        }
                    },
                    scales: {
                        r: {
                            beginAtZero: true,
                            ticks: { 
                                color: t.muted,
                                backdropColor: 'transparent',
                                font: { size: 10 }
                            },
                            grid: { color: t.grid },
                            pointLabels: { color: t.muted, font: { size: 11 } }
                        }
                    }
                }
            });
            setTimeout(() => restyleChart(stageEfficiencyChart, 'stageEfficiency'), 50);
        }
    }
    
    function renderIndustryChart(deals) {
        const industryMap = new Map();
        deals.forEach(d => {
            if (d.company && d.company.industry) {
                const industry = d.company.industry;
                if (!industryMap.has(industry)) {
                    industryMap.set(industry, { count: 0, amount: 0 });
                }
                const rec = industryMap.get(industry);
                rec.count += 1;
                rec.amount += (parseFloat(d.amount) || 0);
            }
        });
        
        const industries = Array.from(industryMap.entries())
            .map(([name, data]) => ({ name, ...data }))
            .sort((a, b) => b.amount - a.amount)
            .slice(0, 8);
        
        if (industryChart) industryChart.destroy();
        const el = document.getElementById('industryChart');
        if (el) {
            let canvas = el.querySelector('canvas');
            if (!canvas) {
                canvas = document.createElement('canvas');
                el.innerHTML = '';
                el.appendChild(canvas);
            }
            const t = getReportTheme();
            const indCtx = canvas.getContext('2d');
            const indColors = t.dark && t.premiumGradients
                ? industries.map((_, i) => {
                    const grad = t.premiumGradients[i % t.premiumGradients.length];
                    return createGradient(indCtx, grad[0], grad[1]);
                })
                : industries.map((_, i) => t.premiumColors[i % t.premiumColors.length]);
            
            industryChart = new Chart(canvas, {
                type: 'bar',
                data: {
                    labels: industries.map(i => i.name.length > 15 ? i.name.substring(0, 15) + '...' : i.name),
                    datasets: [{
                        label: 'Прибыль (₽)',
                        data: industries.map(i => i.amount),
                        backgroundColor: indColors,
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, ticks: { color: t.muted }, grid: { color: t.grid } },
                        x: { ticks: { color: t.muted, maxRotation: 45, minRotation: 45 }, grid: { display: false } }
                    }
                }
            });
            setTimeout(() => restyleChart(industryChart, 'industry'), 50);
        }
    }
    
    function renderHourlyChart(deals) {
        const hourlyCounts = Array(24).fill(0);
        deals.forEach(d => {
            if (d.created_at) {
                const date = new Date(d.created_at);
                const hour = date.getHours();
                hourlyCounts[hour] += 1;
            }
        });
        
        if (hourlyChart) hourlyChart.destroy();
        const el = document.getElementById('hourlyChart');
        if (el) {
            let canvas = el.querySelector('canvas');
            if (!canvas) {
                canvas = document.createElement('canvas');
                el.innerHTML = '';
                el.appendChild(canvas);
            }
            const t = getReportTheme();
            const hourCtx = canvas.getContext('2d');
            const hourBg = t.dark 
                ? createGradient(hourCtx, t.cyanGradient[0], t.cyanGradient[1])
                : '#0891b2';
            
            hourlyChart = new Chart(canvas, {
                type: 'bar',
                data: {
                    labels: Array.from({length: 24}, (_, i) => i + ':00'),
                    datasets: [{
                        label: 'Сделок',
                        data: hourlyCounts,
                        backgroundColor: hourBg,
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, ticks: { color: t.muted }, grid: { color: t.grid } },
                        x: { ticks: { color: t.muted, maxRotation: 90, minRotation: 90 }, grid: { display: false } }
                    }
                }
            });
            setTimeout(() => restyleChart(hourlyChart, 'hourly'), 50);
        }
    }
    
    function renderDealVelocityChart(deals) {
        const wonDeals = deals.filter(d => d.stage && (d.stage.name === 'Won' || d.stage.name === 'Выиграно'));
        const velocityRanges = [
            { label: '0-7 дней', max: 7 },
            { label: '8-14 дней', max: 14 },
            { label: '15-30 дней', max: 30 },
            { label: '31-60 дней', max: 60 },
            { label: '60+ дней', max: Infinity }
        ];
        
        const counts = velocityRanges.map((r, idx) => {
            const prevMax = idx > 0 ? velocityRanges[idx - 1].max : -1;
            return wonDeals.filter(d => {
                if (!d.created_at || !d.updated_at) return false;
                const created = new Date(d.created_at);
                const updated = new Date(d.updated_at);
                const days = Math.floor((updated - created) / (1000 * 60 * 60 * 24));
                if (r.max === Infinity) {
                    return days > prevMax;
                }
                return days > prevMax && days <= r.max;
            }).length;
        });
        
        if (dealVelocityChart) dealVelocityChart.destroy();
        const el = document.getElementById('dealVelocityChart');
        if (el) {
            let canvas = el.querySelector('canvas');
            if (!canvas) {
                canvas = document.createElement('canvas');
                el.innerHTML = '';
                el.appendChild(canvas);
            }
            const t = getReportTheme();
            const velCtx = canvas.getContext('2d');
            const velBg = t.dark 
                ? createGradient(velCtx, t.indigoGradient[0] + '40', t.indigoGradient[1] + '10')
                : '#4f46e520';
            
            dealVelocityChart = new Chart(canvas, {
                type: 'line',
                data: {
                    labels: velocityRanges.map(r => r.label),
                    datasets: [{
                        label: 'Сделок',
                        data: counts,
                        borderColor: t.dark ? t.indigo : '#4f46e5',
                        backgroundColor: velBg,
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                        pointRadius: 6,
                        pointHoverRadius: 8,
                        pointBackgroundColor: t.dark ? t.indigo : '#4f46e5',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, ticks: { color: t.muted }, grid: { color: t.grid } },
                        x: { ticks: { color: t.muted }, grid: { display: false } }
                    }
                }
            });
            setTimeout(() => restyleChart(dealVelocityChart, 'dealVelocity'), 50);
        }
    }
    
    function renderPeriodComparisonChart(deals) {
        const now = new Date();
        const currentMonth = new Date(now.getFullYear(), now.getMonth(), 1);
        const lastMonth = new Date(now.getFullYear(), now.getMonth() - 1, 1);
        const lastMonthEnd = new Date(now.getFullYear(), now.getMonth(), 0);
        
        const currentDeals = deals.filter(d => {
            if (!d.created_at) return false;
            const date = new Date(d.created_at);
            return date >= currentMonth && date <= now;
        });
        
        const lastDeals = deals.filter(d => {
            if (!d.created_at) return false;
            const date = new Date(d.created_at);
            return date >= lastMonth && date <= lastMonthEnd;
        });
        
        const currentWon = currentDeals.filter(d => d.stage && (d.stage.name === 'Won' || d.stage.name === 'Выиграно')).length;
        const lastWon = lastDeals.filter(d => d.stage && (d.stage.name === 'Won' || d.stage.name === 'Выиграно')).length;
        
        if (periodComparisonChart) periodComparisonChart.destroy();
        const el = document.getElementById('periodComparisonChart');
        if (el) {
            let canvas = el.querySelector('canvas');
            if (!canvas) {
                canvas = document.createElement('canvas');
                el.innerHTML = '';
                el.appendChild(canvas);
            }
            const t = getReportTheme();
            const periodCtx = canvas.getContext('2d');
            const periodColors = t.dark 
                ? [
                    createGradient(periodCtx, t.emeraldGradient[0], t.emeraldGradient[1]),
                    createGradient(periodCtx, t.sapphireGradient[0], t.sapphireGradient[1])
                ]
                : [t.success, '#3b82f6'];
            
            periodComparisonChart = new Chart(canvas, {
                type: 'bar',
                data: {
                    labels: ['Текущий месяц', 'Прошлый месяц'],
                    datasets: [{
                        label: 'Выиграно',
                        data: [currentWon, lastWon],
                        backgroundColor: periodColors,
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, ticks: { color: t.muted }, grid: { color: t.grid } },
                        x: { ticks: { color: t.muted }, grid: { display: false } }
                    }
                }
            });
            setTimeout(() => restyleChart(periodComparisonChart, 'periodComparison'), 50);
        }
    }

    async function exportData() {
        const res = await fetch('/api/deals?per_page=10000&for_reports=1');
        const data = await res.json();
        const deals = data.data || [];
        
        let csv = 'ID,Название,Компания,Сумма,Стадия,Дата\n';
        deals.forEach(d => {
            csv += `${d.id},"${d.title}","${d.company ? d.company.name : ''}",${d.amount || 0},"${d.stage ? d.stage.name : ''}","${d.created_at || ''}"\n`;
        });
        
        const blob = new Blob([csv], {type: 'text/csv;charset=utf-8;'});
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = `deals_${new Date().toISOString().split('T')[0]}.csv`;
        link.click();
    }

    window.addEventListener('crm-theme-changed', () => {
        restyleAllReportCharts();
    });
    
    // Ждем загрузки DOM и Chart.js
    function initReports() {
        if (typeof Chart === 'undefined') {
            setTimeout(initReports, 200);
            return;
        }
        
        // Дополнительная проверка что Chart.js полностью загружен
        if (!Chart || !Chart.Chart) {
            setTimeout(initReports, 200);
            return;
        }
        
        try {
            loadReports().catch(err => {
                console.error('Failed to load reports:', err);
                // Показываем ошибку пользователю
                const errorMsg = document.createElement('div');
                errorMsg.className = 'fixed top-4 right-4 bg-red-500 text-white px-4 py-2 rounded';
                errorMsg.textContent = 'Ошибка загрузки аналитики';
                document.body.appendChild(errorMsg);
                setTimeout(() => errorMsg.remove(), 5000);
            });
        } catch (e) {
            console.error('Error initializing reports:', e);
        }
    }
    
    // Запускаем после полной загрузки
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(initReports, 500);
        });
    } else {
        setTimeout(initReports, 500);
    }
    </script>
    
    <style>
        /* Свечение для графиков в темной теме */
        @media (prefers-color-scheme: dark) {
            .dark #stagesChart canvas,
            .dark #monthlyChart canvas,
            .dark #conversionChart canvas,
            .dark #dealsTrendChart canvas,
            .dark #companiesChart canvas,
            .dark #funnelChart canvas,
            .dark #weekdayChart canvas,
            .dark #avgTicketChart canvas,
            .dark #amountDistributionChart canvas,
            .dark #conversionTrendChart canvas,
            .dark #stageEfficiencyChart canvas,
            .dark #industryChart canvas,
            .dark #hourlyChart canvas,
            .dark #dealVelocityChart canvas,
            .dark #periodComparisonChart canvas {
                filter: drop-shadow(0 0 8px rgba(16, 185, 129, 0.3)) 
                        drop-shadow(0 0 12px rgba(16, 185, 129, 0.2))
                        drop-shadow(0 0 20px rgba(16, 185, 129, 0.1));
                transition: filter 0.3s ease;
            }
            
            /* Индивидуальное свечение для разных графиков */
            .dark #stagesChart canvas {
                filter: drop-shadow(0 0 10px rgba(167, 139, 250, 0.4)) 
                        drop-shadow(0 0 15px rgba(167, 139, 250, 0.25))
                        drop-shadow(0 0 25px rgba(167, 139, 250, 0.15));
            }
            
            .dark #monthlyChart canvas {
                filter: drop-shadow(0 0 10px rgba(16, 185, 129, 0.4)) 
                        drop-shadow(0 0 15px rgba(16, 185, 129, 0.25))
                        drop-shadow(0 0 25px rgba(16, 185, 129, 0.15));
            }
            
            .dark #conversionChart canvas {
                filter: drop-shadow(0 0 10px rgba(16, 185, 129, 0.4)) 
                        drop-shadow(0 0 15px rgba(16, 185, 129, 0.25))
                        drop-shadow(0 0 25px rgba(16, 185, 129, 0.15));
            }
            
            .dark #dealsTrendChart canvas {
                filter: drop-shadow(0 0 8px rgba(16, 185, 129, 0.3)) 
                        drop-shadow(0 0 12px rgba(59, 130, 246, 0.3))
                        drop-shadow(0 0 20px rgba(16, 185, 129, 0.15));
            }
            
            .dark #companiesChart canvas {
                filter: drop-shadow(0 0 10px rgba(251, 191, 36, 0.35)) 
                        drop-shadow(0 0 15px rgba(251, 191, 36, 0.2))
                        drop-shadow(0 0 25px rgba(251, 191, 36, 0.1));
            }
            
            .dark #funnelChart canvas {
                filter: drop-shadow(0 0 10px rgba(239, 68, 68, 0.35)) 
                        drop-shadow(0 0 15px rgba(249, 115, 16, 0.25))
                        drop-shadow(0 0 25px rgba(251, 191, 36, 0.15));
            }
            
            .dark #weekdayChart canvas {
                filter: drop-shadow(0 0 10px rgba(16, 185, 129, 0.4)) 
                        drop-shadow(0 0 15px rgba(16, 185, 129, 0.25))
                        drop-shadow(0 0 25px rgba(16, 185, 129, 0.15));
            }
            
            .dark #avgTicketChart canvas {
                filter: drop-shadow(0 0 10px rgba(251, 191, 36, 0.4)) 
                        drop-shadow(0 0 15px rgba(251, 191, 36, 0.25))
                        drop-shadow(0 0 25px rgba(251, 191, 36, 0.15));
            }
            
            .dark #amountDistributionChart canvas {
                filter: drop-shadow(0 0 10px rgba(167, 139, 250, 0.4)) 
                        drop-shadow(0 0 15px rgba(167, 139, 250, 0.25))
                        drop-shadow(0 0 25px rgba(167, 139, 250, 0.15));
            }
            
            .dark #conversionTrendChart canvas {
                filter: drop-shadow(0 0 10px rgba(236, 72, 153, 0.4)) 
                        drop-shadow(0 0 15px rgba(236, 72, 153, 0.25))
                        drop-shadow(0 0 25px rgba(236, 72, 153, 0.15));
            }
            
            .dark #stageEfficiencyChart canvas {
                filter: drop-shadow(0 0 10px rgba(167, 139, 250, 0.4)) 
                        drop-shadow(0 0 15px rgba(6, 182, 212, 0.3))
                        drop-shadow(0 0 25px rgba(167, 139, 250, 0.2));
            }
            
            .dark #industryChart canvas {
                filter: drop-shadow(0 0 10px rgba(16, 185, 129, 0.35)) 
                        drop-shadow(0 0 15px rgba(251, 191, 36, 0.25))
                        drop-shadow(0 0 25px rgba(59, 130, 246, 0.15));
            }
            
            .dark #hourlyChart canvas {
                filter: drop-shadow(0 0 10px rgba(6, 182, 212, 0.4)) 
                        drop-shadow(0 0 15px rgba(6, 182, 212, 0.25))
                        drop-shadow(0 0 25px rgba(6, 182, 212, 0.15));
            }
            
            .dark #dealVelocityChart canvas {
                filter: drop-shadow(0 0 10px rgba(99, 102, 241, 0.4)) 
                        drop-shadow(0 0 15px rgba(99, 102, 241, 0.25))
                        drop-shadow(0 0 25px rgba(99, 102, 241, 0.15));
            }
            
            .dark #periodComparisonChart canvas {
                filter: drop-shadow(0 0 10px rgba(16, 185, 129, 0.35)) 
                        drop-shadow(0 0 15px rgba(59, 130, 246, 0.3))
                        drop-shadow(0 0 25px rgba(16, 185, 129, 0.2));
            }
            
            /* Свечение для контейнеров графиков */
            .dark .premium-card {
                position: relative;
            }
            
            .dark .premium-card::before {
                content: '';
                position: absolute;
                inset: 0;
                border-radius: 1rem;
                padding: 1px;
                background: linear-gradient(135deg, 
                    rgba(16, 185, 129, 0.1) 0%, 
                    rgba(167, 139, 250, 0.1) 50%, 
                    rgba(6, 182, 212, 0.1) 100%);
                -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
                mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
                -webkit-mask-composite: xor;
                mask-composite: exclude;
                pointer-events: none;
                opacity: 0;
                transition: opacity 0.3s ease;
            }
            
            .dark .premium-card:hover::before {
                opacity: 1;
            }
        }
        
        /* Альтернативный способ для темной темы через класс dark */
        html.dark #stagesChart canvas,
        html.dark #monthlyChart canvas,
        html.dark #conversionChart canvas,
        html.dark #dealsTrendChart canvas,
        html.dark #companiesChart canvas,
        html.dark #funnelChart canvas,
        html.dark #weekdayChart canvas,
        html.dark #avgTicketChart canvas,
        html.dark #amountDistributionChart canvas,
        html.dark #conversionTrendChart canvas,
        html.dark #stageEfficiencyChart canvas,
        html.dark #industryChart canvas,
        html.dark #hourlyChart canvas,
        html.dark #dealVelocityChart canvas,
        html.dark #periodComparisonChart canvas {
            filter: drop-shadow(0 0 8px rgba(16, 185, 129, 0.3)) 
                    drop-shadow(0 0 12px rgba(16, 185, 129, 0.2))
                    drop-shadow(0 0 20px rgba(16, 185, 129, 0.1));
            transition: filter 0.3s ease;
        }
        
        html.dark #stagesChart canvas {
            filter: drop-shadow(0 0 3px rgba(167, 139, 250, 0.12)) 
                    drop-shadow(0 0 5px rgba(167, 139, 250, 0.08));
        }
        
        html.dark #monthlyChart canvas {
            filter: drop-shadow(0 0 3px rgba(16, 185, 129, 0.12)) 
                    drop-shadow(0 0 5px rgba(16, 185, 129, 0.08));
        }
        
        html.dark #conversionChart canvas {
            filter: drop-shadow(0 0 3px rgba(16, 185, 129, 0.12)) 
                    drop-shadow(0 0 5px rgba(16, 185, 129, 0.08));
        }
        
        html.dark #dealsTrendChart canvas {
            filter: drop-shadow(0 0 3px rgba(16, 185, 129, 0.1)) 
                    drop-shadow(0 0 5px rgba(59, 130, 246, 0.1));
        }
        
        html.dark #companiesChart canvas {
            filter: drop-shadow(0 0 3px rgba(251, 191, 36, 0.12)) 
                    drop-shadow(0 0 5px rgba(251, 191, 36, 0.08));
        }
        
        html.dark #funnelChart canvas {
            filter: drop-shadow(0 0 3px rgba(239, 68, 68, 0.12)) 
                    drop-shadow(0 0 5px rgba(249, 115, 16, 0.08));
        }
        
        html.dark #weekdayChart canvas {
            filter: drop-shadow(0 0 3px rgba(16, 185, 129, 0.12)) 
                    drop-shadow(0 0 5px rgba(16, 185, 129, 0.08));
        }
        
        html.dark #avgTicketChart canvas {
            filter: drop-shadow(0 0 3px rgba(251, 191, 36, 0.12)) 
                    drop-shadow(0 0 5px rgba(251, 191, 36, 0.08));
        }
        
        html.dark #amountDistributionChart canvas {
            filter: drop-shadow(0 0 3px rgba(167, 139, 250, 0.12)) 
                    drop-shadow(0 0 5px rgba(167, 139, 250, 0.08));
        }
        
        html.dark #conversionTrendChart canvas {
            filter: drop-shadow(0 0 3px rgba(236, 72, 153, 0.12)) 
                    drop-shadow(0 0 5px rgba(236, 72, 153, 0.08));
        }
        
        html.dark #stageEfficiencyChart canvas {
            filter: drop-shadow(0 0 3px rgba(167, 139, 250, 0.12)) 
                    drop-shadow(0 0 5px rgba(6, 182, 212, 0.1));
        }
        
        html.dark #industryChart canvas {
            filter: drop-shadow(0 0 3px rgba(16, 185, 129, 0.12)) 
                    drop-shadow(0 0 5px rgba(251, 191, 36, 0.08));
        }
        
        html.dark #hourlyChart canvas {
            filter: drop-shadow(0 0 3px rgba(6, 182, 212, 0.12)) 
                    drop-shadow(0 0 5px rgba(6, 182, 212, 0.08));
        }
        
        html.dark #dealVelocityChart canvas {
            filter: drop-shadow(0 0 3px rgba(99, 102, 241, 0.12)) 
                    drop-shadow(0 0 5px rgba(99, 102, 241, 0.08));
        }
        
        html.dark #periodComparisonChart canvas {
            filter: drop-shadow(0 0 3px rgba(16, 185, 129, 0.12)) 
                    drop-shadow(0 0 5px rgba(59, 130, 246, 0.1));
        }
        
        /* Свечение для карточек метрик в темной теме (едва заметное) */
        html.dark #totalDealsMetric {
            text-shadow: 0 0 3px rgba(16, 185, 129, 0.15),
                         0 0 6px rgba(16, 185, 129, 0.08);
        }
        
        html.dark #wonDealsMetric {
            text-shadow: 0 0 3px rgba(34, 211, 153, 0.15),
                         0 0 6px rgba(34, 211, 153, 0.08);
        }
        
        html.dark #totalProfitMetric {
            text-shadow: 0 0 3px rgba(251, 191, 36, 0.15),
                         0 0 6px rgba(251, 191, 36, 0.08);
        }
        
        html.dark #conversionMetric {
            text-shadow: 0 0 3px rgba(167, 139, 250, 0.15),
                         0 0 6px rgba(167, 139, 250, 0.08);
        }
        
        /* Свечение для иконок в карточках метрик (едва заметное) */
        html.dark .premium-card .w-8.h-8 {
            box-shadow: 0 0 4px rgba(16, 185, 129, 0.1),
                        0 0 6px rgba(16, 185, 129, 0.05);
            transition: box-shadow 0.3s ease;
        }
        
        html.dark .premium-card:hover .w-8.h-8 {
            box-shadow: 0 0 6px rgba(16, 185, 129, 0.15),
                        0 0 10px rgba(16, 185, 129, 0.08);
        }
        
        /* Легкое свечение для контейнеров графиков (едва заметное) */
        html.dark .premium-card {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3),
                        0 2px 4px -1px rgba(0, 0, 0, 0.2),
                        0 0 0 1px rgba(16, 185, 129, 0.05);
            transition: box-shadow 0.3s ease;
        }
        
        html.dark .premium-card:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.4),
                        0 4px 6px -2px rgba(0, 0, 0, 0.3),
                        0 0 0 1px rgba(16, 185, 129, 0.1),
                        0 0 8px rgba(16, 185, 129, 0.05);
            background-color: rgba(255, 255, 255, 0.03);
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }
        
        /* Эффект осветления при наведении в темной теме */
        html.dark .premium-card {
            position: relative;
            transition: background-color 0.3s ease, box-shadow 0.3s ease, transform 0.2s ease;
        }
        
        html.dark .premium-card::after {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: inherit;
            background: linear-gradient(135deg, 
                rgba(255, 255, 255, 0.08) 0%, 
                rgba(255, 255, 255, 0.04) 50%, 
                rgba(255, 255, 255, 0.08) 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
            pointer-events: none;
            z-index: 1;
        }
        
        html.dark .premium-card:hover::after {
            opacity: 1;
        }
        
        /* Убедимся, что контент карточки остается поверх overlay */
        html.dark .premium-card > * {
            position: relative;
            z-index: 2;
        }
        
        /* Дополнительные стили для разнообразия форм */
        .premium-card.rounded-3xl {
            border-radius: 1.5rem;
        }
        
        .premium-card.rounded-2xl {
            border-radius: 1rem;
        }
        
        .premium-card.rounded-xl {
            border-radius: 0.75rem;
        }
        
        /* Разные высоты для визуального разнообразия */
        @media (min-width: 768px) {
            .premium-card[class*="col-span-8"] {
                min-height: 320px;
            }
            
            .premium-card[class*="col-span-7"] {
                min-height: 300px;
            }
            
            .premium-card[class*="col-span-6"] {
                min-height: 340px;
            }
            
            .premium-card[class*="col-span-5"] {
                min-height: 300px;
            }
            
            .premium-card[class*="col-span-4"] {
                min-height: 280px;
            }
        }
        
        /* Плавные переходы для всех карточек */
        .premium-card {
            transition: transform 0.2s ease, box-shadow 0.3s ease, background-color 0.3s ease;
        }
        
        .premium-card:hover {
            transform: translateY(-2px);
        }
        
        /* Эффект осветления в светлой теме при наведении (уже есть, но убедимся) */
        .premium-card:hover {
            background-color: rgba(255, 255, 255, 0.5);
        }
        
        html.dark .premium-card:hover {
            background-color: rgba(255, 255, 255, 0.05);
        }
        
        /* Градиентные границы для больших карточек в темной теме */
        @media (prefers-color-scheme: dark) {
            html.dark .premium-card.rounded-3xl {
                background: linear-gradient(to bottom, rgba(16, 185, 129, 0.02), transparent);
            }
            
            html.dark .premium-card.rounded-2xl {
                background: linear-gradient(to bottom, rgba(167, 139, 250, 0.02), transparent);
            }
        }
    </style>
</x-app-layout>

