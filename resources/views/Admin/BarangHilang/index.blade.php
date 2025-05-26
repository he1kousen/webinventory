@extends('Master.Layouts.app', ['title' => $title])

@section('content')
    <!-- PAGE-HEADER -->
    <div class="page-header">
        <h1 class="page-title">Barang Hilang</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item text-gray">Transaksi</li>
                <li class="breadcrumb-item active" aria-current="page">Barang Hilang</li>
            </ol>
        </div>
    </div>
    <!-- PAGE-HEADER END -->


    <!-- ROW -->
    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header justify-content-between">
                    <h3 class="card-title">Data</h3>
                    @if ($hakTambah > 0)
                        <div>
                            <a class="modal-effect btn btn-primary-light" onclick="generateID()"
                                data-bs-effect="effect-super-scaled" data-bs-toggle="modal" href="#modaldemo8">Tambah Data
                                <i class="fe fe-plus"></i></a>
                        </div>
                    @endif
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="table-1"
                            class="table table-bordered text-nowrap border-bottom dataTable no-footer dtr-inline collapsed">
                            <thead>
                                <th class="border-bottom-0" width="1%">No</th>
                                <th class="border-bottom-0">Tanggal Hilang</th>
                                <th class="border-bottom-0">Kode Barang Hilang</th>
                                <th class="border-bottom-0">Kode Barang</th>
                                <th class="border-bottom-0">Barang</th>
                                <th class="border-bottom-0">Jumlah Hilang</th>
                                <th class="border-bottom-0" width="1%">Action</th>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END ROW -->

    @include('Admin.BarangHilang.tambah')
    @include('Admin.BarangHilang.edit')
    @include('Admin.BarangHilang.hapus')
    @include('Admin.BarangHilang.barang')

    <script>
    function generateID() {
    let lastNumber = 0; // Default nomor urut pertama jika tidak ada data

    // Ambil semua kode barang Hilang yang sudah ada
    $("#table-1 tbody tr").each(function () {
        let kode = $(this).find("td:nth-child(3)").text(); // Kolom ke-3 (Kode Barang Hilang)

        if (kode.startsWith("BH-")) {
            let num = parseInt(kode.replace("BH-", ""), 10); // Ambil angka dari kode
            if (num > lastNumber) {
                lastNumber = num; // Cari angka terbesar
            }
        }
    });

    let newNumber = String(lastNumber + 1).padStart(4, '0'); // Format 4 digit
    let kodeBarangHilang = "BH-" + newNumber;

    $("input[name='bhkode']").val(kodeBarangHilang);
}







        function update(data) {
            $("input[name='idbhU']").val(data.bh_id);
            $("input[name='bhkodeU']").val(data.bh_kode);
            $("input[name='kdbarangU']").val(data.barang_kode);
            $("input[name='jmlU']").val(data.bh_jumlah);
            $("input[name='tujuanU']").val(data.bh_tujuan.replace(/_/g, ' '));

            getbarangbyidU(data.barang_kode);

            $("input[name='tglhilangU").bootstrapdatepicker({
                format: 'yyyy-mm-dd',
                autoclose: true
            }).bootstrapdatepicker("update", data.bh_tanggal);
        }

        function hapus(data) {
            $("input[name='idbh']").val(data.bh_id);
            $("#vbh").html("Kode Barang " + "<b>" + data.bh_kode + "</b>");
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

@section('scripts')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var table;
        $(document).ready(function() {
            //datatables
            table = $('#table-1').DataTable({

                "processing": true,
                "serverSide": true,
                "info": true,
                "order": [],
                "scrollX": true,
                "stateSave":true,
                "lengthMenu": [
                    [5, 10, 25, 50, 100],
                    [5, 10, 25, 50, 100]
                ],
                "pageLength": 10,

                lengthChange: true,

                "ajax": {
                    "url": "{{ route('barang-hilang.getbarang-hilang') }}",
                },

                "columns": [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        searchable: false
                    },
                    {
                        data: 'tgl',
                        name: 'bh_tanggal',
                    },
                    {
                        data: 'bh_kode',
                        name: 'bh_kode',
                    },
                    {
                        data: 'barang_kode',
                        name: 'barang_kode',
                    },
                    {
                        data: 'barang',
                        name: 'barang_nama',
                    },
                    {
                        data: 'bh_jumlah',
                        name: 'bh_jumlah',
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],

            });
        });
    </script>
@endsection
