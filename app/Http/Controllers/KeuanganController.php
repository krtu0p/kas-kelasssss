<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use App\Models\Pemasukan;
use Illuminate\Http\Request;
use Carbon\Carbon;
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

        $dropdownBulan = Pengeluaran::select('bulan', 'tahun')
            ->distinct()
            ->orderByDesc('tahun')
            ->orderByDesc('bulan')
            ->get()
            ->map(fn($item) => [
                'bulan' => $item->bulan,
                'tahun' => $item->tahun,
                'nama' => $this->bulanIndo[$item->bulan] . ' ' . $item->tahun,
            ]);

        if ($dropdownBulan->isEmpty()) {
            $dropdownBulan->push([
                'bulan' => Carbon::now()->format('m'),
                'tahun' => Carbon::now()->year,
                'nama' => $this->bulanIndo[Carbon::now()->format('m')] . ' ' . Carbon::now()->year,
            ]);
        }

        return view('pengeluaran', [
            'pengeluaran' => $pengeluaran,
            'totalPengeluaran' => $totalPengeluaran,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'dropdownBulan' => $dropdownBulan,
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
        $request->validate([
            'nama' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:0',
            'tanggal' => 'required|date',
            'bulan' => 'required|digits:2',
            'tahun' => 'required|digits:4',
        ]);

        try {
            $pengeluaran = Pengeluaran::findOrFail($id);
            $pengeluaran->update([
                'nama' => $request->nama,
                'jumlah' => $request->jumlah,
                'tanggal' => $request->tanggal,
                'bulan' => $request->bulan,
                'tahun' => $request->tahun,
            ]);

            return redirect()->route('pengeluaran')->with('success', 'Pengeluaran berhasil diperbarui!');
        } catch (\Exception $e) {
            Log::error('Pengeluaran update failed: ' . $e->getMessage());
            return back()->with('error', 'Gagal memperbarui pengeluaran.');
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

        $dropdownBulan = Pemasukan::select('bulan', 'tahun')
            ->distinct()
            ->orderByDesc('tahun')
            ->orderByDesc('bulan')
            ->get()
            ->map(fn($item) => [
                'bulan' => $item->bulan,
                'tahun' => $item->tahun,
                'nama' => $this->bulanIndo[$item->bulan] . ' ' . $item->tahun,
            ]);

        if ($dropdownBulan->isEmpty()) {
            $dropdownBulan->push([
                'bulan' => Carbon::now()->format('m'),
                'tahun' => Carbon::now()->year,
                'nama' => $this->bulanIndo[Carbon::now()->format('m')] . ' ' . Carbon::now()->year,
            ]);
        }

        return view('pemasukan', [
            'pemasukan' => $pemasukan,
            'totalPemasukan' => $totalPemasukan,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'dropdownBulan' => $dropdownBulan,
            'bulanIndo' => $this->bulanIndo,
        ]);
    }

    public function pemasukanCreate()
    {
        return view('pemasukan_create', ['bulanIndo' => $this->bulanIndo]);
    }

    public function pemasukanStore(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:0',
            'tanggal' => 'required|date',
            'bulan' => 'required|digits:2',
            'tahun' => 'required|digits:4',
        ]);

        try {
            Pemasukan::create([
                'nama' => $request->nama,
                'jumlah' => $request->jumlah,
                'tanggal' => $request->tanggal,
                'bulan' => $request->bulan,
                'tahun' => $request->tahun,
            ]);

            return redirect()->route('pemasukan')->with('success', 'Pemasukan berhasil ditambahkan!');
        } catch (\Exception $e) {
            Log::error('Pemasukan store failed: ' . $e->getMessage());
            return back()->with('error', 'Gagal menambahkan pemasukan.');
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
        $request->validate([
            'nama' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:0',
            'tanggal' => 'required|date',
            'bulan' => 'required|digits:2',
            'tahun' => 'required|digits:4',
        ]);

        try {
            $pemasukan = Pemasukan::findOrFail($id);
            $pemasukan->update([
                'nama' => $request->nama,
                'jumlah' => $request->jumlah,
                'tanggal' => $request->tanggal,
                'bulan' => $request->bulan,
                'tahun' => $request->tahun,
            ]);

            return redirect()->route('pemasukan')->with('success', 'Pemasukan berhasil diperbarui!');
        } catch (\Exception $e) {
            Log::error('Pemasukan update failed: ' . $e->getMessage());
            return back()->with('error', 'Gagal memperbarui pemasukan.');
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
}