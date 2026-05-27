@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Dashboard Manager</h1>
        <p class="text-sm text-gray-500 mt-0.5">Pantau kinerja departemen dan progres tim Anda.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">Anggota Tim</span>
                <h3 class="text-2xl font-bold text-gray-800">12</h3>
            </div>
            <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
                <i class="fa-solid fa-user-group text-xl"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">Perlu Persetujuan</span>
                <h3 class="text-2xl font-bold text-gray-800">8</h3>
            </div>
            <div class="w-12 h-12 bg-orange-50 rounded-xl flex items-center justify-center text-orange-600">
                <i class="fa-solid fa-file-signature text-xl"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">Tugas Selesai (Bulan Ini)</span>
                <h3 class="text-2xl font-bold text-gray-800">45</h3>
            </div>
            <div class="w-12 h-12 bg-teal-50 rounded-xl flex items-center justify-center text-teal-600">
                <i class="fa-solid fa-check-double text-xl"></i>
            </div>
        </div>
    </div>
</div>
@endsection
