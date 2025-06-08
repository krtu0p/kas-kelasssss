<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use App\Models\Pemasukan;
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
    public function pengeluaran(Request $request)
    {
        $bulan = $request->input('bulan', Carbon::now()->format('m'));
        $tahun = $request->input('tahun', Carbon::now()->year);

        $pengeluaran = Pengeluaran::where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->get();

        $totalPengeluaran = $pengeluaran->sum('jumlah');

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
            ->orderBy('tahun', 'asc')
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

        return view('pengeluaran', [
            'pengeluaran' => $pengeluaran,
            'totalPengeluaran' => $totalPengeluaran,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'dropdownBulan' => $dropdownBulan,
            'yearRange' => $yearRange,
            'bulanIndo' => $this->bulanIndo,
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

    // Pemasukan
    public function pemasukan(Request $request)
    {
        $bulan = $request->input('bulan', Carbon::now()->format('m'));
        $tahun = $request->input('tahun', Carbon::now()->year);

        $pemasukan = Pemasukan::where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->get();

        $totalPemasukan = $pemasukan->sum('jumlah');

        // Fetch distinct months with data for the selected year
        $dropdownBulan = Pemasukan::select('bulan')
            ->distinct()
            ->where('tahun', $tahun)
            ->pluck('bulan')
            ->mapWithKeys(function ($bulan) {
                return [$bulan => $this->bulanIndo[$bulan]];
            });

        // Fetch distinct years with data
        $yearRange = Pemasukan::select('tahun')
            ->distinct()
            ->orderBy('tahun', 'asc')
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

        return view('pemasukan', [
            'pemasukan' => $pemasukan,
            'totalPemasukan' => $totalPemasukan,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'dropdownBulan' => $dropdownBulan,
            'yearRange' => $yearRange,
            'bulanIndo' => $this->bulanIndo,
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
    $bulan = $request->input('bulan', null); // Null if all months
    $tahun = $request->input('tahun', Carbon::now()->year);

    // Get all students
    $siswa = Siswa::all();

    // Initialize debt data
    $utangData = [];

    foreach ($siswa as $s) {
        $missedPayments = 0;
        // Check all 12 months if no specific month is selected, otherwise check the selected month
        $monthsToCheck = $bulan ? [$bulan] : array_keys($this->bulanIndo);

        foreach ($monthsToCheck as $month) {
            // Check if a payment exists for the student in this month/year
            $paymentExists = Pemasukan::where('nama', 'like', '%' . $s->nama . '%')
                ->where('bulan', $month)
                ->where('tahun', $tahun)
                ->exists();

            if (!$paymentExists) {
                $missedPayments++;
            }
        }

        $totalUtang = $missedPayments * 5000;

        if ($missedPayments > 0) { // Only include students with debts
            $utangData[] = [
                'siswa_id' => $s->id,
                'nama' => $s->nama,
                'missed_payments' => $missedPayments,
                'total_utang' => $totalUtang,
            ];
        }
    }

    // Fetch distinct years with data from Pemasukan
    $yearRange = Pemasukan::select('tahun')
        ->distinct()
        ->orderBy('tahun', 'asc')
        ->pluck('tahun')
        ->toArray();

    if (empty($yearRange)) {
        $yearRange = [Carbon::now()->year];
    }

    // Fetch distinct months with data for the selected year
    $dropdownBulan = Pemasukan::select('bulan')
        ->distinct()
        ->where('tahun', $tahun)
        ->pluck('bulan')
        ->mapWithKeys(function ($bulan) {
            return [$bulan => $this->bulanIndo[$bulan]];
        });

    if ($dropdownBulan->isEmpty() && !$request->has('bulan')) {
        $dropdownBulan = collect($this->bulanIndo);
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