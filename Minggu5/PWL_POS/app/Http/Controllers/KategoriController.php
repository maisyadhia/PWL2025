<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\DataTables\KategoriDataTable;

class KategoriController extends Controller{
    public function index(KategoriDataTable $dataTable){
        return $dataTable->render('kategori.index');
        // dd($dataTable->query()->get());
    }
//     public function edit($id)
//     {
//     $kategori = KategoriModel::findOrFail($id);
//     return view('kategori.edit', compact('kategori'));
// }

}
