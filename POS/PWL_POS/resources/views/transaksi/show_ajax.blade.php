<div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

        <div class="modal-header">
            <h5 class="modal-title">{{ $page->title }}</h5>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <div class="modal-body">
            @isset($transaksi)
                <table class="table table-bordered">
                    <tr>
                        <th>Kode Transaksi</th>
                        <td>{{ $transaksi->penjualan_kode }}</td>
                    </tr>
                    <tr>
                        <th>Pembeli</th>
                        <td>{{ $transaksi->pembeli }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal</th>
                        <td>{{ \Carbon\Carbon::parse($transaksi->penjualan_tanggal)->format('d-m-Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Kasir</th>
                        <td>
                            @php
                                $level = $transaksi->user->level->level_nama ?? 'Kasir';
                                $badgeClass = '';
                                
                                if ($level == 'Administrator') {
                                    $badgeClass = 'badge-administrator';
                                } elseif ($level == 'Manager') {
                                    $badgeClass = 'badge-manager';
                                } else {
                                    $badgeClass = 'badge-staff';
                                }
                            @endphp
                            <span class="badge badge-kasir {{ $badgeClass }}">
                                {{ $transaksi->user->nama ?? 'System' }}
                            </span>
                        </td>
                    </tr>
                </table>

                <h4>{{ $transaksi->pembeli }} - {{ $transaksi->penjualan_kode }}</h4>
                <p>Tanggal: {{ $transaksi->penjualan_tanggal }}</p>

                <table class="table">
                    <thead>
                        <tr>
                            <th>Barang</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transaksi->details as $detail)
                            <tr>
                                <td>{{ $detail->barang->barang_nama ?? '-' }}</td>
                                <td>{{ number_format($detail->harga) }}</td>
                                <td>{{ $detail->jumlah }}</td>
                                <td>{{ number_format($detail->harga * $detail->jumlah) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="alert alert-danger">Data transaksi tidak ditemukan.</div>
            @endisset
        </div>
        
    </div>
</div>
