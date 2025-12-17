<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CRM</title>
    <script>
        // Apply theme early to avoid flash
        (function () {
            try {
                const saved = localStorage.getItem('crm_theme');
                const theme = saved || ((window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) ? 'dark' : 'light');
                if (theme === 'dark') document.documentElement.classList.add('dark');
            } catch (_) {}
        })();
    </script>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'><defs><linearGradient id='logoGradient' x1='0%' y1='0%' x2='100%' y2='100%'><stop offset='0%' style='stop-color:%23ef4444;stop-opacity:1' /><stop offset='100%' style='stop-color:%23f97316;stop-opacity:1' /></linearGradient></defs><rect width='32' height='32' rx='6' fill='url(%23logoGradient)'/><path d='M10 12C10 10.8954 10.8954 10 12 10H20C21.1046 10 22 10.8954 22 12V20C22 21.1046 21.1046 22 20 22H12C10.8954 22 10 21.1046 10 20V12Z' fill='white' opacity='0.9'/><path d='M14 16L16 18L18 16' stroke='url(%23logoGradient)' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round' fill='none'/><circle cx='16' cy='16' r='8' stroke='white' stroke-width='1.5' fill='none' opacity='0.3'/></svg>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <style>
        body { background: #f3f4f6; color: #1f2937; font-family: 'Inter', sans-serif; }
        html.dark body { background: #070b14; color: #e5e7eb; }
        .admin-card { background: #ffffff; border: 1px solid #e5e7eb; }
        html.dark .admin-card { background: rgba(15,23,42,0.92); border-color: rgba(148,163,184,0.22); }
        .admin-card:hover { border-color: #3b82f6; transform: translateY(-2px); }
        .stat-card { background: #ffffff; border: 1px solid #e5e7eb; }
        html.dark .stat-card { background: rgba(15,23,42,0.92); border-color: rgba(148,163,184,0.22); }
        .btn-admin { background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%); }
        .btn-admin:hover { background: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%); }

        /* make Tailwind grays readable in dark (since CDN dark: is not class-based) */
        html.dark .text-gray-900,
        html.dark .text-gray-800 { color: rgba(226,232,240,0.96) !important; }
        html.dark .text-gray-700,
        html.dark .text-gray-600 { color: rgba(203,213,225,0.86) !important; }
        html.dark .text-gray-500,
        html.dark .text-gray-400 { color: rgba(148,163,184,0.92) !important; }
        html.dark .bg-gray-50 { background-color: rgba(15,23,42,0.70) !important; }
        html.dark .bg-gray-100 { background-color: transparent !important; }
        html.dark .border-gray-200 { border-color: rgba(255,255,255,0.10) !important; }
        html.dark .bg-gray-200 { background-color: rgba(148,163,184,0.18) !important; }
        html.dark .text-gray-800 { color: rgba(226,232,240,0.96) !important; }

        /* nicer dark page background */
        html.dark body{
            background:
                radial-gradient(900px 520px at 0% 0%, rgba(239,68,68,0.10), transparent 60%),
                radial-gradient(900px 520px at 100% 10%, rgba(249,115,22,0.10), transparent 60%),
                linear-gradient(180deg, #050814 0%, #070b16 60%, #050814 100%);
        }
    </style>
</head>
<body class="min-h-screen">
    @include('layouts.admin-sidebar')
    
    <div class="ml-64 p-8">
        <div class="max-w-7xl mx-auto">
            <h2 class="text-3xl font-bold mb-8 text-gray-900">Общая статистика системы</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="stat-card rounded-xl p-6 shadow-lg transition-all border border-gray-200">
                    <div class="text-gray-600 text-sm mb-2">Пользователи</div>
                    <div class="text-4xl font-bold text-blue-600">{{ $stats['users'] }}</div>
                </div>
                <div class="stat-card rounded-xl p-6 shadow-lg transition-all border border-gray-200">
                    <div class="text-gray-600 text-sm mb-2">Компании</div>
                    <div class="text-4xl font-bold text-purple-600">{{ $stats['companies'] }}</div>
                </div>
                <div class="stat-card rounded-xl p-6 shadow-lg transition-all border border-gray-200">
                    <div class="text-gray-600 text-sm mb-2">Сделки</div>
                    <div class="text-4xl font-bold text-green-600">{{ $stats['deals'] }}</div>
                </div>
                <div class="stat-card rounded-xl p-6 shadow-lg transition-all border border-gray-200">
                    <div class="text-gray-600 text-sm mb-2">Общая сумма</div>
                    <div class="text-4xl font-bold text-orange-600">{{ number_format($stats['total_deal_amount'], 0, ',', ' ') }} ₽</div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <div class="admin-card rounded-xl p-6 shadow-lg">
                    <h3 class="text-xl font-bold mb-4 text-gray-900">Последние пользователи</h3>
                    <div class="space-y-3">
                        @foreach($recentUsers as $user)
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                            <div>
                                <div class="font-semibold text-gray-900">{{ $user->name }}</div>
                                <div class="text-sm text-gray-600">{{ $user->email }}</div>
                                @if($user->last_login_ip)
                                <div class="text-xs text-gray-500 mt-1">IP: {{ $user->last_login_ip }}</div>
                                @endif
                            </div>
                            <div class="text-xs px-2 py-1 rounded {{ $user->is_admin ? 'bg-red-100 text-red-800' : ($user->role === 'manager' ? 'bg-blue-100 text-blue-800' : 'bg-gray-200 text-gray-800') }}">
                                {{ $user->is_admin ? 'Админ' : ($user->role === 'manager' ? 'Менеджер' : 'Сотрудник') }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="admin-card rounded-xl p-6 shadow-lg">
                    <h3 class="text-xl font-bold mb-4 text-gray-900">Последние сделки</h3>
                    <div class="space-y-3">
                        @foreach($recentDeals as $deal)
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                            <div>
                                <div class="font-semibold text-gray-900">{{ $deal->title }}</div>
                                <div class="text-sm text-gray-600">{{ $deal->company->name ?? '-' }}</div>
                            </div>
                            <div class="text-sm font-semibold text-green-600">{{ number_format($deal->amount ?? 0, 0, ',', ' ') }} ₽</div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        (function initCrmThemeToggleAdminPage() {
            if (window.__crmThemeToggleInit) return;
            window.__crmThemeToggleInit = true;

            const root = document.documentElement;

            function isDark() {
                return root.classList.contains('dark');
            }

            function setTheme(theme) {
                root.classList.toggle('dark', theme === 'dark');
                try { localStorage.setItem('crm_theme', theme); } catch (_) {}
                document.querySelectorAll('[data-theme-toggle]').forEach((btn) => {
                    btn.setAttribute('aria-pressed', theme === 'dark' ? 'true' : 'false');
                });
            }

            const current = isDark() ? 'dark' : 'light';
            document.querySelectorAll('[data-theme-toggle]').forEach((btn) => {
                btn.setAttribute('aria-pressed', current === 'dark' ? 'true' : 'false');
            });

            document.addEventListener('click', (e) => {
                const btn = e.target.closest('[data-theme-toggle]');
                if (!btn) return;
                e.preventDefault();

                const next = isDark() ? 'light' : 'dark';
                setTheme(next);

                document.querySelectorAll('[data-theme-toggle]').forEach((b) => {
                    b.classList.remove('crm-theme-burst');
                    void b.offsetWidth;
                    b.classList.add('crm-theme-burst');
                    window.setTimeout(() => b.classList.remove('crm-theme-burst'), 700);
                });
            });
        })();
    </script>
</body>
</html>
