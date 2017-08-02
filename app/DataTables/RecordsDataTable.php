<?php

namespace App\DataTables;

use App\Record;
use Illuminate\Database\Query\Builder;
use Yajra\Datatables\Services\DataTable;

class RecordsDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @return \Yajra\Datatables\Engines\BaseEngine
     */
    public function dataTable()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->editColumn('student_id', function ($record) {
                return view('record.datatables.student', compact('record'))->render();
            })
            ->filterColumn('student_id', function ($query, $keyword) {
                /* @var Builder|Record $query */
                $query->whereIn('student_id', function ($query) use ($keyword) {
                    /* @var Builder $query */
                    $query->select('students.id')
                        ->from('students')
                        ->join('records', 'students.id', '=', 'student_id')
                        ->whereRaw('students.name LIKE ?', ['%' . $keyword . '%'])
                        ->orWhereRaw('students.nid LIKE ?', ['%' . $keyword . '%']);
                });
            })
            ->editColumn('club_id', function ($record) {
                return view('record.datatables.club', compact('record'))->render();
            })
            ->filterColumn('club_id', function ($query, $keyword) {
                /* @var Builder|Record $query */
                $query->whereIn('club_id', function ($query) use ($keyword) {
                    /* @var Builder $query */
                    $query->select('clubs.id')
                        ->from('clubs')
                        ->join('records', 'clubs.id', '=', 'club_id')
                        ->whereRaw('clubs.name LIKE ?', ['%' . $keyword . '%']);
                });
            })
            ->escapeColumns([]);
    }

    /**
     * Get the query object to be processed by dataTables.
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|\Illuminate\Support\Collection
     */
    public function query()
    {
        /** @var Record|\Illuminate\Database\Eloquent\Builder $query */
        $query = Record::with('student', 'club.clubType')->select(array_keys($this->getColumns()));

        return $this->applyScopes($query);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\Datatables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax('')
            ->parameters([
                'order'      => [[0, 'desc']],
                'pageLength' => 50,
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            'id'         => ['title' => '#'],
            'student_id' => ['title' => '學生'],
            'club_id'    => ['title' => '社團'],
            'ip'         => ['title' => '打卡IP'],
            'created_at' => ['title' => '打卡時間'],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'records_' . time();
    }
}
