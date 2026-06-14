<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use Illuminate\Http\Request;

class c_laporan extends Controller
{
    


    public function index(Request $request)
    {
        $user = auth()->user();
        $role = $user->nama_role;

        if ($role === 'admin') {
            $query = Laporan::with(['user.departemen', 'responder']);

            
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            $laporans = $query->latest()->get();
            return view('admin.laporan.index', compact('laporans'));
        }

        
        $laporans = Laporan::with('responder')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $view = $role === 'manager' ? 'manager.laporan.index' : 'staff.laporan.index';
        return view($view, compact('laporans'));
    }

    


    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi'   => 'required|string',
        ], [
            'judul.required' => 'Subjek / judul laporan wajib diisi.',
            'judul.max'      => 'Subjek / judul maksimal 255 karakter.',
            'isi.required'   => 'Detail laporan wajib diisi.',
        ]);

        $laporan = Laporan::create([
            'user_id' => auth()->id(),
            'judul'   => $request->judul,
            'isi'     => $request->isi,
            'status'  => 'Menunggu',
        ]);

        
        $admins = \App\Models\User::where('nama_role', 'admin')->get();
        foreach ($admins as $admin) {
            \App\Models\Notification::create([
                'user_id'    => $admin->id,
                'title'      => 'Laporan Masuk Baru',
                'message'    => 'Laporan baru "' . $laporan->judul . '" dari ' . auth()->user()->nama_lengkap,
                'type'       => 'laporan_masuk',
                'related_id' => $laporan->id,
            ]);
        }

        try {
            event(new \App\Events\RealtimeLaporanEvent(
                'created',
                'Laporan Masuk Baru',
                'Laporan baru "' . $laporan->judul . '" dari ' . auth()->user()->nama_lengkap,
                null,
                $laporan->id
            ));
        } catch (\Throwable $e) {
        }

        return back()->with('success', 'Laporan berhasil dikirim dan sedang menunggu tanggapan admin.');
    }

    


    public function respond(Request $request, string $id)
    {
        
        if (auth()->user()->nama_role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'tanggapan' => 'required|string',
            'status'    => 'required|in:Dibalas,Selesai',
        ], [
            'tanggapan.required' => 'Pesan tanggapan wajib diisi.',
            'status.required'    => 'Status laporan wajib dipilih.',
            'status.in'          => 'Status laporan tidak valid.',
        ]);

        $laporan = Laporan::findOrFail($id);
        $laporan->update([
            'tanggapan'    => $request->tanggapan,
            'status'       => $request->status,
            'responded_by' => auth()->id(),
            'responded_at' => now(),
        ]);

        try {
            event(new \App\Events\RealtimeLaporanEvent(
                'responded',
                'Laporan Dibalas',
                'Laporan Anda "' . $laporan->judul . '" telah ditanggapi oleh Admin.',
                $laporan->user_id,
                $laporan->id
            ));
        } catch (\Throwable $e) {
        }

        return back()->with('success', 'Tanggapan berhasil dikirim dan status laporan diperbarui.');
    }
}
