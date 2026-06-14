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

        $query = Tugas::with('departemen')->latest();

        
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

        
        if ($request->filled('search')) {
            $query->where('nama_tugas', 'like', '%' . $request->search . '%');
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
}
