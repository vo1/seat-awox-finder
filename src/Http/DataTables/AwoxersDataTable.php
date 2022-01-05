<?php

namespace Vo1\Seat\AwoxFinder\Http\DataTables;

use Seat\Eveapi\Models\Alliances\Alliance;
use Seat\Eveapi\Models\Corporation\CorporationInfo;
use Seat\Eveapi\Models\Corporation\CorporationStanding;
use Vo1\Seat\AwoxFinder\Models\Awoxer;
use Yajra\DataTables\Services\DataTable;

class AwoxersDataTable extends DataTable
{
    /**
     * @return \Illuminate\Http\JsonResponse|\Yajra\DataTables\DataTableAbstract|\Yajra\DataTables\EloquentDataTable
     */
    public function ajax()
    {
        return datatables()->eloquent($this->query())
            ->editColumn('name', function ($row) {
                return $row->universe_name->name ?? $row->name;
            })
            ->editColumn('standing', function ($row) {

                return 'UNKNOWN';
                dd($row->universe_name->affiliation);
                $alliance = Alliance::find(auth()->user()->main_character->affiliation->alliance_id);
                $corporation = CorporationInfo::find(auth()->user()->main_character->affiliation->corporation_id);
                dd($corporation->contacts);
                $corpId = $row->universe_name->affiliation->corporation_id;
                $allianceId = $row->universe_name->affiliation->alliance_id;
                $currentStanding = -10;
                foreach ($corp->standings as $standing) {
                    if ($standing->from_type === 'faction') {
                        var_dump($standing->from_id);
                    }
                }
                return $row->universe_name->name;
            })
            ->editColumn('alliance', function ($row) {
                $alliance = Alliance::find($row->universe_name->affiliation->alliance_id);
                return view('web::partials.alliance', compact('alliance'))->render();
            })
            ->editColumn('corporation', function ($row) {
                $corporation = CorporationInfo::find($row->universe_name->affiliation->corporation_id);
                return view('web::partials.corporation', compact('corporation'))->render();
            })
            ->editColumn('description', function ($row) {
                return $row->description;
            })
            ->editColumn('action', function ($row) {
                return view('awox::partials.view', compact('row'))->render();
            })
            ->rawColumns(['action', 'alliance', 'corporation'])
            ->make(true);
    }

    /**
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->postAjax()
            ->columns($this->getColumns())
            ->addAction();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        return Awoxer::with('universe_name');
    }

    /**
     * @return array
     */
    public function getColumns()
    {
        return [
            ['data' => 'name', 'title' => trans_choice('web::seat.name', 1), 'orderable' => false, 'filterable' => false],
            ['data' => 'standing', 'title' => trans_choice('awox::awox.standing', 1), 'orderable' => false, 'filterable' => false],
            ['data' => 'alliance', 'title' => trans_choice('web::seat.alliance', 1), 'orderable' => false, 'filterable' => false],
            ['data' => 'corporation', 'title' => trans_choice('web::seat.corporation', 1), 'orderable' => false, 'filterable' => false],
            ['data' => 'description', 'title' => trans_choice('awox::awox.description', 2), 'orderable' => false, 'filterable' => false],
        ];
    }
}
