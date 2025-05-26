<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaranghilangModel extends Model
{
    use HasFactory;
    protected $table = "tbl_baranghilang";
    protected $primaryKey = 'bh_id';
    protected $fillable = [
        'bh_kode',
        'barang_kode',
        'bh_tanggal',
        'bh_tujuan',
        'bh_jumlah',
    ]; 
}
