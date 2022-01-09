<?php

namespace Vo1\Seat\AwoxFinder\Jobs\Universe;

use Seat\Eveapi\Jobs\Universe\Names as CoreNames;
use Seat\Eveapi\Models\Universe\UniverseName;
use Vo1\Seat\AwoxFinder\Models\Awoxer;

class Names extends CoreNames
{
    /**
     * @inheritdoc
     */
    public function handle()
    {
        $this->entity_ids = collect();
        foreach (Awoxer::with('universe_name')->get() as $item) {
            if (isset($item->universe_name)
                && isset($item->universe_name->affiliation)
                && ($item->universe_name->affiliation->corporation_id)) {
                $this->entity_ids->push($item->universe_name->affiliation->corporation_id);
            }
        }

        $this->entity_ids->flatten()->diff($this->existing_entity_ids)->values()->chunk($this->items_id_limit)->each(function ($chunk) {
            $this->request_body = collect($chunk->values()->all())->unique()->values()->all();
            $resolutions = $this->retrieve();

            collect($resolutions)->each(function ($resolution) {
                UniverseName::firstOrNew([
                    'entity_id' => $resolution->id,
                ])->fill([
                    'name'     => $resolution->name,
                    'category' => $resolution->category,
                ])->save();

            });
        });
    }
}
