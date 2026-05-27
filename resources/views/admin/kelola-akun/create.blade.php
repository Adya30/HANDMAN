@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 shrink-0">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Tambah Akun Baru</h1>
            <p class="text-sm text-gray-500 mt-0.5">Isi seluruh formulir di bawah ini untuk menambahkan pengguna baru.</p>
        </div>
        <div>
            <a href="{{ route('kelola-akun.index') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl shadow-sm transition-colors gap-2">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                Kembali
            </a>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
        <form id="form-akun" action="{{ route('kelola-akun.store') }}" method="POST" class="space-y-6" data-redirect="{{ route('kelola-akun.index') }}" novalidate>
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <div class="space-y-1.5">
                    <label class="text-sm font-medium text-gray-700">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required class="w-full px-4 py-2.5 mt-1.5 text-sm text-gray-800 bg-white border border-gray-200 rounded-xl focus:border-[#3B28CC] focus:ring-1 focus:ring-[#3B28CC] outline-none transition-all">
                    <p class="text-xs text-red-600 error-msg hidden" id="error-nama_lengkap"></p>
                </div>

                <div class="space-y-1.5">
                    <label class="text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-2.5 mt-1.5 text-sm text-gray-800 bg-white border border-gray-200 rounded-xl focus:border-[#3B28CC] focus:ring-1 focus:ring-[#3B28CC] outline-none transition-all">
                    <p class="text-xs text-red-600 error-msg hidden" id="error-email"></p>
                </div>

                <div class="space-y-1.5">
                    <label class="text-sm font-medium text-gray-700">Password</label>
                    <div class="relative w-full">
                        <input type="password" id="password" name="password" required class="w-full px-4 py-2.5 mt-1.5 pr-11 text-sm text-gray-800 bg-white border border-gray-200 rounded-xl focus:border-[#3B28CC] focus:ring-1 focus:ring-[#3B28CC] outline-none transition-all">
                        <button type="button" onclick="const p = document.getElementById('password'); p.type = p.type === 'password' ? 'text' : 'password'; this.querySelector('i').classList.toggle('fa-eye'); this.querySelector('i').classList.toggle('fa-eye-slash');" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
                            <i class="fa-solid fa-eye text-sm"></i>
                        </button>
                    </div>
                    <p class="text-xs text-red-600 error-msg hidden" id="error-password"></p>
                </div>

                <div class="space-y-1.5">
                    <label class="text-sm font-medium text-gray-700">No. Telepon</label>
                    <input type="text" name="no_telp" value="{{ old('no_telp') }}" required class="w-full px-4 py-2.5 mt-1.5 text-sm text-gray-800 bg-white border border-gray-200 rounded-xl focus:border-[#3B28CC] focus:ring-1 focus:ring-[#3B28CC] outline-none transition-all">
                    <p class="text-xs text-red-600 error-msg hidden" id="error-no_telp"></p>
                </div>

                <div class="space-y-1.5">
                    <label class="text-sm font-medium text-gray-700">Jenis Kelamin</label>
                    <select name="jenis_kelamin" required class="w-full px-4 py-2.5 mt-1.5 text-sm text-gray-800 bg-white border border-gray-200 rounded-xl focus:border-[#3B28CC] focus:ring-1 focus:ring-[#3B28CC] outline-none transition-all">
                        <option value="" disabled {{ old('jenis_kelamin') == '' ? 'selected' : '' }}>Pilih Jenis Kelamin</option>
                        <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    <p class="text-xs text-red-600 error-msg hidden" id="error-jenis_kelamin"></p>
                </div>

                <div class="space-y-1.5">
                    <label class="text-sm font-medium text-gray-700">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required class="w-full px-4 py-2.5 mt-1.5 text-sm text-gray-800 bg-white border border-gray-200 rounded-xl focus:border-[#3B28CC] focus:ring-1 focus:ring-[#3B28CC] outline-none transition-all">
                    <p class="text-xs text-red-600 error-msg hidden" id="error-tanggal_lahir"></p>
                </div>

                <div class="space-y-1.5">
                    <label class="text-sm font-medium text-gray-700">Status Pegawai</label>
                    <select name="status_pegawai" required class="w-full px-4 py-2.5 mt-1.5 text-sm text-gray-800 bg-white border border-gray-200 rounded-xl focus:border-[#3B28CC] focus:ring-1 focus:ring-[#3B28CC] outline-none transition-all">
                        <option value="" disabled {{ old('status_pegawai') == '' ? 'selected' : '' }}>Pilih Status Pegawai</option>
                        <option value="magang" {{ old('status_pegawai') == 'magang' ? 'selected' : '' }}>Magang</option>
                        <option value="tetap" {{ old('status_pegawai') == 'tetap' ? 'selected' : '' }}>Tetap</option>
                    </select>
                    <p class="text-xs text-red-600 error-msg hidden" id="error-status_pegawai"></p>
                </div>

                <div class="space-y-1.5">
                    <label class="text-sm font-medium text-gray-700">Role</label>
                    <select name="nama_role" required class="w-full px-4 py-2.5 mt-1.5 text-sm text-gray-800 bg-white border border-gray-200 rounded-xl focus:border-[#3B28CC] focus:ring-1 focus:ring-[#3B28CC] outline-none transition-all">
                        <option value="" disabled {{ old('nama_role') == '' ? 'selected' : '' }}>Pilih Role</option>
                        <option value="manager" {{ old('nama_role') == 'manager' ? 'selected' : '' }}>Manager</option>
                        <option value="staff" {{ old('nama_role') == 'staff' ? 'selected' : '' }}>Staff</option>
                    </select>
                    <p class="text-xs text-red-600 error-msg hidden" id="error-nama_role"></p>
                </div>

                <div class="space-y-1.5 md:col-span-2">
                    <label class="text-sm font-medium text-gray-700">Departemen</label>
                    <div class="flex gap-2">
                        <select name="departemen_id" required class="w-full px-4 py-2.5 mt-1.5 text-sm text-gray-800 bg-white border border-gray-200 rounded-xl focus:border-[#3B28CC] focus:ring-1 focus:ring-[#3B28CC] outline-none transition-all">
                            <option value="" disabled {{ old('departemen_id') == '' ? 'selected' : '' }}>Pilih Departemen</option>
                            @foreach($departemens as $dept)
                                <option value="{{ $dept->id }}" {{ old('departemen_id') == $dept->id ? 'selected' : '' }}>{{ $dept->nama_departemen }}</option>
                            @endforeach
                        </select>
                        <a href="{{ route('departemen.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl shadow-sm transition-colors whitespace-nowrap gap-2">
                            <i class="fa-solid fa-plus text-xs"></i>
                            Tambah Departemen
                        </a>
                    </div>
                    <p class="text-xs text-red-600 error-msg hidden" id="error-departemen_id"></p>
                </div>

                <div class="space-y-1.5 md:col-span-2">
                    <label class="text-sm font-medium text-gray-700">Alamat</label>
                    <textarea name="alamat" rows="3" required class="w-full px-4 py-2.5 mt-1.5 text-sm text-gray-800 bg-white border border-gray-200 rounded-xl focus:border-[#3B28CC] focus:ring-1 focus:ring-[#3B28CC] outline-none transition-all">{{ old('alamat') }}</textarea>
                    <p class="text-xs text-red-600 error-msg hidden" id="error-alamat"></p>
                </div>

                <div class="space-y-1.5 md:col-span-2">
                    <label class="text-sm font-medium text-gray-700">Deskripsi User</label>
                    <textarea name="deskripsi_user" rows="3" class="w-full px-4 py-2.5 mt-1.5 text-sm text-gray-800 bg-white border border-gray-200 rounded-xl focus:border-[#3B28CC] focus:ring-1 focus:ring-[#3B28CC] outline-none transition-all">{{ old('deskripsi_user') }}</textarea>
                    <p class="text-xs text-red-600 error-msg hidden" id="error-deskripsi_user"></p>
                </div>
            </div>

            <div class="flex items-center justify-end pt-4 border-t border-gray-100">
                <button type="button" onclick="openModal('confirm')" class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-medium text-white bg-[#3B28CC] hover:bg-opacity-90 rounded-xl shadow-sm transition-colors cursor-pointer">
                    Simpan Akun
                </button>
            </div>
        </form>
    </div>

</div>

<x-confirm-modal
    id="confirm"
    title="Konfirmasi Simpan Akun"
    message="Apakah Anda yakin data yang dimasukkan sudah benar dan ingin menyimpan akun baru ini?"
    action="executeGlobalAjaxSubmit('form-akun', 'confirm')"
    method="POST"
    type="primary"
/>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        initRealTimeValidation('form-akun');
    });
</script>
@endsection
