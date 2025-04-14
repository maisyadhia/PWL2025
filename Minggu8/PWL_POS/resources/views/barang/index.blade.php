@extends('layouts.template')
   
@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('/barang/import') }}')" class="btn btn-info btn-sm mt-1">
                    <i class="fas fa-plus"></i> Import Barang
                </button>                
                {{-- <a class="btn btn-sm btn-primary mt-1" href="{{ url('barang/create') }}">Tambah</a> --}}
                <a href="{{ url('/barang/export_excel') }}" class="btn btn-sm btn-primary mt-1"><i class="fa fa-file- excel"></i> Export Barang</a>
                <a href="{{ url('/barang/export_pdf') }}" class="btn btn-sm btn-warning mt-1"><i class="fa fa-file- pdf"></i> Export PDF Barang</a>
                <button onclick="modalAction('{{ url('barang/create_ajax') }}')" class="btn btn-sm btn-success mt-1">Tambah Ajax</button>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <table class="table-bordered table-striped table-hover table-sm table" id="table_barang">
                <thead>
                    <tr>
                        <th>ID Barang</th>
                        <th>ID Kategori</th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Harga Beli</th>
                        <th>Harga Jual</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" 
        data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true">
    </div>  

    <!-- Modal Detail -->
    <div id="modalDetail" class="modal fade animate fadeIn" tabindex="-1" role="dialog" 
    aria-labelledby="modalDetailLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content" id="modalDetailContent">
        <!-- Konten detail akan dimuat via Ajax -->
    </div>
    </div>
    </div>

@endsection

@push('js')
<script>
    function modalAction(url = ''){
        $('#myModal').load(url, function() {
            $('#myModal').modal('show');
        });
    }

    $(document).ready(function () {
        const tableBarang = $('#table_barang').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ url('barang/list') }}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            },
            columns: [
                { data: "barang_id", className: "text-center", orderable: true },
                { data: "kategori_id", className: "", orderable: true },
                { data: "barang_kode", className: "", orderable: true },
                { data: "barang_nama", className: "", orderable: true },
                { data: "harga_beli", className: "", orderable: true },
                { data: "harga_jual", className: "", orderable: true },
                { data: "aksi", className: "text-center", orderable: false, searchable: false },
            ]
        });

        // Auto reload every 30 seconds
        setInterval(() => {
            tableBarang.ajax.reload(null, false); // false to keep current pagination
        }, 30000);
    });

    function showDetail(id) {
        $.ajax({
            url: '/barang/' + id + '/detail',
            type: 'GET',
            success: function (response) {
                $('#modalDetailContent').html(response);
                $('#modalDetail').modal('show');
            },
            error: function () {
                alert('Gagal menampilkan detail barang.');
            }
        });
    }
</script>
@endpush
