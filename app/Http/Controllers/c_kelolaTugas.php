<?php

namespace App\Http\Controllers;

use App\Models\GrupKerja;
use App\Models\Notification;
use App\Models\Tugas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class c_kelolaTugas extends Controller
{
    public function index()
    {
        $departemenId = auth()->user()->departemen_id;

        if (auth()->user()->nama_role === 'staff') {
            $userId = auth()->id();

            $myGrupIds = GrupKerja::whereHas('anggota', function ($q) use ($userId) {
                $q->where('users.id', $userId);
            })->pluck('id');

            $tugas = Tugas::where('departemen_id', $departemenId)
                ->where(function ($query) use ($userId, $myGrupIds) {
                    $query->whereHas('detailTugas', function ($q) use ($userId, $myGrupIds) {
                        $q->where('user_id', $userId)
                            ->orWhereIn('grup_kerja_id', $myGrupIds);
                    })
                        ->orWhereDoesntHave('detailTugas');
                })
                ->latest()
                ->get();

            return view('staff.tugas.index', compact('tugas'));
        }

        $tugas = Tugas::where('departemen_id', $departemenId)->latest()->get();

        return view('manager.tugas.index', compact('tugas'));
    }

    public function create()
    {
        $departemenId = auth()->user()->departemen_id;

        $staffs = User::where('departemen_id', $departemenId)
            ->where('nama_role', 'staff')
            ->where('is_active', 1)
            ->orderBy('nama_lengkap')
            ->get();

        $grups = GrupKerja::where('departemen_id', $departemenId)
            ->orderBy('nama_grup')
            ->get();

        return view('manager.tugas.create', compact('staffs', 'grups'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'tanggal_tugas' => $request->filled(['tanggal_tugas_date', 'tanggal_tugas_time'])
                ? $request->tanggal_tugas_date.' '.$request->tanggal_tugas_time
                : null,
            'deadline_tugas' => $request->filled(['deadline_tugas_date', 'deadline_tugas_time'])
                ? $request->deadline_tugas_date.' '.$request->deadline_tugas_time
                : null,
        ]);

        $request->validate([
            'nama_tugas' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal_tugas' => 'required|date',
            'deadline_tugas' => 'required|date|after_or_equal:tanggal_tugas',
            'prioritas' => 'required|string|max:200',
            'kategoritugas' => 'required|string|max:50',
            'user_id' => 'required_if:kategoritugas,Individu|nullable|exists:users,id',
            'grup_kerja_id' => 'required_if:kategoritugas,Kelompok|nullable|exists:grup_kerjas,id',
            'gambar_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'nama_file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt|max:20480',
            'link_tugas' => 'nullable|url',
        ], [
            'user_id.required_if' => 'Staff penanggung jawab wajib dipilih jika kategori tugas adalah Individu.',
            'grup_kerja_id.required_if' => 'Grup kerja penanggung jawab wajib dipilih jika kategori tugas adalah Kelompok.',
        ]);

        $dataTugas = $request->only([
            'nama_tugas', 'deskripsi', 'tanggal_tugas', 'deadline_tugas', 'prioritas', 'kategoritugas',
        ]);

        $dataTugas['departemen_id'] = auth()->user()->departemen_id;
        $dataTugas['status_tugas'] = 'Belum Dikerjakan';

        $tugas = Tugas::create($dataTugas);

        $tugas->detailTugas()->create([
            'user_id' => $request->kategoritugas === 'Individu' ? $request->user_id : null,
            'grup_kerja_id' => $request->kategoritugas === 'Kelompok' ? $request->grup_kerja_id : null,
        ]);

        if ($tugas->kategoritugas === 'Individu' && $request->user_id) {
            \App\Models\Notification::create([
                'user_id' => $request->user_id,
                'title' => 'Tugas Baru Masuk',
                'message' => 'Anda mendapatkan tugas baru: "' . $tugas->nama_tugas . '".',
                'type' => 'tugas_baru',
                'related_id' => $tugas->id,
            ]);
        } elseif ($tugas->kategoritugas === 'Kelompok' && $request->grup_kerja_id) {
            $anggotaIds = \DB::table('detail_grups')
                ->where('grup_kerja_id', $request->grup_kerja_id)
                ->pluck('user_id');
            foreach ($anggotaIds as $uid) {
                \App\Models\Notification::create([
                    'user_id' => $uid,
                    'title' => 'Tugas Kelompok Baru',
                    'message' => 'Grup Anda mendapatkan tugas kelompok baru: "' . $tugas->nama_tugas . '".',
                    'type' => 'tugas_baru',
                    'related_id' => $tugas->id,
                ]);
            }
        }

        $hasGambar = $request->hasFile('gambar_file');
        $hasDokumen = $request->hasFile('nama_file');

        if ($hasGambar || $hasDokumen || $request->filled('link_tugas')) {
            $tugas->lampirans()->create([
                'gambar_file' => $hasGambar ? $request->file('gambar_file')->store('tugas/gambar', 'public') : null,
                'nama_file' => $hasDokumen ? $request->file('nama_file')->store('tugas/dokumen', 'public') : null,
                'link_tugas' => $request->link_tugas,
            ]);
        }

        try {
            event(new \App\Events\RealtimeTugasEvent(
                $tugas->departemen_id,
                'created',
                'Tugas Baru Masuk',
                'Tugas baru "' . $tugas->nama_tugas . '" telah diterbitkan oleh Manager.',
                null,
                $tugas->id
            ));
        } catch (\Throwable $e) {
        }

        return redirect()->route('tugas.index')->with('success', 'Tugas berhasil dibuat.');
    }

    public function show(string $id)
    {
        $departemenId = auth()->user()->departemen_id;
        $tugas = Tugas::where('departemen_id', $departemenId)
            ->with(['lampirans', 'detailTugas.user', 'detailTugas.grupKerja'])
            ->findOrFail($id);

        if (auth()->user()->nama_role === 'staff') {
            return view('staff.tugas.show', compact('tugas'));
        }

        return view('manager.tugas.show', compact('tugas'));
    }

    public function edit(string $id)
    {
        $departemenId = auth()->user()->departemen_id;
        $tugas = Tugas::where('departemen_id', $departemenId)->with('detailTugas')->findOrFail($id);

        $staffs = User::where('departemen_id', $departemenId)
            ->where('nama_role', 'staff')
            ->where('is_active', 1)
            ->orderBy('nama_lengkap')
            ->get();

        $grups = GrupKerja::where('departemen_id', $departemenId)
            ->orderBy('nama_grup')
            ->get();

        return view('manager.tugas.edit', compact('tugas', 'staffs', 'grups'));
    }

    public function update(Request $request, string $id)
    {
        $departemenId = auth()->user()->departemen_id;
        $tugas = Tugas::where('departemen_id', $departemenId)->findOrFail($id);

        $request->validate([
            'nama_tugas' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal_tugas' => 'required|date',
            'deadline_tugas' => 'required|date|after_or_equal:tanggal_tugas',
            'prioritas' => 'required|string|max:200',
            'status_tugas' => 'required|string|max:50',
            'kategoritugas' => 'required|string|max:50',
            'user_id' => 'required_if:kategoritugas,Individu|nullable|exists:users,id',
            'grup_kerja_id' => 'required_if:kategoritugas,Kelompok|nullable|exists:grup_kerjas,id',
            'catatan_revisi' => 'nullable|string',
        ], [
            'user_id.required_if' => 'Staff penanggung jawab wajib dipilih jika kategori tugas adalah Individu.',
            'grup_kerja_id.required_if' => 'Grup kerja penanggung jawab wajib dipilih jika kategori tugas adalah Kelompok.',
        ]);

        $tugas->update($request->only([
            'nama_tugas', 'deskripsi', 'tanggal_tugas', 'deadline_tugas', 'prioritas', 'status_tugas', 'kategoritugas', 'catatan_revisi',
        ]));

        $tugas->detailTugas()->updateOrCreate(
            ['tugas_id' => $tugas->id],
            [
                'user_id' => $request->kategoritugas === 'Individu' ? $request->user_id : null,
                'grup_kerja_id' => $request->kategoritugas === 'Kelompok' ? $request->grup_kerja_id : null,
            ]
        );

        try {
            event(new \App\Events\RealtimeTugasEvent(
                $tugas->departemen_id,
                'updated',
                'Tugas Diperbarui',
                'Tugas "' . $tugas->nama_tugas . '" telah diperbarui oleh Manager.',
                null,
                $tugas->id
            ));
        } catch (\Throwable $e) {
        }

        return redirect()->route('tugas.index')->with('success', 'Tugas berhasil diperbarui.');
    }

    public function reviewTugas(Request $request, string $id)
    {
        $departemenId = auth()->user()->departemen_id;
        $tugas = Tugas::where('departemen_id', $departemenId)->findOrFail($id);

        $request->validate([
            'action' => 'required|in:setujui,revisi',
            'catatan_revisi' => 'required_if:action,revisi|nullable|string',
        ]);

        if ($request->action === 'setujui') {
            $tugas->update([
                'status_tugas' => 'Selesai',
                'catatan_revisi' => null,
            ]);

            if ($tugas->kategoritugas === 'Individu' && $tugas->detailTugas && $tugas->detailTugas->user_id) {
                Notification::create([
                    'user_id' => $tugas->detailTugas->user_id,
                    'title' => 'Tugas Selesai',
                    'message' => 'Tugas "' . $tugas->nama_tugas . '" telah disetujui oleh Manager.',
                    'type' => 'tugas_baru',
                    'related_id' => $tugas->id,
                ]);
            } elseif ($tugas->kategoritugas === 'Kelompok' && $tugas->detailTugas && $tugas->detailTugas->grup_kerja_id) {
                $anggotaIds = \DB::table('detail_grups')
                    ->where('grup_kerja_id', $tugas->detailTugas->grup_kerja_id)
                    ->pluck('user_id');
                foreach ($anggotaIds as $uid) {
                    Notification::create([
                        'user_id' => $uid,
                        'title' => 'Tugas Kelompok Selesai',
                        'message' => 'Tugas kelompok "' . $tugas->nama_tugas . '" telah disetujui oleh Manager.',
                        'type' => 'tugas_baru',
                        'related_id' => $tugas->id,
                    ]);
                }
            }

            try {
                event(new \App\Events\RealtimeTugasEvent(
                    $tugas->departemen_id,
                    'reviewed',
                    'Tugas Selesai',
                    'Tugas "' . $tugas->nama_tugas . '" telah disetujui oleh Manager.',
                    null,
                    $tugas->id
                ));
            } catch (\Throwable $e) {
            }

            return redirect()->back()->with('success', 'Tugas berhasil disetujui.');
        }

        if ($request->action === 'revisi') {
            $tugas->update([
                'status_tugas' => 'Revisi',
                'catatan_revisi' => $request->catatan_revisi,
            ]);

            if ($tugas->kategoritugas === 'Individu' && $tugas->detailTugas && $tugas->detailTugas->user_id) {
                Notification::create([
                    'user_id' => $tugas->detailTugas->user_id,
                    'title' => 'Tugas Direvisi',
                    'message' => 'Tugas "'.$tugas->nama_tugas.'" memerlukan revisi: '.$request->catatan_revisi,
                    'type' => 'revisi_tugas',
                    'related_id' => $tugas->id,
                ]);
            } elseif ($tugas->kategoritugas === 'Kelompok' && $tugas->detailTugas && $tugas->detailTugas->grup_kerja_id) {
                $anggotaIds = \DB::table('detail_grups')
                    ->where('grup_kerja_id', $tugas->detailTugas->grup_kerja_id)
                    ->pluck('user_id');
                foreach ($anggotaIds as $uid) {
                    Notification::create([
                        'user_id' => $uid,
                        'title' => 'Tugas Kelompok Direvisi',
                        'message' => 'Tugas kelompok "'.$tugas->nama_tugas.'" memerlukan revisi: '.$request->catatan_revisi,
                        'type' => 'revisi_tugas',
                        'related_id' => $tugas->id,
                    ]);
                }
            }

            try {
                event(new \App\Events\RealtimeTugasEvent(
                    $tugas->departemen_id,
                    'reviewed',
                    'Tugas Direvisi',
                    'Tugas "' . $tugas->nama_tugas . '" memerlukan revisi.',
                    null,
                    $tugas->id
                ));
            } catch (\Throwable $e) {
            }

            return redirect()->back()->with('success', 'Tugas dikembalikan untuk revisi.');
        }
    }

    public function destroy(string $id)
    {
        $departemenId = auth()->user()->departemen_id;
        $tugas = Tugas::where('departemen_id', $departemenId)->with('lampirans')->findOrFail($id);

        foreach ($tugas->lampirans as $lampiran) {
            if ($lampiran->gambar_file) {
                Storage::disk('public')->delete($lampiran->gambar_file);
            }
            if ($lampiran->nama_file) {
                Storage::disk('public')->delete($lampiran->nama_file);
            }
        }

        $tugas->delete();

        return redirect()->route('tugas.index')->with('success', 'Tugas berhasil dihapus.');
    }



    public function submitTugas(Request $request, string $id)
    {
        $departemenId = auth()->user()->departemen_id;
        $tugas = Tugas::where('departemen_id', $departemenId)->findOrFail($id);

        $request->validate([
            'gambar_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'nama_file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt|max:20480',
            'link_tugas' => 'nullable|url',
        ]);

        $hasGambar = $request->hasFile('gambar_file');
        $hasDokumen = $request->hasFile('nama_file');

        if ($hasGambar || $hasDokumen || $request->filled('link_tugas')) {
            $tugas->lampirans()->create([
                'gambar_file' => $hasGambar ? $request->file('gambar_file')->store('pengumpulan/gambar', 'public') : null,
                'nama_file' => $hasDokumen ? $request->file('nama_file')->store('pengumpulan/dokumen', 'public') : null,
                'link_tugas' => $request->link_tugas,
            ]);
        }

        $tugas->update([
            'status_tugas' => 'Menunggu Persetujuan',
        ]);

        $manager = User::where('departemen_id', $tugas->departemen_id)
            ->where('nama_role', 'manager')
            ->first();
        if ($manager) {
            Notification::create([
                'user_id' => $manager->id,
                'title' => 'Tugas Dikumpulkan',
                'message' => 'Staff '.auth()->user()->nama_lengkap.' telah mengumpulkan tugas "'.$tugas->nama_tugas.'".',
                'type' => 'tugas_dikumpulkan',
                'related_id' => $tugas->id,
            ]);
        }

        try {
            event(new \App\Events\RealtimeTugasEvent(
                $tugas->departemen_id,
                'submitted',
                'Tugas Dikumpulkan',
                'Staff ' . auth()->user()->nama_lengkap . ' telah mengumpulkan tugas "' . $tugas->nama_tugas . '".',
                null,
                $tugas->id
            ));
        } catch (\Throwable $e) {
        }

        return redirect()->route('staff.tugas.show', $tugas->id)->with('success', 'Tugas berhasil dikumpulkan.');
    }
}
