<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayarans';

    protected $fillable = ['siswa_id', 'minggu', 'status'];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}
