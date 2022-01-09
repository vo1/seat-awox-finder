<?php

namespace Vo1\Seat\AwoxFinder\Commands\Awox;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Seat\Eveapi\Jobs\Character\Affiliation;
use Vo1\Seat\AwoxFinder\Http\DataTables\AwoxersDataTable;
use Vo1\Seat\AwoxFinder\Jobs\AwoxFinder;
use Vo1\Seat\AwoxFinder\Jobs\Universe\Names;
use Vo1\Seat\AwoxFinder\Models\Awoxer;

class Dispatch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'awox:dispatch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatches needed jobs for awox finder.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Names::dispatch();
        Affiliation::dispatch(Awoxer::select('id')->pluck('id')->toArray());
    }
}
