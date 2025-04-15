<form action="{{ url('/stok/ajax') }}" method="POST" id="form-tambah">
    @csrf
    <div class="modal-body">
        <div class="form-group">
            <label>Jenis Transaksi</label>
            <select name="jenis" id="jenis" class="form-control" required>
                <option value="masuk">Stok Masuk</option>
                <option value="keluar">Stok Keluar</option>
            </select>
        </div>
        
        <div class="form-group">
            <label>Barang</label>
            <select name="barang_id" class="form-control select2" required>
                <option value="">Pilih Barang</option>
                @foreach($barang as $b)
                    <option value="{{ $b->barang_id }}">{{ $b->barang_kode }} - {{ $b->barang_nama }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Tanggal</label>
            <input type="datetime-local" name="stok_tanggal" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Jumlah</label>
            <input type="number" name="stok_jumlah" class="form-control" min="1" required>
        </div>

        <div class="form-group" id="penjualan-field" style="display:none">
            <label>No. Penjualan</label>
            <select name="penjualan_id" class="form-control select2">
                <option value="">Pilih Penjualan</option>
                @foreach($penjualan as $p)
                    <option value="{{ $p->penjualan_id }}">{{ $p->penjualan_kode }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Keterangan</label>
            <textarea name="keterangan" class="form-control"></textarea>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        $('select[name="jenis"]').change(function() {
            if ($(this).val() == 'keluar') {
                $('#penjualan-field').show();
                $('select[name="penjualan_id"]').prop('required', true);
            } else {
                $('#penjualan-field').hide();
                $('select[name="penjualan_id"]').prop('required', false);
            }
        });
    });
    </script>
</form>