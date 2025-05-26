<!DOCTYPE html>
<html lang="en">

<?php

use Carbon\Carbon;
?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{$web->web_deskripsi}}">
    <meta name="author" content="{{$web->web_nama}}">
    <meta name="keywords" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <!-- FAVICON -->
    @if($web->web_logo == '' || $web->web_logo == 'default.png')
    <link rel="shortcut icon" type="image/x-icon" href="{{url('/assets/default/web/default.png')}}" />
    @else
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('storage/web/' . $web->web_logo)}}" />
    @endif

    <title>{{$title}}</title>

    <style>
        * {
            font-family: Arial, Helvetica, sans-serif;
        }

        #table1 {
            border-collapse: collapse;
            width: 100%;
            margin-top: 32px;
        }

        #table1 td,
        #table1 th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        #table1 th {
            padding-top: 12px;
            padding-bottom: 12px;
            color: black;
            font-size: 12px;
        }

        #table1 td {
            font-size: 11px;
        }

        .font-medium {
            font-weight: 500;
        }

        .font-bold {
            font-weight: 600;
        }

        .d-2 {
            display: flex;
            align-items: flex-start;
            margin-top: 32px;
        }
    </style>

</head>

<body onload="window.print()">

    <center>
        @if($web->web_logo == '' || $web->web_logo == 'default.png')
        <img src="{{url('/assets/default/web/default.png')}}" width="80px" alt="">
        @else
        <img src="{{asset('storage/web/' . $web->web_logo)}}" width="80px" alt="">
        @endif
    </center>

    <div class="row">
        <div class="col-md-12">
            <div class="text-center">
                <h4>LAPORAN BARANG KELUAR</h4>
                <h5>{{ $web->web_nama }}</h5>
                <p>{{ $web->web_alamat }}</p>
            </div>
            <div class="text-center">
                @if($tglawal && $tglakhir)
                    <p>Periode: {{ \Carbon\Carbon::parse($tglawal)->translatedFormat('d F Y') }} - {{ \Carbon\Carbon::parse($tglakhir)->translatedFormat('d F Y') }}</p>
                @else
                    <p>Semua Tanggal</p>
                @endif
                @if($customer)
                    <p>Customer: {{ $customer }}</p>
                @endif
            </div>
        </div>
    </div>

    <table border="1" id="table1">
        <thead>
            <tr>
                <th align="center" width="1%">NO</th>
                <th>TGL KELUAR</th>
                <th>KODE BRG KELUAR</th>
                <th>KODE BARANG</th>
                <th>BARANG</th>
                <th>JML PESANAN</th>
                <th>HARGA PER BRG</th>
                <th>CUSTOMER</th>
            </tr>
        </thead>
        <tbody>
            @php $no=1; @endphp
            @foreach($data as $d)
            <tr>
                <td align="center">{{$no++}}</td>
                <td>{{$d->bk_tanggal ? \Carbon\Carbon::parse($d->bk_tanggal)->translatedFormat('d F Y') : '-'}}</td>
                <td>{{$d->bk_kode}}</td>
                <td>{{$d->barang_kode}}</td>
                <td>{{$d->barang_nama}}</td>
                <td align="center">{{$d->bk_jumlah}}</td>
                <td align="right">{{ number_format($d->barang_harga,0,',','.') }}</td>
                <td>{{$d->customer_id}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @php $grandTotal = 0; foreach($data as $d) { $grandTotal += $d->bk_jumlah * $d->barang_harga; } @endphp
    <div style="width:100%; text-align:right; margin-top:10px; font-weight:bold;">
        Total Harga: Rp {{ number_format($grandTotal,0,',','.') }}
    </div>

</body>

</html>