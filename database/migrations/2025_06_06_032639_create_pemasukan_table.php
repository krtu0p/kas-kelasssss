<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePemasukanTable extends Migration
{
    public function up()
    {
        Schema::create('pemasukans', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->decimal('jumlah', 15, 2);
            $table->date('tanggal');
            $table->string('bulan', 2);
            $table->string('tahun', 4);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pemasukans');
    }
}