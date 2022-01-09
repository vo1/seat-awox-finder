<?php

namespace Vo1\Seat\AwoxFinder\Http\DataTables;

use Seat\Eveapi\Models\Alliances\Alliance;
use Seat\Eveapi\Models\Corporation\CorporationInfo;
use Vo1\Seat\AwoxFinder\Models\Awoxer;
use Yajra\DataTables\Services\DataTable;

class AwoxersDataTable extends DataTable
{
    /**
     * @param $contacts
     * @param $row
     * @return int
     */
    public function getStandingFromContacts($contacts, $row, $myAllianceId = null)
    {
        $corporationId = $row->universe_name->affiliation->corporation_id;
        $allianceId = $row->universe_name->affiliation->alliance_id;
        $corporation = isset($row->universe_name->affiliation->corporation_id)
            ? CorporationInfo::find($row->universe_name->affiliation->corporation_id)
            : null;
        $alliance = isset($row->universe_name->affiliation->alliance_id)
            ? Alliance::find($row->universe_name->affiliation->alliance_id)
            : null;
        if (!$corporation) {
            return 0;
        }
        $myAllianceId = ($myAllianceId == null ? auth()->user()->main_character->affiliation->alliance_id : $myAllianceId);
        $result = -10;
        if ($allianceId == $myAllianceId) {
            return 10;
        }
        foreach ($contacts as $contact) {
            $contactStanding = ($contact->standing == 0) ? '0.001' : $contact->standing;
            if (($contactStanding > 0) && ($contactStanding > $result)) {
                if (($contact->contact_type == 'corporation') && ($corporationId == $contact->contact_id)) {
                    $result = $contactStanding;
                } elseif ($alliance && ($contact->contact_type == 'alliance') && ($allianceId == $contact->contact_id)) {
                    $result = $contactStanding;
                }
            }
        }
        return $result;
    }

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
                $standing = max(
                    $this->getStandingFromContacts(
                        CorporationInfo::find(auth()->user()->main_character->affiliation->corporation_id)->contacts,
                        $row
                    ),
                    $this->getStandingFromContacts(
                        Alliance::find(auth()->user()->main_character->affiliation->alliance_id)->contacts,
                        $row
                    )
                );
                return view('awox::partials.standing', compact('standing'))->render();
            })
            ->editColumn('alliance', function ($row) {
                if (!isset($row->universe_name->affiliation->alliance_id)) {
                    return 'UNKNOWN';
                }
                $alliance = Alliance::find($row->universe_name->affiliation->alliance_id);
                return view('web::partials.alliance', compact('alliance'))->render();
            })
            ->editColumn('corporation', function ($row) {
                if (!isset($row->universe_name->affiliation->corporation_id)) {
                    return 'UNKNOWN';
                }
                $corporation = CorporationInfo::find($row->universe_name->affiliation->corporation_id);
                if (!$corporation) {
                    return $row->universe_name->affiliation->corporation->name;
                    return 'NOT RECORDED';
                }
                return view('web::partials.corporation', compact('corporation'))->render();
            })
            ->editColumn('description', function ($row) {
                return $row->description;
            })
            ->editColumn('action', function ($row) {
                return view('awox::partials.view', compact('row'))->render();
            })
            ->rawColumns(['action', 'alliance', 'corporation', 'standing'])
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
            ['data' => 'standing', 'title' => trans_choice('awox::awox.standing', 1), 'orderable' => false, 'filterable' => false, 'searchable' => false ],
            ['data' => 'alliance', 'title' => trans_choice('web::seat.alliance', 1), 'orderable' => false, 'filterable' => false, 'searchable' => false],
            ['data' => 'corporation', 'title' => trans_choice('web::seat.corporation', 1), 'orderable' => false, 'filterable' => false, 'searchable' => false],
            ['data' => 'description', 'title' => trans_choice('awox::awox.description', 2), 'orderable' => false, 'filterable' => false],
        ];
    }
}
