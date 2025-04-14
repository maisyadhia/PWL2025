@empty($supplier)
<div id="modal-edit-supplier" class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Kesalahan</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="alert alert-danger">
                <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                Data yang Anda cari tidak ditemukan
            </div>
            <a href="{{ url('/supplier') }}" class="btn btn-warning">Kembali</a>
        </div>
    </div>
</div>
@else
<form action="{{ url('/supplier/' . $supplier->supplier_id . '/update_ajax') }}" method="POST" id="form-edit-supplier">
    @csrf
    @method('PUT')

    <div id="modal-edit-supplier" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Data Supplier</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Supplier ID</label>
                    <input value="{{ $supplier->supplier_id }}" type="text" name="supplier_id" id="supplier_id" class="form-control" required>
                    <small id="error-supplier_id" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Nama Supplier</label>
                    <input value="{{ $supplier->nama_supplier }}" type="text" name="nama_supplier" id="nama_supplier" class="form-control" required>
                    <small id="error-nama_supplier" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Kontak</label>
                    <input value="{{ $supplier->supplier_kontak }}" type="text" name="supplier_kontak" id="supplier_kontak" class="form-control" required>
                    <small id="error-supplier_kontak" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Alamat</label>
                    <textarea name="alamat" id="alamat" class="form-control" required>{{ $supplier->alamat }}</textarea>
                    <small id="error-alamat" class="error-text form-text text-danger"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>

<script>
$(document).ready(function() {
    $("#form-edit-supplier").validate({
        rules: {
            supplier_id: { required: true, minlength: 1, maxlength: 20 },
            nama_supplier: { required: true, minlength: 3, maxlength: 100 },
            supplier_kontak: { required: true, minlength: 10, maxlength: 15, digits: true },
            alamat: { required: true, minlength: 5, maxlength: 255 }
        },

        submitHandler: function(form) {
            let formData = $(form).serialize();
            
            // Debugging untuk cek apakah supplier_id ada
            console.log("Data yang dikirim:", formData);

            $.ajax({
                url: form.action,
                type: "POST", // Harus POST karena ada _method=PUT
                data: formData,
                success: function(response) {
                    if (response.status) {
                        // Reset form & error
                        $(form)[0].reset();
                        $('.error-text').text('');
                        $('#supplier_id').val('');

                        // Tutup modal langsung
                        $('#modal-master').modal('hide');

                        // Reload tabel langsung
                        dataSupplier.ajax.reload();

                        // Notifikasi sukses
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message
                        });
                    } else {
                        // Handle error validasi
                        $('.error-text').text('');
                        $.each(response.msgField, function(prefix, val) {
                            $('#error-' + prefix).text(val[0]);
                        });
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: response.message
                        });
                    }
                },

                error: function(xhr) {
                    console.log("Error:", xhr.responseText);
                }
            });

            return false;
        },
        errorElement: 'span',
        errorPlacement: function(error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function(element) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function(element) {
            $(element).removeClass('is-invalid');
        }
    });
});

</script>
@endempty