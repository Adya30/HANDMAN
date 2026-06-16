<?php

namespace App\Http\Controllers;

use App\Models\Tugas;
use App\Models\Departemen;
use Illuminate\Http\Request;

class c_adminTugas extends Controller
{
    public function index(Request $request)
    {
        $departemens = Departemen::orderBy('nama_departemen')->get();

        $query = Tugas::with('departemen')
            ->orderBy('tanggal_tugas', 'desc')
            ->orderBy('created_at', 'desc');

        if ($request->filled('departemen_id')) {
            $query->where('departemen_id', $request->departemen_id);
        }

        if ($request->filled('status')) {
            $query->where('status_tugas', $request->status);
        }

        if ($request->filled('prioritas')) {
            $query->where('prioritas', $request->prioritas);
        }

        if ($request->filled('kategori')) {
            $query->where('kategoritugas', $request->kategori);
        }

        $tugas = $query->get();

        $totalTugas      = $tugas->count();
        $tugasSelesai    = $tugas->where('status_tugas', 'Selesai')->count();
        $tugasBerjalan   = $tugas->whereIn('status_tugas', ['Belum Dikerjakan', 'Revisi'])->count();
        $tugasMenunggu   = $tugas->where('status_tugas', 'Menunggu Persetujuan')->count();

        return view('admin.tugas.index', compact(
            'tugas',
            'departemens',
            'totalTugas',
            'tugasSelesai',
            'tugasBerjalan',
            'tugasMenunggu'
        ));
    }

    public function exportPdf(Request $request)
    {
        $query = Tugas::with('departemen')
            ->orderBy('tanggal_tugas', 'desc')
            ->orderBy('created_at', 'desc');

        if ($request->filled('departemen_id')) {
            $query->where('departemen_id', $request->departemen_id);
            $departemen = Departemen::find($request->departemen_id);
            $departemenName = $departemen ? $departemen->nama_departemen : 'Tidak Diketahui';
        } else {
            $departemenName = 'Semua Departemen';
        }

        if ($request->filled('status')) {
            $query->where('status_tugas', $request->status);
        }

        if ($request->filled('prioritas')) {
            $query->where('prioritas', $request->prioritas);
        }

        if ($request->filled('kategori')) {
            $query->where('kategoritugas', $request->kategori);
        }

        $tugasList = $query->get();

        $totalTugas      = $tugasList->count();
        $tugasSelesai    = $tugasList->where('status_tugas', 'Selesai')->count();
        $tugasBerjalan   = $tugasList->whereIn('status_tugas', ['Belum Dikerjakan', 'Revisi'])->count();
        $tugasMenunggu   = $tugasList->where('status_tugas', 'Menunggu Persetujuan')->count();

        $currentEfficiency = $totalTugas > 0 ? round(($tugasSelesai / $totalTugas) * 100) : 0;
        
        $cutoffDate = now()->subDays(14);
        $pastTugas = $tugasList->filter(function($t) use ($cutoffDate) {
            return \Carbon\Carbon::parse($t->tanggal_tugas)->lt($cutoffDate);
        });
        $recentTugas = $tugasList->filter(function($t) use ($cutoffDate) {
            return \Carbon\Carbon::parse($t->tanggal_tugas)->gte($cutoffDate);
        });

        $pastTotal = $pastTugas->count();
        $pastSelesai = $pastTugas->where('status_tugas', 'Selesai')->count();
        $pastEfficiency = $pastTotal > 0 ? round(($pastSelesai / $pastTotal) * 100) : null;

        $recentTotal = $recentTugas->count();
        $recentSelesai = $recentTugas->where('status_tugas', 'Selesai')->count();
        $recentEfficiency = $recentTotal > 0 ? round(($recentSelesai / $recentTotal) * 100) : null;

        if ($pastEfficiency !== null && $recentEfficiency !== null) {
            $change = $recentEfficiency - $pastEfficiency;
        } elseif ($recentEfficiency !== null) {
            $change = $recentEfficiency - 75;
        } else {
            $change = 0;
        }

        $filters = [];
        if ($request->filled('status')) {
            $filters[] = 'Status: ' . $request->status;
        }
        if ($request->filled('prioritas')) {
            $filters[] = 'Prioritas: ' . $request->prioritas;
        }
        if ($request->filled('kategori')) {
            $filters[] = 'Kategori: ' . ($request->kategori === 'Kelompok' ? 'Departemen' : $request->kategori);
        }
        $kategoriFilter = count($filters) > 0 ? implode(', ', $filters) : 'Semua';

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('exports.tugas-pdf', compact(
            'tugasList',
            'departemenName',
            'kategoriFilter',
            'totalTugas',
            'tugasSelesai',
            'tugasBerjalan',
            'tugasMenunggu',
            'currentEfficiency',
            'change'
        ));
        return $pdf->download('Laporan_Monitoring_Tugas_' . now()->format('YmdHis') . '.pdf');
    }
}
