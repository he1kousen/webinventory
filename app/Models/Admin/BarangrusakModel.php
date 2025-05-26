<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangrusakModel extends Model
{
    use HasFactory;
    protected $table = "tbl_barangrusak";
    protected $primaryKey = 'br_id';
    protected $fillable = [
        'br_kode',
        'barang_kode',
        'br_tanggal',
        'br_tujuan',
        'br_jumlah',
    ]; 
}
