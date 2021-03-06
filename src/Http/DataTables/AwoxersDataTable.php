<?php

namespace Vo1\Seat\AwoxFinder\Http\DataTables;

use Seat\Eveapi\Models\Alliances\Alliance;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Eveapi\Models\Corporation\CorporationInfo;
use Seat\Web\Models\User;
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
            ->editColumn('added_by', function ($row) {
                if ($row->added_by && ($user = User::find($row->added_by))) {
                    return view('web::partials.character', ['character' => $user->main_character])->render();
                }
                return 'NOT FOUND';
            })
            ->editColumn('created_at', function ($row) {
                return view('web::partials.date', [ 'datetime' => $row->created_at ]);
            })
            ->editColumn('updated_at', function ($row) {
                return view('web::partials.date', [ 'datetime' => $row->updated_at ]);
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
            ->rawColumns(['action', 'alliance', 'corporation', 'standing', 'added_by'])
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
            ['data' => 'name', 'title' => trans_choice('web::seat.name', 1), 'orderable' => true, 'filterable' => false],
            ['data' => 'added_by', 'title' => trans_choice('awox::awox.added_by', 1), 'orderable' => true, 'filterable' => true],
            ['data' => 'created_at', 'title' => trans_choice('awox::awox.created_at', 1), 'orderable' => true, 'filterable' => true],
            ['data' => 'updated_at', 'title' => trans_choice('awox::awox.updated_at', 1), 'orderable' => true, 'filterable' => true],
            ['data' => 'reason', 'title' => trans_choice('awox::awox.reason', 1), 'orderable' => true, 'filterable' => true, 'searchable' => true ],
            ['data' => 'affiliation', 'title' => trans_choice('awox::awox.affiliation', 1), 'orderable' => true, 'filterable' => true, 'searchable' => true ],
            ['data' => 'standing', 'title' => trans_choice('awox::awox.standing', 1), 'orderable' => false, 'filterable' => false, 'searchable' => false ],
            ['data' => 'alliance', 'title' => trans_choice('web::seat.alliance', 1), 'orderable' => false, 'filterable' => false, 'searchable' => false],
            ['data' => 'corporation', 'title' => trans_choice('web::seat.corporation', 1), 'orderable' => false, 'filterable' => false, 'searchable' => false],
            ['data' => 'description', 'title' => trans_choice('awox::awox.description', 2), 'orderable' => true, 'filterable' => false],
        ];
    }
}
