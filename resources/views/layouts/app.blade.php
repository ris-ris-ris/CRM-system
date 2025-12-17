<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

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

        <title>CRM</title>
        <link rel="icon" href="/logo.svg">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Styles (CDN minimal) -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
        <link rel="stylesheet" href="/css/crm.css">
        <link rel="stylesheet" href="/css/premium.css">
        
        <!-- SVG Gradient for sidebar icons -->
        <svg width="0" height="0" style="position: absolute;">
            <defs>
                <linearGradient id="orangeGradientSidebar" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" style="stop-color:#ef4444;stop-opacity:1" />
                    <stop offset="100%" style="stop-color:#f97316;stop-opacity:1" />
                </linearGradient>
            </defs>
        </svg>
        <style>
            .sidebar-icon svg {
                stroke: url(#orangeGradientSidebar);
            }

            /* Theme layer (Tailwind CDN build uses prefers-color-scheme for dark: by default).
               We implement class-based theming via html.dark to make the toggle actually visible. */
            :root{
                --crm-bg: #f3f4f6;
                --crm-surface: #ffffff;
                --crm-surface-2: #f8fafc;
                --crm-border: #e5e7eb;
                --crm-text: #0f172a;
                --crm-muted: #475569;
                --crm-muted-2: #64748b;
            }
            html.dark{
                --crm-bg: #050814;
                --crm-surface: rgba(15, 23, 42, 0.92);
                --crm-surface-2: rgba(15, 23, 42, 0.68);
                --crm-border: rgba(255, 255, 255, 0.10);
                --crm-text: rgba(226, 232, 240, 0.96);
                --crm-muted: rgba(203, 213, 225, 0.86);
                --crm-muted-2: rgba(148, 163, 184, 0.92);
                color-scheme: dark;
            }
            .crm-shell{
                background: var(--crm-bg);
                color: var(--crm-text);
            }
            html.dark .crm-shell{
                background:
                    radial-gradient(900px 520px at 0% 0%, rgba(239,68,68,0.14), transparent 62%),
                    radial-gradient(900px 520px at 100% 10%, rgba(249,115,22,0.14), transparent 62%),
                    radial-gradient(800px 520px at 40% 120%, rgba(59,130,246,0.10), transparent 65%),
                    linear-gradient(180deg, #050814 0%, #070b16 60%, #050814 100%);
            }
            .crm-shell .crm-header{
                background: var(--crm-surface);
                color: var(--crm-text);
                border-bottom: 1px solid var(--crm-border);
            }
            /* common Tailwind colors used in views */
            .crm-shell .bg-white{ background-color: var(--crm-surface) !important; }
            .crm-shell .bg-gray-50{ background-color: var(--crm-surface-2) !important; }
            .crm-shell .bg-gray-100{ background-color: var(--crm-bg) !important; }
            .crm-shell .border-gray-200{ border-color: var(--crm-border) !important; }
            .crm-shell .border-gray-300{ border-color: var(--crm-border) !important; }
            .crm-shell .divide-gray-200 > :not([hidden]) ~ :not([hidden]){ border-color: var(--crm-border) !important; }

            .crm-shell .text-gray-900{ color: var(--crm-text) !important; }
            .crm-shell .text-gray-800{ color: var(--crm-text) !important; }
            .crm-shell .text-gray-700{ color: var(--crm-muted) !important; }
            .crm-shell .text-gray-600{ color: var(--crm-muted) !important; }
            .crm-shell .text-gray-500{ color: var(--crm-muted-2) !important; }
            .crm-shell .text-gray-400{ color: rgba(148,163,184,0.95) !important; }

            /* make “pastel chips” not look toxic in dark */
            html.dark .crm-shell .bg-gray-200{ background-color: rgba(148,163,184,0.18) !important; }
            html.dark .crm-shell .bg-red-100{ background-color: rgba(239,68,68,0.16) !important; }
            html.dark .crm-shell .bg-blue-100{ background-color: rgba(59,130,246,0.16) !important; }
            html.dark .crm-shell .bg-green-100{ background-color: rgba(34,197,94,0.16) !important; }
            html.dark .crm-shell .bg-orange-100{ background-color: rgba(249,115,22,0.16) !important; }
            html.dark .crm-shell .text-red-800{ color: rgba(252,165,165,0.95) !important; }
            html.dark .crm-shell .text-blue-800{ color: rgba(147,197,253,0.95) !important; }
            html.dark .crm-shell .text-blue-900{ color: rgba(191,219,254,0.95) !important; }
            html.dark .crm-shell .text-orange-800{ color: rgba(253,186,116,0.95) !important; }
            html.dark .crm-shell .text-gray-800{ color: rgba(226,232,240,0.96) !important; }

            /* Base dark UI normalization (Tailwind CDN dark: won't help us) */
            html.dark .crm-shell .bg-gray-50{ background-color: rgba(15,23,42,0.70) !important; }
            html.dark .crm-shell .bg-gray-100{ background-color: transparent !important; }
            html.dark .crm-shell .bg-gray-700{ background-color: rgba(15,23,42,0.78) !important; }
            html.dark .crm-shell .bg-gray-800{ background-color: rgba(15,23,42,0.88) !important; }
            html.dark .crm-shell .bg-gray-900{ background-color: rgba(2,6,23,0.92) !important; }

            html.dark .crm-shell .border-gray-600{ border-color: rgba(255,255,255,0.12) !important; }
            html.dark .crm-shell .border-gray-700{ border-color: rgba(255,255,255,0.10) !important; }
            html.dark .crm-shell .divide-gray-700 > :not([hidden]) ~ :not([hidden]){ border-color: rgba(255,255,255,0.10) !important; }

            /* Hover utilities commonly used in templates */
            html.dark .crm-shell .hover\:bg-gray-50:hover{ background-color: rgba(15,23,42,0.76) !important; }
            html.dark .crm-shell .hover\:bg-gray-100:hover{ background-color: rgba(15,23,42,0.78) !important; }
            html.dark .crm-shell .hover\:bg-gray-600:hover{ background-color: rgba(15,23,42,0.80) !important; }
            html.dark .crm-shell .hover\:bg-gray-700:hover{ background-color: rgba(15,23,42,0.86) !important; }

            /* Inputs / selects / textareas (generic) */
            html.dark .crm-shell input[type="text"],
            html.dark .crm-shell input[type="email"],
            html.dark .crm-shell input[type="password"],
            html.dark .crm-shell input[type="number"],
            html.dark .crm-shell input[type="search"],
            html.dark .crm-shell input[type="date"],
            html.dark .crm-shell input[type="datetime-local"],
            html.dark .crm-shell input[type="url"],
            html.dark .crm-shell textarea,
            html.dark .crm-shell select{
                background-color: rgba(15,23,42,0.62) !important;
                border-color: rgba(255,255,255,0.12) !important;
                color: rgba(226,232,240,0.96) !important;
                box-shadow: inset 0 1px 0 rgba(255,255,255,0.04);
            }
            html.dark .crm-shell input::placeholder,
            html.dark .crm-shell textarea::placeholder{
                color: rgba(148,163,184,0.92) !important;
            }
            html.dark .crm-shell select option{
                background-color: #0b1220;
                color: rgba(226,232,240,0.96);
            }
            html.dark .crm-shell input:focus,
            html.dark .crm-shell textarea:focus,
            html.dark .crm-shell select:focus{
                outline: none;
                border-color: rgba(249,115,22,0.70) !important;
                box-shadow: 0 0 0 4px rgba(249,115,22,0.16) !important;
            }

            /* Tables */
            html.dark .crm-shell table{
                color: rgba(226,232,240,0.94);
            }
            html.dark .crm-shell thead{
                background: rgba(15,23,42,0.70);
            }
            html.dark .crm-shell tbody tr:hover{
                background: rgba(15,23,42,0.62);
            }

            /* Shadows - remove "dirty gray" look in dark */
            html.dark .crm-shell .shadow{ box-shadow: 0 12px 30px rgba(0,0,0,0.45) !important; }
            html.dark .crm-shell .shadow-lg{ box-shadow: 0 18px 50px rgba(0,0,0,0.55) !important; }
            html.dark .crm-shell .shadow-2xl{ box-shadow: 0 22px 80px rgba(0,0,0,0.65) !important; }

            /* “Windows/panels” in dark: force premium dark surfaces everywhere */
            html.dark .crm-shell .premium-card,
            html.dark .crm-shell .admin-card,
            html.dark .crm-shell .stat-card{
                background:
                    radial-gradient(900px 520px at 0% 0%, rgba(239,68,68,0.10), transparent 62%),
                    radial-gradient(900px 520px at 100% 0%, rgba(249,115,22,0.10), transparent 62%),
                    linear-gradient(135deg, rgba(15,23,42,0.96) 0%, rgba(15,23,42,0.82) 100%) !important;
                border-color: rgba(255,255,255,0.10) !important;
            }

            /* Kill the “white waves” (shine sweep) in dark theme */
            html.dark .crm-shell .premium-card::before{
                display: none !important;
            }

            /* Gradients: only neutral “panel” gradients should become dark.
               DO NOT override colored KPI cards on dashboard (blue/green/purple/orange). */
            html.dark .crm-shell .bg-gradient-to-br.from-gray-50,
            html.dark .crm-shell .bg-gradient-to-br.from-gray-100,
            html.dark .crm-shell .bg-gradient-to-br.from-gray-200,
            html.dark .crm-shell .bg-gradient-to-r.from-gray-50,
            html.dark .crm-shell .bg-gradient-to-r.from-gray-100,
            html.dark .crm-shell .bg-gradient-to-r.from-gray-200{
                background-image: linear-gradient(135deg, rgba(15,23,42,0.94) 0%, rgba(15,23,42,0.74) 100%) !important;
                background-color: rgba(15,23,42,0.90) !important;
            }
            html.dark .crm-shell .bg-gradient-to-br.from-green-50{
                background-image: linear-gradient(135deg, rgba(34,197,94,0.14) 0%, rgba(15,23,42,0.84) 70%) !important;
                background-color: rgba(15,23,42,0.90) !important;
            }
            html.dark .crm-shell .bg-gradient-to-br.from-red-50{
                background-image: linear-gradient(135deg, rgba(239,68,68,0.14) 0%, rgba(15,23,42,0.84) 70%) !important;
                background-color: rgba(15,23,42,0.90) !important;
            }

            /* Modals */
            html.dark .crm-shell .modal-premium{
                background: linear-gradient(135deg, rgba(15,23,42,0.96) 0%, rgba(15,23,42,0.86) 100%) !important;
                border: 1px solid rgba(255,255,255,0.12) !important;
            }

            /* subtle transitions (not for everything) */
            .crm-shell,
            .crm-shell .crm-header,
            .crm-shell .premium-card,
            .crm-shell .admin-card,
            .crm-shell .stat-card{
                transition: background-color .22s ease, color .22s ease, border-color .22s ease;
            }
            
            /* Kanban deal cards - instant color swap, only hover animates */
            .crm-shell .kanban-deal{
                transition: transform .18s ease, box-shadow .18s ease !important;
                background-color: var(--crm-surface) !important;
            }
            html.dark .crm-shell .kanban-deal.bg-white,
            html.dark .crm-shell .kanban-deal[class*="bg-white"]{
                background-color: rgba(15,23,42,0.88) !important;
            }
            .crm-shell .kanban-stage{
                transition: none !important;
            }
            
            /* Text colors in kanban cards - instant switch */
            html.dark .crm-shell .kanban-deal .text-gray-900{
                color: rgba(226,232,240,0.96) !important;
            }
            html.dark .crm-shell .kanban-deal .text-gray-600{
                color: rgba(203,213,225,0.86) !important;
            }
            html.dark .crm-shell .kanban-deal .text-gray-500{
                color: rgba(148,163,184,0.92) !important;
            }
            html.dark .crm-shell .kanban-deal .text-gray-400{
                color: rgba(148,163,184,0.75) !important;
            }
            
            /* Kanban stage columns - instant switch */
            html.dark .crm-shell [class*="from-gray-50"][class*="to-gray-100"]{
                background-image: linear-gradient(to bottom right, rgba(15,23,42,0.78), rgba(15,23,42,0.68)) !important;
            }

            /* Shimmering section divider (used in Analytics separators) */
            .crm-shimmer-line-white{
                height: 1px;
                background: linear-gradient(90deg,
                    rgba(255,255,255,0),
                    rgba(255,255,255,0.10),
                    rgba(255,255,255,0.65),
                    rgba(255,255,255,0.10),
                    rgba(255,255,255,0)
                );
                background-size: 220% 100%;
                animation: crmWhiteLineShimmer 5.2s ease-in-out infinite;
                opacity: .95;
            }
            @keyframes crmWhiteLineShimmer{
                0% { background-position: 0% 50%; }
                50% { background-position: 100% 50%; }
                100% { background-position: 0% 50%; }
            }
        </style>
        
        <!-- Alpine.js -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="font-sans antialiased">
        <div class="crm-shell min-h-screen flex">
            <!-- Mobile overlay -->
            <div x-data="{ open: false }" x-init="window.addEventListener('toggle-sidebar', () => { open = !open; document.getElementById('mobile-overlay').classList.toggle('hidden'); })" class="lg:hidden">
                <div id="mobile-overlay" class="hidden fixed inset-0 bg-black bg-opacity-50 z-40" onclick="document.dispatchEvent(new CustomEvent('toggle-sidebar'))"></div>
            </div>
            
            @include('layouts.navigation')

            <!-- Main Content -->
            <div class="flex-1 lg:ml-64">
                <!-- Mobile menu button -->
                <div class="lg:hidden fixed top-4 left-4 z-30">
                    <button onclick="document.dispatchEvent(new CustomEvent('toggle-sidebar'))" class="p-2 bg-slate-900 text-white rounded-lg shadow-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
                <!-- Page Heading -->
                @isset($header)
                    <header class="crm-header shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main class="p-6">
                    {{ $slot }}
                </main>
            </div>
        </div>

        <script>
            (function initCrmThemeToggle() {
                if (window.__crmThemeToggleInit) return;
                window.__crmThemeToggleInit = true;

                const root = document.documentElement;

                function isDark() {
                    return root.classList.contains('dark');
                }

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
                    
                    // Mirror sidebar gradients on theme change with animation
                    const sidebar = document.querySelector('.crm-sidebar');
                    const adminSidebar = document.querySelector('.crm-admin-sidebar');
                    animateSidebarGradients(sidebar, adminSidebar, theme === 'dark');
                }

                // Sync toggle UI with current theme
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
                    try {
                        window.dispatchEvent(new CustomEvent('crm-theme-changed', { detail: { theme: next } }));
                    } catch (_) {}

                    // replay burst animation
                    document.querySelectorAll('[data-theme-toggle]').forEach((b) => {
                        b.classList.remove('crm-theme-burst');
                        void b.offsetWidth; // reflow
                        b.classList.add('crm-theme-burst');
                        window.setTimeout(() => b.classList.remove('crm-theme-burst'), 700);
                    });
                });
            })();
        </script>
        
        <!-- Chat Widget - показываем везде кроме админки -->
        @if(!request()->is('admin*'))
            <x-chat-widget />
        @endif
    </body>
</html>
