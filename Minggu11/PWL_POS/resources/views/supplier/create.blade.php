@extends('layouts.template')

@section('content')
<div class="container">
    <h1>Tambah Supplier</h1>

    <form action="{{ route('supplier.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="nama_supplier" class="form-label">Nama Supplier</label>
            <input type="text" name="nama_supplier" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="supplier_kontak" class="form-label">supplier_kontak</label>
            <input type="text" name="supplier_kontak" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="alamat" class="form-label">Alamat</label>
            <textarea name="alamat" class="form-control" required></textarea>
        </div>
        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('supplier.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection