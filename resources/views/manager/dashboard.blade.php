@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Dashboard Manager</h1>
        <p class="text-sm text-gray-500">Kelola tugas dan pantau progres tim Anda di sini.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="{{ route('tugas.index') }}" class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:border-[#3B28CC] transition">
            <h3 class="text-lg font-bold text-gray-800">Kelola Tugas</h3>
            <p class="text-sm text-gray-500">Buat dan pantau semua tugas.</p>
        </a>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
        <h2 class="text-lg font-bold mb-4">Tugas Terbaru</h2>
        </div>
</div>
@endsection
