<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SiswaController extends Controller
{
    private $bulanIndo = [
        '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
        '04' => 'April', '05' => 'Mei', '06' => 'Juni',
        '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
        '10' => 'Oktober', '11' => 'November', '12' => 'Desember',
    ];

    public function index(Request $request)
    {
        $bulan = $request->input('bulan', Carbon::now()->format('m'));
        $tahun = $request->input('tahun', Carbon::now()->year);

        $siswas = Siswa::with(['pembayaran' => fn($query) => 
            $query->where('bulan', $bulan)->where('tahun', $tahun)
        ])->get();

        $maxMinggu = Pembayaran::where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->max('minggu') ?? 0;

        $dropdownBulan = Pembayaran::select('bulan', 'tahun')
            ->distinct()
            ->orderByDesc('tahun')
            ->orderByDesc('bulan')
            ->get()
            ->map(fn($item) => [
                'bulan' => $item->bulan,
                'tahun' => $item->tahun,
                'nama' => $this->bulanIndo[$item->bulan] . ' ' . $item->tahun,
            ]);

        // Fallback for empty dropdown
        if ($dropdownBulan->isEmpty()) {
            $dropdownBulan->push([
                'bulan' => Carbon::now()->format('m'),
                'tahun' => Carbon::now()->year,
                'nama' => $this->bulanIndo[Carbon::now()->format('m')] . ' ' . Carbon::now()->year,
            ]);
        }

        return view('siswa', [
            'siswas' => $siswas,
            'maxMinggu' => $maxMinggu,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'dropdownBulan' => $dropdownBulan,
            'bulanIndo' => $this->bulanIndo,
        ]);
    }
}