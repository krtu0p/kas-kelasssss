<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use App\Models\Pemasukan;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Siswa;
use Illuminate\Support\Facades\Log;

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

    // Pengeluaran
    // =================================================================
    // GANTI METHOD 'pengeluaran' ANDA DENGAN YANG INI
    // =================================================================
    public function pengeluaran(Request $request)
    {
        $bulan = $request->input('bulan', Carbon::now()->format('m'));
        $tahun = $request->input('tahun', Carbon::now()->year);

        // PERBAIKAN: Query disederhanakan
        $pengeluaran = Pengeluaran::where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->orderBy('tanggal', 'desc')
            ->get();

        // --- PERBAIKAN: Perhitungan untuk Kartu Statistik (mengambil total keseluruhan) ---
        $totalPemasukan = Pemasukan::sum('jumlah');
        $totalPengeluaran = Pengeluaran::sum('jumlah');
        $totalKas = $totalPemasukan - $totalPengeluaran;
        // --- Akhir Perbaikan ---

        // Logika untuk dropdown filter (sudah baik, tidak perlu diubah)
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

        // PERBAIKAN: Mengirim ke view 'pengeluaran' bukan 'pengeluaran_siswa'
        return view('pengeluaran_siswa', [
            'pengeluaran' => $pengeluaran,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'dropdownBulan' => $dropdownBulan,
            'yearRange' => $yearRange,
            'bulanIndo' => $this->bulanIndo,
            'totalPengeluaran' => $totalPengeluaran,
            'totalPemasukan' => $totalPemasukan,
            'totalKas' => $totalKas,
        ]);
    }

    // ================================================================
    // GANTI METHOD 'pengeluaranStore' ANDA DENGAN YANG INI
    // =================================================================
    public function pengeluaranStore(Request $request)
    {
        // PERBAIKAN: Hapus validasi untuk bulan dan tahun
        $request->validate([
            'nama' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:0',
            'tanggal' => 'required|date',
        ]);

        try {
            // PERBAIKAN: Ambil bulan dan tahun dari tanggal yang diinput
            $tanggal = Carbon::parse($request->tanggal);
            $bulan = $tanggal->format('m');
            $tahun = $tanggal->format('Y');

            Pengeluaran::create([
                'nama' => $request->nama,
                'jumlah' => $request->jumlah,
                'tanggal' => $request->tanggal,
                'bulan' => $bulan, // Gunakan bulan yang sudah diekstrak
                'tahun' => $tahun, // Gunakan tahun yang sudah diekstrak
            ]);

            // Redirect dengan filter bulan dan tahun dari data yang baru dibuat
            return redirect()->route('pengeluaran', ['bulan' => $bulan, 'tahun' => $tahun])
                ->with('success', 'Pengeluaran berhasil ditambahkan!');
        } catch (\Exception $e) {
            Log::error('Pengeluaran store failed: ' . $e->getMessage());
            return back()->with('error', 'Gagal menambahkan pengeluaran.');
        }
    }

    // =================================================================
    // GANTI METHOD 'pengeluaranUpdate' ANDA DENGAN YANG INI
    // =================================================================
    public function pengeluaranUpdate(Request $request, $id)
    {
        $pengeluaran = Pengeluaran::findOrFail($id);

        // PERBAIKAN: Hapus validasi untuk bulan dan tahun
        $request->validate([
            'nama' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:0',
            'tanggal' => 'required|date',
        ]);

        try {
            // PERBAIKAN: Ambil bulan dan tahun dari tanggal yang diinput
            $tanggal = Carbon::parse($request->tanggal);
            $bulan = $tanggal->format('m');
            $tahun = $tanggal->format('Y');

            $pengeluaran->update([
                'nama' => $request->nama,
                'jumlah' => $request->jumlah,
                'tanggal' => $request->tanggal,
                'bulan' => $bulan, // Gunakan bulan yang sudah diekstrak
                'tahun' => $tahun, // Gunakan tahun yang sudah diekstrak
            ]);

            // Redirect dengan filter bulan dan tahun dari data yang baru diupdate
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

    // =================================================================
    // GANTI METHOD 'pemasukan' ANDA DENGAN YANG INI
    // =================================================================
    public function pemasukan(Request $request)
    {
        $bulan = $request->input('bulan', Carbon::now()->format('m'));
        $tahun = $request->input('tahun', Carbon::now()->year);

        $pemasukan = Pemasukan::where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->orderBy('tanggal', 'desc')
            ->get();

        // --- PERBAIKAN: Perhitungan untuk Kartu Statistik (mengambil total keseluruhan) ---
        $totalPemasukan = Pemasukan::sum('jumlah');
        $totalPengeluaran = Pengeluaran::sum('jumlah');
        $totalKas = $totalPemasukan - $totalPengeluaran;
        // --- Akhir Perbaikan ---

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

        // PERBAIKAN: Mengirim ke view 'pemasukan' bukan 'pemasukan_siswa'
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

    // =================================================================
    // GANTI METHOD 'pemasukanStore' ANDA DENGAN YANG INI
    // =================================================================
    public function pemasukanStore(Request $request)
    {
        // PERBAIKAN: Hapus validasi untuk bulan dan tahun
        $request->validate([
            'nama' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:0',
            'tanggal' => 'required|date',
        ]);

        try {
            // PERBAIKAN: Ambil bulan dan tahun dari tanggal yang diinput
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


    // =================================================================
    // GANTI METHOD 'pemasukanUpdate' ANDA DENGAN YANG INI
    // =================================================================
    public function pemasukanUpdate(Request $request, $id)
    {
        $pemasukan = Pemasukan::findOrFail($id);

        // PERBAIKAN: Hapus validasi untuk bulan dan tahun
        $request->validate([
            'nama' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:0',
            'tanggal' => 'required|date',
        ]);

        try {
            // PERBAIKAN: Ambil bulan dan tahun dari tanggal yang diinput
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

    // GANTI SELURUH METHOD INI DI KeuanganController.php
    public function utang(Request $request)
    {
        $bulan = $request->input('bulan', null); // Null for all months
        $tahun = $request->input('tahun', Carbon::now()->year);

        // --- DITAMBAHKAN: Perhitungan untuk Kartu Statistik ---
        // Kita akan hitung total keseluruhan, tidak terpengaruh filter bulan/tahun
        // agar kartu statistik menunjukkan gambaran umum keuangan.
        $totalPemasukan = Pemasukan::sum('jumlah');
        $totalPengeluaran = Pengeluaran::sum('jumlah');
        $totalKas = $totalPemasukan - $totalPengeluaran;
        // --- Akhir Penambahan ---

        // Get all students
        $siswa = Siswa::all();

        // Initialize debt data
        $utangData = [];

        foreach ($siswa as $s) {
            $missedPayments = 0;
            $monthsToCheck = $bulan ? [$bulan] : array_keys($this->bulanIndo);

            foreach ($monthsToCheck as $month) {
                // Get max weeks for this month/year
                $maxMinggu = Pembayaran::where('bulan', $month)
                    ->where('tahun', $tahun)
                    ->max('minggu') ?? 0;

                for ($week = 1; $week <= $maxMinggu; $week++) {
                    $payment = Pembayaran::where('siswa_id', $s->id)
                        ->where('bulan', $month)
                        ->where('tahun', $tahun)
                        ->where('minggu', $week)
                        ->first();

                    // Count as missed if no record exists or status is false
                    if (!$payment || !$payment->status) {
                        $missedPayments++;
                    }
                }
            }

            $totalUtang = $missedPayments * 5000;

            if ($missedPayments > 0) {
                $utangData[] = [
                    'siswa_id' => $s->id,
                    'nama' => $s->nama,
                    'missed_payments' => $missedPayments,
                    'total_utang' => $totalUtang,
                ];
            }
        }

        // Fetch distinct months and years from Pembayaran
        $dropdownBulan = Pembayaran::select('bulan', 'tahun')
            ->distinct()
            ->orderByDesc('tahun')
            ->orderByDesc('bulan')
            ->get()
            ->map(function ($item) {
                return [
                    'bulan' => $item->bulan,
                    'tahun' => $item->tahun,
                    'nama' => $this->bulanIndo[$item->bulan] ?? 'N/A',
                ];
            });

        $yearRange = $dropdownBulan->pluck('tahun')->unique()->sortDesc()->values();

        if (empty($yearRange->toArray())) {
            $yearRange = [Carbon::now()->year];
        }

        if ($dropdownBulan->isEmpty() && !$request->has('bulan')) {
            $dropdownBulan = collect($this->bulanIndo)->map(function ($nama, $bulan) {
                return ['bulan' => $bulan, 'nama' => $nama, 'tahun' => Carbon::now()->year];
            });
        }

        return view('utang', [
            'utangData' => $utangData,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'dropdownBulan' => $dropdownBulan,
            'yearRange' => $yearRange,
            'bulanIndo' => $this->bulanIndo,

            // Variabel BARU untuk kartu statistik
            'totalPemasukan' => $totalPemasukan,
            'totalPengeluaran' => $totalPengeluaran,
            'totalKas' => $totalKas,
        ]);
    }
}
