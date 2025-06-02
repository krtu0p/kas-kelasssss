<?php

namespace App\Http\Controllers;

use App\Models\Siswa;

class SiswaController extends Controller
{
    public function index()
    {
        $maxMinggu = 10; // contoh jumlah minggu default
        $siswas = Siswa::with('pembayaran')->get();
        return view('siswa', compact('siswas', 'maxMinggu'));
    }
}
