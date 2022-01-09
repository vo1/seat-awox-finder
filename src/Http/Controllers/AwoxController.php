<?php

namespace Vo1\Seat\AwoxFinder\Http\Controllers;

use Illuminate\Http\Request;
use Seat\Eveapi\Models\Alliances\Alliance;
use Seat\Eveapi\Models\Universe\UniverseName;
use Seat\Web\Http\Controllers\Controller;
use Vo1\Seat\AwoxFinder\Http\DataTables\AwoxersDataTable;
use Vo1\Seat\AwoxFinder\Jobs\AwoxFinder;
use Vo1\Seat\AwoxFinder\Models\Awoxer;

class AwoxController extends Controller
{
    /**
     * @param AwoxersDataTable $dataTable
     * @return mixed
     */
    public function list(AwoxersDataTable $dataTable)
    {
        $alliance = Alliance::find(auth()->user()->main_character->affiliation->alliance_id);
        return $dataTable->render('awox::list', compact('alliance'));
    }

    /**
     * @return mixed
     */
    public function formCreate()
    {
        $action = 'create';
        $alliance = auth()->user()->main_character->affiliation->alliance;
        return view('awox::forms.awoxer', compact('alliance', 'action'));
    }

    /**
     * @return mixed
     * @throws \Seat\Services\Exceptions\SettingException
     */
    public function formSettings()
    {
        $discordUrls = setting(AwoxFinder::SETTINGS_DC_URLS, true);
        $discordUrls = implode("\n", $discordUrls);
        return view('awox::forms.settings', compact('discordUrls'));
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \Seat\Services\Exceptions\SettingException
     */
    public function settings(Request $request)
    {
        $urls = $request->input('discord_urls');
        if ($urls) {
            $urls = explode("\n", $urls);
        }
        setting([ AwoxFinder::SETTINGS_DC_URLS, $urls], true);
        return redirect()->route('awox.form.settings')->with('success', trans('awox::awox.settings.updated'));
    }

    /**
     * @param $id
     * @return mixed
     */
    public function formRead($id)
    {
        $action = 'update';
        $row = Awoxer::find($id);
        $alliance = auth()->user()->main_character->affiliation->alliance;
        return view('awox::form', compact('alliance', 'action', 'row'));
    }

    /**
     * @param Request $request
     * @return mixed
     */
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

    /**
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function update(Request $request, $id)
    {
        Awoxer::find($id)->update(['description' => $request->input('description')]);
        return redirect()->route('awox.list')->with('success', trans('awox::awox.entry.updated'));
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        Awoxer::find($id)->delete();
        return redirect()->route('awox.list')->with('success', trans('awox::awox.entry.deleted'));
    }
}
