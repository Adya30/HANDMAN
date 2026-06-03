@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="flex justify-between mb-6">
        <h1 class="text-2xl font-bold">Daftar Semua Tugas</h1>
        <a href="{{ route('tugas.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg">Tambah Tugas</a>
    </div>

    <div class="bg-white rounded-lg shadow p-4">
        <table class="w-full text-left">
            <thead>
                <tr class="border-b">
                    <th class="p-2">Nama</th>
                    <th class="p-2">Deadline</th>
                    <th class="p-2">Status</th>
                    <th class="p-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tugas as $item)
                <tr class="border-b">
                    <td class="p-2">{{ $item->nama_tugas }}</td>
                    <td class="p-2">{{ $item->deadline_tugas }}</td>
                    <td class="p-2">{{ $item->status ?? 'Pending' }}</td>
                    <td class="p-2 flex gap-2">
                        <form action="{{ route('tugas.konfirmasi', $item->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <button class="text-green-600 font-bold">Terima</button>
                        </form>
                        <a href="{{ route('tugas.edit', $item->id) }}" class="text-blue-600">Edit</a>
                        <form action="{{ route('tugas.destroy', $item->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button class="text-red-600">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
