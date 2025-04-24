@extends('layouts.template')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Data Transaksi Penjualan</h3>
        <div class="card-tools">
            <button onclick="modalAction('{{ url('/transaksi/create_ajax') }}')" class="btn btn-success">
                <i class="fas fa-plus mr-1"></i> Tambah Transaksi
            </button>
        </div>
    </div>

    <div class="card-body">
        <!-- Filter Section -->
        <div class="row mb-3">
            <div class="col-md-3">
                <select class="form-control form-control-sm" id="filter-user">
                    <option value="">- Semua Kasir -</option>
                    <option value="Administrator">Administrator</option>
                    <option value="Manager">Manager</option>
                    <option value="Staff/Kasir">Staff/Kasir</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="date" class="form-control form-control-sm" id="filter-tanggal">
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control form-control-sm" id="filter-pembeli" placeholder="Cari pembeli...">
            </div>
            <div class="col-md-3">
                <button class="btn btn-primary btn-sm" id="btn-filter">Filter</button>
                <button class="btn btn-default btn-sm" id="btn-reset">Reset</button>
            </div>
        </div>

        <!-- Data Table -->
        <table id="datatable-transaksi" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th>Kode Transaksi</th>
                    <th>Pembeli</th>
                    <th>Tanggal</th>
                    <th>Total</th>
                    <th>Kasir</th>
                    <th width="15%">Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div id="myModal" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" aria-hidden="true"></div>
@endsection

@push('css')
<style>
    .table th, .table td {
        vertical-align: middle;
    }
    .badge-kasir {
        font-size: 0.8em;
        padding: 3px 6px;
    }
    .badge-administrator {
        background-color: #6c757d;
        color: white;
    }
    .badge-manager {
        background-color: #17a2b8;
        color: white;
    }
    .badge-staff {
        background-color: #28a745;
        color: white;
    }
    .badge-secondary {
        background-color: #6c757d;
        color: white;
    }
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
        line-height: 1.5;
    }
</style>
@endpush

@push('js')
<script>
    // Fungsi untuk memuat modal
    function modalAction(url = '') {
        $('#myModal').load(url, function () {
            $('#myModal').modal('show');
        });
    }

    // Fungsi untuk menampilkan detail transaksi
    window.showDetail = function(id) {
    $.ajax({
        url: '/transaksi/' + id + '/show_ajax',
        method: 'GET',
        success: function(response) {
            $('#myModal').html(response);
            $('#myModal').modal('show');
        },
        error: function(xhr) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Gagal memuat detail transaksi'
            });
        }
    });
};

// Fungsi untuk menampilkan form edit
function editTransaksi(id) {
    $.ajax({
        url: '/transaksi/' + id + '/edit_ajax',
        method: 'GET',
        success: function(response) {
            $('#myModal').html(response);
            $('#myModal').modal('show');
        },
        error: function(xhr) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Gagal memuat form edit'
            });
        }
    });
}

// Fungsi untuk menghapus transaksi
function deleteTransaksi(id) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data transaksi akan dihapus permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/transaksi/' + id + '/delete_ajax',
                type: 'DELETE',
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (response.status) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message,
                            timer: 1500
                        });
                        dataTransaksi.ajax.reload();
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: xhr.responseJSON.message || 'Terjadi kesalahan'
                    });
                }
            });
        }
    });
}
    // Inisialisasi DataTable
    var dataTransaksi;
    $(document).ready(function () {
        dataTransaksi = $('#datatable-transaksi').DataTable({
            processing: true,
            serverSide: true,
            order: [[0, 'asc']],
            ajax: {
                url: "{{ route('transaksi.list') }}",
                type: "POST",
                data: function(d) {
                    d._token = "{{ csrf_token() }}";
                    d.user = $('#filter-user').val();
                    d.tanggal = $('#filter-tanggal').val();
                    d.pembeli = $('#filter-pembeli').val();
                }
            },
            columns: [
                { 
                    data: 'DT_RowIndex', 
                    orderable: false, 
                    searchable: false,
                    className: 'text-center'
                },
                { 
                    data: 'penjualan_kode',
                    className: 'font-weight-bold'
                },
                { 
                    data: 'pembeli'
                },
                { 
                    data: 'penjualan_tanggal',
                    render: function(data) {
                        return new Date(data).toLocaleDateString('id-ID', {
                            day: '2-digit',
                            month: '2-digit',
                            year: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit'
                        }).replace(/\//g, '-');
                    }
                },
                { 
                    data: 'total',
                    render: function(data) {
                        return 'Rp ' + data.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                    },
                    className: 'text-right'
                },
                { 
                    data: 'user_nama',
                    name: 'user.nama'
                },
                { 
                    data: 'penjualan_id',
                    render: function(data, type, row) {
                        return `
                            <button onclick="showDetail(${data})" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i> Detail
                            </button>
                            <button onclick="editTransaksi(${data})" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button onclick="deleteTransaksi(${data})" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        `;
                    },
                    orderable: false,
                    searchable: false,
                    className: 'text-center'
                }
            ],
            order: [[3, 'asc']],
            language: {
                "lengthMenu": "Tampilkan _MENU_ data per halaman",
                "zeroRecords": "Data tidak ditemukan",
                "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                "infoEmpty": "Data tidak tersedia",
                "infoFiltered": "(difilter dari _MAX_ total data)",
                "search": "Cari:",
                "paginate": {
                    "first": "Pertama",
                    "last": "Terakhir",
                    "next": "Selanjutnya",
                    "previous": "Sebelumnya"
                }
            }
        });

        // Filter
        $('#btn-filter').click(function() {
            dataTransaksi.ajax.reload();
        });

        $('#btn-reset').click(function() {
            $('#filter-user').val('');
            $('#filter-tanggal').val('');
            $('#filter-pembeli').val('');
            dataTransaksi.ajax.reload();
        });

        // Submit filter on enter
        $('#filter-pembeli').keypress(function(e) {
            if (e.which == 13) {
                dataTransaksi.ajax.reload();
            }
        });
    });
</script>
@endpush