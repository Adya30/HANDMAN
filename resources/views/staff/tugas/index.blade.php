@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold">Tugas Anda</h1>
    @foreach($tugas as $item)
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 space-y-4">
        <div>
            <h3 class="font-bold text-lg">{{ $item->nama_tugas }}</h3>
            <p class="text-sm text-gray-500">{{ $item->deskripsi }}</p>
        </div>

        <form action="{{ route('tugas.submit', $item->id) }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-4 items-end">
            @csrf
            <div>
                <label class="text-sm block">Upload File (Foto/Dokumen)</label>
                <input type="file" name="nama_file" class="w-full border rounded p-2">
            </div>
            <div>
                <label class="text-sm block">Atau Masukkan Link</label>
                <input type="url" name="link_tugas" class="w-full border rounded p-2">
            </div>
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-xl">Kumpul Tugas</button>
        </form>

        @foreach($item->lampirans as $lampiran)
            <div class="mt-4 p-3 bg-gray-50 rounded flex justify-between items-center text-sm">
                <span>{{ $lampiran->jenis == 'file' ? 'File Terkumpul' : $lampiran->link_tugas }}</span>
                <form action="{{ route('lampiran.destroy', $lampiran->id) }}" method="POST">
                    @csrf @method('DELETE')
                    <button class="text-red-500 text-xs">Hapus</button>
                </form>
            </div>
        @endforeach
    </div>
    @endforeach
</div>
@endsection
