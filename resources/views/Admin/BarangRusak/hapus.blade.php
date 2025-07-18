<!-- MODAL HAPUS -->
<div class="modal fade" data-bs-backdrop="static" id="Hmodaldemo8">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-body text-center p-4 pb-5">
                <button type="reset" aria-label="Close" onclick="resetH()" class="btn-close position-absolute" data-bs-dismiss="modal"><span aria-hidden="true">×</span></button>
                <br>
                <i class="icon icon-exclamation fs-70 text-warning lh-1 my-5 d-inline-block"></i>
                <h3 class="mb-5">Yakin hapus <span id="vbr"></span> ?</h3>
                <input type="hidden" name="idbr" id="idbr">
                <button class="btn btn-danger-light pd-x-25 d-none" id="btnLoaderH" type="button" disabled="">
                    <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                    Loading...
                </button>
                <button onclick="submitFormH()" class="btn btn-danger-light pd-x-25" id="btnSubmit">Iya</button>
                <button type="reset" data-bs-dismiss="modal" class="btn btn-default pd-x-25">Batal</button>
            </div>
        </div>
    </div>
</div>

@section('formHapusJS')
<script>
    function submitFormH() {
        setLoadingH(true);
        const id = $("input[name='idbr']").val();
        $.ajax({
            type: 'POST',
            url: "{{url('admin/barang-rusak/proses_hapus')}}/" + id,
            enctype: 'multipart/form-data',
            success: function(data) {
                swal({
                    title: "Berhasil dihapus!",
                    type: "success"
                });
                $('#Hmodaldemo8').modal('toggle');
                table.ajax.reload(null, false);
                resetH();
            }
        });
    }
    function resetH() {
        $("input[name='idbr']").val('');
        setLoadingH(false);
    }
    function setLoadingH(bool) {
        if (bool == true) {
            $('#btnLoaderH').removeClass('d-none');
            $('#btnSubmit').addClass('d-none');
        } else {
            $('#btnSubmit').removeClass('d-none');
            $('#btnLoaderH').addClass('d-none');
        }
    }

    // Tambahkan event listener untuk tombol Enter
    $(document).ready(function() {
        $('#Hmodaldemo8').on('shown.bs.modal', function () {
            $(document).on('keydown', function(event) {
                if (event.key === "Enter") {
                    event.preventDefault(); // Mencegah submit form default
                    submitFormH(); // Panggil fungsi hapus
                }
            });
        });

        $('#Hmodaldemo8').on('hidden.bs.modal', function () {
            $(document).off('keydown'); // Hapus event listener saat modal ditutup
        });
    });
</script>
@endsection
