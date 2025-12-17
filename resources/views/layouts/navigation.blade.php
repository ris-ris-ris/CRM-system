<nav x-data="{ open: false }" x-init="window.addEventListener('toggle-sidebar', () => open = !open)" class="crm-sidebar {{ request()->is('admin*') ? 'crm-admin-sidebar' : '' }} fixed left-0 top-0 h-screen w-64 shadow-xl z-50 flex flex-col transform transition-transform duration-300 ease-in-out -translate-x-full lg:translate-x-0" :class="{ 'translate-x-0': open }">
    <!-- FX overlay (snow + garland) -->
    <div class="crm-sidebar-fx" aria-hidden="true">
        <canvas class="crm-snow-canvas"></canvas>
        <canvas class="crm-gir-canvas"></canvas>
    </div>
    <style>
        .crm-sidebar{
            /* keep sidebar fixed; do NOT override Tailwind .fixed */
            /* IMPORTANT: don't clip dropdowns */
            overflow: visible;
            isolation: isolate;
            height: 100dvh;
            --sb-grad-1-x: -10%;
            --sb-grad-2-x: 120%;
            background: radial-gradient(1200px 600px at var(--sb-grad-1-x) -10%, rgba(239,68,68,0.18), transparent 55%),
                        radial-gradient(900px 500px at var(--sb-grad-2-x) 20%, rgba(249,115,22,0.16), transparent 55%),
                        linear-gradient(180deg, #0b1220 0%, #0f172a 55%, #0b1220 100%);
            border-right: 1px solid rgba(255,255,255,0.08);
            box-shadow: 0 30px 80px rgba(0,0,0,0.55);
            /* Gradient animation handled by JavaScript */
        }
        .crm-sidebar > :not(.crm-sidebar-fx){ position: relative; z-index: 3; }
        .crm-sidebar::before{
            content:'';
            position:absolute;
            inset:0;
            pointer-events:none;
            /* warm tint to avoid "white corner" */
            background:
                radial-gradient(900px 520px at -10% 0%, rgba(249,115,22,0.10), transparent 60%),
                linear-gradient(180deg, rgba(249,115,22,0.07), transparent 22%, transparent 76%, rgba(239,68,68,0.06));
            opacity: .55;
            z-index: 0;
        }
        /* FX overlay */
        .crm-sidebar > .crm-sidebar-fx{
            position:absolute;
            inset:0;
            pointer-events:none;
            z-index: 2;
            overflow:hidden;
        }
        .crm-snow-canvas{
            position:absolute;
            inset:0;
            width:100%;
            height:100%;
            opacity: .9;
        }
        /* Garland canvas (drawn along a curve) */
        .crm-gir-canvas{
            position:absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 78px;
            opacity: .92;
            filter: drop-shadow(0 10px 16px rgba(0,0,0,0.55)) saturate(0.95);
            z-index: 3;
        }
        .crm-sep-b,
        .crm-sep-t{ position: relative; }
        .crm-sep-b::after{
            content:'';
            position:absolute;
            left: 16px;
            right: 16px;
            bottom: 0;
            height: 1px;
            background: linear-gradient(90deg, rgba(0,0,0,0), rgba(239,68,68,0.95), rgba(249,115,22,0.95), rgba(0,0,0,0));
            background-size: 200% 100%;
            animation: crmOrangeLineShimmer 4.2s ease-in-out infinite;
            opacity: .95;
        }
        .crm-sep-t::before{
            content:'';
            position:absolute;
            left: 16px;
            right: 16px;
            top: 0;
            height: 1px;
            background: linear-gradient(90deg, rgba(0,0,0,0), rgba(249,115,22,0.95), rgba(239,68,68,0.95), rgba(0,0,0,0));
            background-size: 200% 100%;
            animation: crmOrangeLineShimmer 4.2s ease-in-out infinite;
            opacity: .95;
        }
        /* Admin sidebar - same orange glow lines */
        .crm-admin-sidebar .crm-sep-b::after,
        .crm-admin-sidebar .crm-sep-t::before{
            background: linear-gradient(90deg, rgba(0,0,0,0), rgba(239,68,68,0.95), rgba(249,115,22,0.95), rgba(0,0,0,0));
            background-size: 200% 100%;
            animation: crmOrangeLineShimmer 4.2s ease-in-out infinite;
            opacity: .95;
        }
        @keyframes crmOrangeLineShimmer{
            0% { background-position: 0% 50%; filter: brightness(0.92); }
            50% { background-position: 100% 50%; filter: brightness(1.08); }
            100% { background-position: 0% 50%; filter: brightness(0.92); }
        }
        .crm-admin-sidebar{
            --sb-admin-grad-1-x: -20%;
            --sb-admin-grad-2-x: 130%;
            background: radial-gradient(1200px 600px at var(--sb-admin-grad-1-x) -10%, rgba(239,68,68,0.18), transparent 55%),
                        radial-gradient(900px 500px at var(--sb-admin-grad-2-x) 20%, rgba(249,115,22,0.16), transparent 55%),
                        linear-gradient(180deg, #0b1220 0%, #0f172a 55%, #0b1220 100%);
        }
        /* Sidebar logo: subtle orange glow “downwards” */
        .crm-sidebar-logo{
            filter: drop-shadow(0 10px 10px rgba(249,115,22,0.22))
                    drop-shadow(0 6px 8px rgba(239,68,68,0.14));
        }
        .crm-sidebar .x-sidebar-link a,
        .crm-sidebar a{
            transition: background-color .2s ease, color .2s ease, transform .2s ease;
        }
        /* Sidebar content must be scrollable, but we hide the scrollbar визуально (по твоей просьбе) */
        .crm-sidebar .flex-1{
            overflow-y: auto;
            scrollbar-width: none; /* Firefox */
        }
        .crm-sidebar .flex-1::-webkit-scrollbar{ display: none; } /* Chrome/Safari */

        /* Theme toggle */
        .crm-theme-toggle{
            width: 100%;
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap: 10px;
            padding: 8px 10px;
            border-radius: 12px;
            border: 1px solid rgba(255,255,255,0.10);
            background: rgba(15, 23, 42, 0.35);
            box-shadow: inset 0 1px 0 rgba(255,255,255,0.06);
            position: relative;
            overflow: hidden;
        }
        .crm-theme-toggle:hover{ background: rgba(15, 23, 42, 0.48); }
        .crm-theme-toggle:active{ transform: translateY(1px); }
        .crm-theme-toggle .crm-theme-left{ display:flex; align-items:center; gap:8px; min-width:0; }
        .crm-theme-toggle .crm-theme-title{ font-size: 11px; font-weight: 800; color: rgba(226,232,240,0.95); letter-spacing: .02em; }
        .crm-theme-toggle .crm-theme-sub{ font-size: 10px; color: rgba(148,163,184,0.90); margin-top: 1px; }

        .crm-theme-ico{ width: 20px; height: 20px; position: relative; flex: 0 0 auto; }
        .crm-theme-ico svg{ position:absolute; inset:0; width: 20px; height: 20px; transition: opacity .25s ease, transform .45s cubic-bezier(.2,.9,.2,1); }
        .crm-theme-ico .sun{ opacity: 1; transform: rotate(0deg) scale(1); }
        .crm-theme-ico .moon{ opacity: 0; transform: rotate(-35deg) scale(.85); }
        .dark .crm-theme-ico .sun{ opacity: 0; transform: rotate(50deg) scale(.85); }
        .dark .crm-theme-ico .moon{ opacity: 1; transform: rotate(0deg) scale(1); }

        .crm-theme-switch{
            width: 42px; height: 24px;
            border-radius: 999px;
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.10);
            padding: 2px;
            flex: 0 0 auto;
            position: relative;
            overflow: hidden;
        }
        .crm-theme-switch::before{
            content:'';
            position:absolute;
            inset:-40%;
            background: conic-gradient(from 180deg, rgba(239,68,68,0.0), rgba(239,68,68,0.35), rgba(249,115,22,0.35), rgba(239,68,68,0.0));
            opacity: .0;
            animation: crmThemeSpin 2.2s linear infinite;
        }
        .crm-theme-thumb{
            width: 20px; height: 20px;
            border-radius: 999px;
            background: radial-gradient(circle at 30% 30%, rgba(255,255,255,0.85), rgba(255,255,255,0.25) 40%, rgba(0,0,0,0.0) 70%),
                        linear-gradient(135deg, #ef4444 0%, #f97316 100%);
            box-shadow: 0 6px 18px rgba(0,0,0,0.45), inset 0 1px 0 rgba(255,255,255,0.35);
            transform: translateX(0);
            transition: transform .48s cubic-bezier(.2,.9,.2,1);
            position: relative;
            z-index: 1;
        }
        .dark .crm-theme-thumb{ transform: translateX(18px); }
        .dark .crm-theme-switch::before{ opacity: .9; }

        .crm-theme-toggle.crm-theme-burst::after{
            content:'';
            position:absolute;
            left: 14px;
            top: 50%;
            width: 10px; height: 10px;
            transform: translateY(-50%);
            background: radial-gradient(circle, rgba(249,115,22,0.55), rgba(239,68,68,0.25), rgba(0,0,0,0) 70%);
            border-radius: 999px;
            animation: crmThemeBurst .65s ease-out both;
            pointer-events:none;
        }
        @keyframes crmThemeSpin { to { transform: rotate(360deg); } }
        @keyframes crmThemeBurst {
            0% { opacity: 0; transform: translateY(-50%) scale(.4); filter: blur(0px); }
            25% { opacity: 1; }
            100% { opacity: 0; transform: translateY(-50%) scale(14); filter: blur(1px); }
        }
        @media (prefers-reduced-motion: reduce){
            .crm-theme-ico svg, .crm-theme-thumb{ transition: none; }
            .crm-theme-switch::before{ animation: none; }
            .crm-theme-toggle.crm-theme-burst::after{ animation: none; }
        }
    </style>
    <!-- Mobile close button -->
    <div class="crm-sep-b lg:hidden flex justify-end p-4">
        <button @click="open = false" class="text-slate-300 hover:text-white">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    
    <!-- Logo (keep below garland canvas, but stay compact to avoid sidebar scroll) -->
    <div class="crm-sep-b px-6 pt-16 pb-4">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
            <x-application-logo class="h-8 w-8 crm-sidebar-logo" />
            <span class="text-2xl font-bold text-white">CRM</span>
        </a>
    </div>

    <!-- Navigation Links -->
    <div class="flex-1 overflow-y-auto py-3">
        <div class="space-y-1 px-3">
            <x-sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span>Дашборд</span>
            </x-sidebar-link>
            <x-sidebar-link :href="route('reports.page')" :active="request()->routeIs('reports.page')">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 19h16" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 15l4-4 4 4 6-8" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 15h.01M10 11h.01M14 15h.01M20 7h.01" />
                </svg>
                <span>Аналитика</span>
            </x-sidebar-link>
            <x-sidebar-link :href="route('kanban.page')" :active="request()->routeIs('kanban.page')">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2H19a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" />
                </svg>
                <span>Воронка продаж</span>
            </x-sidebar-link>
            <x-sidebar-link :href="route('activities.page')" :active="request()->routeIs('activities.page')">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
                <span>Задачи</span>
            </x-sidebar-link>
            <x-sidebar-link :href="route('calendar.page')" :active="request()->routeIs('calendar.page')">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>Календарь</span>
            </x-sidebar-link>
            <x-sidebar-link :href="route('notes.page')" :active="request()->routeIs('notes.page')">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                <span>Заметки</span>
            </x-sidebar-link>
            <x-sidebar-link :href="route('deals.page')" :active="request()->routeIs('deals.page')">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span>Сделки</span>
            </x-sidebar-link>
            <x-sidebar-link :href="route('companies.page')" :active="request()->routeIs('companies.page')">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <span>Компании</span>
            </x-sidebar-link>
            <x-sidebar-link :href="route('contacts.page')" :active="request()->routeIs('contacts.page')">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span>Контакты</span>
            </x-sidebar-link>
            @if(Auth::user()->is_admin ?? false)
            <x-sidebar-link :href="route('admin.index')" :active="request()->routeIs('admin.*')">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span>Панель</span>
            </x-sidebar-link>
            @endif
        </div>
    </div>

    <!-- Theme toggle -->
    <div class="crm-sep-t px-4 pt-2 pb-2">
        <button type="button" data-theme-toggle class="crm-theme-toggle" aria-pressed="false">
            <div class="crm-theme-left">
                <span class="crm-theme-ico" aria-hidden="true">
                    <svg class="sun" viewBox="0 0 24 24" fill="none">
                        <path d="M12 17.5a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11Z" stroke="rgba(255,255,255,0.9)" stroke-width="1.6"/>
                        <path d="M12 2.5v2.2M12 19.3v2.2M21.5 12h-2.2M4.7 12H2.5M19 5l-1.55 1.55M6.55 17.45 5 19M19 19l-1.55-1.55M6.55 6.55 5 5" stroke="rgba(249,115,22,0.95)" stroke-width="1.6" stroke-linecap="round"/>
                    </svg>
                    <svg class="moon" viewBox="0 0 24 24" fill="none">
                        <path d="M21 14.4A7.7 7.7 0 0 1 9.6 3a6.2 6.2 0 1 0 11.4 11.4Z" stroke="rgba(255,255,255,0.9)" stroke-width="1.6" stroke-linejoin="round"/>
                        <path d="M15.6 4.2c.5.2 1 .5 1.4.9.5.4.9.9 1.2 1.5" stroke="rgba(239,68,68,0.95)" stroke-width="1.6" stroke-linecap="round"/>
                    </svg>
                </span>
                <div class="min-w-0">
                    <div class="crm-theme-title">Тема</div>
                    <div class="crm-theme-sub">светлая / тёмная</div>
                </div>
            </div>
            <span class="crm-theme-switch" aria-hidden="true"><span class="crm-theme-thumb"></span></span>
        </button>
    </div>

    <!-- User Section -->
    <div class="p-4 pt-2">
        <x-dropdown align="right" width="64">
            <x-slot name="trigger">
                <button onclick="loadUserStats()" class="group w-full flex items-center justify-between px-3 py-2 rounded-lg text-white hover:bg-slate-800 transition">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-slate-700 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="url(#orangeGradientSidebar)" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div class="text-left">
                            <div class="text-sm font-medium">{{ Auth::user()->name }}</div>
                            <div class="text-xs text-slate-400">{{ Auth::user()->email }}</div>
                        </div>
                    </div>
                    <!-- Индикатор "раскрывается" (более аккуратный) -->
                    <svg class="shrink-0 w-3.5 h-3.5 text-slate-300 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </x-slot>

            <x-slot name="content">
                <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                    <div class="font-medium text-sm text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</div>
                    @if(Auth::user()->is_admin)
                        <div class="text-xs text-red-600 dark:text-red-400 mt-1 font-semibold">Администратор</div>
                    @elseif(Auth::user()->role === 'manager')
                        <div class="text-xs text-blue-600 dark:text-blue-400 mt-1 font-semibold">Менеджер</div>
                    @else
                        <div class="text-xs text-gray-600 dark:text-gray-400 mt-1 font-semibold">Сотрудник</div>
                    @endif
                </div>
                
                <x-dropdown-link :href="route('profile.edit')">
                    Профиль
                </x-dropdown-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-dropdown-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        Выйти
                    </x-dropdown-link>
                </form>
            </x-slot>
        </x-dropdown>
    </div>
</nav>

<script>
async function loadUserStats() {
    try {
        const response = await fetch('/api/users/{{ Auth::id() }}');
        const data = await response.json();
        if (data.stats) {
            document.getElementById('statDeals').textContent = data.stats.deals || 0;
            document.getElementById('statActivities').textContent = data.stats.activities || 0;
        }
    } catch (e) {
        console.error('Error loading stats:', e);
    }
}

(function initCrmSidebarSnow() {
    // replaced with initCrmSidebarFx
})();

(function initCrmSidebarFx() {
    if (window.__crmSidebarFxInit) return;
    window.__crmSidebarFxInit = true;

    const sidebar = document.querySelector('.crm-sidebar');
    const fx = sidebar && sidebar.querySelector('.crm-sidebar-fx');
    if (!sidebar || !fx) return;

    // Garland (sprite) -> canvas, drawn along a smooth sag curve
    (function initGirCanvas() {
        const canvas = fx.querySelector('.crm-gir-canvas');
        if (!canvas) return;
        const ctx = canvas.getContext('2d', { alpha: true });
        if (!ctx) return;

        const img = new Image();
        img.decoding = 'async';
        img.src = '/gir.png';

        const FRAME_H = 62;
        const framesY = [0, -62, -124];
        let frameY = 0;
        let w = 0, dpr = 1;

        function resize() {
            const rect = sidebar.getBoundingClientRect();
            w = Math.max(1, Math.floor(rect.width));
            dpr = Math.max(1, Math.min(2, window.devicePixelRatio || 1));
            canvas.width = Math.floor(w * dpr);
            canvas.height = Math.floor(78 * dpr);
            ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
        }

        function draw() {
            if (!img.complete || !img.naturalWidth) return;
            ctx.clearRect(0, 0, w, 78);

            const scale = 0.86;          // smaller + softer
            const tileW = img.naturalWidth * scale;
            const tileH = FRAME_H * scale;
            const sliceW = 12 * scale;   // small slices -> smooth sag
            const baseY = 2;
            const sag = 10;              // px: drop towards center

            // Fade edges slightly like your example
            ctx.save();
            const grad = ctx.createLinearGradient(0, 0, w, 0);
            grad.addColorStop(0, 'rgba(0,0,0,0)');
            grad.addColorStop(0.10, 'rgba(0,0,0,1)');
            grad.addColorStop(0.90, 'rgba(0,0,0,1)');
            grad.addColorStop(1, 'rgba(0,0,0,0)');
            ctx.globalCompositeOperation = 'source-over';

            // draw slices across repeated tiles
            const ySrc = (frameY === 0) ? 0 : (frameY === -62 ? 62 : 124);
            for (let x0 = -tileW; x0 < w + tileW; x0 += tileW) {
                for (let sx = 0; sx < tileW; sx += sliceW) {
                    const gx = x0 + sx;
                    if (gx < -sliceW || gx > w + sliceW) continue;
                    const t = Math.min(1, Math.max(0, gx / w));
                    const drop = sag * Math.sin(Math.PI * t);
                    const dx = gx;
                    const dy = baseY + drop;
                    const srcX = Math.floor((sx / scale));
                    const srcW = Math.max(1, Math.floor(sliceW / scale));
                    ctx.drawImage(img, srcX, ySrc, srcW, FRAME_H, dx, dy, sliceW, tileH);
                }
            }

            // edge mask
            ctx.globalCompositeOperation = 'destination-in';
            ctx.fillStyle = grad;
            ctx.fillRect(0, 0, w, 78);
            ctx.restore();
        }

        function tick() {
            draw();
            requestAnimationFrame(tick);
        }

        img.onload = () => {
            resize();
        };

        // random frame switching
        window.setInterval(() => {
            frameY = framesY[Math.floor(Math.random() * 3)];
        }, 180);

        resize();
        try {
            const ro = new ResizeObserver(() => resize());
            ro.observe(sidebar);
        } catch (_) {
            window.addEventListener('resize', resize, { passive: true });
        }
        requestAnimationFrame(tick);
    })();

    // Snow canvas
    const canvas = fx.querySelector('.crm-snow-canvas');
    if (!canvas) return;
    const ctx = canvas.getContext('2d', { alpha: true });
    if (!ctx) return;

    let w = 0, h = 0, dpr = 1;
    const flakes = [];
    const maxFlakes = 70;

    function resize() {
        const rect = sidebar.getBoundingClientRect();
        w = Math.max(1, Math.floor(rect.width));
        h = Math.max(1, Math.floor(rect.height));
        dpr = Math.max(1, Math.min(2, window.devicePixelRatio || 1));
        canvas.width = Math.floor(w * dpr);
        canvas.height = Math.floor(h * dpr);
        ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
    }

    function spawn() {
        const r = 1.1 + Math.random() * 2.6;
        return {
            x: Math.random() * w,
            y: -10 - Math.random() * h,
            r,
            vx: (-8 + Math.random() * 16),            // px/s
            vy: (16 + Math.random() * 26) * (2.6 - (r / 2.2)), // px/s (bigger flakes fall a bit slower)
            a: 0.35 + Math.random() * 0.55,
            t: Math.random() * Math.PI * 2,
            rot: Math.random() * Math.PI,
        };
    }

    function ensureFlakes() {
        while (flakes.length < maxFlakes) flakes.push(spawn());
    }

    let last = performance.now();
    function frame(now) {
        const dt = Math.min(0.033, (now - last) / 1000);
        last = now;

        ctx.clearRect(0, 0, w, h);
        ensureFlakes();

        function drawFlake(x, y, r, rot, alpha) {
            // simple 6-arm snowflake (crisp lines)
            ctx.save();
            ctx.translate(x, y);
            ctx.rotate(rot);
            ctx.globalAlpha = alpha;
            ctx.strokeStyle = 'rgba(255,255,255,0.92)';
            ctx.lineWidth = Math.max(1, r * 0.45);
            ctx.lineCap = 'round';
            for (let k = 0; k < 3; k++) {
                ctx.rotate(Math.PI / 3);
                ctx.beginPath();
                ctx.moveTo(-r, 0);
                ctx.lineTo(r, 0);
                ctx.stroke();
            }
            ctx.restore();
        }

        for (let i = 0; i < flakes.length; i++) {
            const f = flakes[i];
            f.t += dt * 1.2;
            f.rot += dt * 0.35;
            f.x += f.vx * dt + Math.sin(f.t * 0.9) * 4.0 * dt;
            f.y += f.vy * dt;

            // wrap
            if (f.y > h + 12 || f.x < -20 || f.x > w + 20) {
                flakes[i] = spawn();
                flakes[i].y = -12;
                continue;
            }

            drawFlake(f.x, f.y, f.r, f.rot, f.a);
        }
        ctx.globalAlpha = 1;
        requestAnimationFrame(frame);
    }

    resize();
    try {
        const ro = new ResizeObserver(() => resize());
        ro.observe(sidebar);
    } catch (_) {
        window.addEventListener('resize', resize, { passive: true });
    }
    requestAnimationFrame(frame);
})();
</script>
