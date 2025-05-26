<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\AksesModel;
use App\Models\Admin\BaranghilangModel;
use App\Models\Admin\BarangModel;
use App\Models\Admin\BarangmasukModel;
use App\Models\Admin\BarangkeluarModel;
use App\Models\Admin\BarangrusakModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;

class BaranghilangController extends Controller
{
    public function index()
    {
        $data["title"] = "Barang Hilang";
        $data["hakTambah"] = AksesModel::leftJoin('tbl_submenu', 'tbl_submenu.submenu_id', '=', 'tbl_akses.submenu_id')->where(array('tbl_akses.role_id' => Session::get('user')->role_id, 'tbl_submenu.submenu_judul' => 'Barang Hilang', 'tbl_akses.akses_type' => 'create'))->count();
        return view('Admin.BarangHilang.index', $data);
    }

    public function show(Request $request)
    {
        if ($request->ajax()) {
            $data = BaranghilangModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_baranghilang.barang_kode')->orderBy('bh_id', 'DESC')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('tgl', function ($row) {
                    $tgl = $row->bh_tanggal == '' ? '-' : Carbon::parse($row->bh_tanggal)->translatedFormat('d F Y');

                    return $tgl;
                })
                ->addColumn('tujuan', function ($row) {
                    $tujuan = $row->bh_tujuan == '' ? '-' : $row->bh_tujuan;

                    return $tujuan;
                })
                ->addColumn('barang', function ($row) {
                    $barang = $row->barang_id == '' ? '-' : $row->barang_nama;

                    return $barang;
                })
                ->addColumn('action', function ($row) {
                    $array = array(
                        "bh_id" => $row->bh_id,
                        "bh_kode" => $row->bh_kode,
                        "barang_kode" => $row->barang_kode,
                        "bh_tanggal" => $row->bh_tanggal,
                        "bh_tujuan" => trim(preg_replace('/[^A-Za-z0-9-]+/', '_', $row->bh_tujuan)),
                        "bh_jumlah" => $row->bh_jumlah
                    );
                    $button = '';
                    $hakEdit = AksesModel::leftJoin('tbl_submenu', 'tbl_submenu.submenu_id', '=', 'tbl_akses.submenu_id')->where(array('tbl_akses.role_id' => Session::get('user')->role_id, 'tbl_submenu.submenu_judul' => 'Barang Hilang', 'tbl_akses.akses_type' => 'update'))->count();
                    $hakDelete = AksesModel::leftJoin('tbl_submenu', 'tbl_submenu.submenu_id', '=', 'tbl_akses.submenu_id')->where(array('tbl_akses.role_id' => Session::get('user')->role_id, 'tbl_submenu.submenu_judul' => 'Barang Hilang', 'tbl_akses.akses_type' => 'delete'))->count();
                    if ($hakEdit > 0 && $hakDelete > 0) {
                        $button .= '
                        <div class="g-2">
                        <a class="btn modal-effect text-primary btn-sm" data-bs-effect="effect-super-scaled" data-bs-toggle="modal" href="#Umodaldemo8" data-bs-toggle="tooltip" data-bs-original-title="Edit" onclick=update(' . json_encode($array) . ')><span class="fe fe-edit text-success fs-14"></span></a>
                        <a class="btn modal-effect text-danger btn-sm" data-bs-effect="effect-super-scaled" data-bs-toggle="modal" href="#Hmodaldemo8" onclick=hapus(' . json_encode($array) . ')><span class="fe fe-trash-2 fs-14"></span></a>
                        </div>
                        ';
                    } else if ($hakEdit > 0 && $hakDelete == 0) {
                        $button .= '
                        <div class="g-2">
                            <a class="btn modal-effect text-primary btn-sm" data-bs-effect="effect-super-scaled" data-bs-toggle="modal" href="#Umodaldemo8" data-bs-toggle="tooltip" data-bs-original-title="Edit" onclick=update(' . json_encode($array) . ')><span class="fe fe-edit text-success fs-14"></span></a>
                        </div>
                        ';
                    } else if ($hakEdit == 0 && $hakDelete > 0) {
                        $button .= '
                        <div class="g-2">
                        <a class="btn modal-effect text-danger btn-sm" data-bs-effect="effect-super-scaled" data-bs-toggle="modal" href="#Hmodaldemo8" onclick=hapus(' . json_encode($array) . ')><span class="fe fe-trash-2 fs-14"></span></a>
                        </div>
                        ';
                    } else {
                        $button .= '-';
                    }
                    return $button;
                })
                ->rawColumns(['action', 'tgl', 'tujuan', 'barang'])->make(true);
        }
    }

    public function proses_tambah(Request $request)
    {
        // Cek stok
        $barang = BarangModel::where('barang_kode', $request->barang)->first();
        if (!$barang) {
            return response()->json(['error' => 'Barang tidak ditemukan'], 404);
        }
        $jmlmasuk = BarangmasukModel::where('barang_kode', $request->barang)->sum('bm_jumlah');
        $jmlkeluar = BarangkeluarModel::where('barang_kode', $request->barang)->sum('bk_jumlah');
        $jmlrusak = BarangrusakModel::where('barang_kode', $request->barang)->sum('br_jumlah');
        $jmlhilang = BaranghilangModel::where('barang_kode', $request->barang)->sum('bh_jumlah');
        $totalStok = $jmlmasuk - ($jmlkeluar + $jmlrusak + $jmlhilang);
        if ($request->jml > $totalStok) {
            return response()->json(['error' => 'Stok tidak mencukupi. Stok tersedia: ' . $totalStok], 422);
        }
        //insert data
        BaranghilangModel::create([
            'bh_tanggal' => $request->tglhilang,
            'bh_kode' => $request->bhkode,
            'barang_kode' => $request->barang,
            'bh_tujuan'   => $request->tujuan,
            'bh_jumlah'   => $request->jml,
        ]);
        return response()->json(['success' => 'Berhasil']);
    }

    public function proses_ubah(Request $request, BaranghilangModel $baranghilang)
    {
        //update data
        $baranghilang->update([
            'bh_tanggal' => $request->tglhilang,
            'bh_kode' => $request->bhkode,
            'barang_kode' => $request->barang,
            'bh_tujuan'   => $request->tujuan,
            'bh_jumlah'   => $request->jml,
        ]);

        return response()->json(['success' => 'Berhasil']);
    }

    public function proses_hapus(Request $request, BaranghilangModel $baranghilang)
    {
        //delete
        $baranghilang->delete();

        return response()->json(['success' => 'Berhasil']);
    }

}
