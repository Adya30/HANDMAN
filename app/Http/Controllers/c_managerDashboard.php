<?php

namespace App\Http\Controllers;

use App\Models\Tugas;
use App\Models\User;
use App\Models\GrupKerja;
use App\Models\Laporan;
use Illuminate\Http\Request;

class c_managerDashboard extends Controller
{
    public function index()
    {
        $departemenId = auth()->user()->departemen_id;

        $totalTugas = Tugas::where('departemen_id', $departemenId)->count();
        $tugasSelesai = Tugas::where('departemen_id', $departemenId)->where('status_tugas', 'Selesai')->count();
        $tugasPending = Tugas::where('departemen_id', $departemenId)->where('status_tugas', 'Menunggu Persetujuan')->count();
        $tugasRevisi = Tugas::where('departemen_id', $departemenId)->where('status_tugas', 'Revisi')->count();
        $tugasBerjalan = Tugas::where('departemen_id', $departemenId)->whereNotIn('status_tugas', ['Selesai', 'Menunggu Persetujuan'])->count();
        $efisiensi = $totalTugas > 0 ? round(($tugasSelesai / $totalTugas) * 100) : 0;

        $staffCount = User::where('departemen_id', $departemenId)->where('nama_role', 'staff')->count();
        $totalGrup = GrupKerja::where('departemen_id', $departemenId)->count();
        $totalLaporan = Laporan::where('user_id', auth()->id())->count();
        $tugasKelompok = Tugas::where('departemen_id', $departemenId)->where('kategoritugas', 'Kelompok')->count();

        $tugas = Tugas::where('departemen_id', $departemenId)->latest()->take(5)->get();

        $laporans = Laporan::where('user_id', auth()->id())->latest()->take(5)->get();

        return view('manager.dashboard', compact(
            'tugas', 'totalTugas', 'tugasSelesai', 'tugasPending', 'tugasRevisi',
            'tugasBerjalan', 'efisiensi', 'staffCount', 'totalGrup', 'totalLaporan',
            'tugasKelompok', 'laporans'
        ));
    }
}
