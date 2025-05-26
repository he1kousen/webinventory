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
        Schema::create('tbl_barangkeluar', function (Blueprint $table) {
            $table->increments('bk_id');
            $table->string('bk_kode');
            $table->string('barang_kode');
            $table->string('customer_id');
            $table->string('bk_tanggal');
            $table->string('bk_jumlah');
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
        Schema::dropIfExists('tbl_barangkeluar');
    }
};
