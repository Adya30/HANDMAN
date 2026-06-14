@extends('layouts.app')

@section('title', 'Staff & Grup Kerja')

@section('content')
<div class="space-y-6 pb-12">

    
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 shrink-0">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Staff & Grup Kerja</h1>
            <p class="text-sm text-gray-500 mt-0.5">Daftar seluruh staff dan kelola grup kerja di departemen Anda.</p>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" onclick="openGrupModal()" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-[#3B28CC] text-white hover:bg-opacity-95 font-bold text-sm rounded-xl shadow-sm transition-colors cursor-pointer">
                <i class="fa-solid fa-plus text-xs"></i> Buat Grup Kerja
            </button>
        </div>
    </div>

    
    @if(session('success'))
        <div class="p-4 text-sm text-green-800 bg-green-50 border border-green-100 rounded-xl flex items-center gap-3">
            <i class="fa-solid fa-circle-check text-green-600 text-base shrink-0"></i>
            {{ session('success') }}
        </div>
    @endif

    
    <div class="border-b border-gray-200">
        <nav class="flex space-x-8" aria-label="Tabs">
            <button onclick="switchTab('staff-list')" id="tab-btn-staff-list" class="border-[#3B28CC] text-[#3B28CC] whitespace-nowrap py-4 px-1 border-b-2 font-bold text-sm flex items-center gap-2 cursor-pointer transition-all">
                <i class="fa-solid fa-users text-sm"></i>
                Daftar Staff
            </button>
            <button onclick="switchTab('grup-kerja')" id="tab-btn-grup-kerja" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-semibold text-sm flex items-center gap-2 cursor-pointer transition-all">
                <i class="fa-solid fa-people-group text-sm"></i>
                Grup Kerja
                <span class="bg-gray-100 text-gray-600 ml-1 py-0.5 px-2.5 rounded-full text-xs font-bold" id="badge-grup-count">
                    {{ $grups->count() }}
                </span>
            </button>
        </nav>
    </div>

    
    <div id="tab-content-staff-list" class="space-y-6">

        
        <div class="grid grid-cols-3 gap-4 shrink-0">
            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
                <div class="space-y-1">
                    <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">Total Staff</span>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $totalStaff }}</h3>
                </div>
                <div class="w-11 h-11 bg-indigo-50 rounded-xl flex items-center justify-center text-[#3B28CC]">
                    <i class="fa-solid fa-users text-lg"></i>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
                <div class="space-y-1">
                    <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">Aktif</span>
                    <h3 class="text-2xl font-bold text-green-600">{{ $staffAktif }}</h3>
                </div>
                <div class="w-11 h-11 bg-green-50 rounded-xl flex items-center justify-center text-green-600">
                    <i class="fa-solid fa-user-check text-lg"></i>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
                <div class="space-y-1">
                    <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">Non-Aktif</span>
                    <h3 class="text-2xl font-bold text-rose-600">{{ $staffNonAktif }}</h3>
                </div>
                <div class="w-11 h-11 bg-rose-50 rounded-xl flex items-center justify-center text-rose-600">
                    <i class="fa-solid fa-user-slash text-lg"></i>
                </div>
            </div>
        </div>

        
        <form method="GET" action="{{ route('staff-divisi.index') }}" id="filter-form">
            <input type="hidden" name="tab" value="staff-list">
            <div class="bg-white border border-gray-100 rounded-2xl p-4 shadow-sm">
                <div class="flex flex-wrap gap-3 items-end">

                    
                    <div class="flex-1 min-w-[180px]">
                        <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">Cari Staff</label>
                        <div class="relative">
                            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                            <input type="text" name="search" id="filter-search" value="{{ request('search') }}"
                                   placeholder="Nama atau email..."
                                   class="w-full pl-8 pr-3 py-2 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#3B28CC]/20 focus:border-[#3B28CC] transition-all">
                        </div>
                    </div>

                    
                    <div class="min-w-[160px]">
                        <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">Status Pegawai</label>
                        <select name="status" id="filter-status" class="w-full py-2 px-3 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#3B28CC]/20 focus:border-[#3B28CC] transition-all appearance-none cursor-pointer">
                            <option value="">Semua Status</option>
                            <option value="Tetap"    {{ request('status') === 'Tetap'    ? 'selected' : '' }}>Tetap</option>
                            <option value="Kontrak"  {{ request('status') === 'Kontrak'  ? 'selected' : '' }}>Kontrak</option>
                            <option value="Magang"   {{ request('status') === 'Magang'   ? 'selected' : '' }}>Magang</option>
                            <option value="Skorsing" {{ request('status') === 'Skorsing' ? 'selected' : '' }}>Skorsing</option>
                        </select>
                    </div>

                    
                    <div class="flex items-end gap-2">
                        <button type="submit" class="px-4 py-2 bg-[#3B28CC] text-white text-sm font-semibold rounded-xl hover:bg-[#2c1fa3] transition-colors flex items-center gap-2 cursor-pointer">
                            <i class="fa-solid fa-filter text-xs"></i> Filter
                        </button>
                        @if(request()->hasAny(['search', 'status']))
                            <a href="{{ route('staff-divisi.index') }}" class="px-4 py-2 border border-gray-200 text-gray-600 text-sm font-semibold rounded-xl hover:bg-gray-50 transition-colors flex items-center gap-2">
                                <i class="fa-solid fa-xmark text-xs"></i> Reset
                            </a>
                        @endif
                    </div>
                </div>

                
                @php
                    $activeFilters = array_filter([
                        'search' => request('search') ? '"'.request('search').'"' : null,
                        'status' => request('status'),
                    ]);
                @endphp
                @if(count($activeFilters) > 0)
                    <div class="flex flex-wrap gap-2 mt-3 pt-3 border-t border-gray-100">
                        <span class="text-xs text-gray-400 font-medium self-center">Filter aktif:</span>
                        @foreach($activeFilters as $label)
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-indigo-50 text-[#3B28CC] text-xs font-semibold rounded-lg border border-indigo-100">
                                <i class="fa-solid fa-tag text-[9px]"></i> {{ $label }}
                            </span>
                        @endforeach
                    </div>
                @endif
            </div>
        </form>

        
        @if($staffs->isEmpty())
            <div class="bg-white border border-gray-100 rounded-2xl p-12 text-center shadow-sm">
                <div class="flex flex-col items-center gap-3 text-gray-400">
                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center">
                        <i class="fa-solid fa-users-slash text-2xl text-gray-300"></i>
                    </div>
                    <p class="font-semibold text-gray-500">Tidak ada staff ditemukan</p>
                    <p class="text-xs text-gray-400">Coba ubah atau reset filter yang aktif.</p>
                </div>
            </div>
        @else
            <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100 text-xs font-bold text-gray-500 uppercase tracking-wider">
                                <th class="p-4">Nama Staff</th>
                                <th class="p-4">Status Pegawai</th>
                                <th class="p-4">Status Keaktifan</th>
                                <th class="p-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                            @foreach($staffs as $staff)
                            <tr class="hover:bg-gray-50/40 transition-colors staff-row cursor-pointer" data-id="{{ $staff->id }}">
                                <td class="p-4">
                                    <div class="flex items-center gap-3">
                                        @if($staff->foto_profil)
                                            <img src="{{ asset('storage/' . $staff->foto_profil) }}" alt="{{ $staff->nama_lengkap }}"
                                                 class="w-10 h-10 rounded-full object-cover border border-indigo-50 shrink-0">
                                        @else
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($staff->nama_lengkap) }}&background=3B28CC&color=fff&size=64"
                                                 alt="{{ $staff->nama_lengkap }}"
                                                 class="w-10 h-10 rounded-full object-cover border border-indigo-50 shrink-0">
                                        @endif
                                        <div class="min-w-0">
                                            <p class="text-sm font-bold text-gray-900 truncate">{{ $staff->nama_lengkap }}</p>
                                            <p class="text-xs text-gray-400 truncate">{{ $staff->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4 font-medium text-gray-600">
                                    {{ $staff->status_pegawai }}
                                </td>
                                <td class="p-4">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-bold rounded-lg
                                        {{ $staff->is_active ? 'bg-green-50 text-green-700 border border-green-100' : 'bg-rose-50 text-rose-700 border border-rose-100' }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $staff->is_active ? 'bg-green-500' : 'bg-rose-500' }}"></span>
                                        {{ $staff->is_active ? 'Aktif' : 'Non-Aktif' }}
                                    </span>
                                </td>
                                <td class="p-4 text-right" onclick="event.stopPropagation()">
                                    <a href="{{ route('staff-divisi.show', $staff->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-indigo-50/50 hover:bg-indigo-50 text-[#3B28CC] font-bold text-xs rounded-lg border border-indigo-100 transition">
                                        Detail Profil <i class="fa-solid fa-angle-right text-[10px]"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="text-xs text-gray-400 text-right">
                Menampilkan <span class="font-semibold text-gray-600">{{ $staffs->count() }}</span> staff
            </div>
        @endif

    </div>

    
    <div id="tab-content-grup-kerja" class="hidden space-y-6">

        @if($grups->isEmpty())
            <div class="bg-white border border-gray-100 rounded-2xl p-14 text-center shadow-sm">
                <div class="flex flex-col items-center gap-3">
                    <div class="w-16 h-16 bg-indigo-50 rounded-full flex items-center justify-center">
                        <i class="fa-solid fa-people-group text-2xl text-[#3B28CC]/40"></i>
                    </div>
                    <p class="font-semibold text-gray-500">Belum ada grup kerja</p>
                    <p class="text-xs text-gray-400 max-w-xs">Grup kerja membantu mengelompokkan staff untuk penugasan kelompok. Klik tombol "Buat Grup Kerja" di kanan atas.</p>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($grups as $grup)
                <div class="bg-white border border-gray-100 rounded-2xl shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 flex flex-col overflow-hidden">
                    <div class="h-1.5 bg-gradient-to-r from-[#3B28CC] to-indigo-400"></div>

                    <div class="p-5 flex flex-col gap-4 flex-1">
                        <div>
                            <div class="flex items-start justify-between gap-2">
                                <h3 class="text-base font-bold text-gray-900 leading-snug">{{ $grup->nama_grup }}</h3>
                                <span class="shrink-0 inline-flex items-center gap-1 px-2 py-0.5 bg-indigo-50 text-[#3B28CC] text-[10px] font-bold rounded-md">
                                    {{ $grup->anggota->count() }} anggota
                                </span>
                            </div>
                            @if($grup->deskripsi)
                                <p class="text-xs text-gray-500 mt-1.5 leading-relaxed line-clamp-2">{{ $grup->deskripsi }}</p>
                            @else
                                <p class="text-xs text-gray-300 italic mt-1.5">Tidak ada deskripsi.</p>
                            @endif
                        </div>

                        
                        @if($grup->anggota->count() > 0)
                        <div class="flex items-center">
                            <div class="flex -space-x-2">
                                @foreach($grup->anggota->take(5) as $anggota)
                                    @if($anggota->foto_profil)
                                        <img src="{{ asset('storage/' . $anggota->foto_profil) }}"
                                             title="{{ $anggota->nama_lengkap }}"
                                             class="w-8 h-8 rounded-full object-cover border-2 border-white shadow-sm">
                                    @else
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($anggota->nama_lengkap) }}&background=3B28CC&color=fff&size=64"
                                             title="{{ $anggota->nama_lengkap }}"
                                             class="w-8 h-8 rounded-full object-cover border-2 border-white shadow-sm">
                                    @endif
                                @endforeach
                                @if($grup->anggota->count() > 5)
                                    <div class="w-8 h-8 rounded-full bg-gray-100 border-2 border-white flex items-center justify-center text-[10px] font-bold text-gray-500 shadow-sm">
                                        +{{ $grup->anggota->count() - 5 }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        @endif

                        <div class="pt-3 border-t border-gray-100 flex items-center justify-between text-[10px] text-gray-400 mt-auto">
                            <span>Dibuat {{ \Carbon\Carbon::parse($grup->created_at)->diffForHumans() }}</span>
                            <span>oleh <span class="font-semibold text-gray-600">{{ $grup->creator->nama_lengkap ?? '-' }}</span></span>
                        </div>

                        <div class="flex items-center gap-2">
                            <button type="button" onclick="showGroupDetail('{{ $grup->id }}')"
                               class="flex-1 flex items-center justify-center gap-1.5 py-2 border border-gray-200 text-gray-700 text-xs font-semibold rounded-xl hover:bg-gray-50 transition-colors cursor-pointer">
                                <i class="fa-solid fa-eye text-xs"></i> Lihat Detail
                            </button>
                            <form action="{{ route('grup-kerja.destroy', $grup->id) }}" method="POST"
                                  onsubmit="return confirm('Bubarkan grup \"{{ addslashes($grup->nama_grup) }}\"? Tindakan ini tidak dapat dibatalkan.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="flex items-center justify-center w-9 h-9 border border-rose-100 text-rose-500 rounded-xl hover:bg-rose-50 transition-colors cursor-pointer"
                                        title="Bubarkan Grup">
                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="text-xs text-gray-400 text-right">
                Total <span class="font-semibold text-gray-600">{{ $grups->count() }}</span> grup kerja
            </div>
        @endif

    </div>

</div>


<div id="modal-grup" class="fixed inset-0 z-[60] hidden" role="dialog" aria-modal="true">
    
    <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm" onclick="closeGrupModal()"></div>

    
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg border border-gray-100 overflow-hidden">

            
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-gray-900">Buat Grup Kerja</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Isi informasi grup dan pilih anggota divisi Anda.</p>
                </div>
                <button type="button" onclick="closeGrupModal()"
                        class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors cursor-pointer">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            
            <form method="POST" action="{{ route('grup-kerja.store') }}" id="form-grup">
                @csrf
                <div class="p-6 space-y-5 max-h-[70vh] overflow-y-auto">

                    
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-gray-600 uppercase tracking-wider block">
                            Nama Grup <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_grup" id="input-nama-grup"
                               placeholder="Contoh: Tim Proyek Alpha..."
                               required
                               class="w-full px-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#3B28CC]/20 focus:border-[#3B28CC] transition-all">
                    </div>

                    
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-gray-600 uppercase tracking-wider block">Deskripsi</label>
                        <textarea name="deskripsi" id="input-deskripsi" rows="3"
                                  placeholder="Tujuan atau keterangan grup kerja (opsional)..."
                                  class="w-full px-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#3B28CC]/20 focus:border-[#3B28CC] transition-all resize-none"></textarea>
                    </div>


                </div>

                
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex items-center justify-end gap-3">
                    <button type="button" onclick="closeGrupModal()"
                            class="px-4 py-2 border border-gray-200 text-gray-600 text-sm font-semibold rounded-xl hover:bg-gray-50 transition-colors cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" id="btn-submit-grup"
                            class="px-5 py-2 bg-[#3B28CC] text-white text-sm font-semibold rounded-xl hover:bg-[#2c1fa3] transition-colors cursor-pointer flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fa-solid fa-people-group text-xs"></i>
                        Buat Grup
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>


<div id="modal-detail-grup" class="fixed inset-0 z-[60] hidden" role="dialog" aria-modal="true">
    
    <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm" onclick="closeDetailGrupModal()"></div>

    
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg border border-gray-100 overflow-hidden">

            
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-gray-900" id="detail-grup-nama">Detail Grup</h3>
                    <p class="text-xs text-gray-400 mt-0.5" id="detail-grup-meta">Dibuat oleh - pada -</p>
                </div>
                <button type="button" onclick="closeDetailGrupModal()"
                        class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors cursor-pointer">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="p-6 space-y-5">
                
                <div class="space-y-1">
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Deskripsi</span>
                    <p class="text-sm text-gray-700 leading-relaxed" id="detail-grup-deskripsi">Tidak ada deskripsi.</p>
                </div>

                
                <div class="space-y-2">
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">
                        Anggota (<span id="detail-grup-anggota-count">0</span>)
                    </span>
                    <div id="detail-grup-anggota-list" class="space-y-2 max-h-60 overflow-y-auto pr-1">
                        
                    </div>
                </div>
            </div>

            
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex items-center justify-between">
                <form id="form-delete-grup" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 border border-rose-100 text-rose-600 text-sm font-semibold rounded-xl hover:bg-rose-50 transition-colors cursor-pointer flex items-center gap-1.5">
                        <i class="fa-solid fa-trash-can text-xs"></i> Bubarkan Grup
                    </button>
                </form>
                <button type="button" onclick="closeDetailGrupModal()"
                        class="px-4 py-2 border border-gray-200 text-gray-600 text-sm font-semibold rounded-xl hover:bg-gray-50 transition-colors cursor-pointer">
                    Tutup
                </button>
            </div>

        </div>
    </div>
</div>

<script>
// Global data
const allGroups = @json($grups);

// Tab switching
function switchTab(tabId) {
    document.getElementById('tab-content-staff-list').classList.add('hidden');
    document.getElementById('tab-content-grup-kerja').classList.add('hidden');

    const btnStaff = document.getElementById('tab-btn-staff-list');
    const btnGrup = document.getElementById('tab-btn-grup-kerja');

    btnStaff.className = "border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-semibold text-sm flex items-center gap-2 cursor-pointer transition-all";
    btnGrup.className = "border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-semibold text-sm flex items-center gap-2 cursor-pointer transition-all";

    if (tabId === 'staff-list') {
        document.getElementById('tab-content-staff-list').classList.remove('hidden');
        btnStaff.className = "border-[#3B28CC] text-[#3B28CC] whitespace-nowrap py-4 px-1 border-b-2 font-bold text-sm flex items-center gap-2 cursor-pointer transition-all";
    } else {
        document.getElementById('tab-content-grup-kerja').classList.remove('hidden');
        btnGrup.className = "border-[#3B28CC] text-[#3B28CC] whitespace-nowrap py-4 px-1 border-b-2 font-bold text-sm flex items-center gap-2 cursor-pointer transition-all";
    }
}

// Set active tab on load
document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const tab = urlParams.get('tab') || '{{ session("tab") }}';
    if (tab === 'grup-kerja') {
        switchTab('grup-kerja');
    } else {
        switchTab('staff-list');
    }
});

