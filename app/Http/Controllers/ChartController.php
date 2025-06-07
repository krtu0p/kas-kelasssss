<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use Carbon\Carbon;

class ChartController extends Controller
{
    public function index(Request $request)
    {
        // Ambil bulan & tahun dari request, default ke bulan & tahun sekarang
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));

        // Ambil data pemasukan dan pengeluaran untuk bulan & tahun tertentu
        $pemasukan = Pemasukan::where('bulan', $bulan)->where('tahun', $tahun)->get();
        $pengeluaran = Pengeluaran::where('bulan', $bulan)->where('tahun', $tahun)->get();

        // Hitung total
        $totalPemasukan = $pemasukan->sum('jumlah');
        $totalPengeluaran = $pengeluaran->sum('jumlah');
        $totalKas = $totalPemasukan - $totalPengeluaran;

        // Kirimkan ke view dalam bentuk satu titik data (untuk bar chart vertikal 1 bulan)
        return view('chart', [
            'labels' => ['Pemasukan', 'Pengeluaran', 'Total Kas'],
            'pemasukanData' => [$totalPemasukan],
            'pengeluaranData' => [$totalPengeluaran],
            'totalKasData' => [$totalKas],
            'selectedBulan' => $bulan,
            'selectedTahun' => $tahun,
        ]);
    }
}
