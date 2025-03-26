@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title ?? 'Daftar Supplier' }}</h3>
            <div class="card-tools">
                <a class="btn btn-sm btn-primary mt-1" href="{{ route('supplier.create') }}">Tambah Supplier</a>
            </div>
        </div>

        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-1 control-label col-form-label">Filter:</label>
                        <div class="col-3">
                            <select class="form-control" id="supplier_id" name="supplier_id">
                                <option value="">- Semua Supplier -</option>
                                @foreach($supplier as $item)
                                    <option value="{{ $item->supplier_id }}">{{ $item->nama_supplier }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Pilih supplier untuk filter</small>
                        </div>
                    </div>
                </div>
            </div>

            <table class="table table-bordered table-striped table-hover table-sm" id="supplierTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Supplier</th>
                        <th>Kontak</th>
                        <th>Alamat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@push('css')
<!-- Tambahkan CSS jika diperlukan -->
@endpush

@push('js')
<script>
    $(document).ready(function() {
        var dataSupplier = $('#supplierTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('supplier.list') }}",
                type: "POST",
                dataType: "json",
                data: function (d) {
                    d.supplier_id = $('#supplier_id').val();
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            },
            columns: [
                { data: "DT_RowIndex", className: "text-center", orderable: false, searchable: false },
                { data: "nama_supplier", orderable: true, searchable: true },
                { data: "supplier_kontak", orderable: true, searchable: true },
                { data: "alamat", orderable: true, searchable: true },
                { data: "aksi", className: "text-center", orderable: false, searchable: false }
            ]
        });

        $('#supplier_id').change(function() {
            dataSupplier.ajax.reload();
        });
    });
</script>
@endpush
