<?php

namespace Vo1\Seat\AwoxFinder\Jobs\Universe;

use Seat\Eveapi\Jobs\EsiBase;
use Seat\Eveapi\Models\Universe\UniverseName;

class Ids extends EsiBase
{
    /**
     * The maximum number of entity ids we can request resolution for.
     */
    protected $items_limit = 1000;
    /**
     * @var string
     */
    protected $method = 'post';
    /**
     * @var string
     */
    protected $endpoint = '/universe/ids/';
    /**
     * @var string
     */
    protected $version = 'latest';
    /**
     * @var array
     */
    protected $tags = ['public', 'universe'];
    /**
     * @var \Illuminate\Support\Collection
     */
    protected $names = null;

    /**
     * @param array $names
     */
    public function setNames($names)
    {
        $this->names = collect();
        foreach ($names as $name) {
            $this->names->push($name);
        }
    }

    /**
     * Execute the job.
     *
     * @throws \Throwable
     */
    public function handle()
    {
        $result = collect([]);

        $this->names->flatten()->values()->chunk($this->items_limit)->each(function ($chunk) use($result) {
            $this->request_body = collect($chunk->values()->all())->unique()->values()->all();
            $resolutions = json_decode($this->retrieve()->raw);
            collect($resolutions->characters ?? [])->each(function ($resolution) use($result) {
                $result->push($resolution);
            });
        });

        return $result;
    }
}
