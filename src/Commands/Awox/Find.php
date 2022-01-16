<?php

namespace Vo1\Seat\AwoxFinder\Commands\Awox;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Seat\Eveapi\Models\Alliances\Alliance;
use Vo1\Seat\AwoxFinder\Http\DataTables\AwoxersDataTable;
use Vo1\Seat\AwoxFinder\Jobs\AwoxFinder;
use Vo1\Seat\AwoxFinder\Models\Awoxer;

class Find extends Command
{
    const SETTINGS_ALLIANCE_IDS = 'awox.settings.alliance_ids';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'awox:find';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Finds awoxers and warns.';

    /**
     * Execute the console command.
     */
    public function handle(AwoxersDataTable $dt)
    {
        $allianceIds = setting(self::SETTINGS_ALLIANCE_IDS, true) ?? [];
        foreach ($allianceIds as $allianceId) {
            $alliance = Alliance::find($allianceId);
            foreach ($dt->query()->get() as $row) {
                $standing = $dt->getStandingFromContacts($alliance->contacts, $row, $allianceId);
                if ($standing > 0) {
                    AwoxFinder::dispatch($row, $standing);
                }
            }
        }
    }
}
