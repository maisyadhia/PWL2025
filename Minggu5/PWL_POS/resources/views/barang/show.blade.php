@extends('layouts.app')

@section('content')
<div class="container">
    <h2>{{ $breadcrumb->title }}</h2>
    <table class="table">
        <tr>
            <th>Kode Barang</th>
            <td>{{ $barang->barang_kode }}</td>
        </tr>
        <tr>
            <th>Nama Barang</th>
            <td>{{ $barang->barang_nama }}</td>
        </tr>
        <tr>
            <th>Kategori</th>
            <td>{{ $barang->kategori ? $barang->kategori->kategori_nama : '-' }}</td>
        </tr>
        <tr>
            <th>Harga Beli</th>
            <td>{{ number_format($barang->harga_beli, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Harga Jual</th>
            <td>{{ number_format($barang->harga_jual, 0, ',', '.') }}</td>
        </tr>
    </table>
    <a href="{{ route('barang.index') }}" class="btn btn-secondary">Kembali</a>
</div>
@endsection
