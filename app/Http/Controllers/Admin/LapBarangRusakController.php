<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\BarangrusakModel;
use App\Models\Admin\WebModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use PDF;

class LapBarangRusakController extends Controller
{
    public function index()
    {
        $data["title"] = "Lap Barang Rusak";
        return view('Admin.Laporan.BarangRusak.index', $data);
    }

    public function print(Request $request)
    {
        if ($request->tglawal) {
            $data['data'] = BarangrusakModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangrusak.barang_kode')->whereBetween('br_tanggal', [$request->tglawal, $request->tglakhir])->orderBy('br_id', 'DESC')->get();
        } else {
            $data['data'] = BarangrusakModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangrusak.barang_kode')->orderBy('br_id', 'DESC')->get();
        }

        $data["title"] = "Print Barang Rusak";
        $data['web'] = WebModel::first();
        $data['tglawal'] = $request->tglawal;
        $data['tglakhir'] = $request->tglakhir;
        return view('Admin.Laporan.BarangRusak.print', $data);
    }

    public function pdf(Request $request)
    {
        if ($request->tglawal) {
            $data['data'] = BarangrusakModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangrusak.barang_kode')->whereBetween('br_tanggal', [$request->tglawal, $request->tglakhir])->orderBy('br_id', 'DESC')->get();
        } else {
            $data['data'] = BarangrusakModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangrusak.barang_kode')->orderBy('br_id', 'DESC')->get();
        }

        $data["title"] = "PDF Barang Rusak";
        $data['web'] = WebModel::first();
        $data['tglawal'] = $request->tglawal;
        $data['tglakhir'] = $request->tglakhir;
        $pdf = PDF::loadView('Admin.Laporan.BarangRusak.pdf', $data);
        
        if($request->tglawal){
            return $pdf->download('lap-br-'.$request->tglawal.'-'.$request->tglakhir.'.pdf');
        }else{
            return $pdf->download('lap-br-semua-tanggal.pdf');
        }
        
    }

    public function show(Request $request)
    {
        if ($request->ajax()) {
            if ($request->tglawal == '') {
                $data = BarangrusakModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangrusak.barang_kode')->orderBy('br_id', 'DESC')->get();
            } else {
                $data = BarangrusakModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangrusak.barang_kode')->whereBetween('br_tanggal', [$request->tglawal, $request->tglakhir])->orderBy('br_id', 'DESC')->get();
            }
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('tgl', function ($row) {
                    $tgl = $row->br_tanggal == '' ? '-' : Carbon::parse($row->br_tanggal)->translatedFormat('d F Y');

                    return $tgl;
                })
                ->addColumn('tujuan', function ($row) {
                    $tujuan = $row->br_tujuan == '' ? '-' : $row->br_tujuan;

                    return $tujuan;
                })
                ->addColumn('barang', function ($row) {
                    $barang = $row->barang_id == '' ? '-' : $row->barang_nama;

                    return $barang;
                })
                ->rawColumns(['tgl', 'tujuan', 'barang'])->make(true);
        }
    }

}
