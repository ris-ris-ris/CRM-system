<nav class="crm-admin-sidebar fixed left-0 top-0 h-screen w-64 shadow-xl z-50 flex flex-col" style="height: 100dvh;">
    <style>
        .crm-admin-sidebar{
            /* keep sidebar fixed; do NOT override Tailwind .fixed */
            overflow: visible;
            isolation: isolate;
            --sb-admin-grad-1-x: -20%;
            --sb-admin-grad-2-x: 130%;
            background: radial-gradient(900px 520px at var(--sb-admin-grad-1-x) -10%, rgba(239,68,68,0.18), transparent 60%),
                        radial-gradient(900px 520px at var(--sb-admin-grad-2-x) 10%, rgba(249,115,22,0.14), transparent 60%),
                        linear-gradient(180deg, #0b1220 0%, #0f172a 55%, #0b1220 100%);
            border-right: 1px solid rgba(255,255,255,0.08);
            box-shadow: 0 30px 80px rgba(0,0,0,0.55);
            /* Gradient animation handled by JavaScript */
        }
        .crm-admin-sidebar > *{ position: relative; z-index: 1; }
        .crm-admin-sidebar::before{
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
        .crm-admin-sep-b,
        .crm-admin-sep-t{ position: relative; }
        .crm-admin-sep-b::after{
            content:'';
            position:absolute;
            left: 16px;
            right: 16px;
            bottom: 0;
            height: 1px;
            background: linear-gradient(90deg, rgba(0,0,0,0), rgba(239,68,68,0.95), rgba(249,115,22,0.95), rgba(0,0,0,0));
            opacity: .95;
        }
        .crm-admin-sep-t::before{
            content:'';
            position:absolute;
            left: 16px;
            right: 16px;
            top: 0;
            height: 1px;
            background: linear-gradient(90deg, rgba(0,0,0,0), rgba(249,115,22,0.95), rgba(239,68,68,0.95), rgba(0,0,0,0));
            opacity: .95;
        }
        /* Hide scrollbar visually on admin sidebar too */
        .crm-admin-sidebar .flex-1{
            overflow-y: auto;
            scrollbar-width: none;
        }
        .crm-admin-sidebar .flex-1::-webkit-scrollbar{ display:none; }

        /* Admin pages dark-mode normalization (applies to all admin pages because sidebar is included) */
        html.dark body{
            background:
                radial-gradient(900px 520px at 0% 0%, rgba(239,68,68,0.14), transparent 62%),
                radial-gradient(900px 520px at 100% 10%, rgba(249,115,22,0.14), transparent 62%),
                radial-gradient(800px 520px at 40% 120%, rgba(59,130,246,0.10), transparent 65%),
                linear-gradient(180deg, #050814 0%, #070b16 60%, #050814 100%);
            color: rgba(226,232,240,0.96);
        }
        html.dark .bg-white{ background-color: rgba(15,23,42,0.92) !important; }
        html.dark .bg-gray-50{ background-color: rgba(15,23,42,0.70) !important; }
        html.dark .bg-gray-100{ background-color: transparent !important; }
        html.dark .border-gray-200{ border-color: rgba(255,255,255,0.10) !important; }
        html.dark .text-gray-900,
        html.dark .text-gray-800{ color: rgba(226,232,240,0.96) !important; }
        html.dark .text-gray-700,
        html.dark .text-gray-600{ color: rgba(203,213,225,0.86) !important; }
        html.dark .text-gray-500,
        html.dark .text-gray-400{ color: rgba(148,163,184,0.92) !important; }
        html.dark .bg-gray-200{ background-color: rgba(148,163,184,0.18) !important; }
        html.dark .bg-red-100{ background-color: rgba(239,68,68,0.16) !important; }
        html.dark .bg-blue-100{ background-color: rgba(59,130,246,0.16) !important; }
        html.dark .text-red-800{ color: rgba(252,165,165,0.95) !important; }
        html.dark .text-blue-800{ color: rgba(147,197,253,0.95) !important; }

        html.dark .shadow-lg{ box-shadow: 0 18px 50px rgba(0,0,0,0.55) !important; }
        html.dark .shadow-2xl{ box-shadow: 0 22px 80px rgba(0,0,0,0.65) !important; }

        /* Admin custom classes used in pages (they set colors in <style> without dark variants) */
        html.dark .admin-card{
            background: linear-gradient(135deg, rgba(15,23,42,0.96) 0%, rgba(15,23,42,0.84) 100%) !important;
            border-color: rgba(255,255,255,0.10) !important;
        }
        html.dark input,
        html.dark select,
        html.dark textarea{
            background: rgba(15,23,42,0.70) !important;
            border-color: rgba(255,255,255,0.12) !important;
            color: rgba(226,232,240,0.96) !important;
        }
        html.dark input::placeholder,
        html.dark textarea::placeholder{
            color: rgba(148,163,184,0.92) !important;
        }

        /* Kill shine “waves” on admin cards in dark (if any use premium-card styles) */
        html.dark .premium-card::before{ display:none !important; }

        /* Theme toggle (same feel as CRM sidebar) */
        .crm-theme-toggle{
            width: 100%;
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap: 12px;
            padding: 10px 12px;
            border-radius: 14px;
            border: 1px solid rgba(255,255,255,0.10);
            background: rgba(15, 23, 42, 0.35);
            box-shadow: inset 0 1px 0 rgba(255,255,255,0.06);
            position: relative;
            overflow: hidden;
        }
        .crm-theme-toggle:hover{ background: rgba(15, 23, 42, 0.48); }
        .crm-theme-toggle:active{ transform: translateY(1px); }
        .crm-theme-toggle .crm-theme-left{ display:flex; align-items:center; gap:10px; min-width:0; }
        .crm-theme-toggle .crm-theme-title{ font-size: 12px; font-weight: 800; color: rgba(226,232,240,0.95); letter-spacing: .02em; }
        .crm-theme-toggle .crm-theme-sub{ font-size: 11px; color: rgba(148,163,184,0.90); margin-top: 1px; }
        .crm-theme-ico{ width: 22px; height: 22px; position: relative; flex: 0 0 auto; }
        .crm-theme-ico svg{ position:absolute; inset:0; width: 22px; height: 22px; transition: opacity .25s ease, transform .45s cubic-bezier(.2,.9,.2,1); }
        .crm-theme-ico .sun{ opacity: 1; transform: rotate(0deg) scale(1); }
        .crm-theme-ico .moon{ opacity: 0; transform: rotate(-35deg) scale(.85); }
        .dark .crm-theme-ico .sun{ opacity: 0; transform: rotate(50deg) scale(.85); }
        .dark .crm-theme-ico .moon{ opacity: 1; transform: rotate(0deg) scale(1); }
        .crm-theme-switch{
            width: 46px; height: 26px;
            border-radius: 999px;
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.10);
            padding: 3px;
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
        .dark .crm-theme-thumb{ transform: translateX(20px); }
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

    <script>
        // Make theme toggle work on ALL admin pages (users/settings/database/etc)
        (function initAdminThemeToggle() {
            if (window.__crmAdminThemeToggleInit) return;
            window.__crmAdminThemeToggleInit = true;

            const root = document.documentElement;

            function applySavedTheme() {
                try {
                    const saved = localStorage.getItem('crm_theme');
                    if (saved === 'dark') root.classList.add('dark');
                    else root.classList.remove('dark');
                } catch (_) {}
            }

            function isDark() { return root.classList.contains('dark'); }

            function animateSidebarGradients(sidebar, adminSidebar, isDark) {
                if (!sidebar && !adminSidebar) return;
                
                const duration = 600;
                const startTime = performance.now();
                
                const start1 = sidebar ? parseFloat(sidebar.style.getPropertyValue('--sb-grad-1-x') || '-10') : -10;
                const start2 = sidebar ? parseFloat(sidebar.style.getPropertyValue('--sb-grad-2-x') || '120') : 120;
                const startA1 = adminSidebar ? parseFloat(adminSidebar.style.getPropertyValue('--sb-admin-grad-1-x') || '-20') : -20;
                const startA2 = adminSidebar ? parseFloat(adminSidebar.style.getPropertyValue('--sb-admin-grad-2-x') || '130') : 130;
                
                const end1 = isDark ? 120 : -10;
                const end2 = isDark ? -10 : 120;
                const endA1 = isDark ? 130 : -20;
                const endA2 = isDark ? -20 : 130;
                
                function animate(currentTime) {
                    const elapsed = currentTime - startTime;
                    const progress = Math.min(elapsed / duration, 1);
                    const ease = progress < 0.5 
                        ? 2 * progress * progress 
                        : 1 - Math.pow(-2 * progress + 2, 2) / 2;
                    
                    if (sidebar) {
                        sidebar.style.setProperty('--sb-grad-1-x', (start1 + (end1 - start1) * ease) + '%');
                        sidebar.style.setProperty('--sb-grad-2-x', (start2 + (end2 - start2) * ease) + '%');
                    }
                    if (adminSidebar) {
                        adminSidebar.style.setProperty('--sb-admin-grad-1-x', (startA1 + (endA1 - startA1) * ease) + '%');
                        adminSidebar.style.setProperty('--sb-admin-grad-2-x', (startA2 + (endA2 - startA2) * ease) + '%');
                    }
                    
                    if (progress < 1) {
                        requestAnimationFrame(animate);
                    }
                }
                requestAnimationFrame(animate);
            }
            
            function setTheme(theme) {
                root.classList.toggle('dark', theme === 'dark');
                try { localStorage.setItem('crm_theme', theme); } catch (_) {}
                document.querySelectorAll('[data-theme-toggle]').forEach((btn) => {
                    btn.setAttribute('aria-pressed', theme === 'dark' ? 'true' : 'false');
                });
                try { window.dispatchEvent(new CustomEvent('crm-theme-changed', { detail: { theme } })); } catch (_) {}
                
                // Mirror sidebar gradients on theme change with animation
                const sidebar = document.querySelector('.crm-sidebar');
                const adminSidebar = document.querySelector('.crm-admin-sidebar');
                animateSidebarGradients(sidebar, adminSidebar, theme === 'dark');
            }

            applySavedTheme();
            const current = isDark() ? 'dark' : 'light';
            document.querySelectorAll('[data-theme-toggle]').forEach((btn) => {
                btn.setAttribute('aria-pressed', current === 'dark' ? 'true' : 'false');
            });
            
            // Initialize sidebar gradient positions (no animation on load)
            const sidebar = document.querySelector('.crm-sidebar');
            const adminSidebar = document.querySelector('.crm-admin-sidebar');
            if (current === 'dark') {
                if (sidebar) {
                    sidebar.style.setProperty('--sb-grad-1-x', '120%');
                    sidebar.style.setProperty('--sb-grad-2-x', '-10%');
                }
                if (adminSidebar) {
                    adminSidebar.style.setProperty('--sb-admin-grad-1-x', '130%');
                    adminSidebar.style.setProperty('--sb-admin-grad-2-x', '-20%');
                }
            } else {
                if (sidebar) {
                    sidebar.style.setProperty('--sb-grad-1-x', '-10%');
                    sidebar.style.setProperty('--sb-grad-2-x', '120%');
                }
                if (adminSidebar) {
                    adminSidebar.style.setProperty('--sb-admin-grad-1-x', '-20%');
                    adminSidebar.style.setProperty('--sb-admin-grad-2-x', '130%');
                }
            }

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
    <!-- Logo -->
    <div class="crm-admin-sep-b p-6">
        <a href="{{ route('admin.index') }}" class="flex items-center">
            <span class="text-2xl font-bold text-white">Панель управления</span>
        </a>
    </div>

    <!-- Navigation Links -->
    <div class="flex-1 overflow-y-auto py-4">
        <div class="space-y-1 px-3">
            <a href="{{ route('admin.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg font-medium transition {{ request()->routeIs('admin.index') ? 'text-white bg-slate-800' : 'text-slate-200 hover:bg-slate-800' }}">
                <svg class="w-5 h-5" fill="none" stroke="url(#orangeGradientSidebar)" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span>Дашборд</span>
            </a>
            <a href="{{ route('admin.users') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg font-medium transition {{ request()->routeIs('admin.users') ? 'text-white bg-slate-800' : 'text-slate-200 hover:bg-slate-800' }}">
                <svg class="w-5 h-5" fill="none" stroke="url(#orangeGradientSidebar)" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <span>Пользователи</span>
            </a>
            <a href="{{ route('admin.settings') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg font-medium transition {{ request()->routeIs('admin.settings') ? 'text-white bg-slate-800' : 'text-slate-200 hover:bg-slate-800' }}">
                <svg class="w-5 h-5" fill="none" stroke="url(#orangeGradientSidebar)" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span>Настройки</span>
            </a>
            <a href="{{ route('admin.database') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg font-medium transition {{ request()->routeIs('admin.database') ? 'text-white bg-slate-800' : 'text-slate-200 hover:bg-slate-800' }}">
                <svg class="w-5 h-5" fill="none" stroke="url(#orangeGradientSidebar)" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                </svg>
                <span>База данных</span>
            </a>
        </div>
    </div>

    <!-- Theme toggle -->
    <div class="crm-admin-sep-t px-4 pt-3 pb-2">
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
    <div class="p-4 pt-2 space-y-2">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-slate-800 transition font-semibold" style="background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(255, 255, 255, 0.85) 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; color: white;">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Вернуться в CRM</span>
        </a>
        <form method="POST" action="{{ route('logout') }}" class="inline w-full">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-4 py-2 rounded-lg text-red-400 hover:text-red-300 hover:bg-slate-800 transition font-semibold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span>Выйти</span>
            </button>
        </form>
    </div>
</nav>

<svg width="0" height="0" style="position:absolute; z-index:-1;">
    <defs>
        <linearGradient id="orangeGradientSidebar" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stop-color="#ef4444" stop-opacity="1" />
            <stop offset="100%" stop-color="#f97316" stop-opacity="1" />
        </linearGradient>
    </defs>
</svg>

