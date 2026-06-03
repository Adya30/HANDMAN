@extends('layouts.app')

@section('content')
<div class="p-6 max-w-2xl mx-auto bg-white rounded-lg shadow">
    <h2 class="text-xl font-bold mb-4">Edit Tugas: {{ $tugas->nama_tugas }}</h2>
    <form action="{{ route('tugas.update', $tugas->id) }}" method="POST">
        @csrf @method('PUT')
        <input type="text" name="nama_tugas" value="{{ $tugas->nama_tugas }}" class="w-full border p-2 rounded mb-3" required>
        <textarea name="deskripsi" class="w-full border p-2 rounded mb-3" required>{{ $tugas->deskripsi }}</textarea>
        <input type="datetime-local" name="deadline_tugas" value="{{ date('Y-m-d\TH:i', strtotime($tugas->deadline_tugas)) }}" class="w-full border p-2 rounded mb-3" required>
        <select name="prioritas" class="w-full border p-2 rounded mb-3">
            <option value="rendah" {{ $tugas->prioritas == 'rendah' ? 'selected' : '' }}>Rendah</option>
            <option value="sedang" {{ $tugas->prioritas == 'sedang' ? 'selected' : '' }}>Sedang</option>
            <option value="tinggi" {{ $tugas->prioritas == 'tinggi' ? 'selected' : '' }}>Tinggi</option>
        </select>
        <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded">Update Tugas</button>
    </form>
</div>
@endsection
