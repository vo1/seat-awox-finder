<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to 2021 Leon Jacobs
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 */

namespace Vo1\Seat\AwoxFinder\Jobs\Universe;

use Seat\Eveapi\Jobs\EsiBase;
use Seat\Eveapi\Models\Universe\UniverseName;

/**
 * Class Names.
 *
 * @package Seat\Eveapi\Jobs\Universe
 */
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
