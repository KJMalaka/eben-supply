<!DOCTYPE html>
{{-- PRT362S — Eben Supply | Group KN3 --}}
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $title ?? 'Admin') — Eben Supply</title>

    @include('partials.fonts')
    @fluxAppearance
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Phone drawer styles (kept here so they work without rebuilding Tailwind) --}}
    <style>
        #admin-overlay { display: none; position: fixed; inset: 0; background: rgba(0, 0, 0, 0.4); z-index: 30; }
        .admin-menu-btn { background: none; border: 0; padding: 8px; cursor: pointer; color: rgba(255, 255, 255, 0.7); line-height: 0; }
        .admin-menu-btn:hover { color: #fff; }
        @media (max-width: 1023px) {
            #admin-sidebar.is-open { display: flex; flex-direction: column; position: fixed; top: 0; bottom: 0; left: 0; width: 16rem; z-index: 40; }
            #admin-overlay.is-open { display: block; }
        }
    </style>
</head>
<body class="bg-stone-50 text-[#333333] min-h-screen antialiased">

    {{-- Mobile: dark overlay behind the drawer --}}
    <div id="admin-overlay"></div>

    <div class="flex min-h-screen">

        {{-- ── Sidebar (hidden on phones until the menu button opens it) ── --}}
        <aside id="admin-sidebar" class="hidden lg:flex lg:flex-col lg:w-64 lg:flex-shrink-0 bg-[#333333] text-white">
            <div class="h-16 flex items-center justify-between gap-3 px-6 border-b border-white/10">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full overflow-hidden ring-2 ring-[#D4C7B0] flex-shrink-0">
                        <img src="{{ asset('images/products/logo.jpg') }}" alt="Eben Supply" class="w-full h-full object-cover">
                    </div>
                    <span class="font-black text-sm tracking-widest font-heading uppercase">Eben Supply</span>
                </div>
                <button type="button" id="admin-menu-close" aria-label="Close admin menu" class="admin-menu-btn lg:hidden">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <nav class="flex-1 px-3 py-6 space-y-1">
                @foreach([
                    ['Dashboard', route('admin.dashboard'), 'admin.dashboard', 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                    ['Products', route('admin.products.index'), 'admin.products.*', 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10'],
                    ['Orders', route('admin.orders.index'), 'admin.orders.*', 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                    ['Inventory', route('admin.inventory'), 'admin.inventory', 'M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0H4'],
                ] as [$label, $href, $pattern, $iconPath])
                    <a href="{{ $href }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                              {{ request()->routeIs($pattern) ? 'bg-white/10 text-white' : 'text-white/60 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $iconPath }}"/>
                        </svg>
                        {{ $label }}
                    </a>
                @endforeach
            </nav>

            <div class="px-3 py-4 border-t border-white/10 space-y-1">
                <a href="{{ route('home') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-white/60 hover:bg-white/5 hover:text-white transition-colors">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to store
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-white/60 hover:bg-white/5 hover:text-white transition-colors">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Sign out
                    </button>
                </form>
            </div>
        </aside>

        {{-- ── Main column ── --}}
        <div class="flex-1 flex flex-col min-w-0">

            {{-- Mobile top bar: the menu button opens the sidebar drawer --}}
            <header class="lg:hidden bg-[#333333] text-white h-14 flex items-center justify-between px-4">
                <div class="flex items-center gap-2">
                    <button type="button" id="admin-menu-open" aria-label="Open admin menu" class="admin-menu-btn">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    <span class="font-black text-sm tracking-widest font-heading uppercase">Eben Supply Admin</span>
                </div>
                <a href="{{ route('home') }}" class="inline-block py-2 text-xs text-white/60 hover:text-white">Store →</a>
            </header>

            {{-- Page header --}}
            <div class="bg-white border-b border-stone-100 px-4 sm:px-8 py-5">
                <h1 class="font-heading font-black text-xl text-[#333333]">@yield('title', $title ?? 'Admin')</h1>
            </div>

            {{-- Flash messages --}}
            <x-flash-toast />

            <main class="flex-1 px-4 sm:px-8 py-8">
                @yield('content', $slot ?? '')
            </main>
        </div>
    </div>

    {{-- Drawer open/close (plain JS, no extra library needed) --}}
    <script>
        (function () {
            var sidebar = document.getElementById('admin-sidebar');
            var overlay = document.getElementById('admin-overlay');
            var openBtn = document.getElementById('admin-menu-open');
            var closeBtn = document.getElementById('admin-menu-close');

            function openMenu()  { sidebar.classList.add('is-open'); overlay.classList.add('is-open'); }
            function closeMenu() { sidebar.classList.remove('is-open'); overlay.classList.remove('is-open'); }

            openBtn.addEventListener('click', openMenu);
            closeBtn.addEventListener('click', closeMenu);
            overlay.addEventListener('click', closeMenu);
            document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeMenu(); });
        })();
    </script>

    @fluxScripts
    @stack('scripts')
</body>
</html>