
@extends('layouts.template')

@section('content')
<div class="container">
    <h1>Edit Supplier</h1>

    @isset($supplier)
        <form method="POST" action="{{ route('supplier.update', $supplier->supplier_id) }}" class="form-horizontal">
            @csrf
            @method('PUT')
            
            <div class="form-group row">
                <label class="col-2 control-label col-form-label">Nama Supplier</label>
                <div class="col-10">
                    <input type="text" class="form-control" id="nama_supplier" name="nama_supplier" value="{{ old('nama_supplier', $supplier->nama_supplier) }}" required>
                    @error('nama_supplier')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label class="col-2 control-label col-form-label">Kontak</label>
                <div class="col-10">
                    <input type="text" class="form-control" id="supplier_kontak" name="supplier_kontak" value="{{ old('supplier_kontak', $supplier->supplier_kontak) }}" required>
                    @error('supplier_kontak')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label class="col-2 control-label col-form-label">Alamat</label>
                <div class="col-10">
                    <textarea class="form-control" id="alamat" name="alamat" required>{{ old('alamat', $supplier->alamat) }}</textarea>
                    @error('alamat')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label class="col-2 control-label col-form-label"></label>
                <div class="col-10">
                    <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                    <a class="btn btn-sm btn-default ml-1" href="{{ route('supplier.index') }}">Kembali</a>
                </div>
            </div>
        </form>
    @else
        <div class="alert alert-danger">Data Supplier tidak ditemukan.</div>
        <a href="{{ route('supplier.index') }}" class="btn btn-secondary">Kembali</a>
    @endisset
</div>
@endsection
