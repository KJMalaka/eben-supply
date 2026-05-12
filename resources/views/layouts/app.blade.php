<!DOCTYPE html>
{{-- PRT362S — Eben Supply | Group KN3 --}}
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Eben Supply') — Woodstock, Cape Town</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-[#333333] min-h-screen flex flex-col antialiased">

    {{-- ── Announcement bar ── --}}
    <div class="bg-[#333333] text-white text-xs text-center py-2 tracking-wide font-heading">
        🚚 Free store pickup in Woodstock &nbsp;·&nbsp; Nationwide delivery R60 &nbsp;·&nbsp;
        <span class="hidden sm:inline">Pay securely via PayFast / Ozow</span>
    </div>

    {{-- ── Navigation ── --}}
    <header class="bg-white border-b border-stone-100 sticky top-0 z-50 shadow-soft">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 gap-4">

                {{-- Logo --}}
                <a href="{{ route('home') }}" class="flex items-center gap-3 flex-shrink-0">
                    <div class="w-9 h-9 rounded-full overflow-hidden ring-2 ring-[#D4C7B0]">
                        <img src="{{ asset('images/products/logo.jpg') }}" alt="Eben Supply" class="w-full h-full object-cover">
                    </div>
                    <div class="flex flex-col leading-none">
                        <span class="font-black text-base tracking-widest text-[#333333] font-heading uppercase">Eben Supply</span>
                        <span class="text-[10px] text-[#A3A380] tracking-widest uppercase font-heading hidden sm:block">Woodstock, Cape Town</span>
                    </div>
                </a>

                {{-- Desktop centre nav --}}
                <nav class="hidden lg:flex items-center gap-7 flex-1 justify-center">
                    <a href="{{ route('home') }}" class="nav-link pb-1 {{ request()->routeIs('home') ? 'nav-link-active' : '' }}">Home</a>
                    <a href="{{ route('products.index') }}" class="nav-link pb-1 {{ request()->routeIs('products.*') ? 'nav-link-active' : '' }}">Shop</a>
                    <a href="{{ route('products.index', ['category' => 'tshirt']) }}" class="nav-link pb-1 {{ request()->get('category') === 'tshirt' ? 'nav-link-active' : '' }}">T-Shirts</a>
                    <a href="{{ route('products.index', ['category' => 'cap']) }}" class="nav-link pb-1 {{ request()->get('category') === 'cap' ? 'nav-link-active' : '' }}">Caps</a>
                    <a href="{{ route('products.index', ['category' => 'tote_bag']) }}" class="nav-link pb-1 {{ request()->get('category') === 'tote_bag' ? 'nav-link-active' : '' }}">Tote Bags</a>
                </nav>

                {{-- Right icons --}}
                <div class="flex items-center gap-3 flex-shrink-0">

                    {{-- Search toggle --}}
                    <button id="search-btn" aria-label="Search" class="text-stone-400 hover:text-[#333333] transition-colors p-1">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                            <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                        </svg>
                    </button>

                    {{-- Cart --}}
                    <a href="{{ route('cart.index') }}" class="relative text-stone-400 hover:text-[#333333] transition-colors p-1" aria-label="Cart">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4zM3 6h18M16 10a4 4 0 0 1-8 0"/>
                        </svg>
                        @php $cartCount = \App\Http\Controllers\CartController::getCount(); @endphp
                        @if($cartCount > 0)
                            <span id="cart-badge" class="absolute -top-0.5 -right-0.5 bg-[#D4C7B0] text-[#333333] text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center leading-none font-heading">{{ $cartCount }}</span>
                        @endif
                    </a>

                    {{-- Auth --}}
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="hidden md:flex text-[10px] font-bold text-[#A3A380] border border-[#D4C7B0] px-3 py-1.5 rounded-full hover:bg-[#F5F5F5] transition-colors font-heading uppercase tracking-widest">
                                Admin
                            </a>
                        @endif

                        {{-- User dropdown — click-based --}}
                        <div class="relative" id="user-menu-wrapper">
                            <button id="user-menu-btn" aria-expanded="false" aria-haspopup="true"
                                    class="flex items-center gap-2 text-stone-500 hover:text-[#333333] transition-colors text-sm p-1">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                                </svg>
                                <span class="hidden md:inline font-medium text-sm">{{ Str::before(auth()->user()->name, ' ') }}</span>
                                <svg class="w-3 h-3 hidden md:block transition-transform duration-200" id="user-chevron" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            {{-- Dropdown --}}
                            <div id="user-dropdown"
                                 class="hidden absolute right-0 top-full mt-2 w-52 bg-white border border-stone-100 rounded-xl shadow-card overflow-hidden z-50">
                                <div class="px-4 py-3 border-b border-stone-50">
                                    <p class="text-xs font-semibold text-[#333333] truncate">{{ auth()->user()->name }}</p>
                                    <p class="text-[11px] text-stone-400 truncate">{{ auth()->user()->email }}</p>
                                </div>
                                <a href="{{ route('orders.index') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-stone-600 hover:bg-[#F5F5F5] hover:text-[#333333] transition-colors">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2z"/></svg>
                                    My Orders
                                </a>
                                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-stone-600 hover:bg-[#F5F5F5] hover:text-[#333333] transition-colors">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0zM12 14a7 7 0 0 0-7 7h14a7 7 0 0 0-7-7z"/></svg>
                                    Profile
                                </a>
                                <div class="border-t border-stone-100 mt-1"></div>
                                <form method="POST" action="{{ route('logout') }}" class="px-2 py-1.5">
                                    @csrf
                                    <button type="submit" class="flex items-center gap-2.5 w-full px-3 py-2 text-sm text-stone-400 hover:bg-red-50 hover:text-red-500 transition-colors rounded-lg font-medium">
                                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3V7a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1"/></svg>
                                        Sign Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="hidden sm:block text-sm text-stone-500 hover:text-[#333333] font-medium transition-colors">Sign In</a>
                        <a href="{{ route('register') }}" class="hidden sm:inline-flex btn-accent text-xs px-4 py-2">Register</a>
                    @endauth

                    {{-- Mobile hamburger --}}
                    <button id="mobile-btn" aria-label="Menu" class="lg:hidden text-stone-400 hover:text-[#333333] p-1">
                        <svg id="menu-icon-open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                        <svg id="menu-icon-close" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Search bar (slide down) --}}
            <div id="search-bar" class="hidden pb-4 pt-2">
                <form action="{{ route('products.index') }}" method="GET">
                    <div class="relative">
                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                        <input id="search-input" type="text" name="search" value="{{ request('search') }}"
                               placeholder="Search for t-shirts, caps, tote bags…"
                               autocomplete="off"
                               class="w-full pl-11 pr-4 py-3 bg-[#F5F5F5] border border-stone-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#D4C7B0] focus:border-transparent transition-all">
                        <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 bg-[#333333] text-white text-xs font-heading font-bold px-4 py-1.5 rounded-lg hover:bg-[#444] transition-colors">
                            Search
                        </button>
                    </div>
                </form>
            </div>

            {{-- Mobile menu --}}
            <div id="mobile-menu" class="lg:hidden hidden pb-5 pt-2 border-t border-stone-100">
                <nav class="space-y-0.5 mb-4">
                    @foreach([
                        ['Home', route('home')],
                        ['Shop All', route('products.index')],
                        ['T-Shirts', route('products.index', ['category'=>'tshirt'])],
                        ['Caps', route('products.index', ['category'=>'cap'])],
                        ['Tote Bags', route('products.index', ['category'=>'tote_bag'])],
                    ] as [$label, $href])
                        <a href="{{ $href }}" class="flex items-center px-3 py-2.5 text-sm text-stone-600 hover:text-[#333333] hover:bg-[#F5F5F5] rounded-lg transition-colors font-medium">{{ $label }}</a>
                    @endforeach
                </nav>

                {{-- Mobile search --}}
                <form action="{{ route('products.index') }}" method="GET" class="px-1 mb-4">
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                        <input type="text" name="search" placeholder="Search products…"
                               class="w-full pl-10 pr-3 py-2.5 bg-[#F5F5F5] border border-stone-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#D4C7B0]">
                    </div>
                </form>

                {{-- Mobile auth section --}}
                <div class="border-t border-stone-100 pt-3 space-y-0.5">
                    @auth
                        <div class="px-3 py-2 mb-1">
                            <p class="text-xs font-semibold text-[#333333]">{{ auth()->user()->name }}</p>
                            <p class="text-[11px] text-stone-400">{{ auth()->user()->email }}</p>
                        </div>
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 px-3 py-2.5 text-sm text-[#A3A380] hover:bg-[#F5F5F5] hover:text-[#333333] rounded-lg transition-colors font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                                Admin Panel
                            </a>
                        @endif
                        <a href="{{ route('orders.index') }}" class="flex items-center gap-2.5 px-3 py-2.5 text-sm text-stone-600 hover:bg-[#F5F5F5] hover:text-[#333333] rounded-lg transition-colors font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2z"/></svg>
                            My Orders
                        </a>
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 px-3 py-2.5 text-sm text-stone-600 hover:bg-[#F5F5F5] hover:text-[#333333] rounded-lg transition-colors font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0zM12 14a7 7 0 0 0-7 7h14a7 7 0 0 0-7-7z"/></svg>
                            Profile
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="px-1">
                            @csrf
                            <button type="submit" class="flex items-center gap-2.5 w-full px-3 py-2.5 text-sm text-red-400 hover:bg-red-50 hover:text-red-600 rounded-lg transition-colors font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3V7a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1"/></svg>
                                Sign Out
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="flex items-center gap-2.5 px-3 py-2.5 text-sm text-stone-600 hover:bg-[#F5F5F5] rounded-lg transition-colors font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3V7a3 3 0 0 1 3-3h7a3 3 0 0 1 3 3v1"/></svg>
                            Sign In
                        </a>
                        <a href="{{ route('register') }}" class="flex items-center gap-2.5 px-3 py-2.5 text-sm text-[#333333] font-semibold hover:bg-[#F5F5F5] rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 1 1-8 0 4 4 0 0 1 8 0zM3 20a6 6 0 0 1 12 0v1H3v-1z"/></svg>
                            Create Account
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    {{-- ── Toast flash messages (auto-dismiss) ── --}}
    @if(session('success'))
        <div id="toast-success" class="fixed top-20 right-4 z-[100] flex items-center gap-3 bg-white border border-emerald-200 text-emerald-700 px-5 py-3.5 rounded-xl shadow-card text-sm font-medium max-w-sm animate-slide-in">
            <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{{ session('success') }}</span>
            <button onclick="this.closest('[id^=toast]').remove()" class="ml-2 text-stone-300 hover:text-stone-500">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    @endif
    @if(session('error'))
        <div id="toast-error" class="fixed top-20 right-4 z-[100] flex items-center gap-3 bg-white border border-red-200 text-red-600 px-5 py-3.5 rounded-xl shadow-card text-sm font-medium max-w-sm animate-slide-in">
            <svg class="w-5 h-5 text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><circle cx="12" cy="16" r="0.5" fill="currentColor"/></svg>
            <span>{{ session('error') }}</span>
            <button onclick="this.closest('[id^=toast]').remove()" class="ml-2 text-stone-300 hover:text-stone-500">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    @endif

    {{-- Main content --}}
    <main class="flex-1">
        @yield('content')
    </main>

    {{-- ── Footer ── --}}
    <footer class="bg-[#333333] text-white mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-10">
                {{-- Brand --}}
                <div class="sm:col-span-2">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-full overflow-hidden ring-2 ring-[#D4C7B0]">
                            <img src="{{ asset('images/products/logo.jpg') }}" alt="" class="w-full h-full object-cover">
                        </div>
                        <span class="font-black text-lg tracking-widest font-heading uppercase text-white">Eben Supply</span>
                    </div>
                    <p class="text-stone-400 text-sm leading-relaxed max-w-xs font-accent italic">
                        "Premium branded merchandise crafted for the streets of Cape Town."
                    </p>
                    <div class="flex items-center gap-2 mt-4 text-stone-400 text-sm">
                        <svg class="w-4 h-4 text-[#D4C7B0] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M12 22s-8-4.5-8-11.8A8 8 0 0 1 12 2a8 8 0 0 1 8 8.2c0 7.3-8 11.8-8 11.8z"/><circle cx="12" cy="10" r="3"/></svg>
                        Woodstock, Cape Town, 7925
                    </div>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-stone-400 uppercase tracking-widest mb-4 font-heading">Shop</h4>
                    <ul class="space-y-2 text-sm text-stone-300">
                        <li><a href="{{ route('products.index', ['category' => 'tshirt']) }}" class="hover:text-[#D4C7B0] transition-colors">T-Shirts</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'cap']) }}" class="hover:text-[#D4C7B0] transition-colors">Caps</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'tote_bag']) }}" class="hover:text-[#D4C7B0] transition-colors">Tote Bags</a></li>
                        <li><a href="{{ route('products.index') }}" class="hover:text-[#D4C7B0] transition-colors">All Products</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-stone-400 uppercase tracking-widest mb-4 font-heading">Account</h4>
                    <ul class="space-y-2 text-sm text-stone-300">
                        @auth
                            <li><a href="{{ route('orders.index') }}" class="hover:text-[#D4C7B0] transition-colors">My Orders</a></li>
                            <li><a href="{{ route('profile.edit') }}" class="hover:text-[#D4C7B0] transition-colors">Profile</a></li>
                        @else
                            <li><a href="{{ route('login') }}" class="hover:text-[#D4C7B0] transition-colors">Sign In</a></li>
                            <li><a href="{{ route('register') }}" class="hover:text-[#D4C7B0] transition-colors">Register</a></li>
                        @endauth
                        <li><a href="{{ route('cart.index') }}" class="hover:text-[#D4C7B0] transition-colors">Cart</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-stone-700 mt-10 pt-8 flex flex-col md:flex-row items-center justify-between gap-3 text-xs text-stone-500">
                <span>© {{ date('Y') }} Eben Supply. All rights reserved.</span>
                <span>CPUT PRT362S — Group KN3</span>
            </div>
        </div>
    </footer>

    {{-- ── Back to top ── --}}
    <button id="back-to-top" aria-label="Back to top"
            class="hidden fixed bottom-6 right-6 z-50 w-11 h-11 bg-[#333333] text-white rounded-full shadow-card hover:bg-[#444] transition-all duration-200 flex items-center justify-center">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/></svg>
    </button>

    @stack('scripts')
</body>
</html>