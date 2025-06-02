<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $table = 'siswas';

    protected $fillable = ['nama'];

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class);
    }
}
