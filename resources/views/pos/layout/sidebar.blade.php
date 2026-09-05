<!-- SIDEBAR -->

<!-- DESKTOP SIDEBAR -->
<aside
    class="pos-sidebar w-[72px] md:w-[260px] h-full flex-shrink-0 bg-white/80 glass
           border-r border-white/40 shadow-sm hidden md:flex flex-col
           py-6 px-3 overflow-y-auto">
    <!-- Logo -->
    <div class="flex items-center gap-2 px-3 mb-8">
        <span class="text-xl font-bold text-[#0F172A] tracking-tight">
            The Heaven <span class="text-[#25D366]">Cafe</span>
        </span>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 space-y-1.5">
        @if (Auth::guard('pos')->user()->role == 3)
            <a href="{{ route('pos.index') }}"
                class="sidebar-link {{ request()->routeIs('pos.index') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-600">
                <i class="fas fa-th-large w-5 text-center"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('pos.kitchen.orders') }}"
                class="sidebar-link {{ request()->routeIs('pos.kitchen.*') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-600">
                <i class="fas fa-utensils w-5 text-center"></i>
                <span>Kitchen Orders</span>
            </a>
        @else

        <a href="{{ route('pos.index') }}"
            class="sidebar-link {{ request()->routeIs('pos.index') ? 'active' : '' }}
                  flex items-center gap-3 px-3 py-2.5 rounded-xl
                  text-sm font-medium text-slate-600">

            <i class="fas fa-th-large w-5 text-center"></i>

            <span>Dashboard</span>
        </a>

        <a href="{{ route('pos.order') }}"
            class="sidebar-link {{ request()->routeIs('pos.order*') ? 'active' : '' }}
                  flex items-center gap-3 px-3 py-2.5 rounded-xl
                  text-sm font-medium text-slate-600">

            <i class="fas fa-table w-5 text-center"></i>

            <span>Create Order</span>
        </a>

        @if (Auth::guard('pos')->user()->role == 1)
            <a href="{{ route('pos.staff') }}"
                class="sidebar-link {{ request()->routeIs('pos.staff*') ? 'active' : '' }}
              flex items-center gap-3 px-4 py-3 rounded-xl
              text-sm font-medium text-slate-600">

                <i class="fas fa-users w-5 text-center"></i>

                <span>Staffs</span>

            </a>
        @endif

            <a href="{{ route('leave.index') }}"
                class="sidebar-link {{ request()->routeIs('leave.leave*') ? 'active' : '' }}
              flex items-center gap-3 px-4 py-3 rounded-xl
              text-sm font-medium text-slate-600">

                <i class="fas fa-calendar-days w-5 text-center"></i>

                <span>Leaves</span>

            </a>

        <a href="{{ route('pos.bills') }}"
            class="sidebar-link {{ request()->routeIs('pos.bills*') ? 'active' : '' }}
                  flex items-center gap-3 px-3 py-2.5 rounded-xl
                  text-sm font-medium text-slate-600">

            <i class="fas fa-table w-5 text-center"></i>

            <span>Bills</span>
        </a>

        @if (Auth::guard('pos')->user()->role == 1)
        <a href="{{ route('pos_product.index') }}"
            class="sidebar-link {{ request()->routeIs('pos_product.index*') ? 'active' : '' }}
                  flex items-center gap-3 px-3 py-2.5 rounded-xl
                  text-sm font-medium text-slate-600">

            <i class="fas fa-table w-5 text-center"></i>

            <span>Products</span>
        </a>

        <a href="{{ route('pos_product.orders') }}"
            class="sidebar-link {{ request()->routeIs('pos_product.orders*') ? 'active' : '' }}
                  flex items-center gap-3 px-3 py-2.5 rounded-xl
                  text-sm font-medium text-slate-600">

            <i class="fas fa-truck w-5 text-center"></i>

            <span>Store Orders</span>
        </a>
        @endif

        <a href="{{ route('pos.policy') }}"
            class="sidebar-link {{ request()->routeIs('pos.policy*') ? 'active' : '' }}
            flex items-center gap-3 px-4 py-3 rounded-xl
            text-sm font-medium text-slate-600">

          <i class="fas fa-calendar-days w-5 text-center"></i>

            <span>Company Policy</span>

        </a>

        <a href="#"
            class="sidebar-link flex items-center gap-3 px-3 py-2.5
                  rounded-xl text-sm font-medium text-slate-600">

            <i class="fas fa-cog w-5 text-center"></i>

            <span>Settings</span>
        </a>
        @endif

    </nav>

    <!-- Logout -->
    <div class="mt-auto pt-4 border-t border-slate-200/60">

        <a href="{{ route('pos.logout') }}"
            class="flex items-center gap-3 px-3 py-2.5 rounded-xl
                  text-sm font-medium text-slate-500 hover:text-rose-600">

            <i class="fas fa-sign-out-alt w-5 text-center"></i>

            <span>Logout</span>

        </a>

    </div>
