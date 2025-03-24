<?php
namespace App\DataTables;

use App\Models\SupplierModel;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Column;

class SupplierDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables($query)
            ->addColumn('action', 'supplier.action'); // Anda bisa menambahkan tombol aksi, seperti edit dan delete
    }

    public function query(SupplierModel $model)
    {
        return $model->newQuery();
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('supplier-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1)
            ->buttons([
                'excel', 'csv', 'pdf', 'print', 'reset', 'reload'
            ]);
    }

    public function getColumns()
{
    return [
        Column::make('supplier_nama'), // Update to 'supplier_nama'
        Column::make('supplier_id'),  // Update to 'supplier_id'
        Column::make('created_at'),
        Column::computed('action')
            ->exportable(false)
            ->printable(false)
            ->addClass('text-center'),
    ];
}


    protected function filename()
    {
        return 'Supplier_' . date('YmdHis');
    }
}