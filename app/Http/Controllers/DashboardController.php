<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Pembayaran;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $maxMinggu = Pembayaran::max('minggu') ?? 10;
        $siswas = Siswa::with('pembayaran')->get();

        return view('dashboard', compact('siswas', 'maxMinggu'));
    }

    public function update(Request $request)
    {
        $data = $request->input('pembayaran');
        $maxMinggu = Pembayaran::max('minggu') ?? 10;

        foreach ($data as $siswa_id => $minggu) {
            for ($i = 1; $i <= $maxMinggu; $i++) {
                $status = in_array($i, $minggu ?? []) ? true : false;

                Pembayaran::updateOrCreate(
                    ['siswa_id' => $siswa_id, 'minggu' => $i],
                    ['status' => $status]
                );
            }
        }

        return back()->with('success', 'Data diperbarui!');
    }

    public function tambahMinggu()
    {
        $max = Pembayaran::max('minggu') ?? 0;
        $next = $max + 1;
        $siswas = Siswa::all();

        foreach ($siswas as $s) {
            Pembayaran::firstOrCreate([
                'siswa_id' => $s->id,
                'minggu' => $next,
            ]);
        }

        return back()->with('success', "Minggu ke-$next ditambahkan!");
    }
    public function hapusMinggu()
    {
        // Ambil minggu terbesar
        $maxMinggu = Pembayaran::max('minggu');

        if ($maxMinggu > 1) {
            // Hapus semua data pembayaran untuk minggu terakhir
            Pembayaran::where('minggu', $maxMinggu)->delete();

            return redirect()->back()->with('success', "Minggu ke-$maxMinggu berhasil dihapus.");
        }

        return back()->with('error', 'Tidak ada minggu yang bisa dihapus.');
    }

}
