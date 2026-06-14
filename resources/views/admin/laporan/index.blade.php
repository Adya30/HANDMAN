@extends('layouts.app')

@section('title', 'Laporan Masuk')

@section('content')
<div class="space-y-6 pb-10">

    
    <div class="flex items-center justify-between gap-4 shrink-0">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Laporan Masuk</h1>
            <p class="text-sm text-gray-500 mt-0.5">Pantau dan berikan respon tanggapan terhadap laporan dari Staff dan Manager.</p>
        </div>
    </div>

    
    @if(session('success'))
        <div class="p-4 text-sm text-green-800 bg-green-50 border border-green-100 rounded-xl flex items-center gap-3">
            <i class="fa-solid fa-circle-check text-green-600 text-base shrink-0"></i>
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 text-sm text-rose-800 bg-rose-50 border border-rose-100 rounded-xl space-y-1">
            <div class="flex items-center gap-2 font-bold">
                <i class="fa-solid fa-circle-xmark text-rose-600 text-base"></i> Terjadi Kesalahan:
            </div>
            <ul class="list-disc pl-5 text-xs space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    
    <div class="bg-white border border-gray-100 rounded-2xl p-4 shadow-sm flex items-center justify-between gap-4 flex-wrap">
        <div class="flex items-center gap-2">
            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Status Laporan:</span>
            <div class="flex flex-wrap gap-1.5">
                <a href="{{ route('admin.laporan.index') }}"
                   class="px-3 py-1.5 text-xs font-semibold rounded-lg border transition-colors
                    {{ !request()->filled('status')
                        ? 'bg-[#3B28CC] text-white border-[#3B28CC]'
                        : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-gray-100' }}">
                    Semua
                </a>
                <a href="{{ route('admin.laporan.index', ['status' => 'Menunggu']) }}"
                   class="px-3 py-1.5 text-xs font-semibold rounded-lg border transition-colors
                    {{ request('status') === 'Menunggu'
                        ? 'bg-[#3B28CC] text-white border-[#3B28CC]'
                        : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-gray-100' }}">
                    Menunggu Tanggapan
                </a>
                <a href="{{ route('admin.laporan.index', ['status' => 'Dibalas']) }}"
                   class="px-3 py-1.5 text-xs font-semibold rounded-lg border transition-colors
                    {{ request('status') === 'Dibalas'
                        ? 'bg-[#3B28CC] text-white border-[#3B28CC]'
                        : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-gray-100' }}">
                    Dibalas
                </a>
                <a href="{{ route('admin.laporan.index', ['status' => 'Selesai']) }}"
                   class="px-3 py-1.5 text-xs font-semibold rounded-lg border transition-colors
                    {{ request('status') === 'Selesai'
                        ? 'bg-[#3B28CC] text-white border-[#3B28CC]'
                        : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-gray-100' }}">
                    Selesai
                </a>
            </div>
        </div>
        <div class="text-xs text-gray-400">
            Total <span class="font-bold text-gray-600">{{ $laporans->count() }}</span> laporan ditemukan.
        </div>
    </div>

    
    @if($laporans->isEmpty())
        <div class="bg-white border border-gray-100 rounded-2xl p-14 text-center shadow-sm">
            <div class="flex flex-col items-center gap-3">
                <div class="w-16 h-16 bg-indigo-50 rounded-full flex items-center justify-center text-[#3B28CC]">
                    <i class="fa-solid fa-inbox text-2xl"></i>
                </div>
                <p class="font-semibold text-gray-500">Tidak ada laporan masuk</p>
                <p class="text-xs text-gray-400 max-w-xs">Saat ini tidak ada laporan dengan status terpilih yang masuk ke sistem.</p>
            </div>
        </div>
    @else
        <div class="space-y-4">
            @foreach($laporans as $laporan)
            <div class="bg-white border border-gray-100 rounded-2xl shadow-xs overflow-hidden">
                <div class="p-5 sm:p-6 space-y-4">

                    
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div class="flex items-center gap-3">
                            @if($laporan->user->foto_profil)
                                <img src="{{ asset('storage/' . $laporan->user->foto_profil) }}"
                                     class="w-10 h-10 rounded-full object-cover border border-indigo-50 shrink-0">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($laporan->user->nama_lengkap) }}&background=3B28CC&color=fff&size=64"
                                     class="w-10 h-10 rounded-full object-cover border border-indigo-50 shrink-0">
                            @endif
                            <div class="min-w-0">
                                <div class="flex items-center gap-2">
                                    <p class="text-sm font-bold text-gray-900 truncate">{{ $laporan->user->nama_lengkap }}</p>
                                    <span class="px-2 py-0.5 text-[9px] font-bold rounded-md uppercase tracking-wider
                                        {{ $laporan->user->nama_role === 'manager' ? 'bg-purple-50 text-purple-700' : 'bg-blue-50 text-blue-700' }}">
                                        {{ $laporan->user->nama_role }}
                                    </span>
                                </div>
                                <p class="text-xs text-gray-400 truncate">{{ $laporan->user->departemen->nama_departemen ?? '-' }} &bull; {{ \Carbon\Carbon::parse($laporan->created_at)->translatedFormat('d M Y, H:i') }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-bold rounded-lg border
                                {{ $laporan->status === 'Menunggu'
                                    ? 'bg-amber-50 text-amber-700 border-amber-100'
                                    : ($laporan->status === 'Dibalas'
                                        ? 'bg-blue-50 text-blue-700 border-blue-100'
                                        : 'bg-green-50 text-green-700 border-green-100') }}">
                                <span class="w-1.5 h-1.5 rounded-full
                                    {{ $laporan->status === 'Menunggu'
                                        ? 'bg-amber-500'
                                        : ($laporan->status === 'Dibalas'
                                            ? 'bg-blue-500'
                                            : 'bg-green-500') }}"></span>
                                {{ $laporan->status === 'Menunggu' ? 'Menunggu Tanggapan' : $laporan->status }}
                            </span>
                        </div>
                    </div>

                    
                    <div class="space-y-1.5">
                        <h4 class="text-sm font-bold text-gray-800">{{ $laporan->judul }}</h4>
                        <div class="text-sm text-gray-600 leading-relaxed bg-gray-50/50 p-4 rounded-xl border border-gray-50">
                            {{ $laporan->isi }}
                        </div>
                    </div>

                    
                    @if($laporan->tanggapan)
                        <div class="border-t border-gray-100 pt-4 space-y-3">
                            <div class="flex items-center gap-2 text-xs font-bold text-gray-500">
                                <i class="fa-solid fa-reply text-indigo-500 rotate-180"></i>
                                Tanggapan Admin
                            </div>
                            <div class="bg-indigo-50/30 border border-indigo-50/50 rounded-xl p-4 space-y-2">
                                <p class="text-sm text-gray-700 leading-relaxed">{{ $laporan->tanggapan }}</p>
                                <div class="flex items-center justify-between text-[10px] text-gray-400 pt-1">
                                    <span>Ditanggapi oleh <span class="font-semibold text-gray-600">{{ $laporan->responder->nama_lengkap ?? 'Admin' }}</span></span>
                                    <span>{{ \Carbon\Carbon::parse($laporan->responded_at)->translatedFormat('d M Y, H:i') }}</span>
                                </div>
                            </div>
                        </div>
                    @endif

                    
                    <div class="flex justify-end pt-2">
                        <button type="button" onclick="openResponModal({{ json_encode($laporan) }})"
                                class="bg-white border border-gray-200 text-gray-700 hover:text-[#3B28CC] hover:bg-indigo-50/50 px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer">
                            <i class="fa-solid fa-comment-dots text-xs"></i>
                            {{ $laporan->tanggapan ? 'Perbarui Tanggapan' : 'Beri Tanggapan' }}
                        </button>
                    </div>

                </div>
            </div>
            @endforeach
        </div>
    @endif

</div>


<div id="modal-respon" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true">
    
    <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-xs" onclick="closeResponModal()"></div>

    
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg border border-gray-100 overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-gray-900">Respon Laporan</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Kirim tanggapan resmi dan perbarui status laporan.</p>
                </div>
                <button type="button" onclick="closeResponModal()"
                        class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors cursor-pointer">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form method="POST" action="" id="form-respon">
                @csrf
                @method('PUT')
                <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto">
                    
                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 space-y-2">
                        <div class="flex items-center justify-between text-[10px] text-gray-400">
                            <span class="font-bold text-gray-500 uppercase tracking-wide">Detail Pengaduan</span>
                            <span id="respon-pengirim">-</span>
                        </div>
                        <h4 class="text-xs font-bold text-gray-800" id="respon-judul">-</h4>
                        <p class="text-xs text-gray-600 leading-relaxed line-clamp-4" id="respon-isi">-</p>
                    </div>

                    
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-gray-600 uppercase tracking-wider block">
                            Pesan Tanggapan <span class="text-red-500">*</span>
                        </label>
                        <textarea name="tanggapan" id="respon-tanggapan" rows="5" required
                                  placeholder="Tuliskan respon tanggapan resmi Anda..."
                                  class="w-full px-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#3B28CC]/20 focus:border-[#3B28CC] transition-all resize-none"></textarea>
                    </div>

                    
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-gray-600 uppercase tracking-wider block">
                            Status Laporan <span class="text-red-500">*</span>
                        </label>
                        <select name="status" id="respon-status" required
                                class="w-full py-2.5 px-3 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#3B28CC]/20 focus:border-[#3B28CC] transition-all appearance-none cursor-pointer">
                            <option value="Dibalas">Dibalas (Laporan telah direspon)</option>
                            <option value="Selesai">Selesai (Masalah telah teratasi)</option>
                        </select>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex items-center justify-end gap-3">
                    <button type="button" onclick="closeResponModal()"
                            class="px-4 py-2 border border-gray-200 text-gray-600 text-sm font-semibold rounded-xl hover:bg-gray-50 transition-colors cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" id="btn-submit-respon"
                            class="px-5 py-2 bg-[#3B28CC] text-white text-sm font-semibold rounded-xl hover:bg-[#2c1fa3] transition-colors cursor-pointer flex items-center gap-2">
                        <i class="fa-solid fa-comment-dots text-xs"></i>
                        Kirim Respon
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openResponModal(laporan) {
    document.getElementById('respon-pengirim').textContent = laporan.user ? `${laporan.user.nama_lengkap} (${laporan.user.nama_role})` : 'User';
    document.getElementById('respon-judul').textContent = laporan.judul;
    document.getElementById('respon-isi').textContent = laporan.isi;
    document.getElementById('respon-tanggapan').value = laporan.tanggapan || '';
    
    // Set status select value
    const statusVal = laporan.status === 'Menunggu' ? 'Dibalas' : laporan.status;
    document.getElementById('respon-status').value = statusVal;

    // Set form action target URL
    const form = document.getElementById('form-respon');
    form.action = `{{ url('admin/laporan') }}/${laporan.id}/respon`;

    const modal = document.getElementById('modal-respon');
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    document.getElementById('respon-tanggapan').focus();
}

function closeResponModal() {
    document.getElementById('modal-respon').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

document.getElementById('form-respon')?.addEventListener('submit', function() {
    const btn = document.getElementById('btn-submit-respon');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-xs"></i> Mengirim...';
});
</script>
@endsection