</aside>


<!-- MOBILE SIDEBAR OVERLAY -->
<div id="mobileMenuOverlay" class="fixed inset-0 bg-black/40 z-[9998] hidden md:hidden"></div>


<!-- MOBILE SIDEBAR -->
<aside id="mobileSidebar"
    class="pos-mobile-sidebar fixed top-0 left-0 bottom-0 z-[9999]
           w-[270px] bg-white shadow-2xl
           transform -translate-x-full
           transition-transform duration-300 ease-in-out
           md:hidden flex flex-col">

    <!-- Mobile Header -->
    <div class="h-[72px] flex items-center justify-between
                px-5 border-b border-slate-200">

        <span class="text-xl font-bold text-[#0F172A]">
            The Heaven <span class="text-[#25D366]">Cafe</span>
        </span>

        <button type="button" id="closeMobileMenu"
            class="w-9 h-9 rounded-lg bg-slate-100
                   text-slate-500 flex items-center justify-center
                   hover:bg-slate-200">
            <i class="fas fa-times"></i>
        </button>

    </div>


    <!-- Mobile Navigation -->
    <nav class="flex-1 p-4 space-y-2">
        @if (Auth::guard('pos')->user()->role == 3)
            <a href="{{ route('pos.index') }}"
                class="sidebar-link {{ request()->routeIs('pos.index') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-slate-600">
                <i class="fas fa-th-large w-5 text-center"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('pos.kitchen.orders') }}"
                class="sidebar-link {{ request()->routeIs('pos.kitchen.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-slate-600">
                <i class="fas fa-utensils w-5 text-center"></i>
                <span>Kitchen Orders</span>
            </a>
        @else

        <a href="{{ route('pos.index') }}"
            class="sidebar-link {{ request()->routeIs('pos.index') ? 'active' : '' }}
                   flex items-center gap-3 px-4 py-3 rounded-xl
                   text-sm font-medium text-slate-600">
            <i class="fas fa-th-large w-5 text-center"></i>
            <span>Dashboard</span>
        </a>


        <a href="{{ route('pos.order') }}"
            class="sidebar-link {{ request()->routeIs('pos.order*') ? 'active' : '' }}
                   flex items-center gap-3 px-4 py-3 rounded-xl
                   text-sm font-medium text-slate-600">
            <i class="fas fa-shopping-cart w-5 text-center"></i>
            <span>Create Order</span>
        </a>

        @if (Auth::guard('pos')->user()->role == 1)
            <a href="{{ route('pos.staff') }}"
                class="sidebar-link {{ request()->routeIs('pos.staff*') ? 'active' : '' }}
              flex items-center gap-3 px-4 py-3 rounded-xl
              text-sm font-medium text-slate-600">

                <i class="fas fa-users w-5 text-center"></i>

                <span>Staffs</span>

            </a>
        @endif

        <a href="{{ route('leave.index') }}"
            class="sidebar-link {{ request()->routeIs('leave.leave*') ? 'active' : '' }}
            flex items-center gap-3 px-4 py-3 rounded-xl
            text-sm font-medium text-slate-600">

          <i class="fas fa-calendar-days w-5 text-center"></i>

            <span>Leaves</span>

        </a>

        <a href="{{ route('pos.bills') }}"
            class="sidebar-link {{ request()->routeIs('pos.bills*') ? 'active' : '' }}
                   flex items-center gap-3 px-4 py-3 rounded-xl
                   text-sm font-medium text-slate-600">
            <i class="fas fa-shopping-cart w-5 text-center"></i>
            <span>Bills</span>
        </a>

        <a href="{{ route('pos_product.orders') }}"
            class="sidebar-link {{ request()->routeIs('pos_product.orders*') ? 'active' : '' }}
                   flex items-center gap-3 px-4 py-3 rounded-xl
                   text-sm font-medium text-slate-600">
            <i class="fas fa-truck w-5 text-center"></i>
            <span>Store Orders</span>
        </a>

        <a href="{{ route('pos.policy') }}"
            class="sidebar-link {{ request()->routeIs('pos.policy*') ? 'active' : '' }}
            flex items-center gap-3 px-4 py-3 rounded-xl
            text-sm font-medium text-slate-600">

          <i class="fas fa-calendar-days w-5 text-center"></i>

            <span>Company Policy</span>

        </a>


        <a href="#"
            class="sidebar-link flex items-center gap-3 px-4 py-3
                   rounded-xl text-sm font-medium text-slate-600">
            <i class="fas fa-cog w-5 text-center"></i>
            <span>Settings</span>
        </a>
        @endif

    </nav>


    <!-- Mobile Logout -->
    <div class="p-4 border-t border-slate-200">

        <a href="{{ route('pos.logout') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl
                   text-sm font-medium text-slate-500
                   hover:text-rose-600">
            <i class="fas fa-sign-out-alt w-5 text-center"></i>
            <span>Logout</span>
        </a>

    </div>

