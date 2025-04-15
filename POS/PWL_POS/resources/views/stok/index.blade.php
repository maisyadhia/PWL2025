@extends('layouts.template')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Stok Barang</h3>
        <div class="card-tools d-flex flex-wrap gap-2">
            <button onclick="modalAction('{{ url('/stok/import') }}')" class="btn btn-info">
                <i class="fas fa-file-import mr-1"></i> Import Stok
            </button>
            <a href="{{ url('/stok/export_excel') }}" class="btn btn-primary">
                <i class="fas fa-file-excel mr-1"></i> Export Excel
            </a>
            <a href="{{ url('/stok/export_pdf') }}" class="btn btn-danger">
                <i class="fas fa-file-pdf mr-1"></i> Export PDF
            </a>
            <button onclick="modalAction('{{ url('/stok/create_ajax') }}')" class="btn btn-success">
                <i class="fas fa-plus-square mr-1"></i> Tambah Stok (Ajax)
            </button>
        </div>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <table class="table table-bordered table-sm table-striped table-hover" id="table-stok">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Barang</th>
                    <th>Tanggal</th>
                    <th>Jumlah</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<div id="myModal" class="modal fade animate shake" tabindex="-1" data-backdrop="static" data-keyboard="false" data-width="75%"></div>
@endsection

@push('js')
<script>
    function modalAction(url = '') {
        $('#myModal').load(url, function () {
            $('#myModal').modal('show');
        });
    }

    var tableStok;

    $(document).ready(function () {
    $('#table-stok').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('stok.list') }}",
            type: "POST",
            data: function (d) {
                d._token = "{{ csrf_token() }}";
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { 
                data: 'barang_nama', 
                name: 'barang.barang_nama',
                render: function(data, type, row) {
                    return data; // atau format lain yang Anda inginkan
                }
            },
            { data: 'stok_tanggal', name: 'stok_tanggal' },
            { data: 'stok_jumlah', name: 'stok_jumlah' },
            { data: 'aksi', name: 'aksi', orderable: false, searchable: false }
        ],
        order: [[2, 'desc']]
    });
});
</script>
@endpush