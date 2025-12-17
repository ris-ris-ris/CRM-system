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
        input, select { background: #ffffff; border: 1px solid #d1d5db; color: #1f2937; }
    </style>
</head>
<body class="min-h-screen bg-gray-100">
    @include('layouts.admin-sidebar')
    
    <div class="ml-64 p-8">
        <div class="max-w-7xl mx-auto">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900">Управление пользователями</h2>
                <button onclick="document.getElementById('createModal').classList.remove('hidden')" class="text-white px-6 py-3 rounded-lg font-semibold transition-all shadow-lg" style="background: linear-gradient(135deg, #ef4444 0%, #f97316 100%); box-shadow: 0 10px 40px rgba(239, 68, 68, 0.4); border: none;">
                    + Создать пользователя
                </button>
            </div>

            <div class="admin-card rounded-xl p-6 shadow-lg">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left py-3 px-4 text-gray-600 font-semibold">ID</th>
                                <th class="text-left py-3 px-4 text-gray-600 font-semibold">Имя</th>
                                <th class="text-left py-3 px-4 text-gray-600 font-semibold">Email</th>
                                <th class="text-left py-3 px-4 text-gray-600 font-semibold">Роль</th>
                                <th class="text-left py-3 px-4 text-gray-600 font-semibold">IP адрес</th>
                                <th class="text-left py-3 px-4 text-gray-600 font-semibold">Последний вход</th>
                                <th class="text-left py-3 px-4 text-gray-600 font-semibold">Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                                <td class="py-3 px-4 text-gray-700">{{ $user->id }}</td>
                                <td class="py-3 px-4">
                                    <div class="font-semibold text-gray-900">{{ $user->name }}</div>
                                </td>
                                <td class="py-3 px-4 text-gray-700">{{ $user->email }}</td>
                                <td class="py-3 px-4">
                                    @if($user->id === auth()->id())
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                            Админ (Вы)
                                        </span>
                                    @else
                                        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <select name="role" onchange="this.form.submit()" class="px-3 py-1 rounded text-sm border border-gray-300 bg-white text-gray-900">
                                                <option value="employee" {{ $user->role === 'employee' ? 'selected' : '' }}>Сотрудник</option>
                                                <option value="manager" {{ $user->role === 'manager' ? 'selected' : '' }}>Менеджер</option>
                                                <option value="admin" {{ $user->is_admin ? 'selected' : '' }}>Админ</option>
                                            </select>
                                            <input type="hidden" name="is_admin" value="{{ $user->role === 'admin' ? '1' : '0' }}">
                                        </form>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-sm text-gray-700">
                                    {{ $user->last_login_ip ?? '-' }}
                                </td>
                                <td class="py-3 px-4 text-sm text-gray-700">
                                    @if($user->last_login_at)
                                        @if(is_string($user->last_login_at))
                                            {{ \Carbon\Carbon::parse($user->last_login_at)->format('d.m.Y H:i') }}
                                        @else
                                            {{ $user->last_login_at->format('d.m.Y H:i') }}
                                        @endif
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-sm">
                                    @if($user->id !== auth()->id())
                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline" onsubmit="return confirm('Удалить пользователя?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 transition">Удалить</button>
                                    </form>
                                    @else
                                    <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal создания пользователя -->
    <div id="createModal" class="hidden fixed inset-0 bg-black bg-opacity-60 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl p-8 w-full max-w-md shadow-2xl border border-gray-200">
            <h3 class="text-2xl font-bold mb-6 text-gray-900">Создать пользователя</h3>
            <form method="POST" action="{{ route('admin.users.create') }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Имя</label>
                        <input type="text" name="name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-white text-gray-900 focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" required class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-white text-gray-900 focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Пароль</label>
                        <input type="password" name="password" required minlength="8" class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-white text-gray-900 focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Роль</label>
                        <select name="role" required class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-white text-gray-900 focus:ring-2 focus:ring-blue-500">
                            <option value="employee">Сотрудник</option>
                            <option value="manager">Менеджер</option>
                            <option value="admin">Админ</option>
                        </select>
                    </div>
                    <div class="flex space-x-4">
                        <button type="submit" class="flex-1 text-white px-6 py-2 rounded-lg font-semibold transition" style="background: linear-gradient(135deg, #ef4444 0%, #f97316 100%); box-shadow: 0 10px 40px rgba(239, 68, 68, 0.4); border: none;">Создать</button>
                        <button type="button" onclick="document.getElementById('createModal').classList.add('hidden')" class="flex-1 bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg transition">Отмена</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