</aside>



<!-- MAIN -->
<main class="flex-1 flex flex-col min-w-0 h-full overflow-hidden">

    <!-- TOP NAV -->
    <header
        class="pos-topbar bg-white/70 glass border-b border-white/40
           px-4 md:px-6 py-3 flex items-center
           justify-between flex-shrink-0">

        <div class="flex items-center gap-3 w-full md:w-auto">

            <!-- MOBILE MENU -->
            <button type="button" id="openMobileMenu"
                class="w-10 h-10 rounded-xl
                   flex items-center justify-center
                   text-slate-500 hover:bg-slate-100
                   md:hidden">
                <i class="fas fa-bars text-xl"></i>
            </button>


            <!-- Search -->
            <div class="relative flex-1 md:flex-none">

                <i
                    class="fas fa-search absolute left-3 top-1/2
                       -translate-y-1/2 text-slate-400 text-sm"></i>

                <input type="text" placeholder="Search..."
                    class="w-full md:w-64 pl-9 pr-4 py-2
                       bg-slate-100/70 rounded-xl text-sm
                       border border-transparent
                       focus:border-[#25D366]
                       focus:bg-white
                       focus:outline-none
                       transition
                       placeholder:text-slate-400">

            </div>

        </div>


        <div class="flex items-center gap-3 md:gap-4">

            <i class="fas fa-bell text-slate-500 text-xl
                   hover:text-[#128C7E] transition"></i>

            <div class="flex items-center gap-2
                   border-l border-slate-200 pl-3 md:pl-4">

                <span class="text-sm font-semibold text-slate-700
                       hidden sm:block">
                    {{ Auth::guard('pos')->user()->name }}
                </span>

            </div>

        </div>

    </header>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const openButton = document.getElementById('openMobileMenu');

            const closeButton = document.getElementById('closeMobileMenu');

            const mobileSidebar = document.getElementById('mobileSidebar');

            const overlay = document.getElementById('mobileMenuOverlay');


            function openMobileMenu() {

                mobileSidebar.classList.remove('-translate-x-full');

                mobileSidebar.classList.add('translate-x-0');

                overlay.classList.remove('hidden');

                document.body.classList.add('overflow-hidden');
            }


            function closeMobileMenu() {

                mobileSidebar.classList.remove('translate-x-0');

                mobileSidebar.classList.add('-translate-x-full');

                overlay.classList.add('hidden');

                document.body.classList.remove('overflow-hidden');
            }


            // Open
            if (openButton) {

                openButton.addEventListener('click', function() {

                    openMobileMenu();

                });

            }


            // Close button
            if (closeButton) {

                closeButton.addEventListener('click', function() {

                    closeMobileMenu();

                });

            }


            // Overlay click
            if (overlay) {

                overlay.addEventListener('click', function() {

                    closeMobileMenu();

                });

            }


            // Menu link click ke baad close
            document
                .querySelectorAll('#mobileSidebar a')
                .forEach(function(link) {

                    link.addEventListener('click', function() {

                        closeMobileMenu();

                    });

                });


            // ESC se close
            document.addEventListener('keydown', function(event) {

                if (event.key === 'Escape') {

                    closeMobileMenu();

                }

            });

        });
    </script>
