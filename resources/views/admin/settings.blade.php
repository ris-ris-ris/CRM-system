<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CRM</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'><defs><linearGradient id='logoGradient' x1='0%' y1='0%' x2='100%' y2='100%'><stop offset='0%' style='stop-color:%23ef4444;stop-opacity:1' /><stop offset='100%' style='stop-color:%23f97316;stop-opacity:1' /></linearGradient></defs><rect width='32' height='32' rx='6' fill='url(%23logoGradient)'/><path d='M10 12C10 10.8954 10.8954 10 12 10H20C21.1046 10 22 10.8954 22 12V20C22 21.1046 21.1046 22 20 22H12C10.8954 22 10 21.1046 10 20V12Z' fill='white' opacity='0.9'/><path d='M14 16L16 18L18 16' stroke='url(%23logoGradient)' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round' fill='none'/><circle cx='16' cy='16' r='8' stroke='white' stroke-width='1.5' fill='none' opacity='0.3'/></svg>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <style>
        body { background: #f3f4f6; color: #1f2937; font-family: 'Inter', sans-serif; }
        .admin-card { background: #ffffff; border: 1px solid #e5e7eb; }
        .btn-admin { background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%); }
        .btn-admin:hover { background: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%); }
    </style>
</head>
<body class="min-h-screen bg-gray-100">
    @include('layouts.admin-sidebar')
    
    <div class="ml-64 p-8">
        <div class="max-w-4xl mx-auto">
            <h2 class="text-3xl font-bold mb-8 text-gray-900">Настройки системы</h2>
            
            <div class="admin-card rounded-xl p-6 shadow-lg mb-6">
                <h3 class="text-xl font-bold mb-4 text-gray-900">Управление системой</h3>
                <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="text-red-800 mb-2 font-semibold">Очистка данных</div>
                    <div class="text-sm text-red-700 mb-3">Осторожно! Это действие удалит все тестовые данные</div>
                    <form method="POST" action="{{ route('admin.settings.clear') }}" onsubmit="return confirm('Вы уверены? Это удалит все данные!')">
                        @csrf
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
                            Очистить тестовые данные
                        </button>
                    </form>
                </div>
            </div>

            <div class="admin-card rounded-xl p-6 shadow-lg mb-6">
                <h3 class="text-xl font-bold mb-4 text-gray-900">Информация о системе</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <div class="text-sm text-gray-600 mb-1">Версия системы</div>
                        <div class="text-lg font-semibold text-gray-900">1.0.0</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600 mb-1">Всего пользователей</div>
                        <div class="text-lg font-semibold text-gray-900">{{ \App\Models\User::count() }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600 mb-1">Всего компаний</div>
                        <div class="text-lg font-semibold text-gray-900">{{ \App\Models\Company::count() }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600 mb-1">Всего сделок</div>
                        <div class="text-lg font-semibold text-gray-900">{{ \App\Models\Deal::count() }}</div>
                    </div>
                </div>
            </div>

            <div class="admin-card rounded-xl p-6 shadow-lg">
                <h3 class="text-xl font-bold mb-4 text-gray-900">Параметры сервера</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <span class="text-gray-700 font-medium">PHP версия</span>
                        <span class="text-gray-900 font-semibold">{{ PHP_VERSION }}</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <span class="text-gray-700 font-medium">Laravel версия</span>
                        <span class="text-gray-900 font-semibold">{{ app()->version() }}</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <span class="text-gray-700 font-medium">Окружение</span>
                        <span class="text-gray-900 font-semibold">{{ app()->environment() }}</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <span class="text-gray-700 font-medium">Время работы</span>
                        <span class="text-gray-900 font-semibold">{{ number_format(microtime(true) - LARAVEL_START, 2) }} сек</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <span class="text-gray-700 font-medium">Память</span>
                        <span class="text-gray-900 font-semibold">{{ number_format(memory_get_usage(true) / 1024 / 1024, 2) }} MB</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <span class="text-gray-700 font-medium">Пиковая память</span>
                        <span class="text-gray-900 font-semibold">{{ number_format(memory_get_peak_usage(true) / 1024 / 1024, 2) }} MB</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <span class="text-gray-700 font-medium">База данных</span>
                        <span class="text-gray-900 font-semibold">
                            @php
                                try {
                                    $dbVersion = \DB::connection()->getPdo()->getAttribute(\PDO::ATTR_SERVER_VERSION);
                                    echo $dbVersion;
                                } catch (\Exception $e) {
                                    echo 'Недоступна';
                                }
                            @endphp
                        </span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <span class="text-gray-700 font-medium">Статус БД</span>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold 
                            @php
                                try {
                                    $dbConnected = \DB::connection()->getPdo() !== null;
                                    echo $dbConnected ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
                                } catch (\Exception $e) {
                                    echo 'bg-red-100 text-red-800';
                                }
                            @endphp
                        ">
                            @php
                                try {
                                    $dbConnected = \DB::connection()->getPdo() !== null;
                                    echo $dbConnected ? 'Работает' : 'Ошибка';
                                } catch (\Exception $e) {
                                    echo 'Ошибка подключения';
                                }
                            @endphp
                        </span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <span class="text-gray-700 font-medium">Версия MySQL</span>
                        <span class="text-gray-900 font-semibold">
                            @php
                                try {
                                    $mysqlVersion = \DB::select('SELECT VERSION() as version')[0]->version ?? 'Недоступна';
                                    echo $mysqlVersion;
                                } catch (\Exception $e) {
                                    echo 'Недоступна';
                                }
                            @endphp
                        </span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <span class="text-gray-700 font-medium">Размер БД</span>
                        <span class="text-gray-900 font-semibold">
                            @php
                                try {
                                    $dbSize = \DB::select("SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size_mb FROM information_schema.tables WHERE table_schema = DATABASE()")[0]->size_mb ?? 0;
                                    echo number_format($dbSize, 2) . ' MB';
                                } catch (\Exception $e) {
                                    echo 'Недоступно';
                                }
                            @endphp
                        </span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <span class="text-gray-700 font-medium">Время сервера</span>
                        <span class="text-gray-900 font-semibold">{{ date('Y-m-d H:i:s') }}</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <span class="text-gray-700 font-medium">Часовой пояс</span>
                        <span class="text-gray-900 font-semibold">{{ date_default_timezone_get() }}</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <span class="text-gray-700 font-medium">Максимальный размер загрузки</span>
                        <span class="text-gray-900 font-semibold">{{ ini_get('upload_max_filesize') }}</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <span class="text-gray-700 font-medium">Максимальный размер POST</span>
                        <span class="text-gray-900 font-semibold">{{ ini_get('post_max_size') }}</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <span class="text-gray-700 font-medium">Время выполнения</span>
                        <span class="text-gray-900 font-semibold">{{ ini_get('max_execution_time') }} сек</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <span class="text-gray-700 font-medium">Лимит памяти</span>
                        <span class="text-gray-900 font-semibold">{{ ini_get('memory_limit') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
