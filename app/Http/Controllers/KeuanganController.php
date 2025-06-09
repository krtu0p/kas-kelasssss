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
        '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
        '04' => 'April', '05' => 'Mei', '06' => 'Juni',
        '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
        '10' => 'Oktober', '11' => 'November', '12' => 'Desember',
    ];

    // Pengeluaran
       // GANTI SELURUH METHOD INI DI KeuanganController.php
    public function pengeluaran(Request $request)
    {
        $bulan = $request->input('bulan', Carbon::now()->format('m'));
        $tahun = $request->input('tahun', Carbon::now()->year);

        $pengeluaran = Pengeluaran::where('bulan', $bulan)
            ->where('tahun', '>=', $tahun) // Perbaikan: seharusnya where('tahun', $tahun)
            ->where('tahun', $tahun)
            ->orderBy('tanggal', 'desc') // Mengurutkan agar data terbaru di atas
            ->get();

        // --- Perhitungan untuk Kartu Statistik ---
        // 1. Hitung total pengeluaran (sudah ada)
        $totalPengeluaran = $pengeluaran->sum('jumlah');

        // 2. DITAMBAHKAN: Hitung total pemasukan untuk periode yang sama
        $totalPemasukan = Pemasukan::where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->sum('jumlah');

        // 3. DITAMBAHKAN: Hitung total kas (saldo)
        $totalKas = $totalPemasukan - $totalPengeluaran;
        // --- Akhir Perhitungan Kartu Statistik ---


        // Fetch distinct months with data for the selected year
        $dropdownBulan = Pengeluaran::select('bulan')
            ->distinct()
            ->where('tahun', $tahun)
            ->pluck('bulan')
            ->mapWithKeys(function ($bulan) {
                return [$bulan => $this->bulanIndo[$bulan]];
            });

        // Fetch distinct years with data
        $yearRange = Pengeluaran::select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc') // Diubah ke desc agar tahun terbaru di atas
            ->pluck('tahun')
            ->toArray();

        // If no years with data, default to current year
        if (empty($yearRange)) {
            $yearRange = [Carbon::now()->year];
        }

        // If no data for the selected year, set a default month (e.g., current month) if no selection
        if ($dropdownBulan->isEmpty() && !$request->has('bulan')) {
            $bulan = Carbon::now()->format('m');
            $dropdownBulan = collect([$bulan => $this->bulanIndo[$bulan]]);
        }

        // --- Kirim semua data yang dibutuhkan ke view ---
        return view('pengeluaran_siswa', [
            'pengeluaran' => $pengeluaran,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'dropdownBulan' => $dropdownBulan,
            'yearRange' => $yearRange,
            'bulanIndo' => $this->bulanIndo,
            
            // Variabel BARU untuk kartu statistik
            'totalPengeluaran' => $totalPengeluaran,
            'totalPemasukan' => $totalPemasukan,
            'totalKas' => $totalKas,
        ]);
    }

    public function pengeluaranCreate()
    {
        return view('pengeluaran_create', ['bulanIndo' => $this->bulanIndo]);
    }

    public function pengeluaranStore(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:0',
            'tanggal' => 'required|date',
            'bulan' => 'required|digits:2',
            'tahun' => 'required|digits:4',
        ]);

        try {
            Pengeluaran::create([
                'nama' => $request->nama,
                'jumlah' => $request->jumlah,
                'tanggal' => $request->tanggal,
                'bulan' => $request->bulan,
                'tahun' => $request->tahun,
            ]);

            return redirect()->route('pengeluaran')->with('success', 'Pengeluaran berhasil ditambahkan!');
        } catch (\Exception $e) {
            Log::error('Pengeluaran store failed: ' . $e->getMessage());
            return back()->with('error', 'Gagal menambahkan pengeluaran.');
        }
    }

    public function pengeluaranEdit($id)
    {
        $pengeluaran = Pengeluaran::findOrFail($id);
        return view('pengeluaran_edit', [
            'pengeluaran' => $pengeluaran,
            'bulanIndo' => $this->bulanIndo,
        ]);
    }

    public function pengeluaranUpdate(Request $request, $id)
    {
        \Log::info('pengeluaranUpdate called with data: ', $request->all());

        $pengeluaran = Pengeluaran::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:0',
            'tanggal' => 'required|date',
            'bulan' => 'required|digits:2',
            'tahun' => 'required|digits:4',
        ]);

        try {
            \Log::info('Validation passed, updating Pengeluaran record');
            $pengeluaran->update([
                'nama' => $request->nama,
                'jumlah' => $request->jumlah,
                'tanggal' => $request->tanggal,
                'bulan' => $request->bulan,
                'tahun' => $request->tahun,
            ]);

            \Log::info('Record updated with ID: ' . $pengeluaran->id);
            return redirect()->route('pengeluaran')->with('success', 'Pengeluaran berhasil diperbarui!');
        } catch (\Exception $e) {
            \Log::error('Pengeluaran update failed: ' . $e->getMessage());
            return back()->with('error', 'Gagal memperbarui pengeluaran. Error: ' . $e->getMessage())->withInput();
        }
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

    // Method Pemasukan yang menjadi pusat data untuk halaman Pemasukan Kas
    public function pemasukan(Request $request)
    {
        // --- Langkah 1: Ambil filter bulan dan tahun ---
        $bulan = $request->input('bulan', Carbon::now()->format('m'));
        $tahun = $request->input('tahun', Carbon::now()->year);

        // --- Langkah 2: Ambil daftar pemasukan sesuai filter ---
        $pemasukan = Pemasukan::where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->orderBy('tanggal', 'desc') // Diurutkan agar data terbaru di atas
            ->get();

        // --- Langkah 3: Hitung data untuk kartu statistik ---
        $totalPemasukan = $pemasukan->sum('jumlah');
        
        // DITAMBAHKAN: Ambil juga total pengeluaran untuk periode yang sama
        $totalPengeluaran = Pengeluaran::where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->sum('jumlah');
            
        // DITAMBAHKAN: Hitung total kas (saldo)
        $totalKas = $totalPemasukan - $totalPengeluaran;

        // --- Langkah 4: Siapkan data untuk dropdown filter ---
        // (Logika ini hampir sama dengan kode asli Anda, hanya sedikit penyederhanaan)
        $dropdownBulan = Pemasukan::select('bulan')
            ->distinct()
            ->where('tahun', $tahun)
            ->pluck('bulan')
            ->sort()
            ->mapWithKeys(fn ($b) => [$b => $this->bulanIndo[$b] ?? 'Bulan Tidak Valid']);

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

        // --- Langkah 5: DITAMBAHKAN - Siapkan data untuk Chart.js ---
        // (Ini adalah logika yang kita "pinjam" dari ChartController)
        $chartLabels = ['Pemasukan', 'Pengeluaran', 'Total'];
        $chartPemasukan = [$totalPemasukan];
        $chartPengeluaran = [$totalPengeluaran];
        $chartTotalKas = [$totalKas];

        // --- Langkah 6: Kirim semua data yang dibutuhkan ke view ---
        return view('pemasukan_siswa', [ // Pastikan nama view sudah benar
            // Data untuk daftar & filter
            'pemasukan' => $pemasukan,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'dropdownBulan' => $dropdownBulan,
            'yearRange' => $yearRange,
            'bulanIndo' => $this->bulanIndo,
            
            // Data untuk kartu statistik
            'totalPemasukan' => $totalPemasukan,
            'totalPengeluaran' => $totalPengeluaran,
            'totalKas' => $totalKas,

            // Data untuk chart
            'labels' => $chartLabels,
            'pemasukanData' => $chartPemasukan,
            'pengeluaranData' => $chartPengeluaran,
            'totalKasData' => $chartTotalKas,
        ]);
    }

    public function pemasukanCreate()
    {
        return view('pemasukan_create', ['bulanIndo' => $this->bulanIndo]);
    }

    public function pemasukanStore(Request $request)
    {
        \Log::info('pemasukanStore called with data: ', $request->all());

        $request->validate([
            'nama' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:0',
            'tanggal' => 'required|date',
            'bulan' => 'required|digits:2',
            'tahun' => 'required|digits:4',
        ]);

        try {
            \Log::info('Validation passed, creating Pemasukan record');
            $pemasukan = Pemasukan::create([
                'nama' => $request->nama,
                'jumlah' => $request->jumlah,
                'tanggal' => $request->tanggal,
                'bulan' => $request->bulan,
                'tahun' => $request->tahun,
            ]);

            \Log::info('Record created with ID: ' . $pemasukan->id);
            return redirect()->route('pemasukan')->with('success', 'Pemasukan berhasil ditambahkan!');
        } catch (\Exception $e) {
            \Log::error('Pemasukan store failed: ' . $e->getMessage());
            return back()->with('error', 'Gagal menambahkan pemasukan. Error: ' . $e->getMessage())->withInput();
        }
    }

    public function pemasukanEdit($id)
    {
        $pemasukan = Pemasukan::findOrFail($id);
        return view('pemasukan_edit', [
            'pemasukan' => $pemasukan,
            'bulanIndo' => $this->bulanIndo,
        ]);
    }

    public function pemasukanUpdate(Request $request, $id)
    {
        \Log::info('pemasukanUpdate called with data: ', $request->all());

        $pemasukan = Pemasukan::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:0',
            'tanggal' => 'required|date',
            'bulan' => 'required|digits:2',
            'tahun' => 'required|digits:4',
        ]);

        try {
            \Log::info('Validation passed, updating Pemasukan record');
            $pemasukan->update([
                'nama' => $request->nama,
                'jumlah' => $request->jumlah,
                'tanggal' => $request->tanggal,
                'bulan' => $request->bulan,
                'tahun' => $request->tahun,
            ]);

            \Log::info('Record updated with ID: ' . $pemasukan->id);
            return redirect()->route('pemasukan')->with('success', 'Pemasukan berhasil diperbarui!');
        } catch (\Exception $e) {
            \Log::error('Pemasukan update failed: ' . $e->getMessage());
            return back()->with('error', 'Gagal memperbarui pemasukan. Error: ' . $e->getMessage())->withInput();
        }
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
    // app/Http/Controllers/KeuanganController.php
public function utang(Request $request)
    {
        $bulan = $request->input('bulan', null); // Null for all months
        $tahun = $request->input('tahun', Carbon::now()->year);

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
                    'nama' => $this->bulanIndo[$item->bulan],
                ];
            });

        $yearRange = $dropdownBulan->pluck('tahun')->unique()->sortDesc()->values();

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
        ]);
    }
}