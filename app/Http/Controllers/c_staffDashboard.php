<?php

namespace App\Http\Controllers;

use App\Models\Tugas;
use App\Models\GrupKerja;
use App\Models\Laporan;
use Illuminate\Http\Request;

class c_staffDashboard extends Controller
{
    public function index()
    {
        $departemenId = auth()->user()->departemen_id;
        $userId = auth()->id();

        $myGrupIds = GrupKerja::whereHas('anggota', function ($q) use ($userId) {
            $q->where('users.id', $userId);
        })->pluck('id');

        $tugasQuery = Tugas::where('departemen_id', $departemenId)
            ->where(function ($query) use ($userId, $myGrupIds) {
                $query->whereHas('detailTugas', function ($q) use ($userId, $myGrupIds) {
                    $q->where('user_id', $userId)->orWhereIn('grup_kerja_id', $myGrupIds);
                })
                ->orWhereDoesntHave('detailTugas');
            });

        $totalTugas = (clone $tugasQuery)->count();
        $tugasSelesai = (clone $tugasQuery)->where('status_tugas', 'Selesai')->count();
        $tugasPending = (clone $tugasQuery)->where('status_tugas', 'Menunggu Persetujuan')->count();
        $tugasRevisi = (clone $tugasQuery)->where('status_tugas', 'Revisi')->count();
        $tugasBerjalan = (clone $tugasQuery)->whereNotIn('status_tugas', ['Selesai', 'Menunggu Persetujuan'])->count();
        $efisiensi = $totalTugas > 0 ? round(($tugasSelesai / $totalTugas) * 100) : 0;

        $totalGrupSaya = GrupKerja::whereHas('anggota', function($q) use ($userId) {
            $q->where('users.id', $userId);
        })->count();
        $totalLaporanSaya = Laporan::where('user_id', $userId)->count();
        $tugasKelompokSaya = (clone $tugasQuery)->where('kategoritugas', 'Kelompok')->count();
        $tugasIndividuSaya = (clone $tugasQuery)->where('kategoritugas', 'Individu')->count();

        $tugas = (clone $tugasQuery)->latest()->take(5)->get();
        $laporans = Laporan::where('user_id', $userId)->latest()->take(5)->get();

        return view('staff.dashboard', compact(
            'tugas', 'totalTugas', 'tugasSelesai', 'tugasPending', 'tugasRevisi',
            'tugasBerjalan', 'efisiensi', 'totalGrupSaya', 'totalLaporanSaya',
            'tugasKelompokSaya', 'tugasIndividuSaya', 'laporans'
        ));
    }
}
