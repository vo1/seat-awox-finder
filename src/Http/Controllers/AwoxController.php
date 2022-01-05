<?php

namespace Vo1\Seat\AwoxFinder\Http\Controllers;

use Illuminate\Http\Request;
use Seat\Eveapi\Models\Alliances\Alliance;
use Seat\Eveapi\Models\Corporation\CorporationInfo;
use Seat\Eveapi\Models\Universe\UniverseName;
use Seat\Web\Http\Controllers\Controller;
use Vo1\Seat\AwoxFinder\Http\DataTables\AwoxersDataTable;
use Vo1\Seat\AwoxFinder\Models\Awoxer;

class AwoxController extends Controller
{
    public function list(AwoxersDataTable $dataTable)
    {
        $alliance = Alliance::find(auth()->user()->main_character->affiliation->alliance_id);
        $corporation = CorporationInfo::find(auth()->user()->main_character->affiliation->corporation_id);
//        dd($corporation->contacts);
        return $dataTable->render('awox::list', compact('alliance'));
    }

    public function formCreate()
    {
        $action = 'create';
        $alliance = auth()->user()->main_character->affiliation->alliance;
        return view('awox::form', compact('alliance', 'action'));
    }

    public function formRead($id)
    {
        $action = 'update';
        $row = Awoxer::find($id);
        $alliance = auth()->user()->main_character->affiliation->alliance;
        return view('awox::form', compact('alliance', 'action', 'row'));
    }

    public function create(Request $request)
    {
        Awoxer::create([
            'id' => $request->input('id'),
            'added_by' => auth()->user()->id,
            'name' => $request->input('name'),
            'description' => $request->input('description'),
        ]);

        UniverseName::firstOrNew([
            'entity_id' => $request->input('id'),
        ])->fill([
            'name'     => $request->input('name'),
            'category' => 'character',
        ])->save();

        return redirect()->route('awox.list')->with('success', trans('awox::awox.entry.added'));
    }

    public function update(Request $request, $id)
    {
        Awoxer::find($id)->update(['description' => $request->input('description')]);
        return redirect()->route('awox.list')->with('success', trans('awox::awox.entry.updated'));
    }

    public function delete($id)
    {
        Awoxer::find($id)->delete();
        return redirect()->route('awox.list')->with('success', trans('awox::awox.entry.deleted'));
    }
}
