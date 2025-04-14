@extends('layouts.template')
   
@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <a class="btn btn-sm btn-primary mt-1" href="{{ url('barang/create') }}">Tambah</a>
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

{{-- 
@extends('layouts.template')

@section('content')
<div class="card">
<div class="card-header">
<h3 class="card-title">Daftar barang</h3>
<div class="card-tools">
<button onclick="modalAction('{{ url('/barang/import') }}')" class="btn btn- info">Import Barang</button>
<a href="{{ url('/barang/create') }}" class="btn btn-primary">Tambah
Data</a>
<button onclick="modalAction('{{ url('/barang/create_ajax') }}')" class="btn btn-success">Tambah Data (Ajax)</button>
</div>
</div>
<div class="card-body">
<!-- untuk Filter data -->
<div id="filter" class="form-horizontal filter-date p-2 border-bottom mb-2">
<div class="row">
<div class="col-md-12">
<div class="form-group form-group-sm row text-sm mb-0">
<label for="filter_date" class="col-md-1 col-form-
label">Filter</label>
<div class="col-md-3">
<select name="filter_kategori" class="form-control form-
control-sm filter_kategori">
<option value="">- Semua -</option> @foreach($kategori as $l)
<option value="{{ $l->kategori_id }}">{{ $l-
>kategori_nama }}</option>
@endforeach
</select>
<small class="form-text text-muted">Kategori Barang</small>
</div>
</div>
</div>
</div>
</div> @if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div> @endif
@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div> @endif
<table class="table table-bordered table-sm table-striped table-hover" id="table-barang">
    <thead>
    <tr><th>No</th><th>Kode Barang</th><th>Kode Barang</th><th>Harga Beli</th><th>Harga Jual</th><th>Kategori</th><th>Aksi</th></tr>
    </thead>
    <tbody></tbody>
    </table>
    </div>
    </div>
    <div id="myModal" class="modal fade animate shake" tabindex="-1" data-backdrop="static" data-keyboard="false" data-width="75%"></div>
    @endsection @push('js')
    <script>
    function modalAction(url = ''){
    $('#myModal').load(url,function(){
    $('#myModal').modal('show');
    });
    }
    var tableBarang;
    $(document).ready(function(){
    tableBarang = $('#table-barang').DataTable({ processing: true,
    serverSide: true, ajax: {
    "url": "{{ url('barang/list') }}", "dataType": "json",
    "type": "POST",
    "data": function (d) {
    d.filter_kategori = $('.filter_kategori').val();
    }
    },
    columns: [{
    data: "No_Urut", className: "text-center", width: "5%",
    orderable: false, searchable: false
    },{
    data: "barang_kode", className: "",
    width: "10%", orderable: true, searchable: true
    },{
    data: "barang_nama", className: "",
    width: "37%", orderable: true, searchable: true,
    },{
    data: "harga_beli", className: "",
    width: "10%", orderable: true, searchable: false,
    render: function(data, type, row){
    return new Intl.NumberFormat('id-ID').format(data);
    }
    },{
    data: "harga_jual", className: "",
    width: "10%",
    orderable: true, searchable: false,
render: function(data, type, row){
return new Intl.NumberFormat('id-ID').format(data);
}
},{
data: "kategori.kategori_nama", className: "",
width: "14%", orderable: true, searchable: false
},{
data: "aksi",
className: "text-center", width: "14%",
orderable: false, searchable: false
}
]
});

$('#table-barang_filter input').unbind().bind().on('keyup', function(e){ if(e.keyCode == 13){ // enter key
tableBarang.search(this.value).draw();
}
});

$('.filter_kategori').change(function(){ tableBarang.draw();
});
});
</script> @endpush --}}
