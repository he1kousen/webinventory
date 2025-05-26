<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\BaranghilangModel;
use App\Models\Admin\WebModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use PDF;

class LapBarangHilangController extends Controller
{
    public function index()
    {
        $data["title"] = "Lap Barang Hilang";
        return view('Admin.Laporan.BarangHilang.index', $data);
    }

    public function print(Request $request)
    {
        if ($request->tglawal) {
            $data['data'] = BaranghilangModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_baranghilang.barang_kode')->whereBetween('bh_tanggal', [$request->tglawal, $request->tglakhir])->orderBy('bh_id', 'DESC')->get();
        } else {
            $data['data'] = BaranghilangModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_baranghilang.barang_kode')->orderBy('bh_id', 'DESC')->get();
        }

        $data["title"] = "Print Barang Hilang";
        $data['web'] = WebModel::first();
        $data['tglawal'] = $request->tglawal;
        $data['tglakhir'] = $request->tglakhir;
        return view('Admin.Laporan.BarangHilang.print', $data);
    }

    public function pdf(Request $request)
    {
        if ($request->tglawal) {
            $data['data'] = BaranghilangModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_baranghilang.barang_kode')->whereBetween('bh_tanggal', [$request->tglawal, $request->tglakhir])->orderBy('bh_id', 'DESC')->get();
        } else {
            $data['data'] = BaranghilangModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_baranghilang.barang_kode')->orderBy('bh_id', 'DESC')->get();
        }

        $data["title"] = "PDF Barang Hilang";
        $data['web'] = WebModel::first();
        $data['tglawal'] = $request->tglawal;
        $data['tglakhir'] = $request->tglakhir;
        $pdf = PDF::loadView('Admin.Laporan.BarangHilang.pdf', $data);
        
        if($request->tglawal){
            return $pdf->download('lap-bh-'.$request->tglawal.'-'.$request->tglakhir.'.pdf');
        }else{
            return $pdf->download('lap-bh-semua-tanggal.pdf');
        }
        
    }

    public function show(Request $request)
    {
        if ($request->ajax()) {
            if ($request->tglawal == '') {
                $data = BaranghilangModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_baranghilang.barang_kode')->orderBy('bh_id', 'DESC')->get();
            } else {
                $data = BaranghilangModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_baranghilang.barang_kode')->whereBetween('bh_tanggal', [$request->tglawal, $request->tglakhir])->orderBy('bh_id', 'DESC')->get();
            }
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
                ->rawColumns(['tgl', 'tujuan', 'barang'])->make(true);
        }
    }

}
