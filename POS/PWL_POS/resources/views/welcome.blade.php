@extends('layouts.template')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-100 via-white to-pink-100 px-6 py-10">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-4xl font-bold text-gray-800 mb-6 text-center">👋 Selamat Datang!</h1>
        <p class="text-center text-gray-500 text-lg mb-12">Silakan pilih menu yang ingin kamu kelola hari ini.</p>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            @php
                $menus = [
                    ['title' => 'Data Supplier', 'desc' => 'Kelola supplier aktif', 'emoji' => '🛒', 'url' => '/supplier', 'color' => 'bg-yellow-100 text-yellow-800'],
                    ['title' => 'Data Pengguna', 'desc' => 'pengguna terdaftar', 'emoji' => '👤', 'url' => '/user', 'color' => 'bg-green-100 text-green-800'],
                    ['title' => 'Level User', 'desc' => 'Kelola hak akses', 'emoji' => '⚙️', 'url' => '/level', 'color' => 'bg-blue-100 text-blue-800'],
                    ['title' => 'Kategori Barang', 'desc' => 'Jenis kategori barang', 'emoji' => '🗂️', 'url' => '/kategori', 'color' => 'bg-pink-100 text-pink-800'],
                    ['title' => 'Data Barang', 'desc' => 'Pantau stok barang', 'emoji' => '📦', 'url' => '/barang', 'color' => 'bg-indigo-100 text-indigo-800'],
                    ['title' => 'Manajemen Stok', 'desc' => 'Keluar/masuk barang', 'emoji' => '📊', 'url' => '/stok', 'color' => 'bg-purple-100 text-purple-800'],
                ];
            @endphp

            @foreach ($menus as $menu)
                <a href="{{ $menu['url'] }}"
                   class="transform hover:scale-105 transition-all duration-200 shadow-md rounded-xl p-6 {{ $menu['color'] }} hover:shadow-xl">
                    <div class="text-5xl mb-4">{{ $menu['emoji'] }}</div>
                    <h2 class="text-2xl font-semibold mb-1">{{ $menu['title'] }}</h2>
                    <p class="text-sm text-gray-600">{{ $menu['desc'] }}</p>
                </a>
            @endforeach
        </div>

        <div class="mt-16 text-center text-gray-400 text-sm">✨ Semangat hari ini, kamu bisa!</div>
    </div>
</div>
@endsection
