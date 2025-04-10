<div class="modal-header">
    <h5 class="modal-title">Detail Barang</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="modal-body">
    <table class="table table-bordered">
        <tr>
            <th>Kode Barang</th>
            <td>{{ $barang->barang_kode }}</td>
        </tr>
        <tr>
            <th>Nama Barang</th>
            <td>{{ $barang->barang_nama }}</td>
        </tr>
        <tr>
            <th>Kategori</th>
            <td>{{ $barang->kategori->kategori_nama ?? '-' }}</td>
        </tr>
        <tr>
            <th>Harga Beli</th>
            <td>Rp{{ number_format($barang->harga_beli, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Harga Jual</th>
            <td>Rp{{ number_format($barang->harga_jual, 0, ',', '.') }}</td>
        </tr>
    </table>
</div>

