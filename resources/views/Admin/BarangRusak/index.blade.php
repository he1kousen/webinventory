@extends('Master.Layouts.app', ['title' => $title])

@section('content')
    <!-- PAGE-HEADER -->
    <div class="page-header">
        <h1 class="page-title">Barang Rusak</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item text-gray">Transaksi</li>
                <li class="breadcrumb-item active" aria-current="page">Barang Rusak</li>
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
                                <th class="border-bottom-0">Tanggal Rusak</th>
                                <th class="border-bottom-0">Kode Barang Rusak</th>
                                <th class="border-bottom-0">Kode Barang</th>
                                <th class="border-bottom-0">Barang</th>
                                <th class="border-bottom-0">Jumlah Rusak</th>
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

    @include('Admin.BarangRusak.tambah')
    @include('Admin.BarangRusak.edit')
    @include('Admin.BarangRusak.hapus')
    @include('Admin.BarangRusak.barang')

    <script>
    function generateID() {
    let lastNumber = 0; // Default nomor urut pertama jika tidak ada data

    // Ambil semua kode barang rusak yang sudah ada
    $("#table-1 tbody tr").each(function () {
        let kode = $(this).find("td:nth-child(3)").text(); // Kolom ke-3 (Kode Barang Rusak)

        if (kode.startsWith("BR-")) {
            let num = parseInt(kode.replace("BR-", ""), 10); // Ambil angka dari kode
            if (num > lastNumber) {
                lastNumber = num; // Cari angka terbesar
            }
        }
    });

    let newNumber = String(lastNumber + 1).padStart(4, '0'); // Format 4 digit
    let kodeBarangRusak = "BR-" + newNumber;

    $("input[name='brkode']").val(kodeBarangRusak);
}







        function update(data) {
            $("input[name='idbrU']").val(data.br_id);
            $("input[name='brkodeU']").val(data.br_kode);
            $("input[name='kdbarangU']").val(data.barang_kode);
            $("input[name='jmlU']").val(data.br_jumlah);
            $("input[name='tujuanU']").val(data.br_tujuan.replace(/_/g, ' '));

            getbarangbyidU(data.barang_kode);

            $("input[name='tglrusakU").bootstrapdatepicker({
                format: 'yyyy-mm-dd',
                autoclose: true
            }).bootstrapdatepicker("update", data.br_tanggal);
        }

        function hapus(data) {
            $("input[name='idbr']").val(data.br_id);
            $("#vbr").html("Kode Barang " + "<b>" + data.br_kode + "</b>");
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
                    "url": "{{ route('barang-rusak.getbarang-rusak') }}",
                },

                "columns": [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        searchable: false
                    },
                    {
                        data: 'tgl',
                        name: 'br_tanggal',
                    },
                    {
                        data: 'br_kode',
                        name: 'br_kode',
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
                        data: 'br_jumlah',
                        name: 'br_jumlah',
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
