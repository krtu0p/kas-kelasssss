<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pemasukan extends Model
{
    protected $guarded = [];
    protected $casts = [
        'jumlah' => 'decimal:2',
        'tanggal' => 'date',
    ];
}