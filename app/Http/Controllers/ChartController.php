<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pemasukan;
use Carbon\Carbon;

class ChartController extends Controller
{
    public function index()
    {
        $data = Pemasukan::selectRaw("DATE_FORMAT(tanggal, '%Y-%m') as bulan, SUM(jumlah) as total")
    ->groupByRaw("DATE_FORMAT(tanggal, '%Y-%m')")
    ->orderByRaw("DATE_FORMAT(tanggal, '%Y-%m')")
    ->get();


        $labels = $data->pluck('bulan')->map(function ($bulan) {
            return Carbon::createFromFormat('Y-m', $bulan)->translatedFormat('F Y');
        });

        $values = $data->pluck('total');

        return view('chart', [
            'labels' => $labels,
            'values' => $values,
        ]);
    }
}
