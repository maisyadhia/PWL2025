<form id="form-tambah" method="POST">
    @csrf
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Tambah Transaksi</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Kode Transaksi</label>
                            <input type="text" name="penjualan_kode" class="form-control" required>
                            <small class="error-penjualan_kode error-text text-danger"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Tanggal Transaksi</label>
                            <input type="datetime-local" name="penjualan_tanggal" class="form-control" required>
                            <small class="error-penjualan_tanggal error-text text-danger"></small>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Nama Pembeli</label>
                    <input type="text" name="pembeli" class="form-control" required>
                    <small class="error-pembeli error-text text-danger"></small>
                </div>

                <h5 class="mt-4">Detail Barang</h5>
                <div id="items-container">
                    <div class="item-row row mb-2">
                        <div class="col-md-5">
                            <select name="items[0][barang_id]" class="form-control select-barang" required>
                                <option value="">Pilih Barang</option>
                                @foreach($barang as $item)
                                    <option value="{{ $item->barang_id }}" 
                                        data-harga="{{ $item->harga_jual }}"
                                        data-stok="{{ $item->stok }}">
                                        {{ $item->barang_nama }} (Stok: {{ $item->stok }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="number" name="items[0][jumlah]" min="1" value="1" class="form-control jumlah" required>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control harga" placeholder="Harga" readonly>
                        </div>
                        <div class="col-md-2">
                            <input type="text" class="form-control subtotal" placeholder="Subtotal" readonly>
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-danger btn-sm remove-item">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <button type="button" id="add-item" class="btn btn-secondary mt-2">
                    <i class="fas fa-plus"></i> Tambah Barang
                </button>

                <div class="row mt-3">
                    <div class="col-md-8"></div>
                    <div class="col-md-4">
                        <h5>Total: <span id="total-harga">Rp 0</span></h5>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary" id="btn-simpan">
                    <i class="fas fa-save"></i> Simpan Transaksi
                </button>
            </div>
        </div>
    </div>
</form>

<script>
$(document).ready(function() {
    // Inisialisasi counter untuk item
    let itemCount = 1;
    
    // Fungsi untuk menambah item barang
    $('#add-item').click(function() {
        const newRow = `
        <div class="item-row row mb-2">
            <div class="col-md-5">
                <select name="items[0][barang_id]" class="form-control select-barang" required>
                    <option value="">Pilih Barang</option>
                    @foreach($barang as $item)
                        <option value="{{ $item->barang_id }}" 
                            data-harga="{{ $item->harga_jual }}"
                            data-stok="{{ $item->stok }}">
                            {{ $item->barang_nama }} (Stok: {{ $item->stok }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <input type="number" name="items[${itemCount}][jumlah]" min="1" value="1" class="form-control jumlah" required>
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control harga" placeholder="Harga" readonly>
            </div>
            <div class="col-md-2">
                <input type="text" class="form-control subtotal" placeholder="Subtotal" readonly>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-danger btn-sm remove-item">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>`;
        
        $('#items-container').append(newRow);
        itemCount++;
    });

    // Fungsi untuk menghapus item barang
    $(document).on('click', '.remove-item', function() {
        if ($('#items-container .item-row').length > 1) {
            $(this).closest('.item-row').remove();
            calculateTotal();
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: 'Minimal harus ada 1 barang dalam transaksi'
            });
        }
    });

    // Update harga ketika barang dipilih
    $(document).on('change', '.select-barang', function() {
    const row = $(this).closest('.item-row');
    const selectedOption = $(this).find(':selected');
    
    // Ambil data stok dari atribut data
    const stok = parseInt(selectedOption.data('stok')) || 0;
    const harga = parseFloat(selectedOption.data('harga')) || 0;
    
    // Tampilkan stok tersedia (opsional)
    row.find('.stok-info').text(`Stok tersedia: ${stok}`);
    
    // Set maksimal jumlah yang bisa dibeli
    // row.find('.jumlah').attr('max', stok);
    
    // Update harga
    row.find('.harga').val(harga.toLocaleString('id-ID'));
    
    // Hitung ulang subtotal
    calculateSubtotal(row);
});

    // Hitung subtotal ketika jumlah diubah
    $(document).on('input', '.jumlah', function() {
        const row = $(this).closest('.item-row');
        calculateSubtotal(row);
    });

    // Hitung subtotal per item
    function calculateSubtotal(row) {
        const harga = row.find('.select-barang').find(':selected').data('harga') || 0;
        const jumlah = row.find('.jumlah').val() || 0;
        const subtotal = harga * jumlah;
        
        row.find('.subtotal').val('Rp ' + subtotal.toLocaleString('id-ID'));
        calculateTotal();
    }

    // Hitung total semua item
    function calculateTotal() {
        let total = 0;
        
        $('.item-row').each(function() {
            const harga = $(this).find('.select-barang').find(':selected').data('harga') || 0;
            const jumlah = $(this).find('.jumlah').val() || 0;
            total += harga * jumlah;
        });
        
        $('#total-harga').text('Rp ' + total.toLocaleString('id-ID'));
    }

    // Submit form dengan AJAX
    $('#form-tambah').submit(function(e) {
        e.preventDefault();
        $('.error-text').text('');
        $('#btn-simpan').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');
        $.ajax({
            url: "{{ route('transaksi.store_ajax') }}",
            type: "POST",
            data: $(this).serialize(),
            success: function(response) {
                if (response.status) {
                    $('#myModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.message,
                        timer: 1500
                    }).then(() => {
                        // Refresh tabel transaksi
                        if (typeof dataTransaksi !== 'undefined') {
                            dataTransaksi.ajax.reload();
                        }
                        // Tampilkan detail transaksi
                        modalAction('/transaksi/' + response.data.id + '/show_ajax');
                    });
                }
            },
            error: function(xhr) {
                $('#btn-simpan').prop('disabled', false).html('<i class="fas fa-save"></i> Simpan Transaksi');
                
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        const field = key.replace(/\./g, '_');
                        $(`.error-${field}`).text(value[0]);
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan saat menyimpan data: ' + xhr.responseJSON.message
                    });
                }
            }
        });
    });
});
</script>