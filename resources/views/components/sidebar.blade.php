<div id="sidebar" class="pt-17 -translate-x-full fixed inset-y-0 left-0 z-20 w-64 bg-gray-100 text-gray-700 transform md:translate-x-0 transition-transform duration-300 ease-in-out flex flex-col justify-between border-r border-gray-200">
    <div>
        <div class="px-4 pt-6">
            <div class="flex items-center justify-between p-3 bg-[#E0E7FF] rounded-2xl">
                <div class="flex items-center space-x-3 overflow-hidden">
                    <img src="{{ asset('assets/logo.png') }}" class="w-10 h-10 rounded-full object-cover">
                    <div class="truncate">
                        <h2 class="text-sm font-bold text-gray-900 truncate">Nama Perusahaan</h2>
                        <p class="text-xs text-gray-500 capitalize">{{ Auth::user()->nama_role }}</p>
                    </div>
                </div>
            </div>
        </div>

        <nav class="mt-6 px-3 space-y-1">
            @if(Auth::user()->nama_role === 'admin')
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.dashboard') ? 'text-[#3B28CC] font-semibold border-l-4 border-[#3B28CC] bg-transparent rounded-l-none pl-3' : 'text-gray-600 hover:bg-gray-200/50' }}">
                    <i class="fa-solid fa-sliders w-5 text-center mr-3 {{ request()->routeIs('admin.dashboard') ? 'text-[#3B28CC]' : 'text-gray-400 group-hover:text-gray-600' }}"></i>
                    Dashboard
                </a>

                <a href="{{ route('kelola-akun.index') }}"
                   class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('kelola-akun.*') ? 'text-[#3B28CC] font-semibold border-l-4 border-[#3B28CC] bg-transparent rounded-l-none pl-3' : 'text-gray-600 hover:bg-gray-200/50' }}">
                    <i class="fa-solid fa-users w-5 text-center mr-3 {{ request()->routeIs('kelola-akun.*') ? 'text-[#3B28CC]' : 'text-gray-400 group-hover:text-gray-600' }}"></i>
                    Kelola Pengguna
                </a>
            @endif

            @if(Auth::user()->nama_role === 'manager')
                <a href="{{ route('manager.dashboard') }}"
                   class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('manager.dashboard') ? 'text-[#3B28CC] font-semibold border-l-4 border-[#3B28CC] bg-transparent rounded-l-none pl-3' : 'text-gray-600 hover:bg-gray-200/50' }}">
                    <i class="fa-solid fa-sliders w-5 text-center mr-3 {{ request()->routeIs('manager.dashboard') ? 'text-[#3B28CC]' : 'text-gray-400 group-hover:text-gray-600' }}"></i>
                    Dashboard
                </a>

                <a href="{{ route('tugas.index') }}"
                   class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('tugas.*') ? 'text-[#3B28CC] font-semibold border-l-4 border-[#3B28CC] bg-transparent rounded-l-none pl-3' : 'text-gray-600 hover:bg-gray-200/50' }}">
                    <i class="fa-regular fa-square-check w-5 text-center mr-3 {{ request()->routeIs('tugas.*') ? 'text-[#3B28CC]' : 'text-gray-400 group-hover:text-gray-600' }}"></i>
                    Kelola Tugas
                </a>

                <a href="#"
                   class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('jadwal.*') ? 'text-[#3B28CC] font-semibold border-l-4 border-[#3B28CC] bg-transparent rounded-l-none pl-3' : 'text-gray-600 hover:bg-gray-200/50' }}">
                    <i class="fa-regular fa-calendar w-5 text-center mr-3 {{ request()->routeIs('jadwal.*') ? 'text-[#3B28CC]' : 'text-gray-400 group-hover:text-gray-600' }}"></i>
                    Kelola Jadwal
                </a>

                <a href="#"
                   class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('staff-divisi.*') ? 'text-[#3B28CC] font-semibold border-l-4 border-[#3B28CC] bg-transparent rounded-l-none pl-3' : 'text-gray-600 hover:bg-gray-200/50' }}">
                    <i class="fa-solid fa-users-gear w-5 text-center mr-3 {{ request()->routeIs('staff-divisi.*') ? 'text-[#3B28CC]' : 'text-gray-400 group-hover:text-gray-600' }}"></i>
                    Staff Divisi
                </a>
            @endif

            @if(Auth::user()->nama_role === 'staff')
                <a href="{{ route('staff.dashboard') }}"
                   class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('staff.dashboard') ? 'text-[#3B28CC] font-semibold border-l-4 border-[#3B28CC] bg-transparent rounded-l-none pl-3' : 'text-gray-600 hover:bg-gray-200/50' }}">
                    <i class="fa-solid fa-sliders w-5 text-center mr-3 {{ request()->routeIs('staff.dashboard') ? 'text-[#3B28CC]' : 'text-gray-400 group-hover:text-gray-600' }}"></i>
                    Staff Dashboard
                </a>

                <a href="#"
                   class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('tugas-saya.*') ? 'text-[#3B28CC] font-semibold border-l-4 border-[#3B28CC] bg-transparent rounded-l-none pl-3' : 'text-gray-600 hover:bg-gray-200/50' }}">
                    <i class="fa-regular fa-square-check w-5 text-center mr-3 {{ request()->routeIs('tugas-saya.*') ? 'text-[#3B28CC]' : 'text-gray-400 group-hover:text-gray-600' }}"></i>
                    Tugas Saya
                </a>
            @endif
        </nav>
    </div>
</div>

<div id="sidebar-backdrop" class="hidden fixed inset-0 z-10 bg-slate-900/40 backdrop-blur-xs md:hidden transition-opacity duration-300 opacity-0"></div>
