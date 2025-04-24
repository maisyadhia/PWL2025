<?php
namespace App\DataTables;

use App\Models\LevelModel;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Column;

class LevelDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables($query)
            ->addColumn('action', 'level.action'); // Anda bisa menambahkan tombol aksi, seperti edit dan delete
    }

    public function query(LevelModel $model)
    {
        return $model->newQuery();
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('level-table')
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
            Column::make('level_code'),
            Column::make('level_nama'),
            Column::make('created_at'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-center'),
        ];
    }

    protected function filename()
    {
        return 'Level_' . date('YmdHis');
    }
}
