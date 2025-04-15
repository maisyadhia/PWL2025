<form action="{{ route('transaksi.store_ajax') }}" method="POST" id="form-tambah">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Transaksi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Kode Transaksi</label>
                    <input type="text" name="penjualan_kode" class="form-control" required>
                    <small id="error-penjualan_kode" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Nama Pembeli</label>
                    <input type="text" name="pembeli" class="form-control" required>
                    <small id="error-pembeli" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Tanggal Transaksi</label>
                    <input type="datetime-local" name="penjualan_tanggal" class="form-control" required>
                    <small id="error-penjualan_tanggal" class="error-text form-text text-danger"></small>
                </div>

                <h5>Detail Barang</h5>
                <div id="items-container">
                    <div class="item-row row mb-2">
                        <div class="col-md-5">
                            <select name="items[0][barang_id]" class="form-control select-barang" required>
                                <option value="">Pilih Barang</option>
                                @foreach($barang as $item)
                                    <option value="{{ $item->barang_id }}" data-harga="{{ $item->harga_jual }}">{{ $item->barang_nama }} (Stok: {{ $item->stok }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="number" name="items[0][jumlah]" min="1" value="1" class="form-control jumlah" required>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control harga" readonly>
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-danger remove-item">X</button>
                        </div>
                    </div>
                </div>

                <button type="button" id="add-item" class="btn btn-secondary mt-2">Tambah Barang</button>
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
    // Tambah item barang
    $('#add-item').click(function() {
        const container = $('#items-container');
        const index = container.children().length;
        
        const newRow = `
        <div class="item-row row mb-2">
            <div class="col-md-5">
                <select name="items[${index}][barang_id]" class="form-control select-barang" required>
                    <option value="">Pilih Barang</option>
                    @foreach($barang as $item)
                        <option value="{{ $item->barang_id }}" data-harga="{{ $item->harga_jual }}">{{ $item->barang_nama }} (Stok: {{ $item->stok }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <input type="number" name="items[${index}][jumlah]" min="1" value="1" class="form-control jumlah" required>
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control harga" readonly>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-danger remove-item">X</button>
            </div>
        </div>`;
        
        container.append(newRow);
    });

    // Hapus item barang
    $(document).on('click', '.remove-item', function() {
        if ($('#items-container .item-row').length > 1) {
            $(this).closest('.item-row').remove();
        } else {
            alert('Minimal harus ada 1 barang dalam transaksi');
        }
    });

    // Update harga ketika barang dipilih
    $(document).on('change', '.select-barang', function() {
        const harga = $(this).find(':selected').data('harga');
        $(this).closest('.item-row').find('.harga').val(harga ? 'Rp ' + harga.toLocaleString() : '');
    });

    // Validasi form
    $("#form-tambah").validate({
        rules: {
            penjualan_kode: { required: true, minlength: 3 },
            pembeli: { required: true, minlength: 3 },
            penjualan_tanggal: { required: true }
        },
        submitHandler: function(form) {
            $.ajax({
                url: form.action,
                type: form.method,
                data: $(form).serialize(),
                success: function(response) {
                    if(response.status) {
                        $('#myModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message
                        });
                        dataTransaksi.ajax.reload();
                    } else {
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