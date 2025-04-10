@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Daftar Level</h3>
        <div class="card-tools">
            <button class="btn btn-sm btn-primary mt-1" id="btn-add">Tambah Level</button>
            <button onclick="modalAction('{{ url('level/create_ajax') }}')" class="btn btn-sm btn-success mt-1">Tambah AJAX</button>
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
                        <select class="form-control" id="level_id" name="level_id">
                            <option value="">- Semua -</option>
                            @foreach($level as $item)
                                <option value="{{ $item->level_id }}">{{ $item->level_nama }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Level Pengguna</small>
                    </div>
                </div>
            </div>
        </div>

        <table class="table table-bordered table-striped table-hover table-sm" id="table_level">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Level</th>
                    <th>Nama Level</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<!-- Modal Tambah/Edit Manual -->
<div class="modal fade" id="modal-level" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="form-level">
            @csrf
            <input type="hidden" id="level_id_input" name="level_id">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Tambah Level</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="level_kode">Kode Level</label>
                        <input type="text" class="form-control" id="level_kode" name="level_kode" required>
                    </div>
                    <div class="form-group">
                        <label for="level_nama">Nama Level</label>
                        <input type="text" class="form-control" id="level_nama" name="level_nama" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal AJAX -->
<div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" 
    data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true">
</div>
@endsection

@push('js')
<script>
    // Fungsi buka modal AJAX
    function modalAction(url = ''){
        $('#myModal').load(url, function(){
            $('#myModal').modal('show');
        });
    }

    $(document).ready(function() {
        // Inisialisasi DataTable
        var dataLevel = $('#table_level').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ url('level/list') }}",
                type: "POST",
                data: function(d) {
                    d.level_id = $('#level_id').val();
                    d._token = "{{ csrf_token() }}"; // Krusial untuk POST
                }
            },
            columns: [
                { data: "DT_RowIndex", className: "text-center", orderable: false, searchable: false },
                { data: "level_kode", orderable: true, searchable: true },
                { data: "level_nama", orderable: true, searchable: true },
                { data: "aksi", className: "text-center", orderable: false, searchable: false }
            ]
        });

        // Filter data
        $('#level_id').on('change', function() {
            dataLevel.ajax.reload();
        });

        // Buka modal manual
        $('#btn-add').on('click', function() {
            $('#modalLabel').text('Tambah Level');
            $('#form-level')[0].reset();
            $('#level_id_input').val('');
            $('#modal-level').modal('show');
        });

        // Validasi dan kirim form
        $('#form-level').validate({
            rules: {
                level_kode: { required: true, minlength: 2 },
                level_nama: { required: true, minlength: 2 }
            },
            messages: {
                level_kode: { required: "Harus diisi", minlength: "Min 2 karakter" },
                level_nama: { required: "Harus diisi", minlength: "Min 2 karakter" }
            },
            submitHandler: function(form) {
                $.ajax({
                    url: "{{ url('level/store') }}",
                    type: "POST",
                    data: $(form).serialize(),
                    success: function(res) {
                        if (res.status) {
                            $('#modal-level').modal('hide');
                            Swal.fire('Berhasil', res.message, 'success');
                            dataLevel.ajax.reload();
                        } else {
                            Swal.fire('Gagal', res.message, 'error');
                        }
                    },
                    error: function(err) {
                        Swal.fire('Error', 'Terjadi kesalahan.', 'error');
                    }
                });
                return false;
            }
        });

        // OPTIONAL: auto reload setelah tutup modal AJAX
        $('#myModal').off('hidden.bs.modal').on('hidden.bs.modal', function () {
            dataLevel.ajax.reload();
        });

    });
</script>
@endpush
