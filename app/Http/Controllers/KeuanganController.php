<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use App\Models\Pemasukan;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Siswa;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class KeuanganController extends Controller
{
    private $bulanIndo = [
        '01' => 'Januari',
        '02' => 'Februari',
        '03' => 'Maret',
        '04' => 'April',
        '05' => 'Mei',
        '06' => 'Juni',
        '07' => 'Juli',
        '08' => 'Agustus',
        '09' => 'September',
        '10' => 'Oktober',
        '11' => 'November',
        '12' => 'Desember',
    ];


    public function pengeluaran(Request $request)
    {
        $bulan = $request->input('bulan', Carbon::now()->format('m'));
        $tahun = $request->input('tahun', Carbon::now()->year);

        $pengeluaran = Pengeluaran::where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->orderBy('tanggal', 'desc')
            ->get();

        $totalPemasukan = Pemasukan::where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->sum('jumlah');
        $totalPengeluaran = Pengeluaran::where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->sum('jumlah');
        $totalKas = $totalPemasukan - $totalPengeluaran;

        $dropdownBulan = Pengeluaran::select('bulan')
            ->distinct()
            ->where('tahun', $tahun)
            ->pluck('bulan')
            ->mapWithKeys(function ($bulan) {
                return [$bulan => $this->bulanIndo[$bulan]];
            });

        $yearRange = Pengeluaran::select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun')
            ->toArray();

        if (empty($yearRange)) {
            $yearRange = [Carbon::now()->year];
        }

        if ($dropdownBulan->isEmpty() && !$request->has('bulan')) {
            $dropdownBulan = collect([$bulan => $this->bulanIndo[$bulan]]);
        }

        return view('pengeluaran_siswa', [
            'pengeluaran' => $pengeluaran,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'dropdownBulan' => $dropdownBulan,
            'yearRange' => $yearRange,
            'bulanIndo' => $this->bulanIndo,
            'totalPemasukan' => $totalPemasukan,
            'totalPengeluaran' => $totalPengeluaran,
            'totalKas' => $totalKas,
        ]);
    }

    public function pengeluaranStore(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:0',
            'tanggal' => 'required|date',
        ]);

        try {
            $tanggal = Carbon::parse($request->tanggal);
            $bulan = $tanggal->format('m');
            $tahun = $tanggal->format('Y');

            Pengeluaran::create([
                'nama' => $request->nama,
                'jumlah' => $request->jumlah,
                'tanggal' => $request->tanggal,
                'bulan' => $bulan,
                'tahun' => $tahun,
            ]);

            return redirect()->route('pengeluaran', ['bulan' => $bulan, 'tahun' => $tahun])
                ->with('success', 'Pengeluaran berhasil ditambahkan!');
        } catch (\Exception $e) {
            Log::error('Pengeluaran store failed: ' . $e->getMessage());
            return back()->with('error', 'Gagal menambahkan pengeluaran.');
        }
    }

    public function pengeluaranUpdate(Request $request, $id)
    {
        $pengeluaran = Pengeluaran::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:0',
            'tanggal' => 'required|date',
        ]);

        try {
            $tanggal = Carbon::parse($request->tanggal);
            $bulan = $tanggal->format('m');
            $tahun = $tanggal->format('Y');

            $pengeluaran->update([
                'nama' => $request->nama,
                'jumlah' => $request->jumlah,
                'tanggal' => $request->tanggal,
                'bulan' => $bulan,
                'tahun' => $tahun,
            ]);

            return redirect()->route('pengeluaran', ['bulan' => $bulan, 'tahun' => $tahun])
                ->with('success', 'Pengeluaran berhasil diperbarui!');
        } catch (\Exception $e) {
            Log::error('Pengeluaran update failed: ' . $e->getMessage());
            return back()->with('error', 'Gagal memperbarui pengeluaran.')->withInput();
        }
    }

    public function pengeluaranCreate()
    {
        return view('pengeluaran_create', ['bulanIndo' => $this->bulanIndo]);
    }

    public function pengeluaranEdit($id)
    {
        $pengeluaran = Pengeluaran::findOrFail($id);
        return view('pengeluaran_edit', [
            'pengeluaran' => $pengeluaran,
            'bulanIndo' => $this->bulanIndo,
        ]);
    }

    public function pengeluaranDestroy($id)
    {
        try {
            $pengeluaran = Pengeluaran::findOrFail($id);
            $pengeluaran->delete();

            return redirect()->route('pengeluaran')->with('success', 'Pengeluaran berhasil dihapus!');
        } catch (\Exception $e) {
            Log::error('Pengeluaran destroy failed: ' . $e->getMessage());
            return back()->with('error', 'Gagal menghapus pengeluaran.');
        }
    }

    public function pemasukan(Request $request)
    {
        $bulan = $request->input('bulan', Carbon::now()->format('m'));
        $tahun = $request->input('tahun', Carbon::now()->year);

        $pemasukan = Pemasukan::where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->orderBy('tanggal', 'desc')
            ->get();

        $totalPemasukan = Pemasukan::where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->sum('jumlah');
        $totalPengeluaran = Pengeluaran::where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->sum('jumlah');
        $totalKas = $totalPemasukan - $totalPengeluaran;

        $dropdownBulan = Pemasukan::select('bulan')
            ->distinct()
            ->where('tahun', $tahun)
            ->pluck('bulan')
            ->sort()
            ->mapWithKeys(fn($b) => [$b => $this->bulanIndo[$b] ?? 'Bulan Tidak Valid']);

        $yearRange = Pemasukan::select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun')
            ->toArray();

        if (empty($yearRange)) {
            $yearRange = [Carbon::now()->year];
        }
        if ($dropdownBulan->isEmpty() && !$request->has('bulan')) {
            $dropdownBulan = collect([$bulan => $this->bulanIndo[$bulan]]);
        }

        return view('pemasukan_siswa', [
            'pemasukan' => $pemasukan,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'dropdownBulan' => $dropdownBulan,
            'yearRange' => $yearRange,
            'bulanIndo' => $this->bulanIndo,
            'totalPemasukan' => $totalPemasukan,
            'totalPengeluaran' => $totalPengeluaran,
            'totalKas' => $totalKas,
        ]);
    }

    public function pemasukanStore(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:0',
            'tanggal' => 'required|date',
        ]);

        try {
            $tanggal = Carbon::parse($request->tanggal);
            $bulan = $tanggal->format('m');
            $tahun = $tanggal->format('Y');

            Pemasukan::create([
                'nama' => $request->nama,
                'jumlah' => $request->jumlah,
                'tanggal' => $request->tanggal,
                'bulan' => $bulan,
                'tahun' => $tahun,
            ]);

            return redirect()->route('pemasukan', ['bulan' => $bulan, 'tahun' => $tahun])
                ->with('success', 'Pemasukan berhasil ditambahkan!');
        } catch (\Exception $e) {
            Log::error('Pemasukan store failed: ' . $e->getMessage());
            return back()->with('error', 'Gagal menambahkan pemasukan.');
        }
    }

    public function pemasukanUpdate(Request $request, $id)
    {
        $pemasukan = Pemasukan::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:0',
            'tanggal' => 'required|date',
        ]);

        try {
            $tanggal = Carbon::parse($request->tanggal);
            $bulan = $tanggal->format('m');
            $tahun = $tanggal->format('Y');

            $pemasukan->update([
                'nama' => $request->nama,
                'jumlah' => $request->jumlah,
                'tanggal' => $request->tanggal,
                'bulan' => $bulan,
                'tahun' => $tahun,
            ]);

            return redirect()->route('pemasukan', ['bulan' => $bulan, 'tahun' => $tahun])
                ->with('success', 'Pemasukan berhasil diperbarui!');
        } catch (\Exception $e) {
            Log::error('Pemasukan update failed: ' . $e->getMessage());
            return back()->with('error', 'Gagal memperbarui pemasukan.')->withInput();
        }
    }

    public function pemasukanCreate()
    {
        return view('pemasukan_create', ['bulanIndo' => $this->bulanIndo]);
    }

    public function pemasukanEdit($id)
    {
        $pemasukan = Pemasukan::findOrFail($id);
        return view('pemasukan_edit', [
            'pemasukan' => $pemasukan,
            'bulanIndo' => $this->bulanIndo,
        ]);
    }

    public function pemasukanDestroy($id)
    {
        try {
            $pemasukan = Pemasukan::findOrFail($id);
            $pemasukan->delete();

            return redirect()->route('pemasukan')->with('success', 'Pemasukan berhasil dihapus!');
        } catch (\Exception $e) {
            Log::error('Pemasukan destroy failed: ' . $e->getMessage());
            return back()->with('error', 'Gagal menghapus pemasukan.');
        }
    }

    // Ganti fungsi utangSiswa() yang ada dengan versi ini
    public function utangSiswa(Request $request)
    {
        $bulan = $request->input('bulan', null);
        $tahun = $request->input('tahun', Carbon::now()->year);

        // Variabel untuk stat-cards (total keseluruhan)
        $totalPemasukan = Pemasukan::sum('jumlah');
        $totalPengeluaran = Pengeluaran::sum('jumlah');
        $totalKas = $totalPemasukan - $totalPengeluaran;

        // Logika untuk menghitung utang
        $siswa = Siswa::all();
        $utangData = [];

        foreach ($siswa as $s) {
            $query = Pembayaran::where('siswa_id', $s->id)->where('tahun', $tahun);
            if ($bulan) {
                $query->where('bulan', $bulan);
            }

            $payments = $query->get()->keyBy(function ($item) {
                return $item->bulan . '-' . $item->minggu;
            });

            $missedPayments = 0;
            $monthsToCheck = $bulan ? [$bulan] : array_keys($this->bulanIndo);

            foreach ($monthsToCheck as $month) {
                $maxMinggu = Pembayaran::where('bulan', $month)->where('tahun', $tahun)->max('minggu') ?? 0;
                for ($week = 1; $week <= $maxMinggu; $week++) {
                    $paymentKey = $month . '-' . $week;
                    if (!isset($payments[$paymentKey]) || !$payments[$paymentKey]->status) {
                        $missedPayments++;
                    }
                }
            }

            $totalUtang = $missedPayments * 5000;
            if ($totalUtang > 0) {
                $utangData[] = [
                    'nama' => $s->nama,
                    'missed_payments' => $missedPayments, // Ditambahkan kembali
                    'total_utang' => $totalUtang,
                ];
            }
        }

        usort($utangData, fn($a, $b) => $b['total_utang'] <=> $a['total_utang']);

        // Variabel untuk filter dropdown
        $dropdownBulan = Pembayaran::select('bulan')->distinct()->where('tahun', $tahun)->pluck('bulan')->sort()->mapWithKeys(fn($b) => [$b => $this->bulanIndo[$b]]);
        $yearRange = Pembayaran::select('tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun');

        return view('utang_siswa', [
            'utangData' => $utangData,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'dropdownBulan' => $dropdownBulan,
            'yearRange' => $yearRange,
            'bulanIndo' => $this->bulanIndo,
            'totalPemasukan' => $totalPemasukan,
            'totalPengeluaran' => $totalPengeluaran,
            'totalKas' => $totalKas,
        ]);
    }

    // Ganti fungsi utangAdmin() yang ada dengan versi ini
    public function utangAdmin(Request $request)
    {
        // Logikanya sama persis dengan utangSiswa, hanya view-nya yang berbeda
        $bulan = $request->input('bulan', null);
        $tahun = $request->input('tahun', Carbon::now()->year);

        $totalPemasukan = Pemasukan::sum('jumlah');
        $totalPengeluaran = Pengeluaran::sum('jumlah');
        $totalKas = $totalPemasukan - $totalPengeluaran;

        $siswa = Siswa::all();
        $utangData = [];

        foreach ($siswa as $s) {
            $query = Pembayaran::where('siswa_id', $s->id)->where('tahun', $tahun);
            if ($bulan) {
                $query->where('bulan', $bulan);
            }

            $payments = $query->get()->keyBy(fn($item) => $item->bulan . '-' . $item->minggu);

            $missedPayments = 0;
            $monthsToCheck = $bulan ? [$bulan] : array_keys($this->bulanIndo);

            foreach ($monthsToCheck as $month) {
                $maxMinggu = Pembayaran::where('bulan', $month)->where('tahun', $tahun)->max('minggu') ?? 0;
                for ($week = 1; $week <= $maxMinggu; $week++) {
                    $paymentKey = $month . '-' . $week;
                    if (!isset($payments[$paymentKey]) || !$payments[$paymentKey]->status) {
                        $missedPayments++;
                    }
                }
            }

            $totalUtang = $missedPayments * 5000;
            if ($totalUtang > 0) {
                $utangData[] = [
                    'nama' => $s->nama,
                    'missed_payments' => $missedPayments, // Ditambahkan kembali
                    'total_utang' => $totalUtang,
                ];
            }
        }

        usort($utangData, fn($a, $b) => $b['total_utang'] <=> $a['total_utang']);

        $dropdownBulan = Pembayaran::select('bulan')->distinct()->where('tahun', $tahun)->pluck('bulan')->sort()->mapWithKeys(fn($b) => [$b => $this->bulanIndo[$b]]);
        $yearRange = Pembayaran::select('tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun');

        // Mengarah ke view 'utang_admin'
        return view('utang_admin', [
            'utangData' => $utangData,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'dropdownBulan' => $dropdownBulan,
            'yearRange' => $yearRange,
            'bulanIndo' => $this->bulanIndo,
            'totalPemasukan' => $totalPemasukan,
            'totalPengeluaran' => $totalPengeluaran,
            'totalKas' => $totalKas,
        ]);
    }
}
