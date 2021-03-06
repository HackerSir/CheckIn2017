<?php

namespace App\DataTables\Scopes;

use App\Club;
use Yajra\DataTables\Contracts\DataTableScope;

class PaymentRecordClubScope implements DataTableScope
{
    /**
     * @var Club
     */
    private $club;

    /**
     * PaymentRecordClubScope constructor.
     * @param Club $club
     */
    public function __construct(Club $club)
    {
        $this->club = $club;
    }

    /**
     * Apply a query scope.
     *
     * @param \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder $query
     * @return mixed
     */
    public function apply($query)
    {
        return $query->where('club_id', $this->club->id);
    }
}
