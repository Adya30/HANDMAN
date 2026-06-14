<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Tugas;
use App\Models\Departemen;
use Illuminate\Http\Request;

class c_adminDashboard extends Controller
{
    public function index()
    {

        $totalPegawai   = User::where('nama_role', '!=', 'admin')->count();
        $totalManager   = User::where('nama_role', 'manager')->count();
        $totalStaff     = User::where('nama_role', 'staff')->count();
        $pegawaiAktif   = User::where('nama_role', '!=', 'admin')->where('is_active', 1)->count();
        $pegawaiNonAktif = User::where('nama_role', '!=', 'admin')->where('is_active', 0)->count();

        $totalTugas       = Tugas::count();
        $tugasSelesai     = Tugas::where('status_tugas', 'Selesai')->count();
        $tugasBerjalan    = Tugas::whereIn('status_tugas', ['Belum Dikerjakan', 'Revisi'])->count();
        $tugasMenunggu    = Tugas::where('status_tugas', 'Menunggu Persetujuan')->count();
        $tugasRevisi      = Tugas::where('status_tugas', 'Revisi')->count();

        $efisiensi = $totalTugas > 0 ? round(($tugasSelesai / $totalTugas) * 100, 1) : 0;

        $totalDepartemen = Departemen::count();

        $departemens = Departemen::withCount([
            'tugas',
            'tugas as tugas_selesai_count' => fn($q) => $q->where('status_tugas', 'Selesai'),
            'tugas as tugas_berjalan_count' => fn($q) => $q->whereIn('status_tugas', ['Belum Dikerjakan', 'Revisi', 'Menunggu Persetujuan']),
        ])->withCount('users')->orderByDesc('tugas_count')->get();

        $tugasTerbaru = Tugas::with('departemen')->latest()->take(5)->get();

        $pegawaiTerbaru = User::with('departemen')
            ->where('nama_role', '!=', 'admin')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalPegawai', 'totalManager', 'totalStaff',
            'pegawaiAktif', 'pegawaiNonAktif',
            'totalTugas', 'tugasSelesai', 'tugasBerjalan',
            'tugasMenunggu', 'tugasRevisi', 'efisiensi',
            'totalDepartemen', 'departemens',
            'tugasTerbaru', 'pegawaiTerbaru'
        ));
    }
}
