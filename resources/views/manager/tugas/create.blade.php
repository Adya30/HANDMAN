@extends('layouts.app')

@section('content')
<div class="p-6 max-w-2xl mx-auto bg-white rounded-lg shadow">
    <h2 class="text-xl font-bold mb-4">Buat Tugas Baru</h2>
    <form action="{{ route('tugas.store') }}" method="POST">
        @csrf
        <input type="text" name="nama_tugas" placeholder="Nama Tugas" class="w-full border p-2 rounded mb-3" required>
        <textarea name="deskripsi" placeholder="Deskripsi Tugas" class="w-full border p-2 rounded mb-3" required></textarea>
        <input type="datetime-local" name="tanggal_tugas" class="w-full border p-2 rounded mb-3" required>
        <input type="datetime-local" name="deadline_tugas" class="w-full border p-2 rounded mb-3" required>
        <select name="prioritas" class="w-full border p-2 rounded mb-3">
            <option value="rendah">Rendah</option>
            <option value="sedang">Sedang</option>
            <option value="tinggi">Tinggi</option>
        </select>
        <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded">Simpan Tugas</button>
    </form>
</div>
@endsection
