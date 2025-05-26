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
        Schema::create('tbl_barangrusak', function (Blueprint $table) {
            $table->increments('br_id');
            $table->string('br_kode');
            $table->string('barang_kode');
            $table->string('br_tanggal');
            $table->string('br_tujuan')->nullable();
            $table->string('br_jumlah');
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
        Schema::dropIfExists('tbl_barangrusak');
    }
};
