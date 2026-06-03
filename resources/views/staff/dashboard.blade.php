@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Dashboard Staff</h1>
        <p class="text-sm text-gray-500">Selesaikan tugas Anda sebelum deadline.</p>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
        <h2 class="text-lg font-bold mb-4">Tugas Saya</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-gray-500 border-b">
                    <tr>
                        <th class="pb-3">Nama Tugas</th>
                        <th class="pb-3">Deadline</th>
                        <th class="pb-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
