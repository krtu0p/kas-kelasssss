<?php

namespace App\Http\Controllers;

// PERBAIKAN 1: Tambahkan model Pemasukan dan Pengeluaran
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\Siswa;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DashboardController extends Controller
{
    private $bulanIndo = [
        '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
        '04' => 'April', '05' => 'Mei', '06' => 'Juni',
        '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
        '10' => 'Oktober', '11' => 'November', '12' => 'Desember',
    ];

    // Ganti seluruh method index Anda dengan yang ini
    public function index(Request $request)
    {
        // --- Bagian 1: Logika untuk Tabel Pembayaran (Sudah Benar) ---
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
                'nama' => $this->bulanIndo[$item->bulan],
            ]);
        
        // Menambahkan bulan saat ini ke dropdown jika belum ada data
        if ($dropdownBulan->where('bulan', $bulan)->where('tahun', $tahun)->isEmpty()) {
            $dropdownBulan->prepend([
                'bulan' => $bulan,
                'tahun' => $tahun,
                'nama' => $this->bulanIndo[$bulan]
            ]);
        }

        // --- PERBAIKAN 2: Tambahkan logika untuk menghitung data kartu statistik ---
        $totalPemasukan = Pemasukan::sum('jumlah');
        $totalPengeluaran = Pengeluaran::sum('jumlah');
        $totalKas = $totalPemasukan - $totalPengeluaran;
        // --- Akhir Perbaikan ---

        // Kirim semua data yang dibutuhkan ke view
        return view('dashboard', [
            // Data lama untuk tabel
            'siswas' => $siswas,
            'maxMinggu' => $maxMinggu,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'dropdownBulan' => $dropdownBulan,
            'bulanIndo' => $this->bulanIndo,
            
            // --- PERBAIKAN 3: Kirim variabel baru untuk kartu statistik ke view ---
            'totalPemasukan' => $totalPemasukan,
            'totalPengeluaran' => $totalPengeluaran,
            'totalKas' => $totalKas,
        ]);
    }

    // Method lainnya tidak perlu diubah (update, tambahMinggu, dll.)
    public function update(Request $request)
    {
        $request->validate([
            'bulan' => 'required|digits:2',
            'tahun' => 'required|digits:4',
            'pembayaran' => 'array',
        ]);

        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');
        $data = $request->input('pembayaran', []);
        $maxMinggu = Pembayaran::where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->max('minggu') ?? 0;

        try {
            DB::transaction(function () use ($data, $bulan, $tahun, $maxMinggu) {
                // Ambil semua siswa yang terlibat dalam update ini
                $siswaIds = array_keys($data);
                $semuaSiswa = Siswa::pluck('id');

                // Loop melalui semua siswa yang ada di sistem
                foreach ($semuaSiswa as $siswa_id) {
                    for ($i = 1; $i <= $maxMinggu; $i++) {
                        // Cek apakah siswa ini dibayar untuk minggu ini
                        $status = isset($data[$siswa_id]) && in_array($i, $data[$siswa_id]);
                        
                        Pembayaran::updateOrCreate(
                            [
                                'siswa_id' => $siswa_id,
                                'minggu' => $i,
                                'bulan' => $bulan,
                                'tahun' => $tahun,
                            ],
                            ['status' => $status]
                        );
                    }
                }
            });

            return back()->with('success', 'Data pembayaran berhasil diperbarui!');
        } catch (\Exception $e) {
            Log::error('Update pembayaran failed: ' . $e->getMessage());
            return back()->with('error', 'Gagal memperbarui data pembayaran.');
        }
    }

    public function tambahMinggu(Request $request)
    {
        $request->validate([
            'bulan' => 'required|digits:2',
            'tahun' => 'required|digits:4',
        ]);

        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');

        try {
            $maxMinggu = Pembayaran::where('bulan', $bulan)
                ->where('tahun', $tahun)
                ->max('minggu') ?? 0;

            $nextMinggu = $maxMinggu + 1;

            DB::transaction(function () use ($bulan, $tahun, $nextMinggu) {
                $siswas = Siswa::pluck('id');
                $insertData = $siswas->map(fn($siswa_id) => [
                    'siswa_id' => $siswa_id,
                    'minggu' => $nextMinggu,
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                    'status' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ])->toArray();

                Pembayaran::insert($insertData);
            });

            return redirect()->route('dashboard', ['bulan' => $bulan, 'tahun' => $tahun])
                ->with('success', "Minggu ke-$nextMinggu untuk bulan {$this->bulanIndo[$bulan]} $tahun berhasil ditambahkan!");
        } catch (\Exception $e) {
            Log::error('Tambah minggu failed: ' . $e->getMessage());
            return back()->with('error', 'Gagal menambah minggu.');
        }
    }

    public function hapusMinggu(Request $request)
    {
        $request->validate([
            'bulan' => 'required|digits:2',
            'tahun' => 'required|digits:4',
        ]);

        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');

        try {
            $maxMinggu = Pembayaran::where('bulan', $bulan)
                ->where('tahun', $tahun)
                ->max('minggu');

            if ($maxMinggu <= 1) {
                return back()->with('error', 'Tidak ada minggu yang bisa dihapus.');
            }

            DB::transaction(function () use ($bulan, $tahun, $maxMinggu) {
                Pembayaran::where('bulan', $bulan)
                    ->where('tahun', $tahun)
                    ->where('minggu', $maxMinggu)
                    ->delete();
            });

            return back()->with('success', "Minggu ke-$maxMinggu di bulan {$this->bulanIndo[$bulan]} $tahun berhasil dihapus.");
        } catch (\Exception $e) {
            Log::error('Hapus minggu failed: ' . $e->getMessage());
            return back()->with('error', 'Gagal menghapus minggu.');
        }
    }

    public function tambahBulan(Request $request)
    {
        $request->validate([
            'bulan' => 'required|digits:2',
            'tahun' => 'required|digits:4',
        ]);

        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');

        if (Pembayaran::where('bulan', $bulan)->where('tahun', $tahun)->exists()) {
            return back()->with('error', "Bulan {$this->bulanIndo[$bulan]} $tahun sudah ada!");
        }

        try {
            DB::transaction(function () use ($bulan, $tahun) {
                $siswas = Siswa::pluck('id');
                $maxMingguDefault = config('app.max_minggu_default', 4);
                $insertData = [];

                foreach ($siswas as $siswa_id) {
                    for ($minggu = 1; $minggu <= $maxMingguDefault; $minggu++) {
                        $insertData[] = [
                            'siswa_id' => $siswa_id,
                            'bulan' => $bulan,
                            'tahun' => $tahun,
                            'minggu' => $minggu,
                            'status' => false,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }

                Pembayaran::insert($insertData);
            });

            return redirect()->route('dashboard', ['bulan' => $bulan, 'tahun' => $tahun])
                ->with('success', "Bulan {$this->bulanIndo[$bulan]} $tahun berhasil ditambahkan!");
        } catch (\Exception $e) {
            Log::error('Tambah bulan failed: ' . $e->getMessage());
            return back()->with('error', 'Gagal menambah bulan baru.');
        }
    }
}