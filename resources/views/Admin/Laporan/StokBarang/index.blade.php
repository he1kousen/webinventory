@extends('Master.Layouts.app', ['title' => $title])

@section('content')
<!-- PAGE-HEADER -->
<div class="page-header">
    <h1 class="page-title">Laporan Stok Barang</h1>
    <div>
        <ol class="breadcrumb">
            <li class="breadcrumb-item text-gray">Laporan</li>
            <li class="breadcrumb-item active" aria-current="page">Stok Barang</li>
        </ol>
    </div>
</div>
<!-- PAGE-HEADER END -->

<!-- ROW -->
<div class="row row-sm">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header justify-content-between">
                <h3 class="card-title">Data Laporan Stok Barang</h3>
            </div>
            
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-12 text-end">
                        <button class="btn btn-primary-light" onclick="print()"><i class="fe fe-printer"></i> Print</button>                        
                    </div>
                </div>
     <!-- ALERT BARANG HAMPIR HABIS -->
                <div id="alert-stok" class="alert alert-warning d-none" role="alert">
                    ⚠️ <span id="barang-tipis-text">Beberapa barang hampir habis.</span>
                </div>
                 
                <div class="table-responsive">
                    <table id="table-1" class="table table-bordered text-nowrap border-bottom dataTable no-footer dtr-inline collapsed">
                        <thead>
                            <th class="border-bottom-0" width="1%">No</th>
                            <th class="border-bottom-0">Kode Barang</th>
                            <th class="border-bottom-0">Barang</th>
                            <th class="border-bottom-0">Stok Awal</th>
                            <th class="border-bottom-0">Jumlah Masuk</th>
                            <th class="border-bottom-0">Jumlah Keluar</th>
                            <th class="border-bottom-0">Jumlah Rusak</th>
                            <th class="border-bottom-0">Jumlah Hilang</th>
                            <th class="border-bottom-0">Total Stok</th>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END ROW -->

@endsection

@section('scripts')
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function() {
        getData();
    });

    function getData() {
        table = $('#table-1').DataTable({
            processing: true,
            serverSide: true,
            info: true,
            order: [],
            scrollX: true,
            stateSave: true,
            lengthMenu: [
                [5, 10, 25, 50, 100, -1],
                [5, 10, 25, 50, 100, 'Semua']
            ],
            pageLength: 10,
            lengthChange: true,

            ajax: {
                url: "{{ route('lap-sb.getlap-sb') }}",
                data: function(d) {
                    d.tglawal = $('input[name="tglawal"]').val();
                    d.tglakhir = $('input[name="tglakhir"]').val();
                },
                dataSrc: function(json) {
                    let barangTipis = [];

                    json.data.forEach(row => {
                        let rawStok = row.totalstok;
                        let rawNama = row.barang_nama;

                        // Bersihkan angka dari html tag dan simbol
                        let stok = parseInt(String(rawStok).replace(/<[^>]+>/g, '').replace(/[^\d]/g, ''));

                        // Cek stok
                        if (!isNaN(stok) && stok <= 10) {
                            barangTipis.push(rawNama);
                        }

                        // Warnai kolom stok sesuai kondisi
                        if (!isNaN(stok)) {
                            if (stok < 10) {
                                row.totalstok = `<span style="color: red; font-weight: bold;">${stok}</span>`;
                            } else if (stok < 20) {
                                row.totalstok = `<span style="color: orange; font-weight: bold;">${stok}</span>`;
                            } else {
                                row.totalstok = `<span>${stok}</span>`;
                            }
                        }
                    });

                    // Tampilkan alert jika ada barang hampir habis
                    if (barangTipis.length > 0) {
                        $('#barang-tipis-text').text("Barang hampir habis: " + barangTipis.join(", "));
                        $('#alert-stok').removeClass('d-none').fadeIn();
                    } else {
                        $('#alert-stok').fadeOut();
                    }

                    return json.data;
                }
            },

            "columns": [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    searchable: false
                },
                {
                    data: 'barang_kode',
                    name: 'barang_kode',
                },
                {
                    data: 'barang_nama',
                    name: 'barang_nama',
                },
                {
                    data: 'stokawal',
                    name: 'barang_stok',
                },
                {
                    data: 'jmlmasuk',
                    name: 'barang_kode',
                    orderable: false,
                },
                {
                    data: 'jmlkeluar',
                    name: 'barang_kode',
                    searchable: false,
                    orderable: false,
                },
                {
                    data: 'jmlrusak',
                    name: 'barang_kode',
                    searchable: false,
                    orderable: false,
                },
                {
                    data: 'jmlhilang',
                    name: 'barang_kode',
                    searchable: false,
                    orderable: false,
                },
                {
                    data: 'totalstok',
                    name: 'barang_kode',
                    searchable: false,
                    orderable: false,
                },
            ],

        });
    }

    function filter() {
        var tglawal = $('input[name="tglawal"]').val();
        var tglakhir = $('input[name="tglakhir"]').val();
        if (tglawal != '' && tglakhir != '') {
            table.ajax.reload(null, false);
        } else {
            validasi("Isi dulu Form Filter Tanggal!", 'warning');
        }

    }

    function reset() {
        $('input[name="tglawal"]').val('');
        $('input[name="tglakhir"]').val('');
        table.ajax.reload(null, false);
    }

    function print() {
        var tglawal = $('input[name="tglawal"]').val();
        var tglakhir = $('input[name="tglakhir"]').val();
        window.open(
            "{{route('lap-sb.print')}}?tglawal=" + tglawal + "&tglakhir=" + tglakhir,
            '_blank'
        );

    }

    function pdf() {
        var tglawal = $('input[name="tglawal"]').val();
        var tglakhir = $('input[name="tglakhir"]').val();
        window.open(
            "{{route('lap-sb.pdf')}}?tglawal=" + tglawal + "&tglakhir=" + tglakhir,
            '_blank'
        );

    }

    function validasi(judul, status) {
        swal({
            title: judul,
            type: status,
            confirmButtonText: "Iya."
        });
    }
</script>
@endsection