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
        .btn-admin { background: linear-gradient(135deg, #ef4444 0%, #f97316 100%) !important; box-shadow: 0 10px 40px rgba(239, 68, 68, 0.4) !important; }
        .btn-admin:hover { background: linear-gradient(135deg, #dc2626 0%, #ea580c 100%) !important; box-shadow: 0 20px 60px rgba(239, 68, 68, 0.6) !important; transform: translateY(-2px) !important; }
        .code-block { font-family: 'Courier New', monospace; background: #1f2937; color: #10b981; padding: 1rem; border-radius: 0.5rem; overflow-x: auto; }
    </style>
</head>
<body class="min-h-screen bg-gray-100">
    @include('layouts.admin-sidebar')
    
    <div class="ml-64 p-8">
        <div class="max-w-7xl mx-auto">
            <h2 class="text-3xl font-bold mb-8 text-gray-900">Управление базой данных</h2>
            
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                    @if(session('affected'))
                        <div class="mt-2">Затронуто строк: {{ session('affected') }}</div>
                    @endif
                </div>
            @endif
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Список таблиц -->
                <div class="admin-card rounded-xl p-6 shadow-lg">
                    <h3 class="text-xl font-bold mb-4 text-gray-900">Таблицы</h3>
                    <div id="tablesList" class="space-y-2 max-h-96 overflow-y-auto">
                        @foreach($tableNames as $table)
                            <button onclick="loadTable('{{ $table }}')" class="w-full text-left px-4 py-2 bg-gray-50 hover:bg-blue-50 rounded-lg transition text-gray-700 hover:text-blue-700 table-item" data-name="{{ $table }}">
                                {{ $table }}
                            </button>
                        @endforeach
                    </div>
                </div>
                
                <!-- SQL запросы -->
                <div class="lg:col-span-2 admin-card rounded-xl p-6 shadow-lg">
                    <h3 class="text-xl font-bold mb-4 text-gray-900">SQL запрос</h3>
                    <form method="POST" action="{{ route('admin.database.query') }}">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Введите SQL запрос (SELECT только)</label>
                            <textarea name="query" rows="8" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 code-block" placeholder="SELECT * FROM users LIMIT 10;" required></textarea>
                            <div class="mt-2 text-sm text-gray-600">
                                <strong>Внимание:</strong> Разрешены только SELECT запросы. DROP, TRUNCATE, DELETE, ALTER и другие опасные операции запрещены.
                            </div>
                        </div>
                        <button type="submit" class="text-white px-6 py-3 rounded-lg font-semibold transition-all shadow-lg" style="background: linear-gradient(135deg, #ef4444 0%, #f97316 100%); box-shadow: 0 10px 40px rgba(239, 68, 68, 0.4); border: none;">
                            Выполнить запрос
                        </button>
                    </form>
                    
                    @if(session('results'))
                        <div class="mt-6">
                            <h4 class="text-lg font-semibold mb-2">Результаты:</h4>
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white border border-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            @if(count(session('results')) > 0)
                                                @foreach(array_keys((array)session('results')[0]) as $key)
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ $key }}</th>
                                                @endforeach
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach(session('results') as $row)
                                            <tr class="border-b">
                                                @foreach((array)$row as $value)
                                                    <td class="px-4 py-2 text-sm text-gray-700">{{ $value }}</td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Просмотр таблицы -->
            <div id="tableView" class="mt-6 admin-card rounded-xl p-6 shadow-lg hidden">
                <h3 class="text-xl font-bold mb-4 text-gray-900" id="tableTitle">Данные таблицы</h3>
                <div id="tableContent" class="overflow-x-auto">
                    <!-- Данные загрузятся через JavaScript -->
                </div>
            </div>
        </div>
    </div>
    
    <script>
        const allTables = [
            @foreach($tableNames as $table)
            '{{ $table }}',
            @endforeach
        ];
        
        async function loadTable(tableName) {
            document.getElementById('tableTitle').textContent = 'Данные таблицы: ' + tableName;
            document.getElementById('tableView').classList.remove('hidden');
            document.getElementById('tableContent').innerHTML = '<div class="text-center py-4">Загрузка...</div>';
            
            try {
                const response = await fetch(`{{ route('admin.database.table') }}?table=${tableName}`);
                const data = await response.json();
                
                if (data.error) {
                    document.getElementById('tableContent').innerHTML = `<div class="text-red-600">Ошибка: ${data.error}</div>`;
                    return;
                }
                
                if (data.data.length === 0) {
                    document.getElementById('tableContent').innerHTML = '<div class="text-center py-4 text-gray-500">Таблица пуста</div>';
                    return;
                }
                
                let html = '<table class="min-w-full bg-white border border-gray-200"><thead class="bg-gray-50"><tr>';
                data.columns.forEach(col => {
                    html += `<th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">${col}</th>`;
                });
                html += '</tr></thead><tbody>';
                
                data.data.forEach(row => {
                    html += '<tr class="border-b">';
                    data.columns.forEach(col => {
                        html += `<td class="px-4 py-2 text-sm text-gray-700">${row[col] ?? ''}</td>`;
                    });
                    html += '</tr>';
                });
                
                html += '</tbody></table>';
                document.getElementById('tableContent').innerHTML = html;
            } catch (error) {
                document.getElementById('tableContent').innerHTML = `<div class="text-red-600">Ошибка загрузки: ${error.message}</div>`;
            }
        }
    </script>
</body>
</html>

