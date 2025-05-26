<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_baranghilang', function (Blueprint $table) {
            $table->increments('bh_id');
            $table->string('bh_kode');
            $table->string('barang_kode');
            $table->string('bh_tanggal');
            $table->string('bh_tujuan')->nullable();
            $table->string('bh_jumlah');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_baranghilang');
    }
};
