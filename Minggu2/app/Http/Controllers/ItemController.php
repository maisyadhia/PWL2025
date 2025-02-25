<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * Fungsi ini digunakan untuk menampilkan daftar s?'lkp[-orewq  emua item yang ada di dalam database.
     * Mengambil semua item dari model `Item` dan mengirimkannya ke view `items.index` untuk ditampilkan.
     */
    public function index(){
        $items = Item::all(); // Mengambil semua data item dari database
        return view('items.index', compact('items')); // Mengirim data items ke view
    }

    /**
     * Show the form for creating a new resource.
     *
     * Fungsi ini digunakan untuk menampilkan form pembuatan item baru.
     * Akan menampilkan view `items.create` yang berisi form input data item.
     */
    public function create()
    {
        return view('items.create'); // Mengirimkan view untuk form create item baru
    }

    /**
     * Store a newly created resource in storage.
     *
     * Fungsi ini digunakan untuk menyimpan item baru yang dikirimkan melalui form.
     * Validasi input dilakukan sebelum menyimpan item baru di database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required', // Validasi agar nama tidak kosong
            'description' => 'required', // Validasi agar deskripsi tidak kosong
        ]);

        // Menyimpan data item baru dengan hanya atribut yang diizinkan
        Item::create($request->only(['name', 'description'])); // Hanya atribut yang diizinkan yang disimpan
        return redirect()->route('items.index')->with('success', 'Item added successfully.');
        // Redirect kembali ke daftar item setelah item disimpan, dan memberi pesan sukses
    }

    /**
     * Display the specified resource.
     *
     * Fungsi ini digunakan untuk menampilkan detail dari sebuah item.
     * Menampilkan view `items.show` yang berisi informasi lebih lanjut tentang item yang dipilih.
     */
    public function show(Item $item)
    {
        return view('items.show', compact('item')); // Mengirimkan data item untuk ditampilkan di view show
    }

    /**
     * Show the form for editing the specified resource.
     *
     * Fungsi ini digunakan untuk menampilkan form edit data item yang sudah ada.
     * Akan memuat data item yang akan diubah dan menampilkannya di form edit.
     */
    public function edit(Item $item)
    {
        return view('items.edit',  compact('item')); // Mengirimkan data item untuk ditampilkan di form edit
    }

    /**
     * Update the specified resource in storage.
     *
     * Fungsi ini digunakan untuk mengupdate data item yang sudah ada.
     * Validasi dilakukan sebelum memperbarui data item di database.
     */
    public function update(Request $request, Item $item)
    {
        $request->validate([
            'name' => 'required', // Validasi agar nama tidak kosong
            'description' => 'required', // Validasi agar deskripsi tidak kosong
        ]);

        // Mengupdate data item dengan hanya atribut yang diizinkan
        $item->update($request->only(['name', 'description'])); // Mengupdate data item di database
        return redirect()->route('items.index')->with('success', 'Item updated successfully.');
        // Redirect kembali ke daftar item setelah data diupdate, dan memberi pesan sukses
    }

    /**
     * Remove the specified resource from storage.
     *
     * Fungsi ini digunakan untuk menghapus data item dari database.
     * Item yang dipilih akan dihapus dan redirect ke daftar item.
     */
    public function destroy(Item $item)
    {
        // Menghapus data item
        $item->delete(); // Menghapus item yang dipilih
        return redirect()->route('items.index')->with('success', 'Item deleted successfully.');
        // Redirect kembali ke daftar item setelah item dihapus, dan memberi pesan sukses
    }
}
