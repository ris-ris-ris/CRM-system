<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Профиль
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Статистика -->
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Моя статистика</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white p-4 rounded-lg">
                        <div class="text-blue-100 text-sm mb-1">Активных сделок</div>
                        <div class="text-2xl font-bold">{{ $stats['deals_count'] }}</div>
                    </div>
                    <div class="bg-gradient-to-br from-green-500 to-green-600 text-white p-4 rounded-lg">
                        <div class="text-green-100 text-sm mb-1">Прибыль</div>
                        <div class="text-2xl font-bold">{{ number_format($stats['deals_amount'], 0, ',', ' ') }} ₽</div>
                    </div>
                    <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white p-4 rounded-lg">
                        <div class="text-purple-100 text-sm mb-1">Выиграно</div>
                        <div class="text-2xl font-bold">{{ $stats['won_deals'] }}</div>
                    </div>
                    <div class="bg-gradient-to-br from-orange-500 to-orange-600 text-white p-4 rounded-lg">
                        <div class="text-orange-100 text-sm mb-1">Активностей</div>
                        <div class="text-2xl font-bold">{{ $stats['activities_count'] }}</div>
                    </div>
                    <div class="bg-gradient-to-br from-pink-500 to-pink-600 text-white p-4 rounded-lg">
                        <div class="text-pink-100 text-sm mb-1">Сегодня</div>
                        <div class="text-2xl font-bold">{{ $stats['activities_today'] }}</div>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-md font-semibold mb-3 text-gray-900 dark:text-gray-100">Сделки по месяцам</h4>
                        <div style="height: 300px;">
                            <canvas id="monthlyChart"></canvas>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-md font-semibold mb-3 text-gray-900 dark:text-gray-100">Сделки по стадиям</h4>
                        <div style="height: 300px;">
                            <canvas id="stageChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        // График по месяцам
        const monthlyCtx = document.getElementById('monthlyChart');
        if (monthlyCtx) {
            new Chart(monthlyCtx, {
                type: 'bar',
                data: {
                    labels: ['Янв', 'Фев', 'Мар', 'Апр', 'Май', 'Июн', 'Июл', 'Авг', 'Сен', 'Окт', 'Ноя', 'Дек'],
                    datasets: [{
                        label: 'Количество сделок',
                        data: [{{ implode(',', array_map(fn($d) => $d['count'], $monthlyData)) }}],
                        backgroundColor: 'rgba(59, 130, 246, 0.5)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        }
        
        // График по стадиям
        const stageCtx = document.getElementById('stageChart');
        if (stageCtx) {
            const stageData = @json($dealsByStage);
            new Chart(stageCtx, {
                type: 'pie',
                data: {
                    labels: stageData.map(s => s.name),
                    datasets: [{
                        data: stageData.map(s => s.count),
                        backgroundColor: [
                            'rgba(59, 130, 246, 0.5)',
                            'rgba(139, 92, 246, 0.5)',
                            'rgba(236, 72, 153, 0.5)',
                            'rgba(34, 197, 94, 0.5)',
                            'rgba(251, 146, 60, 0.5)',
                            'rgba(239, 68, 68, 0.5)',
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        }
    </script>
</x-app-layout>
