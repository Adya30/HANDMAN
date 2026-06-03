<?php

namespace App\Http\Controllers;

use App\Models\Tugas;
use App\Models\Lampiran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class c_kelolaTugas extends Controller
{
    public function index()
    {
        $tugas = Tugas::with('lampirans')->latest()->get();

        if (Auth::user()->nama_role === 'manager') {
            return view('manager.tugas.index', compact('tugas'));
        }

        return view('staff.tugas.index', compact('tugas'));
    }

    public function create()
    {
        return view('manager.tugas.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_tugas' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal_tugas' => 'required|date',
            'deadline_tugas' => 'required|date|after:tanggal_tugas',
            'prioritas' => 'required|string',
        ]);

        Tugas::create($validated);
        return redirect()->route('tugas.index')->with('success', 'Tugas berhasil dibuat.');
    }

    public function edit($id)
    {
        $tugas = Tugas::findOrFail($id);
        return view('manager.tugas.edit', compact('tugas'));
    }

    public function update(Request $request, $id)
    {
        $tugas = Tugas::findOrFail($id);
        $validated = $request->validate([
            'nama_tugas' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'deadline_tugas' => 'required|date',
            'prioritas' => 'required|string',
        ]);

        $tugas->update($validated);
        return redirect()->route('tugas.index')->with('success', 'Tugas berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Tugas::findOrFail($id)->delete();
        return redirect()->route('tugas.index')->with('success', 'Tugas berhasil dihapus.');
    }

    public function konfirmasiTerima($id)
    {
        Tugas::findOrFail($id)->update(['status' => 'diterima']);
        return redirect()->back()->with('success', 'Tugas dikonfirmasi.');
    }

    public function submitTugas(Request $request, $id)
    {
        $tugas = Tugas::findOrFail($id);
        if (now()->gt($tugas->deadline_tugas)) {
            return redirect()->back()->with('error', 'Deadline telah terlewati.');
        }

        $validated = $request->validate([
            'nama_file' => 'nullable|file|mimes:jpg,png,pdf,doc,docx|max:2048',
            'link_tugas' => 'nullable|url',
        ]);

        if ($request->hasFile('nama_file')) {
            $path = $request->file('nama_file')->store('lampiran', 'public');
            $validated['nama_file'] = $path;
            $validated['jenis'] = 'file';
        } else {
            $validated['jenis'] = 'link';
        }

        $validated['tugas_id'] = $id;
        Lampiran::create($validated);

        return redirect()->back()->with('success', 'Tugas dikumpulkan.');
    }

    public function updateLampiran(Request $request, $lampiranId)
    {
        $lampiran = Lampiran::findOrFail($lampiranId);
        $tugas = $lampiran->tugas;

        if (now()->gt($tugas->deadline_tugas)) {
            return redirect()->back()->with('error', 'Deadline terlewati.');
        }

        $validated = $request->validate([
            'link_tugas' => 'nullable|url',
        ]);

        $lampiran->update($validated);
        return redirect()->back()->with('success', 'Lampiran diperbarui.');
    }

    public function hapusSubmit($id)
    {
        $lampiran = Lampiran::findOrFail($id);
        if (now()->gt($lampiran->tugas->deadline_tugas)) {
            return redirect()->back()->with('error', 'Deadline terlewati.');
        }

        $lampiran->delete();
        return redirect()->back()->with('success', 'Pengumpulan dihapus.');
    }
}
