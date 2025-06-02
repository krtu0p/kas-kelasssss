<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Pembayaran;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $maxMinggu = Pembayaran::max('minggu_ke') ?? 10;
        $siswas = Siswa::with('pembayaran')->get();

        return view('dashboard', compact('siswas', 'maxMinggu'));
    }

    public function update(Request $request)
    {
        $data = $request->input('pembayaran');
        $maxMinggu = Pembayaran::max('minggu_ke') ?? 10;

        foreach ($data as $siswa_id => $minggu) {
            for ($i = 1; $i <= $maxMinggu; $i++) {
                $status = in_array($i, $minggu ?? []) ? true : false;

                Pembayaran::updateOrCreate(
                    ['siswa_id' => $siswa_id, 'minggu_ke' => $i],
                    ['status' => $status]
                );
            }
        }

        return back()->with('success', 'Data diperbarui!');
    }

    public function tambahMinggu()
    {
        $max = Pembayaran::max('minggu_ke') ?? 0;
        $next = $max + 1;
        $siswas = Siswa::all();

        foreach ($siswas as $s) {
            Pembayaran::firstOrCreate([
                'siswa_id' => $s->id,
                'minggu_ke' => $next,
            ]);
        }

        return back()->with('success', "Minggu ke-$next ditambahkan!");
    }
}
