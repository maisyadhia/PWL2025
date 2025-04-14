<?php
namespace App\DataTables;

use App\Models\ProductModel;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Column;

class ProductDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables($query)
            ->addColumn('action', 'product.action');
    }

    public function query(ProductModel $model)
    {
        return $model->newQuery();
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('product-table')
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
            Column::make('product_code'),
            Column::make('product_name'),
            Column::make('created_at'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-center'),
        ];
    }

    protected function filename()
    {
        return 'Product_' . date('YmdHis');
    }
}