// Row navigation
document.querySelectorAll('.staff-row').forEach(row => {
    row.addEventListener('click', function() {
        window.location.href = "{{ url('staff-divisi') }}/" + this.dataset.id;
    });
});

// ─── Modal Buat Grup Kerja ─────────────────────────────────────
function openGrupModal() {
    document.getElementById('input-nama-grup').value = '';
    document.getElementById('input-deskripsi').value = '';

    const modal = document.getElementById('modal-grup');
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    document.getElementById('input-nama-grup').focus();
}

function closeGrupModal() {
    document.getElementById('modal-grup').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// ─── Modal Detail Grup Kerja ───────────────────────────────────
function showGroupDetail(id) {
    const grup = allGroups.find(g => g.id === id);
    if (!grup) return;

    document.getElementById('detail-grup-nama').textContent = 'Detail Grup - ' + grup.nama_grup;
    document.getElementById('detail-grup-deskripsi').textContent = grup.deskripsi || 'Tidak ada deskripsi.';
    
    const dateStr = new Date(grup.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
    const creatorName = grup.creator ? grup.creator.nama_lengkap : '-';
    document.getElementById('detail-grup-meta').textContent = `Dibuat oleh ${creatorName} pada ${dateStr}`;
    document.getElementById('detail-grup-anggota-count').textContent = grup.anggota.length;

    const listEl = document.getElementById('detail-grup-anggota-list');
    listEl.innerHTML = '';
    grup.anggota.forEach(m => {
        const foto = m.foto_profil ? `{{ asset('storage') }}/${m.foto_profil}` : `https://ui-avatars.com/api/?name=${encodeURIComponent(m.nama_lengkap)}&background=3B28CC&color=fff&size=64`;
        const item = document.createElement('div');
        item.className = 'flex items-center gap-3 p-2.5 bg-gray-50 rounded-xl border border-indigo-50 shrink-0';
        item.innerHTML = `
            <img src="${foto}" class="w-8 h-8 rounded-full object-cover border border-indigo-50 shrink-0">
            <div class="min-w-0 flex-1">
                <p class="text-sm font-semibold text-gray-800 truncate">${m.nama_lengkap}</p>
                <p class="text-xs text-gray-400 truncate">${m.email}</p>
            </div>
        `;
        listEl.appendChild(item);
    });

    const formDelete = document.getElementById('form-delete-grup');
    formDelete.action = `{{ url('grup-kerja') }}/${grup.id}`;

    const modal = document.getElementById('modal-detail-grup');
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeDetailGrupModal() {
    document.getElementById('modal-detail-grup').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Submit loaders
document.getElementById('form-grup')?.addEventListener('submit', function(e) {
    const btn = document.getElementById('btn-submit-grup');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-xs"></i> Memproses...';
});

// Auto-submit filters
document.getElementById('filter-status')?.addEventListener('change', () => document.getElementById('filter-form').submit());

let searchTimer;
const searchEl = document.getElementById('filter-search');
if (searchEl) {
    searchEl.addEventListener('input', function() {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => document.getElementById('filter-form').submit(), 500);
    });
}
</script>
@endsection
