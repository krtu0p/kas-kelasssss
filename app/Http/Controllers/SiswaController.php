<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Pembayaran;

class SiswaController extends Controller
{
    public function index()
    {
        $siswas = Siswa::with('pembayaran')->get();
        $maxMinggu = Pembayaran::max('minggu') ?? 10;

        return view('siswa', compact('siswas', 'maxMinggu'));
    }
}
