@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ $page->title }}</h1>

        <div class="card mt-4">
            <div class="card-header">
                Informasi Barang
            </div>
            <div class="card-body">
                <p><strong>Kode Barang:</strong> {{ $barang->barang_kode }}</p>
                <p><strong>Nama Barang:</strong> {{ $barang->barang_nama }}</p>
                <p><strong>Kategori:</strong> {{ $barang->kategori->kategori_nama ?? '-' }}</p>
                <p><strong>Harga Beli:</strong> Rp{{ number_format($barang->harga_beli, 0, ',', '.') }}</p>
                <p><strong>Harga Jual:</strong> Rp{{ number_format($barang->harga_jual, 0, ',', '.') }}</p>
            </div>
        </div>

        <a href="{{ url('/barang') }}" class="btn btn-secondary mt-3">Kembali</a>
    </div>
@endsection
